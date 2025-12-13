<x-super-layout title="Create Tenant">
    <h1 class="text-lg font-bold mb-4">Create Tenant</h1>

    <form method="POST" action="{{ route('super.tenants.store') }}" class="space-y-4 max-w-xl">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-1">Name</label>
            <input name="name" value="{{ old('name') }}" class="w-full rounded-lg border px-3 py-2" />
            @error('name') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Slug (optional)</label>
            <input name="slug" value="{{ old('slug') }}" class="w-full rounded-lg border px-3 py-2" />
            @error('slug') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Domain (optional)</label>
            <input name="domain" value="{{ old('domain') }}" class="w-full rounded-lg border px-3 py-2" />
            @error('domain') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Status</label>
            <select name="status" class="w-full rounded-lg border px-3 py-2">
                <option value="1" @selected(old('status',1)==1)>Active</option>
                <option value="0" @selected(old('status')==='0')>Inactive</option>
            </select>
        </div>

        <button class="rounded-lg bg-sky-600 text-white px-4 py-2 font-semibold">Create</button>
    </form>
</x-super-layout>
