@extends('tailwind.layouts.admin')
@section('title','Permissions')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Permissions</h2>
    <a href="{{ route('admin.permissions.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        + Add Permission
    </a>
</div>

<div class="bg-white rounded shadow p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3">
        <input name="q" value="{{ request('q') }}" class="border rounded px-3 py-2 w-64"
               placeholder="Search key/label/group...">

        <select name="group" class="border rounded px-3 py-2">
            <option value="">All Groups</option>
            @foreach($groups as $g)
                <option value="{{ $g }}" @selected(request('group')===$g)>{{ $g }}</option>
            @endforeach
        </select>

        <select name="status" class="border rounded px-3 py-2">
            <option value="">All</option>
            <option value="1" @selected(request('status')==='1')>Active</option>
            <option value="0" @selected(request('status')==='0')>Inactive</option>
        </select>

        <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
        <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
    </form>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b">
            <tr class="text-left">
                <th class="p-3">Group</th>
                <th class="p-3">Key</th>
                <th class="p-3">Label</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y">
        @forelse($permissions as $p)
            <tr>
                <td class="p-3">{{ $p->group ?? '-' }}</td>
                <td class="p-3 font-mono text-xs">{{ $p->key }}</td>
                <td class="p-3">{{ $p->label }}</td>
                <td class="p-3">
                    @if($p->status)
                        <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Active</span>
                    @else
                        <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">Inactive</span>
                    @endif
                </td>
                <td class="p-3">
                    <div class="flex justify-end gap-2">
                        <form method="POST" action="{{ route('admin.permissions.toggle', $p) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1.5 rounded border text-xs">Toggle</button>
                        </form>

                        <a href="{{ route('admin.permissions.edit', $p) }}"
                           class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('admin.permissions.destroy', $p) }}"
                              onsubmit="return confirm('Delete this permission?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 rounded bg-rose-600 text-white text-xs">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-6 text-center text-gray-500">No permissions found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="p-4 border-t">
        {{ $permissions->links() }}
    </div>
</div>
@endsection
