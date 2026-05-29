{{-- resources/views/suppliers/deliveries/index.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Deliveries — Supplier #{{ $supplierId }}
    </h2>
    <a href="{{ route('suppliers.deliveries.create', $supplierId) }}"
       class="w-full sm:w-auto text-center px-4 py-2.5 bg-green-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-green-700 active:scale-98 transition-all">
        + Record Delivery
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    
    <form method="GET" action="{{ route('suppliers.deliveries.index', $supplierId) }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 mb-6">
        <div class="flex-1 sm:max-w-xs">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search delivery number..."
                   class="w-full border rounded-md border-gray-300 px-3 py-2 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
        </div>
        
        <div class="w-full sm:w-48">
            <select name="status" class="w-full border rounded-md border-gray-300 px-3 py-2 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
                <option value="">All statuses</option>
                @foreach(['pending','received','partially_received','cancelled'] as $st)
                    <option value="{{ $st }}" {{ ($status ?? '') === $st ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $st)) }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-gray-700 text-white text-sm sm:text-base font-medium rounded-md hover:bg-gray-800 transition-colors shadow-sm">
            Apply
        </button>
    </form>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-sm sm:text-base text-green-800 rounded-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
            <thead class="bg-gray-50">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3">Delivery #</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Items Count</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                @forelse($deliveries as $d)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-mono font-medium text-gray-900">{{ $d->delivery_number }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $d->delivery_date ? $d->delivery_date->format('Y-m-d') : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($d->status === 'received') bg-green-100 text-green-800
                                @elseif($d->status === 'partially_received') bg-yellow-100 text-yellow-800
                                @elseif($d->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $d->status)) }}
                            </span>
                            
                            @if($d->trashed())
                                <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 border border-red-200 text-red-700 animate-pulse">
                                    Archived
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $d->items->count() }}</td>
                        <td class="px-4 py-3 text-sm font-medium space-x-2">
                            <a href="{{ route('suppliers.deliveries.show', [$supplierId, $d->id]) }}" class="text-blue-600 hover:text-blue-900 hover:underline">View</a>
                            <a href="{{ route('suppliers.deliveries.edit', [$supplierId, $d->id]) }}" class="text-yellow-600 hover:text-yellow-900 hover:underline">Edit</a>
                            
                            <form action="{{ route('suppliers.deliveries.destroy', [$supplierId, $d->id]) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to archive this delivery log?');">
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
                        <td colspan="5" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            No specific delivery records or supplier log indices locate inside this registry parameters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($deliveries->hasPages())
        <div class="mt-4 pt-2">
            {{ $deliveries->links() }}
        </div>
    @endif
</div>
@endsection