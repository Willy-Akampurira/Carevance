<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: false, sidebarCollapsed: false, mobileSidebarOpen: false }" 
      :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Supreme-Clinic') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome 6 -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="min-h-screen flex bg-gray-100">

        <!-- Sidebar (desktop) -->
        <aside :class="sidebarCollapsed ? 'w-20' : 'w-72'"
               class="hidden sm:block fixed inset-y-0 left-0 bg-green-900 text-white z-40 transition-all duration-300 ease-in-out">
            <x-sidebar />
        </aside>

        <!-- Mobile Sidebar Backdrop -->
        <div x-show="mobileSidebarOpen" 
             class="sm:hidden fixed inset-0 bg-black bg-opacity-50 z-40"
             @click="mobileSidebarOpen = false"
             x-transition.opacity></div>

        <!-- Mobile Sidebar -->
        <aside x-show="mobileSidebarOpen"
               class="sm:hidden fixed inset-y-0 left-0 w-64 bg-green-900 text-white z-50 transform transition-transform duration-300 ease-in-out"
               x-transition:enter="transform transition ease-in-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transform transition ease-in-out duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">
            <x-sidebar />
        </aside>

        <!-- Main Content -->
        <div :class="sidebarCollapsed ? 'sm:ml-20' : 'sm:ml-72'" 
             class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out">

            <!-- Navbar -->
            <nav class="sticky top-0 z-40 bg-white shadow px-4 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <!-- Collapse/expand sidebar (desktop only) -->
                    <button @click="sidebarCollapsed = !sidebarCollapsed"
                            class="hidden sm:inline text-green-600 hover:text-green-900 transition text-2xl" title="Collapse sidebar">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>

                    <!-- Hamburger (mobile only) -->
                    <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                            class="sm:hidden text-green-600 hover:text-green-900 transition text-2xl" title="Menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>

                <!-- Right side: Notifications + Profile -->
                <div class="flex items-center gap-6">
                    <!-- Expiry Notifications -->
                    @hasanyrole('admin|pharmacist')
                    @php
                        // Threshold days
                        $thresholdDays = (int) (\App\Models\Setting::where('setting_key', 'expiry_threshold')->value('value')
                            ?? config('inventory.expiry_threshold', 30));

                        $today     = \Illuminate\Support\Carbon::today();
                        $dateLimit = $today->copy()->addDays($thresholdDays);

                        // Expired lots
                        $expiredCount = \App\Models\Drug::whereHas('stockLots', function ($q) use ($today) {
                            $q->where('expiry_date', '<', $today);
                        })->count();

                        // Nearing expiry lots
                        $nearingCount = \App\Models\Drug::whereHas('stockLots', function ($q) use ($today, $dateLimit) {
                            $q->whereBetween('expiry_date', [$today, $dateLimit]);
                        })->count();

                        // Total notifications
                        $expiryCount = $expiredCount + $nearingCount;
                    @endphp

                    <div class="relative">
                        <a href="{{ route('expiry.notifications') }}"
                        class="flex items-center text-red-600 hover:text-red-900 relative"
                        title="Expiry Notifications">
                            <i class="fa-solid fa-bell text-2xl"></i>
                            @if($expiryCount > 0)
                                <span class="absolute -top-1 -right-2 px-1.5 py-0.5 rounded-full bg-red-500 text-white text-xs font-semibold">
                                    {{ $expiryCount > 9 ? '9+' : $expiryCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                    @endhasanyrole
            
                    <!-- Low Stock Alerts -->
                     @hasanyrole('admin|pharmacist')
                    <div class="relative">
                        <a href="{{ route('stock.low') }}"
                           class="flex items-center text-yellow-600 hover:text-red-600 relative"
                           title="Low Stock Alerts">
                            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                            @if(!empty($lowStockCount) && $lowStockCount > 0)
                                <span class="absolute -top-1 -right-2 px-1.5 py-0.5 rounded-full bg-red-500 text-white text-xs font-semibold">
                                    {{ $lowStockCount > 9 ? '9+' : $lowStockCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                    @endhasanyrole

                    <!-- Profile dropdown -->
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen"
                                class="flex items-center gap-2 px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none">
                            <i class="fa-solid fa-user-circle text-xl text-gray-600"></i>
                            <span class="text-sm">{{ Auth::user()->name ?? 'Guest' }}</span>
                            <i class="fa-solid fa-caret-down text-gray-500"></i>
                        </button>

                        <div x-show="profileOpen"
                             @click.away="profileOpen = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 z-50">
                            <a href="{{ route('profile.edit') }}" 
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            @hasSection('header')
                <header class="bg-white shadow">
                    <div class="px-8 py-6">
                        @yield('header')
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1 px-4 sm:px-8 py-6 space-y-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer-->
    <footer" class="py-4 mt-10 flex justify-center items-center px-4 w-full text-gray-700">
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
</body>
</html>
