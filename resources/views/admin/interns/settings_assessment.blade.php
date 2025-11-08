@extends('layouts.dashboard')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 p-6 bg-white rounded-lg shadow">

  {{-- === FORM BAGIAN KIRI === --}}
  <div class="w-full lg:w-1/2">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">⚙️ Pengaturan Identitas Perusahaan</h1>

    <div id="alertBox" class="hidden mb-4 p-3 rounded border text-sm"></div>

    <form id="settingsForm" enctype="multipart/form-data" class="space-y-6">
      @csrf

      {{-- NAMA DAN ALAMAT --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Perusahaan</label>
        <input type="text" name="company_name" value="{{ old('company_name', $company_name) }}" 
               class="live-input w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Perusahaan</label>
        <textarea name="company_address" rows="3"
                  class="live-input w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">{{ old('company_address', $company_address) }}</textarea>
      </div>

      {{-- LOGO PERUSAHAAN --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Logo Perusahaan</label>

        <div class="flex items-center gap-3">
          <select name="company_logo_select" id="company_logo_select"
                  class="live-input flex-1 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
            <option value="">Pilih dari daftar logo...</option>
            @foreach($logos as $logo)
              <option value="{{ $logo }}" {{ $company_logo_path == $logo ? 'selected' : '' }}>
                {{ basename($logo) }}
              </option>
            @endforeach
          </select>

          <img id="logoThumbnail" 
               src="{{ asset('storage/' . $company_logo_path) }}" 
               class="border rounded-md shadow-sm object-contain"
               style="width: {{ session('logo_width', 70) }}px; height: {{ session('logo_height', 70) }}px;">
        </div>

        {{-- ukuran logo --}}
        <div class="flex gap-3 mt-2">
          <div class="flex-1">
            <label class="text-xs text-gray-600">Lebar (px)</label>
            <input type="range" name="logo_width" id="logo_width" min="30" max="200" value="{{ session('logo_width', 70) }}" class="w-full live-input">
          </div>
          <div class="flex-1">
            <label class="text-xs text-gray-600">Tinggi (px)</label>
            <input type="range" name="logo_height" id="logo_height" min="30" max="200" value="{{ session('logo_height', 70) }}" class="w-full live-input">
          </div>
        </div>

        <input type="file" name="company_logo" accept="image/*"
               class="live-input block w-full mt-2 text-sm border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5">
        <input type="hidden" name="old_logo" value="{{ $company_logo_path }}">
      </div>

      <hr class="my-4">

      {{-- PENANDATANGAN --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Penandatangan</label>
        <input type="text" name="signature_name" value="{{ old('signature_name', $signature_name) }}" 
               class="live-input w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Jabatan Penandatangan</label>
        <input type="text" name="signature_position" value="{{ old('signature_position', $signature_position) }}" 
               class="live-input w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
      </div>

      {{-- GAMBAR TANDA TANGAN --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar Tanda Tangan</label>

        <div class="flex items-center gap-3">
          <select name="signature_image_select" id="signature_image_select"
                  class="live-input flex-1 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
            <option value="">Pilih dari daftar tanda tangan...</option>
            @foreach($signatures as $sig)
              <option value="{{ $sig }}" {{ $signature_image_path == $sig ? 'selected' : '' }}>
                {{ basename($sig) }}
              </option>
            @endforeach
          </select>

          <img id="signatureThumbnail" 
               src="{{ asset('storage/' . $signature_image_path) }}" 
               class="border rounded-md shadow-sm object-contain"
               style="width: {{ session('sig_width', 80) }}px; height: {{ session('sig_height', 80) }}px;">
        </div>

        {{-- ukuran tanda tangan --}}
        <div class="flex gap-3 mt-2">
          <div class="flex-1">
            <label class="text-xs text-gray-600">Lebar (px)</label>
            <input type="range" name="sig_width" id="sig_width" min="50" max="300" value="{{ session('sig_width', 80) }}" class="w-full live-input">
          </div>
          <div class="flex-1">
            <label class="text-xs text-gray-600">Tinggi (px)</label>
            <input type="range" name="sig_height" id="sig_height" min="50" max="300" value="{{ session('sig_height', 80) }}" class="w-full live-input">
          </div>
        </div>

        <input type="file" name="signature_image" accept="image/*"
               class="live-input block w-full mt-2 text-sm border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5">
        <input type="hidden" name="old_signature" value="{{ $signature_image_path }}">
      </div>
    </form>

    {{-- LOADING INDICATOR --}}
    <div id="loading" class="hidden mt-4 text-blue-600 text-sm flex items-center gap-2">
      <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
      </svg>
      Memperbarui preview...
    </div>
  </div>

  {{-- === PREVIEW PDF (KANAN) === --}}
  <div class="w-full lg:w-1/2">
    <h2 class="text-lg font-semibold text-gray-800 mb-2">📄 Live Preview PDF</h2>
    <iframe id="pdfPreview" src="{{ route('interns.assessment.settings.preview') }}"
            class="w-full h-[750px] border border-gray-300 rounded-lg shadow-sm"></iframe>
  </div>
</div>

{{-- SCRIPT --}}
<script>
const form = document.getElementById('settingsForm');
const inputs = document.querySelectorAll('.live-input');
const iframe = document.getElementById('pdfPreview');
const loader = document.getElementById('loading');
const logoSelect = document.getElementById('company_logo_select');
const logoThumb = document.getElementById('logoThumbnail');
const sigSelect = document.getElementById('signature_image_select');
const sigThumb = document.getElementById('signatureThumbnail');
const logoWidth = document.getElementById('logo_width');
const logoHeight = document.getElementById('logo_height');
const sigWidth = document.getElementById('sig_width');
const sigHeight = document.getElementById('sig_height');
let typingTimer;

// Fungsi update preview tanpa simpan
async function updatePreview() {
  loader.classList.remove('hidden');
  const formData = new FormData(form);

  const response = await fetch('{{ route('interns.assessment.settings.preview.live') }}', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
    body: formData
  });

  if (response.ok) {
    const blob = await response.blob();
    const url = URL.createObjectURL(blob);
    iframe.src = url;
  }

  setTimeout(() => loader.classList.add('hidden'), 800);
}

// Update thumbnail ukuran
function updateImageSize() {
  logoThumb.style.width = logoWidth.value + 'px';
  logoThumb.style.height = logoHeight.value + 'px';
  sigThumb.style.width = sigWidth.value + 'px';
  sigThumb.style.height = sigHeight.value + 'px';
}

// Event untuk semua input teks dan file
inputs.forEach(input => {
  input.addEventListener('input', () => {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => { updateImageSize(); updatePreview(); }, 600);
  });
  input.addEventListener('change', () => { updateImageSize(); updatePreview(); });
});

// Event untuk dropdown logo dan tanda tangan
logoSelect.addEventListener('change', () => {
  const path = logoSelect.value ? "{{ asset('storage') }}/" + logoSelect.value : logoThumb.src;
  logoThumb.src = path;
  updatePreview();
});

sigSelect.addEventListener('change', () => {
  const path = sigSelect.value ? "{{ asset('storage') }}/" + sigSelect.value : sigThumb.src;
  sigThumb.src = path;
  updatePreview();
});
</script>
@endsection
