{{-- resources/views/certificates/certareakerjacom.blade.php --}}
@php
  // ==== DEFAULTS (boleh dihapus di produksi) ====
  $bg           = $bg           ?? asset('storage/images/bg_areakerja.png');
  $logo         = $logo         ?? asset('storage/images/logo_areakerja.png');
  $title        = $title        ?? 'SERTIFIKAT';
  $recipient    = $recipient    ?? 'Fida Royyanatus Syahr';
  $deptText     = $deptText     ?? 'Human Resource';
  $durationText = $durationText ?? '1,5 bulan';
  $startDate    = $startDate    ?? '21 April 2025';
  $endDate      = $endDate      ?? '30 Mei 2025';

  $hrRole       = $hrRole       ?? 'HR Departement';
  $hrName       = $hrName       ?? 'Ari Setia Husbana';
  $dirRole      = $dirRole      ?? 'Direktur';
  $dirName      = $dirName      ?? 'Pipit Damayanti';

  // tanda tangan (png transparan)
  $ttdHr        = $ttdHr        ?? asset('storage/images/ttd_hr.png');
  $ttdDir       = $ttdDir       ?? asset('storage/images/ttd_direktur.png');

    // ========= TTD resolusi ganda (opsional) =========
  $ttdHr2x = $ttdHr2x ?? null;
  $ttdHr3x = $ttdHr3x ?? null;
  $ttdDir2x= $ttdDir2x?? null;
  $ttdDir3x= $ttdDir3x?? null;
  
  
  // ================== UTIL: TANGGAL & DURASI ==================
  function parseTanggal($s) {
    if (!$s) return null;
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $s, $m)) {
      return \Carbon\Carbon::createFromDate((int)$m[1], (int)$m[2], (int)$m[3]);
    }
    try { return \Carbon\Carbon::parse($s); } catch (\Exception $e) { return null; }
  }

  function formatIndo($s) {
    $d = parseTanggal($s);
    if (!$d) return $s ?: '??';
    $bulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    return $d->format('j').' '.$bulan[(int)$d->format('n')].' '.$d->format('Y');
  }

  function hitungDurasiBulan($start, $end) {
    $a = parseTanggal($start); $b = parseTanggal($end);
    if (!$a || !$b) return null;
    $months = ($b->year - $a->year) * 12 + ($b->month - $a->month);
    if ((int)$b->format('j') < (int)$a->format('j')) $months -= 1;
    return max(0, $months);
  }

  if (empty($durationText)) {
    $m = hitungDurasiBulan($startDate, $endDate);
    $durationText = $m !== null && $m > 0 ? "{$m} bulan" : 'beberapa bulan';
  }

  // ================== MODE TAMPILAN ==================
  $mode = request()->query('mode', 'edit');
  $viewerBg = $mode === 'clean' ? '#ffffff' : '#f3f4f6';

  // ================== SRCSET (jika ada) ==================
  $srcsetHr  = trim(implode(', ', array_filter([
    $ttdHr   ? "{$ttdHr} 1x"   : null,
    $ttdHr2x ? "{$ttdHr2x} 2x" : null,
    $ttdHr3x ? "{$ttdHr3x} 3x" : null,
  ])));
  $srcsetDir = trim(implode(', ', array_filter([
    $ttdDir   ? "{$ttdDir} 1x"   : null,
    $ttdDir2x ? "{$ttdDir2x} 2x" : null,
    $ttdDir3x ? "{$ttdDir3x} 3x" : null,
  ])));

  $contentOffset = $contentOffset ?? 70; // default geser 70px

  // ================== KONTROL BEBAS PX UNTUK TTD ==================
  $hrRoleTop = $hrRoleTop ?? 65;  // px: geser teks role HR
  $dirRoleTop = $dirRoleTop ?? 65; // px: geser teks role Direktur

  // (bisa di-override dari controller)
  $ttdHrW    = $ttdHrW    ?? 180; // lebar TTD HR (px)
  $ttdHrTop  = $ttdHrTop  ?? 65;  // offset dari atas kontainer sign (px; minus = naik)
  $ttdHrLeft = $ttdHrLeft ?? 0;   // offset horizontal relatif tengah (px; minus = kiri)

  $ttdDirW    = $ttdDirW    ?? 220; // lebar TTD Direktur (px)
  $ttdDirTop  = $ttdDirTop  ?? 50;  // offset dari atas (px)
  $ttdDirLeft = $ttdDirLeft ?? 0;   // offset horizontal (px)

