<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>

    {{-- Tailwind already in project --}}
    @vite(['resources/css/app.css','resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-slate-100 text-slate-900">
<div class="min-h-screen flex">

    {{-- Mobile overlay --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

    {{-- Sidebar --}}
    <aside id="sidebar"
           class="fixed lg:static inset-y-0 left-0 z-50 w-72 -translate-x-full lg:translate-x-0 transition-transform
                  bg-slate-900 text-slate-100 flex flex-col">

        {{-- Brand --}}
        <div class="h-16 px-5 flex items-center justify-between border-b border-white/10">
            <a href="{{ route('admin.dashboard') ?? '#' }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded bg-white/10 flex items-center justify-center font-bold">
                    A
                </div>
                <div class="leading-tight">
                    <div class="font-semibold text-base">Admin Panel</div>
                    <div class="text-xs text-slate-300">School / Institute CMS</div>
                </div>
            </a>

            <button id="sidebarCloseBtn" class="lg:hidden p-2 rounded hover:bg-white/10" type="button" aria-label="Close">
                ✕
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4">
            @php
                $is = fn($pattern) => request()->routeIs($pattern);
                $active = "bg-white/10 text-white";
                $idle = "text-slate-200 hover:bg-white/10 hover:text-white";
                $item = "flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition";
                $icon = "w-5 h-5 text-slate-300";
                $sectionTitle = "px-3 mt-5 mb-2 text-xs uppercase tracking-wider text-slate-400";
            @endphp

            <div class="{{ $sectionTitle }}">Main</div>

            <a class="{{ $item }} {{ $is('admin.dashboard') ? $active : $idle }}"
               href="{{ route('admin.dashboard') ?? '#' }}">
                <span class="{{ $icon }}">▣</span>
                <span>Dashboard</span>
            </a>

            <div class="{{ $sectionTitle }}">Website CMS</div>

            <a class="{{ $item }} {{ $is('admin.site-profile.*') ? $active : $idle }}"
               href="{{ route('admin.site-profile.edit') ?? '#' }}">
                <span class="{{ $icon }}">🏫</span>
                <span>প্রতিষ্ঠান পরিচিতি</span>
            </a>

            <a class="{{ $item }} {{ $is('admin.notices.*') ? $active : $idle }}"
               href="{{ route('admin.notices.index') ?? '#' }}">
                <span class="{{ $icon }}">📝</span>
                <span>নোটিশবোর্ড</span>
            </a>

            {{-- Dropdown: Menu & Pages --}}
            <button type="button"
                    class="{{ $item }} w-full justify-between {{ ($is('admin.menus.*') || $is('admin.pages.*')) ? $active : $idle }}"
                    data-collapse-target="menuPagesGroup">
                <span class="flex items-center gap-3">
                    <span class="{{ $icon }}">≡</span>
                    <span>মেনু / পেজ</span>
                </span>
                <span class="text-xs opacity-80">▾</span>
            </button>
            <div id="menuPagesGroup" class="ml-3 mt-1 space-y-1 {{ ($is('admin.menus.*') || $is('admin.pages.*')) ? '' : 'hidden' }}">
                <a class="block {{ $item }} {{ $is('admin.menus.*') ? $active : $idle }}"
                   href="{{ route('admin.menus.index') ?? '#' }}">
                    <span class="text-slate-300">•</span>
                    <span>মেনুবাৰ</span>
                </a>
                <a class="block {{ $item }} {{ $is('admin.pages.*') ? $active : $idle }}"
                   href="{{ route('admin.pages.index') ?? '#' }}">
                    <span class="text-slate-300">•</span>
                    <span>পেজ তৈরি করুন</span>
                </a>
            </div>

            {{-- Dropdown: Posts --}}
            <button type="button"
                    class="{{ $item }} w-full justify-between {{ ($is('admin.post-categories.*') || $is('admin.posts.*')) ? $active : $idle }}"
                    data-collapse-target="postsGroup">
                <span class="flex items-center gap-3">
                    <span class="{{ $icon }}">📚</span>
                    <span>পোস্ট</span>
                </span>
                <span class="text-xs opacity-80">▾</span>
            </button>
            <div id="postsGroup" class="ml-3 mt-1 space-y-1 {{ ($is('admin.post-categories.*') || $is('admin.posts.*')) ? '' : 'hidden' }}">
                <a class="block {{ $item }} {{ $is('admin.post-categories.*') ? $active : $idle }}"
                   href="{{ route('admin.post-categories.index') ?? '#' }}">
                    <span class="text-slate-300">•</span>
                    <span>পোস্ট ক্যাটাগরি</span>
                </a>
                <a class="block {{ $item }} {{ $is('admin.posts.*') ? $active : $idle }}"
                   href="{{ route('admin.posts.index') ?? '#' }}">
                    <span class="text-slate-300">•</span>
                    <span>পোস্ট তৈরি করুন</span>
                </a>
            </div>

            <a class="{{ $item }} {{ $is('admin.statements.*') ? $active : $idle }}"
               href="{{ route('admin.statements.index') ?? '#' }}">
                <span class="{{ $icon }}">💬</span>
                <span>বাণী তৈরি করুন</span>
            </a>

            <a class="{{ $item }} {{ $is('admin.events.*') ? $active : $idle }}"
               href="{{ route('admin.events.index') ?? '#' }}">
                <span class="{{ $icon }}">📅</span>
                <span>ইভেন্ট</span>
            </a>

            <a class="{{ $item }} {{ $is('admin.achievements.*') ? $active : $idle }}"
               href="{{ route('admin.achievements.index') ?? '#' }}">
                <span class="{{ $icon }}">🏆</span>
                <span>আমাদের অর্জন</span>
            </a>

            {{-- Dropdown: Gallery --}}
            <button type="button"
                    class="{{ $item }} w-full justify-between {{ ($is('admin.image-albums.*') || $is('admin.image-items.*') || $is('admin.videos.*')) ? $active : $idle }}"
                    data-collapse-target="galleryGroup">
                <span class="flex items-center gap-3">
                    <span class="{{ $icon }}">🖼️</span>
                    <span>গ্যালারী</span>
                </span>
                <span class="text-xs opacity-80">▾</span>
            </button>
            <div id="galleryGroup" class="ml-3 mt-1 space-y-1 {{ ($is('admin.image-albums.*') || $is('admin.image-items.*') || $is('admin.videos.*')) ? '' : 'hidden' }}">
                <a class="block {{ $item }} {{ ($is('admin.image-albums.*') || $is('admin.image-items.*')) ? $active : $idle }}"
                   href="{{ route('admin.image-albums.index') ?? '#' }}">
                    <span class="text-slate-300">•</span>
                    <span>ইমেজ গ্যালারী</span>
                </a>
                <a class="block {{ $item }} {{ $is('admin.videos.*') ? $active : $idle }}"
                   href="{{ route('admin.videos.index') ?? '#' }}">
                    <span class="text-slate-300">•</span>
                    <span>ভিডিও গ্যালারী</span>
                </a>
            </div>

            <a class="{{ $item }} {{ $is('admin.sliders.*') ? $active : $idle }}"
               href="{{ route('admin.sliders.index') ?? '#' }}">
                <span class="{{ $icon }}">🧩</span>
                <span>স্লাইডার ম্যানেজ</span>
            </a>

            <a class="{{ $item }} {{ $is('admin.footer.*') ? $active : $idle }}"
               href="{{ route('admin.footer.settings') ?? '#' }}">
                <span class="{{ $icon }}">⬇</span>
                <span>ফুটার ম্যানেজ</span>
            </a>

            <div class="{{ $sectionTitle }}">Access</div>

            <a class="{{ $item }} {{ $is('admin.roles.*') ? $active : $idle }}"
               href="{{ route('admin.roles.index') ?? '#' }}">
                <span class="{{ $icon }}">👤</span>
                <span>Roles</span>
            </a>

            <a class="{{ $item }} {{ $is('admin.users.*') ? $active : $idle }}"
               href="{{ route('admin.users.index') ?? '#' }}">
                <span class="{{ $icon }}">👥</span>
                <span>Users</span>
            </a>

            <a class="{{ $item }} {{ $is('admin.permissions.*') ? $active : $idle }}"
               href="{{ route('admin.permissions.index') ?? '#' }}">
                <span class="{{ $icon }}">🔒</span>
                <span>Permissions</span>
            </a>

        </nav>

        {{-- Sidebar footer --}}
        <div class="border-t border-white/10 p-4 text-xs text-slate-300">
            <div class="flex items-center justify-between">
                <span>Logged as</span>
                <span class="font-semibold text-slate-100">{{ auth()->user()->name ?? 'Guest' }}</span>
            </div>
            <div class="mt-2 opacity-70">© {{ date('Y') }} {{ config('app.name') }}</div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 lg:ml-0 w-full">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 bg-white border-b">
            <div class="h-16 px-4 lg:px-6 flex items-center justify-between gap-3">

                <div class="flex items-center gap-3">
                    <button id="sidebarOpenBtn" type="button"
                            class="lg:hidden p-2 rounded border hover:bg-slate-50"
                            aria-label="Open sidebar">
                        ☰
                    </button>

                    <div class="leading-tight">
                        <div class="font-semibold text-slate-800">
                            @yield('title', 'Dashboard')
                        </div>
                        <div class="text-xs text-slate-500">
                            Admin Control Panel
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Quick actions placeholder --}}
                    <a href="{{ url('/') }}" target="_blank"
                       class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded border text-sm hover:bg-slate-50">
                        🌐 <span>Visit Site</span>
                    </a>

                    {{-- User dropdown --}}
                    <div class="relative">
                        <button type="button" class="flex items-center gap-2 px-3 py-2 rounded border hover:bg-slate-50"
                                data-dropdown-target="userDropdown">
                            <span class="w-8 h-8 rounded bg-slate-200 flex items-center justify-center font-semibold">
                                {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </span>
                            <div class="hidden sm:block text-left">
                                <div class="text-sm font-medium leading-tight">{{ auth()->user()->name ?? 'User' }}</div>
                                <div class="text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</div>
                            </div>
                            <span class="text-xs text-slate-500">▾</span>
                        </button>

                        <div id="userDropdown"
                             class="hidden absolute right-0 mt-2 w-56 bg-white border rounded-lg shadow-lg overflow-hidden">
                            <a href="{{ route('admin.profile') ?? '#' }}"
                               class="block px-4 py-2 text-sm hover:bg-slate-50">
                                Profile
                            </a>

                            <div class="border-t"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-sm hover:bg-slate-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        {{-- Content area --}}
        <main class="px-4 lg:px-6 py-6">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 rounded-lg bg-rose-50 text-rose-700 border border-rose-200">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-amber-50 text-amber-800 border border-amber-200">
                    <div class="font-semibold mb-1">Please fix the following:</div>
                    <ul class="list-disc ml-5 text-sm space-y-1">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Page content --}}
            @yield('content')
        </main>
    </div>
</div>

<script>
    // Sidebar mobile toggle
    (function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const openBtn = document.getElementById('sidebarOpenBtn');
        const closeBtn = document.getElementById('sidebarCloseBtn');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        openBtn?.addEventListener('click', openSidebar);
        closeBtn?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        // Dropdown toggle
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-dropdown-target]');
            const insideDropdown = e.target.closest('#userDropdown');

            if (btn) {
                const id = btn.getAttribute('data-dropdown-target');
                const dd = document.getElementById(id);
                dd?.classList.toggle('hidden');
                return;
            }

            // click outside closes dropdown
            if (!insideDropdown) {
                const dd = document.getElementById('userDropdown');
                dd?.classList.add('hidden');
            }
        });

        // Collapse groups
        document.querySelectorAll('[data-collapse-target]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = btn.getAttribute('data-collapse-target');
                const box = document.getElementById(id);
                if (!box) return;
                box.classList.toggle('hidden');
            });
        });
    })();
</script>

@stack('scripts')
</body>
</html>
