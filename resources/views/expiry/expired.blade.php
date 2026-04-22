@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Expired Drugs
    </h2>
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
           onclick="triggerExpiredSearch()"></i>

        <input type="text" id="expiredSearchInput"
               placeholder="Search expired drugs..."
               value="{{ $query ?? '' }}"
               class="flex-1 bg-transparent border-none focus:ring-0 text-lg"
               onkeydown="if(event.key === 'Enter'){ triggerExpiredSearch(); }">
    </div>

    <!-- Drugs Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Expiry Date</th>
                    <th class="px-4 py-2 text-left">Days Since Expiry</th>
                    <th class="px-4 py-2 text-left">Reorder Level</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drugs as $drug)
                    @php
                        $expiredLot = $drug->stockLots()
                            ->whereDate('expiry_date', '<=', now())
                            ->orderBy('expiry_date')
                            ->first();
                    @endphp
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $drug->name }}</td>
                        <td class="px-4 py-2">
                            {{ $expiredLot?->expiry_date?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-2 font-semibold text-red-600">
                            {{ $drug->days_since_expiry }}
                        </td>
                        <td class="px-4 py-2">{{ $drug->reorder_level }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-xl text-gray-500">
                            No expired drugs found.
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
function triggerExpiredSearch() {
    const query = document.getElementById('expiredSearchInput').value.trim();
    if(query.length > 0) {
        window.location.href = "{{ route('expiry.expired') }}" + "?q=" + encodeURIComponent(query);
    } else {
        window.location.href = "{{ route('expiry.expired') }}";
    }
}
</script>
@endsection
