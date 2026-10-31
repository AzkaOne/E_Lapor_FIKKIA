<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Laporan - KABAG E-LAPOR</title>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0a8080] selection:text-white" x-data="{ approveModal: false, revisiModal: false, activeReport: null }">
    <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('kabag.dashboard') }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-[#0a8080] hover:bg-slate-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-lg font-black text-[#0c3934] leading-tight">Verifikasi & Validasi Laporan Selesai</h1>
                <p class="text-xs text-slate-500">Tinjau hasil pengerjaan di lapangan sebelum penutupan tiket atau pengajuan revisi</p>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 w-full flex-grow">
        @if (session('success'))
            <div class="mb-6 p-4 bg-[#ecfdf5] border border-[#a7f3d0] text-[#064e3b] rounded-2xl text-sm font-semibold flex items-center gap-3">
                <svg class="w-5 h-5 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Menunggu Verifikasi Akhir</h2>
                    <p class="text-xs text-slate-500">Laporan yang telah ditandai terlaksana atau tertunda oleh Kasubag/Petugas</p>
                </div>
                <span class="px-3 py-1 bg-[#ccfbf1] text-[#0f766e] rounded-full text-xs font-bold">{{ $reports->total() }} Tiket</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($reports as $report)
                    <div class="p-6 hover:bg-slate-50/50 transition flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-2 flex-grow max-w-2xl">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded text-xs font-mono font-bold bg-[#ecfdf5] text-[#0a8080] border border-[#a7f3d0]">{{ $report->ticket_code }}</span>
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold uppercase {{ $report->execution_result === 'terlaksana' ? 'bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]' : 'bg-amber-100 text-amber-800' }}">
                                    Hasil: {{ $report->execution_result }}
                                </span>
                                <span class="text-xs text-slate-400">• {{ $report->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">{{ $report->title }}</h3>
                            <p class="text-sm text-slate-600 line-clamp-2">{{ $report->description }}</p>

                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/60 mt-3 text-xs text-slate-700 space-y-1">
                                <div class="font-semibold text-slate-900">Catatan Pelaksana ({{ $report->handler?->name ?? 'Kasubag' }}):</div>
                                <p class="text-slate-600 italic">"{{ $report->resolution_notes ?? 'Tidak ada catatan khusus.' }}"</p>
                                @if ($report->evidences->count() > 0)
                                    <div class="mt-2 pt-2 border-t border-slate-200 flex items-center gap-2">
                                        <span class="font-semibold">Bukti:</span>
                                        @foreach ($report->evidences as $ev)
                                            <a href="{{ asset('storage/' . $ev->file_path) }}" target="_blank" class="text-[#0a8080] underline hover:text-[#086666] font-medium">
                                                Lihat Dokumen / Foto
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
                            <button @click="activeReport = {{ json_encode($report) }}; approveModal = true"
                                class="px-4 py-2.5 bg-[#0a8080] hover:bg-[#086666] text-white rounded-xl text-sm font-bold shadow-sm transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Verifikasi Selesai</span>
                            </button>
                            <button @click="activeReport = {{ json_encode($report) }}; revisiModal = true"
                                class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold shadow-sm transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span>Minta Revisi</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400 text-sm">
                        Tidak ada laporan yang menunggu verifikasi KABAG saat ini.
                    </div>
                @endforelse
            </div>
            @if ($reports->hasPages())
                <div class="p-6 border-t border-slate-100">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </main>

    <!-- Modal Approve -->
    <div x-show="approveModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4" @click.away="approveModal = false">
            <h3 class="text-lg font-bold text-[#0c3934]">Konfirmasi Verifikasi Laporan</h3>
            <p class="text-xs text-slate-500">Laporan akan ditutup dengan status <strong>Selesai</strong> dan pelapor akan diizinkan memberikan rating evaluasi layanan.</p>

            <form :action="'/kabag/verifikasi/' + activeReport?.id + '/approve'" method="POST" class="space-y-4">
                @csrf
                <div x-show="activeReport?.execution_result === 'tertunda'">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alasan Penundaan Resmi *</label>
                    <textarea name="notes" rows="3" class="w-full text-sm rounded-xl border-slate-200 focus:border-[#0a8080] focus:ring-[#0a8080]" placeholder="Tuliskan keterangan mengapa laporan ini disetujui untuk ditunda..."></textarea>
                </div>
                <div x-show="activeReport?.execution_result === 'terlaksana'">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Verifikasi (Opsional)</label>
                    <textarea name="notes" rows="3" class="w-full text-sm rounded-xl border-slate-200 focus:border-[#0a8080] focus:ring-[#0a8080]" placeholder="Catatan apresiasi atau tindak lanjut..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="approveModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#0a8080] hover:bg-[#086666] text-white rounded-xl text-sm font-bold shadow-md">Selesaikan Tiket</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Revisi -->
    <div x-show="revisiModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4" @click.away="revisiModal = false">
            <h3 class="text-lg font-bold text-[#0c3934]">Kembalikan untuk Revisi Pengerjaan</h3>
            <p class="text-xs text-slate-500">Laporan akan dikembalikan ke Kasubag / Petugas dengan catatan perbaikan yang wajib dipenuhi.</p>

            <form :action="'/kabag/verifikasi/' + activeReport?.id + '/revisi'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Revisi *</label>
                    <textarea name="notes" rows="4" required class="w-full text-sm rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500" placeholder="Jelaskan secara rinci poin-poin yang belum sesuai atau perlu diperbaiki..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="revisiModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold shadow-md">Kirim Perintah Revisi</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
