@php $isEdit = isset($menu); @endphp

<div class="grid gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Menu Name</label>
        <input name="name" value="{{ old('name', $menu->name ?? '') }}"
            class="w-full border rounded px-3 py-2 @error('name') border-rose-400 @enderror">
        @error('name')
            <div class="text-rose-600 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Location</label>
            <select name="location" class="w-full border rounded px-3 py-2">
                <option value="header" @selected(old('location', $menu->location ?? 'header') === 'header')>header</option>
                <option value="footer" @selected(old('location', $menu->location ?? '') === 'footer')>footer</option>
            </select>
        </div>
        <div class="flex items-center gap-2 pt-7">
            <input type="checkbox" name="status" value="1" class="h-4 w-4" @checked(old('status', $menu->status ?? true))>
            <label class="text-sm font-medium">Active</label>
        </div>
    </div>
</div>
