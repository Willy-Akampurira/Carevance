{{-- resources/views/patients/prescriptions.blade.php --}}
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Prescriptions
    </h2>
@endsection

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow rounded-lg p-6">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create Prescription -->
    <div class="mb-8">
        <h3 class="text-2xl font-bold mb-4">Create Prescription</h3>
        <form action="{{ route('patients.prescriptions.store') }}" method="POST" class="space-y-4">
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

            <div>
                <label class="block text-xl font-medium">Appointment</label>
                <select name="appointment_id"
                        class="w-full border border-gray-300 rounded text-xl px-3 py-2
                               focus:ring-green-500 focus:border-green-500" required>
                    <option value="">-- Select Appointment (optional if linking) --</option>
                    @foreach($patients as $patientOption)
                        @foreach($patientOption->appointments as $appt)
                            <option value="{{ $appt->id }}">
                                {{ $patientOption->name }} — {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('Y-m-d H:i') }} ({{ $appt->doctor }})
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xl font-medium">Drug</label>
                <select name="drug_id"
                        class="w-full border border-gray-300 rounded text-xl px-3 py-2
                               focus:ring-green-500 focus:border-green-500" required>
                    <option value="">-- Select Drug --</option>
                    @foreach($drugs as $drug)
                        <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xl font-medium">Drug name (label)</label>
                    <input type="text" name="drug_name"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500"
                           placeholder="e.g. Amoxicillin" required>
                </div>
                <div>
                    <label class="block text-xl font-medium">Dosage</label>
                    <input type="text" name="dosage"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500"
                           placeholder="e.g. 500 mg" required>
                </div>
                <div>
                    <label class="block text-xl font-medium">Frequency</label>
                    <input type="text" name="frequency"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500"
                           placeholder="e.g. 2x daily" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xl font-medium">Duration (days)</label>
                    <input type="number" name="duration_days"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500"
                           min="1" placeholder="e.g. 7" required>
                </div>
                <div>
                    <label class="block text-xl font-medium">Start date</label>
                    <input type="date" name="start_date"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-xl font-medium">End date</label>
                    <input type="date" name="end_date"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xl font-medium">Issued by</label>
                    <input type="text" name="issued_by"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500"
                           placeholder="Doctor's name" required>
                </div>
                <div>
                    <label class="block text-xl font-medium">Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                   focus:ring-green-500 focus:border-green-500" required>
                        <option value="active">Active</option>
                        <option value="dispensed">Dispensed</option>
                        <option value="missed">Missed</option>
                        <option value="completed">Completed</option>
                        <option value="expired">Expired</option>
                        <option value="renewal_requested">Renewal Requested</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xl font-medium">Category</label>
                    <input type="text" name="category"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500"
                           placeholder="e.g. General" value="General">
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <label class="text-xl font-medium">Renewal requested?</label>
                <input type="checkbox" name="renewal_requested" value="1"
                       class="h-5 w-5 border border-gray-300 rounded
                              focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-xl font-medium">Notes</label>
                <textarea name="notes"
                          class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                 focus:ring-green-500 focus:border-green-500"
                          rows="2" placeholder="Optional notes"></textarea>
            </div>

            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Prescription
            </button>
        </form>
    </div>

    <!-- Active Prescriptions -->
    <h3 class="text-2xl font-bold mb-4">Active Prescriptions</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Drug</th>
                <th class="px-4 py-2">Dosage</th>
                <th class="px-4 py-2">Frequency</th>
                <th class="px-4 py-2">Duration</th>
                <th class="px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($active as $rx)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $rx->patient->name }}</td>
                    <td class="px-4 py-2">{{ $rx->drug->name }}</td>
                    <td class="px-4 py-2">{{ $rx->dosage }}</td>
                    <td class="px-4 py-2">{{ $rx->frequency }}</td>
                    <td class="px-4 py-2">{{ $rx->duration_days }} days</td>
                    <td class="px-4 py-2">{{ ucfirst($rx->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-xl text-gray-500">No active prescriptions.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $active->links() }}

    <!-- Renewal Requests -->
    <h3 class="text-2xl font-bold mt-8 mb-4">Renewal Requests</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Drug</th>
                <th class="px-4 py-2">Dosage / Frequency</th>
                <th class="px-4 py-2">Duration</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($renewals as $rx)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $rx->patient->name }}</td>
                    <td class="px-4 py-2">{{ $rx->drug->name }}</td>
                    <td class="px-4 py-2">{{ $rx->dosage }} · {{ $rx->frequency }}</td>
                    <td class="px-4 py-2">{{ $rx->duration_days }} days</td>
                    <td class="px-4 py-2">
                        <form action="{{ route('prescriptions.renewals.approve', $rx->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                Approve
                            </button>
                        </form>
                        <form action="{{ route('prescriptions.renewals.decline', $rx->id) }}" method="POST" class="inline ml-2">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                Decline
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-xl text-gray-500">No renewal requests.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $renewals->links() }}

    <!-- History -->
    <h3 class="text-2xl font-bold mt-8 mb-4">History</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Drug</th>
                <th class="px-4 py-2">Dosage / Frequency</th>
                <th class="px-4 py-2">Duration</th>
                <th class="px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($history as $rx)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $rx->patient->name }}</td>
                    <td class="px-4 py-2">{{ $rx->drug->name }}</td>
                    <td class="px-4 py-2">{{ $rx->dosage }} · {{ $rx->frequency }}</td>
                    <td class="px-4 py-2">{{ $rx->duration_days }} days</td>
                    <td class="px-4 py-2">{{ ucfirst($rx->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-xl text-gray-500">No history found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $history->links() }}

</div>
@endsection
