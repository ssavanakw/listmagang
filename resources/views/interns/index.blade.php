@extends('layouts.dashboard')

@php
    use Illuminate\Support\Str;

    if (!isset($scope)) {
        $scope = 'all';
        $route = request()->route()?->getName();
        $mapRouteScope = [
            'admin.interns.active'    => 'active',
            'admin.interns.completed' => 'completed',
            'admin.interns.exited'    => 'exited',
            'admin.interns.pending'   => 'pending',
            'admin.interns.accepted'  => 'accepted',
            'admin.interns.rejected'  => 'rejected',
        ];
        if ($route && isset($mapRouteScope[$route])) {
            $scope = $mapRouteScope[$route];
        }
    }

    $certAreaKerjaComRouteTmpl = route(
        'admin.interns.certificate.areakerjacom',
        ['intern' => '__ID__']
    );

@endphp

@section('content')
@push('modals')
    <div id="appModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>

        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div id="appModalDialog"
                 class="w-full max-w-4xl rounded-xl bg-white shadow-xl overflow-hidden
                        dark:bg-gray-800">
                <div class="flex items-center justify-between border-b px-4 py-3
                            dark:border-gray-700">
                    <h3 id="appModalTitle" class="font-semibold text-gray-800 dark:text-gray-100">Modal</h3>
                    <button type="button"
                            class="px-2 py-1 text-gray-500 hover:text-gray-700 dark:text-gray-300"
                            data-modal-close>&times;</button>
                </div>
                <div id="appModalBody" class="max-h-[75vh] overflow-auto p-4"></div>
            </div>
        </div>
    </div>
    <div id="confirmModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-black/50" data-confirm-close></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-xl bg-white shadow-xl overflow-hidden
                        dark:bg-gray-800">
                <div class="border-b px-5 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">Konfirmasi</h3>
                </div>
                <div id="confirmBody" class="px-5 py-4 text-gray-700 dark:text-gray-200">
                    Yakin ingin menghapus data ini?
                </div>
                <div class="flex justify-end gap-2 border-t px-5 py-3 dark:border-gray-700">
                    <button class="rounded-lg border px-3 py-1.5" data-confirm-close>Batal</button>
                    <button id="confirmYes" class="rounded-lg bg-red-600 px-3 py-1.5 text-white hover:bg-red-700">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush


