@php $isEdit = isset($video); @endphp

<div class="grid gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input name="title" value="{{ old('title', $video->title ?? '') }}"
            class="w-full border rounded px-3 py-2 @error('title') border-rose-400 @enderror" required>
        @error('title')
            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">YouTube URL</label>
        <input name="youtube_url" value="{{ old('youtube_url', $video->youtube_url ?? '') }}"
            class="w-full border rounded px-3 py-2 @error('youtube_url') border-rose-400 @enderror"
            placeholder="https://www.youtube.com/watch?v=xxxxx" required>
        @error('youtube_url')
            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
        @enderror
        <div class="text-xs text-gray-500 mt-1">watch?v= , youtu.be/ , embed/ , shorts/ — সব acceptable</div>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Thumbnail (optional)</label>
            <input type="file" name="thumbnail" class="w-full border rounded px-3 py-2">
            @if (isset($video) && $video->thumbnail_path)
                <div class="text-xs mt-2">
                    Current: <a class="underline" target="_blank"
                        href="{{ asset('storage/' . $video->thumbnail_path) }}">View</a>
                </div>
            @elseif(isset($video) && $video->youtubeThumbUrl())
                <div class="text-xs mt-2">
                    Auto: <a class="underline" target="_blank" href="{{ $video->youtubeThumbUrl() }}">YouTube Thumb</a>
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Position</label>
            <input name="position" type="number" value="{{ old('position', $video->position ?? 0) }}"
                class="w-full border rounded px-3 py-2">
        </div>

        <div class="flex items-center gap-2 pt-7">
            <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $video->status ?? true))>
            <label class="text-sm font-medium">Active</label>
        </div>
    </div>
</div>
