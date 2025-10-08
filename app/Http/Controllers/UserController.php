<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InternshipRegistration as IR;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan halaman profile user
    public function editProfile()
    {
        return view('user.profile'); // Menampilkan halaman profile
    }

    // Update profil user
    public function updateProfile(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // Validasi gambar
        ]);

        // Ambil user yang sedang login
        /** @var \App\Models\User $user */ // <-- Tambahkan baris ini
        $user = auth()->user();

        // Pastikan user ditemukan
        if (!$user) {
            return redirect()->route('user.profile')->with('error', 'User tidak ditemukan.');
        }

        // Update data user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'];

        // Jika ada password baru, update password
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        // Cek apakah ada gambar profil yang diunggah
        if ($request->hasFile('profile_picture')) {
            // Simpan gambar profil di folder public/images/profile-pictures/
            $imagePath = $request->file('profile_picture')->store('images/profile-pictures', 'public');
            $user->profile_picture = $imagePath;
        }

        // Simpan perubahan data user
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
