@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Edit Invoice — {{ $supplierId }}
    </h2>
    <a href="{{ route('suppliers.invoices.index', $supplier) }}"
       class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
        Back to Invoices
    </a>
</div>
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

    <form method="POST" action="{{ route('suppliers.invoices.update', [$supplierId, $invoice]) }}">
        @csrf
        @method('PUT')

        <!-- Invoice details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xl mb-1">Invoice date</label>
                <input type="date" name="invoice_date"
                       value="{{ old('invoice_date', \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d')) }}"
                       required class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-xl mb-1">Invoice number</label>
                <input type="text" name="invoice_number"
                       value="{{ old('invoice_number', $invoice->invoice_number) }}"
                       required class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-xl mb-1">Amount</label>
                <input type="number" step="0.01" min="0" name="amount"
                       value="{{ old('amount', $invoice->amount) }}"
                       required class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-xl mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
                    @foreach(['unpaid','paid','partially_paid','cancelled'] as $st)
                        <option value="{{ $st }}" {{ old('status', $invoice->status) === $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xl mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">{{ old('notes', $invoice->notes) }}</textarea>
            </div>
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Update Invoice
            </button>
        </div>
    </form>
</div>
@endsection