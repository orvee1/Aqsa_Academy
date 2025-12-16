@extends('tailwind.layouts.admin')
@section('title', 'Image Albums')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Image Albums</h2>
        <a href="{{ route('admin.image-albums.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Album
        </a>
    </div>

    <div class="bg-white rounded shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-72"
                placeholder="Search album...">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>
            <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
            <a href="{{ route('admin.image-albums.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-left">
                    <th class="p-3">Title</th>
                    <th class="p-3">Images</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($albums as $a)
                    <tr>
                        <td class="p-3 font-medium">{{ $a->title }}</td>
                        <td class="p-3">{{ $a->items_count }}</td>
                        <td class="p-3">
                            @if ($a->status)
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Active</span>
                            @else
                                <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">Inactive</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                <a class="px-3 py-1.5 rounded bg-slate-800 text-white text-xs"
                                    href="{{ route('admin.image-albums.items', $a->id) }}">Manage Images</a>

                                <form method="POST" action="{{ route('admin.image-albums.toggle', $a) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1.5 rounded border text-xs">Toggle</button>
                                </form>

                                <a class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs"
                                    href="{{ route('admin.image-albums.edit', $a) }}">Edit</a>

                                <form method="POST" action="{{ route('admin.image-albums.destroy', $a) }}"
                                    onsubmit="return confirm('Delete album?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 rounded bg-rose-600 text-white text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500">No albums found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">{{ $albums->links() }}</div>
    </div>
@endsection
