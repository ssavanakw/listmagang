@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-8 pb-6 lg:px-8 bg-emerald-300">
    @if(auth()->user()->role === 'pemagang' && auth()->user()->internshipRegistration->internship_status === 'active')
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-emerald-100 dark:border-emerald-800 transition hover:shadow-xl ring-1 ring-emerald-200">

                <!-- Header -->
                <h2 class="text-2xl font-semibold text-emerald-800 dark:text-emerald-300 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-list text-emerald-600"></i>
                    Tugas Pending Pemagang
                </h2>
                <p class="text-gray-700 dark:text-gray-300 mb-5">
                    Berikut adalah tugas yang masih pending untuk Anda:
                </p>

                {{-- Daftar tugas pending --}}
                @if($pendingTasks->isEmpty())
                    <div class="p-4 mb-4 text-emerald-800 border border-emerald-200 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-300 dark:border-emerald-700">
                        Tidak ada tugas pending saat ini 🎉
                    </div>
                @else
                    <ul class="space-y-4 mb-6">
                        @foreach($pendingTasks as $task)
                            <li class="p-4 bg-emerald-50/80 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-700 hover:bg-emerald-100/80 dark:hover:bg-emerald-800/30 transition">
                                <h3 class="font-semibold text-emerald-900 dark:text-emerald-100">{{ $task->title }}</h3>
                                <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $task->description }}</p>
                                <span class="inline-flex mt-2 px-3 py-1 text-xs font-semibold text-emerald-800 bg-emerald-100 rounded-full dark:bg-emerald-700 dark:text-emerald-100">
                                    Pending
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <hr class="my-6 border-emerald-200 dark:border-emerald-700">

                {{-- Form tambah tugas --}}
                <h4 class="text-lg font-semibold text-emerald-800 dark:text-emerald-300 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-plus text-emerald-500"></i> Tambah Tugas Baru
                </h4>

                <form action="{{ route('user.storePendingTask') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="task_title" class="block mb-2 text-sm font-medium text-emerald-800 dark:text-emerald-300">
                            Judul Tugas
                        </label>
                        <input type="text" id="task_title" name="task_title" required
                            class="w-full rounded-lg border-emerald-200 dark:border-emerald-700 dark:bg-gray-900 dark:text-gray-100 
                                   focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm">
                    </div>

                    <div>
                        <label for="task_description" class="block mb-2 text-sm font-medium text-emerald-800 dark:text-emerald-300">
                            Deskripsi Tugas
                        </label>
                        <textarea id="task_description" name="task_description" rows="4" required
                            class="w-full rounded-lg border-emerald-200 dark:border-emerald-700 dark:bg-gray-900 dark:text-gray-100 
                                   focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm"></textarea>
                    </div>

                    <div>
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-lg 
                                   hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 dark:focus:ring-emerald-800 transition-all duration-150 ease-in-out">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            Tambah Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="max-w-2xl mx-auto">
            <div class="p-4 text-emerald-800 border border-emerald-300 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-300 dark:border-emerald-700">
                Anda belum memiliki magang aktif atau tidak memiliki peran <strong>pemagang</strong>.
            </div>
        </div>
    @endif
</div>
@endsection
