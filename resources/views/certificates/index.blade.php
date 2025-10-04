@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-0 py-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Certificates</h1>
            <p class="text-sm text-gray-500">Total: <span class="font-semibold">{{ $certificates->count() }}</span> item</p>
        </div>
        <div class="flex flex-wrap gap-2">
            {{-- Buat Sertifikat --}}
            <a href="{{ route('admin.certificate.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 5c.552 0 1 .448 1 1v5h5c.552 0 1 .448 1 1s-.448 1-1 1h-5v5c0 .552-.448 1-1 1s-1-.448-1-1v-5H6c-.552 0-1-.448-1-1s.448-1 1-1h5V6c0-.552.448-1 1-1z"/>
                </svg>
                Buat Sertifikat Magang
            </a>

            <a href="{{ route('admin.certificate.external.create') }}"
                class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">
                Buat Sertifikat Non Magang
            </a>

            {{-- Upload Background --}}
            <button type="button" onclick="openModal('bg')"
                class="inline-flex items-center gap-2 bg-white text-indigo-600 border border-indigo-600 px-4 py-2 rounded-lg shadow-sm 
                    hover:bg-indigo-50 hover:border-indigo-700 hover:text-indigo-700 transition">
                Upload Background
            </button>

            {{-- Upload Logo --}}
            <button type="button" onclick="openModal('logo')"
                class="inline-flex items-center gap-2 bg-white text-purple-600 border border-purple-600 px-4 py-2 rounded-lg shadow-sm 
                    hover:bg-purple-50 hover:border-purple-700 hover:text-purple-700 transition">
                Upload Logo
            </button>

            {{-- Upload Tanda Tangan --}}
            <button type="button" onclick="openModal('ttd')"
                class="inline-flex items-center gap-2 bg-white text-emerald-600 border border-emerald-600 px-4 py-2 rounded-lg shadow-sm 
                    hover:bg-emerald-50 hover:border-emerald-700 hover:text-emerald-700 transition">
                Upload Tanda Tangan
            </button>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5" viewBox="0 0 24 24" fill="currentColor"><path d="M10.97 2.72a1.75 1.75 0 0 1 2.06 0l7.25 5.24c.45.32.72.84.72 1.39v8.68c0 .96-.78 1.75-1.75 1.75H5.75A1.75 1.75 0 0 1 4 18.03V9.35c0-.55.27-1.07.72-1.39l7.25-5.24zM12 4.39 5.5 9v9.03h13V9L12 4.39z"/></svg>
            <div class="flex-1">{{ session('success') }}</div>
            <button onclick="this.parentElement.remove()" class="text-green-700/70 hover:text-green-900">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Toolbar: search & filter (client-side) --}}
    <div class="bg-white border rounded-xl p-4 mb-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari</label>
                <input id="searchInput" type="text" placeholder="Cari nama, company, serial..." class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-100" oninput="filterRows()">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Brand</label>
                <select id="brandFilter" class="w-full border rounded-lg px-3 py-2" onchange="filterRows()">
                    <option value="">Semua</option>
                    @php $brands = collect($certificates)->pluck('brand')->filter()->unique()->values(); @endphp
                    @foreach($brands as $b)
                        <option value="{{ $b }}">{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Divisi</label>
                <select id="divisionFilter" class="w-full border rounded-lg px-3 py-2" onchange="filterRows()">
                    <option value="">Semua</option>
                    @php $divs = collect($certificates)->pluck('division')->filter()->unique()->values(); @endphp
                    @foreach($divs as $d)
                        <option value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Table (desktop) --}}
    <div class="hidden md:block bg-white border rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Divisi</th>
                        <th class="px-4 py-3 text-left">Company</th>
                        <th class="px-4 py-3 text-left">Brand</th>
                        <th class="px-4 py-3 text-left">Serial</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody id="certTable">
                    @forelse($certificates as $cert)
                    <tr class="border-t hover:bg-gray-50 transition" data-brand="{{ $cert->brand }}" data-division="{{ $cert->division }}" data-search="{{ Str::lower($cert->name.' '.$cert->company.' '.$cert->serial_number) }}">
                        <td class="px-4 py-3 align-top">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 align-top">
                            <div class="font-medium">{{ $cert->name }}</div>
                            <div class="text-xs text-gray-500">{{ $cert->company }}</div>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-50 text-blue-700 border border-blue-200">{{ $cert->division }}</span>
                        </td>
                        <td class="px-4 py-3 align-top">{{ $cert->company }}</td>
                        <td class="px-4 py-3 align-top">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-amber-50 text-amber-700 border border-amber-200">{{ $cert->brand }}</span>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex items-center gap-2">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $cert->serial_number }}</code>
                                <button type="button" class="text-gray-500 hover:text-gray-700" onclick="copySerial('{{ $cert->serial_number }}', this)" title="Copy serial">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 3a2 2 0 0 0-2 2v10h2V5h8V3H8zm4 4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-6zm0 2h6v10h-6V9z"/></svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.certificate.show', $cert->id) }}" class="inline-flex items-center px-3 py-1.5 rounded border text-blue-700 border-blue-200 hover:bg-blue-50">Preview</a>
                                <a href="{{ route('admin.certificate.edit', $cert->id) }}" class="inline-flex items-center px-3 py-1.5 rounded border text-green-700 border-green-200 hover:bg-green-50">Edit</a>
                                <form action="{{ route('admin.certificate.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Yakin hapus sertifikat ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded border text-red-700 border-red-200 hover:bg-red-50">Hapus</button>
                                </form>
                                <a href="{{ route('admin.certificate.pdf', $cert->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded border text-amber-700 border-amber-200 hover:bg-amber-50">
                                    {{-- ikon download opsional --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 3a1 1 0 011 1v8.586l2.293-2.293a1 1 0 111.414 1.414l-4.007 4.007a1 1 0 01-1.414 0L7.279 11.707a1 1 0 111.414-1.414L11 12.586V4a1 1 0 011-1zm-7 14a1 1 0 100 2h14a1 1 0 100-2H5z"/>
                                    </svg>
                                    Download PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="currentColor"><path d="M6 2a2 2 0 0 0-2 2v14l4-2 4 2 4-2 4 2V4a2 2 0 0 0-2-2H6z"/></svg>
                                Belum ada sertifikat
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Cards (mobile) --}}
    <div class="md:hidden space-y-3" id="certCards">
        @forelse($certificates as $cert)
        <div class="border rounded-xl bg-white shadow-sm p-4" data-brand="{{ $cert->brand }}" data-division="{{ $cert->division }}" data-search="{{ Str::lower($cert->name.' '.$cert->company.' '.$cert->serial_number) }}">
            <div class="flex items-start justify-between">
                <div>
                    <div class="font-semibold">{{ $cert->name }}</div>
                    <div class="text-xs text-gray-500">{{ $cert->company }}</div>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-amber-50 text-amber-700 border border-amber-200">{{ $cert->brand }}</span>
            </div>
            <div class="mt-2 flex items-center gap-2 text-xs">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">{{ $cert->division }}</span>
                <code class="bg-gray-100 px-2 py-1 rounded">{{ $cert->serial_number }}</code>
                <button type="button" class="text-gray-500" onclick="copySerial('{{ $cert->serial_number }}', this)">Copy</button>
            </div>
            <div class="mt-3 flex flex-wrap gap-2">
                <a href="{{ route('admin.certificate.show', $cert->id) }}" class="inline-flex items-center px-3 py-1.5 rounded border text-blue-700 border-blue-200 hover:bg-blue-50">Preview</a>
                <a href="{{ route('admin.certificate.edit', $cert->id) }}" class="inline-flex items-center px-3 py-1.5 rounded border text-green-700 border-green-200 hover:bg-green-50">Edit</a>
                <form action="{{ route('admin.certificate.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Yakin hapus sertifikat ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded border text-red-700 border-red-200 hover:bg-red-50">Hapus</button>
                </form>
                <a href="{{ route('admin.certificate.pdf', $cert->id) }}"
                    class="inline-flex items-center px-3 py-1.5 rounded border text-amber-700 border-amber-200 hover:bg-amber-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3a1 1 0 011 1v8.586l2.293-2.293a1 1 0 111.414 1.414l-4.007 4.007a1 1 0 01-1.414 0L7.279 11.707a1 1 0 111.414-1.414L11 12.586V4a1 1 0 011-1zm-7 14a1 1 0 100 2h14a1 1 0 100-2H5z"/>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
        @empty
        <div class="text-center text-gray-500">Belum ada sertifikat</div>
        @endforelse
    </div>
</div>

{{-- ===================== MODALS ===================== --}}
<div id="modal-bg" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('bg')"></div>
    <div class="relative bg-white w-full max-w-lg mx-4 rounded-xl shadow-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Upload Background</h2>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal('bg')">✕</button>
        </div>
        <form action="{{ route('admin.uploads.backgrounds.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Format File Gambar (bg_(Nama File Gambar).png/.jpg/.jpeg/.webp)</label>
                <input type="file" name="file" accept=".png,.jpg,.jpeg,.webp" required class="block w-full border rounded px-3 py-2">
            </div>
            <div class="text-xs text-gray-600">
                Disimpan ke: <code>storage/app/public/images/backgrounds/</code><br>
                Otomatis dinamai ulang: <code>bg_{{'{'}}slug{{'}'}}_YYYYmmdd_HHMMSS.ext</code><br>
                Maks 2 MB.
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('bg')" class="px-4 py-2 rounded border">Batal</button>
                <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Upload</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-logo" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('logo')"></div>
    <div class="relative bg-white w-full max-w-lg mx-4 rounded-xl shadow-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Upload Logo</h2>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal('logo')">✕</button>
        </div>
        <form action="{{ route('admin.uploads.logos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Format File Gambar (logo_(Nama File Gambar).png/.jpg/.jpeg/.webp)</label>
                <input type="file" name="file" accept=".png,.jpg,.jpeg,.webp" required class="block w-full border rounded px-3 py-2">
            </div>
            <div class="text-xs text-gray-600">
                Disimpan ke: <code>storage/app/public/images/logos/</code><br>
                Otomatis dinamai ulang: <code>logo_{{'{'}}slug{{'}'}}_YYYYmmdd_HHMMSS.ext</code><br>
                Maks 2 MB.
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('logo')" class="px-4 py-2 rounded border">Batal</button>
                <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-white hover:bg-purple-700">Upload</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-ttd" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('ttd')"></div>
    <div class="relative bg-white w-full max-w-lg mx-4 rounded-xl shadow-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Upload Tanda Tangan</h2>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal('ttd')">✕</button>
        </div>
        <form action="{{ route('admin.uploads.signatures.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Format File Gambar (ttd_(Nama File Gambar).png/.jpg/.jpeg/.webp)</label>
                <input type="file" name="file" accept=".png,.jpg,.jpeg,.webp" required class="block w-full border rounded px-3 py-2">
            </div>
            <div class="text-xs text-gray-600">
                Disimpan ke: <code>storage/app/public/images/signature/</code><br>
                Otomatis dinamai ulang: <code>ttd_{{'{'}}slug{{'}'}}_YYYYmmdd_HHMMSS.ext</code><br>
                Maks 2 MB.
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('ttd')" class="px-4 py-2 rounded border">Batal</button>
                <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700">Upload</button>
            </div>
        </form>
    </div>
