<?php

namespace App\Http\Controllers\Kasubag;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $subDeptId = auth()->user()->sub_department_id;

        $query = Report::with(['user', 'handler', 'subDepartment'])
            ->where('type', 'pelayanan')
            ->where('sub_department_id', $subDeptId);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(10);

        $masuk = Report::where('sub_department_id', $subDeptId)->where('status', 'didisposisi')->count();
        $dikerjakan = Report::where('sub_department_id', $subDeptId)->whereIn('status', ['diterima', 'proses'])->count();
        $verifikasi = Report::where('sub_department_id', $subDeptId)->where('status', 'verifikasi_kabag')->count();
        $selesai = Report::where('sub_department_id', $subDeptId)->where('status', 'selesai')->count();
        $tertunda = Report::where('sub_department_id', $subDeptId)
            ->where(function ($q) {
                $q->where('status', 'ditolak')
                  ->orWhere('execution_result', 'tertunda');
            })->count();

        $counts = [
            'masuk' => $masuk,
            'dikerjakan' => $dikerjakan,
            'verifikasi' => $verifikasi,
            'selesai' => $selesai,
            'tertunda' => $tertunda,
            'perlu_penanganan' => $masuk,
            'dalam_penanganan' => $dikerjakan,
        ];

        $totalReports = $selesai + $masuk + $dikerjakan + $tertunda;

        $todayNewCount = Report::where('sub_department_id', $subDeptId)
            ->whereDate('created_at', today())
            ->count();

        $recentNotes = ReportDisposition::whereHas('report', function ($q) use ($subDeptId) {
                $q->where('sub_department_id', $subDeptId);
            })
            ->whereNotNull('notes')
            ->where('notes', '!=', '')
            ->with(['report', 'sender.subDepartment'])
            ->latest()
            ->take(3)
            ->get();

        $recentActivities = Report::where('sub_department_id', $subDeptId)
            ->latest('updated_at')
            ->take(4)
            ->get();

        return view('kasubag.dashboard_kasubag', compact(
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
        if ($report->sub_department_id !== auth()->user()->sub_department_id) {
            abort(403, 'Laporan ini berada di luar sub bagian Anda.');
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

        // Petugas under this Kasubag's sub-department
        $petugas = User::where('role', 'petugas')
            ->where('sub_department_id', auth()->user()->sub_department_id)
            ->with('subDepartment')
            ->get();

        $isKasubag = true;
        return view('kabag.detail_Kabag', compact('report', 'subDepartments', 'kasubags', 'petugasKabag', 'petugas', 'isKasubag'));
    }

    public function myReports(Request $request): View
    {
        $query = Report::with(['user', 'handler', 'subDepartment', 'evidences'])
            ->where('handler_id', auth()->id())
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

    public function status(Request $request): View
    {
        $subDeptId = auth()->user()->sub_department_id;

        $query = Report::with(['user', 'handler', 'subDepartment'])
            ->where('sub_department_id', $subDeptId);

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
            if ($request->status === 'masuk' || $request->status === 'didisposisi') {
                $query->whereIn('status', ['didisposisi', 'diajukan']);
            } elseif ($request->status === 'terlaksana' || $request->status === 'selesai') {
                $query->where('status', 'selesai');
            } elseif ($request->status === 'tertunda') {
                $query->where(function ($q) {
                    $q->where('status', 'ditolak')
                      ->orWhere('execution_result', 'tertunda');
                });
            } elseif ($request->status === 'proses') {
                $query->whereIn('status', ['diterima', 'proses']);
            } elseif ($request->status === 'revisi' || $request->status === 'verifikasi_kabag') {
                $query->where('status', 'verifikasi_kabag');
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $subDepartments = SubDepartment::all();

        $baseCount = Report::where('sub_department_id', $subDeptId);

        $counts = [
            'total' => (clone $baseCount)->count(),
            'masuk' => (clone $baseCount)->whereIn('status', ['didisposisi', 'diajukan'])->count(),
            'terlaksana' => (clone $baseCount)->where('status', 'selesai')->count(),
            'tertunda' => (clone $baseCount)->where(function ($q) {
                $q->where('status', 'ditolak')
                  ->orWhere('execution_result', 'tertunda');
            })->count(),
            'proses' => (clone $baseCount)->whereIn('status', ['diterima', 'proses'])->count(),
            'revisi' => (clone $baseCount)->where('status', 'verifikasi_kabag')->count(),
        ];

        return view('kasubag.laporan_masuk', compact('reports', 'counts', 'subDepartments'));
    }

    public function history(Request $request): View
    {
        $subDeptId = auth()->user()->sub_department_id;

        $query = Report::with(['user', 'handler', 'subDepartment', 'evidences'])
            ->where('sub_department_id', $subDeptId)
            ->where('status', 'selesai');

        // Filter Search
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

        // Filter Hasil Penanganan (terlaksana & tertunda)
        if ($request->filled('status')) {
            if (in_array($request->status, ['terlaksana', 'tertunda'])) {
                $query->where('execution_result', $request->status);
            }
        }

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->date);
        }

        $reports = $query->latest('updated_at')->paginate(10)->withQueryString();

        $baseCount = Report::where('sub_department_id', $subDeptId)->where('status', 'selesai');
        $counts = [
            'total' => (clone $baseCount)->count(),
            'terlaksana' => (clone $baseCount)->where('execution_result', 'terlaksana')->count(),
            'tertunda' => (clone $baseCount)->where('execution_result', 'tertunda')->count(),
            'rating' => (clone $baseCount)->whereNotNull('rating')->count(),
        ];

        return view('kasubag.riwayat_kasubag', compact('reports', 'counts'));
    }
}
