<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-LAPOR - Sistem Pengaduan & Aspirasi Online</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-rose-500 selection:text-white">
    <!-- Navbar -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-600 to-orange-500 flex items-center justify-center text-white font-bold text-xl shadow-md shadow-rose-500/20">
                        E
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight text-slate-900">E-LAPOR</span>
                        <span class="hidden sm:inline-block ml-2 text-xs font-semibold px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 border border-rose-200">v13</span>
                    </div>
                </div>

                <nav class="hidden md:flex items-center space-x-6 text-sm font-medium text-slate-600">
                    <a href="#tentang" class="hover:text-rose-600 transition">Tentang</a>
                    <a href="#alur" class="hover:text-rose-600 transition">Alur Pengaduan</a>
                    <a href="#fitur" class="hover:text-rose-600 transition">Keunggulan</a>
                    <a href="#status" class="hover:text-rose-600 transition">Cek Status</a>
                </nav>

                <div class="flex items-center space-x-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm font-semibold rounded-lg bg-rose-600 text-white hover:bg-rose-700 shadow-sm transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-rose-600 transition">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold rounded-lg bg-rose-600 text-white hover:bg-rose-700 shadow-sm transition">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    @else
                        <button class="px-4 py-2 text-sm font-semibold rounded-lg bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm">
                            Buat Laporan Baru
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-12 pb-20 md:pt-20 md:pb-32 overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.rose.100),white)] opacity-60"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-100/80 text-rose-700 text-xs font-semibold mb-6">
                    <span class="flex h-2 w-2 rounded-full bg-rose-600 animate-pulse"></span>
                    Laravel {{ app()->version() }} + Tailwind CSS Ready
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Layanan Aspirasi & <br class="hidden sm:inline">
                    <span class="bg-gradient-to-r from-rose-600 to-orange-500 bg-clip-text text-transparent">Pengaduan Online Terpadu</span>
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-slate-600 leading-relaxed">
                    Sampaikan keluhan, aspirasi, atau pengaduan Anda dengan cepat, transparan, dan terintegrasi langsung dengan instansi terkait.
                </p>

                <!-- Action Card / Form Trigger -->
                <div class="mt-10 max-w-xl mx-auto p-3 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col sm:flex-row gap-3">
                    <input type="text" placeholder="Masukkan nomor tiket pengaduan..." class="flex-1 px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 text-sm">
                    <button class="px-6 py-3 rounded-xl bg-gradient-to-r from-rose-600 to-orange-500 text-white font-semibold text-sm hover:from-rose-700 hover:to-orange-600 transition shadow-md shadow-rose-500/25 flex items-center justify-center gap-2">
                        <span>Lacak Laporan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center">
                    <div class="text-3xl font-black text-rose-600">1.250+</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mt-1">Laporan Diterima</div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center">
                    <div class="text-3xl font-black text-emerald-600">98%</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mt-1">Terselesaikan</div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center">
                    <div class="text-3xl font-black text-amber-500">< 24 Jam</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mt-1">Respon Awal</div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center">
                    <div class="text-3xl font-black text-indigo-600">100%</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mt-1">Kerahasiaan Aman</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Steps -->
    <section id="alur" class="py-16 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Alur Penyampaian Pengaduan</h2>
                <p class="mt-3 text-slate-600">4 langkah mudah untuk melaporkan permasalahan di sekitar Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="relative p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-rose-600 text-white font-bold flex items-center justify-center mb-4">1</div>
                    <h3 class="font-bold text-slate-900 mb-2">Tulis Laporan</h3>
                    <p class="text-sm text-slate-600">Laporkan keluhan atau aspirasi Anda secara jelas lengkap dengan bukti foto atau dokumen.</p>
                </div>
                <div class="relative p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-rose-600 text-white font-bold flex items-center justify-center mb-4">2</div>
                    <h3 class="font-bold text-slate-900 mb-2">Proses Verifikasi</h3>
                    <p class="text-sm text-slate-600">Petugas melakukan verifikasi dan meneruskan pengaduan ke pihak yang berwenang.</p>
                </div>
                <div class="relative p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-rose-600 text-white font-bold flex items-center justify-center mb-4">3</div>
                    <h3 class="font-bold text-slate-900 mb-2">Tindak Lanjut</h3>
                    <p class="text-sm text-slate-600">Instansi menindaklanjuti laporan dan memberikan progres real-time kepada pelapor.</p>
                </div>
                <div class="relative p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-rose-600 text-white font-bold flex items-center justify-center mb-4">4</div>
                    <h3 class="font-bold text-slate-900 mb-2">Selesai</h3>
                    <p class="text-sm text-slate-600">Pengaduan dinyatakan tuntas dan masyarakat dapat memberikan umpan balik penilaian.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:flex sm:justify-between sm:items-center">
            <div class="flex items-center justify-center sm:justify-start space-x-3 mb-4 sm:mb-0">
                <div class="w-8 h-8 rounded-lg bg-rose-600 flex items-center justify-center text-white font-bold text-sm">
                    E
                </div>
                <span class="text-lg font-bold text-white">E-LAPOR</span>
            </div>
            <p class="text-xs">
                &copy; {{ date('Y') }} E-LAPOR. Built with Laravel 13 & Tailwind CSS.
            </p>
        </div>
    </footer>
</body>
</html>
