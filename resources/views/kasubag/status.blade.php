<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Status Petugas - KASUBAG E-LAPOR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between">
    <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-3">
            <a href="{{ route('kasubag.dashboard') }}" class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-base font-bold text-slate-900 leading-tight">Monitoring Progres Tugas Petugas</h1>
                <p class="text-xs text-slate-500">Pantau status pengerjaan laporan oleh petugas lapangan</p>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 w-full flex-grow">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Kode Tiket</th>
                            <th class="px-6 py-4">Judul</th>
                            <th class="px-6 py-4">Petugas Ditugaskan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-[#1E3A5F]">{{ $report->ticket_code }}</td>
                                <td class="px-6 py-4 font-medium">{{ $report->title }}</td>
                                <td class="px-6 py-4 text-xs">{{ $report->handler?->name ?? 'Belum Ditugaskan' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase
                                        @if ($report->status === 'didisposisi') bg-amber-100 text-amber-800
                                        @elseif ($report->status === 'diterima') bg-blue-100 text-blue-800
                                        @elseif ($report->status === 'proses') bg-indigo-100 text-indigo-800
                                        @elseif ($report->status === 'verifikasi_kabag') bg-purple-100 text-purple-800
                                        @endif">
                                        {{ str_replace('_', ' ', $report->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('kasubag.show', $report) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                                        Detail &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">Belum ada tugas yang berjalan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
