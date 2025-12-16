<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FooterLinkRequest;
use App\Models\FooterLink;
use Illuminate\Http\Request;

class FooterLinkController extends Controller
{
    public function index(Request $request)
    {
        $q = FooterLink::query()
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('title', 'like', $k)->orWhere('group_title', 'like', $k)->orWhere('url', 'like', $k);
            })
            ->when($request->filled('group'), fn($qq) => $qq->where('group_title', $request->group))
            ->when($request->filled('status'), function ($qq) use ($request) {
                if ($request->status === '1') {
                    $qq->where('status', 1);
                }

                if ($request->status === '0') {
                    $qq->where('status', 0);
                }

            })
            ->orderBy('group_title')
            ->orderBy('position')
            ->orderByDesc('id');

        $links = $q->paginate(15)->appends(request()->query());

        $groups = FooterLink::query()->select('group_title')
            ->whereNotNull('group_title')->distinct()->orderBy('group_title')->pluck('group_title');

        return view('admin.footer.links.index', compact('links', 'groups'));
    }

    public function create()
    {
        return view('admin.footer.links.create');
    }

    public function store(FooterLinkRequest $request)
    {
        FooterLink::create($request->validated());
        return redirect()->route('admin.footer-links.index')->with('success', 'Footer link created.');
    }

    public function edit(FooterLink $footer_link)
    {
        return view('admin.footer.links.edit', ['link' => $footer_link]);
    }

    public function update(FooterLinkRequest $request, FooterLink $footer_link)
    {
        $footer_link->update($request->validated());
        return redirect()->route('admin.footer-links.index')->with('success', 'Footer link updated.');
    }

    public function destroy(FooterLink $footer_link)
    {
        $footer_link->delete();
        return back()->with('success', 'Footer link deleted.');
    }

    public function toggle(FooterLink $footer_link)
    {
        $footer_link->update(['status' => ! $footer_link->status]);
        return back()->with('success', 'Status updated.');
    }

    public function up(FooterLink $footer_link)
    {
        $this->swap($footer_link, 'up');return back();
    }

    public function down(FooterLink $footer_link)
    {
        $this->swap($footer_link, 'down');return back();
    }

    private function swap(FooterLink $item, string $dir): void
    {
        $q = FooterLink::query()->where('group_title', $item->group_title);

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
