{{-- resources/views/user/riwayat-magang.blade.php --}}
@extends('layouts.dashboard')

@section('content')
@php
  use Carbon\Carbon;

  /** @var \App\Models\User|null $user */
  $user = auth()->user();

  // Ambil dari controller jika dikirim; jika tidak, fallback ke relasi user
  /** @var \Illuminate\Support\Collection|\App\Models\DailyReport[] $reports */
  $reports = (isset($reports) && $reports) ? $reports : ($user?->dailyReports()->orderByDesc('date')->get() ?? collect());

  /** @var \Illuminate\Support\Collection|\App\Models\LeaveRequest[] $leaveRequests */
  $leaveRequests = (isset($leaveRequests) && $leaveRequests) ? $leaveRequests : ($user?->leaveRequests()->orderByDesc('leave_date')->get() ?? collect());

  /** @var \Illuminate\Support\Collection|\App\Models\PendingTask[] $pendingTasks */
  $pendingTasks = (isset($pendingTasks) && $pendingTasks) ? $pendingTasks : ($user?->pendingTasks()->orderByDesc('created_at')->get() ?? collect());
@endphp

<div class="min-h-[70vh] bg-emerald-300 py-10">
  <div class="max-w-7xl mx-auto p-6 bg-white rounded-2xl shadow-lg ring-1 ring-emerald-100">

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-zinc-900">Riwayat Magang</h1>
      <p class="text-zinc-600">Ringkasan aktivitas dan histori selama program magang.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
      <div class="mb-4 bg-green-100 border border-green-200 text-green-800 p-3 rounded-lg">
        {{ session('success') }}
      </div>
    @elseif(session('error'))
      <div class="mb-4 bg-red-100 border border-red-200 text-red-800 p-3 rounded-lg">
        {{ session('error') }}
      </div>
    @endif

    {{-- ====== DAILY REPORTS ====== --}}
    <section class="mb-10">
      <div class="flex items-end justify-between gap-4 mb-3">
        <div>
          <h2 class="text-xl font-semibold text-zinc-900">Laporan Harian</h2>
          <p class="text-sm text-zinc-600">Catatan kegiatan harian yang telah Anda laporkan.</p>
        </div>
        <span class="text-xs px-2 py-1 rounded-full bg-zinc-100 text-zinc-700">
          Total: {{ $reports->count() }}
        </span>
      </div>

      <div class="overflow-x-auto rounded-xl border border-zinc-200">
        <table class="min-w-full divide-y divide-zinc-200">
          <thead class="bg-zinc-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Tanggal</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Aktivitas</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Tantangan</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Dikirim</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-100 bg-white">
            @forelse($reports as $r)
              <tr class="hover:bg-zinc-50">
                <td class="px-4 py-3 text-sm text-zinc-800">
                  {{ $r->date ? Carbon::parse($r->date)->isoFormat('D MMM Y') : '-' }}
                </td>
                <td class="px-4 py-3 text-sm text-zinc-800 whitespace-pre-line">
                  {{ $r->activities }}
                </td>
                <td class="px-4 py-3 text-sm text-zinc-700 whitespace-pre-line">
                  {{ $r->challenges }}
                </td>
                <td class="px-4 py-3 text-xs text-zinc-500">
                  {{ $r->created_at ? Carbon::parse($r->created_at)->diffForHumans() : '-' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-6 text-center text-sm text-zinc-500">
                  Belum ada laporan harian.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    {{-- ====== LEAVE REQUESTS ====== --}}
    <section class="mb-10">
      <div class="flex items-end justify-between gap-4 mb-3">
        <div>
          <h2 class="text-xl font-semibold text-zinc-900">Permintaan Izin</h2>
          <p class="text-sm text-zinc-600">Riwayat pengajuan izin Anda.</p>
        </div>
        <span class="text-xs px-2 py-1 rounded-full bg-zinc-100 text-zinc-700">
          Total: {{ $leaveRequests->count() }}
        </span>
      </div>

      <div class="overflow-x-auto rounded-xl border border-zinc-200">
        <table class="min-w-full divide-y divide-zinc-200">
          <thead class="bg-zinc-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Tanggal Izin</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Jenis Izin</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Alasan</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Diajukan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-100 bg-white">
            @forelse($leaveRequests as $l)
              <tr class="hover:bg-zinc-50">
                <td class="px-4 py-3 text-sm text-zinc-800">
                  {{ $l->leave_date ? Carbon::parse($l->leave_date)->isoFormat('D MMM Y') : '-' }}
                </td>
                <td class="px-4 py-3 text-sm text-zinc-800">
                  {{ $l->leave_type ?? '-' }}
                </td>
                <td class="px-4 py-3 text-sm text-zinc-700 whitespace-pre-line">
                  {{ $l->reason ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs text-zinc-500">
                  {{ $l->created_at ? Carbon::parse($l->created_at)->diffForHumans() : '-' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-6 text-center text-sm text-zinc-500">
                  Belum ada permintaan izin.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    {{-- ====== PENDING TASKS ====== --}}
    <section>
      <div class="flex items-end justify-between gap-4 mb-3">
        <div>
          <h2 class="text-xl font-semibold text-zinc-900">Tugas Pending</h2>
          <p class="text-sm text-zinc-600">Daftar tugas yang pernah Anda catat sebagai pending.</p>
        </div>
        <span class="text-xs px-2 py-1 rounded-full bg-zinc-100 text-zinc-700">
          Total: {{ $pendingTasks->count() }}
        </span>
      </div>

      <div class="overflow-x-auto rounded-xl border border-zinc-200">
        <table class="min-w-full divide-y divide-zinc-200">
          <thead class="bg-zinc-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Judul Tugas</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Deskripsi</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Dibuat</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600">Terakhir Update</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-zinc-100 bg-white">
            @forelse($pendingTasks as $t)
              <tr class="hover:bg-zinc-50">
                <td class="px-4 py-3 text-sm text-zinc-800">
                  {{ $t->title ?? '-' }}
                </td>
                <td class="px-4 py-3 text-sm text-zinc-700 whitespace-pre-line">
                  {{ $t->description ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs text-zinc-500">
                  {{ $t->created_at ? Carbon::parse($t->created_at)->isoFormat('D MMM Y, HH:mm') : '-' }}
                </td>
                <td class="px-4 py-3 text-xs text-zinc-500">
                  {{ $t->updated_at ? Carbon::parse($t->updated_at)->isoFormat('D MMM Y, HH:mm') : '-' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-6 text-center text-sm text-zinc-500">
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
