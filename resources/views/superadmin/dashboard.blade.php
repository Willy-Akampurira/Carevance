@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">SaaS Platform Overview</h1>
            <p class="text-sm text-slate-400">Real-time statistics for all clinics and branches.</p>
        </div>
        <a href="{{ route('superadmin.tenants.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition shadow-lg shadow-indigo-600/20">
            <i class="fa-solid fa-plus"></i>
            <span>Provision New Clinic</span>
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="p-6 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-between shadow-md">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Clinics</p>
                <h3 class="text-3xl font-bold text-white mt-1">{{ $totalTenants }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-indigo-600/10 flex items-center justify-center text-indigo-400 text-xl">
                <i class="fa-solid fa-clinic-medical"></i>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="p-6 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-between shadow-md">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Active / Trials</p>
                <h3 class="text-3xl font-bold text-emerald-400 mt-1">{{ $activeTenants }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="p-6 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-between shadow-md">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Suspended Clinics</p>
                <h3 class="text-3xl font-bold text-rose-400 mt-1">{{ $suspendedTenants }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-rose-500/10 flex items-center justify-center text-rose-400 text-xl">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="p-6 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-between shadow-md">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Branches</p>
                <h3 class="text-3xl font-bold text-sky-400 mt-1">{{ $totalBranches }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-sky-500/10 flex items-center justify-center text-sky-400 text-xl">
                <i class="fa-solid fa-code-branch"></i>
            </div>
        </div>
    </div>

    <!-- Recent Tenants Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-white text-base">Recently Provisioned Clinics</h3>
            <a href="{{ route('superadmin.tenants.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">
                View All
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-900 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-3.5">Clinic Name</th>
                        <th class="px-6 py-3.5">Subdomain</th>
                        <th class="px-6 py-3.5">Plan</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Provisioned At</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm">
                    @forelse($recentTenants as $tenant)
                        <tr class="hover:bg-slate-850/40 transition">
                            <td class="px-6 py-4 font-medium text-white">{{ $tenant->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded bg-slate-800 border border-slate-700 text-xs text-slate-300 font-mono">
                                    {{ $tenant->subdomain }}.carevance.test
                                </span>
                            </td>
                            <td class="px-6 py-4 uppercase text-xs font-semibold tracking-wider text-indigo-400">{{ $tenant->plan }}</td>
                            <td class="px-6 py-4">
                                @if($tenant->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/25">
                                        Active
                                    </span>
                                @elseif($tenant->status === 'trial')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/25">
                                        Trial
                                    </span>
                                @elseif($tenant->status === 'suspended')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-rose-500/10 text-rose-400 border border-rose-500/25">
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-slate-500/10 text-slate-400 border border-slate-500/25">
                                        {{ ucfirst($tenant->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-xs">{{ $tenant->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('superadmin.tenants.show', $tenant->id) }}" 
                                   class="text-indigo-400 hover:text-indigo-300 font-medium text-xs">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                No clinics provisioned yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
