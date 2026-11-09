<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pelapor - E-LAPOR FIKKIA</title>

  <!-- Google Fonts: Plus Jakarta Sans, Poppins, Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

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
          },
          colors: {
            brand: {
              header: '#0C3934',
              sidebar: '#2E978E',
              sidebarActive: '#1B736B',
              primaryTeal: '#0a8080',
              primaryHover: '#086666',
              darkHeading: '#0E3B43',
              darkNavy: '#1E293B',
              slateText: '#475569',
              lightMint: '#CCFBF1',
              softMintBorder: '#99F6E4',
              pageBg: '#F4F7F8',
            }
          }
        }
      }
    }
  </script>
  <style>
    body {
      font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
      background-color: #F4F7F8;
    }
    .custom-scroll::-webkit-scrollbar {
      width: 5px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
      background-color: #cbd5e1;
      border-radius: 9999px;
    }
  </style>
</head>
<body class="min-h-screen bg-[#F4F7F8] flex flex-col text-slate-800 antialiased selection:bg-[#0a8080] selection:text-white relative overflow-x-hidden">

  <!-- ================= TOP HEADER BAR ================= -->
 @include('komponen.header_Pelapor')

  <!-- ================= LAYOUT CONTAINER ================= -->
  <div class="flex-1 flex w-full relative pt-14">

    <!-- ================= SIDEBAR NAVIGATION ================= -->
    @include('komponen.sidebar_Pelapor')

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 lg:ml-52 py-4 sm:py-5 px-4 sm:px-6 lg:px-8 max-w-[1220px] w-full mx-auto min-w-0">

      <!-- Top Title & Badge Banner (Compact) -->
      <div class="mb-4 space-y-1">
        <div class="flex items-center gap-2.5">
          <span class="text-xs font-semibold text-[#115E59] font-['Inter',sans-serif]">
            Dashboard
          </span>
        </div>
        <div class="flex items-center gap-2.5">
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#CCFBF1] border border-[#99F6E4] text-[#115E59] font-['Plus_Jakarta_Sans',sans-serif] text-[11px] font-bold shadow-xs">
            <span class="w-1.5 h-1.5 rounded-full bg-[#115E59]"></span>
            E-Lapor FIKKIA
          </div>
        </div>

        <h1 class="text-xl sm:text-2xl font-extrabold text-[#0E3B43] font-['Plus_Jakarta_Sans',sans-serif] tracking-tight">
          Buat Laporan
        </h1>

        <p class="text-xs text-[#64748B] font-['Inter',sans-serif] max-w-2xl leading-normal">
          Sampaikan permasalahan, saran, atau keluhan Anda. Laporan akan diteruskan melalui alur yang telah ditentukan.
        </p>
      </div>

      <!-- Success & Error Notifications -->
      @if (session('success'))
        <div class="mb-4 p-3 rounded-xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#064E3B] text-xs font-medium flex items-center gap-2.5 shadow-xs">
          <svg class="w-4 h-4 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if ($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium shadow-xs">
          <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Two-Column Grid: Form (7-8 Cols) : Info & Stats (4-5 Cols) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-start">

        <!-- ================= FORM PENGADUAN ================= -->
        <section class="lg:col-span-7 xl:col-span-8 bg-white rounded-2xl p-4 sm:p-5 border border-[#E2E8F0] shadow-sm">

          <!-- Form Header -->
          <div class="flex items-center gap-3 pb-3.5 mb-4 border-b border-slate-100">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#F0FDFA] border border-[#0D9488]/30 text-[#0D9488] flex items-center justify-center shrink-0 shadow-xs">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div>
              <h2 class="text-base sm:text-[17px] font-bold text-[#1E293B] font-['Inter',sans-serif] leading-tight">
                Form Laporan
              </h2>
              <p class="text-[11px] sm:text-xs text-[#64748B] font-['Inter',sans-serif] mt-0.5">
                Isi data berikut dengan lengkap dan benar
              </p>
            </div>
          </div>

          <!-- Submission Form -->
          <form action="{{ route('pelapor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 sm:space-y-4" id="reportForm">
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
                class="w-full h-10 px-3.5 rounded-lg sm:rounded-xl border border-[#CBD5E1] bg-white text-xs sm:text-[13px] text-[#0F172A] font-medium placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#0a8080]/20 focus:border-[#0a8080] transition"
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
                    class="w-full h-10 pl-9 pr-8 rounded-lg sm:rounded-xl border border-[#CBD5E1] bg-white text-xs sm:text-[13px] text-[#0F172A] font-medium focus:outline-none focus:ring-2 focus:ring-[#0a8080]/20 focus:border-[#0a8080] transition appearance-none cursor-pointer"
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
                    required
                    class="w-full h-10 pl-9 pr-8 rounded-lg sm:rounded-xl border border-[#CBD5E1] bg-white text-xs sm:text-[13px] text-[#0F172A] font-medium focus:outline-none focus:ring-2 focus:ring-[#0a8080]/20 focus:border-[#0a8080] transition appearance-none cursor-pointer"
                  >
                    <option value="" disabled {{ old('sub_department_id') ? '' : 'selected' }}>Pilih bidang tujuan</option>
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
                  class="w-full p-3 rounded-lg sm:rounded-xl border border-[#CBD5E1] bg-white text-xs sm:text-[13px] text-[#0F172A] font-medium placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#0a8080]/20 focus:border-[#0a8080] transition resize-y min-h-[85px] max-h-[140px]"
                >{{ old('description') }}</textarea>
                <div class="absolute bottom-2.5 right-3 text-[10px] text-slate-400 font-medium select-none pointer-events-none">
                  <span id="charCount">0</span>/1000
                </div>
              </div>
            </div>

            <!-- Field 5: Upload Bukti Laporan (Dropzone Area Compact) -->
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

                <p class="text-[10px] text-[#94A3B8] font-['Inter',sans-serif] mt-0.5">
                  Format yang didukung: JPG, PNG, PDF, DOC, DOCX (maks. 10 MB)
                </p>

                <p id="fileNamePreview" class="text-[11px] font-semibold text-[#0E535E] mt-2 hidden bg-white px-2.5 py-0.5 rounded-md border border-[#CCFBF1]"></p>

                <input
                  type="file"
                  id="fileInput"
                  name="evidence"
                  accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                  class="hidden"
                  onchange="showFileName(this)"
                >
              </div>
            </div>

            <!-- Form Action Bar & Info Alert (Compact) -->
            <div class="rounded-lg sm:rounded-xl bg-[#F0FDFA] border border-[#CCFBF1] py-2 px-3 sm:px-3.5 flex flex-col sm:flex-row items-center justify-between gap-2.5">
              <!-- Left Info -->
              <div class="flex items-center gap-2 text-left w-full sm:w-auto">
                <div class="w-4 h-4 rounded-full bg-teal-100 border border-[#0D9488]/40 text-[#0D9488] flex items-center justify-center font-bold text-[9px] shrink-0">
                  i
                </div>
                <div>
                  <h4 class="text-[11px] sm:text-xs font-bold text-[#0E535E] font-['Inter',sans-serif] leading-tight">
                    Laporan Anda akan langsung diteruskan ke Kabag untuk ditindaklanjuti.
                  </h4>
                  <p class="text-[10px] text-[#64748B] font-['Inter',sans-serif] leading-tight mt-0.5">
                    Pastikan informasi yang Anda berikan sudah benar dan jelas.
                  </p>
                </div>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                id="submitBtn"
                class="w-full sm:w-auto h-8 px-3.5 rounded-lg bg-[#0E535E] hover:bg-[#093D45] text-white font-semibold font-['Inter',sans-serif] text-[11.5px] sm:text-xs tracking-wide flex items-center justify-center gap-1.5 shadow-xs hover:shadow-sm transition duration-200 shrink-0 cursor-pointer"
              >
                <svg class="w-3.5 h-3.5 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <span>Kirim Laporan</span>
              </button>
            </div>

          </form>

        </section>

        <!-- ================= RIGHT COLUMN: INFO & STATISTIK ================= -->
        <div class="lg:col-span-5 xl:col-span-4 space-y-4">

          <!-- Card 1: Butuh Bantuan? (Compact) -->
          <div class="bg-white rounded-2xl p-4 border border-[#E2E8F0] shadow-sm flex items-center gap-3.5">
            <!-- Left Help Graphic -->
            <div class="w-14 h-14 shrink-0">
              <img src="{{ asset('assets/help_illustration.png') }}" alt="Butuh Bantuan" class="w-full h-auto object-contain">
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

          <!-- Card 2: Informasi Penting (Compact) -->
          <div class="bg-white rounded-2xl p-4 border border-[#E2E8F0] shadow-sm space-y-3">
            <!-- Header -->
            <div class="flex items-center gap-2">
              <div class="w-5 h-5 rounded-full bg-[#ECFDF5] text-[#059669] flex items-center justify-center font-bold text-[11px] shrink-0">
                i
              </div>
              <h3 class="text-sm font-bold text-[#0E3B43] font-['Poppins',sans-serif]">
                Informasi Penting
              </h3>
            </div>

            <!-- 4 Info Bullet Points with Icons -->
            <div class="space-y-2.5 text-[11px] text-[#064E3B] font-['Poppins',sans-serif]">

              <!-- Item 1 -->
              <div class="flex items-start gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-[#F0FDFA] border border-[#99F6E4] text-[#0D9488] flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                  </svg>
                </div>
                <p class="leading-snug">
                  Laporan yang masuk akan diterima oleh Kabag sesuai dengan bidang yang Anda pilih.
                </p>
              </div>

              <!-- Item 2 -->
              <div class="flex items-start gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-[#F0FDFA] border border-[#99F6E4] text-[#0D9488] flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <p class="leading-snug">
                  Pastikan bidang, judul, dan deskripsi keluhan sudah jelas dan sesuai permasalahan.
                </p>
              </div>

              <!-- Item 3 -->
              <div class="flex items-start gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-[#F0FDFA] border border-[#99F6E4] text-[#0D9488] flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <p class="leading-snug">
                  Pantau perkembangan laporan melalui menu Riwayat dan Status Laporan.
                </p>
              </div>

              <!-- Item 4 -->
              <div class="flex items-start gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-[#F0FDFA] border border-[#99F6E4] text-[#0D9488] flex items-center justify-center shrink-0 mt-0.5">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
                <p class="leading-snug">
                  Laporan anonim khusus untuk hal sensitif/rahasia, bukan aduan fasilitas umum.
                </p>
              </div>

            </div>
          </div>

          <!-- Card 3: Statistik Laporan (Compact Chart) -->
          <div class="bg-white rounded-2xl p-4 border border-[#E2E8F0] shadow-sm space-y-3">
            <!-- Header -->
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-1.5">
                <div class="w-5 h-5 rounded-full border-2 border-[#0D9488] flex items-center justify-center">
                  <div class="w-2 h-2 rounded-full bg-[#0D9488]"></div>
                </div>
                <div>
                  <h3 class="text-xs sm:text-[13px] font-bold text-[#0E3B43] font-['Poppins',sans-serif] leading-tight">
                    Statistik laporan
                  </h3>
                  <p class="text-[9.5px] text-[#64748B] font-['Inter',sans-serif]">
                    Jumlah laporan berdasarkan kategori
                  </p>
                </div>
              </div>

              <!-- Badge 30 Hari Terakhir -->
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md border border-[#14B8A6] text-[#0F766E] text-[9.5px] font-semibold font-['Poppins',sans-serif] bg-white">
                <span>30 Hari</span>
                <svg class="w-2.5 h-2.5 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </span>
            </div>

            <!-- Bar Chart Visualization -->
            <div class="pt-1">
              <div class="relative h-28 flex items-end justify-between px-3 pb-1 border-b border-[#E2E8F0]">
                <!-- Grid Lines -->
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-30">
                  <div class="border-b border-[#E2E8F0] w-full"></div>
                  <div class="border-b border-[#E2E8F0] w-full"></div>
                  <div class="border-b border-[#E2E8F0] w-full"></div>
                  <div class="border-b border-[#E2E8F0] w-full"></div>
                </div>

                <!-- Bar 1: Sarana Prasarana -->
                <div class="relative z-10 flex flex-col items-center gap-1 w-10 group cursor-pointer" title="Sarana Prasarana">
                  <div class="w-6 sm:w-7 bg-[#0D9488] group-hover:bg-[#0a8080] rounded-t-sm transition-all duration-300" style="height: 75px;"></div>
                </div>

                <!-- Bar 2: Keuangan & SDM -->
                <div class="relative z-10 flex flex-col items-center gap-1 w-10 group cursor-pointer" title="Keuangan & SDM">
                  <div class="w-6 sm:w-7 bg-[#0D9488] group-hover:bg-[#0a8080] rounded-t-sm transition-all duration-300" style="height: 25px;"></div>
                </div>

                <!-- Bar 3: Pendidikan & Kemahasiswaan -->
                <div class="relative z-10 flex flex-col items-center gap-1 w-10 group cursor-pointer" title="Pendidikan & Kemahasiswaan">
                  <div class="w-6 sm:w-7 bg-[#0D9488] group-hover:bg-[#0a8080] rounded-t-sm transition-all duration-300" style="height: 50px;"></div>
                </div>

                <!-- Bar 4: Kepala Bagian -->
                <div class="relative z-10 flex flex-col items-center gap-1 w-10 group cursor-pointer" title="Kepala Bagian">
                  <div class="w-6 sm:w-7 bg-[#0D9488] group-hover:bg-[#0a8080] rounded-t-sm transition-all duration-300" style="height: 60px;"></div>
                </div>
              </div>

              <!-- Bar Labels X-Axis -->
              <div class="grid grid-cols-4 gap-1 text-center pt-1.5 text-[8.5px] sm:text-[9.5px] font-semibold text-[#0E3B43] font-['Poppins',sans-serif] leading-tight">
                <div>Sarana<br>Prasarana</div>
                <div>Keuangan<br>& SDM</div>
                <div>Pendidikan<br>& Ormawa</div>
                <div>Kepala<br>Bagian</div>
              </div>
            </div>

          </div>

        </div>

      </div>

    </main>

  </div>

  <!-- ================= POPUP STATUS MODAL (Tengah Layar) ================= -->
  @php
    $hasSuccessModal = session('success');
    $hasErrorModal = session('error') || $errors->any();
    $showModalOnLoad = $hasSuccessModal || $hasErrorModal;
  @endphp

  <div id="reportStatusModal"
       class="fixed inset-0 z-50 {{ $showModalOnLoad ? 'flex' : 'hidden' }} items-center justify-center p-4 sm:p-6"
       role="dialog"
       aria-modal="true"
       aria-labelledby="modalStatusTitle">

    <!-- Modal Backdrop Overlay -->
    <div id="modalBackdrop"
         onclick="closeStatusModal()"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 {{ $showModalOnLoad ? 'opacity-100' : 'opacity-0' }}"></div>

    <!-- Modal Dialog Card (Centered) -->
    <div id="modalCard"
         class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden z-10 transform transition-all duration-300 ease-out {{ $showModalOnLoad ? 'scale-100 opacity-100' : 'scale-95 opacity-0' }}">

      <!-- Close Button Top Right -->
      <button type="button"
              onclick="closeStatusModal()"
              class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition shadow-xs cursor-pointer"
              title="Tutup Modal"
              aria-label="Tutup Modal">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <!-- Static Blade Content or Dynamic JS Target -->
      <div id="modalContentContainer">
        @if ($hasSuccessModal)
          <!-- ================= KONTEN SUKSES ================= -->
          <div class="p-6 sm:p-7 text-center">
            
            <!-- Animated Top Glow / Illustration Ring -->
            <div class="relative w-24 h-24 mx-auto mb-4 flex items-center justify-center">
              <!-- Pulsing outer ring -->
              <div class="absolute inset-0 rounded-full bg-[#CCFBF1] animate-ping opacity-30"></div>
              <!-- Glow background -->
              <div class="absolute inset-1 rounded-full bg-gradient-to-tr from-[#99F6E4] to-[#CCFBF1] opacity-70"></div>
              <!-- Icon Badge -->
              <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-[#0a8080] to-[#0C3934] text-white flex items-center justify-center shadow-lg shadow-[#0a8080]/30 border-2 border-white">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            </div>

            <!-- Title & Subtitle -->
            <h3 id="modalStatusTitle" class="text-xl sm:text-2xl font-black text-[#0C3934] font-['Plus_Jakarta_Sans',sans-serif] tracking-tight">
              Laporan Berhasil Terkirim!
            </h3>
            <p class="text-xs sm:text-[13px] text-slate-600 font-['Inter',sans-serif] mt-2 leading-relaxed">
              {{ session('success') }}
            </p>

            <!-- Detail Card Info -->
            <div class="bg-gradient-to-b from-[#F0FDFA] to-[#F8FAFC] rounded-2xl p-4 border border-[#CCFBF1] my-5 text-left space-y-2.5 text-xs">
              <!-- Tiket -->
              <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Nomor Tiket</span>
                <span class="font-bold text-[#0D9488] font-mono bg-white px-2.5 py-1 rounded-lg border border-[#99F6E4] shadow-xs">
                  #{{ session('ticket_code') ?? 'LP-TERCATAT' }}
                </span>
              </div>

              @if (session('report_title'))
                <!-- Judul Laporan -->
                <div class="flex items-start justify-between gap-3 pb-2 border-b border-slate-200/60">
                  <span class="text-slate-500 font-medium shrink-0">Judul Laporan</span>
                  <span class="font-bold text-slate-800 text-right line-clamp-1">
                    {{ session('report_title') }}
                  </span>
                </div>
              @endif

              <!-- Status Alur -->
              <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Status Awal</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]">
                  <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                  <span>Diajukan (Menunggu Review KABAG)</span>
                </span>
              </div>

              <!-- Waktu Pengiriman -->
              <div class="flex items-center justify-between">
                <span class="text-slate-500 font-medium">Waktu Kirim</span>
                <span class="font-medium text-slate-700">
                  {{ now()->translatedFormat('d M Y, H:i') }} WIB
                </span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2.5 pt-1">
              <a href="{{ route('pelapor.status') }}"
                 class="w-full py-3 px-4 rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Pantau Status Laporan</span>
              </a>

              <button type="button"
                      onclick="closeStatusModal()"
                      class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs sm:text-sm transition duration-200 cursor-pointer">
                Selesai & Tutup
              </button>
            </div>

          </div>

        @elseif ($hasErrorModal)
          <!-- ================= KONTEN GAGAL / ERROR ================= -->
          <div class="p-6 sm:p-7 text-center">
            
            <!-- Animated Top Warning Ring -->
            <div class="relative w-24 h-24 mx-auto mb-4 flex items-center justify-center">
              <!-- Pulsing outer ring -->
              <div class="absolute inset-0 rounded-full bg-rose-100 animate-ping opacity-30"></div>
              <!-- Glow background -->
              <div class="absolute inset-1 rounded-full bg-gradient-to-tr from-rose-200 to-rose-100 opacity-70"></div>
              <!-- Icon Badge -->
              <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-700 text-white flex items-center justify-center shadow-lg shadow-rose-500/30 border-2 border-white">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
            </div>

            <!-- Title & Subtitle -->
            <h3 id="modalStatusTitle" class="text-xl sm:text-2xl font-black text-rose-950 font-['Plus_Jakarta_Sans',sans-serif] tracking-tight">
              Pengiriman Laporan Gagal
            </h3>
            <p class="text-xs sm:text-[13px] text-slate-600 font-['Inter',sans-serif] mt-2 leading-relaxed">
              Mohon maaf, laporan Anda belum dapat diproses. Silakan periksa kendala di bawah ini:
            </p>

            <!-- Error Details Box -->
            <div class="bg-rose-50/70 rounded-2xl p-4 border border-rose-200/80 my-5 text-left space-y-2 text-xs text-rose-800 max-h-48 overflow-y-auto custom-scroll">
              @if (session('error'))
                <div class="flex items-start gap-2">
                  <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="font-medium">{{ session('error') }}</span>
                </div>
              @endif

              @if ($errors->any())
                @foreach ($errors->all() as $err)
                  <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $err }}</span>
                  </div>
                @endforeach
              @endif
            </div>

            <!-- Action Button -->
            <div class="pt-1">
              <button type="button"
                      onclick="closeStatusModal()"
                      class="w-full py-3 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition duration-200 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Periksa dan Lengkapi Data</span>
              </button>
            </div>

          </div>
        @else
          <!-- Dynamic JS-rendered placeholder -->
          <div id="dynamicPlaceholder"></div>
        @endif
      </div>

    </div>

  </div>

  <!-- ================= SCRIPTS ================= -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebarMenu');
      const backdrop = document.getElementById('sidebarBackdrop');

      if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
      } else {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      }
    }

    function updateCharCount(textarea) {
      document.getElementById('charCount').textContent = textarea.value.length;
    }

    function showFileName(input) {
      const preview = document.getElementById('fileNamePreview');
      if (input.files && input.files[0]) {
        preview.textContent = 'File dipilih: ' + input.files[0].name;
        preview.classList.remove('hidden');
      } else {
        preview.classList.add('hidden');
      }
    }

    /* Modal Popup Controller */
    function openStatusModal() {
      const modal = document.getElementById('reportStatusModal');
      const backdrop = document.getElementById('modalBackdrop');
      const card = document.getElementById('modalCard');

      if (!modal) return;
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      setTimeout(() => {
        if (backdrop) {
          backdrop.classList.remove('opacity-0');
          backdrop.classList.add('opacity-100');
        }
        if (card) {
          card.classList.remove('scale-95', 'opacity-0');
          card.classList.add('scale-100', 'opacity-100');
        }
      }, 10);
    }

    function closeStatusModal() {
      const modal = document.getElementById('reportStatusModal');
      const backdrop = document.getElementById('modalBackdrop');
      const card = document.getElementById('modalCard');

      if (!modal) return;
      if (backdrop) {
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
      }
      if (card) {
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
      }

      setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
      }, 300);
    }

    // Expose helpers globally
    window.closeStatusModal = closeStatusModal;
    window.openStatusModal = openStatusModal;

    // Helper to dynamically show success modal from client-side if needed
    window.showSuccessPopup = function(data = {}) {
      const container = document.getElementById('modalContentContainer');
      const ticket = data.ticketCode || 'LP-TERCATAT';
      const title = data.title || 'Laporan Pengaduan';
      const message = data.message || 'Laporan Anda telah berhasil dicatat dan diteruskan ke KABAG.';
      const statusUrl = "{{ route('pelapor.status') }}";

      if (container) {
        container.innerHTML = `
          <div class="p-6 sm:p-7 text-center">
            <div class="relative w-24 h-24 mx-auto mb-4 flex items-center justify-center">
              <div class="absolute inset-0 rounded-full bg-[#CCFBF1] animate-ping opacity-30"></div>
              <div class="absolute inset-1 rounded-full bg-gradient-to-tr from-[#99F6E4] to-[#CCFBF1] opacity-70"></div>
              <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-[#0a8080] to-[#0C3934] text-white flex items-center justify-center shadow-lg shadow-[#0a8080]/30 border-2 border-white">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            </div>
            <h3 id="modalStatusTitle" class="text-xl sm:text-2xl font-black text-[#0C3934] font-['Plus_Jakarta_Sans',sans-serif] tracking-tight">
              Laporan Berhasil Terkirim!
            </h3>
            <p class="text-xs sm:text-[13px] text-slate-600 font-['Inter',sans-serif] mt-2 leading-relaxed">
              ${message}
            </p>
            <div class="bg-gradient-to-b from-[#F0FDFA] to-[#F8FAFC] rounded-2xl p-4 border border-[#CCFBF1] my-5 text-left space-y-2.5 text-xs">
              <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Nomor Tiket</span>
                <span class="font-bold text-[#0D9488] font-mono bg-white px-2.5 py-1 rounded-lg border border-[#99F6E4] shadow-xs">
                  #${ticket}
                </span>
              </div>
              <div class="flex items-start justify-between gap-3 pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium shrink-0">Judul Laporan</span>
                <span class="font-bold text-slate-800 text-right line-clamp-1">${title}</span>
              </div>
              <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                <span class="text-slate-500 font-medium">Status Awal</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]">
                  <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                  <span>Diajukan (Menunggu Review KABAG)</span>
                </span>
              </div>
            </div>
            <div class="space-y-2.5 pt-1">
              <a href="${statusUrl}" class="w-full py-3 px-4 rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Pantau Status Laporan</span>
              </a>
              <button type="button" onclick="closeStatusModal()" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs sm:text-sm transition duration-200">
                Selesai & Tutup
              </button>
            </div>
          </div>
        `;
      }
      openStatusModal();
    };

    // Helper to dynamically show error modal from client-side if needed
    window.showErrorPopup = function(title = 'Pengiriman Laporan Gagal', errors = []) {
      const container = document.getElementById('modalContentContainer');
      const errList = Array.isArray(errors) ? errors : [errors];

      if (container) {
        container.innerHTML = `
          <div class="p-6 sm:p-7 text-center">
            <div class="relative w-24 h-24 mx-auto mb-4 flex items-center justify-center">
              <div class="absolute inset-0 rounded-full bg-rose-100 animate-ping opacity-30"></div>
              <div class="absolute inset-1 rounded-full bg-gradient-to-tr from-rose-200 to-rose-100 opacity-70"></div>
              <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-700 text-white flex items-center justify-center shadow-lg shadow-rose-500/30 border-2 border-white">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
            </div>
            <h3 id="modalStatusTitle" class="text-xl sm:text-2xl font-black text-rose-950 font-['Plus_Jakarta_Sans',sans-serif] tracking-tight">
              ${title}
            </h3>
            <p class="text-xs sm:text-[13px] text-slate-600 font-['Inter',sans-serif] mt-2 leading-relaxed">
              Mohon maaf, laporan Anda belum dapat diproses. Silakan periksa kendala berikut:
            </p>
            <div class="bg-rose-50/70 rounded-2xl p-4 border border-rose-200/80 my-5 text-left space-y-2 text-xs text-rose-800">
              ${errList.map(err => `
                <div class="flex items-start gap-2">
                  <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>${err}</span>
                </div>
              `).join('')}
            </div>
            <div class="pt-1">
              <button type="button" onclick="closeStatusModal()" class="w-full py-3 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition duration-200">
                <span>Periksa dan Lengkapi Data</span>
              </button>
            </div>
          </div>
        `;
      }
      openStatusModal();
    };

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeStatusModal();
      }
    });

    document.addEventListener('DOMContentLoaded', () => {
      const desc = document.getElementById('description');
      if (desc) {
        updateCharCount(desc);
      }

      // Hook form submit to display immediate loading feedback
      const reportForm = document.getElementById('reportForm');
      const submitBtn = document.getElementById('submitBtn');

      if (reportForm && submitBtn) {
        reportForm.addEventListener('submit', function (e) {
          if (reportForm.checkValidity()) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            submitBtn.innerHTML = `
              <svg class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>Mengirim Laporan...</span>
            `;
          }
        });
      }
    });
  </script>
</body>
</html>
