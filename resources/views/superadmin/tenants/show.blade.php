@extends('layouts.superadmin')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('superadmin.tenants.index') }}" class="text-slate-400 hover:text-white transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">{{ $tenant->name }}</h1>
            <p class="text-sm text-slate-400">Manage clinic status, view branches, and user configuration.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar: Info & Status Toggle -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Clinic Info -->
            <div class="p-6 bg-slate-900 border border-slate-800 rounded-xl space-y-4 shadow-md text-sm">
                <h3 class="font-bold text-white text-base border-b border-slate-800 pb-2">Clinic Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase">Subdomain URL</span>
                        <a href="http://{{ $tenant->subdomain }}.carevance.test" target="_blank"
                           class="text-indigo-400 hover:text-indigo-300 font-mono text-xs hover:underline flex items-center gap-1.5 mt-0.5">
                            <span>{{ $tenant->subdomain }}.carevance.test</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase">Contact Email</span>
                        <span class="text-slate-200">{{ $tenant->email }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase">Subscription Plan</span>
                        <span class="inline-flex px-2 py-0.5 rounded bg-indigo-650/40 text-indigo-300 font-semibold uppercase text-[11px] tracking-wider mt-0.5">
                            {{ $tenant->plan }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase">Trial Expiry</span>
                        <span class="text-slate-300">{{ $tenant->trial_ends_at ? $tenant->trial_ends_at->format('M d, Y') : 'N/A' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase">Current Status</span>
                        @if($tenant->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                Active
                            </span>
                        @elseif($tenant->status === 'trial')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/25 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                Trial
                            </span>
                        @elseif($tenant->status === 'suspended')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-500/10 text-rose-400 border border-rose-500/25 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                Suspended
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-500/10 text-slate-400 border border-slate-500/25 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                {{ ucfirst($tenant->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status Manager -->
            <div class="p-6 bg-slate-900 border border-slate-800 rounded-xl space-y-4 shadow-md">
                <h3 class="font-bold text-white text-base border-b border-slate-800 pb-2">Change Subscription Status</h3>
                
                <form method="POST" action="{{ route('superadmin.tenants.toggle-status', $tenant->id) }}" class="space-y-4">
                    @csrf
                    @method('POST')

                    <div class="space-y-1.5">
                        <label for="status" class="block text-xs font-semibold text-slate-300">Select Status</label>
                        <select name="status" id="status" required
                                class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-slate-100 focus:outline-none focus:border-indigo-500 transition text-sm">
                            <option value="trial" {{ $tenant->status == 'trial' ? 'selected' : '' }}>Trial (Free Trial)</option>
                            <option value="active" {{ $tenant->status == 'active' ? 'selected' : '' }}>Active (Paid Active)</option>
                            <option value="suspended" {{ $tenant->status == 'suspended' ? 'selected' : '' }}>Suspended (Hold)</option>
                            <option value="cancelled" {{ $tenant->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Inactive)</option>
                        </select>
                    </div>

                    <button type="submit" 
                            class="w-full py-2 bg-indigo-650 hover:bg-indigo-600 text-white rounded-lg text-xs font-semibold tracking-wide transition shadow shadow-indigo-600/10">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Panel: Branches and Users -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Branches List -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-md overflow-hidden text-sm">
                <div class="px-6 py-4 border-b border-slate-800">
                    <h3 class="font-bold text-white text-base">Registered Branches (Locations)</h3>
                </div>
                <div class="divide-y divide-slate-800/60">
                    @forelse($branches as $branch)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-850/20 transition">
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-white">{{ $branch->name }}</span>
                                    @if($branch->is_primary)
                                        <span class="px-1.5 py-0.5 rounded bg-indigo-500/10 border border-indigo-500/25 text-[10px] text-indigo-400 font-semibold uppercase tracking-wider">
                                            Primary
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 flex flex-wrap gap-x-4">
                                    <span><i class="fa-solid fa-phone text-slate-650 mr-1 text-[11px]"></i> {{ $branch->phone ?? 'No phone' }}</span>
                                    <span><i class="fa-solid fa-map-marker-alt text-slate-655 mr-1 text-[11px]"></i> {{ $branch->address ?? 'No address' }}</span>
                                </div>
                            </div>
                            <div>
                                @if($branch->status === 'active')
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 font-medium">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-slate-500/10 border border-slate-500/25 text-slate-450 font-medium">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-500">
                            No branches created for this clinic.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Users List -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-md overflow-hidden text-sm">
                <div class="px-6 py-4 border-b border-slate-800">
                    <h3 class="font-bold text-white text-base">Clinic Users (Staff / Admins)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-900/60 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                <th class="px-6 py-3">User Name</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Roles</th>
                                <th class="px-6 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-850/20 transition">
                                    <td class="px-6 py-3.5 font-medium text-white">{{ $user->name }}</td>
                                    <td class="px-6 py-3.5 text-slate-300 font-mono text-xs">{{ $user->email }}</td>
                                    <td class="px-6 py-3.5">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($user->roles as $role)
                                                <span class="px-2 py-0.5 rounded bg-slate-800 border border-slate-700 text-[10px] uppercase font-semibold text-indigo-400">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5 text-center">
                                        @if($user->is_active)
                                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500" title="Active"></span>
                                        @else
                                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-slate-600" title="Inactive"></span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-slate-500">
                                        No users registered under this clinic yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
