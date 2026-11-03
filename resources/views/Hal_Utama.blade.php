<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-LAPOR FIKKIA UNAIR - Layanan Aspirasi & Pengaduan Kampus</title>

  <!-- Google Fonts: Plus Jakarta Sans, Poppins, Caveat -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            poppins: ['"Poppins"', 'sans-serif'],
            caveat: ['"Caveat"', 'cursive'],
          },
          colors: {
            brand: {
              header: '#0C3934',
              primaryTeal: '#0A8080',
              primaryHover: '#086666',
              blueAction: '#2868A8',
              blueActionHover: '#1E5285',
              darkHeading: '#0E3B43',
              darkText: '#1E293B',
              slateText: '#475569',
              lightMint: '#CCFBF1',
              softMintBorder: '#99F6E4',
              trackBg: '#E9F0F8',
              trackTitle: '#043A8C',
              trackText: '#647C9E',
              trackBtn: '#788DAC',
            }
          }
        }
      }
    }
  </script>
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #FFFFFF;
    }
    .custom-scroll::-webkit-scrollbar {
      width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
      background-color: #cbd5e1;
      border-radius: 9999px;
    }
  </style>
</head>
<body class="min-h-screen bg-white flex flex-col text-slate-800 antialiased selection:bg-[#0A8080] selection:text-white relative overflow-x-hidden">

  <!-- ================= TOP HEADER BAR ================= -->
  @include('komponen.header_auth')



  <!-- ================= MAIN CONTENT AREA ================= -->
  <main class="flex-1 w-full relative overflow-hidden py-8 sm:py-10 px-4 sm:px-6 lg:px-12 flex flex-col justify-between">

    <!-- Background Decorative Soft Shapes (FigCraft nodes 4 & 5) -->
    <div class="absolute -top-24 right-10 w-[550px] h-[550px] bg-[#CCFBF1]/40 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute top-[400px] -left-20 w-[500px] h-[500px] bg-[#D1FAE5]/40 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="w-full max-w-7xl mx-auto z-10 relative space-y-8 lg:space-y-10">

      <!-- ================= HERO SECTION ================= -->
      <!-- FigCraft: Group 11 (Left) + Background building graphic & handwritten slogan (Right) -->
      <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center pt-2">

        <!-- Left: Text Header & Pill -->
        <div class="lg:col-span-7 flex flex-col gap-4">
          <!-- Tag / Badge Pill (FigCraft: Frame 'Tag / Badge Pill' @ #28) -->
          <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-[#CCFBF1] border border-[#99F6E4] text-[#115E59] font-['Plus_Jakarta_Sans',sans-serif] text-xs sm:text-[13px] font-bold w-fit tracking-wide shadow-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-[#115E59]"></span>
            E-Lapor FIKKIA
          </div>

          <!-- Heading 1 (FigCraft: text 'Pilih Jenis Laporan' Plus Jakarta Sans 36px Extra Bold) -->
          <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-extrabold text-[#0E3B43] font-['Plus_Jakarta_Sans',sans-serif] tracking-tight leading-tight">
            Pilih Jenis Laporan
          </h1>

          <!-- Subtitle (FigCraft: #33 Plus Jakarta Sans 16px Regular) -->
          <p class="text-sm sm:text-base text-[#475569] font-['Plus_Jakarta_Sans',sans-serif] leading-relaxed max-w-xl">
            Silakan pilih jenis laporan yang ingin Anda lacak.<br class="hidden sm:inline">
            Pastikan data yang Anda masukkan sudah benar agar proses pelacakan berjalan lancar.
          </p>
        </div>

        <!-- Right: Rektorat Building Image with Slanted Handwritten Slogan (FigCraft: Frame #17) -->
        <div class="lg:col-span-5 flex justify-center lg:justify-end">
          <div class="relative w-full max-w-[480px] rounded-2xl overflow-hidden shadow-sm group">
            <!-- Exact exported building image with handwritten slogan -->
            <img src="{{ asset('assets/gedung_kampus.png') }}" alt="Gedung Rektorat UNAIR" class="w-full h-auto object-cover transform group-hover:scale-102 transition duration-500">

            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent flex items-center p-4 sm:p-8">
            <!-- Handwritten Slogan (Font Caveat Bold) -->
            <div class="font-['Caveat',cursive] font-bold text-white text-3xl sm:text-4xl lg:text-[40px] leading-tight drop-shadow-lg select-none -rotate-12">
              Bersama<br>Wujudkan<br>Layanan yang<br>Lebih Baik
            </div>
          </div>
          </div>
        </div>

      </section>

      <!-- ================= 2 MAIN CARDS: LAPORAN PELAYANAN & WHISTLEBLOWER ================= -->
      <!-- FigCraft: Group 34 'LAPORAN PELAYANAN' & Group 90 'LAPORAN ANONIM' -->
      <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">

        <!-- ================= CARD 1: LAPORAN PELAYANAN ================= -->
        <div class="bg-white rounded-[24px] sm:rounded-[28px] border border-[#99F6E4] shadow-sm hover:shadow-md transition-shadow duration-300 p-6 sm:p-7 flex flex-col justify-between gap-5 relative overflow-hidden group">

          <!-- Top Row: Icon, Titles & User Avatar Badge -->
          <div class="space-y-4">
            <div class="flex items-start justify-between gap-3 sm:gap-4">

              <!-- Left: Graphical Icon Illustration (#79) -->
              <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-[16px] bg-[#F0FDFA] border border-[#CCFBF1] flex items-center justify-center relative shrink-0 shadow-xs">
                <!-- Inner Document Icon -->
                <img src="{{ asset('assets/icon_pelayanan.png') }}" alt="Laporan Pelayanan" class="w-12 h-12 object-contain">
              </div>

              <!-- Middle: Title & Subtitle (#76 & #78) -->
              <div class="flex-1">
                <h2 class="text-xl sm:text-[22px] font-bold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight group-hover:text-[#0A8080] transition-colors">
                  Laporan Pelayanan
                </h2>
                <p class="text-xs sm:text-[13.5px] text-[#475569] font-['Plus_Jakarta_Sans',sans-serif] mt-1.5 leading-normal">
                  Digunakan untuk membuat laporan Pelayanan, fasilitas, akademik, SDM, dan lainya mengunakan akun (NIM/NIP).
                </p>
              </div>

              <!-- Right: Circular Teal User Avatar Badge (#70) -->
              <div class="w-8 h-8 rounded-full bg-[#0F766E] flex items-center justify-center text-white shrink-0 shadow-xs" title="Akun Pengguna Terdaftar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
            </div>

            <!-- Information Notice Box (FigCraft: Frame #41, bg: #ECFDF5, border: #A7F3D0) -->
            <div class="rounded-xl sm:rounded-[14px] bg-[#ECFDF5] border border-[#A7F3D0] p-4 sm:p-4.5 space-y-2 text-xs sm:text-[13px] text-[#064E3B] font-['Plus_Jakarta_Sans',sans-serif]">

              <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Masuk menggunakan NIM/NIP dan password untuk mengisi laporan</span>
              </div>

              <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Identitas tercatat untuk validitas laporan</span>
              </div>

              <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Cocok untuk aduan fasilitas & akademik umum</span>
              </div>

              <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Riwayat laporan tersimpan otomatis di profil</span>
              </div>

            </div>
          </div>

          <!-- Action Button (FigCraft: Frame #36, bg: #0A8080, radius: 12px) -->
          <a href="{{ auth()->check() ? route('pelapor.dashboard') : route('login') }}" class="w-full h-11 sm:h-12 rounded-xl bg-[#0A8080] hover:bg-[#086666] text-white font-semibold font-['Plus_Jakarta_Sans',sans-serif] text-sm sm:text-base flex items-center justify-center gap-2 transition duration-200 shadow-sm hover:shadow-md">
            <span>Lanjutkan</span>
            <span class="text-lg">→</span>
          </a>

        </div>

        <!-- ================= CARD 2: LAPORAN WHISTLEBLOWER ================= -->
        <div class="bg-white rounded-[24px] sm:rounded-[28px] border border-[#99F6E4] shadow-sm hover:shadow-md transition-shadow duration-300 p-6 sm:p-7 flex flex-col justify-between gap-5 relative overflow-hidden group">

          <!-- Top Row: Icon & Titles -->
          <div class="space-y-4">
            <div class="flex items-start justify-between gap-3 sm:gap-4">

              <!-- Left: Graphical Icon Illustration (#130) -->
              <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-[16px] bg-[#EFF6FF] border border-[#DBEAFE] flex items-center justify-center relative shrink-0 shadow-xs">
                <!-- Inner Lock Icon -->
                <img src="{{ asset('assets/icon_whistleblower.png') }}" alt="Laporan Whistleblower" class="w-12 h-12 object-contain">
              </div>

              <!-- Middle: Title & Subtitle (#127 & #129) -->
              <div class="flex-1">
                <h2 class="text-xl sm:text-[22px] font-bold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight group-hover:text-[#2868A8] transition-colors">
                  Laporan Whistleblower
                </h2>
                <p class="text-xs sm:text-[13.5px] text-[#475569] font-['Plus_Jakarta_Sans',sans-serif] mt-1.5 leading-normal">
                  Digunakan untuk membuat laporan anonim dengan identitas yang terjaga.
                </p>
              </div>

              <!-- Right: Spacer / Badge placeholder for balance -->
              <div class="w-8 h-8 rounded-full bg-blue-50 border border-blue-200 flex items-center justify-center text-[#2868A8] shrink-0 shadow-xs" title="Laporan 100% Rahasia">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
            </div>

            <!-- Information Notice Box (FigCraft: Frame #97, bg: #EFF6FF, border: #BFDBFE) -->
            <div class="rounded-xl sm:rounded-[14px] bg-[#EFF6FF] border border-[#BFDBFE] p-4 sm:p-4.5 space-y-2 text-xs sm:text-[13px] text-[#1E3A8A] font-['Plus_Jakarta_Sans',sans-serif]">

              <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#2563EB] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Membuat laporan tanpa login</span>
              </div>

              <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#2563EB] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Identitas dijamin 100% rahasia & aman</span>
              </div>

              <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#2563EB] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Cocok untuk laporan sensitif / khusus</span>
              </div>

              <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#2563EB] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Tetap bisa melacak progres laporan (via kode unik)</span>
              </div>

            </div>
          </div>

          <!-- Action Button (FigCraft: Frame #92, bg: #2868A8, radius: 12px) -->
          <a href="{{ route('whistleblowing.create') }}" class="w-full h-11 sm:h-12 rounded-xl bg-[#2868A8] hover:bg-[#1E5285] text-white font-semibold font-['Plus_Jakarta_Sans',sans-serif] text-sm sm:text-base flex items-center justify-center gap-2 transition duration-200 shadow-sm hover:shadow-md">
            <span>Lanjutkan</span>
            <span class="text-lg">→</span>
          </a>

        </div>

      </section>

      <!-- ================= TRACKING BANNER ("Lacak laporanmu") ================= -->
      <section class="flex justify-center w-full">
        <a href="{{ route('tracking') }}" class="w-full max-w-2xl bg-[#E9F0F8] hover:bg-[#dfeaf5] border border-slate-200/60 rounded-[14px] sm:rounded-[18px] p-4 sm:p-5 flex items-center justify-between gap-4 transition duration-200 shadow-xs hover:shadow-sm group">

          <div class="flex items-center gap-3.5 sm:gap-4">
            <!-- Circular Search Icon Badge (#151) -->
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-[#647C9E] flex items-center justify-center text-white shrink-0 shadow-xs group-hover:scale-105 transition-transform">
              <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>

            <!-- Text Content (#149 & #150) -->
            <div class="text-left">
              <h3 class="text-base sm:text-[17px] font-semibold text-[#043A8C] font-['Poppins',sans-serif] leading-tight">
                Lacak laporanmu
              </h3>
              <p class="text-xs sm:text-[13px] text-[#647C9E] font-['Poppins',sans-serif] font-medium leading-snug mt-1">
                Pantau status dan perkembangan tindak lanjut dari laporan yang telah Anda ajukan menggunakan nomor lapor.
              </p>
            </div>
          </div>

          <!-- Arrow Action Button (#158 & #159) -->
          <div class="w-12 sm:w-12 h-6 sm:h-11 rounded-[14px] bg-[#788DAC] group-hover:bg-[#5f7494] text-white flex items-center justify-center shrink-0 transition-colors shadow-xs">
            <svg class="w-3 h-3 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </div>

        </a>
      </section>

      <!-- ================= 4 BOTTOM FEATURE CARDS ================= -->
      <!-- FigCraft: Group 19 (1216x100, border: #CCFBF1, radius: 16px) -->
      <section class="bg-white rounded-2xl border border-[#CCFBF1] shadow-sm p-5 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 lg:divide-[#DBEAFE]">

          <!-- Column 1: Respons Cepat (#163) -->
          <div class="flex items-center gap-3.5 pt-4 sm:pt-0 sm:px-3">
            <div class="w-10 h-10 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488] shrink-0 shadow-xs">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h4 class="text-sm font-bold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif]">Respons Cepat</h4>
              <p class="text-xs text-[#64748B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight mt-0.5">Laporan Anda akan segera ditindaklanjuti.</p>
            </div>
          </div>

          <!-- Column 2: Transparan (#171) -->
          <div class="flex items-center gap-3.5 pt-4 sm:pt-0 sm:px-3">
            <div class="w-10 h-10 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488] shrink-0 shadow-xs">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <div>
              <h4 class="text-sm font-bold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif]">Transparan</h4>
              <p class="text-xs text-[#64748B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight mt-0.5">Pantau perkembangan laporan secara berkala</p>
            </div>
          </div>

          <!-- Column 3: Akuntabel (#181) -->
          <div class="flex items-center gap-3.5 pt-4 sm:pt-0 sm:px-3">
            <div class="w-10 h-10 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488] shrink-0 shadow-xs">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div>
              <h4 class="text-sm font-bold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif]">Akuntabel</h4>
              <p class="text-xs text-[#64748B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight mt-0.5">Setiap proses tercatat dengan jelas</p>
            </div>
          </div>

          <!-- Column 4: Untuk Layanan yang Lebih Baik (#191) -->
          <div class="flex items-center gap-3.5 pt-4 sm:pt-0 sm:px-3">
            <div class="w-10 h-10 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488] shrink-0 shadow-xs">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <div>
              <h4 class="text-sm font-bold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif]">Untuk Layanan yang Lebih Baik</h4>
              <p class="text-xs text-[#64748B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight mt-0.5">Bersama kita wujudkan lingkungan kampus yang nyaman</p>
            </div>
          </div>

        </div>
      </section>

    </div>
  </main>

  <!-- =================  FOOTER ================= -->
  <!-- FigCraft: Frame 'FOOTER' (1440x42 @ y:982, fill: #0C3934) -->
  <footer class="w-full bg-[#0C3934] text-[#CCFBF1] py-3.5 px-4 sm:px-8 text-center text-xs font-['Plus_Jakarta_Sans',sans-serif] z-20 shadow-xs">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 font-medium">
      <span>© 2025 FIKKIA UNAIR</span>
      <span class="hidden sm:inline text-teal-400">|</span>
      <span>E-Lapor. Suara Anda, Perubahan Nyata.</span>
    </div>
  </footer>

</body>
</html>
