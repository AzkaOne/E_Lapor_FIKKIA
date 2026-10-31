<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Status Tiket - E-LAPOR FIKKIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-[#1E3A5F] flex items-center justify-center text-white font-extrabold text-xl shadow-md">
                    EL
                </div>
                <div class="border-l border-slate-200 pl-4 py-1">
                    <h1 class="text-base sm:text-lg font-bold text-[#1E3A5F] leading-tight tracking-tight">E-LAPOR FIKKIA</h1>
                    <p class="text-xs text-slate-500 font-medium">Universitas Airlangga Kampus Banyuwangi</p>
                </div>
            </div>

            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-[#1E3A5F] transition px-3 py-2 rounded-xl hover:bg-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow max-w-4xl mx-auto px-4 sm:px-6 py-10 w-full">
        <!-- Search Header -->
        <div class="text-center mb-8">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-[#1E3A5F] border border-blue-100 mb-2">
                Pelacakan Mandiri Publik
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Lacak Status Laporan</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau perkembangan tindak lanjut pengaduan Anda menggunakan kode tiket</p>

            <form action="{{ route('tracking') }}" method="GET" class="mt-6 max-w-md mx-auto flex gap-2">
                <input type="text" name="ticket_code" value="{{ $ticketCode }}" required
                    placeholder="Masukkan Kode Tiket (misal: WB-2026-XXXXX)"
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 font-mono text-sm uppercase focus:ring-2 focus:ring-[#1E3A5F] focus:border-[#1E3A5F] shadow-sm">
                <button type="submit"
                    class="px-6 py-3 bg-[#1E3A5F] hover:bg-[#152a45] text-white font-semibold rounded-xl shadow-md transition shrink-0">
                    Cari
                </button>
            </form>
        </div>

        @if ($notFound)
            <div class="p-8 rounded-3xl bg-white border border-red-200 text-center shadow-md">
                <div class="w-14 h-14 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Kode tiket tidak ditemukan</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    Kode tiket <strong>{{ $ticketCode }}</strong> tidak terdaftar pada sistem kami. Pastikan format dan karakter kode tiket sudah sesuai.
                </p>
            </div>
        @elseif ($report)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-lg p-6 sm:p-8">
                <!-- Ticket Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-6 mb-6 gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-lg font-bold text-[#1E3A5F]">{{ $report->ticket_code }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $report->type === 'whistleblowing' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $report->type === 'whistleblowing' ? 'Laporan Anonim' : 'Laporan Pelayanan' }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">{{ $report->title }}</h3>
                        <p class="text-xs text-slate-400 mt-1">
                            Diajukan pada {{ $report->created_at->translatedFormat('d F Y, H:i') }} &bull; Lokasi: Kampus {{ ucfirst($report->campus_location) }}
                        </p>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        @if ($report->status === 'diajukan')
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                                Menunggu Verifikasi
                            </span>
                        @elseif (in_array($report->status, ['didisposisi', 'diterima', 'proses', 'verifikasi_kabag']))
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                <span class="w-2 h-2 rounded-full bg-blue-600 mr-2 animate-pulse"></span>
                                Sedang Diproses
                            </span>
                        @elseif ($report->status === 'selesai')
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-600 mr-2"></span>
                                Selesai
                            </span>
                        @elseif ($report->status === 'ditolak')
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                <span class="w-2 h-2 rounded-full bg-red-600 mr-2"></span>
                                Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Tracker Steps -->
                <div class="mb-8">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Perjalanan Laporan</h4>
                    <div class="grid grid-cols-3 gap-2 text-center text-xs">
                        <div class="p-3 rounded-xl {{ in_array($report->status, ['diajukan', 'didisposisi', 'diterima', 'proses', 'verifikasi_kabag', 'selesai']) ? 'bg-blue-50 text-[#1E3A5F] font-bold border border-blue-200' : 'bg-slate-50 text-slate-400' }}">
                            1. Diajukan
                        </div>
                        <div class="p-3 rounded-xl {{ in_array($report->status, ['didisposisi', 'diterima', 'proses', 'verifikasi_kabag', 'selesai']) ? 'bg-blue-50 text-[#1E3A5F] font-bold border border-blue-200' : 'bg-slate-50 text-slate-400' }}">
                            2. Diproses
                        </div>
                        <div class="p-3 rounded-xl {{ $report->status === 'selesai' ? 'bg-emerald-50 text-emerald-700 font-bold border border-emerald-200' : 'bg-slate-50 text-slate-400' }}">
                            3. Selesai
                        </div>
                    </div>
                </div>

                <!-- Resolution Notes -->
                @if ($report->resolution_notes || $report->postponed_reason || $report->rejection_reason)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 mb-6">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#1E3A5F] mb-1">Catatan Tindak Lanjut Pimpinan / Kabag</h4>
                        <p class="text-sm text-slate-700 whitespace-pre-line">
                            {{ $report->resolution_notes ?? $report->postponed_reason ?? $report->rejection_reason }}
                        </p>
                    </div>
                @endif

                <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-100 text-xs text-amber-800 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Laporan ini dijaga kerahasiaannya. Seluruh identitas pelapor anonim tidak ditampilkan kepada pihak manapun.</span>
                </div>
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
