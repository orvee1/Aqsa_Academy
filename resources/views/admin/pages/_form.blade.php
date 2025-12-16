@php $isEdit = isset($page); @endphp

<div class="grid gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input name="title" value="{{ old('title', $page->title ?? '') }}"
            class="w-full border rounded px-3 py-2 @error('title') border-rose-400 @enderror">
        @error('title')
            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Slug (optional)</label>
            <input name="slug" value="{{ old('slug', $page->slug ?? '') }}"
                class="w-full border rounded px-3 py-2 @error('slug') border-rose-400 @enderror">
            @error('slug')
                <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
            @enderror
            <div class="text-xs text-gray-500 mt-1">ফাঁকা রাখলে title থেকে auto slug হবে।</div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Template (optional)</label>
            <input name="template" value="{{ old('template', $page->template ?? '') }}"
                class="w-full border rounded px-3 py-2" placeholder="about / contact / default">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="published" @selected(old('status', $page->status ?? 'published') === 'published')>Published</option>
                <option value="draft" @selected(old('status', $page->status ?? '') === 'draft')>Draft</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Published At (optional)</label>
            <input type="datetime-local" name="published_at"
                value="{{ old('published_at', isset($page->published_at) ? $page->published_at->format('Y-m-d\TH:i') : '') }}"
                class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Content</label>
        <textarea id="pageContent" name="content" class="w-full border rounded px-3 py-2 min-h-[280px]">{{ old('content', $page->content ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        (function() {
            const el = document.getElementById('pageContent');
            if (!el) return;
            ClassicEditor.create(el).catch(console.error);
        })();
    </script>
@endpush
