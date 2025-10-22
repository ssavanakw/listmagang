<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentDownload;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InternshipRegistration as IR; // Sesuaikan dengan model yang digunakan
use App\Models\LoaSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LoaController extends Controller
{
    public function edit()
    {
        $loaSettings = LoaSettings::first();
        return view('admin.loa_editor', compact('loaSettings'));
    }

    public function update(Request $request)
    {
        $loaSettings = LoaSettings::first();
        $loaSettings->update($request->all());
        return redirect()->route('admin.loa.editor')->with('success', 'LOA Settings updated successfully');
    }

    /**
     * Generate LOA (PDF)
     */
    public function generate(Request $request)
    {
        // Validasi input dari request
        $request->validate([
            'intern_id' => ['required', 'integer'],
        ]);

        $user = $request->user();

        // Mengambil data intern (magang) berdasarkan ID yang diterima dalam request
        $intern = IR::where('id', $request->input('intern_id'))
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Pastikan pemagang bisa mengakses dokumen yang sudah selesai
        $this->ensureCanAccessCompletedDocs($user, $intern);

        // Ambil pengaturan LOA (Nama Perusahaan, Kontak, dsb)
        $loaSettings = LoaSettings::first();

        // Ambil informasi dari tabel internship_registration jika ada
        $internDetails = $intern ? $intern->toArray() : [];

        // Organize rows data from the intern and LOA settings
        $rows = [
            [
                'nama_siswa' => $internDetails['fullname'] ?? 'Nama Tidak Diketahui',
                'nim_nis' => $internDetails['nim'] ?? 'NIM Tidak Diketahui',
                'jurusan' => $internDetails['major'] ?? 'Jurusan Tidak Diketahui',
                'instansi' => $internDetails['institution_name'] ?? 'Instansi Tidak Diketahui',
                'periode' => $internDetails['start_date'] ? Carbon::parse($internDetails['start_date'])->format('d F Y') . ' - ' . Carbon::parse($internDetails['end_date'])->format('d F Y') : 'Periode Tidak Diketahui',
                'kontak' => $internDetails['contact_info'] ?? 'Kontak Tidak Diketahui',
            ]
        ];

        try {
            // Generate PDF menggunakan DomPDF
            $pdf = Pdf::loadView('user.loa', [
                'intern' => $intern,
                'user' => $user,
                'loaSettings' => $loaSettings, // Pass the LOA settings to the view
                'rows' => $rows, // The data to be used in the table
            ])->setPaper('A4', 'portrait');

            $safeName = Str::slug($intern->fullname ?? $user->name, '-');
            $fileName = 'LOA-' . $intern->id . '-' . $safeName . '-' . now()->format('Ymd_His') . '.pdf';
            $dir = 'documents/loa';
            $this->ensurePublicDir($dir);
            $path = $dir . '/' . $fileName;

            // Menyimpan file PDF ke storage publik
            Storage::disk('public')->put($path, $pdf->output());
            $publicUrl = asset('storage/' . $path);

            // Log sukses download LOA
            DocumentDownload::create([
                'user_id' => $user->id,
                'doc_type' => DocumentDownload::TYPE_LOA,
                'file_path' => $path,
                'file_url' => $publicUrl,
                'downloaded_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
            ]);

            // Langsung mengunduh file setelah berhasil digenerate
            return response()->download(storage_path("app/public/{$path}"));
        } catch (\Throwable $e) {
            // Log error jika terjadi kesalahan
            \Illuminate\Support\Facades\Log::error('Gagal generate LOA', [
                'err' => $e->getMessage(),
                'intern_id' => $intern->id,
                'user_id' => $user->id,
            ]);
            return back()->with('error', 'Gagal membuat LOA. Silakan coba lagi atau hubungi admin.');
        }
    }

    // Fungsi untuk melihat preview LOA
    public function preview(Request $request)
    {
        return view('user.loa');
    }

    // Preview the LOA
    // public function preview(Request $request)
    // {
    //     $loaSettings = LoaSettings::first();

    //     // Get rows for the live preview (example: participants data)
    //     $interns = IR::all();

    //     return response()->json([
    //         'loaSettings' => $loaSettings,
    //         'rows' => $interns,
    //     ]);
    // }

    protected function ensureCanAccessCompletedDocs($user, $intern): void
    {
        $status = strtolower((string)($intern->internship_status ?? ''));
        if (!($user->role === 'pemagang' && $intern->user_id === $user->id && $status === 'completed')) {
            abort(403, 'Anda tidak berhak membuat/akses LOA untuk data ini.');
        }
    }

    /**
     * Pastikan direktori di disk public tersedia
     */
    protected function ensurePublicDir(string $dir): void
    {
        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }
    }

}
