{{-- resources/views/user/skl.blade.php --}}
@php
  use Carbon\Carbon;

  /** ========================================================
   *  SETUP DATA & FALLBACK
   * ======================================================== */
  $font = "font-family: DejaVu Sans, Arial, Helvetica, sans-serif;";
  $intern = $intern ?? $reg ?? null;

  // ====== Brand / Instansi ======
  $companyName    = $intern->company_name      ?? config('app.company_name', 'Seven Inc');
  $companyAddress = $intern->company_address   ?? config('app.company_address', 'Jl. Raya Teknologi No. 17, Jakarta');
  $leaderName     = $intern->leader_name       ?? config('app.company_leader_name', 'Nama Pimpinan / HRD');
  $leaderTitle    = $intern->leader_title      ?? config('app.company_leader_title', 'Manajer HRD');
  $city           = $intern->company_city      ?? config('app.company_city', 'Jakarta');
  $letterNumber   = $intern->letter_number     ?? ('SKL/' . now()->format('Y') . '/' . ($intern->id ?? 'XXX'));

  // ====== Logo & Stempel ======
  $logoPath  = asset('public/storage/images/logos/logo_seveninc.png');
  $stampPath = asset('storage/images/logos/stamp.png'); // opsional: tambahkan file stempel transparan

  // ====== Data Peserta ======
  $participantName      = $intern->fullname           ?? ($user->name ?? '-');
  $participantId        = $intern->student_id         ?? '-';
  $participantMajor     = $intern->study_program      ?? '-';
  $participantInstitute = $intern->institution_name   ?? '-';
  $divisionName         = $intern->internship_interest ?? '-';

  // ====== Periode ======
  $startAt = $intern?->start_date ? Carbon::parse($intern->start_date) : null;
  $endAt   = $intern?->end_date   ? Carbon::parse($intern->end_date)   : null;
  $startStr = $startAt ? $startAt->isoFormat('D MMMM Y') : '[Tanggal Mulai]';
  $endStr   = $endAt   ? $endAt->isoFormat('D MMMM Y')   : '[Tanggal Selesai]';
  $letterDate = $endAt ?: Carbon::now();
  $letterDateStr = $letterDate->isoFormat('D MMMM Y');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Keterangan Selesai Magang (SKL)</title>
  <style>
    body {
      {{ $font }}
      font-size: 12px;
      color: #0f172a;
      background: #fff;
      margin: 0;
      line-height: 1.5;
    }
    .wrap { padding: 0px; }
    .letter { max-width: 780px; margin: 0; }

    /* Header */
    .header { display: flex; justify-content: space-between; align-items: center; gap: 16px; }
    .brand { display: flex; align-items: center; gap: 12px; }
    .brand img { width: 64px; height: auto; border-radius: 8px; }
    .company { font-weight: 700; font-size: 16px; }
    .company small { display: block; color: #64748b; font-weight: 500; }
    .kop { border-top: 3px solid #059669; margin-top: 10px; padding-top: 6px; }
    .meta-top { text-align: right; }
    .meta-top .no { font-weight: 700; }
    .meta-top small { color: #64748b; }

    /* Title */
    h1.title { text-align: center; font-size: 18px; margin: 20px 0 16px; color: #065f46; letter-spacing: 0.3px; }

    /* Content */
    .content p { text-align: justify; margin: 6px 0; }

    /* Table-like info */
    .table-like p { margin: 4px 0; }
    .table-like strong { display: inline-block; width: 160px; color: #047857; }

    /* Signature */
    .sign-wrap { margin-top: 32px; display: flex; justify-content: flex-end; }
    .sign { text-align: center; width: 260px; }
    .stamp { width: 100px; height: 100px; margin: 6px auto 0; opacity: .25; position: absolute; left: 0; right: 0; }
    .muted { color: #6b7280; font-size: 11px; text-align: center; margin-top: 14px; }

    @page { margin: 2.5cm 2cm; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="letter">

      {{-- HEADER --}}
      <div class="header">
        <div class="brand">
          <img src="{{ $logoPath }}" alt="Logo">
          <div>
            <div class="company">{{ $companyName }}</div>
            <small>{{ $companyAddress }}</small>
          </div>
        </div>
        <div class="meta-top">
          <div class="kop"></div>
          <div class="no">Nomor: {{ $letterNumber }}</div>
          <small>{{ $startStr }} — {{ $endStr }}</small>
        </div>
      </div>

      {{-- TITLE --}}
      <h1 class="title">SURAT KETERANGAN SELESAI MAGANG</h1>

      {{-- PENANDATANGAN --}}
      <div class="table-like">
        <p>Yang bertanda tangan di bawah ini:</p>
        <p><strong>Nama</strong> {{ $leaderName }}</p>
        <p><strong>Jabatan</strong> {{ $leaderTitle }}</p>
        <p><strong>Perusahaan</strong> {{ $companyName }}</p>
        <p><strong>Alamat</strong> {{ $companyAddress }}</p>
      </div>

      {{-- PESERTA --}}
      <div class="table-like" style="margin-top:12px;">
        <p>Dengan ini menerangkan bahwa:</p>
        <p><strong>Nama</strong> {{ $participantName }}</p>
        <p><strong>NIM / Identitas</strong> {{ $participantId }}</p>
        <p><strong>Program Studi</strong> {{ $participantMajor }}</p>
        <p><strong>Asal Institusi</strong> {{ $participantInstitute }}</p>
      </div>

      {{-- ISI --}}
      <div class="content" style="margin-top:10px;">
        <p>
          Telah menyelesaikan program magang di <strong>{{ $companyName }}</strong> pada divisi
          <strong>{{ $divisionName }}</strong> selama periode <strong>{{ $startStr }}</strong> hingga <strong>{{ $endStr }}</strong>.
        </p>
        <p>
          Selama mengikuti kegiatan magang, yang bersangkutan menunjukkan sikap profesional,
          disiplin, dan semangat belajar yang tinggi, serta mampu menyelesaikan setiap tugas yang diberikan dengan baik.
        </p>
        <p>
          Surat keterangan ini dibuat sebagai bukti bahwa peserta telah menyelesaikan masa magangnya
          dengan hasil yang memuaskan, untuk dipergunakan sebagaimana mestinya.
        </p>
      </div>

      {{-- TANDA TANGAN --}}
      <div class="sign-wrap">
        <div class="sign">
          <div>{{ $city }}, {{ $letterDateStr }}</div>
          <div>Hormat kami,</div>
          <div style="height: 80px; position: relative;">
            @if(file_exists(public_path('storage/images/logos/stamp.png')))
              <img src="{{ $stampPath }}" alt="Stempel" class="stamp">
            @endif
          </div>
          <div style="font-weight:700;">{{ $leaderName }}</div>
          <div style="font-size:12px;">{{ $leaderTitle }}</div>
          <div style="font-size:12px;">{{ $companyName }}</div>
        </div>
      </div>

      <div class="muted">
        Dokumen ini sah tanpa tanda tangan basah karena dihasilkan secara digital.
      </div>
    </div>
  </div>
</body>
</html>
