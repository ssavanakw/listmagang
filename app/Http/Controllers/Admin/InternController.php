<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipRegistration as IR;
use Illuminate\Http\Request;

class InternController extends Controller
{
    private function table(Request $request, $query, string $title, string $scope)
    {
        $query->when($request->filled('q'), function ($q) use ($request) {
            $s = trim($request->get('q'));
            $q->where(function ($qq) use ($s) {
                $qq->where('fullname', 'like', "%{$s}%")
                   ->orWhere('email', 'like', "%{$s}%");
            });
        });

        $interns = $query->paginate(15)->withQueryString();
        return view('interns.index', compact('interns', 'title', 'scope'));
    }

    public function index(Request $request)
    {
        return $this->table(
            $request,
            IR::query()->orderByDesc('created_at'),
            'Semua Pemagang',
            'all'
        );
    }

    public function active(Request $request)
    {
        return $this->table(
            $request,
            IR::where('internship_status', IR::STATUS_ACTIVE)->orderByDesc('updated_at'),
            'Pemagang Aktif',
            'active'
        );
    }

    // ✅ Baru: selesai saja
    public function completed(Request $request)
    {
        return $this->table(
            $request,
            IR::where('internship_status', IR::STATUS_COMPLETED)->orderByDesc('updated_at'),
            'Pemagang Selesai',
            'completed'
        );
    }

    // ✅ Baru: keluar saja
    public function exited(Request $request)
    {
        return $this->table(
            $request,
            IR::where('internship_status', IR::STATUS_EXITED)->orderByDesc('updated_at'),
            'Pemagang Keluar',
            'exited'
        );
    }

    public function pending(Request $request)
    {
        return $this->table(
            $request,
            IR::where('internship_status', IR::STATUS_PENDING)->orderByDesc('created_at'),
            'Pemagang Pending',
            'pending'
        );
    }
    public function updateStatus(Request $request, IR $intern)
    {
        $validated = $request->validate([
            'internship_status' => 'required|in:new,active,completed,exited,pending',
        ]);

        $intern->internship_status = $validated['internship_status'];
        $intern->save();

        // AJAX -> JSON, non-AJAX -> back
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('success', 'Status pemagang diperbarui.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'ids'               => 'required|array|min:1',
            'ids.*'             => 'integer|exists:internship_registrations,id',
            'internship_status' => 'required|in:new,active,completed,exited,pending',
        ]);

        $affected = IR::whereIn('id', $validated['ids'])
            ->update(['internship_status' => $validated['internship_status']]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'affected' => $affected]);
        }
        return back()->with('success', "Status {$affected} pemagang diperbarui.");
    }


}
