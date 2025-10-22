@extends('layouts.dashboard')

@section('title', 'Pending Tasks — ' . ($user->name ?? 'User'))

@section('content')
<div class="p-6">
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-emerald-800">🧩 Pending Tasks <span class="text-gray-500">/ {{ $user->name ?? ('User #'.$user->id) }}</span></h1>
    <p class="text-sm text-gray-500">Daftar tugas yang belum selesai untuk pengguna ini. Gunakan filter untuk mempersempit pencarian.</p>
  </div>

  {{-- Filters --}}
  <form method="GET" class="grid md:grid-cols-4 gap-3 bg-white border rounded-xl p-4 shadow-sm mb-6">
    <div class="md:col-span-2">
      <label class="block text-xs font-medium text-gray-600 mb-1">Cari (judul / deskripsi)</label>
      <input type="text" name="q" value="{{ request('q') }}" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Misal: API, UI, laporan" />
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
      <input type="date" name="from" value="{{ request('from') }}" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" />
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
      <input type="date" name="to" value="{{ request('to') }}" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" />
    </div>
    <div class="md:col-span-4 flex gap-3">
      <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Terapkan</button>
      <a href="{{ route('admin.user.pendingTasks', $user) }}" class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">Reset</a>
    </div>
  </form>

  {{-- Table --}}
  <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
          <tr>
            <th class="text-left px-4 py-3 font-semibold">Dibuat</th>
            <th class="text-left px-4 py-3 font-semibold">Judul</th>
            <th class="text-left px-4 py-3 font-semibold">Deskripsi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($tasks as $t)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $t->created_at?->format('d M Y H:i') }}</td>
              <td class="px-4 py-3 font-medium text-gray-900">{{ $t->title }}</td>
              <td class="px-4 py-3 text-gray-700">{{ \Illuminate\Support\Str::limit($t->description, 160) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="px-4 py-8 text-center text-gray-500">Tidak ada data untuk filter saat ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t bg-gray-50">
      {{ $tasks->links() }}
    </div>
  </div>

  {{-- Quick nav --}}
  <div class="mt-6 flex flex-wrap gap-3">
    <a href="{{ route('admin.user.dailyReports', $user) }}"
       class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">Lihat Daily Reports</a>
    <a href="{{ route('admin.user.leaveRequests', $user) }}"
       class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">Lihat Leave Requests</a>
  </div>
</div>
@endsection
