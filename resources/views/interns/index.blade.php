@extends('layouts.dashboard')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="px-4 pt-6">

    {{-- Header + Tabs + Search --}}
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title ?? 'Semua Pemagang' }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Daftar pemagang berdasarkan filter.</p>

            <div class="mt-4 flex gap-2 flex-wrap">
                <a href="{{ route('admin.interns.index') }}"
                   class="px-3 py-2 rounded-lg text-sm {{ ($scope ?? '')==='all' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                    Semua
                </a>
                <a href="{{ route('admin.interns.active') }}"
                   class="px-3 py-2 rounded-lg text-sm {{ ($scope ?? '')==='active' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                    Aktif
                </a>
                <a href="{{ route('admin.interns.completed') }}"
                   class="px-3 py-2 rounded-lg text-sm {{ ($scope ?? '')==='completed' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                    Selesai
                </a>
                <a href="{{ route('admin.interns.exited') }}"
                   class="px-3 py-2 rounded-lg text-sm {{ ($scope ?? '')==='exited' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                    Keluar
                </a>
                <a href="{{ route('admin.interns.pending') }}"
                   class="px-3 py-2 rounded-lg text-sm {{ ($scope ?? '')==='pending' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-100' }}">
                    Pending
                </a>
            </div>
        </div>

        {{-- Optional: pencarian sederhana --}}
        <form method="GET" action="{{ url()->current() }}" class="mt-1">
            <div class="flex items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari nama atau email…"
                       class="px-3 py-2 rounded-lg border
                              border-gray-300 dark:border-gray-700
                              bg-white dark:bg-gray-800
                              text-sm text-gray-900 dark:text-gray-100
                              placeholder-gray-500 dark:placeholder-gray-400
                              caret-emerald-500
                              focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <button class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- Card --}}
    <div class="rounded-xl bg-white dark:bg-gray-800 shadow ring-1 ring-gray-200 dark:ring-gray-700">

        <div class="px-6 pt-6 pb-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">List Pemagang</h2>
        </div>

        {{-- Table wrapper with sticky header + horizontal scroll --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="sticky top-0 z-10 text-xs uppercase tracking-wider
                              bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr class="divide-x divide-gray-200 dark:divide-gray-600">
                        <th class="px-3 py-3 font-semibold whitespace-nowrap">No</th>
                        @php
                            // Kolom lengkap seperti Data Pendaftar + created_at (DIBUAT)
                            $fields = [
                                'fullname' => 'NAMA LENGKAP',
                                'born_date' => 'TANGGAL LAHIR',
                                'student_id' => 'NIM / NIS',
                                'email' => 'EMAIL',
                                'internship_status' => 'STATUS', // workflow: new/active/completed/exited/pending
                                'gender' => 'GENDER',
                                'phone_number' => 'TELEPON',
                                'institution_name' => 'INSTITUSI',
                                'study_program' => 'PRODI',
                                'faculty' => 'FAKULTAS',
                                'current_city' => 'KOTA',
                                'internship_reason' => 'ALASAN MAGANG',
                                'internship_type' => 'JENIS MAGANG',
                                'internship_arrangement' => 'TIPE MAGANG',
                                'current_status' => 'STATUS SAAT INI', // Student/Fresh Graduate
                                'english_book_ability' => 'BACA B.INGGRIS',
                                'supervisor_contact' => 'KONTAK PEMBIMBING',
                                'internship_interest' => 'BIDANG MINAT',
                                'internship_interest_other' => 'MINAT LAIN',
                                'design_software' => 'SOFTWARE DESAIN',
                                'video_software' => 'SOFTWARE VIDEO',
                                'programming_languages' => 'BAHASA PEMROGRAMAN',
                                'digital_marketing_type' => 'DIGITAL MARKETING',
                                'digital_marketing_type_other' => 'MARKETING LAIN',
                                'laptop_equipment' => 'PUNYA LAPTOP',
                                'owned_tools' => 'ALAT DIMILIKI',
                                'owned_tools_other' => 'ALAT LAIN',
                                'start_date' => 'MULAI',
                                'end_date' => 'SELESAI',
                                'internship_info_sources' => 'SUMBER INFO',
                                'internship_info_other' => 'INFO LAIN',
                                'current_activities' => 'AKTIVITAS SAAT INI',
                                'boarding_info' => 'INFO KOST',
                                'family_status' => 'IZIN KELUARGA',
                                'parent_wa_contact' => 'KONTAK ORANG TUA',
                                'social_media_instagram' => 'INSTAGRAM',
                                'cv_ktp_portofolio_pdf' => 'FILE PDF',
                                'portofolio_visual' => 'FILE VISUAL',
                                'created_at' => 'DIBUAT',
                            ];
                        @endphp
                        @foreach ($fields as $label)
                            <th class="px-3 py-3 font-semibold whitespace-nowrap">{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($interns as $i => $r)
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100
                               dark:odd:bg-gray-800 dark:even:bg-gray-800/60 dark:hover:bg-gray-700/60
                               transition-colors">
                        {{-- No --}}
                        <td class="px-3 py-2 text-gray-600 dark:text-gray-300">
                            {{ ($interns->firstItem() ?? 1) + $i }}
                        </td>

                        {{-- Dynamic cells --}}
                        @foreach ($fields as $field => $label)
                            <td class="px-3 py-2 align-top">
                                @php $val = $r->$field ?? '-'; @endphp

                                {{-- File links --}}
                                @if (Str::startsWith($field, 'cv_') || Str::startsWith($field, 'portofolio_'))
                                    @if ($r->$field)
                                        <a href="{{ asset('storage/' . $r->$field) }}" target="_blank"
                                           class="text-emerald-600 hover:text-emerald-700 underline">
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif

                                {{-- Tanggal (born/start/end/created_at) --}}
                                @elseif (in_array($field, ['born_date','start_date','end_date','created_at'], true))
                                    @php $d = $r->$field; $isCarbon = $d instanceof \Carbon\Carbon; @endphp
                                    <span class="whitespace-nowrap">
                                        {{ $isCarbon ? $d->format('d M Y') : ($d ?: '-') }}
                                    </span>

                                {{-- STATUS WORKFLOW badge --}}
                                @elseif ($field === 'internship_status')
                                    @php
                                        $map = [
                                            'new'       => ['Pendaftar Baru', 'bg-teal-100 text-teal-800 dark:bg-teal-600/20 dark:text-teal-300'],
                                            'active'    => ['Aktif',          'bg-blue-100 text-blue-800 dark:bg-blue-600/20 dark:text-blue-300'],
                                            'completed' => ['Selesai',        'bg-indigo-100 text-indigo-800 dark:bg-indigo-600/20 dark:text-indigo-300'],
                                            'exited'    => ['Keluar',         'bg-rose-100 text-rose-800 dark:bg-rose-600/20 dark:text-rose-300'],
                                            'pending'   => ['Pending',        'bg-amber-100 text-amber-800 dark:bg-amber-600/20 dark:text-amber-300'],
                                        ];
                                        [$labelStatus, $cls] = $map[$val] ?? ['-', 'bg-gray-100 text-gray-800 dark:bg-gray-600/20 dark:text-gray-200'];
                                    @endphp
                                    <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium {{ $cls }}">
                                        {{ $labelStatus }}
                                    </span>

                                {{-- STATUS SAAT INI (Student/Fresh Graduate) kecil --}}
                                @elseif ($field === 'current_status')
                                    @php
                                        $csMap = [
                                            'Student'        => 'bg-sky-100 text-sky-800 dark:bg-sky-600/20 dark:text-sky-300',
                                            'Fresh Graduate' => 'bg-slate-100 text-slate-800 dark:bg-slate-600/20 dark:text-slate-300',
                                        ];
                                        $csCls = $csMap[$val] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-600/20 dark:text-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-medium {{ $csCls }}">
                                        {{ $val }}
                                    </span>

                                {{-- Kolom tidak wrap agar rapih --}}
                                @elseif (in_array($field, ['email','student_id','phone_number','gender','social_media_instagram'], true))
                                    <span class="whitespace-nowrap">{{ $val }}</span>

                                {{-- Default: truncate + tooltip --}}
                                @else
                                    <span class="block max-w-[18rem] truncate" title="{{ is_string($val) ? $val : '' }}">
                                        {{ $val }}
                                    </span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($fields) + 1 }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Belum ada data.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $interns->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection
