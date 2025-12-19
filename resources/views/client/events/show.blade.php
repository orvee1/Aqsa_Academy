@extends('client.layouts.app')
@section('title', $event->title)

@section('content')
    <div class="bg-white border rounded shadow overflow-hidden">
        @if ($event->cover_image_path)
            <img src="{{ $event->cover_image_path }}" class="w-full h-72 object-cover" alt="">
        @endif

        <div class="p-5">
            <div class="text-xs text-slate-500">
                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                @if ($event->event_time)
                    • {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                @endif
                @if ($event->venue)
                    • {{ $event->venue }}
                @endif
            </div>

            <h1 class="text-2xl font-bold mt-2 text-slate-800">{{ $event->title }}</h1>

            @if ($event->description)
                <div class="prose max-w-none mt-5">
                    {!! $event->description !!}
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('client.events.index') }}" class="px-4 py-2 border rounded hover:bg-slate-50">← Back to
                    Events</a>
            </div>
        </div>
    </div>
@endsection
