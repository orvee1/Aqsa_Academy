@extends('tailwind.layouts.admin')
@section('title', 'Manage Album Images')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Manage Images</h2>
            <div class="text-sm text-gray-500">Album: <span class="font-semibold">{{ $album->title }}</span></div>
        </div>
        <a href="{{ route('admin.image-albums.index') }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="grid lg:grid-cols-12 gap-6">
        <div class="lg:col-span-4">
            <div class="bg-white rounded-xl shadow p-5">
                <div class="font-semibold mb-4">Upload Images (Multiple)</div>

                <form method="POST" action="{{ route('admin.image-items.store') }}" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <input type="hidden" name="album_id" value="{{ $album->id }}">

                    <div>
                        <label class="block text-sm font-medium mb-1">Select Images</label>
                        <input type="file" name="images[]" multiple class="w-full border rounded px-3 py-2" required>
                        <div class="text-xs text-gray-500 mt-1">একসাথে অনেক ছবি আপলোড হবে। Title/Caption পরে Edit করে দিবেন।
                        </div>
                    </div>

                    <button class="w-full px-4 py-2 bg-slate-800 text-white rounded hover:bg-slate-900">
                        Upload
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow p-5 mt-6">
                <div class="font-semibold mb-3">Filter</div>
                <form method="GET" class="flex gap-2">
                    <select name="status" class="border rounded px-3 py-2 w-full">
                        <option value="">All</option>
                        <option value="1" @selected(request('status') === '1')>Active</option>
                        <option value="0" @selected(request('status') === '0')>Inactive</option>
                    </select>
                    <button class="px-4 py-2 bg-slate-800 text-white rounded">Go</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="font-semibold">Images</div>
                    <div class="text-xs text-gray-500">Sort: position ↑</div>
                </div>

                @if ($items->count() === 0)
                    <div class="text-sm text-gray-500">No images yet.</div>
                @else
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($items as $it)
                            <div class="border rounded-lg overflow-hidden">
                                <div class="aspect-[4/3] bg-slate-100 overflow-hidden">
                                    <img class="w-full h-full object-cover" src="{{ asset('storage/' . $it->image_path) }}"
                                        alt="">
                                </div>

                                <div class="p-3">
                                    <div class="text-sm font-medium truncate">{{ $it->title ?? '—' }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $it->caption ?? '' }}</div>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <form method="POST" action="{{ route('admin.image-items.up', $it) }}">
                                            @csrf @method('PATCH')
                                            <button class="px-2 py-1 border rounded text-xs">↑</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.image-items.down', $it) }}">
                                            @csrf @method('PATCH')
                                            <button class="px-2 py-1 border rounded text-xs">↓</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.image-items.toggle', $it) }}">
                                            @csrf @method('PATCH')
                                            <button
                                                class="px-2 py-1 border rounded text-xs">{{ $it->status ? 'On' : 'Off' }}</button>
                                        </form>

                                        <a href="{{ route('admin.image-items.edit', $it) }}"
                                            class="px-2 py-1 bg-indigo-600 text-white rounded text-xs">Edit</a>

                                        <form method="POST" action="{{ route('admin.image-items.destroy', $it) }}"
                                            onsubmit="return confirm('Delete image?')">
                                            @csrf @method('DELETE')
                                            <button class="px-2 py-1 bg-rose-600 text-white rounded text-xs">Del</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5">{{ $items->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
