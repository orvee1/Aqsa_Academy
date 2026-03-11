@extends('client.layouts.app')
@section('title', 'Videos')

@section('content')
    @php
        $img = function ($path) {
            if (!$path) {
                return null;
            }
            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }
            return asset('storage/' . ltrim($path, '/'));
        };
    @endphp

    <div class="bg-white border rounded shadow p-4">
        <div class="flex items-center justify-between mb-4">
            <div class="font-semibold text-lg">ভিডিও গ্যালারী</div>
            <a class="text-sm text-teal-700 underline" href="{{ route('client.home') }}">Home</a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($videos as $v)
                @php
                    $vid = $v->youtubeId();
                    $thumb = $v->thumbnail_path ? $img($v->thumbnail_path) : $v->youtubeThumbUrl();
                    $embed = $vid ? "https://www.youtube.com/embed/{$vid}" : $v->youtube_url;
                @endphp

                <div class="border rounded overflow-hidden">
                    <div class="aspect-video bg-black">
                        <iframe class="w-full h-full" src="{{ $embed }}" title="{{ $v->title }}" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                    <div class="p-3">
                        <div class="font-semibold text-slate-800">{{ $v->title }}</div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-slate-500">No videos found.</div>
            @endforelse
        </div>

        <div class="mt-5">{{ $videos->links() }}</div>
    </div>
@endsection
