<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Penilaian Magang - {{ $assessment->fullname }}</title>
    <style>
        @page {
            size: A4;
            margin-top: 60px;
            margin-left: 60px;
            margin-right: 60px;
            margin-bottom: 40px;
        }
        body {
            font-family: "Times New Roman", serif;
            font-size: 13px;
            color: #000;
            line-height: 1.5;
        }
        .wrap {
            max-width: 750px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 5px;
        }
        .header img {
            width: {{ $assessment->logo_width ?? 70 }}px;
            height: {{ $assessment->logo_height ?? 70 }}px;
            position: absolute;
            left: 0;
            top: 0;
            object-fit: contain;
        }
        .company {
            text-align: center;
            width: 100%;
        }
        .company h1 {
            margin: 0;
            font-size: 22px;
        }
        .company p {
            margin: 0;
            font-size: 12px;
        }
        hr {
            border: none;
            border-top: 2px solid #444;
            margin: 8px 0 12px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 10px;
        }
        .label {
            width: 150px;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
        }
        th {
            background: #f3f3f3;
            text-align: center;
        }
        td.center {
            text-align: center;
        }
        .range {
            margin-top: 10px;
            font-size: 12px;
        }
        .signature {
            margin-top: 40px;
            width: 100%;
            text-align: right;
            position: relative;
        }
        .signature img.ttd {
            width: {{ $assessment->sig_width ?? 100 }}px;
            height: {{ $assessment->sig_height ?? 100 }}px;
            margin-bottom: -10px;
            margin-top: 10px;
            object-fit: contain;
            position: relative;
            z-index: 2;
        }
        .signature .name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature img.logo-bg {
            position: absolute;
            right: 0;
            bottom: 20px;
            width: 125px;
            opacity: 0.2;
            z-index: 1;
        }
    </style>
</head>
<body>
<div class="wrap">

  {{-- HEADER --}}
  <div class="header">
    <img src="{{ public_path('storage/' . ($assessment->company_logo_path ?? 'images/logos/seveninc_logo.png')) }}" alt="Logo">
    <div class="company">
      <h1>{{ $assessment->company_name ?? 'SEVEN INC.' }}</h1>
      <p>{!! nl2br(e($assessment->company_address ?? 'Jl. Raya Janti, Gang Arjuna No. 59, Karangjambe, Banguntapan, Bantul, Yogyakarta')) !!}</p>
    </div>
  </div>

  <hr>

  <div class="title">FORM PENILAIAN MAGANG {{ strtoupper($assessment->company_name ?? 'SEVEN INC.') }}</div>

  <div class="info">
      Dengan ini pihak <b>{{ $assessment->company_name ?? 'SEVEN INC.' }}</b> memberikan penilaian selama pelaksanaan magang kepada:<br>
      <span class="label">Nama</span>: {{ $assessment->fullname }}<br>
      <span class="label">NIM</span>: {{ $assessment->nim_or_nis }}<br>
      <span class="label">Program Studi</span>: {{ $assessment->study_program }}<br>
      <span class="label">Kompetensi Keahlian</span>: {{ $assessment->div }}
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:50px;">No</th>
        <th>Aspek Penilaian</th>
        <th style="width:90px;">Nilai</th>
      </tr>
    </thead>
    <tbody>
      @foreach($assessment->aspek_penilaian as $index => $item)
      <tr>
        <td class="center">{{ $index + 1 }}</td>
        <td>{{ $item['aspek'] }}</td>
        <td class="center">{{ $item['nilai'] }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="2" class="center"><b>Rata-rata</b></td>
        <td class="center"><b>{{ $assessment->rata_rata }}</b></td>
      </tr>
    </tbody>
  </table>

  <div class="range">
    <b>Keterangan rentang nilai:</b><br>
    a. 81–100 : Amat baik<br>
    b. 65–80 : Baik<br>
    c. 50–64 : Cukup<br>
    d. &lt; 50 : Kurang
  </div>

  {{-- SIGNATURE --}}
  <div class="signature">
    <p>Yogyakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>{{ $assessment->signature_position ?? 'Direktur SEVEN INC' }}</p>

    <img class="ttd" src="{{ public_path('storage/' . ($assessment->signature_image_path ?? 'images/signature/ttd_rekariodanny.png')) }}" alt="Tanda Tangan"><br>
    <span class="name">{{ $assessment->signature_name ?? 'Rekario Danny Sanjaya, S.Kom' }}</span>

    <img class="logo-bg" src="{{ public_path('storage/' . ($assessment->company_logo_path ?? 'images/logos/seveninc_logo.png')) }}" alt="Logo Transparan">
  </div>

</div>
</body>
</html>
