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
            return asset('storage/' . $path);
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
    @endphp

    <div class="grid lg:grid-cols-12 gap-4">

        {{-- LEFT MAIN --}}
        <div class="lg:col-span-9 space-y-4">

            {{-- Slider --}}
            <div class="bg-white border rounded shadow overflow-hidden">
                @php $first = $sliders?->first(); @endphp
                @if ($first && $img($first->image_path))
                    <img src="{{ $img($first->image_path) }}" class="w-full h-[360px] object-cover" alt="">
                @else
                    <div class="h-[360px] flex items-center justify-center text-slate-500">No slider image</div>
                @endif
            </div>

            {{-- Notice board --}}
            <div class="bg-white border rounded shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-semibold">নোটিশ বোর্ড</div>
                    <a href="{{ route('client.notices.index') }}" class="text-sm text-emerald-700 hover:underline">সব
                        নোটিশ</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-600 border-b">
                                <th class="py-2">শিরোনাম</th>
                                <th class="py-2 w-28">তারিখ</th>
                                <th class="py-2 w-24 text-right">ভিউ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($notices as $n)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-2">
                                        <a class="hover:underline" href="{{ route('client.notices.show', $n->slug) }}">
                                            {{ $n->title }}
                                            @if ($n->is_pinned)
                                                <span class="ml-1 text-xs text-rose-600">★</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="py-2 text-slate-600">
                                        {{ $n->published_at?->format('d.m.Y') ?? $n->created_at->format('d.m.Y') }}
                                    </td>
                                    <td class="py-2 text-right">
                                        <a class="inline-flex items-center justify-center px-3 py-1.5 rounded bg-emerald-600 hover:bg-emerald-700 text-white text-xs"
                                            href="{{ route('client.notices.show', $n->slug) }}">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-3 text-slate-500">No notices found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Upcoming events --}}
            <div class="bg-white border rounded shadow p-4">
                <div class="font-semibold text-center mb-3">Upcoming Events</div>

                <div class="grid sm:grid-cols-2 gap-3">
                    @forelse($events as $e)
                        <a href="{{ route('client.events.show', $e->slug) }}"
                            class="border rounded overflow-hidden hover:bg-slate-50 transition">
                            <div class="h-32 bg-slate-100 overflow-hidden">
                                @if ($e->cover_image_path && $img($e->cover_image_path))
                                    <img src="{{ $img($e->cover_image_path) }}" class="w-full h-full object-cover"
                                        alt="">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">Event
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <div class="text-sm font-semibold leading-5">{{ $e->title }}</div>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ \Carbon\Carbon::parse($e->event_date)->format('d F') }}
                                    @if ($e->event_time)
                                        • {{ \Carbon\Carbon::parse($e->event_time)->format('h:i A') }}
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-sm text-slate-500 col-span-2 text-center">No upcoming events.</div>
                    @endforelse
                </div>
            </div>

            {{-- Achievements --}}
            <div class="bg-white border rounded shadow p-4">
                <div class="font-semibold text-center mb-3">আমাদের অর্জন</div>

                <div class="grid sm:grid-cols-3 gap-3">
                    @forelse($achievements as $a)
                        <div class="border rounded overflow-hidden bg-white">
                            <div class="h-28 bg-slate-100">
                                @if ($a->image_path && $img($a->image_path))
                                    <img class="w-full h-full object-cover" src="{{ $img($a->image_path) }}"
                                        alt="">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">No
                                        image</div>
                                @endif
                            </div>
                            <div class="p-2 text-xs text-slate-700 leading-5">
                                <div class="font-semibold">{{ $a->title }}</div>
                                @if ($a->year)
                                    <div class="text-slate-500">{{ $a->year }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500 col-span-3 text-center">No achievements.</div>
                    @endforelse
                </div>

                <div class="mt-4 flex justify-center">
                    <a href="{{ route('client.achievements.index') }}"
                        class="px-6 py-2 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold">
                        সব অর্জন দেখুন
                    </a>
                </div>
            </div>

            {{-- Videos --}}
            <div class="bg-white border rounded shadow p-4">
                <div class="font-semibold text-center mb-3">ভিডিও গ্যালারী</div>

                <div class="grid sm:grid-cols-3 gap-3">
                    @forelse($videos as $v)
                        @php
                            $id = $ytId($v->youtube_url);
                            $thumb = $v->thumbnail_path
                                ? $img($v->thumbnail_path)
                                : ($id
                                    ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg"
                                    : null);
                        @endphp

                        <a href="{{ route('client.videos.index') }}"
                            class="border rounded overflow-hidden hover:bg-slate-50 transition">
                            <div class="aspect-video bg-slate-200 relative overflow-hidden">
                                @if ($thumb)
                                    <img src="{{ $thumb }}" class="w-full h-full object-cover" alt="">
                                @endif
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-14 h-10 rounded bg-red-600/90 flex items-center justify-center shadow">
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
                        <div class="text-sm text-slate-500 col-span-3 text-center">No videos.</div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="lg:col-span-3 space-y-4">

            {{-- Statement --}}
            <div class="bg-white border rounded shadow p-3">
                <div class="text-sm font-semibold mb-2">অধ্যক্ষের বাণী</div>

                @if ($statement)
                    @if ($statement->author_photo_path && $img($statement->author_photo_path))
                        <img src="{{ $img($statement->author_photo_path) }}" class="w-full rounded border mb-2"
                            alt="">
                    @endif

                    <div class="text-xs text-slate-700 leading-5">
                        {!! Str::limit(strip_tags($statement->body), 600, '...') !!}
                    </div>

                    <div class="mt-3 text-xs text-slate-600">
                        <div class="font-semibold">{{ $statement->author_name ?? '' }}</div>
                        <div>{{ $statement->author_designation ?? '' }}</div>
                    </div>
                @else
                    <div class="text-sm text-slate-500">No statement set.</div>
                @endif
            </div>

            {{-- Helplines --}}
            <div class="bg-white border rounded shadow p-3">
                <div class="text-sm font-semibold mb-2">সরকারি হেল্পলাইন</div>
                <div class="space-y-2">
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

            {{-- Important links --}}
            <div class="bg-white border rounded shadow p-3">
                <div class="text-sm font-semibold mb-2">গুরুত্বপূর্ণ লিংক</div>

                @php $links = $importantLinks ?? []; @endphp

                @if (count($links))
                    <div class="space-y-2 text-sm">
                        @foreach ($links as $l)
                            <a class="block hover:underline" href="{{ $l['url'] ?? '#' }}" target="_blank">
                                {{ $l['title'] ?? ($l['label'] ?? 'Link') }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="space-y-2 text-sm">
                        <a class="block hover:underline" href="#" target="_blank">শিক্ষা বোর্ড</a>
                        <a class="block hover:underline" href="#" target="_blank">EMIS</a>
                        <a class="block hover:underline" href="#" target="_blank">মন্ত্রণালয়</a>
                        <a class="block hover:underline" href="#" target="_blank">ফলাফল</a>
                        <a class="block hover:underline" href="#" target="_blank">শিক্ষক বাতায়ন</a>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
