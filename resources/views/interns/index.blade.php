@extends('layouts.dashboard')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="px-4 pt-6">

    {{-- Header + Tabs + Search --}}
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

        {{-- Kanan: Search + PendingBar (konfirmasi perubahan) --}}
        <div class="mt-1 flex w-full max-w-[420px] flex-col items-end gap-2">
            <form method="GET" action="{{ url()->current() }}" class="w-full">
                <div class="flex items-center gap-2">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Cari nama atau email…"
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
                </div>
            </form>

            {{-- Pending bar: di kanan bawah search --}}
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
        </div>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800
                    dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Card --}}
    <div class="rounded-xl bg-white dark:bg-gray-800 shadow ring-1 ring-gray-200 dark:ring-gray-700">

        {{-- Table --}}
        <div id="tableWrap" class="overflow-x-auto">
            <table class="min-w-full w-max text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="sticky top-0 z-10 text-xs uppercase tracking-wider
                              bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr class="divide-x divide-gray-200 dark:divide-gray-600">
                        <th class="px-3 py-3 font-semibold whitespace-nowrap">No</th>
                        @php
                            // STATUS dipindahkan ke paling belakang
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
                                'family_status' => 'IZIN KELUARGA',
                                'parent_wa_contact' => 'KONTAK ORANG TUA',
                                'social_media_instagram' => 'INSTAGRAM',
                                'cv_ktp_portofolio_pdf' => 'FILE PDF',
                                'portofolio_visual' => 'FILE VISUAL',
                                'created_at' => 'DIBUAT',
                                'internship_status' => 'STATUS', // terakhir
                            ];
                        @endphp
                        @foreach ($fields as $label)
                            <th class="px-3 py-3 font-semibold whitespace-nowrap">{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($interns as $i => $r)
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100
                               dark:odd:bg-gray-800 dark:even:bg-gray-800/60 dark:hover:bg-gray-700/60">
                        <td class="px-3 py-2 text-gray-600 dark:text-gray-300">
                            {{ ($interns->firstItem() ?? 1) + $i }}
                        </td>

                        @foreach ($fields as $field => $label)
                            <td class="px-3 py-2 align-top">
                                @php $val = $r->$field ?? '-'; @endphp

                                {{-- File links --}}
                                @if (Str::startsWith($field, 'cv_') || Str::startsWith($field, 'portofolio_'))
                                    @if ($r->$field)
                                        <a href="{{ asset('storage/' . $r->$field) }}" target="_blank"
                                           class="text-emerald-600 hover:text-emerald-700 underline">Lihat</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif

                                {{-- Tanggal --}}
                                @elseif (in_array($field, ['born_date','start_date','end_date','created_at'], true))
                                    @php $d = $r->$field; $isCarbon = $d instanceof \Carbon\Carbon; @endphp
                                    <span class="whitespace-nowrap">
                                        {{ $isCarbon ? $d->format('d M Y') : ($d ?: '-') }}
                                    </span>

                                {{-- STATUS (editable; simpan setelah konfirmasi) --}}
                                @elseif ($field === 'internship_status')
                                    @php
                                        $map = [
                                            'new'       => ['Pendaftar Baru', 'bg-teal-100 text-teal-800 dark:bg-teal-600/20 dark:text-teal-300'],
                                            'active'    => ['Aktif',          'bg-blue-100 text-blue-800 dark:bg-blue-600/20 dark:text-blue-300'],
                                            'completed' => ['Selesai',        'bg-indigo-100 text-indigo-800 dark:bg-indigo-600/20 dark:text-indigo-300'],
                                            'exited'    => ['Keluar',         'bg-rose-100 text-rose-800 dark:bg-rose-600/20 dark:text-rose-300'],
                                            'pending'   => ['Pending',        'bg-amber-100 text-amber-800 dark:bg-amber-600/20 dark:text-amber-300'],
                                        ];
                                        [$labelStatus, $cls] = $map[$val] ?? ['-', 'bg-gray-100 text-gray-800 dark:bg-gray-600/20 dark:text-gray-200'];
                                    @endphp

                                    <div class="flex items-center gap-2 min-w-[12rem]">
                                        <span id="badge-{{ $r->id }}"
                                              class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $cls }}">
                                            {{ $labelStatus }}
                                        </span>

                                        {{-- form untuk ambil action (AJAX) --}}
                                        <form action="{{ route('admin.interns.status.update', $r) }}" class="inline status-form-row">
                                            @csrf
                                            @method('PATCH')
                                            <label class="sr-only">Ubah status</label>
                                            <select
                                              class="text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800
                                                     text-gray-700 dark:text-gray-200 px-2 py-1.5 pr-7 appearance-none
                                                     focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 status-select-row"
                                              data-current="{{ $val }}" data-name="{{ $r->fullname }}" data-id="{{ $r->id }}">
                                                @foreach ($map as $key => [$text])
                                                    <option value="{{ $key }}" {{ $key === $val ? 'selected' : '' }}>{{ $text }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>

                                {{-- STATUS SAAT INI --}}
                                @elseif ($field === 'current_status')
                                    @php
                                        $csMap = [
                                            'Student'        => 'bg-sky-100 text-sky-800 dark:bg-sky-600/20 dark:text-sky-300',
                                            'Fresh Graduate' => 'bg-slate-100 text-slate-800 dark:bg-slate-600/20 dark:text-slate-300',
                                        ];
                                        $csCls = $csMap[$val] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-600/20 dark:text-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-medium {{ $csCls }}">
                                        {{ $val }}
                                    </span>

                                {{-- nowrap --}}
                                @elseif (in_array($field, ['email','student_id','phone_number','gender','social_media_instagram'], true))
                                    <span class="whitespace-nowrap">{{ $val }}</span>

                                {{-- default --}}
                                @else
                                    <span class="block max-w-[18rem] truncate" title="{{ is_string($val) ? $val : '' }}">
                                        {{ $val }}
                                    </span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($fields) + 1 }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Belum ada data.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $interns->withQueryString()->links() }}
        </div>
    </div>

