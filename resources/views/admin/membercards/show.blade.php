@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto py-6">
        <h1 class="text-3xl font-semibold text-gray-900 mb-4">Membercard Details</h1>

        <div class="bg-white p-6 rounded-lg shadow-md space-y-6">
            <!-- Membercard Header -->
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-gray-800">Details for {{ $download->name }}</h2>
                <span class="text-sm text-gray-500">Downloaded on: {{ \Carbon\Carbon::parse($download->downloaded_at)->format('F j, Y') }}</span>
            </div>

            <!-- Membercard Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <p><strong class="text-gray-600">ID:</strong> {{ $download->user_id }}</p>
                    <p><strong class="text-gray-600">Angkatan:</strong> {{ $download->angkatan }}</p>
                    <p><strong class="text-gray-600">Instansi:</strong> {{ $download->instansi }}</p>
                    <p><strong class="text-gray-600">Brand:</strong> {{ $download->brand }}</p>
                </div>

                <!-- File Preview Section -->
                <div class="space-y-3">
                    <h3 class="text-xl font-semibold text-gray-800">Downloaded File Preview</h3>
                    <div class="flex justify-center items-center">
                        <!-- Preview the downloaded PNG file -->
                        @if($download->filename && file_exists(storage_path('app/public/downloads/'.$download->filename)))
                            <img src="{{ asset('storage/downloads/'.$download->filename) }}" alt="Downloaded PNG" class="rounded-lg shadow-lg max-w-full h-auto">
                        @else
                            <p class="text-gray-500">No preview available for this file.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer Section -->
            <div class="flex justify-between items-center text-sm text-gray-500">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none">
                    Download Again
                </button>
                <span>Membercard information is confidential and only visible to the admin.</span>
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ url()->previous() }}" class="inline-block text-sm text-blue-600 hover:text-blue-800 transition duration-200 ease-in-out">
                    &larr; Back to Previous Page
                </a>
            </div>
        </div>
    </div>
@endsection
