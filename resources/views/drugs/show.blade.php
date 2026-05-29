{{-- resources/views/drugs/show.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Drug Profiles & Details
    </h2> 

    <a href="{{ route('drugs.index') }}"
       class="w-full sm:w-auto text-center px-4 py-2 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md hover:bg-gray-200 transition-colors shadow-sm">
        Back to Drugs
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-6">

    @if(session('success'))
        <div class="p-3 bg-green-50 border border-green-200 text-sm sm:text-base text-green-800 rounded-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="divide-y divide-gray-100 border-b border-gray-100 text-sm sm:text-base">
        
        <div class="py-3.5 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
            <span class="font-medium text-gray-500">Drug Name</span>
            <span class="sm:col-span-2 font-semibold text-gray-900">{{ $drug->name }}</span>
        </div>

        <div class="py-3.5 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
            <span class="font-medium text-gray-500">Category Classification</span>
            <span class="sm:col-span-2 text-gray-800">{{ $drug->category?->name ?? '—' }}</span>
        </div>

        <div class="py-3.5 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 items-center">
            <span class="font-medium text-gray-500">Total Stock On Hand</span>
            <div class="sm:col-span-2 font-mono font-bold text-gray-900 flex items-center gap-1.5">
                {{ $drug->totalQuantity() }} 
                <span class="text-xs font-sans font-semibold uppercase bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 border border-gray-200">{{ $drug->unit }}</span>
            </div>
        </div>

        <div class="py-3.5 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 items-center">
            <span class="font-medium text-gray-500">Reserved Status</span>
            <div class="sm:col-span-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                    {{ $drug->reserved ? 'bg-green-50 border-green-200 text-green-700' : 'bg-gray-50 border-gray-200 text-gray-600' }}">
                    {{ $drug->reserved ? 'Yes (Allocated/Held)' : 'No (Available)' }}
                </span>
            </div>
        </div>

        <div class="py-3.5 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
            <span class="font-medium text-gray-500">Nearest Batch Expiry</span>
            <span class="sm:col-span-2 text-gray-800 font-medium">
                @if(isset($nearestLot) && $nearestLot?->expiry_date)
                    <span class="{{ $nearestLot->expiry_date->isPast() ? 'text-red-600 font-bold' : '' }}">
                        {{ $nearestLot->expiry_date->format('d M Y') }}
                    </span>
                @else
                    <span class="text-gray-400">—</span>
                @endif
            </span>
        </div>

        <div class="py-3.5 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
            <span class="font-medium text-gray-500">Reorder Alert Threshold</span>
            <span class="sm:col-span-2 font-mono text-gray-700">{{ $drug->reorder_level }}</span>
        </div>

        <div class="py-3.5 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
            <span class="font-medium text-gray-500">Description / Clinical Remarks</span>
            <span class="sm:col-span-2 text-gray-600 whitespace-pre-line leading-relaxed">{{ $drug->description ?? 'No extra reference description attached to this stock item.' }}</span>
        </div>

        <div class="py-3.5 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
            <span class="font-medium text-gray-500">System Creation Timestamp</span>
            <span class="sm:col-span-2 text-gray-400 font-mono text-xs">{{ $drug->created_at->format('d M Y (H:i)') }}</span>
        </div>
    </div>

    <div class="pt-2 flex flex-col sm:flex-row items-center gap-3 justify-start">
        <a href="{{ route('drugs.edit', $drug) }}"
           class="w-full sm:w-auto text-center px-6 py-2 bg-yellow-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-yellow-700 active:scale-98 transition-all duration-150">
            Edit Profile
        </a>
        
        <form action="{{ route('drugs.destroy', $drug) }}" method="POST" class="w-full sm:w-auto"
              onsubmit="return confirm('Are you sure you want to transfer this drug profile into the system recycling trash?');">
            @csrf 
            @method('DELETE')
            <button type="submit" 
                    class="w-full sm:w-auto text-center px-6 py-2 bg-red-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-red-700 active:scale-98 transition-all duration-150">
                Delete Drug
            </button>
        </form>
    </div>
</div>
@endsection