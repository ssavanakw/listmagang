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
        return view('pages.internship.form-page');
    }

    /**
     * Simpan data pendaftaran.
     */
    public function store(Request $request)
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

            'design_software'             => 'nullable|string|max:255',
            'video_software'              => 'nullable|string|max:255',
            'programming_languages'       => 'nullable|string|max:255',
            'digital_marketing_type'      => 'nullable|string|max:255',
            'digital_marketing_type_other'=> 'nullable|string|max:255',

            'laptop_equipment'            => 'nullable|string|max:50',

            // checkbox-array
            'owned_tools'                 => 'nullable|array',
            'owned_tools.*'               => 'string|max:100',
            'owned_tools_other'           => 'nullable|string|max:255',

            'start_date'                  => 'nullable|string|max:50',
            'end_date'                    => 'nullable|string|max:50',

            'internship_info_sources'     => 'nullable|array',
            'internship_info_sources.*'   => 'string|max:100',
            'internship_info_other'       => 'nullable|string|max:255',

            // file
            'cv_ktp_portofolio_pdf'       => 'nullable|file|mimes:pdf|max:10240',             // 10MB
            'portofolio_visual'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240', // ijinkan pdf juga
            // lain-lain
            'current_activities'          => 'nullable|string|max:255',
            'boarding_info'               => 'nullable|string|max:50',
            'family_status'               => 'required|string|max:50',
            'parent_wa_contact'           => 'nullable|string|max:30',
            'social_media_instagram'      => 'nullable|string|max:255',
        ]);

        $data = $validated;

        // Normalisasi tanggal → coba Y-m-d, jika gagal biarkan apa adanya
        foreach (['born_date', 'start_date', 'end_date'] as $fld) {
            if (!empty($data[$fld])) {
                try {
                    $data[$fld] = Carbon::parse($data[$fld])->format('Y-m-d');
                } catch (\Throwable $e) {
                    // biarkan string asli
                }
            }
        }

        // Gabungkan checkbox array → CSV (abaikan "other"/"Lainnya")
        $toCsv = static function ($arr, array $ignore = []) {
            if (!is_array($arr)) return $arr;
            $vals = array_filter(array_map('trim', $arr), function ($v) use ($ignore) {
                if ($v === '') return false;
                $lv = mb_strtolower($v);
                return !in_array($lv, $ignore, true);
            });
            return empty($vals) ? null : implode(', ', $vals);
        };

        $data['owned_tools']             = $toCsv($request->input('owned_tools', []), ['lainnya', 'other']);
        $data['internship_info_sources'] = $toCsv($request->input('internship_info_sources', []), ['other']);

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

        // Status awal
        $data['internship_status'] = IR::STATUS_NEW;

        IR::create($data);

        return back()->with('success', 'Data berhasil disimpan!');
    }
}
