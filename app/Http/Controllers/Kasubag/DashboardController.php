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
            ->where('sub_department_id', $subDeptId)
            ->where('status', '!=', 'diajukan');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(10);

        $baseKasubag = Report::where('type', 'pelayanan')
            ->where('sub_department_id', $subDeptId)
            ->where('status', '!=', 'diajukan');

        $masuk = (clone $baseKasubag)->where('status', 'didisposisi')->count();
        $dikerjakan = (clone $baseKasubag)->whereIn('status', ['diterima', 'proses'])->count();
        $verifikasi = (clone $baseKasubag)->where('status', 'verifikasi_kabag')->count();
        $selesai = (clone $baseKasubag)->where('status', 'selesai')->count();
        $tertunda = (clone $baseKasubag)
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

        $todayNewCount = (clone $baseKasubag)
            ->whereDate('created_at', today())
            ->count();

        $user = auth()->user();
        $recentNotes = ReportDisposition::with(['sender.subDepartment', 'report'])
            ->where(function ($q) use ($user, $subDeptId) {
                $q->where('receiver_id', $user->id)
                  ->orWhere(function ($sq) use ($subDeptId) {
                      $sq->whereNull('receiver_id')
                         ->whereHas('report', fn($rq) => $rq->where('type', 'pelayanan')->where('sub_department_id', $subDeptId)->where('status', '!=', 'diajukan'))
                         ->whereHas('sender', fn($uq) => $uq->where('role', 'kabag'));
                  });
            })
            ->where('sender_id', '!=', $user->id)
            ->whereNotNull('notes')
            ->where('notes', '!=', '')
            ->latest()
            ->take(5)
            ->get();

        $recentActivities = (clone $baseKasubag)
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
        if ($report->type !== 'pelayanan') {
            abort(403, 'Akses ditolak. Kasubag hanya memiliki akses pada laporan pelayanan.');
        }

        if ($report->sub_department_id !== auth()->user()->sub_department_id) {
            abort(403, 'Laporan ini berada di luar sub bagian Anda.');
        }

        if ($report->status === 'diajukan') {
            abort(403, 'Laporan ini belum didisposisikan oleh KABAG.');
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
            ->where('type', 'pelayanan')
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
            ->where('type', 'pelayanan')
            ->where('sub_department_id', $subDeptId)
            ->where('status', '!=', 'diajukan');

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
                $query->where('status', 'didisposisi');
            } elseif ($request->status === 'terlaksana' || $request->status === 'selesai') {
                $query->where(function ($q) {
                    $q->where('status', 'selesai')
                      ->orWhere('execution_result', 'terlaksana');
                });
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
        } else {
            // Default untuk Laporan Masuk: sembunyikan laporan yang sudah selesai atau diverifikasi karena sudah pindah ke Riwayat, serta belum didisposisi (diajukan)
            $query->whereNotIn('status', ['diajukan', 'selesai', 'verifikasi_kabag']);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $subDepartments = SubDepartment::all();

        $baseCount = Report::where('type', 'pelayanan')
            ->where('sub_department_id', $subDeptId)
            ->where('status', '!=', 'diajukan');

        $counts = [
            'total' => (clone $baseCount)->whereNotIn('status', ['selesai', 'verifikasi_kabag'])->count(),
            'masuk' => (clone $baseCount)->where('status', 'didisposisi')->count(),
            'terlaksana' => (clone $baseCount)->where(function ($q) {
                $q->where('status', 'selesai')
                  ->orWhere('execution_result', 'terlaksana');
            })->count(),
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
            ->where('type', 'pelayanan')
            ->where('sub_department_id', $subDeptId)
            ->whereIn('status', ['selesai', 'verifikasi_kabag']);

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
            if ($request->status === 'terlaksana') {
                $query->where(function ($q) {
                    $q->where('execution_result', 'terlaksana')
                      ->orWhere(function ($sq) {
                          $sq->where('status', 'selesai')->whereNull('execution_result');
                      });
                });
            } elseif ($request->status === 'tertunda') {
                $query->where('execution_result', 'tertunda');
            }
        }

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->date);
        }

        $reports = $query->latest('updated_at')->paginate(10)->withQueryString();

        $baseCount = Report::where('type', 'pelayanan')
            ->where('sub_department_id', $subDeptId)
            ->whereIn('status', ['selesai', 'verifikasi_kabag']);
        $counts = [
            'total' => (clone $baseCount)->count(),
            'terlaksana' => (clone $baseCount)->where(function ($q) {
                $q->where('execution_result', 'terlaksana')
                  ->orWhere(function ($sq) {
                      $sq->where('status', 'selesai')->whereNull('execution_result');
                  });
            })->count(),
            'tertunda' => (clone $baseCount)->where('execution_result', 'tertunda')->count(),
            'rating' => (clone $baseCount)->whereNotNull('rating')->count(),
        ];

        return view('kasubag.riwayat_kasubag', compact('reports', 'counts'));
    }
}
