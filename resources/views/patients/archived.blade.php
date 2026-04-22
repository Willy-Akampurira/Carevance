@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
        Archived Patients
    </h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="table-auto mx-auto border border-gray-200 rounded-lg w-full">
        <thead class="bg-gray-100">
            <tr class="text-2xl">
                <th class="px-4 py-2 text-left">Serial No.</th>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">DOB</th>
                <th class="px-4 py-2 text-left">Gender</th>
                <th class="px-4 py-2 text-left">Contact</th>
                <th class="px-4 py-2 text-left">Address</th>
                <th class="px-4 py-2 text-left">Medical History</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
                <tr class="border-t text-xl">
                    <td class="px-4 py-2">{{ $patient->id }}</td>
                    <td class="px-4 py-2">{{ $patient->name }}</td>
                    <!-- Format DOB to remove timestamp -->
                    <td class="px-4 py-2">
                        {{ \Carbon\Carbon::parse($patient->dob)->format('Y-m-d') }}
                    </td>
                    <td class="px-4 py-2">{{ $patient->gender }}</td>
                    <td class="px-4 py-2">{{ $patient->contact }}</td>
                    <td class="px-4 py-2">{{ $patient->address }}</td>
                    <td class="px-4 py-2 truncate max-w-xs">{{ $patient->medical_history }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <!-- Restore Button -->
                        <form action="{{ route('patients.restore', $patient->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-3 py-1 bg-blue-600 text-white text-xl rounded hover:bg-blue-700">
                                Restore
                            </button>
                        </form>

                        <!-- Delete Permanently Button -->
                        <form action="{{ route('patients.forceDelete', $patient->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-1 bg-red-600 text-white text-xl rounded hover:bg-red-700"
                                    onclick="return confirm('Are you sure you want to permanently delete this patient? This action cannot be undone.')">
                                Delete Permanently
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 text-xl py-4 text-center text-gray-500">
                        No archived patients found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $patients->links() }}
    </div>
</div>
@endsection
