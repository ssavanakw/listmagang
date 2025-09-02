<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Certificate</title>
<style>
  :root{
    --dark: #293936;
    --accent: #BE5640;
    --black: #000;
    --greenHL: #2EBE54;
    --redHL: #D62118;
  }
  html,body{ height:100%; }
  body{
    margin:0;
    background-color:var(--dark);
    display:grid;
    place-items:center;
    padding:12px;
  }
  canvas{
    background:#fff;
    box-shadow:0 0 15px rgba(0,0,0,.45);
    max-width:95vw;
    height:auto;
  }
</style>
</head>
<body>
<canvas id="certificate" width="1123" height="794"></canvas>

<script>
(function(){
  const canvas = document.getElementById('certificate');
  const ctx = canvas.getContext('2d');

  // Colors
  const darkGreen = getCSS('--dark') || '#293936';
  const rustyRed = getCSS('--accent') || '#BE5640';
  const black = '#000';
  const greenHighlight = getCSS('--greenHL') || '#2EBE54';
  const redHighlight = getCSS('--redHL') || '#D62118';

  // Hi-DPI scale
  function scaleCanvasForDPR() {
    const dpr = Math.max(1, window.devicePixelRatio || 1);
    const baseW = 1123, baseH = 794;  // A4 landscape-ish
    canvas.style.width = baseW + 'px';
    canvas.style.height = baseH + 'px';
    canvas.width = Math.round(baseW * dpr);
    canvas.height = Math.round(baseH * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0); // draw in CSS pixels
  }
  function getCSS(v){ return getComputedStyle(document.documentElement).getPropertyValue(v).trim(); }

  function draw(){
    ctx.clearRect(0,0,canvas.width,canvas.height);

    const W = 1123, H = 794;

    // Frame bg (hijau gelap)
    ctx.fillStyle = darkGreen;
    ctx.fillRect(0,0,W,H);

    // White inner paper
    const marginX = 50;
    const marginY = 40;
    const innerW  = W - marginX*2;
    const innerH  = H - marginY*2;

    ctx.fillStyle = '#fff';
    ctx.fillRect(marginX, marginY, innerW, innerH);

    // Rusty red border (dengan kotak sudut)
    const borderMargin = 25;
    ctx.strokeStyle = rustyRed;
    ctx.lineWidth = 4;

    ctx.beginPath();
    // top
    ctx.moveTo(marginX + borderMargin,            marginY + borderMargin);
    ctx.lineTo(marginX + innerW - borderMargin,   marginY + borderMargin);
    // bottom
    ctx.moveTo(marginX + borderMargin,            marginY + innerH - borderMargin);
    ctx.lineTo(marginX + innerW - borderMargin,   marginY + innerH - borderMargin);
    // left
    ctx.moveTo(marginX + borderMargin,            marginY + borderMargin);
    ctx.lineTo(marginX + borderMargin,            marginY + innerH - borderMargin);
    // right
    ctx.moveTo(marginX + innerW - borderMargin,   marginY + borderMargin);
    ctx.lineTo(marginX + innerW - borderMargin,   marginY + innerH - borderMargin);
    ctx.stroke();

    // Corner squares
    ctx.fillStyle = rustyRed;
    const squareSize = 14;
    const sq = (x,y) => ctx.fillRect(x,y,squareSize,squareSize);
    sq(marginX + borderMargin - 4,                            marginY + borderMargin - 4);
    sq(marginX + innerW - borderMargin - squareSize + 4,      marginY + borderMargin - 4);
    sq(marginX + borderMargin - 4,                            marginY + innerH - borderMargin - squareSize + 4);
    sq(marginX + innerW - borderMargin - squareSize + 4,      marginY + innerH - borderMargin - squareSize + 4);

    // Top ribbon
    const bannerW = 100, bannerH = 74;
    const bannerX = marginX + innerW/2 - bannerW/2;
    const bannerY = marginY - 2; // sedikit masuk ke atas border
    ctx.fillStyle = rustyRed;
    ctx.beginPath();
    ctx.moveTo(bannerX, bannerY);
    ctx.lineTo(bannerX + bannerW, bannerY);
    ctx.lineTo(bannerX + bannerW, bannerY + bannerH);
    ctx.lineTo(bannerX + bannerW/2, bannerY + bannerH - 26);
    ctx.lineTo(bannerX, bannerY + bannerH);
    ctx.closePath();
    ctx.fill();

    // ====== TEXT & ELEMENTS ======

    // Logo kiri-atas
    ctx.textAlign = 'left';
    ctx.textBaseline = 'top';
    ctx.fillStyle = black;
    ctx.font = '700 32px Arial, Helvetica, sans-serif';
    const mj1 = 'magangjogja.'; const mj2 = 'com';
    const logoX = marginX + borderMargin + 18;
    const logoY = marginY + 35;
    ctx.fillText(mj1, logoX, logoY);
    const w1 = ctx.measureText(mj1).width;
    ctx.fillStyle = greenHighlight; ctx.fillText(mj2, logoX + w1, logoY);

    // === Offset khusus untuk judul & subjudul (hanya 2 ini yang turun) ===
    const headerShift = 100; // ubah sesuai kebutuhan (semakin besar = semakin turun)

    // Title "Sertifikat"
    ctx.textAlign = 'center';
    ctx.textBaseline = 'alphabetic';
    ctx.fillStyle = darkGreen;
    ctx.font = 'italic 700 72px "Segoe Script","Brush Script MT","Lucida Handwriting",cursive,serif';
    const titleY = marginY + 80 + headerShift;
    ctx.fillText('Sertifikat', marginX + innerW/2, titleY);

    // Subjudul
    ctx.font = '400 22px "Times New Roman", Times, serif';
    ctx.fillStyle = black;
    const subYHeader = titleY + 36;
    ctx.fillText('Diberikan kepada:', marginX + innerW/2, subYHeader);

    // === Nama & elemen di bawahnya TIDAK ikut headerShift ===
    ctx.font = 'italic 72px "Edwardian Script ITC","Segoe Script","Brush Script MT",cursive,serif';
    const nameY = 390; // posisi dikunci, tidak tergantung subjudul
    ctx.fillText('Moh. Iqbal Fatoni', marginX + innerW/2, nameY);

    // Garis bawah nama
    const lineY = 412;
    ctx.strokeStyle = black;
    ctx.beginPath();
    ctx.moveTo(marginX + borderMargin + 55, lineY);
    ctx.lineTo(marginX + innerW - borderMargin - 55, lineY);
    ctx.stroke();
    // Paragraf + tanggal
    ctx.font = '400 20px "Times New Roman", Times, serif';
    ctx.textBaseline = 'top';
    const paraTop = lineY + 22;
    const CX = marginX + innerW/2;
    ctx.fillText('Telah menyelesaikan magang bidang Programmer', CX, paraTop);
    ctx.fillText('di Seven Inc. selama 5 bulan yaitu', CX, paraTop + 30);
    ctx.fillText('mulai dari 15 Juli 2024 sampai dengan 15 Desember 2024', CX, paraTop + 60);
    ctx.fillText('Yogyakarta, 15 Desember 2024', CX, paraTop + 110);

    // ----- Strip 3 garis kiri/kanan -----
    ctx.strokeStyle = darkGreen;
    ctx.lineWidth = 5;

    const tripleLen  = 64;
    const tripleGap  = 12;
    const stripInset = -35; // jarak dari border oranye ke awal strip
    const stripYCenter = marginY + Math.floor(innerH * 0.46);
    const startY = stripYCenter - tripleGap;

    // Kiri
    const leftX = marginX + borderMargin + stripInset;
    for (let i = 0; i < 3; i++) {
      const y = startY + i * tripleGap;
      ctx.beginPath();
      ctx.moveTo(leftX, y);
      ctx.lineTo(leftX + tripleLen, y);
      ctx.stroke();
    }

    // Kanan
    const rightXStart = marginX + innerW - borderMargin - stripInset - tripleLen;
    for (let i = 0; i < 3; i++) {
      const y = startY + i * tripleGap;
      ctx.beginPath();
      ctx.moveTo(rightXStart, y);
      ctx.lineTo(rightXStart + tripleLen, y);
      ctx.stroke();
    }

    // ===== Signatures =====
    ctx.font = '400 18px "Times New Roman", Times, serif';
    const sigBaseY = marginY + innerH - 170;   // posisi label jabatan
    const sigLineW = 165;                      // panjang garis tanda tangan

    // — Kiri —
    const leftLineX = marginX + borderMargin + 50;
    const leftCenterX = leftLineX + sigLineW / 2;

    ctx.textAlign = 'center';
    ctx.fillStyle = black;
    ctx.fillText('HR Department', leftCenterX, sigBaseY);

    ctx.strokeStyle = black;
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(leftLineX, sigBaseY + 96);
    ctx.lineTo(leftLineX + sigLineW, sigBaseY + 96);
    ctx.stroke();

    ctx.fillText('Ari Setia Husbana', leftCenterX, sigBaseY + 106);

    // — Kanan —
    const rightLineXEnd   = marginX + innerW - borderMargin - 50;
    const rightLineXStart = rightLineXEnd - sigLineW;
    const rightCenterX    = (rightLineXStart + rightLineXEnd) / 2;

    ctx.fillText('Owner Seven Inc.', rightCenterX, sigBaseY);

    ctx.beginPath();
    ctx.moveTo(rightLineXStart, sigBaseY + 96);
    ctx.lineTo(rightLineXEnd,   sigBaseY + 96);
    ctx.stroke();

    ctx.fillText('Rekario Danny', rightCenterX, sigBaseY + 106);

    // Logo kanan-atas (SEVEN Inc)
    const sevenX = marginX + innerW - borderMargin - 140;
    const sevenY = marginY + 36;

    ctx.fillStyle = redHighlight; // merah kecil atas
    ctx.beginPath();
    ctx.moveTo(sevenX, sevenY);
    ctx.lineTo(sevenX + 30, sevenY);
    ctx.lineTo(sevenX + 20, sevenY + 20);
    ctx.lineTo(sevenX - 10, sevenY + 20);
    ctx.closePath(); ctx.fill();

    ctx.fillStyle = darkGreen;    // chevron hijau gelap
    ctx.beginPath();
    ctx.moveTo(sevenX + 30, sevenY);
    ctx.lineTo(sevenX + 74, sevenY);
    ctx.lineTo(sevenX + 52, sevenY + 40);
    ctx.lineTo(sevenX + 8,  sevenY + 40);
    ctx.closePath(); ctx.fill();

    ctx.fillStyle = redHighlight; // bentuk merah bawah
    ctx.beginPath();
    ctx.moveTo(sevenX + 42, sevenY + 45);
    ctx.lineTo(sevenX + 72, sevenY + 45);
    ctx.lineTo(sevenX + 82, sevenY + 25);
    ctx.lineTo(sevenX + 52, sevenY + 25);
    ctx.closePath(); ctx.fill();

    ctx.textAlign = 'left';
    ctx.textBaseline = 'top';
    ctx.fillStyle = black;
    ctx.font = 'italic 800 22px Arial, Helvetica, sans-serif';
    ctx.fillText('SEVEN', sevenX + 15, sevenY + 49);

    ctx.font = '700 14px Arial, Helvetica, sans-serif';
    const incWord = 'INC';
    const incX = sevenX + 92, incY = sevenY + 52;
    ctx.fillText(incWord, incX, incY);
    const incW = ctx.measureText(incWord).width;

    ctx.fillStyle = redHighlight;
    ctx.beginPath();
    ctx.arc(incX + incW + 8, incY + 7, 4, 0, Math.PI*2);
    ctx.fill();
  }

  scaleCanvasForDPR();
  draw();
  window.addEventListener('resize', () => { scaleCanvasForDPR(); draw(); }, {passive:true});
})();
</script>
</body>
</html>
