@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Shift & Attendance</h2>
@endsection

@section('content')
<div class="w-full mx-auto space-y-6">

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('staff.attendance.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm sm:text-base rounded hover:bg-green-700">
            + Add Attendance
        </a>
        <a href="{{ route('staff.attendance.shifts.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm sm:text-base rounded hover:bg-blue-700">
            + Add Shift
        </a>
        <a href="{{ route('staff.attendance.shifts.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-sm sm:text-base rounded hover:bg-gray-700">
            View Shifts
        </a>
        <a href="{{ route('staff.attendance.reports') }}"
           class="px-4 py-2 bg-purple-600 text-white text-sm sm:text-base rounded hover:bg-purple-700">
            Reports
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-sm sm:text-base text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded border border-gray-200">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600 text-left">
                        <th class="p-4">Date</th>
                        <th class="p-4">Staff</th>
                        <th class="p-4">Shift</th>
                        <th class="p-4">Clock In</th>
                        <th class="p-4">Clock Out</th>
                        <th class="p-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="text-sm sm:text-base divide-y divide-gray-200">
                    @forelse($attendance as $record)
                        <tr>
                            <td class="p-4">{{ $record->date }}</td>
                            <td class="p-4">{{ $record->staff->name ?? '—' }}</td>
                            <td class="p-4">{{ $record->shift->name ?? '—' }}</td>
                            <td class="p-4">{{ $record->clock_in ?? '—' }}</td>
                            <td class="p-4">{{ $record->clock_out ?? '—' }}</td>
                            <td class="p-4">{{ $record->ip_address ?? 'Unknown' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-sm sm:text-base text-gray-500">
                                No attendance records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-sm sm:text-base">
            {{ $attendance->links() }}
        </div>
    </div>
</div>
@endsection