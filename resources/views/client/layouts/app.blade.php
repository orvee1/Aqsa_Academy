<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Home') - {{ $institute->name ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-slate-100 text-slate-900">

    {{-- TOP THIN BAR (screenshot style) --}}
    <div class="bg-[#d2c56a] text-slate-900">
        <div class="max-w-6xl mx-auto px-3 py-1 text-xs flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap items-center gap-4">
                <div>School Code: <span class="font-semibold">{{ $institute->school_code ?? '—' }}</span></div>
                <div>EIIN: <span class="font-semibold">{{ $institute->eiin ?? '—' }}</span></div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1">
                    <span>🕒</span> <span>{{ now()->format('h:i:s A') }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <span>📅</span> <span>{{ now()->format('d/m/Y') }}</span>
                </div>

                <a href="{{ config('app.online_apply_url') ?? '#' }}"
                    class="bg-[#0a5160] text-white px-3 py-1 rounded hover:bg-[#083f4b]">
                    অনলাইনে আবেদন
                </a>
            </div>
        </div>
    </div>

    {{-- BIG HEADER (logo + title over banner) --}}
    <div class="bg-[#0a5160] text-white">
        <div class="max-w-6xl mx-auto px-3 py-3 grid md:grid-cols-12 gap-3 items-center">
            <div class="md:col-span-2 flex items-center gap-3">
                <div class="w-16 h-16 rounded bg-white/10 overflow-hidden flex items-center justify-center">
                    @if (!empty($institute->logo_path))
                        <img src="{{ asset('storage/' . $institute->logo_path) }}" class="w-full h-full object-cover"
                            alt="logo">
                    @else
                        <span class="text-2xl">🏫</span>
                    @endif
                </div>
            </div>

            <div class="md:col-span-10 relative overflow-hidden rounded">
                {{-- banner --}}
                <div class="h-20 md:h-24 bg-[#083f4b]">
                    @if (!empty($institute->header_banner_path))
                        <img src="{{ asset('storage/' . $institute->header_banner_path) }}"
                            class="w-full h-full object-cover opacity-70" alt="">
                    @endif
                </div>

                {{-- overlay title --}}
                <div class="absolute inset-0 flex items-center px-3">
                    <div class="leading-tight">
                        <div class="text-xl md:text-2xl font-bold">{{ $institute->name ?? 'Institute Name' }}</div>
                        <div class="text-sm text-white/80">{{ $institute->slogan ?? ($institute->address ?? '') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- NAV MENU --}}
        <div class="bg-[#083f4b] border-t border-white/10">
            <div class="max-w-6xl mx-auto px-3">
                <ul class="flex flex-wrap items-center gap-1 py-2 text-white text-sm">
                    @foreach ($menuTree as $node)
                        @include('client.partials.menu-tree', ['node' => $node])
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    {{-- MARQUEE NOTICE STRIP --}}
    @php
        // HomeController থেকে $notices পাঠানো হচ্ছে—তাই layout এ সব পেজে না চাইলে
        // শুধু home view এর উপরে বসাও; নাহলে BaseClientController common এ "latestNotices" যোগ করো।
    @endphp

    @if (!empty($notices) && $notices->count())
        <div class="bg-white border-b">
            <div class="max-w-6xl mx-auto px-3 py-2 text-sm flex gap-3 items-center">
                <span class="bg-[#0a5160] text-white px-3 py-1 rounded text-xs font-semibold">নোটিশ</span>
                <marquee behavior="scroll" direction="left" scrollamount="5">
                    @foreach ($notices->take(8) as $n)
                        <a class="mr-8 hover:underline text-slate-700"
                            href="{{ route('client.notices.show', $n->slug) }}">
                            {{ $n->title }}
                        </a>
                    @endforeach
                </marquee>
            </div>
        </div>
    @endif
    {{-- PAGE CONTENT --}}
    <main class="max-w-6xl mx-auto px-3 py-5">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @php
        use Illuminate\Support\Str;

        $fs = $footer ?? ($footerSetting ?? null);

        // $footerLinks আপনার project এ group করে পাঠানো হচ্ছে (layout এ already)
        // $socialLinks array/collection
        $about = $fs->about ?? null;
        $address = $fs->address ?? ($institute->address ?? null);
        $map = $fs->map_embed ?? null;
    @endphp

    <footer class="bg-[#0a5160] text-white mt-6">
        <div class="max-w-6xl mx-auto px-3 py-8 grid md:grid-cols-12 gap-6">

            <div class="md:col-span-4">
                <div class="font-semibold mb-2">যোগাযোগ</div>
                <div class="text-sm text-white/80 leading-6">
                    {!! nl2br(e($address ?? '—')) !!}
                </div>
                <div class="text-sm text-white/80 mt-2">
                    <div>ফোন: {{ $fs->phone ?? ($institute->phone_1 ?? '—') }}</div>
                    <div>ইমেইল: {{ $fs->email ?? '—' }}</div>
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
                                        href="{{ $l->url }}" target="_blank">
                                        {{ $l->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($socialLinks ?? [] as $s)
                        <a class="px-3 py-1 border border-white/10 rounded text-xs hover:bg-white/10" target="_blank"
                            href="{{ $s->url }}">
                            {{ $s->platform }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="md:col-span-4">
                <div class="font-semibold mb-2">ম্যাপ</div>
                <div class="bg-white/10 rounded overflow-hidden">
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

            <div
                class="md:col-span-12 border-t border-white/10 pt-4 text-xs text-white/70 flex flex-wrap justify-between gap-2">
                <div>{!! $fs->copyright_text ?? '© ' . date('Y') . ' ' . ($institute->name ?? config('app.name')) !!}</div>
                <div>Powered by {{ config('app.name') }}</div>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        function toggleMenu(btn) {
            const li = btn.closest('li');
            const submenu = li.querySelector(':scope > ul.submenu');
            if (submenu) {
                submenu.classList.toggle('hidden');
            }
        }
    </script>

</body>

</html>
