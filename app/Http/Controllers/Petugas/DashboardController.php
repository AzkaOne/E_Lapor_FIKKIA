<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $reports = Report::with(['user', 'subDepartment', 'evidences'])
            ->where('handler_id', auth()->id())
            ->whereIn('status', ['diterima', 'proses'])
            ->latest()
            ->get();

        $counts = [
            'masuk' => Report::where('handler_id', auth()->id())->where('status', 'diterima')->count(),
            'proses' => Report::where('handler_id', auth()->id())->where('status', 'proses')->count(),
            'selesai' => Report::where('handler_id', auth()->id())->whereIn('status', ['verifikasi_kabag', 'selesai'])->count(),
        ];

        return view('petugas.dashboard', compact('reports', 'counts'));
    }

    public function myReports(): View
    {
        $reports = Report::with(['user', 'subDepartment', 'evidences'])
            ->where('handler_id', auth()->id())
            ->where('status', 'proses')
            ->latest()
            ->get();

        return view('petugas.my_reports', compact('reports'));
    }

    public function history(): View
    {
        $reports = Report::with(['user', 'subDepartment'])
            ->where('handler_id', auth()->id())
            ->whereIn('status', ['verifikasi_kabag', 'selesai'])
            ->latest()
            ->paginate(15);

        return view('petugas.history', compact('reports'));
    }
}
