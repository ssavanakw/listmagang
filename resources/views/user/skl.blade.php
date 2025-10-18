<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Selesai Magang (SKL)</title>
    <style>
        @page { size: A4 portrait; margin: 1.5cm 2cm 2.5cm 2cm; }
        body {
            font-family: 'Roboto', Arial, Helvetica, sans-serif;
            font-size: 12px; color: #333; background: #fff; margin: 0; line-height: 1.5;
        }
        .letter { max-width: 780px; margin: 0 auto; padding: 20px; border: 1px solid #e5e5e5; border-radius: 10px; background: #fafafa; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand img { width: 80px; height: auto; border-radius: 8px; }
        .company { font-weight: 700; font-size: 18px; color: #065f46; }
        .company small { display: block; color: #555; font-size: 13px; }
        .kop { border-top: 4px solid #059669; margin-top: 8px; padding-top: 6px; }
        .meta-top { text-align: right; font-size: 14px; color: #555; }
        .meta-top .no { font-weight: 700; font-size: 14px; }

        h1.title { text-align: center; font-size: 22px; margin: 10px 0 20px; color: #065f46; }

        .table-like p { margin: 6px 0; }
        .table-like strong { display: inline-block; width: 180px; color: #047857; }

        .content p { text-align: justify; margin: 10px 0; font-size: 14px; }

        .sign-wrap { margin-top: 0px; display: flex; justify-content: flex-end; border-top: 2px solid #e5e5e500; padding-top: 20px; }
        .sign { text-align: center; width: 260px; position: relative; font-size: 14px; }
        .stamp { width: 100px; height: 100px; opacity: 100;}

        .footer { font-size: 10px; text-align: center; margin-top: 20px; color: #aaa; }
    </style>
</head>
<body>
  {{-- HEADER --}}
  <div class="header">
      <div class="brand">
          <!-- Use asset() to generate public URL -->
          <img src="{{ $logoData ?? '' }}" alt="Logo">
          <div>
              <div class="company">{{ $companyName ?? 'Seven Inc' }}</div>
              <small>{{ $companyAddress ?? 'Jl. Raya Janti Gg. Harjuna No.59, Jaranan, Karangjambe, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198' }}</small>
          </div>
      </div>
      <div class="meta-top">
          <div class="kop"></div>
          <div class="no">Nomor: {{ $letterNumber ?? '001/HRD/SKL/2023' }}</div>
          <small>{{ $startStr ?? '1 Januari 2023' }} — {{ $endStr ?? '31 Januari 2023' }}</small>
      </div>
  </div>

  {{-- TITLE --}}
  <h1 class="title" style="justify-content: flex-end; border-top: 2px solid #059669; padding-top: 10px;">SURAT KETERANGAN SELESAI MAGANG</h1>

  {{-- PENANDATANGAN --}}
  <div class="table-like">
      <p>Yang bertanda tangan di bawah ini:</p>
      <p><strong>Nama</strong> {{ $leaderName ?? 'Nama Pimpinan / HRD' }}</p>
      <p><strong>Jabatan</strong> {{ $leaderTitle ?? 'Manajer HRD' }}</p>
      <p><strong>Perusahaan</strong> {{ $companyName ?? 'Seven Inc' }}</p>
      <p><strong>Alamat</strong> {{ $companyAddress ?? 'Jl. Raya Janti Gg. Harjuna No.59, Jaranan, Karangjambe, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198' }}</p>
  </div>

  {{-- PESERTA --}}
  <div class="table-like" style="margin-top:12px;">
      <p>Dengan ini menerangkan bahwa:</p>
      <p><strong>Nama</strong> {{ $participantName ?? 'Nama Peserta' }}</p>
      <p><strong>NIM / Identitas</strong> {{ $participantId ?? 'NIM Peserta' }}</p>
      <p><strong>Program Studi</strong> {{ $participantMajor ?? 'Program Studi Peserta' }}</p>
      <p><strong>Asal Institusi</strong> {{ $participantInstitute ?? 'Asal Institusi Peserta' }}</p>
  </div>

  {{-- ISI --}}
  <div class="content" style="margin-top:10px;">
      <p><br>Dengan ini menerangkan bahwa <strong>{{ $participantName ?? 'Nama Peserta' }}</strong> telah menyelesaikan program magang di <strong>{{ $companyName ?? 'Seven Inc' }}</strong> pada divisi <strong>{{ $divisionName ?? 'Divisi Peserta' }}</strong> selama periode <strong>{{ $startStr ?? '1 Januari 2023' }}</strong> hingga <strong>{{ $endStr ?? '31 Januari 2023' }}</strong>.</p>
      <p>
        {{ $activityDescription ?? 'Selama magang, yang bersangkutan menunjukkan sikap profesional, inisiatif tinggi, serta kemampuan bekerja dalam tim maupun secara mandiri. Ia menguasai berbagai keterampilan praktis yang mendukung bidangnya dan menyelesaikan seluruh tugas dengan baik serta tepat waktu.' }} 
      </p> 

      <p>
        {{ $participantAchievement ?? 'Peserta magang juga menunjukkan kemajuan signifikan dalam memahami proses operasional dan strategi perusahaan. Kontribusinya dihargai tim, terutama melalui ide-ide kreatif dan inovatif yang berhasil diterapkan di divisi tempat magang. Surat keterangan ini dibuat untuk digunakan sebagaimana mestinya dan sebagai bukti bahwa yang bersangkutan telah mengikuti dan menyelesaikan program magang dengan baik di perusahaan kami.' }}
      </p>

  </div>


  {{-- SIGN --}}
  <div class="sign-wrap">
      <div class="sign">
          <div>{{ $companyCity ?? 'Yogyakarta' }}, {{ $letterDateStr ?? '1 Februari 2023' }}</div>
          <div>Hormat kami,</div>
          <!-- Use asset() to generate public URL for the stamp -->
          <img src="{{ $stampData ?? '' }}" class="stamp">
          <div style="margin-top:-20px; font-weight:700;">{{ $leaderName ?? 'Nama Pimpinan / HRD' }}</div>
          <div style="font-size:12px;">{{ $leaderTitle ?? 'Manajer HRD' }}</div>
          <div style="font-size:12px;">{{ $companyName ?? 'Seven Inc' }}</div>
      </div>
  </div>

  <!-- Optional Footer -->
  <div class="footer">
      <p>© 2023 {{ $companyName ?? 'Seven Inc' }}. All rights reserved.</p>
  </div>

</body>
</html>
