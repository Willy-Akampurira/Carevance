@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Drug Categories</h2> 

    <!-- Add Category Button -->
    <a href="{{ route('drugs.categories.create') }}"
       class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
        + Add Category
    </a>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Categories Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-left">Created At</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $category->name }}</td>
                        <td class="px-4 py-2">{{ $category->description ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $category->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-2 space-x-3">
                            <a href="{{ route('drugs.categories.edit', $category) }}" 
                               class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('drugs.categories.destroy', $category) }}" 
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Delete this category?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-xl text-gray-500">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $categories->links() }}</div>
</div>
@endsection
