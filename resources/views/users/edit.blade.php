@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Edit User</h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">
    <!-- Success / Error Messages -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-xl text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit User Form -->
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                   class="w-full border rounded text-xl border-gray-300 px-3 py-2 focus:ring-green-500 focus:border-green-500" 
                   required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                   class="w-full border rounded text-xl border-gray-300 px-3 py-2 focus:ring-green-500 focus:border-green-500" 
                   required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Role</label>
            <select name="role" 
                    class="w-full border rounded text-xl border-gray-300 px-3 py-2 focus:ring-green-500 focus:border-green-500" 
                    required>
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" 
                        {{ (old('role', $user->roles->pluck('name')->first()) == $role->name) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" 
                class="px-4 py-2 bg-green-600 text-xl text-white rounded hover:bg-green-700">
            Update User
        </button>
    </form>
</div>
@endsection
