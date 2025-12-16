@extends('tailwind.layouts.admin')
@section('title', 'Events')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Events</h2>
        <a href="{{ route('admin.events.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Event
        </a>
    </div>

    <div class="bg-white rounded shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-64"
                placeholder="Search title/slug/venue...">

            <select name="status" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded px-3 py-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded px-3 py-2">

            <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
            <a href="{{ route('admin.events.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-left">
                    <th class="p-3">Event</th>
                    <th class="p-3">Date/Time</th>
                    <th class="p-3">Venue</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($events as $e)
                    <tr>
                        <td class="p-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-12 rounded overflow-hidden bg-slate-100 shrink-0">
                                    @if ($e->cover_image_path)
                                        <img class="h-full w-full object-cover"
                                            src="{{ asset('storage/' . $e->cover_image_path) }}" alt="">
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium">{{ $e->title }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $e->slug }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="p-3 text-gray-700">
                            <div class="text-sm">{{ $e->event_date?->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $e->event_time ? \Carbon\Carbon::createFromFormat('H:i', $e->event_time)->format('h:i A') : '—' }}
                            </div>
                        </td>

                        <td class="p-3">{{ $e->venue ?? '—' }}</td>

                        <td class="p-3">
                            @if ($e->status === 'published')
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Published</span>
                            @else
                                <span class="px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs">Draft</span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.events.toggle-status', $e) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1.5 rounded border text-xs">Toggle</button>
                                </form>

                                <a href="{{ route('admin.events.edit', $e) }}"
                                    class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs">Edit</a>

                                <form method="POST" action="{{ route('admin.events.destroy', $e) }}"
                                    onsubmit="return confirm('Delete event?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 rounded bg-rose-600 text-white text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">{{ $events->links() }}</div>
    </div>
@endsection
