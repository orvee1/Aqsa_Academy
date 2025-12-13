<x-super-layout title="Tenants">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-lg font-bold">Tenants</h1>
        <a href="{{ route('super.tenants.create') }}" class="rounded-lg bg-sky-600 text-white px-4 py-2 font-semibold">+ New</a>
    </div>

    <div class="rounded-xl bg-white border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-left">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Domain</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenants as $t)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">{{ $t->name }}</td>
                        <td class="px-4 py-3">{{ $t->slug }}</td>
                        <td class="px-4 py-3">{{ $t->domain }}</td>
                        <td class="px-4 py-3">{{ $t->status ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-sky-700 font-semibold" href="{{ route('super.tenants.edit', $t) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tenants->links() }}</div>
</x-super-layout>
