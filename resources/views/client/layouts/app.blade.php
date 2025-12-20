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

    {{-- TOP INFO BAR --}}
    <div class="bg-[#0b5c6b] text-white">
        <div class="max-w-6xl mx-auto px-3 py-2 text-xs flex flex-wrap gap-x-6 gap-y-1 items-center justify-between">
            <div class="flex flex-wrap gap-x-6 gap-y-1">
                <div>ইআইআইএনঃ <span class="font-semibold">{{ $institute->eiin ?? '—' }}</span></div>
                <div>স্কুল কোডঃ <span class="font-semibold">{{ $institute->school_code ?? '—' }}</span></div>
                <div>কলেজ কোডঃ <span class="font-semibold">{{ $institute->college_code ?? '—' }}</span></div>
            </div>
            <div class="flex flex-wrap gap-x-6 gap-y-1">
                <div>ফোনঃ <span class="font-semibold">{{ $institute->phone_1 ?? ($institute->phone_2 ?? '—') }}</span>
                </div>
                <div>মোবাইলঃ <span
                        class="font-semibold">{{ $institute->mobile_1 ?? ($institute->mobile_2 ?? '—') }}</span></div>
            </div>
        </div>
    </div>

    {{-- HEADER --}}
    <div class="bg-[#0a5160] text-white">
        <div class="max-w-6xl mx-auto px-3 py-4 flex items-center gap-3">
            <div class="w-14 h-14 rounded bg-white/10 flex items-center justify-center overflow-hidden">
                <span class="text-2xl font-bold">🏫</span>
            </div>
            <div class="leading-tight">
                <div class="text-2xl font-bold">{{ $institute->name ?? 'Institute Name' }}</div>
                <div class="text-sm text-white/80">{{ $institute->slogan ?? ($institute->address ?? '') }}</div>
            </div>
        </div>

        {{-- NAV MENU --}}
        <div class="border-t border-white/10 bg-[#083f4b]">
            <div class="max-w-6xl mx-auto px-3">
                <ul class="flex flex-wrap items-center gap-2 py-2 text-white text-sm">
                    @foreach ($menuTree as $node)
                        @include('client.partials.menu-tree', ['node' => $node])
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

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
</body>

</html>
