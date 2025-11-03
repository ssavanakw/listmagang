@extends('layouts.dashboard')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg">
    <h2 class="text-lg font-semibold mb-4">Edit Membercard</h2>

    @if (session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.membercards.update', $download->code) }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium">Nama</label>
            <input name="name" value="{{ old('name', $download->name) }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Angkatan</label>
            <input name="angkatan" value="{{ old('angkatan', $download->angkatan) }}" class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Instansi</label>
            <input name="instansi" value="{{ old('instansi', $download->instansi) }}" class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Brand</label>
            <input name="brand" value="{{ old('brand', $download->brand) }}" class="w-full border px-3 py-2 rounded">
        </div>
        
        <div class="mb-4">
            <label for="model_url" class="block text-sm font-medium text-gray-700">Model URL</label>
            <input type="text" name="model_url" id="model_url"
                value="{{ old('model_url', $download->model_url) }}"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
