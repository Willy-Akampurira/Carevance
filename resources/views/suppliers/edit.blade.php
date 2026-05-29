{{-- resources/views/suppliers/edit.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Edit Supplier
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-sm sm:text-base text-green-800 rounded-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-sm sm:text-base text-red-800 rounded-md shadow-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="space-y-4">
        @csrf 
        @method('PUT')

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $supplier->name) }}" 
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm" required>
        </div>

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Contact Person</label>
            <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" 
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
        </div>

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" 
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
        </div>

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $supplier->email) }}" 
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
        </div>

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Address</label>
            <input type="text" name="address" value="{{ old('address', $supplier->address) }}" 
                   class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
        </div>

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Status</label>
            <select name="status" 
                    class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2.5 focus:ring-green-500 focus:border-green-500 shadow-sm">
                <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div>
            <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full rounded-md border-gray-300 text-sm sm:text-base px-3 py-2 focus:ring-green-500 focus:border-green-500 shadow-sm">{{ old('notes', $supplier->notes) }}</textarea>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
            <a href="{{ route('suppliers.index') }}" 
               class="w-full sm:w-auto text-center px-6 py-2.5 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="w-full sm:w-auto text-center px-6 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all duration-150">
                <i class="fas fa-save mr-2 opacity-90"></i> Update Supplier
            </button>
        </div>
    </form>
</div>
@endsection