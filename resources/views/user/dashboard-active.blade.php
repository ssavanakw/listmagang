@extends('layouts.dashboard')

@section('content')
@php
    $registration = auth()->user()->internshipRegistration;
@endphp

<div class="min-h-[70vh] flex items-center justify-center py-10">
  <div class="w-full max-w-xl">

    {{-- Tampilan Dashboard Aktif --}}
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg ring-1 ring-emerald-100 p-8 text-center">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 mb-2">Selamat, Kamu Diterima di Magang 🎉</h1>
      <p class="text-zinc-600 mb-6">Status magang kamu sekarang adalah <strong>Aktif</strong>. Kamu bisa memulai kegiatan magang sesuai dengan jadwal yang telah ditentukan.</p>

      <div class="mt-6 text-left text-sm text-zinc-700">
        <h3 class="font-semibold mb-2">Ringkasan Data:</h3>
        <ul class="space-y-1">
          <li><span class="text-zinc-500">Nama:</span> {{ $registration->fullname }}</li>
          <li><span class="text-zinc-500">Instansi:</span> {{ $registration->institution_name }}</li>
          <li><span class="text-zinc-500">Tanggal Mulai:</span> {{ \Carbon\Carbon::parse($registration->start_date)->format('d F Y') }}</li>
        </ul>
      </div>

      <a href="{{ route('user.dashboard') }}"
         class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300">
        Ke Beranda
      </a>

      <form method="POST" action="{{ route('user.logout') }}" class="mt-3">
        @csrf
        <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-red-600 text-white font-medium shadow hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 transition">
          Logout
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