<div class="px-4 pt-6">

    {{-- Header + Tabs --}}
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title ?? 'Semua Pemagang' }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Daftar pemagang berdasarkan filter.</p>

            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('admin.interns.index') }}"
                   class="rounded-lg px-3 py-2 text-sm {{ ($scope ?? '') === 'all' ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                    Semua
                </a>
                <a href="{{ route('admin.interns.active') }}"
                   class="rounded-lg px-3 py-2 text-sm {{ ($scope ?? '') === 'active' ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                    Aktif
                </a>
                <a href="{{ route('admin.interns.completed') }}"
                   class="rounded-lg px-3 py-2 text-sm {{ ($scope ?? '') === 'completed' ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                    Selesai
                </a>
                <a href="{{ route('admin.interns.exited') }}"
                   class="rounded-lg px-3 py-2 text-sm {{ ($scope ?? '') === 'exited' ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                    Keluar
                </a>
                <a href="{{ route('admin.interns.pending') }}"
                   class="rounded-lg px-3 py-2 text-sm {{ ($scope ?? '') === 'pending' ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                    Pending
                </a>
                <a href="{{ route('admin.interns.accepted') }}"
                    class="rounded-lg px-3 py-2 text-sm {{ ($scope ?? '') === 'accepted' ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                    Diterima
                </a>
                <a href="{{ route('admin.interns.rejected') }}"
                    class="rounded-lg px-3 py-2 text-sm {{ ($scope ?? '') === 'rejected' ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                    Ditolak
                </a>
            </div>
        </div>

        {{-- Kanan: Search + Pending Bar + Toast --}}
        <div class="mt-1 flex w-full max-w-[480px] flex-col items-end gap-2">
            {{-- Pending bar --}}
            <div id="pendingBar"
                 class="hidden rounded-full border border-amber-200 bg-amber-50/95 px-3 py-2
                        text-amber-900 shadow-sm
                        dark:border-amber-700 dark:bg-amber-900/40 dark:text-amber-200">
                <div class="flex items-center gap-2">
                    <span class="text-[12px]/none">
                        <strong id="pendingCount">0</strong> perubahan belum disimpan
                    </span>
                    <button id="discardAll"
                            class="rounded-full border border-amber-300 px-2 py-1 text-[12px]
                                   text-amber-800 hover:bg-amber-100
                                   dark:border-amber-700 dark:text-amber-200 dark:hover:bg-amber-900/60">
                        Batalkan
                    </button>
                    <button id="saveAll" disabled
                            class="rounded-full bg-emerald-600 px-3 py-1 text-[12px]
                                   text-white hover:bg-emerald-700 disabled:opacity-50">
                        Simpan
                    </button>
                </div>
            </div>

            {{-- Toast stack --}}
            <div id="toastStack" class="flex w-full flex-col items-end gap-2"></div>
        </div>
    </div>

    {{-- Card --}}
    <div class="rounded-xl bg-white shadow ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">

        {{-- Table --}}
        <div id="tableWrap" class="overflow-x-auto" data-base="{{ url('/admin/interns') }}">
        <table id="tabel-pemagang" class="w-max min-w-full text-left text-sm text-gray-700 dark:text-gray-200">
            <thead class="sticky bg-gray-50 text-xs uppercase tracking-wider text-gray-600 dark:bg-gray-700 dark:text-gray-300">
            @php
                // daftar kolom
                $fields = [
                    'fullname'                   => 'NAMA LENGKAP',
                    'born_date'                  => 'TANGGAL LAHIR',
                    'student_id'                 => 'NIM / NIS',
                    'email'                      => 'EMAIL',
                    'gender'                     => 'GENDER',
                    'phone_number'               => 'TELEPON',
                    'institution_name'           => 'INSTITUSI',
                    'study_program'              => 'PRODI',
                    'faculty'                    => 'FAKULTAS',
                    'current_city'               => 'KOTA',
                    'internship_reason'          => 'ALASAN MAGANG',
                    'internship_type'            => 'JENIS MAGANG',
                    'internship_arrangement'     => 'TIPE MAGANG',
                    'current_status'             => 'STATUS SAAT INI',
                    'english_book_ability'       => 'BACA B.INGGRIS',
                    'supervisor_contact'         => 'KONTAK PEMBIMBING',
                    'internship_interest'        => 'BIDANG MINAT',
                    'internship_interest_other'  => 'MINAT LAIN',
                    'design_software'            => 'SOFTWARE DESAIN',
                    'video_software'             => 'SOFTWARE VIDEO',
                    'programming_languages'      => 'BAHASA PEMROGRAMAN',
                    'digital_marketing_type'     => 'DIGITAL MARKETING',
                    'digital_marketing_type_other'=> 'MARKETING LAIN',
                    'laptop_equipment'           => 'PUNYA LAPTOP',
                    'owned_tools'                => 'ALAT DIMILIKI',
                    'owned_tools_other'          => 'ALAT LAIN',
                    'start_date'                 => 'MULAI',
                    'end_date'                   => 'SELESAI',
                    'internship_info_sources'    => 'SUMBER INFO',
                    'internship_info_other'      => 'INFO LAIN',
                    'current_activities'         => 'AKTIVITAS SAAT INI',
                    'boarding_info'              => 'INFO KOST',
                    'family_status'              => 'SUDAH BERKELUARGA',
                    'parent_wa_contact'          => 'KONTAK ORANG TUA',
                    'social_media_instagram'     => 'INSTAGRAM',
                    'cv_ktp_portofolio_pdf'      => 'FILE PDF',
                    'portofolio_visual'          => 'FILE VISUAL',
                    'created_at'                 => 'DIBUAT',
                    'internship_status'          => 'STATUS',
                ];
                if (($scope ?? '') === 'completed') {
                    $fields['certificate'] = 'SERTIFIKAT';
                }

                // tipe input per kolom
                $dateFields   = ['born_date', 'start_date', 'end_date', 'created_at'];
                $selectFields = ['gender','internship_type','internship_arrangement','current_status','english_book_ability','laptop_equipment','family_status','internship_status'];

                // daftar template sertifikat
                $certTemplates = [
                    'certmagangjogjacom' => 'Magangjogja.com',
                    'certareakerjacom'   => 'AreaKerja.com',
                    'certtitipsinicom'    => 'Titipsini.com',
                ];

                // helper format tanggal
                $fmt = function($d){
                    if (!$d) return '-';
                    try { return \Carbon\Carbon::parse($d)->locale('id')->translatedFormat('d M Y'); }
                    catch (\Throwable $e) { return $d; }
                };
            @endphp

            {{-- Header kolom --}}
            <tr class="divide-x divide-gray-200 dark:divide-gray-600">
                <th class="whitespace-nowrap px-3 py-3 font-semibold">No</th>
                @foreach ($fields as $label)
                    <th class="whitespace-nowrap px-3 py-3 font-semibold">{{ $label }}</th>
                @endforeach
                <th class="whitespace-nowrap px-3 py-3 font-semibold">AKSI</th>
            </tr>

            {{-- Baris filter --}}
            <tr class="divide-x divide-gray-200 bg-white dark:divide-gray-600 dark:bg-gray-800">
                <th class="px-2 py-2"></th>
                @foreach ($fields as $key => $label)
                    <th class="px-2 py-2">
                        @if (in_array($key, $dateFields))
                            <input type="text" placeholder="Cari…" data-col="{{ $loop->index + 1 }}"
                                class="w-full rounded-md border-gray-300 text-xs dark:bg-gray-700" />
                        @elseif (in_array($key, $selectFields))
                            <select data-col="{{ $loop->index + 1 }}"
                                    class="w-full rounded-md border-gray-300 text-xs dark:bg-gray-700">
                                <option value="">Semua</option>
                            </select>
                        @elseif ($key === 'certificate')
                            {{-- HANYA SATU SELECT untuk filter sertifikat --}}
                            <select data-col="{{ $loop->index + 1 }}"
                                    class="w-full rounded-md border-gray-300 text-xs dark:bg-gray-700">
                                <option value="">Semua</option>
                                @foreach ($certTemplates as $tplKey => $tplLabel)
                                    <option value="{{ $tplLabel }}">{{ $tplLabel }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" placeholder="Cari…" data-col="{{ $loop->index + 1 }}"
                                class="w-full rounded-md border-gray-300 text-xs dark:bg-gray-700" />
                        @endif
                    </th>
                @endforeach
                <th class="px-2 py-2"></th>
            </tr>
            </thead>

            <tbody id="rows" class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
            @forelse ($interns as $i => $intern)
                @php
                    $defaultTpl = $intern->certificate_template ?? 'certmagangjogjacom';
                @endphp
                <tr class="divide-x divide-gray-200 dark:divide-gray-700">
                    <td class="px-3 py-3 whitespace-nowrap">{{ $loop->iteration }}</td>

                    @foreach ($fields as $key => $label)
                        @switch($key)
                            @case('born_date')
                            @case('start_date')
                            @case('end_date')
                            @case('created_at')
                                <td class="px-3 py-3 whitespace-nowrap">{{ $fmt($intern->{$key}) }}</td>
                                @break

                            @case('social_media_instagram')
                                <td class="px-3 py-3 whitespace-nowrap">
                                    @if ($intern->social_media_instagram)
                                        <a href="https://instagram.com/{{ ltrim($intern->social_media_instagram, '@') }}"
                                        class="text-blue-600 hover:underline" target="_blank">
                                        {{ '@'.ltrim($intern->social_media_instagram, '@') }}
                                        </a>
                                    @else - @endif
                                </td>
                                @break

                            @case('cv_ktp_portofolio_pdf')
                                <td class="px-3 py-3 whitespace-nowrap">
                                    @if ($intern->cv_ktp_portofolio_pdf)
                                        <a href="{{ asset('storage/'.$intern->cv_ktp_portofolio_pdf) }}" target="_blank"
                                        class="text-blue-600 hover:underline">Lihat</a>
                                    @else - @endif
                                </td>
                                @break

                            @case('portofolio_visual')
                                <td class="px-3 py-3 whitespace-nowrap">
                                    @if ($intern->portofolio_visual)
                                        <a href="{{ asset('storage/'.$intern->portofolio_visual) }}" target="_blank"
                                        class="text-blue-600 hover:underline">Lihat</a>
                                    @else - @endif
                                </td>
                                @break

                            @case('internship_status')
                                @php
                                    // Map kelas badge (sinkron dengan Model & JS)
                                    $statusBadge = [
                                        'waiting'       => ['Menunggu', 'bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-200'],
                                        'active'    => ['Aktif',          'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200'],
                                        'completed' => ['Selesai',        'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200'],
                                        'exited'    => ['Keluar',         'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200'],
                                        'pending'   => ['Pending',        'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200'],
                                        'accepted'  => ['Diterima',       'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200'],
                                        'rejected'  => ['Ditolak',        'bg-gray-200 text-gray-700 dark:bg-gray-800/60 dark:text-gray-200'],
                                    ];
                                    $st = strtolower($intern->internship_status ?? 'waiting');
                                    $label = $statusBadge[$st][0] ?? ucfirst($st);
                                    $cls   = $statusBadge[$st][1] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                                @endphp
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span id="badge-{{ $intern->id }}"
                                        class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $cls }}">
                                        {{ $label }}
                                    </span>
                                </td>
                            @break


                            @case('certificate')
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        {{-- Dropdown pilihan template sertifikat --}}
                                        <select class="cert-tpl form-select rounded-md border-gray-300 text-xs dark:bg-gray-700"
                                                data-intern="{{ $intern->id }}"
                                                style="min-width: 160px;">
                                            @foreach($certTemplates as $tplKey => $tplLabel)
                                                <option value="{{ $tplKey }}" {{ $defaultTpl === $tplKey ? 'selected' : '' }}>
                                                    {{ $tplLabel }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{-- 1 tombol download --}}
                                        <button type="button"
                                                class="btn btn-sm btn-primary js-download-cert"
                                                data-intern="{{ $intern->id }}">
                                            Download PDF
                                        </button>
                                    </div>
                                </td>
                            @break

                            @default
                                <td class="px-3 py-3 whitespace-nowrap">{{ $intern->{$key} ?? '-' }}</td>
                        @endswitch
                    @endforeach

                    {{-- AKSI --}}
                    <td class="px-3 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.interns.certificate', $intern->id) }}" class="btn btn-xs btn-info">Detail</a>
                            <a href="{{ route('admin.interns.update', $intern->id) }}" class="btn btn-xs btn-warning">Edit</a>
                            <form action="{{ route('admin.interns.destroy', $intern->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($fields) + 2 }}" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                        Tidak ada data.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        </div>

        {{-- JS handler Download/Preview --}}
        @push('scripts')
        <script>
        document.addEventListener('click', function (e) {
            if (!e.target.classList.contains('js-download-cert')) return;

            const btn = e.target;
            const internId = btn.getAttribute('data-intern');
            const sel = document.querySelector('select.cert-tpl[data-intern="'+ internId +'"]');
            const tpl = (sel && sel.value) ? sel.value : 'certmagangjogjacom';

            // Bangun URL sesuai route backend: /admin/interns/{id}/certificate/{template}.pdf
            const url = `{{ url('/admin/interns') }}/${internId}/certificate/${tpl}.pdf`;

            // UX kecil: disable tombol sesaat biar tidak double click
            btn.disabled = true;
            try { window.location.href = url; } finally { setTimeout(() => btn.disabled = false, 1200); }
        });
        </script>
        @endpush


        {{-- Pagination placeholder (dibangun via JS) --}}
        <div id="pager" class="px-6 py-4"></div>
    </div>
