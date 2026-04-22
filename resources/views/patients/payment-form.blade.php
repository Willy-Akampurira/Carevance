@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-6">
        Record Payment for Invoice {{ $invoice->invoice_number }}
    </h2>

    <div class="bg-white shadow rounded-lg p-6">
        <!-- Show outstanding balance -->
        <p class="mb-4 text-lg text-gray-700">
            Outstanding Balance: 
            <strong>{{ number_format($balance, 2) }}</strong>
        </p>

        <!-- Show previous payments (simple list, not a table) -->
        @if($invoice->payments->count())
            <div class="mb-4">
                <h3 class="font-semibold text-gray-800 mb-2">Previous Payments:</h3>
                <ul class="list-disc pl-6 text-gray-700">
                    @foreach($invoice->payments as $payment)
                        <li>{{ number_format($payment->amount, 2) }} via {{ $payment->payment_method }} on {{ $payment->payment_date }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('patients.billing.pay', $invoice->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Amount -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Amount</label>
                <input type="number" step="0.01" name="amount"
                       class="border rounded w-full px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                       required>
            </div>

            <!-- Payment Method -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Payment Method</label>
                <input type="text" name="payment_method"
                       class="border rounded w-full px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                       placeholder="Cash, Mobile Money, Bank Transfer..."
                       required>
            </div>

            <!-- Payment Date -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Payment Date</label>
                <input type="date" name="payment_date"
                       class="border rounded w-full px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                       required>
            </div>

            <!-- Submit -->
            <div class="flex items-center space-x-4">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                    Save Payment
                </button>

                <a href="{{ route('patients.billing') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
