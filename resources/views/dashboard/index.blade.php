@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
            <p class="text-gray-600 dark:text-gray-400">Ringkasan aktivitas dan statistik pemagang.</p>
        </div>

        <!-- Quick Nav -->
        <div class="flex gap-2">
            <a href="{{ route('admin.interns.index') }}"
               class="px-3 py-2 rounded-lg text-sm bg-gray-200 dark:bg-gray-700 dark:text-gray-100">Semua</a>
            <a href="{{ route('admin.interns.active') }}"
               class="px-3 py-2 rounded-lg text-sm bg-gray-200 dark:bg-gray-700 dark:text-gray-100">Aktif</a>
            <a href="{{ route('admin.interns.completed') }}"
               class="px-3 py-2 rounded-lg text-sm bg-gray-200 dark:bg-gray-700 dark:text-gray-100">Selesai</a>
            <a href="{{ route('admin.interns.exited') }}"
               class="px-3 py-2 rounded-lg text-sm bg-gray-200 dark:bg-gray-700 dark:text-gray-100">Keluar</a>
            <a href="{{ route('admin.interns.pending') }}"
               class="px-3 py-2 rounded-lg text-sm bg-gray-200 dark:bg-gray-700 dark:text-gray-100">Pending</a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-lg shadow bg-white dark:bg-gray-800 p-4">
            <p class="mb-1 text-sm text-gray-500 dark:text-gray-400">Pendaftar Baru</p>
            <p class="text-2xl font-semibold text-emerald-600 dark:text-emerald-400">{{ $counts['new'] ?? 0 }}</p>
        </div>
        <div class="rounded-lg shadow bg-white dark:bg-gray-800 p-4">
            <p class="mb-1 text-sm text-gray-500 dark:text-gray-400">Pemagang Aktif</p>
            <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $counts['active'] ?? 0 }}</p>
        </div>
        <div class="rounded-lg shadow bg-white dark:bg-gray-800 p-4">
            <p class="mb-1 text-sm text-gray-500 dark:text-gray-400">Selesai</p>
            <p class="text-2xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $counts['completed'] ?? 0 }}</p>
        </div>
        <div class="rounded-lg shadow bg-white dark:bg-gray-800 p-4">
            <p class="mb-1 text-sm text-gray-500 dark:text-gray-400">Keluar</p>
            <p class="text-2xl font-semibold text-rose-600 dark:text-rose-400">{{ $counts['exited'] ?? 0 }}</p>
        </div>
        <div class="rounded-lg shadow bg-white dark:bg-gray-800 p-4">
            <p class="mb-1 text-sm text-gray-500 dark:text-gray-400">Pending</p>
            <p class="text-2xl font-semibold text-amber-600 dark:text-amber-400">{{ $counts['pending'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Chart: 1 Line Chart Total Pendaftar / Bulan -->
    <div class="grid grid-cols-1 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                Tren Total Pendaftar (6 Bulan)
            </h2>
            <canvas id="chartApplicants" height="160"></canvas>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(() => {
  const ctx = document.getElementById('chartApplicants');
  if (!ctx) return;

  // Data dari controller (labels & total)
  const labels = @json($chart['labels'] ?? []);
  const totals = @json($chart['total']  ?? []);

  function getColors() {
    const dark = document.documentElement.classList.contains('dark');
    return {
      axis: dark ? '#d1d5db' : '#374151',
      grid: dark ? 'rgba(209,213,219,.15)' : 'rgba(107,114,128,.15)',
      line: 'rgb(16,185,129)',           // emerald
      fill: 'rgba(16,185,129,.20)',
    };
  }

  let chart;
  function render() {
    const c = getColors();
    if (chart) chart.destroy();

    chart = new Chart(ctx, {
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
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: c.axis } } },
        scales: {
          x: { ticks: { color: c.axis }, grid: { color: c.grid } },
          y: { ticks: { color: c.axis }, grid: { color: c.grid } },
        }
      }
    });
  }

  render();

  // Re-render saat theme (dark/light) berubah
  new MutationObserver(render)
    .observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
})();
</script>
@endpush
