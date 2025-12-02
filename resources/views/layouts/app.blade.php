<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: false, sidebarCollapsed: false }" 
      :class="darkMode ? 'dark' : ''">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Vite assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900">
        <div class="min-h-screen flex bg-gray-100">

            <!-- Sidebar -->
            <aside x-bind:class="sidebarCollapsed ? 'w-20' : 'w-72'"
                   class="fixed inset-y-0 left-0 bg-green-900 text-white z-50 transition-all duration-300 ease-in-out">
                <x-sidebar />
            </aside>

            <!-- Main Content -->
            <div x-bind:class="sidebarCollapsed ? 'ml-20' : 'ml-72'" 
                 class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out">

                <!-- Navbar -->
                <nav class="sticky top-0 z-50 bg-white shadow px-4 py-6 flex items-center justify-between">
                    <!-- Left side: collapse toggle near sidebar -->
                    <div class="flex items-center">
                        <button @click="sidebarCollapsed = !sidebarCollapsed"
                                class="text-green-600 hover:text-green-900 transition" title="Collapse sidebar">
                            <i x-show="!sidebarCollapsed" class="fas fa-angle-double-left"></i>
                            <i x-show="sidebarCollapsed" class="fas fa-angle-double-right"></i>
                        </button>
                    </div>

                    <!-- Right side: dark mode + profile -->
                    <div class="flex items-center gap-3">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode"
                                class="text-gray-600 hover:text-gray-900 transition" title="Toggle theme">
                            <i x-show="!darkMode" class="fas fa-moon"></i>
                            <i x-show="darkMode" class="fas fa-sun"></i>
                        </button>

                        <!-- Profile -->
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user-circle text-xl text-gray-600"></i>
                            <span class="text-sm">{{ Auth::user()->name ?? 'Guest' }}</span>
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
                <main class="flex-1 overflow-y-auto px-8 py-6 space-y-8">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
