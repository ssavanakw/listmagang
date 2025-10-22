@extends('layouts.dashboard')

@section('title', 'Daily Reports — ' . ($user->name ?? 'User'))

@section('content')
@php use Illuminate\Support\Str; @endphp

<div class="p-6">
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-emerald-800">📘 Daily Reports <span class="text-gray-500">/ {{ $user->name ?? ('User #'.$user->id) }}</span></h1>
    <p class="text-sm text-gray-500">Kelola catatan laporan harian milik pengguna ini. Gunakan filter untuk mempersempit rentang tanggal atau kata kunci.</p>
  </div>

  {{-- Filters --}}
  <form method="GET" class="grid md:grid-cols-4 gap-3 bg-white border rounded-xl p-4 shadow-sm mb-6">
    <div class="col-span-2">
      <label class="block text-xs font-medium text-gray-600 mb-1">Cari (aktivitas / tantangan)</label>
      <input type="text" name="q" value="{{ request('q', $filters['q'] ?? '') }}" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Misal: debugging, meeting, API" />
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
      <input type="date" name="from" value="{{ request('from', $filters['from'] ?? '') }}" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" />
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
      <input type="date" name="to" value="{{ request('to', $filters['to'] ?? '') }}" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" />
    </div>
    <div class="md:col-span-4 flex gap-3">
      <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Terapkan</button>
      <a href="{{ route('admin.user.dailyReports', $user) }}" class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">Reset</a>
    </div>
  </form>

  {{-- Table --}}
  <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
          <tr>
            <th class="text-left px-4 py-3 font-semibold">Tanggal</th>
            <th class="text-left px-4 py-3 font-semibold">Aktivitas</th>
            <th class="text-left px-4 py-3 font-semibold">Tantangan</th>
            <th class="text-left px-4 py-3 font-semibold">Dibuat</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($reports as $r)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($r->date)->format('d M Y') }}</td>
              <td class="px-4 py-3">
                <div class="font-medium text-gray-900">{{ Str::limit($r->activities, 120) }}</div>
                @if(strlen($r->activities) > 120)
                  <div class="text-xs text-gray-500 mt-1">…</div>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="text-gray-700">{{ Str::limit($r->challenges, 120) }}</div>
                @if(strlen($r->challenges) > 120)
                  <div class="text-xs text-gray-500 mt-1">…</div>
                @endif
              </td>
              <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $r->created_at?->format('d M Y H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                Tidak ada data untuk filter saat ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="px-4 py-3 border-t bg-gray-50">
      {{ $reports->links() }}
    </div>
  </div>

  {{-- Quick nav to sibling pages --}}
  <div class="mt-6 flex flex-wrap gap-3">
    <a href="{{ route('admin.user.leaveRequests', $user) }}"
       class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">Lihat Leave Requests</a>
    <a href="{{ route('admin.user.pendingTasks', $user) }}"
       class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">Lihat Pending Tasks</a>
  </div>
</div>
@endsection
