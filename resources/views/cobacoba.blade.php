<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Penilaian Magang - {{ $assessment->fullname ?? 'jamal' }}</title>
  <style>
    @page { size: A4; margin: 60px; }

    body {
        font-family: "Times New Roman", serif;
        font-size: 13px;
        color: #000;
        line-height: 1.5;
        position: relative;
    }

    .wrap { width: 100%; max-width: 700px; margin: 0 auto; position: relative; }

    /* ===== HEADER ===== */
    .header-table {
        width: 100%;
        border-collapse: collapse;
        height: 90px;
    }

    .header-table td { vertical-align: middle; border: none; }

    .header-logo img {
        height: {{ $assessment->logo_height ?? 70 }}px;
        width: auto;
    }

    .company { text-align: center; }
    .company h1 { font-size: 20px; margin: 0; font-weight: bold; line-height: 1.3; }
    .company p { margin: 0; font-size: 12px; line-height: 1.3; }

    hr { border: none; border-top: 2px solid #000; margin: 8px 0 12px; }

    /* ===== BODY ===== */
    .title {
        text-align: center;
        font-weight: bold;
        text-decoration: underline;
        margin-bottom: 10px;
    }

    .info { margin-bottom: 12px; }
    .label { width: 160px; display: inline-block; }
    .range { margin-top: 10px; font-size: 12px; }

    /* ===== SIGNATURE ===== */
    .signature {
        width: 100%;
        margin-top: 70px;
        position: relative;
        clear: both;
    }

    .signature-inner {
        width: 260px;
        float: right;
        text-align: center;
        position: relative;
    }

    .signature-text {
        text-align: center;
        line-height: 1.4;
        margin-bottom: 60px;
    }

    .signature-image-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .signature img.ttd {
        height: {{ $assessment->sig_height ?? 90 }}px;
        width: auto;
        margin-bottom: 5px;
        z-index: 2;
    }

    .signature .name {
        font-weight: bold;
        text-decoration: underline;
        text-align: center;
        z-index: 3;
    }

    /* === WATERMARK === */
    .signature img.logo-bg {
        position: absolute;
        right: 35px;
        bottom: 15px;
        height: calc({{ $assessment->logo_height ?? 70 }}px * 1.8);
        width: auto;
        opacity: 0.12;
        z-index: 1;
        filter: blur(0.4px);
    }
  </style>
</head>
<body>
<div class="wrap">

  {{-- HEADER --}}
  <table class="header-table">
      <tr>
          <td class="header-logo">
              @php
                  $logoFile = public_path('storage/' . ($assessment->company_logo_path ?? 'images/logos/seveninc_logo.png'));
                  $logoSrc = file_exists($logoFile)
                      ? $logoFile
                      : asset('storage/' . ($assessment->company_logo_path ?? 'images/logos/seveninc_logo.png'));
              @endphp
              <img src="{{ $logoSrc }}" alt="Logo">
          </td>
          <td>
              <div class="company">
                  <h1>{{ $assessment->company_name ?? 'SEVEN INC.' }}</h1>
                  <p>{!! nl2br(e($assessment->company_address ?? 'Jl. Raya Janti, Gang Arjuna No. 59, Karangjambe, Banguntapan, Bantul, Yogyakarta')) !!}</p>

                  {{-- STATIC CONTACT DATA --}}
                  <p style="margin-top:2px; font-size:11px;">
                      Telp: (0274) 654321 &nbsp; | &nbsp;
                      Email: official@seveninc.id &nbsp; | &nbsp;
                      Website: www.seveninc.id
                  </p>
              </div>
          </td>
      </tr>
  </table>

  <hr>

  {{-- STATIC DOCUMENT NUMBER --}}
  <div style="text-align:right; font-size:12px; margin-bottom:15px;">
      <b>No. Dokumen:</b> SPM-SEVENINC/INT/{{ date('Y') }}/001
  </div>

  {{-- STATIC SCHOOL / INSTITUTION --}}
  <div style="font-size:12px; margin-bottom:20px;">
      <b>Ditujukan kepada:</b><br>
      Kepala Program Studi / Guru Pembimbing Magang<br>
      {{ $assessment->school_name ?? 'Universitas / Sekolah Mitra' }}<br>
      {{ $assessment->school_address ?? 'Alamat institusi pendidikan mitra' }}
  </div>

  <div class="title">FORM PENILAIAN MAGANG {{ strtoupper($assessment->company_name ?? 'SEVEN INC.') }}</div>

  <div class="info">
      Dengan ini pihak <b>{{ $assessment->company_name ?? 'SEVEN INC.' }}</b> memberikan penilaian selama pelaksanaan magang kepada:<br>
      <span class="label">Nama</span>: {{ $assessment->fullname ?? 'jamal'}}<br>
      <span class="label">NIM/NIS</span>: {{ $assessment->nim_or_nis ?? ''}}<br>
      <span class="label">Program Studi</span>: {{ $assessment->study_program ?? '' }}<br>
      <span class="label">Divisi/Keahlian</span>: {{ $assessment->div ??'' }}
  </div>

  {{-- TABEL ASPEK PENILAIAN --}}
  <table style="width: 100%; border-collapse: collapse; font-size: 13px; border: 1px solid #000;">
    <thead>
      <tr style="background-color: #f3f3f3; text-align: center;">
        <th style="width:50px; border: 1px solid #000; padding: 6px;">No</th>
        <th style="border: 1px solid #000; padding: 6px;">Aspek Penilaian</th>
        <th style="width:90px; border: 1px solid #000; padding: 6px;">Nilai</th>
      </tr>
    </thead>
    <tbody>
      @foreach($assessment->aspek_penilaian as $index => $item)
      <tr>
        <td style="border: 1px solid #000; text-align: center; padding: 6px;">{{ $index + 1 }}</td>
        <td style="border: 1px solid #000; padding: 6px;">{{ $item['aspek'] }}</td>
        <td style="border: 1px solid #000; text-align: center; padding: 6px;">{{ $item['nilai'] }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="2" style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 6px;">Rata-rata</td>
        <td style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 6px;">{{ $assessment->rata_rata ?? '' }}</td>
      </tr>
    </tbody>
  </table>

  <div class="range">
    <b>Keterangan rentang nilai:</b><br>
    81–100 : Amat Baik<br>
    65–80 : Baik<br>
    50–64 : Cukup<br>
    &lt; 50 : Kurang
  </div>

  {{-- STATIC NOTE --}}
  <div style="margin-top:15px; font-size:12px;">
      <b>Catatan:</b><br>
      Form ini merupakan dokumen resmi Seven Inc dan digunakan sebagai laporan penilaian magang
      untuk kebutuhan akademik. Segala bentuk pengubahan isi dokumen tanpa izin tertulis adalah
      dilarang.
  </div>

  {{-- QR STATIC --}}
  <div style="position:absolute; left:0; bottom:160px; text-align:center;">
      <img src="{{ public_path('storage/images/static/qr_template.png') }}"
           style="height:75px; opacity:0.7;">
      <div style="font-size:10px;">QR Verifikasi Dokumen</div>
  </div>

  {{-- SIGNATURE --}}
  <div class="signature">
      <div class="signature-inner">
          <p class="signature-text">
              Yogyakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
              {{ $assessment->signature_position ?? 'Direktur SEVEN INC' }}
          </p>

          @php
              $sigFile = public_path('storage/' . ($assessment->signature_image_path ?? 'images/signature/ttd_rekariodanny.png'));
              $sigSrc = file_exists($sigFile)
                  ? $sigFile
                  : asset('storage/' . ($assessment->signature_image_path ?? 'images/signature/ttd_rekariodanny.png'));
          @endphp

          {{-- SIGNATURE BLOCK --}}
          <div class="signature-image-block">
              <img class="ttd" src="{{ $sigSrc }}" alt="Tanda Tangan">
              <span class="name">{{ $assessment->signature_name ?? 'Rekario Danny Sanjaya, S.Kom' }}</span>
          </div>

          {{-- STATIC STAMP --}}
          <img src="{{ public_path('storage/images/static/stamp_seveninc.png') }}"
               style="position:absolute; right:10px; bottom:70px; height:120px; opacity:0.25;">

          {{-- WATERMARK --}}
          <img class="logo-bg" src="{{ $logoSrc }}" alt="Logo Transparan">
      </div>
  </div>

</div>

{{-- STATIC FOOTER --}}
<div style="
    position: fixed;
    bottom: 20px;
    left: 0;
    width: 100%;
    text-align: center;
    font-size: 11px;
    color: #555;
">
    Sistem Penilaian Magang – Seven Inc.
    <br>
    Dokumen ini dibuat otomatis oleh sistem dan sah tanpa tanda tangan basah.
    <br>
    © Seven Inc., Yogyakarta – {{ date('Y') }}
</div>

</body>
</html>
