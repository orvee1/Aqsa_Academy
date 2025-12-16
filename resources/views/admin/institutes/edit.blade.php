@extends('tailwind.layouts.admin')
@section('title','Edit Institute')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold">Edit Institute</h2>
    <a href="{{ route('admin.institutes.index') }}" class="px-4 py-2 border rounded">Back</a>
</div>

<div class="bg-white rounded shadow p-5">
    <form method="POST" action="{{ route('admin.institutes.update', $institute) }}">
        @csrf
        @method('PUT')
        @include('admin.institutes._form', ['institute' => $institute])
        <div class="mt-6 flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
            <a href="{{ route('admin.institutes.index') }}" class="px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection

