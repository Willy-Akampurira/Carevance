@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Trashed Users</h2> 

    <div class="flex space-x-3">
        <!-- Back to Users List -->
        <a href="{{ route('users.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            Back to Users List
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
           onclick="triggerTrashSearch()"></i>

        <input type="text" id="trashSearchInput"
               placeholder="Search trashed users..."
               value="{{ request('search') ?? '' }}"
               class="flex-1 bg-transparent border-none focus:ring-0 text-lg"
               onkeydown="if(event.key === 'Enter'){ triggerTrashSearch(); }">
    </div>

    <!-- Trashed Users Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr class="text-2xl">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Deleted At</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-t text-xl">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->roles->pluck('name')->implode(', ') ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $user->deleted_at }}</td>
                        <td class="px-4 py-2 space-x-3">
                            <!-- Restore -->
                            <form action="{{ route('users.restore',$user->id) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button class="text-blue-600 hover:underline">Restore</button>
                            </form>

                            <!-- Force Delete -->
                            <form action="{{ route('users.forceDelete',$user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Permanently delete this user?');">
                                @csrf @method('DELETE')
                                <button class="text-red-700 hover:underline">Force Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-xl text-gray-500">
                            No trashed users found.
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
function triggerTrashSearch() {
    const query = document.getElementById('trashSearchInput').value.trim();
    if(query.length > 0) {
        window.location.href = "{{ route('users.trash') }}" + "?search=" + encodeURIComponent(query);
    } else {
        window.location.href = "{{ route('users.trash') }}";
    }
}
</script>
@endsection
