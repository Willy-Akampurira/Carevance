{{-- resources/views/purchase_orders/show.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Purchase Order Details
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">
    
    <div>
        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
            Purchase Order: <span class="font-mono text-green-700 bg-green-50 px-1.5 py-0.5 rounded">{{ $purchaseOrder->order_number }}</span>
        </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm sm:text-base text-gray-700">
        <div class="space-y-2">
            <p><strong class="font-medium text-gray-900">Supplier:</strong> {{ optional($purchaseOrder->supplier)->name ?? '—' }}</p>
            <p><strong class="font-medium text-gray-900">Order Date:</strong> {{ $purchaseOrder->order_date ? $purchaseOrder->order_date->format('d M Y') : '—' }}</p>
            <p><strong class="font-medium text-gray-900">Expected Delivery:</strong> {{ $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('d M Y') : '—' }}</p>
        </div>

        <div class="space-y-2">
            <p>
                <strong class="font-medium text-gray-900">Status:</strong> 
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $purchaseOrder->status === 'completed' ? 'bg-green-100 text-green-800' : ($purchaseOrder->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ ucfirst($purchaseOrder->status) }}
                </span>
            </p>
            <p><strong class="font-medium text-gray-900">Total Amount:</strong> <span class="font-semibold text-gray-900">UGX {{ number_format($purchaseOrder->total_amount, 2) }}</span></p>
        </div>
    </div>

    <div class="pt-2">
        <strong class="block text-sm sm:text-base font-medium text-gray-900 mb-1.5">Notes:</strong>
        <div class="text-sm sm:text-base text-gray-600 bg-gray-50 p-3 rounded-md border border-gray-100 min-h-[60px]">
            {{ $purchaseOrder->notes ?? 'No supplemental remarks logged for this purchase order.' }}
        </div>
    </div>

    <div class="pt-2">
        <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3">Items Matrix</h4>

        <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Unit Price</th>
                        <th class="px-4 py-3">Line Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                    @forelse($purchaseOrder->items as $item)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $item->description ?? optional($item->drug)->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                                No specific items found or linked inside this purchase order allocation structure.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
        <a href="{{ route('purchaseOrders.index') }}" 
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
            Back to List
        </a>
        
        <form action="{{ route('purchaseOrders.destroy', $purchaseOrder) }}" method="POST" class="w-full sm:w-auto inline"
              onsubmit="return confirm('Are you sure you want to delete this PO?');">
            @csrf 
            @method('DELETE')
            <button type="submit" class="w-full sm:w-auto text-center px-6 py-2.5 bg-red-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-red-700 transition-colors">
                Delete
            </button>
        </form>

        <a href="{{ route('purchaseOrders.edit', $purchaseOrder) }}" 
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-yellow-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-yellow-700 active:scale-98 transition-all duration-150">
            <i class="fas fa-edit mr-1 opacity-90"></i> Edit PO
        </a>
    </div>

</div>
@endsection