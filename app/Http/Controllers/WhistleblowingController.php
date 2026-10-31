<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\SubDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhistleblowingController extends Controller
{
    public function create(): View
    {
        $subDepartments = SubDepartment::orderBy('name')->get();
        return view('whistleblowing.create', compact('subDepartments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'campus_location' => ['required', 'in:mojo,sobo,giri'],
            'sub_department_id' => ['nullable', 'exists:sub_departments,id'],
            'description' => ['required', 'string'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $ticketCode = Report::generateTicketCode('WB');

        $report = Report::create([
            'type' => 'whistleblowing',
            'ticket_code' => $ticketCode,
            'user_id' => null,
            'sub_department_id' => $validated['sub_department_id'] ?? null,
            'campus_location' => $validated['campus_location'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'diajukan',
        ]);

        if ($request->hasFile('evidence')) {
            $path = $request->file('evidence')->store('evidences', 'public');
            ReportEvidence::create([
                'report_id' => $report->id,
                'uploaded_by_id' => null,
                'file_path' => $path,
                'type' => 'initial',
            ]);
        }

        return redirect()->back()
            ->with('ticket_code', $ticketCode)
            ->with('success', 'Laporan anonim Anda berhasil dikirim dengan kerahasiaan terjaga.');
    }
}
