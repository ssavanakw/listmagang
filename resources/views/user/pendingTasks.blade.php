@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6 lg:px-8">
    @if(auth()->user()->role === 'pemagang' && auth()->user()->internshipRegistration->internship_status === 'active')
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-list text-blue-600"></i>
                    Tugas Pending Pemagang
                </h2>
                <p class="text-gray-600 dark:text-gray-300 mb-5">
                    Berikut adalah tugas yang masih pending untuk Anda:
                </p>

                {{-- Daftar tugas pending --}}
                @if($pendingTasks->isEmpty())
                    <div class="p-4 mb-4 text-green-800 border border-green-200 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-900">
                        Tidak ada tugas pending saat ini 🎉
                    </div>
                @else
                    <ul class="space-y-4 mb-6">
                        @foreach($pendingTasks as $task)
                            <li class="p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-xl border border-yellow-200 dark:border-yellow-700">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $task->title }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $task->description }}</p>
                                <span class="inline-flex mt-2 px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
                                    Pending
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                {{-- Form tambah tugas --}}
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-plus text-blue-500"></i> Tambah Tugas Baru
                </h4>

                <form action="{{ route('user.storePendingTask') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="task_title" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Judul Tugas
                        </label>
                        <input type="text" id="task_title" name="task_title" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="task_description" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Deskripsi Tugas
                        </label>
                        <textarea id="task_description" name="task_description" rows="4" required
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>

                    <div>
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            Tambah Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="max-w-2xl mx-auto">
            <div class="p-4 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-900">
                Anda belum memiliki magang aktif atau tidak memiliki peran <strong>pemagang</strong>.
            </div>
        </div>
    @endif
</div>
@endsection
