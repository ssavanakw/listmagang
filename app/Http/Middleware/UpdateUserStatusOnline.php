<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UpdateUserStatusOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Pastikan pengguna terautentikasi
        if (Auth::check()) {
            // Cast Auth::user() menjadi User model
            $user = Auth::user(); // Mengambil user yang sedang login

            // Pastikan user adalah instance dari model User
            if ($user instanceof User) {
                // Update status online pengguna
                $user->is_online = true;
                $user->save(); // Memperbarui status 'is_online'
            }
        }

        return $next($request);
    }
}
