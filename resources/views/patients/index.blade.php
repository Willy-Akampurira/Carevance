@extends('layouts.app')

@section('header')
    <!-- Responsive Header layout stack on mobile, inline on desktop -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
            Patient List
        </h2>
        <a href="{{ route('patients.archived') }}" 
           class="w-full sm:w-auto text-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-base sm:text-lg shadow-sm transition-colors">
            View Archived Patients
        </a>
    </div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-base sm:text-xl text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- WhatsApp-like Search Bar -->
    <div class="flex items-center mb-6 bg-gray-100 rounded-lg px-3 py-2 shadow-sm">
        <!-- Search Icon -->
        <i class="fas fa-search text-gray-500 mr-3 cursor-pointer"
           onclick="triggerPatientSearch()"></i>

        <!-- Input Field -->
        <input type="text" id="patientSearchInput"
               placeholder="Search patients..."
               value="{{ $search ?? '' }}"
               class="flex-1 bg-transparent border-none focus:ring-0 text-base sm:text-lg placeholder-gray-400"
               onkeydown="if(event.key === 'Enter'){ triggerPatientSearch(); }">
    </div>

    <!-- Responsive Horizontal Scroll Wrapper -->
    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 shadow-sm custom-scrollbar">
        <table class="table-auto w-full min-w-[800px] divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wider">
                    <th class="px-4 py-3 text-left">Serial No.</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">DOB</th>
                    <th class="px-4 py-3 text-left">Gender</th>
                    <th class="px-4 py-3 text-left">Contact</th>
                    <th class="px-4 py-3 text-left">Address</th>
                    <th class="px-4 py-3 text-left">Medical History</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($patients as $patient)
                    <tr class="text-sm sm:text-base text-gray-600 hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $patient->id }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $patient->name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($patient->dob)->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-3 capitalize whitespace-nowrap">{{ $patient->gender }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $patient->contact }}</td>
                        <td class="px-4 py-3 max-w-xs truncate">{{ $patient->address }}</td>
                        <td class="px-4 py-3 max-w-xs truncate">{{ $patient->medical_history }}</td>
                        <td class="px-4 py-3 whitespace-nowrap space-x-2 text-sm font-medium">
                            <a href="{{ route('patients.show', $patient->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">View</a> 
                            <a href="{{ route('patients.edit', $patient->id) }}" class="text-yellow-600 hover:text-yellow-800 hover:underline">Edit</a> 
                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 hover:underline bg-transparent border-none cursor-pointer"
                                        onclick="return confirm('Are you sure you want to archive this patient?')">
                                    Archive
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-base sm:text-lg text-gray-500 font-light">
                            No patients found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Container -->
    <div class="mt-4 px-2">
        {{ $patients->links() }}
    </div>
</div>

<!-- Extra Optional Minimal Scrollbar styling context for webkit layouts -->
<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<!-- Search Script -->
<script>
function triggerPatientSearch() {
    const query = document.getElementById('patientSearchInput').value.trim();
    if(query.length > 0) {
        window.location.href = "{{ route('patients.index') }}" + "?search=" + encodeURIComponent(query);
    } else {
        window.location.href = "{{ route('patients.index') }}";
    }
}
</script>
@endsection