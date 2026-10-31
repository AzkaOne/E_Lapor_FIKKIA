<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(): View
    {
        $reports = Report::with(['user', 'subDepartment', 'handler', 'evidences', 'dispositions.sender'])
            ->where('status', 'verifikasi_kabag')
            ->latest()
            ->paginate(15);

        return view('kabag.verification', compact('reports'));
    }

    public function approve(Request $request, Report $report): RedirectResponse
    {
        if ($report->status !== 'verifikasi_kabag') {
            return back()->with('error', 'Status laporan bukan verifikasi KABAG.');
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $report->status = 'selesai';
        if ($report->execution_result === 'tertunda') {
            $report->postponed_reason = $validated['notes'] ?? 'Ditunda sesuai hasil pelaksanaan lapangan.';
        }
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $report->user_id,
            'notes' => 'Laporan diverifikasi selesai oleh KABAG' . (!empty($validated['notes']) ? ': ' . $validated['notes'] : '.'),
        ]);

        return redirect()->route('kabag.verifikasi.index')
            ->with('success', "Laporan {$report->ticket_code} berhasil diverifikasi dan diselesaikan.");
    }

    public function requestRevision(Request $request, Report $report): RedirectResponse
    {
        if ($report->status !== 'verifikasi_kabag') {
            return back()->with('error', 'Status laporan bukan verifikasi KABAG.');
        }

        $validated = $request->validate([
            'notes' => ['required', 'string'],
        ]);

        // Find Kasubag for this sub_department if present
        $kasubag = null;
        if ($report->sub_department_id) {
            $kasubag = User::where('role', 'kasubag')
                ->where('sub_department_id', $report->sub_department_id)
                ->first();
        }

        $report->status = 'didisposisi';
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $kasubag?->id ?? $report->handler_id,
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('kabag.verifikasi.index')
            ->with('success', "Catatan revisi untuk laporan {$report->ticket_code} berhasil dikirim.");
    }
}