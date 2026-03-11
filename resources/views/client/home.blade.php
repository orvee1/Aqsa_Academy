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

            {{-- Top 3 blocks --}}
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
                                No slider image
                            </div>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-3">
                    <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden h-full">
                        <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">বাণী</div>

                        <div class="p-3">
                            @if ($statement)
                                <div class="text-sm font-semibold text-slate-800">
                                    {{ $statement->author_name ?? 'কর্তৃপক্ষ' }}
                                </div>

                                <div class="text-xs text-slate-500 mb-2">
                                    {{ $statement->author_designation ?? '' }}
                                </div>

                                <div class="text-sm text-slate-700 leading-6">
                                    {!! Str::limit(strip_tags($statement->body), 320, '...') !!}
                                </div>
                            @else
                                <div class="text-sm text-slate-500">No statement set.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden h-full">
                        <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">সম্মানিত প্রধান</div>

                        <div class="p-3">
                            @if ($statement && $statement->author_photo_path && $img($statement->author_photo_path))
                                <img src="{{ $img($statement->author_photo_path) }}"
                                    class="w-full h-[220px] object-cover border rounded" alt="">
                            @else
                                <div
                                    class="w-full h-[220px] border rounded bg-slate-100 flex items-center justify-center text-slate-400">
                                    No photo
                                </div>
                            @endif

                            <div class="mt-3 text-sm font-semibold text-slate-800 leading-5">
                                {{ $statement->author_name ?? 'অধ্যক্ষ / প্রধান শিক্ষক' }}
                            </div>

                            <div class="text-xs text-slate-600 mt-1">
                                {{ $statement->author_designation ?? 'প্রতিষ্ঠান প্রধান' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notice --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white px-3 py-2 flex items-center justify-between">
                    <div class="font-semibold">নোটিশ বোর্ড</div>
                    <a href="{{ route('client.notices.index') }}"
                        class="text-xs bg-white text-emerald-700 px-3 py-1 rounded hover:bg-slate-100">
                        সকল নোটিশ দেখুন
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
                                            <span class="px-2 py-0.5 rounded bg-red-500 text-white">নতুন</span>
                                        @endif

                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600">সাধারণ</span>
                                    </div>
                                </div>

                                <a href="{{ route('client.notices.show', $n->slug) }}"
                                    class="text-slate-400 hover:text-emerald-700">
                                    ›
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">No notices found.</div>
                    @endforelse
                </div>
            </div>

            {{-- Quick cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <a href="{{ route('client.notices.index') }}"
                    class="bg-white border border-slate-300 rounded-sm shadow-sm p-4 hover:bg-slate-50 transition">
                    <div class="text-sm font-semibold text-slate-800">নোটিশ</div>
                    <div class="text-xs text-slate-500 mt-1">সর্বশেষ সকল নোটিশ দেখুন</div>
                </a>

                <a href="{{ route('client.events.index') }}"
                    class="bg-white border border-slate-300 rounded-sm shadow-sm p-4 hover:bg-slate-50 transition">
                    <div class="text-sm font-semibold text-slate-800">ইভেন্ট</div>
                    <div class="text-xs text-slate-500 mt-1">আসন্ন কার্যক্রম ও অনুষ্ঠান</div>
                </a>

                <a href="{{ route('client.achievements.index') }}"
                    class="bg-white border border-slate-300 rounded-sm shadow-sm p-4 hover:bg-slate-50 transition">
                    <div class="text-sm font-semibold text-slate-800">অর্জন</div>
                    <div class="text-xs text-slate-500 mt-1">প্রতিষ্ঠানের সাফল্য ও স্বীকৃতি</div>
                </a>

                <a href="{{ route('client.gallery.index') }}"
                    class="bg-white border border-slate-300 rounded-sm shadow-sm p-4 hover:bg-slate-50 transition">
                    <div class="text-sm font-semibold text-slate-800">ফটো গ্যালারী</div>
                    <div class="text-xs text-slate-500 mt-1">অ্যালবাম ও ছবি দেখুন</div>
                </a>
            </div>

            {{-- Main content rows --}}
            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">

                {{-- Events --}}
                <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                    <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2 flex items-center justify-between">
                        <span>Upcoming Events</span>
                        <a href="{{ route('client.events.index') }}" class="text-xs text-white/90 hover:text-white">সকল</a>
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
                            <div class="text-sm text-slate-500">No upcoming events.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Achievements --}}
                <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                    <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2 flex items-center justify-between">
                        <span>আমাদের অর্জন</span>
                        <a href="{{ route('client.achievements.index') }}"
                            class="text-xs text-white/90 hover:text-white">সকল</a>
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
                                            No image
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
                            <div class="text-sm text-slate-500">No achievements.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Videos --}}
                <div
                    class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden md:col-span-2 xl:col-span-1">
                    <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2 flex items-center justify-between">
                        <span>ভিডিও গ্যালারী</span>
                        <a href="{{ route('client.videos.index') }}" class="text-xs text-white/90 hover:text-white">সকল</a>
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
                            <div class="text-sm text-slate-500">No videos.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Albums row --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2 flex items-center justify-between">
                    <span>ফটো গ্যালারী</span>
                    <a href="{{ route('client.gallery.index') }}" class="text-xs text-white/90 hover:text-white">সকল</a>
                </div>

                <div class="p-3 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($albums->take(6) as $album)
                        <a href="{{ route('client.gallery.album', $album->id) }}"
                            class="border rounded p-4 hover:bg-slate-50">
                            <div class="text-sm font-semibold text-slate-800 line-clamp-2">{{ $album->title }}</div>
                            <div class="text-xs text-slate-500 mt-2">অ্যালবাম দেখুন</div>
                        </a>
                    @empty
                        <div class="sm:col-span-2 lg:col-span-3 text-sm text-slate-500">
                            No gallery album found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="lg:col-span-3 space-y-4">

            {{-- Important links --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">গুরুত্বপূর্ণ লিংক</div>

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
                        সকল
                    </a>
                </div>
            </div>

            {{-- Internal links --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">অভ্যন্তরীণ লিংকসমূহ</div>

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
                    <a href="#" class="block text-center border rounded px-3 py-2 text-sm hover:bg-slate-50">সকল</a>
                </div>
            </div>

            {{-- Helpline --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">সরকারি হেল্পলাইন</div>

                <div class="p-3 space-y-2">
                    <div class="border rounded p-2 flex items-center justify-between text-sm">
                        <span>জাতীয় সেবা</span><span class="font-bold text-emerald-700">333</span>
                    </div>
                    <div class="border rounded p-2 flex items-center justify-between text-sm">
                        <span>জরুরি সেবা</span><span class="font-bold text-rose-600">999</span>
                    </div>
                    <div class="border rounded p-2 flex items-center justify-between text-sm">
                        <span>নারী ও শিশু</span><span class="font-bold text-amber-700">109</span>
                    </div>
                    <div class="border rounded p-2 flex items-center justify-between text-sm">
                        <span>দুদক</span><span class="font-bold text-sky-700">106</span>
                    </div>
                </div>
            </div>

            {{-- Facebook --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">জাতীয় ফেসবুক পেজ</div>

                <div class="p-3">
                    <div class="border rounded bg-[#f5f8ff] p-6 text-center">
                        <div class="text-3xl mb-2">📘</div>
                        <div class="font-semibold text-slate-700">Follow us on Facebook</div>
                    </div>
                </div>
            </div>

            {{-- Visitor --}}
            <div class="bg-white border border-slate-300 rounded-sm shadow-sm overflow-hidden">
                <div class="bg-[#0b8f3a] text-white text-sm font-semibold px-3 py-2">ভিজিটর কাউন্টার</div>
                <div class="p-3 text-sm text-slate-600">
                    <div class="border rounded p-3 text-center">ভিজিটর ইনফরমেশন মডিউল</div>
                </div>
            </div>
        </div>
    </div>
@endsection
