{{-- resources/views/patients/billing.blade.php --}}
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Billing & Insurance
    </h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create Financial Record -->
    <div class="mb-8">
        <h3 class="text-2xl font-bold mb-4">Create Financial Record</h3>
        <form action="{{ route('patients.billing.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Patient -->
            <div>
                <label class="block text-xl font-medium">Patient</label>
                <select name="patient_id"
                        class="w-full border border-gray-300 rounded text-xl px-3 py-2
                            focus:ring-green-500 focus:border-green-500" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Invoice Metadata -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xl font-medium">Invoice Date</label>
                    <input type="date" name="invoice_date"
                        class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                focus:ring-green-500 focus:border-green-500" required>
                </div>
                <div>
                    <label class="block text-xl font-medium">Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                focus:ring-green-500 focus:border-green-500" required>
                        <option value="unpaid">Unpaid</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <!-- Insurance -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xl font-medium">Insurance Provider</label>
                    <input type="text" name="insurance_provider"
                        class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                focus:ring-green-500 focus:border-green-500"
                        placeholder="e.g. Jubilee Insurance">
                </div>
                <div>
                    <label class="block text-xl font-medium">Claim Number</label>
                    <input type="text" name="claim_number"
                        class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                focus:ring-green-500 focus:border-green-500"
                        placeholder="CLM-12345">
                </div>
            </div>

            <div>
                <label class="block text-xl font-medium">Claim Status</label>
                <select name="claim_status"
                        class="w-full border border-gray-300 rounded text-xl px-3 py-2
                            focus:ring-green-500 focus:border-green-500">
                    <option value="">-- Select (optional) --</option>
                    <option value="submitted">Submitted</option>
                    <option value="approved">Approved</option>
                    <option value="denied">Denied</option>
                    <option value="pending">Pending</option>
                </select>
            </div>

            <!-- Payment -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xl font-medium">Payment Method</label>
                    <input type="text" name="payment_method"
                        class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                focus:ring-green-500 focus:border-green-500"
                        placeholder="Cash / Card / Mobile Money">
                </div>
                <div>
                    <label class="block text-xl font-medium">Payment Date</label>
                    <input type="date" name="payment_date"
                        class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xl font-medium">Notes</label>
                <textarea name="notes"
                        class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                focus:ring-green-500 focus:border-green-500"
                        rows="2" placeholder="Additional notes"></textarea>
            </div>

            <!-- Items Section -->
            <div>
                <label class="block text-xl font-medium">Invoice Items</label>
                <div id="items-wrapper" class="space-y-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 item-row">
                        <input type="text" name="items[0][description]" placeholder="Item description"
                            class="border border-gray-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                        <input type="number" name="items[0][quantity]" placeholder="Quantity"
                            class="border border-gray-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500" min="1" required>
                        <input type="number" step="0.01" name="items[0][unit_price]" placeholder="Unit Price"
                            class="border border-gray-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                    </div>
                </div>
                <button type="button" onclick="addItemRow()"
                        class="mt-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Add Item
                </button>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Financial Record
            </button>
        </form>

        <script>
            let itemIndex = 1;
            function addItemRow() {
                const wrapper = document.getElementById('items-wrapper');
                const row = document.createElement('div');
                row.classList.add('grid','grid-cols-1','md:grid-cols-3','gap-4','item-row');
                row.innerHTML = `
                    <input type="text" name="items[${itemIndex}][description]" placeholder="Item description"
                        class="border border-gray-300 rounded px-3 py-2" required>
                    <input type="number" name="items[${itemIndex}][quantity]" placeholder="Quantity"
                        class="border border-gray-300 rounded px-3 py-2" min="1" required>
                    <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" placeholder="Unit Price"
                        class="border border-gray-300 rounded px-3 py-2" required>
                `;
                wrapper.appendChild(row);
                itemIndex++;
            }
        </script>
    </div>

    <!-- Unpaid Invoices -->
    <h3 class="text-2xl font-bold mb-4">Unpaid Invoices</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Invoice #</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unpaid as $record)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $record->patient->name }}</td>
                    <td class="px-4 py-2">{{ $record->invoice_number }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($record->invoice_date)->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">{{ number_format($record->amount,2) }}</td>
                    <td class="px-4 py-2">{{ ucfirst($record->status) }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <!-- Print Invoice (GET) -->
                        <a href="{{ route('billing.printInvoice', $record->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                            Print
                        </a>

                        <!-- Pay Invoice (GET → opens payment form) -->
                        <a href="{{ route('patients.billing.pay.form', $record->id) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                            Pay
                        </a>

                        <!-- Cancel Invoice (POST form) -->
                        <form action="{{ route('patients.billing.cancel', $record->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                Cancel
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-xl text-gray-500">No unpaid invoices.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $unpaid->links() }}

    <!-- Paid Invoices -->
    <h3 class="text-2xl font-bold mt-8 mb-4">Paid Invoices</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Invoice #</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Payment Method</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paid as $record)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $record->patient->name }}</td>
                    <td class="px-4 py-2">{{ $record->invoice_number }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($record->invoice_date)->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">{{ number_format($record->amount,2) }}</td>
                    <td class="px-4 py-2">{{ $record->payment_method }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('billing.printInvoice', $record->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">Print</a>
                        <a href="{{ route('patients.billing.payments', $record->id) }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">View Payments</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-xl text-gray-500">No paid invoices.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $paid->links() }}

    <!-- Insurance Claims -->
    <h3 class="text-2xl font-bold mt-8 mb-4">Insurance Claims</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Provider</th>
                <th class="px-4 py-2">Claim #</th>
                <th class="px-4 py-2">Claim Status</th>
                <th class="px-4 py-2">Invoice #</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($claims as $record)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $record->patient->name }}</td>
                    <td class="px-4 py-2">{{ $record->insurance_provider }}</td>
                    <td class="px-4 py-2">{{ $record->claim_number }}</td>
                    <td class="px-4 py-2">{{ ucfirst($record->claim_status ?? 'pending') }}</td>
                    <td class="px-4 py-2">{{ $record->invoice_number }}</td>
                    <td class="px-4 py-2">{{ number_format($record->amount,2) }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('billing.printInvoice', $record->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">Print</a>
                        <a href="{{ route('patients.billing.claim.form', $record->id) }}"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">
                        Update Claim
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-4 text-center text-xl text-gray-500">No insurance claims.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $claims->links() }}

</div>
@endsection
