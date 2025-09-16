@vite(['resources/css/app.css', 'resources/js/app.js'])

@php
  use Carbon\Carbon;
  Carbon::setLocale('id');

  // ====== Prefer data dari $intern (fallback ke dummy) ======
  $fmt = static fn($d) => $d ? Carbon::parse($d)->translatedFormat('d F Y') : '—';

  // Nama file & UI
  $pdfBaseName  = $pdfBaseName  ?? 'Sertifikat';

  // Perusahaan & judul
  $company      = $company      ?? 'AREAKERJA.COM';
  $title        = $title        ?? 'SERTIFIKAT';

  // Penerima & info magang
  $recipient    = $recipient    ?? ($intern->fullname ?? 'Budi Santoso');
  $deptText     = $deptText     ?? ($intern->internship_interest ?? 'Teknologi Informasi');

  $startDate    = $startDate    ?? ($intern->start_date ? $fmt($intern->start_date) : '01 Juni 2025');
  $endDate      = $endDate      ?? ($intern->end_date   ? $fmt($intern->end_date)   : '31 Agustus 2025');
  $city         = $city         ?? ($intern->current_city ?? 'Yogyakarta');

  if (!empty($intern?->start_date) && !empty($intern?->end_date)) {
    $monthsFloat   = Carbon::parse($intern->start_date)->diffInDays(Carbon::parse($intern->end_date)) / 30;
    $durationText  = $durationText ?? str_replace('.', ',', (string)round($monthsFloat, 1)) . ' Bulan';
  } else {
    $durationText  = $durationText ?? '3 Bulan';
  }

  // ===== Dummy file (storage/images) =====
  $bg        = $bg        ?? asset('storage/images/bg_areakerja.png');
  $logo      = $logo      ?? asset('storage/images/logo_areakerja.png');
  $ttdHr     = $ttdHr     ?? asset('storage/images/ttd_arisetiahusbana.png');
  $ttdDir    = $ttdDir    ?? asset('storage/images/ttd_pipitdamayanti.png');

  // ===== Optional style position/size =====
  $viewerBg     = $viewerBg     ?? '#f8f8f8';
  $contentOffset= $contentOffset?? 0;
  $hrRoleTop    = $hrRoleTop    ?? 0;
  $dirRoleTop   = $dirRoleTop   ?? 0;
  $ttdHrW       = $ttdHrW       ?? 260;
  $ttdDirW      = $ttdDirW      ?? 260;

  // Role & Nama
  $hrRole  = $hrRole  ?? 'HR Department';
  $hrName  = $hrName  ?? 'Ari Setia Husbana';
  $dirRole = $dirRole ?? 'Direktur';
  $dirName = $dirName ?? 'Pipit Damayanti';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="color-scheme" content="light">
  <title>{{ $pdfBaseName ?? 'Sertifikat' }}</title>

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  @if(!empty($bg))
    <link rel="preload" as="image" href="{{ $bg }}">
  @endif
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* A4 landscape @96DPI: 1123 x 794 */
    @page {
      size: 1123px 794px;
      margin: 0;
    }

    :root{
      --brand: #E96A2D;
      --ink: #5C6065;
      --paper: #ffffff;
      --line: #999999;
      --shadow-1: 0 40px 80px rgba(0,0,0,.18);
      --shadow-2: 0 2px 8px rgba(0,0,0,.08);
    }

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      background: {!! $viewerBg !!} !important; /* dari controller */
      -webkit-print-color-adjust: exact; print-color-adjust: exact;
      font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }

    .viewer {
      min-height: 100vh;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      box-sizing: border-box;
    }

    .page {
      position: relative;
      width: 1123px;
      height: 794px;
      overflow: hidden;
      box-sizing: border-box;
      background: var(--paper);
    }

    @media screen {
      .page {
        box-shadow: var(--shadow-1), var(--shadow-2);
        border-radius: 4px;
        outline: 1px solid rgba(0,0,0,.05);
      }
    }

    .bg {
      position: absolute;
      inset: 0;
      z-index: 0;
      background: center/cover no-repeat;
    }

    .header {
      position: absolute;
      top: 36px;
      left: 56px;
      right: 56px;
      z-index: 2;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 24px;
    }
    .brand-left {
      display: flex;
      flex-direction: column;
      gap: 6px;
      align-items: flex-start;
      min-width: 180px;
    }
    .logo {
      height: 74px;
      width: auto;
    }
    .brand-text {
      font-weight: 700;
      font-size: 12px;
      letter-spacing: .12em;
      color: var(--brand);
      text-transform: uppercase;
    }

    .content {
      position: relative;
      z-index: 1;
      width: 100%;
      height: 100%;
      padding: 62px 0 64px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      color: var(--ink);
    }

    .title {
      font-family: 'Pacifico', cursive;
      margin-top: 64px;
      font-weight: 400;
      font-size: 64px;
      line-height: 1.02;
      color: var(--brand);
    }
    .subtitle {
      margin-top: 60px;
      font-weight: 600;
      font-size: 22px;
      color: var(--brand);
    }
    .name {
      margin-top: 60px;
      font-family: 'Pacifico', cursive;
      font-size: 58px;
      color: #D6561F;
    }
    .divider {
      width: 700px;
      height: 3px;
      margin: 8px auto 12px;
      background: #868686;
      border-radius: 2px;
    }
    .desc {
      max-width: 820px;
      margin-top: 2px;
      font-size: 18px;
      font-weight: 600;
      line-height: 1.55;
      color: var(--brand);
    }

    .sign-wrap {
      width:100%;
      margin-top:40px;
      display:flex;
      align-items:flex-start;
      justify-content:center;
      gap:100px;
    }
    .sign {
      position:relative;
      text-align:center;
      width:420px;
      height:130px;
    }
    .role {
      position:absolute;
      top:0;
      left:50%;
      transform:translateX(-50%);
      font-size:16px;
      color:var(--brand);
      font-weight:600;
    }

    .sigimg-free{
      position:absolute;
      top:0;
      left:50%;
      transform:translateX(-50%);
      z-index:3;
      object-fit:contain;
      display:block;
      pointer-events:none;
      user-select:none;
    }
    .line{
      position:absolute;
      left:50%;
      transform:translateX(-50%);
      bottom:40px;
      width:180px;
      height:2px;
      background:var(--line);
      border-radius:3px;
      z-index:2;
    }
    .person{
      position:absolute;
      left:50%;
      transform:translateX(-50%);
      bottom: 20px;
      font-size:14px;
      color:var(--brand);
      font-weight:600;
      z-index:2;
    }

    @media print {
      html, body, .viewer {
        background: #fff !important;
      }
      .viewer {
        padding: 0;
      }
      .page {
        box-shadow: none;
        border-radius: 0;
        outline: none;
      }
    }
  </style>
