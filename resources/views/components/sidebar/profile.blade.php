@props(['collapsed' => false])

<div class="flex items-center space-x-3 px-4 py-2 rounded-md hover:bg-sidebar-hover transition-all">
    <img src="{{ asset('images/user-avatar.png') }}" alt="User Avatar" class="h-8 w-8 rounded-full">
    @unless($collapsed)
        <div class="flex-1">
            <div class="text-sm font-medium text-sidebar-text">{{ Auth::user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-red-500 hover:underline">Logout</button>
            </form>
        </div>
    @endunless
</div>
