{{-- resources/views/suppliers/archived.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Archived Suppliers
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('suppliers.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm sm:text-base font-medium text-gray-700 hover:bg-gray-200 transition-colors shadow-sm">
            <i class="fas fa-arrow-left mr-2 opacity-80"></i> Back to Active Suppliers
        </a>
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
                    <th class="px-4 py-3">Archived At</th>
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
                        <td class="px-4 py-3 text-gray-500">
                            {{ $supplier->deleted_at ? $supplier->deleted_at->format('d M Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium space-x-3">
                            
                            <form action="{{ route('suppliers.restore', $supplier->id) }}" method="POST" class="inline">
                                @csrf
                                <button class="text-green-600 hover:text-green-900 hover:underline focus:outline-none">
                                    Restore
                                </button>
                            </form>

                            <form action="{{ route('suppliers.forceDelete', $supplier->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Permanently delete this supplier? This action cannot be undone.');">
                                @csrf 
                                @method('DELETE')
                                <button class="text-red-700 hover:text-red-950 hover:underline focus:outline-none">
                                    Force Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            No archived supplier entries exist in the soft-deleted collection state.
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