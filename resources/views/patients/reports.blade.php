@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Reports & Analytics
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-8">

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Patient Growth (Monthly Registrations)</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[500px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Month</th>
                        <th class="px-4 py-3">Registrations</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($patientGrowth as $growth)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ DateTime::createFromFormat('!m', $growth->month)->format('F') }}
                            </td>
                            <td class="px-4 py-3">{{ $growth->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-6 text-center text-gray-500 font-light">No growth data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Age Distribution</h3>
        <ul class="list-disc pl-6 text-sm sm:text-base text-gray-600 space-y-1">
            @foreach($ageDistribution as $range => $count)
                <li>{{ $range }} years: <span class="font-semibold text-gray-900">{{ $count }}</span></li>
            @endforeach
        </ul>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Disease Categories</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[500px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Diagnosis</th>
                        <th class="px-4 py-3">Cases</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    @forelse($diseaseCategories as $category)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $category->diagnosis }}</td>
                            <td class="px-4 py-3">{{ $category->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-6 text-center text-gray-500 font-light">No disease data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Billing Reports</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[500px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Count</th>
                        <th class="px-4 py-3">Total Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-red-600">Unpaid</td>
                        <td class="px-4 py-3">{{ $billingStats['unpaid_count'] }}</td>
                        <td class="px-4 py-3 font-mono text-gray-900">UGX {{ number_format($billingStats['unpaid_total'], 2) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-green-600">Paid</td>
                        <td class="px-4 py-3">{{ $billingStats['paid_count'] }}</td>
                        <td class="px-4 py-3 font-mono text-gray-900">UGX {{ number_format($billingStats['paid_total'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 pb-1 border-b border-gray-100">Insurance Claims</h3>
        <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
            <table class="table-auto w-full min-w-[500px] divide-y divide-gray-200 text-left">
                <thead class="bg-gray-100">
                    <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-600">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-blue-600">Submitted</td>
                        <td class="px-4 py-3">{{ $claimStats['submitted'] }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-green-600">Approved</td>
                        <td class="px-4 py-3">{{ $claimStats['approved'] }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-red-600">Denied</td>
                        <td class="px-4 py-3">{{ $claimStats['denied'] }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-yellow-600">Pending</td>
                        <td class="px-4 py-3">{{ $claimStats['pending'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection