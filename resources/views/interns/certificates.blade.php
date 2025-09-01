<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Sertifikat</title>
  <style>
    /* Ukuran halaman A4 landscape untuk cetak/PDF */
    @page { size: A4 landscape; margin: 0; }

    body{
      margin:0;
      font-family:"Times New Roman", serif;
      background-color:#2a363b;            /* bingkai luar */
      -webkit-print-color-adjust:exact;     /* pastikan warna ikut tercetak */
      print-color-adjust:exact;
    }

    .certificate-container{
      box-sizing:border-box;
      width:297mm;                          /* A4 landscape */
      height:210mm;
      background:#fff;
      margin:auto;
      padding:1.5cm;
      position:relative;
      border:12px solid #2a363b;            /* border luar tebal */
    }

    /* Border dalam */
    .border-inner{
      position:absolute; inset:2cm;
      border:5px solid #bf5531;
      box-sizing:border-box;
      pointer-events:none;
    }
    /* Corner squares */
    .border-inner::before,
    .border-inner::after,
    .border-inner > div::before,
    .border-inner > div::after{
      content:""; position:absolute; width:18px; height:18px; background:#bf5531;
    }
    .border-inner::before{ top:0; left:0; }     /* TL */
    .border-inner::after{  top:0; right:0; }    /* TR */
    .border-inner > div::before{ bottom:0; left:0; }  /* BL */
    .border-inner > div::after{  bottom:0; right:0; } /* BR */

    /* Pita di atas */
    .ribbon{
      position:absolute; top:1.5cm; left:50%; transform:translateX(-50%);
      width:100px; height:65px; background:#bf5531;
      clip-path:polygon(0 0,100% 0,100% 70%,50% 100%,0 70%);
    }

    /* Header kiri-kanan */
    .header-left{
      position:absolute; top:2.5cm; left:3cm;
      font-family:Arial, sans-serif; font-weight:bold; font-size:30px; color:#000;
    }
    .header-left .green{ color:#11a833; }

    .header-right{
      position:absolute; top:3cm; right:3cm; width:100px;
    }
    .header-right img{ width:100%; height:auto; display:block; }

    /* Judul */
    .title{
      margin-top:5cm; text-align:center;
      font-family:"Brush Script MT", cursive, serif;
      font-size:57px; color:#2a363b;
    }
    .subtitle{
      text-align:center; margin-top:1.3rem;
      font-size:18px; color:#000;
    }

    /* Nama penerima */
    .name{
      text-align:center;
      font-family:"Edwardian Script ITC", cursive, serif;
      font-size:46px; margin-top:.3rem; color:#000;
      border-bottom:2px solid #000; width:47%; margin-left:auto; margin-right:auto; padding-bottom:5px;
    }

    /* Isi keterangan */
    .deskripsi{
      text-align:center; font-family:Georgia, serif; font-size:16px;
      max-width:70%; margin:20px auto 40px; line-height:1.4; color:#000;
    }

    /* Tanggal */
    .tanggal{ margin-top:15px; font-family:Georgia, serif; font-size:15px; text-align:center; color:#000; }

    /* Garis dekor kiri/kanan tengah */
    .side-bars{
      position:absolute; top:50%; transform:translateY(-50%);
      width:50px; height:8px; background:#2a363b;
    }
    .side-bars.left{ left:3cm; }
    .side-bars.right{ right:3cm; }

    /* Tanda tangan */
    .signatures{
      position:absolute; bottom:2.8cm; left:3cm; right:3cm;
      display:flex; justify-content:space-between;
      font-size:14px;
    }
    .signature-block{ display:flex; flex-direction:column; align-items:center; width:180px; }
    .signature-image{ height:70px; margin-bottom:5px; }
  </style>
</head>
<body>
  <div class="certificate-container">
    <div class="border-inner"><div></div></div>

    <div class="ribbon"></div>

    <div class="header-left">magangjogja<span class="green">.com</span></div>

    <div class="header-right">
      <!-- ganti path gambar sesuai lokasi file Anda -->
      <img src="logos/seven-inc.png" alt="Logo Seven Inc">
    </div>

    <div class="title">Sertifikat</div>
    <div class="subtitle">Di Berikan Kepada :</div>

    <div class="name">Moh. Iqbal Fatoni</div>

    <div class="deskripsi">
      Telah menyelesaikan magang bidang Programmer <br>
      di Seven Inc. selama 5 bulan yaitu <br>
      mulai dari 15 Juli 2024 sampai dengan 15 Desember 2024
    </div>

    <div class="tanggal">Yogyakarta, 15 Desember 2024</div>

    <div class="side-bars left"></div>
    <div class="side-bars right"></div>

    <div class="signatures">
      <div class="signature-block">
        <div>HR Departement</div>
        <!-- ganti path gambar tanda tangan -->
        <img class="signature-image" src="signatures/ari-setia-husbana.png" alt="Tanda Tangan Ari Setia Husbana">
        <div>Ari Setia Husbana</div>
      </div>
      <div class="signature-block">
        <div>Owner Seven Inc.</div>
        <img class="signature-image" src="signatures/rekario-danny.png" alt="Tanda Tangan Rekario Danny">
        <div>Rekario Danny</div>
      </div>
    </div>
  </div>
</body>
</html>
