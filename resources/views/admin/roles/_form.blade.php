@php
  $isEdit = isset($role);
@endphp

<div class="grid gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Role Name</label>
        <input type="text" name="name"
               value="{{ old('name', $role->name ?? '') }}"
               class="w-full border rounded px-3 py-2 @error('name') border-rose-400 @enderror"
               placeholder="Admin / Editor / Staff">
        @error('name') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Slug (optional)</label>
        <input type="text" name="slug"
               value="{{ old('slug', $role->slug ?? '') }}"
               class="w-full border rounded px-3 py-2 @error('slug') border-rose-400 @enderror"
               placeholder="admin / editor / staff">
        <div class="text-xs text-gray-500 mt-1">ফাঁকা রাখলে name থেকে auto slug হবে।</div>
        @error('slug') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="status" value="1"
               class="h-4 w-4"
               @checked(old('status', $role->status ?? true))>
        <label class="text-sm">Active</label>
        @error('status') <div class="text-rose-600 text-xs">{{ $message }}</div> @enderror
    </div>
</div>
