@extends('layouts.dashboard')

@section('content')
<div class="w-full mx-auto px-6 pt-6 pb-6 lg:px-10 bg-primary-300">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold">Sertifikat Peserta Eksternal</h1>
      <p class="text-gray-500 text-sm">Buat sertifikat massal untuk peserta di luar pemagang. Divisi otomatis: <b>EXT</b>.</p>
    </div>
    <a href="{{ route('admin.certificate.index') }}" class="px-3 py-2 rounded border bg-white hover:bg-gray-300"> < Kembali</a>
  </div>

  @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded p-4 mb-4">
      <ul class="list-disc pl-5 space-y-1">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.certificate.external.store') }}" method="POST" class="space-y-6">
    @csrf

    {{-- Info umum --}}
    <div class="bg-white border rounded-xl p-5 shadow-sm">
      <h2 class="font-semibold mb-4">Informasi Umum</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Perusahaan Penerbit</label>
          <input type="text" name="company" value="{{ old('company','Seven Inc') }}" required
                 class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium">Kota</label>
          <input type="text" name="city" value="{{ old('city','Yogyakarta') }}" required
                 class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium">Brand</label>
          <select name="brand" required class="mt-1 w-full border rounded px-3 py-2">
            <option value="">Pilih Brand</option>
            @foreach($brands as $code=>$label)
              <option value="{{ $code }}" @selected(old('brand')===$code)>{{ $label }} ({{ $code }})</option>
            @endforeach
          </select>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium">Mulai</label>
            <input type="date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}" required
                   class="mt-1 w-full border rounded px-3 py-2">
          </div>
          <div>
            <label class="block text-sm font-medium">Selesai</label>
            <input type="date" name="end_date" value="{{ old('end_date', now()->toDateString()) }}" required
                   class="mt-1 w-full border rounded px-3 py-2">
          </div>
        </div>
      </div>
    </div>

    {{-- Media --}}
    <div class="bg-white border rounded-xl p-5 shadow-sm">
      <h2 class="font-semibold mb-4">Background, Logo, TTD</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium">Background (bg_*)</label>
          <select name="background_image" class="mt-1 w-full border rounded px-3 py-2" required>
            <option value="">Pilih file</option>
            @foreach($backgroundFiles as $f)
              <option value="{{ $f }}" @selected(old('background_image')===$f)>{{ $f }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">Logo 1 (logo_*)</label>
          <select name="logo1" class="mt-1 w-full border rounded px-3 py-2" required>
            <option value="">Pilih file</option>
            @foreach($logoFiles as $f)
              <option value="{{ $f }}" @selected(old('logo1')===$f)>{{ $f }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">Logo 2 (opsional)</label>
          <select name="logo2" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">- tanpa logo 2 -</option>
            @foreach($logoFiles as $f)
              <option value="{{ $f }}" @selected(old('logo2')===$f)>{{ $f }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">TTD 1 (ttd_*)</label>
          <select name="signature_image1" class="mt-1 w-full border rounded px-3 py-2" required>
            <option value="">Pilih file</option>
            @foreach($signatureFiles as $f)
              <option value="{{ $f }}" @selected(old('signature_image1')===$f)>{{ $f }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">TTD 2 (opsional)</label>
          <select name="signature_image2" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">- tanpa ttd 2 -</option>
            @foreach($signatureFiles as $f)
              <option value="{{ $f }}" @selected(old('signature_image2')===$f)>{{ $f }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    {{-- Penandatangan --}}
    <div class="bg-white border rounded-xl p-5 shadow-sm">
      <h2 class="font-semibold mb-4">Penandatangan</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Nama Penandatangan 1</label>
          <input type="text" name="name_signatory1" value="{{ old('name_signatory1') }}" required
                 class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium">Nama Penandatangan 2 (ops.)</label>
          <input type="text" name="name_signatory2" value="{{ old('name_signatory2') }}"
                 class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium">Jabatan 1</label>
          <input type="text" name="role1" value="{{ old('role1') }}" required
                 class="mt-1 w-full border rounded px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium">Jabatan 2 (ops.)</label>
          <input type="text" name="role2" value="{{ old('role2') }}"
                 class="mt-1 w-full border rounded px-3 py-2">
        </div>
      </div>
    </div>

    {{-- Peserta (dinamis) --}}
    <div class="bg-white border rounded-xl p-5 shadow-sm">
      <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold">Peserta (bisa banyak)</h2>
        <button type="button" id="btnAddRow"
                class="px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700">
          + Tambah Peserta
        </button>
      </div>

      <div id="rows" class="space-y-3">
        @php $oldRows = old('participants', [['name'=>'']]); @endphp
        @foreach($oldRows as $i => $row)
          <div class="grid grid-cols-[1fr_auto] gap-2 items-center row-item">
            <input type="text" name="participants[{{ $i }}][name]" value="{{ $row['name'] ?? '' }}"
                   placeholder="Nama peserta"
                   class="w-full border rounded px-3 py-2" required>
            <button type="button" class="btnDel px-3 py-2 rounded border hover:bg-gray-50">Hapus</button>
          </div>
        @endforeach
      </div>

      <template id="tplRow">
        <div class="grid grid-cols-[1fr_auto] gap-2 items-center row-item">
          <input type="text" name="__NAME__" placeholder="Nama peserta" class="w-full border rounded px-3 py-2" required>
          <button type="button" class="btnDel px-3 py-2 rounded border hover:bg-gray-50">Hapus</button>
        </div>
      </template>
      <p class="text-xs text-gray-500 mt-2">Divisi tidak ada (otomatis: <b>EXT</b>). Semua peserta memakai setting yang sama di atas.</p>
    </div>

    <div class="flex justify-end">
      <button type="submit" class="px-5 py-2.5 rounded bg-primary-600 text-white hover:bg-primary-700">
        Buat Sertifikat
      </button>
    </div>
  </form>
</div>

{{-- minimal script untuk tambah/hapus baris --}}
<script>
(function(){
  const rows = document.getElementById('rows');
  const tpl  = document.getElementById('tplRow').innerHTML;
  const btn  = document.getElementById('btnAddRow');
  let idx = rows.querySelectorAll('.row-item').length;

  btn.addEventListener('click', () => {
    const html = tpl.replace('__NAME__', `participants[${idx}][name]`);
    const wrap = document.createElement('div');
    wrap.innerHTML = html.trim();
    rows.appendChild(wrap.firstChild);
    idx++;
  });

  rows.addEventListener('click', (e) => {
    if (e.target.classList.contains('btnDel')) {
      const item = e.target.closest('.row-item');
      item?.remove();
    }
  });
})();
</script>
@endsection
