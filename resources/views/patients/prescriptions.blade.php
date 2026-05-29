{{-- resources/views/patients/prescriptions.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Prescriptions
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
        <h3 class="text-base sm:text-lg font-semibold text-green-700 mb-4 pb-1 border-b border-gray-100">Create Prescription</h3>
        <form action="{{ route('patients.prescriptions.store') }}" method="POST" class="space-y-4">
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

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Appointment</label>
                <select name="appointment_id"
                        class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
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
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Drug Lot</label>
                <select name="lot_id"
                        class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                    <option value="">-- Select Lot --</option>
                    @foreach($lots as $lot)
                        <option value="{{ $lot->id }}">
                            {{ $lot->drug->name }} (Exp: {{ $lot->expiry_date->format('d M Y') }}, Qty: {{ $lot->quantity }} {{ $lot->unit }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Quantity Prescribed</label>
                    <input type="number" name="quantity" min="1"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="e.g. 20" required>
                </div>
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Unit</label>
                    <input type="text" name="unit"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="e.g. tablets" required>
                </div>
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Dosage</label>
                    <input type="text" name="dosage"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="e.g. 500 mg" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Frequency</label>
                    <input type="text" name="frequency"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="e.g. 2x daily" required>
                </div>
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Duration (days)</label>
                    <input type="number" name="duration_days" min="1"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="e.g. 7" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Issued By</label>
                    <input type="text" name="issued_by"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="Doctor's name" required>
                </div>
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                        <option value="active">Active</option>
                        <option value="dispensed">Dispensed</option>
                        <option value="missed">Missed</option>
                        <option value="completed">Completed</option>
                        <option value="expired">Expired</option>
                        <option value="renewal_requested">Renewal Requested</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" name="category"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="e.g. General" value="General">
                </div>
            </div>

            <div class="flex items-center space-x-3 py-1">
                <input type="checkbox" name="renewal_requested" value="1" id="renewal_requested"
                       class="h-4 w-4 border border-gray-300 rounded text-green-600 focus:ring-green-500 shadow-sm">
                <label for="renewal_requested" class="text-sm sm:text-base font-medium text-gray-700">Renewal requested?</label>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes"
                          class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                          rows="2" placeholder="Optional clinical notes..."></textarea>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit"
                        class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                    Save Prescription
                </button>
            </div>
        </form>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Active Prescriptions</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[1000px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Drug</th>
                        <th class="px-4 py-3">Quantity</th>
                        <th class="px-4 py-3">Unit</th>
                        <th class="px-4 py-3">Dosage</th>
                        <th class="px-4 py-3">Frequency</th>
                        <th class="px-4 py-3">Duration</th>
                        <th class="px-4 py-3">Issued By</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($active as $rx)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $rx->patient->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-green-700 font-medium">{{ $rx->stockLot->drug->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->quantity }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $rx->unit }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono">{{ $rx->dosage }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->frequency }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->duration_days }} days</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->issued_by }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ ucfirst($rx->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <a href="{{ route('prescriptions.export.pdf', $rx->id) }}" 
                                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium tracking-wide shadow-sm transition-colors">
                                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-6 text-center text-gray-500 font-light">No active prescriptions.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-2">
            {{ $active->links() }}
        </div>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Renewal Requests</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[1000px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Drug</th>
                        <th class="px-4 py-3">Quantity</th>
                        <th class="px-4 py-3">Unit</th>
                        <th class="px-4 py-3">Dosage / Frequency</th>
                        <th class="px-4 py-3">Duration</th>
                        <th class="px-4 py-3">Issued By</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($renewals as $rx)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $rx->patient->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-green-700 font-medium">{{ $rx->stockLot->drug->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->quantity }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $rx->unit }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->dosage }} · {{ $rx->frequency }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->duration_days }} days</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->issued_by }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ ucfirst(str_replace('_', ' ', $rx->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <a href="{{ route('prescriptions.export.pdf', $rx->id) }}" 
                                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium tracking-wide shadow-sm transition-colors">
                                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500 font-light">No renewal requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-2">
            {{ $renewals->links() }}
        </div>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">History</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[1000px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Drug</th>
                        <th class="px-4 py-3">Quantity</th>
                        <th class="px-4 py-3">Unit</th>
                        <th class="px-4 py-3">Dosage / Frequency</th>
                        <th class="px-4 py-3">Duration</th>
                        <th class="px-4 py-3">Issued By</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($history as $rx)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $rx->patient->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-green-700 font-medium">{{ $rx->stockLot->drug->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->quantity }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $rx->unit }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->dosage }} · {{ $rx->frequency }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->duration_days }} days</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $rx->issued_by }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($rx->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <a href="{{ route('prescriptions.export.pdf', $rx->id) }}" 
                                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium tracking-wide shadow-sm transition-colors">
                                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500 font-light">No history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-2">
            {{ $history->links() }}
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