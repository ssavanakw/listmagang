<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InternshipRegistration as IR;

class UserController extends Controller
{
    // Menampilkan form login pengguna biasa
    public function showLoginForm()
    {
        return view('auth.user-login');
    }

    // Menangani login pengguna biasa
    public function login(Request $request)
    {
        // Proses login
    }

    // Menampilkan dashboard pengguna biasa
    public function index()
    {
        $userId = auth()->id();

        // Cek langsung di DB untuk mendapatkan status terakhir
        $registration = IR::where('user_id', $userId)->latest('id')->first();

        // Jika belum mengisi form, arahkan ke halaman form
        if (!$registration) {
            return redirect()->route('internship.form');
        }

        // Jika sudah diterima, tampilkan dashboard pengguna
        if ($registration->internship_status === IR::STATUS_ACCEPTED) {
            return view('user.dashboard', [
                'registration' => $registration,
            ]);
        }

        // Jika belum diterima, tampilkan halaman edit-profile
        return view('user.edit-profile', [
            'registration' => $registration,
        ]);
    }

}
