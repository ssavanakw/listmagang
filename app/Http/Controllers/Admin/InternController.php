<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipRegistration as IR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InternController extends Controller
{
    /**
     * Simpan file menggunakan NAMA ASLI.
     * Jika sudah ada file dengan nama yang sama, tambahkan penomoran di belakang nama file.
     *
     * Contoh hasil:
     *   - "cv-budi.pdf"
     *   - "cv-budi(1).pdf"
     *   - "cv-budi(2).pdf"
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory   direktori relatif di disk 'public' (default: 'uploads')
     * @return string             path relatif yang TERSIMPAN (mis: "uploads/cv-budi.pdf")
     */
    private function storeWithOriginalName($file, string $directory = 'uploads'): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = $file->getClientOriginalExtension();

        // Bersihkan nama agar aman untuk file system
        $base = preg_replace('/[^A-Za-z0-9_\- ]+/', '', $originalName);
        $base = preg_replace('/\s+/', ' ', trim($base));        // rapikan spasi
        $base = str_replace(' ', '-', $base);                   // jadikan kebab-case ringan

        $filename = "{$base}.{$extension}";
        $path     = "{$directory}/{$filename}";
        $i = 1;

        // Tambahkan (n) bila sudah ada file dengan nama yang sama
        while (Storage::disk('public')->exists($path)) {
            $filename = "{$base}({$i}).{$extension}";
            $path     = "{$directory}/{$filename}";
            $i++;
        }

        // Simpan dengan nama yang sudah ditentukan
        $file->storeAs($directory, $filename, 'public');

        return $path; // contoh: "uploads/cv-budi(1).pdf"
    }

    /**
     * Helper untuk render tabel dengan filter pencarian sederhana.
     */
    private function table(Request $request, $query, string $title, string $scope)
    {
        // search global: nama, email, NIM/NIS
        $query->when($request->filled('q'), function ($q) use ($request) {
            $s = trim($request->get('q'));
            $q->where(function ($qq) use ($s) {
                $qq->where('fullname', 'like', "%{$s}%")
                   ->orWhere('email', 'like', "%{$s}%")
                   ->orWhere('student_id', 'like', "%{$s}%");
            });
        });

        $interns = $query->paginate(15)->withQueryString();

        return view('interns.index', [
            'interns' => $interns,
            'title'   => $title,
            'scope'   => $scope,
        ]);
    }

    public function index(Request $request)
    {
        return $this->table(
            $request,
            IR::query()->orderByDesc('created_at'),
            'Semua Pemagang',
            'all'
        );
    }

    public function active(Request $request)
    {
        return $this->table(
            $request,
            IR::where('internship_status', IR::STATUS_ACTIVE)->orderByDesc('updated_at'),
            'Pemagang Aktif',
            'active'
        );
    }

    public function completed(Request $request)
    {
        return $this->table(
            $request,
            IR::where('internship_status', IR::STATUS_COMPLETED)->orderByDesc('updated_at'),
            'Pemagang Selesai',
            'completed'
        );
    }

    public function exited(Request $request)
    {
        return $this->table(
            $request,
            IR::where('internship_status', IR::STATUS_EXITED)->orderByDesc('updated_at'),
            'Pemagang Keluar',
            'exited'
        );
    }

    public function pending(Request $request)
    {
        return $this->table(
            $request,
            IR::where('internship_status', IR::STATUS_PENDING)->orderByDesc('created_at'),
            'Pemagang Pending',
            'pending'
        );
    }

    /**
     * PATCH /admin/interns/{intern}/status
     * Ubah status satu pemagang.
     */
    public function updateStatus(Request $request, IR $intern)
    {
        $validated = $request->validate([
            'internship_status' => 'required|in:new,active,completed,exited,pending',
        ]);

        $intern->internship_status = $validated['internship_status'];
        $intern->save();

        // AJAX -> JSON, non-AJAX -> redirect back
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Status pemagang diperbarui.');
    }

    /**
     * PATCH /admin/interns/bulk/status
     * Ubah status banyak pemagang sekaligus.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'ids'               => 'required|array|min:1',
            'ids.*'             => 'integer|exists:internship_registrations,id',
            'internship_status' => 'required|in:new,active,completed,exited,pending',
        ]);

        $affected = 0;

        DB::transaction(function () use ($validated, &$affected) {
            $affected = IR::whereIn('id', $validated['ids'])
                ->update(['internship_status' => $validated['internship_status']]);
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'affected' => $affected]);
        }

        return back()->with('success', "Status {$affected} pemagang diperbarui.");
    }

    /**
     * (Opsional) Admin unggah/ganti file untuk satu pemagang.
     */
    public function updateFiles(Request $request, IR $intern)
    {
        $request->validate([
            'cv_ktp_portofolio_pdf' => 'nullable|file|mimes:pdf|max:10240',     // 10MB
            'portofolio_visual'     => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ]);

        $data = [];

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

        if (!empty($data)) {
            $intern->fill($data)->save();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'paths' => $data]);
        }

        return back()->with('success', 'Berkas berhasil diperbarui.');
    }

    /**
     * GET /admin/interns/{intern}/certificate
     * Unduh sertifikat PDF untuk pemagang yang statusnya selesai.
     * => now rendered as A4 LANDSCAPE
     */
    public function certificate(IR $intern)
    {
        // Hanya untuk status selesai
        if ($intern->internship_status !== IR::STATUS_COMPLETED) {
            abort(403, 'Sertifikat hanya tersedia untuk pemagang yang sudah selesai.');
        }

        $pdf = Pdf::loadView('interns.certificates', compact('intern'))
            ->setPaper('a4', 'landscape'); // <-- UBAH: A4 Landscape

        $safeName = preg_replace('/[^A-Za-z0-9_\- ]+/', '', $intern->fullname);
        $filename = "Sertifikat_{$safeName}.pdf";

        // Download langsung
        return $pdf->download($filename);

        // Atau tampilkan di browser:
        // return $pdf->stream($filename);
    }
}