</div>



<script>
// letakkan di paling atas script utama, sebelum fungsi2 lain
window.rowData = window.rowData || new Map();

// Debounce helper (kalau nanti butuh)
function debounce(fn, ms=400) {
  let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
}

document.addEventListener('DOMContentLoaded', function () {
  // DOWNLOAD PDF
  document.querySelectorAll('.js-download-cert').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var id   = btn.getAttribute('data-intern');
      var base = btn.getAttribute('data-base'); // ex: /admin/interns
      var select = document.querySelector('select.cert-tpl[data-intern="'+ id +'"]');
      var tpl  = (select && select.value) ? select.value : 'certmagangjogjacom';
      var url  = base + '/' + id + '/certificate/' + tpl + '.pdf';
      window.location.href = url; // trigger download
    });
  });

  // PREVIEW (opsional – mapkan sesuai route preview yg tersedia)
  document.querySelectorAll('.js-preview-cert').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var id   = btn.getAttribute('data-intern');
      var base = btn.getAttribute('data-base');
      var select = document.querySelector('select.cert-tpl[data-intern="'+ id +'"]');
      var tpl  = (select && select.value) ? select.value : 'certmagangjogjacom';

      // Mapping preview per template (ubah sesuai route-mu)
      var previewMap = {
        'certmagangjogjacom': base + '/' + id + '/certificate', // HTML view existing
        'certareakerjacom'  : base + '/' + id + '/certificate/areakerjacom/preview',
        'certtitipsinicom'   : base + '/' + id + '/certificate/titipsinicom/preview'
      };

      var url = previewMap[tpl] || previewMap['certmagangjogjacom'];
      window.open(url, '_blank');
    });
  });
});


