<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\ReportEvidence;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExecutionController extends Controller
{
    public function start(Report $report): RedirectResponse
    {
        $userId = auth()->id();
        $userSubDeptId = auth()->user()->sub_department_id;

        if ($report->handler_id && $report->handler_id !== $userId) {
            abort(403, 'Anda bukan petugas yang ditugaskan untuk laporan ini.');
        }

        if (! $report->handler_id && $report->sub_department_id !== $userSubDeptId) {
            abort(403, 'Laporan ini berada di luar penugasan unit Anda.');
        }

        $report->status = 'proses';
        $report->handler_id = $userId;
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => $userId,
            'receiver_id' => $userId,
            'notes' => 'Petugas mulai mengerjakan laporan di lapangan.',
        ]);

        return redirect()->route('petugas.my-reports')
            ->with('success', 'Laporan telah dimulai dan masuk ke tab Laporan Saya.');
    }

    public function selfExecute(Report $report): RedirectResponse
    {
        $userId = auth()->id();
        $userSubDeptId = auth()->user()->sub_department_id;

        if ($report->handler_id && $report->handler_id !== $userId) {
            abort(403, 'Anda bukan petugas yang ditugaskan untuk laporan ini.');
        }

        if (! $report->handler_id && $report->sub_department_id !== $userSubDeptId) {
            abort(403, 'Laporan ini berada di luar penugasan unit Anda.');
        }

        $report->status = 'proses';
        $report->handler_id = $userId;
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => $userId,
            'receiver_id' => $userId,
            'notes' => 'Petugas mulai mengerjakan laporan secara mandiri.',
        ]);

        return redirect()->route('petugas.my-reports')
            ->with('success', 'Laporan berhasil dimulai dan masuk ke tab Laporan Saya.');
    }

    public function reject(Request $request, Report $report): RedirectResponse
    {
        $userId = auth()->id();
        $userSubDeptId = auth()->user()->sub_department_id;

        if ($report->handler_id && $report->handler_id !== $userId) {
            abort(403, 'Anda bukan petugas yang ditugaskan untuk laporan ini.');
        }

        if (! $report->handler_id && $report->sub_department_id !== $userSubDeptId) {
            abort(403, 'Laporan ini berada di luar penugasan unit Anda.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
            'rejection_evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $report->status = 'ditolak';
        $report->rejection_reason = $validated['rejection_reason'];
        $report->save();

        if ($request->hasFile('rejection_evidence')) {
            $path = $request->file('rejection_evidence')->store('evidences', 'public');
            ReportEvidence::create([
                'report_id' => $report->id,
                'uploaded_by_id' => $userId,
                'file_path' => $path,
                'type' => 'rejection',
            ]);
        }

        $kabag = User::where('role', 'kabag')->first();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => $userId,
            'receiver_id' => $kabag?->id,
            'notes' => 'Petugas menolak laporan dan mengembalikan ke KABAG: ' . $validated['rejection_reason'],
        ]);

        return redirect()->route('petugas.laporan-masuk')
            ->with('success', 'Laporan berhasil ditolak dan diarahkan kembali ke KABAG.');
    }

    public function complete(Request $request, Report $report): RedirectResponse
    {
        if ($report->handler_id !== auth()->id()) {
            abort(403, 'Anda bukan petugas yang ditugaskan untuk laporan ini.');
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
            'execution_result' => ['required', 'in:terlaksana,tertunda'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $notes = $validated['notes'] ?? ($validated['execution_result'] === 'terlaksana' ? 'Pekerjaan selesai terlaksana oleh Petugas.' : 'Pekerjaan tertunda.');

        $report->status = 'verifikasi_kabag';
        $report->execution_result = $validated['execution_result'];
        $report->resolution_notes = $notes;
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

        $kabag = User::where('role', 'kabag')->first();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $kabag?->id,
            'notes' => !empty($validated['notes']) ? $validated['notes'] : ('Petugas telah menyelesaikan pekerjaan (' . $validated['execution_result'] . ') dan meneruskan laporan ke KABAG untuk verifikasi akhir.'),
        ]);

        return redirect()->route('petugas.laporan-masuk')
            ->with('success', 'Laporan pengerjaan berhasil dikirim ke KABAG untuk tahap verifikasi.');
    }
}
