<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InternshipRegistration as IR;
use App\Models\DailyReport;
use App\Models\LeaveRequest;
use App\Models\PendingTask;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{

    /**
     * GET /admin/user/{user}/daily-reports
     * Tampilkan semua Daily Report milik user (dengan filter optional).
     */
    public function showReports(User $user, Request $request)
    {
        // Optional: otorisasi admin
        $this->authorize('viewAny', DailyReport::class);

        $q         = trim($request->get('q', ''));
        $from      = $request->get('from');   // format: Y-m-d
        $to        = $request->get('to');     // format: Y-m-d

        $reports = DailyReport::with('user')
            ->where('user_id', $user->id)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('activities', 'like', "%{$q}%")
                       ->orWhere('challenges', 'like', "%{$q}%");
                });
            })
            ->when($from, fn($query) => $query->whereDate('date', '>=', $from))
            ->when($to,   fn($query) => $query->whereDate('date', '<=', $to))
            ->orderByDesc('date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.reports', [
            'user'    => $user,
            'reports' => $reports,
            'filters' => compact('q', 'from', 'to'),
        ]);
    }

    /**
     * GET /admin/user/{user}/leave-requests
     * Tampilkan semua Leave Request milik user (dengan filter optional).
     */
    public function showLeaves(User $user, Request $request)
    {
        // Optional: otorisasi admin
        $this->authorize('viewAny', LeaveRequest::class);

        $q         = trim($request->get('q', ''));
        $from      = $request->get('from');      // Y-m-d
        $to        = $request->get('to');        // Y-m-d
        $type      = $request->get('type');      // sick/personal/other (opsional)
        $status    = $request->get('status');    // kalau kamu punya kolom status (approved/pending/rejected)

        $leaves = LeaveRequest::with('user')
            ->where('user_id', $user->id)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('reason', 'like', "%{$q}%")
                       ->orWhere('leave_type', 'like', "%{$q}%");
                });
            })
            ->when($from,   fn($query) => $query->whereDate('leave_date', '>=', $from))
            ->when($to,     fn($query) => $query->whereDate('leave_date', '<=', $to))
            ->when($type,   fn($query) => $query->where('leave_type', $type))
            ->when($status, fn($query) => $query->where('status', $status)) // hapus jika tidak ada kolom status
            ->orderByDesc('leave_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.leaves', [
            'user'   => $user,
            'leaves' => $leaves,
            'filters'=> compact('q', 'from', 'to', 'type', 'status'),
        ]);
    }

    /**
     * GET /admin/user/{user}/pending-tasks
     * Tampilkan semua Pending Task milik user (dengan filter optional).
     */
    public function showTasks(User $user, Request $request)
    {
        // Optional: otorisasi admin
        $this->authorize('viewAny', PendingTask::class);

        $q      = trim($request->get('q', ''));
        $from   = $request->get('from');   // Y-m-d
        $to     = $request->get('to');     // Y-m-d

        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $tasks */
        $tasks = PendingTask::with('user')
            ->where('user_id', $user->id)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->when($from, fn($query) => $query->whereDate('created_at', '>=', $from))
            ->when($to,   fn($query) => $query->whereDate('created_at', '<=', $to))
            ->latest()
            ->paginate(15);

        $tasks->appends($request->query());
        
        return view('admin.users.tasks', [
            'user'  => $user,
            'tasks' => $tasks,
            'filters' => compact('q', 'from', 'to'),
        ]);
    }

    public function index()
    {
        $allReports = DailyReport::with('user')->latest('date')->get();
        $allLeaves  = LeaveRequest::with('user')->latest('leave_date')->get();
        $allTasks   = PendingTask::with('user')->latest()->get();

        // Ringkasan hitungan (berdasarkan workflow status)
        $counts = [
            'waiting'   => IR::where('internship_status', IR::STATUS_WAITING)->count(),
            'active'    => IR::where('internship_status', IR::STATUS_ACTIVE)->count(),
            'completed' => IR::where('internship_status', IR::STATUS_COMPLETED)->count(),
            'exited'    => IR::where('internship_status', IR::STATUS_EXITED)->count(),
            'pending'   => IR::where('internship_status', IR::STATUS_PENDING)->count(),
        ];

        // ===========================
        // Line chart: total pendaftar per bulan (6 bulan terakhir, termasuk bulan ini)
        // ===========================
        $end   = Carbon::now()->endOfMonth();
        $start = (clone $end)->subMonths(5)->startOfMonth();

        // Buat list bulan untuk label & kunci (YYYY-MM)
        $months = collect();
        for ($d = $start->copy(); $d <= $end; $d->addMonth()) {
            $months->push($d->copy());
        }
        $labels = $months->map->format('M Y')->all();   // contoh: ['Mar 2025', ...]
        $keys   = $months->map->format('Y-m')->all();   // contoh: ['2025-03', ...]

        // Grouping total pendaftar per bulan berdasarkan created_at
        $grouped = IR::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as c")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('ym')
            ->pluck('c', 'ym') // ['2025-03' => 12, ...]
            ->all();

        // Susun sesuai urutan bulan, isi 0 jika tidak ada
        $totals = array_map(fn ($k) => $grouped[$k] ?? 0, $keys);

        $chart = [
            'labels' => $labels,
            'total'  => $totals,
        ];

        $users = \App\Models\User::with('internshipRegistration')
        ->latest()
        ->limit(10) // tampilkan 10 terbaru (ubah sesuai kebutuhan)
        ->get();

        $me = auth()->user();

        $reportsMine = $me->dailyReports()->exists()
            ? $me->dailyReports()->orderByDesc('date')->limit(5)->get()
            : DailyReport::orderByDesc('date')->limit(5)->get();

        $leaveMine = $me->leaveRequests()->exists()
            ? $me->leaveRequests()->orderByDesc('leave_date')->limit(5)->get()
            : LeaveRequest::orderByDesc('leave_date')->limit(5)->get();

        $tasksMine = $me->pendingTasks()->exists()
            ? $me->pendingTasks()->latest()->limit(5)->get()
            : PendingTask::latest()->limit(5)->get();

        return view('dashboard.index', compact('counts', 'chart', 'users', 'allReports', 'allLeaves', 'allTasks'));
    }
}
