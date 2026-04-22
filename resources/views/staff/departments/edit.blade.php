@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Edit Department</h2> 
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

    <!-- Edit Department Form -->
    <form action="{{ route('staff.departments.update', $department->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Department Name -->
        <div>
            <label for="name" class="block text-xl font-medium text-gray-700">Department Name</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name', $department->name) }}"
                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm text-lg focus:border-green-500 focus:ring-green-500"
                   required>
        </div>

        <!-- Department Description -->
        <div>
            <label for="description" class="block text-xl font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm text-lg focus:border-green-500 focus:ring-green-500">{{ old('description', $department->description) }}</textarea>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('staff.departments.index') }}"
               class="px-4 py-2 bg-gray-500 text-white text-xl rounded hover:bg-gray-600">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Update Department
            </button>
        </div>
    </form>
</div>
@endsection
