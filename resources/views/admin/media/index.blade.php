@extends('tailwind.layouts.admin')
@section('title', 'Media Library')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Media Library</h2>
            <div class="text-sm text-gray-500">Upload + manage files for all modules</div>
        </div>
    </div>

    <div class="grid lg:grid-cols-12 gap-6">
        {{-- Left: Upload --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-xl shadow p-5">
                <div class="font-semibold mb-4">Upload Files</div>

                <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1">Disk</label>
                        <select name="disk" class="w-full border rounded px-3 py-2">
                            @foreach ($disks as $d)
                                <option value="{{ $d }}" @selected(request('disk', $d) === $d)>{{ $d }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Select Files (multiple)</label>
                        <input type="file" name="files[]" multiple class="w-full border rounded px-3 py-2" required>
                        <div class="text-xs text-gray-500 mt-1">images/pdf/doc… সব support. (max 10MB each)</div>
                    </div>

                    <button class="w-full px-4 py-2 bg-slate-800 text-white rounded hover:bg-slate-900">
                        Upload
                    </button>
                </form>
            </div>

            {{-- Filter --}}
            <div class="bg-white rounded-xl shadow p-5 mt-6">
                <div class="font-semibold mb-3">Filter</div>
                <form method="GET" class="space-y-3">
                    <input name="q" value="{{ request('q') }}" class="w-full border rounded px-3 py-2"
                        placeholder="Search path/mime...">

                    <select name="type" class="w-full border rounded px-3 py-2">
                        <option value="">All Types</option>
                        <option value="image" @selected(request('type') === 'image')>Images</option>
                        <option value="pdf" @selected(request('type') === 'pdf')>PDF</option>
                    </select>

                    <select name="disk" class="w-full border rounded px-3 py-2">
                        <option value="">All Disks</option>
                        @foreach ($disks as $d)
                            <option value="{{ $d }}" @selected(request('disk') === $d)>{{ $d }}</option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-slate-800 text-white rounded w-full">Apply</button>
                        <a href="{{ route('admin.media.index') }}"
                            class="px-4 py-2 border rounded w-full text-center bg-slate-50">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Grid --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="font-semibold">Files</div>
                    <div class="text-xs text-gray-500">{{ $media->total() }} items</div>
                </div>

                @if ($media->count() === 0)
                    <div class="text-sm text-gray-500">No media found.</div>
                @else
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($media as $m)
                            @php
                                $url = \Illuminate\Support\Facades\Storage::disk($m->disk)->url($m->path);
                                $isImg = $m->isImage();
                            @endphp

                            <div class="border rounded-lg overflow-hidden">
                                <div class="aspect-[4/3] bg-slate-100 overflow-hidden">
                                    @if ($isImg)
                                        <img class="w-full h-full object-cover" src="{{ $url }}" alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">
                                            {{ $m->mime ?? 'file' }}
                                        </div>
                                    @endif
                                </div>

                                <div class="p-3">
                                    <div class="text-sm font-medium truncate">{{ basename($m->path) }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $m->path }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $m->size ? number_format($m->size / 1024, 1) . ' KB' : '—' }}
                                        • {{ $m->uploader?->name ?? 'System' }}
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <a class="px-2 py-1 border rounded text-xs" target="_blank"
                                            href="{{ $url }}">Open</a>

                                        <button type="button" class="px-2 py-1 border rounded text-xs"
                                            onclick="navigator.clipboard.writeText('{{ $url }}')">
                                            Copy URL
                                        </button>

                                        <button type="button" class="px-2 py-1 border rounded text-xs"
                                            onclick="navigator.clipboard.writeText('{{ $m->path }}')">
                                            Copy Path
                                        </button>

                                        <form method="POST" action="{{ route('admin.media.destroy', $m->id) }}"
                                            onsubmit="return confirm('Delete this media?')">
                                            @csrf @method('DELETE')
                                            <button class="px-2 py-1 bg-rose-600 text-white rounded text-xs">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5">{{ $media->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
