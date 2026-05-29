{{-- resources/views/drugs/restock.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Restock Drug: {{ $drug->name }}
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">

    @if(session('success'))
        <div class="p-3 bg-green-50 border border-green-200 text-sm sm:text-base text-green-800 rounded-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('drugs.restock', $drug) }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-gray-50 border border-gray-100 p-4 rounded-md shadow-sm space-y-3">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 border-b border-gray-200 pb-1.5">
                Target Drug Reference Details
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm sm:text-base text-gray-700">
                <p><strong class="font-medium text-gray-900">Name:</strong> {{ $drug->name }}</p>
                <p><strong class="font-medium text-gray-900">Category:</strong> {{ $drug->category?->name ?? '—' }}</p>
                <p><strong class="font-medium text-gray-900">Dispensing Unit:</strong> <span class="font-mono text-xs bg-gray-200/60 px-1 py-0.5 rounded uppercase font-semibold text-gray-800">{{ $drug->unit }}</span></p>
                <p><strong class="font-medium text-gray-900">Reorder Threshold:</strong> {{ $drug->reorder_level }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="quantity" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Restock Quantity</label>
                <input type="number" name="quantity" id="quantity" required min="1" placeholder="e.g. 50"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
                @error('quantity')
                    <p class="text-red-600 text-xs sm:text-sm mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="expiry_date" class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Batch Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry_date"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
                @error('expiry_date')
                    <p class="text-red-600 text-xs sm:text-sm mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
            <a href="{{ route('drugs.index') }}"
               class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                Process Restock
            </button>
        </div>
    </form>
</div>
@endsection