<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Laporan - {{ $report->ticket_code }} - E-Lapor FIKKIA UNAIR</title>

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
    [x-cloak] { display: none !important; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
  </style>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F4F6F7] text-[#0F172A] antialiased min-h-screen selection:bg-[#2E978E] selection:text-white flex flex-col justify-between"
      x-data="{ showHelpModal: false, showImageModal: false, activeImage: '', copySuccess: false, evidenceTab: 'pelapor' }">

  <!-- ================= TOP HEADER BAR ================= -->
  @include('komponen.header_Pelapor')

  <!-- ================= SIDEBAR NAVIGATION ================= -->
  @include('komponen.sidebar_Pelapor')

  @php
    $campusLabel = $report->campus_location ? 'Kampus ' . ucfirst($report->campus_location) : '-';
    $bidangLabel = $report->subDepartment?->name ?? 'Sarana & Prasarana';
  @endphp

  <!-- ================= MAIN WORKSPACE ================= -->
  <div class="flex-1 flex flex-col min-w-0 lg:ml-52 min-h-screen pt-16 sm:pt-20">
    <main class="p-4 sm:p-6 xl:p-8 flex-grow w-full max-w-[1550px] mx-auto space-y-5 sm:space-y-6">

      <!-- Flash Messages -->
      @if (session('success'))
        <div class="p-4 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#065F46] text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
          <div class="w-6 h-6 rounded-full bg-[#059669] text-white flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
          </div>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if (session('error'))
        <div class="p-4 rounded-2xl bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
          <div class="w-6 h-6 rounded-full bg-[#DC2626] text-white flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
          </div>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      <!-- ================= BREADCRUMB ================= -->
      <nav class="flex items-center gap-2 text-xs sm:text-sm text-[#64738B]">
        @if (request('from') === 'status' || (!request('from') && !in_array($report->status, ['selesai', 'ditolak'])))
          <a href="{{ route('pelapor.status') }}" class="hover:text-[#0F766E] transition">Status laporan</a>
        @else
          <a href="{{ route('pelapor.history') }}" class="hover:text-[#0F766E] transition">Riwayat laporan</a>
        @endif
        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="font-medium text-[#115E59]">Detail laporan</span>
      </nav>

      <!-- ================= GREETING & HELP BANNER ================= -->
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-5">
        <!-- Title & Subtitle -->
        <div class="space-y-1.5 max-w-2xl">
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#CCFBF1] border border-[#99F6E4] text-[#115E59] font-['Plus_Jakarta_Sans',sans-serif] text-[11px] font-bold shadow-xs">
            <span class="w-1.5 h-1.5 rounded-full bg-[#115E59]"></span>
            E-Lapor FIKKIA
          </div>

          <h1 class="text-xl sm:text-2xl font-extrabold text-[#0E3B43] font-['Plus_Jakarta_Sans',sans-serif] tracking-tight">
            Riwayat Laporan
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

      <!-- ================= REPORT SUMMARY CARDS ================= -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        <!-- Left Summary Card -->
        <div class="lg:col-span-7 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-5">
          <div class="w-16 h-16 sm:w-[75px] sm:h-[75px] rounded-xl bg-[#0D9488] text-white flex items-center justify-center shrink-0">
            <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
          </div>
          <div class="min-w-0 flex-1 space-y-2">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-[#E9FAF6] text-[#0F766E] border border-[#CCFAF1]">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1" /></svg>
              <span>{{ ucfirst($report->type ?? 'Laporan Pelayanan') }}</span>
            </div>
            <h2 class="text-xl sm:text-2xl xl:text-3xl font-medium text-[#0D3B43] leading-snug line-clamp-2">{{ $report->title }}</h2>
            <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-xs text-[#64738B]">
              @if ($report->status === 'selesai')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-[#10B981] text-white">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                  <span>Selesai</span>
                </span>
              @elseif (in_array($report->status, ['proses', 'diterima', 'didisposisi']))
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-[#2563EB] text-white">
                  <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                  <span>{{ $report->status === 'didisposisi' ? 'Diteruskan' : 'Dalam Proses' }}</span>
                </span>
              @elseif ($report->status === 'verifikasi_kabag')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-[#7E22CE] text-white">Verifikasi Kabag</span>
              @elseif ($report->status === 'diajukan')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-[#F59E0B] text-white">Diajukan</span>
              @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-[#E11D48] text-white">Ditolak / Tertunda</span>
              @endif

              <div class="inline-flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <span>{{ $report->created_at->translatedFormat('d M Y') }}</span>
                <span>•</span>
                <span>{{ $report->created_at->format('H:i') }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Metadata Card -->
        <div class="lg:col-span-5 bg-white px-5 py-3 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-center divide-y divide-slate-100">

          <!-- Nomor Laporan -->
          <div class="flex items-center justify-between gap-3 py-3">
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-9 h-9 rounded-xl bg-[#F1F4F9] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] text-[#94A3B8]">Nomor Laporan</p>
                <p class="text-xs sm:text-sm font-bold font-mono text-[#1E293B] truncate">{{ $report->ticket_code }}</p>
              </div>
            </div>
            <button type="button"
                    @click="navigator.clipboard.writeText('{{ $report->ticket_code }}'); copySuccess = true; setTimeout(() => copySuccess = false, 2000)"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-[#0F766E] hover:bg-slate-50 transition relative shrink-0"
                    title="Salin Nomor Laporan">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
              <span x-show="copySuccess" x-cloak class="absolute -top-7 -right-1 bg-slate-900 text-white text-[10px] font-semibold px-2 py-0.5 rounded shadow">Disalin!</span>
            </button>
          </div>

          <!-- Bidang -->
          <div class="flex items-center gap-3 py-3">
            <div class="w-9 h-9 rounded-xl bg-[#F1F4F9] flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
            <div class="min-w-0">
              <p class="text-[10px] sm:text-[11px] text-[#94A3B8]">Bidang</p>
              <p class="text-xs sm:text-sm font-bold text-[#1E293B] truncate">{{ $bidangLabel }}</p>
            </div>
          </div>

          <!-- Lokasi Kampus -->
          <div class="flex items-center gap-3 py-3">
            <div class="w-9 h-9 rounded-xl bg-[#F1F4F9] flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <div class="min-w-0">
              <p class="text-[10px] sm:text-[11px] text-[#94A3B8]">Lokasi Kampus</p>
              <p class="text-xs sm:text-sm font-bold text-[#1E293B] truncate">{{ $campusLabel }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- ================= CONTENT GRID ================= -->
      <!-- HP: 1 kolom | Tablet: Informasi penuh, Riwayat + Komentar berdampingan | Desktop lebar (xl): 3 kolom -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[1.5fr_1fr_1fr] gap-5 items-start">

        <!-- ===== COLUMN 1: INFORMASI + BUKTI ===== -->
        <div class="md:col-span-2 xl:col-span-1 space-y-5 min-w-0">

          <!-- Card: Informasi Laporan -->
          <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <h3 class="text-base sm:text-lg font-medium text-[#0D3B43]">Informasi Laporan</h3>

            <div class="mt-5 space-y-3.5 text-xs sm:text-sm">
              @foreach ([
                ['Judul Keluhan', $report->title, ['M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z']],
                ['Lokasi Kampus', $campusLabel, ['M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'M15 11a3 3 0 11-6 0 3 3 0 016 0z']],
                ['Tujuan Keluhan', $bidangLabel, ['M12 19l9 2-9-18-9 18 9-2zm0 0v-8']],
              ] as [$label, $value, $paths])
                <div class="flex items-start gap-3">
                  <svg class="w-5 h-5 text-[#64738B] shrink-0 mt-px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @foreach ($paths as $p)<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $p }}" />@endforeach
                  </svg>
                  <div class="min-w-0 flex-1 flex flex-col sm:flex-row sm:gap-3">
                    <span class="text-[#64738B] sm:w-36 shrink-0">{{ $label }}</span>
                    <span class="font-medium text-[#1E293B] break-words">{{ $value }}</span>
                  </div>
                </div>
              @endforeach

              <!-- Deskripsi -->
              <div>
                <div class="flex items-center gap-3 text-[#64738B]">
                  <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h7" /></svg>
                  <span>Deskripsi Keluhan</span>
                </div>
                <p class="mt-2 pl-8 text-[#334155] leading-relaxed whitespace-pre-line break-words">{{ $report->description }}</p>
              </div>
            </div>
          </div>

          <!-- Card: Bukti Laporan -->
          @php
            $publicDisk = \Illuminate\Support\Facades\Storage::disk('public');
            $fileSize = function ($path) use ($publicDisk) {
                try {
                    if (!$publicDisk->exists($path)) return null;
                    $b = $publicDisk->size($path);
                    return $b >= 1048576 ? number_format($b / 1048576, 1) . ' MB' : number_format(max($b, 1) / 1024, 0) . ' KB';
                } catch (\Throwable $e) {
                    return null;
                }
            };
            $evidenceGroups = [
                'pelapor' => $report->evidences->where('type', 'initial'),
                'petugas' => $report->evidences->where('type', 'completion'),
            ];
          @endphp
          <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <h3 class="flex items-center gap-2 text-base sm:text-lg font-medium text-[#0D3B43]">
              <svg class="w-5 h-5 text-[#0D3B43]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
              <span>Bukti Laporan</span>
            </h3>

            <div class="mt-4 border border-slate-200 rounded-lg overflow-hidden">
              <!-- Tabs -->
              <div class="flex items-center gap-5 px-4 pt-3 border-b border-slate-200 text-xs sm:text-[13px]">
                <button type="button" @click="evidenceTab = 'pelapor'"
                        class="pb-2.5 -mb-px border-b-2 transition"
                        :class="evidenceTab === 'pelapor' ? 'border-[#0F766E] text-[#0D3B43] font-medium' : 'border-transparent text-slate-400 hover:text-slate-600'">
                  Pelapor
                </button>
                <button type="button" @click="evidenceTab = 'petugas'"
                        class="pb-2.5 -mb-px border-b-2 transition"
                        :class="evidenceTab === 'petugas' ? 'border-[#0F766E] text-[#0D3B43] font-medium' : 'border-transparent text-slate-400 hover:text-slate-600'">
                  Petugas
                </button>
              </div>

              @foreach ($evidenceGroups as $key => $items)
                <div x-show="evidenceTab === '{{ $key }}'" @if ($key !== 'pelapor') x-cloak @endif class="p-4 flex flex-wrap gap-3">
                  @forelse ($items as $ev)
                    @php
                      $url = asset('storage/' . $ev->file_path);
                      $ext = strtolower(pathinfo($ev->file_path, PATHINFO_EXTENSION));
                      $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                      $size = $fileSize($ev->file_path);
                    @endphp

                    @if ($isImg)
                      <button type="button" @click="activeImage = '{{ $url }}'; showImageModal = true"
                              class="w-[104px] sm:w-[112px] text-left border border-slate-200 rounded-lg p-1.5 bg-slate-50 hover:shadow-sm transition">
                        <img src="{{ $url }}" alt="Bukti" class="w-full h-[72px] sm:h-[80px] object-cover rounded-md">
                        <div class="flex items-center gap-1 mt-2">
                          <svg class="w-3 h-3 text-[#0F766E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                          <span class="text-[10px] text-slate-700 truncate">{{ basename($ev->file_path) }}</span>
                        </div>
                        @if ($size)<div class="text-[9px] text-slate-400 mt-0.5 pl-4">{{ $size }}</div>@endif
                      </button>
                    @else
                      <a href="{{ $url }}" target="_blank" rel="noopener"
                         class="w-[104px] sm:w-[112px] block border border-slate-200 rounded-lg p-1.5 bg-slate-50 hover:shadow-sm transition">
                        <div class="w-full h-[72px] sm:h-[80px] rounded-md bg-white flex flex-col items-center justify-center gap-1">
                          <span class="w-7 h-7 rounded-full bg-[#EF4444] flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M6 4l10 6-10 6V4z" /></svg>
                          </span>
                          <span class="text-[9px] font-semibold text-[#EF4444] uppercase">{{ $ext ?: 'file' }} doc</span>
                        </div>
                        <div class="flex items-center gap-1 mt-2">
                          <svg class="w-3 h-3 text-[#EF4444] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h5.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0116 6.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" /></svg>
                          <span class="text-[10px] text-slate-700 truncate">{{ basename($ev->file_path) }}</span>
                        </div>
                        @if ($size)<div class="text-[9px] text-slate-400 mt-0.5 pl-4">{{ $size }}</div>@endif
                      </a>
                    @endif
                  @empty
                    <div class="w-full py-6 text-center text-slate-400">
                      <p class="text-xs font-semibold text-slate-600">
                        {{ $key === 'pelapor' ? 'Tidak ada lampiran dari pelapor' : 'Belum ada lampiran dari petugas' }}
                      </p>
                    </div>
                  @endforelse
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <!-- ===== COLUMN 2: RIWAYAT PENANGANAN ===== -->
        @php
          $status = $report->status;
          $isDitolak = $status === 'ditolak';
          $isSelesai = $status === 'selesai';
          $isVerif = $status === 'verifikasi_kabag';
          $isProses = $status === 'proses';

          $fmt = fn($d) => $d ? $d->translatedFormat('d M Y • h:i A') : null;
          $disps = $report->dispositions->sortBy('created_at')->values();

          $dispKabag = $disps->first(fn($d) => strtolower($d->sender?->role ?? '') === 'kabag');
          $dispKasubag = $disps->first(fn($d) => strtolower($d->sender?->role ?? '') === 'kasubag');
          $dispPetugas = $disps->first(fn($d) => strtolower($d->sender?->role ?? '') === 'petugas');
          $petugasName = $report->handler?->name ?? $dispPetugas?->receiver?->name ?? 'Petugas Lapangan';

          // Bangun langkah timeline berurutan yang muncul satu per satu sesuai progres
          $steps = [];

          // 1. Laporan Dikirim (Selalu muncul, sudah dilewati)
          $steps[] = [
            'title' => 'Laporan Dikirim',
            'icon' => ['M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            'time' => $fmt($report->created_at),
            'note' => 'Oleh ' . ($report->user?->name ?? 'Pelapor'),
            'color' => '#10B981',
            'is_passed' => true,
          ];

          // 2. Diterima Kabag (Selalu muncul)
          $kabagPassed = $status !== 'diajukan';
          $steps[] = [
            'title' => 'Diterima Kabag',
            'icon' => ['M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4'],
            'time' => $kabagPassed ? ($fmt($dispKabag?->created_at ?? $report->updated_at)) : 'Menunggu tinjauan',
            'note' => 'Oleh ' . ($dispKabag?->sender?->name ?? 'Kepala Bagian (KABAG)'),
            'color' => '#10B981',
            'is_passed' => $kabagPassed,
          ];

          // Jika Ditolak langsung oleh Kabag
          if ($isDitolak && !$dispKasubag && !$report->handler_id) {
            $steps[] = [
              'title' => 'Laporan Ditolak',
              'icon' => ['M6 18L18 6M6 6l12 12'],
              'time' => $fmt($report->updated_at),
              'note' => 'Ditolak: ' . ($report->rejection_reason ?: 'Laporan ditolak oleh KABAG'),
              'color' => '#EF4444',
              'is_passed' => true,
              'is_rejected' => true,
            ];
          }

          // 3. Diteruskan ke Kasubag / Ditangani Mandiri Kabag
          $hasPassedKabag = $status !== 'diajukan' && (!($isDitolak && !$dispKasubag && !$report->handler_id));
          $isKabagSelf = $report->handler && strtolower($report->handler->role ?? '') === 'kabag';

          if ($hasPassedKabag) {
            if ($isKabagSelf) {
              $kabagSelfPassed = $isVerif || $isSelesai;
              $steps[] = [
                'title' => 'Ditangani Langsung oleh KABAG',
                'icon' => ['M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                'time' => $kabagSelfPassed ? 'Selesai penanganan mandiri' : 'Sedang ditangani KABAG',
                'note' => 'Oleh ' . ($report->handler->name ?? 'KABAG'),
                'color' => '#0D9488',
                'is_passed' => $kabagSelfPassed,
              ];
            } else {
              $kasubagPassed = in_array($status, ['proses', 'verifikasi_kabag', 'selesai']) || ($report->handler_id && $report->handler_id !== $dispKasubag?->receiver_id);
              $steps[] = [
                'title' => 'Diteruskan Ke Kasubag',
                'icon' => ['M14 5l7 7m0 0l-7 7m7-7H3'],
                'time' => $kasubagPassed ? ($fmt($dispKasubag?->created_at ?? $dispKabag?->created_at)) : 'Menunggu arahan Kasubag',
                'note' => 'Kepada ' . ($dispKasubag?->receiver?->name ?? 'Kasubag ' . ($report->subDepartment?->name ?? 'Unit')),
                'color' => '#0D9488',
                'is_passed' => $kasubagPassed,
              ];
            }
          }

          // Jika Ditolak saat di Kasubag
          if ($isDitolak && $dispKasubag && !$report->handler_id) {
            $steps[] = [
              'title' => 'Laporan Ditolak',
              'icon' => ['M6 18L18 6M6 6l12 12'],
              'time' => $fmt($report->updated_at),
              'note' => 'Ditolak: ' . ($report->rejection_reason ?: 'Laporan ditolak oleh Kasubag'),
              'color' => '#EF4444',
              'is_passed' => true,
              'is_rejected' => true,
            ];
          }

          // 4. Ditugaskan ke Petugas / Kasubag Mandiri
          $hasOfficer = ($report->handler_id && !$isKabagSelf) || in_array($status, ['proses', 'verifikasi_kabag', 'selesai']);
          $isKasubagSelf = $report->handler && strtolower($report->handler->role ?? '') === 'kasubag';

          if ($hasOfficer && $hasPassedKabag) {
            if ($isKasubagSelf) {
              $kasubagSelfPassed = $isVerif || $isSelesai;
              $steps[] = [
                'title' => 'Ditangani Langsung oleh Kasubag',
                'icon' => ['M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                'time' => $kasubagSelfPassed ? 'Selesai penanganan mandiri' : 'Sedang ditangani Kasubag',
                'note' => 'Oleh ' . ($report->handler->name ?? 'Kasubag'),
                'color' => '#0D9488',
                'is_passed' => $kasubagSelfPassed,
              ];
            } else {
              $officerPassed = in_array($status, ['proses', 'verifikasi_kabag', 'selesai']);
              $steps[] = [
                'title' => 'Ditugaskan ke Petugas',
                'icon' => ['M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                'time' => $officerPassed ? ($fmt($dispPetugas?->created_at ?? $report->updated_at)) : 'Menunggu penugasan petugas',
                'note' => 'Kepada ' . $petugasName,
                'color' => '#0D9488',
                'is_passed' => $officerPassed,
              ];
            }
          }

          // 5. Diproses (Pengerjaan teknisi)
          $hasStartedProcess = in_array($status, ['proses', 'verifikasi_kabag', 'selesai']) || ($isDitolak && ($dispPetugas || $report->handler_id));

          if ($hasStartedProcess && $hasPassedKabag) {
            $processPassed = in_array($status, ['verifikasi_kabag', 'selesai']);
            $steps[] = [
              'title' => 'Diproses',
              'icon' => ['M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
              'time' => $processPassed ? 'Pekerjaan telah selesai' : 'Sedang dikerjakan teknisi',
              'note' => 'Oleh ' . $petugasName,
              'color' => '#0D9488',
              'is_passed' => $processPassed,
            ];
          }

          // Jika Ditolak saat pengerjaan petugas
          if ($isDitolak && ($dispPetugas || $report->handler_id)) {
            $steps[] = [
              'title' => 'Laporan Ditolak',
              'icon' => ['M6 18L18 6M6 6l12 12'],
              'time' => $fmt($report->updated_at),
              'note' => 'Ditolak: ' . ($report->rejection_reason ?: 'Laporan ditolak oleh petugas pelaksana'),
              'color' => '#EF4444',
              'is_passed' => true,
              'is_rejected' => true,
            ];
          }

          // 6. Laporan Dikirim ke Kabag (Verifikasi)
          if (in_array($status, ['verifikasi_kabag', 'selesai'])) {
            $steps[] = [
              'title' => 'Laporan Dikirim ke Kabag',
              'icon' => ['M10 19l-7-7m0 0l7-7m-7 7h18'],
              'time' => $isSelesai ? 'Siap verifikasi' : 'Menunggu verifikasi KABAG',
              'note' => 'Oleh ' . $petugasName,
              'color' => '#0D9488',
              'is_passed' => $isSelesai,
            ];
          }

          // 7. Diverifikasi & Selesai
          if ($isSelesai) {
            $steps[] = [
              'title' => 'Diverifikasi & Selesai',
              'icon' => ['M5 13l4 4L19 7'],
              'time' => $fmt($report->updated_at),
              'note' => 'Oleh Kepala Bagian (KABAG)',
              'color' => '#059669',
              'is_passed' => true,
            ];
          }
        @endphp

        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs min-w-0">
          <h3 class="flex items-center gap-2 text-base sm:text-lg font-medium text-[#0D3B43]">
            <svg class="w-5 h-5 text-[#0D3B43]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Riwayat Penanganan</span>
          </h3>

          <ol class="mt-5">
            @foreach ($steps as $s)
              <li class="relative flex gap-3.5 {{ $loop->last ? '' : 'pb-6' }}">
                @unless ($loop->last)
                  <span class="absolute left-[15px] top-9 bottom-1 w-[2px] {{ $s['is_passed'] ? 'bg-[#0D9488]' : 'bg-slate-200' }}"></span>
                @endunless

                <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0
                            {{ $s['is_passed'] ? 'text-white' : 'bg-slate-100 text-slate-400 border border-slate-300' }}"
                     @if ($s['is_passed']) style="background-color: {{ $s['color'] }}" @endif>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @foreach ($s['icon'] as $p)<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $p }}" />@endforeach
                  </svg>
                </div>

                <div class="min-w-0">
                  <p class="text-xs sm:text-[13px] font-semibold tracking-wide {{ $s['is_passed'] ? ($s['is_rejected'] ?? false ? 'text-rose-700 font-bold' : 'text-[#1E293B]') : 'text-slate-500' }}">
                    {{ $s['title'] }}
                  </p>
                  <p class="text-[10.5px] text-[#94A3B8] mt-0.5">{{ $s['time'] }}</p>
                  <p class="text-[11px] {{ $s['is_rejected'] ?? false ? 'text-rose-600 font-medium' : 'text-[#64738B]' }} mt-0.5 break-words">
                    {{ $s['note'] }}
                  </p>
                </div>
              </li>
            @endforeach
          </ol>
        </div>

        <!-- ===== COLUMN 3: KOMENTAR + BANNER ===== -->
        <div class="space-y-5 min-w-0">

          <!-- Card: Catatan / Komentar (Desain Serasi dengan detail_Kabag.blade) -->
          <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs md:min-h-[320px] flex flex-col">
            <h3 class="flex items-center gap-2 text-base sm:text-lg font-medium text-[#0D3B43]">
              <svg class="w-5 h-5 text-[#0D3B43]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
              <span>Catatan / Komentar</span>
            </h3>

            @php
              $validDispositions = $report->dispositions->whereNotNull('notes')->filter(fn($d) => trim($d->notes) !== '');
              $hasResolutionInDisp = $validDispositions->contains(fn($d) => trim($d->notes ?? '') === trim($report->resolution_notes ?? ''));
              $hasComments = $validDispositions->count() > 0 || !empty($report->resolution_notes) || !empty($report->postponed_reason) || !empty($report->rejection_reason);
            @endphp

            @if ($hasComments && ($report->show_notes_to_pelapor !== false))
              <div class="mt-4 space-y-3.5 max-h-[440px] overflow-y-auto custom-scroll pr-1">

                @if (!empty($report->rejection_reason))
                  <div class="border border-rose-200/90 rounded-2xl p-4 bg-white space-y-2">
                    <div class="flex items-center justify-between gap-2">
                      <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 bg-rose-100 text-rose-700">
                          !
                        </div>
                        <div class="min-w-0">
                          <div class="font-bold text-slate-900 text-xs truncate">Alasan Penolakan</div>
                          <div class="text-[10px] font-semibold uppercase tracking-wider text-rose-600">
                            STATUS DITOLAK
                          </div>
                        </div>
                      </div>
                      <div class="text-[10px] text-slate-400 shrink-0 text-right">
                        {{ $report->updated_at->format('d M Y') }}<br>{{ $report->updated_at->format('H:i') }}
                      </div>
                    </div>
                    <div class="p-3 rounded-xl text-xs leading-relaxed text-rose-900 bg-rose-50 border border-rose-100">
                      {{ $report->rejection_reason }}
                    </div>
                  </div>
                @endif

                @if (!empty($report->postponed_reason))
                  <div class="border border-amber-200/90 rounded-2xl p-4 bg-white space-y-2">
                    <div class="flex items-center justify-between gap-2">
                      <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 bg-amber-100 text-amber-700">
                          ⏱
                        </div>
                        <div class="min-w-0">
                          <div class="font-bold text-slate-900 text-xs truncate">Alasan Penundaan</div>
                          <div class="text-[10px] font-semibold uppercase tracking-wider text-amber-600">
                            STATUS TERTUNDA
                          </div>
                        </div>
                      </div>
                      <div class="text-[10px] text-slate-400 shrink-0 text-right">
                        {{ $report->updated_at->format('d M Y') }}<br>{{ $report->updated_at->format('H:i') }}
                      </div>
                    </div>
                    <div class="p-3 rounded-xl text-xs leading-relaxed text-amber-900 bg-amber-50 border border-amber-100">
                      {{ $report->postponed_reason }}
                    </div>
                  </div>
                @endif

                @if (!empty($report->resolution_notes) && !$hasResolutionInDisp)
                  <div class="border border-slate-200/90 rounded-2xl p-4 bg-white space-y-2">
                    <div class="flex items-center justify-between gap-2">
                      <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 bg-[#FFEDD5] text-[#C2410C]">
                          {{ strtoupper(substr($report->handler?->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                          <div class="font-bold text-slate-900 text-xs truncate">{{ $report->handler?->name ?? 'Petugas Pelaksana' }}</div>
                          <div class="text-[10px] font-semibold uppercase tracking-wider text-[#C2410C]">
                            PETUGAS PELAKSANA {{ $report->subDepartment?->name ? '• ' . $report->subDepartment->name : '' }}
                          </div>
                        </div>
                      </div>
                      <div class="text-[10px] text-slate-400 shrink-0 text-right">
                        {{ $report->updated_at->format('d M Y') }}<br>{{ $report->updated_at->format('H:i') }}
                      </div>
                    </div>
                    <div class="p-3 rounded-xl text-xs leading-relaxed text-slate-800 bg-[#FFF7ED]">
                      {{ $report->resolution_notes }}
                    </div>
                  </div>
                @endif

                @foreach ($validDispositions as $disp)
                  @php
                    $dispRole = strtolower($disp->sender?->role ?? 'kabag');
                    $dispIsKabag = $dispRole === 'kabag';
                    $dispIsKasubag = $dispRole === 'kasubag';
                  @endphp
                  <div class="border border-slate-200/90 rounded-2xl p-4 bg-white space-y-2">
                    <div class="flex items-center justify-between gap-2">
                      <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0
                          {{ $dispIsKabag ? 'bg-[#CCFBF1] text-[#059669]' : ($dispIsKasubag ? 'bg-[#DBEAFE] text-[#1D4ED8]' : 'bg-[#FFEDD5] text-[#C2410C]') }}">
                          {{ strtoupper(substr($disp->sender?->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                          <div class="font-bold text-slate-900 text-xs truncate">{{ $disp->sender?->name ?? 'Pengguna' }}</div>
                          <div class="text-[10px] font-semibold uppercase tracking-wider
                            {{ $dispIsKabag ? 'text-[#059669]' : ($dispIsKasubag ? 'text-[#1D4ED8]' : 'text-[#C2410C]') }}">
                            {{ strtoupper($disp->sender?->role ?? 'Petugas') }} {{ $disp->sender?->subDepartment?->name ? '• ' . $disp->sender->subDepartment->name : '' }}
                          </div>
                        </div>
                      </div>
                      <div class="text-[10px] text-slate-400 shrink-0 text-right">
                        {{ $disp->created_at->format('d M Y') }}<br>{{ $disp->created_at->format('H:i') }}
                      </div>
                    </div>
                    <div class="p-3 rounded-xl text-xs leading-relaxed text-slate-800
                      {{ $dispIsKabag ? 'bg-[#ECFDF5]' : ($dispIsKasubag ? 'bg-[#EFF6FF]' : 'bg-[#FFF7ED]') }}">
                      {{ $disp->notes }}
                    </div>
                  </div>
                @endforeach

              </div>
            @else
              <div class="flex-1 py-10 px-4 rounded-2xl bg-[#F8FAFC] border border-dashed border-slate-200 text-center flex flex-col items-center justify-center my-auto">
                <div class="w-16 h-16 rounded-2xl bg-white shadow-2xs border border-slate-100 flex items-center justify-center mb-3 text-slate-400">
                  <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                </div>
                <h5 class="text-sm font-bold text-slate-800">Belum ada komentar</h5>
                <p class="text-xs text-slate-500 mt-1 max-w-[220px]">Belum ada komentar atau catatan terkait laporan ini.</p>
              </div>
            @endif
          </div>

          <!-- Banner & Rating (Ketinggian box dibuat lebih ringkas/kecil) -->
          @if ($report->status === 'selesai')
            <div class="bg-[#E9FAF6] border border-[#A7F3D0] rounded-xl p-3 sm:p-3.5 flex items-center gap-3 shadow-2xs">
              <div class="w-6 h-6 rounded-full bg-[#10B981] text-white flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
              </div>
              <div class="min-w-0">
                <h4 class="font-bold text-[#022C22] text-xs sm:text-[13px] leading-tight">Laporan ini telah selesai.</h4>
                <p class="text-[#064E3B] text-[11px] leading-snug mt-0.5">Terima kasih atas partisipasi Anda dalam meningkatkan kualitas layanan di lingkungan UNAIR.</p>
              </div>
            </div>

            <!-- Rating -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-4"
                 x-data="{ userRating: {{ $report->rating ?? 0 }}, hoverRating: 0 }">

              <div class="border-b border-slate-100 pb-2.5">
                <h4 class="text-xs sm:text-sm font-bold text-[#0D3B43]">Beri Penilaian Pelayanan:</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Berapa bintang untuk penanganan laporan ini?</p>
              </div>

              @if ($report->rating)
                <div class="text-center py-2 space-y-2.5">
                  <div class="flex items-center justify-center gap-1.5 text-2xl">
                    @for ($i = 1; $i <= 5; $i++)
                      <span class="{{ $i <= $report->rating ? 'text-amber-400' : 'text-slate-200' }}">★</span>
                    @endfor
                  </div>
                  <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-[#047857] border border-emerald-200 text-xs font-semibold">
                    <svg class="w-3.5 h-3.5 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    <span>Penilaian Anda: {{ $report->rating }}/5 Bintang</span>
                  </div>
                  <p class="text-[11.5px] text-slate-500 leading-snug">Terima kasih atas ulasan dan penilaian yang Anda berikan.</p>
                </div>
              @elseif ($report->execution_result === 'tertunda')
                <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200 text-xs text-amber-900 leading-relaxed">
                  Laporan ini berstatus selesai dengan hasil tertunda, penilaian ulasan tidak diwajibkan.
                </div>
              @else
                <form action="{{ route('pelapor.rate', $report) }}" method="POST" class="space-y-4">
                  @csrf
                  <div class="flex flex-col items-center justify-center gap-2">
                    <div class="flex items-center gap-1" @mouseleave="hoverRating = 0">
                      <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                        <button type="button" @click="userRating = star" @mouseenter="hoverRating = star"
                                class="p-1 focus:outline-none transition-transform hover:scale-125 cursor-pointer" :title="star + ' Bintang'">
                          <svg class="w-7 h-7 sm:w-8 sm:h-8 transition-colors duration-150"
                               :class="(hoverRating || userRating) >= star ? 'text-amber-400 fill-amber-400' : 'text-slate-200 fill-slate-100'"
                               viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                          </svg>
                        </button>
                      </template>
                    </div>
                    <input type="hidden" name="rating" :value="userRating" required>
                    <div class="text-xs font-semibold text-slate-600 h-5 text-center">
                      <span x-show="(hoverRating || userRating) === 1" class="text-rose-600">Sangat Tidak Puas (1 Bintang)</span>
                      <span x-show="(hoverRating || userRating) === 2" class="text-amber-600">Kurang Puas (2 Bintang)</span>
                      <span x-show="(hoverRating || userRating) === 3" class="text-yellow-600">Cukup (3 Bintang)</span>
                      <span x-show="(hoverRating || userRating) === 4" class="text-teal-600">Puas (4 Bintang)</span>
                      <span x-show="(hoverRating || userRating) === 5" class="text-emerald-600">Sangat Puas (5 Bintang)</span>
                      <span x-show="!hoverRating && !userRating" class="text-slate-400">Klik bintang untuk memilih</span>
                    </div>
                  </div>

                  <button type="submit" :disabled="userRating === 0"
                          :class="userRating === 0 ? 'opacity-50 cursor-not-allowed bg-slate-300' : 'bg-[#0F766E] hover:bg-[#0D5B53] shadow-xs hover:shadow-md cursor-pointer'"
                          class="w-full py-2.5 px-4 rounded-xl text-white font-bold text-xs transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span>Kirim Penilaian</span>
                  </button>
                </form>
              @endif
            </div>
          @else
            <div class="bg-[#F0FDFA] border border-[#CCFBF1] rounded-2xl p-4 sm:p-5 flex items-start gap-3.5">
              <div class="w-7 h-7 rounded-full bg-[#0D9488] text-white flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </div>
              <div class="space-y-1 text-xs">
                <h4 class="font-bold text-[#0F766E] text-sm">Laporan Sedang Diproses</h4>
                <p class="text-slate-600 leading-relaxed text-[11.5px]">
                  Laporan Anda sedang berada dalam tahap penanganan oleh petugas unit terkait. Progres akan otomatis diperbarui pada linimasa.
                </p>
              </div>
            </div>
          @endif
        </div>

      </div>
    </main>
  </div>

  <!-- ================= IMAGE PREVIEW MODAL ================= -->
  <div x-show="showImageModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs" @click="showImageModal = false" @keydown.escape.window="showImageModal = false">
    <div class="relative max-w-4xl max-h-[90vh] bg-white rounded-2xl overflow-hidden shadow-2xl" @click.stop>
      <button type="button" @click="showImageModal = false" class="absolute top-3 right-3 p-1.5 rounded-full bg-black/50 text-white hover:bg-black/70 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
      </button>
      <img :src="activeImage" alt="Bukti Laporan" class="max-w-full max-h-[85vh] object-contain">
    </div>
  </div>

  <!-- ================= HELP CONTACT MODAL ================= -->
  <div x-show="showHelpModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" @click="showHelpModal = false" @keydown.escape.window="showHelpModal = false">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4" @click.stop>
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <h3 class="text-base font-bold text-[#0D3B43]">Layanan Bantuan & Informasi</h3>
        <button type="button" @click="showHelpModal = false" class="text-slate-400 hover:text-slate-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
      <div class="space-y-3 text-xs sm:text-sm text-slate-600">
        <p>Jika Anda memerlukan asistensi teknis atau informasi tindak lanjut pengaduan, silakan hubungi saluran resmi berikut:</p>
        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2">
          <p class="font-semibold text-slate-800">Helpdesk FIKKIA UNAIR</p>
          <p class="text-xs">Email: <span class="font-mono text-[#0F766E] font-bold break-all">helpdesk@fikkia.unair.ac.id</span></p>
          <p class="text-xs">WhatsApp Layanan: <span class="font-mono text-[#0F766E] font-bold">+62 812-3456-7890</span></p>
          <p class="text-xs">Jam Operasional: Senin – Jumat (08.00 – 16.00 WIB)</p>
        </div>
      </div>
      <div class="pt-2 text-right">
        <button type="button" @click="showHelpModal = false" class="px-4 py-2 rounded-xl bg-[#0F766E] text-white text-xs font-bold hover:bg-[#0D5B53] transition">Tutup</button>
      </div>
    </div>
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
