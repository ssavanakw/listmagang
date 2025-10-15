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

<div class="min-h-[70vh] flex items-center justify-center py-10 bg-emerald-300">
  <div class="w-full max-w-4xl">

    {{-- Flash / Link dokumen tersimpan --}}
    @if(session('success'))
      <div class="mb-4 bg-green-200 text-green-800 p-3 rounded-lg shadow-sm">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="mb-4 bg-red-200 text-red-800 p-3 rounded-lg shadow-sm">{{ session('error') }}</div>
    @endif

    @if(session('skl_url') || session('loa_url'))
      <div class="mb-4 bg-emerald-50 text-emerald-800 p-3 rounded-lg shadow-sm">
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
      <div class="bg-white rounded-2xl shadow-lg ring-1 ring-emerald-100 p-8 text-center animate__animated animate__fadeInUp">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-amber-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2Zm1 15h-2v-2h2v2Zm0-4h-2V7h2v6Z"/>
          </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 mb-2">Lengkapi Pendaftaran Magang/PKL</h1>
        <p class="text-zinc-600 mb-6">Kamu belum mengisi form pendaftaran. Yuk isi dulu agar kami bisa memproses.</p>
        <a href="{{ route('user.internship.form') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300">
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
      <div class="bg-emerald-300 rounded-2xl shadow-lg ring-1 ring-emerald-100 p-8 text-center animate__animated animate__fadeInUp">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-emerald-600">
            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-2.59a.75.75 0 1 0-1.12-.996l-3.61 4.06-1.52-1.52a.75.75 0 0 0-1.06 1.06l2.1 2.1a.75.75 0 0 0 1.09-.03l4.12-4.67Z" clip-rule="evenodd"/>
          </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 mb-2">Form Berhasil Dikirim 🎉</h1>
        <p class="text-zinc-600 mb-6">Terima kasih sudah mendaftar. Kami akan menghubungi kamu jika ada info lanjutan.</p>
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300">
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
      <div class="bg-emerald-300 rounded-2xl shadow-lg ring-1 ring-emerald-100 p-8 text-center animate__animated animate__fadeInUp">
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
            <a href="{{ route('user.editProfile') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300 transition duration-300">
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
      <div class="bg-white rounded-2xl shadow-lg ring-1 ring-emerald-100 p-8 animate__animated animate__fadeInUp">
        <h2 class="text-2xl font-bold text-emerald-700 mb-2">Magang Selesai 🎉</h2>
        <p class="text-zinc-700 mb-4">Selamat! Masa magang kamu telah selesai. Unduh <strong>SKL</strong> dan buat <strong>LOA</strong> bila diperlukan.</p>

        {{-- Ringkasan Peserta --}}
        <div class="rounded-xl border border-zinc-200/70 p-6 mb-6">
          <h3 class="text-lg font-semibold text-zinc-900 mb-3">Ringkasan Peserta</h3>
          <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <div>
              <dt class="text-zinc-500">Nama</dt>
              <dd class="font-medium">{{ $reg->fullname ?? $user->name }}</dd>
            </div>
            <div>
              <dt class="text-zinc-500">Email</dt>
              <dd class="font-medium">{{ $reg->email ?? $user->email }}</dd>
            </div>
            <div>
              <dt class="text-zinc-500">Institusi</dt>
              <dd class="font-medium">{{ $reg->institution_name ?? '-' }}</dd>
            </div>
            <div>
              <dt class="text-zinc-500">Program Studi</dt>
              <dd class="font-medium">{{ $reg->study_program ?? '-' }}</dd>
            </div>
            <div>
              @php
                $sd = $reg?->start_date ? \Carbon\Carbon::parse($reg->start_date)->isoFormat('D MMMM Y') : null;
                $ed = $reg?->end_date   ? \Carbon\Carbon::parse($reg->end_date)->isoFormat('D MMMM Y')   : null;
              @endphp
              <dt class="text-zinc-500">Periode</dt>
              <dd class="font-medium">{{ $sd && $ed ? $sd.' – '.$ed : '-' }}</dd>
            </div>
            <div>
              <dt class="text-zinc-500">Status</dt>
              <dd class="font-medium">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                  COMPLETED
                </span>
              </dd>
            </div>
          </dl>
        </div>

        {{-- Aksi Dokumen --}}
        <div class="grid md:grid-cols-2 gap-4">
          {{-- Download SKL --}}
          <a
            href="{{ $canDownload ? route('user.skl.download', ['intern_id' => $reg->id]) : 'javascript:void(0)' }}"
            class="w-full inline-flex items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold
                   {{ $canDownload ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-slate-200 text-slate-500 cursor-not-allowed' }}"
            @if(!$canDownload) aria-disabled="true" @endif
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 3a1 1 0 0 1 1 1v9.586l2.293-2.293a1 1 0 1 1 1.414 1.414l-4.004 4.004a1 1 0 0 1-1.414 0l-4.004-4.004a1 1 0 1 1 1.414-1.414L11 13.586V4a1 1 0 0 1 1-1z"/>
              <path d="M5 20a1 1 0 0 1 0-2h14a1 1 0 1 1 0 2H5z"/>
            </svg>
            Download SKL
          </a>

          {{-- Generate LOA --}}
          <form method="POST" action="{{ $canDownload ? route('user.loa.generate') : '#' }}" onsubmit="return {{ $canDownload ? 'true' : 'false' }};">
            @csrf
            <input type="hidden" name="intern_id" value="{{ $reg->id }}">
            <button type="{{ $canDownload ? 'submit' : 'button' }}"
              class="w-full inline-flex items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold
                     {{ $canDownload ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-slate-200 text-slate-500 cursor-not-allowed' }}"
              @if(!$canDownload) disabled aria-disabled="true" @endif
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                <path d="M5 4a2 2 0 0 1 2-2h6l6 6v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4z"/>
                <path d="M13 2v4a2 2 0 0 0 2 2h4"/>
              </svg>
              Generate LOA
            </button>
          </form>
        </div>

        @unless($canDownload)
          <p class="text-xs text-slate-500 mt-3">*Akses dibatasi: hanya <strong>pemagang</strong> dengan status <strong>completed</strong>.</p>
        @endunless

        {{-- Umpan Balik --}}
        <div class="bg-green-100 p-6 rounded-lg shadow-sm mt-6">
          <h3 class="text-lg font-semibold text-gray-800">Umpan Balik Magang</h3>
          <p class="text-gray-700 mt-2">Terima kasih atas kontribusimu. Beri kami masukan tentang pengalamanmu:</p>
          <form action="{{ \Illuminate\Support\Facades\Route::has('user.feedback.submit') ? route('user.feedback.submit') : '#' }}" method="POST" class="mt-3">
            @csrf
            <textarea name="feedback" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis umpan balik Anda..."></textarea>
            <button type="submit" class="mt-3 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none"
              @unless(\Illuminate\Support\Facades\Route::has('user.feedback.submit')) disabled @endunless>
              Kirim Umpan Balik
            </button>
          </form>
        </div>
      </div>
    @endif

  </div>
</div>
@endsection
