<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<body class="h-screen w-screen overflow-x-hidden">

    <!-- Fullscreen Background -->
    <div class="relative h-screen w-full flex flex-col justify-between">
        <img src="{{ asset('storage/' . (\App\Models\Setting::where('setting_key','welcome_bg')->value('value') ?? 'pharmacare.webp')) }}" 
             alt="Welcome Background" 
             class="absolute inset-0 w-full h-full object-cover">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Centered Content -->
        <div class="relative flex flex-col items-center justify-center flex-grow text-center text-white px-4">
            
            <!-- Clinic Logo -->
            <img src="{{ asset('storage/' . (\App\Models\Setting::where('setting_key','clinic_logo')->value('value') ?? 'logo.png')) }}" 
                 alt="{{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Clinic Logo' }}" 
                 class="w-32 h-32 mb-6">

            <h1 class="text-5xl font-bold mb-4">
                Welcome to {{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme Clinic' }}
            </h1>
            <p class="text-2xl mb-8 text-gray-200 text-center max-w-4xl">
                {{ \App\Models\Setting::where('setting_key','clinic_tagline')->value('value') ?? 'Your trusted partner in compassionate healthcare.' }}
            </p>

            <!-- CTA -->
            <div class="space-x-4 mb-6">
                <a href="{{ route('login') }}" 
                   class="px-6 py-3 bg-green-600 rounded text-xl hover:bg-green-700">
                   <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
            </div>

            <!-- Contact Information -->
            <div class="space-y-2 text-lg text-gray-100">
                <p><i class="fas fa-map-marker-alt mr-2 text-red-500"></i> {{ \App\Models\Setting::where('setting_key','clinic_address')->value('value') ?? 'Mbarara, Uganda' }}</p>
                <p><i class="fas fa-phone mr-2 text-green-500"></i> {{ \App\Models\Setting::where('setting_key','clinic_phone')->value('value') ?? '+256 700 123456' }}</p>
                <p><i class="fas fa-envelope mr-2 text-blue-500"></i> {{ \App\Models\Setting::where('setting_key','clinic_email')->value('value') ?? 'info@supremeclinic.ug' }}</p>
                <p><i class="fas fa-clock mr-2 text-yellow-500"></i> {{ \App\Models\Setting::where('setting_key','clinic_hours')->value('value') ?? 'Open 24/7' }}</p>
            </div>
        </div>

        <!-- Footer -->
        <footer class="relative py-4 flex justify-center items-center px-4 w-full text-gray-200 z-10">
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
