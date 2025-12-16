@php $isEdit = isset($statement); @endphp

<div class="grid gap-4">
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Title (optional)</label>
            <input name="title" value="{{ old('title', $statement->title ?? '') }}"
                class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Position</label>
            <input name="position" type="number" value="{{ old('position', $statement->position ?? 0) }}"
                class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Body</label>
        <textarea name="body" class="w-full border rounded px-3 py-2 min-h-[220px] @error('body') border-rose-400 @enderror"
            required>{{ old('body', $statement->body ?? '') }}</textarea>
        @error('body')
            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Author Name</label>
            <input name="author_name" value="{{ old('author_name', $statement->author_name ?? '') }}"
                class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Designation</label>
            <input name="author_designation"
                value="{{ old('author_designation', $statement->author_designation ?? '') }}"
                class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Author Photo</label>
            <input type="file" name="author_photo" class="w-full border rounded px-3 py-2">
            @if (isset($statement) && $statement->author_photo_path)
                <div class="text-xs mt-2">
                    Current: <a class="underline" target="_blank"
                        href="{{ asset('storage/' . $statement->author_photo_path) }}">View</a>
                </div>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $statement->status ?? true))>
        <label class="text-sm font-medium">Active</label>
    </div>
</div>
