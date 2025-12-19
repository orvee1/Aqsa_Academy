<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Home') - {{ $institute->name ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">

    {{-- top info --}}
    <div class="bg-slate-800 text-white text-xs">
        <div class="max-w-6xl mx-auto px-3 py-2 flex flex-wrap gap-x-4 gap-y-1 items-center justify-between">
            <div class="opacity-90">
                EIIN: {{ $institute->eiin ?? '—' }} |
                School Code: {{ $institute->school_code ?? '—' }} |
                College Code: {{ $institute->college_code ?? '—' }}
            </div>
            <div class="opacity-90">
                Phone: {{ $institute->phone_1 ?? '—' }} |
                Mobile: {{ $institute->mobile_1 ?? '—' }}
            </div>
        </div>
    </div>

    {{-- header --}}
    <div class="bg-teal-800">
        <div class="max-w-6xl mx-auto px-3 py-4 flex items-center gap-3">
            <div class="w-12 h-12 rounded bg-white/10 flex items-center justify-center text-white font-bold">🏫</div>
            <div class="text-white">
                <div class="text-2xl font-bold leading-tight">{{ $institute->name ?? 'Institute Name' }}</div>
                <div class="text-sm opacity-90">{{ $institute->slogan ?? ($institute->address ?? '') }}</div>
            </div>
        </div>

        <div class="bg-teal-900">
            <div class="max-w-6xl mx-auto px-3">
                <ul class="flex flex-wrap items-center gap-2 py-2 text-white text-sm">
                    <li>
                        <a href="{{ route('client.home') }}" class="px-3 py-2 rounded hover:bg-white/10">নীড় পাতা</a>
                    </li>
                    @foreach ($menuTree as $node)
                        @include('client.partials.menu-tree', ['node' => $node])
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <main class="max-w-6xl mx-auto px-3 py-5">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-200 mt-8">
        <div class="max-w-6xl mx-auto px-3 py-8 grid md:grid-cols-12 gap-6">
            <div class="md:col-span-4">
                <div class="font-semibold mb-2">যোগাযোগ</div>
                <div class="text-sm text-slate-300 whitespace-pre-line">
                    {{ $footer->address ?? ($institute->address ?? '') }}</div>
                <div class="text-sm text-slate-300 mt-2">Phone: {{ $footer->phone ?? ($institute->phone_1 ?? '') }}</div>
                <div class="text-sm text-slate-300">Email: {{ $footer->email ?? '' }}</div>
            </div>

            <div class="md:col-span-4">
                <div class="font-semibold mb-2">লিংক</div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($footerLinks ?? collect() as $group => $links)
                        <div>
                            <div class="text-sm font-medium text-slate-100 mb-2">{{ $group }}</div>
                            <div class="space-y-1 text-sm">
                                @foreach ($links as $l)
                                    <a class="block text-slate-300 hover:text-white hover:underline"
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
                <div class="bg-white/5 rounded overflow-hidden">
                    {!! $footer->map_embed ?? '' !!}
                </div>
            </div>

            <div
                class="md:col-span-12 border-t border-white/10 pt-4 text-xs text-slate-400 flex flex-wrap justify-between gap-2">
                <div>{!! $footer->copyright_text ?? '© ' . date('Y') . ' ' . ($institute->name ?? config('app.name')) !!}</div>
                <div>Powered by {{ config('app.name') }}</div>
            </div>
        </div>
    </footer>

</body>

</html>
