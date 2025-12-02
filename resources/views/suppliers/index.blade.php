@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Suppliers</h2> 

    <!-- Archived Suppliers Button -->
    <a href="{{ route('suppliers.archived') }}"
    class="px-4 py-2 bg-red-600 text-white text-xl rounded hover:bg-red-700">
        View Archived Suppliers
    </a>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto bg-white shadow rounded-lg p-6">
    <!-- Top bar -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <a href="{{ route('suppliers.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-2xl rounded hover:bg-green-700">
            + Add Supplier
        </a>

        <!-- Filters -->
        <form method="GET" action="{{ route('suppliers.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search suppliers..."
                   class="border rounded border-gray-300 px-3 py-2 w-64 text-xl focus:ring-green-500 focus:border-green-500">
            <select name="status" class="border rounded text-xl border-gray-300 px-3 py-2 focus:ring-green-500 focus:border-green-500">
                <option value="">All statuses</option>
                <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button class="px-4 py-2 bg-gray-700 text-white text-xl rounded hover:bg-gray-800">Apply</button>
        </form>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Supplier Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Contact</th>
                    <th class="px-4 py-2 text-left">Phone</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $supplier->name }}</td>
                        <td class="px-4 py-2">{{ $supplier->contact_person }}</td>
                        <td class="px-4 py-2">{{ $supplier->phone }}</td>
                        <td class="px-4 py-2">{{ $supplier->email }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm
                                {{ $supplier->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700 text-xl' }}">
                                {{ ucfirst($supplier->status) }}
                            </span>
                            @if($supplier->trashed())
                                <span class="ml-2 px-2 py-1 rounded text-sm bg-red-100 text-red-700">Archived</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="text-blue-600 hover:underline">View</a>
                            @if(!$supplier->trashed())
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="text-yellow-600 hover:underline">Edit</a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Archive this supplier?');">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            @else
                                <form action="{{ route('suppliers.restore', $supplier->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-green-600 hover:underline">Restore</button>
                                </form>
                                <form action="{{ route('suppliers.forceDelete', $supplier->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Permanently delete this supplier?');">
                                    @csrf @method('DELETE')
                                    <button class="text-red-800 hover:underline">Force Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-xl text-gray-500">
                            No suppliers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $suppliers->links() }}</div>
</div>
@endsection
