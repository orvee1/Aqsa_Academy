<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLinkRequest;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index(Request $request)
    {
        $q = SocialLink::query()
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('platform', 'like', $k)->orWhere('url', 'like', $k);
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

        $socials = $q->paginate(15)->appends(request()->query());

        return view('admin.footer.socials.index', compact('socials'));
    }

    public function create()
    {
        return view('admin.footer.socials.create');
    }

    public function store(SocialLinkRequest $request)
    {
        SocialLink::create($request->validated());
        return redirect()->route('admin.social-links.index')->with('success', 'Social link created.');
    }

    public function edit(SocialLink $social_link)
    {
        return view('admin.footer.socials.edit', ['social' => $social_link]);
    }

    public function update(SocialLinkRequest $request, SocialLink $social_link)
    {
        $social_link->update($request->validated());
        return redirect()->route('admin.social-links.index')->with('success', 'Social link updated.');
    }

    public function destroy(SocialLink $social_link)
    {
        $social_link->delete();
        return back()->with('success', 'Social link deleted.');
    }

    public function toggle(SocialLink $social_link)
    {
        $social_link->update(['status' => ! $social_link->status]);
        return back()->with('success', 'Status updated.');
    }

    public function up(SocialLink $social_link)
    {
        $this->swap($social_link, 'up');return back();
    }

    public function down(SocialLink $social_link)
    {
        $this->swap($social_link, 'down');return back();
    }

    private function swap(SocialLink $item, string $dir): void
    {
        $q = SocialLink::query();

        $neighbor = $dir === 'up'
            ? (clone $q)->where('position', '<', $item->position)->orderByDesc('position')->first()
            : (clone $q)->where('position', '>', $item->position)->orderBy('position')->first();

        if (! $neighbor) {
            return;
        }

        [$item->position, $neighbor->position] = [$neighbor->position, $item->position];
        $item->save();
        $neighbor->save();
    }
}
