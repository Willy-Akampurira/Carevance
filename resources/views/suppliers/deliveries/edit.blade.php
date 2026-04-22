@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Edit Delivery — {{ $supplierId }}
    </h2>
    <a href="{{ route('suppliers.deliveries.index', $supplierId) }}"
       class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
        Back to Deliveries
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6 space-y-6">
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-lg">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('suppliers.deliveries.update', [$supplierId, $delivery]) }}">
        @csrf
        @method('PUT')

        <!-- Delivery details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xl mb-1">Delivery date</label>
                <input type="date" name="delivery_date"
                       value="{{ old('delivery_date', $delivery->delivery_date->format('Y-m-d')) }}"
                       required class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-xl mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
                    @foreach(['pending','received','partially_received','cancelled'] as $st)
                        <option value="{{ $st }}" {{ old('status', $delivery->status) === $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xl mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
                    {{ old('notes', $delivery->notes) }}
                </textarea>
            </div>
        </div>

        <!-- Items -->
        <div class="mt-6">
            <h3 class="text-2xl font-semibold mb-2">Items</h3>
            @foreach($delivery->items as $index => $item)
                <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-4">
                    <div class="md:col-span-2">
                        <label class="block text-xl mb-1">Drug</label>
                        <select name="items[{{ $index }}][drug_id]" class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
                            <option value="">— None —</option>
                            @foreach($drugs as $drug)
                                <option value="{{ $drug->id }}"
                                    {{ old("items.$index.drug_id", $item->drug_id) == $drug->id ? 'selected' : '' }}>
                                    {{ $drug->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xl mb-1">Batch</label>
                        <input name="items[{{ $index }}][batch_number]"
                               value="{{ old("items.$index.batch_number", $item->batch_number) }}"
                               class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-xl mb-1">Expiry</label>
                        <input type="date" name="items[{{ $index }}][expiry_date]"
                               value="{{ old("items.$index.expiry_date", optional($item->expiry_date)->format('Y-m-d')) }}"
                               class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-xl mb-1">Qty</label>
                        <input type="number" min="0" name="items[{{ $index }}][quantity_received]"
                               value="{{ old("items.$index.quantity_received", $item->quantity_received) }}"
                               required class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500 ">
                    </div>
                    <div>
                        <label class="block text-xl mb-1">Unit cost</label>
                        <input type="number" step="0.01" min="0" name="items[{{ $index }}][unit_cost]"
                               value="{{ old("items.$index.unit_cost", $item->unit_cost) }}"
                                required class="w-full border rounded px-3 py-2 text-xl focus:ring-green-500 focus:border-green-500">   
                               required class="w-full border rounded px-3 py-2 text-xl">
                    </div>
                </div>
            @endforeach
            <p class="text-gray-600 mt-2">Existing items are shown above. Later we can add dynamic rows for new items.</p>
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Update Delivery
            </button>
        </div>
    </form>
</div>
@endsection