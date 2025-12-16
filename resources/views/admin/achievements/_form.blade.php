@php $isEdit = isset($achievement); @endphp

<div class="grid gap-4">
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Title</label>
            <input name="title" value="{{ old('title', $achievement->title ?? '') }}"
                class="w-full border rounded px-3 py-2 @error('title') border-rose-400 @enderror" required>
            @error('title')
                <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Year (optional)</label>
            <input name="year" value="{{ old('year', $achievement->year ?? '') }}"
                class="w-full border rounded px-3 py-2" placeholder="2024 / ২০২৪">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Description (optional)</label>
        <textarea name="description" class="w-full border rounded px-3 py-2 min-h-[90px]">{{ old('description', $achievement->description ?? '') }}</textarea>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Image (optional)</label>
            <input type="file" name="image" class="w-full border rounded px-3 py-2">
            @if (isset($achievement) && $achievement->image_path)
                <div class="text-xs mt-2">
                    Current: <a class="underline" target="_blank"
                        href="{{ asset('storage/' . $achievement->image_path) }}">View</a>
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Position</label>
            <input name="position" type="number" value="{{ old('position', $achievement->position ?? 0) }}"
                class="w-full border rounded px-3 py-2">
        </div>

        <div class="flex items-center gap-2 pt-7">
            <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $achievement->status ?? true))>
            <label class="text-sm font-medium">Active</label>
        </div>
    </div>
</div>
