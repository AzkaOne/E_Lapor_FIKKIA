<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk ke Akun E-Lapor - FIKKIA Universitas Airlangga</title>

  <!-- Google Fonts: Poppins, Plus Jakarta Sans, Caveat -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
              primary: '#0a8080',
              primaryHover: '#086666',
              navy: '#1A3A4B',
              darkText: '#0F172A',
              slateText: '#334155',
              softMint: '#E5F5F3',
              borderMint: '#C1E8E3',
              cardBorder: '#99F6E4',
              bodyBg: '#F4F7F8',
              inputBg: '#F8FAFC',
              inputBorder: '#CBD5E1',
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
<body class="min-h-screen bg-[#F4F7F8] flex flex-col text-slate-800 antialiased selection:bg-[#0a8080] selection:text-white relative overflow-x-hidden">

  <!-- ================= TOP HEADER BAR ================= -->
  @include('komponen.header_auth')

  <!-- ================= MAIN CONTENT WRAPPER ================= -->
  <main class="flex-1 flex items-center justify-center py-8 px-4 sm:px-6 lg:px-12 relative overflow-hidden">

    <!-- Background Gradient Accent on Left Side (Rectangle 31 in FigCraft) -->
    <div class="absolute inset-y-0 left-0 w-full lg:w-[54%] bg-gradient-to-br from-[#E8F7F5]/80 via-[#F0FBF9]/60 to-transparent pointer-events-none z-0"></div>

    <!-- Botanical Foliage SVG at Bottom-Left Corner (FigCraft id=251) -->
    <div class="absolute -bottom-10 -left-12 w-64 sm:w-80 lg:w-96 pointer-events-none z-0 select-none hidden sm:block opacity-90">
      <img src="{{ asset('assets/foliage_left.svg') }}" alt="" class="w-full h-auto">
    </div>

    <!-- Layout Grid: 12 Columns (7 Left : 5 Right) -->
    <div class="w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center z-10 relative">

      <!-- ================= LEFT COLUMN: HERO & BRANDING ================= -->
      <section class="lg:col-span-7 flex flex-col gap-6 lg:pr-4">

        <!-- Tag Chip (FigCraft: Frame 'Tag Chip' @ x=55, y=138) -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F0FDFA] border border-[#99F6E4] text-[#115E59] font-['Poppins',sans-serif] text-xs font-bold w-fit tracking-wide shadow-sm">
          <span class="w-2 h-2 rounded-full bg-[#0D9488]"></span>
          E-LAPOR FIKKIA
        </div>

        <!-- Big Heading (FigCraft: 'Heading 2 - Big Main Title' font: Poppins 48px Extra Bold) -->
        <div class="space-y-3">
          <h1 class="text-3xl sm:text-4xl lg:text-[46px] xl:text-[48px] font-extrabold text-[#1A3A4B] font-['Poppins',sans-serif] leading-[1.18] tracking-tight">
            Satu Langkah<br class="hidden sm:inline"> untuk Perubahan
          </h1>
          <p class="text-sm sm:text-base text-[#475569] font-['Poppins',sans-serif] leading-relaxed max-w-xl font-normal">
            Sampaikan laporan Anda melalui E-Lapor UNAIR untuk mendukung lingkungan kampus yang lebih baik, transparan, dan responsif.
          </p>
        </div>

        <!-- 4 Feature Cards Grid (FigCraft: Groups 23, 24, 25, 26) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-3.5">

          <!-- Card 1: Respon Cepat -->
          <div class="bg-white p-3.5 sm:p-4 rounded-[14px] border border-[#CCFBF1] shadow-sm flex flex-col items-center text-center gap-2.5 transition hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488]">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight">
              Respon Cepat
            </span>
          </div>

          <!-- Card 2: Mudah Digunakan -->
          <div class="bg-white p-3.5 sm:p-4 rounded-[14px] border border-[#CCFBF1] shadow-sm flex flex-col items-center text-center gap-2.5 transition hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488]">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight">
              Mudah Digunakan
            </span>
          </div>

          <!-- Card 3: Transparan dan Akuntable -->
          <div class="bg-white p-3.5 sm:p-4 rounded-[14px] border border-[#CCFBF1] shadow-sm flex flex-col items-center text-center gap-2.5 transition hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488]">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight">
              Transparan dan Akuntable
            </span>
          </div>

          <!-- Card 4: Untuk Kampus yang Lebih Baik -->
          <div class="bg-white p-3.5 sm:p-4 rounded-[14px] border border-[#CCFBF1] shadow-sm flex flex-col items-center text-center gap-2.5 transition hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#99F6E4] bg-[#F0FDFA] flex items-center justify-center text-[#0D9488]">
              <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif] leading-tight">
              Untuk Kampus yang Lebih Baik
            </span>
          </div>

        </div>

        <!-- Campus Image Banner (FigCraft: Rectangle 36 @ x=80, y=613 with Caveat script text) -->
        <div class="relative w-full rounded-2xl overflow-hidden shadow-lg border border-white/60 group h-52 sm:h-64 lg:h-72">
          <img src="{{ asset('assets/gedung_kampus.png') }}" alt="Gedung Kampus FIKKIA UNAIR" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">

          <!-- Gradient Shadow Overlay -->
          <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent flex items-center p-6 sm:p-8">
            <!-- Handwritten Slogan (Font Caveat Bold) -->
            <div class="font-['Caveat',cursive] font-bold text-white text-3xl sm:text-4xl lg:text-[40px] leading-tight drop-shadow-lg select-none">
              Bersama<br>Wujudkan<br>Layanan yang<br>Lebih Baik
            </div>
          </div>
        </div>

      </section>

      <!-- ================= RIGHT COLUMN: LOGIN CARD ================= -->
      <!-- FigCraft: 'bglogin' (521x788, border: #99F6E4, radius: 28px) -->
      <section class="lg:col-span-5 flex justify-center w-full">
        <div class="w-full max-w-[480px] bg-white rounded-[28px] p-6 sm:p-9 shadow-xl border border-[#99F6E4] relative overflow-hidden">

          <!-- Exact Top-Right Leaf Accent from FigCraft (Container id=273) -->
          <img src="{{ asset('assets/card_leaf_accent.svg') }}" alt="" class="absolute top-0 right-0 w-28 sm:w-36 pointer-events-none select-none z-0">

          <!-- Card Header & University Crest -->
          <div class="relative z-10 text-center mb-6">
            <!-- Exact UNAIR Crest + Brand exported from FigCraft Group 27 -->
            <div class="flex items-center justify-center mb-3">
              <img src="{{ asset('assets/unair_card_brand.png') }}" alt="UNAIR Excellence with Morality" class="h-14 sm:h-16 w-auto object-contain">
            </div>

            <!-- Title & Subtitle (Poppins) -->
            <h2 class="text-2xl sm:text-[25px] font-semibold text-[#0F172A] font-['Poppins',sans-serif] tracking-tight">
              Masuk ke Akun E-Lapor
            </h2>
            <p class="text-xs sm:text-[13px] text-slate-500 font-['Poppins',sans-serif] mt-1.5 font-medium max-w-xs mx-auto leading-normal">
              Gunakan NIM/NIP dan password akun anda untuk melanjutkan
            </p>
          </div>

          <!-- Status / Success Message Alert -->
          @if (session('status'))
            <div class="mb-4 p-3.5 rounded-xl bg-teal-50 border border-teal-200 text-xs text-teal-800 font-medium relative z-10">
              {{ session('status') }}
            </div>
          @endif

          <!-- Error Alert -->
          @if ($errors->any())
            <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium relative z-10">
              <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <!-- Login Form -->
          <form id="loginForm" action="{{ route('login') }}" method="POST" class="space-y-4 relative z-10" onsubmit="handleLogin(event)">
            @csrf

            <!-- Field 1: NIM / NIP -->
            <div class="space-y-1.5">
              <label for="username" class="block text-xs sm:text-[13px] font-semibold text-[#334155] font-['Poppins',sans-serif]">
                NIM / NIP
              </label>
              <div class="relative flex items-center h-[52px] sm:h-[56px] bg-[#F8FAFC] border border-[#CBD5E1] rounded-2xl px-3.5 transition focus-within:border-[#0a8080] focus-within:ring-2 focus-within:ring-[#0a8080]/20">
                <span class="text-slate-400 shrink-0">
                  <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </span>
                <input
                  type="text"
                  id="username"
                  name="login"
                  value="{{ old('login', old('email')) }}"
                  required
                  autofocus
                  placeholder="Masukan NIM atau NIP"
                  class="w-full pl-3 pr-2 text-sm text-[#0F172A] font-medium placeholder:text-slate-400 bg-transparent focus:outline-none font-['Poppins',sans-serif]"
                >
              </div>
            </div>

            <!-- Field 2: Password -->
            <div class="space-y-1.5">
              <label for="password" class="block text-xs sm:text-[13px] font-semibold text-[#334155] font-['Poppins',sans-serif]">
                Password
              </label>
              <div class="relative flex items-center h-[52px] sm:h-[56px] bg-[#F8FAFC] border border-[#CBD5E1] rounded-2xl px-3.5 transition focus-within:border-[#0a8080] focus-within:ring-2 focus-within:ring-[#0a8080]/20">
                <span class="text-slate-400 shrink-0">
                  <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </span>
                <input
                  type="password"
                  id="password"
                  name="password"
                  required
                  placeholder="Masukan Password"
                  class="w-full pl-3 pr-10 text-sm text-[#0F172A] font-medium placeholder:text-slate-400 bg-transparent focus:outline-none font-['Poppins',sans-serif]"
                >
                <button
                  type="button"
                  id="togglePassword"
                  onclick="togglePasswordVisibility()"
                  class="absolute right-3.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                  title="Lihat / sembunyikan password"
                >
                  <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Remember Me & Forgot Password Row -->
            <div class="flex items-center justify-between pt-0.5">
              <label class="flex items-center gap-2 cursor-pointer select-none">
                <input
                  type="checkbox"
                  name="remember"
                  class="w-4 h-4 rounded text-[#0a8080] focus:ring-[#0a8080] border-[#CBD5E1] accent-[#0a8080] cursor-pointer"
                >
                <span class="text-xs sm:text-[13px] text-[#334155] font-medium font-['Poppins',sans-serif]">Ingat saya</span>
              </label>
              <a href="#" class="text-xs sm:text-[13px] font-medium text-[#0F766E] hover:text-[#0a8080] hover:underline font-['Poppins',sans-serif]">
                Lupa Password?
              </a>
            </div>

            <!-- Submit Button (FigCraft: Group 28, bg: #0a8080, radius: 14px) -->
            <button
              type="submit"
              id="submitBtn"
              class="w-full mt-2 h-11 sm:h-12 rounded-[14px] bg-[#0a8080] hover:bg-[#086666] text-white text-sm sm:text-base font-semibold font-['Poppins',sans-serif] tracking-wide flex items-center justify-center gap-2.5 transition duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#0a8080] focus:ring-offset-2 cursor-pointer"
            >
              <span>Masuk</span>
              <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </button>
          </form>

          <!-- Divider 'ATAU' (FigCraft: Lines 8 & 9 + text 'ATAU') -->
          <div class="relative my-4 sm:my-5">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-xs">
              <span class="bg-white px-3 text-slate-400 font-medium font-['Poppins',sans-serif] tracking-wider">ATAU</span>
            </div>
          </div>

          <!-- Secondary Actions -->
          <div class="space-y-3">

            <!-- Register Button (FigCraft: Group 82, bg: #E5F5F3, border: #C1E8E3, radius: 12px) -->
            <a
              href="{{ route('register') }}"
              class="w-full h-11 px-4 rounded-xl bg-[#E5F5F3] hover:bg-[#d6f0ec] border border-[#C1E8E3] text-[#11504B] text-xs sm:text-sm font-semibold font-['Poppins',sans-serif] flex items-center justify-center gap-2 transition duration-200 shadow-sm"
            >
              <svg class="w-4 h-4 text-[#11504B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
              </svg>
              <span>Belum punya akun? Daftar disini</span>
            </a>

            <!-- Help Card (FigCraft: Group 83, bg: #F8FAFC, border: #E2E8F0, radius: 14px) -->
            <div
              class="w-full p-3 sm:p-3.5 rounded-[14px] bg-[#F8FAFC] hover:bg-slate-100/90 border border-[#E2E8F0] flex items-center justify-between gap-3 text-left transition duration-200 group cursor-pointer"
            >
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-teal-50 border border-teal-200/80 text-[#0a8080] flex items-center justify-center font-bold text-sm shrink-0">
                  ?
                </div>
                <div>
                  <div class="text-xs sm:text-[13px] font-semibold text-[#0F172A] font-['Poppins',sans-serif]">Butuh bantuan?</div>
                  <div class="text-[11px] text-slate-400 font-medium font-['Poppins',sans-serif]">Hubungi bagian layanan informasi FIKKIA</div>
                </div>
              </div>
              <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>

          </div>

        </div>
      </section>

    </div>
  </main>

  <!-- ================= JAVASCRIPT INTERACTIONS ================= -->
  <script>
    function togglePasswordVisibility() {
      const passwordInput = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
        `;
      } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
      }
    }

    function handleLogin(event) {
      event.preventDefault();
      const form = document.getElementById('loginForm');
      const btn = document.getElementById('submitBtn');

      btn.disabled = true;
      btn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Memverifikasi...</span>
      `;

      setTimeout(() => {
        form.submit();
      }, 500);
    }
  </script>
</body>
</html>
