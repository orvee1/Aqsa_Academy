@php $isEdit = isset($social); @endphp

<div class="grid gap-4">
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Platform</label>
            <input name="platform" value="{{ old('platform', $social->platform ?? '') }}"
                class="w-full border rounded px-3 py-2 @error('platform') border-rose-400 @enderror"
                placeholder="facebook / youtube / instagram" required>
            @error('platform')
                <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
            @enderror
            <div class="text-xs text-gray-500 mt-1">একই নাম consistent রাখুন (facebook, youtube...)</div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Position</label>
            <input name="position" type="number" value="{{ old('position', $social->position ?? 0) }}"
                class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">URL</label>
        <input name="url" value="{{ old('url', $social->url ?? '') }}"
            class="w-full border rounded px-3 py-2 @error('url') border-rose-400 @enderror" placeholder="https://..."
            required>
        @error('url')
            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $social->status ?? true))>
        <label class="text-sm font-medium">Active</label>
    </div>
</div>
