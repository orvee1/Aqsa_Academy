@php $isEdit = isset($category); @endphp
<div class="grid gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input name="name" value="{{ old('name', $category->name ?? '') }}"
            class="w-full border rounded px-3 py-2 @error('name') border-rose-400 @enderror">
        @error('name')
            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Slug (optional)</label>
        <input name="slug" value="{{ old('slug', $category->slug ?? '') }}"
            class="w-full border rounded px-3 py-2 @error('slug') border-rose-400 @enderror">
        @error('slug')
            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
        @enderror
        <div class="text-xs text-gray-500 mt-1">ফাঁকা রাখলে name থেকে auto slug হবে।</div>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $category->status ?? true))>
        <label class="text-sm font-medium">Active</label>
    </div>
</div>
