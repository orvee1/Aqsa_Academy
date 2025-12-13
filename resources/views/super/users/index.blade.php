<x-super-layout title="Users">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-lg font-bold">Users</h1>
        <a href="{{ route('super.users.create') }}" class="rounded-lg bg-sky-600 text-white px-4 py-2 font-semibold">+ New</a>
    </div>

    <div class="rounded-xl bg-white border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-left">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Tenant</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">{{ $u->name }}</td>
                        <td class="px-4 py-3">{{ $u->email }}</td>
                        <td class="px-4 py-3">{{ $u->tenant?->name }}</td>
                        <td class="px-4 py-3">{{ $u->is_super_admin ? 'Super Admin' : ($u->roles->first()->name ?? '-') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-sky-700 font-semibold" href="{{ route('super.users.edit', $u) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</x-super-layout>
