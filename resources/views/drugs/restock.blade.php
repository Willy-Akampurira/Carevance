@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Restock Drug: {{ $drug->name }}
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Restock Form -->
    <form action="{{ route('drugs.restock', $drug) }}" method="POST" class="space-y-6">
        @csrf

        <!-- Drug Snapshot -->
        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
            <h3 class="text-2xl font-semibold mb-3">Drug Details</h3>
            <p><strong>Name:</strong> {{ $drug->name }}</p>
            <p><strong>Category:</strong> {{ $drug->category?->name ?? '—' }}</p>
            <p><strong>Unit:</strong> {{ $drug->unit }}</p>
            <p><strong>Reorder Level:</strong> {{ $drug->reorder_level }}</p>
        </div>

        <!-- Quantity -->
        <div>
            <label for="quantity" class="block text-lg font-medium text-gray-700">Quantity</label>
            <input type="number" name="quantity" id="quantity" required min="1"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-lg focus:border-green-500 focus:ring-green-500">
            @error('quantity')
                <p class="text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Expiry Date -->
        <div>
            <label for="expiry_date" class="block text-lg font-medium text-gray-700">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-lg focus:border-green-500 focus:ring-green-500">
            @error('expiry_date')
                <p class="text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('drugs.index') }}"
               class="px-4 py-2 bg-gray-500 text-white text-lg rounded hover:bg-gray-600">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white text-lg rounded hover:bg-green-700">
                Restock
            </button>
        </div>
    </form>
</div>
@endsection
