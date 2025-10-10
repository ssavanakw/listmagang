<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InternshipRegistration as IR;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
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
