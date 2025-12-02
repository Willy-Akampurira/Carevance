<aside x-bind:class="sidebarCollapsed ? 'w-20' : 'w-72'"
       class="fixed top-0 left-0 h-screen bg-gray-900 text-white shadow-lg z-50 transition-all duration-300 ease-in-out flex flex-col">

    <!-- Logo Section -->
    <div class="p-4 flex items-center justify-center">
        <img src="{{ asset('images/logo.png') }}"
             alt="Carevance"
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
                <a href="{{ route('patients.create') }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Register Patient</a>
                <a href="{{ route('patients.index') }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Patient List</a>
                <a href="{{ route('patients.appointments') }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Appointments</a>
                <a href="{{ route('patients.prescriptions') }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Prescriptions</a>
                <a href="{{ route('patients.records') }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Medical Records</a>
                <a href="{{ route('patients.billing') }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Billing & Insurance</a>
                <a href="{{ route('patients.reports') }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Reports</a>
            </div>
        </div>

        <!-- Supplier Sidebar Dropdown -->
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
                <a href="{{ route('purchaseOrders.receive', 1) }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Deliveries</a>
                <a href="{{ route('suppliers.invoices.store', 1) }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Invoices</a>
                <a href="{{ route('supplierInvoices.pay', 1) }}" class="block px-4 py-2 text-lg rounded-md text-gray-600 hover:bg-green-50">Payments</a>
            </div>
        </div>

        <!-- Other Modules -->
        <a href="{{ route('drugs.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-pills text-lg"></i>
            <span x-show="!sidebarCollapsed" class="text-xl font-medium">Drugs</span>
        </a>
        <a href="{{ route('prescriptions.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-file-medical text-lg"></i>
            <span x-show="!sidebarCollapsed" class="text-xl font-medium">Prescriptions</span>
        </a>
        <a href="{{ route('staff.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-user-md text-lg"></i>
            <span x-show="!sidebarCollapsed" class="text-xl font-medium">Staff</span>
        </a>
        <a href="{{ route('roles.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-user-shield text-lg"></i>
            <span x-show="!sidebarCollapsed" class="text-xl font-medium">Roles & Permissions</span>
        </a>
    </nav>

    <!-- Profile Section -->
    <div class="p-4 border-t border-gray-700">
        <div class="flex items-center gap-3">
            <i class="fas fa-user-circle text-2xl"></i>
            <span x-show="!sidebarCollapsed" class="text-xl">{{ Auth::user()->name ?? 'Guest' }}</span>
        </div>
    </div>
</aside>
