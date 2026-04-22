@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Appointments
    </h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Schedule New Appointment -->
    <div class="mb-8">
        <h3 class="text-2xl font-bold mb-4">Schedule New Appointment</h3>
        <form action="{{ route('patients.appointments.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Patient Dropdown -->
            <div>
                <label class="block text-xl font-medium">Patient</label>
                <select name="patient_id" 
                        class="w-full border rounded border-gray-300 text-xl px-3 py-2 
                               focus:ring-green-500 focus:border-green-500" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xl font-medium">Doctor</label>
                <input type="text" name="doctor" 
                       class="w-full border rounded border-gray-300 text-xl px-3 py-2 
                              focus:ring-green-500 focus:border-green-500" 
                       placeholder="Doctor's name">
            </div>

            <div>
                <label class="block text-xl font-medium">Scheduled Date & Time</label>
                <input type="datetime-local" name="scheduled_at" 
                       class="w-full border rounded border-gray-300 text-xl px-3 py-2 
                              focus:ring-green-500 focus:border-green-500" required>
            </div>

            <div>
                <label class="block text-xl font-medium">Reason</label>
                <input type="text" name="reason" 
                       class="w-full border rounded border-gray-300 text-xl px-3 py-2 
                              focus:ring-green-500 focus:border-green-500" 
                       placeholder="Reason for visit">
            </div>

            <div>
                <label class="block text-xl font-medium">Notes</label>
                <textarea name="notes" 
                          class="w-full border rounded border-gray-300 text-xl px-3 py-2 
                                 focus:ring-green-500 focus:border-green-500" 
                          rows="2"></textarea>
            </div>

            <div>
                <label class="block text-xl font-medium">Visit Summary</label>
                <textarea name="visit_summary" 
                          class="w-full border rounded border-gray-300 text-xl px-3 py-2 
                                 focus:ring-green-500 focus:border-green-500" 
                          rows="3"></textarea>
            </div>

            <div>
                <label class="block text-xl font-medium">Status</label>
                <select name="status" 
                        class="w-full border rounded border-gray-300 text-xl px-3 py-2 
                               focus:ring-green-500 focus:border-green-500">
                    <option value="scheduled">Scheduled</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Appointment
            </button>
        </form>
    </div>

    <!-- Upcoming Appointments -->
    <h3 class="text-2xl font-bold mb-4">Upcoming Appointments</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Doctor</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Reason</th>
                <th class="px-4 py-2">Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($upcoming as $appt)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $appt->patient->name }}</td>
                    <td class="px-4 py-2">{{ $appt->doctor }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appt->scheduled_at)->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-2">{{ $appt->reason }}</td>
                    <td class="px-4 py-2">{{ $appt->notes }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-xl text-gray-500">No upcoming appointments.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $upcoming->links() }}

    <!-- Visit History -->
    <h3 class="text-2xl font-bold mt-8 mb-4">Visit History</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-6">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Doctor</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Reason</th>
                <th class="px-4 py-2">Visit Summary</th>
            </tr>
        </thead>
        <tbody>
            @forelse($history as $appt)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $appt->patient->name }}</td>
                    <td class="px-4 py-2">{{ $appt->doctor }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appt->scheduled_at)->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-2">{{ $appt->reason }}</td>
                    <td class="px-4 py-2">{{ $appt->visit_summary }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-xl text-gray-500">No visit history found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $history->links() }}

</div>
@endsection
