<?php

namespace App\Http\Controllers;

use App\Models\InternshipRegistration as IR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class InternshipRegistrationController extends Controller
{
    

    /**
     * Simpan file dengan NAMA ASLI.
     * Jika terdapat nama yang sama, tambahkan penomoran di belakang nama.
     * Return: path relatif pada disk 'public', mis: "uploads/cv-budi(1).pdf"
     */
    private function storeWithOriginalName($file, string $directory = 'uploads'): string
    {
        $nameOnly  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        // bersihkan nama agar aman untuk filesystem
        $base = preg_replace('/[^A-Za-z0-9_\- ]+/', '', $nameOnly);
        $base = preg_replace('/\s+/', ' ', trim($base));
        $base = str_replace(' ', '-', $base);

        // fallback kalau jadi kosong
        if ($base === '') $base = 'file';

        $filename = "{$base}.{$extension}";
        $path     = "{$directory}/{$filename}";
        $i = 1;

        // tambah (n) jika sudah ada
        while (Storage::disk('public')->exists($path)) {
            $filename = "{$base}({$i}).{$extension}";
            $path     = "{$directory}/{$filename}";
            $i++;
        }

        // simpan
        $file->storeAs($directory, $filename, 'public');

        return $path;
    }

    /**
     * Tabel pendaftar (publik sederhana).
     */
    public function index()
    {
        $registrations = IR::orderByDesc('created_at')->get();
        return view('pages.internship.table', compact('registrations'));
    }

    /**
     * Form pendaftaran.
     */
    public function create()
    {
        $userId = auth()->id();
        if (IR::where('user_id', $userId)->exists()) {
            return redirect()->route('internship.submitted');
        }
        // render form-page normal
        return view('pages.internship.form-page');
    }

    /**
     * Simpan data pendaftaran.
     */
    // InternshipRegistrationController.php
    public function store(Request $request)
    {
        // Cek apakah user sudah mengisi form sebelumnya
        $userId = auth()->id();

        if (IR::where('user_id', $userId)->exists()) {
            return redirect()->route('internship.submitted')
                ->with('info', 'Anda sudah mengisi form pendaftaran sebelumnya.');
        }

        // Validasi (sinkron dengan field yang ada di FORM)
        $validated = $request->validate([
            // Wajib (existing)
            'fullname'              => 'required|string|max:255',
            'born_date'             => 'required|string|max:255', // form pakai teks bebas
            'student_id'            => 'required|string|max:50',
            'email'                 => 'required|string|max:255',
            'gender'                => 'required|string|max:50',
            'phone_number'          => 'required|string|max:30',
            'institution_name'      => 'required|string|max:255',
            'study_program'         => 'required|string|max:255',
            'faculty'               => 'required|string|max:255',
            'current_city'          => 'required|string|max:255',
            'internship_reason'     => 'required|string',
            'internship_type'       => 'required|string|max:50',
            'internship_arrangement'=> 'required|string|max:50',
            'current_status'        => 'required|string|max:50',
            'english_book_ability'  => 'required|string|max:50',
            'supervisor_contact'    => 'nullable|string|max:255',
            'internship_interest'   => 'required|string|max:255',
            'internship_interest_other' => 'nullable|string|max:255',

            // Tambahan dari form
            // Periode magang di form masih input teks → validasi string (nanti kita parse Carbon)
            'start_date'            => 'nullable|string|max:255',
            'end_date'              => 'nullable|string|max:255',

            // Bidang minat/skill
            'design_software'           => 'required|string|max:255',
            'video_software'            => 'required|string|max:255',
            'programming_languages'     => 'required|string|max:255',
            'digital_marketing_type'    => 'nullable|string|max:100',
            'digital_marketing_type_other' => 'nullable|string|max:255',

            // Perlengkapan & alat
            'laptop_equipment'      => 'nullable|string|max:50',   // Ya / Tidak
            'owned_tools'           => 'nullable|array',           // dari checkbox []
            'owned_tools.*'         => 'nullable|string|max:255',
            'owned_tools_other'     => 'nullable|string|max:255',

            // Sumber info
            'internship_info_sources'   => 'nullable|array',       // dari checkbox []
            'internship_info_sources.*' => 'nullable|string|max:100',
            'internship_info_other'     => 'nullable|string|max:255',

            // Lain-lain
            'current_activities'    => 'nullable|string|max:1000',
            'boarding_info'         => 'nullable|string|max:50',   // Ya / Tidak
            'family_status'         => 'nullable|string|max:50',   // form: Ya/Tidak (akan kita mapping)
            'parent_wa_contact'     => 'nullable|string|max:255',
            'social_media_instagram'=> 'nullable|string|max:255',

            // File
            'cv_ktp_portofolio_pdf' => 'nullable|file|mimes:pdf|max:10240',             // 10MB
            'portofolio_visual'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $data = $validated;

        // Normalisasi tanggal → coba Y-m-d, jika gagal biarkan apa adanya
        foreach (['born_date', 'start_date', 'end_date'] as $fld) {
            if (!empty($data[$fld])) {
                try {
                    $data[$fld] = \Carbon\Carbon::parse($data[$fld])->format('Y-m-d');
                } catch (\Throwable $e) {
                    // biarkan string asli
                }
            }
        }

        // Helper: gabung checkbox array → CSV (abaikan "other"/"lainnya")
        $toCsv = static function ($arr, array $ignore = []) {
            if (!is_array($arr)) return $arr;
            $vals = array_filter(array_map('trim', $arr), function ($v) use ($ignore) {
                if ($v === '' || $v === null) return false;
                $lv = mb_strtolower($v);
                return !in_array($lv, $ignore, true);
            });
            return empty($vals) ? null : implode(', ', $vals);
        };

        // owned_tools[] + owned_tools_other → string
        $ownedToolsCsv = $toCsv($request->input('owned_tools', []), ['lainnya', 'other']);
        $ownedOther    = trim((string)$request->input('owned_tools_other'));
        if ($ownedOther !== '') {
            $ownedToolsCsv = $ownedToolsCsv ? ($ownedToolsCsv . ', ' . $ownedOther) : $ownedOther;
        }
        $data['owned_tools'] = $ownedToolsCsv;

        // internship_info_sources[] + internship_info_other → string
        $infoCsv   = $toCsv($request->input('internship_info_sources', []), ['other']);
        $infoOther = trim((string)$request->input('internship_info_other'));
        if ($infoOther !== '') {
            $infoCsv = $infoCsv ? ($infoCsv . ', ' . $infoOther) : $infoOther;
        }
        $data['internship_info_sources'] = $infoCsv;

        // Map family_status dari form (Ya/Tidak) → standar DB (single/married)
        // default: not_provided
        $fs = $request->input('family_status');
        if ($fs) {
            $fsLower = mb_strtolower($fs);
            if (in_array($fsLower, ['ya', 'yes'], true)) {
                $data['family_status'] = 'married';
            } elseif (in_array($fsLower, ['tidak', 'no'], true)) {
                $data['family_status'] = 'single';
            } else {
                $data['family_status'] = 'other';
            }
        } else {
            $data['family_status'] = 'not_provided';
        }

        // Upload file: pakai nama asli + penomoran jika duplikat
        if ($request->hasFile('cv_ktp_portofolio_pdf')) {
            $data['cv_ktp_portofolio_pdf'] = $this->storeWithOriginalName(
                $request->file('cv_ktp_portofolio_pdf'),
                'uploads'
            );
        }
        if ($request->hasFile('portofolio_visual')) {
            $data['portofolio_visual'] = $this->storeWithOriginalName(
                $request->file('portofolio_visual'),
                'uploads'
            );
        }

        // Status awal & relasi user
        $data['internship_status'] = IR::STATUS_WAITING;
        $data['user_id'] = $userId;

        // Simpan
        IR::create($data);

        // Tandai session & redirect
        session(['internship_submitted' => true]);

        return redirect()->route('internship.submitted')
            ->with('success', 'Data berhasil disimpan! Anda dapat melihat profil Anda di dashboard.');
    }


    public function editProfile()
    {
        $intern = optional(auth()->user())->internshipRegistration; // aman kalau null
        return view('user.edit-profile', compact('intern'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'fullname'                    => 'required|string|max:255',
            'born_date'                   => 'required|string|max:255',
            'student_id'                  => 'required|string|max:50',
            'email'                       => 'required|string|max:255',
            'gender'                      => 'required|string|max:50',
            'phone_number'                => 'required|string|max:30',
            'institution_name'            => 'required|string|max:255',
            'study_program'               => 'required|string|max:255',
            'faculty'                     => 'required|string|max:255',
            'current_city'                => 'required|string|max:255',
            'internship_reason'           => 'required|string',
            'internship_type'             => 'required|string|max:50',
            'internship_arrangement'      => 'required|string|max:50',
            'current_status'              => 'required|string|max:50',
            'english_book_ability'        => 'required|string|max:50',
            'supervisor_contact'          => 'nullable|string|max:255',
            'internship_interest'         => 'required|string|max:255',
            'internship_interest_other'   => 'nullable|string|max:255',

            // ⬇︎ TAMBAHAN FIELD BARU
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'owned_tools' => 'nullable|string',
            'internship_info_sources' => 'nullable|string',
            'family_status' => 'nullable|string|in:not_provided,single,married,other',

            // File validation
            'cv_ktp_portofolio_pdf'       => 'nullable|file|mimes:pdf|max:10240',             // 10MB
            'portofolio_visual'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240', // ijinkan pdf juga
            // Additional fields validation here...
        ]);

        // Normalisasi tanggal: born_date/start_date/end_date → Y-m-d (aman kalau gagal parse)
        foreach (['born_date', 'start_date', 'end_date'] as $fld) {
            if (!empty($validated[$fld])) {
                try {
                    $validated[$fld] = \Carbon\Carbon::parse($validated[$fld])->format('Y-m-d');
                } catch (\Throwable $e) {
                    // biarkan nilai aslinya jika gagal parse
                }
            }
        }

        // Handle file upload (pakai nama asli + penomoran, sama seperti store())
        if ($request->hasFile('cv_ktp_portofolio_pdf')) {
            $validated['cv_ktp_portofolio_pdf'] = $this->storeWithOriginalName(
                $request->file('cv_ktp_portofolio_pdf'),
                'uploads'
            );
        }
        if ($request->hasFile('portofolio_visual')) {
            $validated['portofolio_visual'] = $this->storeWithOriginalName(
                $request->file('portofolio_visual'),
                'uploads'
            );
        }

        $intern = auth()->user()->internshipRegistration;
        $intern->update($validated);

        return redirect()->route('user.dashboard')->with('success', 'Profil berhasil diperbarui!');
    }


}
