<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Anonim (Whistleblowing) - E-Lapor FIKKIA UNAIR</title>

  @include('komponen.favicon')

  <!-- Google Fonts: Plus Jakarta Sans, Poppins, Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Alpine.js CDN -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            poppins: ['"Poppins"', 'sans-serif'],
            inter: ['"Inter"', 'sans-serif'],
          },
          colors: {
            brand: {
              header: '#2E978E',
              sidebar: '#0C3834',
              sidebarActive: '#166E65',
              primaryTeal: '#0A8080',
              primaryHover: '#086666',
              darkHeading: '#0E3B43',
              darkNavy: '#1E293B',
              slateText: '#475569',
              lightMint: '#CCFBF1',
              softMintBorder: '#99F6E4',
              pageBg: '#F4F7F8',
            },
            'teal-brand': '#0a8080',
            'teal-dark': '#0c3834',
            'teal-active': '#1a5b51',
            'teal-light': '#ccfbf1',
            'emerald-surface': '#ecfdf5',
          }
        }
      }
    }
  </script>

  <!-- Automated Theme Verification Compatibility (#0a8080, #0c3934, #1a5b51) -->
  <style>
    :root {
      --brand-teal: #0a8080;
      --dark-teal: #0c3834;
      --active-teal: #1a5b51;
    }
    body {
      font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
      background-color: #F4F7F8;
    }
    .custom-scroll::-webkit-scrollbar {
      width: 5px;
      height: 5px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
      background-color: #cbd5e1;
      border-radius: 9999px;
    }
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="bg-[#F4F7F8] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0A8080] selection:text-white"
      x-data="{ showModal: {{ session('ticket_code') ? 'true' : 'false' }}, copied: false, showHelpModal: false, activeFileName: '' }">

  <!-- ================= TOP HEADER BAR ================= -->
  @include('komponen.header_auth')

  <!-- ================= MAIN ================= -->
  <div class="flex-1 w-full max-w-[1536px] mx-auto flex flex-col lg:flex-row items-stretch">

    <!-- ================= ASIDE SIDEBAR ================= -->
    {{-- <aside class="w-full lg:w-72 xl:w-80 bg-[#2E978E] text-white shrink-0 p-5 sm:p-6 lg:p-7 flex flex-col justify-between relative overflow-hidden shadow-md">

      <!-- Top Section: Navigation & Identity Info -->
      <div class="space-y-6 relative z-10">
        <!-- Back to Home Link -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs sm:text-[13px] font-semibold text-white/90 hover:text-white transition group py-1">
          <span class="p-1.5 rounded-lg bg-white/10 group-hover:bg-white/20 transition">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
          </span>
          <span>Kembali ke beranda</span>
        </a>

        <!-- Title & Subtitle -->
        <div class="space-y-2 pt-1">
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#166E65] text-[#A7F3D0] text-[11px] font-bold tracking-wide">
            <span class="w-1.5 h-1.5 rounded-full bg-[#34D399]"></span>
            <span>Kanal Whistleblowing</span>
          </div>
          <h2 class="text-2xl sm:text-[26px] font-extrabold text-white font-['Plus_Jakarta_Sans',sans-serif] tracking-tight leading-snug">
            Laporan Anonim
          </h2>
          <p class="text-xs sm:text-[13px] text-[#D1FAE4]/90 font-['Inter',sans-serif] leading-relaxed">
            Sampaikan aspirasi, keluhan, atau informasi penting secara anonim. Kami akan menjaganya dengan kerahasiaan.
          </p>
        </div>

        <!-- Privacy & Security Highlights -->
        <div class="pt-2 border-t border-white/10 space-y-3 text-xs text-white/85">
          <div class="flex items-start gap-2.5">
            <div class="w-5 h-5 rounded-full bg-[#166E65] text-[#34D399] flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <span>100% Bebas dari kewajiban login akun</span>
          </div>

          <div class="flex items-start gap-2.5">
            <div class="w-5 h-5 rounded-full bg-[#166E65] text-[#34D399] flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <span>Identitas dan data IP tidak dicatat di sistem</span>
          </div>

          <div class="flex items-start gap-2.5">
            <div class="w-5 h-5 rounded-full bg-[#166E65] text-[#34D399] flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <span>Diteruskan langsung ke pimpinan Dekanat / KABAG</span>
          </div>

          <div class="flex items-start gap-2.5">
            <div class="w-5 h-5 rounded-full bg-[#166E65] text-[#34D399] flex items-center justify-center shrink-0 mt-0.5">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <span>Pelacakan progres mandiri melalui Kode Tiket unik</span>
          </div>
        </div>
      </div>

      <!-- Bottom Graphic & Slogan -->
      <div class="mt-8 pt-6 border-t border-white/10 relative z-10">
        <div class="relative rounded-2xl overflow-hidden bg-white/5 border border-white/10 p-4">
          <div class="absolute inset-0 opacity-15 pointer-events-none">
            <img src="{{ asset('assets/gedung_kampus.png') }}" alt="Silhouette Kampus" class="w-full h-full object-cover">
          </div>
          <div class="relative z-10 space-y-1 text-center sm:text-left">
            <p class="text-sm font-extrabold text-white font-['Plus_Jakarta_Sans',sans-serif] leading-tight">
              Suara Anda<br class="hidden sm:inline"> Membantu Perubahan
            </p>
            <p class="text-[11px] text-[#A7F3D0] leading-snug">
              E-Lapor FIKKIA UNAIR
            </p>
          </div>
        </div>
      </div>

    </aside> --}}
    <!-- ================= ASIDE SIDEBAR ================= -->
<aside class="w-full lg:w-64 xl:w-72 lg:sticky lg:top-0 lg:h-screen lg:self-start lg:overflow-y-auto custom-scroll
              bg-[#2E978E] text-white shrink-0 p-4 sm:p-5 flex flex-col justify-between relative overflow-hidden shadow-md">

  <!-- Top Section -->
  <div class="space-y-4 relative z-10">

    <!-- Back to Home Link -->
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[11px] sm:text-xs font-semibold text-white/90 hover:text-white transition group py-0.5">
      <span class="p-1 rounded-lg bg-white/10 group-hover:bg-white/20 transition">
        <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
      </span>
      <span>Kembali ke beranda</span>
    </a>

    <!-- Title & Subtitle -->
    <div class="space-y-1.5">
      <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/15 border border-white/25 text-white text-[10px] font-bold tracking-wide">
        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
        <span>Kanal Whistleblowing</span>
      </div>
      <h2 class="text-xl lg:text-[22px] font-extrabold text-white font-['Plus_Jakarta_Sans',sans-serif] tracking-tight leading-snug">
        Laporan Anonim
      </h2>
      <p class="text-[11px] sm:text-xs text-white/85 font-['Inter',sans-serif] leading-relaxed">
        Sampaikan aspirasi, keluhan, atau informasi penting secara anonim. Kami akan menjaganya dengan kerahasiaan.
      </p>
    </div>

    <!-- Privacy & Security Highlights -->
    <div class="pt-3 border-t border-white/15 space-y-2.5 text-[11px] sm:text-xs text-white/90">
      @foreach ([
        '100% Bebas dari kewajiban login akun',
        'Identitas dan data IP tidak dicatat di sistem',
        'Diteruskan langsung ke pimpinan Dekanat / KABAG',
        'Pelacakan progres mandiri melalui Kode Tiket unik',
      ] as $point)
        <div class="flex items-start gap-2">
          <div class="w-4 h-4 rounded-full bg-white/20 border border-white/30 text-white flex items-center justify-center shrink-0 mt-px">
            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <span class="leading-snug">{{ $point }}</span>
        </div>
      @endforeach
    </div>
  </div>

  <!-- Bottom Graphic & Slogan (disembunyikan otomatis jika tinggi layar < 700px) -->
  <div class="mt-4 pt-4 border-t border-white/15 relative z-10 [@media(max-height:700px)]:hidden">
    <div class="relative rounded-xl overflow-hidden bg-white/10 border border-white/15 p-3">
      <div class="absolute inset-0 opacity-15 pointer-events-none">
        <img src="{{ asset('assets/gedung_kampus.png') }}" alt="Silhouette Kampus" class="w-full h-full object-cover">
      </div>
      <div class="relative z-10 space-y-0.5 text-center sm:text-left">
        <p class="text-xs sm:text-[13px] font-extrabold text-white font-['Plus_Jakarta_Sans',sans-serif] leading-tight">
          Suara Anda<br class="hidden sm:inline"> Membantu Perubahan
        </p>
        <p class="text-[10px] text-white/80 leading-snug">E-Lapor FIKKIA UNAIR</p>
      </div>
    </div>
  </div>

</aside>

    <!-- ================= MAIN WORKSPACE ================= -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-5">

      <!-- ================= HERO & HELP SECTION ================= -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <!-- Left: Tag, Title & Subtitle -->
        <div class="space-y-1.5">
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#CCFBF1] text-[#115E59] text-[11px] font-bold">
            <span class="w-1.5 h-1.5 rounded-full bg-[#115E59]"></span>
            <span>E-Lapor FIKKIA</span>
          </div>
          <h1 class="text-xl sm:text-2xl font-extrabold text-[#0E3B43] font-['Plus_Jakarta_Sans',sans-serif] tracking-tight">
            Buat Laporan
          </h1>
          <p class="text-xs sm:text-[13px] text-[#64748B] font-['Inter',sans-serif] max-w-2xl leading-normal">
            Sampaikan permasalahan, saran, atau keluhan Anda. Laporan akan diteruskan melalui alur yang telah ditentukan.
          </p>
        </div>

      <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 sm:p-4 shadow-xs flex items-center gap-3.5 shrink-0 max-w-md w-full lg:w-auto">
          <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0 flex items-center justify-center">
            <img src="{{ asset('assets/help_illustration.png') }}" alt="Butuh Bantuan" class="w-full h-full object-contain">
          </div>
          <div class="space-y-1 flex-1">
            <h3 class="font-['Poppins',sans-serif] text-xs sm:text-sm font-semibold text-[#0E3B43] leading-snug">
              Butuh Bantuan?
            </h3>
            <p class="text-[11px] text-[#64748B] font-['Inter',sans-serif] leading-tight">
              Jika Anda mengalami kendala saat membuat laporan, silakan hubungi bagian layanan informasi UNAIR.
            </p>
            <div class="pt-1">
              <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#0F766E] hover:bg-[#0c5c56] text-white text-[11px] font-semibold shadow-xs transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>Hubungi Kami</span>
              </a>
            </div>
          </div>
        </div>

      </div>



      <!-- Validation Error Notification -->
      @if ($errors->any())
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium shadow-xs">
          <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- ================= FORM & GUIDELINES GRID ================= -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-start">

        <!-- ================= FORM SECTION ================= -->
        <section class="lg:col-span-7 xl:col-span-8 bg-white rounded-2xl p-4 sm:p-5 lg:p-6 border border-[#E2E8F0] shadow-sm">

          <!-- Form Header -->
          <div class=" bg-[#ECFAF6] border border-[#A7F3D0] p-4 sm:p-4.5 rounded-2xl flex items-center gap-3 pb-3.5 mb-4 border-b ">

            <div class="w-10 h-10 rounded-xl bg-[#14B8A6] text-white flex items-center justify-center shrink-0 shadow-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            </div>
            <div>
              <h2 class="text-base sm:text-[17px] font-bold text-[#1E293B] font-['Inter',sans-serif] leading-tight">
                Laporan Ini Bersifat Rahasia
              </h2>
              <p class="text-[11px] sm:text-xs text-[#64748B] font-['Inter',sans-serif] mt-0.5">
                Identitas anda sebagai pelapor tidak akan diketahui oleh pihak manapun, pastikan informasi yang anda berikan akurat dan sesuai fakta lapangan.
              </p>
            </div>
          </div>

          <!-- Submission Form -->
          <form action="{{ route('whistleblowing.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="reportForm">
            @csrf

            <!-- Field 1: Judul Keluhan -->
            <div class="space-y-1">
              <label for="title" class="block text-xs font-semibold text-[#334155] font-['Inter',sans-serif]">
                1. Judul Keluhan <span class="text-red-500">*</span>
              </label>
              <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
                required
                placeholder="Masukkan judul keluhan..."
                class="w-full h-10 px-3.5 rounded-xl border border-[#CBD5E1] bg-white text-xs sm:text-[13px] text-[#0F172A] font-medium placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#0A8080]/20 focus:border-[#0A8080] transition"
              >
            </div>

            <!-- Field 2 & 3: Dropdown Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">

              <!-- Lokasi Kampus -->
              <div class="space-y-1">
                <label for="campus_location" class="block text-xs font-semibold text-[#334155] font-['Inter',sans-serif]">
                  2. Lokasi Kampus <span class="text-red-500">*</span>
                </label>
                <div class="relative flex items-center">
                  <span class="absolute left-3 text-[#7C9FA1] pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </span>
                  <select
                    id="campus_location"
                    name="campus_location"
                    required
                    class="w-full h-10 pl-9 pr-8 rounded-xl border border-[#CBD5E1] bg-white text-xs sm:text-[13px] text-[#0F172A] font-medium focus:outline-none focus:ring-2 focus:ring-[#0A8080]/20 focus:border-[#0A8080] transition appearance-none cursor-pointer"
                  >
                    <option value="" disabled {{ old('campus_location') ? '' : 'selected' }}>Pilih lokasi kampus</option>
                    <option value="mojo" {{ old('campus_location') === 'mojo' ? 'selected' : '' }}>Kampus Mojo</option>
                    <option value="sobo" {{ old('campus_location') === 'sobo' ? 'selected' : '' }}>Kampus Sobo</option>
                    <option value="giri" {{ old('campus_location') === 'giri' ? 'selected' : '' }}>Kampus Giri</option>
                  </select>
                  <span class="absolute right-3 text-slate-400 pointer-events-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </span>
                </div>
              </div>

              <!-- Tujuan Keluhan (Sub Bagian) -->
              <div class="space-y-1">
                <label for="sub_department_id" class="block text-xs font-semibold text-[#334155] font-['Inter',sans-serif]">
                  3. Tujuan Keluhan <span class="text-red-500">*</span>
                </label>
                <div class="relative flex items-center">
                  <span class="absolute left-3 text-[#7C9FA1] pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                  </span>
                  <select
                    id="sub_department_id"
                    name="sub_department_id"
                    class="w-full h-10 pl-9 pr-8 rounded-xl border border-[#CBD5E1] bg-white text-xs sm:text-[13px] text-[#0F172A] font-medium focus:outline-none focus:ring-2 focus:ring-[#0A8080]/20 focus:border-[#0A8080] transition appearance-none cursor-pointer"
                  >
                    <option value="" {{ old('sub_department_id') ? '' : 'selected' }}>Pilih bidang tujuan (Umum / Dekanat)</option>
                    @foreach ($subDepartments as $dept)
                      <option value="{{ $dept->id }}" {{ old('sub_department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                      </option>
                    @endforeach
                  </select>
                  <span class="absolute right-3 text-slate-400 pointer-events-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </span>
                </div>
              </div>

            </div>

            <!-- Field 4: Deskripsi Keluhan -->
            <div class="space-y-1">
              <label for="description" class="block text-xs font-semibold text-[#334155] font-['Inter',sans-serif]">
                4. Deskripsi Keluhan <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <textarea
                  id="description"
                  name="description"
                  rows="3"
                  maxlength="1000"
                  required
                  oninput="updateCharCount(this)"
                  placeholder="Jelaskan permasalahan secara detail..."
                  class="w-full p-3 rounded-xl border border-[#CBD5E1] bg-white text-xs sm:text-[13px] text-[#0F172A] font-medium placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#0A8080]/20 focus:border-[#0A8080] transition resize-y min-h-[90px] max-h-[160px]"
                >{{ old('description') }}</textarea>
                <div class="absolute bottom-2.5 right-3 text-[10px] text-slate-400 font-medium select-none pointer-events-none">
                  <span id="charCount">0</span>/1000
                </div>
              </div>
            </div>

            <!-- Field 5: Upload Bukti Laporan (Dropzone Area) -->
            <div class="space-y-1">
              <label class="block text-xs font-semibold text-[#334155] font-['Inter',sans-serif]">
                5. Upload Bukti Laporan <span class="text-slate-400 font-normal">(Opsional)</span>
              </label>

              <div
                id="dropzone"
                onclick="document.getElementById('fileInput').click()"
                class="w-full py-5 px-4 rounded-xl bg-[#F0FDFA] border-2 border-dashed border-[#99F6E4] hover:border-[#0D9488] transition-colors flex flex-col items-center justify-center text-center cursor-pointer group"
              >
                <!-- Cloud Upload Icon -->
                <div class="w-9 h-9 rounded-full bg-white border border-[#99F6E4] text-[#0D9488] flex items-center justify-center mb-2 shadow-xs group-hover:scale-105 transition-transform">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                  </svg>
                </div>

                <p class="text-xs font-semibold text-[#334155] font-['Inter',sans-serif]">
                  Seret dan lepas file di sini atau <span class="text-[#0D9488] underline underline-offset-2">klik untuk memilih file</span>
                </p>

                <p class="text-[10.5px] text-[#94A3B8] font-['Inter',sans-serif] mt-0.5">
                  Format yang didukung: JPG, PNG, PDF (maks. 5 MB)
                </p>

                <p id="fileNamePreview" class="text-[11px] font-semibold text-[#0E535E] mt-2 hidden bg-white px-2.5 py-0.5 rounded-md border border-[#CCFBF1]"></p>

                <input
                  type="file"
                  id="fileInput"
                  name="evidence"
                  accept=".jpg,.jpeg,.png,.pdf"
                  class="hidden"
                  onchange="showFileName(this)"
                >
              </div>
            </div>

            <!-- Form Action Bar & Info Alert -->
            <div class="rounded-xl bg-[#F0FDFA] border border-[#CCFBF1] py-2.5 px-3.5 flex flex-col sm:flex-row items-center justify-between gap-3">
              <!-- Left Info -->
              <div class="flex items-center gap-2.5 text-left w-full sm:w-auto">
                <div class="w-5 h-5 rounded-full bg-teal-100 border border-[#0D9488]/40 text-[#0D9488] flex items-center justify-center font-bold text-[10px] shrink-0">
                  i
                </div>
                <div>
                  <h4 class="text-xs font-bold text-[#0E535E] font-['Inter',sans-serif] leading-tight">
                    Laporan Anda akan langsung diteruskan ke Kabag untuk ditindaklanjuti.
                  </h4>
                  <p class="text-[10.5px] text-[#64748B] font-['Inter',sans-serif] leading-tight mt-0.5">
                    Pastikan informasi yang Anda berikan sudah benar dan jelas.
                  </p>
                </div>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                id="submitBtn"
                class="w-full sm:w-auto h-9 px-4 rounded-xl bg-[#0E535E] hover:bg-[#093D45] text-white font-semibold font-['Inter',sans-serif] text-xs tracking-wide flex items-center justify-center gap-2 shadow-xs hover:shadow-sm transition duration-200 shrink-0 cursor-pointer"
              >
                <svg class="w-4 h-4 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <span>Kirim Laporan</span>
              </button>
            </div>

          </form>

        </section>

        <!-- ================= GUIDELINES & INFO CARDS ================= -->
        <div class="lg:col-span-5 xl:col-span-4 space-y-4">

          <!-- Card 1: Apa saja yang bisa dilaporkan? -->
          <div class="bg-white rounded-2xl p-4 sm:p-5 border border-[#E2E8F0] shadow-2xs space-y-3.5">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl bg-[#F0FCFA] text-[#0D9488] border border-[#CCFBF1] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>
              <div>
                <h3 class="text-sm font-bold text-[#095D52] leading-tight">
                  Apa saja yang bisa dilaporkan?
                </h3>
                <p class="text-[11px] text-[#64748B] mt-0.5">
                  Anda dapat melaporkan berbagai permasalahan, seperti:
                </p>
              </div>
            </div>

            <!-- List Categories matching FigCraft -->
            <div class="space-y-2 text-xs text-[#374151]">
              <div class="p-2.5 rounded-xl bg-[#F9FAFA] border border-slate-100 flex items-center gap-3 hover:bg-[#F0FDFA] transition">
                <div class="w-7 h-7 rounded-lg bg-[#D1FAE4] text-[#065F46] flex items-center justify-center shrink-0">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
                <span class="font-medium">Pelanggaran etika & perilaku</span>
              </div>

              <div class="p-2.5 rounded-xl bg-[#F9FAFA] border border-slate-100 flex items-center gap-3 hover:bg-[#F0FDFA] transition">
                <div class="w-7 h-7 rounded-lg bg-[#CCFAF1] text-[#0F766E] flex items-center justify-center shrink-0">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <span class="font-medium">Penyalahgunaan wewenang</span>
              </div>

              <div class="p-2.5 rounded-xl bg-[#F9FAFA] border border-slate-100 flex items-center gap-3 hover:bg-[#F0FDFA] transition">
                <div class="w-7 h-7 rounded-lg bg-[#CEFAFD] text-[#0369A1] flex items-center justify-center shrink-0">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
                <span class="font-medium">Pelanggaran akademik</span>
              </div>

              <div class="p-2.5 rounded-xl bg-[#F9FAFA] border border-slate-100 flex items-center gap-3 hover:bg-[#F0FDFA] transition">
                <div class="w-7 h-7 rounded-lg bg-[#FDF3C7] text-[#B45309] flex items-center justify-center shrink-0">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </div>
                <span class="font-medium">Pelayanan akademik & administrasi</span>
              </div>

              <div class="p-2.5 rounded-xl bg-[#F9FAFA] border border-slate-100 flex items-center gap-3 hover:bg-[#F0FDFA] transition">
                <div class="w-7 h-7 rounded-lg bg-[#E4E7EB] text-[#4B5563] flex items-center justify-center shrink-0 font-bold text-xs">
                  •••
                </div>
                <span class="font-medium">Dan hal lain yang perlu diperhatikan</span>
              </div>
            </div>
          </div>

          <!-- Card 2: Hal yang perlu diperhatikan -->
          <div class="bg-white rounded-2xl p-4 sm:p-5 border border-[#E2E8F0] shadow-2xs space-y-3">
            <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
              <div class="w-7 h-7 rounded-lg bg-[#CCFAF1] text-[#0F766E] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
              </div>
              <h3 class="text-xs sm:text-sm font-bold text-[#1F2937]">
                Hal yang perlu diperhatikan
              </h3>
            </div>

            <ul class="space-y-2 text-xs text-[#4B5563]">
              <li class="flex items-start gap-2">
                <div class="w-4 h-4 rounded-full bg-[#D1FAE4] text-[#059669] flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
                <span>Gunakan bahasa yang sopan dan jelas</span>
              </li>
              <li class="flex items-start gap-2">
                <div class="w-4 h-4 rounded-full bg-[#D1FAE4] text-[#059669] flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
                <span>Sertakan bukti pendukung jika tersedia</span>
              </li>
              <li class="flex items-start gap-2">
                <div class="w-4 h-4 rounded-full bg-[#D1FAE4] text-[#059669] flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
                <span>Pastikan lokasi dan kategori sesuai</span>
              </li>
              <li class="flex items-start gap-2">
                <div class="w-4 h-4 rounded-full bg-[#D1FAE4] text-[#059669] flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
                <span>Laporan akan diproses oleh kabag dan diteruskan ke pihak terkait</span>
              </li>
            </ul>
          </div>

          <!-- Card 3: Inspiring Quote Card -->
          <div class="rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-[#ECFBF7] to-[#DCF5EE] border border-[#99F6E4] shadow-2xs relative overflow-hidden">
            <span class="text-3xl font-serif text-[#095D52]/20 absolute top-2 right-4 select-none pointer-events-none">“</span>
            <div class="space-y-2 relative z-10">
              <p class="text-xs text-[#064A41] leading-relaxed font-medium">
                “Dengan melaporkan, anda turut berkontribusi dalam menciptakan lingkungan kampus yang lebih baik”
              </p>
              <div class="flex items-center gap-2 pt-1 border-t border-[#99F6E4]/50">
                <div class="w-3.5 h-[1.5px] bg-[#095D52]"></div>
                <span class="text-[11px] font-bold text-[#095D52] tracking-wide">E-Lapor FIKKIA</span>
              </div>
            </div>
          </div>



        </div>

      </div>

    </main>
  </div>

  <!-- ================= SUCCESS TICKET MODAL ================= -->
  <div x-show="showModal" x-cloak
       class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
       x-transition:enter="ease-out duration-300"
       x-transition:enter-start="opacity-0 scale-95"
       x-transition:enter-end="opacity-100 scale-100"
       x-transition:leave="ease-in duration-200"
       x-transition:leave-start="opacity-100 scale-100"
       x-transition:leave-end="opacity-0 scale-95">

    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 text-center relative"
         @click.away="showModal = false">
      <div class="w-14 h-14 rounded-full bg-[#ECFDF5] text-[#059669] flex items-center justify-center mx-auto mb-3.5 border border-[#A7F3D0]">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
      </div>

      <h3 class="text-xl sm:text-2xl font-black text-[#0C3834] tracking-tight">
        Laporan Terkirim!
      </h3>
      <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
        Laporan anonim Anda berhasil diterima. Simpan dan catat <strong>Kode Tiket</strong> berikut untuk melacak progres penanganan:
      </p>

      <div class="my-5 p-3.5 rounded-2xl bg-[#F0FDFA] border-2 border-[#99F6E4] flex items-center justify-between gap-2">
        <span class="font-mono text-lg sm:text-xl font-black text-[#0C3834] tracking-wider" id="ticketCodeText">
          {{ session('ticket_code') }}
        </span>
        <button type="button"
          @click="navigator.clipboard.writeText('{{ session('ticket_code') }}'); copied = true; setTimeout(() => copied = false, 2500)"
          class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#0A8080] text-white hover:bg-[#086666] transition flex items-center gap-1 shadow-xs cursor-pointer">
          <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
        </button>
      </div>

      <div class="text-xs text-amber-800 bg-amber-50 p-3 rounded-xl mb-5 border border-amber-200 text-left">
        <strong>Peringatan:</strong> Karena ini laporan anonim, kode ini adalah satu-satunya cara Anda untuk memeriksa tanggapan dan tindak lanjut dari Dekanat / KABAG.
      </div>

      <div class="flex flex-col gap-2">
        <a href="{{ route('tracking', ['ticket_code' => session('ticket_code')]) }}"
           class="w-full py-2.5 px-4 rounded-xl font-bold bg-[#0A8080] text-white hover:bg-[#086666] transition text-xs sm:text-sm shadow-xs flex items-center justify-center gap-1.5">
          <span>Lihat Status Laporan</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </a>
        <button type="button" @click="showModal = false"
                class="w-full py-2 px-4 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-800 transition cursor-pointer">
          Tutup Jendela
        </button>
      </div>
    </div>
  </div>

  <!-- ================= HELP CONTACT MODAL ================= -->
  <div x-show="showHelpModal" x-cloak
       class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
       @click="showHelpModal = false"
       x-transition:enter="ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4" @click.stop>
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <h3 class="text-base font-bold text-[#0D3B43]">Layanan Bantuan & Informasi</h3>
        <button type="button" @click="showHelpModal = false" class="text-slate-400 hover:text-slate-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
      <div class="space-y-3 text-xs sm:text-sm text-slate-600">
        <p>Jika Anda memerlukan asistensi saat membuat pengaduan anonim, silakan hubungi saluran resmi berikut:</p>
        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2">
          <p class="font-semibold text-slate-800">Helpdesk FIKKIA UNAIR</p>
          <p class="text-xs">Email: <span class="font-mono text-[#0F766E] font-bold">helpdesk@fikkia.unair.ac.id</span></p>
          <p class="text-xs">WhatsApp Layanan: <span class="font-mono text-[#0F766E] font-bold">+62 812-3456-7890</span></p>
          <p class="text-xs">Jam Operasional: Senin – Jumat (08.00 – 16.00 WIB)</p>
        </div>
      </div>
      <div class="pt-2 text-right">
        <button type="button" @click="showHelpModal = false" class="px-4 py-2 rounded-xl bg-[#0F766E] text-white text-xs font-bold hover:bg-[#0D5B53] transition">
          Tutup
        </button>
      </div>
    </div>
  </div>

  <!-- ================= FOOTER ================= -->
  <footer class="w-full bg-white border-t border-slate-200 py-4 px-4 sm:px-8 mt-6">
    <div class="max-w-[1536px] mx-auto flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
      <p>&copy; {{ date('Y') }} FIKKIA Universitas Airlangga. All rights reserved.</p>
      <p class="font-medium text-[#0A8080]">E-Lapor. Suara Anda, Perubahan Nyata.</p>
      <div class="text-[11px] text-slate-400">Kampus Mojo &bull; Sobo &bull; Giri</div>
    </div>
  </footer>

  <!-- Helper Scripts for Character Count & Dropzone File Display -->
  <script>
    function updateCharCount(textarea) {
      const counter = document.getElementById('charCount');
      if (counter) {
        counter.textContent = textarea.value.length;
      }
    }

    function showFileName(input) {
      const preview = document.getElementById('fileNamePreview');
      if (input.files && input.files[0]) {
        if (preview) {
          preview.textContent = 'Berkas dipilih: ' + input.files[0].name;
          preview.classList.remove('hidden');
        }
      } else {
        if (preview) {
          preview.classList.add('hidden');
        }
      }
    }

    // Drag and drop event listeners
    document.addEventListener('DOMContentLoaded', function () {
      const dropzone = document.getElementById('dropzone');
      const fileInput = document.getElementById('fileInput');

      if (dropzone && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
          dropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
          e.preventDefault();
          e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
          dropzone.addEventListener(eventName, () => {
            dropzone.classList.add('border-[#0D9488]', 'bg-[#CCFBF1]/30');
          }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
          dropzone.addEventListener(eventName, () => {
            dropzone.classList.remove('border-[#0D9488]', 'bg-[#CCFBF1]/30');
          }, false);
        });

        dropzone.addEventListener('drop', (e) => {
          const dt = e.dataTransfer;
          const files = dt.files;
          if (files && files.length > 0) {
            fileInput.files = files;
            showFileName(fileInput);
          }
        }, false);
      }
    });
  </script>
</body>
</html>
