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
            return redirect()->route('user.dashboard'); // Redirect ke dashboard user jika sudah login
        }
        return view('auth.admin-register');
    }

    // Handle registration
    public function register(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',  // Set role as user for this example
        ]);

        // Login after successful registration
        Auth::login($user);

        // Redirect to user dashboard if the user has 'user' role, or to internship form
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang Admin.');
        }

        // Redirect to internship form for new user
        return redirect()->route('internship.form')
            ->with('success', 'Registrasi berhasil! Silakan lengkapi form pendaftaran.');
    }

    /**
     * Tampilkan form login admin
     */
    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect()->route('user.dashboard'); // Redirect ke dashboard user jika sudah login
        }
        return view('auth.admin-login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        // Validate the login form data
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        // Attempt to log the user in
        if (auth()->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect based on the user role
            return auth()->user()->role === 'admin'
                ? redirect()->route('admin.dashboard.index')     // admin
                : redirect()->route('user.dashboard');     // user
        }

        // If login fails, return back with error
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