</div>

{{-- Script: kumpulkan perubahan -> konfirmasi -> simpan semua (AJAX) + clamp scroll kanan --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  // ====== Clamp scroll kanan agar tidak bisa melewati kolom terakhir ======
  const wrap = document.getElementById('tableWrap');
  if (wrap) {
    wrap.scrollLeft = 0;
    wrap.addEventListener('scroll', () => {
      const max = wrap.scrollWidth - wrap.clientWidth;
      if (wrap.scrollLeft > max) wrap.scrollLeft = max;
      if (wrap.scrollLeft < 0) wrap.scrollLeft = 0;
    }, { passive: true });
  }

  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  const statusMap = {
    new:       {label:'Pendaftar Baru', cls:'bg-teal-100 text-teal-800 dark:bg-teal-600/20 dark:text-teal-300'},
    active:    {label:'Aktif',          cls:'bg-blue-100 text-blue-800 dark:bg-blue-600/20 dark:text-blue-300'},
    completed: {label:'Selesai',        cls:'bg-indigo-100 text-indigo-800 dark:bg-indigo-600/20 dark:text-indigo-300'},
    exited:    {label:'Keluar',         cls:'bg-rose-100 text-rose-800 dark:bg-rose-600/20 dark:text-rose-300'},
    pending:   {label:'Pending',        cls:'bg-amber-100 text-amber-800 dark:bg-amber-600/20 dark:text-amber-300'},
  };

  // Simpan perubahan sementara: Map<id, {id,name,from,to,url,select,badge}>
  const pending = new Map();

  const pendingBar   = document.getElementById('pendingBar');
  const pendingCount = document.getElementById('pendingCount');
  const saveAllBtn   = document.getElementById('saveAll');
  const discardBtn   = document.getElementById('discardAll');

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

  // Buat FormData PATCH (stabil di Laravel)
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
    return res;
  }

  // Kumpulkan perubahan ketika select berubah (belum dikirim)
  document.querySelectorAll('.status-select-row').forEach(sel => {
    sel.addEventListener('change', function(){
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
      pending.set(id, {id, name, from, to, url, select: this, badge: document.getElementById(`badge-${id}`)});
      markSelect(this, true);
      updatePendingBar();
    });
  });

  // Batalkan semua perubahan yang belum disimpan
  discardBtn.addEventListener('click', () => {
    pending.forEach(({select, from}) => {
      select.value = from;
      select.disabled = false; // pastikan aktif lagi
      markSelect(select, false);
    });
    pending.clear();
    updatePendingBar();
  });

  // Simpan semua perubahan (konfirmasi dulu)
  saveAllBtn.addEventListener('click', async () => {
    if (pending.size === 0) return;

    // Ringkas pesan konfirmasi
    const items = Array.from(pending.values())
      .slice(0, 5)
      .map(p => `• ${p.name}: ${statusMap[p.from]?.label || p.from} → ${statusMap[p.to]?.label || p.to}`)
      .join('\n');
    const more = pending.size > 5 ? `\n…dan ${pending.size - 5} perubahan lainnya` : '';
    const ok = confirm(`Simpan ${pending.size} perubahan status?\n\n${items}${more}`);
    if (!ok) return;

    // Kunci UI sementara
    saveAllBtn.disabled = true;

    let success = 0, failed = 0;

    // Kirim satu per satu (gunakan endpoint per baris)
    for (const p of pending.values()) {
      p.select.disabled = true;
      try {
        const res = await patchForm(p.url, { internship_status: p.to });
        if (!res.ok) throw new Error(await res.text());
        // update dataset.current & badge
        p.select.dataset.current = p.to;
        if (p.badge) {
          p.badge.className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ' + (statusMap[p.to]?.cls || '');
          p.badge.textContent = statusMap[p.to]?.label || p.to;
        }
        markSelect(p.select, false);
        p.select.disabled = false; // aktifkan kembali setelah sukses
        success++;
      } catch (_) {
        // tetap aktif untuk diperbaiki
        p.select.disabled = false;
        markSelect(p.select, true);
        failed++;
      }
    }

    // Bersihkan pending untuk yang sukses
    Array.from(pending.keys()).forEach(id => {
      const entry = pending.get(id);
      if (entry && entry.select.dataset.current === entry.to) pending.delete(id);
    });

    updatePendingBar();
    saveAllBtn.disabled = false;

    alert(`Selesai.\nBerhasil: ${success}\nGagal: ${failed}${failed ? '\nCoba ulang untuk yang gagal.' : ''}`);
  });

  // Peringatan jika ada perubahan belum disimpan saat mau menutup/refresh
  window.addEventListener('beforeunload', (e) => {
    if (pending.size > 0) {
      e.preventDefault();
      e.returnValue = '';
    }
  });
});
</script>
@endsection
