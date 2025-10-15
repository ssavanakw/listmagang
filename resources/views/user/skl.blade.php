{{-- resources/views/user/skl.blade.php --}}
@php
  use Carbon\Carbon;

  /**
   * Sumber data utama = $intern (App\Models\InternshipRegistration)
   * Fallback ambil dari config('app.*') / nilai default agar aman.
   */

  $font = "font-family: DejaVu Sans, Arial, Helvetica, sans-serif;";

  // ====== Brand/Instansi (ambil dari IR bila ada, fallback config/app.php) ======
  // Tambahkan kolom di IR jika kamu punya: company_name, company_address, leader_name, leader_title, company_city, letter_number
  $companyName    = $intern->company_name      ?? ( $companyName    ?? config('app.company_name', config('app.name', 'Nama Perusahaan')) );
  $companyAddress = $intern->company_address   ?? ( $companyAddress ?? config('app.company_address', 'Alamat Perusahaan') );
  $leaderName     = $intern->leader_name       ?? ( $leaderName     ?? config('app.company_leader_name', 'Nama Pimpinan / Manajer HRD') );
  $leaderTitle    = $intern->leader_title      ?? ( $leaderTitle    ?? config('app.company_leader_title', 'Jabatan Pimpinan') );
  $city           = $intern->company_city      ?? ( $city           ?? config('app.company_city', 'Kota') );

  // Nomor surat: pakai yang ada di IR (jika kamu simpan), kalau tidak, format default: SKL/{Y}/{id}
  $letterNumber   = $intern->letter_number     ?? ( $letterNumber   ?? (config('app.company_letter_prefix', 'SKL') . '/' . now()->format('Y') . '/' . $intern->id) );

  // (Opsional) logo & stempel (kamu bisa set di controller)
  $logoPath  = $logoPath  ?? null;   // contoh: asset('storage/branding/logo.png')
  $stampPath = $stampPath ?? null;   // contoh: asset('storage/branding/stamp.png')

  // ====== Data peserta (ambil dari IR) ======
  $participantName      = $intern->fullname           ?: ($user->name ?? 'Nama Peserta');
  $participantId        = $intern->student_id         ?: '-';
  $participantMajor     = $intern->study_program      ?: '-';
  $participantInstitute = $intern->institution_name   ?: '-';
  // Divisi/minat magang
  $divisionName         = $intern->internship_interest ?: '-';

  // ====== Periode (ambil dari IR) ======
  $startAt   = $intern?->start_date ? Carbon::parse($intern->start_date) : null;
  $endAt     = $intern?->end_date   ? Carbon::parse($intern->end_date)   : null;
  $startStr  = $startAt ? $startAt->isoFormat('D MMMM Y') : '[Tanggal Mulai]';
  $endStr    = $endAt   ? $endAt->isoFormat('D MMMM Y')   : '[Tanggal Selesai]';

  // Tanggal surat: pakai tanggal selesai jika ada, kalau tidak, hari ini
  $letterDate    = $endAt ?: Carbon::now();
  $letterDateStr = $letterDate->isoFormat('D MMMM Y');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Keterangan Selesai Magang</title>
  <style>
    body { {{ $font }} font-size: 12px; color:#0f172a; background:#fff; margin:0; }
    .wrap { padding: 28px; }
    .letter { max-width: 780px; margin: 0 auto; }
    .header { display:flex; align-items:center; justify-content:space-between; gap:16px; }
    .brand { display:flex; align-items:center; gap:16px; }
    .logo { width:72px; height:72px; border-radius:8px; background:#111; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; }
    .company { font-weight:700; font-size:16px; }
    .company small { display:block; color:#6b7280; font-weight:600; }
    .kop { border-top: 4px solid #0f172a; padding-top: 10px; margin-top: 8px; }
    .meta-top { text-align:right; }
    .meta-top .no { font-weight:700; }
    h1.title { text-align:center; font-size:18px; margin:12px 0 18px; }
    .content p { line-height:1.7; text-align:justify; margin:8px 0; }
    .table-like p { margin:4px 0; }
    .footer { display:flex; justify-content:flex-end; margin-top:28px; }
    .sign { width: 260px; text-align:center; }
    .stamp { width:100px; height:100px; margin:6px auto 0; opacity:.9; }
    .muted { color:#6b7280; font-size:11px; text-align:center; margin-top:10px; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="letter">

      {{-- Header / Kop --}}
      <div class="header">
        <div class="brand">
          @if(!empty($logoPath))
            <img src="{{ $logoPath }}" alt="Logo" class="logo" style="object-fit:cover;">
          @else
            <div class="logo">LOGO</div>
          @endif
          <div>
            <div class="company">{{ $companyName }}</div>
            <small>{{ $companyAddress }}</small>
          </div>
        </div>
        <div class="meta-top">
          <div class="kop"></div>
          <div class="no">Nomor: {{ $letterNumber }}</div>
          <div style="color:#6b7280; font-size:11px;">{{ $startStr }} — {{ $endStr }}</div>
        </div>
      </div>

      <h1 class="title">SURAT KETERANGAN SELESAI MAGANG</h1>

      {{-- Identitas penandatangan --}}
      <div class="table-like">
        <p>Yang bertanda tangan di bawah ini:</p>
        <p><strong>Nama</strong> : {{ $leaderName }}</p>
        <p><strong>Jabatan</strong> : {{ $leaderTitle }}</p>
        <p><strong>Perusahaan</strong> : {{ $companyName }}</p>
        <p><strong>Alamat</strong> : {{ $companyAddress }}</p>
      </div>

      {{-- Identitas peserta --}}
      <div class="table-like" style="margin-top:10px;">
        <p>Dengan ini menyatakan bahwa:</p>
        <p><strong>Nama</strong> : {{ $participantName }}</p>
        <p><strong>NIM/Nomor Identitas</strong> : {{ $participantId }}</p>
        <p><strong>Program Studi/Jurusan</strong> : {{ $participantMajor }}</p>
        <p><strong>Asal Institusi</strong> : {{ $participantInstitute }}</p>
      </div>

      {{-- Pernyataan kelulusan --}}
      <div class="content" style="margin-top:10px;">
        <p>
          Telah selesai melaksanakan program magang di <strong>{{ $companyName }}</strong> pada divisi
          <strong>{{ $divisionName }}</strong> terhitung sejak tanggal <strong>{{ $startStr }}</strong>
          sampai dengan tanggal <strong>{{ $endStr }}</strong>.
        </p>
        <p>
          Selama melaksanakan kegiatan magang, yang bersangkutan telah bekerja dengan baik, menunjukkan dedikasi,
          inisiatif, dan kemampuan yang positif dalam menyelesaikan tugas-tugas yang diberikan.
        </p>
        <p>
          Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>
      </div>

      {{-- Tanda tangan --}}
      <div style="margin-top:18px; display:flex; justify-content:space-between; align-items:flex-start; gap:18px">
        <div style="flex-basis:60%"></div>
        <div class="sign">
          <div>{{ $city }}, {{ $letterDateStr }}</div>
          <div>Hormat kami,</div>
          <div style="height:68px; position:relative;">
            @if(!empty($stampPath))
              <img src="{{ $stampPath }}" alt="Stempel" class="stamp" style="position:absolute; left:0; right:0; margin:auto; top:0; opacity:.25;">
            @endif
          </div>
          <div style="font-weight:700">{{ $leaderName }}</div>
          <div style="font-size:12px">{{ $leaderTitle }}</div>
          <div style="font-size:12px">{{ $companyName }}</div>
        </div>
      </div>

      <div class="muted">
        Dokumen ini sah tanpa tanda tangan basah karena dihasilkan secara digital.
      </div>
    </div>
  </div>
</body>
</html>
