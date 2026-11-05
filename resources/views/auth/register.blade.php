<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Akun Pelapor - FIKKIA Universitas Airlangga</title>

  <!-- Google Fonts: Poppins, Plus Jakarta Sans, Caveat, Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            poppins: ['"Poppins"', 'sans-serif'],
            inter: ['"Inter"', 'sans-serif'],
            caveat: ['"Caveat"', 'cursive'],
          },
          colors: {
            brand: {
              header: '#0C3934',
              primary: '#0A7075',
              primaryHover: '#085C60',
              tealAccent: '#0A8080',
              navy: '#1A3A4B',
              darkText: '#0F172A',
              slateText: '#1A4A50',
              softMint: '#E5F5F3',
              borderMint: '#C1E8E3',
              cardBorder: '#99F6E4',
              bodyBg: '#F4F7F8',
              inputBg: '#F8FAFC',
              inputBorder: '#E2E8F0',
            }
          }
        }
      }
    }
  </script>
  <style>
    body {
      font-family: 'Poppins', 'Plus Jakarta Sans', sans-serif;
      background-color: #F4F7F8;
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
<body class="min-h-screen bg-[#F4F7F8] flex flex-col text-slate-800 antialiased selection:bg-[#0A7075] selection:text-white relative overflow-x-hidden">

  <!-- ================= TOP HEADER BAR ================= -->
  @include('komponen.header_auth')



  <!-- ================= MAIN CONTENT WRAPPER ================= -->
  <main class="flex-1 flex items-center justify-center py-8 sm:py-10 px-4 sm:px-6 lg:px-12 relative overflow-hidden">


    <div class="absolute inset-y-0 left-0 w-full lg:w-[50%] bg-gradient-to-br from-[#E8F7F5]/80 via-[#F0FBF9]/60 to-transparent pointer-events-none z-0"></div>


    <div class="absolute -bottom-10 -left-12 w-64 sm:w-80 lg:w-96 pointer-events-none z-0 select-none hidden sm:block opacity-90">
      <img src="{{ asset('assets/foliage_left.svg') }}" alt="" class="w-full h-auto">
    </div>

    <!-- Layout Grid: 12 Columns (5 Left : 7 Right for spacious 2-column form card) -->
    <div class="w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center z-10 relative">

      <section class="lg:col-span-5 flex flex-col gap-6 lg:pr-2">

         <a href="{{ route('home') }}"
       class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-[#0a8080] transition px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 w-fit">

        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>

        <span>Kembali ke Beranda</span>
    </a>
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F0FDFA] border border-[#99F6E4] text-[#115E59] font-['Poppins',sans-serif] text-xs font-bold w-fit tracking-wide shadow-sm">
          <span class="w-2 h-2 rounded-full bg-[#0D9488]"></span>
          E-LAPOR FIKKIA
        </div>

        <!-- Big Heading (FigCraft: 'Heading 2 - Big Main Title' font: Poppins 48px Extra Bold) -->
        <div class="space-y-3">
          <h1 class="text-3xl sm:text-4xl lg:text-[44px] xl:text-[48px] font-extrabold text-[#1A3A4B] font-['Poppins',sans-serif] leading-[1.18] tracking-tight">
            Satu Langkah<br class="hidden sm:inline"> untuk Perubahan
          </h1>
          <p class="text-sm sm:text-base text-[#475569] font-['Poppins',sans-serif] leading-relaxed max-w-lg font-normal">
            Sampaikan laporan Anda melalui E-Lapor UNAIR untuk mendukung lingkungan kampus yang lebih baik, transparan, dan responsif.
          </p>
        </div>

        <!-- 4 Feature Cards Grid (FigCraft: Groups 23, 24, 25, 26) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-3.5">

          <!-- Card 1: Respon Cepat -->
          <div class="bg-white p-3.5 sm:p-4 rounded-[14px] border border-[#CCFBF1] shadow-sm flex flex-col items-center text-center gap-2 transition hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="w-11 h-11 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488]">
              <!-- Clock Icon -->
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight">
              Respon Cepat
            </span>
          </div>

          <!-- Card 2: Mudah Digunakan -->
          <div class="bg-white p-3.5 sm:p-4 rounded-[14px] border border-[#CCFBF1] shadow-sm flex flex-col items-center text-center gap-2 transition hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="w-11 h-11 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488]">
              <!-- Shield Check Icon -->
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight">
              Mudah Digunakan
            </span>
          </div>


          <div class="bg-white p-3.5 sm:p-4 rounded-[14px] border border-[#CCFBF1] shadow-sm flex flex-col items-center text-center gap-2 transition hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="w-11 h-11 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488]">
              <!-- Document Icon -->
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight">
              Transparan dan Akuntable
            </span>
          </div>


          <div class="bg-white p-3.5 sm:p-4 rounded-[14px] border border-[#CCFBF1] shadow-sm flex flex-col items-center text-center gap-2 transition hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="w-11 h-11 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488]">
              <!-- Heart Icon -->
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight">
              Untuk Kampus yang Lebih Baik
            </span>
          </div>

        </div>


        <div class="relative w-full rounded-2xl overflow-hidden shadow-lg border border-white/60 group h-48 sm:h-60 lg:h-64">
          <img src="{{ asset('assets/gedung_kampus.png') }}" alt="Gedung Kampus FIKKIA UNAIR" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">

          <!-- Gradient Shadow Overlay -->
          <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/40 to-transparent flex items-center p-6 sm:p-8">
            <!-- Handwritten Slogan (Font Caveat Bold) -->
            <div class="font-['Caveat',cursive] font-bold text-white text-3xl sm:text-4xl leading-tight drop-shadow-lg select-none -rotate-12">
              Bersama<br>Wujudkan<br>Layanan yang<br>Lebih Baik
            </div>
          </div>
        </div>

      </section>

      <!-- ================= RIGHT COLUMN: REGISTER CARD ================= -->

      <section class="lg:col-span-7 flex justify-center w-full">
        <div class="w-full max-w-[680px] bg-white rounded-[28px] p-6 sm:p-8 md:p-10 shadow-xl border border-[#99F6E4] relative overflow-hidden">

          <!-- Card Header (FigCraft: Group 73 & Group 72) -->
          <div class="flex items-center gap-3.5 sm:gap-4 mb-6 sm:mb-8">
            <!-- Circular User Add Badge (#613 Group 73) -->
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-[#DCF2EE] flex items-center justify-center text-[#148F77] shrink-0 shadow-xs">
              <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>

            <!-- Title & Subtitle -->
            <div class="flex flex-col">
              <h2 class="text-2xl sm:text-[27px] font-bold text-[#144C52] font-['Poppins',sans-serif] tracking-tight leading-tight">
                Daftar Akun E-Lapor
              </h2>
              <p class="text-xs sm:text-sm text-slate-500 font-['Poppins',sans-serif] mt-0.5 font-medium leading-normal">
                Silahkan lengkapi data diri anda untuk melanjutkan
              </p>
            </div>
          </div>

          <!-- Error Alert -->
          @if ($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium">
              <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <!-- Registration Form -->
          <form id="registerForm" action="{{ route('register') }}" method="POST" class="space-y-4 sm:space-y-5" onsubmit="handleRegister(event)">
            @csrf

            <!-- Row 1: Nama Lengkap & NIM/NISN -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Field: Nama Lengkap (FigCraft: Group 74) -->
              <div class="space-y-1.5">
                <label for="name" class="block text-xs sm:text-[13px] font-semibold text-[#1A4A50] font-['Inter',sans-serif]">
                  Nama Lengkap
                </label>
                <div class="relative flex items-center h-[52px] sm:h-[56px] bg-[#F8FAFC] border border-[#E2E8F0] rounded-[15px] px-3.5 transition focus-within:border-[#0A7075] focus-within:ring-2 focus-within:ring-[#0A7075]/20">
                  <!-- User Outline Icon -->
                  <span class="text-[#7C9FA1] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </span>
                  <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="Masukan nama lengkap anda"
                    class="w-full pl-3 pr-2 text-xs sm:text-sm text-[#0F172A] font-medium placeholder:text-[#94A3B8] bg-transparent focus:outline-none font-['Inter',sans-serif]"
                  >
                </div>
              </div>

              <!-- Field: NIM/NISN (FigCraft: Group 75) -->
              <div class="space-y-1.5">
                <label for="identity_number" class="block text-xs sm:text-[13px] font-semibold text-[#1A4A50] font-['Inter',sans-serif]">
                  NIM/NISN
                </label>
                <div class="relative flex items-center h-[52px] sm:h-[56px] bg-[#F8FAFC] border border-[#E2E8F0] rounded-[15px] px-3.5 transition focus-within:border-[#0A7075] focus-within:ring-2 focus-within:ring-[#0A7075]/20">
                  <!-- ID / Credit Card Icon -->
                  <span class="text-[#7C9FA1] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                  </span>
                  <input
                    type="text"
                    id="identity_number"
                    name="identity_number"
                    value="{{ old('identity_number') }}"
                    required
                    placeholder="Masukan NIM atau NINS"
                    class="w-full pl-3 pr-2 text-xs sm:text-sm text-[#0F172A] font-medium placeholder:text-[#94A3B8] bg-transparent focus:outline-none font-['Inter',sans-serif]"
                  >
                </div>
              </div>
            </div>

            <!-- Row 2: Jabatan & Email -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Field: Jabatan (FigCraft: Group 76) -->
              <div class="space-y-1.5">
                <label for="position" class="block text-xs sm:text-[13px] font-semibold text-[#1A4A50] font-['Inter',sans-serif]">
                  Jabatan
                </label>
                <div class="relative flex items-center h-[52px] sm:h-[56px] bg-[#F8FAFC] border border-[#E2E8F0] rounded-[15px] px-3.5 transition focus-within:border-[#0A7075] focus-within:ring-2 focus-within:ring-[#0A7075]/20">
                  <!-- Briefcase Icon -->
                  <span class="text-[#94A3B8] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </span>
                  <select
                    id="position"
                    name="position"
                    required
                    class="w-full pl-3 pr-8 text-xs sm:text-sm text-[#0F172A] font-medium bg-transparent focus:outline-none appearance-none font-['Inter',sans-serif] cursor-pointer"
                  >
                    <option value="" disabled {{ old('position') ? '' : 'selected' }} >Pilih Jabatan Anda</option>
                    <option value="mahasiswa" {{ old('position') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ old('position') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="tendik" {{ old('position') === 'tendik' ? 'selected' : '' }}>Tenaga Kependidikan (Tendik)</option>
                  </select>
                  <!-- Dropdown Arrow Icon -->
                  <span class="absolute right-3.5 pointer-events-none text-[#94A3B8]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </span>
                </div>
              </div>

              <!-- Field: Email (FigCraft: Group 77) -->
              <div class="space-y-1.5">
                <label for="email" class="block text-xs sm:text-[13px] font-semibold text-[#1A4A50] font-['Inter',sans-serif]">
                  Email
                </label>
                <div class="relative flex items-center h-[52px] sm:h-[56px] bg-[#F8FAFC] border border-[#E2E8F0] rounded-[15px] px-3.5 transition focus-within:border-[#0A7075] focus-within:ring-2 focus-within:ring-[#0A7075]/20">
                  <!-- Message / Mail Icon -->
                  <span class="text-[#94A3B8] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </span>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="Masukan Email anda"
                    class="w-full pl-3 pr-2 text-xs sm:text-sm text-[#0F172A] font-medium placeholder:text-[#94A3B8] bg-transparent focus:outline-none font-['Inter',sans-serif]"
                  >
                </div>
              </div>
            </div>

            <!-- Row 3: No Telp & Password -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Field: No Telp (FigCraft: Group 78) -->
              <div class="space-y-1.5">
                <label for="phone_number" class="block text-xs sm:text-[13px] font-semibold text-[#1A4A50] font-['Inter',sans-serif]">
                  No Telp
                </label>
                <div class="relative flex items-center h-[52px] sm:h-[56px] bg-[#F8FAFC] border border-[#E2E8F0] rounded-[15px] px-3.5 transition focus-within:border-[#0A7075] focus-within:ring-2 focus-within:ring-[#0A7075]/20">
                  <!-- Phone Icon -->
                  <span class="text-[#7C9FA1] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                  </span>
                  <input
                    type="tel"
                    id="phone_number"
                    name="phone_number"
                    value="{{ old('phone_number') }}"
                    required
                    placeholder="08xxxxxxxxxx"
                    class="w-full pl-3 pr-2 text-xs sm:text-sm text-[#0F172A] font-medium placeholder:text-[#94A3B8] bg-transparent focus:outline-none font-['Inter',sans-serif]"
                  >
                </div>
              </div>

              <!-- Field: Password (FigCraft: Group 79) -->
              <div class="space-y-1.5">
                <label for="password" class="block text-xs sm:text-[13px] font-semibold text-[#1A4A50] font-['Inter',sans-serif]">
                  Password
                </label>
                <div class="relative flex items-center h-[52px] sm:h-[56px] bg-[#F8FAFC] border border-[#E2E8F0] rounded-[15px] px-3.5 transition focus-within:border-[#0A7075] focus-within:ring-2 focus-within:ring-[#0A7075]/20">
                  <!-- Lock Icon -->
                  <span class="text-[#94A3B8] shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </span>
                  <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Masukan password"
                    class="w-full pl-3 pr-10 text-xs sm:text-sm text-[#0F172A] font-medium placeholder:text-[#94A3B8] bg-transparent focus:outline-none font-['Inter',sans-serif]"
                  >
                  <!-- Toggle Password Eye Icon -->
                  <button
                    type="button"
                    onclick="toggleVisibility('password', 'eyeIconPassword')"
                    class="absolute right-3.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                    title="Lihat / sembunyikan password"
                  >
                    <svg id="eyeIconPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Row 4: Konfirmasi Password Full Width (FigCraft: Group 80) -->
            <div class="space-y-1.5">
              <label for="password_confirmation" class="block text-xs sm:text-[13px] font-semibold text-[#1A4A50] font-['Inter',sans-serif]">
                Konfirmasi Password
              </label>
              <div class="relative flex items-center h-[52px] sm:h-[56px] bg-[#F8FAFC] border border-[#E2E8F0] rounded-[15px] px-3.5 transition focus-within:border-[#0A7075] focus-within:ring-2 focus-within:ring-[#0A7075]/20">
                <!-- Lock Icon -->
                <span class="text-[#94A3B8] shrink-0">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </span>
                <input
                  type="password"
                  id="password_confirmation"
                  name="password_confirmation"
                  required
                  placeholder="Konfirmasi password anda"
                  class="w-full pl-3 pr-10 text-xs sm:text-sm text-[#0F172A] font-medium placeholder:text-[#94A3B8] bg-transparent focus:outline-none font-['Inter',sans-serif]"
                >
                <!-- Toggle Password Confirmation Eye Icon -->
                <button
                  type="button"
                  onclick="toggleVisibility('password_confirmation', 'eyeIconConfirm')"
                  class="absolute right-3.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                  title="Lihat / sembunyikan konfirmasi password"
                >
                  <svg id="eyeIconConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Row 5: Information Alert Box (FigCraft: Group 81) -->
            <div class="w-full p-3.5 sm:p-4 rounded-[11px] bg-[#E5F5F3] border border-[#C1E8E3] flex items-start gap-3 text-left">
              <!-- Info Circle Icon -->
              <div class="w-4 h-4 rounded-full border border-[#148F77] text-[#148F77] flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                i
              </div>
              <div class="flex-1">
                <span class="block text-xs font-semibold text-[#11504B] font-['Poppins',sans-serif] leading-tight">
                  informasi
                </span>
                <p class="text-[11px] sm:text-xs text-[#236B66] font-['Poppins',sans-serif] font-medium leading-relaxed mt-0.5">
                  Pastikan data yang anda masukkan benar dan aktif, karena akan digunakan untuk login ke sistem E-Lapor FIKKIA
                </p>
              </div>
            </div>

            <!-- Row 6: Submit Button (FigCraft: Rectangle 4164 @ #695, bg: #0A7075, radius: 12px) -->
            <button
              type="submit"
              id="submitBtn"
              class="w-full h-11 sm:h-12 rounded-xl bg-[#0A7075] hover:bg-[#085C60] text-white text-sm sm:text-base font-semibold font-['Poppins',sans-serif] tracking-wide flex items-center justify-center gap-2.5 transition duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#0A7075] focus:ring-offset-2 cursor-pointer mt-2"
            >
              <span>Daftar Akun</span>
              <!-- Arrow Right Icon -->
              <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </button>

            <!-- Row 7: Login Link Button (FigCraft: Rectangle 4165 @ #696, bg: #E5F5F3, border: #C1E8E3, radius: 12px) -->
            <a
              href="{{ route('login') }}"
              class="w-full h-11 px-4 rounded-xl bg-[#E5F5F3] hover:bg-[#d6f0ec] border border-[#C1E8E3] text-[#11504B] text-xs sm:text-sm font-semibold font-['Poppins',sans-serif] flex items-center justify-center gap-2 transition duration-200 shadow-sm"
            >
              <!-- Sign In Square Icon -->
              <svg class="w-4 h-4 text-[#11504B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
              </svg>
              <span>Sudah punya akun? Masuk disini</span>
            </a>

          </form>

        </div>
      </section>

    </div>
  </main>

  <!-- ================= JAVASCRIPT INTERACTIONS ================= -->
  <script>
    function toggleVisibility(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);

      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
        `;
      } else {
        input.type = 'password';
        icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
      }
    }

    function handleRegister(event) {
      event.preventDefault();
      const form = document.getElementById('registerForm');
      const btn = document.getElementById('submitBtn');

      btn.disabled = true;
      btn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Mendaftarkan akun...</span>
      `;

      setTimeout(() => {
        form.submit();
      }, 500);
    }
  </script>
</body>
</html>
