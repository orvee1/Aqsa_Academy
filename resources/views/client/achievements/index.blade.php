@extends('client.layouts.app')
@section('title', 'Achievements')

@section('content')
    <div class="bg-white border rounded shadow p-4">
        <div class="flex items-center justify-between mb-4">
            <div class="font-semibold text-lg">আমাদের অর্জন</div>
            <a class="text-sm text-teal-700 underline" href="{{ route('client.home') }}">Home</a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($achievements as $a)
                <div class="border rounded overflow-hidden bg-slate-50">
                    @if ($a->image_path)
                        <img src="{{ $a->image_path }}" class="w-full h-44 object-cover" alt="">
                    @else
                        <div class="h-44 bg-slate-100 flex items-center justify-center text-slate-500">No Image</div>
                    @endif
                    <div class="p-3">
                        <div class="font-semibold text-slate-800">{{ $a->title }}</div>
                        @if ($a->year)
                            <div class="text-xs text-slate-500 mt-1">Year: {{ $a->year }}</div>
                        @endif
                        @if ($a->description)
                            <div class="text-sm text-slate-600 mt-2">{{ $a->description }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-sm text-slate-500">No achievements found.</div>
            @endforelse
        </div>

        <div class="mt-5">{{ $achievements->links() }}</div>
    </div>
@endsection
