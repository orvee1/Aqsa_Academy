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
                radial-gradient(rgba(0, 0, 0, 0.025) 1px, transparent 1px);
            background-size: 18px 18px, 26px 26px;
            background-position: 0 0, 13px 13px;
        }

        .gov-container {
            max-width: 1180px;
            margin: 0 auto;
        }

        .gov-green {
            background-color: #0b8f3a;
        }

        .gov-green-dark {
            background-color: #06752f;
        }

        .gov-yellow {
            background-color: #d7c86d;
        }

        .gov-card {
            @apply bg-white border border-slate-300 rounded-sm shadow-sm;
        }

        .gov-title {
            @apply bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2 rounded-t-sm;
        }

        .gov-link-list a {
            @apply block px-3 py-2 text-sm text-slate-700 border-b border-slate-200 hover:bg-slate-50 hover:text-emerald-700 transition;
        }

        .gov-mini-link-list a {
            @apply block text-sm text-slate-700 hover:text-emerald-700 hover:underline;
        }
    </style>
</head>

<body class="text-slate-900">

    <div class="gov-container bg-white shadow-sm">

        {{-- TOP THIN BAR --}}
        <div class="gov-green text-white text-xs">
            <div class="px-3 py-2 flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-4">
                    <span>জাতীয় তথ্য বাতায়ন</span>
                    <span>স্কুল কোড:
                        <strong>{{ $institute?->school_code ?? ($institute?->madrasah_code ?? '—') }}</strong>
                    </span>
                    <span>EIIN:
                        <strong>{{ $institute?->eiin ?? '—' }}</strong>
                    </span>
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

                    @php $applyUrl = $institute?->online_apply_url ?? null; @endphp
                    @if ($applyUrl)
                        <a href="{{ $applyUrl }}"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium">
                            আবেদন
                        </a>
                    @endif

                    <a href="#" class="hover:underline">English</a>
                </div>
            </div>
        </div>

        {{-- HEADER / HERO --}}
        <div class="relative border-x border-slate-300 border-b border-slate-300 overflow-hidden">
            <div class="relative h-[180px] md:h-[230px] bg-slate-700">
                @if (!empty($institute?->header_banner_path))
                    <img src="{{ asset('storage/' . $institute->header_banner_path) }}"
                        class="w-full h-full object-cover" alt="banner">
                @else
                    <div class="w-full h-full bg-gradient-to-r from-slate-700 via-slate-500 to-slate-700"></div>
                @endif

                <div class="absolute inset-0 bg-black/35"></div>

                <div class="absolute inset-0 px-4 md:px-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white border-4 border-white overflow-hidden shrink-0">
                            @if (!empty($institute?->logo_path))
                                <img src="{{ asset('storage/' . $institute->logo_path) }}"
                                    class="w-full h-full object-cover" alt="logo">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl">🏫</div>
                            @endif
                        </div>

                        <div class="text-white drop-shadow">
                            <div class="text-xl md:text-4xl font-bold leading-tight">
                                {{ $institute?->name ?? 'প্রতিষ্ঠানের নাম' }}
                            </div>
                            <div class="mt-1 text-sm md:text-base text-white/90">
                                {{ $institute?->slogan ?? ($institute?->address ?? 'শিক্ষা, শৃঙ্খলা, উন্নয়ন') }}
                            </div>
                        </div>
                    </div>

                    <div class="hidden md:block">
                        <div
                            class="w-24 h-24 md:w-32 md:h-32 bg-white/90 rounded shadow overflow-hidden flex items-center justify-center">
                            @if (!empty($institute?->logo_path))
                                <img src="{{ asset('storage/' . $institute->logo_path) }}"
                                    class="w-full h-full object-contain p-2" alt="logo">
                            @else
                                <span class="text-5xl">📘</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- NAVBAR --}}
            <div class="gov-green-dark text-white">
                <div class="px-3">
                    <ul class="flex flex-wrap items-center gap-1 text-sm py-2">
                        <li>
                            <a href="{{ url('/') }}"
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

        {{-- MARQUEE NOTICE --}}
        @if (isset($notices) && !empty($notices) && $notices->count())
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

        {{-- PAGE CONTENT --}}
        <main class="bg-[#f3f3f3] border-x border-b border-slate-300 px-3 py-4">
            @yield('content')
        </main>

        {{-- FOOTER --}}
        @php
            use Illuminate\Support\Str;

            $fs = $footer ?? ($footerSetting ?? null);
            $about = $fs?->about ?? null;
            $address = $fs?->address ?? ($institute?->address ?? null);
            $map = $fs?->map_embed ?? null;
        @endphp

        <footer class="bg-[#e9e9e9] border-x border-b border-slate-300">
            <div class="border-t-4 border-[#777]"></div>

            <div class="px-3 py-8">
                <div class="grid md:grid-cols-12 gap-6">
                    <div class="md:col-span-4">
                        <div class="font-semibold text-sm mb-3">সাইট সম্পর্কিত</div>
                        <div class="space-y-2 text-sm text-slate-700">
                            <div>যোগাযোগ</div>
                            <div>ফিডব্যাক</div>
                            <div>লোকেশন ম্যাপ</div>
                        </div>
                    </div>

                    <div class="md:col-span-4">
                        <div class="font-semibold text-sm mb-3">যোগাযোগ</div>
                        <div class="text-sm text-slate-700 leading-6">
                            {!! nl2br(e($address ?? '—')) !!}
                        </div>
                        <div class="mt-2 text-sm text-slate-700">
                            <div>ফোন: {{ $fs?->phone ?? ($institute?->phone_1 ?? '—') }}</div>
                            <div>ইমেইল: {{ $fs?->email ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="md:col-span-4">
                        <div class="font-semibold text-sm mb-3">ম্যাপ</div>
                        <div class="bg-white border rounded overflow-hidden">
                            @if ($map)
                                <div class="w-full [&_iframe]:w-full [&_iframe]:h-40">{!! $map !!}</div>
                            @else
                                <div class="h-40 flex items-center justify-center text-sm text-slate-500">
                                    Map not set
                                </div>
                            @endif
                        </div>

                        @if ($about)
                            <div class="mt-3 text-sm text-slate-600 leading-6">
                                {{ Str::limit(strip_tags($about), 180, '...') }}
                            </div>
                        @endif
                    </div>
                </div>

                @if (($footerLinks ?? collect())->count())
                    <div class="mt-6 grid md:grid-cols-4 gap-4">
                        @foreach ($footerLinks ?? collect() as $group => $links)
                            <div>
                                <div class="font-semibold text-sm mb-2">{{ $group }}</div>
                                <div class="space-y-1 text-sm">
                                    @foreach ($links as $l)
                                        <a class="block text-slate-700 hover:text-emerald-700 hover:underline"
                                            href="{{ $l->url }}" target="_blank" rel="noopener">
                                            {{ $l->title }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (!empty($socialLinks))
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach ($socialLinks as $s)
                            <a class="px-3 py-1 border border-slate-300 bg-white rounded text-xs hover:bg-slate-50"
                                target="_blank" rel="noopener" href="{{ $s->url }}">
                                {{ $s->platform }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="gov-yellow text-slate-900 text-xs px-3 py-3 flex flex-wrap items-center justify-between gap-2">
                <div>
                    {!! $fs?->copyright_text ?? '© ' . date('Y') . ' ' . ($institute?->name ?? config('app.name')) !!}
                </div>
                <div>Powered by {{ config('app.name') }}</div>
            </div>
        </footer>
    </div>

    @stack('scripts')

    <script>
        function toggleMenu(btn) {
            const li = btn.closest('li');
            if (!li) return;
            const submenu = li.querySelector(':scope > ul.submenu');
            if (submenu) submenu.classList.toggle('hidden');
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
