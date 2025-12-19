@extends('client.layouts.app')
@section('title', $notice->title)

@section('content')
    <div class="bg-white border rounded shadow p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-800">{{ $notice->title }}</h1>
                <div class="text-sm text-slate-500 mt-1">
                    প্রকাশ: {{ optional($notice->published_at)->format('d M Y') ?? $notice->created_at->format('d M Y') }}
                </div>
            </div>
            <a href="{{ route('client.notices.index') }}" class="px-3 py-2 border rounded text-sm hover:bg-slate-50">Back</a>
        </div>

        @if ($notice->file_path)
            <div class="mt-4 p-3 rounded bg-slate-50 border flex items-center justify-between gap-3">
                <div class="text-sm text-slate-700 truncate">Attachment: {{ basename($notice->file_path) }}</div>
                <a class="px-3 py-1.5 rounded bg-emerald-600 text-white text-xs" target="_blank"
                    href="{{ $notice->file_path }}">
                    Download / View
                </a>
            </div>
        @endif

        <div class="prose max-w-none mt-5">
            {!! $notice->body !!}
        </div>
    </div>
@endsection
