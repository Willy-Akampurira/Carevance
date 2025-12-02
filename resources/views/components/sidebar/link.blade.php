@props(['route', 'label', 'icon', 'collapsed' => false])

@php
    $isActive = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
   class="flex items-center px-4 py-2 rounded-md transition-all duration-200
          hover:bg-sidebar-hover
          {{ $isActive ? 'bg-sidebar-active font-semibold' : '' }}">
    <i class="{{ $icon }} text-lg text-sidebar-text"></i>
    @unless($collapsed)
        <span class="ml-3 text-sidebar-text">{{ $label }}</span>
    @endunless
</a>
