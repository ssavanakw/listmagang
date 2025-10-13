@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6 pb-6 lg:px-8 bg-emerald-300">
  @if(auth()->check() && auth()->user()->role === 'pemagang' && isset($internship) && $internship->internship_status === 'active')
    <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
      
      <!-- Header -->
      <div class="bg-emerald-600 text-white px-6 py-4 flex items-center gap-3">
        <i class="fas fa-user-graduate text-2xl"></i>
        <h3 class="text-xl font-semibold">Dashboard Pemagang Aktif</h3>
      </div>

      <div class="p-6 space-y-6">
        <p class="text-gray-700 dark:text-gray-200 text-lg">
          Selamat datang, <strong>{{ auth()->user()->name }}</strong>.
        </p>
        <p class="text-gray-700 dark:text-gray-200">
          Status magang Anda saat ini adalah 
          <span class="font-semibold text-green-600">Aktif</span>.
        </p>

        <!-- Informasi Magang & Jadwal -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Informasi Magang -->
          <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
            <h4 class="text-lg font-semibold mb-3 flex items-center gap-2 text-gray-800 dark:text-gray-100">
              <i class="fas fa-info-circle text-blue-500"></i> Informasi Magang
            </h4>
            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
              <li><strong>Nama Program Magang:</strong> {{ $internship->internship_type }}</li>
              <li><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d-m-Y') }}</li>
              <li><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($internship->end_date)->format('d-m-Y') }}</li>
              <li><strong>Deskripsi:</strong> {{ $internship->internship_reason }}</li>
            </ul>
          </div>

          <!-- Jadwal Masuk -->
          <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
            <h4 class="text-lg font-semibold mb-3 flex items-center gap-2 text-gray-800 dark:text-gray-100">
              <i class="fas fa-calendar-day text-blue-500"></i> Jadwal Masuk
            </h4>
            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
              <li><strong>Hari Masuk:</strong> Senin - Jumat</li>
              <li><strong>Jam Kerja:</strong> 09:00 - 17:00</li>
              <li><strong>Lokasi:</strong> {{ $internship->internship_location ?? 'Tidak ditentukan' }}</li>
            </ul>
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div>
          <h4 class="text-lg font-semibold mb-3 flex items-center gap-2 text-gray-800 dark:text-gray-100">
            <i class="fas fa-cogs text-blue-500"></i> Aksi yang Dapat Dilakukan
          </h4>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <a href="{{ route('user.profile') }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-400 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition">
              <i class="fas fa-user-edit"></i> Perbarui Profil
            </a>
            <a href="{{ route('user.dailyReport') }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-cyan-50 dark:bg-cyan-900/30 border border-cyan-400 text-cyan-700 dark:text-cyan-300 rounded-lg hover:bg-cyan-100 dark:hover:bg-cyan-800 transition">
              <i class="fas fa-clipboard-list"></i> Laporan Harian
            </a>
            <a href="{{ route('user.leaveRequest') }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-400 text-yellow-700 dark:text-yellow-300 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-800 transition">
              <i class="fas fa-calendar-times"></i> Permintaan Izin
            </a>
            <a href="{{ route('user.pendingTasks') }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-red-50 dark:bg-red-900/30 border border-red-400 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 dark:hover:bg-red-800 transition">
              <i class="fas fa-tasks"></i> Tugas Pending
            </a>
          </div>
        </div>

        <!-- Status Magang -->
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-400 rounded-xl p-5 flex items-start gap-3">
          <i class="fas fa-flag-checkered text-green-600 dark:text-green-400 text-xl mt-1"></i>
          <div>
            <h4 class="font-semibold text-green-800 dark:text-green-300">Status Magang</h4>
            <p class="text-sm text-green-700 dark:text-green-200">
              <strong>Status:</strong> 
              <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-600 text-white ml-1">Aktif</span>
            </p>
            <p class="text-sm text-green-700 dark:text-green-200">
              <strong>Catatan:</strong> Anda dapat mengajukan izin atau melihat tugas yang masih pending.
            </p>
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="max-w-3xl mx-auto mt-10">
      <div class="p-6 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-lg">
        <p>Anda belum memiliki magang aktif atau tidak memiliki peran pemagang.</p>
      </div>
    </div>
  @endif
</div>
@endsection
