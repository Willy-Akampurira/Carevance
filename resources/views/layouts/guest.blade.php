<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Carevance') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/webp">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased" 
          style="background-image: url('{{ asset('images/pharmacare.webp') }}'); 
                 background-size: cover; 
                 background-position: center;">
        
        <!-- Overlay to improve readability -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900 bg-opacity-50">
            <div>
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="Carevance Logo" class="w-32 h-32">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <footer class="absolute bottom-4 text-xs text-gray-300 text-center">
               <p class="text-sm text-center">
                    &copy; {{ date('Y') }} Carevance. All rights reserved. 
                    <span class="ml-2">Designed & Developed by Carevance Team</span>
                </p>
            </footer>
        </div>
    </body>
</html>
