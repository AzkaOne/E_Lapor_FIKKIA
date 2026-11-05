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

    public function show(Report $report): View
    {
        $report->load(['user', 'subDepartment', 'handler', 'dispositions.sender', 'dispositions.receiver', 'evidences']);
        $subDepartments = SubDepartment::orderBy('name')->get();

        // Get potential receivers
        $kasubags = User::where('role', 'kasubag')->with('subDepartment')->get();
        
        // Petugas Dekanat/Kabag only
        $dekanatId = SubDepartment::where('code', 'DEK')->value('id');
        $petugasKabag = User::where('role', 'petugas')
            ->where(function ($q) use ($dekanatId) {
                if ($dekanatId) {
                    $q->where('sub_department_id', $dekanatId);
                } else {
                    $q->whereNull('sub_department_id');
                }
            })->get();

        return view('kabag.detail', compact('report', 'subDepartments', 'kasubags', 'petugasKabag'));
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
