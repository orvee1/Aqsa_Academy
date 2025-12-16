@extends('tailwind.layouts.admin')
@section('title', 'Edit Statement')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Edit Statement</h2>
        <a href="{{ route('admin.statements.index') }}" class="px-4 py-2 border rounded">Back</a>
    </div>

    <div class="bg-white rounded shadow p-5">
        <form method="POST" action="{{ route('admin.statements.update', $statement) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.statements._form', ['statement' => $statement])
            <div class="mt-6 flex gap-2">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                <a href="{{ route('admin.statements.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
