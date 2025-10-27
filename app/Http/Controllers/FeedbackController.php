<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = DB::table('feedback')->join('users', 'feedback.user_id', '=', 'users.id')
            ->select('feedback.id', 'feedback.feedback', 'users.name', 'feedback.created_at')
            ->get();
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function edit($id)
    {
        $feedback = DB::table('feedback')->where('id', $id)->first();
        return view('admin.feedback.edit', compact('feedback'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string|min:5|max:1000',
        ]);

        DB::table('feedback')->where('id', $id)->update([
            'feedback' => $request->feedback,
            'updated_at' => now(),
        ]);

        return redirect()->route('feedback.index')->with('success', 'Feedback berhasil diperbarui!');
    }

    public function destroy($id)
    {
        DB::table('feedback')->where('id', $id)->delete();
        return redirect()->route('feedback.index')->with('success', 'Feedback berhasil dihapus!');
    }



    public function submit(Request $request)
    {
        $request->validate([
            'feedback' => 'required|string|min:5|max:1000',
        ]);

        $user = Auth::user();

        // Simpan ke tabel feedback (buat tabelnya dulu jika belum ada)
        DB::table('feedback')->insert([
            'user_id'   => $user->id,
            'feedback'  => $request->feedback,
            'created_at'=> now(),
        ]);

        return back()->with('success', 'Terima kasih atas umpan balik Anda!');
    }
}
