<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sertifikat Magang</title>
  <style>
    /* ==== Size & print setup (A4 landscape @ ~96DPI => 1123x794) ==== */
    @page { size: 1123px 794px; margin: 0; }
    :root{
      --page-w: 1123px;
      --page-h: 794px;
      --pad: 32px;
      --text: #000;
      --dark: #293936;
      --accent: #BE5640;
      --serif: "Times New Roman", Times, serif;
      --script1: "Edwardian Script ITC","Segoe Script","Brush Script MT","Lucida Handwriting",cursive,serif;
      --script2: "Segoe Script","Brush Script MT","Lucida Handwriting",cursive,serif;
    }
    *{ box-sizing: border-box; }
    html, body{ height:100%; }
    body{
      margin:0;
      display:flex;
      align-items:center;
      justify-content:center;
      background:#f0f0f0;
    }

    /* ==== Page container ==== */
    .page{
      position: relative;
      width: var(--page-w);
      height: var(--page-h);
      overflow: hidden;
      background: url('{{ asset('storage/' . $background_image) }}') center/cover no-repeat;

    }

    .content{
      position: absolute;
      inset: 0;
      padding: 60px 72px;
      display: grid;
      grid-template-rows: auto auto 1fr auto;
    }

    /* ==== Top logos ==== */
    .logos{
      position: relative;
      width: 100%;
      height: 120px;
    }
    .logo-left{
      position: absolute;
      top: -20px;
      left: 25px;
      height: 100%;
      display: flex;
      align-items: center;
    }
    .logo-right{
      position: absolute;
      top: 10px;
      right: 20px;
      height: 100%;
      display: flex;
      align-items: center;
    }
    .logo-left img{
      max-height: 90px;
      max-width: 350px;
      object-fit: contain;
    }
    .logo-right img{
      max-height: 120px;
      max-width: 300px;
      object-fit: contain;
    }

    /* ==== Title & subtitle ==== */
    .headings{ text-align: center; margin-top: 0; }
    .title{
      font: italic 700 72px var(--script2);
      color: var(--dark);
      line-height: 1;
      margin: 0 0 8px;
    }
    .subtitle{
      font: 400 22px var(--serif);
      color: var(--text);
      margin: 0 0 10px;
    }

    /* ==== Recipient name ==== */
    .name-wrap{ text-align: center; margin-top: 14px; }
    .name{
      display: inline-block;
      font: italic 72px var(--script1);
      color: var(--text);
      line-height: 1.1;
      white-space: nowrap;
    }
    .name-line{
      width: 80%;
      max-width: 780px;
      height: 2px;
      background: #000;
      margin: 1px auto 0;
    }

    /* ==== Body text ==== */
    .body{
      margin-top: 8px;
      text-align: center;
      font: 400 20px var(--serif);
      color: var(--text);
      display: grid;
      gap: 10px;
      justify-items: center;
    }
    .body > div:last-child{
      margin-top: 16px;
    }

    /* ==== Signatures ==== */
    .signatures{
      position: relative;
      width: 100%;
      height: 230px;
      margin-top: -40px;
      font: 400 18px var(--serif);
      color: var(--text);
    }
    .sig{
      position: absolute;
      bottom: 20px;
      width: 260px;
      text-align: center;
    }
    .sig-left{ left: 50px; }
    .sig-right{ right: 50px; }

    .sig .role,
    .sig .line,
    .sig .name{
      position: relative;
      z-index: 1;
    }
    .sig .line{
      height: 2px;
      background: #000;
      margin: 0 0 6px;
    }
    .sig .name{
      font: 600 18px "Times New Roman", Times, serif;
      letter-spacing: .2px;
    }
    .sig .role{
      margin-bottom: 65px;
    }

    .sig .image{
      position: absolute;
      z-index: 3;
      pointer-events: none;
    }
    .sig .image img{
      position: absolute;
      inset: 0;
      margin: auto;
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
      opacity: 0.95;
    }

    @media print{
      body{ background: none; }
      .page{ box-shadow: none; }
    }
  </style>
</head>
<body>
  <div class="page">
    <div class="content">

      <!-- LOGOS -->
      <div class="logos">
        <div class="logo-left">
          <img src="{{ asset('storage/' . $logo1) }}" alt="Logo 1" />
        </div>
        <div class="logo-right">
          <img src="{{ asset('storage/' . $logo2) }}" alt="Logo 2" />
        </div>
      </div>

      <!-- TITLES -->
      <div class="headings">
        <div class="title">Sertifikat</div>
        <div class="subtitle">Diberikan kepada:</div>
      </div>

      <!-- RECIPIENT NAME -->
      <div class="name-wrap">
        <span class="name">{{ $name }}</span>
        <div class="name-line" aria-hidden="true"></div>
      </div>

      <!-- BODY TEXT -->
      <div class="body">
        <div>
          Telah menyelesaikan magang bidang <strong>{{ $division }}</strong>
          di {{ $company }} selama <strong>{{ $duration_text }} hari</strong> yaitu
        </div>
        <div>
          mulai dari
          <strong>{{ \Carbon\Carbon::parse($start_date)->locale('id')->translatedFormat('j F Y') }}</strong>
          sampai dengan
          <strong>{{ \Carbon\Carbon::parse($end_date)->locale('id')->translatedFormat('j F Y') }}</strong>
        </div>
        <div>
          <strong>{{ $city }}</strong>,
          <strong>{{ \Carbon\Carbon::parse($end_date)->locale('id')->translatedFormat('j F Y') }}</strong>
        </div>
      </div>

      <!-- SIGNATURES -->
      <div class="signatures">
        <!-- Left -->
        <div class="sig sig-left">
          <div class="role">{{ $role1 }}</div>
          <div class="image" style="top:-10px; left:-24px; width:300px; height:140px;">
            <img src="{{ asset('storage/' . $signature_image1) }}" alt="Tanda tangan 1" />
          </div>
          <div class="line" aria-hidden="true"></div>
          <div class="name">{{ $name_signatory1 }}</div>
        </div>

        <!-- Right -->
        <div class="sig sig-right">
          <div class="role">{{ $role2 }}</div>
          <div class="image" style="top:-16px; left:-27px; width:300px; height:160px;">
            <img src="{{ asset('storage/' . $signature_image2) }}" alt="Tanda tangan 2" />
          </div>
          <div class="line" aria-hidden="true"></div>
          <div class="name">{{ $name_signatory2 }}</div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>