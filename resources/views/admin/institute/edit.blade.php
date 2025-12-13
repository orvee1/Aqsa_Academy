<x-admin-layout :title="'প্রতিষ্ঠান পরিচিতি'" :header="'ড্যাশবোর্ড'">
    <div class="max-w-3xl">
        <div class="rounded-2xl bg-white border border-slate-200 p-6">
            <h2 class="text-lg font-bold">প্রতিষ্ঠানের তথ্য আপডেট</h2>

            <form class="mt-6 space-y-4" method="POST" action="{{ route('admin.institute.update') }}">
                @csrf
                @method('PUT')

                @php
                    $fields = [
                        ['name' => 'name', 'label' => 'প্রতিষ্ঠানের নাম', 'placeholder' => 'প্রতিষ্ঠানের নাম লিখুন', 'required' => true],
                        ['name' => 'slogan', 'label' => 'স্লোগান', 'placeholder' => 'স্লোগান লিখুন'],
                        ['name' => 'address', 'label' => 'ঠিকানা', 'placeholder' => 'ঠিকানা লিখুন'],
                        ['name' => 'eiin', 'label' => 'ইআইআইএন', 'placeholder' => 'EIIN লিখুন'],
                        ['name' => 'school_code', 'label' => 'স্কুল কোড', 'placeholder' => 'স্কুল কোড লিখুন'],
                        ['name' => 'college_code', 'label' => 'কলেজ কোড', 'placeholder' => 'কলেজ কোড লিখুন'],
                        ['name' => 'phone_1', 'label' => 'ফোন-১', 'placeholder' => 'ফোন নম্বর'],
                        ['name' => 'phone_2', 'label' => 'ফোন-২', 'placeholder' => 'ফোন নম্বর'],
                        ['name' => 'mobile_1', 'label' => 'মোবাইল-১', 'placeholder' => 'মোবাইল নম্বর'],
                        ['name' => 'mobile_2', 'label' => 'মোবাইল-২', 'placeholder' => 'মোবাইল নম্বর'],
                        ['name' => 'link_1', 'label' => 'লিংক-১', 'placeholder' => 'https://...'],
                        ['name' => 'link_2', 'label' => 'লিংক-২', 'placeholder' => 'https://...'],
                        ['name' => 'link_3', 'label' => 'লিংক-৩', 'placeholder' => 'https://...'],
                    ];
                @endphp

                @foreach($fields as $f)
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            {{ $f['label'] }}
                            @if(!empty($f['required'])) <span class="text-rose-600">*</span> @endif
                        </label>

                        <input
                            name="{{ $f['name'] }}"
                            value="{{ old($f['name'], $institute->{$f['name']} ?? '') }}"
                            placeholder="{{ $f['placeholder'] }}"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2 outline-none focus:ring-2 focus:ring-sky-200 @error($f['name']) border-rose-400 @enderror"
                        />

                        @error($f['name'])
                            <div class="mt-1 text-sm text-rose-600">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach

                <div class="pt-2 flex items-center gap-3">
                    <button type="submit" class="rounded-xl bg-sky-600 text-white px-5 py-2 font-semibold hover:bg-sky-700">
                        সেভ করুন
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-300 bg-white px-5 py-2 font-semibold hover:bg-slate-50">
                        বাতিল
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
