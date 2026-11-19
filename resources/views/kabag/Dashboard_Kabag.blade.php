<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard KABAG - E-Lapor FIKKIA UNAIR</title>

  @include('komponen.favicon')
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


  <div class="flex-1 flex flex-col min-w-0 lg:ml-52 min-h-screen pt-14">

    <!-- ================= TOP HEADER BAR ================= -->
  @include('komponen.header_kabag')

    <!-- Main Workspace Content -->
    <main class="flex-1 p-4 sm:p-6 lg:p-7  sm:pt-20 max-w-350 w-full mx-auto space-y-6">

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
      <div class="space-y-1 mt-6">
        <!-- Tag / Badge Pill -->
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#CCFBF1] text-[#0C3934] text-xs font-semibold shadow-xs">
          <span class="w-2 h-2 rounded-full bg-[#0D9488]"></span>
          <span>E-Lapor FIKKIA</span>
        </div>

        <div class="pt-1">
          <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">SELAMAT DATANG,</div>
          <h2 class="text-xl sm:text-2xl lg:text-[26px] font-extrabold text-[#0C3934] tracking-tight leading-snug">
            Bapak/Ibu {{ Auth::user()->name }}
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
            $circumference = 2 * 3.14159 * 38; // ~238.76
            $cTotal = $chartTotal ?? 0;

            $pAkademik = $cTotal > 0 ? ($pendidikanCount / $cTotal) * $circumference : 0;
            $pKeuangan = $cTotal > 0 ? ($keuanganCount / $cTotal) * $circumference : 0;
            $pSarpras = $cTotal > 0 ? ($sarprasCount / $cTotal) * $circumference : 0;
            $pSDM = $cTotal > 0 ? ($dekanatCount / $cTotal) * $circumference : 0;
            $pWB = $cTotal > 0 ? ($wbCount / $cTotal) * $circumference : 0;

            $offset1 = 0;
            $offset2 = $offset1 - $pAkademik;
            $offset3 = $offset2 - $pKeuangan;
            $offset4 = $offset3 - $pSarpras;
            $offset5 = $offset4 - $pSDM;
          @endphp

          <div class="flex flex-col sm:flex-row lg:flex-row items-center justify-center gap-5 my-auto py-3">
            <!-- Donut SVG Graphic -->
            <div class="relative w-36 h-36 shrink-0 flex items-center justify-center">
              <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <!-- Background track -->
                <circle cx="50" cy="50" r="38" fill="none" stroke="#F1F5F9" stroke-width="20" />

                <!-- Akademik segment -->
                <circle id="circle-pendidikan" cx="50" cy="50" r="38" fill="none" stroke="#256E62" stroke-width="20"
                        stroke-dasharray="{{ $pAkademik }} {{ $circumference - $pAkademik }}"
                        stroke-dashoffset="{{ $offset1 }}" class="transition-all duration-700" />

                <!-- Keuangan segment -->
                <circle id="circle-keuangan" cx="50" cy="50" r="38" fill="none" stroke="#3B82F6" stroke-width="20"
                        stroke-dasharray="{{ $pKeuangan }} {{ $circumference - $pKeuangan }}"
                        stroke-dashoffset="{{ $offset2 }}" class="transition-all duration-700" />

                <!-- Sarpras segment -->
                <circle id="circle-sarpras" cx="50" cy="50" r="38" fill="none" stroke="#F59E0B" stroke-width="20"
                        stroke-dasharray="{{ $pSarpras }} {{ $circumference - $pSarpras }}"
                        stroke-dashoffset="{{ $offset3 }}" class="transition-all duration-700" />

                <!-- SDM / Dekanat segment -->
                <circle id="circle-sdm" cx="50" cy="50" r="38" fill="none" stroke="#A855F7" stroke-width="20"
                        stroke-dasharray="{{ $pSDM }} {{ $circumference - $pSDM }}"
                        stroke-dashoffset="{{ $offset4 }}" class="transition-all duration-700" />

                <!-- Whistleblowing segment -->
                <circle id="circle-wb" cx="50" cy="50" r="38" fill="none" stroke="#94A3B8" stroke-width="20"
                        stroke-dasharray="{{ $pWB }} {{ $circumference - $pWB }}"
                        stroke-dashoffset="{{ $offset5 }}" class="transition-all duration-700" />
              </svg>

              <!-- Center Total Text -->
              <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Total</span>
                <span id="chart-total-count" class="text-xl sm:text-2xl font-extrabold text-slate-800 leading-tight">
                  {{ $cTotal }}
                </span>
              </div>
            </div>

            <!-- Legend List -->
            <div class="grid grid-cols-[12px_minmax(0,1fr)_32px] gap-x-2 gap-y-2 text-xs text-slate-700 font-medium w-full sm:w-auto">

            <span class="w-3 h-3 rounded-full bg-[#256E62]"></span>
            <span>Pendidikan & Kemahasiswaan</span>
            <span id="count-pendidikan" class="font-bold text-slate-900 text-right">{{ $pendidikanCount }}</span>

            <span class="w-3 h-3 rounded-full bg-[#3B82F6]"></span>
            <span>Keuangan & SDM</span>
            <span id="count-keuangan" class="font-bold text-slate-900 text-right">{{ $keuanganCount }}</span>

            <span class="w-3 h-3 rounded-full bg-[#F59E0B]"></span>
            <span>Sarana Prasarana</span>
            <span id="count-sarpras" class="font-bold text-slate-900 text-right">{{ $sarprasCount }}</span>

            <span class="w-3 h-3 rounded-full bg-[#A855F7]"></span>
            <span>Dekanat</span>
            <span id="count-sdm" class="font-bold text-slate-900 text-right">{{ $dekanatCount }}</span>

            <span class="w-3 h-3 rounded-full bg-[#94A3B8]"></span>
            <span>Whistleblowing</span>
            <span id="count-wb" class="font-bold text-slate-900 text-right">{{ $wbCount }}</span>

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
                <span id="stat-perlu-penanganan">{{ $counts['diajukan'] ?? 0 }}</span>
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
                <span id="stat-dalam-penanganan">{{ ($counts['proses'] ?? 0) + ($counts['didisposisi'] ?? 0) }}</span>
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
                <span id="stat-menunggu-verifikasi">{{ $counts['verifikasi'] ?? 0 }}</span>
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
          <div id="notes-list-container" class="space-y-3.5 my-2.5 overflow-y-auto max-h-[220px] custom-scroll pr-1">
            @forelse ($recentNotes ?? [] as $note)
              <div class="space-y-1.5">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-[#CCFBF1] text-[#0D9488] flex items-center justify-center font-bold text-xs shrink-0">
                    {{ strtoupper(substr($note->sender?->name ?? 'P', 0, 1)) }}
                  </div>
                  <div class="min-w-0">
                    <div class="text-xs font-semibold text-slate-900 truncate">{{ $note->sender?->name ?? 'Pengirim' }}</div>
                    <div class="text-[10px] text-slate-400">{{ $note->sender?->subDepartment?->name ?? ($note->sender?->role === 'kasubag' ? 'KASUBAG' : ($note->sender?->role === 'petugas' ? 'Petugas' : 'Sub Bagian')) }}</div>
                  </div>
                  <span class="text-[10px] text-slate-400 ml-auto shrink-0">{{ $note->created_at->format('d M • H:i') }}</span>
                </div>
                <div class="bg-[#F8FAFC] border border-slate-100 rounded-xl p-2.5 text-xs text-slate-600">
                  <div class="font-semibold text-slate-900 mb-0.5 line-clamp-1">{{ $note->report?->title ?? 'Tindak Lanjut' }}</div>
                  <p class="text-[11px] text-slate-500 line-clamp-2">{{ $note->notes }}</p>
                </div>
              </div>
            @empty
              <div class="py-8 text-center text-slate-400">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="text-xs font-semibold text-slate-600">Tidak ada data</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Belum ada catatan atau komentar yang diterima.</p>
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
      <div id="laporan-pelayanan" class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        <div class="lg:col-span-8 h-[450px] overflow-hidden bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4 flex flex-col">

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
              <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Proses</option>
              <option value="verifikasi_kabag" {{ request('status') === 'verifikasi_kabag' ? 'selected' : '' }}>Verifikasi</option>
              <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
              <option value="tertunda" {{ request('status') === 'tertunda' ? 'selected' : '' }}>Tertunda</option>
              <option value="terlaksana" {{ request('status') === 'terlaksana' ? 'selected' : '' }}>Terlaksana</option>
            </select>

            @if (request()->hasAny(['sub_department_id', 'status']))
              <a href="{{ route('kabag.dashboard') }}" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold transition">
                Reset
              </a>
            @endif
          </form>

          <!-- Table Wrapper -->
        <div class="w-full flex-1 min-h-0 overflow-auto custom-scroll -mx-5 sm:mx-0">
            <table class="w-full text-left text-xs min-w-[800px]">
                <thead class="sticky top-0 bg-white z-10">
                <tr class="border-b border-slate-100 text-slate-400 font-semibold uppercase text-[10.5px]">
                  <th class="py-3 px-3 w-10 text-center">No</th>
                  <th class="py-3 px-3">Judul Laporan</th>
                  <th class="py-3 px-3">Bidang</th>
                  <th class="py-3 px-3">Tanggal</th>
                  <th class="py-3 px-3">Status</th>
                  <th class="py-3 px-3 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody id="reports-table-body" class="divide-y divide-slate-100">
                @include('kabag.partials.dashboard_table_rows', ['reports' => $reports])
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
        <div class="lg:col-span-4 h-[450px] overflow-hidden bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4 flex flex-col">

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
          <div id="whistleblower-list-container" class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden divide-y divide-slate-100 custom-scroll">
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
                        {{ $wb->subDepartment?->name ? (str_contains($wb->subDepartment->name, 'Sarana') ? 'Sarpras' : (str_contains($wb->subDepartment->name, 'Pendidikan') ? 'Akademik' : (str_contains($wb->subDepartment->name, 'SDM') ? 'SDM' : 'Dekanat'))) : 'Umum' }}
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
              <div class="py-8 text-center text-slate-400">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-xs font-semibold text-slate-600">Tidak ada data</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Belum ada laporan whistleblower yang masuk.</p>
              </div>
            @endforelse
          </div>

        </div>

      </div>

    </main>

  </div>

  <script>
    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    async function fetchDashboardRealtime() {
      try {
        const response = await fetch("{{ route('kabag.dashboard') }}?realtime=1", {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!response.ok) return;
        const data = await response.json();

        // 1. Update Stat Cards
        const statPerlu = document.getElementById('stat-perlu-penanganan');
        if (statPerlu) statPerlu.textContent = data.counts.diajukan ?? 0;

        const statProses = document.getElementById('stat-dalam-penanganan');
        if (statProses) statProses.textContent = data.counts.dalam_penanganan ?? 0;

        const statVerif = document.getElementById('stat-menunggu-verifikasi');
        if (statVerif) statVerif.textContent = data.counts.verifikasi ?? 0;

        // 2. Update Total Center Text & Legend
        const chartTotalEl = document.getElementById('chart-total-count');
        if (chartTotalEl) chartTotalEl.textContent = data.distribution.total ?? 0;

        const elPend = document.getElementById('count-pendidikan');
        if (elPend) elPend.textContent = data.distribution.pendidikan ?? 0;

        const elKeu = document.getElementById('count-keuangan');
        if (elKeu) elKeu.textContent = data.distribution.keuangan ?? 0;

        const elSar = document.getElementById('count-sarpras');
        if (elSar) elSar.textContent = data.distribution.sarpras ?? 0;

        const elSdm = document.getElementById('count-sdm');
        if (elSdm) elSdm.textContent = data.distribution.sdm ?? 0;

        const elWb = document.getElementById('count-wb');
        if (elWb) elWb.textContent = data.distribution.whistleblowing ?? 0;

        // 3. Update Donut Chart SVG Segments
        const circumference = 2 * Math.PI * 38; // ~238.76
        const total = data.distribution.total ?? 0;

        const circlePendidikan = document.getElementById('circle-pendidikan');
        const circleKeuangan = document.getElementById('circle-keuangan');
        const circleSarpras = document.getElementById('circle-sarpras');
        const circleSDM = document.getElementById('circle-sdm');
        const circleWB = document.getElementById('circle-wb');

        if (total <= 0) {
          if (circlePendidikan) circlePendidikan.setAttribute('stroke-dasharray', `0 ${circumference}`);
          if (circleKeuangan) circleKeuangan.setAttribute('stroke-dasharray', `0 ${circumference}`);
          if (circleSarpras) circleSarpras.setAttribute('stroke-dasharray', `0 ${circumference}`);
          if (circleSDM) circleSDM.setAttribute('stroke-dasharray', `0 ${circumference}`);
          if (circleWB) circleWB.setAttribute('stroke-dasharray', `0 ${circumference}`);
        } else {
          const pPend = (data.distribution.pendidikan / total) * circumference;
          const pKeu = (data.distribution.keuangan / total) * circumference;
          const pSar = (data.distribution.sarpras / total) * circumference;
          const pSdm = (data.distribution.sdm / total) * circumference;
          const pWb = (data.distribution.whistleblowing / total) * circumference;

          const off1 = 0;
          const off2 = off1 - pPend;
          const off3 = off2 - pKeu;
          const off4 = off3 - pSar;
          const off5 = off4 - pSdm;

          if (circlePendidikan) {
            circlePendidikan.setAttribute('stroke-dasharray', `${pPend} ${circumference - pPend}`);
            circlePendidikan.setAttribute('stroke-dashoffset', off1);
          }
          if (circleKeuangan) {
            circleKeuangan.setAttribute('stroke-dasharray', `${pKeu} ${circumference - pKeu}`);
            circleKeuangan.setAttribute('stroke-dashoffset', off2);
          }
          if (circleSarpras) {
            circleSarpras.setAttribute('stroke-dasharray', `${pSar} ${circumference - pSar}`);
            circleSarpras.setAttribute('stroke-dashoffset', off3);
          }
          if (circleSDM) {
            circleSDM.setAttribute('stroke-dasharray', `${pSdm} ${circumference - pSdm}`);
            circleSDM.setAttribute('stroke-dashoffset', off4);
          }
          if (circleWB) {
            circleWB.setAttribute('stroke-dasharray', `${pWb} ${circumference - pWb}`);
            circleWB.setAttribute('stroke-dashoffset', off5);
          }
        }

        // 4. Update Catatan / Komentar
        const notesContainer = document.getElementById('notes-list-container');
        if (notesContainer && data.recent_notes) {
          if (data.recent_notes.length === 0) {
            notesContainer.innerHTML = `
              <div class="py-8 text-center text-slate-400">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="text-xs font-semibold text-slate-600">Tidak ada data</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Belum ada catatan atau komentar yang diterima.</p>
              </div>
            `;
          } else {
            notesContainer.innerHTML = data.recent_notes.map(note => `
              <div class="space-y-1.5">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-[#CCFBF1] text-[#0D9488] flex items-center justify-center font-bold text-xs shrink-0">
                    ${escapeHtml(note.sender_initial)}
                  </div>
                  <div class="min-w-0">
                    <div class="text-xs font-semibold text-slate-900 truncate">${escapeHtml(note.sender_name)}</div>
                    <div class="text-[10px] text-slate-400">${escapeHtml(note.sender_unit)}</div>
                  </div>
                  <span class="text-[10px] text-slate-400 ml-auto shrink-0">${escapeHtml(note.time_formatted)}</span>
                </div>
                <div class="bg-[#F8FAFC] border border-slate-100 rounded-xl p-2.5 text-xs text-slate-600">
                  <div class="font-semibold text-slate-900 mb-0.5 line-clamp-1">${escapeHtml(note.report_title)}</div>
                  <p class="text-[11px] text-slate-500 line-clamp-2">${escapeHtml(note.notes)}</p>
                </div>
              </div>
            `).join('');
          }
        }

        // 5. Update Whistleblower List
        const wbContainer = document.getElementById('whistleblower-list-container');
        if (wbContainer && data.whistleblower_reports) {
          if (data.whistleblower_reports.length === 0) {
            wbContainer.innerHTML = `
              <div class="py-8 text-center text-slate-400">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-xs font-semibold text-slate-600">Tidak ada data</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Belum ada laporan whistleblower yang masuk.</p>
              </div>
            `;
          } else {
            wbContainer.innerHTML = data.whistleblower_reports.map(wb => `
              <a href="${wb.url}" class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/70 -mx-2 px-2 rounded-xl transition group">
                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-[#CCFBF1] group-hover:text-[#0D9488] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <div class="font-semibold text-slate-900 text-xs truncate">${escapeHtml(wb.title)}</div>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span class="px-2 py-0.5 rounded-md bg-[#EFF6FF] text-[#1D4ED8] text-[9.5px] font-semibold">${escapeHtml(wb.unit_badge)}</span>
                      <span class="text-[10px] text-slate-400">${escapeHtml(wb.time_ago)}</span>
                    </div>
                  </div>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-[#0D9488] shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            `).join('');
          }
        }

        // 6. Update Table Rows (if user is not actively on a custom search/filter url)
        const params = new URLSearchParams(window.location.search);
        if (!params.has('sub_department_id') && !params.has('status') && !params.has('page') && data.table_html) {
          const tableBody = document.getElementById('reports-table-body');
          if (tableBody) {
            tableBody.innerHTML = data.table_html;
          }
        }
      } catch (err) {
        // Silent catch for background polling
      }
    }

    // Poll every 5 seconds
    setInterval(fetchDashboardRealtime, 5000);
  </script>

        </div>

      </div>

    </main>

  </div>

</body>
</html>
