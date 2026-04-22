@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Archived Suppliers</h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">
    <!-- Top bar -->
    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('suppliers.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-2xl rounded hover:bg-gray-700">
            ← Back to Active Suppliers
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Archived Supplier Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Contact</th>
                    <th class="px-4 py-2 text-left">Phone</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Archived At</th>
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
                        <td class="px-4 py-2 text-gray-600">
                            {{ $supplier->deleted_at ? $supplier->deleted_at->format('d M Y H:i') : '' }}
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <!-- Restore -->
                            <form action="{{ route('suppliers.restore', $supplier->id) }}" method="POST" class="inline">
                                @csrf
                                <button class="text-green-600 hover:underline">Restore</button>
                            </form>

                            <!-- Force Delete -->
                            <form action="{{ route('suppliers.forceDelete', $supplier->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Permanently delete this supplier?');">
                                @csrf @method('DELETE')
                                <button class="text-red-800 hover:underline">Force Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-xl text-gray-500">
                            No archived suppliers found.
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
