@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Daftar Feedback</h1>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-300 shadow-sm rounded-lg">
        <thead>
            <tr>
                <th class="py-3 px-4 border-b">No</th>
                <th class="py-3 px-4 border-b">Nama Pengguna</th>
                <th class="py-3 px-4 border-b">Feedback</th>
                <th class="py-3 px-4 border-b">Tanggal</th>
                <th class="py-3 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($feedbacks as $index => $feedback)
                <tr>
                    <td class="py-3 px-4 border-b text-center">{{ $index + 1 }}</td>
                    <td class="py-3 px-4 border-b">{{ $feedback->name }}</td>
                    <td class="py-3 px-4 border-b">{{ \Illuminate\Support\Str::limit($feedback->feedback, 50) }}</td>
                    <td class="py-3 px-4 border-b">{{ $feedback->created_at }}</td>
                    <td class="py-3 px-4 border-b text-center">
                        <!-- Show Button -->
                        <button class="text-blue-500 hover:text-blue-700" data-modal-target="feedbackModal{{ $feedback->id }}" data-modal-toggle="feedbackModal{{ $feedback->id }}">Show</button>
                        |
                        <a href="{{ route('admin.feedback.edit', $feedback->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        |
                        <form action="{{ route('admin.feedback.destroy', $feedback->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Show Feedback -->
                <div id="feedbackModal{{ $feedback->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full hidden">
                    <div class="relative w-full h-full max-w-2xl md:h-auto">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="feedbackModalLabel">Isi Feedback</h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:text-white dark:hover:text-white" data-modal-toggle="feedbackModal{{ $feedback->id }}">
                                    <span class="sr-only">Close</span>
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M4.293 4.293a1 1 0 011.414 0L8 6.586l2.293-2.293a1 1 0 111.414 1.414L9.414 8l2.293 2.293a1 1 0 01-1.414 1.414L8 9.414l-2.293 2.293a1 1 0 11-1.414-1.414L6.586 8 4.293 5.707a1 1 0 010-1.414z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6 space-y-6">
                                <p><strong>Nama Pengguna:</strong> {{ $feedback->name }}</p>
                                <p><strong>Feedback:</strong></p>
                                <p>{{ $feedback->feedback }}</p>
                                <p><strong>Tanggal:</strong> {{ $feedback->created_at }}</p>
                            </div>
                            <div class="flex items-center p-4 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button data-modal-toggle="feedbackModal{{ $feedback->id }}" type="button" class="text-white bg-blue-500 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
