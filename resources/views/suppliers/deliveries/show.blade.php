@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Delivery Details
    </h2>
@endsection

@section('content')
<div class="w-full mx-auto text-xl bg-white shadow rounded-lg p-6">
    <h3 class="text-2xl font-bold mb-4">Delivery {{ $delivery->delivery_number }}</h3>

    <!-- Delivery Info -->
    <p><strong>Supplier:</strong> {{ $delivery->supplierId }}</p>
    <p><strong>Date:</strong> {{ $delivery->delivery_date->format('d M Y') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($delivery->status) }}</p>
    <p><strong>Purchase Order:</strong> {{ optional($delivery->purchaseOrder)->order_number ?? '—' }}</p>
    <p><strong>Notes:</strong> {{ $delivery->notes ?? '—' }}</p>
    <p><strong>Created At:</strong> {{ $delivery->created_at->format('d M Y H:i') }}</p>
    <p><strong>Updated At:</strong> {{ $delivery->updated_at->format('d M Y H:i') }}</p>

    <!-- Items Table -->
    @if($delivery->items->count())
        <h4 class="text-2xl font-semibold mt-6 mb-2">Items</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded">
                <thead class="bg-gray-100">
                    <tr class="text-2xl">
                        <th class="px-4 py-2 text-left">Drug</th>
                        <th class="px-4 py-2 text-left">Batch</th>
                        <th class="px-4 py-2 text-left">Expiry</th>
                        <th class="px-4 py-2 text-left">Qty</th>
                        <th class="px-4 py-2 text-left">Unit Cost</th>
                        <th class="px-4 py-2 text-left">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($delivery->items as $item)
                        <tr class="border-t text-xl">
                            <td class="px-4 py-2">{{ optional($item->drug)->name ?? $item->description ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $item->batch_number ?? '—' }}</td>
                            <td class="px-4 py-2">
                                {{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('d M Y') : '—' }}
                            </td>
                            <td class="px-4 py-2">{{ $item->quantity_received }}</td>
                            <td class="px-4 py-2">{{ number_format($item->unit_cost, 2) }}</td>
                            <td class="px-4 py-2">{{ number_format($item->quantity_received * $item->unit_cost, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="mt-4 space-x-2 text-xl">
        <a href="{{ route('suppliers.deliveries.edit', [$supplierId, $delivery]) }}"
           class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Edit</a>
        <form action="{{ route('suppliers.deliveries.destroy', [$supplierId, $delivery]) }}" method="POST" class="inline"
              onsubmit="return confirm('Archive this delivery?');">
            @csrf @method('DELETE')
            <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
        </form>
        <a href="{{ route('suppliers.deliveries.index', $supplierId) }}"
           class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Back to Deliveries</a>
    </div>
</div>
@endsection