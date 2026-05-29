{{-- resources/views/patients/edit.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Edit Patient
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    <form method="POST" action="{{ route('patients.update', $patient->id) }}">
        @csrf
        @method('PUT')

        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-4 pb-1 border-b border-gray-100">Demographics</h3>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $patient->name) }}" required 
                   class="w-full rounded-md border-gray-300 focus:ring-green-500 text-sm sm:text-base focus:border-green-500 shadow-sm">
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Date of Birth</label>
            <input type="date" name="dob" value="{{ old('dob', $patient->dob) }}" required 
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
            @error('dob') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Gender</label>
            <select name="gender" required 
                    class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
                <option value="">Select...</option>
                <option value="Male" {{ old('gender', $patient->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $patient->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('gender', $patient->gender) == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-4 pt-2 pb-1 border-b border-gray-100">Contact Info</h3>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Contact Number</label>
            <input type="text" name="contact" value="{{ old('contact', $patient->contact) }}"
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
            @error('contact') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Address</label>
            <textarea name="address" rows="3" 
                      class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">{{ old('address', $patient->address) }}</textarea>
            @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-4 pt-2 pb-1 border-b border-gray-100">Medical History</h3>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Details</label>
            <textarea name="medical_history" rows="4" 
                      class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">{{ old('medical_history', $patient->medical_history) }}</textarea>
            @error('medical_history') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-4 pt-2 pb-1 border-b border-gray-100">Entry Information</h3>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Date of Entry</label>
            <input type="date" name="entry_date" 
                   value="{{ old('entry_date', ($patient->entry_date instanceof \Carbon\Carbon) ? $patient->entry_date->format('Y-m-d') : (is_string($patient->entry_date) ? substr($patient->entry_date, 0, 10) : date('Y-m-d'))) }}" 
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
            @error('entry_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
            <a href="{{ route('patients.index') }}"
               class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                <i class="fas fa-save mr-2 opacity-90"></i> Update Patient
            </button>
        </div>
    </form>
</div>
@endsection