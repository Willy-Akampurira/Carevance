<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-center">
    @foreach($summary as $staffName => $hours)
        <div class="bg-blue-50 p-4 rounded-lg shadow-sm border border-blue-100">
            <h3 class="text-sm sm:text-base font-semibold text-gray-800">{{ $staffName }}</h3>
            <p class="text-xl sm:text-2xl font-bold text-blue-700 mt-1">{{ $hours }} hrs</p>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">This month</p>
        </div>
    @endforeach
</div>