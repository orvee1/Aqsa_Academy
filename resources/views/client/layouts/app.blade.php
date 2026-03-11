<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Home') - {{ $institute?->name ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        body {
            background-color: #f4f4f4;
            background-image:
                radial-gradient(rgba(0, 0, 0, 0.04) 1px, transparent 1px),
                radial-gradient(rgba(0, 0, 0, 0.02) 1px, transparent 1px);
            background-size: 18px 18px, 26px 26px;
            background-position: 0 0, 13px 13px;
        }

        .gov-container {
            max-width: 1180px;
            margin: 0 auto;
        }
    </style>
</head>

<body class="text-slate-900">

    @php
        $logo = !empty($institute?->logo_path) ? asset('storage/' . $institute->logo_path) : null;
        $banner = !empty($institute?->header_banner_path) ? asset('storage/' . $institute->header_banner_path) : null;
        $applyUrl = $institute?->online_apply_url ?? null;
    @endphp

    <div class="gov-container bg-white shadow-sm">

        {{-- Top Bar --}}
        <div class="bg-[#0b8f3a] text-white text-xs">
            <div class="px-3 py-2 flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-4">
                    <span>জাতীয় তথ্য বাতায়ন</span>
                    <span>স্কুল কোড: <strong>{{ $institute?->school_code ?? '—' }}</strong></span>
                    <span>EIIN: <strong>{{ $institute?->eiin ?? '—' }}</strong></span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-1">
                        <span>📅</span>
                        <span id="live-date">--/--/----</span>
                    </div>
                    <div class="hidden sm:flex items-center gap-1">
                        <span>🕒</span>
                        <span id="live-time">--:--:--</span>
                    </div>

                    @if ($applyUrl)
                        <a href="{{ $applyUrl }}" target="_blank" rel="noopener"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium">
                            আবেদন
                        </a>
                    @endif

                    <a href="#" class="hover:underline">English</a>
                </div>
            </div>
        </div>

        {{-- Header --}}
        <div class="border-x border-b border-slate-300 overflow-hidden">
            <div class="relative h-[170px] md:h-[250px] bg-slate-700">
                @if ($banner)
                    <img src="{{ $banner }}" class="w-full h-full object-cover" alt="banner">
                @else
                    <div class="w-full h-full bg-gradient-to-r from-slate-900 via-slate-700 to-slate-800"></div>
                @endif

                <div class="absolute inset-0 bg-slate-900/45"></div>

                <div class="absolute inset-0 px-4 md:px-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white overflow-hidden flex items-center justify-center border-4 border-white shadow shrink-0">
                            @if ($logo)
                                <img src="{{ $logo }}" class="w-full h-full object-cover" alt="logo">
                            @else
                                <span class="text-3xl">🏫</span>
                            @endif
                        </div>

                        <div class="text-white">
                            <div class="text-2xl md:text-5xl font-bold leading-tight">
                                {{ $institute?->name ?? 'প্রতিষ্ঠানের নাম' }}
                            </div>
                            <div class="mt-1 text-sm md:text-xl text-white/90">
                                {{ $institute?->slogan ?? 'শিক্ষা, শৃঙ্খলা, উন্নয়ন' }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="hidden md:flex w-24 h-24 md:w-36 md:h-36 bg-white/90 rounded shadow items-center justify-center overflow-hidden">
                        @if ($logo)
                            <img src="{{ $logo }}" class="w-full h-full object-contain p-3" alt="logo">
                        @else
                            <span class="text-5xl">📘</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Nav --}}
            <div class="bg-[#06752f] text-white">
                <div class="px-3">
                    <ul class="flex flex-wrap items-center gap-1 text-sm py-2">
                        <li>
                            <a href="{{ route('client.home') }}"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded hover:bg-white/10">
                                <span>🏠</span>
                                <span>হোম</span>
                            </a>
                        </li>

                        @foreach ($menuTree ?? [] as $node)
                            @include('client.partials.menu-tree', ['node' => $node])
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Marquee --}}
        @if (isset($notices) && $notices->count())
            <div class="border-x border-b border-slate-300 bg-white">
                <div class="px-3 py-2 flex items-center gap-3">
                    <span class="bg-red-500 text-white text-xs px-3 py-1 rounded font-semibold whitespace-nowrap">
                        জরুরি নোটিশ
                    </span>

                    <div class="flex-1 overflow-hidden">
                        <marquee behavior="scroll" direction="left" scrollamount="5" onmouseover="this.stop();"
                            onmouseout="this.start();">
                            @foreach ($notices->take(10) as $n)
                                <a class="mr-10 text-sm text-slate-700 hover:underline"
                                    href="{{ route('client.notices.show', $n->slug) }}">
                                    {{ $n->title }}
                                </a>
                            @endforeach
                        </marquee>
                    </div>
                </div>
            </div>
        @endif

        <main class="bg-[#f1f1f1] border-x border-b border-slate-300 px-3 py-4">
            @yield('content')
        </main>

        @php
            use Illuminate\Support\Str;

            $fs = $footer ?? null;
            $address = $fs?->address ?? ($institute?->address ?? null);
            $map = $fs?->map_embed ?? null;
            $about = $fs?->about ?? null;
        @endphp

        <footer class="bg-[#ececec] border-x border-b border-slate-300 mt-6">
            <div class="border-t-[5px] border-[#7b7b7b]"></div>

            <div class="px-4 md:px-5 py-6 md:py-7">
                <div class="grid md:grid-cols-12 gap-6 items-start">

                    {{-- Left --}}
                    <div class="md:col-span-3">
                        <h3 class="text-[15px] font-bold text-slate-900 mb-3">সাইট সম্পর্কিত</h3>

                        <ul class="space-y-2 text-sm text-slate-700">
                            <li>
                                <a href="#" class="hover:text-emerald-700 hover:underline">যোগাযোগ</a>
                            </li>
                            <li>
                                <a href="#" class="hover:text-emerald-700 hover:underline">ফিডব্যাক</a>
                            </li>
                            <li>
                                <a href="#" class="hover:text-emerald-700 hover:underline">লোকেশন ম্যাপ</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Middle --}}
                    <div class="md:col-span-4">
                        <h3 class="text-[15px] font-bold text-slate-900 mb-3">যোগাযোগ</h3>

                        <div class="text-sm text-slate-700 leading-6">
                            <div>{!! nl2br(e($address ?? '—')) !!}</div>

                            <div class="mt-2">
                                <div>ফোন: {{ $fs?->phone ?? ($institute?->phone_1 ?? '—') }}</div>
                                <div>ইমেইল: {{ $fs?->email ?? '—' }}</div>
                            </div>
                        </div>

                        @if ($about)
                            <div class="mt-3 text-xs text-slate-600 leading-5">
                                {{ Str::limit(strip_tags($about), 120, '...') }}
                            </div>
                        @endif
                    </div>

                    {{-- Right --}}
                    <div class="md:col-span-5">
                        <h3 class="text-[15px] font-bold text-slate-900 mb-3">ম্যাপ</h3>

                        <div class="bg-white border border-slate-300 rounded-sm overflow-hidden">
                            @if ($map)
                                <div class="w-full [&_iframe]:w-full [&_iframe]:h-[180px]">{!! $map !!}</div>
                            @else
                                <div
                                    class="h-[180px] flex items-center justify-center text-sm text-slate-500 bg-slate-50">
                                    Map not set
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#d4c567] border-t border-black/10">
                <div
                    class="px-4 md:px-5 py-3 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-slate-900">
                    <div>
                        {!! $fs?->copyright_text ?? '© ' . date('Y') . ' ' . ($institute?->name ?? config('app.name')) !!}
                    </div>

                    <div class="font-medium">
                        Powered by {{ config('app.name') }}
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')

    <script>
        function toggleMenu(btn) {
            const li = btn.closest('li');
            if (!li) return;

            const submenu = li.querySelector(':scope > ul.submenu');
            if (!submenu) return;

            submenu.classList.toggle('hidden');
        }

        (function() {
            const timeEl = document.getElementById('live-time');
            const dateEl = document.getElementById('live-date');
            if (!timeEl || !dateEl) return;

            const tz = 'Asia/Dhaka';

            function tick() {
                const now = new Date();

                timeEl.textContent = new Intl.DateTimeFormat('en-GB', {
                    timeZone: tz,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                }).format(now);

                dateEl.textContent = new Intl.DateTimeFormat('en-GB', {
                    timeZone: tz,
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).format(now);
            }

            tick();
            setInterval(tick, 1000);
        })();
    </script>
</body>

</html>
