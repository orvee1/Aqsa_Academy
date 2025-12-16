<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $q = Post::query()
            ->with(['category:id,name', 'creator:id,name'])
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('title', 'like', $k)->orWhere('slug', 'like', $k);
            })
            ->when($request->filled('post_category_id'), fn($qq) => $qq->where('post_category_id', $request->integer('post_category_id')))
            ->when($request->filled('status'), fn($qq) => $qq->where('status', $request->status))
            ->orderByDesc('published_at')
            ->orderByDesc('id');

        $posts      = $q->paginate(15)->appends(request()->query());
        $categories = PostCategory::active()->orderBy('name')->get(['id', 'name']);

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = PostCategory::active()->orderBy('name')->get(['id', 'name']);
        return view('admin.posts.create', compact('categories'));
    }

    public function store(PostRequest $request)
    {
        $data         = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title']);

        if ($request->hasFile('featured_image')) {
            $data['featured_image_path'] = $request->file('featured_image')->store('posts/featured', 'public');
        }

        if (($data['status'] ?? 'published') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $data['created_by'] = auth()->id();

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Post created.');
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::active()->orderBy('name')->get(['id', 'name']);
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $data         = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $post->id);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image_path) {
                Storage::disk('public')->delete($post->featured_image_path);
            }

            $data['featured_image_path'] = $request->file('featured_image')->store('posts/featured', 'public');
        }

        if (($data['status'] ?? '') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = $post->published_at ?: now();
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        if ($post->featured_image_path) {
            Storage::disk('public')->delete($post->featured_image_path);
        }

        $post->delete();
        return back()->with('success', 'Post deleted.');
    }

    public function toggleStatus(Post $post)
    {
        $post->status = $post->status === 'published' ? 'draft' : 'published';
        if ($post->status === 'published' && ! $post->published_at) {
            $post->published_at = now();
        }

        $post->save();

        return back()->with('success', 'Post status updated.');
    }

    private function uniqueSlug(string $text, ?int $ignoreId = null): string
    {
        $base = Str::slug($text) ?: 'post';
        $slug = $base;
        $i    = 1;

        while (Post::query()
            ->when($ignoreId, fn($qq) => $qq->where('id', '!=', $ignoreId))
            ->where('slug', $slug)->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
