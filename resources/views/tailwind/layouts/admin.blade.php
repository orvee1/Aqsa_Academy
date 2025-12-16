<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>

    {{-- Tailwind already in project --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-slate-100 text-slate-900">
    @php
        use Illuminate\Support\Facades\Route;

        $is = fn($pattern) => request()->routeIs($pattern);

        // Safe route helper: route missing হলে exception না দিয়ে fallback দিবে
        $href = function (string $name, string $fallback = '#', array $params = []) {
            return Route::has($name) ? route($name, $params) : $fallback;
        };

        // Dashboard route আপনার routes এ নাই, তাই fallback হিসেবে Institutes index
        $dashboardUrl = Route::has('admin.dashboard')
            ? route('admin.dashboard')
            : (Route::has('admin.institutes.index')
                ? route('admin.institutes.index')
                : '#');

        $active = 'bg-white/10 text-white';
        $idle = 'text-slate-200 hover:bg-white/10 hover:text-white';
        $item = 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition';
        $icon = 'w-5 h-5 text-slate-300';
        $sectionTitle = 'px-3 mt-5 mb-2 text-xs uppercase tracking-wider text-slate-400';

        $menuPagesOpen = $is('admin.menus.*') || $is('admin.pages.*') || $is('admin.menu-items.*');
        $postsOpen = $is('admin.post-categories.*') || $is('admin.posts.*');

        $galleryOpen = $is('admin.image-albums.*') || $is('admin.image-items.*') || $is('admin.video-items.*');

        $footerOpen = $is('admin.footer.*') || $is('admin.footer-links.*') || $is('admin.social-links.*');
    @endphp

    <div class="min-h-screen flex">

        {{-- Mobile overlay --}}
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

        {{-- Sidebar --}}
        <aside id="sidebar"
            class="fixed lg:static inset-y-0 left-0 z-50 w-72 -translate-x-full lg:translate-x-0 transition-transform
                  bg-slate-900 text-slate-100 flex flex-col">

            {{-- Brand --}}
            <div class="h-16 px-5 flex items-center justify-between border-b border-white/10">
                <a href="{{ $dashboardUrl }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded bg-white/10 flex items-center justify-center font-bold">
                        A
                    </div>
                    <div class="leading-tight">
                        <div class="font-semibold text-base">Admin Panel</div>
                        <div class="text-xs text-slate-300">School / Institute CMS</div>
                    </div>
                </a>

                <button id="sidebarCloseBtn" class="lg:hidden p-2 rounded hover:bg-white/10" type="button"
                    aria-label="Close">
                    ✕
                </button>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4">

                <div class="{{ $sectionTitle }}">Main</div>

                <a class="{{ $item }} {{ (Route::has('admin.dashboard') ? $is('admin.dashboard') : $is('admin.institutes.*')) ? $active : $idle }}"
                    href="{{ $dashboardUrl }}">
                    <span class="{{ $icon }}">▣</span>
                    <span>Dashboard</span>
                </a>

                <div class="{{ $sectionTitle }}">Website CMS</div>

                {{-- Institutes --}}
                <a class="{{ $item }} {{ $is('admin.institutes.*') ? $active : $idle }}"
                    href="{{ $href('admin.institutes.index') }}">
                    <span class="{{ $icon }}">🏫</span>
                    <span>Institute</span>
                </a>

                {{-- Notices --}}
                <a class="{{ $item }} {{ $is('admin.notices.*') ? $active : $idle }}"
                    href="{{ $href('admin.notices.index') }}">
                    <span class="{{ $icon }}">📝</span>
                    <span>নোটিশবোর্ড</span>
                </a>

                {{-- Dropdown: Menu & Pages --}}
                <button type="button"
                    class="{{ $item }} w-full justify-between {{ $menuPagesOpen ? $active : $idle }}"
                    data-collapse-target="menuPagesGroup" aria-expanded="{{ $menuPagesOpen ? 'true' : 'false' }}">
                    <span class="flex items-center gap-3">
                        <span class="{{ $icon }}">≡</span>
                        <span>মেনু / পেজ</span>
                    </span>
                    <span class="text-xs opacity-80">▾</span>
                </button>
                <div id="menuPagesGroup" class="ml-3 mt-1 space-y-1 {{ $menuPagesOpen ? '' : 'hidden' }}">
                    <a class="block {{ $item }} {{ $is('admin.menus.*') ? $active : $idle }}"
                        href="{{ $href('admin.menus.index') }}">
                        <span class="text-slate-300">•</span>
                        <span>মেনুবাৰ</span>
                    </a>
                    <a class="block {{ $item }} {{ $is('admin.pages.*') ? $active : $idle }}"
                        href="{{ $href('admin.pages.index') }}">
                        <span class="text-slate-300">•</span>
                        <span>পেজ</span>
                    </a>
                </div>

                {{-- Dropdown: Posts --}}
                <button type="button"
                    class="{{ $item }} w-full justify-between {{ $postsOpen ? $active : $idle }}"
                    data-collapse-target="postsGroup" aria-expanded="{{ $postsOpen ? 'true' : 'false' }}">
                    <span class="flex items-center gap-3">
                        <span class="{{ $icon }}">📚</span>
                        <span>পোস্ট</span>
                    </span>
                    <span class="text-xs opacity-80">▾</span>
                </button>
                <div id="postsGroup" class="ml-3 mt-1 space-y-1 {{ $postsOpen ? '' : 'hidden' }}">
                    <a class="block {{ $item }} {{ $is('admin.post-categories.*') ? $active : $idle }}"
                        href="{{ $href('admin.post-categories.index') }}">
                        <span class="text-slate-300">•</span>
                        <span>পোস্ট ক্যাটাগরি</span>
                    </a>
                    <a class="block {{ $item }} {{ $is('admin.posts.*') ? $active : $idle }}"
                        href="{{ $href('admin.posts.index') }}">
                        <span class="text-slate-300">•</span>
                        <span>পোস্ট</span>
                    </a>
                </div>

                {{-- Statements --}}
                <a class="{{ $item }} {{ $is('admin.statements.*') ? $active : $idle }}"
                    href="{{ $href('admin.statements.index') }}">
                    <span class="{{ $icon }}">💬</span>
                    <span>বাণী</span>
                </a>

                {{-- Events --}}
                <a class="{{ $item }} {{ $is('admin.events.*') ? $active : $idle }}"
                    href="{{ $href('admin.events.index') }}">
                    <span class="{{ $icon }}">📅</span>
                    <span>ইভেন্ট</span>
                </a>

                {{-- Achievements --}}
                <a class="{{ $item }} {{ $is('admin.achievements.*') ? $active : $idle }}"
                    href="{{ $href('admin.achievements.index') }}">
                    <span class="{{ $icon }}">🏆</span>
                    <span>আমাদের অর্জন</span>
                </a>

                {{-- Dropdown: Gallery --}}
                <button type="button"
                    class="{{ $item }} w-full justify-between {{ $galleryOpen ? $active : $idle }}"
                    data-collapse-target="galleryGroup" aria-expanded="{{ $galleryOpen ? 'true' : 'false' }}">
                    <span class="flex items-center gap-3">
                        <span class="{{ $icon }}">🖼️</span>
                        <span>গ্যালারী</span>
                    </span>
                    <span class="text-xs opacity-80">▾</span>
                </button>
                <div id="galleryGroup" class="ml-3 mt-1 space-y-1 {{ $galleryOpen ? '' : 'hidden' }}">
                    <a class="block {{ $item }} {{ $is('admin.image-albums.*') || $is('admin.image-items.*') ? $active : $idle }}"
                        href="{{ $href('admin.image-albums.index') }}">
                        <span class="text-slate-300">•</span>
                        <span>ইমেজ গ্যালারী</span>
                    </a>

                    <a class="block {{ $item }} {{ $is('admin.video-items.*') ? $active : $idle }}"
                        href="{{ $href('admin.video-items.index') }}">
                        <span class="text-slate-300">•</span>
                        <span>ভিডিও গ্যালারী</span>
                    </a>
                </div>

                {{-- Sliders --}}
                <a class="{{ $item }} {{ $is('admin.sliders.*') ? $active : $idle }}"
                    href="{{ $href('admin.sliders.index') }}">
                    <span class="{{ $icon }}">🧩</span>
                    <span>স্লাইডার</span>
                </a>

                {{-- Footer dropdown --}}
                <button type="button"
                    class="{{ $item }} w-full justify-between {{ $footerOpen ? $active : $idle }}"
                    data-collapse-target="footerGroup" aria-expanded="{{ $footerOpen ? 'true' : 'false' }}">
                    <span class="flex items-center gap-3">
                        <span class="{{ $icon }}">⬇</span>
                        <span>ফুটার</span>
                    </span>
                    <span class="text-xs opacity-80">▾</span>
                </button>
                <div id="footerGroup" class="ml-3 mt-1 space-y-1 {{ $footerOpen ? '' : 'hidden' }}">
                    <a class="block {{ $item }} {{ $is('admin.footer.*') ? $active : $idle }}"
                        href="{{ $href('admin.footer.settings') }}">
                        <span class="text-slate-300">•</span>
                        <span>Footer Settings</span>
                    </a>
                    <a class="block {{ $item }} {{ $is('admin.footer-links.*') ? $active : $idle }}"
                        href="{{ $href('admin.footer-links.index') }}">
                        <span class="text-slate-300">•</span>
                        <span>Footer Links</span>
                    </a>
                    <a class="block {{ $item }} {{ $is('admin.social-links.*') ? $active : $idle }}"
                        href="{{ $href('admin.social-links.index') }}">
                        <span class="text-slate-300">•</span>
                        <span>Social Links</span>
                    </a>
                </div>

                {{-- Media Library --}}
                <a class="{{ $item }} {{ $is('admin.media.*') ? $active : $idle }}"
                    href="{{ $href('admin.media.index') }}">
                    <span class="{{ $icon }}">🗂️</span>
                    <span>Media Library</span>
                </a>

                <div class="{{ $sectionTitle }}">Access</div>

                <a class="{{ $item }} {{ $is('admin.roles.*') ? $active : $idle }}"
                    href="{{ $href('admin.roles.index') }}">
                    <span class="{{ $icon }}">👤</span>
                    <span>Roles</span>
                </a>

                <a class="{{ $item }} {{ $is('admin.users.*') ? $active : $idle }}"
                    href="{{ $href('admin.users.index') }}">
                    <span class="{{ $icon }}">👥</span>
                    <span>Users</span>
                </a>

                <a class="{{ $item }} {{ $is('admin.permissions.*') ? $active : $idle }}"
                    href="{{ $href('admin.permissions.index') }}">
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
                            class="lg:hidden p-2 rounded border hover:bg-slate-50" aria-label="Open sidebar">
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
                        <a href="{{ url('/') }}" target="_blank"
                            class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded border text-sm hover:bg-slate-50">
                            🌐 <span>Visit Site</span>
                        </a>

                        {{-- User dropdown --}}
                        <div class="relative">
                            <button type="button"
                                class="flex items-center gap-2 px-3 py-2 rounded border hover:bg-slate-50"
                                data-dropdown-target="userDropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="w-8 h-8 rounded bg-slate-200 flex items-center justify-center font-semibold">
                                    {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </span>
                                <div class="hidden sm:block text-left">
                                    <div class="text-sm font-medium leading-tight">
                                        {{ auth()->user()->name ?? 'User' }}</div>
                                    <div class="text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</div>
                                </div>
                                <span class="text-xs text-slate-500">▾</span>
                            </button>

                            <div id="userDropdown"
                                class="hidden absolute right-0 mt-2 w-56 bg-white border rounded-lg shadow-lg overflow-hidden">
                                {{-- আপনার routes এ admin.profile নাই, তাই safe fallback --}}
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-slate-50">
                                    Profile
                                </a>

                                <div class="border-t"></div>

                                <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
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

    {{-- Media Picker Modal (exists হলে include হবে) --}}
    @includeWhen(view()->exists('admin.media._picker_modal'), 'admin.media._picker_modal')

    <script>
        (function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const openBtn = document.getElementById('sidebarOpenBtn');
            const closeBtn = document.getElementById('sidebarCloseBtn');

            function openSidebar() {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
            }

            openBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);

            // Dropdown toggle + click outside close
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('[data-dropdown-target]');
                const dd = document.getElementById('userDropdown');

                if (btn) {
                    const id = btn.getAttribute('data-dropdown-target');
                    const box = document.getElementById(id);
                    box?.classList.toggle('hidden');
                    return;
                }

                if (!e.target.closest('#userDropdown')) {
                    dd?.classList.add('hidden');
                }
            });

            // Collapse groups
            document.querySelectorAll('[data-collapse-target]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = btn.getAttribute('data-collapse-target');
                    const box = document.getElementById(id);
                    if (!box) return;
                    box.classList.toggle('hidden');
                });
            });

            // ESC closes sidebar + dropdown
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSidebar();
                    document.getElementById('userDropdown')?.classList.add('hidden');
                }
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>
