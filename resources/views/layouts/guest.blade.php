<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme-Clinic' }}</title>

    <!-- Favicon -->
    <link rel="icon" 
          href="{{ asset('storage/' . (\App\Models\Setting::where('setting_key','clinic_logo')->value('value') ?? 'logo.png')) }}" 
          type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome 6 -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased" 
      style="background-image: url('{{ asset('storage/' . (\App\Models\Setting::where('setting_key','guest_bg')->value('value') ?? 'pharmacare.webp')) }}'); 
             background-size: cover; 
             background-position: center;">
    
    <!-- Overlay to improve readability -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900 bg-opacity-50">
        
        <!-- Clinic Logo -->
        <div>
            <a href="/">
                <img src="{{ asset('storage/' . (\App\Models\Setting::where('setting_key','clinic_logo')->value('value') ?? 'logo.png')) }}" 
                     alt="{{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme Clinic' }} Logo" 
                     class="w-32 h-32">
            </a>
        </div>

        <!-- Guest Content Slot -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>

        <!-- Contact Information -->
        <div class="space-y-2 text-lg text-gray-100 text-center mb-4 mt-6">
            <p><i class="fas fa-map-marker-alt mr-2 text-red-500"></i> {{ \App\Models\Setting::where('setting_key','clinic_address')->value('value') ?? 'Mbarara, Uganda' }}</p>
            <p><i class="fas fa-phone mr-2 text-green-500"></i> {{ \App\Models\Setting::where('setting_key','clinic_phone')->value('value') ?? '+256 700 123456' }}</p>
            <p><i class="fas fa-envelope mr-2 text-blue-500"></i> {{ \App\Models\Setting::where('setting_key','clinic_email')->value('value') ?? 'info@supremeclinic.ug' }}</p>
            <p><i class="fas fa-clock mr-2 text-yellow-500"></i> {{ \App\Models\Setting::where('setting_key','clinic_hours')->value('value') ?? 'Open 24/7' }}</p>
        </div>

        <!-- Footer -->
        <footer class="absolute bottom-4 text-xs text-gray-300 text-center">
            <p class="text-sm text-center flex flex-wrap items-center justify-center gap-4">
                &copy; {{ date('Y') }} 
                {{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme-Clinic' }}. 
                {{ \App\Models\Setting::where('setting_key','footer_text')->value('value') ?? 'Designed & Developed by Supreme-Clinic Team' }}

                <span>
                    <i class="fas fa-phone text-green-500 mr-1"></i>
                    {{ \App\Models\Setting::where('setting_key','clinic_phone')->value('value') ?? '+256 700 123456' }}
                </span>

                <span>
                    <i class="fas fa-envelope text-blue-500 mr-1"></i>
                    {{ \App\Models\Setting::where('setting_key','clinic_email')->value('value') ?? 'info@supremeclinic.ug' }}
                </span>

                <span>
                    <a href="{{ \App\Models\Setting::where('setting_key','facebook_url')->value('value') ?? '#' }}" 
                    class="text-blue-600 hover:underline mr-2">
                    <i class="fab fa-facebook"></i>
                    </a>
                    <a href="{{ \App\Models\Setting::where('setting_key','twitter_url')->value('value') ?? '#' }}" 
                    class="text-black hover:underline mr-2">
                    <i class="fab fa-x-twitter"></i>
                    </a>
                    <a href="{{ \App\Models\Setting::where('setting_key','whatsapp_url')->value('value') ?? '#' }}" 
                    class="text-green-500 hover:underline">
                    <i class="fab fa-whatsapp"></i>
                    </a>
                </span>
            </p>
        </footer>
    </div>
</body>
</html>
