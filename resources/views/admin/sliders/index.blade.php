@extends('tailwind.layouts.admin')
@section('title', 'Sliders')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Sliders</h2>
        <a href="{{ route('admin.sliders.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Slider
        </a>
    </div>

    <div class="bg-white rounded shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-72"
                placeholder="Search title/subtitle/link...">

            <select name="status" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>

            <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
            <a href="{{ route('admin.sliders.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-left">
                    <th class="p-3">Image</th>
                    <th class="p-3">Text</th>
                    <th class="p-3">Window</th>
                    <th class="p-3">Position</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($sliders as $s)
                    <tr>
                        <td class="p-3">
                            <div class="h-10 w-16 rounded overflow-hidden bg-slate-100">
                                <img class="h-full w-full object-cover" src="{{ asset('storage/' . $s->image_path) }}"
                                    alt="">
                            </div>
                        </td>

                        <td class="p-3">
                            <div class="font-medium">{{ $s->title ?? '—' }}</div>
                            <div class="text-xs text-gray-500">{{ $s->subtitle ?? '' }}</div>
                            @if ($s->link_url)
                                <div class="text-xs mt-1">
                                    <a target="_blank" class="underline text-indigo-600"
                                        href="{{ $s->link_url }}">{{ $s->link_url }}</a>
                                </div>
                            @endif
                        </td>

                        <td class="p-3 text-xs text-gray-600">
                            <div>Start: {{ $s->start_at?->format('d M Y, h:i A') ?? '—' }}</div>
                            <div>End: {{ $s->end_at?->format('d M Y, h:i A') ?? '—' }}</div>
                        </td>

                        <td class="p-3">{{ $s->position }}</td>

                        <td class="p-3">
                            @if ($s->status)
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Active</span>
                            @else
                                <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">Inactive</span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.sliders.up', $s) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-2 py-1 border rounded text-xs">↑</button>
                                </form>
                                <form method="POST" action="{{ route('admin.sliders.down', $s) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-2 py-1 border rounded text-xs">↓</button>
                                </form>

                                <form method="POST" action="{{ route('admin.sliders.toggle', $s) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1.5 rounded border text-xs">Toggle</button>
                                </form>

                                <a href="{{ route('admin.sliders.edit', $s) }}"
                                    class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs">Edit</a>

                                <form method="POST" action="{{ route('admin.sliders.destroy', $s) }}"
                                    onsubmit="return confirm('Delete slider?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 rounded bg-rose-600 text-white text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">No sliders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">{{ $sliders->links() }}</div>
    </div>
@endsection
