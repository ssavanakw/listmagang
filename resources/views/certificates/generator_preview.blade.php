<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sertifikat Magang</title>
  @php
    $bgUrl     = url('storage/' . $background_image);
    $logo1Url  = url('storage/' . $logo1);
    $logo2Url  = !empty($logo2) ? url('storage/' . $logo2) : null;
    $ttd1Url   = url('storage/' . $signature_image1);
    $ttd2Url   = !empty($signature_image2) ? url('storage/' . $signature_image2) : null;
    $hasRightSig = !empty($role2) || !empty($name_signatory2) || !empty($signature_image2);
  @endphp
  <style>
    @page { size: 1123px 794px; margin: 0; }
    html, body { height: 100%; }
    * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    :root {
      --page-w: 1123px; --page-h: 794px; --text: #000; --dark: #293936; --accent: #BE5640;
      --serif: "Times New Roman", Times, serif; --script1: "Edwardian Script ITC", "Segoe Script", "Brush Script MT", "Lucida Handwriting", cursive, serif;
      --script2: "Segoe Script", "Brush Script MT", "Lucida Handwriting", cursive, serif;
    }
    body { margin: 0; display: flex; align-items: center; justify-content: center; background: #f0f0f0; }
    .page { position: relative; width: var(--page-w); height: var(--page-h); overflow: hidden; background: url('{{ $bgUrl }}') center/cover no-repeat; }
    .content { position: absolute; inset: 0; padding: 60px 72px; display: grid; grid-template-rows: auto auto 1fr auto; }
    .logos { position: relative; width: 100%; height: 120px; }
    /* Logo 1 move to left when Logo 2 exists */
    .logo-left { position: absolute; top: -20px; left: 2%; height: 100%; display: flex; align-items: center; }
    /* Centered Logo 1 when Logo 2 does not exist */
    @if(!$logo2Url) 
      .logo-left { left: 50%; transform: translateX(-50%); }
    @endif
    .logo-right { position: absolute; top: 10px; right: 20px; height: 100%; display: flex; align-items: center; }
    .logo-left img { max-height: 90px; max-width: 250px; object-fit: contain; }
    .logo-right img { max-height: 120px; max-width: 300px; object-fit: contain; }
    .serial { position: absolute; top: 220px; left: 50%; transform: translateX(-50%); padding: 6px 12px; font: 600 16px var(--serif); color: var(--dark); background: rgba(255,255,255,0.72); border: 1px solid rgba(0,0,0,0.15); border-radius: 8px; letter-spacing: .2px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); white-space: nowrap; }
    .serial b { font-weight: 700; }
    .headings { text-align: center; margin-top: 0; }
    .title { font: italic 700 72px var(--script2); color: var(--dark); line-height: 1; margin: 0 0 8px; }
    .subtitle { font: 400 22px var(--serif); color: var(--text); margin: 0 0 10px; }
    .name-wrap { text-align: center; margin-top: 14px; }
    .name { display: inline-block; font: italic 72px var(--script1); color: var(--text); line-height: 1.1; white-space: nowrap; }
    .name-line { width: 80%; max-width: 780px; height: 2px; background: #000; margin: 1px auto 0; }
    .body { margin-top: 8px; text-align: center; font: 400 20px var(--serif); color: var(--text); display: grid; gap: 10px; justify-items: center; }
    .body > div:last-child { margin-top: 16px; }
    .signatures { position: relative; width: 100%; height: 230px; margin-top: -40px; font: 400 18px var(--serif); color: var(--text); }
    /* Signature left moved down if no right signature */
    .sig { position: absolute; bottom: 20px; width: 260px; text-align: center; }
    .sig-left { left: 50%; transform: translateX(-50%); }
    /* Signature 1 moved back to the left if Signature 2 exists */
    @if($hasRightSig) 
      .sig-left { left: 20%; }
    @endif
    .sig-right { right: 20%; transform: translateX(50%); }
    .sig .line { height: 2px; background: #000; margin: 0 0 6px; }
    .sig .name { font: 600 18px "Times New Roman", Times, serif; letter-spacing: .2px; }
    .sig .role { margin-bottom: 65px; }
    .sig .image { position: absolute; z-index: 3; pointer-events: none; }
    .sig .image img { position: absolute; inset: 0; margin: auto; max-width: 100%; max-height: 100%; object-fit: contain; opacity: 0.95; }
    @media print { body { background: none; } .page { box-shadow: none; }
  </style>
</head>
<body>
  <div class="page">
    <div class="content">

      <!-- LOGOS -->
      <div class="logos">
        <div class="logo-left">
          <img src="{{ $logo1Url }}" alt="Logo 1" />
        </div>
        <div class="logo-right">
          @if($logo2Url)
            <img src="{{ $logo2Url }}" alt="Logo 2" />
          @endif
        </div>
      </div>

      <!-- SERIAL NUMBER -->
      <div>
        <b></b>
      </div>

      <!-- TITLES -->
      <div class="headings">
        <div class="title">Sertifikat</div>
        <p><b>NO: {{ $serial_number ?? '000/SERT/—/—/—/—' }}</b></p>
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
          di {{ $company }} selama <strong>{{ $duration_text }}</strong>.
        </div>
        <div>
          Mulai dari
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
        <div class="sig sig-left">
          <div class="role">{{ $role1 }}</div>
          <div class="image" style="top:-10px; left:-24px; width:300px; height:140px;">
            <img src="{{ $ttd1Url }}" alt="Tanda tangan 1" />
          </div>
          <div class="line" aria-hidden="true"></div>
          <div class="name">{{ $name_signatory1 }}</div>
        </div>

        <!-- Right (optional) -->
        @if($hasRightSig)
          <div class="sig sig-right">
            @if(!empty($role2))
              <div class="role">{{ $role2 }}</div>
            @endif

            @if($ttd2Url)
              <div class="image" style="top:-16px; left:-27px; width:300px; height:160px;">
                <img src="{{ $ttd2Url }}" alt="Tanda tangan 2" />
              </div>
            @endif

            @if(!empty($name_signatory2))
              <div class="line" aria-hidden="true"></div>
              <div class="name">{{ $name_signatory2 }}</div>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>
</body>
</html>
