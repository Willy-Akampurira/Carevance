@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Create Shift</h2> 

    <div class="flex space-x-3">
        <!-- Back to Attendance -->
        <a href="{{ route('staff.attendance.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            ← Back
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-xl text-red-800 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Create Shift Form -->
    <form action="{{ route('staff.attendance.shifts.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-xl font-medium text-gray-700">Shift Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="mt-2 w-full border-gray-300 rounded-lg shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   required>
        </div>

        <div>
            <label for="start_time" class="block text-xl font-medium text-gray-700">Start Time</label>
            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                   class="mt-2 w-full border-gray-300 rounded-lg shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   required>
        </div>

        <div>
            <label for="end_time" class="block text-xl font-medium text-gray-700">End Time</label>
            <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                   class="mt-2 w-full border-gray-300 rounded-lg shadow-sm text-xl focus:ring-green-500 focus:border-green-500"
                   required>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-3 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Shift
            </button>
        </div>
    </form>
</div>
@endsection
