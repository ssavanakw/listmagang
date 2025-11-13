@extends('layouts.dashboard')

@section('content')
@php
    $user = auth()->user();
    $reg  = $registration ?? ($user->internshipRegistration ?? null);

    $isCompleted = $reg && (
        $reg->internship_status === 'completed' ||
        (defined(\App\Models\InternshipRegistration::class.'::STATUS_COMPLETED')
            && $reg->internship_status === \App\Models\InternshipRegistration::STATUS_COMPLETED)
    );
    $isPemagang  = $user && $user->role === 'pemagang';
    $canDownload = $isPemagang && $isCompleted;
@endphp

<div class="min-h-[70vh] flex items-center justify-center py-10 bg-primary-300">
  <div class="w-full max-w-4xl">

    {{-- Flash / Link dokumen tersimpan --}}
    @if(session('success'))
      <div class="mb-4 bg-green-200 text-green-800 p-3 rounded-lg shadow-sm">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="mb-4 bg-red-200 text-red-800 p-3 rounded-lg shadow-sm">{{ session('error') }}</div>
    @endif

    @if(session('skl_url') || session('loa_url'))
      <div class="mb-4 bg-primary-50 text-primary-800 p-3 rounded-lg shadow-sm">
        <div class="font-semibold mb-1">Dokumen tersimpan:</div>
        <ul class="list-disc pl-5">
          @if(session('skl_url'))
            <li><a href="{{ session('skl_url') }}" target="_blank" class="underline">Buka SKL</a></li>
          @endif
          @if(session('loa_url'))
            <li><a href="{{ session('loa_url') }}" target="_blank" class="underline">Buka LOA</a></li>
          @endif
        </ul>
      </div>
    @endif

    {{-- Jika Belum Mengisi Form --}}
    @if(!$reg)
      <div class="bg-white rounded-2xl shadow-lg ring-1 ring-primary-100 p-8 text-center animate__animated animate__fadeInUp">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-amber-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2Zm1 15h-2v-2h2v2Zm0-4h-2V7h2v6Z"/>
          </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 mb-2">Lengkapi Pendaftaran Magang/PKL</h1>
        <p class="text-zinc-600 mb-6">Kamu belum mengisi form pendaftaran. Yuk isi dulu agar kami bisa memproses.</p>
        <a href="{{ route('internship.form') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-primary-600 text-white font-medium hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300">
          Isi Form Pendaftaran
        </a>
        <form method="POST" action="{{ route('user.logout') }}" class="mt-3">
          @csrf
          <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-red-600 text-white font-medium shadow hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 transition">
            Logout
          </button>
        </form>
      </div>

    {{-- Jika Status "Waiting" --}}
    @elseif($reg->internship_status === 'waiting')
      <div class="bg-primary-300 rounded-2xl shadow-lg ring-1 ring-primary-100 p-8 text-center animate__animated animate__fadeInUp">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-primary-100 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-primary-600">
            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-2.59a.75.75 0 1 0-1.12-.996l-3.61 4.06-1.52-1.52a.75.75 0 0 0-1.06 1.06l2.1 2.1a.75.75 0 0 0 1.09-.03l4.12-4.67Z" clip-rule="evenodd"/>
          </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 mb-2">Form Berhasil Dikirim 🎉</h1>
        <p class="text-zinc-600 mb-6">Terima kasih sudah mendaftar. Kami akan menghubungi kamu jika ada info lanjutan.</p>
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-primary-600 text-white font-medium hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300">
          Ke Beranda
        </a>
        <form method="POST" action="{{ route('user.logout') }}" class="mt-3">
          @csrf
          <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-red-600 text-white font-medium shadow hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 transition">
            Logout
          </button>
        </form>
        <p class="mt-4 text-xs text-zinc-500">Halaman ini adalah bukti bahwa kamu sudah mengirim formulir.</p>
      </div>

    {{-- Jika Status "Accepted" --}}
    @elseif($reg->internship_status === 'accepted')
      <div class="bg-primary-300 rounded-2xl shadow-lg ring-1 ring-primary-100 p-8 text-center animate__animated animate__fadeInUp">
        <h2 class="text-2xl font-bold text-primary-700 mb-2">Selamat! 🎉</h2>
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

    {{-- Jika Status "Rejected" --}}
    @elseif($reg->internship_status === 'rejected')
      <div class="bg-white/90 max-w-7xl mx-auto rounded-2xl shadow-lg ring-1 ring-red-100 p-8 animate__animated animate__fadeInUp">
        <div class="flex items-center gap-4 mb-6">
          <div class="shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-600" viewBox="0 0 24 24" fill="currentColor">
              <path fill-rule="evenodd" d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2Zm1 12h-2v2h2v-2Zm0-8h-2v6h2V6Z" clip-rule="evenodd"/>
            </svg>
          </div>
          <div class="flex-1">
            <h2 class="text-2xl font-bold text-red-600">Maaf, pengajuanmu belum bisa diterima</h2>
          </div>
        </div>

        <div class="flex flex-col gap-4">
          <p class="mt-2 text-zinc-700">Terima kasih sudah mendaftar. Kamu bisa perbaiki data atau ajukan kembali di periode berikutnya.</p>

          <div class="mt-6 grid sm:grid-cols-1 md:grid-cols-2 gap-6">
            <div class="rounded-xl border border-zinc-200/70 p-6">
              <h3 class="text-sm font-semibold text-zinc-900">Ringkasan Data</h3>
              <ul class="mt-2 text-sm text-zinc-700 space-y-2">
                <li><span class="text-zinc-500">Nama:</span> {{ $reg->fullname }}</li>
                <li><span class="text-zinc-500">Email:</span> {{ $reg->email }}</li>
                <li><span class="text-zinc-500">Instansi:</span> {{ $reg->institution_name }}</li>
                @if($reg->internship_interest)
                  <li><span class="text-zinc-500">Minat:</span> {{ $reg->internship_interest }}</li>
                @endif
              </ul>
            </div>

            <div class="rounded-xl border border-zinc-200/70 p-6">
              <h3 class="text-sm font-semibold text-zinc-900">Langkah Selanjutnya</h3>
              <ol class="mt-2 text-sm text-zinc-700 list-decimal list-inside space-y-2">
                <li>Periksa kembali data profilmu (kontak, periode, jurusan, dsb).</li>
                <li>Lengkapi dokumen pendukung (CV/portofolio) bila belum lengkap.</li>
                <li>Ajukan ulang pendaftaran saat data sudah siap.</li>
              </ol>
            </div>
          </div>

          <div class="mt-8 flex gap-4 flex-wrap">
            <a href="{{ route('user.editProfile') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300 transition duration-300">
              Edit Profil
            </a>
            <a href="{{ route('internship.form') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-white text-zinc-900 font-semibold border border-zinc-200 hover:bg-zinc-50 focus:outline-none focus:ring-4 focus:ring-zinc-200 transition duration-300">
              Ajukan Ulang
            </a>
          </div>

          <form method="POST" action="{{ route('user.logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 transition duration-300">
              Logout
            </button>
          </form>
        </div>
      </div>

    {{-- =========================
        Jika Status "Completed"
       ========================= --}}
    @elseif($isCompleted)
      @include('user.dashboard-completed')
    @endif
  </div>
</div>
@endsection
