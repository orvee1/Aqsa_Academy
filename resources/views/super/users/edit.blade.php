<x-super-layout title="Edit User">
    <h1 class="text-lg font-bold mb-4">Edit User</h1>

    <form method="POST" action="{{ route('super.users.update', $user) }}" class="space-y-4 max-w-xl">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold mb-1">Name</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border px-3 py-2" />
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Email</label>
            <input name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border px-3 py-2" />
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-semibold mb-1">New Password (optional)</label>
                <input type="password" name="password" class="w-full rounded-lg border px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Confirm</label>
                <input type="password" name="password_confirmation" class="w-full rounded-lg border px-3 py-2" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Role</label>
            <select name="role" class="w-full rounded-lg border px-3 py-2">
                <option value="tenant-admin" @selected(!$user->is_super_admin && $user->hasRole('tenant-admin'))>tenant-admin</option>
                <option value="editor" @selected(!$user->is_super_admin && $user->hasRole('editor'))>editor</option>
                <option value="super-admin" @selected($user->is_super_admin)>super-admin</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Tenant</label>
            <select name="tenant_id" class="w-full rounded-lg border px-3 py-2">
                <option value="">—</option>
                @foreach($tenants as $t)
                    <option value="{{ $t->id }}" @selected(old('tenant_id', $user->tenant_id)==$t->id)>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="rounded-lg bg-sky-600 text-white px-4 py-2 font-semibold">Save</button>
    </form>
</x-super-layout>
