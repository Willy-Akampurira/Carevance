{{-- resources/views/suppliers/payments/show.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        @if($invoice)
            Payment Details — Invoice {{ $invoice->invoice_number }}
        @else
            Payment Details — Invoice #{{ $invoiceId }}
        @endif
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">
    
    <div>
        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
            Payment Log Record: <span class="font-mono text-green-700 bg-green-50 px-1.5 py-0.5 rounded">#{{ $payment->id }}</span>
        </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm sm:text-base text-gray-700">
        <div class="space-y-2">
            <p>
                <strong class="font-medium text-gray-900">Linked Invoice:</strong> 
                <span class="font-mono bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100 font-semibold text-gray-900">
                    @if($invoice)
                        {{ $invoice->invoice_number }}
                    @else
                        #{{ $invoiceId }}
                    @endif
                </span>
            </p>
            <p><strong class="font-medium text-gray-900">Payment Date:</strong> {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '—' }}</p>
            <p><strong class="font-medium text-gray-900">Amount Cleared:</strong> <span class="font-semibold text-gray-900 text-base sm:text-lg text-green-700 font-mono">{{ number_format($payment->amount, 2) }}</span></p>
            <p><strong class="font-medium text-gray-900">Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $payment->method)) }}</p>
            <p><strong class="font-medium text-gray-900">Transaction Reference:</strong> <span class="font-mono text-xs bg-gray-50 px-1 py-0.5 rounded">{{ $payment->reference ?? '—' }}</span></p>
        </div>

        <div class="space-y-2 border-t pt-2 md:border-t-0 md:pt-0 md:pl-4 md:border-l border-gray-100 text-xs sm:text-sm text-gray-500">
            <p><strong class="font-medium text-gray-700">Logged to Ledger:</strong> {{ $payment->created_at ? $payment->created_at->format('d M Y H:i') : '—' }}</p>
            <p><strong class="font-medium text-gray-700">Last System Update:</strong> {{ $payment->updated_at ? $payment->updated_at->format('d M Y H:i') : '—' }}</p>
        </div>
    </div>

    <div class="pt-2">
        <strong class="block text-sm sm:text-base font-medium text-gray-900 mb-1.5">Notes / Transaction Remarks:</strong>
        <div class="text-sm sm:text-base text-gray-600 bg-gray-50 p-3 rounded-md border border-gray-100 min-h-[50px]">
            {{ $payment->notes ?? 'No extra administrative transaction notes or disbursement details provided.' }}
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
        <a href="{{ route('suppliers.invoices.payments.index', [$supplierId, $invoiceId]) }}"
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
            Back to Payments
        </a>
        
        <form action="{{ route('suppliers.invoices.payments.destroy', [$supplierId, $invoiceId, $payment->id]) }}" method="POST" class="w-full sm:w-auto inline"
              onsubmit="return confirm('Are you sure you want to permanently delete this payment transaction? This will alter your balance histories.');">
            @csrf 
            @method('DELETE')
            <button type="submit" class="w-full sm:w-auto text-center px-6 py-2.5 bg-red-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-red-700 transition-colors">
                Delete
            </button>
        </form>

        <a href="{{ route('suppliers.invoices.payments.edit', [$supplierId, $invoiceId, $payment->id]) }}"
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-yellow-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-yellow-700 active:scale-98 transition-all">
            Edit Details
        </a>
    </div>

</div>
@endsection