@php $isEdit = isset($post); @endphp

<div class="grid gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input name="title" value="{{ old('title', $post->title ?? '') }}" class="w-full border rounded px-3 py-2">
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Slug (optional)</label>
            <input name="slug" value="{{ old('slug', $post->slug ?? '') }}" class="w-full border rounded px-3 py-2">
            <div class="text-xs text-gray-500 mt-1">ফাঁকা রাখলে title থেকে auto slug হবে।</div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <select name="post_category_id" class="w-full border rounded px-3 py-2">
                <option value="">— None —</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" @selected(old('post_category_id', $post->post_category_id ?? '') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="published" @selected(old('status', $post->status ?? 'published') === 'published')>Published</option>
                <option value="draft" @selected(old('status', $post->status ?? '') === 'draft')>Draft</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Published At (optional)</label>
            <input type="datetime-local" name="published_at"
                value="{{ old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Excerpt (optional)</label>
        <textarea name="excerpt" class="w-full border rounded px-3 py-2 min-h-[90px]">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Featured Image (optional)</label>
        <input type="file" name="featured_image" class="w-full border rounded px-3 py-2">
        @if (isset($post) && $post->featured_image_path)
            <div class="text-xs mt-2">
                Current: <a class="underline" target="_blank"
                    href="{{ asset('storage/' . $post->featured_image_path) }}">View</a>
            </div>
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Content</label>
        <textarea id="postContent" name="content" class="w-full border rounded px-3 py-2 min-h-[280px]">{{ old('content', $post->content ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        (function() {
            const el = document.getElementById('postContent');
            if (!el) return;
            ClassicEditor.create(el).catch(console.error);
        })();
    </script>
@endpush
