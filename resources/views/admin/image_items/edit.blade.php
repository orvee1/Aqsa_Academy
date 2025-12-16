@extends('tailwind.layouts.admin')
@section('title', 'Edit Image')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Edit Image</h2>
        <a href="{{ route('admin.image-albums.items', $album->id) }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="bg-white rounded shadow p-5">
        <form method="POST" action="{{ route('admin.image-items.update', $imageItem) }}" enctype="multipart/form-data"
            class="grid gap-4">
            @csrf
            @method('PUT')

            <input type="hidden" name="album_id" value="{{ $album->id }}">

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Title (optional)</label>
                    <input name="title" value="{{ old('title', $imageItem->title) }}"
                        class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Caption (optional)</label>
                    <input name="caption" value="{{ old('caption', $imageItem->caption) }}"
                        class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Replace Image (optional)</label>
                    <input type="file" name="image" class="w-full border rounded px-3 py-2">
                    <div class="text-xs mt-2">
                        Current: <a class="underline" target="_blank"
                            href="{{ asset('storage/' . $imageItem->image_path) }}">View</a>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Position</label>
                    <input name="position" type="number" value="{{ old('position', $imageItem->position) }}"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex items-center gap-2 pt-7">
                    <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $imageItem->status))>
                    <label class="text-sm font-medium">Active</label>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                <a href="{{ route('admin.image-albums.items', $album->id) }}" class="px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
