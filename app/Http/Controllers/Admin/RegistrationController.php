<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipRegistration as IR;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Tabel Pendaftar (versi admin, pakai layout dashboard).
     * Mendukung pencarian sederhana melalui query ?q=
     * dan pengaturan per_page (?per_page=20).
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $perPage = (int) $request->get('per_page', 20);
        if ($perPage < 10 || $perPage > 100) {
            $perPage = 20;
        }

        $query = IR::query()
            ->when($q !== '', function ($qBuilder) use ($q) {
                $qBuilder->where(function ($qq) use ($q) {
                    $qq->where('fullname', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%")
                       ->orWhere('student_id', 'like', "%{$q}%")
                       ->orWhere('institution_name', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('created_at');

        $registrations = $query->paginate($perPage)->withQueryString();

        return view('admin.registrations.index', compact('registrations', 'q', 'perPage'));
    }
}
