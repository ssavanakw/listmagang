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


  {{-- === DATA LENGKAP DI BAWAH CHART === --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

  {{-- === A. USERS + STATUS MAGANG === --}}
  <div class="bg-white rounded-2xl shadow p-6 ring-1 ring-emerald-100 overflow-hidden">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-emerald-900">Daftar Pengguna & Status Magang</h2>
    </div>

    <div class="overflow-x-auto rounded-xl border border-emerald-100">
      <table class="min-w-full text-sm divide-y divide-emerald-100">
        <thead class="bg-emerald-50 text-emerald-700 text-xs font-semibold uppercase">
          <tr>
            <th class="w-1/4 px-4 py-3 text-left">Nama</th>
            <th class="w-1/4 px-4 py-3 text-left">Email</th>
            <th class="w-1/4 px-4 py-3 text-center">Status</th>
            <th class="w-1/4 px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-emerald-50">
          @forelse($users as $u)
            @php
              $st = optional($u->internshipRegistration)->internship_status ?? 'Not Registered';
              $badge = match($st) {
                'active'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'inactive' => 'bg-amber-100 text-amber-800 border-amber-200',
                'ended'    => 'bg-zinc-100 text-zinc-800 border-zinc-200',
                default    => 'bg-zinc-50 text-zinc-700 border-zinc-200',
              };
            @endphp
            <tr class="hover:bg-emerald-50/50">
              <td class="px-4 py-3 text-zinc-900">{{ $u->name }}</td>
              <td class="px-4 py-3 text-zinc-600">{{ $u->email }}</td>
              <td class="px-4 py-3 text-center">
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badge }}">
                  {{ ucfirst($st) }}
                </span>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-2">
                  <a href="{{ route('admin.user.dailyReports', $u->id) }}"
                     class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs hover:bg-emerald-200 transition">
                     Laporan
                  </a>
                  <a href="{{ route('admin.user.leaveRequests', $u->id) }}"
                     class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs hover:bg-blue-200 transition">
                     Izin
                  </a>
                  <a href="{{ route('admin.user.pendingTasks', $u->id) }}"
                     class="px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs hover:bg-amber-200 transition">
                     Tugas
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="4" class="px-4 py-6 text-center text-zinc-500">Belum ada pengguna.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- === B. SEMUA DATA (GLOBAL VIEW) === --}}
  <div class="bg-white rounded-2xl shadow p-6 ring-1 ring-emerald-100 space-y-8">

    {{-- Daily Reports --}}
    <div>
      <h3 class="font-semibold text-emerald-800 mb-2 flex items-center gap-2">
        <i class="fa-solid fa-clipboard-list"></i> Semua Laporan Harian
      </h3>
      <div class="overflow-hidden rounded-xl border border-emerald-100">
        <table class="min-w-full text-sm divide-y divide-emerald-100">
          <thead class="bg-emerald-50 text-emerald-700 text-xs font-semibold uppercase">
            <tr>
              <th class="px-4 py-3 text-left w-40">Nama User</th>
              <th class="px-4 py-3 text-left w-28">Tanggal</th>
              <th class="px-4 py-3 text-left">Aktivitas</th>
              <th class="px-4 py-3 text-left w-40">Tantangan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-emerald-50">
            @forelse($allReports ?? [] as $r)
              <tr class="hover:bg-emerald-50/50">
                <td class="px-4 py-3 font-medium text-zinc-800">{{ $r->user->name ?? '-' }}</td>
                <td class="px-4 py-3 text-zinc-700">{{ \Carbon\Carbon::parse($r->date)->isoFormat('D MMM Y') }}</td>
                <td class="px-4 py-3 text-zinc-800 whitespace-pre-line">{{ $r->activities }}</td>
                <td class="px-4 py-3 text-zinc-700 whitespace-pre-line">{{ $r->challenges }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="px-4 py-4 text-center text-zinc-500">Belum ada laporan harian.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Leave Requests --}}
    <div>
      <h3 class="font-semibold text-emerald-800 mb-2 flex items-center gap-2">
        <i class="fa-solid fa-calendar-days"></i> Semua Permintaan Izin
      </h3>
      <div class="overflow-hidden rounded-xl border border-emerald-100">
        <table class="min-w-full text-sm divide-y divide-emerald-100">
          <thead class="bg-emerald-50 text-emerald-700 text-xs font-semibold uppercase">
            <tr>
              <th class="px-4 py-3 text-left w-40">Nama User</th>
              <th class="px-4 py-3 text-left w-28">Tanggal</th>
              <th class="px-4 py-3 text-left w-36">Jenis</th>
              <th class="px-4 py-3 text-left">Alasan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-emerald-50">
            @forelse($allLeaves ?? [] as $l)
              <tr class="hover:bg-emerald-50/50">
                <td class="px-4 py-3 font-medium text-zinc-800">{{ $l->user->name ?? '-' }}</td>
                <td class="px-4 py-3 text-zinc-700">{{ \Carbon\Carbon::parse($l->leave_date)->isoFormat('D MMM Y') }}</td>
                <td class="px-4 py-3 text-zinc-800">{{ $l->leave_type }}</td>
                <td class="px-4 py-3 text-zinc-700 whitespace-pre-line">{{ $l->reason }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="px-4 py-4 text-center text-zinc-500">Belum ada data izin.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Pending Tasks --}}
    <div>
      <h3 class="font-semibold text-emerald-800 mb-2 flex items-center gap-2">
        <i class="fa-solid fa-list-check"></i> Semua Tugas Pending
      </h3>
      <div class="overflow-hidden rounded-xl border border-emerald-100">
        <table class="min-w-full text-sm divide-y divide-emerald-100">
          <thead class="bg-emerald-50 text-emerald-700 text-xs font-semibold uppercase">
            <tr>
              <th class="px-4 py-3 text-left w-40">Nama User</th>
              <th class="px-4 py-3 text-left w-60">Judul</th>
              <th class="px-4 py-3 text-left">Deskripsi</th>
              <th class="px-4 py-3 text-left w-36">Dibuat</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-emerald-50">
            @forelse($allTasks ?? [] as $t)
              <tr class="hover:bg-emerald-50/50">
                <td class="px-4 py-3 font-medium text-zinc-800">{{ $t->user->name ?? '-' }}</td>
                <td class="px-4 py-3 text-zinc-900">{{ $t->title }}</td>
                <td class="px-4 py-3 text-zinc-700 whitespace-pre-line">{{ $t->description }}</td>
                <td class="px-4 py-3 text-zinc-600">{{ \Carbon\Carbon::parse($t->created_at)->isoFormat('D MMM Y, HH:mm') }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="px-4 py-4 text-center text-zinc-500">Tidak ada tugas pending.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
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
