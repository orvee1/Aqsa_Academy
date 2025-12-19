@extends('client.layouts.app')
@section('title', 'Notice Board')

@section('content')
    <div class="bg-white border rounded shadow p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="font-semibold text-lg">নোটিশ বোর্ড</div>
            <a class="text-sm text-teal-700 underline" href="{{ route('client.home') }}">Home</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-600">
                        <th class="py-2">শিরোনাম</th>
                        <th class="py-2 w-32">তারিখ</th>
                        <th class="py-2 w-32 text-right">অ্যাকশন</th>
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

        <div class="mt-4">
            {{ $notices->links() }}
        </div>
    </div>
@endsection
