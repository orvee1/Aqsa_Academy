@extends('client.layouts.app')
@section('title', $album->title)

@section('content')
    <div class="bg-white border rounded shadow p-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm text-slate-500">Album</div>
                <div class="font-semibold text-lg">{{ $album->title }}</div>
            </div>
            <a class="px-3 py-2 border rounded text-sm hover:bg-slate-50" href="{{ route('client.gallery.index') }}">Back</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @forelse($items as $it)
                <button type="button" class="border rounded overflow-hidden bg-slate-50 hover:ring-2 hover:ring-teal-500"
                    data-lightbox="{{ $it->image_path }}" data-caption="{{ $it->caption ?? ($it->title ?? '') }}">
                    <img src="{{ $it->image_path }}" class="w-full h-36 object-cover" alt="">
                </button>
            @empty
                <div class="text-sm text-slate-500">No images found.</div>
            @endforelse
        </div>

        <div class="mt-5">{{ $items->links() }}</div>
    </div>

    {{-- Lightbox modal --}}
    <div id="lbModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70" id="lbClose"></div>
        <div class="relative max-w-5xl mx-auto mt-10 p-3">
            <div class="bg-white rounded shadow overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <div class="text-sm font-semibold" id="lbCaption">Preview</div>
                    <button class="px-3 py-1.5 border rounded" id="lbBtnClose">Close</button>
                </div>
                <div class="bg-black">
                    <img id="lbImg" src="" class="w-full max-h-[75vh] object-contain" alt="">
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const modal = document.getElementById('lbModal');
            const img = document.getElementById('lbImg');
            const cap = document.getElementById('lbCaption');
            const close1 = document.getElementById('lbClose');
            const close2 = document.getElementById('lbBtnClose');

            function open(src, caption) {
                img.src = src;
                cap.textContent = caption || 'Preview';
                modal.classList.remove('hidden');
            }

            function close() {
                modal.classList.add('hidden');
                img.src = '';
            }

            document.addEventListener('click', function(e) {
                const b = e.target.closest('[data-lightbox]');
                if (b) {
                    open(b.getAttribute('data-lightbox'), b.getAttribute('data-caption'));
                }
            });

            close1?.addEventListener('click', close);
            close2?.addEventListener('click', close);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') close();
            });
        })();
    </script>
@endsection
