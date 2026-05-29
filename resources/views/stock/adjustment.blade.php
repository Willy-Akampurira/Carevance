{{-- resources/views/stock/adjustment.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Stock Discrepancy Adjustment
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

    @if ($errors->any())
        <div class="p-3 bg-red-50 border border-red-200 text-sm sm:text-base text-red-800 rounded-md shadow-sm">
            <p class="font-semibold mb-1">Please correct the following input issues:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs sm:text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
            <thead class="bg-gray-50">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3">Drug Name</th>
                    <th class="px-4 py-3">Current Quantity</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3 text-right">Action Interface Form</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                @forelse($stockLots as $lot)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $lot->drug->name }}
                        </td>
                        <td class="px-4 py-3 font-mono text-gray-950 font-semibold">
                            {{ $lot->quantity }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs font-semibold uppercase">
                            {{ $lot->unit ?? $lot->drug->unit ?? 'units' }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            <form method="POST" action="{{ route('stock.adjustment.store') }}" class="inline-flex items-center gap-2 justify-end">
                                @csrf

                                <input type="hidden" name="drug_id" value="{{ $lot->drug->id }}">
                                <input type="hidden" name="stock_lot_id" value="{{ $lot->id }}">

                                <input type="number" name="adjustment" step="any"
                                       class="w-24 sm:w-28 rounded-md border-gray-300 text-xs sm:text-sm px-2 py-1.5 focus:border-green-500 focus:ring-green-500 shadow-sm"
                                       placeholder="e.g. -5 or +10" required>

                                <select name="reason"
                                        class="w-32 sm:w-36 rounded-md border-gray-300 text-xs sm:text-sm px-2 py-1.5 focus:border-green-500 focus:ring-green-500 shadow-sm"
                                        required>
                                    <option value="" disabled selected>-- Reason --</option>
                                    <option value="damaged">Damaged</option>
                                    <option value="expired">Expired</option>
                                    <option value="lost">Lost / Stolen</option>
                                    <option value="correction">Audit Correction</option>
                                    <option value="found">Found Item</option>
                                    <option value="other">Other Reason</option>
                                </select>

                                <button type="submit"
                                        class="px-3 py-1.5 bg-yellow-600 text-white font-medium text-xs sm:text-sm rounded hover:bg-yellow-700 active:scale-98 shadow-sm transition-all duration-150">
                                    Adjust
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            No batches or stock lot references match this facility layout record view.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection