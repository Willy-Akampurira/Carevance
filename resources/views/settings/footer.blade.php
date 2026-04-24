@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Footer Settings</h2>

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

    <!-- Footer Settings Form -->
    <form action="{{ route('settings.footer.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Footer Text -->
        <div>
            <label for="footer_text" class="block text-xl font-medium text-gray-700">Footer Text</label>
            <textarea name="footer_text" id="footer_text"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">{{ \App\Models\Setting::getValue('footer_text') }}</textarea>
        </div>

        <!-- Facebook Link -->
        <div>
            <label for="facebook" class="block text-xl font-medium text-gray-700">Facebook URL</label>
            <input type="url" name="facebook" id="facebook"
                   value="{{ \App\Models\Setting::getValue('footer_facebook') }}"
                   placeholder="https://facebook.com/yourpage"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
        </div>

        <!-- Twitter Link -->
        <div>
            <label for="twitter" class="block text-xl font-medium text-gray-700">Twitter URL</label>
            <input type="url" name="twitter" id="twitter"
                   value="{{ \App\Models\Setting::getValue('footer_twitter') }}"
                   placeholder="https://twitter.com/yourhandle"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
        </div>

        <!-- WhatsApp Link -->
        <div>
            <label for="whatsapp" class="block text-xl font-medium text-gray-700">WhatsApp URL</label>
            <input type="url" name="whatsapp" id="whatsapp"
                   value="{{ \App\Models\Setting::getValue('footer_whatsapp') }}"
                   placeholder="https://wa.me/256XXXXXXXXX"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
        </div>

        <!-- Contact Info -->
        <div>
            <label for="contact_info" class="block text-xl font-medium text-gray-700">Contact Info</label>
            <textarea name="contact_info" id="contact_info"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">{{ \App\Models\Setting::getValue('footer_contact_info') }}</textarea>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Footer Settings
            </button>
        </div>
    </form>
</div>
@endsection
