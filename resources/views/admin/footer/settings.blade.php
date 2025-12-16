@extends('tailwind.layouts.admin')
@section('title', 'Footer Settings')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Footer Settings</h2>
            <div class="text-sm text-gray-500">About + Contact + Map + Copyright</div>
        </div>
    </div>

    <div class="bg-white rounded shadow p-5">
        <form method="POST" action="{{ route('admin.footer.settings.update') }}">
            @csrf
            @method('PUT')

            <div class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">About (Footer)</label>
                    <textarea id="footerAbout" name="about" class="w-full border rounded px-3 py-2 min-h-[180px]">{{ old('about', $setting->about) }}</textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Address</label>
                        <textarea name="address" class="w-full border rounded px-3 py-2 min-h-[100px]">{{ old('address', $setting->address) }}</textarea>
                    </div>
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Phone</label>
                            <input name="phone" value="{{ old('phone', $setting->phone) }}"
                                class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input name="email" value="{{ old('email', $setting->email) }}"
                                class="w-full border rounded px-3 py-2">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Map Embed (iframe code)</label>
                    <textarea name="map_embed" class="w-full border rounded px-3 py-2 min-h-[120px]" placeholder="<iframe ...>">{{ old('map_embed', $setting->map_embed) }}</textarea>
                    <div class="text-xs text-gray-500 mt-1">Google map iframe embed code paste করুন।</div>

                    @if ($setting->map_embed)
                        <div class="mt-3 border rounded p-3 bg-slate-50">
                            <div class="text-xs text-gray-600 mb-2">Preview</div>
                            <div class="overflow-auto">{!! $setting->map_embed !!}</div>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Copyright Text</label>
                    <input name="copyright_text" value="{{ old('copyright_text', $setting->copyright_text) }}"
                        class="w-full border rounded px-3 py-2" placeholder="© 2025 Your Institute. All rights reserved.">
                </div>

                <div class="flex justify-end">
                    <button class="px-5 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Save</button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            (function() {
                const el = document.getElementById('footerAbout');
                if (!el) return;
                ClassicEditor.create(el).catch(console.error);
            })();
        </script>
    @endpush
@endsection
