@extends('layouts.dashboard')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 p-6 bg-white rounded-lg shadow">

  {{-- === FORM KIRI === --}}
  <div class="w-full lg:w-1/2">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">⚙️ Pengaturan Identitas Perusahaan</h1>

    <form id="settingsForm" enctype="multipart/form-data" class="space-y-6">
      @csrf

      {{-- NAMA DAN ALAMAT --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Perusahaan</label>
        <input type="text" name="company_name" 
               value="{{ old('company_name', $company_name ?? 'SEVEN INC.') }}" 
               class="live-input w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Perusahaan</label>
        <textarea name="company_address" rows="3"
                  class="live-input w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">{{ old('company_address', $company_address ?? 'Jl. Raya Janti, Gang Arjuna No. 59, Karangjambe, Banguntapan, Bantul, Yogyakarta') }}</textarea>
      </div>

      {{-- LOGO --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Logo Perusahaan</label>

        <div class="flex items-center gap-3">
          <select name="company_logo_select" id="company_logo_select"
                  class="live-input flex-1 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
            <option value="">Pilih dari daftar logo...</option>
            @foreach($logos as $logo)
              <option value="{{ $logo }}"{{
                (old('company_logo_select', $company_logo_path ?? 'images/logos/logo_seveninc.png') == $logo) ? 'selected' : '' }}>
                {{ basename($logo) }}
              </option>
            @endforeach
          </select>

          <img id="logoThumbnail" 
               src="{{ asset('storage/' . ($company_logo_path ?? 'images/logos/logo_seveninc.png')) }}" 
               class="border rounded-md shadow-sm object-contain"
               style="height: {{ session('logo_height', 70) }}px; width: auto;">
        </div>

        <div class="mt-2">
          <label class="text-xs text-gray-600">Tinggi Logo (px)</label>
          <input type="range" name="logo_height" id="logo_height" min="30" max="200"
                 value="{{ session('logo_height', 70) }}" class="w-full live-input">
        </div>

        <input type="file" name="company_logo" accept="image/*"
               class="live-input block w-full mt-2 text-sm border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5">
        <input type="hidden" name="old_logo" value="{{ $company_logo_path ?? 'images/logos/logo_seveninc.png' }}">
      </div>

      <hr class="my-4">

      {{-- PENANDATANGAN --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Penandatangan</label>
        <input type="text" name="signature_name" 
               value="{{ old('signature_name', $signature_name ?? 'Rekario Danny Sanjaya, S.Kom') }}" 
               class="live-input w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Jabatan Penandatangan</label>
        <input type="text" name="signature_position" 
               value="{{ old('signature_position', $signature_position ?? 'Direktur SEVEN INC') }}" 
               class="live-input w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
      </div>

      {{-- TANDA TANGAN --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar Tanda Tangan</label>

        <div class="flex items-center gap-3">
          <select name="signature_image_select" id="signature_image_select"
                  class="live-input flex-1 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
            <option value="">Pilih dari daftar tanda tangan...</option>
            @foreach($signatures as $sig)
              <option value="{{ $sig }}"{{
                (old('signature_image_select', $signature_image_path ?? 'images/signature/ttd_rekariodanny.png') == $sig) ? 'selected' : '' }}>
                {{ basename($sig) }}
              </option>
            @endforeach
          </select>

          <img id="signatureThumbnail" 
               src="{{ asset('storage/' . ($signature_image_path ?? 'images/signature/ttd_rekariodanny.png')) }}" 
               class="border rounded-md shadow-sm object-contain"
               style="height: {{ session('sig_height', 80) }}px; width: auto;">
        </div>

        <div class="mt-2">
          <label class="text-xs text-gray-600">Tinggi Tanda Tangan (px)</label>
          <input type="range" name="sig_height" id="sig_height" min="50" max="300"
                 value="{{ session('sig_height', 80) }}" class="w-full live-input">
        </div>

        <input type="file" name="signature_image" accept="image/*"
               class="live-input block w-full mt-2 text-sm border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5">
        <input type="hidden" name="old_signature" value="{{ $signature_image_path ?? 'images/signature/ttd_rekariodanny.png' }}">
      </div>

      {{-- TOMBOL SIMPAN --}}
      <div class="pt-4 border-t mt-6">
        <button type="button" id="saveSettingsBtn"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200">
          💾 Simpan Pengaturan
        </button>
      </div>
    </form>

    {{-- LOADING --}}
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
const iframe = document.getElementById('pdfPreview');
const loader = document.getElementById('loading');
const logoThumb = document.getElementById('logoThumbnail');
const sigThumb = document.getElementById('signatureThumbnail');
const logoSelect = document.getElementById('company_logo_select');
const sigSelect = document.getElementById('signature_image_select');
const logoHeight = document.getElementById('logo_height');
const sigHeight = document.getElementById('sig_height');
const inputs = document.querySelectorAll('.live-input');
const saveBtn = document.getElementById('saveSettingsBtn');
let debounceTimer = null;

// Fungsi utama update preview
async function updatePreview() {
  loader.classList.remove('hidden');
  const formData = new FormData(form);

  try {
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
  } catch (err) {
    console.error("Gagal memperbarui preview:", err);
  }

  setTimeout(() => loader.classList.add('hidden'), 800);
}

// Update tinggi logo & tanda tangan realtime
function updateImageSize() {
  logoThumb.style.height = logoHeight.value + 'px';
  sigThumb.style.height = sigHeight.value + 'px';
}

// Debounce input agar tidak reload terus
function debounceUpdate() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    updateImageSize();
    updatePreview();
  }, 700);
}

// Simpan pengaturan ke database
saveBtn.addEventListener('click', async () => {
  const formData = new FormData(form);
  saveBtn.disabled = true;
  saveBtn.innerText = '⏳ Menyimpan...';

  try {
    const response = await fetch('{{ route('interns.assessment.settings.save') }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
      body: formData
    });
    const result = await response.json();
    alert(result.message || 'Pengaturan berhasil disimpan!');
  } catch (err) {
    alert('Terjadi kesalahan saat menyimpan pengaturan.');
  } finally {
    saveBtn.disabled = false;
    saveBtn.innerText = '💾 Simpan Pengaturan';
  }
});

// Event input & dropdown
inputs.forEach(input => {
  input.addEventListener('input', debounceUpdate);
  input.addEventListener('change', debounceUpdate);
});

logoSelect.addEventListener('change', () => {
  logoThumb.src = logoSelect.value ? "{{ asset('storage') }}/" + logoSelect.value : "{{ asset('storage/images/logos/logo_seveninc.png') }}";
  updatePreview();
});

sigSelect.addEventListener('change', () => {
  sigThumb.src = sigSelect.value ? "{{ asset('storage') }}/" + sigSelect.value : "{{ asset('storage/images/signature/ttd_rekariodanny.png') }}";
  updatePreview();
});
</script>
@endsection
