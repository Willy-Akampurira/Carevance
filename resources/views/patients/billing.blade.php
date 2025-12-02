{{-- resources/views/patients/billing.blade.php --}}
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Billing & Insurance
    </h2>
@endsection

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow rounded-lg p-6">

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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xl font-medium">Invoice Number</label>
                    <input type="text" name="invoice_number"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500"
                           placeholder="INV-001" required>
                </div>
                <div>
                    <label class="block text-xl font-medium">Invoice Date</label>
                    <input type="date" name="invoice_date"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500" required>
                </div>
                <div>
                    <label class="block text-xl font-medium">Amount</label>
                    <input type="number" step="0.01" name="amount"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500"
                           placeholder="0.00" required>
                </div>
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

            <div>
                <label class="block text-xl font-medium">Notes</label>
                <textarea name="notes"
                          class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                 focus:ring-green-500 focus:border-green-500"
                          rows="2" placeholder="Additional notes"></textarea>
            </div>

            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Financial Record
            </button>
        </form>
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
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-xl text-gray-500">No unpaid invoices.</td>
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
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-xl text-gray-500">No paid invoices.</td>
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
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-xl text-gray-500">No insurance claims.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $claims->links() }}

</div>
@endsection
