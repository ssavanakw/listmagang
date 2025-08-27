<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan form login admin
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // cek role admin
            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('dashboard.index'));
            }

            Auth::logout();
            return back()->with('error', 'Anda tidak memiliki akses sebagai admin.');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Berhasil logout.');
    }
}
