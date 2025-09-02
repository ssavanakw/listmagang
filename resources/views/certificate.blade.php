<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Certificate</title>

<style>
  /* KUNCI: pakai page size PX 1123x794 (A4 landscape @96DPI) */
  @page { size: 1123px 794px; margin: 0; }

  :root{
    --dark: #293936;
    --accent: #BE5640;
    --black: #000;
    --greenHL: #2EBE54;
    --redHL: #D62118;
  }

  html, body{
    margin:0 !important;
    padding:0 !important;
    width:1123px;        /* match @page */
    height:794px;        /* match @page */
    background: var(--dark);
    -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
  }

  /* 1 halaman saja – ukurannya HARUS sama dengan @page */
  .page{
    width:1123px;
    height:794px;
    overflow:hidden;
    display:flex;
    align-items:center;
    justify-content:center;
    background: var(--dark); /* bingkai hijau gelap menyatu ke tepi halaman */
    box-sizing:border-box;
  }

  /* Canvas mengisi penuh halaman (tanpa shadow supaya tidak dihitung tinggi ekstra) */
  #certificate{
    width:1123px;   /* persis */
    height:794px;   /* persis */
    display:block;
    background:#fff;
    box-shadow:none;
  }
</style>
</head>
<body>

<div class="page">
  <!-- CSS width/height sudah 1123x794; atribut width/height di JS (HiDPI) -->
  <canvas id="certificate"></canvas>
</div>

{{-- Data dinamis dari server --}}
@php
  $certPayload = [
      'name'       => optional($intern)->fullname ?? 'Nama Pemagang',
      'role'       => optional($intern)->internship_interest ?? 'Programmer',
      'start_date' => optional($intern)->start_date ?? '',
      'end_date'   => optional($intern)->end_date ?? '',
      'city'       => optional($intern)->current_city ?? 'Yogyakarta',
      'issued'     => optional($intern)->end_date ?? '',
  ];
@endphp
<script>window.__CERT__ = @json($certPayload);</script>

