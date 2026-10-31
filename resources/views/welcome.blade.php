<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-LAPOR FIKKIA UNAIR - Layanan Aspirasi & Pengaduan Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#1E3A5F] selection:text-white">

    <!-- TopHeader matching Figma node 35:579 -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-[#1E3A5F] flex items-center justify-center text-white font-extrabold text-xl shadow-md border border-blue-900">
                    EL
                </div>
                <div class="border-l border-slate-200 pl-4 py-1">
                    <h1 class="text-base sm:text-lg font-bold text-[#1E3A5F] leading-tight tracking-tight">E-LAPOR FIKKIA</h1>
                    <p class="text-xs text-slate-500 font-medium">Fakultas Ilmu Kesehatan, Kedokteran, dan Ilmu Alam - UNAIR</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                @auth
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" 
                        class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-[#1E3A5F] text-white hover:bg-[#152a45] shadow-sm transition">
                        Dashboard Saya
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                        class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:text-[#1E3A5F] hover:bg-slate-100 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                        class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-[#1E3A5F] text-white hover:bg-[#152a45] shadow-sm transition">
                        Daftar Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        <!-- Hero Header matching Figma node 35:889 -->
        <div class="max-w-3xl mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-[#1E3A5F] border border-blue-200 mb-3 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-[#2563EB] animate-pulse"></span>
                E-Lapor FIKKIA Banyuwangi
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Pilih Jenis Laporan
            </h2>
            <p class="text-base text-slate-600 mt-2 leading-relaxed">
                Sampaikan aspirasi, kendala operasional kampus (Mojo, Sobo, Giri), atau laporan pelanggaran secara terarah. Pastikan data yang Anda masukkan tepat agar proses penanganan berjalan cepat dan terukur.
            </p>
        </div>

        <!-- 2 Main Cards Grid matching Figma nodes 36:1068 & 36:1069 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Card 1: Laporan Pelayanan -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-md hover:shadow-xl transition duration-300 flex flex-col justify-between relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -z-0 transition-all group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-[#1E3A5F] flex items-center justify-center shadow-inner">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-[#1E3A5F] border border-blue-100">
                            Terautentikasi (NIM/NIP)
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 group-hover:text-[#1E3A5F] transition">
                        Laporan Pelayanan
                    </h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        Digunakan untuk membuat laporan pelayanan, fasilitas fisik, sistem akademik, SDM & keuangan menggunakan akun resmi UNAIR.
                    </p>

                    <!-- Features checklist -->
                    <div class="mt-6 space-y-3 bg-slate-50/80 p-5 rounded-2xl border border-slate-100 text-sm">
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-slate-700">Masuk menggunakan NIM/NIP & email resmi <strong>@unair.ac.id</strong></span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-slate-700">Identitas tercatat untuk validitas & respon resmi dari dekanat</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-slate-700">Riwayat laporan tersimpan otomatis di dashboard pribadi</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-slate-700">Beri penilaian (rating bintang 1–5) setelah pengerjaan selesai</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="{{ auth()->check() ? route('pelapor.dashboard') : route('login') }}" 
                        class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl font-bold bg-[#1E3A5F] hover:bg-[#152a45] text-white shadow-md hover:shadow-lg transition">
                        <span>{{ auth()->check() ? 'Buka Dashboard Laporan' : 'Masuk / Lapor Sekarang' }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Laporan Anonim / Whistleblowing -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-md hover:shadow-xl transition duration-300 flex flex-col justify-between relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50 rounded-bl-full -z-0 transition-all group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shadow-inner">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                            100% Rahasia & Anonim
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 group-hover:text-amber-700 transition">
                        Laporan Whistleblowing (Anonim)
                    </h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        Saluran aman untuk melaporkan indikasi pelanggaran etika, pungli, penyalahgunaan wewenang, atau hal sensitif tanpa membagikan identitas Anda.
                    </p>

                    <!-- Features checklist -->
                    <div class="mt-6 space-y-3 bg-amber-50/50 p-5 rounded-2xl border border-amber-100 text-sm">
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-slate-700"><strong>Tanpa login</strong> dan tanpa riwayat data pribadi tersimpan</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-slate-700">Mendapatkan <strong>Kode Tiket Unik</strong> untuk melacak progres secara mandiri</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-slate-700">Ditangani <strong>eksklusif langsung oleh KABAG (Dekanat)</strong></span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-slate-700">Petugas lapangan & pihak lain tidak memiliki akses ke laporan ini</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="{{ route('whistleblowing.create') }}" 
                        class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl font-bold bg-[#D97706] hover:bg-[#b45309] text-white shadow-md hover:shadow-lg transition">
                        <span>Buat Laporan Anonim Sekarang</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Public Quick Tracker Bar -->
        <div class="bg-gradient-to-r from-[#1E3A5F] to-[#2563EB] rounded-3xl p-8 sm:p-10 text-white shadow-xl relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white mb-2">
                    Lacak Status Laporan
                </span>
                <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Punya Kode Tiket Laporan?</h3>
                <p class="text-blue-100 text-sm mt-1 mb-6">
                    Masukkan kode tiket unik yang Anda peroleh saat membuat pengaduan anonim (contoh: <code>WB-2026-XXXXX</code>) untuk memantau status penyelesaiannya secara real-time.
                </p>

                <form action="{{ route('tracking') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-grow">
                        <input type="text" name="ticket_code" required
                            placeholder="Ketik Kode Tiket (misal: WB-2026-XXXXX)"
                            class="w-full px-5 py-3.5 rounded-xl bg-white text-slate-900 placeholder-slate-400 font-mono text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-sm uppercase">
                    </div>
                    <button type="submit"
                        class="px-8 py-3.5 rounded-xl font-bold bg-amber-500 hover:bg-amber-600 text-slate-900 shadow-md hover:shadow-lg transition duration-200 shrink-0 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span>Cek Laporan</span>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer matching Figma node 35:891 -->
    <footer class="bg-white border-t border-slate-200 py-6 px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
            <p>&copy; 2026 FIKKIA Universitas Airlangga. All rights reserved.</p>
            <p class="font-medium text-slate-700">E-Lapor. Suara Anda, Perubahan Nyata.</p>
            <div class="flex items-center gap-4">
                <span>Kampus Mojo</span> &bull; <span>Kampus Sobo</span> &bull; <span>Kampus Giri</span>
            </div>
        </div>
    </footer>
</body>
</html>
