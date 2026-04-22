@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Payment Details for Invoice {{ $invoice->invoice_number }}</h2>

    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Payment Method</th>
                <th class="px-4 py-2">Payment Date</th>
                <th class="px-4 py-2">Notes</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-t text-lg">
                <td class="px-4 py-2">{{ $invoice->patient->name }}</td>
                <td class="px-4 py-2">{{ number_format($invoice->amount,2) }}</td>
                <td class="px-4 py-2">{{ ucfirst($invoice->status) }}</td>
                <td class="px-4 py-2">{{ $invoice->payment_method ?? 'N/A' }}</td>
                <td class="px-4 py-2">
                    {{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('Y-m-d') : 'N/A' }}
                </td>
                <td class="px-4 py-2">{{ $invoice->notes ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>

    <a href="{{ route('patients.billing') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Back to Billing</a>
</div>
@endsection
