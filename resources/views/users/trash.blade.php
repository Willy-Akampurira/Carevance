@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">Trashed Users</h2> 

    <div class="flex space-x-3">
        <a href="{{ route('users.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white text-sm sm:text-base rounded hover:bg-gray-700">
            Back to Users List
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
           onclick="triggerTrashSearch()"></i>

        <input type="text" id="trashSearchInput"
               placeholder="Search trashed users..."
               value="{{ request('search') ?? '' }}"
               class="flex-1 bg-transparent border-none focus:ring-0 text-sm sm:text-base"
               onkeydown="if(event.key === 'Enter'){ triggerTrashSearch(); }">
    </div>

    <div class="overflow-x-auto rounded border border-gray-200">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-gray-600 text-left">
                    <th class="p-4">Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Deleted At</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm sm:text-base divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="p-4 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="p-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="p-4 whitespace-nowrap">{{ $user->roles->pluck('name')->implode(', ') ?? '—' }}</td>
                        <td class="p-4 whitespace-nowrap">{{ $user->deleted_at }}</td>
                        <td class="p-4 whitespace-nowrap space-x-3">
                            <form action="{{ route('users.restore', $user->id) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button class="text-blue-600 hover:underline">Restore</button>
                            </form>

                            <form action="{{ route('users.forceDelete', $user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Permanently delete this user?');">
                                @csrf @method('DELETE')
                                <button class="text-red-700 hover:underline">Force Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-sm sm:text-base text-gray-500">
                            No trashed users found.
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