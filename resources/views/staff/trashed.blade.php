@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Trashed Staff</h2> 

    <div class="flex space-x-3">
        <a href="{{ route('staff.index') }}"
           class="px-4 py-2 bg-gray-600 text-white text-sm sm:text-base rounded hover:bg-gray-700">
            &larr; Back to Staff
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-sm sm:text-base text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded border border-gray-200">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Phone</th>
                    <th class="px-4 py-3 text-left">Department</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-left">Deleted At</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm sm:text-base">
                @forelse($staff as $member)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $member->name }}</td>
                        <td class="px-4 py-3">{{ $member->email }}</td>
                        <td class="px-4 py-3">{{ $member->phone }}</td>
                        <td class="px-4 py-3">{{ $member->department?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $member->role?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $member->deleted_at?->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 space-x-3">
                            <form action="{{ route('staff.restore', $member->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Restore this staff member?');">
                                @csrf
                                <button class="text-green-600 hover:underline">Restore</button>
                            </form>

                            <form action="{{ route('staff.forceDelete', $member->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Permanently delete this staff member? This cannot be undone.');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Force Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-sm sm:text-base text-gray-500">
                            No trashed staff records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm sm:text-base">{{ $staff->links() }}</div>
</div>
@endsection