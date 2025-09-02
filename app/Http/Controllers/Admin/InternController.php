<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipRegistration as IR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Str;

class InternController extends Controller
{
    /**
     * Simpan file memakai nama asli; bila bentrok, beri (n).
     */
    private function storeWithOriginalName($file, string $directory = 'uploads'): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = $file->getClientOriginalExtension();

        $base = preg_replace('/[^A-Za-z0-9_\- ]+/', '', $originalName);
        $base = preg_replace('/\s+/', ' ', trim($base));
        $base = str_replace(' ', '-', $base);

        $filename = "{$base}.{$extension}";
        $path     = "{$directory}/{$filename}";
        $i = 1;

        while (Storage::disk('public')->exists($path)) {
            $filename = "{$base}({$i}).{$extension}";
            $path     = "{$directory}/{$filename}";
            $i++;
        }

        $file->storeAs($directory, $filename, 'public');
        return $path;
    }

    /**
     * Helper list tabel + pencarian ringan.
     */
    private function table(Request $request, $query, string $title, string $scope)
    {
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
     */
    public function updateStatus(Request $request, IR $intern)
    {
        $validated = $request->validate([
            'internship_status' => 'required|in:new,active,completed,exited,pending',
        ]);

        $intern->internship_status = $validated['internship_status'];
        $intern->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Status pemagang diperbarui.');
    }

    /**
     * PATCH /admin/interns/bulk/status
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
     * Admin unggah/ganti file untuk satu pemagang.
     */
    public function updateFiles(Request $request, IR $intern)
    {
        $request->validate([
            'cv_ktp_portofolio_pdf' => 'nullable|file|mimes:pdf|max:10240',
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
     * (Opsional) DomPDF versi ringan (bukan canvas/JS).
     */
    public function certificate(IR $intern)
    {
        if ($intern->internship_status !== IR::STATUS_COMPLETED) {
            abort(403, 'Sertifikat hanya tersedia untuk pemagang yang sudah selesai.');
        }

        // Ganti 'interns.certificates' dengan view DomPDF kamu jika ada
        $pdf = Pdf::loadView('interns.certificates', compact('intern'))
            ->setPaper('a4', 'landscape');

        $safeName = preg_replace('/[^A-Za-z0-9_\- ]+/', '', $intern->fullname);
        $filename = "Sertifikat_{$safeName}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Spatie Browsershot — render JS/canvas → PDF 1 halaman (full-bleed).
     * GET /admin/interns/{intern}/certificate.pdf
     */
    public function certificatePdf(IR $intern)
    {
        if ($intern->internship_status !== IR::STATUS_COMPLETED) {
            abort(403, 'Sertifikat hanya tersedia untuk pemagang yang sudah selesai.');
        }

        // Render Blade yang berisi canvas
        $html = view('certificate', compact('intern'))->render();

        $safeName = trim(preg_replace('/[^A-Za-z0-9_\- ]+/', '', (string) $intern->fullname)) ?: 'Pemagang';
        $filename = 'Sertifikat_' . Str::slug($safeName, '_') . '.pdf';

        $dir  = storage_path('app/public/certificates');
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $path = $dir . DIRECTORY_SEPARATOR . $filename;

        $bs = Browsershot::html($html)
            ->showBackground()                         // warna & bg
            ->margins(0, 0, 0, 0)                      // no margin
            ->setOption('printBackground', true)       // jaga warna
            ->setOption('preferCSSPageSize', true)     // IKUTI @page size 1123x794
            ->emulateMedia('print')                    // gunakan CSS print
            ->windowSize(1123, 794)                    // viewport pas
            ->deviceScaleFactor(2)                     // tajam
            ->waitForFunction('window.__CERT_READY === true') // tunggu canvas selesai
            ->timeout(120);

        if ($chromePath = env('BROWSERSHOT_CHROME_PATH')) {
            $bs->setChromePath($chromePath);
        }
        // Jika server perlu:
        // $bs->addChromiumArguments(['--no-sandbox','--disable-setuid-sandbox']);

        $bs->savePdf($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
