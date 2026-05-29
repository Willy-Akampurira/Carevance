{{-- resources/views/suppliers/invoices/show.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Invoice Details
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">
    
    <div>
        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
            Invoice: <span class="font-mono text-green-700 bg-green-50 px-1.5 py-0.5 rounded">{{ $invoice->invoice_number }}</span>
        </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm sm:text-base text-gray-700">
        <div class="space-y-2">
            <p><strong class="font-medium text-gray-900">Supplier Account ID:</strong> <span class="font-mono bg-gray-50 px-1 py-0.5 rounded border border-gray-100">{{ $invoice->supplierId }}</span></p>
            <p><strong class="font-medium text-gray-900">Invoice Date:</strong> {{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') : '—' }}</p>
            <p><strong class="font-medium text-gray-900">Total Amount:</strong> <span class="font-semibold text-gray-900">{{ number_format($invoice->amount, 2) }}</span></p>
            <p>
                <strong class="font-medium text-gray-900">Status:</strong> 
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status === 'partially_paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                </span>
            </p>
        </div>

        <div class="space-y-2 border-t pt-2 md:border-t-0 md:pt-0 md:pl-4 md:border-l border-gray-100 text-xs sm:text-sm text-gray-500">
            <p><strong class="font-medium text-gray-700">Recorded At:</strong> {{ $invoice->created_at ? $invoice->created_at->format('d M Y H:i') : '—' }}</p>
            <p><strong class="font-medium text-gray-700">Last System Update:</strong> {{ $invoice->updated_at ? $invoice->updated_at->format('d M Y H:i') : '—' }}</p>
        </div>
    </div>

    <div class="pt-2">
        <strong class="block text-sm sm:text-base font-medium text-gray-900 mb-1.5">Notes / Ledger Remarks:</strong>
        <div class="text-sm sm:text-base text-gray-600 bg-gray-50 p-3 rounded-md border border-gray-100 min-h-[50px]">
            {{ $invoice->notes ?? 'No supplemental payment terms or invoice remarks recorded.' }}
        </div>
    </div>

    @if($invoice->payments->count())
        <div class="pt-2">
            <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3">Transaction Payments History</h4>

            <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
                    <thead class="bg-gray-50">
                        <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                            <th class="px-4 py-3">Payment Date</th>
                            <th class="px-4 py-3">Amount Cleared</th>
                            <th class="px-4 py-3">Payment Method</th>
                            <th class="px-4 py-3">Transaction Reference #</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                        @foreach($invoice->payments as $payment)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : '—' }}
                                Amin       </td>
                                <td class="px-4 py-3 font-semibold text-gray-900">
                                    {{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-gray-600">
                                    {{ $payment->reference ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
        <a href="{{ route('suppliers.invoices.index', $supplierId) }}"
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
            Back to Invoices
        </a>
        
        <form action="{{ route('suppliers.invoices.destroy', [$supplierId, $invoice]) }}" method="POST" class="w-full sm:w-auto inline"
              onsubmit="return confirm('Are you sure you want to permanently delete this invoice record?');">
            @csrf 
            @method('DELETE')
            <button type="submit" class="w-full sm:w-auto text-center px-6 py-2.5 bg-red-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-red-700 transition-colors">
                Delete
            </button>
        </form>

        <a href="{{ route('suppliers.invoices.edit', [$supplierId, $invoice]) }}"
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-yellow-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-yellow-700 active:scale-98 transition-all">
            Edit Details
        </a>
    </div>

</div>
@endsection