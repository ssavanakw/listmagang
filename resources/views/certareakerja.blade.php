{{-- resources/views/certificates/areakerja.blade.php --}}
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
  $ttdHr        = $ttdHr        ?? asset('storage/images/ttd_hr.png');       // letakkan file di storage/app/public/images/ttd_hr.png
  $ttdDir       = $ttdDir       ?? asset('storage/images/ttd_direktur.png'); // storage/app/public/images/ttd_direktur.png
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>{{ $title }} - AreaKerja</title>

<link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  /* A4 landscape @96DPI = 1123 x 794 px */
  @page { size: 1123px 794px; margin: 0; }

  :root{
    --brand:#E96A2D;
    --brand-strong:#D6561F;
    --text:#5C6065;
    --muted:#8a8f95;
    --line:#d9d9d9;
  }
  html,body{
    margin:0!important; padding:0!important;
    width:1123px; height:794px; background:#fff;
    -webkit-print-color-adjust: exact; print-color-adjust: exact;
    font-family:'Poppins', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    color:var(--text);
  }

  .page{position:relative; width:1123px; height:794px; overflow:hidden; box-sizing:border-box;}
  .bg{position:absolute; inset:0; background:url('{{ $bg }}') center/cover no-repeat; z-index:0;}

  .content{
    position:relative; z-index:1; width:100%; height:100%;
    padding:52px 84px 64px;  /* agak lebih naik agar mirip referensi */
    display:flex; flex-direction:column; align-items:center; text-align:center;
  }

  /* Header kiri: logo + teks areakerja.com */
  .header{width:100%; display:flex; align-items:flex-start; justify-content:space-between;}
  .brand{
    display:flex; flex-direction:column; align-items:flex-start; gap:6px;
  }
  .logo{height:64px; width:auto;}
  .brand-text{
    font-weight:700;
    font-size:12px;
    line-height:1;
    letter-spacing:0.24em; /* font “AREAKERJA.COM” lebih tegas seperti referensi */
    color:var(--brand);
    transform:translateX(6px); /* sedikit maju ke kanan */
    text-transform:uppercase;
  }

  .title{
    margin-top:18px;
    font-family:'Pacifico', cursive;
    font-size:64px; line-height:1;
    color:var(--brand);
    letter-spacing:1px;
  }

  .subtitle{margin-top:22px; font-weight:600; font-size:18px; color:#6a6f75;}

  .name{
    margin-top:14px;
    font-family:'Pacifico', cursive;
    font-size:48px; color:var(--brand-strong);
  }

  .divider{
    width:520px; height:3px; background:var(--line);
    margin:8px auto 16px; border-radius:2px;
  }

  .desc{
    max-width:760px; font-size:16px; font-weight:600; color:#ff6a00;
    line-height:1.4;
  }
  .desc span{color:#ff6a00;}

  /* Area tanda tangan */
  .sign-wrap{
    width:100%;
    margin-top:46px;   /* naik-turun tweak */
    display:flex; gap:120px; align-items:flex-end; justify-content:center;
  }
  .sign{
    width:320px; text-align:center;
  }
  .sigimg{
    height:56px; width:auto; object-fit:contain; object-position:center;
    filter: contrast(1.1) saturate(0) brightness(0.3); /* abu-abu seperti scan */
    margin:6px auto 8px;
  }
  .line{width:100%; height:2px; background:var(--line); margin:6px 0 6px;}
  .role{font-size:12px; color:var(--muted); margin-bottom:6px;}
  .person{font-size:12px; color:var(--muted);}

  /* Aksen segitiga */
  .triangle{
    position:absolute; left:50%; transform:translateX(-50%); bottom:94px;
    width:0;height:0; border-left:24px solid transparent; border-right:24px solid transparent; border-top:48px solid var(--brand);
  }

  @media print {.header-placeholder{display:none;}}
</style>
</head>
<body>
  <div class="page" aria-label="Sertifikat AreaKerja">
    <div class="bg" role="img" aria-label="Background AreaKerja"></div>

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
</body>
</html>
