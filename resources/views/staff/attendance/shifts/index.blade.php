@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Shifts</h2> 

    <div class="flex space-x-3">
        <a href="{{ route('staff.attendance.shifts.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm sm:text-base rounded hover:bg-blue-700">
            + Add Shift
        </a>

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
                    <th class="p-4">Name</th>
                    <th class="p-4">Start Time</th>
                    <th class="p-4">End Time</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm sm:text-base divide-y divide-gray-200">
                @forelse($shifts as $shift)
                    <tr>
                        <td class="p-4">{{ $shift->name }}</td>
                        <td class="p-4">{{ $shift->start_time }}</td>
                        <td class="p-4">{{ $shift->end_time }}</td>
                        <td class="p-4 flex space-x-2">
                            <a href="{{ route('staff.attendance.shifts.edit', $shift->id) }}"
                               class="text-yellow-600 hover:underline">
                                Edit
                            </a>
                            <form action="{{ route('staff.attendance.shifts.destroy', $shift->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this shift?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-sm sm:text-base text-gray-500">
                            No shifts found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm sm:text-base">
        {{ $shifts->links() }}
    </div>
</div>
@endsection