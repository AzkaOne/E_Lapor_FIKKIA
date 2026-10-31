<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Selesai - KASUBAG E-LAPOR</title>
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
                <h1 class="text-base font-bold text-slate-900 leading-tight">Riwayat Pengaduan Ditutup (Sub Bagian)</h1>
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
                            <th class="px-6 py-4">Petugas</th>
                            <th class="px-6 py-4">Status Akhir</th>
                            <th class="px-6 py-4">Rating</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-[#1E3A5F]">{{ $report->ticket_code }}</td>
                                <td class="px-6 py-4 font-medium">{{ $report->title }}</td>
                                <td class="px-6 py-4 text-xs">{{ $report->handler?->name ?? '-' }}</td>
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
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">Belum ada riwayat selesai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
