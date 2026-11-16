<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportDisposition;
use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Report::with(['user', 'subDepartment', 'handler', 'evidences', 'dispositions.sender'])
            ->where('status', 'verifikasi_kabag')
            ->where(function ($q) {
                $q->whereIn('execution_result', ['terlaksana', 'tertunda'])
                  ->orWhereNull('execution_result');
            });

        // Filter Search
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

        // Filter Bidang
        if ($request->filled('sub_department_id')) {
            $query->where('sub_department_id', $request->sub_department_id);
        }

        // Filter Status / Hasil Penanganan (terlaksana & tertunda)
        if ($request->filled('status')) {
            if (in_array($request->status, ['terlaksana', 'tertunda'])) {
                $query->where('execution_result', $request->status);
            }
        }

        // Filter Tanggal
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

        $baseVerif = Report::where('status', 'verifikasi_kabag')
            ->where(function ($q) {
                $q->whereIn('execution_result', ['terlaksana', 'tertunda'])
                  ->orWhereNull('execution_result');
            });

        $totalCount = (clone $baseVerif)->count();
        $sarprasCount = $sarprasDept ? (clone $baseVerif)->where('sub_department_id', $sarprasDept->id)->count() : 0;
        $dekanatCount = $dekanatDept ? (clone $baseVerif)->where('sub_department_id', $dekanatDept->id)->count() : 0;
        $keuanganCount = $keuanganDept ? (clone $baseVerif)->where('sub_department_id', $keuanganDept->id)->count() : 0;
        $akademikCount = $akademikDept ? (clone $baseVerif)->where('sub_department_id', $akademikDept->id)->count() : 0;

        $counts = [
            'total' => $totalCount,
            'sarpras' => $sarprasCount,
            'dekanat' => $dekanatCount,
            'keuangan' => $keuanganCount,
            'akademik' => $akademikCount,
            'verifikasi' => $totalCount,
        ];

        return view('kabag.verifikasi_kabag', compact('reports', 'counts', 'subDepartments'));
    }

    public function approve(Request $request, Report $report): RedirectResponse
    {
        if ($report->status !== 'verifikasi_kabag') {
            return back()->with('error', 'Status laporan bukan verifikasi KABAG.');
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'show_evidence_to_pelapor' => ['nullable'],
            'show_notes_to_pelapor' => ['nullable'],
        ]);

        $report->status = 'selesai';
        if ($report->execution_result === 'tertunda') {
            $report->postponed_reason = $validated['notes'] ?? $report->postponed_reason ?? 'Ditunda sesuai hasil pelaksanaan lapangan.';
        }

        if ($request->has('show_evidence_to_pelapor') || $request->has('show_notes_to_pelapor')) {
            $report->show_evidence_to_pelapor = $request->has('show_evidence_to_pelapor');
            $report->show_notes_to_pelapor = $request->has('show_notes_to_pelapor');
        }

        if ($request->hasFile('evidence')) {
            $path = $request->file('evidence')->store('evidences', 'public');
            \App\Models\ReportEvidence::create([
                'report_id' => $report->id,
                'uploaded_by_id' => auth()->id(),
                'file_path' => $path,
                'type' => 'resolution',
            ]);
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
            'notes' => ['nullable', 'string'],
            'receiver_id' => ['nullable', 'exists:users,id'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'show_evidence_to_pelapor' => ['nullable'],
            'show_notes_to_pelapor' => ['nullable'],
        ]);

        // Find Kasubag for this sub_department if present or from receiver_id
        $kasubag = null;
        if (!empty($validated['receiver_id'])) {
            $kasubag = User::where('id', $validated['receiver_id'])->first();
        } elseif ($report->sub_department_id) {
            $kasubag = User::where('role', 'kasubag')
                ->where('sub_department_id', $report->sub_department_id)
                ->first();
        }

        $report->status = 'didisposisi';

        if ($request->has('show_evidence_to_pelapor') || $request->has('show_notes_to_pelapor')) {
            $report->show_evidence_to_pelapor = $request->has('show_evidence_to_pelapor');
            $report->show_notes_to_pelapor = $request->has('show_notes_to_pelapor');
        }

        if ($request->hasFile('evidence')) {
            $path = $request->file('evidence')->store('evidences', 'public');
            \App\Models\ReportEvidence::create([
                'report_id' => $report->id,
                'uploaded_by_id' => auth()->id(),
                'file_path' => $path,
                'type' => 'resolution',
            ]);
        }

        $report->save();

        $revisionNote = !empty($validated['notes']) ? $validated['notes'] : 'Laporan dikembalikan oleh KABAG untuk perbaikan / revisi pengerjaan.';

        ReportDisposition::create([
            'report_id' => $report->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $kasubag?->id ?? $report->handler_id,
            'notes' => $revisionNote,
        ]);

        return redirect()->route('kabag.verifikasi.index')
            ->with('success', "Catatan revisi untuk laporan {$report->ticket_code} berhasil dikirim ke " . ($kasubag?->name ?? 'Kasubag / Petugas') . ".");
    }
}