</head>
<body>
  <div class="viewer">
    <div class="page" aria-label="Sertifikat {{ $company }}">
      {{-- background div memakai data URI / URL $bg --}}
      <div class="bg" style="background-image:url('{{ $bg }}');" role="img" aria-label="Background {{ $company }}"></div>

      <header class="header" aria-label="Kepala Halaman">
        <div class="brand-left" aria-label="Brand Kiri">
          @if(!empty($logo))
            <img class="logo" src="{{ $logo }}" alt="Logo {{ $company }}">
          @endif
          <div class="brand-text">{{ Str::upper(Str::slug($company,' ')) }}</div>
        </div>
      </header>

      <main class="content">
        <div class="content-inner" style="top:{{ (int)($contentOffset ?? 0) }}px;">
          <h1 class="title">{{ $title }}</h1>
          <p class="subtitle">Diberikan Kepada :</p>
          <div class="name">{{ $recipient }}</div>
          <div class="divider" aria-hidden="true"></div>

          <p class="desc">
            Telah menyelesaikan magang bidang <span>{{ $deptText }}</span> di {{ $company }} selama
            <span>{{ $durationText }}</span><br>
            yaitu mulai dari <span>{{ $startDate }}</span>
            sampai dengan <span>{{ $endDate }}</span><br>
          </p>
        </div>

        <section class="sign-wrap" aria-label="Tanda Tangan">
          {{-- KIRI (HR) --}}
          <div class="sign" aria-label="Tanda Tangan {{ $hrRole }}">
            <div class="role" style="top:{{ (int)($hrRoleTop ?? 0) }}px;">{{ $hrRole }}</div>
            @if(!empty($ttdHr))
              <img class="sigimg-free"
                   src="{{ $ttdHr }}"
                   alt="Tanda tangan {{ $hrName }}"
                   style="width:140px; top:{{ (int)($ttdHrTop ?? 5) }}px; transform:translateX(-50%) translateX({{ (int)($ttdHrLeft ?? -3) }}px);">
            @endif
            <div class="line"></div>
            <div class="person">{{ $hrName }}</div>
          </div>

          {{-- KANAN (Direktur/Owner) --}}
          <div class="sign" aria-label="Tanda Tangan {{ $dirRole }}">
            <div class="role" style="top:{{ (int)($dirRoleTop ?? 0) }}px;">{{ $dirRole }}</div>
            @if(!empty($ttdDir))
              <img class="sigimg-free"
                   src="{{ $ttdDir }}"
                   alt="Tanda tangan {{ $dirName }}"
                   style="width:200px; top:{{ (int)($ttdDirTop ?? -9) }}px; transform:translateX(-50%) translateX({{ (int)($ttdDirLeft ?? 0) }}px);">
            @endif
            <div class="line"></div>
            <div class="person">{{ $dirName }}</div>
          </div>
        </section>
      </main>
    </div>
  </div>
</body>
</html>
