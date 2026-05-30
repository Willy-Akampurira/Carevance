<!DOCTYPE html>
<html lang="en"
      class="text-[13.5px] lg:text-sm"> {{-- MATCHES THE CHOSEN APP BASELINE SCALING --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<body class="min-h-screen antialiased bg-gray-900 select-none">

    <div class="relative min-h-screen w-full flex flex-col justify-between items-center overflow-y-auto">
        
        <div class="absolute inset-0 w-full h-full z-0">
            <img src="{{ asset('storage/' . (\App\Models\Setting::where('setting_key','welcome_bg')->value('value') ?? 'pharmacare.webp')) }}" 
                 alt="Welcome Background" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-65 backdrop-blur-[2px]"></div>
        </div>

        <div class="relative z-10 w-full flex flex-col items-center justify-center flex-grow text-center text-white px-6 py-10 max-w-4xl mx-auto space-y-5 sm:space-y-6">
            
            <div class="transform hover:scale-105 transition-transform duration-300 ease-out active:scale-95">
                <img src="{{ asset('storage/' . (\App\Models\Setting::where('setting_key','clinic_logo')->value('value') ?? 'logo.png')) }}" 
                     alt="{{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Clinic Logo' }}" 
                     class="w-20 h-20 sm:w-28 sm:h-28 object-contain drop-shadow-xl mx-auto">
            </div>

            <div class="space-y-2 sm:space-y-3">
                <h1 class="text-2xl sm:text-4xl font-bold tracking-tight leading-tight px-2">
                    Welcome to <span class="text-green-400">{{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme Clinic' }}</span>
                </h1>
                <p class="text-sm sm:text-base text-gray-300 max-w-xl mx-auto font-light leading-relaxed px-4">
                    {{ \App\Models\Setting::where('setting_key','clinic_tagline')->value('value') ?? 'Your trusted partner in compassionate healthcare.' }}
                </p>
            </div>

            <div class="pt-1 w-full max-w-xs sm:max-w-none flex justify-center">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center justify-center px-7 py-2.5 bg-green-600 text-white rounded-lg text-sm sm:text-base font-medium tracking-wide shadow-lg shadow-green-950/50 hover:bg-green-500 active:scale-98 transform transition-all duration-150 w-full sm:w-auto">
                    <i class="fas fa-sign-in-alt mr-2 text-xs sm:text-sm opacity-90"></i> Login to System
                </a>
            </div>

            {{-- Squeezed metrics panel tracking text slightly down to stay proportional --}}
            <div class="w-full max-w-md pt-4 sm:pt-5 space-y-2 text-xs sm:text-sm text-gray-200 border-t border-white border-opacity-10 px-4">
                <p class="flex items-center justify-center gap-2.5">
                    <i class="fas fa-map-marker-alt text-red-500 w-5 text-center text-xs sm:text-sm"></i> 
                    <span>{{ \App\Models\Setting::where('setting_key','clinic_address')->value('value') ?? 'Mbarara, Uganda' }}</span>
                </p>
                <p class="flex items-center justify-center gap-2.5">
                    <i class="fas fa-phone text-green-400 w-5 text-center text-xs sm:text-sm"></i> 
                    <span>{{ \App\Models\Setting::where('setting_key','clinic_phone')->value('value') ?? '+256 700 123456' }}</span>
                </p>
                <p class="flex items-center justify-center gap-2.5 break-all">
                    <i class="fas fa-envelope text-blue-400 w-5 text-center text-xs sm:text-sm"></i> 
                    <span>{{ \App\Models\Setting::where('setting_key','clinic_email')->value('value') ?? 'info@supremeclinic.ug' }}</span>
                </p>
                <p class="flex items-center justify-center gap-2.5">
                    <i class="fas fa-clock text-yellow-400 w-5 text-center text-xs sm:text-sm"></i> 
                    <span>{{ \App\Models\Setting::where('setting_key','clinic_hours')->value('value') ?? 'Open 24/7' }}</span>
                </p>
            </div>
        </div>

        <footer class="relative z-10 w-full max-w-4xl mx-auto px-6 pb-5 pt-3 border-t border-gray-800 border-opacity-60 text-center space-y-2 mt-auto">
            
            <div class="flex items-center justify-center gap-5 text-lg">
                <a href="{{ \App\Models\Setting::where('setting_key','facebook_url')->value('value') ?? '#' }}" 
                   class="text-blue-400 hover:text-blue-300 transition-colors duration-200" target="_blank" rel="noopener">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="{{ \App\Models\Setting::where('setting_key','twitter_url')->value('value') ?? '#' }}" 
                   class="text-gray-400 hover:text-white transition-colors duration-200" target="_blank" rel="noopener">
                    <i class="fab fa-x-twitter"></i>
                </a>
                <a href="{{ \App\Models\Setting::where('setting_key','whatsapp_url')->value('value') ?? '#' }}" 
                   class="text-green-400 hover:text-green-300 transition-colors duration-200" target="_blank" rel="noopener">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>

            <p class="text-[11px] text-gray-400 leading-relaxed max-w-xl mx-auto font-light">
                &copy; {{ date('Y') }} 
                {{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme-Clinic' }}. 
                {{ \App\Models\Setting::where('setting_key','footer_text')->value('value') ?? 'All Rights Reserved.' }}
            </p>
        </footer>
    </div>
</body>
</html>