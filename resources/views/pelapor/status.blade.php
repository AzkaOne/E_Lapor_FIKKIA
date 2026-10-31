<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Laporan - E-LAPOR FIKKIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between">

    <!-- TopHeader matching Figma node 53:429 -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-[#0c3934] flex items-center justify-center text-white font-extrabold text-xl shadow-md">
                    EL
                </div>
                <div class="border-l border-slate-200 pl-4 py-1">
                    <h1 class="text-base sm:text-lg font-bold text-[#0c3934] leading-tight tracking-tight">E-LAPOR FIKKIA</h1>
                    <p class="text-xs text-slate-500 font-medium">Universitas Airlangga Kampus Banyuwangi</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 uppercase">{{ auth()->user()->identity_number }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2.5 rounded-xl border border-slate-200 text-slate-600 hover:text-red-600 hover:bg-red-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Sub-Navbar Navigation Tabs -->
    <div class="bg-white border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex space-x-2 sm:space-x-8">
            <a href="{{ route('pelapor.dashboard') }}" class="py-4 border-b-2 border-transparent hover:border-slate-300 text-sm font-semibold text-slate-500 hover:text-[#0a8080] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Buat Laporan Baru</span>
            </a>
            <a href="{{ route('pelapor.status') }}" class="py-4 border-b-2 border-[#0a8080] text-sm font-bold text-[#0a8080] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span>Status Laporan Aktif</span>
            </a>
            <a href="{{ route('pelapor.history') }}" class="py-4 border-b-2 border-transparent hover:border-slate-300 text-sm font-semibold text-slate-500 hover:text-[#0a8080] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Riwayat Laporan & Rating</span>
            </a>
        </div>
    </div>

    <!-- Main Container matching Figma node 53:413 -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Status Pengaduan Berjalan</h2>
            <p class="text-sm text-slate-500 mt-1">Laporan yang sedang ditinjau, didisposisikan, atau dalam pengerjaan teknis.</p>
        </div>

        @if ($reports->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm max-w-lg mx-auto my-8">
                <div class="w-16 h-16 rounded-2xl bg-[#ecfdf5] text-[#0a8080] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Belum Ada Laporan Aktif</h3>
                <p class="text-xs text-slate-500 mt-1 mb-6">Anda tidak memiliki laporan yang sedang diproses saat ini.</p>
                <a href="{{ route('pelapor.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl font-bold bg-[#0a8080] text-white text-sm shadow hover:bg-[#086666] transition">
                    + Buat Laporan Sekarang
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($reports as $report)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 mb-4 gap-2">
                            <div>
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-base font-bold text-[#0a8080]">{{ $report->ticket_code }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]">
                                        {{ $report->subDepartment?->name }}
                                    </span>
                                    <span class="text-xs text-slate-400">Kampus {{ ucfirst($report->campus_location) }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 mt-1">{{ $report->title }}</h3>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold uppercase
                                    {{ $report->status === 'diajukan' ? 'bg-amber-100 text-amber-800' : 'bg-[#ccfbf1] text-[#0f766e]' }}">
                                    <span class="w-2 h-2 rounded-full mr-2 {{ $report->status === 'diajukan' ? 'bg-amber-500' : 'bg-[#0a8080]' }} animate-pulse"></span>
                                    Status: {{ $report->status }}
                                </span>
                            </div>
                        </div>

                        <p class="text-sm text-slate-600 mb-6 leading-relaxed">{{ $report->description }}</p>

                        <!-- Dispositions tracker -->
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Catatan & Histori Alur</h4>
                            @if ($report->dispositions->isEmpty())
                                <p class="text-xs text-slate-400 italic">Laporan baru diajukan dan sedang menunggu review KABAG.</p>
                            @else
                                <div class="space-y-2 text-xs">
                                    @foreach ($report->dispositions as $disp)
                                        <div class="flex items-start gap-2 text-slate-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#0a8080] mt-1.5 shrink-0"></span>
                                            <div>
                                                <span class="font-bold text-[#0a8080]">{{ $disp->sender?->name }} ({{ strtoupper($disp->sender?->role) }}):</span>
                                                <span class="text-slate-600">{{ $disp->notes }}</span>
                                                <span class="text-[10px] text-slate-400 ml-1">({{ $disp->created_at->diffForHumans() }})</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
            <p>&copy; 2026 FIKKIA Universitas Airlangga.</p>
            <p class="font-medium text-slate-700">E-Lapor. Suara Anda, Perubahan Nyata.</p>
            <div>Kampus Mojo &bull; Sobo &bull; Giri</div>
        </div>
    </footer>
</body>
</html>