</div>

{{-- ================ SCRIPTS ================ --}}
<script>
function openModal(kind){
    const id = kind === 'bg' ? 'modal-bg' : kind === 'logo' ? 'modal-logo' : 'modal-ttd';
    const el = document.getElementById(id);
    if(!el) return;
    el.classList.remove('hidden');
    el.classList.add('flex');
}
function closeModal(kind){
    const id = kind === 'bg' ? 'modal-bg' : kind === 'logo' ? 'modal-logo' : 'modal-ttd';
    const el = document.getElementById(id);
    if(!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
}

function filterRows(){
    const q = (document.getElementById('searchInput').value || '').toLowerCase();
    const b = (document.getElementById('brandFilter').value || '').toLowerCase();
    const d = (document.getElementById('divisionFilter').value || '').toLowerCase();

    const rows = document.querySelectorAll('#certTable tr');
    rows.forEach(r => {
        const ds = (r.getAttribute('data-search') || '').toLowerCase();
        const rb = (r.getAttribute('data-brand') || '').toLowerCase();
        const rd = (r.getAttribute('data-division') || '').toLowerCase();
        const ok = (!q || ds.includes(q)) && (!b || rb === b) && (!d || rd === d);
        r.style.display = ok ? '' : 'none';
    });

    const cards = document.querySelectorAll('#certCards > div[data-search]');
    cards.forEach(c => {
        const ds = (c.getAttribute('data-search') || '').toLowerCase();
        const rb = (c.getAttribute('data-brand') || '').toLowerCase();
        const rd = (c.getAttribute('data-division') || '').toLowerCase();
        const ok = (!q || ds.includes(q)) && (!b || rb === b) && (!d || rd === d);
        c.style.display = ok ? '' : 'none';
    });
}

function copySerial(text, btn){
    navigator.clipboard.writeText(text).then(() => {
        const old = btn.innerHTML;
        btn.innerHTML = '✔';
        setTimeout(() => btn.innerHTML = old, 1000);
    });
}
</script>
@endsection
