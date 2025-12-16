@php $isEdit = isset($permission); @endphp

<div class="grid gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Key</label>
        <input name="key" value="{{ old('key', $permission->key ?? '') }}"
               class="w-full border rounded px-3 py-2 @error('key') border-rose-400 @enderror"
               placeholder="notice.create">
        @error('key') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
        <div class="text-xs text-gray-500 mt-1">Unique key. Example: notice.create / users.edit</div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Label</label>
        <input name="label" value="{{ old('label', $permission->label ?? '') }}"
               class="w-full border rounded px-3 py-2 @error('label') border-rose-400 @enderror"
               placeholder="Create Notice">
        @error('label') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Group (optional)</label>
        <input name="group" value="{{ old('group', $permission->group ?? '') }}"
               class="w-full border rounded px-3 py-2 @error('group') border-rose-400 @enderror"
               placeholder="Notice / Users / Pages">
        @error('group') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="status" value="1"
               class="h-4 w-4" @checked(old('status', $permission->status ?? true))>
        <label class="text-sm">Active</label>
    </div>
</div>
