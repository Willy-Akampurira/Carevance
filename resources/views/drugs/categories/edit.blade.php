@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Edit Category</h2> 

    <!-- Back Button -->
    <a href="{{ route('drugs.categories.index') }}"
       class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
        Back to Categories
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Alerts -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-xl text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Form -->
    <form action="{{ route('drugs.categories.update', $category) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-medium text-gray-700">Category Name</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $category->name) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   required>
        </div>

        <div>
            <label for="description" class="block font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('drugs.categories.index') }}"
               class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
               Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-yellow-600 text-white text-xl rounded hover:bg-yellow-700">
                Update Category
            </button>
        </div>
    </form>
</div>
@endsection
