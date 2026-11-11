<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportDisposition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $userId = auth()->id();

        $query = Report::with(['user', 'subDepartment', 'evidences'])
            ->where('handler_id', $userId);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(10);

        $masuk = Report::where('handler_id', $userId)->where('status', 'diterima')->count();
        $dikerjakan = Report::where('handler_id', $userId)->where('status', 'proses')->count();
        $verifikasi = Report::where('handler_id', $userId)->where('status', 'verifikasi_kabag')->count();
        $selesai = Report::where('handler_id', $userId)->where('status', 'selesai')->count();
        $tertunda = Report::where('handler_id', $userId)
            ->where(function ($q) {
                $q->where('status', 'ditolak')
                  ->orWhere('execution_result', 'tertunda');
            })->count();

        $counts = [
            'masuk' => $masuk,
            'dikerjakan' => $dikerjakan,
            'proses' => $dikerjakan,
            'verifikasi' => $verifikasi,
            'selesai' => $selesai,
            'tertunda' => $tertunda,
            'perlu_penanganan' => $masuk,
            'dalam_penanganan' => $dikerjakan,
        ];

        $totalReports = $selesai + $masuk + $dikerjakan + $tertunda;

        $todayNewCount = Report::where('handler_id', $userId)
            ->whereDate('updated_at', today())
            ->count();

        $recentNotes = ReportDisposition::whereHas('report', function ($q) use ($userId) {
                $q->where('handler_id', $userId);
            })
            ->whereNotNull('notes')
            ->where('notes', '!=', '')
            ->with(['report', 'sender.subDepartment'])
            ->latest()
            ->take(5)
            ->get();

        $recentActivities = Report::where('handler_id', $userId)
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact(
            'reports',
            'counts',
            'totalReports',
            'todayNewCount',
            'recentNotes',
            'recentActivities'
        ));
    }

    public function myReports(): View
    {
        $reports = Report::with(['user', 'subDepartment', 'evidences'])
            ->where('handler_id', auth()->id())
            ->where('status', 'proses')
            ->latest()
            ->get();

        return view('petugas.my_reports', compact('reports'));
    }

    public function history(): View
    {
        $reports = Report::with(['user', 'subDepartment'])
            ->where('handler_id', auth()->id())
            ->whereIn('status', ['verifikasi_kabag', 'selesai'])
            ->latest()
            ->paginate(15);

        return view('petugas.history', compact('reports'));
    }
}
