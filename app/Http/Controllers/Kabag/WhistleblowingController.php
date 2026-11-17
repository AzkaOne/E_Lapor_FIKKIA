<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\SubDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhistleblowingController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $activeTab = $request->query('tab', 'whistleblowing');
        if (!in_array($activeTab, ['whistleblowing', 'saya'])) {
            $activeTab = 'whistleblowing';
        }

        if ($activeTab !== 'saya') {
            if ($request->status === 'proses') {
                return redirect()->route('kabag.whistleblowing', array_merge($request->except(['tab', 'status']), ['tab' => 'saya']));
            }
            if ($request->status === 'selesai') {
                return redirect()->route('kabag.history', array_merge($request->except(['tab', 'status']), ['status' => 'whistleblowing']));
            }
        }

        $query = Report::with(['subDepartment', 'evidences'])
            ->where('type', 'whistleblowing');

        if ($activeTab === 'saya') {
            // Tab Laporan Saya: hanya menampilkan laporan whistleblowing yang dikerjakan (proses)
            $query->where('status', 'proses')
                  ->where(function ($q) {
                      $q->where('handler_id', auth()->id())
                        ->orWhereNull('handler_id');
                  });
        } else {
            // Tab Laporan Whistleblowing: tidak menampilkan laporan yang diproses di Laporan Saya maupun yang sudah selesai di Riwayat
            $query->whereNotIn('status', ['proses', 'selesai']);

            if ($request->filled('status')) {
                if ($request->status === 'masuk') {
                    $query->where('status', 'diajukan');
                } else {
                    $query->where('status', $request->status);
                }
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sub_department_id')) {
            $query->where('sub_department_id', $request->sub_department_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $subDepartments = SubDepartment::orderBy('name')->get();

        $baseCount = Report::where('type', 'whistleblowing');

        $counts = [
            'total' => (clone $baseCount)->whereNotIn('status', ['proses', 'selesai'])->count(),
            'masuk' => (clone $baseCount)->where('status', 'diajukan')->count(),
            'proses' => (clone $baseCount)->where('status', 'proses')->count(),
            'selesai' => (clone $baseCount)->where('status', 'selesai')->count(),
            'saya' => (clone $baseCount)->where('status', 'proses')
                ->where(function ($q) {
                    $q->where('handler_id', auth()->id())
                      ->orWhereNull('handler_id');
                })->count(),
        ];

        return view('kabag.whistleblowing', compact('reports', 'counts', 'subDepartments', 'activeTab'));
    }

    public function process(Report $report): RedirectResponse
    {
        if ($report->type !== 'whistleblowing') {
            abort(404);
        }

        $report->status = 'proses';
        $report->handler_id = auth()->id();
        $report->save();

        return redirect()->back()->with('success', 'Status laporan whistleblowing diubah menjadi Sedang Diproses.');
    }

    public function complete(Request $request, Report $report): RedirectResponse
    {
        if ($report->type !== 'whistleblowing') {
            abort(404);
        }

        $validated = $request->validate([
            'resolution_notes' => ['nullable', 'string'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $report->status = 'selesai';
        $report->resolution_notes = $validated['resolution_notes'];
        $report->save();

        if ($request->hasFile('evidence')) {
            $path = $request->file('evidence')->store('evidences', 'public');
            ReportEvidence::create([
                'report_id' => $report->id,
                'uploaded_by_id' => auth()->id(),
                'file_path' => $path,
                'type' => 'resolution',
            ]);
        }

        return redirect()->back()->with('success', 'Laporan whistleblowing telah diselesaikan.');
    }
}
