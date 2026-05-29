{{-- resources/views/drugs/old_stock.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Old Stock Batches
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-4">

    @if(session('success'))
        <div class="p-3 bg-green-50 border border-green-200 text-sm sm:text-base text-green-800 rounded-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
            <thead class="bg-gray-50">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3">Drug Name</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Quantity</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Expiry Date</th>
                    <th class="px-4 py-3">Reorder Level</th>
                    <th class="px-4 py-3 text-right">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                @php
                    // Filter down to active, unexhausted lots to prevent layout rendering artifacts
                    $activeLots = $stockLots->filter(fn($lot) => $lot->quantity > 0);
                @endphp

                @forelse($activeLots as $lot)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $lot->drug?->name ?? $lot->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $lot->drug?->category?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 font-mono text-gray-900 font-semibold">
                            {{ $lot->quantity }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 uppercase text-xs font-semibold">
                            {{ $lot->unit ?? $lot->drug?->unit }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            @if($lot->expiry_date)
                                <span class="{{ \Carbon\Carbon::parse($lot->expiry_date)->isPast() ? 'text-red-600 font-bold' : '' }}">
                                    {{ \Carbon\Carbon::parse($lot->expiry_date)->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-mono text-gray-500">
                            {{ $lot->reorder_level ?? $lot->drug?->reorder_level ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-gray-100 border-gray-200 text-gray-700">
                                {{ ucfirst($lot->status ?? 'Old') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            No older stock or batch assignments available within active inventory parameters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($stockLots->hasPages())
        <div class="pt-2 border-t border-gray-100 text-sm sm:text-base">
            {{ $stockLots->links() }}
        </div>
    @endif
</div>
@endsection