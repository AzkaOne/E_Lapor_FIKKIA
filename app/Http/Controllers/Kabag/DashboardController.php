<?php

namespace App\Http\Controllers\Kabag;

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
        $query = Report::with(['user', 'subDepartment', 'handler'])
            ->where('type', 'pelayanan');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sub_department_id')) {
            $query->where('sub_department_id', $request->sub_department_id);
        }

        $reports = $query->latest()->paginate(10);

        $counts = [
            'total' => Report::where('type', 'pelayanan')->count(),
            'diajukan' => Report::where('type', 'pelayanan')->where('status', 'diajukan')->count(),
            'didisposisi' => Report::where('type', 'pelayanan')->where('status', 'didisposisi')->count(),
            'proses' => Report::where('type', 'pelayanan')->whereIn('status', ['diterima', 'proses'])->count(),
            'verifikasi' => Report::where('type', 'pelayanan')->where('status', 'verifikasi_kabag')->count(),
            'selesai' => Report::where('type', 'pelayanan')->where('status', 'selesai')->count(),
            'whistleblowing' => Report::where('type', 'whistleblowing')->count(),
        ];

        $subDepartments = SubDepartment::orderBy('name')->get();

        // Sub department counts for donut chart
        $subDeptCounts = Report::where('type', 'pelayanan')
            ->selectRaw('sub_department_id, count(*) as count')
            ->groupBy('sub_department_id')
            ->pluck('count', 'sub_department_id')
            ->toArray();

        // Whistleblower reports for right list
        $whistleblowerReports = Report::where('type', 'whistleblowing')
            ->with('subDepartment')
            ->latest()
            ->take(6)
            ->get();

        // Recent notes / comments
        $recentNotes = ReportDisposition::with(['sender.subDepartment', 'report'])
            ->whereNotNull('notes')
            ->where('notes', '!=', '')
            ->latest()
            ->take(4)
            ->get();

        return view('kabag.Dashboard_Kabag', compact(
            'reports',
            'counts',
            'subDepartments',
            'subDeptCounts',
            'whistleblowerReports',
            'recentNotes'
        ));
    }

    public function laporanPelayanan(Request $request): View
    {
        $query = Report::with(['user', 'subDepartment', 'handler'])
            ->where('type', 'pelayanan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('sub_department_id')) {
            $query->where('sub_department_id', $request->sub_department_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'proses') {
                $query->whereIn('status', ['didisposisi', 'diterima', 'proses']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $subDepartments = SubDepartment::orderBy('name')->get();

        $sarprasDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'sarana') || str_contains(strtolower($d->name), 'sarpras'));
        $dekanatDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'dekanat'));
        $keuanganDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'keuangan') || str_contains(strtolower($d->name), 'sdm'));
        $akademikDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'pendidikan') || str_contains(strtolower($d->name), 'kemahasiswaan') || str_contains(strtolower($d->name), 'akademik'));

        $totalCount = Report::where('type', 'pelayanan')->count();
        $sarprasCount = $sarprasDept ? Report::where('type', 'pelayanan')->where('sub_department_id', $sarprasDept->id)->count() : 0;
        $dekanatCount = $dekanatDept ? Report::where('type', 'pelayanan')->where('sub_department_id', $dekanatDept->id)->count() : 0;
        $keuanganCount = $keuanganDept ? Report::where('type', 'pelayanan')->where('sub_department_id', $keuanganDept->id)->count() : 0;
        $akademikCount = $akademikDept ? Report::where('type', 'pelayanan')->where('sub_department_id', $akademikDept->id)->count() : 0;

        $counts = [
            'total' => $totalCount,
            'sarpras' => $sarprasCount,
            'dekanat' => $dekanatCount,
            'keuangan' => $keuanganCount,
            'akademik' => $akademikCount,
            'verifikasi' => Report::where('type', 'pelayanan')->where('status', 'verifikasi_kabag')->count(),
        ];

        return view('kabag.laporan_pelayanan', compact('reports', 'counts', 'subDepartments'));
    }

    public function show(Report $report): View
    {
        $report->load([
            'user',
            'subDepartment',
            'handler.subDepartment',
            'dispositions.sender.subDepartment',
            'dispositions.receiver.subDepartment',
            'evidences',
        ]);
        $subDepartments = SubDepartment::orderBy('name')->get();

        // Get potential receivers
        $kasubags = User::where('role', 'kasubag')->with('subDepartment')->get();
        $petugas = User::where('role', 'petugas')->with('subDepartment')->get();

        // Petugas Dekanat/Kabag
        $dekanatId = SubDepartment::whereIn('code', ['DEK', 'DEKANAT'])->value('id');
        $petugasKabag = User::where('role', 'petugas')
            ->where(function ($q) use ($dekanatId) {
                if ($dekanatId) {
                    $q->where('sub_department_id', $dekanatId);
                } else {
                    $q->whereNull('sub_department_id');
                }
            })->with('subDepartment')->get();

        return view('kabag.detail_Kabag', compact('report', 'subDepartments', 'kasubags', 'petugasKabag', 'petugas'));
    }

    public function myReports(): View
    {
        $reports = Report::with(['user', 'subDepartment', 'evidences'])
            ->where('handler_id', auth()->id())
            ->latest()
            ->get();

        return view('kabag.my_reports', compact('reports'));
    }

    public function history(): View
    {
        $reports = Report::with(['user', 'subDepartment', 'handler'])
            ->where('type', 'pelayanan')
            ->whereIn('status', ['selesai', 'ditolak'])
            ->latest()
            ->paginate(15);

        return view('kabag.history', compact('reports'));
    }
}
