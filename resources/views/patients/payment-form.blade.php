{{-- resources/views/patients/payment_form.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Record Payment
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto max-w-2xl bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">
    
    <div>
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 pb-1 border-b border-gray-100">
            Invoice Details: <span class="font-mono text-green-700 bg-green-50 px-1.5 py-0.5 rounded">{{ $invoice->invoice_number }}</span>
        </h3>

        <div class="mb-4 p-3 bg-gray-50 rounded-md border border-gray-100">
            <p class="text-sm sm:text-base text-gray-700">
                Outstanding Balance: 
                <span class="font-bold text-red-600 text-base sm:text-lg">UGX {{ number_format($balance, 2) }}</span>
            </p>
        </div>

        @if($invoice->payments->count())
            <div class="mb-6">
                <h4 class="text-sm sm:text-base font-semibold text-gray-800 mb-2">Previous Payments:</h4>
                <ul class="list-disc pl-5 text-xs sm:text-sm text-gray-600 space-y-1">
                    @foreach($invoice->payments as $payment)
                        <li>
                            <span class="font-semibold text-gray-700">UGX {{ number_format($payment->amount, 2) }}</span> 
                            via {{ $payment->payment_method }} on {{ is_string($payment->payment_date) ? substr($payment->payment_date, 0, 10) : $payment->payment_date->format('Y-m-d') }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('patients.billing.pay', $invoice->id) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Amount</label>
                <input type="number" step="0.01" name="amount" max="{{ $balance }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm"
                       placeholder="0.00" required>
                @error('amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Payment Method</label>
                <input type="text" name="payment_method"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm"
                       placeholder="Cash, Mobile Money, Bank Transfer..." required>
                @error('payment_method') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Payment Date</label>
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm"
                       required>
                @error('payment_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('patients.billing') }}"
                   class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                    <i class="fas fa-check-circle mr-1.5 opacity-90"></i> Save Payment
                </button>
            </div>
        </form>
    </div>

</div>
@endsection