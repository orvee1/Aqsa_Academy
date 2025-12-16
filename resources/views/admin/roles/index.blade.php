@extends('tailwind.layouts.admin')

@section('title', 'Roles')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Roles</h2>
        <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Role
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name/slug..."
                class="border rounded px-3 py-2 w-64">

            <select name="status" class="border rounded px-3 py-2">
                <option value="">All Status</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>

            <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>

            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-slate-100 text-slate-800 rounded border">
                Reset
            </a>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-left">
                    <th class="p-3">Name</th>
                    <th class="p-3">Slug</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($roles as $role)
                    <tr>
                        <td class="p-3 font-medium">{{ $role->name }}</td>
                        <td class="p-3 text-gray-600">{{ $role->slug }}</td>
                        <td class="p-3">
                            @if ($role->status)
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Active</span>
                            @else
                                <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">Inactive</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.roles.toggle', $role) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1.5 rounded border text-xs hover:bg-slate-50">
                                        Toggle
                                    </button>
                                </form>

                                <a href="{{ route('admin.roles.edit', $role) }}"
                                    class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs hover:bg-indigo-700">
                                    Edit
                                </a>

                                <a href="{{ route('admin.roles.permissions.edit', $role) }}"
                                    class="px-3 py-1.5 rounded bg-slate-800 text-white text-xs">
                                    Permissions
                                </a>

                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                                    onsubmit="return confirm('Delete this role?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 rounded bg-rose-600 text-white text-xs hover:bg-rose-700">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-6 text-center text-gray-500" colspan="4">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">
            {{ $roles->links() }}
        </div>
    </div>
@endsection
