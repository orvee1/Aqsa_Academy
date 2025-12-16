@extends('tailwind.layouts.admin')
@section('title', 'Edit Post Category')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Edit Post Category</h2>
        <a href="{{ route('admin.post-categories.index') }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="bg-white rounded shadow p-5">
        <form method="POST" action="{{ route('admin.post-categories.update', $category) }}">
            @csrf
            @method('PUT')

            @include('admin.post_categories._form', ['category' => $category])

            <div class="mt-6 flex gap-2">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                <a href="{{ route('admin.post-categories.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
