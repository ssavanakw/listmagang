<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Penilaian Magang - {{ isset($assessment->fullname) ? $assessment->fullname : 'Nama Tidak Tersedia' }}</title>
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
        height: {{ isset($assessment->logo_height) ? $assessment->logo_height : 70 }}px;
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
        height: {{ isset($assessment->sig_height) ? $assessment->sig_height : 90 }}px;
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
        height: calc({{ isset($assessment->logo_height) ? $assessment->logo_height : 70 }}px * 1.8);
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
                  $logoFile = public_path('storage/' . (isset($assessment->company_logo_path) ? $assessment->company_logo_path : 'images/logos/seveninc_logo.png'));
                  $logoSrc = file_exists($logoFile)
                      ? $logoFile
                      : asset('storage/' . (isset($assessment->company_logo_path) ? $assessment->company_logo_path : 'images/logos/seveninc_logo.png'));
              @endphp
              <img src="{{ $logoSrc }}" alt="Logo">
          </td>
          <td>
              <div class="company">
                  <h1>{{ isset($assessment->company_name) ? $assessment->company_name : 'SEVEN INC.' }}</h1>
                  <p>{!! nl2br(e(isset($assessment->company_address) ? $assessment->company_address : 'Jl. Raya Janti, Gang Arjuna No. 59, Karangjambe, Banguntapan, Bantul, Yogyakarta')) !!}</p>
              </div>
          </td>
      </tr>
  </table>

  <hr>

  <div class="title">FORM PENILAIAN MAGANG {{ strtoupper(isset($assessment->company_name) ? $assessment->company_name : 'SEVEN INC.') }}</div>

  <div class="info">
      Dengan ini pihak <b>{{ isset($assessment->company_name) ? $assessment->company_name : 'SEVEN INC.' }}</b> memberikan penilaian selama pelaksanaan magang kepada:<br>
      <span class="label">Nama</span>: {{ isset($assessment->fullname) ? $assessment->fullname : 'Nama Tidak Tersedia' }}<br>
      <span class="label">NIM/NIS</span>: {{ isset($assessment->nim_or_nis) ? $assessment->nim_or_nis : 'NIM/NIS Tidak Tersedia' }}<br>
      <span class="label">Program Studi</span>: {{ isset($assessment->study_program) ? $assessment->study_program : 'Program Studi Tidak Tersedia' }}<br>
      <span class="label">Divisi/Keahlian</span>: {{ isset($assessment->div) ? $assessment->div : 'Divisi/Keahlian Tidak Tersedia' }}<br>

      {{-- Menambahkan data statis --}}
      <span class="label">Tanggal Penilaian</span>: {{ date('d F Y') }}<br>  <!-- Menambahkan tanggal statis -->
      <span class="label">Lokasi Magang</span>: Yogyakarta, Indonesia<br>  <!-- Menambahkan lokasi magang statis -->
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
      @foreach(isset($assessment->aspek_penilaian) ? $assessment->aspek_penilaian : [] as $index => $item)
      <tr>
        <td style="border: 1px solid #000; text-align: center; padding: 6px;">{{ $index + 1 }}</td>
        <td style="border: 1px solid #000; padding: 6px;">{{ $item['aspek'] ?? 'Aspek Tidak Tersedia' }}</td>
        <td style="border: 1px solid #000; text-align: center; padding: 6px;">{{ $item['nilai'] ?? 'Nilai Tidak Tersedia' }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="2" style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 6px;">Rata-rata</td>
        <td style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 6px;">{{ isset($assessment->rata_rata) ? $assessment->rata_rata : 'Rata-rata Tidak Tersedia' }}</td>
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

  {{-- SIGNATURE --}}
  <div class="signature">
      <div class="signature-inner">
          <p class="signature-text">
              Yogyakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
              {{ isset($assessment->signature_position) ? $assessment->signature_position : 'Direktur SEVEN INC' }}
          </p>

          @php
              $sigFile = public_path('storage/' . (isset($assessment->signature_image_path) ? $assessment->signature_image_path : 'images/signature/ttd_rekariodanny.png'));
              $sigSrc = file_exists($sigFile)
                  ? $sigFile
                  : asset('storage/' . (isset($assessment->signature_image_path) ? $assessment->signature_image_path : 'images/signature/ttd_rekariodanny.png'));
          @endphp

          <div class="signature-image-block">
              <img class="ttd" src="{{ $sigSrc }}" alt="Tanda Tangan">
              <span class="name">{{ isset($assessment->signature_name) ? $assessment->signature_name : 'Rekario Danny Sanjaya, S.Kom' }}</span>
          </div>

          {{-- WATERMARK --}}
          <img class="logo-bg" src="{{ $logoSrc }}" alt="Logo Transparan">
      </div>
  </div>

</div>
</body>
</html>
