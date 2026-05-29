@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Add New Patient
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    <form method="POST" action="{{ route('patients.store') }}">
        @csrf

        <!-- Demographics -->
        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-4 pb-1 border-b border-gray-100">Demographics</h3>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" required 
                   class="w-full rounded-md border-gray-300 focus:ring-green-500 text-sm sm:text-base focus:border-green-500 shadow-sm">
        </div>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Date of Birth</label>
            <input type="date" name="dob" required 
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
        </div>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Gender</label>
            <select name="gender" required 
                    class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
                <option value="">Select...</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select>
        </div>

        <!-- Contact Info -->
        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-4 pt-2 pb-1 border-b border-gray-100">Contact Info</h3>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Contact Number</label>
            <input type="text" name="contact" 
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
        </div>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Address</label>
            <textarea name="address" rows="3" 
                      class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm"></textarea>
        </div>

        <!-- Medical History -->
        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-4 pt-2 pb-1 border-b border-gray-100">Medical History</h3>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Details</label>
            <textarea name="medical_history" rows="4" 
                      class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm"></textarea>
        </div>

        <!-- Date of Entry -->
        <h3 class="text-lg sm:text-xl font-semibold text-green-700 mb-4 pt-2 pb-1 border-b border-gray-100">Entry Information</h3>
        <div class="mb-4">
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Date of Entry</label>
            <input type="date" name="entry_date" 
                   value="{{ date('Y-m-d') }}" 
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
        </div>

        <!-- Submit Button Section -->
        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" 
                    class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                <i class="fas fa-save mr-2 opacity-90"></i> Save Patient
            </button>
        </div>
    </form>
</div>
@endsection