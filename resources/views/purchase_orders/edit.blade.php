@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Edit Purchase Order {{ $purchaseOrder->order_number }}</h2>

    <form method="POST" action="{{ route('purchaseOrders.update', $purchaseOrder) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold text-xl">Supplier</label>
            <select name="supplier_id" class="w-full border rounded px-3 py-2 border-gray-300 text-xl focus:ring-green-500 focus:border-green-500" required>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" {{ $purchaseOrder->supplier_id == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-semibold text-xl">Order Date</label>
                <input type="date" name="order_date" value="{{ $purchaseOrder->order_date->format('Y-m-d') }}"
                       class="w-full border rounded px-3 py-2 border-gray-300 text-xl focus:ring-green-500 focus:border-green-500" required>
            </div>
            <div>
                <label class="block font-semibold text-xl">Expected Delivery</label>
                <input type="date" name="expected_delivery_date"
                       value="{{ optional($purchaseOrder->expected_delivery_date)->format('Y-m-d') }}"
                       class="w-full border rounded px-3 py-2 border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">
            </div>
        </div>

        <div class="mb-4">
            <label class="block font-semibold text-xl">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2 border-gray-300 text-xl focus:ring-green-500 focus:border-green-500" required>
                @foreach(['pending','approved','received','cancelled'] as $status)
                    <option value="{{ $status }}" {{ $purchaseOrder->status === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold text-xl">Notes</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2 border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">{{ $purchaseOrder->notes }}</textarea>
        </div>

        <h3 class="font-semibold mb-2 text-xl">Items</h3>
        <div id="items">
            @foreach($purchaseOrder->items as $index => $item)
                <div class="grid grid-cols-5 gap-3 mb-3 text-xl">
                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                    <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}"
                           placeholder="Description" class="border rounded px-3 py-2 text-xl border-gray-300 focus:ring-green-500 focus:border-green-500" required>
                    <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}"
                           placeholder="Qty" min="1" class="border rounded px-3 py-2 text-xl border-gray-300 focus:ring-green-500 focus:border-green-500" required>
                    <input type="number" step="0.01" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}"
                           placeholder="Unit price" class="border rounded px-3 py-2 text-xl border-gray-300 focus:ring-green-500 focus:border-green-500" required>
                    <input type="number" name="items[{{ $index }}][drug_id]" value="{{ $item->drug_id }}"
                           placeholder="Drug ID (optional)" class="border rounded px-3 py-2 text-xl border-gray-300 focus:ring-green-500 focus:border-green-500">
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700  text-white rounded text-xl">Update PO</button>
            <a href="{{ route('purchaseOrders.index') }}" class="ml-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-xl text-white rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection