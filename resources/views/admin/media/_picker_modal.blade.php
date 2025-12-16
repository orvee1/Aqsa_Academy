{{-- Reusable Media Picker Modal --}}
<div id="mediaPickerModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" data-mp-close></div>

    <div class="relative mx-auto mt-10 w-[95%] max-w-5xl">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-5 py-4 border-b flex items-center justify-between">
                <div>
                    <div class="text-lg font-semibold">Select Media</div>
                    <div class="text-xs text-gray-500">Search, preview, then choose</div>
                </div>
                <button class="px-3 py-1.5 border rounded" data-mp-close>Close</button>
            </div>

            <div class="p-4 border-b flex flex-wrap gap-3 items-center">
                <input id="mp_q" class="border rounded px-3 py-2 w-64" placeholder="Search path/mime...">
                <select id="mp_type" class="border rounded px-3 py-2">
                    <option value="">All</option>
                    <option value="image">Images</option>
                    <option value="pdf">PDF</option>
                </select>
                <select id="mp_disk" class="border rounded px-3 py-2">
                    <option value="">All disks</option>
                    <option value="public">public</option>
                </select>

                <button id="mp_search" class="px-4 py-2 bg-slate-800 text-white rounded">Search</button>

                <div class="ml-auto text-xs text-gray-500">
                    <span id="mp_meta">—</span>
                </div>
            </div>

            <div class="p-4">
                <div id="mp_grid" class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    {{-- items injected by JS --}}
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <button id="mp_prev" class="px-3 py-1.5 border rounded disabled:opacity-40" disabled>Prev</button>
                    <div class="text-xs text-gray-500" id="mp_page">—</div>
                    <button id="mp_next" class="px-3 py-1.5 border rounded disabled:opacity-40" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            const modal = document.getElementById('mediaPickerModal');
            const grid = document.getElementById('mp_grid');

            const qEl = document.getElementById('mp_q');
            const typeEl = document.getElementById('mp_type');
            const diskEl = document.getElementById('mp_disk');

            const prevBtn = document.getElementById('mp_prev');
            const nextBtn = document.getElementById('mp_next');
            const pageEl = document.getElementById('mp_page');
            const metaEl = document.getElementById('mp_meta');
            const searchBtn = document.getElementById('mp_search');

            let state = {
                targetInput: null, // where to set url/path
                targetIdInput: null, // optional hidden input for media_id
                valueType: 'url', // 'url' or 'path'
                nextUrl: null,
                prevUrl: null,
                baseUrl: "{{ route('admin.media.picker') }}",
            };

            function openModal({
                target,
                targetId = null,
                valueType = 'url'
            }) {
                state.targetInput = document.querySelector(target);
                state.targetIdInput = targetId ? document.querySelector(targetId) : null;
                state.valueType = valueType || 'url';

                modal.classList.remove('hidden');
                load(state.baseUrl);
            }

            function closeModal() {
                modal.classList.add('hidden');
            }

            async function load(url) {
                grid.innerHTML = `<div class="text-sm text-gray-500 col-span-full">Loading...</div>`;
                const params = new URLSearchParams();

                if (qEl.value.trim()) params.set('q', qEl.value.trim());
                if (typeEl.value) params.set('type', typeEl.value);
                if (diskEl.value) params.set('disk', diskEl.value);

                const finalUrl = url.includes('?') ? (url + '&' + params.toString()) : (url + '?' + params
                .toString());

                const res = await fetch(finalUrl, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const json = await res.json();

                state.nextUrl = json.links?.next || null;
                state.prevUrl = json.links?.prev || null;

                prevBtn.disabled = !state.prevUrl;
                nextBtn.disabled = !state.nextUrl;

                pageEl.textContent = `Page ${json.meta.current_page} / ${json.meta.last_page}`;
                metaEl.textContent = `${json.meta.total} items`;

                render(json.data || []);
            }

            function render(items) {
                if (!items.length) {
                    grid.innerHTML = `<div class="text-sm text-gray-500 col-span-full">No media found.</div>`;
                    return;
                }

                grid.innerHTML = items.map(m => {
                    const thumb = m.is_image ?
                        `<img class="w-full h-full object-cover" src="${m.url}" alt="">` :
                        `<div class="w-full h-full flex items-center justify-center text-xs text-slate-500">${m.mime || 'file'}</div>`;

                    return `
            <button type="button"
                class="text-left border rounded-lg overflow-hidden hover:ring-2 hover:ring-indigo-500"
                data-mp-pick="1"
                data-id="${m.id}"
                data-url="${m.url}"
                data-path="${m.path}">
                <div class="aspect-[4/3] bg-slate-100 overflow-hidden">${thumb}</div>
                <div class="p-2">
                    <div class="text-sm font-medium truncate">${m.filename}</div>
                    <div class="text-xs text-gray-500 truncate">${m.path}</div>
                    <div class="text-[11px] text-gray-500 mt-1">${m.size_kb ? (m.size_kb+' KB') : '—'} • ${m.uploader || 'System'}</div>
                </div>
            </button>`;
                }).join('');
            }

            // Global trigger: any button with data-media-picker
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('[data-media-picker]');
                if (btn) {
                    openModal({
                        target: btn.getAttribute('data-target'),
                        targetId: btn.getAttribute('data-target-id'),
                        valueType: btn.getAttribute('data-value') || 'url',
                    });
                }

                if (e.target.matches('[data-mp-close]') || e.target.closest('[data-mp-close]')) closeModal();

                const pick = e.target.closest('[data-mp-pick]');
                if (pick) {
                    const id = pick.getAttribute('data-id');
                    const url = pick.getAttribute('data-url');
                    const path = pick.getAttribute('data-path');

                    if (state.targetInput) {
                        state.targetInput.value = (state.valueType === 'path') ? path : url;
                        state.targetInput.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                    if (state.targetIdInput) {
                        state.targetIdInput.value = id;
                        state.targetIdInput.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                    closeModal();
                }
            });

            prevBtn.addEventListener('click', () => state.prevUrl && load(state.prevUrl));
            nextBtn.addEventListener('click', () => state.nextUrl && load(state.nextUrl));
            searchBtn.addEventListener('click', () => load(state.baseUrl));

        })();
    </script>
@endpush
