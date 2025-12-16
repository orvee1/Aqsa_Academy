@php $isEdit = isset($album); @endphp
<div class="grid gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Album Title</label>
        <input name="title" value="{{ old('title', $album->title ?? '') }}"
            class="w-full border rounded px-3 py-2 @error('title') border-rose-400 @enderror" required>
        @error('title')
            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $album->status ?? true))>
        <label class="text-sm font-medium">Active</label>
    </div>
</div>
