@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Manage Clinics (Tenants)</h1>
            <p class="text-sm text-slate-400">View, monitor, and provision all clinics in the system.</p>
        </div>
        <a href="{{ route('superadmin.tenants.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition shadow-lg shadow-indigo-600/20">
            <i class="fa-solid fa-plus"></i>
            <span>Provision New Clinic</span>
        </a>
    </div>

    <!-- Tenants Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-900 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Clinic Name</th>
                        <th class="px-6 py-4">Domain (Subdomain)</th>
                        <th class="px-6 py-4">Admin Email</th>
                        <th class="px-6 py-4 text-center">Branches</th>
                        <th class="px-6 py-4 text-center">Users</th>
                        <th class="px-6 py-4">Plan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm">
                    @forelse($tenants as $tenant)
                        <tr class="hover:bg-slate-850/40 transition">
                            <td class="px-6 py-4 font-medium text-white">{{ $tenant->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded bg-slate-800 border border-slate-700 text-xs text-slate-300 font-mono">
                                    {{ $tenant->subdomain }}.carevance.test
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-300">{{ $tenant->email }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-slate-300">{{ $tenant->branches_count }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-slate-300">{{ $tenant->users_count }}</td>
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
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('superadmin.tenants.show', $tenant->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded text-xs font-medium border border-slate-700 transition">
                                    <i class="fa-solid fa-cog"></i>
                                    <span>Manage</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                No clinics provisioned yet. Click "Provision New Clinic" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