document.addEventListener('DOMContentLoaded', () => {

        // ====== Clamp scroll kanan hanya di tabel ======
        const wrap = document.getElementById('tableWrap');
        if (wrap) {
            wrap.scrollLeft = 0;
            wrap.addEventListener('scroll', () => {
                const max = wrap.scrollWidth - wrap.clientWidth;
                if (wrap.scrollLeft > max) wrap.scrollLeft = max;
                if (wrap.scrollLeft < 0) wrap.scrollLeft = 0;
            }, {
                passive: true
            });
        }

        const ADMIN_INTERNS_BASE = @json(url('/admin/interns'));
        const API_URL = @json(route('admin.interns.api'));
        const SCOPE = @json($scope ?? 'all');
        const csrf = document.querySelector('meta[name="csrf-token"]') ?.getAttribute('content') || '';

        const rowsEl = document.getElementById('rows');
        const pagerEl = document.getElementById('pager');
        const searchForm = document.getElementById('searchForm');
        const qInput = document.getElementById('q');

        // ====== Status map (badge + label) ======
        const statusMap = {
            waiting: {
                label: 'Menunggu',
                cls: 'bg-teal-100 text-teal-800 dark:bg-teal-600/20 dark:text-teal-300'
            },
            active: {
                label: 'Aktif',
                cls: 'bg-blue-100 text-blue-800 dark:bg-blue-600/20 dark:text-blue-300'
            },
            completed: {
                label: 'Selesai',
                cls: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-600/20 dark:text-indigo-300'
            },
            exited: {
                label: 'Keluar',
                cls: 'bg-rose-100 text-rose-800 dark:bg-rose-600/20 dark:text-rose-300'
            },
            pending: {
                label: 'Pending',
                cls: 'bg-amber-100 text-amber-800 dark:bg-amber-600/20 dark:text-amber-300'
            },accepted:  { 
                label: 'Diterima',       
                cls: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-700/20 dark:text-emerald-200' 
            },
            rejected:  { 
                label: 'Ditolak',        
                cls: 'bg-gray-200 text-gray-700 dark:bg-gray-700/40 dark:text-gray-200' 
            },

        };

        // ====== Advanced Column Search ======
        const columnFilter = {};
        const TABLE_ID = 'tabel-pemagang';
        const WRAP_ID = 'tableWrap';

        const dateCols = new Set(['born_date', 'start_date', 'end_date', 'created_at']);
        const selectCols = new Set([
            'gender', 'internship_type', 'internship_arrangement',
            'current_status', 'english_book_ability', 'laptop_equipment',
            'family_status', 'internship_status'
        ]);

        // urutan kolom sesuai Blade $fields (tetap sinkron!)
        const fieldOrder = [
            'fullname', 'born_date', 'student_id', 'email', 'gender', 'phone_number', 'institution_name', 'study_program',
            'faculty', 'current_city', 'internship_reason', 'internship_type', 'internship_arrangement', 'current_status',
            'english_book_ability', 'supervisor_contact', 'internship_interest', 'internship_interest_other', 'design_software',
            'video_software', 'programming_languages', 'digital_marketing_type', 'digital_marketing_type_other', 'laptop_equipment',
            'owned_tools', 'owned_tools_other', 'start_date', 'end_date', 'internship_info_sources', 'internship_info_other',
            'current_activities', 'boarding_info', 'family_status', 'parent_wa_contact', 'social_media_instagram', 'cv_ktp_portofolio_pdf',
            'portofolio_visual', 'created_at', 'internship_status'

        ];

        // buat baris input filter tepat di bawah header
        function buildAdvancedSearchRow() {
            const table = document.getElementById('tableWrap');
            if (!table) return;
            const thead = table.querySelector('thead');
            const headRows = thead ?.querySelectorAll('tr');
            if (!thead || !headRows ?.length) return;

            // jika sudah ada, jangan duplikasi
            if (thead.querySelector('tr[data-filter-row]')) return;

            const headerCols = headRows[0].children.length; // termasuk kolom "No"
            const tr = document.createElement('tr');
            tr.setAttribute('data-filter-row', '');
            tr.className = 'divide-x divide-gray-200 dark:divide-gray-600 bg-white dark:bg-gray-800';

            for (let i = 0; i < headerCols; i++) {
                const th = document.createElement('th');
                th.className = 'px-2 py-2';

                if (i === 0) { // kolom No: kosong
                    tr.appendChild(th);
                    continue;
                }

                const fieldKey = fieldOrder[i - 1]; // offset karena kolom No
                if (!fieldKey) {
                    tr.appendChild(th);
                    continue;
                }

                if (dateCols.has(fieldKey)) {
                    th.innerHTML = `
                        <input type="text" placeholder="Cari…"
                            data-col="${i}"
                            class="w-full rounded-md border-gray-300 dark:bg-gray-700 text-xs"/>`;
                    columnFilter[i] = {
                        kind: 'text',
                        q: ''
                    }; // <— bukan 'date-range' lagi
                } else if (selectCols.has(fieldKey)) {
                    th.innerHTML = `
                        <select data-col="${i}"
                            class="w-full rounded-md border-gray-300 dark:bg-gray-700 text-xs">
                            <option value="">Semua</option>
                        </select>`;
                    columnFilter[i] = {
                        kind: 'select',
                        q: ''
                    };
                } else {
                    th.innerHTML = `
                        <input type="text" placeholder="Cari…"
                            data-col="${i}"
                            class="w-full rounded-md border-gray-300 dark:bg-gray-700 text-xs"/>`;
                    columnFilter[i] = {
                        kind: 'text',
                        q: ''
                    };
                }
                tr.appendChild(th);
            }
            thead.appendChild(tr);

            bindFilterInputs();
            hydrateSelectOptions();
        }

        function getFilterTextFromCell(cell) {
            if (!cell) return '';

            // Prioritas: pakai elemen penanda bila ada (badge status, dsb.)
            const marker = cell.querySelector('[data-filter-value], [id^="badge-"], .filter-value');
            if (marker) return (marker.textContent || '').trim();

            // Fallback: clone lalu buang elemen interaktif
            const clone = cell.cloneNode(true);
            clone.querySelectorAll('select, option, form, button, input, textarea').forEach(n => n.remove());

            return (clone.textContent || '')
                .trim()
                .replace(/\s+/g, ' '); // normalisasi spasi
        }

        function hydrateSelectOptions() {
            const table = document.getElementById(TABLE_ID);
            const tbody = table ?.tBodies ?.[0];
            if (!tbody) return;

            const rows = [...tbody.rows]; // semua baris yang sedang dirender

            table.querySelectorAll('thead select[data-col]').forEach(sel => {
                const keep = sel.value; // simpan pilihan user

                // hapus opsi lama (kecuali "Semua")
                sel.querySelectorAll('option:not(:first-child)').forEach(o => o.remove());

                const col = +sel.dataset.col;
                const vals = new Set(
                    rows.map(r => getFilterTextFromCell(r.cells[col]))
                    .filter(v => v && v !== '-') // kosong & placeholder di-skip
                );

                [...vals].sort((a, b) => a.localeCompare(b, 'id')).forEach(v => {
                    const o = document.createElement('option');
                    o.value = v;
                    o.textContent = v;
                    sel.appendChild(o);
                });

                // kembalikan pilihan sebelumnya bila masih valid
                if (keep && [...sel.options].some(o => o.value === keep)) sel.value = keep;
            });
        }



        function rowMatchByFilters(tr) {
            const tds = [...tr.cells];
            for (const key in columnFilter) {
                const i = +key;
                const cfg = columnFilter[key];
                const raw = getFilterTextFromCell(tds[i]);

                if (cfg.kind === 'text') {
                    if (cfg.q && !raw.toLowerCase().includes(cfg.q.toLowerCase())) return false;
                } else if (cfg.kind === 'select') {
                    if (cfg.q && raw !== cfg.q) return false;
                }
            }
            return true;
        }

        // ====== TERAPKAN FILTER KE TABEL ======
        const applyColumnFilters = (() => {
            const run = () => {
                const table = document.getElementById(TABLE_ID);
                const tbody = table ?.tBodies ?.[0];
                if (!tbody) return;

                [...tbody.rows].forEach(tr => {
                    tr.style.display = rowMatchByFilters(tr) ? '' : 'none';
                });
            };
            return debounce(run, 120);
        })();

        function bindFilterInputs() {
            const table = document.getElementById(TABLE_ID);
            if (!table) return;

            // input teks per kolom (termasuk kolom tanggal yang sekarang string)
            table.querySelectorAll('thead input[data-col]').forEach(inp => {
                const col = +inp.dataset.col;
                if (!columnFilter[col]) columnFilter[col] = {
                    kind: 'text',
                    q: ''
                };
                inp.addEventListener('input', e => {
                    columnFilter[col].q = e.target.value;
                    applyColumnFilters();
                });
            });

            // select per kolom
            table.querySelectorAll('thead select[data-col]').forEach(sel => {
                const col = +sel.dataset.col;
                if (!columnFilter[col]) columnFilter[col] = {
                    kind: 'select',
                    q: ''
                };
                sel.addEventListener('change', e => {
                    columnFilter[col].q = e.target.value;
                    applyColumnFilters();
                });
            });
        }




        // ====== Localized label maps (ID) ======
        const interestMapID = {
            'project-manager': 'Manajer Proyek',
            'administration': 'Administrasi',
            'hr': 'Sumber Daya Manusia (HR)',
            'uiux': 'UI/UX',
            'programmer': 'Programmer (Front End / Backend)',
            'photographer': 'Fotografer',
            'videographer': 'Videografer',
            'graphic-designer': 'Desainer Grafis',
            'social-media-specialist': 'Spesialis Media Sosial',
            'content-writer': 'Penulis Konten',
            'content-planner': 'Perencana Konten',
            'marketing-and-sales': 'Penjualan & Pemasaran',
            'public-relation': 'Hubungan Masyarakat (Marcomm)',
            'digital-marketing': 'Pemasaran Digital',
            'tiktok-creator': 'Kreator TikTok',
            'welding': 'Pengelasan',
            'customer-service': 'Layanan Pelanggan',
        };

        const genderMapID = {
            'male': 'Laki-laki',
            'laki-laki': 'Laki-laki',
            'pria': 'Laki-laki',
            'm': 'Laki-laki',
            'female': 'Perempuan',
            'perempuan': 'Perempuan',
            'wanita': 'Perempuan',
            'f': 'Perempuan'
        };

        const statusNowMapID = {
            'Fresh Graduate': 'Lulusan Baru',
            'Student': 'Mahasiswa/Pelajar',
            'Employee': 'Karyawan',
            'Unemployed': 'Tidak Bekerja',
        };

        const arrangementMapID = { // TIPE MAGANG (cara kerja)
            'onsite': 'WFO',
            'hybrid': 'HYBRID',
            'remote': 'WFH'
        };

        const typeMapID = { // JENIS MAGANG (skema)
            'campus': 'Magang Kampus',
            'mandiri': 'Magang Mandiri',
            'pkl': 'PKL',
            'kampus-merdeka': 'Kampus Merdeka',
            'mbkm': 'Kampus Merdeka'
        };

        const yesNoMapID = {
            'yes': 'Ya',
            'y': 'Ya',
            'true': 'Ya',
            '1': 'Ya',
            'ya': 'Ya',
            'no': 'Tidak',
            'n': 'Tidak',
            'false': 'Tidak',
            '0': 'Tidak',
            'tidak': 'Tidak'
        };

        // ====== Helpers ======
        const fmtStr = (s) => (s && String(s).trim() !== '' ? String(s) : '-');

        // labelizer generik dengan fallback string polos
        const labelize = (map, val) => {
            if (val == null) return '-';
            const key = String(val).toLowerCase();
            for (const k in map) {
                if (k.toLowerCase() === key) return map[k];
            }
            return String(val); // fallback tampilkan apa adanya
        };

        function interestLabelID(slug) {
            if (!slug) return '-';
            const key = String(slug).toLowerCase();
            return interestMapID[key] ?? String(slug).replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        }

        // created_at dari server → tetap format tanggal
        const fmtDate = (s) => {
            if (!s) return '-';
            const d = new Date(s);
            if (isNaN(d)) return String(s);
            return d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        };

        // ====== Toast helpers ======
        const toastStack = document.getElementById('toastStack');

        function pushToast(message, type = 'success') {
            const base = 'flex items-center gap-2 rounded-lg border px-3 py-2 text-sm shadow-sm';
            const theme = type === 'success' ?
                'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-200' :
                'bg-rose-50 border-rose-200 text-rose-800 dark:bg-rose-900/20 dark:border-rose-700 dark:text-rose-200';
            const el = document.createElement('div');
            el.className = `${base} ${theme}`;
            el.innerHTML = `<span>${message}</span>
                            <button class="ml-2 rounded px-2 py-1 text-xs opacity-70 hover:opacity-100">Tutup</button>`;
            el.querySelector('button').addEventListener('click', () => el.remove());
            toastStack.appendChild(el);
            setTimeout(() => el.remove(), 4000);
        }

        // ====== Pending bar state ======
        const pendingBar = document.getElementById('pendingBar');
        const pendingCount = document.getElementById('pendingCount');
        const saveAllBtn = document.getElementById('saveAll');
        const discardBtn = document.getElementById('discardAll');
        const pending = new Map(); // key: id, value: {id,name,from,to,url,select,badge}

        function updatePendingBar() {
            const n = pending.size;
            pendingCount.textContent = n;
            pendingBar.classList.toggle('hidden', n === 0);
            saveAllBtn.disabled = n === 0;
        }

        function markSelect(sel, active) {
            sel.classList.toggle('ring-2', active);
            sel.classList.toggle('ring-amber-400', active);
            sel.classList.toggle('bg-amber-50', active);
        }

        // ubah tampilan badge status setelah sukses
        function applyBadge(badgeEl, newVal) {
            const m = statusMap[newVal] || {
                label: newVal,
                cls: 'bg-gray-100 text-gray-800 dark:bg-gray-600/20 dark:text-gray-200'
            };
            badgeEl.className = `inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${m.cls}`;
            badgeEl.textContent = m.label;
        }

        async function patchForm(url, fields) {
            const fd = new FormData();
            fd.append('_method', 'PATCH');
            for (const [k, v] of Object.entries(fields)) fd.append(k, v);
            const res = await fetch(url, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            if (!res.ok) {
                const t = await res.text().catch(() => '');
                throw new Error(t || `HTTP ${res.status}`);
            }
            return res;
        }

        function buildActionCell(it) {
            return `
                <div class="flex gap-2">
                    <button type="button" class="rounded bg-blue-500 px-2 py-1 text-xs text-white hover:bg-blue-600 js-detail"
                        data-id="${it.id}">
                        Detail
                    </button>
                    <button type="button" class="rounded bg-amber-600 px-2 py-1 text-xs text-white hover:bg-amber-700 js-edit"
                        data-id="${it.id}">
                        Edit
                    </button>
                    <button type="button" class="rounded bg-red-500 px-2 py-1 text-xs text-white hover:bg-red-600 js-delete"
                        data-id="${it.id}">
                        Hapus
                    </button>
                </div>`;
        }

        // ====== Konstanta pilihan template yang muncul di dropdown ======
        const CERT_OPTIONS = [
        { value: 'certmagangjogjacom', label: 'Magangjogja.com' },
        { value: 'certareakerjacom',   label: 'AreaKerja.com'   },
        { value: 'certtitipsinicom',    label: 'Titipsini.com'    },
        ];

        // ====== Helper membuat isi cell "SERTIFIKAT" (dropdown + 1 tombol) ======
        function buildCertCell(it) {
        const selected = it.certificate_template || 'certmagangjogjacom';
        const options  = CERT_OPTIONS.map(o =>
            `<option value="${o.value}" ${o.value === selected ? 'selected' : ''}>${o.label}</option>`
        ).join('');

        const base = document.getElementById('tableWrap')?.dataset?.base || '/admin/interns';

        return `
            <div class="flex items-center gap-2">
            <select class="cert-tpl rounded-md border-gray-300 text-xs dark:bg-gray-700"
                    data-intern="${it.id}" style="min-width: 160px;">
                ${options}
            </select>

            <button type="button"
                    class="btn btn-sm btn-primary js-download-cert"
                    data-intern="${it.id}" data-base="${base}">
                Download
            </button>
            </div>
        `;
        }

        function buildStatusCell(item) {
            const cur = item.internship_status || 'waiting';
            const badge = statusMap[cur] || {
                label: cur,
                cls: 'bg-gray-100 text-gray-800 dark:bg-gray-600/20 dark:text-gray-200'
            };
            return `
                <div class="flex min-w-[12rem] items-center justify-between">
                    <span id="badge-${item.id}"
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${badge.cls}">
                        ${badge.label}
                    </span>
                    <form action="${item.status_update_url}" class="inline status-form-row">
                        <select
                            class="status-select-row appearance-none rounded-lg border border-gray-300 bg-white px-2 py-1.5 pr-7 text-xs text-gray-700
                            dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            data-current="${cur}" data-name="${item.fullname || 'pemagang'}" data-id="${item.id}">
                            ${Object.entries(statusMap).map(([val, obj]) =>
                                `<option value="${val}" ${val===cur?'selected':''}>${obj.label}</option>`).join('')}
                        </select>
                    </form>
                </div>`;
        }

        function bindStatusListeners() {
            document.querySelectorAll('.status-select-row').forEach(sel => {
                sel.onchange = function() {
                    const form = this.closest('form');
                    const url = form.getAttribute('action');
                    const id = Number(this.dataset.id);
                    const name = this.dataset.name || 'pemagang';
                    const from = this.dataset.current;
                    const to = this.value;

                    if (to === from) {
                        if (pending.has(id)) {
                            pending.delete(id);
                            markSelect(this, false);
                            updatePendingBar();
                        }
                        return;
                    }

                    // simpan state baru ke pending
                    pending.set(id, {
                        id,
                        name,
                        from,
                        to,
                        url,
                        select: this,
                        badge: document.getElementById(`badge-${id}`)
                    });
                    markSelect(this, true);
                    updatePendingBar();
                };
            });
        }

        function renderRows(payload) {
            const {
                data,
                meta
            } = payload;
            const offset = (meta.current_page - 1) * meta.per_page;

            if (!data || data.length === 0) {
                rowsEl.innerHTML = `
                    <tr>
                        <td colspan="{{ count($fields) + 2 }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Belum ada data.
                        </td>
                    </tr>`;
                pagerEl.innerHTML = '';
                return;
            }

            rowsEl.innerHTML = data.map((it, idx) => {
                window.rowData.set(it.id, it);
                // ==== (ISI KOLOM TETAP, PERSIS seperti punyamu) ====
                return `
                    <tr data-row-id="${it.id}"
                        class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 dark:odd:bg-gray-800 dark:even:bg-gray-800/60 dark:hover:bg-gray-700/60">
                        <td class="px-3 py-2 text-gray-600 dark:text-gray-300">${offset + idx + 1}</td>

                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.fullname)}">${fmtStr(it.fullname)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${fmtStr(it.born_date)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${fmtStr(it.student_id)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${fmtStr(it.email)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${labelize(genderMapID, it.gender)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${fmtStr(it.phone_number)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.institution_name)}">${fmtStr(it.institution_name)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.study_program)}">${fmtStr(it.study_program)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.faculty)}">${fmtStr(it.faculty)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.current_city)}">${fmtStr(it.current_city)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.internship_reason)}">${fmtStr(it.internship_reason)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${labelize(typeMapID, it.internship_type)}">${labelize(typeMapID, it.internship_type)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${labelize(arrangementMapID, it.internship_arrangement)}">${labelize(arrangementMapID, it.internship_arrangement)}</span></td>
                        <td class="px-3 py-2 align-top">
                            <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-medium ${it.current_status==='Fresh Graduate'
                                ? 'bg-slate-100 text-slate-800 dark:bg-slate-600/20 dark:text-slate-300'
                                : (it.current_status==='Student'
                                ? 'bg-sky-100 text-sky-800 dark:bg-sky-600/20 dark:text-sky-300'
                                : 'bg-gray-100 text-gray-800 dark:bg-gray-600/20 dark:text-gray-200')}">
                                ${labelize(statusNowMapID, it.current_status)}
                            </span>
                        </td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.english_book_ability)}">${fmtStr(it.english_book_ability)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.supervisor_contact)}">${fmtStr(it.supervisor_contact)}</span></td>

                        <td class="px-3 py-2 align-top">
                            <span class="block max-w-[18rem] truncate" title="${interestLabelID(it.internship_interest)}">
                                ${interestLabelID(it.internship_interest)}
                            </span>
                        </td>

                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.internship_interest_other)}">${fmtStr(it.internship_interest_other)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.design_software)}">${fmtStr(it.design_software)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.video_software)}">${fmtStr(it.video_software)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.programming_languages)}">${fmtStr(it.programming_languages)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.digital_marketing_type)}">${fmtStr(it.digital_marketing_type)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.digital_marketing_type_other)}">${fmtStr(it.digital_marketing_type_other)}</span></td>

                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${labelize(yesNoMapID, it.laptop_equipment)}">${labelize(yesNoMapID, it.laptop_equipment)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.owned_tools)}">${fmtStr(it.owned_tools)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.owned_tools_other)}">${fmtStr(it.owned_tools_other)}</span></td>

                        <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${fmtStr(it.start_date)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${fmtStr(it.end_date)}</span></td>

                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.internship_info_sources)}">${fmtStr(it.internship_info_sources)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.internship_info_other)}">${fmtStr(it.internship_info_other)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.current_activities)}">${fmtStr(it.current_activities)}</span></td>

                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.boarding_info)}">${fmtStr(it.boarding_info)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="block max-w-[18rem] truncate" title="${fmtStr(it.family_status)}">${fmtStr(it.family_status)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${fmtStr(it.parent_wa_contact)}</span></td>
                        <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${fmtStr(it.social_media_instagram)}</span></td>

                        <td class="px-3 py-2 align-top">${
                            it.cv_ktp_portofolio_pdf
                                ? `<a href="${it.cv_ktp_portofolio_pdf}" target="_blank" class="text-emerald-600 underline hover:text-emerald-700">Lihat</a>`
                                : '<span class="text-gray-400">-</span>'}
                        </td>
                        <td class="px-3 py-2 align-top">${
                            it.portofolio_visual
                                ? `<a href="${it.portofolio_visual}" target="_blank" class="text-emerald-600 underline hover:text-emerald-700">Lihat</a>`
                                : '<span class="text-gray-400">-</span>'}
                        </td>

                        <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${fmtDate(it.created_at)}</span></td>

                        <td class="px-3 py-2 align-top">
                            ${buildStatusCell(it)}
                        </td>
                        ${
                        SCOPE === 'completed'
                            ? `<td class="px-3 py-2 align-top">${buildCertCell(it)}</td>`
                            : ''
                        }
                        <td class="px-3 py-2 align-top">
                            ${buildActionCell(it)}
                        </td>
                    </tr>
                `;
            }).join('');

            bindStatusListeners();
            buildPager(meta);
            hydrateSelectOptions();
            applyColumnFilters();
        }

        function buildPager(meta) {
            const {
                current_page,
                last_page
            } = meta;
            const prev = current_page > 1 ? current_page - 1 : null;
            const next = current_page < last_page ? current_page + 1 : null;

            pagerEl.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        Halaman <strong>${current_page}</strong> dari <strong>${last_page}</strong>
                    </div>
                    <div class="flex gap-2">
                        <button ${!prev?'disabled':''} data-goto="${prev||''}"
                            class="rounded-lg border px-3 py-2 text-sm disabled:opacity-50">Sebelumnya</button>
                        <button ${!next?'disabled':''} data-goto="${next||''}"
                            class="rounded-lg border px-3 py-2 text-sm disabled:opacity-50">Berikutnya</button>
                    </div>
                </div>
            `;

            pagerEl.querySelectorAll('button[data-goto]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const p = Number(btn.dataset.goto);
                    if (p) loadPage(p);
                });
            });
        }

        // ========== AKSI: BATALKAN ==========
        discardBtn ?.addEventListener('click', () => {
            if (pending.size === 0) return;
            for (const {
                    select,
                    from
                } of pending.values()) {
                select.value = from;
                // tetap biarkan dataset.current tidak berubah; commit UI saja
                markSelect(select, false);
            }
            pending.clear();
            updatePendingBar();
            pushToast('Semua perubahan dibatalkan.', 'success');
        });

        // Helper: batasi paralel request agar tidak membebani server
        async function runWithConcurrency(tasks, limit = 4) {
            const results = [];
            let i = 0;
            const workers = Array.from({
                length: Math.min(limit, tasks.length)
            }, async () => {
                while (i < tasks.length) {
                    const cur = i++;
                    try {
                        results[cur] = await tasks[cur]();
                    } catch (e) {
                        results[cur] = e;
                    }
                }
            });
            await Promise.all(workers);
            return results;
        }

        // ========== AKSI: SIMPAN ==========
        saveAllBtn ?.addEventListener('click', async () => {
            if (pending.size === 0) return;

            const items = Array.from(pending.values());
            saveAllBtn.disabled = true;
            discardBtn.disabled = true;

            const tasks = items.map(item => async () => {
                // jika endpoint-mu memakai field berbeda, ganti di sini:
                await patchForm(item.url, {
                    internship_status: item.to
                });
                return item;
            });

            try {
                const results = await runWithConcurrency(tasks, 4);

                let ok = 0,
                    fail = 0;
                const failed = [];

                results.forEach((res, idx) => {
                    if (res instanceof Error) {
                        fail++;
                        failed.push({
                            item: items[idx],
                            err: res
                        });
                        return;
                    }
                    ok++;

                    const it = res; // item yang berhasil
                    it.select.dataset.current = it.to; // commit state baru
                    markSelect(it.select, false);
                    applyBadge(it.badge, it.to);

                    pending.delete(it.id);
                });

                updatePendingBar();

                if (ok) pushToast(`${ok} perubahan disimpan.`, 'success');
                if (fail) {
                    failed.forEach(({
                        item
                    }) => { // tetap pending
                        item.select.value = item.to;
                        markSelect(item.select, true);
                    });
                    pushToast(`${fail} perubahan gagal disimpan. Coba lagi.`, 'error');
                }
            } catch (e) {
                pushToast('Gagal menyimpan perubahan.', 'error');
            } finally {
                saveAllBtn.disabled = pending.size === 0;
                discardBtn.disabled = false;
            }
        });

        // ===== Loader API
        async function loadPage(page = 1, perPage = 1000, searchQuery = '') {
            const params = new URLSearchParams({
                scope: SCOPE,
                page: String(page),
                per_page: searchQuery ? '1000' : String(perPage),
                search: searchQuery
            });

            rowsEl.innerHTML = `
                <tr>
                    <td colspan="{{ count($fields) + 2 }}" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                        Memuat data…
                    </td>
                </tr>`;

            try {
                const url = `${API_URL}?${params.toString()}`;
                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                if (!res.ok) {
                    const txt = await res.text().catch(() => '');
                    throw new Error(`HTTP ${res.status} ${res.statusText} ${txt}`);
                }
                const json = await res.json();
                renderRows(json);

                // simpan halaman aktif
                window.__CURRENT_PAGE = page;

                // saat searching, sembunyikan pagination
                if (searchQuery) pagerEl.innerHTML = '';
            } catch (e) {
                console.error('Error loading page data:', e);
                rowsEl.innerHTML = `
                    <tr>
                        <td colspan="{{ count($fields) + 2 }}" class="px-6 py-6 text-center text-rose-600">
                            Gagal memuat data.
                        </td>
                    </tr>`;
            }
        } // <<< AKHIR fungsi loadPage

        // ====== EKSPOR HELPER (agar tombol Edit/Hapus bisa refresh tabel)
        window.__CURRENT_PAGE = 1;
        window.reloadInterns = (p) => {
            const page = p || window.__CURRENT_PAGE || 1;
            loadPage(page);
        }; // <<< JANGAN LUPA TUTUP

        // ====== INIT
        bindFilterInputs();
        loadPage(Number(new URLSearchParams(location.search).get('page') || 1));

        // Peringatan unload jika masih ada pending
        window.addEventListener('beforeunload', (e) => {
            if (pending.size > 0) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    });
</script>
@endsection


@push('scripts')
<script>
    // gunakan Map dari script utama
    const rowData = window.rowData || new Map();
    const ADMIN_INTERNS_BASE = @json(url('/admin/interns'));
    const csrf = document.querySelector('meta[name="csrf-token"]') ?.getAttribute('content') || '';

    // ===== Util Modal =====
    const $ = (id) => document.getElementById(id);

    function openModal(titleStr, html, opts = {}) {
        const appModal = $('appModal');
        const appDialog = $('appModalDialog');
        const appTitle = $('appModalTitle');
        const appBody = $('appModalBody');
        if (!appModal || !appTitle || !appBody) return console.error('Modal tidak ditemukan');

        const size = opts.size || 'md'; // md|lg|xl
        if (appDialog) {
            appDialog.classList.remove('max-w-md', 'max-w-lg', 'max-w-xl', 'max-w-2xl', 'max-w-3xl', 'max-w-4xl');
            appDialog.classList.add(size === 'md' ? 'max-w-2xl' : size === 'lg' ? 'max-w-3xl' : 'max-w-4xl');
        }
        appTitle.textContent = titleStr;
        appBody.innerHTML = html;
        appModal.classList.remove('hidden');
    }

    function closeModal() {
        $('appModal') ?.classList.add('hidden');
        const body = $('appModalBody');
        if (body) body.innerHTML = '';
    }
    $('appModal') ?.addEventListener('click', (e) => {
        if (e.target.hasAttribute('data-modal-close')) closeModal();
    });

    // ===== Confirm Modal =====
    let confirmCb = null;

    function openConfirm(message, onYes) {
        $('confirmBody').innerHTML = message || 'Yakin?';
        confirmCb = onYes;
        $('confirmModal') ?.classList.remove('hidden');
    }

    function closeConfirm() {
        $('confirmModal') ?.classList.add('hidden');
        confirmCb = null;
    }
    $('confirmModal') ?.addEventListener('click', e => {
        if (e.target.hasAttribute('data-confirm-close')) closeConfirm();
    });
    $('confirmYes') ?.addEventListener('click', async () => {
        try {
            if (confirmCb) await confirmCb();
        } finally {
            closeConfirm();
        }
    });

    // ===== Helpers =====
    const h = (s) => String(s ?? '').replace(/[&<>"']/g, m => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;'
    }[m]));

    function renderDetailHTML(it) {
        const rows = [
            ['Nama Lengkap', it.fullname],
            ['Email', it.email],
            ['No. WA', it.phone_number],
            ['Institusi', it.institution_name],
            ['Prodi / Fakultas', [it.study_program, it.faculty].filter(Boolean).join(' / ')],
            ['Domisili', it.current_city],
            ['Tipe / Skema', [it.internship_arrangement, it.internship_type].filter(Boolean).join(' · ')],
            ['Tanggal', [it.start_date, it.end_date].filter(Boolean).join(' s/d ')],
            ['Status', it.internship_status],
            ['Alasan Magang', it.internship_reason],
            ['Minat', it.internship_interest],
        ];
        return `
            <div class="space-y-4">
                <div class="max-h-[70vh] overflow-auto">
                    <table class="w-full rounded-lg border dark:border-gray-700 overflow-hidden">
                        ${rows.map(([k, v]) => `
                            <tr class="border-b last:border-0 dark:border-gray-700">
                                <td class="w-44 px-3 py-2 text-sm text-gray-500 dark:text-gray-400">${h(k)}</td>
                                <td class="break-words whitespace-normal px-3 py-2 text-sm text-gray-800 dark:text-gray-100">${h(v)}</td>
                            </tr>`).join('')}
                    </table>
                </div>
            </div>`;
    }

    // ===== Edit form (modal, polished UI) =====
    function openEditForm(it) {
        // ——— helpers ———
        const monthsID = {
            Januari:'01', Februari:'02', Maret:'03', April:'04', Mei:'05', Juni:'06',
            Juli:'07', Agustus:'08', September:'09', Oktober:'10', November:'11', Desember:'12'
        };
        const pad2 = (n) => String(n).padStart(2, '0');

        // normalisasi ke YYYY-MM-DD utk <input type="date">
        function toInputDate(s) {
            if (!s) return '';
            const str = String(s).trim();

            // sudah YYYY-MM-DD
            if (/^\d{4}-\d{2}-\d{2}$/.test(str)) return str;

            // dd/mm/yyyy
            const m1 = str.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
            if (m1) return `${m1[3]}-${pad2(m1[2])}-${pad2(m1[1])}`;

            // “23 Oktober 1999”
            const m2 = str.match(/^(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})$/);
            if (m2 && monthsID[m2[2]]) return `${m2[3]}-${monthsID[m2[2]]}-${pad2(m2[1])}`;

            // fallback: biarkan apa adanya
            return str;
        }

        const initials = (name) =>
            (name || '')
            .split(/\s+/)
            .filter(Boolean)
            .slice(0, 2)
            .map(w => w[0]?.toUpperCase() || '')
            .join('') || 'IN';

        const vBorn  = toInputDate(it.born_date);
        const vStart = toInputDate(it.start_date);
        const vEnd   = toInputDate(it.end_date);

        const baseInput =
            "w-full rounded-xl border border-gray-200 bg-white/80 p-3 shadow-sm " +
            "focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 " +
            "placeholder:text-gray-400";

        const baseLabel = "text-sm font-medium text-gray-700";

        const html = `
        <form id="editForm" data-id="${it.id}" class="space-y-5 max-h-[70vh] overflow-y-auto pr-1">
            <!-- Header mini profile -->
            <div class="flex items-center gap-3 rounded-2xl border border-gray-100 bg-gradient-to-r from-white to-gray-50 p-4">
            <div class="h-12 w-12 shrink-0 rounded-full bg-indigo-600/10 text-indigo-700 grid place-items-center font-semibold">
                ${initials(it.fullname)}
            </div>
            <div class="min-w-0">
                <p class="text-sm text-gray-500">Edit Pemagang</p>
                <p class="truncate text-base font-semibold text-gray-800">${h(it.fullname || '-')}</p>
            </div>
            </div>

            <!-- Identitas -->
            <fieldset class="rounded-2xl border border-gray-100 bg-white p-4">
            <legend class="px-2 text-sm font-semibold text-gray-800">Identitas</legend>
            <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                <label class="${baseLabel}" for="fullname">Nama Lengkap</label>
                <input id="fullname" name="fullname" value="${h(it.fullname)}" class="${baseInput}" required />
                </div>
                <div>
                <label class="${baseLabel}" for="born_date">Tanggal Lahir</label>
                <input id="born_date" name="born_date" type="date" value="${h(vBorn)}" class="${baseInput}" />
                <p class="mt-1 text-xs text-gray-500">Opsional. Format otomatis.</p>
                </div>
                <div>
                <label class="${baseLabel}" for="student_id">NIM / NIS</label>
                <input id="student_id" name="student_id" value="${h(it.student_id)}" class="${baseInput}" />
                </div>
                <div>
                <label class="${baseLabel}" for="email">Email</label>
                <input id="email" name="email" type="email" value="${h(it.email || '')}" class="${baseInput}" />
                </div>
                <div>
                <label class="${baseLabel}" for="phone_number">Telepon</label>
                <input id="phone_number" name="phone_number" value="${h(it.phone_number || '')}" class="${baseInput}" inputmode="tel" />
                </div>
                <div>
                <label class="${baseLabel}" for="current_city">Kota</label>
                <input id="current_city" name="current_city" value="${h(it.current_city || '')}" class="${baseInput}" />
                </div>
            </div>
            </fieldset>

            <!-- Akademik -->
            <fieldset class="rounded-2xl border border-gray-100 bg-white p-4">
            <legend class="px-2 text-sm font-semibold text-gray-800">Akademik</legend>
            <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                <label class="${baseLabel}" for="institution_name">Institusi</label>
                <input id="institution_name" name="institution_name" value="${h(it.institution_name || '')}" class="${baseInput}" />
                </div>
                <div>
                <label class="${baseLabel}" for="faculty">Fakultas</label>
                <input id="faculty" name="faculty" value="${h(it.faculty || '')}" class="${baseInput}" />
                </div>
                <div>
                <label class="${baseLabel}" for="study_program">Prodi</label>
                <input id="study_program" name="study_program" value="${h(it.study_program || '')}" class="${baseInput}" />
                </div>
                <div class="sm:col-span-2">
                <label class="${baseLabel}" for="internship_reason">Alasan Magang</label>
                <textarea id="internship_reason" name="internship_reason" rows="3" class="${baseInput}">${h(it.internship_reason || '')}</textarea>
                </div>
            </div>
            </fieldset>

            <!-- Penugasan -->
            <fieldset class="rounded-2xl border border-gray-100 bg-white p-4">
            <legend class="px-2 text-sm font-semibold text-gray-800">Penugasan</legend>
            <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                <label class="${baseLabel}" for="internship_type">Tipe Magang</label>
                <select id="internship_type" name="internship_type" class="${baseInput}">
                    <option value="remote" ${it.internship_type === 'remote' ? 'selected' : ''}>WFH</option>
                    <option value="onsite" ${it.internship_type === 'onsite' ? 'selected' : ''}>WFO</option>
                </select>
                </div>
                <div>
                <label class="${baseLabel}" for="start_date">Mulai</label>
                <input id="start_date" name="start_date" type="date" value="${h(vStart)}" class="${baseInput}" />
                </div>
                <div>
                <label class="${baseLabel}" for="end_date">Selesai</label>
                <input id="end_date" name="end_date" type="date" value="${h(vEnd)}" class="${baseInput}" />
                </div>
            </div>
            </fieldset>

            <!-- Footer actions (sticky) -->
            <div class="sticky bottom-0 -mx-4 border-t bg-white/70 px-4 pt-4 backdrop-blur">
            <div class="flex justify-end gap-2">
                <button type="button" id="btnCancelEdit"
                class="rounded-xl border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-100">Batal</button>
                <button type="submit" id="btnSaveEdit"
                class="rounded-xl bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700 disabled:opacity-60">Simpan</button>
            </div>
            </div>
        </form>
        `;

        openModal('Edit Pemagang', html, { size: 'lg' });

        $('btnCancelEdit')?.addEventListener('click', closeModal);

        $('editForm')?.addEventListener('submit', async (ev) => {
            ev.preventDefault();
            const form = ev.currentTarget;
            const id = Number(form.dataset.id);
            const fd = new FormData(form);

            // Safeguard: jika born_date kosong, kirim nilai lama
            if (!fd.get('born_date')) fd.set('born_date', toInputDate(it.born_date || ''));

            fd.append('_method', 'PATCH');

            const btn = $('btnSaveEdit');
            btn?.setAttribute('disabled', 'true');

            try {
            const res = await fetch(`${ADMIN_INTERNS_BASE}/${id}`, {
                method: 'POST',
                body: fd,
                headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error(await res.text().catch(()=>'Gagal menyimpan'));
            closeModal();
            window.reloadInterns?.();
            pushToast('Data berhasil disimpan.', 'success');
            } catch (err) {
            console.error(err);
            pushToast('Gagal menyimpan data.', 'error');
            } finally {
            btn?.removeAttribute('disabled');
            }
        });
    }



    // ===== Delete =====
    async function deleteIntern(id) {
        const fd = new FormData();
        fd.append('_method', 'DELETE');
        const res = await fetch(`${ADMIN_INTERNS_BASE}/${id}`, {
            method: 'POST',
            body: fd,
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        if (!res.ok) throw new Error(await res.text().catch(() => 'Gagal menghapus'));
    }


    // ===== Event delegation untuk tombol di kolom AKSI =====
    document.addEventListener('click', async (e) => {
        // DETAIL
        const dBtn = e.target.closest('.js-detail');
        if (dBtn) {
            e.preventDefault();
            const id = Number(dBtn.dataset.id);
            const it = rowData.get(id);
            if (it) openModal('Detail Pemagang', renderDetailHTML(it), {
                size: 'md'
            });
            return;
        }

        // EDIT
        const eBtn = e.target.closest('.js-edit');
        if (eBtn) {
            e.preventDefault();
            const id = Number(eBtn.dataset.id);
            const it = rowData.get(id);
            if (it) openEditForm(it); // <— bukan iframe
            return;
        }

        // HAPUS
        const hBtn = e.target.closest('.js-delete');
        if (hBtn) {
            e.preventDefault();
            const id = Number(hBtn.dataset.id);
            openConfirm('Yakin ingin menghapus data ini?', async () => {
                try {
                    await deleteIntern(id);
                    document.querySelector(`tr[data-row-id="${id}"]`) ?.remove();
                    window.reloadInterns ?.();
                    pushToast('Data berhasil dihapus.', 'success');
                } catch (err) {
                    console.error(err);
                    pushToast('Gagal menghapus data.', 'error');
                }
            });
            return;
        }
    });
</script>
@endpush
