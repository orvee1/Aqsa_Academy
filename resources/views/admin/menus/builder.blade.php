@extends('tailwind.layouts.admin')
@section('title', 'Menu Builder')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Menu Builder</h2>
            <div class="text-sm text-gray-500">Menu: <span class="font-semibold">{{ $menu->name }}</span>
                ({{ $menu->location }})</div>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="grid lg:grid-cols-12 gap-6">
        {{-- Left: Add item form (screenshot vibe) --}}
        <div class="lg:col-span-6">
            <div class="bg-white rounded-xl shadow p-5">
                <div class="font-semibold mb-4">Add Menu Item</div>

                <form method="POST" action="{{ route('admin.menu-items.store') }}" class="space-y-4" id="addItemForm">
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}" />

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Label (BN)</label>
                            <input name="label_bn" value="{{ old('label_bn') }}" class="w-full border rounded px-3 py-2"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Label (EN)</label>
                            <input name="label_en" value="{{ old('label_en') }}" class="w-full border rounded px-3 py-2">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Parent (optional)</label>
                            <select name="parent_id" class="w-full border rounded px-3 py-2">
                                <option value="">— Root —</option>
                                @foreach ($allItems as $it)
                                    <option value="{{ $it->id }}">{{ $it->label_bn }} (id:{{ $it->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Type</label>
                            <select name="type" class="w-full border rounded px-3 py-2" id="typeSelect">
                                <option value="url">URL</option>
                                <option value="page">Page</option>
                                <option value="post_category">Post Category</option>
                                <option value="route">Route</option>
                            </select>
                        </div>
                    </div>

                    {{-- Type dynamic fields --}}
                    <div id="field_url" class="type-field">
                        <label class="block text-sm font-medium mb-1">URL</label>
                        <input name="url" class="w-full border rounded px-3 py-2" placeholder="https://...">
                    </div>

                    <div id="field_page" class="type-field hidden">
                        <label class="block text-sm font-medium mb-1">Select Page</label>
                        <select name="page_id" class="w-full border rounded px-3 py-2">
                            <option value="">— Select —</option>
                            @foreach ($pages as $p)
                                <option value="{{ $p->id }}">{{ $p->title }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div id="field_post_category" class="type-field hidden">
                        <label class="block text-sm font-medium mb-1">Post Category ID</label>
                        <input name="post_category_id" type="number" class="w-full border rounded px-3 py-2"
                            placeholder="(later select from categories)">
                    </div>

                    <div id="field_route" class="type-field hidden">
                        <label class="block text-sm font-medium mb-1">Route Name</label>
                        <input name="route_name" class="w-full border rounded px-3 py-2"
                            placeholder="e.g. home / client.notice.index">
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Position</label>
                            <input name="position" type="number" value="0" class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="flex items-center gap-2 pt-7">
                            <input type="checkbox" name="open_new_tab" value="1" class="h-4 w-4">
                            <label class="text-sm">New Tab</label>
                        </div>

                        <div class="flex items-center gap-2 pt-7">
                            <input type="checkbox" name="status" value="1" class="h-4 w-4" checked>
                            <label class="text-sm">Active</label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button class="px-5 py-2 rounded bg-slate-800 text-white hover:bg-slate-900">
                            Add Item
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Tree list --}}
        <div class="lg:col-span-6">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-4 py-3 border-b font-semibold">Menu Items</div>

                <div class="p-4">
                    @if (empty($tree) || count($tree) === 0)
                        <div class="text-sm text-gray-500">No items yet.</div>
                    @else
                        @include('admin.menus._tree', ['nodes' => $tree, 'level' => 0])
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const sel = document.getElementById('typeSelect');
                const map = {
                    url: document.getElementById('field_url'),
                    page: document.getElementById('field_page'),
                    post_category: document.getElementById('field_post_category'),
                    route: document.getElementById('field_route'),
                };

                function update() {
                    Object.values(map).forEach(el => el.classList.add('hidden'));
                    map[sel.value]?.classList.remove('hidden');
                }
                sel?.addEventListener('change', update);
                update();
            })();
        </script>
    @endpush
@endsection