@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>{{ $title }} - AreaKerja</title>

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="image" href="{{ $bg }}">
<link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* A4 landscape @96DPI: 1123 x 794 */
    @page { size: 1123px 794px; margin: 0; }

    :root{
      --brand: #E96A2D;
      --ink: #5C6065;
      --paper: #ffffff;
      --line: #999999;
      --shadow-1: 0 40px 80px rgba(0,0,0,.18);
      --shadow-2: 0 2px 8px rgba(0,0,0,.08);
    }

    html, body {
      margin: 0; padding: 0; height: 100%;
      background: {{ $viewerBg }} !important;
      -webkit-print-color-adjust: exact; print-color-adjust: exact;
      font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }

    .viewer {
      min-height: 100vh; width: 100%;
      display: flex; align-items: center; justify-content: center;
      padding: 24px; box-sizing: border-box;
    }

    .page {
      position: relative; width: 1123px; height: 794px;
      overflow: hidden; box-sizing: border-box; background: var(--paper);
    }

    @media screen {
      .page { box-shadow: var(--shadow-1), var(--shadow-2); border-radius: 4px; outline: 1px solid rgba(0,0,0,.05); }
    }

    .bg { position: absolute; inset: 0; z-index: 0; background: url('{{ $bg }}') center/cover no-repeat; }

    /* Header (dua logo opsional) */
    .header {
      position: absolute; top: 36px; left: 56px; right: 56px; z-index: 2;
      display: flex; align-items: flex-start; justify-content: space-between; gap: 24px;
    }
    .brand-left, .brand-right { display: flex; flex-direction: column; gap: 6px; align-items: flex-start; min-width: 180px; }
    .brand-right { align-items: flex-end; }
    .logo { height: 78px; width: auto; }
    .brand-text { font-weight: 700; font-size: 11px; letter-spacing: .24em; color: var(--brand); text-transform: uppercase; }

    /* Konten */
    .content {
      position: relative; z-index: 1; width: 100%; height: 100%;
      padding: 62px 0 64px;
      display: flex; flex-direction: column; align-items: center; text-align: center; color: var(--ink);
    }

    .title { margin-top: 12px; font-weight: 700; font-size: 76px; line-height: 1.02; color: var(--brand); }
    .subtitle { margin-top: 20px; font-weight: 600; font-size: 22px; color: var(--brand); }
    .name { margin-top: 12px; font-family: 'Pacifico', cursive; font-size: 58px; font-style: normal; color: #D6561F; }
    .divider { width: 520px; height: 3px; margin: 8px auto 12px; background: #e5e5e5; border-radius: 2px; }
    .desc { max-width: 820px; margin-top: 2px; font-size: 18px; font-weight: 600; line-height: 1.55; color: var(--brand); }

    /* ================== Tanda Tangan (absolute: stabil) ================== */
    .sign-wrap{
      width:100%; margin-top:34px;
      display:flex; align-items:flex-start; justify-content:center; gap:140px;
      
    }
    .sign{
      position:relative; text-align:center; width:420px; height:230px; /* ruang tetap agar layout stabil */
    }

    /* Role tetap (absolute) */
    .role{
      position:absolute; top:0; left:50%; transform:translateX(-50%);
      z-index:2; font-size:16px; color:var(--brand); font-weight:600;
    }

    /* Gambar TTD bebas & di atas role/garis/nama */
    .sigimg-free{
      position:absolute; top:0; left:50%; transform:translateX(-50%);
      z-index:3; object-fit:contain; display:block; pointer-events:none; user-select:none;
    }

    /* Garis (absolute) */
    .line{
      position:absolute; left:50%; transform:translateX(-50%);
      bottom:70px; width:200px; height:2px; background:var(--line); border-radius:3px; z-index:2;
    }

    /* Nama (absolute) */
    .person{
      position:absolute; left:50%; transform:translateX(-50%);
      bottom: 40px; font-size:18px; color:var(--brand); font-weight:600; z-index:2;
    }
    .content-inner{
      position: relative;   /* agar bisa di-offset tanpa mempengaruhi sibling */
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    @media print { html, body, .viewer { background: #fff !important; } .viewer { padding: 0; } .page { box-shadow: none; border-radius: 0; outline: none; } }
  </style>
</head>
<body>
  <div class="page" aria-label="Sertifikat AreaKerja">
    <div class="bg" role="img" aria-label="Background AreaKerja"></div>

    <!-- PRELOAD background CSS agar benar-benar terunduh (tersembunyi; tidak mengubah tampilan) -->
    <img src="{{ $bg }}" alt="" aria-hidden="true" style="position:absolute;width:0;height:0;opacity:0;pointer-events:none;">

    <div class="content">
      <div class="header">
        <div class="brand">
          <img class="logo" src="{{ $logo }}" alt="Logo AreaKerja">
          <div class="brand-text">AREAKERJA.COM</div>
        </div>
        <div class="header-placeholder" style="width:64px;"></div>
      </div>

      <h1 class="title">{{ $title }}</h1>

      <div class="subtitle">Diberikan Kepada :</div>
      <div class="name">{{ $recipient }}</div>
      <div class="divider"></div>

      <p class="desc">
        Telah menyelesaikan magang bidang <span>{{ $deptText }}</span> di Area Kerja selama
        <span>{{ $durationText }}</span><br/>
        yaitu mulai dari <span>{{ $startDate }}</span> sampai dengan <span>{{ $endDate }}</span>
      </p>

      <div class="triangle" aria-hidden="true"></div>

      <div class="sign-wrap">
        <div class="sign">
          <div class="role">{{ $hrRole }}</div>
          @if($ttdHr)
            <img class="sigimg" src="{{ $ttdHr }}" alt="Tanda tangan {{ $hrName }}">
          @else
            <div style="height:56px"></div>
          @endif
          <div class="line"></div>
          <div class="person">{{ $hrName }}</div>
        </div>

        <div class="sign">
          <div class="role">{{ $dirRole }}</div>
          @if($ttdDir)
            <img class="sigimg" src="{{ $ttdDir }}" alt="Tanda tangan {{ $dirName }}">
          @else
            <div style="height:56px"></div>
          @endif
          <div class="line"></div>
          <div class="person">{{ $dirName }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- FLAG untuk Browsershot; tidak mengubah tampilan -->
  <script>
    (function() {
      function waitFonts() {
        return (document.fonts && document.fonts.ready) ? document.fonts.ready : Promise.resolve();
      }
      function waitImages() {
        var imgs = Array.prototype.slice.call(document.images || []);
        if (!imgs.length) return Promise.resolve();
        return Promise.all(imgs.map(function(img){
          if (img.complete) return Promise.resolve();
          return new Promise(function(resolve){
            img.addEventListener('load', resolve, {once:true});
            img.addEventListener('error', resolve, {once:true});
          });
        }));
      }
      function nextFrame() {
        return new Promise(function(r){ requestAnimationFrame(function(){ requestAnimationFrame(r); }); });
      }
      function ready() {
        Promise.all([waitFonts(), waitImages(), nextFrame()]).then(function(){
          window.__CERT_READY = true;
        });
      }
      if (document.readyState === 'complete' || document.readyState === 'interactive') {
        ready();
      } else {
        document.addEventListener('DOMContentLoaded', ready, {once:true});
      }
    })();
  </script>
</body>
</html>
