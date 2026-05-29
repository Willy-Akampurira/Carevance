@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Activity Logs</h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">
    <p class="text-gray-600 mb-4 text-sm sm:text-base">Audit trail of staff actions (create, update, delete, restore).</p>

    <div class="overflow-x-auto rounded border border-gray-200">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600 text-left">
                    <th class="p-4">Date/Time</th>
                    <th class="p-4">Staff</th>
                    <th class="p-4">Action</th>
                    <th class="p-4">IP Address</th>
                </tr>
            </thead>
            <tbody class="text-sm sm:text-base divide-y divide-gray-200">
                @forelse($logs as $log)
                    <tr>
                        <td class="p-4">{{ $log->created_at->format('d M Y, H:i') }}</td>
                        <td class="p-4">{{ $log->staff->name ?? 'System' }}</td>
                        <td class="p-4 font-semibold">{{ ucfirst($log->action) }}</td>
                        <td class="p-4">{{ $log->ip_address ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500">
                            No activity logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm sm:text-base">
        {{ $logs->links() }}
    </div>
</div>
@endsection