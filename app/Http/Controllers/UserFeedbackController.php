<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserFeedbackController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'feedback' => 'required|string|min:5|max:1000',
        ]);

        $user = Auth::user();

        // Simpan ke tabel feedback (buat tabelnya dulu jika belum ada)
        DB::table('user_feedback')->insert([
            'user_id'   => $user->id,
            'feedback'  => $request->feedback,
            'created_at'=> now(),
        ]);

        return back()->with('success', 'Terima kasih atas umpan balik Anda!');
    }
}
