@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Performance Reports</h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    @include('staff.reports._summary')

    <div class="overflow-x-auto mt-6 rounded border border-gray-200">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600 text-left">
                    <th class="p-4">Staff</th>
                    <th class="p-4">Title</th>
                    <th class="p-4">Total Hours</th>
                    <th class="p-4">Period</th>
                    <th class="p-4">Remarks</th>
                    <th class="p-4">Generated</th>
                    <th class="p-4">Created</th>
                </tr>
            </thead>
            <tbody class="text-sm sm:text-base divide-y divide-gray-200">
                @forelse($reports as $report)
                    <tr>
                        <td class="p-4">{{ $report->staff->name }}</td>
                        <td class="p-4">{{ $report->title }}</td>
                        <td class="p-4">{{ $report->total_hours }}</td>
                        <td class="p-4 whitespace-nowrap">
                            {{ $report->period_start }} → {{ $report->period_end }}
                        </td>
                        <td class="p-4">{{ $report->remarks ?? '—' }}</td>
                        <td class="p-4">{{ $report->generated_by_system ? 'System' : 'Manual' }}</td>
                        <td class="p-4 whitespace-nowrap">{{ $report->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-sm sm:text-base text-gray-500">
                            No performance reports generated yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm sm:text-base">
        {{ $reports->links() }}
    </div>
</div>
@endsection