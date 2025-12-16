@php $isEdit = isset($event); @endphp

<div class="grid gap-4">
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Title</label>
            <input name="title" value="{{ old('title', $event->title ?? '') }}"
                class="w-full border rounded px-3 py-2 @error('title') border-rose-400 @enderror" required>
            @error('title')
                <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Slug (optional)</label>
            <input name="slug" value="{{ old('slug', $event->slug ?? '') }}"
                class="w-full border rounded px-3 py-2 @error('slug') border-rose-400 @enderror">
            @error('slug')
                <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
            @enderror
            <div class="text-xs text-gray-500 mt-1">ফাঁকা রাখলে title থেকে auto slug হবে।</div>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea id="eventDesc" name="description" class="w-full border rounded px-3 py-2 min-h-[220px]">{{ old('description', $event->description ?? '') }}</textarea>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Event Date</label>
            <input type="date" name="event_date"
                value="{{ old('event_date', isset($event->event_date) ? $event->event_date->format('Y-m-d') : '') }}"
                class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Event Time (optional)</label>
            <input type="time" name="event_time" value="{{ old('event_time', $event->event_time ?? '') }}"
                class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Venue (optional)</label>
            <input name="venue" value="{{ old('venue', $event->venue ?? '') }}"
                class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Cover Image (optional)</label>
            <input type="file" name="cover_image" class="w-full border rounded px-3 py-2">
            @if (isset($event) && $event->cover_image_path)
                <div class="text-xs mt-2">
                    Current: <a class="underline" target="_blank"
                        href="{{ asset('storage/' . $event->cover_image_path) }}">View</a>
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="published" @selected(old('status', $event->status ?? 'published') === 'published')>Published</option>
                <option value="draft" @selected(old('status', $event->status ?? '') === 'draft')>Draft</option>
            </select>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        (function() {
            const el = document.getElementById('eventDesc');
            if (!el) return;
            ClassicEditor.create(el).catch(console.error);
        })();
    </script>
@endpush
