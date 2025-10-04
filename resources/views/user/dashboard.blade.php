@extends('layouts.dashboard')

@section('content')
@php
    $reg = auth()->user()->internshipRegistration ?? null;
@endphp

<div class="min-h-[70vh] flex items-center justify-center py-10">
  <div class="w-full max-w-xl">

    {{-- Belum pernah isi form --}}
    @if(!$reg)
      <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg ring-1 ring-emerald-100 p-8 text-center">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-amber-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2Zm1 15h-2v-2h2v2Zm0-4h-2V7h2v6Z"/>
          </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 mb-2">Lengkapi Pendaftaran Magang/PKL</h1>
        <p class="text-zinc-600 mb-6">Kamu belum mengisi form pendaftaran. Yuk isi dulu agar kami bisa memproses.</p>

        <a href="{{ route('internship.form') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300">
          Isi Form Pendaftaran
        </a>

        <form method="POST" action="{{ route('user.logout') }}" class="mt-3">
          @csrf
          <button type="submit"
                  class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-red-600 text-white font-medium shadow hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 transition">
            Logout
          </button>
        </form>
      </div>
    @else

      {{-- Sudah submit - status "new" => tampilkan tampilan submitted-page --}}
      @if($reg->internship_status === 'new')
        <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg ring-1 ring-emerald-100 p-8 text-center">
          <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-emerald-600">
              <path fill-rule="evenodd"
                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-2.59a.75.75 0 1 0-1.12-.996l-3.61 4.06-1.52-1.52a.75.75 0 0 0-1.06 1.06l2.1 2.1a.75.75 0 0 0 1.09-.03l4.12-4.67Z"
                    clip-rule="evenodd" />
            </svg>
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 mb-2">Form Berhasil Dikirim 🎉</h1>
          <p class="text-zinc-600 mb-6">
            Terima kasih sudah mendaftar Magang/PKL. Data kamu sudah kami terima.
            Kami akan menghubungi kamu melalui email/WhatsApp jika ada informasi lanjutan.
          </p>

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

          <p class="mt-4 text-xs text-zinc-500">
            Halaman ini adalah bukti bahwa kamu sudah mengirim formulir.
          </p>
        </div>

      {{-- Status accepted --}}
      @elseif($reg->internship_status === 'accepted')
        <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg ring-1 ring-emerald-100 p-8 text-center">
          <h2 class="text-2xl font-bold text-emerald-700 mb-2">Selamat! 🎉</h2>
          <p class="text-zinc-600">Anda <strong>DITERIMA</strong> magang. Silakan tunggu informasi jadwal mulai.</p>
          <div class="mt-6 text-left text-sm text-zinc-700">
            <h3 class="font-semibold mb-2">Ringkasan Data:</h3>
            <ul class="space-y-1">
              <li><span class="text-zinc-500">Nama:</span> {{ $reg->fullname }}</li>
              <li><span class="text-zinc-500">Email:</span> {{ $reg->email }}</li>
              <li><span class="text-zinc-500">Instansi:</span> {{ $reg->institution_name }}</li>
            </ul>
          </div>
        </div>

      {{-- Status rejected --}}
      @elseif($reg->internship_status === 'rejected')
        <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg ring-1 ring-red-100 p-8 text-center">
          <h2 class="text-2xl font-bold text-red-600 mb-2">Maaf 🙏</h2>
          <p class="text-zinc-600">Pengajuan magang kamu <strong>belum dapat kami terima</strong>.</p>
        </div>
      @endif

    @endif
  </div>
</div>
@endsection
