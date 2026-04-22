@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        @if($invoice)
            Edit Payment — Invoice {{ $invoice->invoice_number }}
        @else
            Edit Payment — Invoice #{{ $invoiceId }}
        @endif
    </h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6 space-y-6">
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-lg">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('suppliers.invoices.payments.update', [$supplierId, $invoiceId, $payment->id]) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xl mb-1">Payment Date</label>
                <input type="date" name="payment_date"
                       value="{{ old('payment_date', \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d')) }}"
                       required class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-xl mb-1">Amount</label>
                <input type="number" step="0.01" min="0" name="amount"
                       value="{{ old('amount', $payment->amount) }}"
                       required class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-xl mb-1">Method</label>
                <select name="method" class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
                    @foreach(['cash','bank_transfer','mobile_money','cheque'] as $m)
                        <option value="{{ $m }}" {{ old('method', $payment->method) === $m ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ', $m)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xl mb-1">Reference</label>
                <input type="text" name="reference" value="{{ old('reference', $payment->reference) }}"
                       class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xl mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">{{ old('notes', $payment->notes) }}</textarea>
            </div>
        </div>

        <div class="mt-6">
            <button class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Update Payment
            </button>
        </div>
    </form>
</div>
@endsection
