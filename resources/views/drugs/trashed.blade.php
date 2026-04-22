@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Trashed Drugs</h2> 

    <div class="flex space-x-3">
        <!-- Back to Drugs -->
        <a href="{{ route('drugs.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            Back to Drugs
        </a>
    </div>
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

    <!-- Trashed Drugs Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Deleted At</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drugs as $drug)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $drug->name }}</td>
                        <td class="px-4 py-2">{{ $drug->category?->name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $drug->quantity }}</td>
                        <td class="px-4 py-2">
                            {{ $drug->deleted_at ? \Carbon\Carbon::parse($drug->deleted_at)->format('d M Y H:i') : '—' }}
                        </td>
                        <td class="px-4 py-2 space-x-3">
                            <!-- Restore -->
                            <form action="{{ route('drugs.restore', $drug->id) }}" method="POST" class="inline">
                                @csrf
                                <button class="text-green-600 hover:underline">Restore</button>
                            </form>

                            <!-- Force Delete -->
                            <form action="{{ route('drugs.forceDelete', $drug->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Permanently delete this drug?');">
                                @csrf @method('DELETE')
                                <button class="text-red-800 hover:underline">Force Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-xl text-gray-500">
                            No trashed drugs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $drugs->links() }}</div>
</div>
@endsection
