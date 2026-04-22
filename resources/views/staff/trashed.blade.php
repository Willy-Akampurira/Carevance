@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Trashed Staff</h2> 

    <div class="flex space-x-3">
        <!-- Back to Staff List -->
        <a href="{{ route('staff.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            ← Back to Staff
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

    <!-- Trashed Staff Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Phone</th>
                    <th class="px-4 py-2 text-left">Department</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Deleted At</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $member)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $member->name }}</td>
                        <td class="px-4 py-2">{{ $member->email }}</td>
                        <td class="px-4 py-2">{{ $member->phone }}</td>
                        <td class="px-4 py-2">{{ $member->department?->name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $member->role?->name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $member->deleted_at?->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-2 space-x-3">
                            <!-- Restore Button -->
                            <form action="{{ route('staff.restore',$member->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Restore this staff member?');">
                                @csrf
                                <button class="text-green-600 hover:underline">Restore</button>
                            </form>

                            <!-- Force Delete Button -->
                            <form action="{{ route('staff.forceDelete',$member->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Permanently delete this staff member? This cannot be undone.');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Force Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-xl text-gray-500">
                            No trashed staff records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $staff->links() }}</div>
</div>
@endsection
