@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Shifts</h2> 

    <div class="flex space-x-3">
        <!-- Add Shift Button -->
        <a href="{{ route('staff.attendance.shifts.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-xl rounded hover:bg-blue-700">
            + Add Shift
        </a>

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

    <!-- Shifts Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Start Time</th>
                    <th class="px-4 py-2 text-left">End Time</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shifts as $shift)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $shift->name }}</td>
                        <td class="px-4 py-2">{{ $shift->start_time }}</td>
                        <td class="px-4 py-2">{{ $shift->end_time }}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <!-- Edit -->
                            <a href="{{ route('staff.attendance.shifts.edit', $shift->id) }}"
                               class="px-3 py-1 text-yellow-600 hover:underline">
                                Edit
                            </a>
                            <!-- Delete -->
                            <form action="{{ route('staff.attendance.shifts.destroy', $shift->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this shift?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1 text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-xl text-gray-500">
                            No shifts found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $shifts->links() }}
    </div>
</div>
@endsection
