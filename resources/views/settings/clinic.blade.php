@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Clinic Information</h2>

    <div class="flex space-x-3">
        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 bg-gray-600 text-white text-sm sm:text-base rounded hover:bg-gray-700">
            Back to Dashboard
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

    <form action="{{ route('settings.clinic.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="logo" class="block text-sm sm:text-base font-medium text-gray-700">Clinic Logo</label>
            <input type="file" name="logo" id="logo"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500 p-2">
            @if(\App\Models\Setting::getValue('clinic_logo'))
                <img src="{{ asset('storage/' . \App\Models\Setting::getValue('clinic_logo')) }}" 
                     alt="Clinic Logo" class="h-16 mt-2 object-contain">
            @endif
            @error('logo')
                <p class="text-red-600 text-sm sm:text-base mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="welcome_bg" class="block text-sm sm:text-base font-medium text-gray-700">Welcome Page Background</label>
            <input type="file" name="welcome_bg" id="welcome_bg"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500 p-2">
            @if(\App\Models\Setting::getValue('welcome_bg'))
                <img src="{{ asset('storage/' . \App\Models\Setting::getValue('welcome_bg')) }}" 
                     alt="Welcome Background" class="h-24 mt-2 object-cover rounded">
            @endif
            @error('welcome_bg')
                <p class="text-red-600 text-sm sm:text-base mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="guest_bg" class="block text-sm sm:text-base font-medium text-gray-700">Guest Page Background</label>
            <input type="file" name="guest_bg" id="guest_bg"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500 p-2">
            @if(\App\Models\Setting::getValue('guest_bg'))
                <img src="{{ asset('storage/' . \App\Models\Setting::getValue('guest_bg')) }}" 
                     alt="Guest Background" class="h-24 mt-2 object-cover rounded">
            @endif
            @error('guest_bg')
                <p class="text-red-600 text-sm sm:text-base mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name" class="block text-sm sm:text-base font-medium text-gray-700">Clinic Name</label>
            <input type="text" name="name" id="name"
                   value="{{ \App\Models\Setting::getValue('clinic_name') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500" required>
            @error('name')
                <p class="text-red-600 text-sm sm:text-base mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tagline" class="block text-sm sm:text-base font-medium text-gray-700">Tagline</label>
            <input type="text" name="tagline" id="tagline"
                   value="{{ \App\Models\Setting::getValue('clinic_tagline') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500">
        </div>

        <div>
            <label for="address" class="block text-sm sm:text-base font-medium text-gray-700">Address</label>
            <textarea name="address" id="address" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500">{{ \App\Models\Setting::getValue('clinic_address') }}</textarea>
        </div>

        <div>
            <label for="phone" class="block text-sm sm:text-base font-medium text-gray-700">Phone</label>
            <input type="text" name="phone" id="phone"
                   value="{{ \App\Models\Setting::getValue('clinic_phone') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500">
        </div>

        <div>
            <label for="email" class="block text-sm sm:text-base font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email"
                   value="{{ \App\Models\Setting::getValue('clinic_email') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500">
        </div>

        <div>
            <label for="hours" class="block text-sm sm:text-base font-medium text-gray-700">Operating Hours</label>
            <input type="text" name="hours" id="hours"
                   value="{{ \App\Models\Setting::getValue('clinic_hours') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500">
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-sm sm:text-base rounded-md hover:bg-green-700 transition duration-150 ease-in-out">
                Save Clinic Information
            </button>
        </div>
    </form>
</div>
@endsection