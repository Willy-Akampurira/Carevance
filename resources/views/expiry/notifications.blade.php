@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Expiry Notifications
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- WhatsApp-like Search Bar -->
    <form id="searchForm" method="GET" action="{{ route('expiry.notifications') }}" class="mb-6">
        <div class="flex items-center bg-gray-100 rounded-lg px-3 py-2 shadow-sm">
            <i class="fas fa-search text-gray-500 mr-3 cursor-pointer"
               onclick="triggerNotificationSearch();"></i>

            <input type="text" name="q" id="notificationSearchInput"
                   placeholder="Search drugs..."
                   value="{{ $q ?? '' }}"
                   class="flex-1 bg-transparent border-none focus:ring-0 text-lg"
                   onkeydown="if(event.key === 'Enter'){ event.preventDefault(); triggerNotificationSearch(); }">
        </div>
    </form>

    <!-- Badge counts -->
    @php
        $expiredCount = $notifications->where('notification_type', 'expired')->count();
        $nearingCount = $notifications->where('notification_type', 'nearing')->count();
    @endphp

    <div class="flex items-center gap-4 mb-4 text-lg">
        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold">
            Expired: {{ $expiredCount }}
        </span>
        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-semibold">
            Nearing: {{ $nearingCount }}
        </span>
        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold">
            Total: {{ $notifications->count() }}
        </span>
    </div>

    <!-- Threshold info -->
    <div class="mb-4 text-sm text-gray-600">
        Showing nearing expiry within <span class="font-semibold">{{ $thresholdDays }}</span> days, plus already expired.
    </div>

    <!-- Notifications Table -->
    <div class="overflow-x-auto rounded border bg-white text-left">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr class="text-lg">
                    <th class="px-4 py-2">Drug</th>
                    <th class="px-4 py-2">Expiry Date</th>
                    <th class="px-4 py-2">Indicator</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Reorder Level</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $drug)
                    @php
                        $lot = $drug->stockLots()
                            ->orderBy('expiry_date')
                            ->first();
                    @endphp
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $drug->name }}</td>
                        <td class="px-4 py-2">
                            {{ $lot?->expiry_date?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-2">
                            @if($drug->notification_type === 'expired')
                                <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">
                                    {{ $drug->days_since_expiry }} days ago
                                </span>
                            @else
                                <span class="px-2 py-1 rounded text-sm
                                    @if(($drug->days_to_expiry ?? 0) <= 7) bg-red-100 text-red-700
                                    @elseif(($drug->days_to_expiry ?? 0) <= 14) bg-yellow-100 text-yellow-700
                                    @else bg-green-100 text-green-700 @endif">
                                    in {{ $drug->days_to_expiry }} days
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if($drug->notification_type === 'expired')
                                <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs">Expired</span>
                            @else
                                <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs">Nearing</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $drug->reorder_level }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-xl text-gray-500">
                            No expiry notifications at the moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>

<!-- Search Script -->
<script>
function triggerNotificationSearch() {
    const query = document.getElementById('notificationSearchInput').value.trim();
    const form = document.getElementById('searchForm');
    if(query.length > 0) {
        form.action = "{{ route('expiry.notifications') }}" + "?q=" + encodeURIComponent(query);
    } else {
        form.action = "{{ route('expiry.notifications') }}";
    }
    form.submit();
}
</script>
@endsection
