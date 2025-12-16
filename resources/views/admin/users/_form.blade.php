@php $isEdit = isset($user); @endphp

<div class="grid gap-4">
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                   class="w-full border rounded px-3 py-2 @error('name') border-rose-400 @enderror">
            @error('name') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                   class="w-full border rounded px-3 py-2 @error('phone') border-rose-400 @enderror">
            @error('phone') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                   class="w-full border rounded px-3 py-2 @error('email') border-rose-400 @enderror">
            @error('email') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role_id"
                    class="w-full border rounded px-3 py-2 @error('role_id') border-rose-400 @enderror">
                <option value="">Select role</option>
                @foreach($roles as $r)
                    <option value="{{ $r->id }}" @selected(old('role_id', $user->role_id ?? '') == $r->id)>
                        {{ $r->name }}
                    </option>
                @endforeach
            </select>
            @error('role_id') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
            <div class="text-xs text-gray-500 mt-1">Super admin হলে role প্রয়োজন নেই।</div>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_super_admin" value="1"
               class="h-4 w-4"
               @checked(old('is_super_admin', $user->is_super_admin ?? false))>
        <label class="text-sm font-medium">Is Super Admin</label>
        @error('is_super_admin') <div class="text-rose-600 text-xs">{{ $message }}</div> @enderror
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">
                Password {{ $isEdit ? '(leave blank to keep)' : '' }}
            </label>
            <input type="password" name="password"
                   class="w-full border rounded px-3 py-2 @error('password') border-rose-400 @enderror">
            @error('password') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full border rounded px-3 py-2">
        </div>
    </div>
</div>
