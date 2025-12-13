<x-admin-layout :title="'ড্যাশবোর্ড'" :header="'ড্যাশবোর্ড'">
    <div class="max-w-3xl">
        <div class="rounded-2xl bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold">প্রতিষ্ঠান পরিচিতি</h2>
                <a href="{{ route('admin.institute.edit') }}"
                   class="rounded-lg bg-slate-900 text-white px-4 py-2 font-semibold hover:bg-slate-800">
                    এডিট করুন
                </a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-3">
                @php
                    $rows = [
                        ['label' => 'প্রতিষ্ঠানের নাম', 'value' => $institute?->name],
                        ['label' => 'স্লোগান', 'value' => $institute?->slogan],
                        ['label' => 'ঠিকানা', 'value' => $institute?->address],
                        ['label' => 'ইআইআইএন', 'value' => $institute?->eiin],
                        ['label' => 'স্কুল কোড', 'value' => $institute?->school_code],
                        ['label' => 'কলেজ কোড', 'value' => $institute?->college_code],
                        ['label' => 'ফোন-১', 'value' => $institute?->phone_1],
                        ['label' => 'ফোন-২', 'value' => $institute?->phone_2],
                        ['label' => 'মোবাইল-১', 'value' => $institute?->mobile_1],
                        ['label' => 'মোবাইল-২', 'value' => $institute?->mobile_2],
                        ['label' => 'লিংক-১', 'value' => $institute?->link_1],
                        ['label' => 'লিংক-২', 'value' => $institute?->link_2],
                        ['label' => 'লিংক-৩', 'value' => $institute?->link_3],
                    ];
                @endphp

                @foreach($rows as $r)
                    <div class="grid grid-cols-12 items-center gap-3">
                        <div class="col-span-4 md:col-span-3 font-semibold text-slate-700">
                            {{ $r['label'] }}:
                        </div>
                        <div class="col-span-8 md:col-span-9">
                            <div class="flex items-center gap-2">
                                <input readonly
                                    value="{{ $r['value'] ?? '' }}"
                                    class="w-full rounded-full border border-slate-300 bg-white px-4 py-2 outline-none focus:ring-2 focus:ring-sky-200" />
                                <span class="inline-flex w-9 h-9 items-center justify-center rounded-full border border-slate-300 bg-white">
                                    <svg class="w-5 h-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-admin-layout>
