<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya (Mandiri) - KASUBAG E-LAPOR</title>
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
            <a href="{{ route('kasubag.dashboard') }}" class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-base font-bold text-slate-900 leading-tight">Laporan Saya (Pengerjaan Mandiri Kasubag)</h1>
                <p class="text-xs text-slate-500">Laporan yang diselesaikan langsung oleh Kasubag dan dikirim untuk verifikasi Kabag</p>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 w-full flex-grow">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($reports->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm my-8">
                <h3 class="text-lg font-bold text-slate-900">Tidak Ada Tugas Mandiri</h3>
                <p class="text-xs text-slate-500 mt-1">Laporan yang Anda pilih "Kerjakan Sendiri" akan tampil di sini.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($reports as $report)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 mb-4 gap-2">
                            <div>
                                <span class="font-mono text-sm font-bold text-[#1E3A5F]">{{ $report->ticket_code }}</span>
                                <h3 class="text-xl font-bold text-slate-900 mt-1">{{ $report->title }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Kampus {{ ucfirst($report->campus_location) }}</p>
                            </div>
                            <div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                    @if ($report->status === 'verifikasi_kabag') bg-purple-100 text-purple-800
                                    @elseif ($report->status === 'selesai') bg-emerald-100 text-emerald-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ str_replace('_', ' ', $report->status) }}
                                </span>
                            </div>
                        </div>

                        <p class="text-sm text-slate-600 mb-6">{{ $report->description }}</p>

                        @if ($report->status === 'proses')
                            <!-- Form kirim verifikasi ke Kabag -->
                            <form action="{{ route('kasubag.submit-verification', $report) }}" method="POST" enctype="multipart/form-data" class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-4">
                                @csrf
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#1E3A5F]">Penyelesaian & Kirim ke Verifikasi KABAG</h4>

                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Hasil Pelaksanaan <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="flex items-center p-3 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-emerald-500">
                                            <input type="radio" name="execution_result" value="terlaksana" checked class="text-emerald-600">
                                            <span class="ml-2 text-xs font-bold text-emerald-800">Terlaksana (Tuntas)</span>
                                        </label>
                                        <label class="flex items-center p-3 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-amber-500">
                                            <input type="radio" name="execution_result" value="tertunda" class="text-amber-600">
                                            <span class="ml-2 text-xs font-bold text-amber-800">Tertunda (Ada Kendala)</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Catatan Hasil Pengerjaan <span class="text-red-500">*</span></label>
                                    <textarea name="notes" rows="3" required placeholder="Tuliskan detail pekerjaan atau kendala yang dihadapi..."
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm bg-white"></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Upload Bukti Foto / Dokumen (Opsional)</label>
                                    <input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf"
                                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-[#1E3A5F]">
                                </div>

                                <button type="submit" class="py-3 px-6 rounded-xl font-bold bg-[#1E3A5F] hover:bg-[#152a45] text-white text-xs transition shadow-sm">
                                    Kirim Laporan untuk Diverifikasi KABAG
                                </button>
                            </form>
                        @elseif ($report->status === 'verifikasi_kabag')
                            <div class="p-4 rounded-xl bg-purple-50 text-xs text-purple-800 font-semibold border border-purple-200">
                                Sedang dalam antrean verifikasi akhir oleh KABAG.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
