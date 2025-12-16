@extends('tailwind.layouts.admin')

@section('title','Users')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Users</h2>
    <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        + Add User
    </a>
</div>

<div class="bg-white rounded shadow p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Search name/email/phone..."
               class="border rounded px-3 py-2 w-64">

        <select name="role_id" class="border rounded px-3 py-2">
            <option value="">All Roles</option>
            @foreach($roles as $r)
                <option value="{{ $r->id }}" @selected(request('role_id') == $r->id)>{{ $r->name }}</option>
            @endforeach
        </select>

        <select name="super" class="border rounded px-3 py-2">
            <option value="">All</option>
            <option value="1" @selected(request('super')==='1')>Super Admin</option>
            <option value="0" @selected(request('super')==='0')>Normal</option>
        </select>

        <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 text-slate-800 rounded border">Reset</a>
    </form>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b">
        <tr class="text-left">
            <th class="p-3">Name</th>
            <th class="p-3">Email</th>
            <th class="p-3">Phone</th>
            <th class="p-3">Role</th>
            <th class="p-3">Type</th>
            <th class="p-3 text-right">Action</th>
        </tr>
        </thead>

        <tbody class="divide-y">
        @forelse($users as $u)
            <tr>
                <td class="p-3 font-medium">{{ $u->name }}</td>
                <td class="p-3 text-gray-600">{{ $u->email }}</td>
                <td class="p-3 text-gray-600">{{ $u->phone }}</td>
                <td class="p-3">
                    {{ $u->role?->name ?? '-' }}
                </td>
                <td class="p-3">
                    @if($u->is_super_admin)
                        <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Super Admin</span>
                    @else
                        <span class="px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs">Normal</span>
                    @endif
                </td>
                <td class="p-3">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.users.edit',$u) }}"
                           class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs hover:bg-indigo-700">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('admin.users.toggle-super',$u) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1.5 rounded border text-xs hover:bg-slate-50">
                                Toggle Super
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.destroy',$u) }}"
                              onsubmit="return confirm('Delete this user?')">
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
                <td class="p-6 text-center text-gray-500" colspan="6">No users found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="p-4 border-t">
        {{ $users->links() }}
    </div>
</div>
@endsection
