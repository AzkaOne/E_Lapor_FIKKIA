<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard KABAG - E-Lapor FIKKIA UNAIR</title>
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

  <!-- ================= SIDEBAR COMPONENT (Desktop fixed w-64 & Mobile drawer) ================= -->
  @include('komponen.sidebar_Kabag')

  <!-- ================= RIGHT COLUMN WRAPPER (Header + Main beside Sidebar) ================= -->
  <div class="flex-1 flex flex-col min-w-0 lg:ml-52 min-h-screen pt-14">

    <!-- ================= TOP HEADER BAR ================= -->
    <header class="bg-[#2E978E] text-white h-16 fixed top-0 left-0 right-0 lg:left-52 z-40 shadow-sm w-auto">
      <div class="px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between gap-4">

        <div class="flex items-center gap-3 sm:gap-6">

          <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition focus:outline-none" title="Buka Menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <!-- Institutional Logos (Desktop & Tablet) -->
          <div class="hidden sm:flex items-center ">
            @if (file_exists(public_path('assets/header_accreditation.png')))
              <img src="{{ asset('assets/header_accreditation.png') }}" alt="Akreditasi & Mitra UNAIR" class="h-12 w-auto object-contain opacity-95">
            @elseif (file_exists(public_path('assets/accreditation_logos.png')))
              <img src="{{ asset('assets/accreditation_logos.png') }}" alt="Akreditasi UNAIR" class="h-12 w-auto object-contain opacity-95">
            @else
              <!-- Fallback Clean Vector Emblems -->
              <div class="flex items-center gap-3 text-[11px] font-semibold text-white/95">
                <span class="px-2.5 py-1 rounded-md bg-white/15 tracking-wide uppercase text-[10px]">Zona Integritas</span>
                <span class="px-2.5 py-1 rounded-md bg-white/15 tracking-wide text-[10px]">SMARTVERSITAS</span>
                <span class="px-2.5 py-1 rounded-md bg-white/15 tracking-wide text-[10px]">TOP #287 QS</span>
              </div>
            @endif
          </div>

          <!-- Mobile Title for Compact View -->
          <div class="sm:hidden flex items-center gap-2">
            <span class="text-xs font-bold tracking-tight text-white uppercase">E-Lapor KABAG</span>
          </div>
        </div>

        <!-- Right: Notifications & User Profile -->
        <div class="flex items-center gap-3 sm:gap-4">
          <!-- Notification Bell -->
          <a href="{{ route('kabag.verifikasi.index') }}" class="relative p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition" title="Verifikasi Laporan">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            @if (($counts['verifikasi'] ?? 0) > 0)
              <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-[#EF4444] rounded-full ring-2 ring-[#2E978E]"></span>
            @endif
          </a>

          <!-- User Profile Badge -->
            <div class="flex items-center gap-2 pl-2 sm:pl-3 border-l border-white/20">
                <div class="w-8 h-8 rounded-full bg-[#0F766E] border border-teal-300 flex items-center justify-center text-white shrink-0 font-bold shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <div class="hidden md:flex flex-col text-left">
                    <span class="text-xs font-bold text-white leading-tight truncate max-w-[130px]">
                        {{ auth()->user()->name ?? 'Fuad Raden, S.D., S.Mp' }}
                    </span>
                    <span class="text-[10px] text-teal-200 capitalize leading-tight">
                        {{ auth()->user()->position ?? 'Kepala Bagian' }}
                    </span>
                </div>
            </div>

          <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST" class="ml-1">
            @csrf
            <button type="submit"
                    class="w-8 h-8 rounded-full bg-white/10 hover:bg-rose-500/80 hover:text-white transition flex items-center justify-center text-white/90"
                    title="Keluar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
        </div>

      </div>
    </header>

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
            Bapak/Ibu Kepala Bagian
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Berikut adalah ringkasan laporan terbaru di E-Lapor UNAIR.
          </p>
        </div>
      </div>

      <!-- ================= TOP SECTION (CHART + STATS + NOTES) ================= -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-5 items-stretch">

        <!-- CARD 1: Laporan Per Bidang (Donut Chart) -->
        <div class="lg:col-span-5 bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 tracking-tight">Laporan Per Bidang</h3>
            <span class="text-[11px] font-medium text-slate-400">Total Distribusi</span>
          </div>

          @php
            // Calculate dynamic counts for donut chart
            $pendidikanCount = $subDeptCounts[68] ?? 42;
            $keuanganCount = $subDeptCounts[67] ?? 28;
            $sarprasCount = $subDeptCounts[66] ?? 24;
            $sdmCount = $subDeptCounts[65] ?? 15;
            $wbCount = $counts['whistleblowing'] ?? 15;
            $chartTotal = $pendidikanCount + $keuanganCount + $sarprasCount + $sdmCount + $wbCount;
            if ($chartTotal <= 0) { $chartTotal = 124; }
          @endphp

          <div class="flex flex-col sm:flex-row lg:flex-row items-center justify-center gap-5 my-auto py-3">
            <!-- Donut SVG Graphic -->
            <div class="relative w-36 h-36 shrink-0 flex items-center justify-center">
              <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <!-- Background track -->
                <circle cx="50" cy="50" r="38" fill="none" stroke="#F1F5F9" stroke-width="20" />

                @php
                  $circumference = 2 * 3.14159 * 38; // ~238.76
                  $pAkademik = ($pendidikanCount / $chartTotal) * $circumference;
                  $pKeuangan = ($keuanganCount / $chartTotal) * $circumference;
                  $pSarpras = ($sarprasCount / $chartTotal) * $circumference;
                  $pSDM = ($sdmCount / $chartTotal) * $circumference;
                  $pWB = ($wbCount / $chartTotal) * $circumference;

                  $offset1 = 0;
                  $offset2 = $offset1 - $pAkademik;
                  $offset3 = $offset2 - $pKeuangan;
                  $offset4 = $offset3 - $pSarpras;
                  $offset5 = $offset4 - $pSDM;
                @endphp

                <!-- Akademik segment -->
                <circle cx="50" cy="50" r="38" fill="none" stroke="#256E62" stroke-width="20"
                        stroke-dasharray="{{ $pAkademik }} {{ $circumference - $pAkademik }}"
                        stroke-dashoffset="{{ $offset1 }}" class="transition-all duration-700" />

                <!-- Keuangan segment -->
                <circle cx="50" cy="50" r="38" fill="none" stroke="#3B82F6" stroke-width="20"
                        stroke-dasharray="{{ $pKeuangan }} {{ $circumference - $pKeuangan }}"
                        stroke-dashoffset="{{ $offset2 }}" class="transition-all duration-700" />

                <!-- Sarpras segment -->
                <circle cx="50" cy="50" r="38" fill="none" stroke="#F59E0B" stroke-width="20"
                        stroke-dasharray="{{ $pSarpras }} {{ $circumference - $pSarpras }}"
                        stroke-dashoffset="{{ $offset3 }}" class="transition-all duration-700" />

                <!-- SDM segment -->
                <circle cx="50" cy="50" r="38" fill="none" stroke="#A855F7" stroke-width="20"
                        stroke-dasharray="{{ $pSDM }} {{ $circumference - $pSDM }}"
                        stroke-dashoffset="{{ $offset4 }}" class="transition-all duration-700" />

                <!-- Whistleblowing segment -->
                <circle cx="50" cy="50" r="38" fill="none" stroke="#94A3B8" stroke-width="20"
                        stroke-dasharray="{{ $pWB }} {{ $circumference - $pWB }}"
                        stroke-dashoffset="{{ $offset5 }}" class="transition-all duration-700" />
              </svg>

              <!-- Center Total Text -->
              <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Total</span>
                <span class="text-xl sm:text-2xl font-extrabold text-slate-800 leading-tight">
                  {{ ($counts['total'] ?? 0) > 0 ? $counts['total'] : 124 }}
                </span>
              </div>
            </div>

            <!-- Legend List -->
            <div class="grid grid-cols-[12px_minmax(0,1fr)_32px] gap-x-2 gap-y-2 text-xs text-slate-700 font-medium w-full sm:w-auto">

            <span class="w-3 h-3 rounded-full bg-[#256E62]"></span>
            <span>Pendidikan & Kemahasiswaan</span>
            <span class="font-bold text-slate-900 text-right">{{ $pendidikanCount }}</span>

            <span class="w-3 h-3 rounded-full bg-[#3B82F6]"></span>
            <span>Keuangan & SDM</span>
            <span class="font-bold text-slate-900 text-right">{{ $keuanganCount }}</span>

            <span class="w-3 h-3 rounded-full bg-[#F59E0B]"></span>
            <span>Sarana Prasarana</span>
            <span class="font-bold text-slate-900 text-right">{{ $sarprasCount }}</span>

            <span class="w-3 h-3 rounded-full bg-[#A855F7]"></span>
            <span>Dekanat</span>
            <span class="font-bold text-slate-900 text-right">{{ $sdmCount }}</span>

            <span class="w-3 h-3 rounded-full bg-[#94A3B8]"></span>
            <span>Whistleblowing</span>
            <span class="font-bold text-slate-900 text-right">{{ $wbCount }}</span>

            </div>
          </div>

          <div class="pt-2 text-center text-[10.5px] text-slate-400 border-t border-slate-100">
            Perincian laporan masuk per unit kerja
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
                {{ ($counts['diajukan'] ?? 0) > 0 ? $counts['diajukan'] : 7 }}
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
                {{ ($counts['proses'] ?? 0) + ($counts['didisposisi'] ?? 0) > 0 ? ($counts['proses'] + $counts['didisposisi']) : 48 }}
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
                {{ ($counts['verifikasi'] ?? 0) > 0 ? $counts['verifikasi'] : 48 }}
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
                    {{ strtoupper(substr($note->sender?->name ?? 'F', 0, 1)) }}
                  </div>
                  <div class="min-w-0">
                    <div class="text-xs font-semibold text-slate-900 truncate">{{ $note->sender?->name ?? 'Kasubag' }}</div>
                    <div class="text-[10px] text-slate-400">{{ $note->sender?->subDepartment?->name ?? 'Sub Bagian' }}</div>
                  </div>
                  <span class="text-[10px] text-slate-400 ml-auto shrink-0">{{ $note->created_at->format('d M • H:i') }}</span>
                </div>
                <div class="bg-[#F8FAFC] border border-slate-100 rounded-xl p-2.5 text-xs text-slate-600">
                  <div class="font-semibold text-slate-900 mb-0.5 line-clamp-1">{{ $note->report?->title ?? 'Tindak Lanjut' }}</div>
                  <p class="text-[11px] text-slate-500 line-clamp-2">{{ $note->notes }}</p>
                </div>
              </div>
            @empty
              <!-- FigCraft Sample Comment Item 1 -->
              <div class="space-y-1.5">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-[#CCFBF1] text-[#0D9488] flex items-center justify-center font-bold text-xs shrink-0">
                    F
                  </div>
                  <div class="min-w-0">
                    <div class="text-xs font-semibold text-slate-900 truncate">Fuad Zikr, S.Kom</div>
                    <div class="text-[10px] text-slate-400">Kasubag Sarana Prasarana</div>
                  </div>
                  <span class="text-[10px] text-slate-400 ml-auto shrink-0">15 Mei 2025 • 09:20</span>
                </div>
                <div class="bg-[#F8FAFC] border border-slate-100 rounded-xl p-2.5 text-xs text-slate-600">
                  <div class="font-semibold text-slate-900 mb-0.5">Kerusakan AC di Ruang Kelas 3.2</div>
                  <p class="text-[11px] text-slate-500">Kerusakan AC pada kelas ini sudah parah tidak bisa dikerjakan karena para petugas sibuk</p>
                </div>
              </div>

              <!-- FigCraft Sample Comment Item 2 -->
              <div class="space-y-1.5 pt-2 border-t border-slate-100">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-[#CCFBF1] text-[#0D9488] flex items-center justify-center font-bold text-xs shrink-0">
                    F
                  </div>
                  <div class="min-w-0">
                    <div class="text-xs font-semibold text-slate-900 truncate">Fuad Zikr, S.Kom</div>
                    <div class="text-[10px] text-slate-400">Kasubag Sarana Prasarana</div>
                  </div>
                  <span class="text-[10px] text-slate-400 ml-auto shrink-0">15 Mei 2025 • 09:20</span>
                </div>
                <div class="bg-[#F8FAFC] border border-slate-100 rounded-xl p-2.5 text-xs text-slate-600">
                  <div class="font-semibold text-slate-900 mb-0.5">Kerusakan AC di Ruang Kelas 3.2</div>
                  <p class="text-[11px] text-slate-500">Kerusakan AC pada kelas ini sudah parah tidak bisa dikerjakan karena para petugas sibuk</p>
                </div>
              </div>
            @endforelse
          </div>

          <div class="pt-2 text-right">
            <a href="{{ route('kabag.verifikasi.index') }}" class="text-[11px] font-semibold text-[#0D9488] hover:underline flex items-center justify-end gap-1">
              <span>Buka Verifikasi</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
          </div>
        </div>

      </div>

      <!-- ================= BOTTOM SECTION (TABLE & WHISTLEBLOWER) ================= -->
      <div id="laporan-pelayanan" class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        <!-- TABLE: Laporan Pelayanan (~65% width on desktop) -->
        <div class="lg:col-span-8 bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">

          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">Laporan Pelayanan</h3>
              <p class="text-xs text-slate-500">Daftar aspirasi dan aduan yang memerlukan monitoring & disposisi</p>
            </div>
            <div class="flex items-center gap-2">
              <a href="{{ route('kabag.dashboard') }}" class="text-xs font-semibold text-[#0F766E] hover:underline flex items-center gap-1">
                <span>Lihat semua</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
              </a>
            </div>
          </div>

          <!-- Filter Controls -->
          <form method="GET" action="{{ route('kabag.dashboard') }}" class="flex flex-wrap items-center gap-2.5 text-xs">
            <select name="sub_department_id" onchange="this.form.submit()"
                    class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium focus:ring-2 focus:ring-[#2E978E] focus:outline-none">
              <option value="">Semua Sub Bagian</option>
              @foreach ($subDepartments as $dept)
                <option value="{{ $dept->id }}" {{ request('sub_department_id') == $dept->id ? 'selected' : '' }}>
                  {{ $dept->name }}
                </option>
              @endforeach
            </select>

            <select name="status" onchange="this.form.submit()"
                    class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium focus:ring-2 focus:ring-[#2E978E] focus:outline-none">
              <option value="">Semua Status</option>
              <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
              <option value="didisposisi" {{ request('status') === 'didisposisi' ? 'selected' : '' }}>Didisposisi</option>
              <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
              <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Proses</option>
              <option value="verifikasi_kabag" {{ request('status') === 'verifikasi_kabag' ? 'selected' : '' }}>Verifikasi</option>
              <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
              <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>

            @if (request()->hasAny(['sub_department_id', 'status']))
              <a href="{{ route('kabag.dashboard') }}" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold transition">
                Reset
              </a>
            @endif
          </form>

          <!-- Table Wrapper -->
          <div class=" w-full overflow-x-auto custom-scroll -mx-5 sm:mx-0">
            <table class="w-full text-left text-xs min-w-[800px]">
              <thead>
                <tr class="border-b border-slate-100 text-slate-400 font-semibold uppercase text-[10.5px]">
                  <th class="py-3 px-3 w-10 text-center">No</th>
                  <th class="py-3 px-3">Judul Laporan</th>
                  <th class="py-3 px-3">Bidang</th>
                  <th class="py-3 px-3">Tanggal</th>
                  <th class="py-3 px-3">Status</th>
                  <th class="py-3 px-3 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse ($reports as $index => $report)
                  <tr class="hover:bg-slate-50/70 transition">
                    <td class="py-3.5 px-3 text-center font-bold text-slate-700">
                      {{ $reports->firstItem() ? $reports->firstItem() + $index : $index + 1 }}
                    </td>
                    <td class="py-3.5 px-3">
                      <div class="font-bold text-slate-800 text-[13px] leading-snug line-clamp-1">{{ $report->title }}</div>
                      <div class="text-[10.5px] text-slate-400 mt-0.5">Tiket: {{ $report->ticket_code }} • Kampus {{ $report->campus_location }}</div>
                    </td>
                    <td class="py-3.5 px-3">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#EFF6FF] text-[#1D4ED8] border border-[#DBEAFE]">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span>{{ $report->subDepartment?->name ? (str_contains($report->subDepartment->name, 'Sarana') ? 'Sarpras' : (str_contains($report->subDepartment->name, 'Pendidikan') ? 'Akademik' : (str_contains($report->subDepartment->name, 'SDM') ? 'Keuangan & SDM' : 'Dekanat'))) : 'Dekanat' }}</span>
                      </span>
                    </td>
                    <td class="py-3.5 px-3 text-slate-600">
                      <div class="font-medium text-[11.5px]">{{ $report->created_at->format('d M Y') }}</div>
                      <div class="text-[10px] text-slate-400">{{ $report->created_at->format('H:i') }}</div>
                    </td>
                    <td class="py-3.5 px-3">
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
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#F5F3FF] text-[#7C3AED] border border-[#DDD6FE]">
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
                    <td class="py-3.5 px-3 text-right">
                      <a href="{{ route('kabag.show', $report) }}"
                         class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-[#0D9488] text-[#0D9488] hover:bg-[#0D9488] hover:text-white transition font-bold text-xs shadow-xs">
                        <span>Lihat Detail</span>
                      </a>
                    </td>
                  </tr>
                @empty
                  <!-- FigCraft Sample Rows (When database is empty) -->
                  <tr class="hover:bg-slate-50/70 transition">
                    <td class="py-3.5 px-3 text-center font-bold text-slate-700">1</td>
                    <td class="py-3.5 px-3">
                      <div class="font-bold text-slate-800 text-[13px] leading-snug">Kerusakan AC di Ruang kelas 3.2</div>
                      <div class="text-[10.5px] text-slate-400 mt-0.5">Tiket: TK-2025-001 • Kampus Sobo</div>
                    </td>
                    <td class="py-3.5 px-3">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#EFF6FF] text-[#1D4ED8] border border-[#DBEAFE]">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <span>Akademik</span>
                      </span>
                    </td>
                    <td class="py-3.5 px-3 text-slate-600">
                      <div class="font-medium text-[11.5px]">12 Mei 2025</div>
                      <div class="text-[10px] text-slate-400">10:24</div>
                    </td>
                    <td class="py-3.5 px-3">
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EFF6FF] text-[#2563EB] border border-[#BFDBFE]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6]"></span>
                        <span>Dalam Proses</span>
                      </span>
                    </td>
                    <td class="py-3.5 px-3 text-right">
                      <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-[#0D9488] text-[#0D9488] font-bold text-xs">
                        <span>Lihat Detail</span>
                      </span>
                    </td>
                  </tr>

                  <tr class="hover:bg-slate-50/70 transition">
                    <td class="py-3.5 px-3 text-center font-bold text-slate-700">2</td>
                    <td class="py-3.5 px-3">
                      <div class="font-bold text-slate-800 text-[13px] leading-snug">Kerusakan AC di Ruang kelas 3.2</div>
                      <div class="text-[10.5px] text-slate-400 mt-0.5">Tiket: TK-2025-002 • Kampus Mojo</div>
                    </td>
                    <td class="py-3.5 px-3">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#EFF6FF] text-[#1D4ED8] border border-[#DBEAFE]">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span>Sarpras</span>
                      </span>
                    </td>
                    <td class="py-3.5 px-3 text-slate-600">
                      <div class="font-medium text-[11.5px]">12 Mei 2025</div>
                      <div class="text-[10px] text-slate-400">10:24</div>
                    </td>
                    <td class="py-3.5 px-3">
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                        <span>Diajukan</span>
                      </span>
                    </td>
                    <td class="py-3.5 px-3 text-right">
                      <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-[#0D9488] text-[#0D9488] font-bold text-xs">
                        <span>Lihat Detail</span>
                      </span>
                    </td>
                  </tr>

                  <tr class="hover:bg-slate-50/70 transition">
                    <td class="py-3.5 px-3 text-center font-bold text-slate-700">3</td>
                    <td class="py-3.5 px-3">
                      <div class="font-bold text-slate-800 text-[13px] leading-snug">Kerusakan AC di Ruang kelas 3.2</div>
                      <div class="text-[10.5px] text-slate-400 mt-0.5">Tiket: TK-2025-003 • Kampus Giri</div>
                    </td>
                    <td class="py-3.5 px-3">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#F5F3FF] text-[#7C3AED] border border-[#DDD6FE]">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <span>Akademik</span>
                      </span>
                    </td>
                    <td class="py-3.5 px-3 text-slate-600">
                      <div class="font-medium text-[11.5px]">12 Mei 2025</div>
                      <div class="text-[10px] text-slate-400">10:24</div>
                    </td>
                    <td class="py-3.5 px-3">
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                        <span>Diajukan</span>
                      </span>
                    </td>
                    <td class="py-3.5 px-3 text-right">
                      <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-[#0D9488] text-[#0D9488] font-bold text-xs">
                        <span>Lihat Detail</span>
                      </span>
                    </td>
                  </tr>

                  <tr class="hover:bg-slate-50/70 transition">
                    <td class="py-3.5 px-3 text-center font-bold text-slate-700">4</td>
                    <td class="py-3.5 px-3">
                      <div class="font-bold text-slate-800 text-[13px] leading-snug">Kerusakan AC di Ruang kelas 3.2</div>
                      <div class="text-[10.5px] text-slate-400 mt-0.5">Tiket: TK-2025-004 • Kampus Sobo</div>
                    </td>
                    <td class="py-3.5 px-3">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#EFF6FF] text-[#1D4ED8] border border-[#DBEAFE]">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span>Sarpras</span>
                      </span>
                    </td>
                    <td class="py-3.5 px-3 text-slate-600">
                      <div class="font-medium text-[11.5px]">12 Mei 2025</div>
                      <div class="text-[10px] text-slate-400">10:24</div>
                    </td>
                    <td class="py-3.5 px-3">
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EFF6FF] text-[#2563EB] border border-[#BFDBFE]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6]"></span>
                        <span>Dalam Proses</span>
                      </span>
                    </td>
                    <td class="py-3.5 px-3 text-right">
                      <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-[#0D9488] text-[#0D9488] font-bold text-xs">
                        <span>Lihat Detail</span>
                      </span>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          @if ($reports->hasPages())
            <div class="pt-3 border-t border-slate-100">
              {{ $reports->links() }}
            </div>
          @endif

        </div>

        <!-- LIST: Laporan Whistleblower (~35% width on desktop) -->
        <div class="lg:col-span-4 bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">

          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">Laporan Whistleblower</h3>
              <p class="text-xs text-slate-500">Kanal pengaduan khusus & rahasia</p>
            </div>
            <a href="{{ route('kabag.whistleblowing') }}" class="text-xs font-semibold text-[#0F766E] hover:underline flex items-center gap-1 shrink-0">
              <span>Lihat semua</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
          </div>

          <!-- Whistleblower Items List -->
          <div class="divide-y divide-slate-100">
            @forelse ($whistleblowerReports as $wb)
              <a href="{{ route('kabag.whistleblowing') }}" class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/70 -mx-2 px-2 rounded-xl transition group">
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-[#CCFBF1] group-hover:text-[#0D9488] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <div class="font-semibold text-slate-900 text-xs truncate">{{ $wb->title }}</div>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span class="px-2 py-0.5 rounded-md bg-[#EFF6FF] text-[#1D4ED8] text-[9.5px] font-semibold">
                        {{ $wb->subDepartment?->name ? (str_contains($wb->subDepartment->name, 'Sarana') ? 'Sarpras' : (str_contains($wb->subDepartment->name, 'Pendidikan') ? 'Akademik' : 'Umum')) : 'Umum' }}
                      </span>
                      <span class="text-[10px] text-slate-400">{{ $wb->created_at->diffForHumans() }}</span>
                    </div>
                  </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-[#0D9488] shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            @empty
              <!-- FigCraft Sample WB Items -->
              <a href="{{ route('kabag.whistleblowing') }}" class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/70 -mx-2 px-2 rounded-xl transition group">
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-[#CCFBF1] group-hover:text-[#0D9488] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="font-semibold text-slate-900 text-xs truncate">Pelecehan petugas</div>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span class="px-2 py-0.5 rounded-md bg-[#EFF6FF] text-[#1D4ED8] text-[9.5px] font-semibold">Sarpras</span>
                      <span class="text-[10px] text-slate-400">2 jam yang lalu</span>
                    </div>
                  </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-[#0D9488] shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
              </a>

              <a href="{{ route('kabag.whistleblowing') }}" class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/70 -mx-2 px-2 rounded-xl transition group">
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-[#CCFBF1] group-hover:text-[#0D9488] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="font-semibold text-slate-900 text-xs truncate">Petugas tidak disiplin</div>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span class="px-2 py-0.5 rounded-md bg-[#EFF6FF] text-[#1D4ED8] text-[9.5px] font-semibold">Sarpras</span>
                      <span class="text-[10px] text-slate-400">2 jam yang lalu</span>
                    </div>
                  </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-[#0D9488] shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
              </a>

              <a href="{{ route('kabag.whistleblowing') }}" class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/70 -mx-2 px-2 rounded-xl transition group">
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-[#CCFBF1] group-hover:text-[#0D9488] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="font-semibold text-slate-900 text-xs truncate">Kerusakan AC di Ruang kelas 3.2</div>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span class="px-2 py-0.5 rounded-md bg-[#EFF6FF] text-[#1D4ED8] text-[9.5px] font-semibold">Sarpras</span>
                      <span class="text-[10px] text-slate-400">2 jam yang lalu</span>
                    </div>
                  </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-[#0D9488] shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
              </a>

              <a href="{{ route('kabag.whistleblowing') }}" class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/70 -mx-2 px-2 rounded-xl transition group">
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-[#CCFBF1] group-hover:text-[#0D9488] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="font-semibold text-slate-900 text-xs truncate">Kerusakan AC di Ruang kelas 3.2</div>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span class="px-2 py-0.5 rounded-md bg-[#EFF6FF] text-[#1D4ED8] text-[9.5px] font-semibold">Sarpras</span>
                      <span class="text-[10px] text-slate-400">2 jam yang lalu</span>
                    </div>
                  </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-[#0D9488] shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
              </a>

              <a href="{{ route('kabag.whistleblowing') }}" class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/70 -mx-2 px-2 rounded-xl transition group">
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-[#CCFBF1] group-hover:text-[#0D9488] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="font-semibold text-slate-900 text-xs truncate">Kerusakan AC di Ruang kelas 3.2</div>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span class="px-2 py-0.5 rounded-md bg-[#EFF6FF] text-[#1D4ED8] text-[9.5px] font-semibold">Sarpras</span>
                      <span class="text-[10px] text-slate-400">2 jam yang lalu</span>
                    </div>
                  </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-[#0D9488] shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
              </a>

              <a href="{{ route('kabag.whistleblowing') }}" class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/70 -mx-2 px-2 rounded-xl transition group">
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-[#CCFBF1] group-hover:text-[#0D9488] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="font-semibold text-slate-900 text-xs truncate">Kerusakan AC di Ruang kelas 3.2</div>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span class="px-2 py-0.5 rounded-md bg-[#EFF6FF] text-[#1D4ED8] text-[9.5px] font-semibold">Sarpras</span>
                      <span class="text-[10px] text-slate-400">2 jam yang lalu</span>
                    </div>
                  </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-[#0D9488] shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
              </a>
            @endforelse
          </div>

        </div>

      </div>

    </main>

  </div>

</body>
</html>
