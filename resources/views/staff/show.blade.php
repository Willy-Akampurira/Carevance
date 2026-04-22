@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Staff Profile: {{ $staff->name }}
    </h2> 

    <div class="flex space-x-3">
        <!-- Back to Staff List -->
        <a href="{{ route('staff.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            ← Back
        </a>

        <!-- Edit Staff -->
        <a href="{{ route('staff.edit', $staff->id) }}"
           class="px-4 py-2 bg-yellow-600 text-white text-xl rounded hover:bg-yellow-700">
            Edit
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6 space-y-6">

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Staff Details -->
    <div class="grid grid-cols-2 gap-6 text-lg">
        <div>
            <strong>Name:</strong> {{ $staff->name }}
        </div>
        <div>
            <strong>Email:</strong> {{ $staff->email }}
        </div>
        <div>
            <strong>Phone:</strong> {{ $staff->phone ?? '—' }}
        </div>
        <div>
            <strong>Department:</strong> {{ $staff->department?->name ?? '—' }}
        </div>
        <div>
            <strong>Role:</strong> {{ $staff->role?->name ?? '—' }}
        </div>
        <div>
            <strong>Status:</strong>
            <span class="px-2 py-1 rounded text-sm
                {{ $staff->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                {{ ucfirst($staff->status) }}
            </span>
        </div>
    </div>

    <!-- Activity Logs -->
    <div>
        <h3 class="text-2xl font-semibold mb-4">Recent Activity Logs</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded">
                <thead class="bg-gray-100">
                    <tr class="text-xl">
                        <th class="px-4 py-2 text-left">Action</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff->activityLogs()->latest()->take(10)->get() as $log)
                        <tr class="border-t text-lg">
                            <td class="px-4 py-2">{{ $log->action }}</td>
                            <td class="px-4 py-2">{{ $log->description }}</td>
                            <td class="px-4 py-2">{{ $log->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-lg text-gray-500">
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
