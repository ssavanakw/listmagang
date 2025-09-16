@php
use Carbon\Carbon;
Carbon::setLocale('id');

// Guard
$template = $template ?? 'certmagangjogjacom';
$intern   = $intern   ?? null;

// === Tanggal & durasi ===
$fmt = static fn($d) => $d ? Carbon::parse($d)->translatedFormat('d F Y') : '—';
$startDate    = $startDate ?? $fmt($intern->start_date ?? null);
$endDate      = $endDate   ?? $fmt($intern->end_date   ?? null);

if (!empty($intern->start_date) && !empty($intern->end_date)) {
  $monthsFloat  = round(Carbon::parse($intern->start_date)->diffInDays(Carbon::parse($intern->end_date)) / 30, 1);
  $durationText = $durationText ?? str_replace('.', ',', (string)$monthsFloat) . ' bulan';
} else {
  $durationText = $durationText ?? 'beberapa bulan';
}

// === Data dasar ===
$title     = $title     ?? 'Sertifikat';
$company   = $company   ?? 'Seven Inc.';
$recipient = $recipient ?? ($intern->fullname ?? 'Nama Pemagang');
$deptText  = $deptText  ?? ($intern->internship_interest ?? 'Human Resource');
$city      = $city      ?? ($intern->current_city ?? 'Yogyakarta');

// === Aset per template (URL publik) ===
switch ($template) {
  case 'certareakerjacom':
    $bg        = $bg        ?? '/storage/images/bg_areakerjacom.png';
    $logo      = $logo      ?? '/storage/images/logo_areakerja.png';
    $logoRight = $logoRight ?? '/storage/images/logo_seveninc.png';
    $ttdHr     = $ttdHr     ?? '/storage/images/ttd_arisetiahusbana.png';
    $ttdDir    = $ttdDir    ?? '/storage/images/ttd_pipitdamayanti.png';

    $deptLabel     = $deptLabel     ?? 'HR Departement';
    $directorLabel = $directorLabel ?? 'Direktur';
    $hrRole  = $hrRole  ?? $deptLabel;
    $dirRole = $dirRole ?? $directorLabel;

    $hrName = $hrName ?? 'Ari Setia Husbana';
    $dirName= $dirName?? 'Pipit Damayanti';
    break;

  default: // 'certmagangjogjacom' (dan template serupa owner)
    $bg        = $bg        ?? '/storage/images/bg_magangjogjacom.png';
    $logo      = $logo      ?? '/storage/images/logo_magangjogjacom.png';
    $logoRight = $logoRight ?? '/storage/images/logo_seveninc.png';
    $ttdHr     = $ttdHr     ?? '/storage/images/ttd_arisetiahusbana.png';
    $ttdDir    = $ttdDir    ?? '/storage/images/ttd_rekariodanny.png';

    $deptLabel     = $deptLabel     ?? 'HR Departement';
    $hrRole  = $hrRole  ?? $deptLabel;
    $dirRole = $dirRole ?? 'Owner Seven Inc.';

    $hrName = $hrName ?? 'Ari Setia Husbana';
    $dirName= $dirName?? 'Rekario Danny';
    break;
}

// === Viewer background (CSS) ===
$viewerBg = $viewerBg ?? ($bg ? "url('{$bg}') center/cover no-repeat #ffffff" : '#ffffff');

// === Posisi/ukuran TTD default (jaga struktur variabel tetap) ===
$contentOffset = $contentOffset ?? 0;
$hrRoleTop     = $hrRoleTop     ?? 0;
$dirRoleTop    = $dirRoleTop    ?? 0;

$ttdHrW   = $ttdHrW   ?? 300;  $ttdHrTop  = $ttdHrTop  ?? 80;  $ttdHrLeft  = $ttdHrLeft  ?? 0;
$ttdDirW  = $ttdDirW  ?? 300;  $ttdDirTop = $ttdDirTop ?? 80;  $ttdDirLeft = $ttdDirLeft ?? 0;
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
</body>
</html>