<x-super-layout title="Edit Tenant">
    <h1 class="text-lg font-bold mb-4">Edit Tenant</h1>

    <form method="POST" action="{{ route('super.tenants.update', $tenant) }}" class="space-y-4 max-w-xl">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold mb-1">Name</label>
            <input name="name" value="{{ old('name', $tenant->name) }}" class="w-full rounded-lg border px-3 py-2" />
            @error('name') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Slug</label>
            <input name="slug" value="{{ old('slug', $tenant->slug) }}" class="w-full rounded-lg border px-3 py-2" />
            @error('slug') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Domain</label>
            <input name="domain" value="{{ old('domain', $tenant->domain) }}" class="w-full rounded-lg border px-3 py-2" />
            @error('domain') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Status</label>
            <select name="status" class="w-full rounded-lg border px-3 py-2">
                <option value="1" @selected(old('status', $tenant->status)==1)>Active</option>
                <option value="0" @selected(old('status', $tenant->status)==0)>Inactive</option>
            </select>
        </div>

        <button class="rounded-lg bg-sky-600 text-white px-4 py-2 font-semibold">Save</button>
    </form>
</x-super-layout>
