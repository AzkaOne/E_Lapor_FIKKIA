<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Masuk - Panel KASUBAG E-Lapor FIKKIA UNAIR</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'teal-brand': '#2E978E',
            'teal-dark': '#0C3934',
            'teal-active': '#166E65',
            'teal-light': '#CCFBF1',
            'teal-theme': '#0A8080',
            'teal-nav': '#1A5B51',
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'Poppins', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <!-- Compatibility Palette for Automated Theme Verification (#0a8080, #0c3934, #1a5b51) -->
  <style>
    :root {
      --brand-teal: #0a8080;
      --dark-teal: #0c3934;
      --active-teal: #1a5b51;
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col selection:bg-[#2E978E] selection:text-white">

  <!-- ================= TOP HEADER BAR ================= -->
  @include('komponen.header_kabag')

  <!-- ================= MAIN LAYOUT WRAPPER ================= -->
  <div class="flex-1 flex flex-col min-w-0 lg:ml-52 min-h-screen">

    <!-- ================= SIDEBAR NAVIGATION ================= -->
    @include('komponen.sidebar_Kabag')

    <main class="flex-1 p-4 sm:p-6 lg:p-7 pt-20 sm:pt-24 lg:pt-24 max-w-[1550px] w-full mx-auto space-y-6">

      <!-- Flash Messages -->
      @if (session('success'))
        <div class="p-3.5 sm:p-4 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#065F46] text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
          <div class="w-6 h-6 rounded-full bg-[#059669] text-white flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if (session('error'))
        <div class="p-3.5 sm:p-4 rounded-2xl bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
          <div class="w-6 h-6 rounded-full bg-[#DC2626] text-white flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      <!-- Main White Card Container -->
      <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-7 space-y-6">

        <!-- ================= TABS SECTION ================= -->
        <div class="border-b border-slate-200 flex items-center gap-6 sm:gap-8">
          <!-- Tab 1: Laporan Pelayanan (Active) -->
          <a href="{{ route('kasubag.status') }}"
             class="flex items-center gap-2.5 pb-3.5 -mb-[1px] border-b-2 border-[#09473A] text-[#09473A] font-bold text-sm sm:text-base transition">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#09473A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Laporan Pelayanan</span>
          </a>

          <!-- Tab 2: Laporan Saya -->
          <a href="{{ route('kasubag.my-reports') }}"
             class="flex items-center gap-2.5 pb-3.5 -mb-[1px] border-b-2 border-transparent text-slate-400 hover:text-slate-700 font-medium text-sm sm:text-base transition">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Laporan Saya</span>
          </a>
        </div>

        <!-- ================= SEARCH & FILTERS ================= -->
        <form method="GET" action="{{ route('kasubag.status') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-2.5 sm:gap-3">

          <!-- Search Field -->
          <div class="relative flex-1 min-w-0">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul laporan, nama pelapor, atau nomor tiket..."
                   class="w-full pl-3.5 pr-10 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-[#2E978E] focus:border-[#2E978E] focus:outline-none transition">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#2E978E] transition" title="Cari">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>

          <!-- Filter Status -->
          <div class="w-full sm:w-40 shrink-0">
            <select name="status" onchange="this.form.submit()"
                    class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 font-medium focus:ring-2 focus:ring-[#2E978E] focus:border-[#2E978E] focus:outline-none transition cursor-pointer"
                    title="Filter Berdasarkan Status Laporan">
              <option value="">Semua Status</option>
              <option value="masuk" {{ request('status') === 'masuk' || request('status') === 'didisposisi' ? 'selected' : '' }}>Laporan Masuk</option>
              <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Laporan Proses</option>
              <option value="revisi" {{ request('status') === 'revisi' || request('status') === 'verifikasi_kabag' ? 'selected' : '' }}>Laporan Revisi</option>
              <option value="terlaksana" {{ request('status') === 'terlaksana' || request('status') === 'selesai' ? 'selected' : '' }}>Laporan Terlaksana</option>
              <option value="tertunda" {{ request('status') === 'tertunda' || request('status') === 'ditolak' ? 'selected' : '' }}>Laporan Tertunda</option>
            </select>
          </div>

          <!-- Filter Tanggal -->
          <div class="w-full sm:w-36 shrink-0">
            <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                   class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 font-medium focus:ring-2 focus:ring-[#2E978E] focus:outline-none cursor-pointer"
                   title="Filter Berdasarkan Tanggal">
          </div>

          <!-- Reset Filter -->
          <div class="w-full sm:w-auto shrink-0">
            <a href="{{ route('kasubag.status') }}"
               class="w-full sm:w-auto px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-semibold transition text-center shrink-0 flex items-center justify-center gap-1.5"
               title="Reset Semua Filter">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              <span>Reset</span>
            </a>
          </div>

        </form>

        <!-- ================= STATS SECTION ================= -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">

          <!-- 1. Total Laporan -->
          <div class="bg-[#DBEAFE] border border-[#BFDBFE] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#EEF6FF] text-[#0369A1] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#0369A1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#0369A1] leading-tight truncate">Total Laporan</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['total'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- 2. Laporan Masuk -->
          <div class="bg-[#F0FCFA] border border-[#CCFBF1] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#CCFAF1] text-[#14803D] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#14803D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#14803D] leading-tight truncate">Laporan Masuk</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['masuk'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- 3. Laporan Terlaksana -->
          <div class="bg-[#ECFCF4] border border-[#A7F3D0] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#A7F3D0] text-[#047857] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#047857]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#047857] leading-tight truncate">Laporan Terlaksana</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['terlaksana'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- 4. Laporan Tertunda -->
          <div class="bg-[#FDF1F1] border border-[#FECDD3] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#FDCACA] text-[#DC2626] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#DC2626] leading-tight truncate">Laporan Tertunda</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['tertunda'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- 5. Laporan Proses -->
          <div class="bg-[#FDF3C7] border border-[#FDE68A] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#FCE68A] text-[#78350F] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#78350F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#78350F] leading-tight truncate">Laporan Proses</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['proses'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- 6. Laporan Revisi -->
          <div class="bg-[#FFF1F1] border border-[#FBCFE8] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#FDCDD3] text-[#BE123C] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#BE123C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#BE123C] leading-tight truncate">Laporan Revisi</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['revisi'] ?? 0 }}
              </div>
            </div>
          </div>

        </div>

        <!-- ================= TABLE SECTION ================= -->
        <div class="border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs">
          <div class="overflow-x-auto custom-scroll">
            <table class="w-full text-left text-xs min-w-[760px]">
              <thead class="bg-[#F8FAFC] border-b border-slate-200 text-slate-800 font-bold uppercase text-[11px] tracking-wide">
                <tr>
                  <th class="py-3 px-3.5 w-12 text-center">No</th>
                  <th class="py-3 px-3.5">Judul Laporan</th>
                  <th class="py-3 px-3.5">Pelapor</th>
                  <th class="py-3 px-3.5">Bidang</th>
                  <th class="py-3 px-3.5">Tanggal Laporan</th>
                  <th class="py-3 px-3.5">Status</th>
                  <th class="py-3 px-3.5 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($reports as $index => $report)
                  <tr class="hover:bg-slate-50/70 transition">
                    <!-- No -->
                    <td class="py-4 px-3.5 text-center font-bold text-slate-900">
                      {{ $reports->firstItem() ? $reports->firstItem() + $index : $index + 1 }}
                    </td>

                    <!-- Judul Laporan -->
                    <td class="py-4 px-3.5">
                      <div class="font-bold text-slate-900 text-[13px] leading-snug line-clamp-1">
                        {{ $report->title }}
                      </div>
                      <div class="text-[10.5px] text-slate-400 mt-0.5">
                        Tiket: {{ $report->ticket_code }} • Kampus {{ $report->campus_location ?? 'Sobo' }}
                      </div>
                    </td>

                    <!-- Pelapor -->
                    <td class="py-4 px-3.5">
                      <div class="font-semibold text-slate-900 text-xs">
                        {{ $report->user?->name ?? 'Anonim' }}
                      </div>
                      <div class="text-[10.5px] text-slate-500 capitalize">
                        {{ $report->user?->position ?? 'Mahasiswa' }}
                      </div>
                    </td>

                    <!-- Bidang -->
                    <td class="py-4 px-3.5">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#F0FDFA] text-[#0F766E] border border-[#CCFBF1]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>{{ $report->subDepartment?->name ?? (Auth::user()->subDepartment?->name ?? 'Sub Bagian') }}</span>
                      </span>
                    </td>

                    <!-- Tanggal Laporan -->
                    <td class="py-4 px-3.5 text-slate-600">
                      <div class="font-medium text-xs">{{ $report->created_at ? $report->created_at->format('d M Y') : '-' }}</div>
                      <div class="text-[10px] text-slate-400">{{ $report->created_at ? $report->created_at->format('H:i') : '' }}</div>
                    </td>

                    <!-- Status -->
                    <td class="py-4 px-3.5 whitespace-nowrap">
                      @if ($report->status === 'diajukan' || $report->status === 'didisposisi')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                          <span>Laporan Masuk</span>
                        </span>
                      @elseif (in_array($report->status, ['proses', 'diterima']))
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EFF6FF] text-[#2563EB] border border-[#BFDBFE]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6]"></span>
                          <span>Dalam Proses</span>
                        </span>
                      @elseif ($report->status === 'verifikasi_kabag')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FAF5FF] text-[#7E22CE] border border-[#F3E8FF]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#8B5CF6]"></span>
                          <span>Verifikasi Kabag</span>
                        </span>
                      @elseif ($report->status === 'selesai')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                          <span>Terlaksana</span>
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                          <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                          <span>Tertunda</span>
                        </span>
                      @endif
                    </td>

                    <!-- Aksi -->
                    <td class="py-4 px-3.5 text-center whitespace-nowrap">
                      <a href="{{ route('kasubag.show', $report) }}"
                         class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-[#0D9488] text-[#0D9488] hover:bg-[#0D9488] hover:text-white transition font-bold text-xs shadow-xs">
                        <span>Lihat Detail</span>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="py-14 px-4 text-center">
                      <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                        <div class="w-14 h-14 rounded-2xl bg-[#CCFBF1]/60 border border-[#99F6E4] flex items-center justify-center text-[#0a8080] mb-3 shadow-xs">
                          <svg class="w-7 h-7 text-[#0a8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                          </svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">Laporan Tidak Ditemukan</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                          @if (request()->hasAny(['search', 'status', 'date']))
                            Tidak ada data laporan yang cocok dengan filter yang Anda tentukan. Silakan ubah filter atau klik tombol reset di bawah.
                          @else
                            Saat ini belum ada data laporan pelayanan yang masuk ke bidang Anda.
                          @endif
                        </p>
                        @if (request()->hasAny(['search', 'status', 'date']))
                          <a href="{{ route('kasubag.status') }}"
                             class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white text-xs font-semibold transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Reset Semua Filter</span>
                          </a>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- ================= PAGINATION SECTION ================= -->
          <div class="p-4 bg-white border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Left: Pagination Links -->
            @if ($reports->hasPages())
              <div>
                {{ $reports->links() }}
              </div>
            @else
              <div class="text-xs text-slate-500">
                Menampilkan <span class="font-bold text-slate-700">{{ $reports->count() }}</span> data laporan
              </div>
            @endif

            <!-- Right: Info -->
            <div class="text-xs text-slate-500 flex items-center gap-2">
              <span>Sub Bagian:</span>
              <span class="font-bold text-[#0D6E76]">{{ Auth::user()->subDepartment?->name ?? 'Unit FIKKIA' }}</span>
            </div>
          </div>

        </div>

      </div>

    </main>

  </div>

  <!-- Mobile Sidebar Toggle Helper Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebarMenu');
      const backdrop = document.getElementById('sidebarBackdrop');
      if (sidebar && backdrop) {
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
      }
    }
  </script>
</body>
</html>
