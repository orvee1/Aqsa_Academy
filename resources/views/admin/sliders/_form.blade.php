@php $isEdit = isset($slider); @endphp

<div class="grid gap-4">
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Title (optional)</label>
            <input name="title" value="{{ old('title', $slider->title ?? '') }}" class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Subtitle (optional)</label>
            <input name="subtitle" value="{{ old('subtitle', $slider->subtitle ?? '') }}"
                class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Image
                {{ $isEdit ? '(optional replace)' : '(required)' }}</label>
            <input type="file" name="image" class="w-full border rounded px-3 py-2"
                {{ $isEdit ? '' : 'required' }}>
            @if ($isEdit && $slider->image_path)
                <div class="text-xs mt-2">
                    Current: <a class="underline" target="_blank"
                        href="{{ asset('storage/' . $slider->image_path) }}">View</a>
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Link URL (optional)</label>
            <input name="link_url" value="{{ old('link_url', $slider->link_url ?? '') }}"
                class="w-full border rounded px-3 py-2" placeholder="https://...">
        </div>
    </div>

    <div class="grid md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Position</label>
            <input name="position" type="number" value="{{ old('position', $slider->position ?? 0) }}"
                class="w-full border rounded px-3 py-2">
        </div>

        <div class="flex items-center gap-2 pt-7">
            <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $slider->status ?? true))>
            <label class="text-sm font-medium">Active</label>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Start At (optional)</label>
            <input type="datetime-local" name="start_at"
                value="{{ old('start_at', isset($slider->start_at) ? $slider->start_at->format('Y-m-d\TH:i') : '') }}"
                class="w-full border rounded px-3 py-2">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">End At (optional)</label>
            <input type="datetime-local" name="end_at"
                value="{{ old('end_at', isset($slider->end_at) ? $slider->end_at->format('Y-m-d\TH:i') : '') }}"
                class="w-full border rounded px-3 py-2">
        </div>
    </div>
</div>
