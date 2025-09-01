@extends('layouts.dashboard')

@php
    use Illuminate\Support\Str;

    // fallback scope kalau belum dikirim controller (infer dari route)
    if (!isset($scope)) {
        $scope = 'all';
        $route = request()->route()?->getName();
        $mapRouteScope = [
            'admin.interns.active'    => 'active',
            'admin.interns.completed' => 'completed',
            'admin.interns.exited'    => 'exited',
            'admin.interns.pending'   => 'pending',
        ];
        if ($route && isset($mapRouteScope[$route])) $scope = $mapRouteScope[$route];
    }
@endphp

@section('content')
<div class="px-4 pt-6">

    {{-- Header + Tabs --}}
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title ?? 'Semua Pemagang' }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Daftar pemagang berdasarkan filter.</p>

            <div class="mt-4 flex gap-2 flex-wrap">
                <a href="{{ route('admin.interns.index') }}"
                   class="px-3 py-2 rounded-lg text-sm {{ ($scope ?? '')==='all' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                    Semua
                </a>
                <a href="{{ route('admin.interns.active') }}"
                   class="px-3 py-2 rounded-lg text-sm {{ ($scope ?? '')==='active' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                    Aktif
                </a>
                <a href="{{ route('admin.interns.completed') }}"
                   class="px-3 py-2 rounded-lg text-sm {{ ($scope ?? '')==='completed' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                    Selesai
                </a>
                <a href="{{ route('admin.interns.exited') }}"
                   class="px-3 py-2 rounded-lg text-sm {{ ($scope ?? '')==='exited' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                    Keluar
                </a>
                <a href="{{ route('admin.interns.pending') }}"
                   class="px-3 py-2 rounded-lg text-sm {{ ($scope ?? '')==='pending' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                    Pending
                </a>
            </div>
        </div>

        {{-- Kanan: Search + Pending Bar + Toast --}}
        <div class="mt-1 flex w-full max-w-[420px] flex-col items-end gap-2">
            <form id="searchForm" class="w-full">
                <div class="flex items-center gap-2">
                    <input id="q" type="text" value="{{ request('q') }}"
                           placeholder="Cari nama, email, NIM/NIS, HP, institusi, prodi, kota, gender…"
                           class="w-full px-3 py-2 rounded-lg border
                                  border-gray-300 dark:border-gray-700
                                  bg-white dark:bg-gray-800
                                  text-sm text-gray-900 dark:text-gray-100
                                  placeholder-gray-500 dark:placeholder-gray-400
                                  caret-emerald-500
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <button class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                        Cari
                    </button>
                    <button id="resetFilters" type="button"
                            class="px-3 py-2 rounded-lg border text-sm dark:border-gray-700">
                      Reset
                    </button>
                </div>
            </form>

            {{-- Pending bar --}}
            <div id="pendingBar"
                 class="hidden rounded-full border border-amber-200 bg-amber-50/95
                        px-3 py-2 text-amber-900 shadow-sm
                        dark:bg-amber-900/40 dark:border-amber-700 dark:text-amber-200">
                <div class="flex items-center gap-2">
                    <span class="text-[12px]/none">
                        <strong id="pendingCount">0</strong> perubahan belum disimpan
                    </span>
                    <button id="discardAll"
                            class="rounded-full border border-amber-300 px-2 py-1 text-[12px] text-amber-800 hover:bg-amber-100
                                   dark:border-amber-700 dark:text-amber-200 dark:hover:bg-amber-900/60">
                        Batalkan
                    </button>
                    <button id="saveAll" disabled
                            class="rounded-full bg-emerald-600 px-3 py-1 text-[12px] text-white hover:bg-emerald-700 disabled:opacity-50">
                        Simpan
                    </button>
                </div>
            </div>

            {{-- Toast stack --}}
            <div id="toastStack" class="w-full flex flex-col items-end gap-2"></div>
        </div>
    </div>

    {{-- Card --}}
    <div class="rounded-xl bg-white dark:bg-gray-800 shadow ring-1 ring-gray-200 dark:ring-gray-700">

        {{-- Table --}}
        <div id="tableWrap" class="overflow-x-auto">
            <table class="min-w-full w-max text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="sticky top-0 z-10 text-xs uppercase tracking-wider
                              bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">

                    @php
                        // daftar kolom
                        $fields = [
                            'fullname' => 'NAMA LENGKAP',
                            'born_date' => 'TANGGAL LAHIR',
                            'student_id' => 'NIM / NIS',
                            'email' => 'EMAIL',
                            'gender' => 'GENDER',
                            'phone_number' => 'TELEPON',
                            'institution_name' => 'INSTITUSI',
                            'study_program' => 'PRODI',
                            'faculty' => 'FAKULTAS',
                            'current_city' => 'KOTA',
                            'internship_reason' => 'ALASAN MAGANG',
                            'internship_type' => 'JENIS MAGANG',
                            'internship_arrangement' => 'TIPE MAGANG',
                            'current_status' => 'STATUS SAAT INI',
                            'english_book_ability' => 'BACA B.INGGRIS',
                            'supervisor_contact' => 'KONTAK PEMBIMBING',
                            'internship_interest' => 'BIDANG MINAT',
                            'internship_interest_other' => 'MINAT LAIN',
                            'design_software' => 'SOFTWARE DESAIN',
                            'video_software' => 'SOFTWARE VIDEO',
                            'programming_languages' => 'BAHASA PEMROGRAMAN',
                            'digital_marketing_type' => 'DIGITAL MARKETING',
                            'digital_marketing_type_other' => 'MARKETING LAIN',
                            'laptop_equipment' => 'PUNYA LAPTOP',
                            'owned_tools' => 'ALAT DIMILIKI',
                            'owned_tools_other' => 'ALAT LAIN',
                            'start_date' => 'MULAI',
                            'end_date' => 'SELESAI',
                            'internship_info_sources' => 'SUMBER INFO',
                            'internship_info_other' => 'INFO LAIN',
                            'current_activities' => 'AKTIVITAS SAAT INI',
                            'boarding_info' => 'INFO KOST',
                            'family_status' => 'SUDAH BERKELUARGA',
                            'parent_wa_contact' => 'KONTAK ORANG TUA',
                            'social_media_instagram' => 'INSTAGRAM',
                            'cv_ktp_portofolio_pdf' => 'FILE PDF',
                            'portofolio_visual' => 'FILE VISUAL',
                            'created_at' => 'DIBUAT',
                            'internship_status' => 'STATUS',
                        ];
                        if (($scope ?? '') === 'completed') {
                            $fields['certificate'] = 'SERTIFIKAT';
                        }
                    @endphp

                    {{-- Row: Header Judul Kolom --}}
                    <tr class="divide-x divide-gray-200 dark:divide-gray-600">
                        <th class="px-3 py-3 font-semibold whitespace-nowrap">No</th>
                        @foreach ($fields as $label)
                            <th class="px-3 py-3 font-semibold whitespace-nowrap">{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody id="rows" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td colspan="{{ count($fields) + 1 }}" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                            Memuat data…
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination placeholder (dibangun via JS) --}}
        <div id="pager" class="px-6 py-4"></div>
    </div>

