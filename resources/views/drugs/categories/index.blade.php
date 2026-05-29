{{-- resources/views/drugs/categories/index.blade.php --}}
@extends('layouts.app')

@section('header')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Drug Categories
    </h2> 

    <a href="{{ route('drugs.categories.create') }}"
       class="w-full sm:w-auto text-center px-4 py-2 bg-green-600 text-white font-medium text-sm sm:text-base rounded-md shadow hover:bg-green-700 active:scale-98 transition-all">
        + Add Category
    </a>
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
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Created At</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-sm sm:text-base text-gray-700">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $category->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                            {{ $category->description ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $category->created_at ? $category->created_at->format('d M Y') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-right space-x-3">
                            <a href="{{ route('drugs.categories.edit', $category) }}" 
                               class="text-yellow-600 hover:text-yellow-900 hover:underline">Edit</a>
                            
                            <form action="{{ route('drugs.categories.destroy', $category) }}" 
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to permanently delete this drug category? This will affect indexed records attached to it.');">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 hover:underline focus:outline-none">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500 bg-gray-50/50">
                            No medical classifications or drug categories located in database indices.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="pt-2 border-t border-gray-100 text-sm sm:text-base">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection