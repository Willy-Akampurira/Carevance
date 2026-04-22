@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        @if($invoice)
            Payment Details — Invoice {{ $invoice->invoice_number }}
        @else
            Payment Details — Invoice #{{ $invoiceId }}
        @endif
    </h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6 text-xl">
    <h3 class="text-2xl font-bold mb-4">Payment #{{ $payment->id }}</h3>

    <p><strong>Invoice:</strong> 
        @if($invoice)
            {{ $invoice->invoice_number }}
        @else
            #{{ $invoiceId }}
        @endif
    </p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</p>
    <p><strong>Amount:</strong> {{ number_format($payment->amount, 2) }}</p>
    <p><strong>Method:</strong> {{ ucfirst($payment->method) }}</p>
    <p><strong>Reference:</strong> {{ $payment->reference ?? '—' }}</p>
    <p><strong>Notes:</strong> {{ $payment->notes ?? '—' }}</p>
    <p><strong>Created At:</strong> {{ $payment->created_at->format('d M Y H:i') }}</p>
    <p><strong>Updated At:</strong> {{ $payment->updated_at->format('d M Y H:i') }}</p>

    <div class="mt-4 space-x-2 text-xl">
        <a href="{{ route('suppliers.invoices.payments.edit', [$supplierId, $invoiceId, $payment->id]) }}"
           class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Edit</a>

        <form action="{{ route('suppliers.invoices.payments.destroy', [$supplierId, $invoiceId, $payment->id]) }}" 
              method="POST" class="inline"
              onsubmit="return confirm('Delete this payment?');">
            @csrf @method('DELETE')
            <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
        </form>

        <a href="{{ route('suppliers.invoices.payments.index', [$supplierId, $invoiceId]) }}"
           class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Back to Payments</a>
    </div>
</div>
@endsection
