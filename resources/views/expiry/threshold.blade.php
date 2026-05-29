{{-- resources/views/settings/expiry_threshold.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Expiry Threshold Settings
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

    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
        <p class="text-sm sm:text-base text-gray-700">
            Current Alert Parameter: 
            <span class="font-mono font-bold text-green-700 bg-green-100/60 px-2 py-0.5 rounded ml-1">
                {{ $current }} days
            </span>
        </p>
        <span class="text-xs text-gray-400 mt-1 block">
            Batches coming within this timeframe will be automatically marked as critical or pending expiry across inventory views.
        </span>
    </div>

    <form action="{{ route('expiry.updateThreshold') }}" method="POST" class="space-y-4 max-w-md">
        @csrf

        <div class="space-y-2">
            <label for="expiry_threshold" class="block text-sm sm:text-base font-medium text-gray-700">
                Set New Threshold Value (in days)
            </label>
            
            <div class="relative mt-1 max-w-[12rem] rounded-md shadow-sm">
                <input type="number" name="expiry_threshold" id="expiry_threshold"
                       value="{{ old('expiry_threshold', $current) }}"
                       class="w-full rounded-md border-gray-300 pr-12 text-sm sm:text-base focus:border-green-500 focus:ring-green-500 shadow-sm transition-colors"
                       min="1" max="365" required>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <span class="text-xs sm:text-sm text-gray-400 font-medium">days</span>
                </div>
            </div>

            @error('expiry_threshold')
                <p class="mt-1.5 text-xs sm:text-sm text-red-600 font-medium flex items-center gap-1">
                    <span class="inline-block w-1 h-1 rounded-full bg-red-600"></span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full sm:w-auto text-center px-4 py-2 bg-green-600 text-white font-medium text-sm sm:text-base rounded-md shadow-sm hover:bg-green-700 active:scale-98 transition-all duration-150">
                Update Threshold
            </button>
        </div>
    </form>
</div>
@endsection