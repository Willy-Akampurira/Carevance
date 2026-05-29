@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Appointments
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
        <h3 class="text-base sm:text-lg font-semibold text-green-700 mb-4 pb-1 border-b border-gray-100">Schedule New Appointment</h3>
        <form action="{{ route('patients.appointments.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Patient</label>
                <select name="patient_id" 
                        class="w-full border rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Doctor</label>
                <input type="text" name="doctor" 
                       class="w-full border rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" 
                       placeholder="Doctor's name">
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Scheduled Date & Time</label>
                <input type="datetime-local" name="scheduled_at" 
                       class="w-full border rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Reason</label>
                <input type="text" name="reason" 
                       class="w-full border rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" 
                       placeholder="Reason for visit">
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" 
                          class="w-full border rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" 
                          rows="2" placeholder="Any initial notes..."></textarea>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Visit Summary</label>
                <textarea name="visit_summary" 
                          class="w-full border rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm" 
                          rows="3" placeholder="Summary details for past or completed visits..."></textarea>
            </div>

            <div>
                <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Status</label>
                <select name="status" 
                        class="w-full border rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                    <option value="scheduled">Scheduled</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" 
                        class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                    <i class="fas fa-calendar-check mr-2 opacity-90"></i> Save Appointment
                </button>
            </div>
        </form>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Upcoming Appointments</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[700px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Doctor</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Reason</th>
                        <th class="px-4 py-3">Notes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($upcoming as $appt)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $appt->patient->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $appt->doctor }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono">
                                {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3 max-w-xs truncate">{{ $appt->reason }}</td>
                            <td class="px-4 py-3 max-w-xs truncate">{{ $appt->notes }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 font-light">No upcoming appointments.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-2">
            {{ $upcoming->links() }}
        </div>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Visit History</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[700px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Doctor</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Reason</th>
                        <th class="px-4 py-3">Visit Summary</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($history as $appt)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $appt->patient->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $appt->doctor }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono">
                                {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3 max-w-xs truncate">{{ $appt->reason }}</td>
                            <td class="px-4 py-3 max-w-xs truncate">{{ $appt->visit_summary }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 font-light">No visit history found.</td>
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