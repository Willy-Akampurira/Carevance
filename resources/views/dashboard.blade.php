@extends('layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-8 space-y-8 flex-1 overflow-x-hidden">
    {{-- Welcome Banner --}}
    <div x-data="{ showBanner: true }" x-show="showBanner" 
        class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-6 py-4 flex items-center justify-between">
        <span class="font-medium text-lg sm:text-xl">
            Welcome Back, {{ Auth::user()->name ?? 'Admin User' }}
        </span>
        <button @click="showBanner = false" 
                class="text-gray-600 hover:text-green-900 transition text-2xl" 
                title="Dismiss">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Summary Cards --}}
    @php
        $cards = [];

        if(auth()->user()->hasAnyRole(['admin','doctor','staff'])) {
            $cards[] = [
                'icon' => 'fa-solid fa-users',
                'label' => 'Total Patients',
                'value' => $metrics['totalPatients'],
                'accent' => 'blue'
            ];
        }

        if(auth()->user()->hasAnyRole(['admin','pharmacist'])) {
            $cards[] = [
                'icon' => 'fa-solid fa-pills',
                'label' => 'Total Drugs',
                'value' => $metrics['totalDrugs'],
                'accent' => 'emerald'
            ];
            $cards[] = [
                'icon' => 'fa-solid fa-triangle-exclamation',
                'label' => 'Out Of Stock',
                'value' => $metrics['outOfStock'],
                'accent' => 'amber'
            ];
            $cards[] = [
                'icon' => 'fa-solid fa-ban',
                'label' => 'Expired Drugs',
                'value' => $metrics['expiredDrugs'],
                'accent' => 'red'
            ];
        }

        if(auth()->user()->hasAnyRole(['admin','doctor','staff'])) {
            $cards[] = [
                'icon' => 'fa-solid fa-file-medical',
                'label' => 'Active Prescriptions',
                'value' => $metrics['activePrescriptions'],
                'accent' => 'indigo'
            ];
        }

        $count = count($cards);
    @endphp

    <div class="
        grid gap-4 sm:gap-6
        @if($count === 1) justify-center grid-cols-1 @endif
        @if($count === 2) grid-cols-2 @endif
        @if($count === 3) grid-cols-1 sm:grid-cols-3 @endif
        @if($count >= 4) grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 @endif
    ">
        @foreach($cards as $card)
            <x-summary-card 
                :icon="$card['icon']" 
                :label="$card['label']" 
                :value="$card['value']" 
                :accent="$card['accent']" 
            />
        @endforeach
    </div>

    {{-- Charts Grid --}}
    @php
        $charts = [];

        // Pie chart → admin, pharmacist
        if(auth()->user()->hasAnyRole(['admin','pharmacist'])) {
            $charts[] = [
                'title' => 'Drug Stock Distribution',
                'id' => 'pieChart'
            ];
        }

        // Line chart → admin, doctor, staff
        if(auth()->user()->hasAnyRole(['admin','doctor','staff'])) {
            $charts[] = [
                'title' => 'Patients Trend',
                'id' => 'lineChart'
            ];
        }

        // Bar chart → admin, doctor, staff
        if(auth()->user()->hasAnyRole(['admin','doctor','staff'])) {
            $charts[] = [
                'title' => 'Prescriptions Overview',
                'id' => 'barChart'
            ];
        }

        $chartCount = count($charts);
    @endphp

    <div class="
        grid gap-6
        @if($chartCount === 1) grid-cols-1 @endif
        @if($chartCount === 2) grid-cols-1 md:grid-cols-2 @endif
        @if($chartCount === 3) grid-cols-1 md:grid-cols-2 xl:grid-cols-3 @endif
    ">
        @foreach($charts as $chart)
            <div class="bg-white shadow rounded-lg p-4 sm:p-6 w-full flex flex-col justify-between min-h-[360px] sm:min-h-[420px]">
                <h3 class="text-lg sm:text-xl font-semibold mb-4 text-center text-gray-800">{{ $chart['title'] }}</h3>
                <div class="flex-1 w-full relative flex items-center justify-center">
                    <div id="{{ $chart['id'] }}" class="w-full h-full absolute inset-0"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Two-panel section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Activity --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-xl sm:text-2xl font-semibold mb-4">Recent Activity</h3>
            <ul class="divide-y divide-gray-200">

                {{-- Admin sees everything --}}
                @role('admin')
                    @forelse($recentActivity as $activity)
                        @include('partials.activity-item', ['activity' => $activity])
                    @empty
                        <li class="py-3 text-lg text-gray-500">No recent activity found.</li>
                    @endforelse
                @endrole

                {{-- Doctor sees Active + prescription-related statuses --}}
                @role('doctor')
                    @forelse($recentActivity->whereIn('status', ['Active','Dispensed','Missed','Completed','Renewal Requested']) as $activity)
                        @include('partials.activity-item', ['activity' => $activity])
                    @empty
                        <li class="py-3 text-lg text-gray-500">No prescription activity found.</li>
                    @endforelse
                @endrole

                {{-- Pharmacist sees Active + dispensing/stock statuses --}}
                @role('pharmacist')
                    @forelse($recentActivity->whereIn('status', ['Active','Dispensed','Deleted']) as $activity)
                        @include('partials.activity-item', ['activity' => $activity])
                    @empty
                        <li class="py-3 text-lg text-gray-500">No dispensing activity found.</li>
                    @endforelse
                @endrole

                {{-- Staff sees Active + patient-facing statuses --}}
                @role('staff')
                    @forelse($recentActivity->whereIn('status', ['Active','Missed','Completed','Renewal Requested']) as $activity)
                        @include('partials.activity-item', ['activity' => $activity])
                    @empty
                        <li class="py-3 text-lg text-gray-500">No recent activity found.</li>
                    @endforelse
                @endrole

            </ul>
        </div>

        {{-- Today's Report --}}
        @php
            $reports = [];

            if(auth()->user()->hasAnyRole(['admin','doctor','staff'])) {
                $reports[] = [
                    'label' => 'New Patients',
                    'value' => $todaysReport['patientsToday']
                ];
            }

            if(auth()->user()->hasAnyRole(['admin','doctor','staff'])) {
                $reports[] = [
                    'label' => 'New Prescriptions',
                    'value' => $todaysReport['prescriptionsToday']
                ];
            }

            if(auth()->user()->hasAnyRole(['admin','doctor','staff'])) {
                $reports[] = [
                    'label' => 'Active Prescriptions',
                    'value' => $todaysReport['activePrescriptionsToday']
                ];
            }

            if(auth()->user()->hasAnyRole(['admin','pharmacist'])) {
                $reports[] = [
                    'label' => 'Drugs Expiring Today',
                    'value' => $todaysReport['drugsExpiredToday']
                ];
            }

            if(auth()->user()->hasAnyRole(['admin','pharmacist'])) {
                $reports[] = [
                    'label' => 'Out Of Stock',
                    'value' => $todaysReport['outOfStockNow']
                ];
            }

            $reportCount = count($reports);
        @endphp

        <div class="bg-white shadow rounded-lg p-6 flex flex-col justify-between">
            <h3 class="text-xl sm:text-2xl font-semibold mb-4">Today's Report</h3>

            <div class="
                grid gap-4
                @if($reportCount === 1) justify-center grid-cols-1 @endif
                @if($reportCount === 2) grid-cols-2 @endif
                @if($reportCount === 3) grid-cols-1 sm:grid-cols-3 @endif
                @if($reportCount >= 4) grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 @endif
            ">
                @foreach($reports as $report)
                    <div class="rounded-lg border border-gray-100 p-4 bg-gray-50">
                        <p class="text-sm text-gray-500 font-medium truncate">{{ $report['label'] }}</p>
                        <p class="text-2xl sm:text-3xl font-semibold text-gray-900 mt-1">{{ $report['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const patientsTrend = @json($patientsTrend);
            const drugStock = @json($drugStock);
            const prescriptionsData = @json($prescriptionsData);

            // Universal Responsive Chart Context Configurations
            const sharedOptions = {
                chart: {
                    width: '100%',
                    height: '100%',
                    parentHeightOffset: 0,
                    toolbar: { show: false }
                },
                legend: { position: 'bottom' }
            };

            // Pie Chart
            if(document.querySelector("#pieChart")) {
                new ApexCharts(document.querySelector("#pieChart"), Object.assign({}, sharedOptions, {
                    chart: { type: 'pie', width: '100%', height: '100%' },
                    series: [drugStock.inStock, drugStock.outOfStock, drugStock.expired, drugStock.reserved],
                    labels: ['In Stock', 'Out Of Stock', 'Expired', 'Reserved'],
                    colors: ['#10b981', '#f59e0b', '#ef4444', '#6366f1']
                })).render();
            }

            // Line Chart
            if(document.querySelector("#lineChart")) {
                new ApexCharts(document.querySelector("#lineChart"), Object.assign({}, sharedOptions, {
                    chart: { type: 'line', width: '100%', height: '100%' },
                    series: [{
                        name: 'Patients',
                        data: Object.values(patientsTrend)
                    }],
                    xaxis: {
                        categories: Object.keys(patientsTrend).map(m => {
                            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                            return months[m-1] || m;
                        })
                    },
                    colors: ['#3b82f6']
                })).render();
            }

            // Bar Chart
            if(document.querySelector("#barChart")) {
                new ApexCharts(document.querySelector("#barChart"), Object.assign({}, sharedOptions, {
                    chart: { type: 'bar', width: '100%', height: '100%' },
                    series: [{
                        name: 'Prescriptions',
                        data: Object.values(prescriptionsData)
                    }],
                    xaxis: {
                        categories: Object.keys(prescriptionsData)
                    },
                    colors: ['#6366f1']
                })).render();
            }
        });
    </script>
</div>
@endsection