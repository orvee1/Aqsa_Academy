@extends('client.layouts.app')
@section('title', 'Events')

@section('content')
    <div class="bg-white border rounded shadow p-4">
        <div class="flex items-center justify-between mb-4">
            <div class="font-semibold text-lg">Events</div>
            <div class="flex gap-2 text-sm">
                <a class="px-3 py-1.5 border rounded hover:bg-slate-50 {{ $type === 'upcoming' ? 'bg-slate-100' : '' }}"
                    href="{{ route('client.events.index', ['type' => 'upcoming']) }}">Upcoming</a>
                <a class="px-3 py-1.5 border rounded hover:bg-slate-50 {{ $type === 'past' ? 'bg-slate-100' : '' }}"
                    href="{{ route('client.events.index', ['type' => 'past']) }}">Past</a>
                <a class="px-3 py-1.5 border rounded hover:bg-slate-50 {{ empty($type) ? 'bg-slate-100' : '' }}"
                    href="{{ route('client.events.index') }}">All</a>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($events as $e)
                <a href="{{ route('client.events.show', $e->slug) }}"
                    class="border rounded overflow-hidden hover:bg-slate-50">
                    @if ($e->cover_image_path)
                        <img src="{{ $e->cover_image_path }}" class="w-full h-40 object-cover" alt="">
                    @else
                        <div class="h-40 bg-slate-100 flex items-center justify-center text-slate-500">No Image</div>
                    @endif
                    <div class="p-3">
                        <div class="font-semibold text-slate-800">{{ $e->title }}</div>
                        <div class="text-xs text-slate-500 mt-1">
                            {{ \Carbon\Carbon::parse($e->event_date)->format('d M Y') }}
                            @if ($e->event_time)
                                • {{ \Carbon\Carbon::parse($e->event_time)->format('h:i A') }}
                            @endif
                        </div>
                        @if ($e->venue)
                            <div class="text-xs text-slate-500 mt-1">Venue: {{ $e->venue }}</div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="text-sm text-slate-500">No events found.</div>
            @endforelse
        </div>

        <div class="mt-5">{{ $events->links() }}</div>
    </div>
@endsection
