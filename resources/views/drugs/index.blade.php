@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Drugs</h2> 

    <div class="flex space-x-3">
        <!-- Add Drug Button -->
        <a href="{{ route('drugs.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
            + Add Drug
        </a>

        <!-- View Trash Button -->
        <a href="{{ route('drugs.trashed') }}"
           class="px-4 py-2 bg-red-600 text-white text-xl rounded hover:bg-red-700">
            Trash
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
           onclick="triggerDrugSearch()"></i>

        <input type="text" id="drugSearchInput"
               placeholder="Search drugs..."
               value="{{ $search ?? '' }}"
               class="flex-1 bg-transparent border-none focus:ring-0 text-lg"
               onkeydown="if(event.key === 'Enter'){ triggerDrugSearch(); }">
    </div>

    <!-- Drugs Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Reserved</th>
                    <th class="px-4 py-2 text-left">Expiry Date</th>
                    <th class="px-4 py-2 text-left">Reorder Level</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drugs as $drug)
                    @php
                        $nearestLot = $drug->stockLots()
                            ->orderBy('expiry_date')
                            ->first();
                    @endphp
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $drug->name }}</td>
                        <td class="px-4 py-2">{{ $drug->category?->name ?? '—' }}</td>
                        <td class="px-4 py-2"> {{ $drug->totalQuantity() }} {{ $drug->unit }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm
                                {{ $drug->reserved ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $drug->reserved ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            {{ $nearestLot?->expiry_date?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-2">{{ $drug->reorder_level }}</td>
                        <td class="px-4 py-2 space-x-3">
                            <a href="{{ route('drugs.show', $drug) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('drugs.restock.form', $drug) }}" class="text-green-600 hover:underline">Restock</a>
                            <a href="{{ route('drugs.edit', $drug) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('drugs.destroy', $drug) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Move this drug to trash?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-xl text-gray-500">
                            No drugs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $drugs->links() }}</div>
</div>

<!-- Search Script -->
<script>
function triggerDrugSearch() {
    const query = document.getElementById('drugSearchInput').value.trim();
    if(query.length > 0) {
        window.location.href = "{{ route('drugs.index') }}" + "?search=" + encodeURIComponent(query);
    } else {
        window.location.href = "{{ route('drugs.index') }}";
    }
}
</script>
@endsection
