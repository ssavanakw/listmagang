<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show registration form
    public function showRegisterForm()
    {
        if (auth()->check()) {
            return redirect()->route('user.dashboard'); // Redirect ke dashboard jika sudah login
        }
        return view('auth.admin-register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('user.login')
            ->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
    }

    /**
     * Tampilkan form login admin
     */
    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect()->route('user.dashboard'); // Redirect ke dashboard jika sudah login
        }
        return view('auth.admin-login');
    }


    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        // Validasi kredensial login
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Proses login menggunakan kredensial
        if (auth()->attempt($credentials, $request->boolean('remember'))) {
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Cek apakah pengguna adalah admin
            if (auth()->user()->role === 'admin') {
                // Jika admin, arahkan ke admin dashboard
                return redirect()->route('admin.dashboard.index');
            }

            // Cek status pendaftaran magang
            $userId = auth()->id();
            $registration = \App\Models\InternshipRegistration::where('user_id', $userId)
                ->latest('id')
                ->first();

            if ($registration) {
                // Cek status magang
                if (auth()->user()->role === 'pemagang' && $registration->internship_status === 'active') {
                    return redirect()->route('user.dashboard-active');
                } elseif (auth()->user()->role === 'pemagang' && $registration->internship_status === 'completed') {
                    return redirect()->route('user.dashboard.completed');
                }
            }

            // Jika belum mengisi form pendaftaran magang
            return redirect()->route('user.dashboard');
        }

        // Jika login gagal
        return back()->with('error', 'Email atau password salah!')->onlyInput('email');
    }





    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session to prevent session hijacking
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect back to login page after logout
        return redirect()->route('user.login')->with('success', 'Berhasil logout.');
    }
}
