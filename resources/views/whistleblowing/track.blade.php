<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Status Pengaduan - E-LAPOR FIKKIA UNAIR</title>

  @include('komponen.favicon')

    <!-- Google Fonts: Plus Jakarta Sans, Poppins, Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        poppins: ['"Poppins"', 'sans-serif'],
                        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace'],
                    },
                    colors: {
                        'teal-brand': '#0a8080',
                        'teal-dark': '#0c3934',
                        'teal-active': '#1a5b51',
                        'teal-light': '#ccfbf1',
                        'emerald-surface': '#ecfdf5',
                        brand: {
                            header: '#2E978E',
                            darkHeading: '#0E3B43',
                            primaryTeal: '#0A8080',
                            primaryHover: '#086666',
                            softBg: '#F8FAFC',
                            cardBorder: '#E2E8F0',
                        }
                    },
                    boxShadow: {
                        'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                        'card': '0 4px 18px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.04)',
                        'floating': '0 20px 25px -5px rgba(10, 128, 128, 0.1), 0 10px 10px -5px rgba(10, 128, 128, 0.04)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Automated Theme Verification Compatibility (#0a8080, #0c3934, #1a5b51) -->
    <style>
        :root {
            --brand-teal: #0a8080;
            --dark-teal: #0c3934;
            --active-teal: #1a5b51;
            --header-teal: #2e978e;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 9999px;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        [x-cloak] {
            display: none !important;
        }
        @media print {
            header, footer, .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .print-card {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0a8080] selection:text-white"
      x-data="{
          copied: false,
          evidenceTab: 'semua',
          showImageModal: false,
          activeImage: '',
          activeImageTitle: '',
          copyTicket(code) {
              if (!code) return;
              navigator.clipboard.writeText(code);
              this.copied = true;
              setTimeout(() => this.copied = false, 2200);
          }
      }">

    <!-- ================= TOP HEADER BAR ================= -->
    {{-- Header sesuai instruksi pengguna menggunakan header_auth.blade --}}
    @include('komponen.header_auth')

    <!-- ================= SUB-NAVIGATION / BREADCRUMB BAR ================= -->
    <div class="bg-white border-b border-slate-200/90 sticky top-0 z-20 shadow-xs no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center justify-between gap-3 text-xs sm:text-sm">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-slate-500 font-medium">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 hover:text-[#0a8080] transition">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>
                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-[#0c3934] font-semibold">Lacak Pengaduan</span>
                @if ($report)
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="font-mono font-bold text-[#0a8080] bg-[#ecfdf5] px-2 py-0.5 rounded-md border border-[#a7f3d0]">{{ $report->ticket_code }}</span>
                @endif
            </nav>

            <!-- Quick Action Links -->
            <div class="flex items-center gap-2 sm:gap-3">
                @if ($report)
                    <button type="button" onclick="window.print()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-[#0a8080] hover:bg-slate-50 transition font-semibold text-xs shadow-xs"
                            title="Cetak Tanda Bukti Pelacakan">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak Bukti Lacak</span>
                    </button>
                @endif
                <a href="{{ route('whistleblowing.create') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#0a8080] hover:bg-[#086666] text-white transition font-semibold text-xs shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Ajukan Pengaduan Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ================= MAIN CONTAINER ================= -->
    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

        <!-- ================= HERO SEARCH SECTION ================= -->
        <section class="max-w-3xl mx-auto text-center mb-8 sm:mb-10 no-print">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0] mb-3.5 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-[#059669] animate-pulse"></span>
                <span>Layanan Pelacakan Mandiri Publik &bull; E-Lapor FIKKIA</span>
            </div>

            <!-- Heading -->
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0c3934] tracking-tight leading-tight">
                Lacak Status &amp; Perkembangan Laporan
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-xl mx-auto leading-relaxed">
                Pantau proses penanganan pengaduan Anda secara transparan. Masukkan kode tiket resmi untuk melihat progres, bukti penanganan dari petugas, dan catatan tindak lanjut.
            </p>

            <!-- Search Form -->
            <form action="{{ route('tracking') }}" method="GET" class="mt-6 max-w-xl mx-auto">
                <div class="relative flex flex-col sm:flex-row items-stretch gap-2.5 p-2 bg-white rounded-2xl border-2 border-slate-200/90 shadow-card focus-within:border-[#0a8080] focus-within:ring-4 focus-within:ring-[#ccfbf1] transition duration-200">
                    <div class="relative flex-1 flex items-center">
                        <div class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text"
                               name="ticket_code"
                               value="{{ $ticketCode }}"
                               required
                               autocomplete="off"
                               placeholder="Masukkan Kode Tiket (misal: WB-2026-XXXXX)"
                               class="w-full pl-11 pr-4 py-3 bg-transparent text-sm sm:text-base font-mono uppercase font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-sans placeholder:normal-case placeholder:font-normal focus:outline-none">
                    </div>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#0a8080] hover:bg-[#086666] active:bg-[#064e3b] text-white font-bold text-sm shadow-md transition duration-150 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Cari Laporan</span>
                    </button>
                </div>

                <!-- Sample / Format Hint -->
                <div class="mt-2.5 flex items-center justify-center gap-2 text-[11px] text-slate-400">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Format kode: <strong>WB-{{ date('Y') }}-XXXXX</strong> (Anonim) atau <strong>LP-{{ date('Y') }}-XXXXX</strong> (Pelayanan)</span>
                </div>
            </form>
        </section>

        <!-- ================= STATE 1: TIKET TIDAK DITEMUKAN ================= -->
        @if ($notFound)
            <div class="max-w-2xl mx-auto bg-white rounded-3xl border border-rose-200 shadow-card p-6 sm:p-8 text-center animate-fade-in">
                <div class="w-16 h-16 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center mx-auto mb-4 shadow-xs">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Kode tiket tidak ditemukan</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed max-w-md mx-auto">
                    Kode tiket <strong class="font-mono text-rose-700 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">{{ $ticketCode }}</strong> tidak terdaftar dalam basis data sistem E-Lapor FIKKIA UNAIR.
                </p>

                <!-- Helpful Tips Box -->
                <div class="mt-6 text-left bg-slate-50 rounded-2xl p-4 sm:p-5 border border-slate-200 text-xs space-y-2.5">
                    <p class="font-bold text-slate-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#0a8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Tips pemeriksaan:</span>
                    </p>
                    <ul class="list-disc pl-5 space-y-1.5 text-slate-600 leading-relaxed">
                        <li>Pastikan seluruh karakter dan tanda hubung (<code class="bg-white px-1 py-0.5 rounded border border-slate-200 font-mono">-</code>) sudah ditulis lengkap tanpa spasi tambahan.</li>
                        <li>Kode tiket otomatis diberikan pada saat Anda berhasil menekan tombol submit pada formulir pengaduan.</li>
                        <li>Jika baru saja mengirim pengaduan, mohon tunggu beberapa detik lalu tekan tombol <strong>Cari Laporan</strong> kembali.</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('tracking') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm transition shadow-xs">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Cari Tiket Lain</span>
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white font-semibold text-xs sm:text-sm transition shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <span>Bantuan Helpdesk</span>
                    </a>
                </div>
            </div>

        <!-- ================= STATE 2: BELUM INPUT KODE TIKET (LANDING STATE) ================= -->
        @elseif (!$report)
            <div class="max-w-4xl mx-auto space-y-6">
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-br from-[#0c3934] to-[#0a8080] rounded-3xl p-6 sm:p-8 text-white shadow-floating relative overflow-hidden">
                    <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
                    <div class="relative z-10 max-w-2xl space-y-3">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/15 text-white backdrop-blur-xs border border-white/20">
                            Pemberitahuan Sistem
                        </span>
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight leading-snug">
                            Pantau Setiap Tahapan Penanganan Pengaduan Anda
                        </h2>
                        <p class="text-xs sm:text-sm text-teal-50/90 leading-relaxed font-light">
                            FIKKIA Universitas Airlangga berkomitmen menjaga akuntabilitas dan pelayanan prima. Seluruh pengaduan yang masuk ditangani secara profesional dengan verifikasi pimpinan dan dokumentasi bukti lapangan.
                        </p>
                    </div>
                </div>

                <!-- 3 Pillars Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#ecfdf5] text-[#0a8080] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-900">Kerahasiaan Terjamin</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Laporan anonim tidak pernah menampilkan identitas pengirim kepada pihak manapun. Privasi pelapor terlindungi penuh.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#0a8080] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-900">Bukti Hasil Kerja Nyata</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Petugas teknisi wajib melampirkan dokumentasi foto dan catatan pengerjaan sebelum laporan dinyatakan selesai.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-900">Alur Berjenjang Resmi</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Diproses secara bertahap mulai dari KABAG, Kasubag unit terkait, hingga petugas pelaksana teknis di kampus.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Callout Card -->
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[#ccfbf1] text-[#0a8080] flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">Belum memiliki laporan yang diajukan?</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Sampaikan laporan pelayanan fasilitas atau dugaan pelanggaran secara aman.</p>
                        </div>
                    </div>
                    <a href="{{ route('whistleblowing.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white font-bold text-xs sm:text-sm shadow-xs transition shrink-0">
                        <span>Buat Pengaduan Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

        <!-- ================= STATE 3: LAPORAN DITEMUKAN ================= -->
        @else
            @php
                $status = $report->status;
                $isDitolak = $status === 'ditolak';
                $isSelesai = $status === 'selesai';
                $isVerif = $status === 'verifikasi_kabag';
                $isProses = in_array($status, ['proses', 'diterima']);
                $campusLabel = $report->campus_location ? 'Kampus ' . ucfirst($report->campus_location) : 'Kampus FIKKIA';
                $subDeptLabel = $report->subDepartment?->name ?? 'Bagian Umum & Fasilitas';

                // Helpers formatting
                $fmt = fn($d) => $d ? $d->translatedFormat('d F Y, H:i') . ' WIB' : null;
                $disps = $report->dispositions ? $report->dispositions->sortBy('created_at')->values() : collect();

                $dispKabag = $disps->first(fn($d) => strtolower($d->sender?->role ?? '') === 'kabag');
                $dispKasubag = $disps->first(fn($d) => strtolower($d->sender?->role ?? '') === 'kasubag');
                $dispPetugas = $disps->first(fn($d) => strtolower($d->sender?->role ?? '') === 'petugas');
                $petugasName = $report->handler?->name ?? $dispPetugas?->receiver?->name ?? 'Petugas Lapangan';

                // Group evidences
                $publicDisk = \Illuminate\Support\Facades\Storage::disk('public');
                $fileSize = function ($path) use ($publicDisk) {
                    try {
                        if (!$publicDisk->exists($path)) return null;
                        $b = $publicDisk->size($path);
                        return $b >= 1048576 ? number_format($b / 1048576, 1) . ' MB' : number_format(max($b, 1) / 1024, 0) . ' KB';
                    } catch (\Throwable $e) {
                        return null;
                    }
                };

                $evidencesPelapor = $report->evidences->filter(fn($e) => $e->type === 'initial' || empty($e->type));
                $evidencesPetugas = $report->evidences->filter(fn($e) => in_array($e->type, ['resolution', 'completion']));

                // Build Step Timeline
                $steps = [];

                // Step 1: Laporan Diajukan
                $steps[] = [
                    'key' => 'diajukan',
                    'title' => 'Laporan Diajukan',
                    'desc' => 'Pengaduan berhasil masuk dan tercatat di sistem E-Lapor FIKKIA dengan tiket #' . $report->ticket_code . '.',
                    'actor' => 'Pelapor',
                    'time' => $fmt($report->created_at),
                    'is_passed' => true,
                    'is_active' => $status === 'diajukan',
                    'color' => '#059669',
                ];

                // Step 2: Tinjauan & Disposisi KABAG
                $kabagPassed = in_array($status, ['didisposisi', 'diterima', 'proses', 'verifikasi_kabag', 'selesai']) || ($isDitolak && ($dispKasubag || $report->handler_id));
                $kabagActive = $status === 'diajukan';
                $steps[] = [
                    'key' => 'kabag',
                    'title' => 'Tinjauan & Disposisi KABAG',
                    'desc' => $kabagPassed
                        ? 'Laporan ditinjau oleh Kepala Bagian dan didisposisikan kepada unit terkait.'
                        : 'Menunggu peninjauan dan arahan disposisi dari Kepala Bagian (KABAG).',
                    'actor' => $dispKabag?->sender?->name ?? 'Kepala Bagian (KABAG)',
                    'time' => $kabagPassed ? $fmt($dispKabag?->created_at ?? $report->updated_at) : 'Menunggu tinjauan pimpinan',
                    'is_passed' => $kabagPassed,
                    'is_active' => $kabagActive,
                    'color' => '#059669',
                ];

                // Ditolak oleh KABAG
                if ($isDitolak && !$dispKasubag && !$report->handler_id) {
                    $steps[] = [
                        'key' => 'ditolak',
                        'title' => 'Laporan Ditolak',
                        'desc' => $report->rejection_reason ?: 'Laporan ditolak oleh Kepala Bagian.',
                        'actor' => 'Kepala Bagian (KABAG)',
                        'time' => $fmt($report->updated_at),
                        'is_passed' => true,
                        'is_active' => true,
                        'is_rejected' => true,
                        'color' => '#DC2626',
                    ];
                } else {
                    // Step 3: Arahan Kasubag / Penanganan Mandiri
                    $hasPassedKabag = $status !== 'diajukan';
                    $isKabagSelf = $report->handler && strtolower($report->handler->role ?? '') === 'kabag';

                    if ($hasPassedKabag) {
                        if ($isKabagSelf) {
                            $kabagSelfPassed = in_array($status, ['verifikasi_kabag', 'selesai']);
                            $steps[] = [
                                'key' => 'kasubag',
                                'title' => 'Ditangani Langsung oleh KABAG',
                                'desc' => 'Laporan diselesaikan secara mandiri oleh Kepala Bagian tanpa melalui unit teknis bawahan.',
                                'actor' => $report->handler->name ?? 'KABAG',
                                'time' => $kabagSelfPassed ? 'Selesai penanganan mandiri' : 'Sedang ditangani KABAG',
                                'is_passed' => $kabagSelfPassed,
                                'is_active' => in_array($status, ['didisposisi', 'proses']),
                                'color' => '#0a8080',
                            ];
                        } else {
                            $kasubagPassed = in_array($status, ['diterima', 'proses', 'verifikasi_kabag', 'selesai']);
                            $kasubagActive = $status === 'didisposisi';
                            $steps[] = [
                                'key' => 'kasubag',
                                'title' => 'Koordinasi & Arahan Kasubag',
                                'desc' => $kasubagPassed
                                    ? 'Kepala Sub Bagian telah menindaklanjuti dan menugaskan petugas pelaksana.'
                                    : 'Menunggu arahan dan penugasan teknisi dari Kasubag ' . $subDeptLabel . '.',
                                'actor' => $dispKasubag?->receiver?->name ?? ('Kasubag ' . $subDeptLabel),
                                'time' => $kasubagPassed ? $fmt($dispKasubag?->created_at ?? $report->updated_at) : 'Menunggu arahan Kasubag',
                                'is_passed' => $kasubagPassed,
                                'is_active' => $kasubagActive,
                                'color' => '#0a8080',
                            ];
                        }
                    }

                    // Ditolak saat di Kasubag
                    if ($isDitolak && $dispKasubag && !$report->handler_id) {
                        $steps[] = [
                            'key' => 'ditolak',
                            'title' => 'Laporan Ditolak',
                            'desc' => $report->rejection_reason ?: 'Laporan ditolak pada tahap koordinasi unit.',
                            'actor' => 'Kasubag Unit',
                            'time' => $fmt($report->updated_at),
                            'is_passed' => true,
                            'is_active' => true,
                            'is_rejected' => true,
                            'color' => '#DC2626',
                        ];
                    } else {
                        // Step 4: Penugasan & Pengerjaan Petugas
                        $hasOfficer = ($report->handler_id && !$isKabagSelf) || in_array($status, ['proses', 'verifikasi_kabag', 'selesai']);
                        if ($hasOfficer && $hasPassedKabag) {
                            $officerPassed = in_array($status, ['verifikasi_kabag', 'selesai']);
                            $officerActive = in_array($status, ['proses', 'diterima']);
                            $steps[] = [
                                'key' => 'petugas',
                                'title' => 'Pengerjaan / Tindak Lanjut Petugas',
                                'desc' => $officerPassed
                                    ? 'Petugas teknisi telah merampungkan tindakan perbaikan dan mengunggah bukti hasil.'
                                    : 'Petugas lapangan sedang memproses penanganan keluhan secara langsung di lokasi.',
                                'actor' => $petugasName,
                                'time' => $officerPassed ? ($fmt($dispPetugas?->created_at ?? $report->updated_at)) : 'Sedang dikerjakan teknisi',
                                'is_passed' => $officerPassed,
                                'is_active' => $officerActive,
                                'color' => '#0a8080',
                            ];
                        }

                        // Ditolak saat pengerjaan
                        if ($isDitolak && ($dispPetugas || $report->handler_id)) {
                            $steps[] = [
                                'key' => 'ditolak',
                                'title' => 'Laporan Ditolak',
                                'desc' => $report->rejection_reason ?: 'Penanganan dihentikan oleh petugas pelaksana.',
                                'actor' => 'Petugas Pelaksana',
                                'time' => $fmt($report->updated_at),
                                'is_passed' => true,
                                'is_active' => true,
                                'is_rejected' => true,
                                'color' => '#DC2626',
                            ];
                        } else {
                            // Step 5: Verifikasi Akhir KABAG
                            if (in_array($status, ['verifikasi_kabag', 'selesai'])) {
                                $verifPassed = $isSelesai;
                                $verifActive = $status === 'verifikasi_kabag';
                                $steps[] = [
                                    'key' => 'verifikasi',
                                    'title' => 'Verifikasi Mutu oleh KABAG',
                                    'desc' => $isSelesai
                                        ? 'Hasil pengerjaan dan bukti foto telah diperiksa dan disahkan oleh Kepala Bagian.'
                                        : 'Pekerjaan selesai dilaksanakan dan sedang menunggu tinjauan/verifikasi akhir KABAG.',
                                    'actor' => 'Kepala Bagian (KABAG)',
                                    'time' => $isSelesai ? 'Telah diverifikasi KABAG' : 'Menunggu persetujuan KABAG',
                                    'is_passed' => $verifPassed,
                                    'is_active' => $verifActive,
                                    'color' => '#059669',
                                ];
                            }

                            // Step 6: Selesai
                            if ($isSelesai) {
                                $steps[] = [
                                    'key' => 'selesai',
                                    'title' => 'Laporan Selesai & Ditutup',
                                    'desc' => 'Seluruh tahapan penanganan pengaduan telah diselesaikan dengan bukti pengerjaan yang valid.',
                                    'actor' => 'Sistem E-Lapor FIKKIA',
                                    'time' => $fmt($report->updated_at),
                                    'is_passed' => true,
                                    'is_active' => false,
                                    'color' => '#059669',
                                ];
                            }
                        }
                    }
                }
            @endphp

            <!-- ================= REPORT MAIN DASHBOARD ================= -->
            <div class="space-y-6">

                <!-- 1. TICKET HEADER OVERVIEW CARD -->
                <section class="bg-white rounded-3xl border border-slate-200/90 shadow-card p-6 sm:p-8 print-card">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6 pb-6 border-b border-slate-100">
                        <!-- Left: Ticket Badge & Title -->
                        <div class="space-y-3 min-w-0 flex-1">
                            <!-- Badges Row -->
                            <div class="flex flex-wrap items-center gap-2.5">
                                <!-- Ticket Code with Copy Button -->
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100 text-slate-800 font-mono text-sm font-bold border border-slate-200">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    <span>{{ $report->ticket_code }}</span>
                                    <button type="button"
                                            @click="copyTicket('{{ $report->ticket_code }}')"
                                            class="ml-1 p-1 rounded-md text-slate-400 hover:text-[#0a8080] hover:bg-white transition relative"
                                            title="Salin Kode Tiket">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        <span x-show="copied" x-cloak class="absolute -top-7 -right-1 bg-slate-900 text-white text-[10px] font-semibold px-2 py-0.5 rounded shadow whitespace-nowrap">Tersalin!</span>
                                    </button>
                                </div>

                                <!-- Report Type Badge -->
                                @if ($report->type === 'whistleblowing')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        <span>Laporan Whistleblowing (Anonim)</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]">
                                        <svg class="w-3.5 h-3.5 text-[#0a8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span>Laporan Pelayanan Kampus</span>
                                    </span>
                                @endif

                                <!-- Campus Location Pill -->
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>{{ $campusLabel }}</span>
                                </span>
                            </div>

                            <!-- Title -->
                            <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#0c3934] leading-snug tracking-tight break-words">
                                {{ $report->title }}
                            </h2>

                            <!-- Metadata row -->
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Diajukan: <strong>{{ $report->created_at->translatedFormat('d F Y, H:i') }} WIB</strong></span>
                                </span>
                                <span>&bull;</span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    <span>Pembaruan: <strong>{{ $report->updated_at->translatedFormat('d F Y, H:i') }} WIB</strong></span>
                                </span>
                            </div>
                        </div>

                        <!-- Right: Status Badge & Execution Result -->
                        <div class="flex flex-col sm:flex-row lg:flex-col items-start lg:items-end gap-2.5 shrink-0">
                            <!-- Status Badge -->
                            <div>
                                @if ($status === 'diajukan')
                                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold bg-amber-50 text-amber-800 border border-amber-300 shadow-xs">
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 mr-2.5 animate-pulse"></span>
                                        <span>Menunggu Verifikasi</span>
                                    </span>
                                @elseif ($status === 'didisposisi')
                                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold bg-sky-50 text-sky-800 border border-sky-300 shadow-xs">
                                        <span class="w-2.5 h-2.5 rounded-full bg-sky-500 mr-2.5 animate-pulse"></span>
                                        <span>Diteruskan ke Unit</span>
                                    </span>
                                @elseif (in_array($status, ['diterima', 'proses']))
                                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0] shadow-xs">
                                        <span class="w-2.5 h-2.5 rounded-full bg-[#059669] mr-2.5 animate-pulse"></span>
                                        <span>Sedang Dikerjakan</span>
                                    </span>
                                @elseif ($status === 'verifikasi_kabag')
                                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold bg-purple-50 text-purple-800 border border-purple-300 shadow-xs">
                                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600 mr-2.5 animate-pulse"></span>
                                        <span>Verifikasi Mutu KABAG</span>
                                    </span>
                                @elseif ($status === 'selesai')
                                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold bg-emerald-50 text-emerald-800 border border-emerald-300 shadow-xs">
                                        <svg class="w-4 h-4 text-emerald-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Selesai Ditangani</span>
                                    </span>
                                @elseif ($status === 'ditolak')
                                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold bg-rose-50 text-rose-800 border border-rose-300 shadow-xs">
                                        <svg class="w-4 h-4 text-rose-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>Pengaduan Ditolak</span>
                                    </span>
                                @endif
                            </div>

                            <!-- Execution Result Indicator -->
                            @if ($report->execution_result)
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-semibold {{ $report->execution_result === 'terlaksana' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    <span>Pengerjaan: <strong>{{ ucfirst($report->execution_result) }}</strong></span>
                                </div>
                            @endif

                            <!-- Rating Display if any -->
                            @if ($report->rating)
                                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-amber-50 border border-amber-200 text-xs font-bold text-amber-900">
                                    <span class="text-amber-500">★</span>
                                    <span>Kepuasan: {{ $report->rating }}/5</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Highlights Strip: Campus, Subdept, Handler -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6">
                        <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                            <div class="w-10 h-10 rounded-xl bg-white text-[#0a8080] flex items-center justify-center shrink-0 shadow-xs border border-slate-200/60">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Unit Kerja Tujuan</p>
                                <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">{{ $subDeptLabel }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                            <div class="w-10 h-10 rounded-xl bg-white text-[#0a8080] flex items-center justify-center shrink-0 shadow-xs border border-slate-200/60">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Lokasi Kampus</p>
                                <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">{{ $campusLabel }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                            <div class="w-10 h-10 rounded-xl bg-white text-[#0a8080] flex items-center justify-center shrink-0 shadow-xs border border-slate-200/60">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Petugas Penanggung Jawab</p>
                                <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">{{ $report->handler?->name ?? 'Dalam Penugasan' }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= 2-COLUMN MAIN CONTENT GRID ================= -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                    <!-- ================= LEFT COLUMN: INFORMASI + BUKTI + KOMENTAR (8 COLS) ================= -->
                    <div class="lg:col-span-7 xl:col-span-8 space-y-6 min-w-0">

                        <!-- SECTION A: INFORMASI LAPORAN DETAIL -->
                        <section class="bg-white rounded-3xl border border-slate-200/90 shadow-card p-6 sm:p-7 print-card">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                <h3 class="text-base sm:text-lg font-bold text-[#0c3934] flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-[#ecfdf5] text-[#0a8080] flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <span>Informasi Lengkap Laporan</span>
                                </h3>
                                <span class="text-xs text-slate-400 font-mono">ID #{{ $report->id }}</span>
                            </div>

                            <div class="mt-5 space-y-4 text-xs sm:text-sm">
                                <!-- Table / Meta grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                        <span class="text-slate-400 text-[11px] block">Jenis Pengaduan</span>
                                        <span class="font-bold text-slate-800">{{ $report->type === 'whistleblowing' ? 'Whistleblowing (Laporan Rahasia / Anonim)' : 'Pelayanan Sarana & Akademik' }}</span>
                                    </div>
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                        <span class="text-slate-400 text-[11px] block">Unit Penanggung Jawab</span>
                                        <span class="font-bold text-slate-800">{{ $subDeptLabel }}</span>
                                    </div>
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                        <span class="text-slate-400 text-[11px] block">Lokasi Kampus FIKKIA</span>
                                        <span class="font-bold text-slate-800">{{ $campusLabel }}</span>
                                    </div>
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                        <span class="text-slate-400 text-[11px] block">Petugas Lapangan</span>
                                        <span class="font-bold text-slate-800">{{ $report->handler?->name ?? 'Belum Ditugaskan' }}</span>
                                    </div>
                                </div>

                                <!-- Deskripsi Keluhan -->
                                <div class="mt-4">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                        Deskripsi / Isi Keluhan Lengkap:
                                    </label>
                                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 leading-relaxed whitespace-pre-line text-xs sm:text-sm break-words font-normal">
                                        {{ $report->description }}
                                    </div>
                                </div>

                                <!-- Jaminan Kerahasiaan Notice -->
                                @if ($report->type === 'whistleblowing')
                                    <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200 flex items-start gap-3 text-xs text-amber-900">
                                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        <div class="leading-relaxed">
                                            <strong class="font-bold block text-amber-950">Jaminan Kerahasiaan Pelapor Anonim</strong>
                                            Identitas pengirim dilindungi sepenuhnya. Sistem E-Lapor FIKKIA tidak menyimpan atau mempublikasikan identitas pelapor whistleblowing pada portal pelacakan ini.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </section>

                        <!-- SECTION B: BUKTI LAPORAN (PELAPOR & PETUGAS) -->
                        <section class="bg-white rounded-3xl border border-slate-200/90 shadow-card p-6 sm:p-7 print-card">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-[#0c3934] flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-[#ecfdf5] text-[#0a8080] flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <span>Bukti Laporan &amp; Dokumentasi</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Lampiran bukti awal dari pelapor dan foto bukti penyelesaian dari petugas</p>
                                </div>

                                <!-- Tab Switcher -->
                                <div class="inline-flex p-1 bg-slate-100 rounded-xl text-xs font-semibold self-start sm:self-auto no-print">
                                    <button type="button"
                                            @click="evidenceTab = 'semua'"
                                            class="px-3 py-1.5 rounded-lg transition"
                                            :class="evidenceTab === 'semua' ? 'bg-white text-[#0c3934] shadow-xs' : 'text-slate-500 hover:text-slate-800'">
                                        Semua ({{ $report->evidences->count() }})
                                    </button>
                                    <button type="button"
                                            @click="evidenceTab = 'pelapor'"
                                            class="px-3 py-1.5 rounded-lg transition"
                                            :class="evidenceTab === 'pelapor' ? 'bg-white text-[#0c3934] shadow-xs' : 'text-slate-500 hover:text-slate-800'">
                                        Pelapor ({{ $evidencesPelapor->count() }})
                                    </button>
                                    <button type="button"
                                            @click="evidenceTab = 'petugas'"
                                            class="px-3 py-1.5 rounded-lg transition"
                                            :class="evidenceTab === 'petugas' ? 'bg-white text-[#0c3934] shadow-xs' : 'text-slate-500 hover:text-slate-800'">
                                        Petugas ({{ $evidencesPetugas->count() }})
                                    </button>
                                </div>
                            </div>

                            <!-- Visibility Check -->
                            @if ($report->show_evidence_to_pelapor === false)
                                <div class="p-6 text-center text-slate-500 bg-slate-50 rounded-2xl border border-slate-200 mt-5">
                                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                    <p class="font-bold text-slate-700 text-xs sm:text-sm">Lampiran Bukti Bersifat Internal</p>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Dokumentasi bukti dibatasi oleh pihak administrasi untuk keperluan penyelidikan internal.</p>
                                </div>
                            @elseif ($report->evidences->isEmpty())
                                <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200 mt-5">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="font-bold text-slate-700 text-xs sm:text-sm">Belum ada lampiran bukti yang diunggah</p>
                                    <p class="text-xs text-slate-400 mt-1">Laporan ini diajukan tanpa lampiran file atau bukti pengerjaan teknisi belum diunggah.</p>
                                </div>
                            @else
                                <div class="mt-5 space-y-6">

                                    <!-- BUKTI PELAPOR SECTION -->
                                    <div x-show="evidenceTab === 'semua' || evidenceTab === 'pelapor'" class="space-y-3">
                                        <div class="flex items-center gap-2 text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            <span class="w-2.5 h-2.5 rounded-full bg-teal-600"></span>
                                            <span>Bukti Awal dari Pelapor ({{ $evidencesPelapor->count() }})</span>
                                        </div>

                                        @if ($evidencesPelapor->isEmpty())
                                            <p class="text-xs text-slate-400 italic bg-slate-50 p-3 rounded-xl border border-slate-200/60">Tidak ada lampiran bukti awal yang diunggah oleh pelapor.</p>
                                        @else
                                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5">
                                                @foreach ($evidencesPelapor as $ev)
                                                    @php
                                                        $url = asset('storage/' . $ev->file_path);
                                                        $ext = strtolower(pathinfo($ev->file_path, PATHINFO_EXTENSION));
                                                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                                        $size = $fileSize($ev->file_path);
                                                        $filename = basename($ev->file_path);
                                                    @endphp

                                                    @if ($isImg)
                                                        <div class="group relative bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition">
                                                            <div class="aspect-4/3 w-full bg-slate-100 overflow-hidden relative cursor-pointer"
                                                                 @click="activeImage = '{{ $url }}'; activeImageTitle = '{{ $filename }}'; showImageModal = true">
                                                                <img src="{{ $url }}" alt="Bukti Pelapor" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                                                </div>
                                                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-[#0a8080] text-white text-[10px] font-bold shadow-xs">
                                                                    Pelapor
                                                                </span>
                                                            </div>
                                                            <div class="p-2.5">
                                                                <p class="text-[11px] font-semibold text-slate-800 truncate" title="{{ $filename }}">{{ $filename }}</p>
                                                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $size ?? 'Gambar' }} &bull; {{ $ev->created_at->format('d M H:i') }}</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                                           class="group bg-white border border-slate-200 rounded-2xl p-3 shadow-xs hover:shadow-md hover:border-[#0a8080] transition flex flex-col justify-between">
                                                            <div class="flex items-center gap-2.5 mb-2">
                                                                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                                </div>
                                                                <div class="min-w-0">
                                                                    <span class="text-[9px] font-bold uppercase tracking-wider text-rose-600 bg-rose-100/70 px-1.5 py-0.5 rounded">{{ $ext ?: 'DOC' }}</span>
                                                                    <p class="text-[11px] font-semibold text-slate-800 truncate mt-1" title="{{ $filename }}">{{ $filename }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                                                                <span>{{ $size ?? 'Dokumen' }}</span>
                                                                <span class="text-[#0a8080] font-semibold group-hover:underline">Buka &rarr;</span>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- BUKTI PETUGAS SECTION -->
                                    <div x-show="evidenceTab === 'semua' || evidenceTab === 'petugas'" class="space-y-3 pt-3 border-t border-slate-100">
                                        <div class="flex items-center gap-2 text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                                            <span>Bukti Pengerjaan dari Petugas ({{ $evidencesPetugas->count() }})</span>
                                        </div>

                                        @if ($evidencesPetugas->isEmpty())
                                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70 text-center">
                                                <p class="text-xs text-slate-500 font-semibold">Belum ada bukti penanganan yang diunggah oleh petugas lapangan.</p>
                                                <p class="text-[11px] text-slate-400 mt-0.5">Bukti foto atau dokumen hasil perbaikan akan muncul di sini setelah teknisi menyelesaikan pekerjaannya.</p>
                                            </div>
                                        @else
                                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5">
                                                @foreach ($evidencesPetugas as $ev)
                                                    @php
                                                        $url = asset('storage/' . $ev->file_path);
                                                        $ext = strtolower(pathinfo($ev->file_path, PATHINFO_EXTENSION));
                                                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                                        $size = $fileSize($ev->file_path);
                                                        $filename = basename($ev->file_path);
                                                    @endphp

                                                    @if ($isImg)
                                                        <div class="group relative bg-white border border-emerald-200/90 rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition">
                                                            <div class="aspect-4/3 w-full bg-slate-100 overflow-hidden relative cursor-pointer"
                                                                 @click="activeImage = '{{ $url }}'; activeImageTitle = '{{ $filename }}'; showImageModal = true">
                                                                <img src="{{ $url }}" alt="Bukti Petugas" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                                                </div>
                                                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-[#059669] text-white text-[10px] font-bold shadow-xs">
                                                                    Hasil Petugas
                                                                </span>
                                                            </div>
                                                            <div class="p-2.5">
                                                                <p class="text-[11px] font-semibold text-slate-800 truncate" title="{{ $filename }}">{{ $filename }}</p>
                                                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $size ?? 'Gambar' }} &bull; {{ $ev->created_at->format('d M H:i') }}</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                                           class="group bg-white border border-emerald-200 rounded-2xl p-3 shadow-xs hover:shadow-md hover:border-emerald-500 transition flex flex-col justify-between">
                                                            <div class="flex items-center gap-2.5 mb-2">
                                                                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                                </div>
                                                                <div class="min-w-0">
                                                                    <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100/70 px-1.5 py-0.5 rounded">{{ $ext ?: 'DOC' }}</span>
                                                                    <p class="text-[11px] font-semibold text-slate-800 truncate mt-1" title="{{ $filename }}">{{ $filename }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                                                                <span>{{ $size ?? 'Dokumen' }}</span>
                                                                <span class="text-emerald-700 font-semibold group-hover:underline">Buka &rarr;</span>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </section>

                        <!-- SECTION C: CATATAN & KOMENTAR DARI PETUGAS / PIMPINAN -->
                        <section class="bg-white rounded-3xl border border-slate-200/90 shadow-card p-6 sm:p-7 print-card">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-[#0c3934] flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-[#ecfdf5] text-[#0a8080] flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        </div>
                                        <span>Catatan &amp; Komentar Penanganan</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Ulasan resmi, alasan tindak lanjut, dan instruksi penanganan dari petugas &amp; pimpinan</p>
                                </div>
                            </div>

                            @php
                                $validDispositions = $report->dispositions ? $report->dispositions->whereNotNull('notes')->filter(fn($d) => trim($d->notes) !== '') : collect();
                                $hasResolutionInDisp = $validDispositions->contains(fn($d) => trim($d->notes ?? '') === trim($report->resolution_notes ?? ''));
                                $hasAnyNotes = !empty($report->resolution_notes) || !empty($report->postponed_reason) || !empty($report->rejection_reason) || $validDispositions->isNotEmpty();
                            @endphp

                            @if ($report->show_notes_to_pelapor === false)
                                <div class="p-6 text-center text-slate-500 bg-slate-50 rounded-2xl border border-slate-200 mt-5">
                                    <p class="font-bold text-slate-700 text-xs sm:text-sm">Catatan Bersifat Internal</p>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Catatan tindak lanjut hanya dapat dilihat oleh staf berwenang.</p>
                                </div>
                            @elseif (!$hasAnyNotes)
                                <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200 mt-5">
                                    <p class="font-bold text-slate-700 text-xs sm:text-sm">Belum ada catatan atau komentar petugas</p>
                                    <p class="text-xs text-slate-400 mt-1">Catatan dan instruksi kerja dari petugas atau pimpinan akan tercantum di sini.</p>
                                </div>
                            @else
                                <div class="mt-5 space-y-4">

                                    <!-- 1. Alasan Penolakan jika ada -->
                                    @if (!empty($report->rejection_reason))
                                        <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 space-y-2">
                                            <div class="flex items-center justify-between gap-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs font-bold">!</span>
                                                    <span class="font-bold text-xs uppercase tracking-wider text-rose-800">Alasan Penolakan Laporan</span>
                                                </div>
                                                <span class="text-[10px] text-rose-500">{{ $report->updated_at->format('d M Y, H:i') }} WIB</span>
                                            </div>
                                            <div class="text-xs sm:text-sm leading-relaxed whitespace-pre-line pl-8 font-medium">
                                                {{ $report->rejection_reason }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- 2. Alasan Penundaan jika ada -->
                                    @if (!empty($report->postponed_reason))
                                        <div class="p-4 sm:p-5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 space-y-2">
                                            <div class="flex items-center justify-between gap-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold">⏱</span>
                                                    <span class="font-bold text-xs uppercase tracking-wider text-amber-800">Alasan Penundaan Pekerjaan</span>
                                                </div>
                                                <span class="text-[10px] text-amber-600">{{ $report->updated_at->format('d M Y, H:i') }} WIB</span>
                                            </div>
                                            <div class="text-xs sm:text-sm leading-relaxed whitespace-pre-line pl-8 font-medium">
                                                {{ $report->postponed_reason }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- 3. Catatan Penyelesaian Pekerjaan Petugas / Pimpinan -->
                                    @if (!empty($report->resolution_notes) && !$hasResolutionInDisp)
                                        <div class="p-4 sm:p-5 rounded-2xl bg-[#f0fdfa] border-2 border-[#ccfbf1] text-[#0c3934] space-y-2 shadow-xs">
                                            <div class="flex items-center justify-between gap-2">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-7 h-7 rounded-lg bg-[#0a8080] text-white flex items-center justify-center text-xs font-bold shadow-xs">
                                                        ✓
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-xs text-[#0c3934] block">Catatan Resmi Penyelesaian / Tindak Lanjut</span>
                                                        <span class="text-[10px] text-[#0a8080] font-semibold uppercase tracking-wider">Oleh: {{ $report->handler?->name ?? 'Petugas FIKKIA' }}</span>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] text-slate-400">{{ $report->updated_at->format('d M Y, H:i') }} WIB</span>
                                            </div>
                                            <div class="p-3.5 rounded-xl bg-white/80 border border-[#99f6e4] text-xs sm:text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                                                {{ $report->resolution_notes }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- 4. Riwayat Disposisi / Komentar dari Petugas & Pimpinan -->
                                    @if ($validDispositions->isNotEmpty())
                                        <div class="space-y-3 pt-2">
                                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Riwayat Instruksi &amp; Komentar Disposisi:
                                            </h4>

                                            <div class="space-y-3">
                                                @foreach ($validDispositions as $disp)
                                                    @php
                                                        $senderRole = strtolower($disp->sender?->role ?? 'petugas');
                                                        $isKabagSender = $senderRole === 'kabag';
                                                        $isKasubagSender = $senderRole === 'kasubag';
                                                    @endphp
                                                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-slate-300 transition space-y-2">
                                                        <div class="flex items-center justify-between gap-2">
                                                            <div class="flex items-center gap-2.5">
                                                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white shadow-xs
                                                                    {{ $isKabagSender ? 'bg-[#0c3934]' : ($isKasubagSender ? 'bg-[#0a8080]' : 'bg-emerald-600') }}">
                                                                    {{ strtoupper(substr($disp->sender?->name ?? 'P', 0, 1)) }}
                                                                </div>
                                                                <div>
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="font-bold text-xs text-slate-900">{{ $disp->sender?->name ?? 'Petugas FIKKIA' }}</span>
                                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                                                            {{ $isKabagSender ? 'bg-teal-100 text-teal-800' : ($isKasubagSender ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800') }}">
                                                                            {{ strtoupper($disp->sender?->role ?? 'Petugas') }}
                                                                        </span>
                                                                    </div>
                                                                    @if ($disp->receiver)
                                                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                                                            Diteruskan ke: <strong class="text-slate-600">{{ $disp->receiver->name }} ({{ strtoupper($disp->receiver->role ?? '') }})</strong>
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <span class="text-[10px] text-slate-400 shrink-0 text-right">
                                                                {{ $disp->created_at->format('d M Y') }}<br>{{ $disp->created_at->format('H:i') }} WIB
                                                            </span>
                                                        </div>

                                                        <div class="p-3 rounded-xl bg-white border border-slate-200/70 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                                            {{ $disp->notes }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </section>
                    </div>

                    <!-- ================= RIGHT COLUMN: ALUR PROSES & BANTUAN (4 COLS) ================= -->
                    <div class="lg:col-span-5 xl:col-span-4 space-y-6 min-w-0">

                        <!-- SECTION D: PERJALANAN / ALUR PROSES PENANGANAN -->
                        <section class="bg-white rounded-3xl border border-slate-200/90 shadow-card p-6 sm:p-7 print-card">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-[#0c3934] flex items-center gap-2">
                                        <svg class="w-5 h-5 text-[#0a8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Alur Penanganan Laporan</span>
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Perjalanan proses dari pengajuan sampai selesai</p>
                                </div>
                            </div>

                            <!-- Stepper List -->
                            <ol class="mt-6 relative">
                                @foreach ($steps as $index => $s)
                                    @php
                                        $isLast = $loop->last;
                                        $isPassed = $s['is_passed'];
                                        $isActive = $s['is_active'] ?? false;
                                        $isRej = $s['is_rejected'] ?? false;
                                    @endphp

                                    <li class="relative flex gap-3.5 {{ $isLast ? '' : 'pb-6' }}">
                                        <!-- Vertical Line Connector -->
                                        @unless ($isLast)
                                            <span class="absolute left-[15px] top-8 bottom-0 w-[2px] {{ $isPassed ? 'bg-[#0a8080]' : 'bg-slate-200' }}"></span>
                                        @endunless

                                        <!-- Step Icon Node -->
                                        <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0 font-bold text-xs transition duration-200
                                            {{ $isRej ? 'bg-rose-600 text-white shadow-md' : ($isPassed ? 'bg-[#0a8080] text-white shadow-xs' : ($isActive ? 'bg-[#ccfbf1] text-[#0a8080] border-2 border-[#0a8080] ring-4 ring-[#ecfdf5]' : 'bg-slate-100 text-slate-400 border border-slate-200')) }}">
                                            @if ($isRej)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            @elseif ($isPassed)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            @elseif ($isActive)
                                                <span class="w-2 h-2 rounded-full bg-[#0a8080] animate-ping"></span>
                                            @else
                                                <span>{{ $loop->iteration }}</span>
                                            @endif
                                        </div>

                                        <!-- Step Details -->
                                        <div class="min-w-0 flex-1 pt-0.5">
                                            <div class="flex items-center justify-between gap-2">
                                                <p class="text-xs sm:text-[13px] font-bold {{ $isRej ? 'text-rose-700' : ($isPassed ? 'text-slate-900' : ($isActive ? 'text-[#0a8080]' : 'text-slate-400')) }}">
                                                    {{ $s['title'] }}
                                                </p>
                                                @if ($isActive)
                                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#ccfbf1] text-[#064e3b] uppercase tracking-wider animate-pulse">
                                                        Sedang Berjalan
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 font-medium">
                                                {{ $s['time'] }} &bull; Oleh: <strong class="text-slate-600">{{ $s['actor'] }}</strong>
                                            </p>

                                            <p class="text-xs {{ $isRej ? 'text-rose-600 font-semibold' : 'text-slate-600' }} mt-1 leading-relaxed break-words">
                                                {{ $s['desc'] }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </section>

                        <!-- SECTION E: BUTUH BANTUAN HELPDESK CARD -->
                        <section class="bg-gradient-to-br from-white to-[#f0fdfa] rounded-3xl border border-[#ccfbf1] shadow-card p-6 no-print space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-white border border-[#99f6e4] p-2 flex items-center justify-center shrink-0 shadow-xs">
                                    <img src="{{ asset('assets/help_illustration.png') }}" alt="Helpdesk FIKKIA" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-[#0c3934]">Pusat Layanan &amp; Pengaduan</h4>
                                    <p class="text-xs text-slate-500">FIKKIA Universitas Airlangga</p>
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed">
                                Apabila penanganan laporan Anda membutuhkan klarifikasi mendesak, silakan hubungi tim layanan terpadu FIKKIA melalui kanal resmi di bawah ini.
                            </p>

                            <div class="space-y-2 pt-1">
                                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20E-Lapor%20FIKKIA%2C%20saya%20ingin%20menanyakan%20status%20tiket%20{{ $report->ticket_code }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-bold text-xs shadow-xs transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.158.572 4.184 1.572 5.932l-1.572 5.736 5.894-1.545c1.701.928 3.654 1.477 5.736 1.477 6.627 0 12-5.373 12-12 0-6.627-5.373-12-12-12z"/></svg>
                                    <span>Chat WhatsApp Petugas</span>
                                </a>

                                <div class="p-3 bg-white/70 rounded-xl border border-slate-200/70 text-[11px] text-slate-500 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span>Jam Kerja:</span>
                                        <strong class="text-slate-700">Senin - Jumat (08:00 - 16:00)</strong>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>Lokasi Kampus:</span>
                                        <strong class="text-slate-700">Mojo &bull; Sobo &bull; Giri</strong>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <!-- ================= IMAGE LIGHTBOX MODAL ================= -->
    <div x-show="showImageModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs no-print"
         @keydown.escape.window="showImageModal = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <!-- Backdrop click to close -->
        <div class="absolute inset-0" @click="showImageModal = false"></div>

        <!-- Modal Content -->
        <div class="relative z-10 max-w-4xl max-h-[90vh] bg-white rounded-3xl overflow-hidden shadow-2xl flex flex-col"
             @click.outside="showImageModal = false">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                <div class="flex items-center gap-2 min-w-0 pr-4">
                    <svg class="w-5 h-5 text-[#0a8080] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-xs sm:text-sm font-bold text-slate-800 truncate" x-text="activeImageTitle || 'Bukti Lampiran'"></span>
                </div>
                <div class="flex items-center gap-2">
                    <a :href="activeImage" download target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold transition"
                       title="Unduh Gambar">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Unduh</span>
                    </a>
                    <button type="button" @click="showImageModal = false"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition"
                            title="Tutup Preview">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Modal Image Body -->
            <div class="p-4 bg-slate-900 flex items-center justify-center overflow-auto max-h-[75vh]">
                <img :src="activeImage" :alt="activeImageTitle" class="max-w-full max-h-[70vh] object-contain rounded-lg">
            </div>
        </div>
    </div>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-white border-t border-slate-200 py-6 px-4 mt-12 no-print">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-3 text-center sm:text-left">
            <div>
                <p class="font-bold text-slate-700">&copy; {{ date('Y') }} FIKKIA Universitas Airlangga.</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Sistem Pengaduan Pelayanan &amp; Whistleblowing Terpadu</p>
            </div>
            <div class="flex items-center gap-4 text-slate-500 font-medium">
                <a href="{{ route('home') }}" class="hover:text-[#0a8080] transition">Beranda</a>
                <span>&bull;</span>
                <a href="{{ route('whistleblowing.create') }}" class="hover:text-[#0a8080] transition">Kirim Pengaduan</a>
                <span>&bull;</span>
                <a href="https://fikkia.unair.ac.id" target="_blank" rel="noopener noreferrer" class="hover:text-[#0a8080] transition">Portal FIKKIA</a>
            </div>
            <div class="text-[11px] text-slate-400">
                Kampus Mojo &bull; Sobo &bull; Giri (Banyuwangi)
            </div>
        </div>
    </footer>

</body>
</html>
