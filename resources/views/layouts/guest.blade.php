<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme-Clinic' }}</title>

    <link rel="icon" 
          href="{{ asset('storage/' . (\App\Models\Setting::where('setting_key','clinic_logo')->value('value') ?? 'logo.png')) }}" 
          type="image/png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased" 
      style="background-image: url('{{ asset('storage/' . (\App\Models\Setting::where('setting_key','guest_bg')->value('value') ?? 'pharmacare.webp')) }}'); 
             background-size: cover; 
             background-position: center;
             background-attachment: fixed;">
    
    <div class="min-h-screen flex flex-col justify-between items-center px-4 py-8 bg-gray-900 bg-opacity-60 overflow-y-auto">
        
        <div class="flex justify-center items-center mt-2">
            <a href="/">
                <img src="{{ asset('storage/' . (\App\Models\Setting::where('setting_key','clinic_logo')->value('value') ?? 'logo.png')) }}" 
                     alt="{{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme Clinic' }} Logo" 
                     class="w-24 h-24 sm:w-32 sm:h-32 object-contain drop-shadow-md">
            </a>
        </div>

        <div class="w-full flex flex-col items-center justify-center my-6 space-y-6">
            
            <div class="w-full sm:max-w-md px-6 py-6 bg-white bg-opacity-95 shadow-xl rounded-xl backdrop-blur-sm">
                {{ $slot }}
            </div>

            <div class="w-full max-w-md space-y-2 text-sm sm:text-base text-gray-100 text-center px-2">
                <p class="flex items-center justify-center gap-2 flex-wrap">
                    <i class="fas fa-map-marker-alt text-red-500"></i> 
                    <span>{{ \App\Models\Setting::where('setting_key','clinic_address')->value('value') ?? 'Mbarara, Uganda' }}</span>
                </p>
                <p class="flex items-center justify-center gap-2">
                    <i class="fas fa-phone text-green-400"></i> 
                    <span>{{ \App\Models\Setting::where('setting_key','clinic_phone')->value('value') ?? '+256 700 123456' }}</span>
                </p>
                <p class="flex items-center justify-center gap-2 break-all">
                    <i class="fas fa-envelope text-blue-400"></i> 
                    <span>{{ \App\Models\Setting::where('setting_key','clinic_email')->value('value') ?? 'info@supremeclinic.ug' }}</span>
                </p>
                <p class="flex items-center justify-center gap-2">
                    <i class="fas fa-clock text-yellow-400"></i> 
                    <span>{{ \App\Models\Setting::where('setting_key','clinic_hours')->value('value') ?? 'Open 24/7' }}</span>
                </p>
            </div>
        </div>

        <footer class="w-full max-w-2xl mt-auto pt-4 border-t border-gray-700 border-opacity-40 text-center space-y-3">
            <div class="flex items-center justify-center gap-6 text-xl">
                <a href="{{ \App\Models\Setting::where('setting_key','facebook_url')->value('value') ?? '#' }}" 
                   class="text-blue-400 hover:text-blue-300 transition-colors" target="_blank" rel="noopener">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="{{ \App\Models\Setting::where('setting_key','twitter_url')->value('value') ?? '#' }}" 
                   class="text-gray-300 hover:text-white transition-colors" target="_blank" rel="noopener">
                    <i class="fab fa-x-twitter"></i>
                </a>
                <a href="{{ \App\Models\Setting::where('setting_key','whatsapp_url')->value('value') ?? '#' }}" 
                   class="text-green-400 hover:text-green-300 transition-colors" target="_blank" rel="noopener">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>

            <p class="text-xs text-gray-300 leading-relaxed px-4">
                &copy; {{ date('Y') }} 
                {{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme-Clinic' }}. 
                {{ \App\Models\Setting::where('setting_key','footer_text')->value('value') ?? 'All Rights Reserved.' }}
            </p>
        </footer>
    </div>
</body>
</html>