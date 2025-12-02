@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Add Supplier</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
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

    <!-- Add Supplier Form -->
    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Contact Person</label>
            <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Address</label>
            <input type="text" name="address" value="{{ old('address') }}" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Tax ID</label>
            <input type="text" name="tax_id" value="{{ old('tax_id') }}" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Status</label>
            <select name="status" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-xl">Notes</label>
            <textarea name="notes" class="w-full border rounded text-xl border-gray-300 px-3 py-2  focus:ring-green-500 focus:border-green-500">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="px-4 py-2 bg-green-600 text-xl text-white rounded hover:bg-green-700">
            Save Supplier
        </button>
    </form>
</div>
@endsection