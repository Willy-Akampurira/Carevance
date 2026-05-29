{{-- resources/views/patients/billing.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Billing & Insurance
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-8">

    @if(session('success'))
        <div class="p-3 bg-green-100 text-sm sm:text-base text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-green-700 mb-4 pb-1 border-b border-gray-100">Create Financial Record</h3>
        <form action="{{ route('patients.billing.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Patient</label>
                <select name="patient_id"
                        class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Invoice Date</label>
                    <input type="date" name="invoice_date"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                        <option value="unpaid">Unpaid</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Insurance Provider</label>
                    <input type="text" name="insurance_provider"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="e.g. Jubilee Insurance">
                </div>
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Claim Number</label>
                    <input type="text" name="claim_number"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="CLM-12345">
                </div>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Claim Status</label>
                <select name="claim_status"
                        class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                    <option value="">-- Select (optional) --</option>
                    <option value="submitted">Submitted</option>
                    <option value="approved">Approved</option>
                    <option value="denied">Denied</option>
                    <option value="pending">Pending</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Payment Method</label>
                    <input type="text" name="payment_method"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="Cash / Card / Mobile Money">
                </div>
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Payment Date</label>
                    <input type="date" name="payment_date"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes"
                          class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                          rows="2" placeholder="Internal accounts description or breakdown references..."></textarea>
            </div>

            <div class="bg-gray-50 p-3 sm:p-4 rounded-lg border border-gray-100">
                <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-2">Invoice Items</label>
                <div id="items-wrapper" class="space-y-2">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-3 item-row items-center">
                        <div class="sm:col-span-6">
                            <input type="text" name="items[0][description]" placeholder="Item description / Consultation / Lab fee"
                                   class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                        </div>
                        <div class="sm:col-span-2">
                            <input type="number" name="items[0][quantity]" placeholder="Qty"
                                   class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" min="1" required>
                        </div>
                        <div class="sm:col-span-3">
                            <input type="number" step="0.01" name="items[0][unit_price]" placeholder="Unit Price"
                                   class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                        </div>
                        <div class="hidden sm:block sm:col-span-1 text-center">
                            </div>
                    </div>
                </div>
                <button type="button" onclick="addItemRow()"
                        class="mt-3 px-3 py-1.5 bg-blue-600 text-white font-medium text-xs sm:text-sm rounded hover:bg-blue-700 shadow-sm transition-colors">
                    + Add Line Item
                </button>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit"
                        class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                    Save Financial Record
                </button>
            </div>
        </form>

        <script>
            let itemIndex = 1;
            function addItemRow() {
                const wrapper = document.getElementById('items-wrapper');
                const row = document.createElement('div');
                row.classList.add('grid', 'grid-cols-1', 'sm:grid-cols-12', 'gap-2', 'sm:gap-3', 'item-row', 'items-center');
                row.innerHTML = `
                    <div class="sm:col-span-6">
                        <input type="text" name="items[${itemIndex}][description]" placeholder="Item description"
                            class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                    </div>
                    <div class="sm:col-span-2">
                        <input type="number" name="items[${itemIndex}][quantity]" placeholder="Qty"
                            class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500" min="1" required>
                    </div>
                    <div class="sm:col-span-3">
                        <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" placeholder="Unit Price"
                            class="w-full text-sm border border-gray-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                    </div>
                    <div class="col-span-1 text-right sm:text-center">
                        <button type="button" onclick="this.closest('.item-row').remove()" 
                                class="text-red-500 hover:text-red-700 p-1 font-bold text-sm" title="Remove Item">✕</button>
                    </div>
                `;
                wrapper.appendChild(row);
                itemIndex++;
            }
        </script>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Unpaid Invoices</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[900px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Invoice #</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($unpaid as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $record->patient->name }}</td>
                            <td class="px-4 py-3 font-mono text-gray-700 whitespace-nowrap">{{ $record->invoice_number }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($record->invoice_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 whitespace-nowrap">{{ number_format($record->amount, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center space-x-1.5">
                                <a href="{{ route('billing.printInvoice', $record->id) }}"
                                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-1 rounded text-xs font-medium tracking-wide shadow-sm transition-colors">
                                    Print
                                </a>
                                <a href="{{ route('patients.billing.pay.form', $record->id) }}"
                                   class="inline-block bg-green-600 hover:bg-green-700 text-white px-2.5 py-1 rounded text-xs font-medium tracking-wide shadow-sm transition-colors">
                                    Pay
                                </a>
                                <form action="{{ route('patients.billing.cancel', $record->id) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this invoice?')">
                                    @csrf
                                    <button type="submit"
                                            class="bg-gray-100 hover:bg-red-600 hover:text-white text-gray-700 px-2.5 py-1 rounded text-xs font-medium tracking-wide border border-gray-200 transition-colors">
                                        Cancel
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500 font-light">No unpaid invoices pending execution.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-2">
            {{ $unpaid->links() }}
        </div>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Paid Invoices</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[900px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Invoice #</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Payment Method</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($paid as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $record->patient->name }}</td>
                            <td class="px-4 py-3 font-mono text-gray-500 whitespace-nowrap">{{ $record->invoice_number }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($record->invoice_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 font-semibold text-green-700 whitespace-nowrap">{{ number_format($record->amount, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700 font-medium">{{ $record->payment_method ?: 'Cleared' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center space-x-1.5">
                                <a href="{{ route('billing.printInvoice', $record->id) }}"
                                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-1 rounded text-xs font-medium shadow-sm">Print</a>
                                <a href="{{ route('patients.billing.payments', $record->id) }}"
                                   class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-2.5 py-1 rounded text-xs font-medium shadow-sm">View Payments</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500 font-light">No paid historical sheets documented.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-2">
            {{ $paid->links() }}
        </div>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Insurance Claims</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[900px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Provider</th>
                        <th class="px-4 py-3">Claim #</th>
                        <th class="px-4 py-3">Claim Status</th>
                        <th class="px-4 py-3">Invoice #</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($claims as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $record->patient->name }}</td>
                            <td class="px-4 py-3 font-medium text-gray-700 whitespace-nowrap">{{ $record->insurance_provider }}</td>
                            <td class="px-4 py-3 font-mono text-gray-600 whitespace-nowrap">{{ $record->claim_number }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $cStatus = strtolower($record->claim_status ?? 'pending');
                                    $badgeClass = match($cStatus) {
                                        'approved' => 'bg-green-100 text-green-800',
                                        'denied'   => 'bg-red-100 text-red-800',
                                        'submitted'=> 'bg-blue-100 text-blue-800',
                                        default    => 'bg-yellow-100 text-yellow-800',
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                    {{ ucfirst($cStatus) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-gray-500 whitespace-nowrap">{{ $record->invoice_number }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 whitespace-nowrap">{{ number_format($record->amount, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center space-x-1.5">
                                <a href="{{ route('billing.printInvoice', $record->id) }}"
                                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-1 rounded text-xs font-medium shadow-sm">Print</a>
                                <a href="{{ route('patients.billing.claim.form', $record->id) }}"
                                   class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white px-2.5 py-1 rounded text-xs font-medium shadow-sm tracking-wide">
                                    Update Claim
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500 font-light">No insurance tracking structures logged.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-2">
            {{ $claims->links() }}
        </div>
    </div>

</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection