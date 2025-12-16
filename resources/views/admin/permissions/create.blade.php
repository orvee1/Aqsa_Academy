@extends('tailwind.layouts.admin')
@section('title','Create Permission')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold">Create Permission</h2>
    <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 border rounded">Back</a>
</div>

<div class="bg-white rounded shadow p-5">
    <form method="POST" action="{{ route('admin.permissions.store') }}">
        @csrf
        @include('admin.permissions._form')
        <div class="mt-6 flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
            <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
