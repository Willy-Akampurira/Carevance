@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Edit User</h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-sm sm:text-base text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-sm sm:text-base text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 text-sm sm:text-base font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                   class="w-full border rounded-lg text-sm sm:text-base border-gray-300 px-3 py-2 focus:ring-green-500 focus:border-green-500" 
                   required>
        </div>

        <div>
            <label class="block text-gray-700 text-sm sm:text-base font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                   class="w-full border rounded-lg text-sm sm:text-base border-gray-300 px-3 py-2 focus:ring-green-500 focus:border-green-500" 
                   required>
        </div>

        <div>
            <label class="block text-gray-700 text-sm sm:text-base font-medium mb-1">Role</label>
            <select name="role" 
                    class="w-full border rounded-lg text-sm sm:text-base border-gray-300 px-3 py-2 focus:ring-green-500 focus:border-green-500" 
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

        <div class="pt-2">
            <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-sm sm:text-base text-white rounded-lg hover:bg-green-700 transition duration-150 ease-in-out">
                Update User
            </button>
        </div>
    </form>
</div>
@endsection