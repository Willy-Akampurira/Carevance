{{-- resources/views/suppliers/deliveries/edit.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Edit Delivery — #{{ $supplierId }}
    </h2>
    <a href="{{ route('suppliers.deliveries.index', $supplierId) }}"
       class="w-full sm:w-auto text-center px-4 py-2 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors shadow-sm">
        Back to Deliveries
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">
    
    @if ($errors->any())
        <div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded-md shadow-sm">
            <ul class="list-disc pl-5 space-y-1 text-sm sm:text-base">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('suppliers.deliveries.update', [$supplierId, $delivery]) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Delivery Date</label>
                <input type="date" name="delivery_date"
                       value="{{ old('delivery_date', $delivery->delivery_date ? $delivery->delivery_date->format('Y-m-d') : '') }}" required
                       class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>
            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
                    @foreach(['pending','received','partially_received','cancelled'] as $st)
                        <option value="{{ $st }}" {{ old('status', $delivery->status) === $st ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $st)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Notes / Remarks</label>
                <textarea name="notes" rows="2" placeholder="Provide extra delivery details or shipment remarks..."
                          class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">{{ old('notes', $delivery->notes) }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3">Items Entry Matrix</h3>
            
            <div class="space-y-3">
                @foreach($delivery->items as $index => $item)
                    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-12 gap-3 p-4 bg-gray-50 border border-gray-100 rounded-md items-end">
                        
                        <div class="lg:col-span-4">
                            <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Drug Item</label>
                            <select name="items[{{ $index }}][drug_id]" class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                                <option value="">— None —</option>
                                @foreach($drugs as $drug)
                                    <option value="{{ $drug->id }}"
                                        {{ old("items.$index.drug_id", $item->drug_id) == $drug->id ? 'selected' : '' }}>
                                        {{ $drug->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="lg:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Batch Number</label>
                            <input type="text" name="items[{{ $index }}][batch_number]"
                                   value="{{ old("items.$index.batch_number", $item->batch_number) }}" placeholder="e.g. BNT-202"
                                   class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                        </div>
                        
                        <div class="lg:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Expiry Date</label>
                            <input type="date" name="items[{{ $index }}][expiry_date]"
                                   value="{{ old("items.$index.expiry_date", $item->expiry_date ? $item->expiry_date->format('Y-m-d') : '') }}"
                                   class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                        </div>
                        
                        <div class="lg:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Qty Received</label>
                            <input type="number" min="0" name="items[{{ $index }}][quantity_received]"
                                   value="{{ old("items.$index.quantity_received", $item->quantity_received) }}" required
                                   class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                        </div>
                        
                        <div class="lg:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Unit Cost</label>
                            <input type="number" step="0.01" min="0" name="items[{{ $index }}][unit_cost]"
                                   value="{{ old("items.$index.unit_cost", $item->unit_cost) }}" required
                                   class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                        </div>
                    </div>
                @endforeach
            </div>
            
            <p class="text-xs sm:text-sm text-gray-500 mt-2 italic">
                * Existing delivery logs are listed above. Dynamic creation arrays for adding empty item records inline can be wired up via Alpine/JS as needed.
            </p>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
            <a href="{{ route('suppliers.deliveries.index', $supplierId) }}" 
               class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                <i class="fas fa-save mr-1.5 opacity-90"></i> Update Delivery
            </button>
        </div>
    </form>
</div>
@endsection