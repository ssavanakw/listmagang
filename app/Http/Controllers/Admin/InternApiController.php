<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipRegistration as IR;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InternApiController extends Controller
{
    /* =======================
     * Label maps (Bahasa ID)
     * ======================= */
    private array $mapLaptop = [
        'yes-laptop' => 'Ya, punya laptop',
        'no-laptop'  => 'Tidak punya laptop',
        'yes'        => 'Ya, punya laptop',
        'no'         => 'Tidak punya laptop',
        'y'          => 'Ya, punya laptop',
        'n'          => 'Tidak punya laptop',
        'true'       => 'Ya, punya laptop',
        'false'      => 'Tidak punya laptop',
        '1'          => 'Ya, punya laptop',
        '0'          => 'Tidak punya laptop',
        'ya'         => 'Ya, punya laptop',
        'tidak'      => 'Tidak punya laptop',
    ];

    private array $mapTools = [
        'tool-corel-photoshop' => 'Corel / Photoshop',
        'tool-adobe-video'     => 'Adobe Premiere / After Effects',
        'tool-camera'          => 'Kamera',
        'tool-drone'           => 'Drone',
        'tool-pen-tablet'      => 'Pen Tablet',
        'tool-tripod'          => 'Tripod',
    ];

    private array $mapGender = [
        'male' => 'Laki-laki', 'pria' => 'Laki-laki', 'laki-laki' => 'Laki-laki', 'm' => 'Laki-laki', 'lk' => 'Laki-laki',
        'female' => 'Perempuan', 'wanita' => 'Perempuan', 'perempuan' => 'Perempuan', 'f' => 'Perempuan', 'pr' => 'Perempuan',
    ];

    private array $mapCurrentStatus = [
        'Fresh Graduate' => 'Lulusan Baru',
        'Student'        => 'Mahasiswa/Pelajar',
        'Employee'       => 'Karyawan',
        'Unemployed'     => 'Tidak Bekerja',
    ];

    private array $mapArrangement = [ // TIPE MAGANG (cara kerja)
        'onsite' => 'Onsite',
        'hybrid' => 'Hibrida',
        'remote' => 'Remote',
    ];

    private array $mapType = [ // JENIS MAGANG (skema)
        'campus'          => 'Magang Kampus',
        'mandiri'         => 'Magang Mandiri',
        'pkl'             => 'PKL',
        'kampus-merdeka'  => 'Kampus Merdeka',
        'mbkm'            => 'Kampus Merdeka',
    ];

    private array $mapInterest = [
    // Slug + alias Indonesia  => English label
    'project-manager'                  => 'Project Manager',
    'manajer proyek'                  => 'Project Manager',

    'administration'                  => 'Administration',
    'administrasi'                    => 'Administration',

    'hr'                               => 'Human Resources (HR)',
    'sumber daya manusia (hr)'         => 'Human Resources (HR)',

    'uiux'                             => 'UI/UX',
    'ui/ux'                            => 'UI/UX',

    'programmer'                       => 'Programmer (Front End / Backend)',
    'programmer (front end / backend)' => 'Programmer (Front End / Backend)',

    'photographer'                     => 'Photographer',
    'fotografer'                       => 'Photographer',

    'videographer'                     => 'Videographer',
    'videografer'                      => 'Videographer',

    'graphic-designer'                 => 'Graphic Designer',
    'desainer grafis'                  => 'Graphic Designer',

    'social-media-specialist'          => 'Social Media Specialist',
    'spesialis media sosial'           => 'Social Media Specialist',

    'content-writer'                   => 'Content Writer',
    'penulis konten'                   => 'Content Writer',

    'content-planner'                  => 'Content Planner',
    'perencana konten'                 => 'Content Planner',

    'marketing-and-sales'              => 'Sales & Marketing',
    'penjualan & pemasaran'            => 'Sales & Marketing',
    'penjualan dan pemasaran'          => 'Sales & Marketing',

    'public-relation'                  => 'Public Relations (Marcomm)',
    'hubungan masyarakat (marcomm)'    => 'Public Relations (Marcomm)',

    'digital-marketing'                => 'Digital Marketing',
    'pemasaran digital'                => 'Digital Marketing',

    'tiktok-creator'                   => 'TikTok Creator',
    'kreator tiktok'                   => 'TikTok Creator',

    'welding'                          => 'Welding',
    'pengelasan'                       => 'Welding',

    'customer-service'                 => 'Customer Service',
    'layanan pelanggan'                => 'Customer Service',
    ];

    // Yes/No umum → Ya/Tidak (INFO KOST & SUDAH BERKELUARGA)
    private array $mapYesNo = [
        'yes' => 'Ya', 'y' => 'Ya', '1' => 'Ya', 'true' => 'Ya', 'ya' => 'Ya',
        'no'  => 'Tidak', 'n' => 'Tidak', '0' => 'Tidak', 'false' => 'Tidak', 'tidak' => 'Tidak',
    ];

    /** Humanize slug → “Corel Photoshop” */
    private function humanizeSlug(string $val): string
    {
        $s = str_replace('-', ' ', trim($val));
        return mb_convert_case($s, MB_CASE_TITLE, 'UTF-8');
    }

    /** Ambil label dari map (case-insensitive), fallback humanize slug */
    private function labelize(array $map, ?string $val): string
    {
        if ($val === null || $val === '') return '-';
        $key = Str::lower(trim($val));
        foreach ($map as $k => $v) {
            if (Str::lower($k) === $key) return $v;
        }
        return $this->humanizeSlug($val);
    }

    /** Untuk list yang dipisah koma: mapping satu-satu lalu gabung */
    private function labelizeList(array $map, ?string $csv): string
    {
        if ($csv === null || trim($csv) === '') return '-';
        $parts = array_filter(array_map('trim', explode(',', $csv)), fn($x) => $x !== '');
        if (empty($parts)) return '-';
        return implode(', ', array_map(fn($p) => $this->labelize($map, $p), $parts));
    }

    /**
     * GET /admin/interns.json
     * JSON untuk tabel (global search semua kolom + advanced per kolom + pagination).
     */
    public function index(Request $req)
    {
        $scope   = $req->get('scope', 'all');
        $perPage = (int) $req->get('per_page', 15);
        $perPage = $perPage > 0 ? $perPage : 15;

        // Kolom yang ikut di-search
        $searchable = [
            'fullname','born_date','student_id','email','gender','phone_number',
            'institution_name','study_program','faculty','current_city',
            'internship_reason','internship_type','internship_arrangement',
            'current_status','internship_status',
            'english_book_ability','supervisor_contact',
            'internship_interest','internship_interest_other',
            'design_software','video_software','programming_languages',
            'digital_marketing_type','digital_marketing_type_other',
            'laptop_equipment','owned_tools','owned_tools_other',
            'start_date','end_date',
            'internship_info_sources','internship_info_other',
            'current_activities','boarding_info','family_status',
            'parent_wa_contact','social_media_instagram',
            'created_at',
        ];

        $q = IR::query();

        // Scope
        if ($scope !== 'all') {
            $q->where('internship_status', $scope);
        }

        // ========== GLOBAL SEARCH ==========
        if ($req->filled('q')) {
            $raw = trim($req->get('q'));
            $needle = $raw;
            $needleLower = Str::lower($raw);

            $genderCandidates = $this->normalizeGenderKeywords($needleLower);

            $q->where(function ($qq) use ($searchable, $needle, $genderCandidates) {
                foreach ($searchable as $col) {
                    if ($col === 'created_at') {
                        $dates = $this->parseDateCandidates($needle);
                        foreach ($dates as $d) $qq->orWhereDate('created_at', '=', $d);
                        $qq->orWhere('created_at', 'like', "%{$needle}%");
                        continue;
                    }
                    if ($col === 'gender' && !empty($genderCandidates)) {
                        foreach ($genderCandidates as $g) $qq->orWhere('gender', 'like', "%{$g}%");
                        continue;
                    }
                    $qq->orWhere($col, 'like', "%{$needle}%");
                }
            });
        }

        // ========== ADVANCED FILTERS ==========
        foreach ($searchable as $col) {
            if (!$req->filled($col)) continue;
            $val = trim($req->get($col));

            if ($col === 'created_at') {
                $dateCandidates = $this->parseDateCandidates($val);
                if (!empty($dateCandidates)) {
                    $q->where(function ($qq) use ($dateCandidates, $val) {
                        foreach ($dateCandidates as $d) $qq->orWhereDate('created_at', '=', $d);
                        $qq->orWhere('created_at', 'like', "%{$val}%");
                    });
                } else {
                    $q->where('created_at', 'like', "%{$val}%");
                }
                continue;
            }

            $q->where($col, 'like', "%{$val}%");
        }

        // ========== DATE RANGE FILTERS (string Y-m-d → aman leksikografis) ==========
        if ($req->filled('start_date_from')) {
            $from = $this->normalizeYmdInput($req->get('start_date_from'));
            if ($from) $q->where('start_date', '>=', $from);
            else $q->where('start_date', 'like', '%'.trim($req->get('start_date_from')).'%');
        }
        if ($req->filled('start_date_to')) {
            $to = $this->normalizeYmdInput($req->get('start_date_to'));
            if ($to) $q->where('start_date', '<=', $to);
            else $q->where('start_date', 'like', '%'.trim($req->get('start_date_to')).'%');
        }
        if ($req->filled('end_date_from')) {
            $from = $this->normalizeYmdInput($req->get('end_date_from'));
            if ($from) $q->where('end_date', '>=', $from);
            else $q->where('end_date', 'like', '%'.trim($req->get('end_date_from')).'%');
        }
        if ($req->filled('end_date_to')) {
            $to = $this->normalizeYmdInput($req->get('end_date_to'));
            if ($to) $q->where('end_date', '<=', $to);
            else $q->where('end_date', 'like', '%'.trim($req->get('end_date_to')).'%');
        }

        // Urutkan terbaru
        $q->orderByDesc('created_at');

        // Paginate
        $p = $q->paginate($perPage)->appends($req->query());

        // Map data → tambah certificate_pdf_url (Browsershot) + aman-kan hanya untuk completed
        $rows = array_map(function (IR $r) {
            $canCert = $r->internship_status === IR::STATUS_COMPLETED;

            return [
                'id'            => $r->id,
                'fullname'      => $r->fullname,

                // Biarkan born_date tampil apa adanya (string "YYYY-MM-DD" jika tersimpan begitu)
                'born_date'     => $this->formatIndoDateOut($r->born_date),

                'student_id'    => preg_replace('/^(NIM|NIS)\s*/i', '', (string) $r->student_id),
                'email'         => $r->email,
                'internship_status' => $r->internship_status,

                'gender'        => $this->labelize($this->mapGender, $r->gender),
                'phone_number'  => $r->phone_number,
                'institution_name' => $r->institution_name,
                'study_program' => $r->study_program,
                'faculty'       => $r->faculty,
                'current_city'  => $r->current_city,
                'internship_reason' => $r->internship_reason,

                'internship_type'         => $this->labelize($this->mapType, $r->internship_type),
                'internship_arrangement'  => $this->labelize($this->mapArrangement, $r->internship_arrangement),
                'current_status'          => $this->labelize($this->mapCurrentStatus, $r->current_status),

                'english_book_ability' => $r->english_book_ability,
                'supervisor_contact'   => $r->supervisor_contact,
                'internship_interest'  => $this->labelize($this->mapInterest, $r->internship_interest),
                'internship_interest_other' => $r->internship_interest_other,

                'design_software' => $r->design_software,
                'video_software'  => $r->video_software,
                'programming_languages' => $r->programming_languages,
                'digital_marketing_type' => $r->digital_marketing_type,
                'digital_marketing_type_other' => $r->digital_marketing_type_other,

                'laptop_equipment' => $this->labelize($this->mapLaptop, $r->laptop_equipment),
                'owned_tools'      => $this->labelizeList($this->mapTools, $r->owned_tools),
                'owned_tools_other'=> $r->owned_tools_other,

                // 👇 Perbaikan inti: tampilkan MULAI & SELESAI dengan format Indonesia "d F Y"
                'start_date'       => $this->formatIndoDateOut($r->start_date),
                'end_date'         => $this->formatIndoDateOut($r->end_date),

                'internship_info_sources' => $r->internship_info_sources,
                'internship_info_other'   => $r->internship_info_other,
                'current_activities'      => $r->current_activities,

                'boarding_info'           => $this->labelize($this->mapYesNo, $r->boarding_info),
                'family_status'           => $this->labelize($this->mapYesNo, $r->family_status),

                'parent_wa_contact'       => $r->parent_wa_contact,
                'social_media_instagram'  => $r->social_media_instagram,
                'cv_ktp_portofolio_pdf'   => $r->cv_ktp_portofolio_pdf ? asset('storage/'.$r->cv_ktp_portofolio_pdf) : null,
                'portofolio_visual'       => $r->portofolio_visual ? asset('storage/'.$r->portofolio_visual) : null,

                'created_at'              => optional($r->created_at)->format('Y-m-d'),

                // URL sertifikat: hanya untuk yang selesai
                'certificate_url'         => $canCert ? route('admin.interns.certificate', $r) : null,          // DomPDF (ringan)
                'certificate_pdf_url'     => $canCert ? route('admin.interns.certificate.pdf', $r) : null,      // Headless Chrome (identik)
                'status_update_url'       => route('admin.interns.status.update', $r),
            ];
        }, $p->items());

        return response()->json([
            'data' => $rows,
            'meta' => [
                'current_page' => $p->currentPage(),
                'per_page'     => $p->perPage(),
                'total'        => $p->total(),
                'last_page'    => $p->lastPage(),
            ],
            'links' => [
                'first' => $p->url(1),
                'prev'  => $p->previousPageUrl(),
                'next'  => $p->nextPageUrl(),
                'last'  => $p->url($p->lastPage()),
            ],
        ]);
    }

    /**
     * Konversi input tanggal bebas menjadi kandidat 'Y-m-d'.
     * Dukung: YYYY-MM-DD, DD-MM-YYYY, DD/MM/YYYY, YYYYMMDD.
     */
    private function parseDateCandidates(string $s): array
    {
        $s = trim($s);
        $cands = [];

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)) $cands[] = $s;

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $s)) {
            [$d, $m, $y] = explode('-', $s);
            $cands[] = sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d);
        }

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $s)) {
            [$d, $m, $y] = explode('/', $s);
            $cands[] = sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d);
        }

        if (preg_match('/^\d{8}$/', $s)) {
            $y = substr($s, 0, 4);
            $m = substr($s, 4, 2);
            $d = substr($s, 6, 2);
            $cands[] = sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d);
        }

        return array_values(array_unique(array_filter($cands)));
    }

    /** Normalisasi INPUT user ke Y-m-d; kalau gagal, return '' (untuk range filter) */
    private function normalizeYmdInput(string $s): string
    {
        $c = $this->parseDateCandidates($s);
        return $c[0] ?? '';
    }

    /**
     * Normalisasi nilai (ISO/Unix/string) ke Y-m-d.
     * Jika gagal parse, kembalikan string apa adanya.
     */
    private function normalizeYmdOut($v): ?string
    {
        if ($v === null || $v === '') return null;
        try {
            return Carbon::parse($v)->format('Y-m-d');
        } catch (\Throwable $e) {
            return is_string($v) ? $v : null;
        }
    }

    /**
     * Format tanggal ke Indonesia (d F Y), contoh: "08 Juni 2025".
     * Jika tidak bisa di-parse, kembalikan string aslinya (apa adanya).
     */
    private function formatIndoDateOut($v): ?string
    {
        if ($v === null || $v === '') return null;
        try {
            $d = Carbon::parse($v);
            $bulan = [
                'Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'
            ];
            return $d->format('d') . ' ' . $bulan[$d->month - 1] . ' ' . $d->format('Y');
        } catch (\Throwable $e) {
            return is_string($v) ? trim($v) : null;
        }
    }

    /**
     * Normalisasi kata kunci gender → daftar kandidat nilai.
     */
    private function normalizeGenderKeywords(string $g): array
    {
        $out = [];
        if (preg_match('/\b(perempuan|wanita|cewek|female|f)\b/u', $g)) {
            $out = array_merge($out, ['perempuan','Perempuan','wanita','Wanita','female','Female','F','f']);
        }
        if (preg_match('/\b(laki|pria|lelaki|cowok|male|m)\b/u', $g)) {
            $out = array_merge($out, ['laki-laki','Laki-laki','pria','Pria','male','Male','M','m']);
        }
        return array_values(array_unique($out));
    }
}
