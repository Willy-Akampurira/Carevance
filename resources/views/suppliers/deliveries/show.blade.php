{{-- resources/views/suppliers/deliveries/show.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Delivery Details
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">
    
    <div>
        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
            Delivery Log: <span class="font-mono text-green-700 bg-green-50 px-1.5 py-0.5 rounded">{{ $delivery->delivery_number }}</span>
        </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm sm:text-base text-gray-700">
        <div class="space-y-2">
            <p><strong class="font-medium text-gray-900">Supplier Account ID:</strong> <span class="font-mono bg-gray-50 px-1 py-0.5 rounded border border-gray-100">{{ $supplierId }}</span></p>
            <p><strong class="font-medium text-gray-900">Delivery Date:</strong> {{ $delivery->delivery_date ? $delivery->delivery_date->format('d M Y') : '—' }}</p>
            <p>
                <strong class="font-medium text-gray-900">Status:</strong> 
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $delivery->status === 'received' ? 'bg-green-100 text-green-800' : ($delivery->status === 'partially_received' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                </span>
            </p>
            <p><strong class="font-medium text-gray-900">Linked Purchase Order:</strong> {{ optional($delivery->purchaseOrder)->order_number ?? '—' }}</p>
        </div>

        <div class="space-y-2 border-t pt-2 md:border-t-0 md:pt-0 md:pl-4 md:border-l border-gray-100 text-xs sm:text-sm text-gray-500">
            <p><strong class="font-medium text-gray-700">Created At:</strong> {{ $delivery->created_at ? $delivery->created_at->format('d M Y H:i') : '—' }}</p>
            <p><strong class="font-medium text-gray-700">Last System Update:</strong> {{ $delivery->updated_at ? $delivery->updated_at->format('d M Y H:i') : '—' }}</p>
        </div>
    </div>

    <div class="pt-2">
        <strong class="block text-sm sm:text-base font-medium text-gray-900 mb-1.5">Notes / Shipment Summary:</strong>
        <div class="text-sm sm:text-base text-gray-600 bg-gray-50 p-3 rounded-md border border-gray-100 min-h-[50px]">
            {{ $delivery->notes ?? 'No supplemental remarks or shipment annotations logged for this delivery transaction.' }}
        </div>
    </div>

    @if($delivery->items->count())
        <div class="pt-2">
            <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3">Line Items Delivered</h4>

            <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
                    <thead class="bg-gray-50">
                        <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                            <th class="px-4 py-3">Drug Description</th>
                            <th class="px-4 py-3">Batch Reference</th>
                            <th class="px-4 py-3">Expiry Date</th>
                            <th class="px-4 py-3">Qty Received</th>
                            <th class="px-4 py-3">Unit Cost</th>
                            <th class="px-4 py-3">Line Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                        @foreach($delivery->items as $item)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ optional($item->drug)->name ?? $item->description ?? '—' }}
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-gray-600">
                                    {{ $item->batch_number ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('d M Y') : '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $item->quantity_received }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ number_format($item->unit_cost, 2) }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900">
                                    {{ number_format($item->quantity_received * $item->unit_cost, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
        <a href="{{ route('suppliers.deliveries.index', $supplierId) }}"
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
            Back to Deliveries
        </a>
        
        <form action="{{ route('suppliers.deliveries.destroy', [$supplierId, $delivery]) }}" method="POST" class="w-full sm:w-auto inline"
              onsubmit="return confirm('Are you sure you want to archive this delivery log?');">
            @csrf 
            @method('DELETE')
            <button type="submit" class="w-full sm:w-auto text-center px-6 py-2.5 bg-red-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-red-700 transition-colors">
                Delete
            </button>
        </form>

        <a href="{{ route('suppliers.deliveries.edit', [$supplierId, $delivery]) }}"
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-yellow-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-yellow-700 active:scale-98 transition-all">
            Edit Details
        </a>
    </div>

</div>
@endsection