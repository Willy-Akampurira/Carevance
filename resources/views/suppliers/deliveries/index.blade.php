@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Deliveries — Supplier #{{ $supplierId }}
    </h2>
    <a href="{{ route('suppliers.deliveries.create', $supplierId) }}"
       class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
        + Record Delivery
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">
    <!-- Filters -->
    <form method="GET" action="{{ route('suppliers.deliveries.index', $supplierId) }}" class="flex flex-wrap items-center gap-2 mb-4">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search delivery number..."
               class="border rounded border-gray-300 px-3 py-2 w-64 text-xl focus:ring-green-500 focus:border-green-500">
        <select name="status" class="border rounded text-xl border-gray-300 px-3 py-2 focus:ring-green-500 focus:border-green-500">
            <option value="">All statuses</option>
            @foreach(['pending','received','partially_received','cancelled'] as $st)
                <option value="{{ $st }}" {{ ($status ?? '') === $st ? 'selected' : '' }}>
                    {{ ucfirst($st) }}
                </option>
            @endforeach
        </select>
        <button class="px-4 py-2 bg-gray-700 text-white text-xl rounded hover:bg-gray-800">Apply</button>
    </form>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Deliveries Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Delivery #</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Items</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $d)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $d->delivery_number }}</td>
                        <td class="px-4 py-2">{{ $d->delivery_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm
                                @if($d->status === 'received') bg-green-100 text-green-700
                                @elseif($d->status === 'partially_received') bg-yellow-100 text-yellow-700
                                @elseif($d->status === 'cancelled') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($d->status) }}
                            </span>
                            @if($d->trashed())
                                <span class="ml-2 px-2 py-1 rounded text-sm bg-red-100 text-red-700">Archived</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $d->items->count() }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('suppliers.deliveries.show', [$supplierId, $d->id]) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('suppliers.deliveries.edit', [$supplierId, $d->id]) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('suppliers.deliveries.destroy', [$supplierId, $d->id]) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Archive this delivery?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-xl text-gray-500">
                            No deliveries found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $deliveries->links() }}</div>
</div>
@endsection
