{{-- resources/views/user/riwayat-magang.blade.php --}}
@extends('layouts.dashboard')

@section('content')
@php
  use Carbon\Carbon;

  /** @var \App\Models\User|null $user */
  $user = auth()->user();

  // Ambil data dari controller, fallback ke relasi user
  $reports = (isset($reports) && $reports) ? $reports : ($user?->dailyReports()->orderByDesc('date')->get() ?? collect());
  $leaveRequests = (isset($leaveRequests) && $leaveRequests) ? $leaveRequests : ($user?->leaveRequests()->orderByDesc('leave_date')->get() ?? collect());
  $pendingTasks = (isset($pendingTasks) && $pendingTasks) ? $pendingTasks : ($user?->pendingTasks()->orderByDesc('created_at')->get() ?? collect());
@endphp

<div class="min-h-screen bg-emerald-300 py-12">
  <div class="max-w-7xl mx-auto px-6 space-y-8">

    {{-- Header --}}
    <div class="text-center mb-6">
      <h1 class="text-4xl font-bold text-emerald-900 mb-2">Riwayat Magang</h1>
      <p class="text-zinc-700 text-sm">Ringkasan aktivitas, izin, dan catatan tugas selama program magang Anda.</p>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
      <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl shadow">
        {{ session('success') }}
      </div>
    @elseif(session('error'))
      <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl shadow">
        {{ session('error') }}
      </div>
    @endif

    {{-- === CARD: LAPORAN HARIAN === --}}
    <section class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg ring-1 ring-emerald-100 p-6">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-2xl font-semibold text-emerald-800">Laporan Harian</h2>
          <p class="text-sm text-zinc-600">Catatan kegiatan yang telah Anda laporkan setiap hari.</p>
        </div>
        <span class="text-xs px-3 py-1 rounded-full bg-emerald-100 text-emerald-800">
          Total: {{ $reports->count() }}
        </span>
      </div>

      <div class="overflow-hidden rounded-xl border border-emerald-100 shadow-sm">
        <table class="min-w-full divide-y divide-emerald-100 text-sm">
          <thead class="bg-emerald-50 text-emerald-700 text-xs font-semibold uppercase">
            <tr>
              <th class="w-1/6 px-4 py-3 text-center align-middle leading-tight">Tanggal</th>
              <th class="w-1/2 px-4 py-3 text-left align-middle leading-tight">Aktivitas</th>
              <th class="w-1/4 px-4 py-3 text-left align-middle leading-tight">Tantangan</th>
              <th class="w-1/6 px-4 py-3 text-center align-middle leading-tight">Dikirim</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-emerald-50">
            @forelse($reports as $r)
              <tr class="hover:bg-emerald-50 transition-colors duration-150">
                <td class="px-4 py-3 text-center align-middle leading-tight">
                  {{ $r->date ? Carbon::parse($r->date)->isoFormat('D MMM Y') : '-' }}
                </td>
                <td class="px-4 py-3 align-middle leading-tight whitespace-pre-line">{{ $r->activities }}</td>
                <td class="px-4 py-3 align-middle leading-tight whitespace-pre-line text-zinc-700">{{ $r->challenges }}</td>
                <td class="px-4 py-3 text-center align-middle leading-tight text-xs text-zinc-500">
                  {{ $r->created_at ? Carbon::parse($r->created_at)->diffForHumans() : '-' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-6 text-center text-sm text-zinc-500 align-middle leading-tight">
                  Belum ada laporan harian.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    {{-- === CARD: PERMINTAAN IZIN === --}}
    <section class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg ring-1 ring-emerald-100 p-6">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-2xl font-semibold text-emerald-800">Permintaan Izin</h2>
          <p class="text-sm text-zinc-600">Riwayat pengajuan izin Anda selama magang.</p>
        </div>
        <span class="text-xs px-3 py-1 rounded-full bg-emerald-100 text-emerald-800">
          Total: {{ $leaveRequests->count() }}
        </span>
      </div>

      <div class="overflow-hidden rounded-xl border border-emerald-100 shadow-sm">
        <table class="min-w-full divide-y divide-emerald-100 text-sm">
          <thead class="bg-emerald-50 text-emerald-700 text-xs font-semibold uppercase">
            <tr>
              <th class="w-1/6 px-4 py-3 text-center align-middle leading-tight">Tanggal Izin</th>
              <th class="w-1/5 px-4 py-3 text-left align-middle leading-tight">Jenis Izin</th>
              <th class="w-1/2 px-4 py-3 text-left align-middle leading-tight">Alasan</th>
              <th class="w-1/6 px-4 py-3 text-center align-middle leading-tight">Diajukan</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-emerald-50">
            @forelse($leaveRequests as $l)
              <tr class="hover:bg-emerald-50 transition-colors duration-150">
                <td class="px-4 py-3 text-center align-middle leading-tight">
                  {{ $l->leave_date ? Carbon::parse($l->leave_date)->isoFormat('D MMM Y') : '-' }}
                </td>
                <td class="px-4 py-3 align-middle leading-tight">{{ $l->leave_type ?? '-' }}</td>
                <td class="px-4 py-3 align-middle leading-tight whitespace-pre-line text-zinc-700">{{ $l->reason ?? '-' }}</td>
                <td class="px-4 py-3 text-center align-middle leading-tight text-xs text-zinc-500">
                  {{ $l->created_at ? Carbon::parse($l->created_at)->diffForHumans() : '-' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-6 text-center text-sm text-zinc-500 align-middle leading-tight">
                  Belum ada permintaan izin.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    {{-- === CARD: TUGAS PENDING === --}}
    <section class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg ring-1 ring-emerald-100 p-6">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-2xl font-semibold text-emerald-800">Tugas Pending</h2>
          <p class="text-sm text-zinc-600">Daftar tugas yang pernah Anda tandai sebagai pending.</p>
        </div>
        <span class="text-xs px-3 py-1 rounded-full bg-emerald-100 text-emerald-800">
          Total: {{ $pendingTasks->count() }}
        </span>
      </div>

      <div class="overflow-hidden rounded-xl border border-emerald-100 shadow-sm">
        <table class="min-w-full divide-y divide-emerald-100 text-sm">
          <thead class="bg-emerald-50 text-emerald-700 text-xs font-semibold uppercase">
            <tr>
              <th class="w-1/6 px-4 py-3 text-center align-middle leading-tight">Tanggal</th>
              <th class="w-1/2 px-4 py-3 text-left align-middle leading-tight">Aktivitas</th>
              <th class="w-1/4 px-4 py-3 text-left align-middle leading-tight">Tantangan</th>
              <th class="w-1/6 px-4 py-3 text-center align-middle leading-tight">Dikirim</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-emerald-50">
            @forelse($pendingTasks as $p)
              <tr class="hover:bg-emerald-50 transition-colors duration-150">
                <td class="px-4 py-3 text-center align-middle leading-tight">
                  {{ $p->created_at ? Carbon::parse($p->created_at)->isoFormat('D MMM Y') : '-' }}
                </td>
                <td class="px-4 py-3 align-middle leading-tight whitespace-pre-line">{{ $p->title ?? '-' }}</td>
                <td class="px-4 py-3 align-middle leading-tight whitespace-pre-line text-zinc-700">{{ $p->description ?? '-' }}</td>
                <td class="px-4 py-3 text-center align-middle leading-tight text-xs text-zinc-500">
                  {{ $p->created_at ? Carbon::parse($p->created_at)->diffForHumans() : '-' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-6 text-center text-sm text-zinc-500 align-middle leading-tight">
                  Belum ada tugas pending.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

  </div>
</div>

@endsection
