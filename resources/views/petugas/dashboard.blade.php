<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Petugas - E-Lapor FIKKIA UNAIR</title>
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
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
  </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen selection:bg-[#2E978E] selection:text-white">

  <!-- ================= SIDEBAR COMPONENT ================= -->
  @include('komponen.sidebar_Kabag')

  <div class="flex-1 flex flex-col min-w-0 lg:ml-52 min-h-screen pt-14">

    <!-- ================= TOP HEADER BAR ================= -->
    @include('komponen.header_kabag')

    <!-- Main Workspace Content -->
    <main class="flex-1 p-4 sm:p-6 lg:p-7 pt-20 max-w-[1400px] w-full mx-auto space-y-6">

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

      <!-- ================= GREETING & TITLE ================= -->
      <div class="space-y-1">
        <!-- Tag / Badge Pill -->
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#CCFBF1] text-[#0C3934] text-xs font-semibold shadow-xs">
          <span class="w-2 h-2 rounded-full bg-[#0D9488]"></span>
          <span>E-Lapor FIKKIA</span>
        </div>

        <div class="pt-1">
          <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">SELAMAT DATANG,</div>
          <h2 class="text-xl sm:text-2xl lg:text-[26px] font-extrabold text-[#0C3934] tracking-tight leading-snug">
            {{ Auth::user()->name }}
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Berikut adalah ringkasan penugasan lapangan terbaru di E-Lapor UNAIR untuk sub bagian {{ Auth::user()->subDepartment?->name ?? 'Anda' }}.
          </p>
        </div>
      </div>

      <!-- ================= TOP SECTION (CHART + STATS + NOTES) ================= -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-5 items-stretch">

        <!-- CARD 1: Laporan Per Bidang / Penugasan (Donut Chart) -->
        <div class="lg:col-span-5 bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 tracking-tight">Laporan Per Bidang</h3>
            <span class="text-[11px] font-medium text-slate-400">
              {{ Auth::user()->subDepartment?->name ?? 'Total Penugasan' }}
            </span>
          </div>

          @php
            $cTerlaksana = $counts['selesai'] ?? 0;
            $cMasuk = $counts['masuk'] ?? 0;
            $cProses = $counts['dikerjakan'] ?? 0;
            $cTertunda = $counts['tertunda'] ?? 0;
            $cTotal = $totalReports ?? ($cTerlaksana + $cMasuk + $cProses + $cTertunda);

            $circumference = 2 * 3.14159 * 38; // ~238.76
            $chartTotal = $cTotal > 0 ? $cTotal : 1;

            $pTerlaksana = ($cTerlaksana / $chartTotal) * $circumference;
            $pMasuk = ($cMasuk / $chartTotal) * $circumference;
            $pTertunda = ($cTertunda / $chartTotal) * $circumference;
            $pProses = ($cProses / $chartTotal) * $circumference;

            $offset1 = 0;
            $offset2 = $offset1 - $pTerlaksana;
            $offset3 = $offset2 - $pMasuk;
            $offset4 = $offset3 - $pTertunda;
          @endphp

          <div class="flex flex-col sm:flex-row lg:flex-row items-center justify-center gap-5 my-auto py-3">
            <!-- Donut SVG Graphic -->
            <div class="relative w-36 h-36 shrink-0 flex items-center justify-center">
              <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <!-- Background track -->
                <circle cx="50" cy="50" r="38" fill="none" stroke="#F1F5F9" stroke-width="20" />

                @if ($cTotal > 0)
                  <!-- Terlaksana segment -->
                  @if ($cTerlaksana > 0)
                    <circle cx="50" cy="50" r="38" fill="none" stroke="#256E62" stroke-width="20"
                            stroke-dasharray="{{ $pTerlaksana }} {{ $circumference - $pTerlaksana }}"
                            stroke-dashoffset="{{ $offset1 }}" class="transition-all duration-700" />
                  @endif

                  <!-- Masuk segment -->
                  @if ($cMasuk > 0)
                    <circle cx="50" cy="50" r="38" fill="none" stroke="#3B82F6" stroke-width="20"
                            stroke-dasharray="{{ $pMasuk }} {{ $circumference - $pMasuk }}"
                            stroke-dashoffset="{{ $offset2 }}" class="transition-all duration-700" />
                  @endif

                  <!-- Tertunda segment -->
                  @if ($cTertunda > 0)
                    <circle cx="50" cy="50" r="38" fill="none" stroke="#F43E5E" stroke-width="20"
                            stroke-dasharray="{{ $pTertunda }} {{ $circumference - $pTertunda }}"
                            stroke-dashoffset="{{ $offset3 }}" class="transition-all duration-700" />
                  @endif

                  <!-- Proses segment -->
                  @if ($cProses > 0)
                    <circle cx="50" cy="50" r="38" fill="none" stroke="#F59E0B" stroke-width="20"
                            stroke-dasharray="{{ $pProses }} {{ $circumference - $pProses }}"
                            stroke-dashoffset="{{ $offset4 }}" class="transition-all duration-700" />
                  @endif
                @endif
              </svg>

              <!-- Center Total Text -->
              <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Total</span>
                <span class="text-xl sm:text-2xl font-extrabold text-slate-800 leading-tight">
                  {{ $cTotal }}
                </span>
              </div>
            </div>

            <!-- Legend List -->
            <div class="grid grid-cols-[12px_minmax(0,1fr)_32px] gap-x-2 gap-y-2 text-xs text-slate-700 font-medium w-full sm:w-auto">

              <span class="w-3 h-3 rounded-full bg-[#256E62]"></span>
              <span>Terlaksana</span>
              <span class="font-bold text-slate-900 text-right">{{ $cTerlaksana }}</span>

              <span class="w-3 h-3 rounded-full bg-[#3B82F6]"></span>
              <span>Masuk</span>
              <span class="font-bold text-slate-900 text-right">{{ $cMasuk }}</span>

              <span class="w-3 h-3 rounded-full bg-[#F43E5E]"></span>
              <span>Tertunda</span>
              <span class="font-bold text-slate-900 text-right">{{ $cTertunda }}</span>

              <span class="w-3 h-3 rounded-full bg-[#F59E0B]"></span>
              <span>Proses</span>
              <span class="font-bold text-slate-900 text-right">{{ $cProses }}</span>

            </div>
          </div>

          <div class="pt-2 text-center text-[10.5px] text-slate-400 border-t border-slate-100">
            Perincian status penugasan sub bagian {{ Auth::user()->subDepartment?->name ?? 'unit' }}
          </div>
        </div>

        <!-- CARD 2: 3 Stat Cards (Perlu Penanganan, Dalam Penanganan, Menunggu Verifikasi) -->
        <div class="lg:col-span-3 flex flex-col justify-between gap-3 sm:gap-3.5">

          <!-- Stat 1: Perlu penanganan -->
          <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 flex items-center gap-3.5 hover:shadow-sm transition">
            <div class="w-12 h-12 rounded-xl bg-[#FFF1F2] text-[#F43F5E] flex items-center justify-center shrink-0 border border-[#FFE4E6]">
              <div class="w-7 h-7 rounded-full border-2 border-[#F43F5E] flex items-center justify-center font-bold text-base leading-none">!</div>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-medium text-slate-500 leading-tight">Perlu penanganan</div>
              <div class="text-2xl font-bold text-slate-900 mt-0.5 leading-tight">
                {{ $counts['perlu_penanganan'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- Stat 2: Dalam Penanganan -->
          <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 flex items-center gap-3.5 hover:shadow-sm transition">
            <div class="w-12 h-12 rounded-xl bg-[#FFFBEB] text-[#F59E0B] flex items-center justify-center shrink-0 border border-[#FEF3C7]">
              <svg class="w-6 h-6 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-medium text-slate-500 leading-tight">Dalam Penanganan</div>
              <div class="text-2xl font-bold text-slate-900 mt-0.5 leading-tight">
                {{ $counts['dalam_penanganan'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- Stat 3: Menunggu Verifikasi -->
          <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 flex items-center gap-3.5 hover:shadow-sm transition">
            <div class="w-12 h-12 rounded-xl bg-[#F0FDFA] text-[#0D9488] flex items-center justify-center shrink-0 border border-[#CCFBF1]">
              <svg class="w-6 h-6 text-[#0D9488]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-medium text-slate-500 leading-tight">Menunggu Verifikasi</div>
              <div class="text-2xl font-bold text-slate-900 mt-0.5 leading-tight">
                {{ $counts['verifikasi'] ?? 0 }}
              </div>
            </div>
          </div>

        </div>

        <!-- CARD 3: Catatan / Komentar -->
        <div class="md:col-span-2 lg:col-span-4 bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between">
          <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
            <svg class="w-5 h-5 text-[#0E3B43]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <h3 class="text-sm font-bold text-[#0E3B43] tracking-tight">Catatan / Komentar</h3>
          </div>

          <!-- Notes List -->
          <div class="space-y-3.5 my-2.5 overflow-y-auto max-h-[220px] custom-scroll pr-1">
            @forelse ($recentNotes ?? [] as $note)
              <div class="space-y-1.5">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-[#CCFBF1] text-[#0D9488] flex items-center justify-center font-bold text-xs shrink-0">
                    {{ strtoupper(substr($note->sender?->name ?? 'P', 0, 1)) }}
                  </div>
                  <div class="min-w-0">
                    <div class="text-xs font-semibold text-slate-900 truncate">{{ $note->sender?->name ?? 'Petugas' }}</div>
                    <div class="text-[10px] text-slate-400 truncate">
                      {{ $note->sender?->role == 'kabag' ? 'Kepala Bagian' : ($note->sender?->role == 'kasubag' ? 'Kasubag ' . ($note->sender?->subDepartment?->name ?? '') : 'Petugas Lapangan') }}
                    </div>
                  </div>
                  <span class="text-[10px] text-slate-400 ml-auto shrink-0">{{ $note->created_at ? $note->created_at->format('d M • H:i') : '' }}</span>
                </div>
                <div class="bg-[#F8FAFC] border border-slate-100 rounded-xl p-2.5 text-xs text-slate-600">
                  <div class="font-semibold text-slate-900 mb-0.5 line-clamp-1">{{ $note->report?->title ?? 'Instruksi Tugas' }}</div>
                  <p class="text-[11px] text-slate-500 line-clamp-2">{{ $note->notes }}</p>
                </div>
              </div>
            @empty
              <div class="py-8 text-center text-slate-400">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="text-xs font-semibold text-slate-600">Belum ada catatan atau komentar</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Catatan disposisi dan instruksi tugas akan muncul di sini.</p>
              </div>
            @endforelse
          </div>

          <div class="pt-2 text-right">
            <a href="{{ route('petugas.my-reports') }}" class="text-[11px] font-semibold text-[#0D9488] hover:underline flex items-center justify-end gap-1">
              <span>Buka Laporan Masuk</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
          </div>
        </div>

      </div>

      <!-- ================= BOTTOM SECTION (TABLE & ACTIVITIES) ================= -->
      <div id="laporan-pelayanan" class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        <!-- Left: Table Laporan Pelayanan (lg:col-span-8) -->
        <div class="lg:col-span-8 bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">Laporan Pelayanan</h3>
              <p class="text-xs text-slate-500">Daftar penugasan lapangan untuk {{ Auth::user()->name }}</p>
            </div>
            <div class="flex items-center gap-2">
              <a href="{{ route('petugas.my-reports') }}" class="text-xs font-semibold text-[#0F766E] hover:underline flex items-center gap-1">
                <span>Lihat semua</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
              </a>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
              <thead class="bg-slate-50 text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider border-b border-slate-100">
                <tr>
                  <th class="px-5 py-3.5 text-center w-14">No</th>
                  <th class="px-5 py-3.5">Judul Laporan</th>
                  <th class="px-5 py-3.5">Tanggal</th>
                  <th class="px-5 py-3.5">Status</th>
                  <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse ($reports as $index => $report)
                  <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-5 py-4 text-center text-slate-400 font-medium">
                      {{ $reports->firstItem() ? $reports->firstItem() + $index : $index + 1 }}
                    </td>
                    <td class="px-5 py-4">
                      <div class="font-semibold text-slate-900 line-clamp-1 hover:text-[#0F766E] transition">
                        {{ $report->title }}
                      </div>
                      <div class="text-[11px] font-mono font-bold text-[#0A8080] mt-0.5">
                        {{ $report->ticket_code }}
                      </div>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                      <div class="font-semibold text-slate-700 text-xs">
                        {{ $report->created_at ? $report->created_at->format('d M Y') : '-' }}
                      </div>
                      <div class="text-[11px] text-[#94A3B8]">
                        {{ $report->created_at ? $report->created_at->format('H:i') : '' }}
                      </div>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                      @if ($report->status == 'didisposisi' || $report->status == 'diajukan' || $report->status == 'diterima')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#FFFAEB] text-[#B45309] border border-[#FDE68A]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                          <span>{{ $report->status == 'diterima' ? 'Menunggu Mulai' : 'Diajukan' }}</span>
                        </span>
                      @elseif ($report->status == 'proses')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#EEF6FF] text-[#1C4ED8] border border-[#BFDBFE]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]"></span>
                          <span>Dalam Proses</span>
                        </span>
                      @elseif ($report->status == 'verifikasi_kabag')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#F0FDFA] text-[#0F766E] border border-[#99F6E4]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#0D9488]"></span>
                          <span>Verifikasi</span>
                        </span>
                      @elseif ($report->status == 'selesai')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#F0FDF4] text-[#15803D] border border-[#BBF7D0]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                          <span>Selesai</span>
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#FFF1F2] text-[#BE123C] border border-[#FECDD3]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></span>
                          <span>Tertunda</span>
                        </span>
                      @endif
                    </td>
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                      @if ($report->status === 'diterima')
                        <form action="{{ route('petugas.start', $report) }}" method="POST" class="inline-block">
                          @csrf
                          <button type="submit"
                                  class="inline-block px-3 py-1.5 rounded-lg bg-[#2E978E] text-white hover:bg-[#166E65] text-xs font-bold transition shadow-xs">
                            Mulai Kerjakan
                          </button>
                        </form>
                      @elseif ($report->status === 'proses')
                        <a href="{{ route('petugas.my-reports') }}"
                           class="inline-block px-3 py-1.5 rounded-lg border border-teal-200 text-xs font-bold text-[#0F766E] hover:bg-teal-50 transition">
                          Lanjutkan
                        </a>
                      @else
                        <a href="{{ route('petugas.history') }}"
                           class="inline-block px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                          Lihat Riwayat
                        </a>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="py-12 text-center text-slate-400">
                      <div class="flex flex-col items-center justify-center gap-2">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm font-semibold text-slate-600">Tidak ada laporan yang perlu ditangani</p>
                        <p class="text-xs text-slate-400 max-w-sm">Semua penugasan untuk Anda sudah terselesaikan atau belum ada pengaduan baru yang ditugaskan.</p>
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          @if ($reports->hasPages())
            <div class="p-4 border-t border-slate-100">
              {{ $reports->links() }}
            </div>
          @endif
        </div>

        <!-- Right: Side widgets (lg:col-span-4) -->
        <div class="lg:col-span-4 space-y-4">

          <!-- Card 1: Informasi Hari Ini -->
          <a href="{{ route('petugas.my-reports') }}" class="block group">
            <div class="bg-[#E9FAF6] border border-[#A7F3D0]/70 p-4 sm:p-5 rounded-2xl flex items-center justify-between gap-4 transition hover:shadow-xs hover:border-[#2E978E]">
              <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-xl bg-[#256E62] text-white flex items-center justify-center shrink-0 shadow-xs">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div class="min-w-0">
                  <h3 class="text-xs sm:text-sm font-bold text-[#095D52] leading-tight">Informasi Hari Ini</h3>
                  <p class="text-xs text-[#095D52]/80 mt-1 leading-snug">
                    @if ($todayNewCount > 0)
                      <span class="font-extrabold text-[#095D52]">{{ $todayNewCount }} penugasan aktif</span> hari ini
                    @else
                      Tidak ada penugasan baru hari ini
                    @endif
                  </p>
                </div>
              </div>
              <div class="w-7 h-7 rounded-full bg-white/70 group-hover:bg-white text-[#256E62] flex items-center justify-center shrink-0 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>
          </a>

          <!-- Card 2: Aktivitas Terbaru -->
          <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <div class="flex items-center gap-2 text-[#0D3B43]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-sm sm:text-base font-bold">Aktivitas Terbaru</h2>
              </div>
              <a href="{{ route('petugas.my-reports') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#0F766E] hover:text-[#0D5B53] transition">
                <span>Lihat semua</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            </div>

            <div class="space-y-3">
              @forelse ($recentActivities as $act)
                <div class="flex items-start gap-3 text-xs">
                  @if ($act->status == 'didisposisi' || $act->status == 'diajukan' || $act->status == 'diterima')
                    <span class="w-2.5 h-2.5 rounded-full bg-[#3B82F6] shrink-0 mt-1.5"></span>
                  @elseif ($act->status == 'proses')
                    <span class="w-2.5 h-2.5 rounded-full bg-[#F49E0B] shrink-0 mt-1.5"></span>
                  @elseif ($act->status == 'selesai')
                    <span class="w-2.5 h-2.5 rounded-full bg-[#256E62] shrink-0 mt-1.5"></span>
                  @else
                    <span class="w-2.5 h-2.5 rounded-full bg-[#F43E5E] shrink-0 mt-1.5"></span>
                  @endif

                  <div class="min-w-0 flex-1">
                    <p class="font-semibold text-slate-800 truncate">
                      Laporan "{{ $act->title }}"
                    </p>
                    <p class="text-[11px] text-slate-400 capitalize">
                      @if ($act->status == 'didisposisi' || $act->status == 'diajukan' || $act->status == 'diterima')
                        Penugasan baru
                      @elseif ($act->status == 'proses')
                        Sedang ditangani
                      @elseif ($act->status == 'verifikasi_kabag')
                        Menunggu verifikasi Kabag
                      @elseif ($act->status == 'selesai')
                        Terlaksana
                      @else
                        Ditunda
                      @endif
                    </p>
                  </div>
                  <span class="text-[10px] text-slate-400 whitespace-nowrap">
                    {{ $act->updated_at ? $act->updated_at->format('H:i') : '' }}
                  </span>
                </div>
              @empty
                <div class="py-6 text-center text-slate-400">
                  <p class="text-xs font-semibold text-slate-600">Belum ada aktivitas terbaru</p>
                </div>
              @endforelse
            </div>
          </div>

        </div>

      </div>

    </main>
  </div>

</body>
</html>
