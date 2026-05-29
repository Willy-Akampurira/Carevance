@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Attendance Reports</h2>

    <div class="flex space-x-3">
        <a href="{{ route('staff.attendance.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-sm sm:text-base rounded hover:bg-gray-700">
            Back to Attendance
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-sm sm:text-base text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded border border-gray-200">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600 text-left">
                    <th class="p-4">Staff</th>
                    <th class="p-4">Days Present</th>
                    <th class="p-4">Total Hours</th>
                </tr>
            </thead>
            <tbody class="text-sm sm:text-base divide-y divide-gray-200">
                @forelse($reports as $report)
                    <tr>
                        <td class="p-4">{{ $report['staff']->name ?? '—' }}</td>
                        <td class="p-4">{{ $report['days_present'] }}</td>
                        <td class="p-4">
                            {{ number_format($report['total_hours'], 2) }} hrs
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-sm sm:text-base text-gray-500">
                            No attendance reports available.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection