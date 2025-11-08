@extends('layouts.dashboard')
@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
<meta charset="UTF-8">
<title>Form Penilaian Magang Seven Inc</title>
<style>
  @page { size: A4; margin: 12mm; }
  body {
    font-family: "Times New Roman", serif;
    margin: 0; padding: 0;
    font-size: 13.5px;
  }
  .wrap { max-width: 750px; margin: 0 auto; }

  .header {
        display: flex;
        align-items: center;
        justify-content: center; /* seluruh header di-center */
        position: relative; /* agar logo bisa fixed di kiri */
        margin-bottom: 5px;
    }
  .header img {
        width: 80px;
        position: relative;
        left: 0; /* logo mentok kiri */
        top: 50px;
        transform: translateY(-50%);
    }
  .company { text-align: center; width: 100%; }
  .company h1 { margin: 0; font-size: 22px; }
  .company p { margin: 0; font-size: 12px; }

  hr { border: none; border-top: 2px solid #444; margin: 8px 0 12px; }

  .title { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 10px; }
  .info { margin-bottom: 10px; line-height: 1.5; }
  .label { width: 150px; display: inline-block; }

  table { width: 100%; border-collapse: collapse; font-size: 13px; }
  th, td { border: 1px solid #333; padding: 6px; }
  th { background: #f3f3f3; text-align: center; }
  .center { text-align: center; }

  input.table-input {
    width: 100%; border: none; outline: none;
    font-size: 13px; font-family: inherit;
  }

  .admin-tools { margin: 14px 0; display: flex; gap: 8px; }
  button {
    background: #1f2937; color: white; border: none;
    padding: 7px 12px; border-radius: 5px; cursor: pointer;
    font-size: 12px;
  }

  .range { margin-top: 8px; font-size: 12px; }

  .signature { margin-top: 20px; width: 100%; display: flex; flex-direction: column; align-items: flex-end; text-align: right; position: relative;}
  .signature-box {
        display: flex;
        flex-direction: column;
        align-items: center; /* gambar & nama sejajar vertical center */
    }
  .signature img.ttd { width: 120px; z-index: 3; margin-bottom: -20px; margin-top: 10px; position: relative;}
  .signature .name { font-weight: bold; text-decoration: underline; }

  .signature img.logo-bg {
        position: absolute;
        right: 0px;
        bottom: 20px;
        width: 125px;
        opacity: 0.2;
        z-index: 1; /* logo tetap di belakang ttd, tapi tidak invisible */
        pointer-events: none;
    }

  @media print { .admin-tools, .btn-print { display: none; } }
</style>
</head>
<body>

<div class="wrap">

  <div class="admin-tools">
    <button onclick="addRow()">Tambah Aspek</button>
    <button onclick="window.print()" class="btn-print">Print / PDF</button>
  </div>

  <div class="header">
    <img src="{{ asset('storage/images/logos/logo_seveninc.png') }}">
    <div class="company">
      <h1>{{ $company ?? 'SEVEN INC.'}}</h1>
      <p>{{ $companyAddress ?? 'Jl. Raya Janti, Gang Arjuna No. 59, Karangjambe,<br>
      Banguntapan, Bantul, Yogyakarta'}}</p>
    </div>
  </div>

  <hr>

  <div class="title">{{ $title ?? 'FORM PENILAIAN MAGANG SEVEN INC.'}}</div>

  <div class="info">
      {{ $openingSentences ?? 'Dengan ini pihak SEVEN INC memberikan penilaian selama pelaksanaan magang kepada:'}}<br>

      <span class="label">Nama</span>:
      <input id="namaInput" class="table-input" style="width:300px" 
        value="{{ $intern->fullname ?? 'Aulia Sri Handayani Aritonang' }}">
      <br>

      <span class="label">NIM</span>:
      <input id="nimInput" class="table-input" style="width:200px" 
        value="{{ $intern->nim_or_nis ?? '22020144077' }}">
      <br>

      <span class="label">Program Studi</span>:
      <input id="prodiInput" class="table-input" style="width:250px"
        value="{{ $intern->study_program ?? 'Sastra Indonesia' }}">
      <br>

      <span class="label">Kompetensi Keahlian</span>:
      <input id="keahlianInput" class="table-input" style="width:250px"
        value="{{ $intern->div ?? 'Content Writer' }}">
  </div>


  <table id="tableNilai">
    <thead>
      <tr>
        <th style="width:50px">No</th>
        <th>Aspek Penilaian</th>
        <th style="width:90px">Nilai</th>
        <th style="width:50px">Del</th>
      </tr>
    </thead>
    <tbody id="tbody">
      <tr>
        <td class="center"></td>
        <td><input class="table-input" value="Copywriting"></td>
        <td><input class="table-input center" value="95" oninput="updateAvg()"></td>
        <td class="center"><button onclick="deleteRow(this)">X</button></td>
      </tr>
      <tr>
        <td class="center"></td>
        <td><input class="table-input" value="Branding"></td>
        <td><input class="table-input center" value="95" oninput="updateAvg()"></td>
        <td class="center"><button onclick="deleteRow(this)">X</button></td>
      </tr>
      <tr>
        <td class="center"></td>
        <td><input class="table-input" value="Riset Konten"></td>
        <td><input class="table-input center" value="95" oninput="updateAvg()"></td>
        <td class="center"><button onclick="deleteRow(this)">X</button></td>
      </tr>
      <tr>
        <td class="center"></td>
        <td><input class="table-input" value="Kedisiplinan"></td>
        <td><input class="table-input center" value="95" oninput="updateAvg()"></td>
        <td class="center"><button onclick="deleteRow(this)">X</button></td>
      </tr>
      <tr>
        <td class="center"></td>
        <td><input class="table-input" value="Kreativitas"></td>
        <td><input class="table-input center" value="95" oninput="updateAvg()"></td>
        <td class="center"><button onclick="deleteRow(this)">X</button></td>
      </tr>
      <tr>
        <td class="center"></td>
        <td><input class="table-input" value="Kerjasama"></td>
        <td><input class="table-input center" value="95" oninput="updateAvg()"></td>
        <td class="center"><button onclick="deleteRow(this)">X</button></td>
      </tr>
      <tr>
        <td class="center"></td>
        <td><input class="table-input" value="Kehadiran"></td>
        <td><input class="table-input center" value="95" oninput="updateAvg()"></td>
        <td class="center"><button onclick="deleteRow(this)">X</button></td>
      </tr>
    </tbody>

    <tfoot>
      <tr>
        <td colspan="2" class="center"><b>Rata-rata</b></td>
        <td class="center"><b id="avg">{{ $rumusRatarata ?? '95'}}</b></td>
        <td></td>
      </tr>
    </tfoot>
  </table>

  <div class="range">
    <b>Keterangan rentang nilai:</b><br>
    a. 81–100 : Amat baik<br>
    b. 65–80 : Baik<br>
    c. 50–64 : Cukup<br>
    d. &lt; 50 : Kurang
  </div>

  <div class="signature">
    {{ $locDate ?? 'Yogyakarta, 14 Mei 2025'}}<br>
    {{ $ttdposition ?? 'Direktur SEVEN INC'}}<br>
    
    <div class="signature-box">
        <img class="ttd" src="{{$logos ?? asset('storage/images/signature/ttd_rekariodanny.png') }}"><br>
        <span class="name">{{ $ttdname ?? 'Rekario Danny Sanjaya, S. Kom'}}</span>
    </div>

    <img class="logo-bg" src="{{ $logos ?? asset('storage/images/logos/logo_seveninc.png') }}">

  </div>

</div>

<script>
  function updateNumbers() {
    const rows = document.querySelectorAll("#tbody tr");
    rows.forEach((row, i) => {
      row.children[0].textContent = i + 1;
    });
  }

  function updateAvg() {
    const inputs = document.querySelectorAll("#tbody td:nth-child(3) input");
    let total = 0, count = 0;

    inputs.forEach(inp => {
      const val = parseFloat(inp.value);
      if (!isNaN(val)) {
        total += val;
        count++;
      }
    });

    const avg = count ? (total / count).toFixed(2) : "-";
    document.getElementById("avg").textContent = avg;
  }

  function addRow() {
    const tbody = document.getElementById("tbody");
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td class="center"></td>
      <td><input class="table-input" value="Aspek Baru"></td>
      <td><input class="table-input center" value="0" oninput="updateAvg()"></td>
      <td class="center"><button onclick="deleteRow(this)">X</button></td>
    `;
    tbody.appendChild(tr);
    updateNumbers();
    updateAvg();
  }

  function deleteRow(btn) {
    btn.parentElement.parentElement.remove();
    updateNumbers();
    updateAvg();
  }

  updateNumbers();
  updateAvg();
</script>

</body>
</html>
@endsection