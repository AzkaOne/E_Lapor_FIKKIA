<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Laporan - E-LAPOR FIKKIA</title>

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
              header: '#0C3934',
              sidebar: '#2E978E',
              sidebarActive: '#0F6B60',
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
      height: 5px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
      background-color: #cbd5e1;
      border-radius: 9999px;
    }
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="min-h-screen bg-[#F4F7F8] flex flex-col text-slate-800 antialiased selection:bg-[#0a8080] selection:text-white relative overflow-x-hidden" x-data="statusReportModal()">

  <!-- ================= TOP HEADER BAR ================= -->
  @include('komponen.header_Pelapor')

  <!-- ================= LAYOUT CONTAINER ================= -->
  <div class="flex-1 flex w-full relative pt-14">

    <!-- ================= SIDEBAR NAVIGATION ================= -->
    @include('komponen.sidebar_Pelapor')

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 lg:ml-52 py-4 sm:py-5 px-4 sm:px-6 lg:px-8 max-w-[1220px] w-full mx-auto min-w-0">

      <!-- Breadcrumb -->
      <div class="mb-1">
        <span class="text-xs font-semibold text-[#115E59] font-['Inter',sans-serif]">
          Dashboard
        </span>
      </div>

      <!-- Header Row: Title & Subtitle + Butuh Bantuan Card -->
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-5">
        <!-- Title & Subtitle -->
        <div class="space-y-1.5 max-w-2xl">
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#CCFBF1] border border-[#99F6E4] text-[#115E59] font-['Plus_Jakarta_Sans',sans-serif] text-[11px] font-bold shadow-xs">
            <span class="w-1.5 h-1.5 rounded-full bg-[#115E59]"></span>
            E-Lapor FIKKIA
          </div>

          <h1 class="text-xl sm:text-2xl font-extrabold text-[#0E3B43] font-['Plus_Jakarta_Sans',sans-serif] tracking-tight">
            Status Laporan
          </h1>

          <p class="text-xs text-[#64748B] font-['Inter',sans-serif] leading-relaxed">
            Pantau perkembangan laporan yang Anda buat. Informasi status akan diperbarui secara berkala sesuai dengan proses penanganan di setiap unit.
          </p>
        </div>

        <!-- Butuh Bantuan Card -->
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

      <!-- ================= SUMMARY CARDS SECTION ================= -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3 mb-4">

        <!-- Card 1: Total Laporan -->
        <a href="{{ route('pelapor.status', ['status' => 'all']) }}" class="bg-white rounded-xl border border-[#DBEAFE] p-3 sm:p-3.5 shadow-xs hover:shadow-sm hover:border-blue-300 transition flex items-center gap-3 group">
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0 group-hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div class="min-w-0">
            <div class="text-[11px] font-semibold text-[#64748B] truncate">Total Laporan</div>
            <div class="text-lg sm:text-xl font-bold text-[#1E293B] leading-tight">{{ $totalCount }}</div>
          </div>
        </a>

        <!-- Card 2: Dalam Proses -->
        <a href="{{ route('pelapor.status', ['status' => 'proses']) }}" class="bg-[#FFFCF4] rounded-xl border border-[#FEF3C7] p-3 sm:p-3.5 shadow-xs hover:shadow-sm hover:border-amber-300 transition flex items-center gap-3 group">
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0 group-hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="min-w-0">
            <div class="text-[11px] font-semibold text-[#64748B] truncate">Dalam Proses</div>
            <div class="text-lg sm:text-xl font-bold text-[#1E293B] leading-tight">{{ $prosesCount }}</div>
          </div>
        </a>

        <!-- Card 3: Selesai -->
        <a href="{{ route('pelapor.status', ['status' => 'selesai']) }}" class="bg-[#F6FCF8] rounded-xl border border-[#D1FAE5] p-3 sm:p-3.5 shadow-xs hover:shadow-sm hover:border-emerald-300 transition flex items-center gap-3 group">
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0 group-hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="min-w-0">
            <div class="text-[11px] font-semibold text-[#64748B] truncate">Selesai</div>
            <div class="text-lg sm:text-xl font-bold text-[#1E293B] leading-tight">{{ $selesaiCount }}</div>
          </div>
        </a>

        <!-- Card 4: Perlu Perbaikan -->
        <a href="{{ route('pelapor.status', ['status' => 'perbaikan']) }}" class="bg-[#FFFBEB] rounded-xl border border-[#FEF3C7] p-3 sm:p-3.5 shadow-xs hover:shadow-sm hover:border-rose-300 transition flex items-center gap-3 group">
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#FEE2E2] text-[#DC2626] flex items-center justify-center shrink-0 group-hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div class="min-w-0">
            <div class="text-[11px] font-semibold text-[#64748B] truncate">Perlu Perbaikan</div>
            <div class="text-lg sm:text-xl font-bold text-[#1E293B] leading-tight">{{ $perbaikanCount }}</div>
          </div>
        </a>

        <!-- Card 5: Menunggu -->
        <a href="{{ route('pelapor.status', ['status' => 'menunggu']) }}" class="bg-[#FAF6FF] rounded-xl border border-[#F3E8FF] p-3 sm:p-3.5 shadow-xs hover:shadow-sm hover:border-purple-300 transition flex items-center gap-3 group col-span-2 sm:col-span-1">
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#F3E8FF] text-[#9333EA] flex items-center justify-center shrink-0 group-hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="min-w-0">
            <div class="text-[11px] font-semibold text-[#64748B] truncate">Menunggu</div>
            <div class="text-lg sm:text-xl font-bold text-[#1E293B] leading-tight">{{ $menungguCount }}</div>
          </div>
        </a>

      </div>

      <!-- ================= FILTER SECTION ================= -->
      <div class="bg-white rounded-xl border border-slate-200/90 p-3 sm:p-4 mb-4 shadow-xs">
        <form method="GET" action="{{ route('pelapor.status') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">

          <!-- Filter Bidang -->
          <div class="lg:col-span-3 space-y-1">
            <label for="bidang" class="block text-[11px] font-semibold text-slate-700">
              Filter Bidang
            </label>
            <div class="relative">
              <select name="bidang" id="bidang" onchange="this.form.submit()" class="w-full h-9 pl-3 pr-8 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0F766E] focus:border-[#0F766E] appearance-none cursor-pointer">
                <option value="">Semua Bidang</option>
                @foreach ($subDepartments as $dept)
                  <option value="{{ $dept->id }}" {{ request('bidang') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                  </option>
                @endforeach
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Filter Tanggal -->
          <div class="lg:col-span-3 space-y-1">
            <label for="tanggal" class="block text-[11px] font-semibold text-slate-700">
              Filter Tanggal
            </label>
            <div class="relative">
              <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()" class="w-full h-9 px-3 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0F766E] focus:border-[#0F766E] cursor-pointer">
            </div>
          </div>

          <!-- Filter Status -->
          <div class="lg:col-span-2 space-y-1">
            <label for="status" class="block text-[11px] font-semibold text-slate-700">
              Filter Status
            </label>
            <div class="relative">
              <select name="status" id="status" onchange="this.form.submit()" class="w-full h-9 pl-3 pr-8 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#0F766E] focus:border-[#0F766E] appearance-none cursor-pointer">
                <option value="">Semua Status</option>
                <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Dalam Proses</option>
                <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="perbaikan" {{ request('status') === 'perbaikan' || request('status') === 'ditolak' ? 'selected' : '' }}>Perlu Perbaikan</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Cari Laporan -->
          <div class="lg:col-span-3 space-y-1">
            <label for="q" class="block text-[11px] font-semibold text-slate-700">
              Cari Laporan
            </label>
            <div class="relative">
              <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Cari judul laporan..." class="w-full h-9 pl-8 pr-3 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#0F766E] focus:border-[#0F766E]">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Reset Filter Button -->
          <div class="lg:col-span-1 flex items-center">
            @if(request()->hasAny(['bidang', 'tanggal', 'status', 'q']))
              <a href="{{ route('pelapor.status') }}" class="w-full h-9 px-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600 hover:text-slate-900 flex items-center justify-center gap-1 text-[11px] font-medium transition" title="Hapus Filter">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="sm:hidden lg:inline">Reset</span>
              </a>
            @else
              <button type="submit" class="w-full h-9 px-2 rounded-lg bg-[#0F766E] hover:bg-[#0c5c56] text-white flex items-center justify-center gap-1 text-[11px] font-semibold transition" title="Cari">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="sm:hidden lg:inline">Cari</span>
              </button>
            @endif
          </div>

        </form>
      </div>

      <!-- ================= TABLE SECTION ================= -->
      <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">

        @if ($reports->isEmpty())
          <!-- Empty State -->
          <div class="py-12 px-4 text-center">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-[#0F766E] flex items-center justify-center mx-auto mb-3">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <h3 class="text-sm sm:text-base font-bold text-slate-900">Belum Ada Laporan</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
              @if(request()->hasAny(['bidang', 'tanggal', 'status', 'q']))
                Tidak ada laporan yang sesuai dengan kriteria filter yang Anda pilih.
              @else
                Saat ini Anda tidak memiliki laporan aktif yang sedang diproses.
              @endif
            </p>
            @if(request()->hasAny(['bidang', 'tanggal', 'status', 'q']))
              <a href="{{ route('pelapor.status') }}" class="inline-flex items-center gap-1.5 mt-4 px-3.5 py-1.5 rounded-lg bg-[#0F766E] hover:bg-[#0c5c56] text-white text-xs font-semibold shadow-xs transition">
                <span>Reset Filter</span>
              </a>
            @else
              <a href="{{ route('pelapor.dashboard') }}" class="inline-flex items-center gap-1.5 mt-4 px-3.5 py-1.5 rounded-lg bg-[#0F766E] hover:bg-[#0c5c56] text-white text-xs font-semibold shadow-xs transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Buat Laporan Baru</span>
              </a>
            @endif
          </div>
        @else
          <!-- Responsive Table Container -->
          <div class="overflow-x-auto custom-scroll">
            <table class="w-full text-left border-collapse min-w-[880px]">
              <thead>
                <tr class="bg-[#F8FAFC] border-b border-slate-200/90 text-slate-600 font-['Plus_Jakarta_Sans',sans-serif] text-xs font-bold">
                  <th scope="col" class="py-3 px-3.5 text-center w-12">No</th>
                  <th scope="col" class="py-3 px-3.5 min-w-[220px]">Judul Laporan</th>
                  <th scope="col" class="py-3 px-3.5 w-32">Bidang</th>
                  <th scope="col" class="py-3 px-3.5 w-36">Tanggal Laporan</th>
                  <th scope="col" class="py-3 px-3.5 w-36">Status</th>
                  <th scope="col" class="py-3 px-3.5 w-56">Penanganan</th>
                  <th scope="col" class="py-3 px-3.5 text-center w-28">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-xs">
                @foreach ($reports as $index => $report)
                  @php
                    $deptCode = $report->subDepartment?->code ?? '';
                    $deptName = $report->subDepartment?->name ?? 'Umum';

                    // Determine badge styling based on SubDepartment
                    if (str_contains(strtoupper($deptCode), 'SARPRAS')) {
                      $deptBadge = 'bg-[#EFF6FF] text-[#1D4ED8] border-[#DBEAFE]';
                      $shortDept = 'Sarpras';
                    } elseif (str_contains(strtoupper($deptCode), 'PENDIDIKAN') || str_contains(strtoupper($deptCode), 'AKADEMIK')) {
                      $deptBadge = 'bg-[#ECFDF5] text-[#047857] border-[#A7F3D0]';
                      $shortDept = 'Akademik';
                    } elseif (str_contains(strtoupper($deptCode), 'SDM') || str_contains(strtoupper($deptCode), 'KEUANGAN')) {
                      $deptBadge = 'bg-[#F0FDF4] text-[#15803D] border-[#BBF7D0]';
                      $shortDept = 'Keuangan & SDM';
                    } elseif (str_contains(strtoupper($deptCode), 'DEKANAT')) {
                      $deptBadge = 'bg-[#FAF5FF] text-[#7E22CE] border-[#E9D5FF]';
                      $shortDept = 'Dekanat';
                    } else {
                      $deptBadge = 'bg-slate-50 text-slate-700 border-slate-200';
                      $shortDept = $deptName;
                    }

                    // Determine status badge styling
                    if (in_array($report->status, ['didisposisi', 'diterima', 'proses', 'verifikasi_kabag'])) {
                      $statusLabel = 'Dalam Proses';
                      $statusBadge = 'bg-[#EFF6FF] text-[#1D4ED8] border-[#BFDBFE]';
                      $statusDot = 'bg-[#2563EB] animate-pulse';
                    } elseif ($report->status === 'diajukan') {
                      $statusLabel = 'Menunggu';
                      $statusBadge = 'bg-[#FAF5FF] text-[#7E22CE] border-[#E9D5FF]';
                      $statusDot = 'bg-[#9333EA]';
                    } elseif ($report->status === 'selesai') {
                      $statusLabel = 'Selesai';
                      $statusBadge = 'bg-[#ECFDF5] text-[#047857] border-[#A7F3D0]';
                      $statusDot = 'bg-[#059669]';
                    } elseif ($report->status === 'ditolak') {
                      $statusLabel = 'Perlu Perbaikan';
                      $statusBadge = 'bg-[#FFFBEB] text-[#B45309] border-[#FDE68A]';
                      $statusDot = 'bg-[#D97706]';
                    } else {
                      $statusLabel = ucfirst($report->status);
                      $statusBadge = 'bg-slate-50 text-slate-700 border-slate-200';
                      $statusDot = 'bg-slate-500';
                    }
                  @endphp
                  <tr class="hover:bg-slate-50/75 transition duration-150">

                    <!-- No -->
                    <td class="py-3.5 px-3.5 text-center text-slate-500 font-medium">
                      {{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration }}
                    </td>

                    <!-- Judul Laporan -->
                    <td class="py-3.5 px-3.5">
                      <div class="space-y-0.5">
                        <div class="font-bold text-[#1E293B] text-[12.5px] leading-snug line-clamp-1 hover:text-[#0F766E] transition cursor-pointer" @click="openModal({{ json_encode($report) }})">
                          {{ $report->title }}
                        </div>
                        <p class="text-[11px] text-[#64748B] line-clamp-1 leading-normal">
                          {{ $report->description }}
                        </p>
                      </div>
                    </td>

                    <!-- Bidang -->
                    <td class="py-3.5 px-3.5 whitespace-nowrap">
                      <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md border text-[11px] font-medium {{ $deptBadge }}">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>{{ $shortDept }}</span>
                      </span>
                    </td>

                    <!-- Tanggal Laporan -->
                    <td class="py-3.5 px-3.5 whitespace-nowrap">
                      <div class="text-[#334155] font-medium text-[11.5px]">
                        {{ $report->created_at->translatedFormat('d M Y') }}
                      </div>
                      <div class="text-[10.5px] text-[#94A3B8]">
                        {{ $report->created_at->format('H:i') }} WIB
                      </div>
                    </td>

                    <!-- Status -->
                    <td class="py-3.5 px-3.5 whitespace-nowrap">
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full border text-[11px] font-semibold {{ $statusBadge }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                        <span>{{ $statusLabel }}</span>
                      </span>
                    </td>

                    <!-- Penanganan (Workflow Trail) -->
                    <td class="py-3.5 px-3.5 whitespace-nowrap">
                      <div class="flex items-center gap-1 text-[11px] text-[#475569] font-medium">
                        <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-700">Kabag</span>
                        <svg class="w-2.5 h-2.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H9M17 7v8" />
                        </svg>
                        <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-700">Kasubag {{ $shortDept }}</span>
                        <svg class="w-2.5 h-2.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H9M17 7v8" />
                        </svg>
                        <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-700">Petugas</span>
                      </div>
                    </td>

                    <!-- Aksi -->
                    <td class="py-3.5 px-3.5 text-center whitespace-nowrap">
                      <button type="button" @click="openModal({{ json_encode($report) }})" class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-[#14B8A6]/70 hover:bg-teal-50 text-[#0F766E] font-semibold text-xs shadow-2xs transition">
                        <svg class="w-3.5 h-3.5 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Lihat Detail</span>
                      </button>
                    </td>

                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Table Pagination Footer -->
          <div class="px-4 py-3 sm:px-6 bg-white border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <div>
              Menampilkan <span class="font-semibold text-slate-800">{{ $reports->firstItem() ?? 0 }}</span>–<span class="font-semibold text-slate-800">{{ $reports->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-slate-800">{{ $reports->total() ?? 0 }}</span> laporan
            </div>

            <!-- Pagination Links -->
            <div>
              {{ $reports->links('pagination::tailwind') }}
            </div>
          </div>
        @endif

      </div>

    </main>

  </div>

  <!-- ================= MODAL DETAIL LAPORAN ================= -->
  <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop Overlay -->
    <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="closeModal()"></div>

    <!-- Modal Content Box -->
    <div class="min-h-full flex items-center justify-center p-4">
      <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden border border-slate-200 text-left my-8" @click.away="closeModal()">

        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-[#0C3934] to-[#134E48] px-5 py-4 text-white flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-white/15 text-teal-200 border border-white/20" x-text="activeReport?.ticket_code"></span>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full capitalize bg-white/15 text-white border border-white/20" x-text="getStatusLabel(activeReport?.status)"></span>
          </div>
          <button type="button" @click="closeModal()" class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/90 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 sm:p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scroll text-xs">

          <!-- Judul & Meta -->
          <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-snug" x-text="activeReport?.title"></h2>
            <div class="flex flex-wrap items-center gap-2 mt-1.5 text-[11px] text-slate-500">
              <span class="font-medium text-[#0F766E]" x-text="activeReport?.sub_department?.name || 'Umum'"></span>
              <span>&bull;</span>
              <span>Kampus <span class="capitalize font-semibold text-slate-700" x-text="activeReport?.campus_location"></span></span>
              <span>&bull;</span>
              <span x-text="formatDate(activeReport?.created_at)"></span>
            </div>
          </div>

          <!-- Deskripsi Masalah -->
          <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-100">
            <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Uraian Masalah</h4>
            <p class="text-slate-700 leading-relaxed whitespace-pre-line text-xs" x-text="activeReport?.description"></p>
          </div>

          <!-- Catatan / Resolusi Laporan -->
          <template x-if="activeReport?.resolution_notes || activeReport?.postponed_reason || activeReport?.rejection_reason">
            <div class="p-3.5 rounded-xl border bg-slate-50 border-slate-200">
              <span class="font-bold block mb-1 text-slate-800" x-text="activeReport?.status === 'selesai' ? 'Catatan Penyelesaian:' : 'Alasan Penolakan / Catatan Khusus:'"></span>
              <p class="text-xs leading-relaxed text-slate-600" x-text="activeReport?.resolution_notes || activeReport?.postponed_reason || activeReport?.rejection_reason"></p>
            </div>
          </template>

          <!-- Bukti / Lampiran (Evidences) -->
          <template x-if="activeReport?.evidences && activeReport?.evidences.length > 0">
            <div>
              <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Lampiran Bukti</h4>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                <template x-for="evidence in activeReport.evidences" :key="evidence.id">
                  <a :href="'/storage/' + evidence.file_path" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg border border-slate-200 hover:border-teal-500 bg-white flex items-center gap-2 transition group">
                    <svg class="w-4 h-4 text-[#0F766E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span class="text-[11px] font-medium text-slate-700 truncate group-hover:text-[#0F766E]">Lihat Berkas</span>
                  </a>
                </template>
              </div>
            </div>
          </template>

          <!-- Timeline Alur & Catatan Disposisi -->
          <div class="pt-3 border-t border-slate-100">
            <h4 class="text-xs font-bold text-slate-900 mb-2.5">Histori Alur Penanganan</h4>

            <template x-if="!activeReport?.dispositions || activeReport?.dispositions.length === 0">
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-500 italic text-[11px]">
                Laporan telah diterima oleh sistem dan sedang menunggu review disposisi oleh KABAG.
              </div>
            </template>

            <template x-if="activeReport?.dispositions && activeReport?.dispositions.length > 0">
              <div class="space-y-2.5">
                <template x-for="(disp, idx) in activeReport.dispositions" :key="disp.id">
                  <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-[#0F766E]/15 text-[#0F766E] flex items-center justify-center shrink-0 mt-0.5 font-bold text-[10px]">
                      <span x-text="idx + 1"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between gap-2">
                        <span class="font-bold text-[#0F766E] text-[11px]" x-text="(disp.sender?.name || 'Petugas') + ' (' + (disp.sender?.role || 'KABAG').toUpperCase() + ')'"></span>
                        <span class="text-[10px] text-slate-400" x-text="formatDate(disp.created_at)"></span>
                      </div>
                      <p class="text-slate-600 text-[11px] mt-0.5" x-text="disp.notes || 'Disposisi penugasan diteruskan.'"></p>
                    </div>
                  </div>
                </template>
              </div>
            </template>
          </div>

        </div>

        <!-- Modal Footer -->
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
          <button type="button" @click="closeModal()" class="px-4 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-medium transition">
            Tutup
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- ================= SCRIPTS ================= -->
  <script>
    function statusReportModal() {
      return {
        showModal: false,
        activeReport: null,
        openModal(report) {
          this.activeReport = report;
          this.showModal = true;
        },
        closeModal() {
          this.showModal = false;
          this.activeReport = null;
        },
        getStatusLabel(status) {
          if (!status) return 'Menunggu';
          if (['didisposisi', 'diterima', 'proses', 'verifikasi_kabag'].includes(status)) return 'Dalam Proses';
          if (status === 'diajukan') return 'Menunggu';
          if (status === 'selesai') return 'Selesai';
          if (status === 'ditolak') return 'Perlu Perbaikan';
          return status;
        },
        formatDate(dateString) {
          if (!dateString) return '-';
          const d = new Date(dateString);
          return d.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          }) + ' WIB';
        }
      }
    }
  </script>

</body>
</html>
