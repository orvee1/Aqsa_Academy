@extends('tailwind.layouts.admin')
@section('title', 'Videos')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Video Items</h2>
        <a href="{{ route('admin.video-items.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Video
        </a>
    </div>

    <div class="bg-white rounded shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-72"
                placeholder="Search title/url...">

            <select name="status" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>

            <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
            <a href="{{ route('admin.video-items.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-left">
                    <th class="p-3">Thumb</th>
                    <th class="p-3">Title</th>
                    <th class="p-3">Position</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($videos as $v)
                    <tr>
                        <td class="p-3">
                            <div class="h-10 w-16 rounded overflow-hidden bg-slate-100">
                                @php
                                    $thumb = $v->thumbnail_path
                                        ? asset('storage/' . $v->thumbnail_path)
                                        : $v->youtubeThumbUrl() ?? null;
                                @endphp
                                @if ($thumb)
                                    <img class="h-full w-full object-cover" src="{{ $thumb }}" alt="">
                                @endif
                            </div>
                        </td>

                        <td class="p-3">
                            <div class="font-medium">{{ $v->title }}</div>
                            <div class="text-xs text-gray-500 break-all">{{ $v->youtube_url }}</div>
                        </td>

                        <td class="p-3">{{ $v->position }}</td>

                        <td class="p-3">
                            @if ($v->status)
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Active</span>
                            @else
                                <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">Inactive</span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.video-items.up', $v) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-2 py-1 border rounded text-xs">↑</button>
                                </form>
                                <form method="POST" action="{{ route('admin.video-items.down', $v) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-2 py-1 border rounded text-xs">↓</button>
                                </form>

                                <form method="POST" action="{{ route('admin.video-items.toggle', $v) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1.5 rounded border text-xs">Toggle</button>
                                </form>

                                <a href="{{ route('admin.video-items.edit', $v) }}"
                                    class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs">Edit</a>

                                <form method="POST" action="{{ route('admin.video-items.destroy', $v) }}"
                                    onsubmit="return confirm('Delete video?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 rounded bg-rose-600 text-white text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">No videos found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">{{ $videos->links() }}</div>
    </div>
@endsection
