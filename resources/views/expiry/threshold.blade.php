@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Expiry Threshold Settings
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 p-4 rounded bg-green-100 text-green-700 text-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Current Threshold -->
    <div class="mb-6">
        <p class="text-xl text-gray-700">
            Current threshold: 
            <span class="font-semibold text-green-700">{{ $current }} days</span>
        </p>
    </div>

    <!-- Update Form -->
    <form action="{{ route('expiry.updateThreshold') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="expiry_threshold" class="block text-lg font-medium text-gray-700">
                Set New Threshold (days)
            </label>
            <input type="number" name="expiry_threshold" id="expiry_threshold"
                   value="{{ old('expiry_threshold', $current) }}"
                   class="mt-2 w-40 px-3 py-2 border rounded-md text-lg focus:ring-green-500 focus:border-green-500"
                   min="1" max="365" required>
            @error('expiry_threshold')
                <p class="mt-2 text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-lg rounded-md hover:bg-green-700 transition">
                Update Threshold
            </button>
        </div>
    </form>
</div>
@endsection
