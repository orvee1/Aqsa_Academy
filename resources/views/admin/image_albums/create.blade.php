@extends('tailwind.layouts.admin')
@section('title', 'Create Album')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Create Album</h2>
        <a href="{{ route('admin.image-albums.index') }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="bg-white rounded shadow p-5">
        <form method="POST" action="{{ route('admin.image-albums.store') }}">
            @csrf
            @include('admin.image_albums._form')
            <div class="mt-6 flex gap-2">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
                <a href="{{ route('admin.image-albums.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
