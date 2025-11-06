<!DOCTYPE html>
<html>
<head>
    <title>Form Penilaian Magang</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; }
        .table th { text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        @if($logo)
            <img src="{{ public_path($logo) }}" alt="Logo" width="100">
        @endif
        <h2>SEVEN INC.</h2>
        <p>Jl. Raya Janti, Gang Arjuna No. 59, Karangjambe, Banguntapan, Bantul, Yogyakarta</p>
        <h3>FORM PENILAIAN MAGANG SEVEN INC.</h3>
    </div>

    <p>Dengan ini pihak SEVEN INC memberikan penilaian selama pelaksanaan magang kepada:</p>

    <p>Nama: {{ $name }}</p>
    <p>NIM: {{ $nim }}</p>
    <p>Program Studi: {{ $program_studi }}</p>
    <p>Kompetensi Keahlian: {{ $kompetensi }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Aspek Penilaian</th>
                <th>Nilai (Angka)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aspects as $index => $score)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ ['Copywriting', 'Branding', 'Riset Konten', 'Kedisiplinan', 'Kreativitas', 'Kerjasama', 'Kehadiran'][$index] }}</td>
                    <td>{{ $score }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">Rata-rata</td>
                <td>{{ number_format(array_sum($aspects) / count($aspects), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p>Keterangan range nilai (angka):</p>
    <ul>
        <li>a. 81 – 100 : Amat baik</li>
        <li>b. 65 – 80  : Baik</li>
        <li>c. 50 – 64  : Cukup</li>
        <li>d. < 50     : Kurang</li>
    </ul>

    <div style="margin-top: 40px;">
        <p>Yogyakarta, {{ $date }}</p>
        <p>Direktur SEVEN INC.</p>
        <p><strong>Rekario Danny Sanjaya, S. Kom</strong></p>
    </div>
</body>
</html>
