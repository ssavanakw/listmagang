@extends('layouts.dashboard')

@section('title', 'Riwayat Magang')

@section('content')
@php
  /** @var \App\Models\User $user */
  $user = auth()->user();

  // Sumber data: pakai variabel dari controller jika ada, else fallback ke relasi
  $internships = $internships
      ?? (method_exists($user, 'internshipRegistrations') ? $user->internshipRegistrations()->latest('id')->paginate(10) : collect());

  // Ambil param filter
  $q      = request('q');
  $status = request('status');

  // Helper status badge
  $statusBadge = function($s) {
      $s = strtolower((string)$s);
      return match($s) {
          'waiting'   => ['bg-amber-50 text-amber-700 ring-amber-200', 'Waiting'],
          'accepted'  => ['bg-blue-50 text-blue-700 ring-blue-200', 'Accepted'],
          'active'    => ['bg-indigo-50 text-indigo-700 ring-indigo-200', 'Active'],
          'completed' => ['bg-emerald-50 text-emerald-700 ring-emerald-200', 'Completed'],
          'exited'    => ['bg-rose-50 text-rose-700 ring-rose-200', 'Exited'],
          'rejected'  => ['bg-red-50 text-red-700 ring-red-200', 'Rejected'],
          default     => ['bg-slate-50 text-slate-700 ring-slate-200', ucfirst($s ?: 'Unknown')],
      };
  };

  // Ambil data internshipRegistration milik user yang sedang login
  $internshipRegistration = $user->internshipRegistration;
  $reg = $internshipRegistration;

  // Inisialisasi $canDownload untuk tombol "Download SKL" dan "Generate LOA"
  $canDownload = ($user->role === 'pemagang' && $internshipRegistration && strtolower($internshipRegistration->internship_status) === 'completed');

@endphp

<div class="min-h-[70vh] py-10 bg-emerald-300">
  <div class="max-w-7xl mx-auto px-4">
    {{-- Flash --}}
    @if(session('success'))
      <div class="mb-4 bg-green-200 text-green-800 p-3 rounded-lg shadow-sm">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="mb-4 bg-red-200 text-red-800 p-3 rounded-lg shadow-sm">{{ session('error') }}</div>
    @endif
    {{-- Tampilan untuk menampilkan link ke file PDF setelah SKL berhasil dibuat --}}
    @if(session('skl_url'))
        <div class="mt-6 text-center">
            <a href="{{ session('skl_url') }}" target="_blank"
              class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M5 3a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h1zm14 0a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h1zm-8 4h2v8h-2z"/>
                </svg>
                Lihat File PDF
            </a>
        </div>
    @endif
    {{-- Tampilan untuk menampilkan link ke file PDF setelah LOA berhasil dibuat --}}
    @if(session('loa_url'))
        <div class="mt-6 text-center">
            <a href="{{ session('loa_url') }}" target="_blank"
              class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M5 4a2 2 0 0 1 2-2h6l6 6v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4z"/>
                    <path d="M13 2v4a2 2 0 0 0 2 2h4"/>
                </svg>
                Lihat LOA
            </a>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex items-start justify-between gap-4 flex-wrap">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-zinc-900">Riwayat Magang</h1>
        <p class="text-zinc-600">Lihat seluruh pengajuan dan periode magang kamu.</p>
      </div>

      {{-- Filter & Search --}}
      <form method="GET" class="w-full md:w-auto flex items-end gap-3 bg-white/90 p-3 rounded-xl shadow ring-1 ring-emerald-100">
        <div>
          <label class="block text-xs font-semibold text-zinc-600 mb-1">Status</label>
          <select name="status" class="border-zinc-200 rounded-lg text-sm">
            <option value="">Semua</option>
            @foreach(['waiting','accepted','active','completed','exited','rejected'] as $s)
              <option value="{{ $s }}" @selected($status===$s)>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-zinc-600 mb-1">Cari</label>
          <input type="text" name="q" value="{{ $q }}" placeholder="Nama / Institusi / Divisi"
                 class="border-zinc-200 rounded-lg text-sm px-3 py-2 w-56">
        </div>
        <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
          Terapkan
        </button>
        @if($q || $status)
          <a href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName()) }}"
             class="px-4 py-2 rounded-lg bg-white border text-sm font-semibold hover:bg-zinc-50">
            Reset
          </a>
        @endif
      </form>
    </div>

    {{-- Jika kosong --}}
    @if(($internships instanceof \Illuminate\Support\Collection && $internships->isEmpty())
      || ($internships instanceof \Illuminate\Contracts\Pagination\Paginator && $internships->count()===0))
      <div class="bg-white rounded-2xl shadow p-10 text-center">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-amber-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2Zm1 15h-2v-2h2v2Zm0-4h-2V7h2v6Z"/>
          </svg>
        </div>
        <h3 class="text-xl font-semibold mb-1">Belum ada data</h3>
        <p class="text-zinc-600">Kamu belum memiliki riwayat magang yang tersimpan.</p>
      </div>
    @else
      {{-- Desktop table --}}
      <div class="hidden md:block bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full">
          <thead class="bg-emerald-50 text-emerald-900">
            <tr>
              <th class="text-left text-sm font-semibold p-3">#</th>
              <th class="text-left text-sm font-semibold p-3">Periode</th>
              <th class="text-left text-sm font-semibold p-3">Institusi / Prodi</th>
              <th class="text-left text-sm font-semibold p-3">Divisi</th>
              <th class="text-left text-sm font-semibold p-3">Status</th>
              <th class="text-left text-sm font-semibold p-3">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @foreach($internships as $i => $r)
              @php
                $idx = ($internships instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                        ? ($internships->firstItem() + $i) : ($i + 1);
                $sd = $r?->start_date ? \Carbon\Carbon::parse($r->start_date)->isoFormat('D MMM Y') : '-';
                $ed = $r?->end_date   ? \Carbon\Carbon::parse($r->end_date)->isoFormat('D MMM Y')   : '-';
                [$badgeCls, $label] = $statusBadge($r->internship_status);
                $isCompleted = strtolower((string)$r->internship_status) === 'completed'
                  || (defined(\App\Models\InternshipRegistration::class.'::STATUS_COMPLETED')
                      && $r->internship_status === \App\Models\InternshipRegistration::STATUS_COMPLETED);
                $canDownload = ($user->role === 'pemagang') && $isCompleted;
              @endphp
              <tr class="hover:bg-zinc-50">
                <td class="p-3 text-sm text-zinc-700">{{ $idx }}</td>
                <td class="p-3">
                  <div class="text-sm font-medium text-zinc-900">{{ $sd }} — {{ $ed }}</div>
                  <div class="text-xs text-zinc-500">Dibuat: {{ $r->created_at?->format('d/m/Y') }}</div>
                </td>
                <td class="p-3">
                  <div class="text-sm text-zinc-900">{{ $r->institution_name ?? '-' }}</div>
                  <div class="text-xs text-zinc-500">{{ $r->study_program ?? '-' }}</div>
                </td>
                <td class="p-3 text-sm text-zinc-700">{{ $r->internship_interest ?? '-' }}</td>
                <td class="p-3">
                  <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $badgeCls }}">
                    {{ $label }}
                  </span>
                </td>
                <td class="p-3">
                    {{-- SKL --}}
                    <a href="{{ $canDownload ? route('user.skl.download', ['intern_id' => $r->id]) : 'javascript:void(0)' }}"
                    class="px-3 py-2 rounded-lg text-xs font-semibold
                            {{ $canDownload ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-zinc-200 text-zinc-500 cursor-not-allowed' }}"
                    @if(!$canDownload) aria-disabled="true" @endif>
                    Download SKL
                    </a>

                    {{-- Generate LOA (POST + custom rows per baris) --}}
                    @php $uid = 'loa-'.$r->id; @endphp
                    @if($canDownload)
                    {{-- Form untuk menambah rincian kegiatan --}}

                    <div class="space-y-4">
                        <form method="POST" action="{{ route('user.loa.generate') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="intern_id" value="{{ $intern->id }}">
                            
                            {{-- Tabel Dinamis --}}
                            <div id="loa-rows" class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <input name="loa_deskripsi[]" type="text" class="w-full border rounded p-2 text-xs" placeholder="Deskripsi Kegiatan">
                                    <input name="loa_keterangan[]" type="text" class="w-full border rounded p-2 text-xs" placeholder="Keterangan">
                                </div>
                            </div>
                            
                            {{-- Tambah Baris Button --}}
                            <div class="flex gap-2">
                                <button type="button" id="loa-add-row" class="px-3 py-2 rounded bg-slate-100 hover:bg-slate-200 text-xs">+ Tambah Baris</button>
                                <button type="submit" class="px-6 py-3 text-sm font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                    Generate LOA
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Script untuk menambah baris dinamis --}}
                    <script>
                        (function() {
                            const addRowButton = document.getElementById('loa-add-row');
                            if (!addRowButton) return;
                            
                            addRowButton.addEventListener('click', function () {
                                const rowsContainer = document.getElementById('loa-rows');
                                const newRow = document.createElement('div');
                                newRow.className = 'grid grid-cols-1 sm:grid-cols-2 gap-4';
                                newRow.innerHTML = `
                                    <input name="loa_deskripsi[]" type="text" class="w-full border rounded p-2 text-xs" placeholder="Deskripsi Kegiatan">
                                    <input name="loa_keterangan[]" type="text" class="w-full border rounded p-2 text-xs" placeholder="Keterangan">
                                `;
                                rowsContainer.appendChild(newRow);
                            });
                        })();
                    </script>

                    @else
                    <button type="button"
                            class="px-3 py-2 rounded-lg text-xs font-semibold bg-zinc-200 text-zinc-500 cursor-not-allowed"
                            disabled>
                        Generate LOA
                    </button>
                    @endif

                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Mobile cards --}}
      <div class="md:hidden space-y-4">
        @foreach($internships as $r)
          @php
            $sd = $r?->start_date ? \Carbon\Carbon::parse($r->start_date)->isoFormat('D MMM Y') : '-';
            $ed = $r?->end_date   ? \Carbon\Carbon::parse($r->end_date)->isoFormat('D MMM Y')   : '-';
            [$badgeCls, $label] = $statusBadge($r->internship_status);
            $isCompleted = strtolower((string)$r->internship_status) === 'completed'
              || (defined(\App\Models\InternshipRegistration::class.'::STATUS_COMPLETED')
                  && $r->internship_status === \App\Models\InternshipRegistration::STATUS_COMPLETED);
            $canDownload = ($user->role === 'pemagang') && $isCompleted;
          @endphp
          <div class="bg-white rounded-2xl shadow p-4">
            <div class="flex items-center justify-between gap-3">
              <div>
                <div class="text-sm font-semibold text-zinc-900">{{ $sd }} — {{ $ed }}</div>
                <div class="text-xs text-zinc-500">Instansi: {{ $r->institution_name ?? '-' }}</div>
              </div>
              <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $badgeCls }}">
                {{ $label }}
              </span>
            </div>
            <div class="mt-2 text-sm text-zinc-700">
              <div>Prodi: {{ $r->study_program ?? '-' }}</div>
              <div>Divisi: {{ $r->internship_interest ?? '-' }}</div>
            </div>
            <div class="mt-3 flex flex-wrap gap-2">
                {{-- Tombol SKL --}}
                <a href="{{ $canDownload ? route('user.skl.download', ['intern_id' => $r->id]) : 'javascript:void(0)' }}"
                    class="px-3 py-2 rounded-lg text-xs font-semibold
                            {{ $canDownload ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-zinc-200 text-zinc-500 cursor-not-allowed' }}"
                    @if(!$canDownload) aria-disabled="true" @endif>
                    Download SKL
                </a>

                {{-- Form LOA custom (per-card, ID unik) --}}
                @php $muid = 'mloa-'.$r->id; @endphp
                @if($canDownload)
                    <form method="POST" action="{{ route('user.loa.generate') }}" class="space-y-2 w-full">
                    @csrf
                    <input type="hidden" name="intern_id" value="{{ $r->id }}">

                    <div class="text-xs font-semibold text-zinc-700">Rincian Kegiatan (opsional)</div>
                    <div id="{{ $muid }}-rows" class="space-y-2">
                        <div class="grid grid-cols-1 gap-2">
                        <input name="loa_deskripsi[]" type="text" class="w-full border rounded p-2 text-xs" placeholder="Deskripsi kegiatan">
                        <input name="loa_keterangan[]" type="text" class="w-full border rounded p-2 text-xs" placeholder="Keterangan">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" id="{{ $muid }}-add"
                                class="px-3 py-2 rounded bg-slate-100 hover:bg-slate-200 text-xs">+ Baris</button>
                        <button type="submit"
                                class="px-3 py-2 rounded bg-indigo-600 text-white text-xs hover:bg-indigo-700">Generate LOA</button>
                    </div>
                    </form>

                    <script>
                    (function(){
                        const addBtn = document.getElementById('{{ $muid }}-add');
                        if (!addBtn) return;
                        addBtn.addEventListener('click', function () {
                        const wrap = document.getElementById('{{ $muid }}-rows');
                        const row  = document.createElement('div');
                        row.className = 'grid grid-cols-1 gap-2';
                        row.innerHTML = `
                            <input name="loa_deskripsi[]" type="text" class="w-full border rounded p-2 text-xs" placeholder="Deskripsi kegiatan">
                            <input name="loa_keterangan[]" type="text" class="w-full border rounded p-2 text-xs" placeholder="Keterangan">
                        `;
                        wrap.appendChild(row);
                        });
                    })();
                    </script>
                @else
                    <button type="button"
                            class="px-3 py-2 rounded-lg text-xs font-semibold bg-zinc-200 text-zinc-500 cursor-not-allowed"
                            disabled>
                    Generate LOA
                    </button>
                @endif
                </div>
            </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($internships instanceof \Illuminate\Contracts\Pagination\Paginator)
        <div class="mt-6">
          {{ $internships->withQueryString()->links() }}
        </div>
      @endif
    @endif
  </div>
  {{-- Tampilan khusus untuk pemagang dengan status completed --}}
  @if($user->role === 'pemagang' && $reg->internship_status === 'completed')
      <h2 class="text-3xl font-semibold text-emerald-700 mb-4">🎉 Magang Selesai</h2>
      <p class="text-zinc-700 mb-6">Selamat! Masa magang kamu telah selesai. Kamu bisa mengunduh <strong>SKL</strong> dan membuat <strong>LOA</strong> jika diperlukan.</p>

      {{-- Ringkasan Peserta --}}
      <div class="bg-white rounded-xl border border-zinc-200 shadow-md p-6 mb-8">
        <h3 class="text-xl font-semibold text-zinc-900 mb-4">Ringkasan Peserta</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm text-zinc-700">
          <div>
            <dt class="text-zinc-500">Nama</dt>
            <dd class="font-medium">{{ $reg->fullname ?? $user->name }}</dd>
          </div>
          <div>
            <dt class="text-zinc-500">Email</dt>
            <dd class="font-medium">{{ $reg->email ?? $user->email }}</dd>
          </div>
          <div>
            <dt class="text-zinc-500">Institusi</dt>
            <dd class="font-medium">{{ $reg->institution_name ?? '-' }}</dd>
          </div>
          <div>
            <dt class="text-zinc-500">Program Studi</dt>
            <dd class="font-medium">{{ $reg->study_program ?? '-' }}</dd>
          </div>
          <div>
            @php
              $sd = $reg?->start_date ? \Carbon\Carbon::parse($reg->start_date)->isoFormat('D MMM Y') : null;
              $ed = $reg?->end_date ? \Carbon\Carbon::parse($reg->end_date)->isoFormat('D MMM Y') : null;
            @endphp
            <dt class="text-zinc-500">Periode</dt>
            <dd class="font-medium">{{ $sd && $ed ? $sd.' – '.$ed : '-' }}</dd>
          </div>
          <div>
            <dt class="text-zinc-500">Status</dt>
            <dd class="font-medium">
              <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                COMPLETED
              </span>
            </dd>
          </div>
        </dl>
      </div>

      {{-- Form Tambah Tabel untuk loa --}}
      <div>
        {{-- disini --}}
      </div>

      {{-- Aksi Dokumen --}}
      <div class="grid md:grid-cols-2 gap-4">
          {{-- Download SKL --}}
          <a
              href="{{ $canDownload ? route('user.skl.download', ['intern_id' => $reg->id]) : 'javascript:void(0)' }}"
              class="w-full inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-semibold
                    {{ $canDownload ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-zinc-200 text-zinc-500 cursor-not-allowed' }}"
              @if(!$canDownload) aria-disabled="true" @endif
          >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 3a1 1 0 0 1 1 1v9.586l2.293-2.293a1 1 0 1 1 1.414 1.414l-4.004 4.004a1 1 0 0 1-1.414 0l-4.004-4.004a1 1 0 1 1 1.414-1.414L11 13.586V4a1 1 0 0 1 1-1z"/>
                  <path d="M5 20a1 1 0 0 1 0-2h14a1 1 0 1 1 0 2H5z"/>
              </svg>
              Download SKL
          </a>

          {{-- Generate LOA --}}
          <form method="POST" action="{{ $canDownload ? route('user.loa.generate') : '#' }}" onsubmit="return {{ $canDownload ? 'true' : 'false' }};">
              @csrf
              <input type="hidden" name="intern_id" value="{{ $reg->id }}">
              <button type="{{ $canDownload ? 'submit' : 'button' }}"
                  class="w-full inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-semibold
                        {{ $canDownload ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-zinc-200 text-zinc-500 cursor-not-allowed' }}"
                  @if(!$canDownload) disabled aria-disabled="true" @endif
              >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M5 4a2 2 0 0 1 2-2h6l6 6v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4z"/>
                      <path d="M13 2v4a2 2 0 0 0 2 2h4"/>
                  </svg>
                  Generate LOA
              </button>
          </form>
      </div>

      @unless($canDownload)
          <p class="text-xs text-zinc-500 mt-3">*Akses dibatasi: hanya <strong>pemagang</strong> dengan status <strong>completed</strong>.</p>
      @endunless

      {{-- Umpan Balik --}}
      <div class="bg-green-100 p-6 rounded-lg shadow-sm mt-6">
        <h3 class="text-lg font-semibold text-gray-800">Umpan Balik Magang</h3>
        <p class="text-gray-700 mt-2">Terima kasih atas kontribusimu. Beri kami masukan tentang pengalamanmu:</p>
        <form action="{{ \Illuminate\Support\Facades\Route::has('user.feedback.submit') ? route('user.feedback.submit') : '#' }}" method="POST" class="mt-3">
          @csrf
          <textarea name="feedback" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis umpan balik Anda..."></textarea>
          <button type="submit" class="mt-3 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none"
            @unless(\Illuminate\Support\Facades\Route::has('user.feedback.submit')) disabled @endunless>
            Kirim Umpan Balik
          </button>
        </form>
      </div>
  @endif
</div>
@endsection
