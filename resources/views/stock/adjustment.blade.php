@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Stock Adjustment
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

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-xl text-red-800 rounded">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Stock Adjustment Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Drug Name</th>
                    <th class="px-4 py-2 text-left">Current Quantity</th>
                    <th class="px-4 py-2 text-left">Unit</th>
                    <th class="px-4 py-2 text-left">Adjust</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockLots as $lot)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $lot->drug->name }}</td>
                        <td class="px-4 py-2">{{ $lot->quantity }}</td>
                        <td class="px-4 py-2">{{ $lot->unit ?? 'units' }}</td>
                        <td class="px-4 py-2">
                            <!-- Adjustment form -->
                            <form method="POST" action="{{ route('stock.adjustment.store') }}" class="flex flex-wrap gap-2">
                                @csrf

                                <input type="hidden" name="drug_id" value="{{ $lot->drug->id }}">
                                <input type="hidden" name="stock_lot_id" value="{{ $lot->id }}">

                                <input type="number" name="adjustment"
                                    class="w-32 border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500"
                                    placeholder="+/- amount" required>

                                <select name="reason"
                                        class="w-40 border-gray-300 rounded-md focus:border-green-500 focus:ring-green-500"
                                        required>
                                    <option value="">-- Reason --</option>
                                    <option value="damaged">Damaged</option>
                                    <option value="expired">Expired</option>
                                    <option value="lost">Lost</option>
                                    <option value="correction">Correction</option>
                                    <option value="found">Found</option>
                                    <option value="other">Other</option>
                                </select>

                                <button type="submit"
                                        class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                                    Adjust
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-xl text-gray-500">
                            No stock lots available.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
