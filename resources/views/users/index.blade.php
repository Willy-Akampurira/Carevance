@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Users</h2> 

    <div class="flex space-x-3">
        <!-- Add User Button -->
        <a href="{{ route('users.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
            + Add User
        </a>

        <!-- View Trash Button -->
        <a href="{{ route('users.trash') }}" 
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
           onclick="triggerUserSearch()"></i>

        <input type="text" id="userSearchInput"
               placeholder="Search users..."
               value="{{ request('search') ?? '' }}"
               class="flex-1 bg-transparent border-none focus:ring-0 text-lg"
               onkeydown="if(event.key === 'Enter'){ triggerUserSearch(); }">
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->roles->pluck('name')->implode(', ') ?? '—' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm
                                {{ $user->deleted_at ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $user->deleted_at ? 'Deleted' : 'Active' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-3">
                            <a href="{{ route('users.edit', $user->id) }}" class="text-yellow-600 hover:underline">Edit</a>

                            <form action="{{ route('users.destroy',$user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Move this user to trash?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-xl text-gray-500">
                            No user records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $users->links() }}</div>
</div>

<!-- Search Script -->
<script>
function triggerUserSearch() {
    const query = document.getElementById('userSearchInput').value.trim();
    if(query.length > 0) {
        window.location.href = "{{ route('users.index') }}" + "?search=" + encodeURIComponent(query);
    } else {
        window.location.href = "{{ route('users.index') }}";
    }
}
</script>
@endsection
