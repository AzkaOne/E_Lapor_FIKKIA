<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportEvidence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhistleblowingController extends Controller
{
    public function index(): View
    {
        $reports = Report::where('type', 'whistleblowing')
            ->with(['subDepartment', 'evidences'])
            ->latest()
            ->paginate(15);

        return view('kabag.whistleblowing', compact('reports'));
    }

    public function process(Report $report): RedirectResponse
    {
        if ($report->type !== 'whistleblowing') {
            abort(404);
        }

        $report->status = 'proses';
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
