<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DailyReport;
use App\Models\LeaveRequest;
use App\Models\PendingTask;
use App\Models\SKLSetting;
use App\Models\InternshipRegistration as IR;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class UserController extends Controller
{

    public function riwayatMagang(Request $request)
    {
        $user = $request->user();

        // Pastikan relasi ada di model User:
        // dailyReports(), leaveRequests(), pendingTasks()
        $reports = $user->dailyReports()
            ->orderByDesc('date')
            ->get();

        $leaveRequests = $user->leaveRequests()
            ->orderByDesc('leave_date')
            ->get();

        $pendingTasks = $user->pendingTasks()
            ->orderByDesc('created_at')
            ->get();

        return view('user.riwayat-magang', compact('reports', 'leaveRequests', 'pendingTasks'));
    }

    /**
     * Pastikan direktori publik ada di disk 'public'.
     */
    private function ensurePublicDir(string $dir): void
    {
        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }
    }


    public function __construct()
    {
        // Wajib login untuk semua aksi di controller ini
        $this->middleware('auth');

        // (Opsional) kalau pakai email verification:
        // $this->middleware('verified')->only(['downloadSKL','generateLOA']);
    }

    /**
     * Helper: pastikan user berhak akses dokumen kelulusan.
     * Syarat: role = pemagang, owner dari IR, status = completed.
     */
    private function ensureCanAccessCompletedDocs(User $user, IR $intern): void
    {
        // Pastikan data milik user yang login
        if ((int) $intern->user_id !== (int) $user->id) {
            Log::warning('Dokumen ditolak: bukan pemilik data.', [
                'actor_id' => $user->id,
                'intern_id' => $intern->id,
            ]);
            abort(403, 'Anda tidak berhak mengakses dokumen ini.');
        }

        // Hanya role pemagang
        if ($user->role !== 'pemagang') {
            Log::warning('Dokumen ditolak: role bukan pemagang.', [
                'actor_id' => $user->id,
                'role' => $user->role,
            ]);
            abort(403, 'Hanya pemagang yang dapat mengakses dokumen ini.');
        }

        // Status harus completed
        $isCompleted = $intern->internship_status === 'completed'
            || (defined(IR::class.'::STATUS_COMPLETED') && $intern->internship_status === IR::STATUS_COMPLETED);

        if (!$isCompleted) {
            Log::info('Dokumen ditolak: status belum completed.', [
                'actor_id' => $user->id,
                'intern_id' => $intern->id,
                'status' => $intern->internship_status,
            ]);
            abort(403, 'Dokumen hanya tersedia jika status magang sudah completed.');
        }
    }

    /**
     * Download SKL (PDF)
     */
    public function downloadSKL(Request $request)
    {
        $request->validate(['intern_id' => 'required|integer']);

        $user = $request->user();

        // Ambil data IR (Internship Registration)
        $intern = IR::where('id', $request->intern_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // 🔹 Ambil konfigurasi SKL dari database (yang diubah admin)
        $sklConfig = SKLSetting::first();

        // Jika belum ada data di DB → fallback default
        $companyName    = $sklConfig->company_name    ?? 'Seven Inc';
        $companyAddress = $sklConfig->company_address ?? 'Jl. Raya Teknologi No. 17, Jakarta';
        $companyCity    = $sklConfig->company_city    ?? 'Jakarta';
        $leaderName     = $sklConfig->leader_name     ?? 'Nama Pimpinan / HRD';
        $leaderTitle    = $sklConfig->leader_title    ?? 'Manajer HRD';

        // 🔹 Logo & stempel (cek database dulu, baru fallback)
        $logoPathDB  = $sklConfig->logo_path  ?? 'storage/images/logos/logo_seveninc.png';
        $stampPathDB = $sklConfig->stamp_path ?? 'storage/images/logos/stamp.png';

        $logoPathAbs  = public_path($logoPathDB);
        $stampPathAbs = public_path($stampPathDB);

        $logoPath  = file_exists($logoPathAbs)  ? $logoPathAbs  : public_path('storage/images/logos/logo_seveninc.png');
        $stampPath = file_exists($stampPathAbs) ? $stampPathAbs : public_path('storage/images/logos/stamp.png');

        $letterNumber = 'SKL/' . now()->format('Y') . '/' . ($intern->id ?? 'XXX');

        try {
            // 🔹 Render ke PDF
            $pdf = Pdf::loadView('user.skl', [
                'intern'         => $intern,
                'user'           => $user,
                'companyName'    => $companyName,
                'companyAddress' => $companyAddress,
                'leaderName'     => $leaderName,
                'leaderTitle'    => $leaderTitle,
                'letterNumber'   => $letterNumber,
                'city'           => $companyCity,
                'logoPath'       => $logoPath,
                'stampPath'      => $stampPath,
            ])->setPaper('A4', 'portrait');

            // 🔹 Simpan PDF
            $safeName = Str::slug($intern->fullname ?? $user->name, '-');
            $fileName = 'SKL-'.$intern->id.'-'.$safeName.'-'.now()->format('Ymd_His').'.pdf';
            $dir = 'documents/skl';
            Storage::disk('public')->makeDirectory($dir);

            $path = $dir.'/'.$fileName;
            Storage::disk('public')->put($path, $pdf->output());
            $publicUrl = asset('storage/'.$path);

            return back()->with([
                'success' => '✅ SKL berhasil dibuat!',
                'skl_url' => $publicUrl
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal generate SKL', [
                'error' => $e->getMessage(),
                'intern_id' => $intern->id,
                'user_id' => $user->id,
            ]);
            return back()->with('error', 'Gagal membuat SKL. Silakan coba lagi atau hubungi admin.');
        }
    }




    

    // Tambahkan helper ini di dalam class UserController (private):
    private function assertPemagangOrAbort($user): void
    {
        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }
        if ($user->role !== 'pemagang') {
            abort(403, 'Hanya pemagang yang diizinkan mengakses fitur ini.');
        }
    }

    /* =========================
    * DAILY REPORTS
    * ========================= */

    // Menampilkan laporan harian
    public function dailyReport(Request $request)
    {
        $user = $request->user();
        $this->assertPemagangOrAbort($user);

        // paginate 10, urut terbaru di atas
        $reports = $user->dailyReports()
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('user.dailyReport', compact('reports'));
    }

    // Menyimpan laporan harian
    public function storeDailyReport(Request $request)
    {
        $user = $request->user();
        $this->assertPemagangOrAbort($user);

        $validated = $request->validate([
            'date'       => ['required', 'date'],
            'activities' => ['required', 'string', 'max:1000'],
            'challenges' => ['required', 'string', 'max:1000'],
        ], [
            'date.required'       => 'Tanggal wajib diisi.',
            'date.date'           => 'Format tanggal tidak valid.',
            'activities.required' => 'Aktivitas wajib diisi.',
            'challenges.required' => 'Tantangan wajib diisi.',
        ]);

        // Cegah duplikasi report pada tanggal yang sama untuk user ini
        $already = $user->dailyReports()->whereDate('date', $validated['date'])->exists();
        if ($already) {
            return back()
                ->withInput()
                ->with('error', 'Laporan untuk tanggal tersebut sudah pernah dikirim.');
        }

        $user->dailyReports()->create($validated);

        return redirect()
            ->route('user.dailyReport')
            ->with('success', 'Laporan harian berhasil dikirim.');
    }

    /* =========================
    * LEAVE REQUESTS
    * ========================= */

    // Menampilkan permintaan izin
    public function leaveRequest(Request $request)
    {
        $user = $request->user();
        $this->assertPemagangOrAbort($user);

        $leaveRequests = $user->leaveRequests()
            ->orderByDesc('leave_date')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('user.leaveRequest', compact('leaveRequests'));
    }

    // Menyimpan permintaan izin
    public function storeLeaveRequest(Request $request)
    {
        $user = $request->user();
        $this->assertPemagangOrAbort($user);

        $validated = $request->validate([
            'leave_type' => ['required', 'string', 'max:50'],
            'leave_date' => ['required', 'date'],
            'reason'     => ['required', 'string', 'max:1000'],
        ], [
            'leave_type.required' => 'Jenis izin wajib diisi.',
            'leave_date.required' => 'Tanggal izin wajib diisi.',
            'leave_date.date'     => 'Format tanggal izin tidak valid.',
            'reason.required'     => 'Alasan izin wajib diisi.',
        ]);

        $user->leaveRequests()->create($validated);

        return redirect()
            ->route('user.leaveRequest')
            ->with('success', 'Permintaan izin berhasil diajukan.');
    }

    /* =========================
    * PENDING TASKS
    * ========================= */

    // Menampilkan tugas yang tertunda
    public function pendingTasks(Request $request)
    {
        $user = $request->user();
        $this->assertPemagangOrAbort($user);

        $pendingTasks = $user->pendingTasks()
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('user.pendingTasks', compact('pendingTasks'));
    }

    // Menyimpan tugas pending
    public function storePendingTask(Request $request)
    {
        $user = $request->user();
        $this->assertPemagangOrAbort($user);

        $validated = $request->validate([
            'task_title'       => ['required', 'string', 'max:255'],
            'task_description' => ['required', 'string', 'max:1000'],
        ], [
            'task_title.required'       => 'Judul tugas wajib diisi.',
            'task_description.required' => 'Deskripsi tugas wajib diisi.',
        ]);

        $user->pendingTasks()->create([
            'title'       => $validated['task_title'],
            'description' => $validated['task_description'],
        ]);

        return redirect()
            ->route('user.pendingTasks')
            ->with('success', 'Tugas pending berhasil ditambahkan.');
    }


    public function userActive(Request $request)
    {
        // Ambil data pengguna yang sedang login
        $user = auth()->user();

        // Ambil data magang terakhir berdasarkan user_id dan pastikan statusnya "active"
        $internship = IR::where('user_id', $user->id)
            ->latest()
            ->first(); // Mengambil magang terakhir berdasarkan user_id

        // Jika data magang ada dan statusnya "active"
        if ($internship && $internship->internship_status === IR::STATUS_ACTIVE) {
            return view('user.dashboard-active', compact('user', 'internship'));
        }

        // Jika tidak aktif, arahkan ke halaman lain atau beri pesan
        return redirect()->route('user.dashboard')
            ->with('error', 'Magang Anda tidak aktif.');
    }

    // Fungsi untuk menampilkan halaman dashboard completed
    public function userCompleted()
    {
        // Mengambil data magang terakhir yang terhubung dengan pengguna yang sedang login
        $user = auth()->user();

        // Mengambil data magang terakhir untuk user yang login
        $internship = IR::where('user_id', $user->id)
            ->latest()
            ->first(); // Mengambil magang terakhir berdasarkan user_id

        $internship_status = $internship ? $internship->internship_status : null;

        // Kirim data magang ke view
        return view('user.dashboard-completed', compact('user', 'internship', 'internship_status'));
    }

    // Menampilkan halaman profile user
    public function editUserProfile()
    {
        // Ambil user yang sedang login
        $user = User::find(auth()->id());

        // Pastikan user ditemukan
        if (!$user) {
            return redirect()->route('user.profile')->with('error', 'User tidak ditemukan.');
        }

        return view('user.profile', compact('user')); // Kirim data user ke view
    }

    // Update profil user
    public function updateUserProfile(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . auth()->id(), // Menambahkan unique dengan pengecualian
            'phone_number' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // Validasi gambar
        ]);

        // Ambil user yang sedang login
        $user = User::find(auth()->id());

        // Pastikan user ditemukan
        if (!$user) {
            return redirect()->route('user.profile')->with('error', 'User tidak ditemukan.');
        }

        // Update name, email, dan phone_number jika ada
        if (!empty($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (!empty($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (!empty($validated['phone_number'])) {
            $user->phone_number = $validated['phone_number'];
        }

        // Jika ada password baru, update password
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Cek apakah ada gambar profil yang diunggah
        if ($request->hasFile('profile_picture')) {
            // Hapus gambar lama jika ada
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            // Simpan gambar profil di folder public/images/profile-pictures/
            $imagePath = $request->file('profile_picture')->store('images/profile-pictures', 'public');
            $user->profile_picture = $imagePath;
        }

        // Simpan semua perubahan data user
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Akun berhasil diperbarui!');
    }



    // Menampilkan form login pengguna biasa
    public function showLoginForm()
    {
        return view('auth.user-login');
    }

    // Menampilkan dashboard pengguna biasa
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Ambil pendaftaran terakhir (boleh null)
        $registration = \App\Models\InternshipRegistration::where('user_id', $userId)
            ->latest('id')
            ->first();

        // Jika belum mengisi form pendaftaran, arahkan ke dashboard
        if (!$registration) {
            return view('user.dashboard'); // Redirect ke halaman dashboard
        }

        // Cek apakah tanggal mulai magang sudah lewat, jika iya ubah status menjadi aktif
        if ($registration->start_date && Carbon::parse($registration->start_date)->isToday()) {
            $registration->update(['internship_status' => 'active']);
        }

        // Jika status waiting atau accepted -> langsung ke halaman edit profile
        if (in_array($registration->internship_status, [
            \App\Models\InternshipRegistration::STATUS_WAITING,
            \App\Models\InternshipRegistration::STATUS_ACCEPTED,
        ], true)) {
            return view('user.edit-profile', ['registration' => $registration]);
        }

        // Jika status aktif, arahkan ke dashboard pengguna
        if ($registration->internship_status === \App\Models\InternshipRegistration::STATUS_ACTIVE) {
            return view('user.dashboard-active', ['registration' => $registration]);
        }

        // Default: render dashboard biasa jika status lainnya
        return view('user.dashboard', ['registration' => $registration]);
    }

    // Menampilkan form pendaftaran magang
    public function showForm()
    {
        return view('user.form'); // Mengarahkan ke halaman form pendaftaran magang
    }
}
