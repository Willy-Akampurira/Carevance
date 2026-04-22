@extends('layouts.app')

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6 text-xl">
    <h2 class="text-3xl font-bold mb-4">Purchase Order {{ $purchaseOrder->order_number }}</h2>

    <!-- Header details -->
    <p><strong>Supplier:</strong> {{ optional($purchaseOrder->supplier)->name ?? '—' }}</p>
    <p><strong>Order Date:</strong> 
        {{ $purchaseOrder->order_date ? $purchaseOrder->order_date->format('d M Y') : '—' }}
    </p>
    <p><strong>Expected Delivery:</strong> 
        {{ $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('d M Y') : '—' }}
    </p>
    <p><strong>Status:</strong> {{ ucfirst($purchaseOrder->status) }}</p>
    <p><strong>Total:</strong> {{ number_format($purchaseOrder->total_amount, 2) }}</p>
    <p><strong>Notes:</strong> {{ $purchaseOrder->notes ?? '—' }}</p>

    <!-- Items -->
    <h3 class="font-semibold mt-4 mb-2 text-2xl">Items</h3>
    <table class="min-w-full border text-left">
        <thead class="bg-gray-100">
            <tr class="text-xl">
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Qty</th>
                <th class="px-4 py-2">Unit Price</th>
                <th class="px-4 py-2">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchaseOrder->items as $item)
                <tr class="border-t">
                    <td class="px-4 py-2">
                        {{ $item->description ?? optional($item->drug)->name ?? '—' }}
                    </td>
                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                    <td class="px-4 py-2">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-4 py-2">{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        No items found for this purchase order.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Actions -->
    <div class="mt-6 space-x-2">
        <a href="{{ route('purchaseOrders.edit', $purchaseOrder) }}" 
           class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Edit</a>
        <form action="{{ route('purchaseOrders.destroy', $purchaseOrder) }}" method="POST" class="inline"
              onsubmit="return confirm('Are you sure you want to delete this PO?');">
            @csrf @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
        </form>
        <a href="{{ route('purchaseOrders.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Back to List</a>
    </div>
</div>
@endsection
