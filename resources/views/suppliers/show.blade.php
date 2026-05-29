{{-- resources/views/suppliers/show.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Supplier Details
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">
    
    <div>
        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">
            {{ $supplier->name }}
        </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm sm:text-base text-gray-700">
        <div class="space-y-2">
            <p><strong class="font-medium text-gray-900">Contact Person:</strong> {{ $supplier->contact_person ?? 'N/A' }}</p>
            <p><strong class="font-medium text-gray-900">Phone:</strong> {{ $supplier->phone ?? 'N/A' }}</p>
            <p><strong class="font-medium text-gray-900">Email:</strong> {{ $supplier->email ?? 'N/A' }}</p>
            <p><strong class="font-medium text-gray-900">Address:</strong> {{ $supplier->address ?? 'N/A' }}</p>
            <p><strong class="font-medium text-gray-900">Tax ID:</strong> {{ $supplier->tax_id ?? 'N/A' }}</p>
        </div>

        <div class="space-y-2">
            <p>
                <strong class="font-medium text-gray-900">Status:</strong> 
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $supplier->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($supplier->status) }}
                </span>
            </p>
            <p><strong class="font-medium text-gray-900">Created At:</strong> {{ $supplier->created_at ? $supplier->created_at->format('d M Y H:i') : 'N/A' }}</p>
            <p><strong class="font-medium text-gray-900">Updated At:</strong> {{ $supplier->updated_at ? $supplier->updated_at->format('d M Y H:i') : 'N/A' }}</p>
        </div>
    </div>

    <div class="pt-2">
        <strong class="block text-sm sm:text-base font-medium text-gray-900 mb-1.5">Notes:</strong>
        <div class="text-sm sm:text-base text-gray-600 bg-gray-50 p-3 rounded-md border border-gray-100 min-h-[60px]">
            {{ $supplier->notes ?? 'No supplementary notes recorded for this supplier.' }}
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
        <a href="{{ route('suppliers.index') }}" 
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
            Back to List
        </a>
        <a href="{{ route('suppliers.edit', $supplier) }}" 
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-yellow-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-yellow-700 active:scale-98 transition-all duration-150">
            <i class="fas fa-edit mr-1 opacity-90"></i> Edit Supplier
        </a>
    </div>

</div>
@endsection