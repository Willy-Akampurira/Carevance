{{-- resources/views/purchase_orders/create.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Add New Purchase Order
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    
    <div class="mb-5 border-b border-gray-100 pb-2">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800">
            New Purchase Order Details
        </h3>
    </div>

    <form method="POST" action="{{ route('purchaseOrders.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Supplier</label>
            <select name="supplier_id" 
                    class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                <option value="">Select supplier</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Order Date</label>
                <input type="date" name="order_date" value="{{ date('Y-m-d') }}"
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Expected Delivery</label>
                <input type="date" name="expected_delivery_date" 
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="2"
                      class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100">
            <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-3">Items Matrix</h4>
            
            <div id="items-container" class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-12 gap-2 p-3 bg-gray-50 rounded-md border border-gray-100 item-row">
                    <div class="lg:col-span-4">
                        <input type="text" name="items[0][description]" placeholder="Description" 
                               class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                    </div>
                    <div class="lg:col-span-2">
                        <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" 
                               class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                    </div>
                    <div class="lg:col-span-3">
                        <input type="number" step="0.01" name="items[0][unit_price]" placeholder="Unit price" 
                               class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                    </div>
                    <div class="lg:col-span-3">
                        <input type="number" name="items[0][drug_id]" placeholder="Drug ID (optional)" 
                               class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                    </div>
                </div>
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
                <i class="fas fa-check-circle mr-1.5 opacity-90"></i> Create PO
            </button>
        </div>
    </form>
</div>

<script>
    let rowIndex = 1;
    document.getElementById('addRow').addEventListener('click', function() {
        const itemsContainer = document.getElementById('items-container');
        const newRow = document.createElement('div');
        newRow.classList.add('grid', 'grid-cols-1', 'sm:grid-cols-4', 'lg:grid-cols-12', 'gap-2', 'p-3', 'bg-gray-50', 'rounded-md', 'border', 'border-gray-100', 'item-row');
        
        newRow.innerHTML = `
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
                <input type="number" name="items[${rowIndex}][drug_id]" placeholder="Drug ID (optional)" 
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>
            <div class="lg:col-span-1 flex items-center justify-end">
                <button type="button" class="removeRow w-full text-center px-2 py-2 bg-red-50 border border-red-200 text-red-600 rounded-md text-xs sm:text-sm font-medium hover:bg-red-100 transition-colors">
                    Remove
                </button>
            </div>
        `;
        itemsContainer.appendChild(newRow);
        rowIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('.item-row').remove();
        }
    });
</script>
@endsection