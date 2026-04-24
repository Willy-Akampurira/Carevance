@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Clinic Information</h2>

    <div class="flex space-x-3">
        <!-- Back to Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            Back to Dashboard
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

    <!-- Clinic Information Form -->
    <form action="{{ route('settings.clinic.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Logo -->
        <div>
            <label for="logo" class="block text-xl font-medium text-gray-700">Clinic Logo</label>
            <input type="file" name="logo" id="logo"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
            @if(\App\Models\Setting::getValue('clinic_logo'))
                <img src="{{ asset('storage/' . \App\Models\Setting::getValue('clinic_logo')) }}" 
                     alt="Clinic Logo" class="h-16 mt-2">
            @endif
            @error('logo')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Welcome Page Background -->
        <div>
            <label for="welcome_bg" class="block text-xl font-medium text-gray-700">Welcome Page Background</label>
            <input type="file" name="welcome_bg" id="welcome_bg"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
            @if(\App\Models\Setting::getValue('welcome_bg'))
                <img src="{{ asset('storage/' . \App\Models\Setting::getValue('welcome_bg')) }}" 
                     alt="Welcome Background" class="h-24 mt-2">
            @endif
            @error('welcome_bg')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Guest Page Background -->
        <div>
            <label for="guest_bg" class="block text-xl font-medium text-gray-700">Guest Page Background</label>
            <input type="file" name="guest_bg" id="guest_bg"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
            @if(\App\Models\Setting::getValue('guest_bg'))
                <img src="{{ asset('storage/' . \App\Models\Setting::getValue('guest_bg')) }}" 
                     alt="Guest Background" class="h-24 mt-2">
            @endif
            @error('guest_bg')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Clinic Name -->
        <div>
            <label for="name" class="block text-xl font-medium text-gray-700">Clinic Name</label>
            <input type="text" name="name" id="name"
                   value="{{ \App\Models\Setting::getValue('clinic_name') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500" required>
            @error('name')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tagline -->
        <div>
            <label for="tagline" class="block text-xl font-medium text-gray-700">Tagline</label>
            <input type="text" name="tagline" id="tagline"
                   value="{{ \App\Models\Setting::getValue('clinic_tagline') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
        </div>

        <!-- Address -->
        <div>
            <label for="address" class="block text-xl font-medium text-gray-700">Address</label>
            <textarea name="address" id="address"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">{{ \App\Models\Setting::getValue('clinic_address') }}</textarea>
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-xl font-medium text-gray-700">Phone</label>
            <input type="text" name="phone" id="phone"
                   value="{{ \App\Models\Setting::getValue('clinic_phone') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xl font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email"
                   value="{{ \App\Models\Setting::getValue('clinic_email') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
        </div>

        <!-- Operating Hours -->
        <div>
            <label for="hours" class="block text-xl font-medium text-gray-700">Operating Hours</label>
            <input type="text" name="hours" id="hours"
                   value="{{ \App\Models\Setting::getValue('clinic_hours') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Clinic Information
            </button>
        </div>
    </form>
</div>
@endsection
