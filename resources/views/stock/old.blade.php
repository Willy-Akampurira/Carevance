@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Old Stock
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

    <!-- Old Stock Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Drug Name</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Unit</th>
                    <th class="px-4 py-2 text-left">Expiry Date</th>
                    <th class="px-4 py-2 text-left">Reorder Level</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockLots as $lot)
                    @if($lot->quantity > 0) <!-- Hide exhausted lots -->
                        <tr class="border-t text-xl">
                            <td class="px-4 py-2">{{ $lot->drug?->name ?? $lot->name }}</td>
                            <td class="px-4 py-2">{{ $lot->drug?->category?->name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $lot->quantity }}</td>
                            <td class="px-4 py-2">{{ $lot->unit }}</td>
                            <td class="px-4 py-2">
                                {{ $lot->expiry_date ? \Carbon\Carbon::parse($lot->expiry_date)->format('d M Y') : '—' }}
                            </td>
                            <td class="px-4 py-2">{{ $lot->reorder_level }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm bg-gray-200 text-gray-700">
                                    {{ ucfirst($lot->status) }}
                                </span>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-xl text-gray-500">
                            No old stock available.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $stockLots->links() }}</div>
</div>
@endsection
