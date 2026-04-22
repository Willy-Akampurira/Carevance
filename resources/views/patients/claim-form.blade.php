@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-6">
        Update Claim for Invoice {{ $invoice->invoice_number }}
    </h2>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('patients.billing.claim.update', $invoice->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Claim Status -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Claim Status</label>
                <select name="claim_status"
                        class="border rounded w-full px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                        required>
                    <option value="submitted" {{ $invoice->claim_status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="approved" {{ $invoice->claim_status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="denied" {{ $invoice->claim_status == 'denied' ? 'selected' : '' }}>Denied</option>
                    <option value="pending" {{ $invoice->claim_status == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="flex items-center space-x-4">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                    Save Update
                </button>

                <a href="{{ route('patients.billing') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
