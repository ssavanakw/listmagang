<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentDownload;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Tampilkan daftar LOA yang diunduh
     */
    public function listLoas(Request $request)
    {
        // Ambil data LOA yang diunduh oleh pengguna
        $loas = DocumentDownload::where('doc_type', DocumentDownload::TYPE_LOA)
            ->with('user') // Relasi dengan user
            ->latest('downloaded_at')
            ->paginate(10);

        return view('admin.documents.loas', compact('loas'));
    }

    /**
     * Tampilkan daftar SKL yang diunduh
     */
    public function listSkls(Request $request)
    {
        // Ambil data SKL yang diunduh oleh pengguna
        $skls = DocumentDownload::where('doc_type', DocumentDownload::TYPE_SKL)
            ->with('user') // Relasi dengan user
            ->latest('downloaded_at')
            ->paginate(10);

        return view('admin.documents.skls', compact('skls'));
    }
}
