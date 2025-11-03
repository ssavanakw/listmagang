@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-semibold mb-6 text-gray-800 dark:text-gray-100">📋 Daftar Feedback</h1>

    @if(session('success'))
        <div class="mb-4 flex items-center p-4 text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <svg class="flex-shrink-0 w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zM9 13a1 1 0 102 0V9a1 1 0 00-2 0v4zm0 2a1 1 0 100-2h2a1 1 0 100 2H9z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">Berhasil!</span>&nbsp;{{ session('success') }}
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-center">No</th>
                    <th class="px-6 py-3">Nama Pengguna</th>
                    <th class="px-6 py-3">Feedback</th>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($feedbacks as $index => $feedback)
                    <tr class="bg-white border-b hover:bg-gray-50 dark:bg-gray-900 dark:border-gray-700 dark:hover:bg-gray-800 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $feedback->name }}</td>
                        <td class="px-6 py-4">{{ \Illuminate\Support\Str::limit($feedback->feedback, 50) }}</td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $feedback->created_at }}</td>
                        <td class="px-6 py-4 text-center space-x-3">
                            <button class="text-blue-600 hover:text-blue-800 font-semibold"
                                    data-modal-target="feedbackModal{{ $feedback->id }}"
                                    data-modal-toggle="feedbackModal{{ $feedback->id }}">Lihat</button>
                            <span class="text-gray-400">|</span>
                            <a href="{{ route('admin.feedback.edit', $feedback->id) }}"
                               class="text-yellow-500 hover:text-yellow-600 font-semibold">Edit</a>
                            <span class="text-gray-400">|</span>
                            <form action="{{ route('admin.feedback.destroy', $feedback->id) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-800 font-semibold transition duration-100"
                                        onclick="return confirm('Yakin ingin menghapus feedback ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Feedback -->
                    <div id="feedbackModal{{ $feedback->id }}" tabindex="-1" aria-hidden="true"
                         class="hidden fixed inset-0 z-50 flex items-center justify-center w-full h-full p-4 overflow-y-auto bg-black/40 backdrop-blur-sm">
                        <div class="relative w-full max-w-lg">
                            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between p-4 border-b dark:border-gray-600">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Isi Feedback</h3>
                                    <button type="button"
                                            class="text-gray-400 hover:text-gray-700 bg-transparent rounded-lg p-1.5 dark:hover:text-gray-300"
                                            data-modal-toggle="feedbackModal{{ $feedback->id }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-6 space-y-4">
                                    <p><strong class="text-gray-800 dark:text-gray-200">Nama Pengguna:</strong> {{ $feedback->name }}</p>
                                    <p><strong class="text-gray-800 dark:text-gray-200">Feedback:</strong></p>
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $feedback->feedback }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><strong>Tanggal:</strong> {{ $feedback->created_at }}</p>
                                </div>
                                <div class="flex justify-end p-4 border-t dark:border-gray-600">
                                    <button data-modal-toggle="feedbackModal{{ $feedback->id }}"
                                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition">
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
</div>
@endsection
