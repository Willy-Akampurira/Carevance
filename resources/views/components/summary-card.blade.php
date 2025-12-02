@props(['icon', 'label', 'value', 'accent' => 'blue'])

@php
    $colors = [
        'blue'    => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700',
        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900 dark:text-emerald-200 dark:border-emerald-700',
        'amber'   => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900 dark:text-amber-200 dark:border-amber-700',
        'red'     => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900 dark:text-red-200 dark:border-red-700',
        'gray'    => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700',
        'indigo'  => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-900 dark:text-indigo-200 dark:border-indigo-700',
    ];

    $c = $colors[$accent] ?? $colors['gray'];
@endphp

<div class="w-full rounded-xl border {{ $c }} p-5 flex items-center gap-5 shadow-sm">
    <div class="shrink-0">
        <i class="{{ $icon }} text-4xl"></i>
    </div>
    <div class="flex flex-col">
        <span class="text-xl font-semibold">{{ $label }}</span>
        <span class="text-3xl font-bold">{{ $value }}</span>
    </div>
</div>
