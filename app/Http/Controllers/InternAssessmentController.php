<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InternAssessment;
use App\Models\InternshipRegistration as IR;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InternAssessmentController extends Controller
{
    // ===========================
    // PREVIEW PDF
    // ===========================
    public function previewPDF($id)
    {
        $assessment = InternAssessment::findOrFail($id);

        $assessment->update([
            'company_name' => $assessment->company_name ?? 'SEVEN INC.',
            'company_address' => $assessment->company_address ?? "Jl. Raya Janti, Gang Arjuna No. 59, Karangjambe,\nBanguntapan, Bantul, Yogyakarta",
            'company_logo_path' => $assessment->company_logo_path ?? 'images/logos/seveninc_logo.png',
            'signature_name' => $assessment->signature_name ?? 'Rekario Danny Sanjaya, S.Kom',
            'signature_position' => $assessment->signature_position ?? 'Direktur SEVEN INC',
            'signature_image_path' => $assessment->signature_image_path ?? 'images/signature/ttd_rekariodanny.png',
        ]);

        $pdf = Pdf::loadView('admin.interns.pdf_assessment', compact('assessment'))
                ->setPaper('A4', 'portrait');

        return $pdf->stream('Form Penilaian - ' . $assessment->fullname . '.pdf');
    }

    // ===========================
    // DOWNLOAD PDF
    // ===========================
    public function downloadPdf($id)
    {
        $assessment = InternAssessment::findOrFail($id);

        $assessment->update([
            'company_name' => $assessment->company_name ?? 'SEVEN INC.',
            'company_address' => $assessment->company_address ?? "Jl. Raya Janti, Gang Arjuna No. 59, Karangjambe,\nBanguntapan, Bantul, Yogyakarta",
            'company_logo_path' => $assessment->company_logo_path ?? 'images/logos/seveninc_logo.png',
            'signature_name' => $assessment->signature_name ?? 'Rekario Danny Sanjaya, S.Kom',
            'signature_position' => $assessment->signature_position ?? 'Direktur SEVEN INC',
            'signature_image_path' => $assessment->signature_image_path ?? 'images/signature/ttd_rekariodanny.png',
        ]);

        $pdf = Pdf::loadView('admin.interns.pdf_assessment', compact('assessment'))
                ->setPaper('A4', 'portrait');

        return $pdf->download('Penilaian_' . str_replace(' ', '_', $assessment->fullname) . '.pdf');
    }

    // ===========================
    // INDEX
    // ===========================
    public function index()
    {
        $data = InternAssessment::latest()->get();
        return view('admin.interns.index_assessment', compact('data'));
    }

    // ===========================
    // CREATE
    // ===========================
    public function create(Request $request)
    {
        $division = $request->get('div', 'Content Writer');
        $divisions = ['Content Writer', 'Programmer', 'UI/UX Designer', 'Graphic Designer', 'Digital Marketing', 'Lainnya'];

        $defaultAspects = [
            'Content Writer' => [
                ['aspek' => 'Copywriting', 'nilai' => 0],
                ['aspek' => 'Branding', 'nilai' => 0],
                ['aspek' => 'Riset Konten', 'nilai' => 0],
                ['aspek' => 'Kedisiplinan', 'nilai' => 0],
                ['aspek' => 'Kreativitas', 'nilai' => 0],
                ['aspek' => 'Kerjasama', 'nilai' => 0],
                ['aspek' => 'Kehadiran', 'nilai' => 0],
            ],
            'Programmer' => [
                ['aspek' => 'Coding Front/Backend', 'nilai' => 0],
                ['aspek' => 'Database', 'nilai' => 0],
                ['aspek' => 'Debugging', 'nilai' => 0],
                ['aspek' => 'Problem Solving', 'nilai' => 0],
                ['aspek' => 'Kedisiplinan', 'nilai' => 0],
                ['aspek' => 'Kerjasama', 'nilai' => 0],
                ['aspek' => 'Kehadiran', 'nilai' => 0],
            ],
            'UI/UX Designer' => [
                ['aspek' => 'User Research', 'nilai' => 0],
                ['aspek' => 'Wireframing & Prototyping', 'nilai' => 0],
                ['aspek' => 'Visual Design', 'nilai' => 0],
                ['aspek' => 'Layout & Typography', 'nilai' => 0],
                ['aspek' => 'Color Theory', 'nilai' => 0],
                ['aspek' => 'Kedisiplinan', 'nilai' => 0],
                ['aspek' => 'Kerjasama', 'nilai' => 0],
                ['aspek' => 'Kehadiran', 'nilai' => 0],
            ],
            'Graphic Designer' => [
                ['aspek' => 'Desain Visual', 'nilai' => 0],
                ['aspek' => 'Kreativitas & Inovasi', 'nilai' => 0],
                ['aspek' => 'Typography & Warna', 'nilai' => 0],
                ['aspek' => 'Infografis & Branding', 'nilai' => 0],
                ['aspek' => 'Kedisiplinan', 'nilai' => 0],
                ['aspek' => 'Kerjasama', 'nilai' => 0],
                ['aspek' => 'Kehadiran', 'nilai' => 0],
            ],
            'Digital Marketing' => [
                ['aspek' => 'Strategi Konten', 'nilai' => 0],
                ['aspek' => 'Analisis Data & Keyword', 'nilai' => 0],
                ['aspek' => 'Social Media Marketing', 'nilai' => 0],
                ['aspek' => 'Copywriting & Engagement', 'nilai' => 0],
                ['aspek' => 'Kedisiplinan', 'nilai' => 0],
                ['aspek' => 'Kreativitas', 'nilai' => 0],
                ['aspek' => 'Kerjasama', 'nilai' => 0],
                ['aspek' => 'Kehadiran', 'nilai' => 0],
            ],
        ];

        $aspects = $defaultAspects[$division] ?? $defaultAspects['Content Writer'];
        $interns = IR::whereIn('internship_status', [IR::STATUS_ACTIVE, IR::STATUS_COMPLETED])
            ->orderBy('fullname', 'asc')->get(['id', 'fullname', 'student_id', 'study_program']);

        return view('admin.interns.create_assessment', compact('aspects', 'division', 'divisions', 'interns'));
    }

    // ===========================
    // AJAX: LOAD ASPEK
    // ===========================
    public function getAspekByDivision(Request $request)
    {
        $division = $request->get('division', 'Content Writer');

        $defaultAspek = [
            'Content Writer' => [['aspek' => 'Copywriting'], ['aspek' => 'Branding'], ['aspek' => 'Riset Konten'], ['aspek' => 'Kedisiplinan'], ['aspek' => 'Kreativitas'], ['aspek' => 'Kerjasama'], ['aspek' => 'Kehadiran']],
            'Programmer' => [['aspek' => 'Coding Front/Backend'], ['aspek' => 'Database'], ['aspek' => 'Debugging'], ['aspek' => 'Problem Solving'], ['aspek' => 'Kedisiplinan'], ['aspek' => 'Kerjasama'], ['aspek' => 'Kehadiran']],
        ];

        return response()->json(['aspek' => $defaultAspek[$division] ?? [['aspek' => 'Kedisiplinan']]]);
    }

    // ===========================
    // STORE & UPDATE
    // ===========================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'nim_or_nis' => 'nullable|string|max:50',
            'study_program' => 'nullable|string|max:255',
            'div' => 'nullable|string|max:255',
            'aspek' => 'required|array',
            'nilai' => 'required|array',
        ]);

        $data = [];
        $total = 0;
        foreach ($validated['aspek'] as $i => $aspek) {
            $nilai = (float)($validated['nilai'][$i] ?? 0);
            $data[] = ['aspek' => $aspek, 'nilai' => $nilai];
            $total += $nilai;
        }

        InternAssessment::create([
            'fullname' => $validated['fullname'],
            'nim_or_nis' => $validated['nim_or_nis'] ?? null,
            'study_program' => $validated['study_program'] ?? null,
            'div' => $validated['div'] ?? 'Content Writer',
            'aspek_penilaian' => $data,
            'rata_rata' => round($total / count($validated['aspek']), 2),
        ]);

        return redirect()->route('interns.assessment.index')->with('success', 'Penilaian berhasil disimpan.');
    }

    // ===========================
    // SETTINGS PAGE
    // ===========================
    public function settings()
    {
        $company_name = 'SEVEN INC.';
        $company_address = 'Jl. Raya Janti, Gang Arjuna No. 59, Karangjambe, Banguntapan, Bantul, Yogyakarta';
        $company_logo_path = 'images/logos/seveninc_logo.png';
        $signature_name = 'Rekario Danny Sanjaya, S.Kom';
        $signature_position = 'Direktur SEVEN INC';
        $signature_image_path = 'images/signature/ttd_rekariodanny.png';

        // ambil semua file dari folder logos dan signature
        $logos = collect(Storage::disk('public')->files('images/logos'))
            ->filter(fn($file) => preg_match('/\.(png|jpg|jpeg)$/i', $file))
            ->values()
            ->toArray();

        $signatures = collect(Storage::disk('public')->files('images/signature'))
            ->filter(fn($file) => preg_match('/\.(png|jpg|jpeg)$/i', $file))
            ->values()
            ->toArray();

        return view('admin.interns.settings_assessment', compact(
            'company_name', 'company_address', 'company_logo_path',
            'signature_name', 'signature_position', 'signature_image_path',
            'logos', 'signatures'
        ));
    }


    // ===========================
    // PREVIEW LIVE (AJAX)
    // ===========================
    public function previewLive(Request $request)
    {
        $assessment = InternAssessment::latest()->first() ?? new InternAssessment([
            'fullname' => 'Contoh Nama',
            'nim_or_nis' => '202200123',
            'study_program' => 'Teknik Informatika',
            'div' => 'Frontend Developer',
            'aspek_penilaian' => [
                ['aspek' => 'Kedisiplinan', 'nilai' => 90],
                ['aspek' => 'Kerjasama', 'nilai' => 85],
                ['aspek' => 'Kreativitas', 'nilai' => 88],
            ],
            'rata_rata' => 87.6,
        ]);

        // dropdown pilihan logo & ttd
        $logoSelect = $request->input('company_logo_select');
        $sigSelect = $request->input('signature_image_select');

        if ($logoSelect) $assessment->company_logo_path = $logoSelect;
        if ($sigSelect) $assessment->signature_image_path = $sigSelect;

        // input teks
        $assessment->company_name = $request->input('company_name') ?: $assessment->company_name;
        $assessment->company_address = $request->input('company_address') ?: $assessment->company_address;
        $assessment->signature_name = $request->input('signature_name') ?: $assessment->signature_name;
        $assessment->signature_position = $request->input('signature_position') ?: $assessment->signature_position;

        // upload manual
        if ($request->hasFile('company_logo')) {
            $assessment->company_logo_path = $request->file('company_logo')->store('temp', 'public');
        }
        if ($request->hasFile('signature_image')) {
            $assessment->signature_image_path = $request->file('signature_image')->store('temp', 'public');
        }

        $pdf = Pdf::loadView('admin.interns.pdf_assessment', compact('assessment'))
            ->setPaper('A4', 'portrait');

        return response($pdf->output())->header('Content-Type', 'application/pdf');
    }

    /**
     * Preview statis PDF (untuk tampilan awal sebelum user mengubah form)
     */
    public function previewSettingsPdf()
    {
        // Ambil data penilaian terakhir atau buat dummy jika kosong
        $latest = InternAssessment::latest()->first();

        if (!$latest) {
            $latest = new InternAssessment([
                'fullname' => 'Contoh Peserta Magang',
                'nim_or_nis' => '22020144001',
                'study_program' => 'Sistem Informasi',
                'div' => 'Content Writer',
                'aspek_penilaian' => [
                    ['aspek' => 'Copywriting', 'nilai' => 95],
                    ['aspek' => 'Branding', 'nilai' => 92],
                    ['aspek' => 'Kreativitas', 'nilai' => 94],
                    ['aspek' => 'Kedisiplinan', 'nilai' => 96],
                ],
                'rata_rata' => 94.25,
                'company_name' => 'SEVEN INC.',
                'company_address' => "Jl. Raya Janti, Gang Arjuna No. 59, Karangjambe,\nBanguntapan, Bantul, Yogyakarta",
                'company_logo_path' => 'images/logos/seveninc_logo.png',
                'signature_name' => 'Rekario Danny Sanjaya, S.Kom',
                'signature_position' => 'Direktur SEVEN INC',
                'signature_image_path' => 'images/signature/ttd_rekariodanny.png',
            ]);
        }

        // Generate PDF untuk preview awal
        $pdf = Pdf::loadView('admin.interns.pdf_assessment', [
            'assessment' => $latest
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Preview_Penilaian.pdf');
    }

}
