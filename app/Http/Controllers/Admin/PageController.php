<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $q = Page::query()
            ->with('creator:id,name')
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('title', 'like', $k)->orWhere('slug', 'like', $k);
            })
            ->when($request->filled('status'), fn($qq) => $qq->where('status', $request->status))
            ->orderByDesc('published_at')
            ->orderByDesc('id');

        $pages = $q->paginate(15)->appends(request()->query());

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(PageRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title']);

        // status published হলে published_at সেট
        if (($data['status'] ?? 'published') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        if (($data['status'] ?? '') === 'draft') {
            $data['published_at'] = $data['published_at'] ?? null;
        }

        $data['created_by'] = auth()->id();

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page)
    {
        $data = $request->validated();

        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?: $data['title'], $page->id);

        if (($data['status'] ?? '') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = $page->published_at ?: now();
        }
        if (($data['status'] ?? '') === 'draft') {
            // draft করলে চাইলে published_at null করে দিতে পারেন
            // $data['published_at'] = null;
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return back()->with('success', 'Page deleted.');
    }

    public function toggleStatus(Page $page)
    {
        $new          = $page->status === 'published' ? 'draft' : 'published';
        $page->status = $new;

        if ($new === 'published' && ! $page->published_at) {
            $page->published_at = now();
        }

        $page->save();

        return back()->with('success', 'Status updated.');
    }

    private function makeUniqueSlug(string $text, ?int $ignoreId = null): string
    {
        $base = Str::slug($text);
        $slug = $base ?: 'page';

        $i = 1;
        while (
            Page::query()
            ->when($ignoreId, fn($qq) => $qq->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
