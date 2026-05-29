{{-- resources/views/patients/claim_form.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Insurance Claims
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto max-w-2xl bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">
    
    <div>
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 pb-1 border-b border-gray-100">
            Update Claim for Invoice <span class="font-mono text-green-700 bg-green-50 px-1.5 py-0.5 rounded">{{ $invoice->invoice_number }}</span>
        </h3>

        <form action="{{ route('patients.billing.claim.update', $invoice->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Claim Status</label>
                <select name="claim_status"
                        class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm"
                        required>
                    <option value="submitted" {{ (old('claim_status', $invoice->claim_status) === 'submitted') ? 'selected' : '' }}>Submitted</option>
                    <option value="approved" {{ (old('claim_status', $invoice->claim_status) === 'approved') ? 'selected' : '' }}>Approved</option>
                    <option value="denied" {{ (old('claim_status', $invoice->claim_status) === 'denied') ? 'selected' : '' }}>Denied</option>
                    <option value="pending" {{ (old('claim_status', $invoice->claim_status) === 'pending') ? 'selected' : '' }}>Pending</option>
                </select>
                @error('claim_status')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('patients.billing') }}"
                   class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                
                <button type="submit"
                        class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                    Save Update
                </button>
            </div>
        </form>
    </div>

</div>
@endsection