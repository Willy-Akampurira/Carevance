@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Staff</h2> 

    <div class="flex space-x-3">
        <!-- Add Staff Button -->
        <a href="{{ route('staff.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
            + Add Staff
        </a>

        <!-- View Trash Button -->
        <a href="{{ route('staff.trashed') }}" 
           class="px-4 py-2 bg-red-600 text-white text-xl rounded hover:bg-red-700">
            Trash
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

    <!-- WhatsApp-like Search Bar -->
    <div class="flex items-center mb-6 bg-gray-100 rounded-lg px-3 py-2 shadow-sm">
        <i class="fas fa-search text-gray-500 mr-3 cursor-pointer"
           onclick="triggerStaffSearch()"></i>

        <input type="text" id="staffSearchInput"
               placeholder="Search staff..."
               value="{{ request('search') ?? '' }}"
               class="flex-1 bg-transparent border-none focus:ring-0 text-lg"
               onkeydown="if(event.key === 'Enter'){ triggerStaffSearch(); }">
    </div>

    <!-- Staff Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Phone</th>
                    <th class="px-4 py-2 text-left">Department</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Status</th>
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
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm
                                {{ $member->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-3">
                            <a href="{{ route('staff.show', $member->id) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('staff.edit', $member->id) }}" class="text-yellow-600 hover:underline">Edit</a>

                            <!-- Only Deactivate here -->
                            <form action="{{ route('staff.destroy',$member->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Move this staff member to trash?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Deactivate</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-xl text-gray-500">
                            No staff records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $staff->links() }}</div>
</div>

<!-- Search Script -->
<script>
function triggerStaffSearch() {
    const query = document.getElementById('staffSearchInput').value.trim();
    if(query.length > 0) {
        window.location.href = "{{ route('staff.index') }}" + "?search=" + encodeURIComponent(query);
    } else {
        window.location.href = "{{ route('staff.index') }}";
    }
}
</script>
@endsection