</div>

{{-- Script: fetch API -> render tabel + fitur edit status (pending, konfirmasi, toast) --}}
<script>
// Debounce helper (kalau nanti butuh)
function debounce(fn, ms=400) {
  let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
}

document.addEventListener('DOMContentLoaded', () => {
  // ====== Clamp scroll kanan hanya di tabel ======
  const wrap = document.getElementById('tableWrap');
  if (wrap) {
    wrap.scrollLeft = 0;
    wrap.addEventListener('scroll', () => {
      const max = wrap.scrollWidth - wrap.clientWidth;
      if (wrap.scrollLeft > max) wrap.scrollLeft = max;
      if (wrap.scrollLeft < 0) wrap.scrollLeft = 0;
    }, { passive: true });
  }

  const API_URL = @json(route('admin.interns.api'));
  const SCOPE   = @json($scope ?? 'all');
  const csrf    = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  const rowsEl  = document.getElementById('rows');
  const pagerEl = document.getElementById('pager');
  const searchForm = document.getElementById('searchForm');
  const qInput  = document.getElementById('q');

  // ====== Status map (badge + label) ======
  const statusMap = {
    new:       {label:'Pendaftar Baru', cls:'bg-teal-100 text-teal-800 dark:bg-teal-600/20 dark:text-teal-300'},
    active:    {label:'Aktif',          cls:'bg-blue-100 text-blue-800 dark:bg-blue-600/20 dark:text-blue-300'},
    completed: {label:'Selesai',        cls:'bg-indigo-100 text-indigo-800 dark:bg-indigo-600/20 dark:text-indigo-300'},
    exited:    {label:'Keluar',         cls:'bg-rose-100 text-rose-800 dark:bg-rose-600/20 dark:text-rose-300'},
    pending:   {label:'Pending',        cls:'bg-amber-100 text-amber-800 dark:bg-amber-600/20 dark:text-amber-300'},
  };

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
    'male':'Laki-laki','laki-laki':'Laki-laki','pria':'Laki-laki','m':'Laki-laki',
    'female':'Perempuan','perempuan':'Perempuan','wanita':'Perempuan','f':'Perempuan'
  };

  const statusNowMapID = {
    'Fresh Graduate':'Lulusan Baru',
    'Student':'Mahasiswa/Pelajar',
    'Employee':'Karyawan',
    'Unemployed':'Tidak Bekerja',
  };

  const arrangementMapID = { // TIPE MAGANG (cara kerja)
    'onsite':'Onsite', 'hybrid':'Hibrida', 'remote':'Remote'
  };

  const typeMapID = { // JENIS MAGANG (skema)
    'campus':'Magang Kampus', 'mandiri':'Magang Mandiri', 'pkl':'PKL',
    'kampus-merdeka':'Kampus Merdeka', 'mbkm':'Kampus Merdeka'
  };

  const yesNoMapID = {
    'yes':'Ya','y':'Ya','true':'Ya','1':'Ya','ya':'Ya',
    'no':'Tidak','n':'Tidak','false':'Tidak','0':'Tidak','tidak':'Tidak'
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
    return interestMapID[key] ?? String(slug).replace(/-/g,' ').replace(/\b\w/g, c => c.toUpperCase());
  }

  // created_at dari server → tetap format tanggal
  const fmtDate = (s) => {
    if (!s) return '-';
    const d = new Date(s);
    if (isNaN(d)) return String(s);
    return d.toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' });
  };

  // ====== Toast helpers ======
  const toastStack = document.getElementById('toastStack');
  function pushToast(message, type='success') {
    const base = 'flex items-center gap-2 rounded-lg border px-3 py-2 text-sm shadow-sm';
    const theme = type === 'success'
      ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-200'
      : 'bg-rose-50 border-rose-200 text-rose-800 dark:bg-rose-900/20 dark:border-rose-700 dark:text-rose-200';
    const el = document.createElement('div');
    el.className = `${base} ${theme}`;
    el.innerHTML = `<span>${message}</span>
                    <button class="ml-2 rounded px-2 py-1 text-xs opacity-70 hover:opacity-100">Tutup</button>`;
    el.querySelector('button').addEventListener('click', () => el.remove());
    toastStack.appendChild(el);
    setTimeout(() => el.remove(), 4000);
  }

  // ====== Pending bar state ======
  const pendingBar   = document.getElementById('pendingBar');
  const pendingCount = document.getElementById('pendingCount');
  const saveAllBtn   = document.getElementById('saveAll');
  const discardBtn   = document.getElementById('discardAll');
  const pending = new Map(); // key: id, value: {id,name,from,to,url,select,badge}

  function updatePendingBar(){
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
  function applyBadge(badgeEl, newVal){
    const m = statusMap[newVal] || {label:newVal, cls:'bg-gray-100 text-gray-800 dark:bg-gray-600/20 dark:text-gray-200'};
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
      headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With':'XMLHttpRequest' },
      credentials: 'same-origin'
    });
    if (!res.ok) {
      const t = await res.text().catch(()=>'');
      throw new Error(t || `HTTP ${res.status}`);
    }
    return res;
  }

  function buildStatusCell(item){
    const cur = item.internship_status || 'new';
    const badge = statusMap[cur] || {label: cur, cls:'bg-gray-100 text-gray-800 dark:bg-gray-600/20 dark:text-gray-200'};
    return `
      <div class="flex items-center gap-2 min-w-[12rem] justify-between">
        <span id="badge-${item.id}"
              class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${badge.cls}">
          ${badge.label}
        </span>
        <form action="${item.status_update_url}" class="inline status-form-row">
          <select
            class="status-select-row text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800
                   text-gray-700 dark:text-gray-200 px-2 py-1.5 pr-7 appearance-none
                   focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
            data-current="${cur}" data-name="${item.fullname || 'pemagang'}" data-id="${item.id}">
              ${Object.entries(statusMap).map(([val, obj]) =>
                 `<option value="${val}" ${val===cur?'selected':''}>${obj.label}</option>`).join('')}
          </select>
        </form>
      </div>
    `;
  }

  function bindStatusListeners(){
    document.querySelectorAll('.status-select-row').forEach(sel => {
      sel.onchange = function(){
        const form  = this.closest('form');
        const url   = form.getAttribute('action');
        const id    = Number(this.dataset.id);
        const name  = this.dataset.name || 'pemagang';
        const from  = this.dataset.current;
        const to    = this.value;

        if (to === from) {
          if (pending.has(id)) {
            pending.delete(id);
            markSelect(this, false);
            updatePendingBar();
          }
          return;
        }

        // simpan state baru ke pending
        pending.set(id, {id, name, from, to, url, select: this, badge: document.getElementById(`badge-${id}`)});
        markSelect(this, true);
        updatePendingBar();
      };
    });
  }

  function renderRows(payload){
    const { data, meta } = payload;
    const offset = (meta.current_page - 1) * meta.per_page;

    if (!data || data.length === 0) {
      rowsEl.innerHTML = `
        <tr>
          <td colspan="{{ count($fields) + 1 }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
            Belum ada data.
          </td>
        </tr>`;
      pagerEl.innerHTML = '';
      return;
    }

    rowsEl.innerHTML = data.map((it, idx) => {
      return `
        <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100
                   dark:odd:bg-gray-800 dark:even:bg-gray-800/60 dark:hover:bg-gray-700/60">
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
              ? `<a href="${it.cv_ktp_portofolio_pdf}" target="_blank" class="text-emerald-600 hover:text-emerald-700 underline">Lihat</a>`
              : '<span class="text-gray-400">-</span>'}
          </td>
          <td class="px-3 py-2 align-top">${
            it.portofolio_visual
              ? `<a href="${it.portofolio_visual}" target="_blank" class="text-emerald-600 hover:text-emerald-700 underline">Lihat</a>`
              : '<span class="text-gray-400">-</span>'}
          </td>

          <td class="px-3 py-2 align-top"><span class="whitespace-nowrap">${fmtDate(it.created_at)}</span></td>

          <td class="px-3 py-2 align-top">
            ${buildStatusCell(it)}
          </td>
          ${SCOPE === 'completed'
            ? `<td class="px-3 py-2 align-top">
                  <a href="${it.certificate_url}"
                    class="inline-flex items-center gap-1 rounded-lg border px-3 py-1.5 text-xs
                            hover:bg-emerald-50 dark:hover:bg-emerald-900/20">
                    Download
                  </a>
              </td>`
            : ''
          }
        </tr>
      `;
    }).join('');

    bindStatusListeners();
    buildPager(meta);
  }

  function buildPager(meta){
    const { current_page, last_page } = meta;
    const prev = current_page > 1 ? current_page - 1 : null;
    const next = current_page < last_page ? current_page + 1 : null;

    pagerEl.innerHTML = `
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-600 dark:text-gray-300">
          Halaman <strong>${current_page}</strong> dari <strong>${last_page}</strong>
        </div>
        <div class="flex gap-2">
          <button ${!prev?'disabled':''} data-goto="${prev||''}"
            class="px-3 py-2 rounded-lg border text-sm disabled:opacity-50">Sebelumnya</button>
          <button ${!next?'disabled':''} data-goto="${next||''}"
            class="px-3 py-2 rounded-lg border text-sm disabled:opacity-50">Berikutnya</button>
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
  discardBtn?.addEventListener('click', () => {
    if (pending.size === 0) return;
    for (const {select, from} of pending.values()) {
      select.value = from;
      // tetap biarkan dataset.current tidak berubah; commit UI saja
      markSelect(select, false);
    }
    pending.clear();
    updatePendingBar();
    pushToast('Semua perubahan dibatalkan.', 'success');
  });

  // Helper: batasi paralel request agar tidak membebani server
  async function runWithConcurrency(tasks, limit=4){
    const results = [];
    let i = 0;
    const workers = Array.from({length: Math.min(limit, tasks.length)}, async () => {
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
  saveAllBtn?.addEventListener('click', async () => {
    if (pending.size === 0) return;

    const items = Array.from(pending.values());
    saveAllBtn.disabled = true;
    discardBtn.disabled = true;

    const tasks = items.map(item => async () => {
      // jika endpoint-mu memakai field berbeda, ganti di sini:
      await patchForm(item.url, { internship_status: item.to });
      return item;
    });

    try {
      const results = await runWithConcurrency(tasks, 4);

      let ok = 0, fail = 0;
      const failed = [];

      results.forEach((res, idx) => {
        if (res instanceof Error) {
          fail++;
          failed.push({item: items[idx], err: res});
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

      if (ok)  pushToast(`${ok} perubahan disimpan.`, 'success');
      if (fail){
        failed.forEach(({item}) => { // tetap pending
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
  async function loadPage(page=1){
    const perPage = 15;
    const params = new URLSearchParams({
      scope: SCOPE,
      page: String(page),
      per_page: String(perPage),
    });

    const q = (qInput?.value || '').trim();
    if (q) params.set('q', q);

    rowsEl.innerHTML = `
      <tr>
        <td colspan="{{ count($fields) + 1 }}" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
          Memuat data…
        </td>
      </tr>`;

    try {
      const url = `${API_URL}?${params.toString()}`;
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      renderRows(json);
    } catch (e) {
      rowsEl.innerHTML = `
        <tr>
          <td colspan="{{ count($fields) + 1 }}" class="px-6 py-6 text-center text-rose-600">
            Gagal memuat data.
          </td>
        </tr>`;
    }
  }

  // Search submit -> reload page 1
  searchForm?.addEventListener('submit', (e) => {
    e.preventDefault();
    loadPage(1);
  });

  // Reset
  document.getElementById('resetFilters')?.addEventListener('click', () => {
    qInput.value = '';
    loadPage(1);
  });

  // initial
  loadPage(Number(new URLSearchParams(location.search).get('page') || 1));

  // warning unload jika masih ada pending
  window.addEventListener('beforeunload', (e) => {
    if (pending.size > 0) {
      e.preventDefault();
      e.returnValue = '';
    }
  });
});
</script>

@endsection
