<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCategoryRequest;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = PostCategory::query()
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('name', 'like', $k)->orWhere('slug', 'like', $k);
            })
            ->when($request->filled('status'), function ($qq) use ($request) {
                if ($request->status === '1') {
                    $qq->where('status', 1);
                }

                if ($request->status === '0') {
                    $qq->where('status', 0);
                }

            })
            ->latest('id');

        $categories = $q->paginate(15)->appends(request()->query());

        return view('admin.post_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.post_categories.create');
    }

    public function store(PostCategoryRequest $request)
    {
        $data         = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name']);

        PostCategory::create($data);

        return redirect()->route('admin.post-categories.index')->with('success', 'Category created.');
    }

    public function edit(PostCategory $post_category)
    {
        return view('admin.post_categories.edit', ['category' => $post_category]);
    }

    public function update(PostCategoryRequest $request, PostCategory $post_category)
    {
        $data         = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $post_category->id);

        $post_category->update($data);

        return redirect()->route('admin.post-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(PostCategory $post_category)
    {
        $post_category->delete();
        return back()->with('success', 'Category deleted.');
    }

    public function toggle(PostCategory $post_category)
    {
        $post_category->update(['status' => ! $post_category->status]);
        return back()->with('success', 'Category status updated.');
    }

    private function uniqueSlug(string $text, ?int $ignoreId = null): string
    {
        $base = Str::slug($text) ?: 'category';
        $slug = $base;
        $i    = 1;

        while (PostCategory::query()
            ->when($ignoreId, fn($qq) => $qq->where('id', '!=', $ignoreId))
            ->where('slug', $slug)->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
