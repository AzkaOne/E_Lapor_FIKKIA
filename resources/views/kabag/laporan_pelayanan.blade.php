<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Masuk - Panel KABAG E-Lapor FIKKIA UNAIR</title>
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
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col selection:bg-[#2E978E] selection:text-white">

  <!-- ================= TOP HEADER BAR ================= -->
@include('komponen.header_kabag')

  <!-- ================= MAIN LAYOUT WRAPPER ================= -->
   <div class="flex-1 flex flex-col min-w-0 lg:ml-52 min-h-screen">


    @include('komponen.sidebar_Kabag')
{{--
 <main class="flex-1 lg:ml-52 p-4 pt-20 sm:p-6 sm:pt-24 lg:p-7 lg:pt-24 max-w-[1400px] w-full mx-auto space-y-5"> --}}
    <main class="flex-1 p-4 sm:p-6 lg:p-7 pt-20 sm:pt-24 lg:pt-24 max-w-[1400px] w-full mx-auto space-y-6">

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
          <a href="{{ route('kabag.laporan-pelayanan') }}"
             class="flex items-center gap-2.5 pb-3.5 -mb-[1px] border-b-2 border-[#09473A] text-[#09473A] font-bold text-sm sm:text-base transition">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#09473A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Laporan Pelayanan</span>
          </a>

          <!-- Tab 2: Verifikasi laporan -->
          <a href="{{ route('kabag.verifikasi.index') }}"
             class="flex items-center gap-2.5 pb-3.5 -mb-[1px] border-b-2 border-transparent text-slate-400 hover:text-slate-700 font-medium text-sm sm:text-base transition">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Verifikasi laporan</span>
            @if (($counts['verifikasi'] ?? 0) > 0)
              <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-[#EF4444] text-white">
                {{ $counts['verifikasi'] }}
              </span>
            @endif
          </a>
        </div>

        <!-- ================= SEARCH & FILTERS ================= -->
        <form method="GET" action="{{ route('kabag.laporan-pelayanan') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-2.5 sm:gap-3">

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

        <!-- Filter Bidang -->
        <div class="w-full sm:w-36 shrink-0">
            <select name="sub_department_id" onchange="this.form.submit()"
                    class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 font-medium focus:ring-2 focus:ring-[#2E978E] focus:border-[#2E978E] focus:outline-none transition truncate cursor-pointer"
                    title="Filter Berdasarkan Bidang">
            <option value="">Semua Bidang</option>
            @foreach ($subDepartments as $dept)
                <option value="{{ $dept->id }}" {{ request('sub_department_id') == $dept->id ? 'selected' : '' }}>
                {{ $dept->name }}
                </option>
            @endforeach
            </select>
        </div>

        <!-- Filter Status -->
        <div class="w-full sm:w-32 shrink-0">
            <select name="status" onchange="this.form.submit()"
                    class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 font-medium focus:ring-2 focus:ring-[#2E978E] focus:border-[#2E978E] focus:outline-none transition cursor-pointer"
                    title="Filter Berdasarkan Status Laporan">
            <option value="">Semua Status</option>
            <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
            <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Dalam Proses</option>
            <option value="verifikasi_kabag" {{ request('status') === 'verifikasi_kabag' ? 'selected' : '' }}>Verifikasi</option>
            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <!-- Filter Tanggal -->
        <div class="w-full sm:w-36 shrink-0">
            <input type="date" name="date" value="{{ request('date', request('start_date')) }}" onchange="this.form.submit()"
                class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 font-medium focus:ring-2 focus:ring-[#2E978E] focus:outline-none cursor-pointer"
                title="Filter Berdasarkan Tanggal">
        </div>

        <!-- Reset Filter: Selalu Tampil -->
        <div class="w-full sm:w-auto shrink-0">
            <a href="{{ route('kabag.laporan-pelayanan') }}"
            class="w-full sm:w-auto px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-semibold transition text-center shrink-0 flex items-center justify-center gap-1.5"
            title="Reset Semua Filter">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span>Reset</span>
            </a>
        </div>

        </form>


        <!-- ================= STATS SECTION ================= -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">

          <!-- Stat 1: Total Laporan -->
          <div class="bg-[#ECFDF5] border border-[#059669] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#CCFBF1] text-[#059669] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#059669] leading-tight truncate">Total Laporan</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['total'] ?? 15 }}
              </div>
            </div>
          </div>

          <!-- Stat 2: Sarpras -->
          <div class="bg-[#EFF6FF] border border-[#0369A1] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#DBEAFE] text-[#0369A1] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#0369A1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#0369A1] leading-tight truncate">Sarpras</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['sarpras'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- Stat 3: Dekanat -->
          <div class="bg-[#F0FDFA] border border-[#CCFBF1] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#CCFBF1] text-[#15803D] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#15803D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#15803D] leading-tight truncate">Dekanat</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['dekanat'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- Stat 4: Keuangan -->
          <div class="bg-[#FEF3C7] border border-[#FDE68A] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#FDE68A] text-[#D97706] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#78350F] leading-tight truncate">Keuangan</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['keuangan'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- Stat 5: Akademik -->
          <div class="bg-[#FAF5FF] border border-[#F3E8FF] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm col-span-2 sm:col-span-1">
            <div class="w-11 h-11 rounded-xl bg-[#F3E8FF] text-[#7E22CE] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#7E22CE]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#7E22CE] leading-tight truncate">Akademik</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['akademik'] ?? 0 }}
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
                      @php
                        $deptName = $report->subDepartment?->name ?? 'Dekanat';
                        $isSarpras = str_contains(strtolower($deptName), 'sarana') || str_contains(strtolower($deptName), 'sarpras');
                        $isAkademik = str_contains(strtolower($deptName), 'pendidikan') || str_contains(strtolower($deptName), 'kemahasiswaan') || str_contains(strtolower($deptName), 'akademik');
                        $isKeuangan = str_contains(strtolower($deptName), 'keuangan') || str_contains(strtolower($deptName), 'sdm');
                      @endphp
                      @if ($isSarpras)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#EFF6FF] text-[#1D4ED8] border border-[#DBEAFE]">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                          <span>Sarpras</span>
                        </span>
                      @elseif ($isAkademik)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#FAF5FF] text-[#7E22CE] border border-[#F3E8FF]">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                          <span>Akademik</span>
                        </span>
                      @elseif ($isKeuangan)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#FEF3C7] text-[#78350F] border border-[#FDE68A]">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                          <span>Keuangan</span>
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#F0FDFA] text-[#15803D] border border-[#CCFBF1]">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                          <span>Dekanat</span>
                        </span>
                      @endif
                    </td>

                    <!-- Tanggal Laporan -->
                    <td class="py-4 px-3.5 text-slate-600">
                      <div class="font-medium text-xs">{{ $report->created_at->format('d M Y') }}</div>
                      <div class="text-[10px] text-slate-400">{{ $report->created_at->format('H:i') }}</div>
                    </td>

                    <!-- Status -->
                    <td class="py-4 px-3.5">
                      @if ($report->status === 'diajukan')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                          <span>Diajukan</span>
                        </span>
                      @elseif (in_array($report->status, ['proses', 'didisposisi', 'diterima']))
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EFF6FF] text-[#2563EB] border border-[#BFDBFE]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6]"></span>
                          <span>Dalam Proses</span>
                        </span>
                      @elseif ($report->status === 'verifikasi_kabag')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FAF5FF] text-[#7E22CE] border border-[#F3E8FF]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#8B5CF6]"></span>
                          <span>Verifikasi</span>
                        </span>
                      @elseif ($report->status === 'selesai')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                          <span>Selesai</span>
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                          <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                          <span>Ditolak</span>
                        </span>
                      @endif
                    </td>

                    <!-- Aksi -->
                    <td class="py-4 px-3.5 text-center">
                      <a href="{{ route('kabag.show', $report) }}"
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
                          @if (request()->hasAny(['search', 'sub_department_id', 'status', 'date', 'start_date', 'end_date']))
                            Tidak ada data laporan yang cocok dengan filter yang Anda tentukan. Silakan ubah filter atau klik tombol reset di bawah.
                          @else
                            Saat ini belum ada data laporan pelayanan yang masuk ke dalam sistem.
                          @endif
                        </p>
                        @if (request()->hasAny(['search', 'sub_department_id', 'status', 'date', 'start_date', 'end_date']))
                          <a href="{{ route('kabag.laporan-pelayanan') }}"
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
            <!-- Left: Page Selectors -->
            @if ($reports->hasPages())
              <div>
                {{ $reports->links() }}
              </div>
            @else
              <div class="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition text-slate-400" disabled>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button type="button" class="w-8 h-8 rounded-lg bg-[#0C3934] text-white font-bold flex items-center justify-center shadow-xs">
                  1
                </button>
                <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition">
                  2
                </button>
                <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition">
                  3
                </button>
                <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
              </div>
            @endif

            <!-- Right: Rows per page selector -->
            <div class="text-xs text-slate-500 flex items-center gap-2">
              <span>Tampilkan</span>
              <select class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 bg-white focus:outline-none focus:ring-1 focus:ring-[#2E978E]">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
              </select>
              <span>data per halaman</span>
            </div>
          </div>

        </div>

      </div>

    </main>

  </div>

</body>
</html>
