@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6 pb-6 bg-emerald-300">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 ">Dashboard Admin</h1>
            <p class="text-gray-600 ">Ringkasan aktivitas dan statistik pemagang.</p>
        </div>

        <!-- Quick Nav -->
        <div class="flex gap-2">
            <a href="{{ route('admin.interns.index') }}"
               class="px-3 py-2 rounded-lg text-sm bg-gray-200 ">Semua</a>
            <a href="{{ route('admin.interns.active') }}"
               class="px-3 py-2 rounded-lg text-sm bg-gray-200 ">Aktif</a>
            <a href="{{ route('admin.interns.completed') }}"
               class="px-3 py-2 rounded-lg text-sm bg-gray-200 ">Selesai</a>
            <a href="{{ route('admin.interns.exited') }}"
               class="px-3 py-2 rounded-lg text-sm bg-gray-200 ">Keluar</a>
            <a href="{{ route('admin.interns.pending') }}"
               class="px-3 py-2 rounded-lg text-sm bg-gray-200 ">Pending</a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-5">

      <!-- Pendaftar Baru -->
      <a href="{{ route('admin.interns.index') }}"
        class="block cursor-pointer rounded-lg shadow-lg bg-emerald-600 p-6 transition transform hover:scale-105 hover:shadow-xl hover:bg-emerald-900 duration-300"
        aria-label="Lihat semua pendaftar">
        <div class="flex justify-between items-center">
          <p class="mb-2 text-sm font-medium text-gray-100">Pendaftar Baru</p>
          <i class="fas fa-user-plus text-gray-100 text-2xl"></i>
        </div>
        <p class="text-4xl font-bold text-gray-100">{{ $counts['waiting'] ?? 0 }}</p>
      </a>

      <!-- Pemagang Aktif -->
      <a href="{{ route('admin.interns.active') }}"
        class="block cursor-pointer rounded-lg shadow-lg bg-blue-600 p-6 transition transform hover:scale-105 hover:shadow-xl hover:bg-blue-900 duration-300"
        aria-label="Lihat pemagang aktif">
        <div class="flex justify-between items-center">
          <p class="mb-2 text-sm font-medium text-gray-100">Pemagang Aktif</p>
          <i class="fas fa-users text-gray-100 text-2xl"></i>
        </div>
        <p class="text-4xl font-bold text-gray-100">{{ $counts['active'] ?? 0 }}</p>
      </a>

      <!-- Selesai -->
      <a href="{{ route('admin.interns.completed') }}"
        class="block cursor-pointer rounded-lg shadow-lg bg-indigo-600 p-6 transition transform hover:scale-105 hover:shadow-xl hover:bg-indigo-900 duration-300"
        aria-label="Lihat pemagang selesai">
        <div class="flex justify-between items-center">
          <p class="mb-2 text-sm font-medium text-gray-100">Selesai</p>
          <i class="fas fa-check-circle text-gray-100 text-2xl"></i>
        </div>
        <p class="text-4xl font-bold text-gray-100">{{ $counts['completed'] ?? 0 }}</p>
      </a>

      <!-- Keluar -->
      <a href="{{ route('admin.interns.exited') }}"
        class="block cursor-pointer rounded-lg shadow-lg bg-rose-600 p-6 transition transform hover:scale-105 hover:shadow-xl hover:bg-rose-900 duration-300"
        aria-label="Lihat pemagang keluar">
        <div class="flex justify-between items-center">
          <p class="mb-2 text-sm font-medium text-gray-100">Keluar</p>
          <i class="fas fa-sign-out-alt text-gray-100 text-2xl"></i>
        </div>
        <p class="text-4xl font-bold text-gray-100">{{ $counts['exited'] ?? 0 }}</p>
      </a>

      <!-- Pending -->
      <a href="{{ route('admin.interns.pending') }}"
        class="block cursor-pointer rounded-lg shadow-lg bg-amber-600 p-6 transition transform hover:scale-105 hover:shadow-xl hover:bg-amber-900 duration-300"
        aria-label="Lihat pemagang pending">
        <div class="flex justify-between items-center">
          <p class="mb-2 text-sm font-medium text-gray-100">Pending</p>
          <i class="fas fa-clock text-gray-100 text-2xl"></i>
        </div>
        <p class="text-4xl font-bold text-gray-100">{{ $counts['pending'] ?? 0 }}</p>
      </a>
    </div>


    <!-- Chart: 1 Line Chart Total Pendaftar / Bulan -->
    <div class="grid grid-cols-1 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
          Tren Total Pendaftar (6 Bulan)
        </h2>
        <!-- KUNCI TINGGI DI KONTENER -->
        <div class="relative h-64">
          <canvas id="chartApplicants" class="w-full h-full"></canvas>
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(() => {
  const el = document.getElementById('chartApplicants');
  if (!el) return;

  // Data dari controller
  const labelsRaw = @json($chart['labels'] ?? []);
  const totalsRaw = @json($chart['total']  ?? []);

  // Jaga-jaga: pastikan panjang sama & konversi ke number
  const len = Math.min(labelsRaw.length, totalsRaw.length);
  const labels = labelsRaw.slice(0, len);
  const totals = totalsRaw.slice(0, len).map(v => {
    const n = Number(v);
    return Number.isFinite(n) ? n : null; // biar null, bukan 0
  });

  function getColors() {
    const dark = document.documentElement.classList.contains('dark');
    return {
      axis: dark ? '#d1d5db' : '#374151',
      grid: dark ? 'rgba(209,213,219,.15)' : 'rgba(107,114,128,.15)',
      line: 'rgb(16,185,129)',           // emerald
      fill: 'rgba(16,185,129,.20)',
    };
  }

  function getSuggestedMax(values) {
    const valid = values.filter(v => Number.isFinite(v));
    const max = valid.length ? Math.max(...valid) : 10;
    return max <= 0 ? 10 : Math.ceil(max * 1.2);
  }

  let chart;
  function render() {
    const c = getColors();
    if (chart) chart.destroy();

    chart = new Chart(el, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Total Pendaftar',
          data: totals,
          borderColor: c.line,
          backgroundColor: c.fill,
          borderWidth: 2,
          tension: 0.35,
          fill: true,
          pointRadius: 3,
          pointHoverRadius: 5,
          spanGaps: true, // lewati gap, jangan turun ke bawah
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false, // biar ikut h-64
        animation: { duration: 500 },
        plugins: { legend: { labels: { color: c.axis } } },
        scales: {
          x: { ticks: { color: c.axis }, grid: { color: c.grid } },
          y: {
            min: 0,                      // KUNCI DARI 0
            suggestedMax: getSuggestedMax(totals),
            ticks: { color: c.axis, precision: 0, beginAtZero: true },
            grid: { color: c.grid }
          },
        }
      }
    });
  }

  render();

  // Re-render saat theme (dark/light) berubah
  const obs = new MutationObserver((muts) => {
    if (muts.some(m => m.attributeName === 'class')) render();
  });
  obs.observe(document.documentElement, { attributes: true });
})();
</script>
@endpush
