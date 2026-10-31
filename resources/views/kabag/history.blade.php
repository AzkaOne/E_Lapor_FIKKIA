<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan Selesai / Ditolak - KABAG E-LAPOR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col justify-between shrink-0 h-screen sticky top-0">
        <div>
            <div class="h-20 flex items-center px-6 border-b border-slate-100 gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#1E3A5F] flex items-center justify-center text-white font-extrabold text-lg shadow-sm">EL</div>
                <div>
                    <h1 class="text-sm font-bold text-[#1E3A5F] leading-tight">E-LAPOR KABAG</h1>
                    <p class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">Portal Dekanat</p>
                </div>
            </div>
            <nav class="p-4 space-y-1.5 text-sm">
                <a href="{{ route('kabag.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-100 transition">
                    <span>Laporan Pelayanan</span>
                </a>
                <a href="{{ route('kabag.whistleblowing') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-amber-800 hover:bg-amber-50 transition">
                    <span>Whistleblowing</span>
                </a>
                <a href="{{ route('kabag.my-reports') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-100 transition">
                    <span>Laporan Saya</span>
                </a>
                <a href="{{ route('kabag.history') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-[#1E3A5F] text-white shadow-sm transition">
                    <span>Riwayat Laporan</span>
                </a>
            </nav>
        </div>
    </aside>

    <div class="flex-grow flex flex-col min-w-0">
        <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h2 class="text-lg font-bold text-slate-900 leading-tight">Riwayat Pengaduan Ditutup</h2>
                <p class="text-xs text-slate-500">Laporan pelayanan yang telah berstatus selesai atau ditolak</p>
            </div>
        </header>

        <main class="p-6 sm:p-8 flex-grow">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/80 text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Kode Tiket</th>
                                <th class="px-6 py-4">Pelapor</th>
                                <th class="px-6 py-4">Judul</th>
                                <th class="px-6 py-4">Tujuan</th>
                                <th class="px-6 py-4">Status Akhir</th>
                                <th class="px-6 py-4">Rating</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($reports as $report)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-[#1E3A5F]">{{ $report->ticket_code }}</td>
                                    <td class="px-6 py-4">{{ $report->user?->name ?? 'Anonim' }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $report->title }}</td>
                                    <td class="px-6 py-4">{{ $report->subDepartment?->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                                            {{ $report->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($report->rating)
                                            <span class="font-bold text-amber-600">⭐ {{ $report->rating }}/5</span>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">Belum ada riwayat laporan ditutup.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
