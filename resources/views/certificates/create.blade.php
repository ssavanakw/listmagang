@extends('layouts.dashboard')

@section('content')

<div class="px-4 pt-6 lg:px-8">
  <div class="w-full mx-auto px-6 lg:px-10">
    <header class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-white flex items-center gap-3">
        <i class="fa-solid fa-award text-blue-600"></i>
        Generator Sertifikat
      </h1>
      <p class="text-gray-600 dark:text-gray-300 mt-1">
        Lengkapi data di bawah untuk membuat sertifikat.
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
      <form method="POST" action="{{ route('admin.certificate.store') }}" enctype="multipart/form-data" id="certificateForm">
        @csrf

        {{-- =============================== --}}
        {{-- Search Intern (Typeahead)      --}}
        {{-- =============================== --}}
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-user-graduate text-blue-600"></i>
            Ambil dari Intern (Auto-Fill + Search)
          </h2>

          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="relative">
              <label for="intern_search" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Cari Intern (nama/email/institusi/NIM/telepon)
              </label>
              <input type="text" id="intern_search" autocomplete="off"
                     placeholder="Ketik minimal 2 huruf..."
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              <input type="hidden" id="intern_id" value="">

              <!-- Dropdown hasil -->
              <div id="intern_results"
                   class="absolute z-20 mt-1 max-h-72 w-full overflow-auto rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900 hidden">
                <!-- item hasil akan di-render via JS -->
              </div>

              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Pilih salah satu hasil untuk mengisi otomatis Nama, Divisi (dari interest), Tanggal, dan Kota.
              </p>
            </div>

            <div>
              <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                  <div><span class="font-medium">Institusi:</span> <span id="preview_institution">—</span></div>
                  <div><span class="font-medium">Interest:</span> <span id="preview_interest">—</span></div>
                  <div><span class="font-medium">Periode:</span> <span id="preview_period">—</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- =============================== --}}
        {{-- Data Pribadi                    --}}
        {{-- =============================== --}}
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
              @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="division" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Divisi</label>
              <select id="division" name="division" required
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Divisi</option>
                @foreach ($divisions as $key => $division)
                  <option value="{{ $key }}" {{ old('division') == $key ? 'selected' : '' }}>{{ $division }}</option>
                @endforeach
              </select>
              @error('division') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Perusahaan</label>
              <input type="text" id="company" name="company" value="{{ old('company') }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('company') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="background_image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Background</label>
              <select name="background_image" id="background_image" required
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Background</option>
                @foreach ($backgroundFiles as $file)
                  @if (Str::startsWith($file, 'bg_'))
                    <option value="{{ $file }}">{{ $file }}</option>
                  @endif
                @endforeach
              </select>
              @error('background_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
              <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Mulai</label>
              <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
              <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Selesai</label>
              <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Kota</label>
              <input type="text" id="city" name="city" value="{{ old('city') }}" required
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Brand <span class="text-xs text-gray-500">(mis. MJ)</span></label>
              <select id="brand" name="brand" required
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Brand</option>
                @foreach ($brands as $key => $brand)
                  <option value="{{ $key }}" {{ old('brand') == $key ? 'selected' : '' }}>{{ $brand }}</option>
                @endforeach
              </select>
              @error('brand') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
              <label for="serial_number" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nomor Seri Sertifikat</label>
              <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" readonly
                     class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Otomatis mengikuti format: NNN/SERT/DIV/COMPANY.BRAND/ROMAWI/TAHUN. Angka NNN hanya pratinjau (<code>000</code>).
              </p>
              @error('serial_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        {{-- =============================== --}}
        {{-- Logo                            --}}
        {{-- =============================== --}}
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <i class="fa-regular fa-images text-blue-600"></i>
            Logo
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="logo1" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Logo 1</label>
              <select name="logo1" id="logo1" required
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Logo 1</option>
                @foreach ($logoFiles as $file)
                  @if (Str::startsWith($file, 'logo_'))
                    <option value="{{ $file }}">{{ $file }}</option>
                  @endif
                @endforeach
              </select>
              @error('logo1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="logo2" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Logo 2</label>
              <select name="logo2" id="logo2"
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Logo 2</option>
                @foreach ($logoFiles as $file)
                  @if (Str::startsWith($file, 'logo_'))
                    <option value="{{ $file }}">{{ $file }}</option>
                  @endif
                @endforeach
              </select>
              @error('logo2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        {{-- =============================== --}}
        {{-- Penandatangan                   --}}
        {{-- =============================== --}}
        <div class="mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-pen-fancy text-blue-600"></i>
            Penandatangan
          </h2>
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <label for="name_signatory1" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Penandatangan 1</label>
              <input type="text" id="name_signatory1" name="name_signatory1" value="{{ old('name_signatory1') }}"
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
              <label for="signature_image1" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Tanda Tangan 1</label>
              <select name="signature_image1" id="signature_image1" required
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Tanda Tangan 1</option>
                @foreach ($signatureFiles as $file)
                  @if (Str::startsWith($file, 'ttd_'))
                    <option value="{{ $file }}">{{ $file }}</option>
                  @endif
                @endforeach
              </select>
              @error('signature_image1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label for="signature_image2" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Tanda Tangan 2</label>
              <select name="signature_image2" id="signature_image2"
                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Pilih Tanda Tangan 2</option>
                @foreach ($signatureFiles as $file)
                  @if (Str::startsWith($file, 'ttd_'))
                    <option value="{{ $file }}">{{ $file }}</option>
                  @endif
                @endforeach
              </select>
              @error('signature_image2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-3">
          <a href="{{ route('admin.certificate.index') }}"
             class="inline-flex items-center rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-800 shadow hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali ke Daftar Sertifikat
          </a>

          <button type="submit" class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-600 to-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow hover:from-indigo-700 hover:to-sky-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // ====== Elemen Form ======
    const startDateInput = document.getElementById('start_date');
    const endDateInput   = document.getElementById('end_date');
    const elDivision     = document.getElementById('division');
    const elCompany      = document.getElementById('company');
    const elBrand        = document.getElementById('brand');
    const elEndDate      = document.getElementById('end_date');
    const elSerial       = document.getElementById('serial_number');

    // Search UI
    const SEARCH_URL   = "{{ route('admin.interns.search') }}";
    const DETAIL_URL   = "{{ route('admin.interns.api') }}"; // optional (dipakai jika ada detail by ?id=)
    const elSearch     = document.getElementById('intern_search');
    const elInternId   = document.getElementById('intern_id');
    const elResults    = document.getElementById('intern_results');

    // Preview box
    const prevInst   = document.getElementById('preview_institution');
    const prevInt    = document.getElementById('preview_interest');
    const prevPeriod = document.getElementById('preview_period');

    // ====== Helpers ======
    const roman = ['', 'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];

    function companyCode(raw) {
      if (!raw) return '';
      let t = (raw || '').toUpperCase();
      t = t.replace(/\b(PT|CV|CO\.?|LTD\.?|INC\.?|TBK|PERSERO)\b\.?/gi, ' ').trim();
      const first = (t.split(/\s+/)[0] || '').replace(/[^A-Z0-9]/g, '');
      return first || 'COMP';
    }

    function monthToRoman(dateStr){
      if (!dateStr) return '';
      const d = new Date(dateStr);
      if (isNaN(d.getTime())) return '';
      const m = d.getMonth() + 1;
      return roman[m] || '';
    }

    function yearFrom(dateStr){
      if (!dateStr) return '';
      const d = new Date(dateStr);
      return isNaN(d.getTime()) ? '' : d.getFullYear();
    }

    function buildSerialPreview(){
      const divCode = (elDivision?.value || '').toUpperCase().trim();
      const comp    = companyCode(elCompany?.value || '');
      const brand   = (elBrand?.value || '').toUpperCase().trim();
      const romawi  = monthToRoman(elEndDate?.value || '');
      const tahun   = yearFrom(elEndDate?.value || '');
      const runDemo = '000';
      elSerial.value = [runDemo, 'SERT', (divCode || 'DIV'), (comp || 'COMP') + (brand ? '.'+brand : ''), (romawi || 'I'), (tahun || new Date().getFullYear())].join('/');
    }

    function interestToDivision(interestRaw){
      if(!interestRaw) return null;
      const key = String(interestRaw).toLowerCase().replace(/\//g,'-').trim();
      const map = {
        'administration':'ADM','administrasi':'ADM',
        'uiux':'UIUX','ui-ux':'UIUX','ui/ux':'UIUX',
        'programmer':'PROG','programmer (front end / backend)':'PROG',
        'hr':'HR','human resources (hr)':'HR',
        'social-media-specialist':'SMM','spesialis media sosial':'SMM',
        'photographer':'PV','videographer':'PV','fotografer':'PV','videografer':'PV',
        'content-writer':'CW','penulis konten':'CW',
        'marketing-and-sales':'MS','penjualan & pemasaran':'MS','penjualan dan pemasaran':'MS',
        'graphic-designer':'CD','desainer grafis':'CD',
        'digital-marketing':'DM','pemasaran digital':'DM',
        'public-relation':'PR','public relations (marcomm)':'PR','hubungan masyarakat (marcomm)':'PR',
        'tiktok-creator':'TC','kreator tiktok':'TC',
        'content-planner':'CP','perencana konten':'CP',
        'project-manager':'PM','manajer proyek':'PM',
        'welding':'LAS','pengelasan':'LAS',
        'animation':'ANIM','animasi':'ANIM',
      };
      return map[key] ?? null;
    }

    // ====== Typeahead (AJAX) ======
    let debounceTimer = null;
    let activeIndex = -1;
    let currentItems = [];

    function hideResults() {
      elResults.classList.add('hidden');
      elResults.innerHTML = '';
      activeIndex = -1;
      currentItems = [];
    }

    function renderResults(items) {
      currentItems = items;
      activeIndex = -1;
      if (!items.length) {
        elResults.innerHTML = `<div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-300">Tidak ada hasil</div>`;
        elResults.classList.remove('hidden');
        return;
      }
      elResults.innerHTML = items.map((it, idx) => {
        // it.text sudah berisi "Nama (DIV)" dari controller
        return `
          <button type="button"
            data-index="${idx}"
            class="intern-item w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 focus:bg-gray-100 dark:focus:bg-gray-800">
            <div class="text-sm font-medium text-gray-800 dark:text-gray-100">${it.text}</div>
          </button>
        `;
      }).join('');
      elResults.classList.remove('hidden');
    }

    async function fetchDetailsAndFill(id, fallback) {
      try {
        const url = `${DETAIL_URL}?id=${encodeURIComponent(id)}`;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const json = await res.json();

        // Terima beberapa kemungkinan bentuk payload:
        // 1) { data: { ...fields } }
        // 2) { data: [ { ...fields } ] }
        // 3) { result: { ...fields } }  (fallback)
        const cand =
          (json && json.data && !Array.isArray(json.data) && json.data) ||
          (json && Array.isArray(json.data) && json.data[0]) ||
          (json && json.result) ||
          null;

        if (!cand) {
          // fallback minimal
          if (fallback) fallback();
          return;
        }

        // Ambil field aman
        const name = cand.fullname || cand.name || '';
        const start = cand.start_date || '';
        const end   = cand.end_date || '';
        const city  = cand.current_city || cand.city || '';
        const inst  = cand.institution_name || cand.institution || '';
        const interest = cand.internship_interest || cand.interest || '';

        // Fill preview
        prevInst.textContent   = inst || '—';
        prevInt.textContent    = interest || '—';
        prevPeriod.textContent = (start && end) ? `${start} s/d ${end}` : '—';

        // Fill form field
        const elName = document.getElementById('name');
        const elCity = document.getElementById('city');
        if (elName && name) elName.value = name;
        if (startDateInput && start) {
          startDateInput.value = start;
          endDateInput.min = start;
        }
        if (endDateInput && end) endDateInput.value = end;
        if (elCity && city) elCity.value = city;

        // Division dari interest (kalau division dari search tidak ada)
        if (!elDivision.value) {
          const code = interestToDivision(interest);
          if (code) {
            elDivision.value = code;
            elDivision.dispatchEvent(new Event('change', {bubbles:true}));
          }
        }

        buildSerialPreview();
      } catch (e) {
        console.error(e);
        if (fallback) fallback();
      }
    }

    function pickItem(item) {
      elInternId.value = item.id;

      // text contoh: "Budi Santoso (PROG)" → ambil nama tanpa "(..)"
      const nameOnly = String(item.text || '').replace(/\s*\([^)]+\)\s*$/, '').trim();
      const division = item.division || (item.text.match(/\(([^)]+)\)/)?.[1] || '').trim();

      // Set minimal (tanpa detail)
      const elName = document.getElementById('name');
      if (elName && nameOnly) elName.value = nameOnly;
      if (division) {
        elDivision.value = division;
        elDivision.dispatchEvent(new Event('change', {bubbles:true}));
      }

      // Reset preview sementara
      prevInst.textContent   = '—';
      prevInt.textContent    = division || '—';
      prevPeriod.textContent = '—';

      // Coba ambil detail; kalau gagal, pakai fallback: update serial saja
      fetchDetailsAndFill(item.id, () => { buildSerialPreview(); });

      elSearch.value = nameOnly;
      hideResults();
    }

    elSearch.addEventListener('input', function () {
      const term = this.value.trim();
      if (term.length < 2) {
        hideResults();
        return;
      }

      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(async () => {
        try {
          // default: hanya completed=true (sesuai controller-mu)
          const url = `${SEARCH_URL}?q=${encodeURIComponent(term)}&completed=1`;
          const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
          const json = await res.json();
          const items = (json && Array.isArray(json.results)) ? json.results : [];
          renderResults(items);
        } catch (e) {
          console.error(e);
          hideResults();
        }
      }, 250);
    });

    elResults.addEventListener('click', function (e) {
      const btn = e.target.closest('.intern-item');
      if (!btn) return;
      const idx = Number(btn.getAttribute('data-index'));
      const item = currentItems[idx];
      if (item) pickItem(item);
    });

    // Navigasi keyboard (↑ ↓ Enter Esc)
    elSearch.addEventListener('keydown', function (e) {
      if (elResults.classList.contains('hidden')) return;

      const max = currentItems.length - 1;

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        activeIndex = Math.min(max, activeIndex + 1);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        activeIndex = Math.max(0, activeIndex - 1);
      } else if (e.key === 'Enter') {
        if (activeIndex >= 0 && currentItems[activeIndex]) {
          e.preventDefault();
          pickItem(currentItems[activeIndex]);
        }
      } else if (e.key === 'Escape') {
        hideResults();
        return;
      } else {
        return;
      }

      const nodes = elResults.querySelectorAll('.intern-item');
      nodes.forEach((n, i) => {
        if (i === activeIndex) {
          n.classList.add('bg-gray-100', 'dark:bg-gray-800');
          n.scrollIntoView({ block: 'nearest' });
        } else {
          n.classList.remove('bg-gray-100', 'dark:bg-gray-800');
        }
      });
    });

    document.addEventListener('click', function (e) {
      if (!elResults.contains(e.target) && e.target !== elSearch) {
        hideResults();
      }
    });

    // ====== Serial Preview & Date Min ======
    ['change','input'].forEach(evt => {
      elDivision?.addEventListener(evt, buildSerialPreview);
      elCompany?.addEventListener(evt, buildSerialPreview);
      elBrand?.addEventListener(evt, buildSerialPreview);
      elEndDate?.addEventListener(evt, buildSerialPreview);
    });

    buildSerialPreview();

    startDateInput.addEventListener('change', function () {
      const startDate = new Date(startDateInput.value);
      if(!isNaN(startDate.getTime())){
        endDateInput.min = startDate.toISOString().split('T')[0];
        if (endDateInput.value && new Date(endDateInput.value) < startDate) {
          endDateInput.value = endDateInput.min;
        }
      }
      buildSerialPreview();
    });

    if (startDateInput.value) {
      const startDate = new Date(startDateInput.value);
      if(!isNaN(startDate.getTime())){
        endDateInput.min = startDate.toISOString().split('T')[0];
      }
    }
  });
</script>
@endpush
