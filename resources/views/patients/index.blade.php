@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            Patient List
        </h2>
        <!-- Archived Patients Button -->
        <a href="{{ route('patients.archived') }}" 
           class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-lg">
            View Archived Patients
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow rounded-lg p-6">
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="table-auto mx-auto border border-gray-200 rounded-lg w-full">
        <thead class="bg-gray-100">
            <tr class="border-t text-2xl">
                <th class="px-4 py-2 text-left">#</th>
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
                    <td class="px-4 py-2">
                        <a href="{{ route('patients.show', $patient->id) }}" 
                           class="text-blue-600 hover:underline">View</a> |
                        <a href="{{ route('patients.edit', $patient->id) }}" 
                           class="text-yellow-600 hover:underline">Edit</a> |
                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:underline"
                                    onclick="return confirm('Are you sure you want to archive this patient?')">
                                Archive
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-4 text-center text-xl text-gray-500">
                        No patients found.
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
