@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Low Stock Alerts
    </h2>
    <div class="flex items-center gap-2">
        <a href="{{ route('drugs.create') }}"
           class="bg-green-600 text-lg text-white px-4 py-2 rounded hover:bg-green-700">
            Add New Drug
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- WhatsApp-like Search Bar -->
    <div class="flex items-center mb-6 bg-gray-100 rounded-lg px-3 py-2 shadow-sm">
        <i class="fas fa-search text-gray-500 mr-3 cursor-pointer"
           onclick="triggerLowStockSearch()"></i>

        <input type="text" id="lowStockSearchInput"
               placeholder="Search low stock lots..."
               value="{{ $q ?? '' }}"
               class="flex-1 bg-transparent border-none focus:ring-0 text-lg"
               onkeydown="if(event.key === 'Enter'){ triggerLowStockSearch(); }">
    </div>

    <!-- Stock Lots Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Unit</th>
                    <th class="px-4 py-2 text-left">Expiry Date</th>
                    <th class="px-4 py-2 text-left">Reorder Level</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockLots as $lot)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $lot->drug->name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $lot->drug->category->name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $lot->quantity }}</td>
                        <td class="px-4 py-2">{{ $lot->drug->unit ?? '—' }}</td>
                        <td class="px-4 py-2">
                            {{ $lot->expiry_date ? \Carbon\Carbon::parse($lot->expiry_date)->format('d M Y') : '—' }}
                        </td>
                        <td class="px-4 py-2">{{ $lot->drug->reorder_level ?? '—' }}</td>
                        <td class="px-4 py-2 text-right">
                            <div class="flex justify-end gap-2">
                                <!-- Edit -->
                                <a href="{{ route('drugs.edit', $lot->drug->id) }}"
                                   class="px-3 py-1 rounded border text-blue-600 hover:bg-blue-50">
                                    Edit
                                </a>

                                <!-- Restock -->
                                <form method="POST" action="{{ route('drugs.restock', $lot->drug->id) }}">
                                    @csrf
                                    <input type="hidden" name="amount" value="1">
                                    <button type="submit"
                                            class="px-3 py-1 rounded border text-green-600 hover:bg-green-50">
                                        +1
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-xl text-gray-500">
                            No stock lots currently below reorder level.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $stockLots->links() }}</div>
</div>

<!-- Search Script -->
<script>
function triggerLowStockSearch() {
    const query = document.getElementById('lowStockSearchInput').value.trim();
    if(query.length > 0) {
        window.location.href = "{{ route('stock.low') }}" + "?q=" + encodeURIComponent(query);
    } else {
        window.location.href = "{{ route('stock.low') }}";
    }
}
</script>
@endsection
