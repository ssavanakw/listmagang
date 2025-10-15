{{-- resources/views/user/loa.blade.php --}}
@php
  use Carbon\Carbon;
  $font = "font-family: DejaVu Sans, Arial, sans-serif;";
  $rows = $rows ?? []; // Expect: [['deskripsi' => '...', 'keterangan' => '...'], ...]
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Letter of Acceptance (LOA)</title>
  <style>
    body { {{ $font }} font-size: 12px; color:#111; }
    .wrap { padding: 24px; }
    .head { text-align:center; margin-bottom: 12px; }
    .head h1 { margin:0 0 6px 0; font-size: 18px; letter-spacing: .5px; }
    .head p { margin:0; font-size: 11px; color:#555; }
    .divider { height: 2px; background:#111; margin:12px 0 18px; }
    .meta table { width:100%; border-collapse: collapse; }
    .meta td { padding:6px 8px; vertical-align: top; }
    .meta td.key { width: 28%; color:#444; }
    .section-title { margin:16px 0 8px; font-weight: bold; font-size: 13px; }
    table.grid { width:100%; border-collapse: collapse; }
    table.grid th, table.grid td { border: 1px solid #999; padding: 6px 8px; }
    table.grid th { background:#f3f3f3; font-weight: bold; }
    .muted { color:#666; font-size: 11px; }
    .sign { margin-top: 28px; width:100%; }
    .sign td { vertical-align: bottom; height: 90px; }
    .right { text-align: right; }
    .center { text-align: center; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="head">
      <h1>LETTER OF ACCEPTANCE (LOA)</h1>
      <p>Surat Penerimaan Peserta Magang</p>
    </div>
    <div class="divider"></div>

    <div class="meta">
      <table>
        <tr>
          <td class="key">Nama Peserta</td>
          <td>: {{ $intern->fullname ?? $user->name }}</td>
        </tr>
        <tr>
          <td class="key">Email</td>
          <td>: {{ $intern->email ?? $user->email }}</td>
        </tr>
        <tr>
          <td class="key">Institusi / Prodi</td>
          <td>: {{ $intern->institution_name ?? '-' }} / {{ $intern->study_program ?? '-' }}</td>
        </tr>
        <tr>
          <td class="key">Periode Magang</td>
          @php
            $sd = $intern?->start_date ? Carbon::parse($intern->start_date)->isoFormat('D MMMM Y') : '-';
            $ed = $intern?->end_date   ? Carbon::parse($intern->end_date)->isoFormat('D MMMM Y')   : '-';
          @endphp
          <td>: {{ $sd }} s.d. {{ $ed }}</td>
        </tr>
        <tr>
          <td class="key">Bidang/Divisi Tujuan</td>
          <td>: {{ $intern->internship_interest ?? '-' }}</td>
        </tr>
      </table>
    </div>

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
        @forelse($rows as $i => $r)
          <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ $r['deskripsi'] ?? '' }}</td>
            <td>{{ $r['keterangan'] ?? '' }}</td>
          </tr>
        @empty
          {{-- Placeholder baris jika tidak ada rows dikirim --}}
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
        @endforelse
      </tbody>
    </table>


    <p class="muted" style="margin-top:10px;">
      Catatan: Rencana kegiatan dapat disesuaikan berdasarkan kebutuhan dan kesepakatan dengan pembimbing/mentor.
    </p>

    <table class="sign">
      <tr>
        <td style="width:50%">
          <div>Mengetahui,</div>
          <div>Koordinator/HR Magang</div>
          <div style="margin-top:58px; font-weight:bold;">________________________</div>
        </td>
        <td style="width:50%" class="right">
          <div>{{ Carbon::now()->isoFormat('D MMMM Y') }}</div>
          <div>Menyetujui,</div>
          <div>Peserta</div>
          <div style="margin-top:58px; font-weight:bold;">{{ $intern->fullname ?? $user->name }}</div>
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
