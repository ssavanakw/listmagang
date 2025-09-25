@extends('layouts.dashboard')

@section('content')

<div class="px-4 pt-6 lg:px-8">
  <div class="max-w-6xl mx-auto">
    <header class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-white flex items-center gap-3">
        <i class="fa-solid fa-award text-blue-600"></i>
        Generator Sertifikat
      </h1>
      <p class="text-gray-600 dark:text-gray-300 mt-1">
        Lengkapi data di bawah untuk membuat pratinjau sertifikat.
      </p>
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
      <form method="POST" action="{{ route('certificate.generatePreview') }}" enctype="multipart/form-data" id="certificateForm">
        @csrf

        <!-- Data Pribadi -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-user text-blue-600"></i>
            Data Pribadi
          </h2>
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama</label>
              <input type="text" id="name" name="name" value="{{ old('name') }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sesuai ejaan yang benar</p>
              @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="division" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Divisi</label>
              <input type="text" id="division" name="division" value="{{ old('division') }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('division') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Perusahaan</label>
              <input type="text" id="company" name="company" value="{{ old('company') }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('company') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="background_image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Upload Background Image</label>
              <input type="file" id="background_image" name="background_image" accept="image/*"
                     class="mt-1 block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 file:mr-4 file:rounded-l-md file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:file:bg-gray-700 dark:file:text-gray-200"/>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG/PNG direkomendasikan. Maks 2MB.</p>
              @error('background_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
              <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Mulai</label>
              <div class="mt-1 relative">
                <input type="text" id="start_date" name="start_date" value="{{ old('start_date') }}" autocomplete="off" required
                      class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                  <i class="fa-regular fa-calendar"></i>
                </span>
              </div>
              @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
              <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Selesai</label>
              <div class="mt-1 relative">
                <input type="text" id="end_date" name="end_date" value="{{ old('end_date') }}" autocomplete="off" required
                      class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                  <i class="fa-regular fa-calendar-check"></i>
                </span>
              </div>
              @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-1">
              <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Kota</label>
              <input type="text" id="city" name="city" value="{{ old('city') }}" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-1">
              <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Brand <span class="text-xs text-gray-500">(mis. MJ)</span></label>
              <input type="text" id="brand" name="brand" value="{{ old('brand') }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Gunakan singkatan huruf/angka, uppercase disarankan.</p>
              @error('brand') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
              <label for="serial_number" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nomor Seri Sertifikat</label>
              <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" readonly
              class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Otomatis mengikuti format: NNN/SERT/DIV/COMP.BRAND/ROMAWI/TAHUN.</p>
              @error('serial_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        <!-- Logo -->
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <i class="fa-regular fa-images text-blue-600"></i>
            Logo
          </h2>
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Logo 1 (Kiri Atas)</label>
              <input type="file" id="logo1" name="logo1" accept="image/*" required
                     class="mt-1 block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 file:mr-4 file:rounded-l-md file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:file:bg-gray-700 dark:file:text-gray-200"/>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG transparan direkomendasikan. Maks 2MB.</p>
              <img id="preview_logo1" class="hidden mt-2 max-h-16 rounded" alt="Preview Logo 1">
              @error('logo1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Logo 2 (Kanan Atas)</label>
              <input type="file" id="logo2" name="logo2" accept="image/*"
                     class="mt-1 block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 file:mr-4 file:rounded-l-md file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:file:bg-gray-700 dark:file:text-gray-200"/>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG transparan direkomendasikan. Maks 2MB.</p>
              <img id="preview_logo2" class="hidden mt-2 max-h-16 rounded" alt="Preview Logo 2">
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
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <label for="name_signatory1" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Penandatangan 1</label>
              <input type="text" id="name_signatory1" name="name_signatory1" value="{{ old('name_signatory1') }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('name_signatory1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label for="name_signatory2" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Penandatangan 2</label>
              <input type="text" id="name_signatory2" name="name_signatory2" value="{{ old('name_signatory2') }}"

                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('name_signatory2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label for="role1" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Jabatan Penandatangan 1</label>
              <input type="text" id="role1" name="role1" value="{{ old('role1') }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('role1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label for="role2" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Jabatan Penandatangan 2</label>
              <input type="text" id="role2" name="role2" value="{{ old('role2') }}"
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('role2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanda Tangan 1</label>
              <input type="file" id="signature_image1" name="signature_image1" accept="image/*" required
                     class="mt-1 block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 file:mr-4 file:rounded-l-md file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:file:bg-gray-700 dark:file:text-gray-200"/>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG transparan direkomendasikan. Maks 2MB.</p>
              <img id="preview_signature1" class="hidden mt-2 max-h-16 rounded" alt="Preview TTD 1">
              @error('signature_image1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanda Tangan 2</label>
              <input type="file" id="signature_image2" name="signature_image2" accept="image/*"
                     class="mt-1 block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 file:mr-4 file:rounded-l-md file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:file:bg-gray-700 dark:file:text-gray-200"/>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG transparan direkomendasikan. Maks 2MB.</p>
              <img id="preview_signature2" class="hidden mt-2 max-h-16 rounded" alt="Preview TTD 2">
              @error('signature_image2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-3">
          <button type="submit" id="submitBtn"
                  class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-600 to-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow hover:from-indigo-700 hover:to-sky-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
            <i class="fa-regular fa-eye mr-2"></i>
            Pratinjau Sertifikat
          </button>

          <button type="button" id="downloadBtn" value="serial_number"
                  class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-600 to-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow hover:from-indigo-700 hover:to-sky-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
            <i class="fa-regular fa-download mr-2"></i>
            Unduh PDF Sertifikat
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
// Datepicker init (Flowbite) + fallback ke input[type=date]
document.addEventListener('DOMContentLoaded', function () {
  const startEl = document.getElementById('start_date');
  const endEl   = document.getElementById('end_date');

  const LS = { start: 'cert.start_date', end: 'cert.end_date' };

  const persistDates = () => {
    try {
      localStorage.setItem(LS.start, startEl.value || '');
      localStorage.setItem(LS.end, endEl.value || '');
    } catch (e) { /* ignore quota errors */ }
  };

  const restoreDates = () => {
    try {
      const s = localStorage.getItem(LS.start);
      const e = localStorage.getItem(LS.end);
      if (!startEl.value && s) startEl.value = s;
      if (!endEl.value && e)   endEl.value   = e;
    } catch (e) { /* ignore */ }
  };

  let startPicker = null;
  let endPicker   = null;

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
  restoreDates();

  const normalize = (v) => v ? new Date(v) : null;
  startEl.addEventListener('change', () => {
    const start = normalize(startEl.value);
    if (!start) { persistDates(); return;}
    if (endPicker && typeof endPicker.setOptions === 'function') {
      endPicker.setOptions({ minDate: start });
      const endVal = normalize(endEl.value);
      if (endVal && endVal < start) endPicker.setDate(start);
    } else {
      endEl.min = startEl.value;
      if (endEl.value && new Date(endEl.value) < start) endEl.value = startEl.value;
    }
    persistDates();
  });
  ['input', 'change'].forEach(evt => endEl.addEventListener(evt, persistDates));

  // ==== Preview Serial Number (client-side, non-final) ====
  function monthToRoman(dateStr){
    if(!dateStr) return '';
    const m = new Date(dateStr).getMonth()+1;
    const map = ['','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
    return map[m] || '';
  }
  function mapDivisionCode(txt){
    if(!txt) return '';
    const exact = {
      'Pemrogramman FrontEnd dan BackEnd': 'PROG',
    };
    const key = txt.trim();
    if (exact[key]) return exact[key];
    // fallback: ambil huruf awal tiap kata, maks 6
    const abbr = key.split(/\s+/).map(w=>w[0]||'').join('');
    return (abbr || key).toUpperCase().replace(/[^A-Z0-9]/g,'').slice(0,6);
  }
  function mapCompanyCode(txt){
    if(!txt) return '';
    let t = (txt||'').toUpperCase();
    t = t.replace(/\b(PT|CV|CO\.?|LTD\.?|INC\.?|TBK|PERSERO)\b\.?/gi,'').trim();
    t = (t.split(/\s+/)[0]||t).replace(/[^A-Z0-9]/g,'');
    return t || 'COMP';
  }
  function buildSerialPreview(){
    const division = document.getElementById('division')?.value || '';
    const company  = document.getElementById('company')?.value  || '';
    const brand    = document.getElementById('brand')?.value    || '';
    const endDate  = document.getElementById('end_date')?.value || '';

    const divCode  = mapDivisionCode(division);      // PROG
    const compCode = mapCompanyCode(company);        // SEVEN
    const brandUp  = (brand||'').toUpperCase().replace(/[^A-Z0-9]/g,''); // MJ
    const romawi   = monthToRoman(endDate);          // VIII
    const tahun    = endDate ? new Date(endDate).getFullYear() : ''; // 2025

    const runDemo  = '000'; // preview; nomor asli akan diisi server
    const parts = [
      runDemo, 'SERT', divCode,
      compCode + (brandUp ? '.'+brandUp : ''),
      romawi, tahun
    ].filter(Boolean);

    const serial = parts.join('/');
    const el = document.getElementById('serial_number');
    if (el) el.value = serial;
  }
  ['division','company','brand','end_date'].forEach(id=>{
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('input', buildSerialPreview);
      el.addEventListener('change', buildSerialPreview);
    }
  });
  document.addEventListener('DOMContentLoaded', buildSerialPreview);

  // ==== Preview image + size/type validation ====
  function bindPreview(inputId, imgId) {
    const input = document.getElementById(inputId);
    const img   = document.getElementById(imgId);
    if (!input || !img) return;
    input.addEventListener('change', (e) => {
      const file = e.target.files?.[0];
      if (!file) return;
      if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar.');
        input.value = '';
        return;
      }
      if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran gambar maksimum 2MB.');
        input.value = '';
        return;
      }
      img.src = URL.createObjectURL(file);
      img.classList.remove('hidden');
    });
  }
  bindPreview('logo1', 'preview_logo1');
  bindPreview('logo2', 'preview_logo2');
  bindPreview('signature_image1', 'preview_signature1');
  bindPreview('signature_image2', 'preview_signature2');

  // Loading state
  const form = document.getElementById('certificateForm');
  const submitBtn = document.getElementById('submitBtn');
  form.addEventListener('submit', () => {
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
    submitBtn.innerHTML = `
      <svg class="mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
      </svg>
      Memproses...
    `;
  });
});
</script>
@endpush
