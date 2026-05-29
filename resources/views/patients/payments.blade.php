{{-- resources/views/patients/payment_details.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Payment Details
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">
    
    <div>
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 pb-1 border-b border-gray-100">
            Payment Records for Invoice: <span class="font-mono text-green-700 bg-green-50 px-1.5 py-0.5 rounded">{{ $invoice->invoice_number }}</span>
        </h3>

        <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm mb-6">
            <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Payment Method</th>
                        <th class="px-4 py-3">Payment Date</th>
                        <th class="px-4 py-3">Notes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $invoice->patient->name }}</td>
                        <td class="px-4 py-3 font-semibold">UGX {{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $invoice->payment_method ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('Y-m-d') : 'N/A' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $invoice->notes ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <a href="{{ route('patients.billing') }}"
               class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                Back to Billing
            </a>
        </div>
    </div>

</div>
@endsection