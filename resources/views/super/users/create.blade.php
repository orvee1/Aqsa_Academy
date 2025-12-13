<x-super-layout title="Create User">
    <h1 class="text-lg font-bold mb-4">Create User</h1>

    <form method="POST" action="{{ route('super.users.store') }}" class="space-y-4 max-w-xl">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-1">Name</label>
            <input name="name" value="{{ old('name') }}" class="w-full rounded-lg border px-3 py-2" />
            @error('name') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Email</label>
            <input name="email" value="{{ old('email') }}" class="w-full rounded-lg border px-3 py-2" />
            @error('email') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-semibold mb-1">Password</label>
                <input type="password" name="password" class="w-full rounded-lg border px-3 py-2" />
                @error('password') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Confirm</label>
                <input type="password" name="password_confirmation" class="w-full rounded-lg border px-3 py-2" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Role</label>
            <select name="role" class="w-full rounded-lg border px-3 py-2">
                <option value="tenant-admin">tenant-admin</option>
                <option value="editor">editor</option>
                <option value="super-admin">super-admin</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Tenant (only for non-super)</label>
            <select name="tenant_id" class="w-full rounded-lg border px-3 py-2">
                <option value="">—</option>
                @foreach($tenants as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="rounded-lg bg-sky-600 text-white px-4 py-2 font-semibold">Create</button>
    </form>
</x-super-layout>
