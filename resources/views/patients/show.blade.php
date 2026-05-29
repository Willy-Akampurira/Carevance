{{-- resources/views/patients/show.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Patient Details
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">
    
    <div>
        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-3 pb-1 border-b border-gray-100">Demographics</h3>
        <div class="space-y-2 text-sm sm:text-base text-gray-700">
            <p><strong class="font-medium text-gray-900">Name:</strong> {{ $patient->name }}</p>
            <p><strong class="font-medium text-gray-900">Date of Birth:</strong> {{ $patient->dob }}</p>
            <p><strong class="font-medium text-gray-900">Gender:</strong> {{ $patient->gender }}</p>
        </div>
    </div>

    <div>
        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-3 pb-1 border-b border-gray-100">Contact Info</h3>
        <div class="space-y-2 text-sm sm:text-base text-gray-700">
            <p><strong class="font-medium text-gray-900">Contact:</strong> {{ $patient->contact ?? 'N/A' }}</p>
            <p><strong class="font-medium text-gray-900">Address:</strong> {{ $patient->address ?? 'N/A' }}</p>
        </div>
    </div>

    <div>
        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-3 pb-1 border-b border-gray-100">Medical History</h3>
        <div class="text-sm sm:text-base text-gray-700 bg-gray-50 p-3 rounded-md border border-gray-100 min-h-[80px]">
            {{ $patient->medical_history ?? 'No recorded medical history background.' }}
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
        <a href="{{ route('patients.index') }}" 
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
            Back to List
        </a>
        <a href="{{ route('patients.edit', $patient->id) }}" 
           class="w-full sm:w-auto text-center px-6 py-2.5 bg-yellow-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-yellow-700 active:scale-98 transition-all duration-150">
            <i class="fas fa-edit mr-1 opacity-90"></i> Edit Patient
        </a>
    </div>
</div>
@endsection