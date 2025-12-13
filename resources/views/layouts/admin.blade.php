<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Admin' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="min-h-screen flex">
        <aside class="w-72 shrink-0 bg-white border-r border-slate-200">
            <div class="p-6">
                <div class="flex flex-col items-center">
                    <div class="w-28 h-28 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center">
                        <svg class="w-16 h-16 text-slate-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.42 0-8 2-8 4.5V21h16v-2.5C20 16 16.42 14 12 14Z"/>
                        </svg>
                    </div>

                    <div class="mt-3 text-center">
                        <div class="font-semibold">{{ auth()->user()->name }}</div>
                        @if(app()->bound('tenant') && app('tenant'))
                            <div class="text-xs text-slate-500">{{ app('tenant')->name }}</div>
                        @endif
                    </div>
                </div>

                <nav class="mt-6 space-y-2">
                    @php
                        $links = [
                            ['label' => 'প্রতিষ্ঠান পরিচিতি', 'route' => 'admin.institute.edit'],
                            ['label' => 'নোটিশবোর্ড', 'route' => 'admin.notices'],
                            ['label' => 'মেম্বার', 'route' => 'admin.members'],
                            ['label' => 'পেজ তৈরি করুন', 'route' => 'admin.pages'],
                            ['label' => 'পোস্ট তৈরি করুন', 'route' => 'admin.posts'],
                            ['label' => 'ব্যানার তৈরি করুন', 'route' => 'admin.banners'],
                            ['label' => 'ইভেন্ট', 'route' => 'admin.events'],
                            ['label' => 'আমাদের অর্জন', 'route' => 'admin.achievements'],
                            ['label' => 'ইমেজ গ্যালারি', 'route' => 'admin.image_gallery'],
                            ['label' => 'ভিডিও গ্যালারি', 'route' => 'admin.video_gallery'],
                            ['label' => 'সাইডবার ম্যানেজ', 'route' => 'admin.sidebar'],
                            ['label' => 'ফুটার ম্যানেজ', 'route' => 'admin.footer'],
                        ];
                    @endphp

                    @foreach($links as $l)
                        @php $active = request()->routeIs($l['route']); @endphp
                        <a href="{{ route($l['route']) }}"
                           class="block rounded-lg px-4 py-2 font-semibold transition
                                  {{ $active ? 'bg-slate-800 text-white' : 'bg-sky-600 text-white hover:bg-sky-700' }}">
                            {{ $l['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        <main class="flex-1">
            <header class="bg-white border-b border-slate-200">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="rounded-full bg-sky-600 text-white px-5 py-2 font-semibold shadow">
                        {{ $header ?? 'ড্যাশবোর্ড' }}
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-full bg-sky-600 text-white px-5 py-2 font-semibold shadow hover:bg-sky-700">
                            সাইন আউট করুন
                        </button>
                    </form>
                </div>
            </header>

            <div class="p-6">
                @if (session('success'))
                    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
