<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-3">
                <!-- Sidebar toggle (open/close) -->
                <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-600 hover:text-gray-900 transition" title="Toggle sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <!-- Collapse/expand sidebar width -->
                <button @click="sidebarCollapsed = !sidebarCollapsed"
                        class="text-gray-600 hover:text-gray-900 transition" title="Collapse sidebar">
                    <i x-show="!sidebarCollapsed" class="fa-solid fa-bars-staggered"></i>
                    <i x-show="sidebarCollapsed" class="fa-solid fa-bars-staggered"></i>
                </button>

                <!-- Logo -->
                <div class="shrink-0 flex items-center ms-4">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="Carevance Logo" class="h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right side: Low Stock Alerts + Profile -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <!-- Expiry Notifications Icon -->
                 @hasanyrole('admin|pharmacist')
                <div class="relative">
                    <a href="{{ route('expiry.notifications') }}"
                    class="flex items-center text-red-600 hover:text-red-900 relative"
                    title="Expiry Notifications">
                        <i class="fa-solid fa-bell text-2xl"></i>

                        {{-- Badge positioned at top-right of bell --}}
                        @if(!empty($expiryCount) && $expiryCount > 0)
                            <span class="absolute -top-1 -right-2 px-1.5 py-0.5 rounded-full bg-red-500 text-white text-xs font-semibold">
                                {{ $expiryCount > 9 ? '9+' : $expiryCount }}
                            </span>
                        @endif
                    </a>
                </div>
                @endhasanyrole

                <!-- Low Stock Alerts Icon -->
                @hasanyrole('admin|pharmacist')
                <div class="relative">
                    <a href="{{ route('stock.low') }}"
                    class="flex items-center text-yellow-600 hover:text-red-600 relative"
                    title="Low Stock Alerts">
                        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>

                        {{-- Badge positioned at top-right of icon --}}
                        @if(!empty($lowStockCount) && $lowStockCount > 0)
                            <span class="absolute -top-1 -right-2 px-1.5 py-0.5 rounded-full bg-red-500 text-white text-xs font-semibold">
                                {{ $lowStockCount > 9 ? '9+' : $lowStockCount }}
                            </span>
                        @endif
                    </a>
                </div>
                @endhasanyrole

                <!-- Settings Dropdown (Profile) -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <i class="fa-solid fa-user-circle text-lg text-gray-600"></i>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
