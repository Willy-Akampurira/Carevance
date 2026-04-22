@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Performance Reports</h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Include summary widget -->
    @include('staff.reports._summary')

    <!-- Reports Table -->
    <div class="overflow-x-auto mt-6">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Staff</th>
                    <th class="px-4 py-2 text-left">Title</th>
                    <th class="px-4 py-2 text-left">Total Hours</th>
                    <th class="px-4 py-2 text-left">Period</th>
                    <th class="px-4 py-2 text-left">Remarks</th>
                    <th class="px-4 py-2 text-left">Generated</th>
                    <th class="px-4 py-2 text-left">Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $report->staff->name }}</td>
                        <td class="px-4 py-2">{{ $report->title }}</td>
                        <td class="px-4 py-2">{{ $report->total_hours }}</td>
                        <td class="px-4 py-2">
                            {{ $report->period_start }} → {{ $report->period_end }}
                        </td>
                        <td class="px-4 py-2">{{ $report->remarks ?? '—' }}</td>
                        <td class="px-4 py-2">
                            {{ $report->generated_by_system ? 'System' : 'Manual' }}
                        </td>
                        <td class="px-4 py-2">{{ $report->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-xl text-gray-500">
                            No performance reports generated yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $reports->links() }}
    </div>
</div>
@endsection
