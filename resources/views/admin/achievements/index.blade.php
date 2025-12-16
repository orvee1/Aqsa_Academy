@extends('tailwind.layouts.admin')
@section('title', 'Achievements')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Achievements</h2>
        <a href="{{ route('admin.achievements.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Achievement
        </a>
    </div>

    <div class="bg-white rounded shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-72"
                placeholder="Search title/year...">

            <select name="status" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>

            <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
            <a href="{{ route('admin.achievements.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-left">
                    <th class="p-3">Image</th>
                    <th class="p-3">Title</th>
                    <th class="p-3">Year</th>
                    <th class="p-3">Position</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($achievements as $a)
                    <tr>
                        <td class="p-3">
                            <div class="h-10 w-14 rounded overflow-hidden bg-slate-100">
                                @if ($a->image_path)
                                    <img class="h-full w-full object-cover" src="{{ asset('storage/' . $a->image_path) }}"
                                        alt="">
                                @endif
                            </div>
                        </td>

                        <td class="p-3">
                            <div class="font-medium">{{ $a->title }}</div>
                            @if ($a->description)
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ \Illuminate\Support\Str::limit($a->description, 90) }}
                                </div>
                            @endif
                        </td>

                        <td class="p-3">{{ $a->year ?? '—' }}</td>
                        <td class="p-3">{{ $a->position }}</td>

                        <td class="p-3">
                            @if ($a->status)
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Active</span>
                            @else
                                <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">Inactive</span>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.achievements.up', $a) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-2 py-1 border rounded text-xs">↑</button>
                                </form>
                                <form method="POST" action="{{ route('admin.achievements.down', $a) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-2 py-1 border rounded text-xs">↓</button>
                                </form>

                                <form method="POST" action="{{ route('admin.achievements.toggle', $a) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1.5 rounded border text-xs">Toggle</button>
                                </form>

                                <a href="{{ route('admin.achievements.edit', $a) }}"
                                    class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs">Edit</a>

                                <form method="POST" action="{{ route('admin.achievements.destroy', $a) }}"
                                    onsubmit="return confirm('Delete achievement?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 rounded bg-rose-600 text-white text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">No achievements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">{{ $achievements->links() }}</div>
    </div>
@endsection
