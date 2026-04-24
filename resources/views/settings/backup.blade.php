@extends('layouts.app')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Backup & Restore</h2>

    <div class="flex space-x-3">
        <!-- Back to Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="px-4 py-2 bg-gray-600 text-white text-xl rounded hover:bg-gray-700">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full mx-auto bg-white shadow rounded-lg p-6">

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-xl text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Backup Section -->
    <div class="mb-6">
        <form action="{{ route('settings.backup.run') }}" method="GET">
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-xl rounded hover:bg-green-700">
                Backup Now
            </button>
        </form>
    </div>

    <!-- Restore Section -->
    <div class="mb-6">
        <form action="{{ route('settings.restore') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="backup_file" class="block text-xl font-medium text-gray-700">Restore from Backup</label>
            <input type="file" name="backup_file" id="backup_file" accept=".sql"
                   class="mt-2 block w-full border-gray-300 rounded-md shadow-sm text-xl focus:border-green-500 focus:ring-green-500">
            @error('backup_file')
                <p class="text-red-600 text-lg mt-1">{{ $message }}</p>
            @enderror
            <button type="submit"
                    class="mt-3 px-6 py-2 bg-red-600 text-white text-xl rounded hover:bg-red-700">
                Restore
            </button>
        </form>
    </div>

    <!-- Backup History -->
    <div>
        <h3 class="text-2xl font-semibold mb-4">Backup History</h3>
        <ul class="list-disc pl-6 space-y-2">
            @foreach(Storage::files('backups') as $file)
                <li>
                    {{ basename($file) }}
                    <a href="{{ route('settings.backup.download', basename($file)) }}"
                       class="ml-3 text-blue-600 hover:underline">Download</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
