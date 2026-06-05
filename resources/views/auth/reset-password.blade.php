<x-guest-layout>
    <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-8">
        
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ __('Account Setup & Reset') }}</h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                {{ __('Please complete your details below to activate your account and configure a secure password.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <x-input-label for="name" :value="__('Full Name')" class="text-lg font-semibold text-gray-700" />
                <x-text-input id="name" 
                              class="block mt-1 w-full text-lg border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" 
                              type="text" 
                              name="name" 
                              :value="old('name')" 
                              placeholder="e.g., Dr. John Doe"
                              required 
                              autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Address')" class="text-lg font-semibold text-gray-700" />
                <x-text-input id="email" 
                              class="block mt-1 w-full text-lg border-gray-300 rounded-md bg-gray-50 text-gray-500 shadow-sm focus:border-green-500 focus:ring-green-500" 
                              type="email" 
                              name="email" 
                              :value="old('email', $request->email)" 
                              required 
                              readonly />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Create Password')" class="text-lg font-semibold text-gray-700" />
                <x-text-input id="password" 
                              class="block mt-1 w-full text-lg border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" 
                              type="password" 
                              name="password" 
                              required 
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-lg font-semibold text-gray-700" />
                <x-text-input id="password_confirmation" 
                              class="block mt-1 w-full text-lg border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" 
                              type="password" 
                              name="password_confirmation" 
                              required 
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-primary-button class="w-full justify-center px-6 py-2.5 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                    {{ __('Activate Account & Login') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>