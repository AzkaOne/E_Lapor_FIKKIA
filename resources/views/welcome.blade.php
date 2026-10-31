<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-LAPOR FIKKIA UNAIR - Layanan Aspirasi & Pengaduan Kampus</title>
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
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0a8080] selection:text-white relative overflow-x-hidden">

    <!-- TopHeader matching Figma node 35:579 -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-11 h-11 rounded-2xl bg-[#0a8080] flex items-center justify-center text-white font-black text-xl shadow-md">
                    E
                </div>
                <div class="border-l border-slate-200 pl-4 py-1">
                    <h1 class="text-base sm:text-lg font-black text-[#0c3934] leading-tight tracking-tight">E-LAPOR FIKKIA</h1>
                    <p class="text-xs text-slate-500 font-medium">Fakultas Ilmu Kesehatan, Kedokteran, dan Ilmu Alam - UNAIR</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('tracking') }}" class="hidden sm:inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-[#0a8080] hover:bg-slate-50 transition">
                    Lacak Pengaduan
                </a>
                @auth
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" 
                        class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold bg-[#0a8080] hover:bg-[#086666] text-white shadow-md transition">
                        Dashboard Saya
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                        class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:text-[#0a8080] hover:bg-slate-50 transition border border-slate-200">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                        class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold bg-[#0a8080] hover:bg-[#086666] text-white shadow-md transition">
                        Daftar Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full relative">
        <!-- Hero Header matching Figma node 35:889 -->
        <div class="max-w-3xl mb-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#ecfdf5] text-[#064e3b] border border-[rgba(167,243,208,0.8)] mb-3 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-[#059669] animate-pulse"></span>
                E-Lapor FIKKIA Banyuwangi
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0c3934] tracking-tight leading-tight">
                Pilih Jenis Laporan
            </h2>
            <p class="text-base text-slate-600 mt-2 leading-relaxed">
                Silakan pilih jenis laporan yang ingin Anda ajukan. Pastikan data yang Anda masukkan sudah benar agar proses penanganan dan disposisi berjalan lancar.
            </p>
        </div>

        <!-- 2 Main Cards Grid matching Figma nodes 36:1068 & 36:1069 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Card 1: Laporan Pelayanan (Figma 36:1068) -->
            <div class="bg-white rounded-3xl p-8 border border-[rgba(153,246,228,0.6)] shadow-md hover:shadow-xl transition duration-300 flex flex-col justify-between relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-[#f0fdfa] border border-[#ccfbf1] text-[#0a8080] flex items-center justify-center shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#ecfdf5] text-[#064e3b] border border-[rgba(167,243,208,0.6)]">
                            Akun Terdaftar (NIM/NIP)
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 group-hover:text-[#0a8080] transition">
                        Laporan Pelayanan
                    </h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        Digunakan untuk membuat laporan pelayanan, sarana prasarana fisik, akademik, SDM & keuangan menggunakan akun resmi civitas (@unair.ac.id).
                    </p>

                    <!-- Features checklist matching Figma 35:925 Notice Box -->
                    <div class="mt-6 space-y-2.5 bg-[#ecfdf5] p-5 rounded-2xl border border-[rgba(167,243,208,0.6)] text-xs text-[#064e3b]">
                        <div class="flex items-start gap-2.5">
                            <div class="w-4 h-4 rounded-full bg-[#059669] text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Masuk menggunakan <strong>NIM/NIP</strong> dan password untuk mengisi laporan</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <div class="w-4 h-4 rounded-full bg-[#059669] text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Identitas tercatat resmi untuk validitas dan tanggapan berkala</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <div class="w-4 h-4 rounded-full bg-[#059669] text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Cocok untuk aduan fasilitas kampus (Mojo, Sobo, Giri) & layanan unit</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <div class="w-4 h-4 rounded-full bg-[#059669] text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Riwayat laporan tersimpan otomatis & dapat dinilai (Rating 1–5 bintang)</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="{{ auth()->check() ? route('pelapor.dashboard') : route('login') }}" 
                        class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl font-bold bg-[#0a8080] hover:bg-[#086666] text-white shadow-md hover:shadow-lg transition">
                        <span>{{ auth()->check() ? 'Buka Dashboard Laporan' : 'Lanjutkan ke Pelaporan' }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Laporan Anonim / Whistleblowing (Figma 36:1069) -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-md hover:shadow-xl transition duration-300 flex flex-col justify-between relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                            100% Rahasia & Anonim
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 group-hover:text-amber-700 transition">
                        Laporan Whistleblowing (Anonim)
                    </h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        Layanan pengaduan khusus dugaan pelanggaran integritas, penyimpangan wewenang, etika, dan tata kelola kampus tanpa perlu login atau menyertakan identitas pribadi.
                    </p>

                    <!-- Features checklist -->
                    <div class="mt-6 space-y-2.5 bg-amber-50/60 p-5 rounded-2xl border border-amber-200/60 text-xs text-amber-900">
                        <div class="flex items-start gap-2.5">
                            <div class="w-4 h-4 rounded-full bg-amber-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Tanpa registrasi, tanpa login, dan tanpa nama pelapor (identitas aman)</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <div class="w-4 h-4 rounded-full bg-amber-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Mendapatkan <strong>Kode Tiket Rahasia</strong> untuk memantau status secara mandiri</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <div class="w-4 h-4 rounded-full bg-amber-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Ditangani secara eksklusif langsung oleh <strong>Kepala Bagian (KABAG)</strong></span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <div class="w-4 h-4 rounded-full bg-amber-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Lacak status perkembangan kapan saja melalui halaman Lacak Pengaduan</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('whistleblowing.create') }}" 
                        class="flex-1 flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl font-bold bg-[#0c3934] hover:bg-[#082824] text-white shadow-md hover:shadow-lg transition">
                        <span>Buat Laporan Anonim</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="{{ route('tracking') }}" 
                        class="flex items-center justify-center gap-1.5 py-3.5 px-5 rounded-xl font-bold border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                        <span>Lacak Tiket</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Institutional Footer matching Figma node 35:891 -->
    <footer class="bg-white border-t border-slate-200 py-6 px-4 sm:px-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="font-medium text-slate-600">&copy; 2025 FIKKIA UNAIR • E-Lapor. Suara Anda, Perubahan Nyata.</p>
            <p class="text-slate-400">Kampus Mojo • Kampus Sobo • Kampus Giri Banyuwangi</p>
        </div>
    </footer>
</body>
</html>