<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\SubDepartment;
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

        $recentNotes = ReportDisposition::with(['sender.subDepartment', 'report'])
            ->where(function ($q) use ($userId) {
                $q->where('receiver_id', $userId)
                  ->orWhere(function ($sq) use ($userId) {
                      $sq->whereNull('receiver_id')
                         ->whereHas('report', fn($rq) => $rq->where('handler_id', $userId))
                         ->whereHas('sender', fn($uq) => $uq->whereIn('role', ['kabag', 'kasubag']));
                  });
            })
            ->where('sender_id', '!=', $userId)
            ->whereNotNull('notes')
            ->where('notes', '!=', '')
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

    public function show(Report $report): View
    {
        $userId = auth()->id();
        $userSubDeptId = auth()->user()->sub_department_id;

        if ($report->handler_id && $report->handler_id !== $userId) {
            abort(403, 'Anda bukan petugas yang ditugaskan untuk laporan ini.');
        }

        if (! $report->handler_id && $report->sub_department_id !== $userSubDeptId) {
            abort(403, 'Laporan ini berada di luar penugasan unit Anda.');
        }

        $report->load([
            'user',
            'subDepartment',
            'handler.subDepartment',
            'dispositions.sender.subDepartment',
            'dispositions.receiver.subDepartment',
            'evidences',
        ]);

        $subDepartments = SubDepartment::orderBy('name')->get();
        $kasubags = collect();
        $petugasKabag = collect();
        $petugas = collect();

        $isPetugas = true;
        $isKasubag = false;

        return view('kabag.detail_Kabag', compact(
            'report',
            'subDepartments',
            'kasubags',
            'petugasKabag',
            'petugas',
            'isPetugas',
            'isKasubag'
        ));
    }

    public function laporanMasuk(Request $request): View
    {
        $userId = auth()->id();

        $query = Report::with(['user', 'handler', 'subDepartment'])
            ->where('handler_id', $userId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'all') {
                // Tampilkan semua status
            } elseif ($request->status === 'masuk' || $request->status === 'diterima' || $request->status === 'didisposisi') {
                $query->whereIn('status', ['diterima', 'didisposisi', 'diajukan']);
            } elseif ($request->status === 'terlaksana' || $request->status === 'selesai') {
                $query->where('status', 'selesai');
            } elseif ($request->status === 'tertunda') {
                $query->where(function ($q) {
                    $q->where('status', 'ditolak')
                      ->orWhere('execution_result', 'tertunda');
                });
            } elseif ($request->status === 'proses') {
                $query->where('status', 'proses');
            } else {
                $query->where('status', $request->status);
            }
        } else {
            // Default untuk Laporan Masuk: tampilkan laporan baru/didisposisi yang menunggu penanganan serta laporan menunggu verifikasi
            $query->whereIn('status', ['diterima', 'didisposisi', 'diajukan', 'verifikasi_kabag']);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $subDepartments = SubDepartment::all();

        $baseCount = Report::where('handler_id', $userId);

        $counts = [
            'total' => (clone $baseCount)->count(),
            'masuk' => (clone $baseCount)->whereIn('status', ['diterima', 'didisposisi', 'diajukan', 'verifikasi_kabag'])->count(),
            'terlaksana' => (clone $baseCount)->where('status', 'selesai')->count(),
            'tertunda' => (clone $baseCount)->where(function ($q) {
                $q->where('status', 'ditolak')
                  ->orWhere('execution_result', 'tertunda');
            })->count(),
            'proses' => (clone $baseCount)->where('status', 'proses')->count(),
        ];

        return view('petugas.laporan_masuk_petugas', compact('reports', 'counts', 'subDepartments'));
    }

    public function myReports(Request $request): View
    {
        $userId = auth()->id();

        $query = Report::with(['user', 'handler', 'subDepartment', 'evidences'])
            ->where('handler_id', $userId)
            ->where('status', 'proses');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        return view('petugas.laporanSaya_petugas', compact('reports'));
    }

    public function history(): View
    {
        $reports = Report::with(['user', 'subDepartment'])
            ->where('handler_id', auth()->id())
            ->where('status', 'selesai')
            ->latest()
            ->paginate(15);

        return view('petugas.history', compact('reports'));
    }
}
