<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\SubDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $activeReports = Report::with(['subDepartment', 'evidences'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['diajukan', 'didisposisi', 'diterima', 'proses', 'verifikasi_kabag'])
            ->latest()
            ->get();

        $subDepartments = SubDepartment::orderBy('name')->get();

        return view('pelapor.Dashboard_Pelapor', compact('activeReports', 'subDepartments'));
    }

    public function create(): View
    {
        $subDepartments = SubDepartment::orderBy('name')->get();
        return view('pelapor.create', compact('subDepartments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'campus_location' => ['required', 'in:mojo,sobo,giri'],
            'sub_department_id' => ['required', 'exists:sub_departments,id'],
            'description' => ['required', 'string'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        try {
            $report = Report::create([
                'type' => 'pelayanan',
                'ticket_code' => Report::generateTicketCode('LP'),
                'user_id' => auth()->id(),
                'sub_department_id' => $validated['sub_department_id'],
                'campus_location' => $validated['campus_location'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => 'diajukan',
            ]);

            if ($request->hasFile('evidence')) {
                $path = $request->file('evidence')->store('evidences', 'public');
                ReportEvidence::create([
                    'report_id' => $report->id,
                    'uploaded_by_id' => auth()->id(),
                    'file_path' => $path,
                    'type' => 'initial',
                ]);
            }

            return redirect()->route('pelapor.dashboard')
                ->with('success', 'Laporan pelayanan berhasil dikirim dan siap ditinjau oleh KABAG.')
                ->with('ticket_code', $report->ticket_code)
                ->with('report_title', $report->title);
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengirim laporan. Terjadi gangguan pada sistem: ' . $e->getMessage());
        }
    }

    public function status(Request $request): View
    {
        $userId = auth()->id();

        // 5 Summary metrics
        $countsQuery = Report::where('user_id', $userId);
        $totalCount = (clone $countsQuery)->count();
        $prosesCount = (clone $countsQuery)->whereIn('status', ['didisposisi', 'diterima', 'proses', 'verifikasi_kabag'])->count();
        $selesaiCount = (clone $countsQuery)->where('status', 'selesai')->count();
        $perbaikanCount = (clone $countsQuery)->where('status', 'ditolak')->count();
        $menungguCount = (clone $countsQuery)->where('status', 'diajukan')->count();

        // Table Query
        $query = Report::with(['subDepartment', 'evidences', 'dispositions.sender', 'dispositions.receiver'])
            ->where('user_id', $userId);

        if ($request->filled('status')) {
            if ($request->status === 'proses') {
                $query->whereIn('status', ['didisposisi', 'diterima', 'proses', 'verifikasi_kabag']);
            } elseif ($request->status === 'menunggu') {
                $query->where('status', 'diajukan');
            } elseif ($request->status === 'perbaikan' || $request->status === 'ditolak') {
                $query->where('status', 'ditolak');
            } elseif ($request->status === 'selesai') {
                $query->where('status', 'selesai');
            } elseif ($request->status === 'all') {
                // all statuses
            } else {
                $query->where('status', $request->status);
            }
        } else {
            $query->whereIn('status', ['diajukan', 'didisposisi', 'diterima', 'proses', 'verifikasi_kabag']);
        }

        if ($request->filled('bidang')) {
            $query->where('sub_department_id', $request->bidang);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();
        $subDepartments = SubDepartment::orderBy('name')->get();

        return view('pelapor.Status_Pelapor', compact(
            'reports',
            'subDepartments',
            'totalCount',
            'prosesCount',
            'selesaiCount',
            'perbaikanCount',
            'menungguCount'
        ));
    }

    public function history(Request $request): View
    {
        $query = Report::with(['subDepartment', 'evidences', 'dispositions.sender', 'dispositions.receiver'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['selesai', 'ditolak']);

        if ($request->filled('bidang')) {
            $query->where('sub_department_id', $request->bidang);
        }

        if ($request->filled('status') && in_array($request->status, ['selesai', 'ditolak'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();
        $completedReports = $reports;
        $subDepartments = SubDepartment::orderBy('name')->get();

        return view('pelapor.Riwayat_Pelapor', compact('reports', 'completedReports', 'subDepartments'));
    }

    public function rate(Request $request, Report $report): RedirectResponse
    {
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        if ($report->status !== 'selesai' || $report->execution_result !== 'terlaksana') {
            abort(422, 'Hanya laporan selesai dan terlaksana yang dapat diberi rating.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        $report->update([
            'rating' => $validated['rating'],
        ]);

        return redirect()->back()->with('success', 'Terima kasih telah memberikan ulasan penilaian pelayanan.');
    }
}
