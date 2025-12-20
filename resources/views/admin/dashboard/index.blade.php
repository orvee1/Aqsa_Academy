@extends('tailwind.layouts.admin')
@section('title', 'Dashboard')

@section('content')
    @php
        $fmtBytes = function ($bytes) {
            $bytes = (int) $bytes;
            if ($bytes <= 0) {
                return '0 B';
            }
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return number_format($bytes, $i === 0 ? 0 : 1) . ' ' . $units[$i];
        };
    @endphp

    <div class="grid lg:grid-cols-12 gap-6">

        {{-- Left big area --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Stats cards --}}
            <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <a href="{{ route('admin.notices.index') }}"
                    class="bg-white rounded-xl shadow p-4 hover:ring-2 hover:ring-indigo-500 transition">
                    <div class="text-xs text-slate-500">Notices</div>
                    <div class="text-2xl font-bold mt-1">{{ $noticesTotal }}</div>
                    <div class="text-xs text-slate-500 mt-2">Published: {{ $noticesPublished }} • Pinned:
                        {{ $noticesPinned }}</div>
                </a>

                <a href="{{ route('admin.posts.index') }}"
                    class="bg-white rounded-xl shadow p-4 hover:ring-2 hover:ring-indigo-500 transition">
                    <div class="text-xs text-slate-500">Posts</div>
                    <div class="text-2xl font-bold mt-1">{{ $postsTotal }}</div>
                    <div class="text-xs text-slate-500 mt-2">Published: {{ $postsPublished }} • Categories:
                        {{ $postCategories }}</div>
                </a>

                <a href="{{ route('admin.events.index') }}"
                    class="bg-white rounded-xl shadow p-4 hover:ring-2 hover:ring-indigo-500 transition">
                    <div class="text-xs text-slate-500">Events</div>
                    <div class="text-2xl font-bold mt-1">{{ $eventsTotal }}</div>
                    <div class="text-xs text-slate-500 mt-2">Upcoming: {{ $eventsUpcoming }}</div>
                </a>

                <a href="{{ route('admin.media.index') }}"
                    class="bg-white rounded-xl shadow p-4 hover:ring-2 hover:ring-indigo-500 transition">
                    <div class="text-xs text-slate-500">Media</div>
                    <div class="text-2xl font-bold mt-1">{{ $mediaTotal }}</div>
                    <div class="text-xs text-slate-500 mt-2">Storage: {{ $fmtBytes($mediaSize) }}</div>
                </a>
            </div>

            {{-- Secondary cards --}}
            <div class="grid md:grid-cols-3 gap-4">
                <a href="{{ route('admin.pages.index') }}" class="bg-white rounded-xl shadow p-4">
                    <div class="text-xs text-slate-500">Pages</div>
                    <div class="text-xl font-bold mt-1">{{ $pagesTotal }}</div>
                    <div class="text-xs text-slate-500 mt-2">Published: {{ $pagesPublished }}</div>
                </a>

                <a href="{{ route('admin.sliders.index') }}" class="bg-white rounded-xl shadow p-4">
                    <div class="text-xs text-slate-500">Sliders</div>
                    <div class="text-xl font-bold mt-1">{{ $slidersTotal }}</div>
                    <div class="text-xs text-slate-500 mt-2">Active now: {{ $slidersActiveNow }}</div>
                </a>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-xs text-slate-500">Gallery</div>
                    <div class="text-xl font-bold mt-1">{{ $albums }} Albums</div>
                    <div class="text-xs text-slate-500 mt-2">{{ $videos }} Videos</div>
                </div>
            </div>

            {{-- Recent activity --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-slate-800">Recent Activity</div>
                        <div class="text-xs text-slate-500">Latest notices + posts</div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x">
                    <div class="p-5">
                        <div class="font-medium mb-3">Latest Notices</div>
                        <div class="space-y-3">
                            @forelse($recentNotices as $n)
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="text-sm font-medium text-slate-800 line-clamp-1">{{ $n->title }}
                                        </div>
                                        <div class="text-xs text-slate-500">{{ $n->created_at?->format('d M Y, h:i A') }}
                                        </div>
                                    </div>
                                    <div class="text-xs">
                                        @if ($n->is_hidden)
                                            <span class="px-2 py-1 rounded bg-rose-100 text-rose-700">Hidden</span>
                                        @elseif($n->is_published)
                                            <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">Published</span>
                                        @else
                                            <span class="px-2 py-1 rounded bg-amber-100 text-amber-800">Draft</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-sm text-slate-500">No notices yet.</div>
                            @endforelse
                        </div>

                        <div class="mt-4">
                            <a class="text-sm text-indigo-600 underline" href="{{ route('admin.notices.index') }}">View all
                                notices</a>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="font-medium mb-3">Latest Posts</div>
                        <div class="space-y-3">
                            @forelse($recentPosts as $p)
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="text-sm font-medium text-slate-800 line-clamp-1">{{ $p->title }}
                                        </div>
                                        <div class="text-xs text-slate-500">{{ $p->created_at?->format('d M Y, h:i A') }}
                                        </div>
                                    </div>
                                    <div class="text-xs">
                                        @if ($p->status === 'published')
                                            <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">Published</span>
                                        @else
                                            <span class="px-2 py-1 rounded bg-amber-100 text-amber-800">Draft</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-sm text-slate-500">No posts yet.</div>
                            @endforelse
                        </div>

                        <div class="mt-4">
                            <a class="text-sm text-indigo-600 underline" href="{{ route('admin.posts.index') }}">View all
                                posts</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upcoming events --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-slate-800">Upcoming Events</div>
                        <div class="text-xs text-slate-500">Published events from today</div>
                    </div>
                    <a href="{{ route('admin.events.index') }}" class="text-sm text-indigo-600 underline">Manage</a>
                </div>

                <div class="p-5">
                    @if ($upcomingEventsList->count() === 0)
                        <div class="text-sm text-slate-500">No upcoming events.</div>
                    @else
                        <div class="space-y-3">
                            @foreach ($upcomingEventsList as $e)
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <div class="text-sm font-medium text-slate-800">{{ $e->title }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ \Carbon\Carbon::parse($e->event_date)->format('d M Y') }}
                                            @if ($e->event_time)
                                                • {{ \Carbon\Carbon::parse($e->event_time)->format('h:i A') }}
                                            @endif
                                            @if ($e->venue)
                                                • {{ $e->venue }}
                                            @endif
                                        </div>
                                    </div>
                                    <a class="px-3 py-1.5 border rounded text-xs hover:bg-slate-50"
                                        href="{{ route('admin.events.edit', $e->id) }}">Edit</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right sidebar --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Quick actions --}}
            <div class="bg-white rounded-xl shadow p-5">
                <div class="font-semibold text-slate-800 mb-3">Quick Actions</div>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.notices.index') }}"
                        class="px-3 py-2 rounded border hover:bg-slate-50 text-sm">+ Notice</a>
                    <a href="{{ route('admin.posts.create') }}"
                        class="px-3 py-2 rounded border hover:bg-slate-50 text-sm">+ Post</a>
                    <a href="{{ route('admin.pages.create') }}"
                        class="px-3 py-2 rounded border hover:bg-slate-50 text-sm">+ Page</a>
                    <a href="{{ route('admin.sliders.create') }}"
                        class="px-3 py-2 rounded border hover:bg-slate-50 text-sm">+ Slider</a>
                    <a href="{{ route('admin.media.index') }}"
                        class="px-3 py-2 rounded border hover:bg-slate-50 text-sm">Media</a>
                    <a href="{{ route('admin.footer.settings') }}"
                        class="px-3 py-2 rounded border hover:bg-slate-50 text-sm">Footer</a>
                </div>
            </div>

            {{-- Access overview --}}
            <div class="bg-white rounded-xl shadow p-5">
                <div class="font-semibold text-slate-800 mb-3">Access Overview</div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-slate-600">Users</span><span
                            class="font-semibold">{{ $usersTotal }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Super Admin</span><span
                            class="font-semibold">{{ $superAdmins }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Roles</span><span
                            class="font-semibold">{{ $rolesTotal }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Permissions</span><span
                            class="font-semibold">{{ $permissionsTotal }}</span></div>
                </div>
            </div>

            {{-- Footer status --}}
            <div class="bg-white rounded-xl shadow p-5">
                <div class="font-semibold text-slate-800 mb-3">Website Setup</div>
                <div class="text-sm text-slate-600 space-y-2">
                    <div class="flex justify-between">
                        <span>Footer Settings</span>
                        @if ($footerConfigured)
                            <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Configured</span>
                        @else
                            <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs">Not set</span>
                        @endif
                    </div>
                    <div class="flex justify-between">
                        <span>Footer Links (active)</span>
                        <span class="font-semibold">{{ $footerLinksActive }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Social Links (active)</span>
                        <span class="font-semibold">{{ $socialLinksActive }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Statements (active)</span>
                        <span class="font-semibold">{{ $statementsActive }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Achievements (active)</span>
                        <span class="font-semibold">{{ $achievementsActive }}</span>
                    </div>
                </div>
            </div>

            {{-- Recent media --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-slate-800">Recent Uploads</div>
                        <div class="text-xs text-slate-500">Last 6 media items</div>
                    </div>
                    <a href="{{ route('admin.media.index') }}" class="text-sm text-indigo-600 underline">Open</a>
                </div>
                <div class="p-5 grid grid-cols-3 gap-3">
                    @forelse($recentMedia as $m)
                        @php
                            $url = \Storage::disk($m->disk)->url($m->path);
                            $isImg = is_string($m->mime) && str_starts_with($m->mime, 'image/');
                        @endphp
                        <a href="{{ $url }}" target="_blank"
                            class="block rounded overflow-hidden border bg-slate-50 aspect-square">
                            @if ($isImg)
                                <img src="{{ $url }}" class="w-full h-full object-cover" alt="">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center text-[10px] text-slate-500 px-1 text-center">
                                    {{ $m->mime ?? 'file' }}
                                </div>
                            @endif
                        </a>
                    @empty
                        <div class="col-span-3 text-sm text-slate-500">No media uploaded.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection
