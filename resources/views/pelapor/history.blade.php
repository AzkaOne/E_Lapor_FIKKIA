<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan & Penilaian - E-LAPOR FIKKIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between">

    <!-- TopHeader matching Figma node 64:216 -->
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
            <a href="{{ route('pelapor.status') }}" class="py-4 border-b-2 border-transparent hover:border-slate-300 text-sm font-semibold text-slate-500 hover:text-[#0a8080] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span>Status Laporan Aktif</span>
            </a>
            <a href="{{ route('pelapor.history') }}" class="py-4 border-b-2 border-[#0a8080] text-sm font-bold text-[#0a8080] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Riwayat Laporan & Rating</span>
            </a>
        </div>
    </div>

    <!-- Main Content matching Figma node 64:201 -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-[#ecfdf5] border border-[#a7f3d0] text-[#064e3b] text-sm font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat Laporan Selesai</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar semua pengaduan Anda yang telah diselesaikan atau ditutup.</p>
        </div>

        @if ($completedReports->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm max-w-lg mx-auto my-8">
                <div class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Belum Ada Riwayat</h3>
                <p class="text-xs text-slate-500 mt-1">Laporan yang telah selesai diverifikasi oleh Kabag akan muncul di halaman ini.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($completedReports as $report)
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
                                @if ($report->status === 'selesai')
                                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]">
                                        <span class="w-2 h-2 rounded-full bg-[#059669] mr-2"></span>
                                        Selesai {{ $report->execution_result ? '('.ucfirst($report->execution_result).')' : '' }}
                                    </span>
                                @elseif ($report->status === 'ditolak')
                                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                        <span class="w-2 h-2 rounded-full bg-red-600 mr-2"></span>
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-sm text-slate-600 mb-6 leading-relaxed">{{ $report->description }}</p>

                        <!-- Resolution Notes -->
                        @if ($report->resolution_notes || $report->postponed_reason || $report->rejection_reason)
                            <div class="p-4 rounded-2xl bg-[#f0fdfa] border border-[#ccfbf1] mb-6 text-xs text-slate-700">
                                <span class="font-bold text-[#0a8080] block mb-1">Catatan Hasil Tindak Lanjut:</span>
                                <p>{{ $report->resolution_notes ?? $report->postponed_reason ?? $report->rejection_reason }}</p>
                            </div>
                        @endif

                        <!-- Rating Section (Only for Selesai + Terlaksana) matching specification -->
                        @if ($report->status === 'selesai' && $report->execution_result === 'terlaksana')
                            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">Penilaian Kepuasan Pelayanan</h4>
                                    <p class="text-xs text-slate-500">Berikan bintang 1 hingga 5 terhadap kualitas penyelesaian laporan ini</p>
                                </div>

                                @if ($report->rating)
                                    <div class="flex items-center gap-1.5 bg-amber-50 px-4 py-2 rounded-2xl border border-amber-200">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $report->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="text-xs font-bold text-amber-800 ml-2">Rating: {{ $report->rating }}/5</span>
                                    </div>
                                @else
                                    <form action="{{ route('pelapor.rate', $report) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <div class="flex items-center gap-1" x-data="{ selectedRating: 5 }">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <button type="button" @click="selectedRating = {{ $i }}"
                                                    class="p-1 text-slate-300 hover:text-amber-400 transition"
                                                    :class="{ 'text-amber-400 fill-amber-400': selectedRating >= {{ $i }} }">
                                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                </button>
                                            @endfor
                                            <input type="hidden" name="rating" :value="selectedRating">
                                        </div>
                                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#0a8080] text-white hover:bg-[#086666] transition shadow-sm">
                                            Kirim Nilai
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
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
