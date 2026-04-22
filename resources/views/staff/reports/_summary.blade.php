<div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
    @foreach($summary as $staffName => $hours)
        <div class="bg-blue-50 p-4 rounded shadow">
            <h3 class="text-xl font-semibold">{{ $staffName }}</h3>
            <p class="text-2xl font-bold text-blue-700">{{ $hours }} hrs</p>
            <p class="text-gray-600">This month</p>
        </div>
    @endforeach
</div>
