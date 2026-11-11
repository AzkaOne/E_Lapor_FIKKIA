<?php

namespace App\Http\Controllers\Kasubag;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\ReportEvidence;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DispositionController extends Controller
{
    public function dispose(Request $request, Report $report): RedirectResponse
    {
        if ($report->sub_department_id !== auth()->user()->sub_department_id) {
            abort(403);
        }

        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $receiver = User::findOrFail($validated['receiver_id']);

        // Must be petugas and must belong to the exact same sub-department!
        if ($receiver->role !== 'petugas' || $receiver->sub_department_id !== auth()->user()->sub_department_id) {
            return redirect()->back()
                ->withErrors(['receiver_id' => 'Petugas yang dipilih harus berada di bawah unit sub bagian Anda.'])
                ->withInput();
        }

        $report->status = 'diterima';
        $report->handler_id = $receiver->id;
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'notes' => $validated['notes'] ?? 'Disposisi penugasan dari Kasubag.',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil didisposisikan ke petugas ' . $receiver->name);
    }

    public function returnToKabag(Request $request, Report $report): RedirectResponse
    {
        if ($report->sub_department_id !== auth()->user()->sub_department_id) {
            abort(403);
        }

        $validated = $request->validate([
            'notes' => ['required', 'string'],
        ]);

        $report->status = 'diajukan';
        $report->handler_id = null;
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => null,
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('kasubag.dashboard')
            ->with('success', 'Laporan berhasil dikembalikan ke KABAG karena bukan ranah sub bagian ini.');
    }

    public function reject(Request $request, Report $report): RedirectResponse
    {
        if ($report->sub_department_id !== auth()->user()->sub_department_id) {
            abort(403);
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $kabag = User::where('role', 'kabag')->first();

        $report->status = 'ditolak';
        $report->handler_id = null;
        $report->rejection_reason = $validated['rejection_reason'];
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $kabag?->id,
            'notes' => 'Laporan ditolak oleh Kasubag (' . (auth()->user()->subDepartment?->name ?? 'Sub Bagian') . ') dan dikembalikan ke KABAG: ' . $validated['rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil ditolak dan status laporan menjadi ditolak serta diarahkan kembali ke KABAG.');
    }

    public function selfExecute(Report $report): RedirectResponse
    {
        if ($report->sub_department_id !== auth()->user()->sub_department_id) {
            abort(403);
        }

        $report->status = 'proses';
        $report->handler_id = auth()->id();
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => auth()->id(),
            'notes' => 'Kasubag memilih untuk mengerjakan laporan ini secara mandiri.',
        ]);

        return redirect()->back()->with('success', 'Laporan telah dipindahkan ke tab Laporan Saya untuk dikerjakan mandiri.');
    }

    public function submitVerification(Request $request, Report $report): RedirectResponse
    {
        if ($report->handler_id !== auth()->id()) {
            abort(403);
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
            'notes' => 'Kasubag telah menyelesaikan pengerjaan mandiri (' . $validated['execution_result'] . ') dan meneruskan ke KABAG untuk verifikasi akhir.',
        ]);

        return redirect()->route('kasubag.my-reports')
            ->with('success', 'Pengerjaan laporan berhasil dikirim ke KABAG untuk tahap verifikasi.');
    }
}
