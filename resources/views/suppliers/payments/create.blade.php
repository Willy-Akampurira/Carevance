{{-- resources/views/suppliers/payments/create.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        @if($invoice)
            Record Payment — Invoice {{ $invoice->invoice_number }}
        @else
            Record Payment — Invoice #{{ $invoiceId }}
        @endif
    </h2>
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

    <form method="POST" action="{{ route('suppliers.invoices.payments.store', [$supplierId, $invoiceId]) }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Payment Date</label>
                <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Amount Cleared</label>
                <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" required placeholder="0.00"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="method" class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
                    @foreach(['cash','bank_transfer','mobile_money','cheque'] as $m)
                        <option value="{{ $m }}" {{ old('method') === $m ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ', $m)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Transaction Reference</label>
                <input type="text" name="reference" value="{{ old('reference') }}" placeholder="e.g. TXN-10023490"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Notes / Ledger Remarks</label>
                <textarea name="notes" rows="3" placeholder="Provide extra transaction notes, receipt breakdowns, or clearance details..."
                          class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
            <a href="{{ route('suppliers.invoices.payments.index', [$supplierId, $invoiceId]) }}" 
               class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                Save Payment
            </button>
        </div>
    </form>
</div>
@endsection