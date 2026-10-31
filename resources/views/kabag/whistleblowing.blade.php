<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Whistleblowing (Eksklusif KABAG) - E-LAPOR FIKKIA</title>
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
                <div class="w-10 h-10 rounded-xl bg-[#1E3A5F] flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                    EL
                </div>
                <div>
                    <h1 class="text-sm font-bold text-[#1E3A5F] leading-tight">E-LAPOR KABAG</h1>
                    <p class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">Portal Dekanat</p>
                </div>
            </div>

            <nav class="p-4 space-y-1.5 text-sm">
                <a href="{{ route('kabag.dashboard') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Laporan Pelayanan</span>
                </a>
                <a href="{{ route('kabag.whistleblowing') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-amber-500 text-white shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>Whistleblowing</span>
                </a>
                <a href="{{ route('kabag.my-reports') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Laporan Saya</span>
                </a>
                <a href="{{ route('kabag.history') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Riwayat Laporan</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2 px-3 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:text-red-600 hover:bg-red-50 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content matching Figma node 218:2914 -->
    <div class="flex-grow flex flex-col min-w-0">
        <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h2 class="text-lg font-bold text-slate-900 leading-tight">Pengaduan Anonim / Whistleblowing</h2>
                <p class="text-xs text-amber-700 font-medium">Kanal Rahasia Khusus Dekanat/KABAG</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200">
                Akses Terbatas
            </span>
        </header>

        <main class="p-6 sm:p-8 flex-grow">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="space-y-6">
                @forelse ($reports as $report)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 mb-4 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-base font-bold text-[#1E3A5F]">{{ $report->ticket_code }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        {{ $report->subDepartment?->name ?? 'Dekanat' }}
                                    </span>
                                    <span class="text-xs text-slate-400">Kampus {{ ucfirst($report->campus_location) }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 mt-1">{{ $report->title }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Diterima {{ $report->created_at->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                            <div>
                                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase
                                    @if ($report->status === 'diajukan') bg-amber-100 text-amber-800
                                    @elseif ($report->status === 'proses') bg-blue-100 text-blue-800
                                    @elseif ($report->status === 'selesai') bg-emerald-100 text-emerald-800
                                    @endif">
                                    {{ $report->status }}
                                </span>
                            </div>
                        </div>

                        <div class="text-sm text-slate-700 whitespace-pre-line leading-relaxed mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            {{ $report->description }}
                        </div>

                        @if ($report->evidences->isNotEmpty())
                            <div class="mb-6 flex gap-2">
                                @foreach ($report->evidences as $ev)
                                    <a href="{{ asset('storage/' . $ev->file_path) }}" target="_blank"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:text-blue-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <span>Lihat Bukti Terlampir</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Action Form -->
                        @if ($report->status === 'diajukan')
                            <form action="{{ route('kabag.whistleblowing.process', $report) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 rounded-xl font-bold bg-[#1E3A5F] hover:bg-[#152a45] text-white text-xs transition shadow-sm">
                                    Mulai Tindak Lanjut & Proses
                                </button>
                            </form>
                        @elseif ($report->status === 'proses')
                            <form action="{{ route('kabag.whistleblowing.complete', $report) }}" method="POST" class="bg-amber-50/60 p-5 rounded-2xl border border-amber-200 space-y-3">
                                @csrf
                                <h4 class="text-xs font-bold uppercase text-amber-800">Selesaikan Tindak Lanjut Whistleblowing</h4>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Hasil Tindak Lanjut / Respon (Dapat dilihat Pelapor via Kode Tiket)</label>
                                    <textarea name="resolution_notes" rows="2" required placeholder="Tuliskan hasil evaluasi pimpinan..."
                                        class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs bg-white"></textarea>
                                </div>
                                <button type="submit" class="px-5 py-2.5 rounded-xl font-bold bg-emerald-600 hover:bg-emerald-700 text-white text-xs transition shadow-sm">
                                    Tandai Laporan Selesai Ditangani
                                </button>
                            </form>
                        @else
                            <div class="p-4 rounded-xl bg-emerald-50 text-xs text-emerald-800 font-medium">
                                Laporan telah selesai ditangani. Catatan: {{ $report->resolution_notes }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm">
                        <p class="text-xs text-slate-400">Belum ada laporan whistleblowing yang masuk.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $reports->links() }}
            </div>
        </main>
    </div>
</body>
</html>
