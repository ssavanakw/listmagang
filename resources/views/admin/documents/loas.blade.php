@extends('layouts.dashboard')

@section('title', 'Daftar LOA yang Diunduh')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold text-emerald-800 mb-4">Daftar LOA yang Diunduh</h1>

    <!-- Tampilkan pesan jika ada -->
    @if(session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Daftar LOA -->
    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
        <thead>
            <tr class="bg-emerald-100">
                <th class="px-4 py-2 text-left text-sm font-semibold text-emerald-600">No.</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-emerald-600">Nama Pengguna</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-emerald-600">Tanggal Unduhan</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-emerald-600">URL LOA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loas as $key => $loa)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $loas->firstItem() + $key }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $loa->user->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $loa->downloaded_at->format('d M Y, H:i') }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        <a href="{{ $loa->file_url }}" target="_blank" class="text-emerald-600 hover:underline">Lihat LOA</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $loas->links() }}
    </div>
</div>
@endsection
