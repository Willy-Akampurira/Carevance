@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Add Staff</h2> 

    <div class="flex space-x-3">
        <!-- Back to Staff List -->
        <a href="{{ route('staff.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            ← Back
        </a>

        <!-- View Trash Button -->
        <a href="{{ route('staff.logs') }}"
           class="px-4 py-2 bg-red-600 text-white text-xl rounded hover:bg-red-700">
            Trash
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Alerts -->
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-xl text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Create Staff Form -->
    <form action="{{ route('staff.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label class="block text-lg font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="mt-1 block w-full border rounded px-4 py-2 text-lg focus:ring-green-500 focus:border-green-500"
                   required>
        </div>

        <!-- Email -->
        <div>
            <label class="block text-lg font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="mt-1 block w-full border rounded px-4 py-2 text-lg focus:ring-green-500 focus:border-green-500"
                   required>
        </div>

        <!-- Phone -->
        <div>
            <label class="block text-lg font-medium text-gray-700">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="mt-1 block w-full border rounded px-4 py-2 text-lg focus:ring-green-500 focus:border-green-500">
        </div>

        <!-- Department -->
        <div>
            <label class="block text-lg font-medium text-gray-700">Department</label>
            <select name="department_id"
                    class="mt-1 block w-full border rounded px-4 py-2 text-lg focus:ring-green-500 focus:border-green-500"
                    required>
                <option value="">-- Select Department --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Role -->
        <div>
            <label class="block text-lg font-medium text-gray-700">Role</label>
            <select name="role_id"
                    class="mt-1 block w-full border rounded px-4 py-2 text-lg focus:ring-green-500 focus:border-green-500"
                    required>
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-lg font-medium text-gray-700">Status</label>
            <select name="status"
                    class="mt-1 block w-full border rounded px-4 py-2 text-lg focus:ring-green-500 focus:border-green-500"
                    required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Save Staff
            </button>
        </div>
    </form>
</div>
@endsection
