@extends('tailwind.layouts.admin')
@section('title', 'Footer Links')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Footer Links</h2>
            <div class="text-sm text-gray-500">Group wise links (Quick Links, Important Links...)</div>
        </div>
        <a href="{{ route('admin.footer-links.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Link
        </a>
    </div>

    <div class="bg-white rounded shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-64" placeholder="Search...">

            <select name="group" class="border rounded px-3 py-2">
                <option value="">All Groups</option>
                @foreach ($groups as $g)
                    <option value="{{ $g }}" @selected(request('group') === $g)>{{ $g }}</option>
                @endforeach
            </select>

            <select name="status" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>

            <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
            <a href="{{ route('admin.footer-links.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-left">
                    <th class="p-3">Group</th>
                    <th class="p-3">Title</th>
                    <th class="p-3">URL</th>
                    <th class="p-3">Pos</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($links as $l)
                    <tr>
                        <td class="p-3">{{ $l->group_title ?? '—' }}</td>
                        <td class="p-3 font-medium">{{ $l->title }}</td>
                        <td class="p-3">
                            <a class="text-indigo-600 underline break-all" target="_blank"
                                href="{{ $l->url }}">{{ $l->url }}</a>
                        </td>
                        <td class="p-3">{{ $l->position }}</td>
                        <td class="p-3">
                            @if ($l->status)
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Active</span>
                            @else
                                <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">Inactive</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.footer-links.up', $l) }}">@csrf
                                    @method('PATCH')
                                    <button class="px-2 py-1 border rounded text-xs">↑</button>
                                </form>
                                <form method="POST" action="{{ route('admin.footer-links.down', $l) }}">@csrf
                                    @method('PATCH')
                                    <button class="px-2 py-1 border rounded text-xs">↓</button>
                                </form>

                                <form method="POST" action="{{ route('admin.footer-links.toggle', $l) }}">@csrf
                                    @method('PATCH')
                                    <button class="px-3 py-1.5 border rounded text-xs">Toggle</button>
                                </form>

                                <a href="{{ route('admin.footer-links.edit', $l) }}"
                                    class="px-3 py-1.5 bg-indigo-600 text-white rounded text-xs">Edit</a>

                                <form method="POST" action="{{ route('admin.footer-links.destroy', $l) }}"
                                    onsubmit="return confirm('Delete link?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 bg-rose-600 text-white rounded text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">No links found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">{{ $links->links() }}</div>
    </div>
@endsection
