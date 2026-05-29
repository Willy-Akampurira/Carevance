{{-- resources/views/drugs/trashed.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Trashed Drugs Archive
    </h2> 

    <div class="w-full sm:w-auto flex justify-end">
        <a href="{{ route('drugs.index') }}"
           class="w-full sm:w-auto text-center px-4 py-2 bg-gray-100 border border-gray-300 text-gray-700 font-medium text-sm sm:text-base rounded-md shadow-sm hover:bg-gray-200 transition-all">
            Back to Active Inventory
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6 space-y-4">

    @if(session('success'))
        <div class="p-3 bg-green-50 border border-green-200 text-sm sm:text-base text-green-800 rounded-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full overflow-x-auto border border-gray-200 rounded-md shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left whitespace-nowrap">
            <thead class="bg-gray-50">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Quantity</th>
                    <th class="px-4 py-3">Deleted At</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                @forelse($drugs as $drug)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $drug->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $drug->category?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 font-mono text-gray-900">
                            {{ $drug->quantity }} <span class="text-xs text-gray-400 uppercase">{{ $drug->unit ?? '' }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">
                            {{ $drug->deleted_at ? \Carbon\Carbon::parse($drug->deleted_at)->format('d M Y (H:i)') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-right space-x-3">
                            <form action="{{ route('drugs.restore', $drug->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 hover:underline focus:outline-none">
                                    Restore
                                </button>
                            </form>

                            <form action="{{ route('drugs.forceDelete', $drug->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('CRITICAL ALERT: Are you completely sure you want to permanently erase this drug layout from the local storage systems? This action is irreversible.');">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 hover:underline focus:outline-none font-medium">
                                    Force Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            The recycling archive tray is empty. No soft-deleted drug item records match this filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($drugs->hasPages())
        <div class="pt-2 border-t border-gray-100 text-sm sm:text-base">
            {{ $drugs->links() }}
        </div>
    @endif
</div>
@endsection