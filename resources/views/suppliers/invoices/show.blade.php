@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Invoice Details
    </h2>
@endsection

@section('content')
<div class="w-full mx-auto text-xl bg-white shadow rounded-lg p-6">
    <h3 class="text-2xl font-bold mb-4">Invoice {{ $invoice->invoice_number }}</h3>

    <p><strong>Supplier:</strong> {{ $invoice->supplierId }}</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</p>
    <p><strong>Amount:</strong> {{ number_format($invoice->amount, 2) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
    <p><strong>Notes:</strong> {{ $invoice->notes ?? '—' }}</p>
    <p><strong>Created At:</strong> {{ $invoice->created_at->format('d M Y H:i') }}</p>
    <p><strong>Updated At:</strong> {{ $invoice->updated_at->format('d M Y H:i') }}</p>

    <!-- Payments Section -->
    @if($invoice->payments->count())
        <h4 class="text-2xl font-semibold mt-6 mb-2">Payments</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded">
                <thead class="bg-gray-100">
                    <tr class="text-2xl">
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Amount</th>
                        <th class="px-4 py-2 text-left">Method</th>
                        <th class="px-4 py-2 text-left">Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                        <tr class="border-t text-xl">
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                            <td class="px-4 py-2">{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-4 py-2">{{ ucfirst($payment->method) }}</td>
                            <td class="px-4 py-2">{{ $payment->reference ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="mt-4 space-x-2 text-xl">
        <a href="{{ route('suppliers.invoices.edit', [$supplierId, $invoice]) }}"
           class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Edit</a>
        <form action="{{ route('suppliers.invoices.destroy', [$supplierId, $invoice]) }}" method="POST" class="inline"
              onsubmit="return confirm('Delete this invoice?');">
            @csrf @method('DELETE')
            <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
        </form>
        <a href="{{ route('suppliers.invoices.index', $supplierId) }}"
           class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Back to Invoices</a>
    </div>
</div>
@endsection