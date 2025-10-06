@extends('layouts.dashboard')
@php
    /** @var \App\Models\InternshipRegistration|null $intern */
    $intern = $intern ?? (auth()->user()->internshipRegistration ?? null);

    // utility classes biar konsisten
    $label = 'block mb-2 text-[13px] sm:text-sm font-semibold text-gray-800 dark:text-gray-200 select-none';
    $input = 'w-full h-11 sm:h-11 lg:h-12 px-3 rounded-xl mb-3
          border border-gray-300/80 dark:border-gray-700/70
          bg-white/95 dark:bg-gray-800/80
          text-gray-900 dark:text-gray-100
          placeholder-gray-400 dark:placeholder-gray-500
          shadow-sm transition-colors
          hover:border-gray-400 dark:hover:border-gray-600
          focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
          disabled:opacity-60 disabled:cursor-not-allowed
          read-only:bg-gray-50 dark:read-only:bg-gray-800/60';
    $card  = 'rounded-2xl bg-white/95 dark:bg-gray-900/80 backdrop-blur-sm
         ring-1 ring-gray-200/80 dark:ring-white/10
         shadow hover:shadow-md transition-shadow
         p-6 md:p-7 lg:p-8
         mb-8 md:mb-10 last:mb-0';
@endphp

@section('content')
<div class="container mx-auto px-4 pt-6 bg-emerald-300">
  <div class="max-w-6xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Edit Profil</h2>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
      <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800">
        {{ session('success') }}
      </div>
    @endif
    @if (session('info'))
      <div class="mb-4 px-4 py-3 rounded-xl bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/20 dark:text-blue-200 dark:border-blue-800">
        {{ session('info') }}
      </div>
    @endif
    @if ($errors->any())
      <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800">
        <div class="font-semibold mb-1">Ada yang perlu dicek ulang:</div>
        <ul class="list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if (empty($intern))
      <div class="px-4 py-5 rounded-xl bg-yellow-50 text-yellow-800 border border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-100 dark:border-yellow-800">
        Kamu belum memiliki data pendaftaran. <a href="{{ route('internship.form') }}" class="underline font-medium">Isi form dulu</a> ya ✍️
      </div>
    @else
    <form action="{{ route('user.updateProfile') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
      @csrf
      <input type="hidden" name="internship_status" value="new" />
      <div class="grid grid-cols-1 gap-y-10 lg:gap-y-12">
        {{-- Data Pribadi --}}
        <section class="{{ $card }}">
          <div class="flex items-center gap-2 mb-6">
            <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">🌱</span>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Data Pribadi</h3>
          </div>

          <div class="grid grid-cols-12 gap-x-6 gap-y-6">
            {{-- Nama --}}
            <div class="col-span-12 md:col-span-6 lg:col-span-4">
              <label class="{{ $label }}">Nama Lengkap</label>
              <input type="text" name="fullname" value="{{ old('fullname', $intern->fullname) }}"
                    class="{{ $input }} @error('fullname') border-red-500 focus:ring-red-500 @enderror" required autofocus>
              @error('fullname') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tanggal Lahir --}}
            <div class="col-span-12 md:col-span-6 lg:col-span-4">
              <label class="{{ $label }}">Tanggal Lahir</label>
              <input type="date" name="born_date"
                    value="{{ old('born_date', \Illuminate\Support\Str::of($intern->born_date)->substr(0,10)) }}"
                    class="{{ $input }} @error('born_date') border-red-500 focus:ring-red-500 @enderror" required>
              @error('born_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Gender --}}
            @php $g = old('gender', $intern->gender); @endphp
            <div class="col-span-12 md:col-span-6 lg:col-span-4">
              <label class="{{ $label }}">Jenis Kelamin</label>
              <select name="gender" class="{{ $input }} pr-10 @error('gender') border-red-500 focus:ring-red-500 @enderror" required>
                <option value="" disabled {{ $g ? '' : 'selected' }}>Pilih gender</option>
                <option value="male"   {{ $g === 'male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ $g === 'female' ? 'selected' : '' }}>Perempuan</option>
                <option value="other"  {{ $g === 'other' ? 'selected' : '' }}>Lainnya</option>
              </select>
              @error('gender') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div class="col-span-12 md:col-span-6 lg:col-span-6">
              <label class="{{ $label }}">Email</label>
              <input type="email" name="email" value="{{ old('email', $intern->email) }}"
                    class="{{ $input }} @error('email') border-red-500 focus:ring-red-500 @enderror" required>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pastikan email aktif untuk komunikasi.</p>
              @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- No HP --}}
            <div class="col-span-12 md:col-span-6 lg:col-span-6">
              <label class="{{ $label }}">No. HP</label>
              <input type="text" name="phone_number" value="{{ old('phone_number', $intern->phone_number) }}"
                    class="{{ $input }} @error('phone_number') border-red-500 focus:ring-red-500 @enderror" required>
              @error('phone_number') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
          </div>
        </section>
      </div>

      {{-- Akademik --}}
      <section class="{{ $card }}">
        <div class="flex items-center gap-2 mb-6">
          <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">🎓</span>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Akademik</h3>
        </div>

        <div class="grid grid-cols-12 gap-x-6 gap-y-6">
          <div class="col-span-12 md:col-span-6 lg:col-span-4">
            <label class="{{ $label }}">NIM / Student ID</label>
            <input type="text" name="student_id" value="{{ old('student_id', $intern->student_id) }}"
                   class="{{ $input }} @error('student_id') border-red-500 focus:ring-red-500 @enderror" required>
            @error('student_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-4">
            <label class="{{ $label }}">Institusi</label>
            <input type="text" name="institution_name" value="{{ old('institution_name', $intern->institution_name) }}"
                   class="{{ $input }} @error('institution_name') border-red-500 focus:ring-red-500 @enderror" required>
            @error('institution_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-4">
            <label class="{{ $label }}">Program Studi</label>
            <input type="text" name="study_program" value="{{ old('study_program', $intern->study_program) }}"
                   class="{{ $input }} @error('study_program') border-red-500 focus:ring-red-500 @enderror" required>
            @error('study_program') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-6">
            <label class="{{ $label }}">Fakultas</label>
            <input type="text" name="faculty" value="{{ old('faculty', $intern->faculty) }}"
                   class="{{ $input }} @error('faculty') border-red-500 focus:ring-red-500 @enderror" required>
            @error('faculty') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-6">
            <label class="{{ $label }}">Kota Saat Ini</label>
            <input type="text" name="current_city" value="{{ old('current_city', $intern->current_city) }}"
                   class="{{ $input }} @error('current_city') border-red-500 focus:ring-red-500 @enderror" required>
            @error('current_city') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        {{-- Periode Magang --}}
        <div class="flex items-center gap-2 mb-6">
          <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">🗓️</span>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Periode Magang</h3>
        </div>

        <div class="grid grid-cols-12 gap-x-6 gap-y-6">
          {{-- Tanggal Mulai --}}
          <div class="col-span-12 md:col-span-6">
            <label class="{{ $label }}">Tanggal Mulai</label>
            <input
              type="date"
              name="start_date"
              value="{{ old('start_date', optional($intern->start_date)->format('Y-m-d')) }}"
              class="{{ $input }}"
            >
            @error('start_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          {{-- Tanggal Selesai --}}
          <div class="col-span-12 md:col-span-6">
            <label class="{{ $label }}">Tanggal Selesai</label>
            <input
              type="date"
              name="end_date"
              value="{{ old('end_date', optional($intern->end_date)->format('Y-m-d')) }}"
              class="{{ $input }}"
            >
            @error('end_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
          Kosongkan jika belum pasti. Format otomatis <code>YYYY-MM-DD</code>.
        </p>


      </section>

      {{-- Preferensi & Alasan --}}
      <section class="{{ $card }}">
        <div class="flex items-center gap-2 mb-6">
          <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">🎯</span>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Preferensi & Alasan</h3>
        </div>

        <div class="grid grid-cols-12 gap-x-6 gap-y-6">
          <div class="col-span-12">
            <label class="{{ $label }}">Alasan Magang</label>
            <textarea name="internship_reason" rows="4"
                      class="{{ $input }} @error('internship_reason') border-red-500 focus:ring-red-500 @enderror">{{ old('internship_reason', $intern->internship_reason) }}</textarea>
            @error('internship_reason') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-4">
            <label class="{{ $label }}">Tipe Magang</label>
            <input type="text" name="internship_type" value="{{ old('internship_type', $intern->internship_type) }}"
                   class="{{ $input }} @error('internship_type') border-red-500 focus:ring-red-500 @enderror" required>
            @error('internship_type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-4">
            <label class="{{ $label }}">Skema / Arrangement</label>
            <input type="text" name="internship_arrangement" value="{{ old('internship_arrangement', $intern->internship_arrangement) }}"
                   class="{{ $input }} @error('internship_arrangement') border-red-500 focus:ring-red-500 @enderror" required>
            @error('internship_arrangement') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-4">
            <label class="{{ $label }}">Status Saat Ini</label>
            <input type="text" name="current_status" value="{{ old('current_status', $intern->current_status) }}"
                   class="{{ $input }} @error('current_status') border-red-500 focus:ring-red-500 @enderror" required>
            @error('current_status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-6">
            <label class="{{ $label }}">Kemampuan Buku Inggris</label>
            <input type="text" name="english_book_ability" value="{{ old('english_book_ability', $intern->english_book_ability) }}"
                   class="{{ $input }} @error('english_book_ability') border-red-500 focus:ring-red-500 @enderror" required>
            @error('english_book_ability') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-6">
            <label class="{{ $label }}">Kontak Dosen Pembimbing</label>
            <input type="text" name="supervisor_contact" value="{{ old('supervisor_contact', $intern->supervisor_contact) }}"
                   class="{{ $input }} @error('supervisor_contact') border-red-500 focus:ring-red-500 @enderror">
            @error('supervisor_contact') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-6">
            <label class="{{ $label }}">Minat Divisi</label>
            <input type="text" name="internship_interest" value="{{ old('internship_interest', $intern->internship_interest) }}"
                   class="{{ $input }} @error('internship_interest') border-red-500 focus:ring-red-500 @enderror" required>
            @error('internship_interest') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6 lg:col-span-6">
            <label class="{{ $label }}">Minat (Lainnya)</label>
            <input type="text" name="internship_interest_other" value="{{ old('internship_interest_other', $intern->internship_interest_other) }}"
                   class="{{ $input }} @error('internship_interest_other') border-red-500 focus:ring-red-500 @enderror">
            @error('internship_interest_other') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>
      </section>

      {{-- Berkas --}}
      <section class="{{ $card }}">
        <div class="flex items-center gap-2 mb-6">
          <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">📎</span>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Berkas</h3>
        </div>

        <div class="grid grid-cols-12 gap-x-6 gap-y-6">
          <div class="col-span-12 md:col-span-6">
            <label class="{{ $label }}">CV/KTP/Portofolio (PDF)</label>
            @if ($intern->cv_ktp_portofolio_pdf)
              <div class="text-sm mb-2">
                File saat ini:
                <a class="text-emerald-700 dark:text-emerald-300 underline"
                   href="{{ Storage::disk('public')->url($intern->cv_ktp_portofolio_pdf) }}" target="_blank">
                  {{ basename($intern->cv_ktp_portofolio_pdf) }}
                </a>
              </div>
            @endif
            <label class="group flex items-center justify-center w-full rounded-xl border border-dashed
                          border-gray-300 dark:border-gray-700 bg-white/90 dark:bg-gray-800/80 px-3 py-6 text-sm
                          shadow-sm cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/70 transition">
              <input type="file" name="cv_ktp_portofolio_pdf" accept="application/pdf" class="hidden">
              <span class="group-hover:underline">Unggah PDF (maks 10MB)</span>
            </label>
            @error('cv_ktp_portofolio_pdf') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="col-span-12 md:col-span-6">
            <label class="{{ $label }}">Portofolio Visual (JPG/PNG/PDF)</label>
            @if ($intern->portofolio_visual)
              <div class="text-sm mb-2">
                File saat ini:
                <a class="text-emerald-700 dark:text-emerald-300 underline"
                   href="{{ Storage::disk('public')->url($intern->portofolio_visual) }}" target="_blank">
                  {{ basename($intern->portofolio_visual) }}
                </a>
              </div>
            @endif
            <label class="group flex items-center justify-center w-full rounded-xl border border-dashed
                          border-gray-300 dark:border-gray-700 bg-white/90 dark:bg-gray-800/80 px-3 py-6 text-sm
                          shadow-sm cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/70 transition">
              <input type="file" name="portofolio_visual" accept=".jpg,.jpeg,.png,application/pdf" class="hidden">
              <span class="group-hover:underline">Unggah JPG/PNG/PDF (maks 10MB)</span>
            </label>
            @error('portofolio_visual') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>
      </section>

      {{-- Aksi --}}
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
        <button type="submit"
                class="inline-flex justify-center items-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow">
          💾 Simpan Perubahan
        </button>
        <a href="{{ route('user.dashboard') }}"
           class="inline-flex justify-center items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60 shadow">
          Batal
        </a>
      </div>
    </form>
    @endif
  </div>
</div>
@endsection
