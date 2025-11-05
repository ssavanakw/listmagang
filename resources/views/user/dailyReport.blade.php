@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-8 pb-6 lg:px-8 bg-primary-300">
  @if(auth()->user()->role === 'pemagang' && auth()->user()->internshipRegistration && auth()->user()->internshipRegistration->internship_status === 'active')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transition hover:shadow-xl ring-1 ring-primary-200">

      <!-- Header -->
      <div class="bg-primary-600 text-white px-6 py-4 flex items-center gap-3">
        <i class="fas fa-clipboard-list text-2xl"></i>
        <h3 class="text-xl font-semibold tracking-wide">Laporan Harian Pemagang</h3>
      </div>

      <div class="p-6 space-y-6">
        <p class="text-gray-700 dark:text-gray-200">Silakan isi laporan harian Anda di bawah ini.</p>

        <!-- Form Laporan Harian -->
        <form action="{{ route('user.storeDailyReport') }}" method="POST" class="space-y-5">
          @csrf
          
          <div>
            <label for="date" class="block text-sm font-medium text-primary-800 dark:text-primary-300 mb-1">
              Tanggal
            </label>
            <input 
              type="date" 
              name="date" 
              id="date" 
              required
              class="w-full rounded-lg border-primary-200 dark:border-primary-700 dark:bg-gray-900 dark:text-gray-100 
                     focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition shadow-sm"
            >
          </div>

          <div>
            <label for="activities" class="block text-sm font-medium text-primary-800 dark:text-primary-300 mb-1">
              Kegiatan Hari Ini
            </label>
            <textarea 
              name="activities" 
              id="activities" 
              rows="4" 
              required
              placeholder="Tuliskan kegiatan Anda hari ini..."
              class="w-full rounded-lg border-primary-200 dark:border-primary-700 dark:bg-gray-900 dark:text-gray-100 
                     focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition shadow-sm"
            ></textarea>
          </div>

          <div>
            <label for="challenges" class="block text-sm font-medium text-primary-800 dark:text-primary-300 mb-1">
              Tantangan yang Dihadapi
            </label>
            <textarea 
              name="challenges" 
              id="challenges" 
              rows="4" 
              required
              placeholder="Tuliskan tantangan yang Anda hadapi..."
              class="w-full rounded-lg border-primary-200 dark:border-primary-700 dark:bg-gray-900 dark:text-gray-100 
                     focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition shadow-sm"
            ></textarea>
          </div>

          <div>
            <button 
              type="submit"
              class="w-full sm:w-auto px-6 py-2.5 bg-primary-600 text-white font-medium rounded-lg shadow 
                     hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 
                     transition-all duration-150 ease-in-out"
            >
              <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan
            </button>
          </div>
        </form>

        <hr class="border-primary-200 dark:border-primary-700">

        <!-- Laporan Terkirim -->
        <div>
          <h4 class="text-lg font-semibold text-primary-800 dark:text-primary-300 mb-4 flex items-center gap-2">
            <i class="fas fa-clock text-primary-500"></i> Laporan Terkirim
          </h4>

          @forelse($reports as $report)
            <div class="mb-4 p-4 rounded-lg border border-primary-200 dark:border-primary-700 
                        bg-primary-50/50 dark:bg-gray-900 transition hover:shadow-md hover:bg-primary-50">
              <div class="flex items-center justify-between mb-2">
                <h5 class="font-semibold text-primary-800 dark:text-primary-200">
                  {{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}
                </h5>
                <span class="text-xs text-primary-600 dark:text-primary-400">
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
            <div class="p-4 text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-gray-900 
                        border border-primary-200 dark:border-primary-700 rounded-lg">
              Belum ada laporan yang dikirim.
            </div>
          @endforelse
        </div>
      </div>
    </div>
  @else
    <div class="max-w-3xl mx-auto mt-10">
      <div class="p-6 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-lg shadow">
        <p>Anda bukan  magang aktif atau bukan pemagang.</p>
      </div>
    </div>
  @endif
</div>
@endsection
