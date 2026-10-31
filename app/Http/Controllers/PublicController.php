<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function index(): View
    {
        return view('welcome');
    }

    public function track(Request $request): View
    {
        $ticketCode = trim($request->input('ticket_code', ''));
        $report = null;
        $notFound = false;

        if ($ticketCode !== '') {
            $report = Report::with(['subDepartment', 'evidences'])
                ->where('ticket_code', $ticketCode)
                ->first();

            if (! $report) {
                $notFound = true;
            }
        }

        return view('whistleblowing.track', compact('report', 'ticketCode', 'notFound'));
    }
}
