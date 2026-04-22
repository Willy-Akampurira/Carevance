@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        @if($invoice)
            Payments — Invoice {{ $invoice->invoice_number }}
        @else
            Payments — Invoice #{{ $invoiceId }}
        @endif
    </h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between mb-4">
        <h3 class="text-2xl font-bold">Payments List</h3>
        <a href="{{ route('suppliers.invoices.payments.create', [$supplierId, $invoiceId]) }}"
           class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
            + Record Payment
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                    <th class="px-4 py-2 text-left">Method</th>
                    <th class="px-4 py-2 text-left">Reference</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                        <td class="px-4 py-2">{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-4 py-2">{{ ucfirst($payment->method) }}</td>
                        <td class="px-4 py-2">{{ $payment->reference ?? '—' }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('suppliers.invoices.payments.show', [$supplierId, $invoiceId, $payment->id]) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('suppliers.invoices.payments.edit', [$supplierId, $invoiceId, $payment->id]) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('suppliers.invoices.payments.destroy', [$supplierId, $invoiceId, $payment->id]) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this payment?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-xl text-gray-500">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
