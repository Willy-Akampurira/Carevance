{{-- resources/views/purchase_orders/index.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Purchase Orders List
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <h3 class="text-lg sm:text-xl font-bold text-gray-900">
            Purchase Orders
        </h3>
        <a href="{{ route('purchaseOrders.create') }}" 
           class="w-full sm:w-auto text-center px-4 py-2.5 bg-green-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-green-700 active:scale-98 transition-all">
            + New PO
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-sm sm:text-base text-green-800 rounded-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
            <thead class="bg-gray-50">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3">PO #</th>
                    <th class="px-4 py-3">Supplier</th>
                    <th class="px-4 py-3">Order Date</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                @forelse($orders as $po)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-mono font-medium text-gray-900">{{ $po->order_number }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ optional($po->supplier)->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $po->order_date ? $po->order_date->format('d M Y') : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $po->status === 'completed' ? 'bg-green-100 text-green-800' : ($po->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($po->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-900">
                            {{ number_format($po->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium space-x-2">
                            <a href="{{ route('purchaseOrders.show', $po) }}" class="text-blue-600 hover:text-blue-900 hover:underline">View</a>
                            <a href="{{ route('purchaseOrders.edit', $po) }}" class="text-yellow-600 hover:text-yellow-900 hover:underline">Edit</a>
                            
                            <form action="{{ route('purchaseOrders.destroy', $po) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this PO?');">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 hover:underline focus:outline-none">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            No transactional purchase orders or matching logs located inside the database.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="mt-4 pt-2">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection