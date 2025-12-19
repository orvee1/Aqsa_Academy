@extends('client.layouts.app')
@section('title', 'Gallery')

@section('content')
    <div class="bg-white border rounded shadow p-4">
        <div class="flex items-center justify-between mb-4">
            <div class="font-semibold text-lg">ইমেজ গ্যালারী</div>
            <a class="text-sm text-teal-700 underline" href="{{ route('client.home') }}">Home</a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($albums as $a)
                @php
                    $cover = $covers[$a->id] ?? null;
                    $count = $counts[$a->id] ?? 0;
                @endphp
                <a href="{{ route('client.gallery.album', $a->id) }}" class="border rounded overflow-hidden hover:bg-slate-50">
                    @if ($cover)
                        <img src="{{ $cover }}" class="w-full h-44 object-cover" alt="">
                    @else
                        <div class="h-44 bg-slate-100 flex items-center justify-center text-slate-500">No Image</div>
                    @endif
                    <div class="p-3">
                        <div class="font-semibold text-slate-800">{{ $a->title }}</div>
                        <div class="text-xs text-slate-500 mt-1">{{ $count }} photos</div>
                    </div>
                </a>
            @empty
                <div class="text-sm text-slate-500">No albums found.</div>
            @endforelse
        </div>

        <div class="mt-5">{{ $albums->links() }}</div>
    </div>
@endsection
