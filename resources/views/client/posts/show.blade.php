@extends('client.layouts.app')
@section('title', $post->title)

@section('content')
    <div class="grid lg:grid-cols-12 gap-4">
        <div class="lg:col-span-8">
            <div class="bg-white border rounded shadow overflow-hidden">
                @if ($post->featured_image_path)
                    <img src="{{ $post->featured_image_path }}" class="w-full h-72 object-cover" alt="">
                @endif

                <div class="p-5">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span>{{ optional($post->published_at)->format('d M Y') ?? $post->created_at->format('d M Y') }}</span>
                        @if ($post->category)
                            <span>•</span>
                            <a class="text-teal-700 underline"
                                href="{{ route('client.posts.category', $post->category->slug) }}">
                                {{ $post->category->name }}
                            </a>
                        @endif
                    </div>

                    <h1 class="text-2xl font-bold mt-2 text-slate-800">{{ $post->title }}</h1>

                    @if ($post->excerpt)
                        <div class="mt-3 text-slate-600">{{ $post->excerpt }}</div>
                    @endif

                    <div class="prose max-w-none mt-6">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white border rounded shadow p-4">
                <div class="font-semibold mb-3">More Posts</div>
                <div class="space-y-3">
                    @forelse($related as $r)
                        <a class="block border rounded p-3 hover:bg-slate-50"
                            href="{{ route('client.posts.show', $r->slug) }}">
                            <div class="font-medium text-slate-800">{{ $r->title }}</div>
                            <div class="text-xs text-slate-500 mt-1">
                                {{ optional($r->published_at)->format('d M Y') ?? '' }}
                            </div>
                        </a>
                    @empty
                        <div class="text-sm text-slate-500">No related posts.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border rounded shadow p-4">
                <a class="px-4 py-2 border rounded inline-block hover:bg-slate-50" href="{{ route('client.posts.index') }}">
                    ← Back to News
                </a>
            </div>
        </div>
    </div>
@endsection
