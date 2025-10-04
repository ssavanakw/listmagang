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
        Lengkapi data di bawah untuk membuat sertifikat.
      </p>
    </header>

    <!-- Error Messages -->
    @if ($errors->any())
      <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
        <ul class="list-disc list-inside space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Form for creating a new certificate -->
    <div class="rounded-2xl bg-white p-6 shadow ring-1 ring-gray-200/60 dark:bg-gray-800 dark:ring-gray-700 sm:p-8">
      <form method="POST" action="{{ route('admin.certificate.store') }}" enctype="multipart/form-data" id="certificateForm">
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
                <div class="mt-1 relative">
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
                </div>
                @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Selesai</label>
                <div class="mt-1 relative">
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
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
                <select id="brand" name="brand" required
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                    <option value="">Pilih Brand</option>
                    @foreach ($brands as $key => $brand)
                        <option value="{{ $key }}" {{ old('brand') == $key ? 'selected' : '' }}>{{ $brand }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih brand sesuai dengan daftar yang tersedia.</p>
                @error('brand') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="serial_number" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Nomor Seri Sertifikat
                </label>
                <input type="text" id="serial_number" name="serial_number"
                        value="{{ old('serial_number') }}"
                        readonly
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-gray-900 shadow-sm
                                focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500
                                dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"/>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Otomatis mengikuti format: NNN/SERT/DIV/COMPANY.BRAND/ROMAWI/TAHUN. <br>
                    <span class="italic">Catatan:</span> Angka NNN hanya pratinjau (<code>000</code>). Nomor asli dihasilkan saat penyimpanan.
                </p>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dropdown Logo 1 -->
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

                <!-- Dropdown Logo 2 -->
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


        <!-- Penandatangan -->
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

            <!-- Dropdown Signature Image 2 -->
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
            <i class="fa-regular"></i>
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
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    const elDivision = document.getElementById('division');   // dropdown berisi kode divisi (ex: ADM, PROG)
    const elCompany  = document.getElementById('company');    // text company
    const elBrand    = document.getElementById('brand');      // dropdown berisi kode brand (ex: MJ, AK)
    const elEndDate  = document.getElementById('end_date');   // tanggal selesai
    const elSerial   = document.getElementById('serial_number');

    // Peta bulan ke romawi
    const roman = ['', 'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];

    // Normalisasi kode perusahaan: buang PT/CV/CO/LTD/INC/TBK/PERSERO, ambil kata pertama, uppercase alnum
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
      const m = d.getMonth() + 1; // 1..12
      return roman[m] || '';
    }

    function yearFrom(dateStr){
      if (!dateStr) return '';
      const d = new Date(dateStr);
      return isNaN(d.getTime()) ? '' : d.getFullYear();
    }

    function buildSerialPreview(){
      const divCode = (elDivision?.value || '').toUpperCase().trim(); // contoh: PROG
      const comp    = companyCode(elCompany?.value || '');            // contoh: SEVEN
      const brand   = (elBrand?.value || '').toUpperCase().trim();    // contoh: MJ
      const romawi  = monthToRoman(elEndDate?.value || '');
      const tahun   = yearFrom(elEndDate?.value || '');

      // Placeholder untuk nomor urut (server akan generate sebenarnya)
      const runDemo = '000';

      // Format: NNN/SERT/DIV/COMPANY.BRAND/ROMAWI/TAHUN
      const parts = [
        runDemo, 'SERT',
        divCode || 'DIV',
        (comp || 'COMP') + (brand ? '.' + brand : ''),
        romawi || 'I',
        tahun  || new Date().getFullYear()
      ];
      elSerial.value = parts.join('/');
    }

    // Event listeners
    ['change','input'].forEach(evt => {
      elDivision?.addEventListener(evt, buildSerialPreview);
      elCompany?.addEventListener(evt, buildSerialPreview);
      elBrand?.addEventListener(evt, buildSerialPreview);
      elEndDate?.addEventListener(evt, buildSerialPreview);
    });

    // Inisialisasi pertama
    buildSerialPreview();

    // Set the minimum date for end_date to be the same as start_date
    startDateInput.addEventListener('change', function () {
      const startDate = new Date(startDateInput.value);
      endDateInput.min = startDate.toISOString().split('T')[0]; // Format as yyyy-mm-dd
    });

    // Initialize the min date for end_date when the page loads
    if (startDateInput.value) {
      const startDate = new Date(startDateInput.value);
      endDateInput.min = startDate.toISOString().split('T')[0];
    }
  });
</script>
@endpush