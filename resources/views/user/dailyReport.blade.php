@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6 lg:px-8">
  @if(auth()->user()->role === 'pemagang' && auth()->user()->internshipRegistration && auth()->user()->internshipRegistration->internship_status === 'active')
    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">

      <!-- Header -->
      <div class="bg-blue-600 text-white px-6 py-4 flex items-center gap-3">
        <i class="fas fa-clipboard-list text-2xl"></i>
        <h3 class="text-xl font-semibold">Laporan Harian Pemagang</h3>
      </div>

      <div class="p-6 space-y-6">
        <p class="text-gray-700 dark:text-gray-200">Silakan isi laporan harian Anda di bawah ini.</p>

        <!-- Form Laporan Harian -->
        <form action="{{ route('user.storeDailyReport') }}" method="POST" class="space-y-5">
          @csrf
          
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
              Tanggal
            </label>
            <input 
              type="date" 
              name="date" 
              id="date" 
              required
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            >
          </div>

          <div>
            <label for="activities" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
              Kegiatan Hari Ini
            </label>
            <textarea 
              name="activities" 
              id="activities" 
              rows="4" 
              required
              placeholder="Tuliskan kegiatan Anda hari ini..."
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            ></textarea>
          </div>

          <div>
            <label for="challenges" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
              Tantangan yang Dihadapi
            </label>
            <textarea 
              name="challenges" 
              id="challenges" 
              rows="4" 
              required
              placeholder="Tuliskan tantangan yang Anda hadapi..."
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            ></textarea>
          </div>

          <div>
            <button 
              type="submit"
              class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan
            </button>
          </div>
        </form>

        <hr class="border-gray-200 dark:border-gray-700">

        <!-- Laporan Terkirim -->
        <div>
          <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
            <i class="fas fa-clock text-blue-500"></i> Laporan Terkirim
          </h4>

          @forelse($reports as $report)
            <div class="mb-4 p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 transition hover:shadow-sm">
              <div class="flex items-center justify-between mb-2">
                <h5 class="font-semibold text-gray-900 dark:text-gray-100">
                  {{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}
                </h5>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                  {{ \Carbon\Carbon::parse($report->created_at)->diffForHumans() }}
                </span>
              </div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                <strong>Kegiatan:</strong> {{ $report->activities }}
              </p>
              <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                <strong>Tantangan:</strong> {{ $report->challenges }}
              </p>
            </div>
          @empty
            <div class="p-4 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
              Belum ada laporan yang dikirim.
            </div>
          @endforelse
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
