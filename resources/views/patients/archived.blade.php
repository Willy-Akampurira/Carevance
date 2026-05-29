@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 leading-tight">
        Archived Patients
    </h2>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-4 sm:p-6">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-base sm:text-xl text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

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
                        <td class="px-4 py-3 whitespace-nowrap space-x-2 text-xs sm:text-sm font-medium">
                            <form action="{{ route('patients.restore', $patient->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white font-medium rounded hover:bg-blue-700 transition-colors shadow-sm">
                                    <i class="fas fa-undo mr-1 text-[10px] sm:text-xs"></i> Restore
                                </button>
                            </form>

                            <form action="{{ route('patients.forceDelete', $patient->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white font-medium rounded hover:bg-red-700 transition-colors shadow-sm"
                                        onclick="return confirm('Are you sure you want to permanently delete this patient? This action cannot be undone.')">
                                    <i class="fas fa-trash-alt mr-1 text-[10px] sm:text-xs"></i> Delete Permanently
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-base sm:text-lg text-gray-500 font-light">
                            No archived patients found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 px-2">
        {{ $patients->links() }}
    </div>
</div>

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
@endsection