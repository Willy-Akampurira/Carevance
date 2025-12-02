@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow rounded-lg p-6 text-xl">
    <h2 class="text-3xl font-bold mb-4">Purchase Order {{ $purchaseOrder->order_number }}</h2>

    <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}</p>
    <p><strong>Order Date:</strong> {{ $purchaseOrder->order_date->format('d M Y') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($purchaseOrder->status) }}</p>
    <p><strong>Total:</strong> {{ number_format($purchaseOrder->total_amount, 2) }}</p>
    <p><strong>Notes:</strong> {{ $purchaseOrder->notes }}</p>

    <h3 class="font-semibold mt-4 mb-2">Items</h3>
    <table class="min-w-full border text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Qty</th>
                <th class="px-4 py-2">Unit Price</th>
                <th class="px-4 py-2">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->items as $item)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $item->description }}</td>
                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                    <td class="px-4 py-2">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-4 py-2">{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        <a href="{{ route('purchaseOrders.edit', $purchaseOrder) }}" class="px-4 py-2 bg-yellow-600 text-white rounded">Edit</a>
        <form action="{{ route('purchaseOrders.destroy', $purchaseOrder) }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <button class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
        </form>
    </div>
</div>
@endsection
