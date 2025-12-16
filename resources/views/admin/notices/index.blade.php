@extends('tailwind.layouts.admin')
@section('title', 'নোটিশবোর্ড')

@section('content')
    <div class="grid lg:grid-cols-12 gap-6">
        {{-- Left: Create Notice form (Screenshot-like) --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow p-5">
                <div class="text-lg font-semibold mb-4">নোটিশবোর্ড</div>

                <form method="POST" action="{{ route('admin.notices.store') }}" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1">নোটিশ শিরোনাম:</label>
                        <input name="title" value="{{ old('title') }}"
                            class="w-full border rounded-lg px-3 py-2 @error('title') border-rose-400 @enderror"
                            placeholder="যেমন: গ্রীষ্মকালীন ছুটি">
                        @error('title')
                            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">নোটিশ লিখুন:</label>
                        <textarea id="noticeBody" name="body"
                            class="w-full border rounded-lg px-3 py-2 min-h-[220px] @error('body') border-rose-400 @enderror"
                            placeholder="নোটিশ বিস্তারিত লিখুন...">{{ old('body') }}</textarea>
                        @error('body')
                            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Attachment (optional)</label>
                            <input type="file" name="file" class="w-full border rounded-lg px-3 py-2">
                            @error('file')
                                <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4 pt-6">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="is_published" value="1" class="h-4 w-4"
                                    @checked(old('is_published', true))>
                                Published
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="is_pinned" value="1" class="h-4 w-4"
                                    @checked(old('is_pinned', false))>
                                Pin
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="is_hidden" value="1" class="h-4 w-4"
                                    @checked(old('is_hidden', false))>
                                Hidden
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button class="px-5 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-900">
                            নোটিশ পাবলিশ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Notice Board list (Screenshot-like) --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <div class="font-semibold">নোটিশ বোর্ড: প্রকাশিত নোটিশ</div>
                    <div class="text-xs text-gray-500">হাইড/আনহাইড</div>
                </div>

                <div class="p-4 border-b">
                    <form method="GET" class="flex gap-2">
                        <input name="q" value="{{ request('q') }}" placeholder="Search..."
                            class="w-full border rounded-lg px-3 py-2 text-sm">
                        <button class="px-3 py-2 rounded-lg bg-slate-800 text-white text-sm">Go</button>
                    </form>

                    <div class="mt-2 flex gap-2 text-xs">
                        <a class="px-2 py-1 rounded border {{ request('hidden') === '' ? 'bg-slate-50' : '' }}"
                            href="{{ route('admin.notices.index', array_merge(request()->query(), ['hidden' => null])) }}">All</a>
                        <a class="px-2 py-1 rounded border {{ request('hidden') === '0' ? 'bg-slate-50' : '' }}"
                            href="{{ route('admin.notices.index', array_merge(request()->query(), ['hidden' => '0'])) }}">Unhidden</a>
                        <a class="px-2 py-1 rounded border {{ request('hidden') === '1' ? 'bg-slate-50' : '' }}"
                            href="{{ route('admin.notices.index', array_merge(request()->query(), ['hidden' => '1'])) }}">Hidden</a>
                    </div>
                </div>

                <div class="divide-y">
                    @forelse($notices as $idx => $n)
                        <div class="px-4 py-3 flex items-start gap-3">
                            <div class="text-sm text-gray-700 w-6">
                                {{ ($notices->firstItem() ?? 1) + $idx }}.
                            </div>

                            <div class="flex-1">
                                <div class="text-sm font-medium leading-6">
                                    {{ $n->title }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $n->published_at?->format('d M Y') ?? '—' }}
                                    @if ($n->is_pinned)
                                        <span class="ml-2 px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">Pinned</span>
                                    @endif
                                    @if (!$n->is_published)
                                        <span
                                            class="ml-2 px-1.5 py-0.5 rounded bg-rose-100 text-rose-700">Unpublished</span>
                                    @endif
                                </div>

                                <div class="mt-2 flex gap-2 text-xs">
                                    <a href="{{ route('admin.notices.edit', $n) }}" class="underline">Edit</a>

                                    <form method="POST" action="{{ route('admin.notices.destroy', $n) }}"
                                        onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="underline text-rose-600">Delete</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.notices.toggle-publish', $n) }}">
                                        @csrf @method('PATCH')
                                        <button class="underline">{{ $n->is_published ? 'Unpublish' : 'Publish' }}</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.notices.toggle-pin', $n) }}">
                                        @csrf @method('PATCH')
                                        <button class="underline">{{ $n->is_pinned ? 'Unpin' : 'Pin' }}</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Eye icon hide/unhide (Screenshot vibe) --}}
                            <form method="POST" action="{{ route('admin.notices.toggle-hide', $n) }}" class="pt-1">
                                @csrf @method('PATCH')
                                <button class="p-2 rounded hover:bg-slate-100"
                                    title="{{ $n->is_hidden ? 'Unhide' : 'Hide' }}">
                                    @if ($n->is_hidden)
                                        {{-- eye-off --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.8 21.8 0 0 1 5.06-6.94" />
                                            <path d="M1 1l22 22" />
                                            <path
                                                d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.9 21.9 0 0 1-3.17 4.55" />
                                            <path d="M14.12 14.12a3 3 0 0 1-4.24-4.24" />
                                        </svg>
                                    @else
                                        {{-- eye --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7S2 12 2 12z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-5 text-sm text-gray-500">No notices found.</div>
                    @endforelse
                </div>

                <div class="p-4 border-t">
                    {{ $notices->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- CKEditor --}}
    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            (function() {
                const el = document.getElementById('noticeBody');
                if (!el) return;

                ClassicEditor.create(el, {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                }).catch(console.error);
            })();
        </script>
    @endpush
@endsection
