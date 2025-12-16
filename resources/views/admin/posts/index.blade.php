@extends('tailwind.layouts.admin')
@section('title', 'পোস্ট তৈরি করুন')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Posts</h2>
        <a href="{{ route('admin.posts.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Post
        </a>
    </div>

    <div class="bg-white rounded shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-64"
                placeholder="Search title/slug...">

            <select name="post_category_id" class="border rounded px-3 py-2">
                <option value="">All Categories</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" @selected(request('post_category_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>

            <select name="status" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </select>

            <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
            <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-left">
                    <th class="p-3">Title</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Published</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($posts as $p)
                    <tr>
                        <td class="p-3">
                            <div class="font-medium">{{ $p->title }}</div>
                            <div class="text-xs text-gray-500 font-mono">{{ $p->slug }}</div>
                        </td>
                        <td class="p-3">{{ $p->category?->name ?? '-' }}</td>
                        <td class="p-3">
                            @if ($p->status === 'published')
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Published</span>
                            @else
                                <span class="px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs">Draft</span>
                            @endif
                        </td>
                        <td class="p-3 text-xs text-gray-600">
                            {{ $p->published_at?->format('d M Y, h:i A') ?? '—' }}
                        </td>
                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.posts.toggle-status', $p) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1.5 rounded border text-xs">Toggle</button>
                                </form>

                                <a href="{{ route('admin.posts.edit', $p) }}"
                                    class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs">Edit</a>

                                <form method="POST" action="{{ route('admin.posts.destroy', $p) }}"
                                    onsubmit="return confirm('Delete post?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 rounded bg-rose-600 text-white text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">No posts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">{{ $posts->links() }}</div>
    </div>
@endsection
