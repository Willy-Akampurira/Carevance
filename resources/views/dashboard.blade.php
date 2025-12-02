@extends('layouts.app')

@section('content')
    <div class="py-6 px-8 space-y-8 flex-1">
        {{-- Welcome Banner --}}
        <div x-data="{ showBanner: true }" x-show="showBanner" 
            class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-6 py-4 flex items-center justify-between">
            
            <span class="font-medium text-xl">
                Welcome Back, {{ Auth::user()->name ?? 'Admin User' }}
            </span>

            <button @click="showBanner = false" 
                    class="text-gray-600 hover:text-green-900 transition text-2xl" 
                    title="Dismiss">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
            <x-summary-card icon="fa-solid fa-users" label="Total Patients" :value="$metrics['totalPatients']" accent="blue" />
            <x-summary-card icon="fa-solid fa-pills" label="Total Drugs" :value="$metrics['totalDrugs']" accent="emerald" />
            <x-summary-card icon="fa-solid fa-triangle-exclamation" label="Out Of Stock" :value="$metrics['outOfStock']" accent="amber" />
            <x-summary-card icon="fa-solid fa-ban" label="Expired Drugs" :value="$metrics['expiredDrugs']" accent="red" />
            <x-summary-card icon="fa-solid fa-file-medical" label="Active Prescriptions" :value="$metrics['activePrescriptions']" accent="indigo" />
        </div>

        {{-- Charts Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Pie Chart -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-2xl font-semibold mb-4">Drug Stock Distribution</h3>
                <div id="pieChart"></div>
            </div>

            <!-- Line Chart -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-2xl font-semibold mb-4">Patients Trend</h3>
                <div id="lineChart"></div>
            </div>

            <!-- Bar Chart -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-2xl font-semibold mb-4">Prescriptions Overview</h3>
                <div id="barChart"></div>
            </div>
        </div>

        {{-- Two-panel section: Recent Activity (left) and Today's Report (right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Activity --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-2xl font-semibold mb-4">Recent Activity</h3>
                <ul class="divide-y divide-gray-200">
                    @forelse($recentActivity as $activity)
                        <li class="py-3 flex items-start justify-between">
                            <div class="space-y-1">
                                <p class="text-xl font-medium text-gray-900">
                                    Prescription: {{ optional($activity->drug)->name ?? 'Unknown drug' }}
                                    <span class="text-gray-500 text-xl">· {{ $activity->category ?? 'General' }}</span>
                                </p>
                                <p class="text-xl text-gray-500">
                                    Patient: {{ optional($activity->patient)->name ?? 'Unknown patient' }}
                                    · {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xl rounded-full
                                {{ $activity->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($activity->status) }}
                            </span>
                        </li>
                    @empty
                        <li class="py-3 text-xl text-gray-500">No recent activity found.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Today's Report --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-2xl font-semibold mb-4">Today's Report</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-lg border border-gray-100 p-4">
                        <p class="text-xl text-gray-500">New Patients</p>
                        <p class="text-3xl font-semibold text-gray-900">{{ $todaysReport['patientsToday'] }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-100 p-4">
                        <p class="text-xl text-gray-500">New Prescriptions</p>
                        <p class="text-3xl font-semibold text-gray-900">{{ $todaysReport['prescriptionsToday'] }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-100 p-4">
                        <p class="text-xl text-gray-500">Active Prescriptions (today)</p>
                        <p class="text-3xl font-semibold text-gray-900">{{ $todaysReport['activePrescriptionsToday'] }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-100 p-4">
                        <p class="text-xl text-gray-500">Drugs Expiring Today</p>
                        <p class="text-3xl font-semibold text-gray-900">{{ $todaysReport['drugsExpiredToday'] }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-100 p-4 sm:col-span-2">
                        <p class="text-xl text-gray-500">Out Of Stock (snapshot)</p>
                        <p class="text-3xl font-semibold text-gray-900">{{ $todaysReport['outOfStockNow'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Inject PHP data into JS --}}
        <script>
            const patientsTrend = @json($patientsTrend);
            const drugStock = @json($drugStock);
            const prescriptionsData = @json($prescriptionsData);
        </script>
    </div>
@endsection
