@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Supplier Details</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto text-xl bg-white shadow rounded-lg p-6">
    <h3 class="text-2xl font-bold mb-4">{{ $supplier->name }}</h3>

    <p><strong>Contact Person:</strong> {{ $supplier->contact_person }}</p>
    <p><strong>Phone:</strong> {{ $supplier->phone }}</p>
    <p><strong>Email:</strong> {{ $supplier->email }}</p>
    <p><strong>Address:</strong> {{ $supplier->address }}</p>
    <p><strong>Tax ID:</strong> {{ $supplier->tax_id }}</p>
    <p><strong>Status:</strong> {{ ucfirst($supplier->status) }}</p>
    <p><strong>Notes:</strong> {{ $supplier->notes }}</p>
    <p><strong>Created At:</strong> {{ $supplier->created_at->format('d M Y H:i') }}</p>
    <p><strong>Updated At:</strong> {{ $supplier->updated_at->format('d M Y H:i') }}</p>

    <div class="mt-4 space-x-2 text-xl">
        <a href="{{ route('suppliers.edit', $supplier) }}" 
           class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Edit</a>
        <a href="{{ route('suppliers.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Back to List</a>
    </div>
</div>
@endsection