@extends('tailwind.layouts.admin')
@section('title', 'Create Post Category')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Create Post Category</h2>
        <a href="{{ route('admin.post-categories.index') }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="bg-white rounded shadow p-5">
        <form method="POST" action="{{ route('admin.post-categories.store') }}">
            @csrf

            @include('admin.post_categories._form')

            <div class="mt-6 flex gap-2">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
                <a href="{{ route('admin.post-categories.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
