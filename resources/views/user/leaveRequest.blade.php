@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6 lg:px-8">
  @if(auth()->user()->role === 'pemagang' && auth()->user()->internshipRegistration->internship_status === 'active')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">

      <!-- Header -->
      <div class="bg-yellow-500 text-white px-6 py-4 flex items-center gap-3">
        <i class="fas fa-calendar-times text-2xl"></i>
        <h3 class="text-xl font-semibold">Permintaan Izin Pemagang</h3>
      </div>

      <!-- Body -->
      <div class="p-6 space-y-6">
        <p class="text-gray-700 dark:text-gray-200">Silakan ajukan permintaan izin Anda di bawah ini.</p>

        <form action="{{ route('user.storeLeaveRequest') }}" method="POST" class="space-y-5">
          @csrf

          <!-- Jenis Izin -->
          <div>
            <label for="leave_type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
              Jenis Izin
            </label>
            <select 
              name="leave_type" 
              id="leave_type" 
              required
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
            >
              <option value="sick">Sakit</option>
              <option value="personal">Keperluan Pribadi</option>
              <option value="other">Lainnya</option>
            </select>
          </div>

          <!-- Tanggal Izin -->
          <div>
            <label for="leave_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
              Tanggal Izin
            </label>
            <input 
              type="date" 
              name="leave_date" 
              id="leave_date" 
              required
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
            >
          </div>

          <!-- Alasan Izin -->
          <div>
            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
              Alasan Izin
            </label>
            <textarea 
              name="reason" 
              id="reason" 
              rows="4" 
              required
              placeholder="Tuliskan alasan izin Anda..."
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
            ></textarea>
          </div>

          <!-- Tombol Kirim -->
          <div>
            <button 
              type="submit" 
              class="w-full sm:w-auto px-6 py-2.5 bg-yellow-500 text-white font-medium rounded-lg shadow hover:bg-yellow-600 transition focus:outline-none focus:ring-2 focus:ring-yellow-400"
            >
              <i class="fas fa-paper-plane mr-2"></i> Ajukan Izin
            </button>
          </div>
        </form>
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
