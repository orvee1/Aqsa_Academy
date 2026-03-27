@extends('client.layouts.app')
@section('title', $institute->name ?? 'Home')

@section('content')
    @php
        use Illuminate\Support\Str;

        $img = function ($path) {
            if (!$path) {
                return null;
            }

            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            if (Str::startsWith($path, 'storage/')) {
                return asset($path);
            }

            return asset('storage/' . ltrim($path, '/'));
        };

        $ytId = function ($url) {
            if (!$url) {
                return null;
            }

            $id = null;

            if (str_contains($url, 'embed/')) {
                $id = Str::after($url, 'embed/');
            } elseif (str_contains($url, 'youtu.be/')) {
                $id = Str::after($url, 'youtu.be/');
            } elseif (str_contains($url, 'v=')) {
                $id = Str::after($url, 'v=');
            }

            return $id ? strtok($id, '&?') : null;
        };

        $sliderItems = $sliders ?? collect();
        $first = $sliderItems->first();
        $quickLinks = $importantLinks ?? collect();
        $internalItems = $internalLinks ?? collect();
    @endphp

    <div class="grid lg:grid-cols-12 gap-4">

        {{-- LEFT --}}
        <div class="lg:col-span-9 space-y-4">

            <div class="grid md:grid-cols-12 gap-4">
                <div class="md:col-span-7">
                    <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                        @if ($first && $img($first->image_path))
                            <div class="relative">
                                <img src="{{ $img($first->image_path) }}" class="w-full h-[340px] object-cover" alt="">
                                @if ($sliderItems->count() > 1)
                                    <div class="absolute bottom-3 right-3 bg-black/50 text-white text-xs px-3 py-1 rounded">
                                        {{ $sliderItems->count() }} slides
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="h-[340px] flex items-center justify-center text-slate-500 bg-slate-50">
                                {{ __('home.no_slider') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-3">
                    <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden h-full">
                        <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">{{ __('home.message') }}</div>

                        <div class="p-3">
                            @if ($statement)
                                <div class="text-sm font-semibold text-slate-800">
                                    {{ $statement->author_name ?? __('home.authority_default') }}
                                </div>

                                <div class="text-xs text-slate-500 mb-2">
                                    {{ $statement->author_designation ?? '' }}
                                </div>

                                <div class="text-sm text-slate-700 leading-6">
                                    {!! Str::limit(strip_tags($statement->body), 320, '...') !!}
                                </div>
                            @else
                                <div class="text-sm text-slate-500">{{ __('home.no_notices') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden h-full">
                        <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">
                            {{ __('home.honourable_head') }}</div>

                        <div class="p-3">
                            @if ($statement && $statement->author_photo_path && $img($statement->author_photo_path))
                                <img src="{{ $img($statement->author_photo_path) }}"
                                    class="w-full h-[220px] object-cover border rounded" alt="">
                            @else
                                <div
                                    class="w-full h-[220px] border rounded bg-slate-100 flex items-center justify-center text-slate-400">
                                    {{ __('home.no_photo') }}
                                </div>
                            @endif

                            <div class="mt-3 text-sm font-semibold text-slate-800 leading-5">
                                {{ $statement->author_name ?? __('home.principal_default') }}
                            </div>

                            <div class="text-xs text-slate-600 mt-1">
                                {{ $statement->author_designation ?? __('home.principal_designation_default') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notice --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white px-3 py-2 flex items-center justify-between">
                    <div class="font-semibold">{{ __('home.notice_board') }}</div>
                    <a href="{{ route('client.notices.index') }}"
                        class="text-xs bg-white text-emerald-700 px-3 py-1 rounded hover:bg-slate-100">
                        {{ __('home.all_notices') }}
                    </a>
                </div>

                <div class="p-3">
                    @forelse($notices->take(5) as $n)
                        <div class="border-b border-slate-200 pb-3 mb-3 last:border-b-0 last:pb-0 last:mb-0">
                            <div class="flex items-start gap-3">
                                <div class="pt-1 text-green-600">●</div>

                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('client.notices.show', $n->slug) }}"
                                        class="text-sm text-slate-800 hover:text-emerald-700 hover:underline line-clamp-2">
                                        {{ $n->title }}
                                    </a>

                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                        <span>{{ $n->published_at?->format('d-m-Y') ?? $n->created_at->format('d-m-Y') }}</span>

                                        @if ($n->is_pinned)
                                            <span
                                                class="px-2 py-0.5 rounded bg-red-500 text-white">{{ __('home.new') }}</span>
                                        @endif

                                        <span
                                            class="px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ __('home.general') }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('client.notices.show', $n->slug) }}"
                                    class="text-slate-400 hover:text-emerald-700">
                                    ›
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">{{ __('home.no_notices') }}</div>
                    @endforelse
                </div>
            </div>

            {{-- Quick cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <a href="{{ route('client.notices.index') }}"
                    class="bg-white border border-slate-300 rounded-sm shadow-sm p-4 hover:bg-slate-50 transition">
                    <div class="text-sm font-semibold text-slate-800">{{ __('home.latest_notices') }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ __('home.latest_notices_desc') }}</div>
                </a>

                <a href="{{ route('client.events.index') }}"
                    class="bg-white border border-slate-300 rounded-sm shadow-sm p-4 hover:bg-slate-50 transition">
                    <div class="text-sm font-semibold text-slate-800">{{ __('home.events') }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ __('home.events_desc') }}</div>
                </a>

                <a href="{{ route('client.achievements.index') }}"
                    class="bg-white border border-slate-300 rounded-sm shadow-sm p-4 hover:bg-slate-50 transition">
                    <div class="text-sm font-semibold text-slate-800">{{ __('home.achievements') }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ __('home.achievements_desc') }}</div>
                </a>

                <a href="{{ route('client.gallery.index') }}"
                    class="bg-white border border-slate-300 rounded-sm shadow-sm p-4 hover:bg-slate-50 transition">
                    <div class="text-sm font-semibold text-slate-800">{{ __('home.photo_gallery') }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ __('home.photo_gallery_desc') }}</div>
                </a>
            </div>

            {{-- Main content rows --}}
            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">

                {{-- Events --}}
                <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                    <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2 flex items-center justify-between">
                        <span>{{ __('home.upcoming_events') }}</span>
                        <a href="{{ route('client.events.index') }}"
                            class="text-xs text-white/90 hover:text-white">{{ __('home.all') }}</a>
                    </div>

                    <div class="p-3 space-y-3">
                        @forelse($events->take(3) as $e)
                            <a href="{{ route('client.events.show', $e->slug) }}"
                                class="block border rounded overflow-hidden hover:bg-slate-50">
                                <div class="h-32 bg-slate-100">
                                    @if ($e->cover_image_path && $img($e->cover_image_path))
                                        <img src="{{ $img($e->cover_image_path) }}" class="w-full h-full object-cover"
                                            alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">
                                            Event
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <div class="text-sm font-medium line-clamp-2">{{ $e->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ \Carbon\Carbon::parse($e->event_date)->format('d F, Y') }}
                                        @if ($e->event_time)
                                            • {{ \Carbon\Carbon::parse($e->event_time)->format('h:i A') }}
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-sm text-slate-500">{{ __('home.no_events') }}</div>
                        @endforelse
                    </div>
                </div>

                {{-- Achievements --}}
                <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                    <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2 flex items-center justify-between">
                        <span>{{ __('home.achievements') }}</span>
                        <a href="{{ route('client.achievements.index') }}"
                            class="text-xs text-white/90 hover:text-white">{{ __('home.all') }}</a>
                    </div>

                    <div class="p-3 space-y-3">
                        @forelse($achievements->take(3) as $a)
                            <div class="border rounded overflow-hidden">
                                <div class="h-32 bg-slate-100">
                                    @if ($a->image_path && $img($a->image_path))
                                        <img src="{{ $img($a->image_path) }}" class="w-full h-full object-cover"
                                            alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">
                                            {{ __('home.no_photo') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3 text-sm">
                                    <div class="font-medium line-clamp-2">{{ $a->title }}</div>
                                    @if ($a->year)
                                        <div class="text-xs text-slate-500 mt-1">{{ $a->year }}</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-slate-500">{{ __('home.no_achievements') }}</div>
                        @endforelse
                    </div>
                </div>

                {{-- Videos --}}
                <div
                    class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden md:col-span-2 xl:col-span-1">
                    <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2 flex items-center justify-between">
                        <span>{{ __('home.video_gallery') }}</span>
                        <a href="{{ route('client.videos.index') }}"
                            class="text-xs text-white/90 hover:text-white">{{ __('home.all') }}</a>
                    </div>

                    <div class="p-3 space-y-3">
                        @forelse($videos->take(3) as $v)
                            @php
                                $id = $ytId($v->youtube_url);
                                $thumb = $v->thumbnail_path
                                    ? $img($v->thumbnail_path)
                                    : ($id
                                        ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg"
                                        : null);
                            @endphp

                            <a href="{{ route('client.videos.index') }}"
                                class="block border rounded overflow-hidden hover:bg-slate-50 transition">
                                <div class="aspect-video bg-slate-200 relative overflow-hidden">
                                    @if ($thumb)
                                        <img src="{{ $thumb }}" class="w-full h-full object-cover"
                                            alt="">
                                    @endif

                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div
                                            class="w-14 h-10 rounded bg-red-600/90 flex items-center justify-center shadow">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 text-sm font-medium line-clamp-2">{{ $v->title }}</div>
                            </a>
                        @empty
                            <div class="text-sm text-slate-500">{{ __('home.no_videos') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Albums row --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2 flex items-center justify-between">
                    <span>{{ __('home.photo_gallery') }}</span>
                    <a href="{{ route('client.gallery.index') }}"
                        class="text-xs text-white/90 hover:text-white">{{ __('home.all') }}</a>
                </div>

                <div class="p-3 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($albums->take(6) as $album)
                        <a href="{{ route('client.gallery.album', $album->id) }}"
                            class="border rounded p-4 hover:bg-slate-50">
                            <div class="text-sm font-semibold text-slate-800 line-clamp-2">{{ $album->title }}</div>
                            <div class="text-xs text-slate-500 mt-2">{{ __('home.album_view') }}</div>
                        </a>
                    @empty
                        <div class="sm:col-span-2 lg:col-span-3 text-sm text-slate-500">
                            {{ __('home.no_albums') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="lg:col-span-3 space-y-4">

            {{-- Important links --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">{{ __('home.important_links') }}
                </div>

                <div class="divide-y">
                    @if (count($quickLinks))
                        @foreach ($quickLinks as $l)
                            <a href="{{ $l['url'] ?? '#' }}" target="_blank" rel="noopener"
                                class="flex items-center justify-between px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-emerald-700">
                                <span>{{ $l['title'] ?? 'Link' }}</span>
                                <span>›</span>
                            </a>
                        @endforeach
                    @else
                        <a href="#" class="flex items-center justify-between px-3 py-2 text-sm hover:bg-slate-50">
                            <span>প্রধানমন্ত্রীর কার্যালয়</span><span>›</span>
                        </a>
                        <a href="#" class="flex items-center justify-between px-3 py-2 text-sm hover:bg-slate-50">
                            <span>শিক্ষা মন্ত্রণালয়</span><span>›</span>
                        </a>
                        <a href="#" class="flex items-center justify-between px-3 py-2 text-sm hover:bg-slate-50">
                            <span>All cadre PMIS</span><span>›</span>
                        </a>
                    @endif
                </div>

                <div class="p-3">
                    <a href="#" class="block text-center border rounded px-3 py-2 text-sm hover:bg-slate-50">
                        {{ __('home.all') }}
                    </a>
                </div>
            </div>

            {{-- Internal links --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">{{ __('home.internal_links') }}</div>

                <div class="divide-y text-sm">
                    @foreach ($internalItems as $item)
                        <a href="{{ $item['url'] ?? '#' }}" target="_blank" rel="noopener"
                            class="flex items-center justify-between px-3 py-2 hover:bg-slate-50">
                            <span>{{ $item['title'] ?? 'Link' }}</span>
                            <span>›</span>
                        </a>
                    @endforeach
                </div>

                <div class="p-3">
                    <a href="#"
                        class="block text-center border rounded px-3 py-2 text-sm hover:bg-slate-50">{{ __('home.all') }}</a>
                </div>
            </div>

            {{-- Helpline --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">{{ __('home.helpline') }}</div>

                <div class="p-3 space-y-2">
                    <div class="border rounded p-2 flex items-center justify-between text-sm">
                        <span>{{ __('home.national_service') }}</span><span class="font-bold text-emerald-700">333</span>
                    </div>
                    <div class="border rounded p-2 flex items-center justify-between text-sm">
                        <span>{{ __('home.emergency_service') }}</span><span class="font-bold text-rose-600">999</span>
                    </div>
                    <div class="border rounded p-2 flex items-center justify-between text-sm">
                        <span>{{ __('home.women_children') }}</span><span class="font-bold text-amber-700">109</span>
                    </div>
                    <div class="border rounded p-2 flex items-center justify-between text-sm">
                        <span>{{ __('home.dudok') }}</span><span class="font-bold text-sky-700">106</span>
                    </div>
                </div>
            </div>

            {{-- Facebook --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">
                    {{ __('home.national_facebook_page') }}</div>

                <div class="p-3">
                    <div class="border rounded bg-[#f5f8ff] p-6 text-center">
                        <div class="text-3xl mb-2">📘</div>
                        <div class="font-semibold text-slate-700">{{ __('home.follow_facebook') }}</div>
                    </div>
                </div>
            </div>

            {{-- Visitor --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">{{ __('home.visitor_counter') }}
                </div>
                <div class="p-3 text-sm text-slate-600">
                    <div class="border rounded p-3 text-center">{{ __('home.visitor_information_module') }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
