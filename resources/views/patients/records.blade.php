{{-- resources/views/patients/records.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Medical Records
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
        <h3 class="text-base sm:text-lg font-semibold text-green-700 mb-4 pb-1 border-b border-gray-100">Create Medical Record</h3>
        <form action="{{ route('patients.records.store') }}" method="POST" class="space-y-4">
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
                    <option value="">-- Optional Appointment --</option>
                    @foreach($appointments as $appt)
                        <option value="{{ $appt->id }}">
                            {{ $appt->patient->name ?? 'Unknown Patient' }} — {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('Y-m-d H:i') }} ({{ $appt->doctor }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Diagnosis</label>
                <input type="text" name="diagnosis"
                       class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                       placeholder="Primary medical diagnosis" required>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Lab Results</label>
                <textarea name="lab_results"
                          class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                          rows="2" placeholder="Enter findings or lab observation summaries..."></textarea>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Imaging Results</label>
                <textarea name="imaging_results"
                          class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                          rows="2" placeholder="Radiology reports, X-Ray, or Ultrasound summaries..."></textarea>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Allergies</label>
                <textarea name="allergies"
                          class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                          rows="2" placeholder="Specify any known allergies or environmental counter-indications..."></textarea>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes"
                          class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                          rows="2" placeholder="Additional treatment notes or general provider observations..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Recorded By</label>
                    <input type="text" name="recorded_by"
                           class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm"
                           placeholder="Clinician or Attending Physician name" required>
                </div>
                <div>
                    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded-md text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                        <option value="active">Active</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit"
                        class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                    Save Medical Record
                </button>
            </div>
        </form>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Active Medical Records</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[900px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Diagnosis</th>
                        <th class="px-4 py-3">Lab Results</th>
                        <th class="px-4 py-3">Imaging</th>
                        <th class="px-4 py-3">Allergies</th>
                        <th class="px-4 py-3">Recorded By</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($active as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $record->patient->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-green-700">{{ $record->diagnosis }}</td>
                            <td class="px-4 py-3 max-w-xs truncate" title="{{ $record->lab_results }}">{{ $record->lab_results ?: 'None' }}</td>
                            <td class="px-4 py-3 max-w-xs truncate" title="{{ $record->imaging_results }}">{{ $record->imaging_results ?: 'None' }}</td>
                            <td class="px-4 py-3 max-w-xs truncate text-red-600" title="{{ $record->allergies }}">{{ $record->allergies ?: 'None' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $record->recorded_by }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500 font-light">No active medical records found.</td>
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
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Archived Medical Records</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[900px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Diagnosis</th>
                        <th class="px-4 py-3">Lab Results</th>
                        <th class="px-4 py-3">Imaging</th>
                        <th class="px-4 py-3">Allergies</th>
                        <th class="px-4 py-3">Recorded By</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-500">
                    @forelse($archived as $record)
                        <tr class="hover:bg-gray-50 bg-gray-50/40 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $record->patient->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-600">{{ $record->diagnosis }}</td>
                            <td class="px-4 py-3 max-w-xs truncate" title="{{ $record->lab_results }}">{{ $record->lab_results ?: 'None' }}</td>
                            <td class="px-4 py-3 max-w-xs truncate" title="{{ $record->imaging_results }}">{{ $record->imaging_results ?: 'None' }}</td>
                            <td class="px-4 py-3 max-w-xs truncate" title="{{ $record->allergies }}">{{ $record->allergies ?: 'None' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-400">{{ $record->recorded_by }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400 font-light">No archived medical records available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-2">
            {{ $archived->links() }}
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