<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $q = Slider::query()
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('title', 'like', $k)
                    ->orWhere('subtitle', 'like', $k)
                    ->orWhere('link_url', 'like', $k);
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

        $sliders = $q->paginate(15)->appends(request()->query());

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(SliderRequest $request)
    {
        $data = $request->validated();

        // required image on create
        $data['image_path'] = $request->file('image')->store('sliders', 'public');

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(SliderRequest $request, Slider $slider)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }

            $data['image_path'] = $request->file('image')->store('sliders', 'public');
        } else {
            // image not uploaded -> keep old
            unset($data['image_path']);
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated.');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image_path) {
            Storage::disk('public')->delete($slider->image_path);
        }

        $slider->delete();

        return back()->with('success', 'Slider deleted.');
    }

    public function toggle(Slider $slider)
    {
        $slider->update(['status' => ! $slider->status]);
        return back()->with('success', 'Status updated.');
    }

    public function up(Slider $slider)
    {
        $this->swap($slider, 'up');
        return back();
    }

    public function down(Slider $slider)
    {
        $this->swap($slider, 'down');
        return back();
    }

    private function swap(Slider $s, string $dir): void
    {
        $q = Slider::query();

        if ($dir === 'up') {
            $neighbor = (clone $q)->where('position', '<', $s->position)->orderByDesc('position')->first();
        } else {
            $neighbor = (clone $q)->where('position', '>', $s->position)->orderBy('position')->first();
        }

        if (! $neighbor) {
            return;
        }

        $tmp                = $s->position;
        $s->position        = $neighbor->position;
        $neighbor->position = $tmp;

        $s->save();
        $neighbor->save();
    }
}