<script>
(function(){
  const canvas = document.getElementById('certificate');
  const ctx = canvas.getContext('2d');

  // warna dari CSS
  const css = v => getComputedStyle(document.documentElement).getPropertyValue(v).trim();
  const darkGreen = css('--dark') || '#293936';
  const rustyRed  = css('--accent') || '#BE5640';
  const black     = '#000';
  const greenHL   = css('--greenHL') || '#2EBE54';
  const redHL     = css('--redHL') || '#D62118';

  // data
  const cert = (window.__CERT__ || {});
  const NAME  = cert.name || 'Nama Pemagang';
  const ROLE  = cert.role || 'Programmer';
  const CITY  = cert.city || 'Yogyakarta';
  const SDATE = cert.start_date || '';
  const EDATE = cert.end_date || cert.issued || '';

  function parseYmd(s){
    if(!s) return null;
    const m = String(s).match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (m) return new Date(+m[1], +m[2]-1, +m[3]);
    const d = new Date(s); return isNaN(d) ? null : d;
  }
  function formatIndoDate(s){
    const d = parseYmd(s);
    if(!d) return s || '??';
    const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
  }
  function monthDiff(aStr,bStr){
    const a = parseYmd(aStr), b = parseYmd(bStr);
    if(!a || !b) return null;
    let m = (b.getFullYear()-a.getFullYear())*12 + (b.getMonth()-a.getMonth());
    if (b.getDate() < a.getDate()) m -= 1;
    return m < 0 ? 0 : m;
  }
  const MONTHS = monthDiff(SDATE, EDATE);
  const DURTXT = (MONTHS!==null && MONTHS>0) ? `${MONTHS} bulan` : 'beberapa bulan';

  // Hi-DPI: internal buffer besar, CSS size tetap 1123x794 px
  function setupHiDPI(){
    const dpr = Math.max(1, window.devicePixelRatio || 1);
    const W = 1123, H = 794; // ukuran koordinat “base” yang sama dgn CSS
    canvas.width  = Math.round(W * dpr);
    canvas.height = Math.round(H * dpr);
    ctx.setTransform(dpr,0,0,dpr,0,0); // gambar pakai koordinat base
  }

  function draw(){
    const W = 1123, H = 794;
    ctx.clearRect(0,0,W,H);

    // latar hijau gelap
    ctx.fillStyle = darkGreen;
    ctx.fillRect(0,0,W,H);

    // kertas putih
    const marginX = 50, marginY = 40;
    const innerW  = W - marginX*2;
    const innerH  = H - marginY*2;
    ctx.fillStyle = '#fff';
    ctx.fillRect(marginX, marginY, innerW, innerH);

    // border oranye
    const b = 25;
    ctx.strokeStyle = rustyRed; ctx.lineWidth = 4; ctx.beginPath();
    ctx.moveTo(marginX+b, marginY+b);                    ctx.lineTo(marginX+innerW-b, marginY+b);
    ctx.moveTo(marginX+b, marginY+innerH-b);             ctx.lineTo(marginX+innerW-b, marginY+innerH-b);
    ctx.moveTo(marginX+b, marginY+b);                    ctx.lineTo(marginX+b, marginY+innerH-b);
    ctx.moveTo(marginX+innerW-b, marginY+b);             ctx.lineTo(marginX+innerW-b, marginY+innerH-b);
    ctx.stroke();

    // kotak sudut
    ctx.fillStyle = rustyRed;
    const s=14;
    ctx.fillRect(marginX+b-4,                       marginY+b-4,                       s, s);
    ctx.fillRect(marginX+innerW-b-s+4,              marginY+b-4,                       s, s);
    ctx.fillRect(marginX+b-4,                       marginY+innerH-b-s+4,              s, s);
    ctx.fillRect(marginX+innerW-b-s+4,              marginY+innerH-b-s+4,              s, s);

    // pita
    const pW=100, pH=74, pX=marginX+innerW/2-pW/2, pY=marginY-2;
    ctx.fillStyle = rustyRed; ctx.beginPath();
    ctx.moveTo(pX, pY); ctx.lineTo(pX+pW, pY); ctx.lineTo(pX+pW, pY+pH);
    ctx.lineTo(pX+pW/2, pY+pH-26); ctx.lineTo(pX, pY+pH); ctx.closePath(); ctx.fill();

    // logo kiri
    ctx.textAlign='left'; ctx.textBaseline='top'; ctx.fillStyle=black; ctx.font='700 32px Arial, Helvetica, sans-serif';
    const mj1='magangjogja.', mj2='com', logoX=marginX+b+18, logoY=marginY+35;
    ctx.fillText(mj1, logoX, logoY); const w1=ctx.measureText(mj1).width;
    ctx.fillStyle=greenHL; ctx.fillText(mj2, logoX+w1, logoY);

    // judul + subjudul
    ctx.textAlign='center'; ctx.textBaseline='alphabetic'; ctx.fillStyle=darkGreen;
    ctx.font='italic 700 72px "Segoe Script","Brush Script MT","Lucida Handwriting",cursive,serif';
    const titleY=marginY+180; ctx.fillText('Sertifikat', marginX+innerW/2, titleY);

    ctx.font='400 22px "Times New Roman", Times, serif'; ctx.fillStyle=black;
    ctx.fillText('Diberikan kepada:', marginX+innerW/2, titleY+36);

    // nama
    ctx.font='italic 72px "Edwardian Script ITC","Segoe Script","Brush Script MT",cursive,serif';
    const nameY = 390; ctx.fillText(NAME, marginX+innerW/2, nameY);

    // garis bawah
    const lineY = 412; ctx.strokeStyle=black; ctx.beginPath();
    ctx.moveTo(marginX+b+55, lineY); ctx.lineTo(marginX+innerW-b-55, lineY); ctx.stroke();

    // paragraf
    ctx.font='400 20px "Times New Roman", Times, serif'; ctx.textBaseline='top';
    const paraTop=lineY+22, CX=marginX+innerW/2;
    const startTxt = SDATE ? formatIndoDate(SDATE) : '??';
    const endTxt   = EDATE ? formatIndoDate(EDATE) : '??';
    const DURTXT   = (function(){ const m = (function(aStr,bStr){ const a=parseYmd(aStr), b=parseYmd(bStr); if(!a||!b) return null; let m=(b.getFullYear()-a.getFullYear())*12+(b.getMonth()-a.getMonth()); if(b.getDate()<a.getDate()) m-=1; return m<0?0:m; })(SDATE,EDATE); return (m!==null && m>0) ? `${m} bulan` : 'beberapa bulan'; })();

    ctx.fillText(`Telah menyelesaikan magang bidang ${ROLE}`, CX, paraTop);
    ctx.fillText(`di Seven Inc. selama ${DURTXT} yaitu`, CX, paraTop+30);
    ctx.fillText(`mulai dari ${startTxt} sampai dengan ${endTxt}`, CX, paraTop+60);
    ctx.fillText(`${CITY}, ${endTxt}`, CX, paraTop+110);

    // strip kiri/kanan
    ctx.strokeStyle=darkGreen; ctx.lineWidth=5;
    const tripleLen=64, tripleGap=12, stripInset=-35, stripYCenter=marginY+Math.floor(innerH*0.46), startY=stripYCenter-tripleGap;
    const leftX=marginX+b+stripInset;
    for(let i=0;i<3;i++){ const y=startY+i*tripleGap; ctx.beginPath(); ctx.moveTo(leftX,y); ctx.lineTo(leftX+tripleLen,y); ctx.stroke(); }
    const rightXStart=marginX+innerW-b-stripInset-tripleLen;
    for(let i=0;i<3;i++){ const y=startY+i*tripleGap; ctx.beginPath(); ctx.moveTo(rightXStart,y); ctx.lineTo(rightXStart+tripleLen,y); ctx.stroke(); }

    // ttd
    ctx.font='400 18px "Times New Roman", Times, serif';
    const sigBaseY=marginY+innerH-170, sigLineW=165;
    const leftLineX=marginX+b+50, leftCenterX=leftLineX+sigLineW/2;
    ctx.textAlign='center'; ctx.fillStyle=black;
    ctx.fillText('HR Department', leftCenterX, sigBaseY);
    ctx.strokeStyle=black; ctx.lineWidth=2; ctx.beginPath(); ctx.moveTo(leftLineX, sigBaseY+96); ctx.lineTo(leftLineX+sigLineW, sigBaseY+96); ctx.stroke();
    ctx.fillText('Ari Setia Husbana', leftCenterX, sigBaseY+106);

    const rightLineXEnd=marginX+innerW-b-50, rightLineXStart=rightLineXEnd-sigLineW, rightCenterX=(rightLineXStart+rightLineXEnd)/2;
    ctx.fillText('Owner Seven Inc.', rightCenterX, sigBaseY);
    ctx.beginPath(); ctx.moveTo(rightLineXStart, sigBaseY+96); ctx.lineTo(rightLineXEnd, sigBaseY+96); ctx.stroke();
    ctx.fillText('Rekario Danny', rightCenterX, sigBaseY+106);

    // logo kanan atas
    const sevenX=marginX+innerW-b-140, sevenY=marginY+36;
    ctx.fillStyle=redHL; ctx.beginPath(); ctx.moveTo(sevenX, sevenY); ctx.lineTo(sevenX+30, sevenY); ctx.lineTo(sevenX+20, sevenY+20); ctx.lineTo(sevenX-10, sevenY+20); ctx.closePath(); ctx.fill();
    ctx.fillStyle=darkGreen; ctx.beginPath(); ctx.moveTo(sevenX+30, sevenY); ctx.lineTo(sevenX+74, sevenY); ctx.lineTo(sevenX+52, sevenY+40); ctx.lineTo(sevenX+8, sevenY+40); ctx.closePath(); ctx.fill();
    ctx.fillStyle=redHL; ctx.beginPath(); ctx.moveTo(sevenX+42, sevenY+45); ctx.lineTo(sevenX+72, sevenY+45); ctx.lineTo(sevenX+82, sevenY+25); ctx.lineTo(sevenX+52, sevenY+25); ctx.closePath(); ctx.fill();
    ctx.textAlign='left'; ctx.textBaseline='top'; ctx.fillStyle=black; ctx.font='italic 800 22px Arial, Helvetica, sans-serif'; ctx.fillText('SEVEN', sevenX+15, sevenY+49);
    ctx.font='700 14px Arial, Helvetica, sans-serif'; const inc='INC', incX=sevenX+92, incY=sevenY+52; ctx.fillText(inc, incX, incY);
    const incW=ctx.measureText(inc).width; ctx.fillStyle=redHL; ctx.beginPath(); ctx.arc(incX+incW+8, incY+7, 4, 0, Math.PI*2); ctx.fill();
  }

  document.fonts.ready.then(()=>{
    setupHiDPI();
    draw();
    window.__CERT_READY = true; // untuk Browsershot wait
  });
})();
</script>
</body>
</html>
