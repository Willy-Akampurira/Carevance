<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ sidebarCollapsed: false, mobileSidebarOpen: false }" 
      class="h-full overflow-hidden text-[13.5px] lg:text-sm">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SuperAdmin Control Panel - Carevance</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-900 text-slate-100 h-full overflow-hidden">
    
    <div class="h-screen w-screen flex overflow-hidden relative bg-slate-950">

        <!-- Sidebar -->
        <aside :class="sidebarCollapsed ? 'w-20' : 'w-72'"
               class="hidden sm:block fixed inset-y-0 left-0 bg-slate-900 text-white z-40 transition-all duration-300 ease-in-out h-full overflow-y-auto px-2">
            <div class="h-full py-4 flex flex-col justify-between">
                <div>
                    <!-- Logo / Brand Header -->
                    <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-white shadow-md">
                            C
                        </div>
                        <span x-show="!sidebarCollapsed" class="font-semibold text-lg tracking-wider text-slate-200">
                            Carevance <span class="text-xs text-indigo-400 font-normal">SaaS</span>
                        </span>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="mt-6 space-y-1">
                        <a href="{{ route('superadmin.dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-all {{ Route::is('superadmin.dashboard') ? 'bg-indigo-600/20 text-indigo-400 font-medium' : '' }}">
                            <i class="fa-solid fa-chart-line text-lg w-6 text-center"></i>
                            <span x-show="!sidebarCollapsed">Dashboard</span>
                        </a>

                        <a href="{{ route('superadmin.tenants.index') }}" 
                           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-all {{ Route::is('superadmin.tenants.*') ? 'bg-indigo-600/20 text-indigo-400 font-medium' : '' }}">
                            <i class="fa-solid fa-clinic-medical text-lg w-6 text-center"></i>
                            <span x-show="!sidebarCollapsed">Clinics (Tenants)</span>
                        </a>
                    </nav>
                </div>

                <!-- Footer Section -->
                <div class="px-4 py-3 border-t border-slate-800">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="flex items-center gap-3 w-full py-2 px-3 text-red-400 hover:bg-red-500/10 hover:text-red-300 rounded-lg transition-all">
                            <i class="fa-solid fa-sign-out-alt text-lg w-6 text-center"></i>
                            <span x-show="!sidebarCollapsed">Log Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Body Wrapper -->
        <div :class="sidebarCollapsed ? 'sm:ml-20' : 'sm:ml-72'" 
             class="w-full flex-1 flex flex-col h-screen max-h-screen overflow-hidden transition-all duration-300 ease-in-out">

            <!-- Sticky Topbar Header -->
            <nav class="flex-shrink-0 h-16 bg-slate-900 border-b border-slate-800 px-4 flex items-center justify-between z-30 shadow-sm">
                <div class="flex items-center gap-3">
                    <button @click="sidebarCollapsed = !sidebarCollapsed"
                            class="hidden sm:inline text-indigo-500 hover:text-indigo-400 transition text-xl">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-slate-300">
                        <i class="fa-solid fa-user-shield text-lg text-indigo-400"></i>
                        <span class="text-xs font-semibold tracking-wide uppercase">{{ Auth::user()->name ?? 'Super Admin' }}</span>
                    </div>
                </div>
            </nav>

            <!-- Notification Messages -->
            @if(session('success'))
                <div class="mx-6 mt-4 p-4 bg-emerald-950/40 border border-emerald-800 text-emerald-400 rounded-lg flex items-center gap-3 shadow-md">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Scrollable Content View Pane -->
            <div class="flex-1 overflow-y-auto flex flex-col justify-between">
                <main class="p-6 space-y-6 flex-1 bg-slate-950">
                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="py-3 flex-shrink-0 text-center text-xs text-slate-500 border-t border-slate-900 bg-slate-900/60">
                    &copy; {{ date('Y') }} Carevance Platform Administration Panel. All Rights Reserved.
                </footer>
            </div>

        </div>
    </div>
</body>
</html>
