@extends('tailwind.layouts.admin')
@section('title', 'Edit Menu Item')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold">Edit Menu Item</h2>
        <a href="{{ route('admin.menus.builder', $menu) }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <form method="POST" action="{{ route('admin.menu-items.update', $menuItem) }}" class="space-y-4" id="editItemForm">
            @csrf
            @method('PUT')

            <input type="hidden" name="menu_id" value="{{ $menu->id }}" />

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Label (BN)</label>
                    <input name="label_bn" value="{{ old('label_bn', $menuItem->label_bn) }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Label (EN)</label>
                    <input name="label_en" value="{{ old('label_en', $menuItem->label_en) }}"
                        class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Parent (optional)</label>
                    <select name="parent_id" class="w-full border rounded px-3 py-2">
                        <option value="">— Root —</option>
                        @foreach ($allItems as $it)
                            <option value="{{ $it->id }}" @selected(old('parent_id', $menuItem->parent_id) == $it->id)>
                                {{ $it->label_bn }} (id:{{ $it->id }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select name="type" class="w-full border rounded px-3 py-2" id="typeSelect">
                        <option value="url" @selected(old('type', $menuItem->type) === 'url')>URL</option>
                        <option value="page" @selected(old('type', $menuItem->type) === 'page')>Page</option>
                        <option value="post_category" @selected(old('type', $menuItem->type) === 'post_category')>Post Category</option>
                        <option value="route" @selected(old('type', $menuItem->type) === 'route')>Route</option>
                    </select>
                </div>
            </div>

            <div id="field_url" class="type-field">
                <label class="block text-sm font-medium mb-1">URL</label>
                <input name="url" value="{{ old('url', $menuItem->url) }}" class="w-full border rounded px-3 py-2">
            </div>

            <div id="field_page" class="type-field hidden">
                <label class="block text-sm font-medium mb-1">Select Page</label>
                <select name="page_id" class="w-full border rounded px-3 py-2">
                    <option value="">— Select —</option>
                    @foreach ($pages as $p)
                        <option value="{{ $p->id }}" @selected(old('page_id', $menuItem->page_id) == $p->id)>{{ $p->title }}</option>
                    @endforeach
                </select>
            </div>


            <div id="field_post_category" class="type-field hidden">
                <label class="block text-sm font-medium mb-1">Post Category ID</label>
                <input name="post_category_id" type="number"
                    value="{{ old('post_category_id', $menuItem->post_category_id) }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div id="field_route" class="type-field hidden">
                <label class="block text-sm font-medium mb-1">Route Name</label>
                <input name="route_name" value="{{ old('route_name', $menuItem->route_name) }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Position</label>
                    <input name="position" type="number" value="{{ old('position', $menuItem->position) }}"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex items-center gap-2 pt-7">
                    <input type="checkbox" name="open_new_tab" value="1" class="h-4 w-4" @checked(old('open_new_tab', $menuItem->open_new_tab))>
                    <label class="text-sm">New Tab</label>
                </div>

                <div class="flex items-center gap-2 pt-7">
                    <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $menuItem->status))>
                    <label class="text-sm">Active</label>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button class="px-5 py-2 rounded bg-indigo-600 text-white">Update</button>
                <a href="{{ route('admin.menus.builder', $menu) }}" class="px-5 py-2 rounded border">Cancel</a>
            </div>
        </form>
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
