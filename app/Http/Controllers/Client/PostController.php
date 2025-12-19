<?php
namespace App\Http\Controllers\Client;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostController extends BaseClientController
{
    public function index()
    {
        $posts = Post::with('category:id,name,slug')
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(12)
            ->appends(request()->query());

        return $this->view('client.posts.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::with('category:id,name,slug')
            ->where('slug', $slug)->where('status', 'published')
            ->firstOrFail();

        $related = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->when($post->post_category_id, fn($q) => $q->where('post_category_id', $post->post_category_id))
            ->latest('id')->take(6)->get(['id', 'title', 'slug', 'featured_image_path', 'published_at']);

        return $this->view('client.posts.show', compact('post', 'related'));
    }

    public function category(string $slug)
    {
        $category = PostCategory::where('slug', $slug)->where('status', true)->firstOrFail();

        $posts = Post::with('category:id,name,slug')
            ->where('status', 'published')
            ->where('post_category_id', $category->id)
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(12)
            ->appends(request()->query());

        return $this->view('client.posts.category', compact('category', 'posts'));
    }
}
