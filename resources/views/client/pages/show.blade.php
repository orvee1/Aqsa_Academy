@extends('client.layouts.app')
@section('title', $page->title)

@section('content')
    <div class="bg-white border rounded shadow p-5">
        <h1 class="text-xl font-bold text-slate-800">{{ $page->title }}</h1>
        <div class="text-sm text-slate-500 mt-1">
            প্রকাশ: {{ optional($page->published_at)->format('d M Y') ?? $page->created_at->format('d M Y') }}
        </div>

        <div class="prose max-w-none mt-5">
            {!! $page->content !!}
        </div>
    </div>
@endsection
