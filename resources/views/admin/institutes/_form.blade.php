@php $isEdit = isset($institute); @endphp

<div class="grid gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input name="name" value="{{ old('name', $institute->name ?? '') }}"
               class="w-full border rounded px-3 py-2 @error('name') border-rose-400 @enderror">
        @error('name') <div class="text-rose-600 text-xs mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Slogan</label>
            <input name="slogan" value="{{ old('slogan', $institute->slogan ?? '') }}"
                   class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Address</label>
            <input name="address" value="{{ old('address', $institute->address ?? '') }}"
                   class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">EIIN</label>
            <input name="eiin" value="{{ old('eiin', $institute->eiin ?? '') }}"
                   class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">School Code</label>
            <input name="school_code" value="{{ old('school_code', $institute->school_code ?? '') }}"
                   class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">College Code</label>
            <input name="college_code" value="{{ old('college_code', $institute->college_code ?? '') }}"
                   class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Phone 1</label>
            <input name="phone_1" value="{{ old('phone_1', $institute->phone_1 ?? '') }}"
                   class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Phone 2</label>
            <input name="phone_2" value="{{ old('phone_2', $institute->phone_2 ?? '') }}"
                   class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Mobile 1</label>
            <input name="mobile_1" value="{{ old('mobile_1', $institute->mobile_1 ?? '') }}"
                   class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Mobile 2</label>
            <input name="mobile_2" value="{{ old('mobile_2', $institute->mobile_2 ?? '') }}"
                   class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Link 1</label>
            <input name="link_1" value="{{ old('link_1', $institute->link_1 ?? '') }}"
                   class="w-full border rounded px-3 py-2" placeholder="https://...">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Link 2</label>
            <input name="link_2" value="{{ old('link_2', $institute->link_2 ?? '') }}"
                   class="w-full border rounded px-3 py-2" placeholder="https://...">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Link 3</label>
            <input name="link_3" value="{{ old('link_3', $institute->link_3 ?? '') }}"
                   class="w-full border rounded px-3 py-2" placeholder="https://...">
        </div>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="status" value="1" class="h-4 w-4"
               @checked(old('status', $institute->status ?? true))>
        <label class="text-sm font-medium">Active</label>
    </div>
</div>
