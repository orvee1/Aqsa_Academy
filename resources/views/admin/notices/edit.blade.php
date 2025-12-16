@extends('tailwind.layouts.admin')
@section('title','Edit Notice')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold">Edit Notice</h2>
    <a href="{{ route('admin.notices.index') }}" class="px-4 py-2 border rounded">Back</a>
</div>

<div class="bg-white rounded-xl shadow p-5">
    <form method="POST" action="{{ route('admin.notices.update', $notice) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">নোটিশ শিরোনাম:</label>
            <input name="title" value="{{ old('title', $notice->title) }}"
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Slug (optional)</label>
            <input name="slug" value="{{ old('slug', $notice->slug) }}"
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">নোটিশ লিখুন:</label>
            <textarea id="noticeBody" name="body"
                      class="w-full border rounded-lg px-3 py-2 min-h-[260px]">{{ old('body', $notice->body) }}</textarea>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Attachment</label>
                <input type="file" name="file" class="w-full border rounded-lg px-3 py-2">
                @if($notice->file_path)
                    <div class="text-xs mt-2 text-gray-600">
                        Current: <a class="underline" target="_blank" href="{{ asset('storage/'.$notice->file_path) }}">View file</a>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4 pt-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_published" value="1" class="h-4 w-4"
                           @checked(old('is_published', $notice->is_published))>
                    Published
                </label>

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_pinned" value="1" class="h-4 w-4"
                           @checked(old('is_pinned', $notice->is_pinned))>
                    Pin
                </label>

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_hidden" value="1" class="h-4 w-4"
                           @checked(old('is_hidden', $notice->is_hidden))>
                    Hidden
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button class="px-5 py-2 rounded-lg bg-indigo-600 text-white">Update</button>
            <a href="{{ route('admin.notices.index') }}" class="px-5 py-2 rounded-lg border">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#noticeBody')).catch(console.error);
</script>
@endpush
@endsection

