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
    public function index(Request $request)
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

        $sarprasDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'sarana') || str_contains(strtolower($d->name), 'sarpras'));
        $dekanatDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'dekanat'));
        $keuanganDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'keuangan') || str_contains(strtolower($d->name), 'sdm'));
        $akademikDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'pendidikan') || str_contains(strtolower($d->name), 'kemahasiswaan') || str_contains(strtolower($d->name), 'akademik'));

        $pendidikanCount = $akademikDept ? ($subDeptCounts[$akademikDept->id] ?? 0) : 0;
        $keuanganCount = $keuanganDept ? ($subDeptCounts[$keuanganDept->id] ?? 0) : 0;
        $sarprasCount = $sarprasDept ? ($subDeptCounts[$sarprasDept->id] ?? 0) : 0;
        $dekanatCount = $dekanatDept ? ($subDeptCounts[$dekanatDept->id] ?? 0) : 0;
        $wbCount = (int) ($counts['whistleblowing'] ?? 0);
        $chartTotal = $pendidikanCount + $keuanganCount + $sarprasCount + $dekanatCount + $wbCount;

        // Whistleblower reports for right list
        $whistleblowerReports = Report::where('type', 'whistleblowing')
            ->with('subDepartment')
            ->latest()
            ->take(6)
            ->get();

        // Recent notes / comments ONLY received by Kabag
        $userId = auth()->id();
        $recentNotes = ReportDisposition::with(['sender.subDepartment', 'report'])
            ->where(function ($q) use ($userId) {
                $q->where('receiver_id', $userId)
                  ->orWhereHas('receiver', fn($rq) => $rq->where('role', 'kabag'))
                  ->orWhere(function ($sq) {
                      $sq->whereNull('receiver_id')
                         ->whereHas('sender', fn($uq) => $uq->whereIn('role', ['kasubag', 'petugas']));
                  });
            })
            ->where('sender_id', '!=', $userId)
            ->whereNotNull('notes')
            ->where('notes', '!=', '')
            ->latest()
            ->take(5)
            ->get();

        if ($request->ajax() || $request->wantsJson() || $request->has('realtime')) {
            $tableHtml = view('kabag.partials.dashboard_table_rows', compact('reports'))->render();

            return response()->json([
                'counts' => [
                    'total' => $counts['total'],
                    'diajukan' => $counts['diajukan'],
                    'didisposisi' => $counts['didisposisi'],
                    'proses' => $counts['proses'],
                    'dalam_penanganan' => $counts['proses'] + $counts['didisposisi'],
                    'verifikasi' => $counts['verifikasi'],
                    'selesai' => $counts['selesai'],
                    'whistleblowing' => $counts['whistleblowing'],
                ],
                'distribution' => [
                    'pendidikan' => $pendidikanCount,
                    'keuangan' => $keuanganCount,
                    'sarpras' => $sarprasCount,
                    'sdm' => $dekanatCount,
                    'whistleblowing' => $wbCount,
                    'total' => $chartTotal,
                ],
                'recent_notes' => $recentNotes->map(function ($note) {
                    return [
                        'id' => $note->id,
                        'sender_name' => $note->sender?->name ?? 'Kasubag / Petugas',
                        'sender_initial' => strtoupper(substr($note->sender?->name ?? 'P', 0, 1)),
                        'sender_unit' => $note->sender?->subDepartment?->name ?? ($note->sender?->role === 'kasubag' ? 'KASUBAG' : ($note->sender?->role === 'petugas' ? 'Petugas' : 'Sub Bagian')),
                        'time_formatted' => $note->created_at->format('d M • H:i'),
                        'report_title' => $note->report?->title ?? 'Tindak Lanjut',
                        'notes' => $note->notes,
                    ];
                }),
                'whistleblower_reports' => $whistleblowerReports->map(function ($wb) {
                    return [
                        'id' => $wb->id,
                        'title' => $wb->title,
                        'unit_badge' => $wb->subDepartment?->name ? (str_contains($wb->subDepartment->name, 'Sarana') ? 'Sarpras' : (str_contains($wb->subDepartment->name, 'Pendidikan') ? 'Akademik' : (str_contains($wb->subDepartment->name, 'SDM') ? 'SDM' : 'Dekanat'))) : 'Umum',
                        'time_ago' => $wb->created_at->diffForHumans(),
                        'url' => route('kabag.whistleblowing'),
                    ];
                }),
                'table_html' => $tableHtml,
            ]);
        }

        return view('kabag.Dashboard_Kabag', compact(
            'reports',
            'counts',
            'subDepartments',
            'subDeptCounts',
            'pendidikanCount',
            'keuanganCount',
            'sarprasCount',
            'dekanatCount',
            'wbCount',
            'chartTotal',
            'whistleblowerReports',
            'recentNotes'
        ));
    }

    public function laporanPelayanan(Request $request): View
    {
        $query = Report::with(['user', 'subDepartment', 'handler'])
            ->where('type', 'pelayanan')
            ->where('status', '!=', 'selesai')
            ->where(function ($q) {
                $q->where('status', '!=', 'proses')
                  ->orWhere('handler_id', '!=', auth()->id())
                  ->orWhereNull('handler_id');
            });

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

        $basePelayanan = Report::where('type', 'pelayanan')
            ->where('status', '!=', 'selesai')
            ->where(function ($q) {
                $q->where('status', '!=', 'proses')
                  ->orWhere('handler_id', '!=', auth()->id())
                  ->orWhereNull('handler_id');
            });

        $totalCount = (clone $basePelayanan)->count();
        $sarprasCount = $sarprasDept ? (clone $basePelayanan)->where('sub_department_id', $sarprasDept->id)->count() : 0;
        $dekanatCount = $dekanatDept ? (clone $basePelayanan)->where('sub_department_id', $dekanatDept->id)->count() : 0;
        $keuanganCount = $keuanganDept ? (clone $basePelayanan)->where('sub_department_id', $keuanganDept->id)->count() : 0;
        $akademikCount = $akademikDept ? (clone $basePelayanan)->where('sub_department_id', $akademikDept->id)->count() : 0;

        $counts = [
            'total' => $totalCount,
            'sarpras' => $sarprasCount,
            'dekanat' => $dekanatCount,
            'keuangan' => $keuanganCount,
            'akademik' => $akademikCount,
            'verifikasi' => Report::where('type', 'pelayanan')->where('status', 'verifikasi_kabag')->count(),
            'saya' => Report::where('type', 'pelayanan')->where('status', 'proses')->where('handler_id', auth()->id())->count(),
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

        $isKasubag = false;
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

        $counts = [
            'verifikasi' => Report::where('status', 'verifikasi_kabag')->count(),
        ];

        return view('petugas.laporanSaya_petugas', compact('reports', 'counts'));
    }

    public function history(Request $request): View
    {
        $query = Report::with(['user', 'subDepartment', 'handler', 'evidences'])
            ->whereIn('type', ['pelayanan', 'whistleblowing'])
            ->where('status', 'selesai');

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Bidang
        if ($request->filled('sub_department_id')) {
            $query->where('sub_department_id', $request->sub_department_id);
        }

        // Filter Hasil Penanganan & Kategori Laporan (Pelayanan / Whistleblowing)
        if ($request->filled('status')) {
            if (in_array($request->status, ['terlaksana', 'tertunda'])) {
                $query->where('execution_result', $request->status);
            } elseif (in_array($request->status, ['pelayanan', 'whistleblowing'])) {
                $query->where('type', $request->status);
            }
        }

        if ($request->filled('type') && in_array($request->type, ['pelayanan', 'whistleblowing'])) {
            $query->where('type', $request->type);
        }

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->date);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('updated_at', '>=', $request->start_date)
                  ->whereDate('updated_at', '<=', $request->end_date);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('updated_at', '>=', $request->start_date);
        }

        $reports = $query->latest('updated_at')->paginate(10)->withQueryString();

        $subDepartments = SubDepartment::orderBy('name')->get();

        $sarprasDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'sarana') || str_contains(strtolower($d->name), 'sarpras'));
        $dekanatDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'dekanat'));
        $keuanganDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'keuangan') || str_contains(strtolower($d->name), 'sdm'));
        $akademikDept = $subDepartments->first(fn($d) => str_contains(strtolower($d->name), 'pendidikan') || str_contains(strtolower($d->name), 'kemahasiswaan') || str_contains(strtolower($d->name), 'akademik'));

        $baseHistory = Report::whereIn('type', ['pelayanan', 'whistleblowing'])->where('status', 'selesai');
        $totalCount = (clone $baseHistory)->count();
        $sarprasCount = $sarprasDept ? (clone $baseHistory)->where('sub_department_id', $sarprasDept->id)->count() : 0;
        $dekanatCount = $dekanatDept ? (clone $baseHistory)->where('sub_department_id', $dekanatDept->id)->count() : 0;
        $keuanganCount = $keuanganDept ? (clone $baseHistory)->where('sub_department_id', $keuanganDept->id)->count() : 0;
        $akademikCount = $akademikDept ? (clone $baseHistory)->where('sub_department_id', $akademikDept->id)->count() : 0;

        $counts = [
            'total' => $totalCount,
            'sarpras' => $sarprasCount,
            'dekanat' => $dekanatCount,
            'keuangan' => $keuanganCount,
            'akademik' => $akademikCount,
            'selesai' => $totalCount,
        ];

        return view('kabag.riwayat_kabag', compact('reports', 'counts', 'subDepartments'));
    }
}
