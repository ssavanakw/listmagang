<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Belum login → lempar ke admin.login (named route yang kita buat)
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Sudah login tapi role tidak cocok → arahkan ke dashboard user biasa
        if (Auth::user()->role !== $role) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
