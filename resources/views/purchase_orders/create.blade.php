@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">New Purchase Order</h2>

    <form method="POST" action="{{ route('purchaseOrders.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-xl">Supplier</label>
            <select name="supplier_id" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500" required>
                <option value="">Select supplier</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xl">Order Date</label>
                <input type="date" name="order_date" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500" required>
            </div>
            <div>
                <label class="block text-xl">Expected Delivery</label>
                <input type="date" name="expected_delivery_date" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-xl">Notes</label>
            <textarea name="notes" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500"></textarea>
        </div>

        <h3 class="font-semibold mb-2 text-2xl">Items</h3>
        <div id="items">
            <div class="grid grid-cols-4 gap-3 mb-3">
                <input type="text" name="items[0][description]" placeholder="Description" class="border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500" required>
                <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" class="border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500" required>
                <input type="number" step="0.01" name="items[0][unit_price]" placeholder="Unit price" class="border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500" required>
                <input type="number" name="items[0][drug_id]" placeholder="Drug ID (optional)" class="border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500">
            </div>
        </div>

        <div class="mt-6">
        <button type="submit" class="px-4 py-2 bg-green-600 text-xl text-white rounded hover:bg-green-700">
            Create PO
        </button>
        <a href="{{ route('purchaseOrders.index') }}" class="ml-2 px-4 py-2 text-xl bg-gray-600 text-white rounded hover:bg-gray-700">
            View
        </a>
        </div>
    </form>
</div>
@endsection
