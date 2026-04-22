@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-2xl text-gray-800">Roles & Permissions</h2>
@endsection

@section('content')
<div class="bg-white shadow rounded-lg p-6 space-y-6">
    <p class="text-gray-600">Manage user accounts, roles, and permissions from here.</p>

    <div class="grid grid-cols-2 gap-6">
        <!-- Roles Management -->
        <a href="{{ route('roles.index') }}"
           class="block p-6 bg-blue-100 hover:bg-blue-200 rounded-lg text-center">
            <h3 class="font-bold text-lg">Roles Management</h3>
            <p class="text-sm text-gray-600">View all roles and assign permissions.</p>
        </a>

        <!-- Permissions Management -->
        <a href="{{ route('permissions.index') }}"
           class="block p-6 bg-green-100 hover:bg-green-200 rounded-lg text-center">
            <h3 class="font-bold text-lg">Permissions Management</h3>
            <p class="text-sm text-gray-600">Create, edit, and delete permissions.</p>
        </a>

        <!-- User Management -->
        <a href="{{ route('staff.index') }}"
           class="block p-6 bg-purple-100 hover:bg-purple-200 rounded-lg text-center">
            <h3 class="font-bold text-lg">User Management</h3>
            <p class="text-sm text-gray-600">Assign roles to staff accounts.</p>
        </a>

        <!-- Audit Logs -->
        <a href="{{ route('staff.logs') }}"
           class="block p-6 bg-yellow-100 hover:bg-yellow-200 rounded-lg text-center">
            <h3 class="font-bold text-lg">Audit Logs</h3>
            <p class="text-sm text-gray-600">Track role and permission changes.</p>
        </a>
    </div>
</div>
@endsection
