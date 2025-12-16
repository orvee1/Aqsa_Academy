@extends('tailwind.layouts.admin')
@section('title', 'Edit Menu')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold">Edit Menu</h2>
        <a href="{{ route('admin.menus.index') }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="bg-white rounded shadow p-5">
        <form method="POST" action="{{ route('admin.menus.update', $menu) }}">
            @csrf
            @method('PUT')
            @include('admin.menus._form', ['menu' => $menu])
            <div class="mt-6 flex gap-2">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
                <a href="{{ route('admin.menus.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
