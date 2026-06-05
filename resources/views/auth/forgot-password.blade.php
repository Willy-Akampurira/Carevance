<x-guest-layout>
    <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg p-8">
        
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Account Activation & Recovery</h2>
            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                {{ __('Enter your registered clinic email address below. We will send you a secure link to activate your brand new account or reset your current password.') }}
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Clinic Email Address')" class="text-xl font-semibold text-gray-700" />
                <x-text-input id="email" 
                              class="block mt-1 w-full text-lg border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" 
                              type="email" 
                              name="email" 
                              :value="old('email')" 
                              placeholder="name@supremeclinic.com"
                              required 
                              autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-green-600 underline transition focus:outline-none focus:ring-2 focus:ring-green-500 rounded">
                    {{ __('Back to login') }}
                </a>

                <x-primary-button class="px-5 py-2.5 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                    {{ __('Email Link') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>