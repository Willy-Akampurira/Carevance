{{-- resources/views/suppliers/payments/index.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        @if($invoice)
            Payments — Invoice {{ $invoice->invoice_number }}
        @else
            Payments — Invoice #{{ $invoiceId }}
        @endif
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

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-100">
        <h3 class="text-base sm:text-lg font-bold text-gray-900">Payments Ledger Transactions</h3>
        <a href="{{ route('suppliers.invoices.payments.create', [$supplierId, $invoiceId]) }}"
           class="w-full sm:w-auto text-center px-4 py-2 bg-green-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-green-700 active:scale-98 transition-all">
            + Record Payment
        </a>
    </div>

    <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
            <thead class="bg-gray-50">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Amount Cleared</th>
                    <th class="px-4 py-3">Payment Method</th>
                    <th class="px-4 py-3">Transaction Reference #</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 text-gray-600">
                            {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '—' }}
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-900">
                            {{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-600">
                            {{ $payment->reference ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-right space-x-3">
                            <a href="{{ route('suppliers.invoices.payments.show', [$supplierId, $invoiceId, $payment->id]) }}" class="text-blue-600 hover:text-blue-900 hover:underline">View</a>
                            <a href="{{ route('suppliers.invoices.payments.edit', [$supplierId, $invoiceId, $payment->id]) }}" class="text-yellow-600 hover:text-yellow-900 hover:underline">Edit</a>
                            
                            <form action="{{ route('suppliers.invoices.payments.destroy', [$supplierId, $invoiceId, $payment->id]) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to permanently clear this payment record from the ledger?');">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 hover:underline focus:outline-none">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            No sequential payments or disbursement profiles located for this specific invoice index.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection