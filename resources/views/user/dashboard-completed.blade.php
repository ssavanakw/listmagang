@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen bg-emerald-300 py-10">
    <div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-lg">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 bg-green-200 text-green-800 p-3 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="mb-4 bg-red-200 text-red-800 p-3 rounded-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Dashboard Header --}}
        <div class="flex flex-col items-center mb-8">
            <h1 class="text-4xl font-semibold text-gray-800">Riwayat Magang</h1>
            <p class="text-lg text-gray-600">Status Magang Anda</p>
        </div>

        {{-- 1. Lakukan pengecekan utama: Apakah variabel $internship ada isinya? --}}
        @if($internship)

            {{-- 2. Karena $internship sudah pasti ada, sekarang aman untuk mengecek statusnya --}}
            @if($internship->internship_status === 'completed')
                {{-- Tampilan jika magang sudah selesai --}}
                <div class="space-y-6 mb-10">
                    <div class="bg-blue-100 p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Magang</h2>
                        <div class="mt-4">
                            <p class="text-gray-700"><strong>Perusahaan:</strong> {{ $internship->company_name }}</p>
                            <p class="text-gray-700"><strong>Durasi:</strong> {{ $internship->duration }} bulan</p>
                            <p class="text-gray-700"><strong>Status Magang:</strong> <span class="font-semibold text-green-600">Selesai</span></p>
                            <p class="text-gray-700"><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d-m-Y') }}</p>
                            <p class="text-gray-700"><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($internship->end_date)->format('d-m-Y') }}</p>
                        </div>
                    </div>

                    {{-- Feedback Section --}}
                    <div class="bg-green-100 p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold text-gray-800">Umpan Balik Magang</h2>
                        <div class="mt-4">
                            <p class="text-gray-700">Terima kasih telah menyelesaikan magang di <strong>{{ $internship->company_name }}</strong>.</p>
                            <p class="text-gray-700">Kami sangat menghargai kontribusi Anda. Harap berikan umpan balik mengenai pengalaman Anda:</p>
                            <form action="{{ route('user.feedback.submit') }}" method="POST" class="mt-4">
                                @csrf
                                <textarea name="feedback" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis umpan balik Anda..."></textarea>
                                <button type="submit" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">Kirim Umpan Balik</button>
                            </form>
                        </div>
                    </div>

                    {{-- Certificate Section --}}
                    <div class="bg-yellow-100 p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold text-gray-800">Sertifikat Magang</h2>
                        <div class="mt-4">
                            <p class="text-gray-700">Anda telah memenuhi semua syarat untuk mendapatkan sertifikat. Klik tombol di bawah untuk mengunduh.</p>
                            <a href="{{ route('user.certificate.download', $internship->id) }}" class="inline-block mt-4 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Unduh Sertifikat</a>
                        </div>
                    </div>
                </div>
            @else
                {{-- Tampilan jika magang belum selesai --}}
                <div class="space-y-6 mb-10">
                    <div class="bg-gray-100 p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold text-gray-800">Magang Anda Belum Selesai</h2>
                        <div class="mt-4">
                            <p class="text-gray-700">Magang Anda masih dalam proses atau belum selesai. Berikut informasinya:</p>
                            <p class="text-gray-700"><strong>Status:</strong> <span class="font-semibold text-yellow-600 capitalize">{{ $internship->internship_status }}</span></p>
                            <p class="text-gray-700"><strong>Perusahaan:</strong> {{ $internship->company_name }}</p>
                            <p class="text-gray-700"><strong>Durasi:</strong> {{ $internship->duration }} bulan</p>
                            <p class="text-gray-700"><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d-m-Y') }}</p>
                            <p class="text-gray-700"><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($internship->end_date)->format('d-m-Y') }}</p>
                        </div>
                    </div>

                    {{-- Instructions or Next Steps --}}
                    <div class="bg-blue-100 p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold text-gray-800">Langkah Berikutnya</h2>
                        <div class="mt-4">
                            <p class="text-gray-700">Anda dapat melanjutkan magang ini dan melengkapi tugas Anda. Berikut langkah-langkah yang dapat Anda ambil:</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                <li class="text-gray-700">Pastikan semua tugas magang diselesaikan sesuai jadwal.</li>
                                <li class="text-gray-700">Ikuti pertemuan evaluasi dengan supervisor untuk mendapatkan masukan.</li>
                                <li class="text-gray-700">Kirimkan laporan akhir magang setelah selesai.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

        @else
            {{-- 3. Blok ini hanya akan jalan jika $internship dari awal memang null --}}
            <div class="bg-red-100 text-red-800 p-6 rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold">Data Magang Tidak Ditemukan</h2>
                <div class="mt-4">
                    <p>Anda belum terdaftar dalam program magang atau data magang tidak ditemukan. Pastikan Anda sudah melakukan pendaftaran.</p>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection