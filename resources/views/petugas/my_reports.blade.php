<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengerjaan Tugas Lapangan - PETUGAS E-LAPOR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between">

    <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-3">
            <a href="{{ route('petugas.dashboard') }}" class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-base font-bold text-slate-900 leading-tight">Laporan Saya (Sedang Dikerjakan)</h1>
                <p class="text-xs text-slate-500">Isi bukti pengerjaan teknis dan kirimkan hasilnya ke KABAG</p>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 w-full flex-grow">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($reports->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm my-8">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-[#1E3A5F] flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Tidak Ada Tugas yang Aktif Diproses</h3>
                <p class="text-xs text-slate-500 mt-1 mb-6">Buka tab Laporan Ditugaskan dan klik "Mulai Kerjakan" untuk memulai tugas.</p>
                <a href="{{ route('petugas.dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-[#1E3A5F] text-white">
                    Lihat Tugas Masuk &rarr;
                </a>
            </div>
        @else
            <div class="space-y-8">
                @foreach ($reports as $report)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                        <div class="border-b border-slate-100 pb-4 mb-4">
                            <div class="flex items-center gap-3 text-xs text-slate-500 mb-1">
                                <span class="font-mono font-bold text-[#1E3A5F]">{{ $report->ticket_code }}</span>
                                <span>&bull;</span>
                                <span>Kampus <strong>{{ ucfirst($report->campus_location) }}</strong></span>
                                <span>&bull;</span>
                                <span>Pelapor: {{ $report->user?->name ?? 'Civitas' }}</span>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900">{{ $report->title }}</h3>
                        </div>

                        <p class="text-sm text-slate-700 whitespace-pre-line leading-relaxed mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            {{ $report->description }}
                        </p>

                        <!-- Form Penyelesaian matching Figma node 207:997 -->
                        <form action="{{ route('petugas.complete', $report) }}" method="POST" enctype="multipart/form-data" class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 space-y-4">
                            @csrf
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#1E3A5F]">Penyelesaian Tugas Lapangan</h4>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Opsi Hasil Pelaksanaan <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="flex items-center p-3 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-emerald-500">
                                        <input type="radio" name="execution_result" value="terlaksana" checked class="text-emerald-600">
                                        <span class="ml-2 text-xs font-bold text-emerald-800">Terlaksana (Tuntas Selesai)</span>
                                    </label>
                                    <label class="flex items-center p-3 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-amber-500">
                                        <input type="radio" name="execution_result" value="tertunda" class="text-amber-600">
                                        <span class="ml-2 text-xs font-bold text-amber-800">Tertunda (Ada Kendala Teknis)</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Catatan Pengerjaan / Penjelasan Kendala <span class="text-red-500">*</span></label>
                                <textarea name="notes" rows="3" required placeholder="Tuliskan tindakan perbaikan atau kendala yang ditemui..."
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm bg-white"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Upload Bukti Foto Hasil Pengerjaan (Opsional)</label>
                                <input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf"
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-[#1E3A5F]">
                                <p class="text-[11px] text-slate-400 mt-1">Upload foto hasil perbaikan di lokasi kampus</p>
                            </div>

                            <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold bg-[#1E3A5F] hover:bg-[#152a45] text-white text-xs transition shadow-sm">
                                Kirim Laporan untuk Diverifikasi KABAG
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
