<aside x-bind:class="sidebarCollapsed ? 'w-20' : 'w-72'"
       class="fixed top-0 left-0 h-screen bg-gray-900 text-white shadow-lg z-50 transition-all duration-300 ease-in-out flex flex-col overflow-auto">

   <!-- Logo Section -->
    <div class="p-4 flex items-center justify-center">
        <img src="{{ asset('storage/' . \App\Models\Setting::getValue('clinic_logo', 'logo.png')) }}"
            alt="{{ \App\Models\Setting::getValue('clinic_name', 'Supreme Clinic') }} Logo"
            class="h-32 transition-all duration-300"
            x-bind:class="sidebarCollapsed ? 'mx-auto' : 'ml-2'">
    </div>

    <!-- Navigation Links -->
    <nav class="mt-6 space-y-2 text-base font-medium flex-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-house text-lg"></i>
            <span x-show="!sidebarCollapsed" class="text-xl font-medium">Dashboard</span>
        </a>

        <!-- Patients Sidebar Dropdown -->
         @hasanyrole('admin|doctor|staff')
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-md transition
                        text-gray-700 dark:text-gray-200
                        hover:bg-green-100 hover:text-green-700
                        dark:hover:bg-green-900 dark:hover:text-green-200">
                <i class="fa-solid fa-user-injured text-lg"></i>
                <span x-show="!sidebarCollapsed" class="text-xl font-medium flex-1 text-left">
                    Patients
                </span>
                <i x-show="!sidebarCollapsed" 
                   :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" 
                   class="ml-auto text-sm"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 ml-10 space-y-1">

                {{-- Register Patient → admin, doctor, staff --}}
                @hasanyrole('admin|doctor|staff')
                    <a href="{{ route('patients.create') }}" 
                    class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                        Register Patient
                    </a>
                @endhasanyrole

                {{-- Patient List → admin, doctor, staff --}}
                @hasanyrole('admin|doctor|staff')
                    <a href="{{ route('patients.index') }}" 
                    class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                        Patient List
                    </a>
                @endhasanyrole

                {{-- Reports → admin, doctor, staff --}}
                @hasanyrole('admin|doctor|staff')
                    <a href="{{ route('patients.reports') }}" 
                    class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                        Reports
                    </a>
                @endhasanyrole

            </div>

        </div>
        @endhasanyrole

        {{-- Appointments → admin, doctor, staff only --}}
        @hasanyrole('admin|doctor|staff')
        <a href="{{ route('patients.appointments') }}" 
        class="flex items-center gap-3 px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-calendar-check text-lg"></i>
            <span x-show="!sidebarCollapsed" class="text-xl font-medium">Appointments</span>
        </a>
        @endhasanyrole

        {{-- Prescriptions → admin, doctor only --}}
        @hasanyrole('admin|doctor')
        <a href="{{ route('patients.prescriptions') }}" 
        class="flex items-center gap-3 px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-prescription-bottle-medical text-lg"></i>
            <span x-show="!sidebarCollapsed" class="text-xl font-medium">Prescriptions</span>
        </a>
        @endhasanyrole

        {{-- Medical Records → admin, doctor only --}}
        @hasanyrole('admin|doctor')
        <a href="{{ route('patients.records') }}" 
        class="flex items-center gap-3 px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-file-medical text-lg"></i>
            <span x-show="!sidebarCollapsed" class="text-xl font-medium">Medical Records</span>
        </a>
        @endhasanyrole

        {{-- Billing & Insurance → admin, staff only --}}
        @hasanyrole('admin|staff')
        <a href="{{ route('patients.billing') }}" 
        class="flex items-center gap-3 px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
            <span x-show="!sidebarCollapsed" class="text-xl font-medium">Billing & Insurance</span>
        </a>
        @endhasanyrole

        <!-- Supplier Sidebar Dropdown -->
         @hasanyrole('admin|pharmacist')
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-md transition
                        text-gray-700 dark:text-gray-200
                        hover:bg-green-100 hover:text-green-700
                        dark:hover:bg-green-900 dark:hover:text-green-200">
                <i class="fa-solid fa-truck text-lg"></i>
                <span x-show="!sidebarCollapsed" class="text-xl font-medium flex-1 text-left">
                    Suppliers
                </span>
                <i x-show="!sidebarCollapsed" 
                   :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" 
                   class="ml-auto text-sm"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 ml-10 space-y-1">
                <a href="{{ route('suppliers.create') }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Add Supplier</a>
                <a href="{{ route('suppliers.index') }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Supplier List</a>
                <a href="{{ route('suppliers.po.create', 1) }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Purchase Orders</a>
                <a href="{{ route('suppliers.deliveries.index', 1) }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Deliveries</a>
                <a href="{{ route('suppliers.invoices.index', 1) }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Invoices</a>
                <a href="{{ route('suppliers.invoices.payments.index', [1,1]) }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Payments</a>
            </div>
        </div>
        @endhasanyrole

        <!-- Drugs Sidebar Dropdown -->
         @hasanyrole('admin|pharmacist')
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-md transition
                        text-gray-700 dark:text-gray-200
                        hover:bg-green-100 hover:text-green-700
                        dark:hover:bg-green-900 dark:hover:text-green-200">
                <i class="fa-solid fa-pills text-lg"></i>
                <span x-show="!sidebarCollapsed" class="text-xl font-medium flex-1 text-left">
                    Drugs
                </span>
                <i x-show="!sidebarCollapsed" 
                :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" 
                class="ml-auto text-sm"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 ml-10 space-y-1">
                <a href="{{ route('drugs.categories.index') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Categories
                </a>
                <a href="{{ route('drugs.create') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Add Drug
                </a>
                <a href="{{ route('drugs.index') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Drugs List
                </a>
            </div>
        </div>
        @endhasanyrole

        @hasanyrole('admin|pharmacist')
        <!-- Stock Management Sidebar Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-md transition
                        text-gray-700 dark:text-gray-200
                        hover:bg-green-100 hover:text-green-700
                        dark:hover:bg-green-900 dark:hover:text-green-200">
                <i class="fa-solid fa-boxes-stacked text-lg"></i>
                <span x-show="!sidebarCollapsed" class="text-xl font-medium flex-1 text-left">
                    Stock Management
                </span>
                <i x-show="!sidebarCollapsed" 
                :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" 
                class="ml-auto text-sm"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 ml-10 space-y-1">
                <!-- New Stock -->
                <a href="{{ route('stock.new') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                New Stock
                </a>

                <!-- Old Stock -->
                <a href="{{ route('stock.old') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Old Stock
                </a>

                <!-- Stock Adjustment -->
                <a href="{{ route('stock.adjustment') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Stock Adjustment
                </a>
            </div>
        </div>
        @endhasanyrole

         

        <!-- Expiry Management Sidebar Dropdown -->
         @hasanyrole('admin|pharmacist')
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-md transition
                        text-gray-700 dark:text-gray-200
                        hover:bg-green-100 hover:text-green-700
                        dark:hover:bg-green-900 dark:hover:text-green-200">
                <i class="fa-solid fa-calendar-xmark text-lg"></i>
                <span x-show="!sidebarCollapsed" class="text-xl font-medium flex-1 text-left">
                    Expiry Management
                </span>
                <i x-show="!sidebarCollapsed" 
                :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" 
                class="ml-auto text-sm"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 ml-10 space-y-1">
                <!-- Set Expiry Threshold -->
                <a href="{{ route('expiry.threshold') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Set Expiry Threshold
                </a>

                <!-- Nearing Expiry Drugs -->
                <a href="{{ route('expiry.nearing') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Nearing Expiry Drugs
                </a>

                <!-- Expired Drugs -->
                <a href="{{ route('expiry.expired') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Expired Drugs
                </a>

                <!-- Expiry Notifications -->
                <a href="{{ route('expiry.notifications') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Expiry Notifications
                </a>
            </div>
        </div>
        @endhasanyrole

        <!-- Staff Module Sidebar Dropdown -->
         @hasanyrole('admin')
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-md transition
                        text-gray-700 dark:text-gray-200
                        hover:bg-green-100 hover:text-green-700
                        dark:hover:bg-green-900 dark:hover:text-green-200">
                <i class="fa-solid fa-users text-lg"></i>
                <span x-show="!sidebarCollapsed" class="text-xl font-medium flex-1 text-left">
                    Staff
                </span>
                <i x-show="!sidebarCollapsed" 
                :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" 
                class="ml-auto text-sm"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 ml-10 space-y-1">
                <!-- Staff Management -->
                <a href="{{ route('staff.index') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Staff Management
                </a>

                <!-- Departments -->
                <a href="{{ route('staff.departments.index') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Departments
                </a>

                <!-- Activity Logs -->
                <a href="{{ route('staff.logs') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Activity Logs
                </a>

                <!-- Shift & Attendance -->
                <a href="{{ route('staff.attendance.index') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Shift & Attendance
                </a>

                <!-- Performance Reports -->
                <a href="{{ route('staff.reports.index') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Performance Reports
                </a>
            </div>
        </div>
        @endhasanyrole

        @hasanyrole('admin')
        <!-- Users Module Sidebar Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-md transition
                        text-gray-700 dark:text-gray-200
                        hover:bg-green-100 hover:text-green-700
                        dark:hover:bg-green-900 dark:hover:text-green-200">
                <i class="fa-solid fa-user-gear text-lg"></i>
                <span x-show="!sidebarCollapsed" class="text-xl font-medium flex-1 text-left">
                    Users Management
                </span>
                <i x-show="!sidebarCollapsed" 
                :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" 
                class="ml-auto text-sm"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 ml-10 space-y-1">
                <!-- Add User -->
                <a href="{{ route('users.create') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Add User
                </a>

                <!-- Users List -->
                <a href="{{ route('users.index') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                Users List
                </a>
            </div>
        </div>
        @endhasanyrole

        @hasanyrole('admin')
        <!-- Settings Module Sidebar Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-md transition
                        text-gray-700 dark:text-gray-200
                        hover:bg-green-100 hover:text-green-700
                        dark:hover:bg-green-900 dark:hover:text-green-200">
                <i class="fa-solid fa-gear text-lg"></i>
                <span x-show="!sidebarCollapsed" class="text-xl font-medium flex-1 text-left">
                    Settings
                </span>
                <i x-show="!sidebarCollapsed" 
                :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" 
                class="ml-auto text-sm"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 ml-10 space-y-1">
                <!-- Clinic Information -->
                <a href="{{ route('settings.clinic') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                    Clinic Information
                </a>

                <!-- Invoice Settings -->
                <a href="{{ route('settings.invoice') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                    Invoice Settings
                </a>

                <!-- Theme Settings -->
                <a href="{{ route('settings.theme') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                    Theme Settings
                </a>

                <!-- Footer Settings -->
                <a href="{{ route('settings.footer') }}" 
                class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">
                    Footer Settings
                </a>
            </div>
        </div>
        @endhasanyrole

        {{-- Backup & Restore → admin only --}}
        @hasanyrole('admin')
        <a href="{{ route('settings.backup.page') }}" 
        class="flex items-center gap-3 px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-database text-lg"></i>
            <span x-show="!sidebarCollapsed" class="text-xl font-medium">Backup & Restore</span>
        </a>
        @endhasanyrole

    </nav>

    <!-- Profile Section -->
    <div class="p-4 border-t border-gray-700">
        <div class="flex items-center gap-3">
            <i class="fas fa-user-circle text-2xl"></i>
            <span x-show="!sidebarCollapsed" class="text-xl">{{ Auth::user()->name ?? 'Guest' }}</span>
        </div>
    </div>
</aside>
