{{-- resources/views/suppliers/index.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Suppliers
    </h2> 

    <a href="{{ route('suppliers.archived') }}"
       class="w-full sm:w-auto text-center px-4 py-2 bg-red-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-red-700 transition-colors">
        <i class="fas fa-archive mr-1.5 opacity-90"></i> View Archived Suppliers
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <a href="{{ route('suppliers.create') }}"
           class="w-full lg:w-auto text-center px-5 py-2.5 bg-green-600 text-white font-medium text-base sm:text-lg rounded-md shadow hover:bg-green-700 active:scale-98 transition-all">
            + Add Supplier
        </a>

        <form method="GET" action="{{ route('suppliers.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <div class="relative flex-1 sm:w-64">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search suppliers..."
                       class="w-full border rounded-md border-gray-300 px-3 py-2 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
            </div>
            
            <select name="status" class="border rounded-md border-gray-300 px-3 py-2 text-sm sm:text-base focus:ring-green-500 focus:border-green-500 shadow-sm">
                <option value="">All statuses</option>
                <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            
            <button class="px-4 py-2 bg-gray-700 text-white font-medium text-sm sm:text-base rounded-md hover:bg-gray-800 transition-colors">
                Apply
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-sm sm:text-base text-green-800 rounded-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
            <thead class="bg-gray-50">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Contact</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $supplier->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $supplier->contact_person ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $supplier->phone ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $supplier->email ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $supplier->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($supplier->status) }}
                            </span>
                            @if($supplier->trashed())
                                <span class="ml-1.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Archived
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-medium space-x-2">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="text-blue-600 hover:text-blue-900 hover:underline">View</a>
                            
                            @if(!$supplier->trashed())
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="text-yellow-600 hover:text-yellow-900 hover:underline">Edit</a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Archive this supplier?');">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-900 hover:underline">Delete</button>
                                </form>
                            @else
                                <form action="{{ route('suppliers.restore', $supplier->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-green-600 hover:text-green-900 hover:underline">Restore</button>
                                </form>
                                <form action="{{ route('suppliers.forceDelete', $supplier->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Permanently delete this supplier?');">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="text-red-800 hover:text-red-950 hover:underline">Force Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            No functional records or supplier profiles matched the query criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suppliers->hasPages())
        <div class="mt-4 pt-2">
            {{ $suppliers->links() }}
        </div>
    @endif
</div>
@endsection