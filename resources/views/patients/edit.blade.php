@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Edit Patient
    </h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
    <form method="POST" action="{{ route('patients.update', $patient->id) }}">
        @csrf
        @method('PUT')

        <!-- Demographics -->
        <h3 class="text-2xl font-semibold text-green-700 mb-4">Demographics</h3>
        <div class="mb-4">
            <label class="block text-xl">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $patient->name) }}" required
                   class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">
        </div>
        <div class="mb-4">
            <label class="block text-xl">Date of Birth</label>
            <input type="date" name="dob" value="{{ old('dob', $patient->dob) }}" required
                   class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">
        </div>
        <div class="mb-4">
            <label class="block text-xl">Gender</label>
            <select name="gender" required
                    class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">
                <option value="">Select...</option>
                <option value="Male" {{ old('gender', $patient->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $patient->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('gender', $patient->gender) == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <!-- Contact Info -->
        <h3 class="text-2xl font-semibold text-green-700 mb-4">Contact Info</h3>
        <div class="mb-4">
            <label class="block text-xl">Contact Number</label>
            <input type="text" name="contact" value="{{ old('contact', $patient->contact) }}"
                   class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">
        </div>
        <div class="mb-4">
            <label class="block text-xl">Address</label>
            <textarea name="address" rows="3"
                      class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">{{ old('address', $patient->address) }}</textarea>
        </div>

        <!-- Medical History -->
        <h3 class="text-2xl font-semibold text-green-700 mb-4">Medical History</h3>
        <div class="mb-4">
            <label class="block text-xl">Details</label>
            <textarea name="medical_history" rows="4"
                      class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">{{ old('medical_history', $patient->medical_history) }}</textarea>
        </div>

        <!-- Submit -->
        <div class="flex justify-end mt-6">
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-xl text-white rounded-md shadow hover:bg-green-700">
                Update Patient
            </button>
        </div>
    </form>
</div>
@endsection
