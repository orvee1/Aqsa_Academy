@php $isEdit = isset($link); @endphp

<div class="grid gap-4">
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Group Title (optional)</label>
            <input name="group_title" value="{{ old('group_title', $link->group_title ?? '') }}"
                class="w-full border rounded px-3 py-2" placeholder="Quick Links">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Position</label>
            <input name="position" type="number" value="{{ old('position', $link->position ?? 0) }}"
                class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Title</label>
            <input name="title" value="{{ old('title', $link->title ?? '') }}"
                class="w-full border rounded px-3 py-2 @error('title') border-rose-400 @enderror" required>
            @error('title')
                <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">URL</label>
            <input name="url" value="{{ old('url', $link->url ?? '') }}"
                class="w-full border rounded px-3 py-2 @error('url') border-rose-400 @enderror"
                placeholder="https://..." required>
            @error('url')
                <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $link->status ?? true))>
        <label class="text-sm font-medium">Active</label>
    </div>
</div>
