@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Attendance Reports</h2>

    <div class="flex space-x-3">
        <!-- Back to Attendance -->
        <a href="{{ route('staff.attendance.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            Back to Attendance
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

    <!-- Reports Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Staff</th>
                    <th class="px-4 py-2 text-left">Days Present</th>
                    <th class="px-4 py-2 text-left">Total Hours</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $report['staff']->name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $report['days_present'] }}</td>
                        <td class="px-4 py-2">
                            {{ number_format($report['total_hours'], 2) }} hrs
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-xl text-gray-500">
                            No attendance reports available.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
