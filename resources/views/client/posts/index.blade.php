@extends('client.layouts.app')
@section('title', 'News')

@section('content')
    <div class="bg-white border rounded shadow p-4">
        <div class="flex items-center justify-between mb-4">
            <div class="font-semibold text-lg">News / Posts</div>
            <a class="text-sm text-teal-700 underline" href="{{ route('client.home') }}">Home</a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($posts as $p)
                <a href="{{ route('client.posts.show', $p->slug) }}" class="border rounded overflow-hidden hover:bg-slate-50">
                    @if ($p->featured_image_path)
                        <img src="{{ $p->featured_image_path }}" class="w-full h-40 object-cover" alt="">
                    @else
                        <div class="h-40 bg-slate-100 flex items-center justify-center text-slate-500">No Image</div>
                    @endif

                    <div class="p-3">
                        @if ($p->category)
                            <div
                                class="text-xs inline-flex px-2 py-1 rounded bg-teal-50 text-teal-700 border border-teal-100">
                                {{ $p->category->name }}
                            </div>
                        @endif
                        <div class="mt-2 font-semibold text-slate-800">{{ $p->title }}</div>
                        @if ($p->excerpt)
                            <div class="text-sm text-slate-600 mt-1 line-clamp-3">
                                {{ $p->excerpt }}
                            </div>
                        @endif
                        <div class="text-xs text-slate-500 mt-2">
                            {{ optional($p->published_at)->format('d M Y') ?? $p->created_at->format('d M Y') }}
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-sm text-slate-500">No posts found.</div>
            @endforelse
        </div>

        <div class="mt-5">{{ $posts->links() }}</div>
    </div>
@endsection
