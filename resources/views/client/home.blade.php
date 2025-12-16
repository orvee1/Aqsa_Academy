@extends('client.layouts.app')
@section('title', 'Home')

@section('content')
    <div class="grid lg:grid-cols-12 gap-4">

        {{-- Left main --}}
        <div class="lg:col-span-9 space-y-4">

            {{-- Slider area --}}
            <div class="bg-white border rounded shadow overflow-hidden">
                @php $first = $sliders->first(); @endphp
                @if ($first)
                    <img src="{{ $first->image_path }}" class="w-full h-[360px] object-cover" alt="">
                @else
                    <div class="h-[360px] flex items-center justify-center text-slate-500">No slider image</div>
                @endif
            </div>

            {{-- Notice board block --}}
            <div class="bg-white border rounded shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-semibold">নোটিশ বোর্ড</div>
                    <a href="{{ route('client.notices.index') }}" class="text-sm text-teal-700 underline">সব নোটিশ</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-600">
                                <th class="py-2">শিরোনাম</th>
                                <th class="py-2 w-32">তারিখ</th>
                                <th class="py-2 w-24 text-right">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($notices as $n)
                                <tr>
                                    <td class="py-2">
                                        <a class="hover:underline" href="{{ route('client.notices.show', $n->slug) }}">
                                            {{ $n->title }}
                                            @if ($n->is_pinned)
                                                <span class="text-xs text-rose-600">★</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="py-2 text-slate-600">
                                        {{ optional($n->published_at)->format('d.m.Y') ?? $n->created_at->format('d.m.Y') }}
                                    </td>
                                    <td class="py-2 text-right">
                                        <a class="px-3 py-1.5 rounded bg-emerald-600 text-white text-xs"
                                            href="{{ route('client.notices.show', $n->slug) }}">View</a>
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
                <div class="font-semibold mb-3 text-center">Upcoming Events</div>
                <div class="grid sm:grid-cols-2 gap-3">
                    @forelse($events as $e)
                        <a href="{{ route('client.events.show', $e->slug) }}" class="border rounded p-3 hover:bg-slate-50">
                            <div class="font-medium">{{ $e->title }}</div>
                            <div class="text-xs text-slate-500 mt-1">
                                {{ \Carbon\Carbon::parse($e->event_date)->format('d M, Y') }}
                                @if ($e->event_time)
                                    • {{ \Carbon\Carbon::parse($e->event_time)->format('h:i A') }}
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-sm text-slate-500 col-span-2 text-center">No upcoming events.</div>
                    @endforelse
                </div>
            </div>

            {{-- Achievements --}}
            <div class="bg-white border rounded shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-semibold">আমাদের অর্জন</div>
                    <a href="{{ route('client.achievements.index') }}" class="text-sm text-teal-700 underline">সব অর্জন</a>
                </div>

                <div class="grid sm:grid-cols-3 gap-3">
                    @foreach ($achievements as $a)
                        <div class="border rounded overflow-hidden bg-slate-50">
                            @if ($a->image_path)
                                <img class="w-full h-32 object-cover" src="{{ $a->image_path }}" alt="">
                            @else
                                <div class="h-32 flex items-center justify-center text-slate-500">No image</div>
                            @endif
                            <div class="p-2 text-sm font-medium">{{ $a->title }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Video gallery --}}
            <div class="bg-white border rounded shadow p-4">
                <div class="font-semibold mb-3 text-center">ভিডিও গ্যালারী</div>
                <div class="grid sm:grid-cols-3 gap-3">
                    @foreach ($videos as $v)
                        <div class="border rounded overflow-hidden">
                            <div class="aspect-video bg-black">
                                <iframe class="w-full h-full"
                                    src="{{ \Illuminate\Support\Str::contains($v->youtube_url, 'embed')
                                        ? $v->youtube_url
                                        : 'https://www.youtube.com/embed/' . \Illuminate\Support\Str::after($v->youtube_url, 'v=') }}"
                                    title="{{ $v->title }}" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                            <div class="p-2 text-sm font-medium">{{ $v->title }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Right sidebar --}}
        <div class="lg:col-span-3 space-y-4">

            {{-- Statement --}}
            <div class="bg-white border rounded shadow p-3">
                <div class="text-sm font-semibold mb-2">অধ্যক্ষের বাণী</div>

                @if ($statement)
                    @if ($statement->author_photo_path)
                        <img src="{{ $statement->author_photo_path }}" class="w-full rounded mb-2" alt="">
                    @endif

                    <div class="text-sm text-slate-700 whitespace-pre-line">{!! nl2br(e($statement->body)) !!}</div>

                    <div class="mt-3 text-xs text-slate-600">
                        <div class="font-semibold">{{ $statement->author_name ?? '' }}</div>
                        <div>{{ $statement->author_designation ?? '' }}</div>
                    </div>
                @else
                    <div class="text-sm text-slate-500">No statement set.</div>
                @endif
            </div>

            {{-- Govt helplines (static placeholders; আপনি image replace করবেন) --}}
            <div class="bg-white border rounded shadow p-3">
                <div class="text-sm font-semibold mb-2">সরকারি হেল্পলাইন</div>
                <div class="space-y-2 text-sm text-slate-700">
                    <div class="border rounded p-2">333 (National Helpline)</div>
                    <div class="border rounded p-2">999 (Emergency)</div>
                    <div class="border rounded p-2">109 (Women & Children)</div>
                    <div class="border rounded p-2">106 (Anti-corruption)</div>
                </div>
            </div>

            {{-- Important links --}}
            <div class="bg-white border rounded shadow p-3">
                <div class="text-sm font-semibold mb-2">গুরুত্বপূর্ণ লিংক</div>
                <div class="space-y-2 text-sm">
                    <a class="block hover:underline" href="#" target="_blank">শিক্ষা বোর্ড</a>
                    <a class="block hover:underline" href="#" target="_blank">EMIS</a>
                    <a class="block hover:underline" href="#" target="_blank">মন্ত্রণালয়</a>
                </div>
            </div>

        </div>
    </div>
@endsection
