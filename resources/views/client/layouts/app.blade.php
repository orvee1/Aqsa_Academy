<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Home') - {{ $institute?->name ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Screenshot-like patterned background + centered page shell --}}
    <style>
        /* Patterned page background (no image required) */
        body {
            background-color: #e7e3d2;
            background-image:
                radial-gradient(rgba(0, 0, 0, 0.05) 1px, transparent 1px),
                radial-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px);
            background-size: 18px 18px, 24px 24px;
            background-position: 0 0, 10px 10px;
        }
    </style>
</head>

<body class="text-slate-900">

    {{-- PAGE SHELL (center area like screenshot) --}}
    <div class="max-w-6xl mx-auto">

        {{-- TOP THIN BAR (yellow like screenshot) --}}
        <div class="bg-[#d2c56a] text-slate-900 border-b border-black/10">
            <div class="px-3 py-1 text-xs flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-4">
                    <div>Madrasah/School Code:
                        <span
                            class="font-semibold">{{ $institute?->school_code ?? ($institute?->madrasah_code ?? '—') }}</span>
                    </div>
                    <div>EIIN:
                        <span class="font-semibold">{{ $institute?->eiin ?? '—' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        <span>🕒</span>
                        <span id="live-time" class="font-semibold">--:--:--</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span>📅</span>
                        <span id="live-date" class="font-semibold">--/--/----</span>
                    </div>

                    {{-- Optional apply button (only show if you have url) --}}
                    @php $applyUrl = $institute?->online_apply_url ?? null; @endphp
                    @if ($applyUrl)
                        <a href="{{ $applyUrl }}"
                            class="bg-[#0a5160] hover:bg-[#083f4b] text-white px-3 py-1 rounded text-xs">
                            অনলাইনে আবেদন
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- BIG HEADER (teal) --}}
        <div class="bg-[#0a5160] text-white border-x border-black/10 border-b border-black/10">
            <div class="px-3 py-3 grid md:grid-cols-12 gap-3 items-center">

                {{-- Logo --}}
                <div class="md:col-span-2 flex items-center gap-3">
                    <div
                        class="w-16 h-16 rounded bg-white/10 overflow-hidden flex items-center justify-center border border-white/10">
                        @if (!empty($institute?->logo_path))
                            <img src="{{ asset('storage/' . $institute->logo_path) }}"
                                class="w-full h-full object-cover" alt="logo">
                        @else
                            <span class="text-2xl">🏫</span>
                        @endif
                    </div>
                </div>

                {{-- Banner area --}}
                <div class="md:col-span-10 relative overflow-hidden rounded border border-white/10">
                    <div class="h-20 md:h-24 bg-[#083f4b]">
                        @if (!empty($institute?->header_banner_path))
                            <img src="{{ asset('storage/' . $institute->header_banner_path) }}"
                                class="w-full h-full object-cover opacity-80" alt="">
                        @else
                            {{-- fallback simple gradient --}}
                            <div class="w-full h-full bg-gradient-to-r from-[#083f4b] to-[#0a5160] opacity-90"></div>
                        @endif
                    </div>

                    <div class="absolute inset-0 flex items-center px-3">
                        <div class="leading-tight drop-shadow">
                            <div class="text-xl md:text-2xl font-bold">
                                {{ $institute?->name ?? 'Institute Name' }}
                            </div>
                            <div class="text-sm text-white/80">
                                {{ $institute?->slogan ?? ($institute?->address ?? '') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- NAV MENU (deep teal like screenshot) --}}
            <div class="bg-[#083f4b] border-t border-white/10">
                <div class="px-3">
                    <ul class="flex flex-wrap items-center gap-1 py-2 text-white text-sm">
                        @foreach ($menuTree ?? [] as $node)
                            @include('client.partials.menu-tree', ['node' => $node])
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- MARQUEE NOTICE STRIP --}}
        {{-- Safe: only show when $notices exists --}}
        @if (isset($notices) && !empty($notices) && $notices->count())
            <div class="bg-white border-x border-black/10 border-b border-black/10">
                <div class="px-3 py-2 text-sm flex gap-3 items-center">
                    <span class="bg-[#0a5160] text-white px-3 py-1 rounded text-xs font-semibold">
                        নোটিশ
                    </span>
                    <marquee behavior="scroll" direction="left" scrollamount="5">
                        @foreach ($notices->take(10) as $n)
                            <a class="mr-8 hover:underline text-slate-700"
                                href="{{ route('client.notices.show', $n->slug) }}">
                                {{ $n->title }}
                            </a>
                        @endforeach
                    </marquee>
                </div>
            </div>
        @endif

        {{-- PAGE CONTENT (light blue body area like screenshot) --}}
        <main class="bg-[#dbeafe] border-x border-black/10 border-b border-black/10 px-3 py-5">
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

        <footer class="bg-[#0a5160] text-white border-x border-black/10 border-b border-black/10">
            <div class="px-3 py-8 grid md:grid-cols-12 gap-6">

                <div class="md:col-span-4">
                    <div class="font-semibold mb-2">যোগাযোগ</div>
                    <div class="text-sm text-white/80 leading-6">
                        {!! nl2br(e($address ?? '—')) !!}
                    </div>
                    <div class="text-sm text-white/80 mt-2">
                        <div>ফোন: {{ $fs?->phone ?? ($institute?->phone_1 ?? '—') }}</div>
                        <div>ইমেইল: {{ $fs?->email ?? '—' }}</div>
                    </div>
                </div>

                <div class="md:col-span-4">
                    <div class="font-semibold mb-2">লিংক</div>

                    <div class="grid grid-cols-2 gap-4">
                        @foreach ($footerLinks ?? collect() as $group => $links)
                            <div>
                                <div class="text-sm font-medium text-white/90 mb-2">{{ $group }}</div>
                                <div class="space-y-1 text-sm">
                                    @foreach ($links as $l)
                                        <a class="block text-white/80 hover:text-white hover:underline"
                                            href="{{ $l->url }}" target="_blank" rel="noopener">
                                            {{ $l->title }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($socialLinks ?? [] as $s)
                            <a class="px-3 py-1 border border-white/10 rounded text-xs hover:bg-white/10"
                                target="_blank" rel="noopener" href="{{ $s->url }}">
                                {{ $s->platform }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="md:col-span-4">
                    <div class="font-semibold mb-2">ম্যাপ</div>
                    <div class="bg-white/10 rounded overflow-hidden border border-white/10">
                        @if ($map)
                            <div class="w-full [&_iframe]:w-full [&_iframe]:h-40">{!! $map !!}</div>
                        @else
                            <div class="h-40 flex items-center justify-center text-white/70 text-sm">Map not set</div>
                        @endif
                    </div>

                    @if ($about)
                        <div class="mt-4 text-sm text-white/80 leading-6">
                            {{ Str::limit(strip_tags($about), 220, '...') }}
                        </div>
                    @endif
                </div>

            </div>

            {{-- Bottom bar (yellow-ish like screenshot footer strip) --}}
            <div class="bg-[#d2c56a] text-slate-900 border-t border-black/10">
                <div class="px-3 py-2 text-xs flex flex-wrap justify-between gap-2">
                    <div>
                        {!! $fs?->copyright_text ?? '© ' . date('Y') . ' ' . ($institute?->name ?? config('app.name')) !!}
                    </div>
                    <div>Powered by {{ config('app.name') }}</div>
                </div>
            </div>
        </footer>

    </div> {{-- /max-w-6xl shell --}}

    @stack('scripts')

    <script>
        // Dropdown toggle (works if submenu ul has class="submenu")
        function toggleMenu(btn) {
            const li = btn.closest('li');
            if (!li) return;
            const submenu = li.querySelector(':scope > ul.submenu');
            if (submenu) submenu.classList.toggle('hidden');
        }

        // Live time/date (Asia/Dhaka)
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
