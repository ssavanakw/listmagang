<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InternshipRegistration as IR; // sesuaikan model

class LoaController extends Controller
{
    /**
     * Generate LOA (PDF)
     */
    public function generate(Request $request)
    {
        $request->validate([
            'intern_id'                 => ['required','integer'],
            // 3 cara input rows (semua opsional)
            'loa_items'                 => ['sometimes','array'],
            'loa_items.*.deskripsi'     => ['nullable','string','max:1000'],
            'loa_items.*.keterangan'    => ['nullable','string','max:1000'],

            'loa_deskripsi'             => ['sometimes','array'],
            'loa_deskripsi.*'           => ['nullable','string','max:1000'],
            'loa_keterangan'            => ['sometimes','array'],
            'loa_keterangan.*'          => ['nullable','string','max:1000'],

            'rows_json'                 => ['sometimes','string'], // JSON string berisi [{deskripsi, keterangan}, ...]
        ]);

        $user = $request->user();

        $intern = IR::where('id', $request->input('intern_id'))
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Pemagang + owner + status completed
        $this->ensureCanAccessCompletedDocs($user, $intern);

        // ------ Kumpulkan rows dari SEMUA sumber (digabung) ------
        $rows = [];

        // Helper tambah baris jika tidak kosong semua
        $pushRow = function (?string $d, ?string $k) use (&$rows) {
            $d = trim((string)$d);
            $k = trim((string)$k);
            if ($d !== '' || $k !== '') {
                $rows[] = ['deskripsi' => $d, 'keterangan' => $k];
            }
        };

        // 1) loa_items[] (array of objects)
        $items = $request->input('loa_items', []);
        if (is_array($items)) {
            foreach ($items as $it) {
                $pushRow($it['deskripsi'] ?? '', $it['keterangan'] ?? '');
            }
        }

        // 2) dua array paralel: loa_deskripsi[] + loa_keterangan[]
        $descs = $request->input('loa_deskripsi', []);
        $notes = $request->input('loa_keterangan', []);
        if (is_array($descs) || is_array($notes)) {
            $descs = is_array($descs) ? $descs : [];
            $notes = is_array($notes) ? $notes : [];
            $max   = max(count($descs), count($notes));
            for ($i = 0; $i < $max; $i++) {
                $pushRow($descs[$i] ?? '', $notes[$i] ?? '');
            }
        }

        // 3) rows_json (JSON string)
        if ($request->filled('rows_json')) {
            try {
                $decoded = json_decode($request->input('rows_json'), true);
                if (is_array($decoded)) {
                    foreach ($decoded as $it) {
                        if (is_array($it)) {
                            $pushRow($it['deskripsi'] ?? '', $it['keterangan'] ?? '');
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Abaikan error JSON → akan jatuh ke placeholder di view
            }
        }

        // Batasi jumlah baris agar aman
        if (count($rows) > 100) {
            $rows = array_slice($rows, 0, 100);
        }

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.loa', [
                'intern' => $intern,
                'user'   => $user,
                'rows'   => $rows, // <-- Blade akan menggambar tabel dari sini
            ])->setPaper('A4', 'portrait');

            $safeName = \Illuminate\Support\Str::slug($intern->fullname ?? $user->name, '-');
            $fileName = 'LOA-'.$intern->id.'-'.$safeName.'-'.now()->format('Ymd_His').'.pdf';
            $dir      = 'documents/loa';
            $this->ensurePublicDir($dir);
            $path     = $dir.'/'.$fileName;

            \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());
            $publicUrl = asset('storage/'.$path);

            return back()->with('success', 'LOA berhasil digenerate.')
                        ->with('loa_url', $publicUrl);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal generate LOA', [
                'err' => $e->getMessage(),
                'intern_id' => $intern->id,
                'user_id' => $user->id,
            ]);
            return back()->with('error', 'Gagal membuat LOA. Silakan coba lagi atau hubungi admin.');
        }
    }

    public function preview(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $reg = IR::query()
            ->where('id', $id)
            ->when(method_exists($user, 'internshipRegistrations'), fn($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        $payload = [
            'reg'  => $reg,
            'user' => $user,
            'rows' => [
                ['deskripsi' => 'Orientasi dan pengenalan tim', 'keterangan' => 'Minggu pertama'],
                ['deskripsi' => 'Implementasi fitur modul X', 'keterangan' => 'Sesuai task JIRA'],
            ],
            'now'  => now(),
        ];

        return view('user.loa', $payload); // sebagai HTML biasa
    }

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
