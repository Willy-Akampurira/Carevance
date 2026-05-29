{{-- resources/views/drugs/categories/edit.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Edit Category
    </h2> 

    <a href="{{ route('drugs.categories.index') }}"
       class="w-full sm:w-auto text-center px-4 py-2 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors shadow-sm">
        Back to Categories
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">

    @if ($errors->any())
        <div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded-md shadow-sm">
            <ul class="list-disc pl-5 space-y-1 text-sm sm:text-base">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('drugs.categories.update', $category) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Category Name</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $category->name) }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm"
                       required>
            </div>

            <div>
                <label for="description" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">{{ old('description', $category->description) }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
            <a href="{{ route('drugs.categories.index') }}"
               class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="w-full sm:w-auto text-center px-6 py-2.5 bg-yellow-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-yellow-700 active:scale-98 transition-all duration-150">
                Update Category
            </button>
        </div>
    </form>
</div>
@endsection