@extends('tailwind.layouts.admin')
@section('title', 'Edit Footer Link')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Edit Footer Link</h2>
        <a href="{{ route('admin.footer-links.index') }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="bg-white rounded shadow p-5">
        <form method="POST" action="{{ route('admin.footer-links.update', $link) }}">
            @csrf
            @method('PUT')
            @include('admin.footer.links._form', ['link' => $link])
            <div class="mt-6 flex gap-2">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                <a href="{{ route('admin.footer-links.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
