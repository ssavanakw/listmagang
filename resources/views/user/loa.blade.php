@php
  use Carbon\Carbon;
  $font = "font-family: 'Times New Roman', serif;";
  $interns = $rows ?? [];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Letter of Acceptance (LOA)</title>
  <link href="https://fonts.googleapis.com/css2?family=Georgia:wght@400;700&display=swap" rel="stylesheet">
  <style>
    /* ===== A4 & Margin ===== */
    @page {
      size: A4 portrait;
      margin: 0;
    }

    body {
      font-family: 'Times New Roman', serif; /* Changed font-family to Times New Roman */
      font-size: 12px;
      color: #2d3748;
      line-height: 1.5;
      margin: 0;
      padding: 0;
      background-color: #f9fafb;
      padding-top: 10mm; /* Added top padding to ensure content starts correctly */
      padding-bottom: 10mm; /* Bottom padding to avoid content cutoff */
    }

    .wrap {
      width: 100%;
      max-width: 19cm; /* Ensure proportional in A4 */
      margin: 0 auto;
      background-color: #ffffff;
      padding: 40px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    .head {
      margin-bottom: 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #e2e8f0;
      padding-bottom: 10px;
    }

    .head img {
      width: 50px;
      height: auto;
      border-radius: 8px;
    }

    .head .brand, .head .name {
      display: inline-block;
      vertical-align: top;
      margin-left: 15px;
    }

    .head .name h2 {
      font-size: 26px;
      font-weight: 700;
      color: #000000;
      margin: 0;
      text-transform: uppercase;
    }

    .head .name p {
      font-size: 14px;
      color: #4a5568;
      margin: 5px 0;
    }

    h1.title {
      text-align: center;
      font-size: 24px;
      margin: 30px 0;
      color: #065f46;
      font-weight: 700;
      text-transform: uppercase;
    }

    .content p {
      text-align: justify;
      font-size: 14px;
      margin: 15px 0;
      color: #2d3748;
      line-height: 1.7;
    }

    table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
      border-radius: 8px;
      overflow: hidden;
    }

    table, th, td {
      border: 1px solid #e2e8f0;
    }

    th, td {
      padding: 14px;
      text-align: left;
      font-size: 14px;
    }

    th {
      background-color: #edf2f7;
      font-weight: 600;
    }

    td {
      background-color: #ffffff;
    }

    /* Signature alignment */
    .signature {
      margin-top: 0;
      text-align: left; /* Align the signature to the left */
      margin-left: 0;
    }

    .signature-name {
      text-align: right;
      font-weight: bold;
      font-size: 16px;
      padding-top: -20px;
      color: #2d3748;
    }

    .signature-position {
      text-align: right;
      font-size: 14px;
      color: #4a5568;
    }

    .signature-img {
      margin-top: 0px;
      width: 150px; /* Adjust the size of the signature image */
      height: auto;
    }

    .footer {
      font-size: 10px;
      text-align: center;
      margin-top: 30px;
      color: #aaa;
    }

    .header-text {
      font-size: 16px;
      font-style: italic;
      color: #065f46;
      margin-top: 15px;
    }

    .footer-text {
      font-size: 12px;
      color: #4a5568;
      margin-top: 25px;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="head">
      <div class="brand">
        <img src="{{ $logoData ?? asset('storage/images/logos/logo_seveninc.png') }}" alt="Logo">
      </div>
      <div class="name">
        <h2>Letter of Acceptance (LOA)</h2>
        @if(!empty($loaSettings?->header_text))
          <p class="header-text">{{ $loaSettings->header_text }}</p>
        @endif
        <p>{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
      </div>
    </div>

    <div class="content">
      <p>{{ $openingGreeting ?? 'Dengan ini kami mengonfirmasi bahwa Anda telah diterima untuk mengikuti program magang di perusahaan kami. 
        Berikut adalah detail magang Anda:' }}</p>

      <!-- Table to display dynamic data of students -->
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>NIM/NIS</th>
            <th>Jurusan</th>
            <th>Instansi</th>
            <th>Periode Magang</th>
            <th>Kontak</th>
          </tr>
        </thead>
        <tbody>
          @foreach($interns as $index => $row)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $row['nama_siswa'] ?? 'Nama Tidak Diketahui' }}</td>
              <td>{{ $row['nim_nis'] ?? 'NIM/NIS Tidak Diketahui' }}</td>
              <td>{{ $row['jurusan'] ?? 'Jurusan Tidak Diketahui' }}</td>
              <td>{{ $row['instansi'] ?? 'Instansi Tidak Diketahui' }}</td>
              <td>{{ $row['periode'] ?? 'Periode Tidak Diketahui' }}</td>
              <td>{{ $row['kontak'] ?? 'Kontak Tidak Diketahui' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <p>{{ $closingGreeting ?? 'Harap konfirmasi kehadiran Anda melalui email atau telepon yang tertera di bawah ini.' }}</p>

      <p>Terima kasih atas perhatian Anda.</p>
      <p>Hormat kami,</p>

      <!-- Tanda tangan gambar -->
      <div class="signature">
        <img src="{{ $stampData ?? asset('storage/images/signature/ttd_arisetiahusbana.png') }}" alt="Tanda Tangan" class="signature-img">
      </div>

      <!-- Tanda tangan -->
      <div class="signature">
        <p class="signature-name">{{ $loaSettings->signatory_name ?? 'Ari Setia Husbana' }}</p>
        <p class="signature-position">{{ $loaSettings->signatory_position ?? 'HRD' }}</p>
      </div>

      <!-- Static company data -->
      <p>{{ $loaSettings->company_name ?? 'Seven Inc.' }}</p>
      <p>{{ $loaSettings->company_contact_email ?? 'Kontak Perusahaan: (Email / Telepon)' }}</p>

      @if(!empty($loaSettings?->footer_text))
        <div class="footer-text">{{ $loaSettings->footer_text }}</div>
      @endif
    </div>
  </div>
  <script> 
    window.addEventListener('message', (event) => {
      if (event.data.type === 'updateLOA') {
          updateLOA(event.data.rows);
      }
    }); 
    function updateLOA(rows) {
      const tableBody = document.querySelector('tbody');
      tableBody.innerHTML = '';
      // Clear previous table rows
      rows.forEach((row, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
        <td>${index + 1}</td> 
        <td>${row.nama_siswa}</td> 
        <td>${row.nim_nis}</td> 
        <td>${row.jurusan}</td> 
        <td>${row.instansi}</td> 
        <td>${row.periode}</td> 
        <td>${row.kontak}</td>`;
        tableBody.appendChild(tr);
      });
    }
  </script>
</body>
</html>
