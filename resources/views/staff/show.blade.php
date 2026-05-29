@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Staff Profile: {{ $staff->name }}
    </h2> 

    <div class="flex space-x-3">
        <a href="{{ route('staff.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-sm sm:text-base rounded hover:bg-gray-700">
            &larr; Back
        </a>

        <a href="{{ route('staff.edit', $staff->id) }}"
           class="px-4 py-2 bg-yellow-600 text-white text-sm sm:text-base rounded hover:bg-yellow-700">
            Edit
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6 space-y-6">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-sm sm:text-base text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm sm:text-base">
        <div>
            <strong class="text-gray-700">Name:</strong> {{ $staff->name }}
        </div>
        <div>
            <strong class="text-gray-700">Email:</strong> {{ $staff->email }}
        </div>
        <div>
            <strong class="text-gray-700">Phone:</strong> {{ $staff->phone ?? '—' }}
        </div>
        <div>
            <strong class="text-gray-700">Department:</strong> {{ $staff->department?->name ?? '—' }}
        </div>
        <div>
            <strong class="text-gray-700">Role:</strong> {{ $staff->role?->name ?? '—' }}
        </div>
        <div>
            <strong class="text-gray-700">Status:</strong>
            <span class="px-2 py-1 rounded text-xs sm:text-sm
                {{ $staff->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                {{ ucfirst($staff->status) }}
            </span>
        </div>
    </div>

    <div>
        <h3 class="text-xl sm:text-2xl font-semibold mb-4">Recent Activity Logs</h3>
        <div class="overflow-x-auto rounded border border-gray-200">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                        <th class="px-4 py-3 text-left">Action</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="text-sm sm:text-base">
                    @forelse($staff->activityLogs()->latest()->take(10)->get() as $log)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $log->action }}</td>
                            <td class="px-4 py-3">{{ $log->description }}</td>
                            <td class="px-4 py-3">{{ $log->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-sm sm:text-base text-gray-500">
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection