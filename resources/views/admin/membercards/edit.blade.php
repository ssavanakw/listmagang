@extends('layouts.dashboard')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Membercard</h2>

    @if (session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-100 dark:bg-green-200 dark:text-green-900" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.membercards.update', $download->code) }}"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf

        {{-- Nama --}}
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name', $download->name) }}"
                   class="form-input block w-full rounded-lg border border-gray-300 text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                   required>
        </div>

        {{-- Angkatan --}}
        <div>
            <label for="angkatan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Angkatan</label>
            <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan', $download->angkatan) }}"
                   class="form-input block w-full rounded-lg border border-gray-300 text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        {{-- Instansi --}}
        <div>
            <label for="instansi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Instansi</label>
            <input type="text" id="instansi" name="instansi" value="{{ old('instansi', $download->instansi) }}"
                   class="form-input block w-full rounded-lg border border-gray-300 text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        {{-- Brand --}}
        <div>
            <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
            <input type="text" id="brand" name="brand" value="{{ old('brand', $download->brand) }}"
                   class="form-input block w-full rounded-lg border border-gray-300 text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        {{-- Pilih GLB --}}
        <div>
            <label for="model_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Model (.glb)</label>
            <select name="model_url" id="model_url"
                    class="form-select block w-full rounded-lg border border-gray-300 text-sm p-2.5 bg-white focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">-- Pilih File GLB --</option>
                @foreach ($glbFiles as $file)
                    <option value="{{ 'storage/models/' . $file }}"
                        {{ old('model_url', $download->model_url) === 'storage/models/' . $file ? 'selected' : '' }}>
                        {{ $file }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Upload GLB --}}
        <div>
            <label for="model_upload" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload Model (.glb)</label>
            <input type="file" name="model_upload" id="model_upload" accept=".glb"
                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600 focus:outline-none">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">File harus berformat <strong>.glb</strong>.</p>
            @error('model_upload')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan --}}
        <div class="text-end">
            <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
