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

        return view('pelapor.dashboard', compact('activeReports', 'subDepartments'));
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
            ->with('success', 'Laporan pelayanan berhasil dikirim dan siap ditinjau oleh KABAG.');
    }

    public function status(): View
    {
        $reports = Report::with(['subDepartment', 'dispositions', 'evidences'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['diajukan', 'didisposisi', 'diterima', 'proses', 'verifikasi_kabag'])
            ->latest()
            ->get();

        return view('pelapor.status', compact('reports'));
    }

    public function history(): View
    {
        $completedReports = Report::with(['subDepartment', 'evidences'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['selesai', 'ditolak'])
            ->latest()
            ->get();

        return view('pelapor.history', compact('completedReports'));
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
