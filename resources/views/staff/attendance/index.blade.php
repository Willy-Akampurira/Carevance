@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Shift & Attendance</h2> 

    <div class="flex space-x-3">
        <!-- Add Attendance Button -->
        <a href="{{ route('staff.attendance.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
            + Add Attendance
        </a>

        <!-- Add Shift Button -->
        <a href="{{ route('staff.attendance.shifts.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-xl rounded hover:bg-blue-700">
            + Add Shift
        </a>

        <!-- View Shifts Button -->
        <a href="{{ route('staff.attendance.shifts.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            View Shifts
        </a>

        <!-- Reports Button -->
        <a href="{{ route('staff.attendance.reports') }}"
           class="px-4 py-2 bg-purple-600 text-white text-xl rounded hover:bg-purple-700">
            Reports
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Attendance Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Staff</th>
                    <th class="px-4 py-2 text-left">Shift</th>
                    <th class="px-4 py-2 text-left">Clock In</th>
                    <th class="px-4 py-2 text-left">Clock Out</th>
                    <th class="px-4 py-2 text-left">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendance as $record)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $record->date }}</td>
                        <td class="px-4 py-2">{{ $record->staff->name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $record->shift->name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $record->clock_in ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $record->clock_out ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $record->ip_address ?? 'Unknown' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-xl text-gray-500">
                            No attendance records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $attendance->links() }}
    </div>
</div>
@endsection
