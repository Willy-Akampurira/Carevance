@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto bg-white shadow rounded-lg p-6">
    <h2 class="text-3xl font-bold mb-4">Purchase Orders</h2>

    <a href="{{ route('purchaseOrders.create') }}" 
       class="px-4 py-2 bg-green-600 text-white rounded text-2xl">+ New PO</a>

    @if(session('success'))
        <div class="mt-3 p-3 bg-green-100 text-green-800 rounded text-xl">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full mt-4 border text-left">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">PO #</th>
                <th class="px-4 py-2">Supplier</th>
                <th class="px-4 py-2">Order Date</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Total</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $po)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $po->order_number }}</td>
                    <td class="px-4 py-2">{{ $po->supplier->name }}</td>
                    <td class="px-4 py-2">{{ $po->order_date->format('d M Y') }}</td>
                    <td class="px-4 py-2">{{ ucfirst($po->status) }}</td>
                    <td class="px-4 py-2">{{ number_format($po->total_amount, 2) }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('purchaseOrders.show', $po) }}" class="text-blue-600">View</a>
                        <a href="{{ route('purchaseOrders.edit', $po) }}" class="text-yellow-600">Edit</a>
                        <form action="{{ route('purchaseOrders.destroy', $po) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-6 text-center text-xl text-gray-500">No purchase orders found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
