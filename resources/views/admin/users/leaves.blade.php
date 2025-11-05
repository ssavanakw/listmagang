@extends('layouts.dashboard')

@section('title', 'Leave Requests — ' . ($user->name ?? 'User'))

@section('content')
<div class="p-6">
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-primary-800">🗓️ Leave Requests <span class="text-gray-500">/ {{ $user->name ?? ('User #'.$user->id) }}</span></h1>
    <p class="text-sm text-gray-500">Filter berdasarkan kata kunci, rentang tanggal, jenis izin, atau status.</p>
  </div>

  {{-- Filters --}}
  <form method="GET" class="grid md:grid-cols-6 gap-3 bg-white border rounded-xl p-4 shadow-sm mb-6">
    <div class="md:col-span-2">
      <label class="block text-xs font-medium text-gray-600 mb-1">Cari (alasan / jenis)</label>
      <input type="text" name="q" value="{{ request('q') }}" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Misal: sakit, keluarga, kuliah" />
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
      <input type="date" name="from" value="{{ request('from') }}" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
      <input type="date" name="to" value="{{ request('to') }}" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Izin</label>
      <input type="text" name="type" value="{{ request('type') }}" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="sick / personal / other" />
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
      <input type="text" name="status" value="{{ request('status') }}" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="approved / pending / rejected" />
    </div>
    <div class="md:col-span-6 flex gap-3">
      <button type="submit" class="px-4 py-2 rounded-lg bg-primary-600 text-white hover:bg-primary-700">Terapkan</button>
      <a href="{{ route('admin.user.leaveRequests', $user) }}" class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">Reset</a>
    </div>
  </form>

  {{-- Table --}}
  <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
          <tr>
            <th class="text-left px-4 py-3 font-semibold">Tanggal Izin</th>
            <th class="text-left px-4 py-3 font-semibold">Jenis</th>
            <th class="text-left px-4 py-3 font-semibold">Alasan</th>
            <th class="text-left px-4 py-3 font-semibold">Status</th>
            <th class="text-left px-4 py-3 font-semibold">Dibuat</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($leaves as $l)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($l->leave_date)->format('d M Y') }}</td>
              <td class="px-4 py-3">{{ $l->leave_type }}</td>
              <td class="px-4 py-3 text-gray-700">{{ \Illuminate\Support\Str::limit($l->reason, 140) }}</td>
              <td class="px-4 py-3">
                @php $st = $l->status ?? '—'; @endphp
                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                  @if($st === 'approved') bg-primary-100 text-primary-700
                  @elseif($st === 'rejected') bg-rose-100 text-rose-700
                  @elseif($st === 'pending') bg-amber-100 text-amber-700
                  @else bg-gray-100 text-gray-700 @endif">
                  {{ strtoupper($st) }}
                </span>
              </td>
              <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $l->created_at?->format('d M Y H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data untuk filter saat ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t bg-gray-50">
      {{ $leaves->links() }}
    </div>
  </div>

  {{-- Quick nav --}}
  <div class="mt-6 flex flex-wrap gap-3">
    <a href="{{ route('admin.user.dailyReports', $user) }}"
       class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">Lihat Daily Reports</a>
    <a href="{{ route('admin.user.pendingTasks', $user) }}"
       class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">Lihat Pending Tasks</a>
  </div>
</div>
@endsection
