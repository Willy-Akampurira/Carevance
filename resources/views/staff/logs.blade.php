@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-2xl text-gray-800">Activity Logs</h2>
@endsection

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <p class="text-gray-600 mb-4">Audit trail of staff actions (create, update, delete, restore).</p>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-3">Date/Time</th>
                <th class="p-3">Staff</th>
                <th class="p-3">Action</th>
                <th class="p-3">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr class="border-b">
                    <td class="p-3">{{ $log->created_at->format('d M Y, H:i') }}</td>
                    <td class="p-3">{{ $log->staff->name ?? 'System' }}</td>
                    <td class="p-3 font-semibold">{{ ucfirst($log->action) }}</td>
                    <td class="p-3">{{ $log->ip_address ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-3 text-center text-gray-500">
                        No activity logs found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
