@extends('client.layouts.app')
@section('title', 'Videos')

@section('content')
    <div class="bg-white border rounded shadow p-4">
        <div class="flex items-center justify-between mb-4">
            <div class="font-semibold text-lg">ভিডিও গ্যালারী</div>
            <a class="text-sm text-teal-700 underline" href="{{ route('client.home') }}">Home</a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($videos as $v)
                @php
                    $u = $v->youtube_url;
                    $vid = null;
                    if (str_contains($u, 'embed/')) {
                        $vid = \Illuminate\Support\Str::after($u, 'embed/');
                    } elseif (str_contains($u, 'youtu.be/')) {
                        $vid = \Illuminate\Support\Str::after($u, 'youtu.be/');
                    } elseif (str_contains($u, 'v=')) {
                        $vid = \Illuminate\Support\Str::after($u, 'v=');
                    }
                    $vid = $vid ? strtok($vid, '&?') : null;
                    $embed = $vid ? "https://www.youtube.com/embed/{$vid}" : $u;
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
