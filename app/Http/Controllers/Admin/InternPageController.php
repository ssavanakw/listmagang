<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class InternPageController extends Controller
{
    // Hanya return view — data akan di-load via JS dari API
    public function index()
    {
        return view('interns.index', ['title' => 'Semua Pemagang', 'scope' => 'all']);
    }


    public function active()
    {
        return view('interns.index', ['title' => 'Pemagang Aktif', 'scope' => 'active']);
    }

    public function completed()
    {
        return view('interns.index', ['title' => 'Pemagang Selesai', 'scope' => 'completed']);
    }

    public function exited()
    {
        return view('interns.index', ['title' => 'Pemagang Keluar', 'scope' => 'exited']);
    }

    public function pending()
    {
        return view('interns.index', ['title' => 'Pemagang Pending', 'scope' => 'pending']);
    }
}
