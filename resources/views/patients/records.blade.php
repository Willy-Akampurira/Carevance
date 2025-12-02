@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Medical Records
    </h2>
@endsection

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow rounded-lg p-6">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create Medical Record -->
    <div class="mb-8">
        <h3 class="text-2xl font-bold mb-4">Create Medical Record</h3>
        <form action="{{ route('patients.records.store') }}" method="POST" class="space-y-4">
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
                               focus:ring-green-500 focus:border-green-500">
                    <option value="">-- Optional Appointment --</option>
                    @foreach($appointments as $appt)
                        <option value="{{ $appt->id }}">
                            {{ $appt->patient->name }} — {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('Y-m-d H:i') }} ({{ $appt->doctor }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xl font-medium">Diagnosis</label>
                <input type="text" name="diagnosis"
                       class="w-full border border-gray-300 rounded text-xl px-3 py-2
                              focus:ring-green-500 focus:border-green-500"
                       placeholder="Primary diagnosis" required>
            </div>

            <div>
                <label class="block text-xl font-medium">Lab Results</label>
                <textarea name="lab_results"
                          class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                 focus:ring-green-500 focus:border-green-500"
                          rows="2" placeholder="Lab findings"></textarea>
            </div>

            <div>
                <label class="block text-xl font-medium">Imaging Results</label>
                <textarea name="imaging_results"
                          class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                 focus:ring-green-500 focus:border-green-500"
                          rows="2" placeholder="Radiology / Imaging summary"></textarea>
            </div>

            <div>
                <label class="block text-xl font-medium">Allergies</label>
                <textarea name="allergies"
                          class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                 focus:ring-green-500 focus:border-green-500"
                          rows="2" placeholder="Known allergies"></textarea>
            </div>

            <div>
                <label class="block text-xl font-medium">Notes</label>
                <textarea name="notes"
                          class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                 focus:ring-green-500 focus:border-green-500"
                          rows="2" placeholder="Additional notes"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xl font-medium">Recorded By</label>
                    <input type="text" name="recorded_by"
                           class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                  focus:ring-green-500 focus:border-green-500"
                           placeholder="Clinician name" required>
                </div>
                <div>
                    <label class="block text-xl font-medium">Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded text-xl px-3 py-2
                                   focus:ring-green-500 focus:border-green-500" required>
                        <option value="active">Active</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>

            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Medical Record
            </button>
        </form>
    </div>

    <!-- Active Medical Records -->
    <h3 class="text-2xl font-bold mb-4">Active Medical Records</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Diagnosis</th>
                <th class="px-4 py-2">Lab Results</th>
                <th class="px-4 py-2">Imaging</th>
                <th class="px-4 py-2">Allergies</th>
                <th class="px-4 py-2">Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($active as $record)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $record->patient->name }}</td>
                    <td class="px-4 py-2">{{ $record->diagnosis }}</td>
                    <td class="px-4 py-2">{{ $record->lab_results }}</td>
                    <td class="px-4 py-2">{{ $record->imaging_results }}</td>
                    <td class="px-4 py-2">{{ $record->allergies }}</td>
                    <td class="px-4 py-2">{{ $record->recorded_by }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-xl text-gray-500">No active records.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $active->links() }}

    <!-- Archived Medical Records -->
    <h3 class="text-2xl font-bold mt-8 mb-4">Archived Medical Records</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Diagnosis</th>
                <th class="px-4 py-2">Lab Results</th>
                <th class="px-4 py-2">Imaging</th>
                <th class="px-4 py-2">Allergies</th>
                <th class="px-4 py-2">Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($archived as $record)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $record->patient->name }}</td>
                    <td class="px-4 py-2">{{ $record->diagnosis }}</td>
                    <td class="px-4 py-2">{{ $record->lab_results }}</td>
                    <td class="px-4 py-2">{{ $record->imaging_results }}</td>
                    <td class="px-4 py-2">{{ $record->allergies }}</td>
                    <td class="px-4 py-2">{{ $record->recorded_by }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-xl text-gray-500">No archived records.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $archived->links() }}

</div>
@endsection
