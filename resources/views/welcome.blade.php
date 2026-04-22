<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carevance</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen w-screen overflow-x-hidden">

    <!-- Fullscreen Background -->
    <div class="relative h-screen w-full flex flex-col justify-between">
        <img src="{{ asset('images/pharmacare.webp') }}" 
             alt="Pharmacy Background" 
             class="absolute inset-0 w-full h-full object-cover">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Centered Content -->
        <div class="relative flex flex-col items-center justify-center flex-grow text-center text-white px-4">
            
            <!-- Carevance Logo -->
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Carevance Logo" 
                 class="w-32 h-32 mb-6">

            <h1 class="text-5xl font-bold mb-4">Welcome to Carevance</h1>
            <p class="text-2xl mb-8">A Medical Clinic Management System</p>

            <div class="space-x-4">
                <a href="{{ route('login') }}" 
                   class="px-6 py-3 bg-green-600 rounded text-xl hover:bg-green-700">
                   Login
                </a>
                {{-- 
                <a href="{{ route('register') }}" 
                   class="px-6 py-3 bg-blue-600 rounded text-xl hover:bg-blue-700">
                   Register
                </a> 
                --}}
            </div>
        </div>

        <!-- Footer (bottom, centered, no light background) -->
        <footer class="relative py-4 flex justify-center items-center px-4 w-full text-gray-200 z-10">
            <p class="text-sm text-center">
                &copy; {{ date('Y') }} Carevance. All rights reserved. 
                <span class="ml-2">Designed & Developed by Carevance Team</span>
            </p>
        </footer>
    </div>
</body>
</html>
