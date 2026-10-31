<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Whistleblowing (Eksklusif KABAG) - E-LAPOR FIKKIA</title>
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0a8080] selection:text-white">

    <!-- Top Navbar -->
    @include('components.navbar')

    <div class="flex-grow flex">
        <!-- Sidebar matching Figma Page 3 -->
        @include('components.sidebar')

        <!-- Main Content matching Figma node 218:2914 -->
        <div class="flex-grow flex flex-col min-w-0">
            <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
                <div>
                    <h2 class="text-xl font-black text-[#0c3934] leading-tight">Pengaduan Anonim / Whistleblowing</h2>
                    <p class="text-xs text-amber-700 font-medium">Kanal Rahasia Khusus Dekanat/KABAG</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]">
                    Akses Khusus Pimpinan
                </span>
            </header>

            <main class="p-6 sm:p-8 flex-grow max-w-7xl w-full mx-auto">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-[#ecfdf5] border border-[#a7f3d0] text-[#064e3b] text-sm font-semibold flex items-center gap-3">
                        <svg class="w-5 h-5 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="space-y-6">
                    @forelse ($reports as $report)
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 mb-4 gap-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-base font-bold text-[#0a8080]">{{ $report->ticket_code }}</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]">
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
                                        @elseif ($report->status === 'proses') bg-[#ccfbf1] text-[#0f766e]
                                        @elseif ($report->status === 'selesai') bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]
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
                                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:text-[#0a8080]">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            <span>Lihat Bukti Terlampir</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Action Form -->
                            @if ($report->status === 'diajukan')
                                <form action="{{ route('kabag.whistleblowing.process', $report) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold bg-[#0a8080] hover:bg-[#086666] text-white text-xs transition shadow-sm">
                                        Mulai Tindak Lanjut & Proses
                                    </button>
                                </form>
                            @elseif ($report->status === 'proses')
                                <form action="{{ route('kabag.whistleblowing.complete', $report) }}" method="POST" class="bg-[#f0fdfa] p-5 rounded-2xl border border-[#ccfbf1] space-y-3">
                                    @csrf
                                    <h4 class="text-xs font-bold uppercase text-[#0c3934]">Selesaikan Tindak Lanjut Whistleblowing</h4>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Hasil Tindak Lanjut / Respon (Dapat dilihat Pelapor via Kode Tiket)</label>
                                        <textarea name="resolution_notes" rows="2" required placeholder="Tuliskan hasil evaluasi pimpinan..."
                                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs bg-white focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080]"></textarea>
                                    </div>
                                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold bg-[#0a8080] hover:bg-[#086666] text-white text-xs transition shadow-sm">
                                        Tandai Laporan Selesai Ditangani
                                    </button>
                                </form>
                            @else
                                <div class="p-4 rounded-xl bg-[#ecfdf5] text-xs text-[#064e3b] font-medium border border-[#a7f3d0]">
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
    </div>
</body>
</html>
