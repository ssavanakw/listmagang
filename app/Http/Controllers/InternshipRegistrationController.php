<?php

namespace App\Http\Controllers;

use App\Models\InternshipRegistration as IR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InternshipRegistrationController extends Controller
{
    /**
     * Tabel pendaftar (versi publik/admin sederhana).
     * Versi admin dengan filter ada di Admin\RegistrationController.
     */
    public function index()
    {
        // Boleh diganti paginate(25) bila diperlukan
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
            'fullname'                   => 'required|string|max:255',
            'born_date'                  => 'required|string|max:255',  // kolom di DB bertipe string
            'student_id'                 => 'required|string|max:50',
            'email'                      => 'required|string|max:255',
            'gender'                     => 'required|string|max:50',
            'phone_number'               => 'required|string|max:30',
            'institution_name'           => 'required|string|max:255',
            'study_program'              => 'required|string|max:255',
            'faculty'                    => 'required|string|max:255',
            'current_city'               => 'required|string|max:255',
            'internship_reason'          => 'required|string',
            'internship_type'            => 'required|string|max:50',
            'internship_arrangement'     => 'required|string|max:50',
            'current_status'             => 'required|string|max:50',   // student/fresh graduate (biodata)
            'english_book_ability'       => 'required|string|max:50',
            'supervisor_contact'         => 'nullable|string|max:255',
            'internship_interest'        => 'required|string|max:255',
            'internship_interest_other'  => 'nullable|string|max:255',

            'design_software'            => 'nullable|string|max:255',
            'video_software'             => 'nullable|string|max:255',
            'programming_languages'      => 'nullable|string|max:255',
            'digital_marketing_type'     => 'nullable|string|max:255',
            'digital_marketing_type_other'=> 'nullable|string|max:255',

            // dukung array → CSV
            'laptop_equipment'           => 'nullable|string|max:50',
            'owned_tools'                => 'nullable', // string / array
            'owned_tools_other'          => 'nullable|string|max:255',

            'start_date'                 => 'nullable|string|max:50',
            'end_date'                   => 'nullable|string|max:50',

            'internship_info_sources'    => 'nullable', // string / array
            'internship_info_other'      => 'nullable|string|max:255',

            'cv_ktp_portofolio_pdf'      => 'nullable|mimes:pdf|max:4096',
            'portofolio_visual'          => 'nullable|mimes:jpg,jpeg,png,pdf|max:4096',

            'current_activities'         => 'nullable|string|max:255',
            'boarding_info'              => 'nullable|string|max:50',
            'family_status'              => 'required|string|max:50',
            'parent_wa_contact'          => 'nullable|string|max:30',
            'social_media_instagram'     => 'nullable|string|max:255',
        ]);

        // Siapkan payload
        $data = $validated;

        // Normalisasi tanggal (tetap disimpan sebagai string "Y-m-d" agar rapi)
        foreach (['born_date','start_date','end_date'] as $fld) {
            if (!empty($data[$fld])) {
                try {
                    $data[$fld] = Carbon::parse($data[$fld])->format('Y-m-d');
                } catch (\Throwable $e) {
                    // biarkan apa adanya jika tidak bisa diparse
                }
            }
        }

        // Dukung input array → CSV sederhana (karena kolom DB string)
        $toCsv = function ($value) {
            if (is_array($value)) {
                // hilangkan nilai kosong, trim, jadikan CSV
                $value = implode(', ', array_filter(array_map('trim', $value), fn($v) => $v !== ''));
            }
            return $value;
        };
        $data['owned_tools']             = $toCsv($request->input('owned_tools'));
        $data['internship_info_sources'] = $toCsv($request->input('internship_info_sources'));

        // Upload file (disimpan di disk "public" → /storage/uploads/...)
        if ($request->hasFile('cv_ktp_portofolio_pdf')) {
            $data['cv_ktp_portofolio_pdf'] = $request->file('cv_ktp_portofolio_pdf')
                ->store('uploads', 'public'); // simpan path "uploads/xxx.pdf"
        }
        if ($request->hasFile('portofolio_visual')) {
            $data['portofolio_visual'] = $request->file('portofolio_visual')
                ->store('uploads', 'public');
        }

        // Set status workflow default agar tercatat di dashboard & tampil di tab terkait.
        // Pilih salah satu (umumnya "new" saat pertama submit).
        $data['internship_status'] = IR::STATUS_NEW;
        // Atau jika mau menunggu verifikasi:
        // $data['internship_status'] = IR::STATUS_PENDING;

        IR::create($data);

        return back()->with('success', 'Data berhasil disimpan!');
    }
}
