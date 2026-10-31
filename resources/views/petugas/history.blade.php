<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Selesai - Petugas E-LAPOR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'teal-brand': '#0a8080',
                        'teal-dark': '#0c3934',
                        'teal-active': '#1a5b51',
                        'teal-light': '#ccfbf1',
                        'emerald-surface': '#ecfdf5',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0a8080] selection:text-white">
    <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('petugas.dashboard') }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-[#0a8080] hover:bg-slate-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-lg font-black text-[#0c3934] leading-tight">Riwayat Pengaduan Selesai & Dalam Verifikasi</h1>
                <p class="text-xs text-slate-500">Daftar penugasan lapangan yang telah Anda rampungkan</p>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 w-full flex-grow">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#f0fdfa] text-xs font-bold uppercase tracking-wider text-[#0c3934] border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Kode Tiket</th>
                            <th class="px-6 py-4">Judul</th>
                            <th class="px-6 py-4">Hasil Kerja</th>
                            <th class="px-6 py-4">Status Siklus</th>
                            <th class="px-6 py-4">Rating</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-[#0a8080]">{{ $report->ticket_code }}</td>
                                <td class="px-6 py-4 font-medium">{{ $report->title }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $report->execution_result === 'terlaksana' ? 'bg-[#ecfdf5] text-[#064e3b]' : 'bg-amber-50 text-amber-700' }}">
                                        {{ ucfirst($report->execution_result ?? '-') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                                        {{ $report->status === 'selesai' ? 'bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $report->status === 'verifikasi_kabag' ? 'Menunggu Verifikasi' : 'Selesai' }}
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
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">Belum ada riwayat penugasan yang selesai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($reports->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>
