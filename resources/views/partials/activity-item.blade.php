<li class="py-3 flex items-center justify-between">
    <div>
        <!-- Patient and Drug -->
        <p class="text-lg font-medium text-gray-900">
            {{ $activity->patient->name ?? 'Unknown Patient' }}
            – {{ $activity->drug->name ?? 'Unknown Drug' }}
        </p>

        <!-- Action / Status -->
        <p class="text-sm text-gray-600">
            {{ $activity->action ?? $activity->status ?? 'No action recorded' }}
        </p>

        <!-- Timestamp -->
        <p class="text-xs text-gray-500">
            {{ $activity->created_at ? $activity->created_at->diffForHumans() : '' }}
        </p>
    </div>

    <!-- Status badge -->
    <span class="px-3 py-1 rounded-full text-sm
        @switch($activity->status)
            @case('Active') bg-green-100 text-green-800 @break
            @case('Dispensed') bg-blue-100 text-blue-800 @break
            @case('Missed') bg-yellow-100 text-yellow-800 @break
            @case('Completed') bg-gray-200 text-gray-800 @break
            @case('Expired') bg-red-100 text-red-800 @break
            @case('Renewal Requested') bg-purple-100 text-purple-800 @break
            @default bg-gray-100 text-gray-800
        @endswitch">
        {{ $activity->status ?? 'Unknown' }}
    </span>
</li>
