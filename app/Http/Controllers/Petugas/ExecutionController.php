<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\ReportEvidence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExecutionController extends Controller
{
    public function start(Report $report): RedirectResponse
    {
        if ($report->handler_id !== auth()->id()) {
            abort(403, 'Anda bukan petugas yang ditugaskan untuk laporan ini.');
        }

        $report->status = 'proses';
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => auth()->id(),
            'notes' => 'Petugas mulai mengerjakan laporan di lapangan.',
        ]);

        return redirect()->route('petugas.my-reports')
            ->with('success', 'Laporan telah dimulai dan masuk ke tab Laporan Saya.');
    }

    public function complete(Request $request, Report $report): RedirectResponse
    {
        if ($report->handler_id !== auth()->id()) {
            abort(403, 'Anda bukan petugas yang ditugaskan untuk laporan ini.');
        }

        $validated = $request->validate([
            'notes' => ['required', 'string'],
            'execution_result' => ['required', 'in:terlaksana,tertunda'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $report->status = 'verifikasi_kabag';
        $report->execution_result = $validated['execution_result'];
        $report->resolution_notes = $validated['notes'];
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

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => null,
            'notes' => 'Petugas telah menyelesaikan pekerjaan (' . $validated['execution_result'] . ') dan meneruskan laporan ke KABAG untuk verifikasi akhir.',
        ]);

        return redirect()->route('petugas.dashboard')
            ->with('success', 'Laporan pengerjaan berhasil dikirim ke KABAG untuk tahap verifikasi.');
    }
}
