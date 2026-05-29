{{-- resources/views/drugs/index.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Drugs Inventory
    </h2> 

    <div class="flex items-center space-x-3 w-full sm:w-auto justify-end">
        <a href="{{ route('drugs.create') }}"
           class="flex-1 sm:flex-initial text-center px-4 py-2 bg-green-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-green-700 active:scale-98 transition-all">
            + Add Drug
        </a>

        <a href="{{ route('drugs.trashed') }}"
           class="flex-1 sm:flex-initial text-center px-4 py-2 bg-red-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-red-700 active:scale-98 transition-all">
            Trash Archive
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

    <div class="flex items-center bg-gray-100 rounded-lg px-4 py-2.5 shadow-sm border border-gray-200 focus-within:bg-white focus-within:ring-2 focus-within:ring-green-500 transition-all">
        <i class="fas fa-search text-gray-400 mr-3 cursor-pointer hover:text-green-600 transition-colors"
           onclick="triggerDrugSearch()"></i>

        <input type="text" id="drugSearchInput"
               placeholder="Search medical products, batches, or compound formulas..."
               value="{{ $search ?? '' }}"
               class="flex-1 bg-transparent border-none p-0 focus:ring-0 text-sm sm:text-base text-gray-800 placeholder-gray-400"
               onkeydown="if(event.key === 'Enter'){ triggerDrugSearch(); }">
    </div>

    <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
            <thead class="bg-gray-50">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Quantity</th>
                    <th class="px-4 py-3">Reserved</th>
                    <th class="px-4 py-3">Expiry Date</th>
                    <th class="px-4 py-3">Reorder Threshold</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                @forelse($drugs as $drug)
                    @php
                        $nearestLot = $drug->stockLots()
                            ?->orderBy('expiry_date')
                            ->first();
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $drug->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $drug->category?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 font-mono text-gray-900 font-semibold">
                            {{ $drug->totalQuantity() }} <span class="text-xs text-gray-500 font-normal uppercase">{{ $drug->unit }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border
                                {{ $drug->reserved ? 'bg-green-50 border-green-200 text-green-700' : 'bg-gray-50 border-gray-200 text-gray-600' }}">
                                {{ $drug->reserved ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            @if($nearestLot?->expiry_date)
                                <span class="{{ $nearestLot->expiry_date->isPast() ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $nearestLot->expiry_date->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-gray-500">
                            {{ $drug->reorder_level }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-right space-x-2">
                            <a href="{{ route('drugs.show', $drug) }}" class="text-blue-600 hover:text-blue-900 hover:underline">View</a>
                            <a href="{{ route('drugs.restock.form', $drug) }}" class="text-green-600 hover:text-green-900 hover:underline">Restock</a>
                            <a href="{{ route('drugs.edit', $drug) }}" class="text-yellow-600 hover:text-yellow-900 hover:underline">Edit</a>
                            
                            <form action="{{ route('drugs.destroy', $drug) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to transfer this drug profile into the system recycling trash?');">
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
                        <td colspan="7" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            No medical items or pharmaceutical records located within current inventory configurations.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($drugs->hasPages())
        <div class="pt-2 border-t border-gray-100 text-sm sm:text-base">
            {{ $drugs->links() }}
        </div>
    @endif
</div>

<script>
function triggerDrugSearch() {
    const query = document.getElementById('drugSearchInput').value.trim();
    const targetUrl = "{{ route('drugs.index') }}";
    
    if(query.length > 0) {
        window.location.href = targetUrl + "?search=" + encodeURIComponent(query);
    } else {
        window.location.href = targetUrl;
    }
}
</script>
@endsection