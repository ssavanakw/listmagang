<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    // Menampilkan semua pengguna
    public function index(Request $request)
    {
        $query = User::query();

        // filter per kolom
        if ($name = $request->get('name')) {
            $query->where('name', 'like', "%$name%");
        }

        if ($email = $request->get('email')) {
            $query->where('email', 'like', "%$email%");
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        // sorting
        switch ($request->get('sort')) {
            case 'name_asc':   $query->orderBy('name', 'asc'); break;
            case 'name_desc':  $query->orderBy('name', 'desc'); break;
            case 'email_asc':  $query->orderBy('email', 'asc'); break;
            case 'email_desc': $query->orderBy('email', 'desc'); break;
            case 'role_asc':   $query->orderBy('role', 'asc'); break;
            case 'role_desc':  $query->orderBy('role', 'desc'); break;
            case 'status_asc': $query->orderBy('is_online', 'asc'); break;   // offline dulu
            case 'status_desc':$query->orderBy('is_online', 'desc'); break;  // online dulu
            default:
                // ✅ Default: Online dulu
                $query->orderBy('is_online', 'desc')->orderBy('name', 'asc');
        }


        $users = $query->paginate(10)->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    // Mengedit data pengguna
    public function edit($id)
    {
        $user = User::findOrFail($id); // Cari pengguna berdasarkan ID
        return view('admin.users.edit', compact('user')); // Tampilkan form edit
    }

    // Menghapus data pengguna
    public function destroy($id)
    {
        $user = User::find($id); // Cari pengguna berdasarkan ID
        if ($user) {
            $user->delete(); // Hapus pengguna
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        }
        return redirect()->route('admin.users.index')->with('error', 'User not found.');
    }

}
