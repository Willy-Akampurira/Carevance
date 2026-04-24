@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Invoice Settings</h2>

    <div class="flex space-x-3">
        <!-- Back to Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            Back to Dashboard
        </a>
    </div>
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

    <!-- Invoice Settings Form -->
    <form action="{{ route('settings.invoice.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Invoice Prefix -->
        <div>
            <label for="prefix" class="block text-xl font-medium text-gray-700">Invoice Prefix</label>
            <input type="text" name="prefix" id="prefix"
                   value="{{ \App\Models\Setting::getValue('invoice_prefix') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
            @error('prefix')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tax Percentage -->
        <div>
            <label for="tax" class="block text-xl font-medium text-gray-700">Tax / VAT (%)</label>
            <input type="number" step="0.01" name="tax" id="tax"
                   value="{{ \App\Models\Setting::getValue('invoice_tax') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
            @error('tax')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Currency -->
        <div>
            <label for="currency" class="block text-xl font-medium text-gray-700">Currency</label>
            <input type="text" name="currency" id="currency"
                   value="{{ \App\Models\Setting::getValue('invoice_currency') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
            @error('currency')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Footer Note -->
        <div>
            <label for="footer_note" class="block text-xl font-medium text-gray-700">Footer Note</label>
            <textarea name="footer_note" id="footer_note"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">{{ \App\Models\Setting::getValue('invoice_footer_note') }}</textarea>
            @error('footer_note')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Discount Toggle -->
        <div class="flex items-center">
            <input type="checkbox" name="discount" id="discount" value="1"
                   {{ \App\Models\Setting::getValue('invoice_discount') ? 'checked' : '' }}
                   class="h-5 w-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
            <label for="discount" class="ml-2 block text-xl text-gray-700">Enable Discounts</label>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Invoice Settings
            </button>
        </div>
    </form>
</div>
@endsection
