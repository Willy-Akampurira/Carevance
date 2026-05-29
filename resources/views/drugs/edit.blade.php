{{-- resources/views/drugs/edit.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Edit Drug Profile
    </h2> 

    <a href="{{ route('drugs.index') }}"
       class="w-full sm:w-auto text-center px-4 py-2 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors shadow-sm">
        Back to Drugs
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

    <form action="{{ route('drugs.update', $drug) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <div>
                <label for="name" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Drug Name</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $drug->name) }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm"
                       required>
            </div>

            <div>
                <label for="category_id" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Category</label>
                <select name="category_id" id="category_id"
                        class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ old('category_id', $drug->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="quantity" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Stock Quantity</label>
                <input type="number" name="quantity" id="quantity"
                       value="{{ old('quantity', $drug->quantity) }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm"
                       required>
            </div>

            <div>
                <label for="unit" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Stock Unit</label>
                <input type="text" name="unit" id="unit"
                       value="{{ old('unit', $drug->unit) }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm"
                       placeholder="e.g. litres, bottles, kg, pieces"
                       required>
            </div>

            <div>
                <label for="reserved" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Reserved Status</label>
                <select name="reserved" id="reserved"
                        class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
                    <option value="0" {{ old('reserved', $drug->reserved) == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('reserved', $drug->reserved) == 1 ? 'selected' : '' }}>Yes</option>
                </select>
            </div>

            <div>
                <label for="expiry_date" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry_date"
                       value="{{ old('expiry_date', $drug->expiry_date ? \Carbon\Carbon::parse($drug->expiry_date)->format('Y-m-d') : '') }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>

            <div class="md:col-span-2">
                <label for="reorder_level" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Reorder Threshold Alert Level</label>
                <input type="number" name="reorder_level" id="reorder_level"
                       value="{{ old('reorder_level', $drug->reorder_level) }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm"
                       required>
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Description / Composition Details</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">{{ old('description', $drug->description) }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
            <a href="{{ route('drugs.index') }}"
               class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="w-full sm:w-auto text-center px-6 py-2.5 bg-yellow-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-yellow-700 active:scale-98 transition-all duration-150">
                Update Drug Profile
            </button>
        </div>
    </form>
</div>
@endsection