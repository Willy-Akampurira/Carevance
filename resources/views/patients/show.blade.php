@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Patient Details
    </h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
    <div class="mb-4 text-xl">
        <h3 class="text-2xl font-semibold text-green-700">Demographics</h3>
        <p><strong>Name:</strong> {{ $patient->name }}</p>
        <p><strong>Date of Birth:</strong> {{ $patient->dob }}</p>
        <p><strong>Gender:</strong> {{ $patient->gender }}</p>
    </div>

    <div class="mb-4 text-xl">
        <h3 class="text-2xl font-semibold text-green-700">Contact Info</h3>
        <p><strong>Contact:</strong> {{ $patient->contact }}</p>
        <p><strong>Address:</strong> {{ $patient->address }}</p>
    </div>

    <div class="mb-4 text-xl">
        <h3 class="text-2xl font-semibold text-green-700">Medical History</h3>
        <p>{{ $patient->medical_history }}</p>
    </div>

    <div class="flex justify-end mt-6">
        <a href="{{ route('patients.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded-md hover:bg-gray-700">
            Back to List
        </a>
        <a href="{{ route('patients.edit', $patient->id) }}" 
           class="ml-2 px-4 py-2 bg-yellow-600 text-white text-xl rounded-md hover:bg-yellow-700">
            Edit
        </a>
    </div>
</div>
@endsection
