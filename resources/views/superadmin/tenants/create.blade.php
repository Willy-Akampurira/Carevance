@extends('layouts.superadmin')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex items-center gap-3">
        <a href="{{ route('superadmin.tenants.index') }}" class="text-slate-400 hover:text-white transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Provision New Clinic</h1>
            <p class="text-sm text-slate-400">Register a new client clinic workspace on the SaaS platform.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-950/40 border border-rose-800 text-rose-400 rounded-lg shadow-md space-y-1">
            <div class="flex items-center gap-2 font-semibold text-sm">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('superadmin.tenants.store') }}" class="space-y-6">
        @csrf

        <!-- Section 1: Clinic Settings -->
        <div class="p-6 bg-slate-900 border border-slate-800 rounded-xl space-y-4 shadow-md">
            <h3 class="font-bold text-white text-base border-b border-slate-800 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-hospital text-indigo-400"></i>
                <span>1. Clinic Workspace Information</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Clinic Name -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-semibold text-slate-300">Clinic Name</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                           class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition"
                           placeholder="e.g. City Health Clinic">
                </div>

                <!-- Subdomain -->
                <div class="space-y-1.5">
                    <label for="subdomain" class="block text-xs font-semibold text-slate-300">Subdomain Prefix</label>
                    <div class="flex">
                        <input type="text" name="subdomain" id="subdomain" required value="{{ old('subdomain') }}"
                               class="w-full bg-slate-950 border border-slate-800 rounded-l-lg px-3 py-2 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition font-mono text-sm"
                               placeholder="cityhealth">
                        <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 border-slate-800 bg-slate-800 text-slate-400 text-xs font-mono">
                            .carevance.test
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500">Only letters, numbers, and dashes. Must be unique.</p>
                </div>

                <!-- Primary Email -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-slate-300">Primary Contact Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                           class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition"
                           placeholder="billing@cityhealth.com">
                </div>

                <!-- Subscription Plan -->
                <div class="space-y-1.5">
                    <label for="plan" class="block text-xs font-semibold text-slate-300">Subscription Tier</label>
                    <select name="plan" id="plan" required
                            class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-slate-100 focus:outline-none focus:border-indigo-500 transition">
                        <option value="basic" {{ old('plan') == 'basic' ? 'selected' : '' }}>Basic Plan (Trial)</option>
                        <option value="professional" {{ old('plan') == 'professional' ? 'selected' : '' }}>Professional Plan</option>
                        <option value="enterprise" {{ old('plan') == 'enterprise' ? 'selected' : '' }}>Enterprise Plan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Admin Account -->
        <div class="p-6 bg-slate-900 border border-slate-800 rounded-xl space-y-4 shadow-md">
            <h3 class="font-bold text-white text-base border-b border-slate-800 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-user-shield text-indigo-400"></i>
                <span>2. Clinic Administrator Account</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Admin Name -->
                <div class="space-y-1.5">
                    <label for="admin_name" class="block text-xs font-semibold text-slate-300">Full Name</label>
                    <input type="text" name="admin_name" id="admin_name" required value="{{ old('admin_name') }}"
                           class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition"
                           placeholder="John Doe">
                </div>

                <!-- Admin Email -->
                <div class="space-y-1.5">
                    <label for="admin_email" class="block text-xs font-semibold text-slate-300">Login Email</label>
                    <input type="email" name="admin_email" id="admin_email" required value="{{ old('admin_email') }}"
                           class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition"
                           placeholder="johndoe@cityhealth.com">
                </div>

                <!-- Admin Password -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="admin_password" class="block text-xs font-semibold text-slate-300">Password</label>
                    <input type="password" name="admin_password" id="admin_password" required
                           class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition font-mono"
                           placeholder="••••••••">
                    <p class="text-[11px] text-slate-500">Must be at least 8 characters long.</p>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('superadmin.tenants.index') }}" 
               class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-medium transition border border-slate-700">
                Cancel
            </a>
            <button type="submit" 
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition shadow-lg shadow-indigo-600/20">
                Provision & Activate Clinic
            </button>
        </div>
    </form>
</div>
@endsection
