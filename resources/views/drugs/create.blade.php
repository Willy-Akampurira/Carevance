@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Add New Drug</h2> 

    <!-- Back Button -->
    <a href="{{ route('drugs.index') }}"
       class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
        Back to Drugs
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Alerts -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-xs text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add Drug Form -->
    <form action="{{ route('drugs.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Drug Name -->
        <div>
            <label for="name" class="block font-medium text-gray-700 text-xl">Drug Name</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   required>
        </div>

        <!-- Category -->
        <div>
            <label for="category_id" class="block font-medium text-gray-700 text-xl">Category</label>
            <select name="category_id" id="category_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500">
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Quantity -->
        <div>
            <label for="quantity" class="block font-medium text-gray-700 text-xl">Quantity</label>
            <input type="number" name="quantity" id="quantity"
                   value="{{ old('quantity') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   required>
        </div>

        <!-- Unit -->
        <div>
            <label for="unit" class="block font-medium text-gray-700 text-xl">Unit</label>
            <input type="text" name="unit" id="unit"
                   value="{{ old('unit') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   placeholder="e.g. litres, bottles, kg, pieces"
                   required>
        </div>

        <!-- Reserved -->
        <div>
            <label for="reserved" class="block font-medium text-gray-700 text-xl">Reserved</label>
            <select name="reserved" id="reserved"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500">
                <option value="0" {{ old('reserved') == '0' ? 'selected' : '' }}>No</option>
                <option value="1" {{ old('reserved') == '1' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>

        <!-- Expiry Date -->
        <div>
            <label for="expiry_date" class="block font-medium text-gray-700 text-xl">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date"
                   value="{{ old('expiry_date') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500">
        </div>

        <!-- Reorder Level -->
        <div>
            <label for="reorder_level" class="block font-medium text-gray-700 text-xl">Reorder Level</label>
            <input type="number" name="reorder_level" id="reorder_level"
                   value="{{ old('reorder_level') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   required>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block font-medium text-gray-700 text-xl">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('drugs.index') }}"
               class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
               Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Drug
            </button>
        </div>
    </form>
</div>
@endsection
