<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: false, sidebarCollapsed: false, mobileSidebarOpen: false }" 
      :class="darkMode ? 'dark' : ''"
      class="h-full overflow-hidden text-[13.5px] lg:text-sm">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Supreme-Clinic') }}</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900 h-full overflow-hidden">
    
    {{-- Main View Container System Layout Wrapper Framework --}}
    <div class="h-screen w-screen flex overflow-hidden relative bg-gray-100">

        {{-- Desktop Sidebar Panel Layout (Ultra-Thin 2px Scrollbar) --}}
        <aside :class="sidebarCollapsed ? 'w-20' : 'w-72'"
               class="hidden sm:block fixed inset-y-0 left-0 bg-gray-900 text-white z-40 transition-all duration-300 ease-in-out h-full overflow-y-auto overflow-x-hidden px-2
                      [&::-webkit-scrollbar]:w-[1px]
                      [&::-webkit-scrollbar-track]:bg-transparent
                      [&::-webkit-scrollbar-thumb]:bg-transparent
                      hover:[&::-webkit-scrollbar-thumb]:bg-slate-700/60
                      [&::-webkit-scrollbar-thumb]:rounded-full
                      [scrollbar-width:thin]
                      [scrollbar-color:transparent_transparent]
                      hover:[scrollbar-color:rgba(71,85,105,0.6)_transparent]">
            <div class="h-full py-4 flex flex-col">
                <x-sidebar />
            </div>
        </aside>

        {{-- Mobile Sidebar Drawer Backdrop Overlay --}}
        <div x-show="mobileSidebarOpen" 
             x-cloak
             class="sm:hidden fixed inset-0 bg-black bg-opacity-60 z-40"
             @click="mobileSidebarOpen = false"
             x-transition.opacity></div>

        {{-- Mobile Drawer Navigation Sidebar Panel --}}
        <aside x-show="mobileSidebarOpen"
               x-cloak
               class="sm:hidden fixed inset-y-0 left-0 w-64 bg-gray-900 text-white z-50 transform transition-transform duration-300 ease-in-out h-full overflow-y-auto overflow-x-hidden px-2
                      [&::-webkit-scrollbar]:w-[2px]
                      [&::-webkit-scrollbar-track]:bg-transparent
                      [&::-webkit-scrollbar-thumb]:bg-transparent
                      hover:[&::-webkit-scrollbar-thumb]:bg-slate-700/60
                      [&::-webkit-scrollbar-thumb]:rounded-full
                      [scrollbar-width:thin]
                      [scrollbar-color:transparent_transparent]
                      hover:[scrollbar-color:rgba(71,85,105,0.6)_transparent]"
               x-transition:enter="transform transition ease-in-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transform transition ease-in-out duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">
            <div class="h-full py-4 flex flex-col">
                <x-sidebar />
            </div>
        </aside>

        {{-- Core Application View Surface Frame Canvas Wrapper --}}
        <div :class="sidebarCollapsed ? 'sm:ml-20' : 'sm:ml-72'" 
             class="w-full flex-1 flex flex-col h-screen max-h-screen overflow-hidden transition-all duration-300 ease-in-out">

            {{-- Optimized Sticky Header Topbar Nav Utilities Element --}}
            <nav class="flex-shrink-0 h-16 bg-white shadow px-4 flex items-center justify-between z-30">
                <div class="flex items-center gap-3">
                    <button @click="sidebarCollapsed = !sidebarCollapsed"
                            class="hidden sm:inline text-green-600 hover:text-green-900 transition text-xl" title="Collapse sidebar">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>

                    <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                            class="sm:hidden text-green-600 hover:text-green-900 transition text-xl" title="Menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>

                <div class="flex items-center gap-4 sm:gap-5">
                    @hasanyrole('admin|pharmacist')
                    @php
                        $thresholdDays = (int) (\App\Models\Setting::where('setting_key', 'expiry_threshold')->value('value')
                            ?? config('inventory.expiry_threshold', 30));

                        $today     = \Illuminate\Support\Carbon::today();
                        $dateLimit = $today->copy()->addDays($thresholdDays);

                        $expiredCount = \App\Models\Drug::whereHas('stockLots', function ($q) use ($today) {
                            $q->where('expiry_date', '<', $today);
                        })->count();

                        $nearingCount = \App\Models\Drug::whereHas('stockLots', function ($q) use ($today, $dateLimit) {
                            $q->where('expiry_date', [$today, $dateLimit]);
                        })->count();

                        $expiryCount = $expiredCount + $nearingCount;
                    @endphp

                    <div class="relative">
                        <a href="{{ route('expiry.notifications') }}"
                        class="flex items-center text-red-600 hover:text-red-900 relative"
                        title="Expiry Notifications">
                            <i class="fa-solid fa-bell text-lg sm:text-xl"></i>
                            @if($expiryCount > 0)
                                <span class="absolute -top-1.5 -right-2 px-1.5 py-0.5 rounded-full bg-red-500 text-white text-[10px] font-semibold">
                                    {{ $expiryCount > 9 ? '9+' : $expiryCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                    @endhasanyrole
            
                    @hasanyrole('admin|pharmacist')
                    <div class="relative">
                        <a href="{{ route('stock.low') }}"
                           class="flex items-center text-yellow-600 hover:text-red-600 relative"
                           title="Low Stock Alerts">
                            <i class="fa-solid fa-triangle-exclamation text-lg sm:text-xl"></i>
                            @if(!empty($lowStockCount) && $lowStockCount > 0)
                                <span class="absolute -top-1.5 -right-2 px-1.5 py-0.5 rounded-full bg-red-500 text-white text-[10px] font-semibold">
                                    {{ $lowStockCount > 9 ? '9+' : $lowStockCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                    @endhasanyrole

                    {{-- User Profile Utilities Dropdown Menu --}}
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen"
                                class="flex items-center gap-1.5 px-2 py-1.5 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none">
                            <i class="fa-solid fa-user-circle text-lg text-gray-600"></i>
                            <span class="text-xs hidden xs:inline font-medium">{{ Auth::user()->name ?? 'Guest' }}</span>
                            <i class="fa-solid fa-caret-down text-gray-500 text-[10px]"></i>
                        </button>

                        <div x-show="profileOpen"
                             @click.away="profileOpen = false"
                             x-transition
                             x-cloak
                             class="absolute right-0 mt-2 w-44 bg-white shadow-lg rounded-md py-1.5 z-50 border border-gray-100">
                            <a href="{{ route('profile.edit') }}" 
                               class="block px-4 py-1.5 text-xs text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-1.5 text-xs text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- Compacted Sub-Header Wrapper Section Element Layout --}}
            @hasSection('header')
                <header class="bg-white border-b border-gray-100 flex-shrink-0">
                    <div class="px-4 sm:px-6 py-3">
                        @yield('header')
                    </div>
                </header>
            @endif

            {{-- Core Inner Views Container Pane: (Clean 5px Wide Scrollbar Track Layout) --}}
            <div class="flex-1 overflow-y-auto flex flex-col justify-between">
                <main class="p-4 sm:p-5 space-y-4 overflow-x-hidden flex-1
                             [&::-webkit-scrollbar]:w-[5px]
                             [&::-webkit-scrollbar-track]:bg-transparent
                             [&::-webkit-scrollbar-thumb]:bg-slate-400/40
                             [&::-webkit-scrollbar-thumb]:rounded-full
                             hover:[&::-webkit-scrollbar-thumb]:bg-slate-500/70
                             [scrollbar-width:thin]
                             [scrollbar-color:rgba(148,163,184,0.4)_transparent]">
                    @yield('content')
                </main>

                {{-- Compact Layout System Footer Bar Component Element --}}
                <footer class="py-3 flex-shrink-0 flex justify-center items-center px-4 w-full text-gray-500 border-t border-gray-200 bg-white">
                    <p class="text-xs text-center flex flex-wrap items-center justify-center gap-x-4 gap-y-1.5">
                        &copy; {{ date('Y') }} 
                        {{ \App\Models\Setting::where('setting_key','clinic_name')->value('value') ?? 'Supreme-Clinic' }}. 
                        {{ \App\Models\Setting::where('setting_key','footer_text')->value('value') ?? 'All Rights Reserved.' }}

                        <span class="inline-flex items-center text-gray-400">
                            <i class="fas fa-phone text-green-600 mr-1.5"></i>
                            {{ \App\Models\Setting::where('setting_key','clinic_phone')->value('value') ?? '+256 700 123456' }}
                        </span>

                        <span class="inline-flex items-center text-gray-400">
                            <i class="fas fa-envelope text-blue-600 mr-1.5"></i>
                            {{ \App\Models\Setting::where('setting_key','clinic_email')->value('value') ?? 'info@supremeclinic.ug' }}
                        </span>

                        <span class="flex items-center gap-2.5 ml-1">
                            <a href="{{ \App\Models\Setting::where('setting_key','facebook_url')->value('value') ?? '#' }}" 
                               class="text-blue-600 hover:scale-105 transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="{{ \App\Models\Setting::where('setting_key','twitter_url')->value('value') ?? '#' }}" 
                               class="text-gray-900 hover:scale-105 transition">
                                <i class="fab fa-x-twitter"></i>
                            </a>
                            <a href="{{ \App\Models\Setting::where('setting_key','whatsapp_url')->value('value') ?? '#' }}" 
                               class="text-green-600 hover:scale-105 transition">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </span>
                    </p>
                </footer>
            </div>

        </div>
    </div>
</body>
</html>