@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Add Attendance</h2>

    <div class="flex space-x-3">
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

    <form action="{{ route('staff.attendance.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="staff_id" class="block text-sm sm:text-base font-medium text-gray-700">Staff</label>
            <select name="staff_id" id="staff_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500" required>
                <option value="">-- Select Staff --</option>
                @foreach($staff as $member)
                    <option value="{{ $member->id }}" {{ old('staff_id') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
            @error('staff_id')
                <p class="text-red-600 text-sm sm:text-base mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="date" class="block text-sm sm:text-base font-medium text-gray-700">Date</label>
            <input type="date" name="date" id="date"
                   value="{{ old('date') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500" required>
            @error('date')
                <p class="text-red-600 text-sm sm:text-base mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="shift_id" class="block text-sm sm:text-base font-medium text-gray-700">Shift</label>
            <select name="shift_id" id="shift_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500">
                <option value="">-- Optional --</option>
                @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                        {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                    </option>
                @endforeach
            </select>
            @error('shift_id')
                <p class="text-red-600 text-sm sm:text-base mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="clock_in" class="block text-sm sm:text-base font-medium text-gray-700">Clock In</label>
            <input type="time" name="clock_in" id="clock_in"
                   value="{{ old('clock_in') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500">
            @error('clock_in')
                <p class="text-red-600 text-sm sm:text-base mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="clock_out" class="block text-sm sm:text-base font-medium text-gray-700">Clock Out</label>
            <input type="time" name="clock_out" id="clock_out"
                   value="{{ old('clock_out') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500">
            @error('clock_out')
                <p class="text-red-600 text-sm sm:text-base mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-sm sm:text-base rounded hover:bg-green-700">
                Save Attendance
            </button>
        </div>
    </form>
</div>
@endsection