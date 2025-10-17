{{-- resources/views/user/loa.blade.php --}}
@php
  use Carbon\Carbon;

  $font = "font-family: DejaVu Sans, Arial, Helvetica, sans-serif;";
  $intern = $intern ?? $reg ?? null;
  $rows = array_values(array_filter(($rows ?? []), fn($r)
      => !empty($r['deskripsi']) || !empty($r['keterangan'])
  ));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Letter of Acceptance (LOA)</title>
  <style>
    /* ===== A4 & Margin ===== */
    @page {
      size: A4 portrait;
      margin: 2cm 2cm 2.5cm 2cm;
    }
    body {
      {{ $font }}
      font-size: 12px;
      color:#0f172a;
      line-height: 1.5;
      margin: 0;
      padding: 0;
    }
    .wrap {
      width: 100%;
      max-width: 19cm; /* agar proporsional di A4 */
      margin: 0 auto;
    }

    /* ===== Warna & Tema ===== */
    :root{
      --emerald-900:#064e3b;
      --emerald-800:#065f46;
      --emerald-700:#047857;
      --emerald-600:#059669;
      --emerald-200:#a7f3d0;
      --emerald-100:#d1fae5;
      --slate-400:#94a3b8;
      --slate-500:#64748b;
      --slate-700:#334155;
      --muted:#475569;
      --border:#cbd5e1;
      --grid:#a7f3d0;
      --grid-header:#ecfdf5;
    }

    /* ===== Header ===== */
    .head { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; margin-top:10px; }
    .brand { display:flex; align-items:center; gap:10px; }
    .brand .mark {
      width:28px; height:28px; border-radius:6px; background:var(--emerald-600);
    }
    .brand .name {
      font-weight:700; color:var(--emerald-800); letter-spacing:.2px;
    }
    .doc { text-align:right; }
    .doc .title { margin:0; font-size:19px; font-weight:800; color:var(--emerald-800); letter-spacing:.4px; }
    .doc .subtitle { margin:2px 0 0; font-size:11px; color:var(--slate-700); }
    .divider {
      height:3px; background:linear-gradient(90deg, var(--emerald-600), var(--emerald-200));
      margin:12px 0 18px; border-radius:4px;
    }

    /* ===== Meta Peserta ===== */
    .meta { border:1px solid var(--grid); border-radius:10px; padding:12px; }
    .meta table { width:100%; border-collapse: collapse; }
    .meta td { padding:6px 10px; vertical-align: top; }
    .meta td.key { width:36%; color:var(--slate-500); }
    .chip {
      display:inline-block; padding:4px 10px; border:1px solid var(--emerald-200);
      border-radius:999px; font-size:10.5px; color:var(--emerald-700);
      background:var(--grid-header); font-weight:700;
    }

    /* ===== Section Title ===== */
    .section-title { margin:16px 0 8px; font-weight:700; font-size:13.5px; color:var(--emerald-800); }

    /* ===== Tabel ===== */
    table.grid { width:100%; border-collapse: collapse; }
    table.grid th, table.grid td { border: 1px solid var(--grid); padding: 7px 9px; }
    table.grid thead th {
      background: var(--grid-header); color: var(--emerald-800);
      font-weight:700; font-size:12px;
    }
    table.grid tbody tr:nth-child(odd) td { background:#fafafa; }
    .center { text-align:center; }

    /* ===== Catatan ===== */
    .muted { color:var(--muted); font-size:11px; margin-top:8px; }

    /* ===== Tanda Tangan ===== */
    .sign { margin-top: 28px; width:100%; }
    .sign td { vertical-align: bottom; height: 92px; }
    .sign .caption { color:var(--slate-500); font-size:11px; }
    .sign .name { margin-top:58px; font-weight:700; color:#0f172a; }

    /* ===== Footer ===== */
    .footer {
      margin-top:20px; padding-top:8px; border-top:1px dashed var(--grid);
      display:flex; justify-content:space-between; align-items:center;
      color:var(--muted); font-size:10.5px;
    }

    .page-break { page-break-after: always; }
  </style>
</head>
<body>
  <div class="wrap">
    {{-- HEADER --}}
    <div class="head">
      <div class="brand">
        <img src="{{ asset('storage/images/logos/logo_seveninc.png') }}" alt="Logo" style="width:48px; height:auto; border-radius:8px;">
        <div class="name">
          <div style="font-weight:700; color:var(--emerald-800); letter-spacing:.3px;">Program Magang</div>
          <div style="font-size:11px; color:var(--slate-500); font-weight:500;">Seven Inc</div>
        </div>
      </div>
      <div class="doc">
        <p class="title">LETTER OF ACCEPTANCE (LOA)</p>
        <p class="subtitle">Surat Penerimaan Peserta Magang</p>
      </div>
    </div>
    <div class="divider"></div>

    {{-- META --}}
    <div class="meta">
      <table>
        <tr><td class="key">Nama Peserta</td><td>: {{ $intern->fullname ?? $user->name }}</td></tr>
        <tr><td class="key">Email</td><td>: {{ $intern->email ?? $user->email }}</td></tr>
        <tr><td class="key">Institusi / Prodi</td><td>: {{ $intern->institution_name ?? '-' }} / {{ $intern->study_program ?? '-' }}</td></tr>
        <tr>
          <td class="key">Periode Magang</td>
          @php
            $sd = $intern?->start_date ? Carbon::parse($intern->start_date)->isoFormat('D MMMM Y') : '-';
            $ed = $intern?->end_date   ? Carbon::parse($intern->end_date)->isoFormat('D MMMM Y')   : '-';
          @endphp
          <td>: {{ $sd }} s.d. {{ $ed }}</td>
        </tr>
        <tr>
          <td class="key">Bidang / Divisi</td>
          <td>
            : {{ $intern->internship_interest ?? '-' }}
            @if(strtolower((string)($intern->internship_status ?? '')) === 'completed')
              &nbsp; <span class="chip">COMPLETED</span>
            @endif
          </td>
        </tr>
      </table>
    </div>

    {{-- KEGIATAN --}}
    <div class="section-title">Rincian Penugasan / Kegiatan</div>
    <table class="grid">
      <thead>
        <tr>
          <th style="width:6%">No</th>
          <th>Deskripsi Kegiatan / Penugasan</th>
          <th style="width:30%">Keterangan</th>
        </tr>
      </thead>
      <tbody>
        @if(count($rows))
          @foreach($rows as $i => $r)
            <tr>
              <td class="center">{{ $i + 1 }}</td>
              <td>{{ $r['deskripsi'] ?? '' }}</td>
              <td>{{ $r['keterangan'] ?? '' }}</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td class="center">1</td>
            <td>Observasi proses kerja divisi terkait</td>
            <td>Orientasi & pengenalan tools</td>
          </tr>
          <tr>
            <td class="center">2</td>
            <td>Pelaksanaan tugas terstruktur sesuai arahan mentor</td>
            <td>Target mingguan</td>
          </tr>
        @endif
      </tbody>
    </table>

    <p class="muted">
      Catatan: Rencana kegiatan dapat disesuaikan berdasarkan kebutuhan dan kesepakatan dengan pembimbing/mentor.
    </p>

    {{-- TANDA TANGAN --}}
    <table class="sign">
      <tr>
        <td style="width:50%">
          <div class="caption">Mengetahui,</div>
          <div>Koordinator/HR Program Magang</div>
          <div class="name">________________________</div>
        </td>
        <td style="width:50%; text-align:right;">
          <div>{{ Carbon::now()->isoFormat('D MMMM Y') }}</div>
          <div class="caption">Menyetujui,</div>
          <div>Peserta</div>
          <div class="name">{{ $intern->fullname ?? $user->name }}</div>
        </td>
      </tr>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
      <div>Dokumen dihasilkan secara elektronik & sah tanpa tanda tangan basah.</div>
      <div>Ref: LOA-{{ $intern->id ?? 'X' }}-{{ now()->format('YmdHis') }}</div>
    </div>
  </div>

<script>
function sendHeight() {
  const height = document.body.scrollHeight;
  parent.postMessage({ type: 'setHeight', height }, '*');
}
window.addEventListener('load', sendHeight);
window.addEventListener('resize', sendHeight);

// Jika tabel diupdate oleh parent
window.addEventListener('message', (e) => {
  if (e.data?.type === 'updateLOA') {
    const { rows } = e.data;
    const tbody = document.querySelector('table.grid tbody');
    tbody.innerHTML = '';
    rows.forEach((r, i) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="center">${i + 1}</td>
        <td>${r.deskripsi || ''}</td>
        <td>${r.keterangan || ''}</td>
      `;
      tbody.appendChild(tr);
    });
    sendHeight(); // update tinggi iframe setelah tabel berubah
  }
});
</script>

</body>
</html>
