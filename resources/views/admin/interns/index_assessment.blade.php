@extends('layouts.dashboard')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

  {{-- HEADER --}}
  <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">📋 Daftar Penilaian Magang</h1>
      <a href="{{ route('interns.assessment.create') }}"
         class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition">
         + Tambah Penilaian
      </a>
  </div>

  {{-- ALERT SUCCESS --}}
  @if(session('success'))
      <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm shadow-sm">
        {{ session('success') }}
      </div>
  @endif

  {{-- TABLE --}}
  <div class="overflow-x-auto bg-white shadow-md rounded-lg">
      <table class="min-w-full text-sm text-left text-gray-700 border border-gray-200">
        <thead class="text-xs uppercase bg-gray-100 border-b border-gray-200">
          <tr>
            <th class="px-4 py-3 text-center">No</th>
            <th class="px-4 py-3">Nama</th>
            <th class="px-4 py-3">NIM</th>
            <th class="px-4 py-3">Program Studi</th>
            <th class="px-4 py-3">Divisi</th>
            <th class="px-4 py-3 text-center">Rata-rata</th>
            <th class="px-4 py-3 text-center">Tanggal</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($data as $i => $row)
          <tr class="border-b hover:bg-gray-50 transition">
            <td class="px-4 py-2 text-center text-gray-600">{{ $i + 1 }}</td>
            <td class="px-4 py-2 font-medium text-gray-800">{{ $row->fullname }}</td>
            <td class="px-4 py-2">{{ $row->nim_or_nis }}</td>
            <td class="px-4 py-2">{{ $row->study_program }}</td>
            <td class="px-4 py-2">{{ $row->div }}</td>
            <td class="px-4 py-2 text-center font-semibold text-gray-700">{{ $row->rata_rata }}</td>
            <td class="px-4 py-2 text-center text-gray-500">{{ $row->created_at->format('d M Y') }}</td>
            <td class="px-4 py-2 text-center">
              <div class="flex justify-center gap-2 flex-wrap">

                {{-- Tombol Edit --}}
                <a href="{{ route('interns.assessment.edit', $row->id) }}"
                   class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-amber-500 hover:bg-amber-600 rounded-md shadow transition">
                   ✏️ Edit
                </a>

                <a href="{{ route('interns.assessment.preview', $row->id) }}"
                  target="_blank"
                  class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow transition">
                  📄 Preview PDF
                </a>


                {{-- Tombol Download PDF --}}
                <a href="{{ route('interns.assessment.pdf', $row->id) }}"
                   class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow transition">
                   📄 PDF
                </a>

                {{-- Tombol Hapus --}}
                <button data-modal-target="modalDelete{{ $row->id }}" data-modal-toggle="modalDelete{{ $row->id }}"
                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-md shadow transition">
                        🗑️ Hapus
                </button>
              </div>

              {{-- Modal Konfirmasi Hapus --}}
              <div id="modalDelete{{ $row->id }}" tabindex="-1" aria-hidden="true"
                   class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full h-full bg-black/50">
                  <div class="relative p-4 w-full max-w-md">
                      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                          <button type="button" class="absolute top-3 right-2.5 text-gray-400 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                              data-modal-hide="modalDelete{{ $row->id }}">
                              ✖
                          </button>
                          <div class="p-6 text-center">
                              <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 13h6m2 10H7a2 2 0 01-2-2V7h14v14a2 2 0 01-2 2zm3-18H6l1-2h10l1 2z" />
                              </svg>
                              <h3 class="mb-5 text-lg font-normal text-gray-700 dark:text-gray-300">
                                Apakah Anda yakin ingin menghapus penilaian <br>
                                <span class="font-semibold text-gray-900">"{{ $row->fullname }}"</span>?
                              </h3>
                              <form action="{{ route('interns.assessment.destroy', $row->id) }}" method="POST" class="inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit"
                                          class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                          Ya, Hapus
                                  </button>
                              </form>
                              <button data-modal-hide="modalDelete{{ $row->id }}" type="button"
                                      class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 rounded-lg text-sm font-medium px-5 py-2.5 focus:z-10">
                                      Batal
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-gray-500 py-4">Belum ada data penilaian magang.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
  </div>
</div>
@endsection
