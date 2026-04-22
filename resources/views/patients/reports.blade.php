@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Reports & Analytics
    </h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Patient Growth -->
    <h3 class="text-2xl font-bold mb-6">Patient Growth (Monthly Registrations)</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-8">
        <thead class="bg-gray-100">
            <tr class="text-xl">
                <th class="px-4 py-2">Month</th>
                <th class="px-4 py-2">Registrations</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patientGrowth as $growth)
                <tr class="border-t text-lg">
                    <td class="px-4 py-2">
                        {{ DateTime::createFromFormat('!m', $growth->month)->format('F') }}
                    </td>
                    <td class="px-4 py-2">{{ $growth->total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">No growth data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Age Distribution -->
    <h3 class="text-2xl font-bold mb-6">Age Distribution</h3>
    <ul class="list-disc pl-6 text-xl mb-8">
        @foreach($ageDistribution as $range => $count)
            <li>{{ $range }} years: <span class="font-semibold">{{ $count }}</span></li>
        @endforeach
    </ul>

    <!-- Disease Categories -->
    <h3 class="text-2xl font-bold mb-6">Disease Categories</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-8">
        <thead class="bg-gray-100">
            <tr class="text-xl">
                <th class="px-4 py-2">Diagnosis</th>
                <th class="px-4 py-2">Cases</th>
            </tr>
        </thead>
        <tbody>
            @forelse($diseaseCategories as $category)
                <tr class="border-t text-lg">
                    <td class="px-4 py-2">{{ $category->diagnosis }}</td>
                    <td class="px-4 py-2">{{ $category->total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="px-4 py-4 text-center text-xl text-gray-500">No disease data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Billing Reports -->
    <h3 class="text-2xl font-bold mb-6">Billing Reports</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg mb-8">
        <thead class="bg-gray-100">
            <tr class="text-xl">
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Count</th>
                <th class="px-4 py-2">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-t text-lg">
                <td class="px-4 py-2">Unpaid</td>
                <td class="px-4 py-2">{{ $billingStats['unpaid_count'] }}</td>
                <td class="px-4 py-2">UGX {{ number_format($billingStats['unpaid_total'], 2) }}</td>
            </tr>
            <tr class="border-t text-lg">
                <td class="px-4 py-2">Paid</td>
                <td class="px-4 py-2">{{ $billingStats['paid_count'] }}</td>
                <td class="px-4 py-2">UGX {{ number_format($billingStats['paid_total'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Insurance Claims -->
    <h3 class="text-2xl font-bold mb-6">Insurance Claims</h3>
    <table class="table-auto w-full border text-left border-gray-200 rounded-lg">
        <thead class="bg-gray-100">
            <tr class="text-xl">
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Count</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-t text-lg">
                <td class="px-4 py-2">Submitted</td>
                <td class="px-4 py-2">{{ $claimStats['submitted'] }}</td>
            </tr>
            <tr class="border-t text-lg">
                <td class="px-4 py-2">Approved</td>
                <td class="px-4 py-2">{{ $claimStats['approved'] }}</td>
            </tr>
            <tr class="border-t text-lg">
                <td class="px-4 py-2">Denied</td>
                <td class="px-4 py-2">{{ $claimStats['denied'] }}</td>
            </tr>
            <tr class="border-t text-lg">
                <td class="px-4 py-2">Pending</td>
                <td class="px-4 py-2">{{ $claimStats['pending'] }}</td>
            </tr>
        </tbody>
    </table>

</div>
@endsection
