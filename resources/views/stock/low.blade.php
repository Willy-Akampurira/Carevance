{{-- resources/views/stock/low_alerts.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Low Stock Alerts
    </h2>
    <div class="w-full sm:w-auto flex justify-end">
        <a href="{{ route('drugs.create') }}"
           class="w-full sm:w-auto text-center px-4 py-2 bg-green-600 text-white font-medium text-sm sm:text-base rounded-md shadow-sm hover:bg-green-700 transition-colors">
            Add New Drug
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-4">

    @if(session('success'))
        <div class="p-3 bg-green-50 border border-green-200 text-sm sm:text-base text-green-800 rounded-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-green-500/20 focus-within:border-green-500 transition-all shadow-sm">
        <i class="fas fa-search text-gray-400 mr-2.5 cursor-pointer text-sm sm:text-base"
           onclick="triggerLowStockSearch()"></i>

        <input type="text" id="lowStockSearchInput"
               placeholder="Search critical low stock lots..."
               value="{{ $q ?? '' }}"
               class="flex-1 bg-transparent border-none p-0 focus:ring-0 text-sm sm:text-base text-gray-900 placeholder-gray-400"
               onkeydown="if(event.key === 'Enter'){ triggerLowStockSearch(); }">
    </div>

    <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
            <thead class="bg-gray-50">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Quantity</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Expiry Date</th>
                    <th class="px-4 py-3">Reorder Level</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                @forelse($stockLots as $lot)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $lot->drug?->name ?? $lot->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $lot->drug?->category?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 font-mono font-bold text-red-600 bg-red-50/30">
                            {{ $lot->quantity }}
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs font-semibold uppercase">
                            {{ $lot->unit ?? $lot->drug?->unit ?? '—' }}
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
                        <td class="px-4 py-2 text-sm font-medium text-right">
                            <div class="flex justify-end items-center gap-2">
                                @if($lot->drug)
                                    <a href="{{ route('drugs.edit', $lot->drug->id) }}"
                                       class="px-2.5 py-1 rounded border border-gray-300 text-yellow-600 bg-white hover:bg-gray-50 transition-colors text-xs sm:text-sm shadow-sm">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('drugs.restock', $lot->drug->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="amount" value="1">
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded border border-green-300 text-green-700 bg-green-50/50 hover:bg-green-100 transition-colors text-xs sm:text-sm font-semibold shadow-sm">
                                            +1 Unit
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 italic">Unlinked Lot</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            Excellent! No stock items are currently running below the designated warning thresholds.
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

<script>
function triggerLowStockSearch() {
    const query = document.getElementById('lowStockSearchInput').value.trim();
    const baseUrl = "{{ route('stock.low') }}";
    
    if (query.length > 0) {
        window.location.href = baseUrl + "?q=" + encodeURIComponent(query);
    } else {
        window.location.href = baseUrl;
    }
}
</script>
@endsection