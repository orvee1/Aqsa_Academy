<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('status','published')
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(12);

        return view('client.posts.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug',$slug)->where('status','published')->firstOrFail();
        return view('client.posts.show', compact('post'));
    }

    public function category(string $slug)
    {
        $category = PostCategory::where('slug',$slug)->where('status',true)->firstOrFail();

        $posts = Post::where('status','published')
            ->where('post_category_id',$category->id)
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('client.posts.category', compact('category','posts'));
    }
}
