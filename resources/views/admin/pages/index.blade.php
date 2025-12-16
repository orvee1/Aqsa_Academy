@extends('tailwind.layouts.admin')
@section('title', 'পেজ তৈরি করুন')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Pages</h2>
        <a href="{{ route('admin.pages.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Page
        </a>
    </div>

    <div class="bg-white rounded shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-72"
                placeholder="Search title/slug...">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </select>
            <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
            <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-left">
                    <th class="p-3">Title</th>
                    <th class="p-3">Slug</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Published</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($pages as $p)
                    <tr>
                        <td class="p-3 font-medium">{{ $p->title }}</td>
                        <td class="p-3 font-mono text-xs text-gray-600">{{ $p->slug }}</td>
                        <td class="p-3">
                            @if ($p->status === 'published')
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Published</span>
                            @else
                                <span class="px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs">Draft</span>
                            @endif
                        </td>
                        <td class="p-3 text-gray-600 text-xs">
                            {{ $p->published_at?->format('d M Y, h:i A') ?? '—' }}
                        </td>
                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.pages.toggle-status', $p) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1.5 rounded border text-xs">Toggle</button>
                                </form>

                                <a href="{{ route('admin.pages.edit', $p) }}"
                                    class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.pages.destroy', $p) }}"
                                    onsubmit="return confirm('Delete this page?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 rounded bg-rose-600 text-white text-xs">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">No pages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">{{ $pages->links() }}</div>
    </div>
@endsection
