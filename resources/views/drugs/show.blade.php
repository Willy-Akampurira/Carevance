@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Drug Details</h2> 

    <!-- Back Button -->
    <a href="{{ route('drugs.index') }}"
       class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
        Back to Drugs
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6 text-xl">

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Drug Info -->
    <div class="space-y-4">
        <div>
            <span class="font-semibold">Name:</span>
            {{ $drug->name }}
        </div>

        <div>
            <span class="font-semibold">Category:</span>
            {{ $drug->category?->name ?? '—' }}
        </div>

        <!-- ✅ Total Quantity + Unit -->
        <div>
            <span class="font-semibold">Total Quantity:</span>
            {{ $drug->totalQuantity() }} {{ $drug->unit }}
        </div>

        <div>
            <span class="font-semibold">Reserved:</span>
            {{ $drug->reserved ? 'Yes' : 'No' }}
        </div>

        <div>
            <span class="font-semibold">Expiry Date:</span>
            {{ $drug->expiry_date ? \Carbon\Carbon::parse($drug->expiry_date)->format('d M Y') : '—' }}
        </div>

        <div>
            <span class="font-semibold">Reorder Level:</span>
            {{ $drug->reorder_level }}
        </div>

        <div>
            <span class="font-semibold">Description:</span>
            {{ $drug->description ?? '—' }}
        </div>

        <div>
            <span class="font-semibold">Created At:</span>
            {{ $drug->created_at->format('d M Y') }}
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex space-x-3">
        <a href="{{ route('drugs.edit', $drug) }}"
           class="px-4 py-2 bg-yellow-600 text-white text-xl rounded hover:bg-yellow-700">
            Edit
        </a>
        <form action="{{ route('drugs.destroy', $drug) }}" method="POST"
              onsubmit="return confirm('Move this drug to trash?');">
            @csrf @method('DELETE')
            <button class="px-4 py-2 bg-red-600 text-white text-xl rounded hover:bg-red-700">
                Delete
            </button>
        </form>
    </div>
</div>
@endsection
