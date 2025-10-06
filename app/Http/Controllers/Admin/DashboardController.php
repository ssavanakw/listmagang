<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipRegistration as IR;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
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

        return view('dashboard.index', compact('counts', 'chart'));
    }
}
