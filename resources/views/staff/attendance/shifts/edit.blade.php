@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Edit Shift</h2>

    <div class="flex space-x-3">
        <!-- Back to Shifts -->
        <a href="{{ route('staff.attendance.shifts.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            Back to Shifts
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

    <!-- Edit Shift Form -->
    <form action="{{ route('staff.attendance.shifts.update', $shift->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Shift Name -->
        <div>
            <label for="name" class="block text-xl font-medium text-gray-700">Shift Name</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $shift->name) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   required>
            @error('name')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Start Time -->
        <div>
            <label for="start_time" class="block text-xl font-medium text-gray-700">Start Time</label>
            <input type="time" name="start_time" id="start_time"
                   value="{{ old('start_time', $shift->start_time) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   required>
            @error('start_time')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- End Time -->
        <div>
            <label for="end_time" class="block text-xl font-medium text-gray-700">End Time</label>
            <input type="time" name="end_time" id="end_time"
                   value="{{ old('end_time', $shift->end_time) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   required>
            @error('end_time')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Update Shift
            </button>
        </div>
    </form>
</div>
@endsection
