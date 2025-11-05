@extends('layouts.dashboard')

@section('title', 'Daftar SKL yang Diunduh')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold text-primary-700 mb-4">Daftar SKL yang Diunduh</h1>

    <!-- Tampilkan pesan jika ada -->
    @if(session('success'))
        <div class="mb-4 text-primary-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Daftar SKL -->
    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
        <thead>
            <tr class="bg-primary-100">
                <th class="px-4 py-2 text-left text-sm font-semibold text-primary-700">No.</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-primary-700">Nama Pengguna</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-primary-700">Tanggal Unduhan</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-primary-700">URL SKL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($skls as $key => $skl)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $skls->firstItem() + $key }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $skl->user->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $skl->downloaded_at->format('d M Y, H:i') }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        <a href="{{ $skl->file_url }}" target="_blank" class="text-primary-700 hover:underline">
                            Lihat SKL
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $skls->links() }}
    </div>
</div>
@endsection
