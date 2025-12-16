@extends('tailwind.layouts.admin')
@section('title', 'Edit Social Link')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Edit Social Link</h2>
        <a href="{{ route('admin.social-links.index') }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="bg-white rounded shadow p-5">
        <form method="POST" action="{{ route('admin.social-links.update', $social) }}">
            @csrf
            @method('PUT')
            @include('admin.footer.socials._form', ['social' => $social])
            <div class="mt-6 flex gap-2">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                <a href="{{ route('admin.social-links.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
