@extends('layouts.dashboard')

@section('content')
@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;

  // Helper kecil untuk selected()
  function sel($a, $b){ return (string)$a === (string)$b ? 'selected' : ''; }

  // URL helper aman
  $bgUrl   = $certificate->background_image ? Storage::url('public/'.$certificate->background_image) : '';
  $logo1U  = $certificate->logo1 ? Storage::url('public/'.$certificate->logo1) : '';
  $logo2U  = $certificate->logo2 ? Storage::url('public/'.$certificate->logo2) : '';
  $ttd1U   = $certificate->signature_image1 ? Storage::url('public/'.$certificate->signature_image1) : '';
  $ttd2U   = $certificate->signature_image2 ? Storage::url('public/'.$certificate->signature_image2) : '';
@endphp

<div class="px-4 pt-6 lg:px-8">
  <div class="max-w-6xl mx-auto">
    <header class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white flex items-center gap-3">
          <i class="fa-solid fa-award text-blue-600"></i>
          Edit Sertifikat
        </h1>
        <p class="text-gray-600 dark:text-gray-300 mt-1">
          Perbarui data sertifikat yang sudah dibuat.
        </p>
      </div>
      <a href="{{ route('certificate.index') }}"
         class="inline-flex items-center rounded-lg bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
        <i class="fa-regular fa-circle-left mr-2"></i> Kembali
      </a>
    </header>

    @if ($errors->any())
      <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
        <ul class="list-disc list-inside space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="rounded-2xl bg-white p-6 shadow ring-1 ring-gray-200/60 dark:bg-gray-800 dark:ring-gray-700 sm:p-8">
      <form method="POST" action="{{ route('certificate.update', $certificate->id) }}" id="certificateForm">
        @csrf
        @method('PUT')

        <!-- Data Pribadi -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-user text-blue-600"></i>
            Data Pribadi
          </h2>
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama</label>
              <input type="text" id="name" name="name"
                     value="{{ old('name', $certificate->name) }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="division" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Divisi</label>
              <select id="division" name="division" required
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Divisi</option>
                @foreach($divisions as $code => $label)
                  <option value="{{ $code }}" {{ sel(old('division', $certificate->division), $code) }}>
                    {{ $label }} ({{ $code }})
                  </option>
                @endforeach
              </select>
              @error('division') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Perusahaan</label>
              <input type="text" id="company" name="company" value="{{ old('company', $certificate->company) }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('company') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Kota</label>
              <input type="text" id="city" name="city" value="{{ old('city', $certificate->city) }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Brand</label>
              <select id="brand" name="brand" required
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Brand</option>
                @foreach($brands as $code => $label)
                  <option value="{{ $code }}" {{ sel(old('brand', $certificate->brand), $code) }}>
                    {{ $label }} ({{ $code }})
                  </option>
                @endforeach
              </select>
              @error('brand') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
              <label for="serial_number" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nomor Seri Sertifikat</label>
              <input type="text" id="serial_number" name="serial_number"
                     value="{{ old('serial_number', $certificate->serial_number) }}" readonly
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nomor seri dihasilkan otomatis saat pembuatan. (Readonly)</p>
              @error('serial_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        <!-- Periode -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <i class="fa-regular fa-calendar text-blue-600"></i>
            Periode Magang
          </h2>
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Mulai</label>
              <div class="relative mt-1">
                <input id="start_date" name="start_date" type="text" autocomplete="off"
                       value="{{ old('start_date', \Carbon\Carbon::parse($certificate->start_date)->format('Y-m-d')) }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                  <i class="fa-regular fa-calendar"></i>
                </span>
              </div>
              @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Selesai</label>
              <div class="relative mt-1">
                <input id="end_date" name="end_date" type="text" autocomplete="off"
                       value="{{ old('end_date', \Carbon\Carbon::parse($certificate->end_date)->format('Y-m-d')) }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                  <i class="fa-regular fa-calendar-check"></i>
                </span>
              </div>
              @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        <!-- Background & Logo -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <i class="fa-regular fa-images text-blue-600"></i>
            Background & Logo
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Background -->
            <div>
              <label for="background_image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Background</label>
              <select name="background_image" id="background_image" required
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Background (prefiks bg_)</option>
                @foreach ($backgroundFiles as $file)
                  @if(Str::startsWith($file, 'bg_'))
                    <option value="{{ $file }}" {{ sel(old('background_image', basename($certificate->background_image)), $file) }}>
                      {{ $file }}
                    </option>
                  @endif
                @endforeach
              </select>
              <p class="mt-1 text-xs text-gray-500">File harus diawali <b>bg_</b></p>
              <img id="preview_bg" src="{{ $bgUrl }}" class="mt-2 max-h-24 rounded {{ $bgUrl ? '' : 'hidden' }}" alt="Preview Background">
              @error('background_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Logo 1 -->
            <div>
              <label for="logo1" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Logo 1</label>
              <select name="logo1" id="logo1" required
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Logo 1 (prefiks logo_)</option>
                @foreach ($logoFiles as $file)
                  @if(Str::startsWith($file, 'logo_'))
                    <option value="{{ $file }}" {{ sel(old('logo1', basename($certificate->logo1)), $file) }}>
                      {{ $file }}
                    </option>
                  @endif
                @endforeach
              </select>
              <p class="mt-1 text-xs text-gray-500">File harus diawali <b>logo_</b></p>
              <img id="preview_logo1" src="{{ $logo1U }}" class="mt-2 max-h-20 rounded {{ $logo1U ? '' : 'hidden' }}" alt="Preview Logo 1">
              @error('logo1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Logo 2 -->
            <div>
              <label for="logo2" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Logo 2 (Opsional)</label>
              <select name="logo2" id="logo2"
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">- Tanpa Logo 2 -</option>
                @foreach ($logoFiles as $file)
                  @if(Str::startsWith($file, 'logo_'))
                    <option value="{{ $file }}" {{ sel(old('logo2', basename($certificate->logo2)), $file) }}>
                      {{ $file }}
                    </option>
                  @endif
                @endforeach
              </select>
              <img id="preview_logo2" src="{{ $logo2U }}" class="mt-2 max-h-20 rounded {{ $logo2U ? '' : 'hidden' }}" alt="Preview Logo 2">
              @error('logo2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        <!-- Penandatangan -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-pen-fancy text-blue-600"></i>
            Penandatangan
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="name_signatory1" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Penandatangan 1</label>
              <input type="text" id="name_signatory1" name="name_signatory1" value="{{ old('name_signatory1', $certificate->name_signatory1) }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('name_signatory1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label for="name_signatory2" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Penandatangan 2 (Opsional)</label>
              <input type="text" id="name_signatory2" name="name_signatory2" value="{{ old('name_signatory2', $certificate->name_signatory2) }}"
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('name_signatory2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="role1" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Jabatan Penandatangan 1</label>
              <input type="text" id="role1" name="role1" value="{{ old('role1', $certificate->role1) }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('role1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label for="role2" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Jabatan Penandatangan 2 (Opsional)</label>
              <input type="text" id="role2" name="role2" value="{{ old('role2', $certificate->role2) }}"
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('role2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="signature_image1" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanda Tangan 1</label>
              <select name="signature_image1" id="signature_image1" required
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih TTD 1 (prefiks ttd_)</option>
                @foreach ($signatureFiles as $file)
                  @if(Str::startsWith($file, 'ttd_'))
                    <option value="{{ $file }}" {{ sel(old('signature_image1', basename($certificate->signature_image1)), $file) }}>
                      {{ $file }}
                    </option>
                  @endif
                @endforeach
              </select>
              <img id="preview_ttd1" src="{{ $ttd1U }}" class="mt-2 max-h-20 rounded {{ $ttd1U ? '' : 'hidden' }}" alt="Preview TTD 1">
              @error('signature_image1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="signature_image2" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanda Tangan 2 (Opsional)</label>
              <select name="signature_image2" id="signature_image2"
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">- Tanpa TTD 2 -</option>
                @foreach ($signatureFiles as $file)
                  @if(Str::startsWith($file, 'ttd_'))
                    <option value="{{ $file }}" {{ sel(old('signature_image2', basename($certificate->signature_image2)), $file) }}>
                      {{ $file }}
                    </option>
                  @endif
                @endforeach
              </select>
              <img id="preview_ttd2" src="{{ $ttd2U }}" class="mt-2 max-h-20 rounded {{ $ttd2U ? '' : 'hidden' }}" alt="Preview TTD 2">
              @error('signature_image2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-3">
          <button type="submit"
                  class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-600 to-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow hover:from-indigo-700 hover:to-sky-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="fa-regular fa-floppy-disk mr-2"></i>
            Simpan Perubahan
          </button>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Flowbite Datepicker init (fallback ke type=date bila tidak ada)
  (function(){
    const startEl = document.getElementById('start_date');
    const endEl   = document.getElementById('end_date');

    let startPicker = null, endPicker = null;
    const initDatepicker = () => {
      if (typeof window.Datepicker === 'function') {
        const opts = { autohide: true, format: 'yyyy-mm-dd' };
        startPicker = new Datepicker(startEl, opts);
        endPicker   = new Datepicker(endEl, opts);
      } else {
        startEl.type = 'date';
        endEl.type   = 'date';
      }
    };
    initDatepicker();

    const normalize = v => v ? new Date(v) : null;
    const applyMinEnd = () => {
      const s = normalize(startEl.value);
      if (!s) return;
      if (endPicker && typeof endPicker.setOptions === 'function') {
        endPicker.setOptions({ minDate: s });
        const e = normalize(endEl.value);
        if (e && e < s) endPicker.setDate(s);
      } else {
        endEl.min = startEl.value;
        if (endEl.value && new Date(endEl.value) < s) endEl.value = startEl.value;
      }
    };
    startEl.addEventListener('change', applyMinEnd);
    // set saat load
    applyMinEnd();
  })();

  // Preview images for dropdown selections (mengambil via /storage/public/...)
  function storageUrl(subpath){
    // Laravel Storage::url('public/...') biasanya -> /storage/...
    // Di server kamu sesuaikan kalau berbeda.
    return '/storage/public/' + subpath.replace(/^public\//,'');
  }

  function bindPreviewSelect(selectId, imgId, baseDir){
    const sel = document.getElementById(selectId);
    const img = document.getElementById(imgId);
    if(!sel || !img) return;
    sel.addEventListener('change', () => {
      const val = sel.value;
      if(!val){
        img.classList.add('hidden');
        img.src = '';
        return;
      }
      img.src = '/storage/' + baseDir + '/' + val;
      img.classList.remove('hidden');
    });
  }
  bindPreviewSelect('background_image','preview_bg','images/backgrounds');
  bindPreviewSelect('logo1','preview_logo1','images/logos');
  bindPreviewSelect('logo2','preview_logo2','images/logos');
  bindPreviewSelect('signature_image1','preview_ttd1','images/signature');
  bindPreviewSelect('signature_image2','preview_ttd2','images/signature');
</script>
@endpush
