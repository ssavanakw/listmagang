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

        // Cek langsung di DB supaya pasti
        $registration = IR::where('user_id', $userId)->latest('id')->first();

        // Belum pernah isi → paksa user ke halaman form
        if (!$registration) {
            return redirect()->route('internship.form');
        }

        // Sudah submit → tampilkan halaman submitted
        return view('user.edit-profile', [
            'registration' => $registration,
        ]);
    }
}
