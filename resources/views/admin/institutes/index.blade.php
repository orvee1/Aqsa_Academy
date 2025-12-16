@extends('tailwind.layouts.admin')
@section('title','Institutes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Institutes</h2>
    <a href="{{ route('admin.institutes.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        + Add Institute
    </a>
</div>

<div class="bg-white rounded shadow p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3">
        <input name="q" value="{{ request('q') }}"
               class="border rounded px-3 py-2 w-72"
               placeholder="Search name / EIIN / codes...">

        <select name="status" class="border rounded px-3 py-2">
            <option value="">All</option>
            <option value="1" @selected(request('status')==='1')>Active</option>
            <option value="0" @selected(request('status')==='0')>Inactive</option>
        </select>

        <button class="px-4 py-2 bg-slate-800 text-white rounded">Filter</button>
        <a href="{{ route('admin.institutes.index') }}" class="px-4 py-2 border rounded bg-slate-50">Reset</a>
    </form>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b">
            <tr class="text-left">
                <th class="p-3">Name</th>
                <th class="p-3">EIIN</th>
                <th class="p-3">Codes</th>
                <th class="p-3">Contact</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y">
        @forelse($institutes as $i)
            <tr>
                <td class="p-3 font-medium">
                    <div>{{ $i->name }}</div>
                    <div class="text-xs text-gray-500">{{ $i->slogan }}</div>
                </td>
                <td class="p-3 text-gray-700">{{ $i->eiin ?? '-' }}</td>
                <td class="p-3 text-gray-700">
                    <div class="text-xs">School: {{ $i->school_code ?? '-' }}</div>
                    <div class="text-xs">College: {{ $i->college_code ?? '-' }}</div>
                </td>
                <td class="p-3 text-gray-700">
                    <div class="text-xs">Phone: {{ $i->phone_1 ?? '-' }}</div>
                    <div class="text-xs">Mobile: {{ $i->mobile_1 ?? '-' }}</div>
                </td>
                <td class="p-3">
                    @if($i->status)
                        <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Active</span>
                    @else
                        <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">Inactive</span>
                    @endif
                </td>
                <td class="p-3">
                    <div class="flex justify-end gap-2">
                        <form method="POST" action="{{ route('admin.institutes.toggle', $i) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1.5 rounded border text-xs hover:bg-slate-50">Toggle</button>
                        </form>

                        <a href="{{ route('admin.institutes.edit', $i) }}"
                           class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs hover:bg-indigo-700">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('admin.institutes.destroy', $i) }}"
                              onsubmit="return confirm('Delete this institute?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 rounded bg-rose-600 text-white text-xs hover:bg-rose-700">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="p-6 text-center text-gray-500">No institutes found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="p-4 border-t">
        {{ $institutes->links() }}
    </div>
</div>
@endsection
