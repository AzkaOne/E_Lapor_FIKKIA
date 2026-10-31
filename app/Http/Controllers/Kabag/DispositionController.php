<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\ReportEvidence;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DispositionController extends Controller
{
    public function dispose(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'target_type' => ['required', 'in:kasubag,petugas'],
            'receiver_id' => ['required', 'exists:users,id'],
            'target_sub_department_id' => ['nullable', 'exists:sub_departments,id'],
            'notes' => ['nullable', 'string'],
        ]);

        // If Kabag corrected destination sub-department
        if (! empty($validated['target_sub_department_id'])) {
            $report->sub_department_id = $validated['target_sub_department_id'];
            $report->save();
        }

        $receiver = User::findOrFail($validated['receiver_id']);
        $currentSubDept = $report->subDepartment;
        $isDekanat = $currentSubDept && ($currentSubDept->code === 'DEK' || str_contains(strtolower($currentSubDept->name), 'dekanat'));

        // Authority Enforcement:
        // If not Dekanat, Kabag cannot assign directly to a Petugas!
        if ($validated['target_type'] === 'petugas' && ! $isDekanat) {
            return redirect()->back()
                ->withErrors(['receiver_id' => 'Laporan sub-bagian hanya dapat didisposisikan ke Kasubag terkait.'])
                ->withInput();
        }

        if ($validated['target_type'] === 'kasubag') {
            if ($receiver->role !== 'kasubag') {
                return redirect()->back()->withErrors(['receiver_id' => 'Penerima harus berstatus Kasubag.']);
            }
            $report->status = 'didisposisi';
        } elseif ($validated['target_type'] === 'petugas') {
            if ($receiver->role !== 'petugas') {
                return redirect()->back()->withErrors(['receiver_id' => 'Penerima harus berstatus Petugas.']);
            }
            $report->status = 'diterima';
            $report->handler_id = $receiver->id;
        }

        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'notes' => $validated['notes'] ?? 'Disposisi dari KABAG.',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil didisposisikan ke ' . $receiver->name);
    }

    public function selfExecute(Report $report): RedirectResponse
    {
        $report->status = 'proses';
        $report->handler_id = auth()->id();
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => auth()->id(),
            'notes' => 'Dikerjakan langsung secara mandiri oleh KABAG.',
        ]);

        return redirect()->back()->with('success', 'Laporan telah dipindahkan ke tab Laporan Saya untuk dikerjakan mandiri.');
    }

    public function completeSelfExecution(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $report->status = 'selesai';
        $report->execution_result = 'terlaksana';
        $report->resolution_notes = $validated['notes'] ?? 'Diselesaikan langsung oleh KABAG.';
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

        return redirect()->back()->with('success', 'Laporan telah berhasil diselesaikan secara mandiri.');
    }

    public function reject(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $report->status = 'ditolak';
        $report->rejection_reason = $validated['rejection_reason'];
        $report->save();

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => null,
            'notes' => 'Laporan ditolak oleh KABAG: ' . $validated['rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil ditolak dan status telah diperbarui.');
    }
}
