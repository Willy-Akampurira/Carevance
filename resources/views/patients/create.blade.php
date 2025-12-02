@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
    <form method="POST" action="{{ route('patients.store') }}">
        @csrf

        <!-- Demographics -->
        <h3 class="text-2xl font-semibold text-green-700 mb-4">Demographics</h3>
        <div class="mb-4">
            <label class="block text-xl">Full Name</label>
            <input type="text" name="name" required 
                   class="w-full rounded-md border-gray-300 focus:ring-green-500 text-xl focus:border-green-500">
        </div>
        <div class="mb-4">
            <label class="block text-xl">Date of Birth</label>
            <input type="date" name="dob" required 
                   class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">
        </div>
        <div class="mb-4">
            <label class="block text-xl">Gender</label>
            <select name="gender" required 
                    class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">
                <option value="">Select...</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select>
        </div>

        <!-- Contact Info -->
        <h3 class="text-2xl font-semibold text-green-700 mb-4">Contact Info</h3>
        <div class="mb-4">
            <label class="block text-xl">Contact Number</label>
            <input type="text" name="contact" 
                   class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500">
        </div>
        <div class="mb-4">
            <label class="block text-xl">Address</label>
            <textarea name="address" rows="3" 
                      class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500"></textarea>
        </div>

        <!-- Medical History -->
        <h3 class="text-2xl font-semibold text-green-700 mb-4">Medical History</h3>
        <div class="mb-4">
            <label class="block text-xl">Details</label>
            <textarea name="medical_history" rows="4" 
                      class="w-full rounded-md border-gray-300 text-xl focus:ring-green-500 focus:border-green-500"></textarea>
        </div>

        <!-- Submit -->
        <div class="mt-6 text-right">
            <button type="submit" 
                    class="px-6 py-2 bg-green-600 text-white text-xl rounded-md shadow hover:bg-green-700">
                Save Patient
            </button>
        </div>
    </form>
</div>
@endsection
