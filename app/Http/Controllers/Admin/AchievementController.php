<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AchievementRequest;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $q = Achievement::query()
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('title', 'like', $k)
                    ->orWhere('year', 'like', $k);
            })
            ->when($request->filled('status'), function ($qq) use ($request) {
                if ($request->status === '1') {
                    $qq->where('status', 1);
                }

                if ($request->status === '0') {
                    $qq->where('status', 0);
                }

            })
            ->orderBy('position')
            ->orderByDesc('id');

        $achievements = $q->paginate(15)->appends(request()->query());

        return view('admin.achievements.index', compact('achievements'));
    }

    public function create()
    {
        return view('admin.achievements.create');
    }

    public function store(AchievementRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('achievements', 'public');
        }

        Achievement::create($data);

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement created.');
    }

    public function edit(Achievement $achievement)
    {
        return view('admin.achievements.edit', compact('achievement'));
    }

    public function update(AchievementRequest $request, Achievement $achievement)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($achievement->image_path) {
                Storage::disk('public')->delete($achievement->image_path);
            }

            $data['image_path'] = $request->file('image')->store('achievements', 'public');
        }

        $achievement->update($data);

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement updated.');
    }

    public function destroy(Achievement $achievement)
    {
        if ($achievement->image_path) {
            Storage::disk('public')->delete($achievement->image_path);
        }

        $achievement->delete();

        return back()->with('success', 'Achievement deleted.');
    }

    public function toggle(Achievement $achievement)
    {
        $achievement->update(['status' => ! $achievement->status]);
        return back()->with('success', 'Status updated.');
    }

    public function up(Achievement $achievement)
    {
        $this->swap($achievement, 'up');
        return back();
    }

    public function down(Achievement $achievement)
    {
        $this->swap($achievement, 'down');
        return back();
    }

    private function swap(Achievement $a, string $dir): void
    {
        $base = Achievement::query();

        if ($dir === 'up') {
            $neighbor = (clone $base)
                ->where('position', '<', $a->position)
                ->orderByDesc('position')
                ->first();
        } else {
            $neighbor = (clone $base)
                ->where('position', '>', $a->position)
                ->orderBy('position')
                ->first();
        }

        if (! $neighbor) {
            return;
        }

        $tmp                = $a->position;
        $a->position        = $neighbor->position;
        $neighbor->position = $tmp;

        $a->save();
        $neighbor->save();
    }
}
