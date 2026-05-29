{{-- resources/views/purchase_orders/edit.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Edit Purchase Orders
    </h2>
</div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    
    <div class="mb-5 border-b border-gray-100 pb-2">
        <h3 class="text-lg sm:text-xl font-bold text-gray-900">
            Edit Purchase Order: <span class="font-mono text-green-700">{{ $purchaseOrder->order_number }}</span>
        </h3>
    </div>

    <form method="POST" action="{{ route('purchaseOrders.update', $purchaseOrder) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Supplier</label>
            <select name="supplier_id" 
                    class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" {{ $purchaseOrder->supplier_id == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Order Date</label>
                <input type="date" name="order_date" value="{{ $purchaseOrder->order_date->format('Y-m-d') }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Expected Delivery</label>
                <input type="date" name="expected_delivery_date"
                       value="{{ optional($purchaseOrder->expected_delivery_date)->format('Y-m-d') }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Status</label>
            <select name="status" 
                    class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                @foreach(['pending','approved','received','cancelled'] as $status)
                    <option value="{{ $status }}" {{ $purchaseOrder->status === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="2"
                      class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">{{ $purchaseOrder->notes }}</textarea>
        </div>

        <div class="pt-4 border-t border-gray-100">
            <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3">Items Matrix</h4>
            
            <div id="items" class="space-y-3">
                @foreach($purchaseOrder->items as $index => $item)
                    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-12 gap-2 p-3 bg-gray-50 rounded-md border border-gray-100 item-row items-center">
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                        
                        <div class="lg:col-span-4">
                            <label class="block text-xs font-medium text-gray-400 mb-0.5 sm:hidden">Description</label>
                            <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}"
                                   placeholder="Description" class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-gray-400 mb-0.5 sm:hidden">Qty</label>
                            <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}"
                                   placeholder="Qty" min="1" class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                        </div>
                        <div class="lg:col-span-3">
                            <label class="block text-xs font-medium text-gray-400 mb-0.5 sm:hidden">Unit Price</label>
                            <input type="number" step="0.01" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}"
                                   placeholder="Unit price" class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-gray-400 mb-0.5 sm:hidden">Drug ID (Optional)</label>
                            <input type="number" name="items[{{ $index }}][drug_id]" value="{{ $item->drug_id }}"
                                   placeholder="Drug ID" class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                        </div>
                        <div class="lg:col-span-1 flex items-center justify-end pt-2 sm:pt-0">
                            <button type="button" class="removeRow w-full text-center px-2 py-2 bg-red-50 border border-red-200 text-red-600 rounded-md text-xs sm:text-sm font-medium hover:bg-red-100 transition-colors">
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="addRow" 
                    class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md text-xs sm:text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus-circle mr-1.5"></i> Add Item Row
            </button>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
            <a href="{{ route('purchaseOrders.index') }}" 
               class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                <i class="fas fa-save mr-1.5 opacity-90"></i> Update PO
            </button>
        </div>
    </form>
</div>

<script>
    let rowIndex = {{ $purchaseOrder->items->count() }};
    document.getElementById('addRow').addEventListener('click', function() {
        const itemsDiv = document.getElementById('items');
        const newRow = document.createElement('div');
        newRow.className = "grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-12 gap-2 p-3 bg-gray-50 rounded-md border border-gray-100 item-row items-center";
        
        newRow.innerHTML = `
            <input type="hidden" name="items[${rowIndex}][id]" value="">
            <div class="lg:col-span-4">
                <input type="text" name="items[${rowIndex}][description]" placeholder="Description" 
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
            </div>
            <div class="lg:col-span-2">
                <input type="number" name="items[${rowIndex}][quantity]" placeholder="Qty" min="1" 
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
            </div>
            <div class="lg:col-span-3">
                <input type="number" step="0.01" name="items[${rowIndex}][unit_price]" placeholder="Unit price" 
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
            </div>
            <div class="lg:col-span-2">
                <input type="number" name="items[${rowIndex}][drug_id]" placeholder="Drug ID" 
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>
            <div class="lg:col-span-1 flex items-center justify-end pt-2 sm:pt-0">
                <button type="button" class="removeRow w-full text-center px-2 py-2 bg-red-50 border border-red-200 text-red-600 rounded-md text-xs sm:text-sm font-medium hover:bg-red-100 transition-colors">
                    Remove
                </button>
            </div>
        `;
        itemsDiv.appendChild(newRow);
        rowIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('.item-row').remove();
        }
    });
</script>
@endsection