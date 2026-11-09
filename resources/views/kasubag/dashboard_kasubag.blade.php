<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard KASUBAG - E-Lapor FIKKIA UNAIR</title>
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
  </style>
</head>
<body class="bg-[#F4F6F9] text-[#0F172A] antialiased min-h-screen selection:bg-[#2E978E] selection:text-white flex flex-col justify-between">

  <!-- ================= SIDEBAR NAVIGATION ================= -->
  @include('komponen.sidebar_Kasubag')

  <!-- ================= TOP HEADER BAR ================= -->
  @include('komponen.header_Kasubag')

  <!-- ================= MAIN WORKSPACE ================= -->
  <div class="flex-1 flex flex-col min-w-0 lg:ml-52 min-h-screen pt-16 sm:pt-20">
    <main class="p-4 sm:p-6 lg:p-8 flex-grow w-full max-w-[1550px] mx-auto space-y-6 sm:space-y-8">

      @if (session('success'))
        <div class="p-4 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#064E3B] text-sm font-semibold flex items-center gap-3 shadow-xs">
          <svg class="w-5 h-5 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      <!-- ================= GREETING & TITLE ================= -->
      <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#CCFAF1] text-[#115E59] text-xs font-bold border border-[#99F6E4]">
          <span class="w-2 h-2 rounded-full bg-[#115E59]"></span>
          <span>E-Lapor FIKKIA</span>
        </div>
        <div>
          <p class="text-xs sm:text-sm font-bold tracking-wider text-[#64738B] uppercase">SELAMAT DATANG,</p>
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0D3B43] tracking-tight leading-tight">
            {{ Auth::user()->name }}
          </h1>
          <p class="text-xs sm:text-sm text-[#64738B] font-medium mt-1">
            Berikut adalah ringkasan laporan terbaru di E-Lapor UNAIR untuk sub bagian {{ Auth::user()->subDepartment?->name ?? 'Anda' }}.
          </p>
        </div>
      </div>

      <!-- ================= CONTENT GRID ================= -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

        <!-- ================= LEFT COLUMN ================= -->
        <div class="lg:col-span-7 xl:col-span-8 space-y-6 sm:space-y-8">

          <!-- ================= DONUT CHART & STATS CARDS ================= -->
          <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

            <!-- Donut Chart: Laporan Per Bidang -->
            <div class="md:col-span-6 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
              <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-sm sm:text-base font-bold text-slate-900">Laporan Per Bidang</h2>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                  {{ Auth::user()->subDepartment?->code ?? 'Unit' }}
                </span>
              </div>

              @php
                $cTerlaksana = $counts['selesai'] ?? 0;
                $cMasuk = $counts['masuk'] ?? 0;
                $cProses = $counts['dikerjakan'] ?? 0;
                $cTertunda = $counts['tertunda'] ?? 0;
                $cTotal = $totalReports ?? ($cTerlaksana + $cMasuk + $cProses + $cTertunda);

                $circumference = 376.99;
                $runningOffset = 0;

                $pctTerlaksana = $cTotal > 0 ? ($cTerlaksana / $cTotal) : 0;
                $strokeTerlaksana = $pctTerlaksana * $circumference;
                $offsetTerlaksana = -$runningOffset;
                $runningOffset += $strokeTerlaksana;

                $pctMasuk = $cTotal > 0 ? ($cMasuk / $cTotal) : 0;
                $strokeMasuk = $pctMasuk * $circumference;
                $offsetMasuk = -$runningOffset;
                $runningOffset += $strokeMasuk;

                $pctTertunda = $cTotal > 0 ? ($cTertunda / $cTotal) : 0;
                $strokeTertunda = $pctTertunda * $circumference;
                $offsetTertunda = -$runningOffset;
                $runningOffset += $strokeTertunda;

                $pctProses = $cTotal > 0 ? ($cProses / $cTotal) : 0;
                $strokeProses = $pctProses * $circumference;
                $offsetProses = -$runningOffset;
              @endphp

              <!-- Diagram Canvas & Legend -->
              <div class="flex flex-col sm:flex-row items-center justify-center gap-6 py-4">
                <!-- SVG Donut Chart -->
                <div class="relative w-44 h-44 flex items-center justify-center shrink-0">
                  <svg class="w-full h-full transform -rotate-90" viewBox="0 0 160 160">
                    @if ($cTotal == 0)
                      <!-- Empty Placeholder Ring -->
                      <circle cx="80" cy="80" r="60" fill="none" stroke="#E2E8F0" stroke-width="16" />
                    @else
                      <!-- Background Ring -->
                      <circle cx="80" cy="80" r="60" fill="none" stroke="#F1F5F9" stroke-width="16" />

                      <!-- 1. Terlaksana (Teal) -->
                      @if ($cTerlaksana > 0)
                        <circle cx="80" cy="80" r="60" fill="none" stroke="#256E62" stroke-width="16"
                                stroke-dasharray="{{ number_format($strokeTerlaksana, 2, '.', '') }} {{ number_format($circumference, 2, '.', '') }}"
                                stroke-dashoffset="{{ number_format($offsetTerlaksana, 2, '.', '') }}" />
                      @endif

                      <!-- 2. Masuk (Blue) -->
                      @if ($cMasuk > 0)
                        <circle cx="80" cy="80" r="60" fill="none" stroke="#3B82F6" stroke-width="16"
                                stroke-dasharray="{{ number_format($strokeMasuk, 2, '.', '') }} {{ number_format($circumference, 2, '.', '') }}"
                                stroke-dashoffset="{{ number_format($offsetMasuk, 2, '.', '') }}" />
                      @endif

                      <!-- 3. Tertunda (Rose) -->
                      @if ($cTertunda > 0)
                        <circle cx="80" cy="80" r="60" fill="none" stroke="#F43E5E" stroke-width="16"
                                stroke-dasharray="{{ number_format($strokeTertunda, 2, '.', '') }} {{ number_format($circumference, 2, '.', '') }}"
                                stroke-dashoffset="{{ number_format($offsetTertunda, 2, '.', '') }}" />
                      @endif

                      <!-- 4. Proses (Amber) -->
                      @if ($cProses > 0)
                        <circle cx="80" cy="80" r="60" fill="none" stroke="#F49E0B" stroke-width="16"
                                stroke-dasharray="{{ number_format($strokeProses, 2, '.', '') }} {{ number_format($circumference, 2, '.', '') }}"
                                stroke-dashoffset="{{ number_format($offsetProses, 2, '.', '') }}" />
                      @endif
                    @endif
                  </svg>

                  <!-- Center Text -->
                  <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <span class="text-[11px] font-semibold text-[#94A3B8] uppercase tracking-wider">Total</span>
                    <span class="text-3xl font-black text-[#1E293B] leading-tight">{{ $cTotal }}</span>
                  </div>
                </div>

                <!-- Legend Items -->
                <div class="space-y-2.5 text-xs font-medium text-[#0F172A] w-full sm:w-auto">
                  <div class="flex items-center justify-between sm:justify-start gap-3">
                    <div class="flex items-center gap-2">
                      <span class="w-3 h-3 rounded-full bg-[#256E62] shrink-0"></span>
                      <span>Terlaksana</span>
                    </div>
                    <span class="font-bold text-slate-700 ml-auto">{{ $cTerlaksana }}</span>
                  </div>
                  <div class="flex items-center justify-between sm:justify-start gap-3">
                    <div class="flex items-center gap-2">
                      <span class="w-3 h-3 rounded-full bg-[#3B82F6] shrink-0"></span>
                      <span>Masuk</span>
                    </div>
                    <span class="font-bold text-slate-700 ml-auto">{{ $cMasuk }}</span>
                  </div>
                  <div class="flex items-center justify-between sm:justify-start gap-3">
                    <div class="flex items-center gap-2">
                      <span class="w-3 h-3 rounded-full bg-[#F43E5E] shrink-0"></span>
                      <span>Tertunda</span>
                    </div>
                    <span class="font-bold text-slate-700 ml-auto">{{ $cTertunda }}</span>
                  </div>
                  <div class="flex items-center justify-between sm:justify-start gap-3">
                    <div class="flex items-center gap-2">
                      <span class="w-3 h-3 rounded-full bg-[#F49E0B] shrink-0"></span>
                      <span>Proses</span>
                    </div>
                    <span class="font-bold text-slate-700 ml-auto">{{ $cProses }}</span>
                  </div>
                </div>
              </div>

              @if ($cTotal == 0)
                <p class="text-center text-xs text-slate-400 mt-2">Belum ada data laporan untuk divisualisasikan</p>
              @endif
            </div>

            <!-- 3 Stat Cards Stacked -->
            <div class="md:col-span-6 flex flex-col justify-between gap-4">

              <!-- Card 1: Perlu Penanganan -->
              <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-4">
                <div>
                  <p class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] leading-tight">
                    {{ $counts['perlu_penanganan'] ?? 0 }}
                  </p>
                  <p class="text-xs sm:text-sm font-medium text-[#64738B] mt-1">Perlu penangganan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#FFF1F1] flex items-center justify-center shrink-0">
                  <div class="w-7 h-7 rounded-full bg-[#FFE4E6] flex items-center justify-center text-[#F43E5E] font-black text-sm">
                    !
                  </div>
                </div>
              </div>

              <!-- Card 2: Dalam Penanganan -->
              <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-4">
                <div>
                  <p class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] leading-tight">
                    {{ $counts['dalam_penanganan'] ?? 0 }}
                  </p>
                  <p class="text-xs sm:text-sm font-medium text-[#64738B] mt-1">Dalam Penangganan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#FFFAEB] flex items-center justify-center shrink-0 text-[#F59E0B]">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>

              <!-- Card 3: Menunggu Verifikasi -->
              <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-4">
                <div>
                  <p class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] leading-tight">
                    {{ $counts['verifikasi'] ?? 0 }}
                  </p>
                  <p class="text-xs sm:text-sm font-medium text-[#64738B] mt-1">Menunggu Verifikasi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#F0FCFA] flex items-center justify-center shrink-0 text-[#0D9488]">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>

            </div>

          </div>

          <!-- ================= RECENT REPORTS TABLE ================= -->
          <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 flex items-center justify-between border-b border-slate-100">
              <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">Laporan Per Bidang</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar pengaduan terbaru untuk {{ Auth::user()->subDepartment?->name ?? 'Sub Bagian Unit' }}</p>
              </div>
              <a href="{{ route('kasubag.status') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#0F766E] hover:text-[#0D5B53] transition">
                <span>Lihat semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
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
                        @if ($report->status == 'didisposisi' || $report->status == 'diajukan')
                          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#FFFAEB] text-[#B45309] border border-[#FDE68A]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                            <span>Diajukan</span>
                          </span>
                        @elseif ($report->status == 'proses' || $report->status == 'diterima')
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
                        <a href="{{ route('kasubag.show', $report) }}"
                           class="inline-block px-3 py-1.5 rounded-lg border border-teal-200 text-xs font-bold text-[#0F766E] hover:bg-teal-50 transition">
                          Lihat Detail
                        </a>
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
                          <p class="text-xs text-slate-400 max-w-sm">Semua laporan pada sub bagian ini sudah terselesaikan atau belum ada pengaduan baru.</p>
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

        </div>

        <!-- ================= RIGHT COLUMN ================= -->
        <div class="lg:col-span-5 xl:col-span-4 space-y-6 sm:space-y-8">

          <!-- Card 1: Catatan / Komentar -->
          <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
              <div class="w-8 h-8 rounded-lg bg-[#CCFAF1] text-[#0D3B43] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-[#0D3B43]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
              </div>
              <h2 class="text-sm sm:text-base font-bold text-[#0D3B43]">Catatan / Komentar</h2>
            </div>

            <div class="space-y-3.5">
              @forelse ($recentNotes as $note)
                <div class="space-y-2 p-3.5 rounded-xl bg-[#F8FAFC] border border-slate-100">
                  <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                      <div class="w-7 h-7 rounded-full bg-[#CCFAF1] text-[#0D3B43] flex items-center justify-center font-bold text-xs shrink-0">
                        {{ strtoupper(substr($note->sender?->name ?? 'U', 0, 1)) }}
                      </div>
                      <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $note->sender?->name ?? 'Petugas' }}</p>
                        <p class="text-[10px] text-slate-400 truncate">
                          {{ $note->sender?->role == 'kabag' ? 'Kepala Bagian' : ($note->sender?->role == 'kasubag' ? 'Kasubag ' . ($note->sender?->subDepartment?->name ?? '') : 'Petugas Lapangan') }}
                        </p>
                      </div>
                    </div>
                    <span class="text-[10px] text-slate-400 whitespace-nowrap">
                      {{ $note->created_at ? $note->created_at->format('d M • H:i') : '' }}
                    </span>
                  </div>

                  <div class="pt-1.5 border-t border-slate-200/60">
                    <p class="text-[11px] font-bold text-slate-900 truncate">
                      {{ $note->report?->title ?? 'Laporan Pelayanan' }}
                    </p>
                    <p class="text-xs text-slate-600 line-clamp-2 mt-0.5 leading-relaxed">
                      {{ $note->notes }}
                    </p>
                  </div>
                </div>
              @empty
                <div class="py-8 text-center text-slate-400">
                  <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                  <p class="text-xs font-semibold text-slate-600">Belum ada catatan atau komentar</p>
                  <p class="text-[11px] text-slate-400 mt-0.5">Catatan disposisi dan penyelesaian laporan akan muncul di sini.</p>
                </div>
              @endforelse
            </div>
          </div>

          <!-- Card 2: Informasi Hari Ini -->
          <a href="{{ route('kasubag.status') }}" class="block group">
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
                      <span class="font-extrabold text-[#095D52]">{{ $todayNewCount }} laporan baru</span> masuk ke bidang anda
                    @else
                      Tidak ada laporan baru masuk hari ini
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

          <!-- Card 3: Aktivitas Terbaru -->
          <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <div class="flex items-center gap-2 text-[#0D3B43]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-sm sm:text-base font-bold">Aktivitas Terbaru</h2>
              </div>
              <a href="{{ route('kasubag.status') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#0F766E] hover:text-[#0D5B53] transition">
                <span>Lihat semua</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            </div>

            <div class="space-y-3">
              @forelse ($recentActivities as $act)
                <div class="flex items-start gap-3 text-xs">
                  @if ($act->status == 'didisposisi' || $act->status == 'diajukan')
                    <span class="w-2.5 h-2.5 rounded-full bg-[#3B82F6] shrink-0 mt-1.5"></span>
                  @elseif ($act->status == 'proses' || $act->status == 'diterima')
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
                      @if ($act->status == 'didisposisi' || $act->status == 'diajukan')
                        Laporan masuk
                      @elseif ($act->status == 'proses' || $act->status == 'diterima')
                        Sedang ditangani
                      @elseif ($act->status == 'verifikasi_kabag')
                        Menunggu verifikasi Kabag
                      @elseif ($act->status == 'selesai')
                        Terlaksana oleh petugas
                      @else
                        Ditunda oleh petugas
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
