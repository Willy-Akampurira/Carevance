@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Users</h2> 

    <div class="flex space-x-3">
        <a href="{{ route('users.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm sm:text-base rounded hover:bg-green-700">
            + Add User
        </a>

        <a href="{{ route('users.trash') }}" 
           class="px-4 py-2 bg-red-600 text-white text-sm sm:text-base rounded hover:bg-red-700">
            Trash
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

    <div class="flex items-center mb-6 bg-gray-100 rounded-lg px-3 py-2 shadow-sm">
        <i class="fas fa-search text-gray-500 mr-3 cursor-pointer"
           onclick="triggerUserSearch()"></i>

        <input type="text" id="userSearchInput"
               placeholder="Search users..."
               value="{{ request('search') ?? '' }}"
               class="flex-1 bg-transparent border-none focus:ring-0 text-sm sm:text-base"
               onkeydown="if(event.key === 'Enter'){ triggerUserSearch(); }">
    </div>

    <div class="overflow-x-auto rounded border border-gray-200">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600 text-left">
                    <th class="p-4">Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm sm:text-base divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="p-4 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="p-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="p-4 whitespace-nowrap">{{ $user->roles->pluck('name')->implode(', ') ?? '—' }}</td>
                        <td class="p-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $user->deleted_at ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $user->deleted_at ? 'Deleted' : 'Active' }}
                            </span>
                        </td>
                        <td class="p-4 whitespace-nowrap space-x-3">
                            <a href="{{ route('users.edit', $user->id) }}" class="text-yellow-600 hover:underline">Edit</a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Move this user to trash?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-sm sm:text-base text-gray-500">
                            No user records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm sm:text-base">
        {{ $users->links() }}
    </div>
</div>

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