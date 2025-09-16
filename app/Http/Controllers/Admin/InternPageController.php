<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipRegistration as IR;

class InternPageController extends Controller
{
    // Semua pemagang
    public function index()
    {
        return view('interns.index', [
            'interns' => IR::orderByDesc('created_at')->get(),
            'title'   => 'Semua Pemagang',
            'scope'   => 'all',
        ]);
    }

    // Pemagang aktif
    public function active()
    {
        $interns = IR::where('internship_status', IR::STATUS_ACTIVE)
            ->orderByDesc('start_date')
            ->get();

        return view('interns.index', [
            'interns' => $interns,
            'title'   => 'Pemagang Aktif',
            'scope'   => 'active',
        ]);
    }

    // Pemagang selesai
    public function completed()
    {
        $interns = IR::where('internship_status', IR::STATUS_COMPLETED)
            ->orderByDesc('end_date')
            ->get();

        return view('interns.index', [
            'interns' => $interns,
            'title'   => 'Pemagang Selesai',
            'scope'   => 'completed',
        ]);
    }

    // Pemagang keluar
    public function exited()
    {
        $interns = IR::where('internship_status', IR::STATUS_EXITED)
            ->orderByDesc('updated_at')
            ->get();

        return view('interns.index', [
            'interns' => $interns,
            'title'   => 'Pemagang Keluar',
            'scope'   => 'exited',
        ]);
    }

    // Pemagang pending
    public function pending()
    {
        $interns = IR::where('internship_status', IR::STATUS_PENDING)
            ->orderByDesc('created_at')
            ->get();

        return view('interns.index', [
            'interns' => $interns,
            'title'   => 'Pemagang Pending',
            'scope'   => 'pending',
        ]);
    }
}
