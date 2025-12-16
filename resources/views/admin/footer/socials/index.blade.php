@extends('tailwind.layouts.admin')
@section('title', 'Social Links')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Social Links</h2>
            <div class="text-sm text-gray-500">Facebook / YouTube / Twitter / Instagram ...</div>
        </div>
        <a href="{{ route('admin.social-links.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Social
        </a>
    </div>

    <div class="bg-white rounded shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-72"
                placeholder="Search platform/url...">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>
            <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
            <a href="{{ route('admin.social-links.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-left">
                    <th class="p-3">Platform</th>
                    <th class="p-3">URL</th>
                    <th class="p-3">Pos</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($socials as $s)
                    <tr>
                        <td class="p-3 font-medium">{{ $s->platform }}</td>
                        <td class="p-3">
                            <a class="text-indigo-600 underline break-all" target="_blank"
                                href="{{ $s->url }}">{{ $s->url }}</a>
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
                                <form method="POST" action="{{ route('admin.social-links.up', $s) }}">@csrf
                                    @method('PATCH')
                                    <button class="px-2 py-1 border rounded text-xs">↑</button>
                                </form>
                                <form method="POST" action="{{ route('admin.social-links.down', $s) }}">@csrf
                                    @method('PATCH')
                                    <button class="px-2 py-1 border rounded text-xs">↓</button>
                                </form>

                                <form method="POST" action="{{ route('admin.social-links.toggle', $s) }}">@csrf
                                    @method('PATCH')
                                    <button class="px-3 py-1.5 border rounded text-xs">Toggle</button>
                                </form>

                                <a href="{{ route('admin.social-links.edit', $s) }}"
                                    class="px-3 py-1.5 bg-indigo-600 text-white rounded text-xs">Edit</a>

                                <form method="POST" action="{{ route('admin.social-links.destroy', $s) }}"
                                    onsubmit="return confirm('Delete social link?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 bg-rose-600 text-white rounded text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">No social links found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">{{ $socials->links() }}</div>
    </div>
@endsection
