@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Theme Settings</h2>

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

    <form action="{{ route('settings.theme.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="primary_color" class="block text-sm sm:text-base font-medium text-gray-700">Primary Color</label>
            <input type="text" name="primary_color" id="primary_color"
                   value="{{ \App\Models\Setting::getValue('theme_primary_color') }}"
                   placeholder="#0f766e"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500 p-2">
        </div>

        <div>
            <label for="secondary_color" class="block text-sm sm:text-base font-medium text-gray-700">Secondary Color</label>
            <input type="text" name="secondary_color" id="secondary_color"
                   value="{{ \App\Models\Setting::getValue('theme_secondary_color') }}"
                   placeholder="#facc15"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500 p-2">
        </div>

        <div>
            <label for="font" class="block text-sm sm:text-base font-medium text-gray-700">Font</label>
            <input type="text" name="font" id="font"
                   value="{{ \App\Models\Setting::getValue('theme_font') }}"
                   placeholder="Figtree, Inter, Roboto"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500 p-2">
        </div>

        <div>
            <label for="logo_position" class="block text-sm sm:text-base font-medium text-gray-700">Logo Position</label>
            <select name="logo_position" id="logo_position"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500 p-2">
                <option value="">-- Select Position --</option>
                <option value="left" {{ \App\Models\Setting::getValue('theme_logo_position') == 'left' ? 'selected' : '' }}>Left</option>
                <option value="center" {{ \App\Models\Setting::getValue('theme_logo_position') == 'center' ? 'selected' : '' }}>Center</option>
                <option value="right" {{ \App\Models\Setting::getValue('theme_logo_position') == 'right' ? 'selected' : '' }}>Right</option>
            </select>
        </div>

        <div>
            <label for="custom_css" class="block text-sm sm:text-base font-medium text-gray-700">Custom CSS</label>
            <textarea name="custom_css" id="custom_css" rows="4"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm text-sm sm:text-base focus:border-green-500 focus:ring-green-500 p-2">{{ \App\Models\Setting::getValue('theme_custom_css') }}</textarea>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-sm sm:text-base rounded-md hover:bg-green-700 transition duration-150 ease-in-out">
                Save Theme Settings
            </button>
        </div>
    </form>
</div>
@endsection