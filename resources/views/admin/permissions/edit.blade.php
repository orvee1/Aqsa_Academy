@extends('tailwind.layouts.admin')
@section('title','Edit Permission')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold">Edit Permission</h2>
    <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 border rounded">Back</a>
</div>

<div class="bg-white rounded shadow p-5">
    <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
        @csrf
        @method('PUT')
        @include('admin.permissions._form', ['permission' => $permission])
        <div class="mt-6 flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
            <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection

