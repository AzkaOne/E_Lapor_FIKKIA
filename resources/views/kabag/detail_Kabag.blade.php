@php
  $isPetugas = isset($isPetugas) ? $isPetugas : (auth()->check() && auth()->user()->role === 'petugas');
  $isKasubag = isset($isKasubag) ? $isKasubag : (auth()->check() && auth()->user()->role === 'kasubag');
  $canTakeAction = ($isPetugas && in_array($report->status, ['diterima', 'didisposisi'])) || ($isKasubag && $report->status === 'didisposisi') || (!$isKasubag && !$isPetugas && $report->status === 'diajukan');

  $isSelfExecuting = ($report->status === 'proses') && (auth()->check() && $report->handler_id === auth()->id());
  $isVerifying = (!$isKasubag && !$isPetugas && $report->status === 'verifikasi_kabag');

  $allPetugas = $petugas->isNotEmpty() ? $petugas : ($petugasKabag ?? collect());
  if ($report->sub_department_id) {
      $filteredPetugas = $allPetugas->filter(fn($p) => $p->sub_department_id == $report->sub_department_id);
  } else {
      $filteredPetugas = $allPetugas->filter(fn($p) => is_null($p->sub_department_id));
  }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Laporan #{{ $report->ticket_code }} - Panel {{ $isPetugas ? 'PETUGAS' : ($isKasubag ? 'KASUBAG' : 'KABAG') }} E-Lapor FIKKIA</title>
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
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col selection:bg-[#2E978E] selection:text-white"
      x-data="{
        activeTab: 'informasi',
        evidenceTab: '{{ $report->evidences->whereIn('type', ['resolution', 'completion', 'rejection'])->isNotEmpty() && !in_array($report->status, ['diajukan', 'didisposisi']) ? 'petugas' : 'pelapor' }}',
        showKasubagModal: false,
        showPetugasModal: false,
        selectedChecklist: '',
        selectedVerificationAction: 'selesai',
        verificationKasubagId: '{{ $kasubags->firstWhere('sub_department_id', $report->sub_department_id)?->id ?? $kasubags->first()?->id ?? '' }}',
        showEvidenceToPelapor: {{ $report->show_evidence_to_pelapor === false ? 'false' : 'true' }},
        showNotesToPelapor: {{ $report->show_notes_to_pelapor === false ? 'false' : 'true' }},
        selectedKasubagId: '{{ $kasubags->firstWhere('sub_department_id', $report->sub_department_id)?->id ?? $kasubags->first()?->id ?? '' }}',
        selectedPetugasId: '{{ $filteredPetugas->first()?->id ?? '' }}',
        notesCount: 0,
        copied: false,
        copyTicket() {
          navigator.clipboard.writeText('{{ $report->ticket_code }}');
          this.copied = true;
          setTimeout(() => this.copied = false, 2000);
        }
      }">

<header class="bg-white text-slate-900 h-16 sm:h-20 fixed top-0 left-0 right-0 lg:left-52 z-40 shadow-sm border-b border-slate-200">
    <div class="px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between gap-4">

        <!-- Left: Hamburger & Page Title -->
        <div class="flex items-center gap-3 sm:gap-6 min-w-0">

            <!-- Mobile Sidebar Toggle -->
            <button type="button"
                    onclick="toggleSidebar()"
                    class="lg:hidden p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition focus:outline-none"
                    title="Buka Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Page Title & Description -->
            <div class="min-w-0">
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight leading-tight">
                    Laporan Masuk
                </h1>
                <p class="hidden sm:block text-xs sm:text-[13px] text-slate-500 mt-0.5 truncate">
                    Berikut adalah daftar laporan pelayanan yang masuk dan perlu ditinjau oleh {{ $isPetugas ? 'Petugas' : ($isKasubag ? 'Kepala Sub Bagian' : 'Kepala Bagian') }}.
                </p>
            </div>

        </div>

        <!-- Right: Notifications & User Profile -->
        <div class="flex items-center gap-3 sm:gap-4 shrink-0">

            <!-- Notification Bell -->
            <a href="{{ $isPetugas ? route('petugas.laporan-masuk') : ($isKasubag ? route('kasubag.status') : route('kabag.verifikasi.index')) }}"
               class="relative p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
               title="{{ $isPetugas ? 'Laporan Masuk' : ($isKasubag ? 'Laporan Masuk' : 'Verifikasi Laporan') }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                @if (!$isKasubag && !$isPetugas && ($counts['verifikasi'] ?? 0) > 0)
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-[#EF4444] rounded-full ring-2 ring-white"></span>
                @endif
            </a>

            <!-- User Profile -->
            <div class="flex items-center gap-2 pl-2 sm:pl-3 border-l border-slate-200">

                <div class="w-8 h-8 rounded-full bg-[#E2E8F0] text-slate-700 flex items-center justify-center shrink-0 font-bold shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <div class="hidden md:flex flex-col text-left">
                    <span class="text-xs font-bold text-slate-900 leading-tight truncate max-w-[130px]">
                        {{ auth()->user()->name ?? ($isPetugas ? 'Petugas' : ($isKasubag ? 'Kasubag' : 'Fuad Raden, S.D., S.Mp')) }}
                    </span>
                    <span class="text-[10px] text-slate-500 capitalize leading-tight">
                        {{ auth()->user()->position ?? ($isPetugas ? 'Petugas Lapangan' : ($isKasubag ? 'Kepala Sub Bagian' : 'Kepala Bagian')) }}
                    </span>
                </div>

            </div>

            <!-- Logout Form -->
            <form action="{{ route('logout') }}" method="POST" class="ml-1">
                @csrf
                <button type="submit"
                        class="w-8 h-8 rounded-full bg-slate-100 hover:bg-rose-500 hover:text-white transition flex items-center justify-center text-slate-600"
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


  <!-- ================= MAIN LAYOUT WRAPPER ================= -->
  <div class="flex-1 flex w-full">

    <!-- Sidebar Component (Desktop fixed & Mobile drawer) -->
    @include('komponen.sidebar_Kabag')

    <!-- Main Workspace Content (Responsive offset for desktop lg:ml-64) -->
    <main class="flex-1 lg:ml-52 p-4 pt-20 sm:p-6 sm:pt-24 lg:p-7 lg:pt-24 max-w-[1400px] w-full mx-auto space-y-5">

      <!-- ================= BREADCRUMB ================= -->
      <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 font-medium">
        <a href="{{ $isPetugas ? route('petugas.laporan-masuk') : ($isKasubag ? route('kasubag.status') : route('kabag.laporan-pelayanan')) }}" class="hover:text-[#2E978E] transition">Laporan Masuk</a>
        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-800 font-semibold">Detail laporan</span>
      </nav>

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

      @if ($errors->any())
        <div class="p-3.5 sm:p-4 rounded-2xl bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-xs sm:text-sm font-semibold shadow-xs">
          <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- ================= SUMMARY & STATUS STEPPER ================= -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        <!-- CARD 1: Main Report Summary Card (60%) -->
        <div class="lg:col-span-7 bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 flex flex-col justify-between">
          <div class="flex items-start gap-4">
            <!-- Green Document Icon -->
            <div class="w-12 h-12 rounded-2xl bg-[#ECFDF5] text-[#059669] flex items-center justify-center shrink-0 border border-[#A7F3D0]">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>

            <!-- Title & ID -->
            <div class="min-w-0 flex-1">
              <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-snug">
                {{ $report->title }}
              </h2>
              <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                <span class="font-medium text-slate-600">Laporan Pelayanan</span>
                <span>•</span>
                <span class="font-mono font-semibold text-slate-500">ID: {{ $report->ticket_code }}</span>
              </div>
            </div>
          </div>

          <!-- 3 Meta Columns -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-5 mt-5 border-t border-slate-100 text-xs">
            <!-- Col 1: Pelapor -->
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <div class="min-w-0">
                <div class="text-[11px] text-slate-400 font-medium">Pelapor</div>
                <div class="font-bold text-slate-800 truncate">{{ $report->user?->name ?? 'Ahmad Fauzi' }}</div>
                <div class="text-[10px] text-slate-500 capitalize">{{ $report->user?->position ?? 'Mahasiswa' }}</div>
              </div>
            </div>

            <!-- Col 2: Bidang -->
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <div class="min-w-0">
                <div class="text-[11px] text-slate-400 font-medium">Bidang</div>
                <div class="font-bold text-slate-800 truncate">{{ $report->subDepartment?->name ?? 'Sarana dan Prasarana' }}</div>
                <div class="text-[10px] text-slate-500">
                  {{ $report->subDepartment?->name ? (str_contains($report->subDepartment->name, 'Sarana') ? 'Sarpras' : (str_contains($report->subDepartment->name, 'Pendidikan') ? 'Akademik' : (str_contains($report->subDepartment->name, 'SDM') ? 'Keuangan' : 'Dekanat'))) : 'Sarpras' }}
                </div>
              </div>
            </div>

            <!-- Col 3: Tanggal Masuk -->
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <div class="min-w-0">
                <div class="text-[11px] text-slate-400 font-medium">Tanggal Masuk</div>
                <div class="font-bold text-slate-800">{{ $report->created_at->format('d M Y') }}</div>
                <div class="text-[10px] text-slate-500">{{ $report->created_at->format('H:i') }} WIB</div>
              </div>
            </div>
          </div>
        </div>

        <!-- CARD 2: Horizontal Timeline / Stepper Status Laporan (40%) -->
        <div class="lg:col-span-5 bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 flex flex-col justify-between">
          <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Status Laporan</h3>

            @php
              $status = $report->status;
              $stepIndex = 1;
              if (in_array($status, ['didisposisi'])) $stepIndex = 2;
              elseif (in_array($status, ['diterima'])) $stepIndex = 3;
              elseif (in_array($status, ['proses'])) $stepIndex = 4;
              elseif (in_array($status, ['verifikasi_kabag', 'selesai'])) $stepIndex = 5;
            @endphp

            <!-- Horizontal Stepper -->
            <div class="relative flex items-start justify-between w-full pt-2">
              <!-- Connecting Line -->
              <div class="absolute top-5 left-3 right-3 h-0.5 bg-slate-200 -z-0"></div>

              <!-- Step 1: Diajukan -->
              <div class="relative z-10 flex flex-col items-center text-center w-16">
                <div class="w-7 h-7 rounded-full {{ $stepIndex >= 1 ? 'bg-[#059669] text-white shadow-xs' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center font-bold text-xs ring-4 ring-white">
                  @if ($stepIndex >= 1)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                  @else
                    1
                  @endif
                </div>
                <div class="text-[10.5px] font-bold text-slate-800 mt-2 leading-tight">Diajukan</div>
                <div class="text-[9px] text-slate-400 leading-tight mt-0.5">{{ $report->created_at->format('d M') }}</div>
              </div>

              <!-- Step 2: Disposisi -->
              <div class="relative z-10 flex flex-col items-center text-center w-16">
                <div class="w-7 h-7 rounded-full {{ $stepIndex >= 2 ? 'bg-[#059669] text-white shadow-xs' : 'bg-white border-2 border-slate-300 text-slate-400' }} flex items-center justify-center font-bold text-xs ring-4 ring-white">
                  @if ($stepIndex >= 2)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                  @else
                    2
                  @endif
                </div>
                <div class="text-[10.5px] font-semibold text-slate-700 mt-2 leading-tight">Disposisi</div>
                <div class="text-[9px] text-slate-400 leading-tight mt-0.5">Kabag&rarr;Kasubag</div>
              </div>

              <!-- Step 3: Diterima -->
              <div class="relative z-10 flex flex-col items-center text-center w-16">
                <div class="w-7 h-7 rounded-full {{ $stepIndex >= 3 ? 'bg-[#059669] text-white shadow-xs' : 'bg-white border-2 border-slate-300 text-slate-400' }} flex items-center justify-center font-bold text-xs ring-4 ring-white">
                  @if ($stepIndex >= 3)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                  @else
                    3
                  @endif
                </div>
                <div class="text-[10.5px] font-semibold text-slate-700 mt-2 leading-tight">Diterima</div>
                <div class="text-[9px] text-slate-400 leading-tight mt-0.5">Petugas</div>
              </div>

              <!-- Step 4: Proses -->
              <div class="relative z-10 flex flex-col items-center text-center w-16">
                <div class="w-7 h-7 rounded-full {{ $stepIndex >= 4 ? 'bg-[#059669] text-white shadow-xs' : 'bg-white border-2 border-slate-300 text-slate-400' }} flex items-center justify-center font-bold text-xs ring-4 ring-white">
                  @if ($stepIndex >= 4)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                  @else
                    4
                  @endif
                </div>
                <div class="text-[10.5px] font-semibold text-slate-700 mt-2 leading-tight">Proses</div>
                <div class="text-[9px] text-slate-400 leading-tight mt-0.5">Penanganan</div>
              </div>

              <!-- Step 5: Terlaksana -->
              <div class="relative z-10 flex flex-col items-center text-center w-16">
                <div class="w-7 h-7 rounded-full {{ $stepIndex >= 5 ? 'bg-[#059669] text-white shadow-xs' : 'bg-white border-2 border-slate-300 text-slate-400' }} flex items-center justify-center font-bold text-xs ring-4 ring-white">
                  @if ($stepIndex >= 5)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                  @else
                    5
                  @endif
                </div>
                <div class="text-[10.5px] font-semibold text-slate-700 mt-2 leading-tight">Terlaksana</div>
                <div class="text-[9px] text-slate-400 leading-tight mt-0.5">Selesai</div>
              </div>
            </div>
          </div>

          <!-- Bottom Status Pill -->
          <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-400">Status Saat Ini:</span>
            <span class="font-bold uppercase tracking-wide px-3 py-1 rounded-full text-[11px]
              @if ($report->status === 'diajukan') bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]
              @elseif (in_array($report->status, ['didisposisi', 'diterima', 'proses'])) bg-[#EFF6FF] text-[#2563EB] border border-[#BFDBFE]
              @elseif ($report->status === 'verifikasi_kabag') bg-[#FAF5FF] text-[#7E22CE] border border-[#F3E8FF]
              @elseif ($report->status === 'selesai') bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0]
              @else bg-rose-50 text-rose-700 border border-rose-200 @endif">
              {{ str_replace('_', ' ', $report->status) }}
            </span>
          </div>
        </div>

      </div>

      <!-- ================= REPORT INFO & COMMENTS TABS ================= -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">


      <!-- COLUMN 1: BOX INFORMASI LAPORAN -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
        <!-- Tabs -->
        <div class="px-6 pt-5 border-b border-slate-200 flex items-center gap-5">
            <button type="button" @click="activeTab = 'informasi'"
                    class="pb-3 text-sm font-bold transition border-b-2 -mb-px"
                    :class="activeTab === 'informasi' ? 'border-[#166E65] text-[#166E65]' : 'border-transparent text-slate-500 hover:text-slate-700'">
            Informasi Laporan
            </button>
            <button type="button" @click="activeTab = 'komentar'"
                    class="pb-3 text-sm font-bold transition border-b-2 -mb-px flex items-center gap-2"
                    :class="activeTab === 'komentar' ? 'border-[#166E65] text-[#166E65]' : 'border-transparent text-slate-500 hover:text-slate-700'">
            Komentar
            @php
              $totalCommentsCount = $report->dispositions->whereNotNull('notes')->filter(fn($d) => trim($d->notes) !== '')->count() + (!empty($report->resolution_notes) && !$report->dispositions->contains(fn($d) => trim($d->notes ?? '') === trim($report->resolution_notes)) ? 1 : 0);
            @endphp
            @if ($totalCommentsCount > 0)
                <span class="px-2 rounded-full text-[10px] font-bold bg-[#CCFBF1] text-[#0F766E]">
                {{ $totalCommentsCount }}
                </span>
            @endif
            </button>
        </div>

        <div class="p-5 sm:p-6 grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_230px] gap-5 items-start">

            <!-- KOLOM ISI (berganti sesuai tab) -->
            <div class="min-w-0">

            <!-- TAB INFORMASI -->
            <div x-show="activeTab === 'informasi'" class="space-y-3">
                <div>
                <h3 class="text-[15px] font-semibold text-[#0C3934]">Judul Laporan</h3>
                <p class="text-[11px] font-bold text-[#0C3934] mt-1">{{ $report->title }}</p>
                </div>

                <div>
                <h3 class="text-[15px] font-semibold text-[#0C3934]">Deskripsi</h3>
                <p class="text-xs text-slate-600 leading-relaxed mt-1 whitespace-pre-line">{{ $report->description }}</p>
                </div>

                <hr class="border-slate-200">

                <div>
                <h3 class="text-[15px] font-semibold text-[#0C3934]">Lokasi</h3>
                <div class="flex items-center gap-2 mt-1.5 ml-1">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-100 via-sky-100 to-indigo-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#166E65]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    </div>
                    <span class="text-[11px] font-bold text-[#0C3934]">Kampus {{ strtoupper($report->campus_location ?? 'Mojo') }}</span>
                </div>
                </div>

                <hr class="border-slate-200">

                <!-- Bukti Laporan -->
                <div class="border border-slate-200 rounded-lg p-3">
                <h3 class="text-[15px] font-semibold text-[#0C3934]">Bukti Laporan</h3>

                <div class="flex items-center gap-4 mt-3 border-b border-slate-200 text-[11px]">
                    <button type="button" @click="evidenceTab = 'pelapor'"
                            class="pb-2 font-semibold -mb-px border-b-2 transition"
                            :class="evidenceTab === 'pelapor' ? 'border-[#166E65] text-[#166E65]' : 'border-transparent text-slate-500'">
                    Pelapor
                    </button>
                    <button type="button" @click="evidenceTab = 'petugas'"
                            class="pb-2 font-semibold -mb-px border-b-2 transition"
                            :class="evidenceTab === 'petugas' ? 'border-[#166E65] text-[#166E65]' : 'border-transparent text-slate-500'">
                    Petugas
                    </button>
                </div>

                @php
                    $evidenceGroups = [
                    'pelapor' => $report->evidences->where('type', 'initial'),
                    'petugas' => $report->evidences->whereIn('type', ['resolution', 'completion', 'rejection'])->sortByDesc('created_at'),
                    ];
                    $publicDisk = \Illuminate\Support\Facades\Storage::disk('public');
                @endphp

                @foreach ($evidenceGroups as $key => $items)
                    <div x-show="evidenceTab === '{{ $key }}'" @if ($key !== 'pelapor') style="display: none;" @endif
                        class="flex flex-wrap gap-3 pt-3">
                    @forelse ($items as $ev)
                        @php
                        $ext = strtolower(pathinfo($ev->file_path, PATHINFO_EXTENSION));
                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
                        $sizeMb = $publicDisk->exists($ev->file_path)
                            ? number_format($publicDisk->size($ev->file_path) / 1048576, 1)
                            : null;
                        @endphp
                        <a href="{{ asset('storage/' . $ev->file_path) }}" target="_blank"
                        class="block w-[138px] border border-slate-200 rounded-lg p-1.5 bg-slate-50 hover:shadow-sm transition">
                        <div class="h-[90px] rounded-md overflow-hidden bg-slate-100 flex items-center justify-center">
                            @if ($isImg)
                            <img src="{{ asset('storage/' . $ev->file_path) }}" alt="Bukti" class="w-full h-full object-cover">
                            @else
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5 mt-2 px-0.5">
                            <svg class="w-3.5 h-3.5 text-[#166E65] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[10px] text-slate-700 truncate">{{ basename($ev->file_path) }}</span>
                        </div>
                        @if ($sizeMb)
                            <div class="text-[9px] text-slate-400 mt-0.5 pl-5">{{ $sizeMb }} MB</div>
                        @endif
                        </a>
                    @empty
                        <div class="w-full p-5 rounded-xl bg-[#F8FAFC] border border-dashed border-slate-200 text-center">
                        <p class="text-xs text-slate-400">
                            {{ $key === 'pelapor' ? 'Tidak ada lampiran bukti dari pelapor.' : 'Belum ada lampiran bukti penyelesaian dari petugas.' }}
                        </p>
                        </div>
                    @endforelse
                    </div>
                @endforeach
                </div>
            </div>

            <!-- TAB KOMENTAR (isi asli Anda, hanya dipindah) -->
            <div x-show="activeTab === 'komentar'" style="display: none;"
                class="space-y-3.5 max-h-[520px] overflow-y-auto custom-scroll pr-1">
                @php
                $validDispositions = $report->dispositions->whereNotNull('notes')->filter(fn($d) => trim($d->notes) !== '');
                $hasResolutionInDisp = $validDispositions->contains(fn($d) => trim($d->notes ?? '') === trim($report->resolution_notes ?? ''));
                @endphp

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

                @forelse ($validDispositions as $disp)
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
                @empty
                <div class="py-12 px-4 rounded-2xl bg-[#F8FAFC] border border-dashed border-slate-200 text-center">
                    <h5 class="text-sm font-bold text-slate-800">Belum ada komentar</h5>
                    <p class="text-xs text-slate-500 mt-1">Belum ada komentar atau catatan terkait laporan ini.</p>
                </div>
                @endforelse
            </div>
            </div>

            <!-- DATA PELAPOR (ditulis sekali, tampil di kedua tab) -->
            <aside class="bg-[#F8FAFC] border border-slate-200 rounded-2xl p-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-200">
                <div class="w-7 h-7 rounded-full bg-[#E2E8F0] text-slate-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-3.3 0-8 1.7-8 5v1h16v-1c0-3.3-4.7-5-8-5z"/></svg>
                </div>
                <div class="min-w-0">
                <div class="text-xs font-bold text-[#0C3934] leading-tight">Data Pelapor</div>
                <div class="text-[11px] text-slate-700 truncate">{{ $report->user?->name ?? '-' }}</div>
                </div>
            </div>

            <dl class="mt-3 space-y-2.5">
                <div>
                <dt class="text-xs font-bold text-[#0C3934]">NIM</dt>
                <dd class="text-[11px] text-slate-700">{{ $report->user?->identity_number ?? '-' }}</dd>
                </div>
                <div>
                <dt class="text-xs font-bold text-[#0C3934]">Jabatan</dt>
                <dd class="text-[11px] text-slate-700 capitalize">{{ $report->user?->position ?? '-' }}</dd>
                </div>
                <div>
                <dt class="text-xs font-bold text-[#0C3934]">No Telpone</dt>
                <dd class="text-[11px] text-slate-700">{{ $report->user?->phone ?? '-' }}</dd>
                </div>
                <div>
                <dt class="text-xs font-bold text-[#0C3934]">Email</dt>
                <dd class="text-[11px] text-slate-700 break-all">{{ $report->user?->email ?? '-' }}</dd>
                </div>
                <div class="flex items-end justify-between gap-2">
                <div class="min-w-0">
                    <dt class="text-xs font-bold text-[#0C3934]">Nomor Laporan</dt>
                    <dd class="text-xs font-bold text-[#0C3934] break-all">{{ $report->ticket_code }}</dd>
                </div>
                <button type="button" @click="copyTicket()" title="Salin Nomor Laporan"
                        class="p-1 text-slate-500 hover:text-[#166E65] transition shrink-0">
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                    <svg x-show="copied" style="display: none;" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
                </div>
            </dl>
            </aside>

        </div>
        </div>


        @if ($isVerifying)
          <!-- COLUMN 2: VERIFIKASI KABAG (Selesai & Revisi) -->
          <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6">

            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 class="text-sm font-bold text-slate-800">Verifikasi Laporan</h3>
              <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full uppercase
                {{ $report->execution_result === 'terlaksana' ? 'bg-[#DCFCE7] text-[#15803D]' : 'bg-[#FEE2E2] text-[#B91C1C]' }}">
                Hasil: {{ $report->execution_result ?? 'Menunggu Verifikasi' }}
              </span>
            </div>

            <form :action="selectedVerificationAction === 'revisi' ? '{{ route('kabag.verifikasi.revisi', $report) }}' : '{{ route('kabag.verifikasi.approve', $report) }}'"
                  method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
              @csrf

              <!-- 2 BUTTONS: SELESAI (HIJAU) & REVISI (MERAH) -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                <!-- 1. Tombol Selesai (Warna Hijau, Bentuk Ceklis) -->
                <div @click="selectedVerificationAction = 'selesai'"
                     class="rounded-xl border p-3.5 cursor-pointer transition flex items-center justify-between gap-2.5 shadow-xs hover:shadow-md"
                     :class="selectedVerificationAction === 'selesai' ? 'border-[#15803D] bg-[#DCFCE7] ring-2 ring-[#15803D]/40' : 'border-[#15803D]/30 bg-[#F0FDF4] hover:bg-[#DCFCE7]'">
                  <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-[#22C55E] text-white flex items-center justify-center shrink-0 shadow-xs">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="min-w-0">
                      <div class="text-xs font-bold text-[#15803D] leading-tight">Selesai</div>
                      <p class="text-[10px] sm:text-[10.5px] text-[#15803D] mt-0.5 leading-snug">Verifikasi & sahkan tiket</p>
                    </div>
                  </div>
                  <!-- Checklist Box Indicator -->
                  <div class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition shrink-0"
                       :class="selectedVerificationAction === 'selesai' ? 'bg-[#15803D] border-[#15803D] text-white shadow-xs' : 'border-[#86EFAC] bg-white text-transparent'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                  </div>
                </div>

                <!-- 2. Tombol Revisi (Warna Merah) -->
                <div @click="selectedVerificationAction = 'revisi'"
                     class="rounded-xl border p-3.5 cursor-pointer transition flex items-center justify-between gap-2.5 shadow-xs hover:shadow-md"
                     :class="selectedVerificationAction === 'revisi' ? 'border-[#B91C1C] bg-[#FEE2E2] ring-2 ring-[#B91C1C]/40' : 'border-[#B91C1C]/30 bg-[#FFF5F5] hover:bg-[#FEE2E2]'">
                  <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-[#EF4444] text-white flex items-center justify-center shrink-0 shadow-xs">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <div class="min-w-0">
                      <div class="text-xs font-bold text-[#B91C1C] leading-tight">Revisi</div>
                      <p class="text-[10px] sm:text-[10.5px] text-[#B91C1C] mt-0.5 leading-snug">Disposisi perbaikan</p>
                    </div>
                  </div>
                  <!-- Checklist Box Indicator -->
                  <div class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition shrink-0"
                       :class="selectedVerificationAction === 'revisi' ? 'bg-[#B91C1C] border-[#B91C1C] text-white shadow-xs' : 'border-[#FCA5A5] bg-white text-transparent'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                  </div>
                </div>

              </div>

              <!-- JIKA REVISI DIKLIK: MUNCUL DAFTAR NAMA KASUBAG UNTUK DISPOSISI -->
              <div x-show="selectedVerificationAction === 'revisi'" x-cloak class="p-3.5 rounded-xl bg-[#FEF2F2] border border-[#FECACA] space-y-2.5">
                <div class="flex items-center justify-between">
                  <label class="block text-xs font-bold text-[#991B1B] uppercase tracking-wider">
                    Daftar Nama Kasubag (Tujuan Disposisi) <span class="text-red-500">*</span>
                  </label>
                  <span class="text-[10px] text-[#B91C1C]">Pilih penerima revisi</span>
                </div>
                <div class="space-y-2 max-h-48 overflow-y-auto custom-scroll pr-1">
                  @forelse ($kasubags as $kasubag)
                    @php
                      $isMatchingDept = $report->sub_department_id && $kasubag->sub_department_id == $report->sub_department_id;
                    @endphp
                    <label class="flex items-center justify-between p-2.5 rounded-xl border cursor-pointer transition hover:bg-white"
                           :class="verificationKasubagId == '{{ $kasubag->id }}' ? 'border-[#B91C1C] bg-white ring-1 ring-[#B91C1C]' : 'border-rose-200/80 bg-white/70'">
                      <div class="flex items-center gap-2.5 min-w-0">
                        <input type="radio" name="receiver_id" value="{{ $kasubag->id }}"
                               x-model="verificationKasubagId"
                               class="text-[#B91C1C] focus:ring-[#B91C1C] shrink-0"
                               :required="selectedVerificationAction === 'revisi'">
                        <div class="min-w-0 text-left">
                          <div class="font-bold text-xs text-slate-900 truncate">
                            {{ $kasubag->name }}
                          </div>
                          <div class="text-[10px] font-semibold text-[#B91C1C] flex items-center gap-1 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#B91C1C]"></span>
                            <span>Bidang: {{ $kasubag->subDepartment?->name ?? 'Dekanat' }}</span>
                          </div>
                        </div>
                      </div>
                      @if ($isMatchingDept)
                        <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold bg-[#FEE2E2] text-[#B91C1C] border border-[#FECACA] shrink-0">
                          Sesuai Bidang
                        </span>
                      @endif
                    </label>
                  @empty
                    <div class="p-3 text-center text-xs text-slate-400 bg-white rounded-xl">
                      Belum ada data akun Kasubag yang terdaftar di sistem.
                    </div>
                  @endforelse
                </div>
              </div>

              <!-- TEMPAT UNTUK MEMASUKKAN KOMENTAR (OPSIONAL) -->
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-[#0C3934] mb-1.5">
                  Catatan / Komentar <span class="text-slate-400 text-[10px] font-normal normal-case">(Opsional)</span>
                </label>
                <textarea rows="3" maxlength="500" name="notes"
                          @input="notesCount = $event.target.value.length"
                          :placeholder="selectedVerificationAction === 'revisi' ? 'Tuliskan catatan arahan atau instruksi poin perbaikan kepada Kasubag...' : 'Tuliskan catatan apresiasi, tindak lanjut, atau verifikasi laporan...'"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-[#2E978E] focus:outline-none transition"></textarea>
                <div class="text-right text-[11px] text-slate-400 mt-1 pr-1" x-text="notesCount + '/500'">0/500</div>
              </div>

              <!-- FORM UPLOAD BUKTI LAPORAN (OPSIONAL) -->
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-[#0C3934] mb-1.5">
                  Bukti Laporan <span class="text-slate-400 text-[10px] font-normal normal-case">(Opsional - JPG, PNG, PDF maks 5MB)</span>
                </label>
                <div class="relative border-2 border-dashed border-slate-200 hover:border-[#2E978E] rounded-xl p-3 bg-slate-50/50 hover:bg-[#F0FDFA] transition text-center group cursor-pointer">
                  <input type="file" name="evidence" id="evidence_verification_kabag" accept=".jpg,.jpeg,.png,.pdf"
                         class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                         onchange="document.getElementById('evidence-verif-filename').textContent = this.files[0] ? this.files[0].name : 'Klik atau seret file bukti untuk mengunggah'">
                  <div class="flex items-center justify-center gap-2 text-slate-500 group-hover:text-[#166E65]">
                    <svg class="w-4 h-4 shrink-0 text-[#2E978E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span id="evidence-verif-filename" class="text-xs font-medium truncate max-w-xs text-slate-600">
                      Klik atau seret file bukti untuk mengunggah
                    </span>
                  </div>
                </div>
              </div>

              <!-- 2 CEKLIS VISIBILITAS KEPADA PELAPOR -->
              <div class="pt-3.5 border-t border-slate-200 space-y-2.5">
                <div class="text-xs font-bold uppercase tracking-wider text-[#0C3934]">
                  Pengaturan Visibilitas Pelapor
                </div>

                <!-- Ceklis 1: Bukti laporan terlihat oleh pelapor atau tidak -->
                <label class="flex items-start gap-2.5 cursor-pointer group">
                  <input type="checkbox" name="show_evidence_to_pelapor" value="1"
                         x-model="showEvidenceToPelapor"
                         class="mt-0.5 w-4 h-4 rounded text-[#166E65] border-slate-300 focus:ring-[#2E978E]">
                  <div class="text-xs">
                    <span class="font-bold text-slate-800 group-hover:text-[#166E65] transition">Tampilkan bukti laporan kepada pelapor</span>
                    <p class="text-[10.5px] text-slate-500 leading-snug">Izinkan pelapor melihat file bukti pengerjaan/verifikasi pada riwayat laporan.</p>
                  </div>
                </label>

                <!-- Ceklis 2: Menunjukan komentar ke pelapor atau tidak (komentar dari petugas / yang mengerjakan laporan) -->
                <label class="flex items-start gap-2.5 cursor-pointer group">
                  <input type="checkbox" name="show_notes_to_pelapor" value="1"
                         x-model="showNotesToPelapor"
                         class="mt-0.5 w-4 h-4 rounded text-[#166E65] border-slate-300 focus:ring-[#2E978E]">
                  <div class="text-xs">
                    <span class="font-bold text-slate-800 group-hover:text-[#166E65] transition">Tampilkan komentar petugas kepada pelapor</span>
                    <p class="text-[10.5px] text-slate-500 leading-snug">Izinkan pelapor membaca catatan / komentar penanganan dari petugas pelaksana.</p>
                  </div>
                </label>
              </div>

              <!-- TOMBOL PROSES -->
              <div class="flex justify-center pt-2">
                <button type="submit"
                        class="px-8 py-2.5 rounded-xl font-bold text-white text-xs sm:text-sm shadow-sm hover:shadow-md transition inline-flex items-center gap-2 cursor-pointer"
                        :class="selectedVerificationAction === 'revisi' ? 'bg-[#DC2626] hover:bg-[#B91C1C]' : 'bg-[#166E65] hover:bg-[#0C3934]'">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                  <span x-text="selectedVerificationAction === 'revisi' ? 'Kirim Perintah Revisi' : 'Selesaikan & Verifikasi'"></span>
                </button>
              </div>

            </form>

          </div>
        @elseif ($canTakeAction)
          <!-- COLUMN 2: BOX TINDAKAN -->
          <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6">

            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 class="text-sm font-bold text-slate-800">Tindakan yang Dapat Dilakukan</h3>
              <span class="text-[11px] text-slate-400 font-medium">Pilih salah satu menu</span>
            </div>

            <!-- KARTU TINDAKAN -->
            {{-- <div class="grid grid-cols-1 {{ $isKasubag ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }} gap-3 mt-4"> --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">

              @if (!$isKasubag && !$isPetugas)
                <!-- 1. Disposisi ke Kasubag (Buka Popup Kasubag & Bidangnya) -->
                <div @click="showKasubagModal = true"
                     class="rounded-xl border border-[#10B981] bg-[#ECFDF5] hover:bg-[#D1FAE5] p-3.5 cursor-pointer transition flex items-center justify-between gap-2.5 shadow-xs hover:shadow-md hover:scale-[1.01] group">
                  <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-[#047857] text-white flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                      <div class="text-xs font-bold text-[#064E3B] leading-tight">Disposisi Ke Kasubag</div>
                      <p class="text-[10px] sm:text-[10.5px] text-[#059669] mt-0.5 leading-snug">Pilih Kasubag & bidang</p>
                    </div>
                  </div>
                  <div class="w-6 h-6 rounded-lg bg-white/90 border border-[#A7F3D0] flex items-center justify-center text-[#047857] shrink-0 group-hover:translate-x-0.5 transition-transform">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                  </div>
                </div>
              @endif

              @if (!$isPetugas)
              <!-- 2. Kirim ke Petugas (Buka Popup Petugas & Bidangnya) -->
              <div @click="showPetugasModal = true"
                   class="rounded-xl border border-[#0369A1] bg-[#DBEAFE] hover:bg-[#BFDBFE] p-3.5 cursor-pointer transition flex items-center justify-between gap-2.5 shadow-xs hover:shadow-md hover:scale-[1.01] group">
                <div class="flex items-center gap-2.5 min-w-0">
                  <div class="w-10 h-10 rounded-xl bg-[#0EA5E9] text-white flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="text-xs font-bold text-[#0369A1] leading-tight">Kirim ke Petugas</div>
                    <p class="text-[10px] sm:text-[10.5px] text-[#0284C7] mt-0.5 leading-snug">Pilih Petugas & bidang</p>
                  </div>
                </div>
                <div class="w-6 h-6 rounded-lg bg-white/90 border border-[#BAE6FD] flex items-center justify-center text-[#0369A1] shrink-0 group-hover:translate-x-0.5 transition-transform">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
              </div>
              @endif

              <!-- 3. Kerjakan Mandiri (Bentuk Button Checklist) -->
              <div @click="selectedChecklist = (selectedChecklist === 'sendiri' ? '' : 'sendiri')"
                   class="rounded-xl border p-3.5 cursor-pointer transition flex items-center justify-between gap-2.5 shadow-xs hover:shadow-md"
                   :class="selectedChecklist === 'sendiri' ? 'border-[#7C3AED] bg-[#EDE9FE] ring-2 ring-[#7C3AED]/40' : 'border-[#DDD6FE] bg-[#F5F3FF] hover:bg-[#EDE9FE]'">
                <div class="flex items-center gap-2.5 min-w-0">
                  <div class="w-10 h-10 rounded-xl bg-[#A855F7] text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="text-xs font-bold text-[#6D28D9] leading-tight">Kerjakan Mandiri</div>
                    <p class="text-[10px] sm:text-[10.5px] text-[#7C3AED] mt-0.5 leading-snug">{{ $isPetugas ? 'Mulai kerjakan penugasan di lapangan' : ($isKasubag ? 'Tangani langsung oleh Kasubag' : 'Tangani langsung oleh Kabag') }}</p>
                  </div>
                </div>
                <!-- Checklist Box Indicator -->
                <div class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition shrink-0"
                     :class="selectedChecklist === 'sendiri' ? 'bg-[#7C3AED] border-[#7C3AED] text-white shadow-xs' : 'border-[#A78BFA] bg-white text-transparent'">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                  </svg>
                </div>
              </div>

              <!-- 4. Tolak Laporan (Bentuk Button Checklist) -->
              <div @click="selectedChecklist = (selectedChecklist === 'tolak' ? '' : 'tolak')"
                   class="{{ ($isKasubag && !$isPetugas) ? 'sm:col-span-2 sm:w-[calc(50%-0.375rem)] sm:justify-self-center' : '' }} rounded-xl border p-3.5 cursor-pointer transition flex items-center justify-between gap-2.5 shadow-xs hover:shadow-md"
                   :class="selectedChecklist === 'tolak' ? 'border-[#B91C1C] bg-[#FEE2E2] ring-2 ring-[#B91C1C]/40' : 'border-[#FECACA] bg-[#FFF5F5] hover:bg-[#FEE2E2]'">
                <div class="flex items-center gap-2.5 min-w-0">
                  <div class="w-10 h-10 rounded-xl bg-[#EF4444] text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="text-xs font-bold text-[#B91C1C] leading-tight">Tolak Laporan</div>
                    <p class="text-[10px] sm:text-[10.5px] text-[#DC2626] mt-0.5 leading-snug">{{ ($isKasubag || $isPetugas) ? 'Tolak dan kembalikan ke Kabag' : 'Tolak jika tidak valid/spam' }}</p>
                  </div>
                </div>
                <!-- Checklist Box Indicator -->
                <div class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition shrink-0"
                     :class="selectedChecklist === 'tolak' ? 'bg-[#B91C1C] border-[#B91C1C] text-white shadow-xs' : 'border-[#F87171] bg-white text-transparent'">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                  </svg>
                </div>
              </div>

            </div>

            <hr class="border-slate-200 my-5">

            <!-- AREA TINDAKAN LANJUTAN CHECKLIST (Kerjakan Mandiri / Tolak Laporan) -->
            <div>

              <!-- State 0: Belum Memilih Checklist -->
              <div x-show="selectedChecklist === ''" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                <p class="text-xs text-slate-500 font-medium">
                  @if ($isPetugas)
                    Pilih menu checklist <span class="font-bold text-[#6D28D9]">Kerjakan Mandiri</span> untuk mulai menangani laporan di lapangan, atau <span class="font-bold text-[#B91C1C]">Tolak Laporan</span> jika ada ketidaksesuaian untuk dikembalikan ke KABAG.
                  @elseif ($isKasubag)
                    Pilih <span class="font-bold text-[#0369A1]">Kirim ke Petugas</span> untuk membuka popup pemilihan nama dan bidangnya, atau klik checklist <span class="font-bold text-[#6D28D9]">Kerjakan Mandiri</span> / <span class="font-bold text-[#B91C1C]">Tolak Laporan</span> lalu klik tombol <span class="font-bold text-slate-700">Lanjutkan</span>.
                  @else
                    Pilih <span class="font-bold text-[#064E3B]">Disposisi Ke Kasubag</span> atau <span class="font-bold text-[#0369A1]">Kirim ke Petugas</span> untuk membuka popup pemilihan nama dan bidangnya, atau klik checklist <span class="font-bold text-[#6D28D9]">Kerjakan Mandiri</span> / <span class="font-bold text-[#B91C1C]">Tolak Laporan</span> lalu klik tombol <span class="font-bold text-slate-700">Lanjutkan</span>.
                  @endif
                </p>
              </div>

              <!-- State 1: Kerjakan Mandiri Terpilih -->
              <div x-show="selectedChecklist === 'sendiri'" style="display: none;">
                <form action="{{ $isPetugas ? route('petugas.self-execute', $report) : ($isKasubag ? route('kasubag.self-execute', $report) : route('kabag.self-execute', $report)) }}" method="POST">
                  @csrf
                  <div class="p-3.5 rounded-xl bg-[#EDE9FE] border border-[#DDD6FE] text-[#6D28D9] text-xs space-y-1 mb-4">
                    <div class="font-bold flex items-center gap-1.5">
                      <svg class="w-4 h-4 text-[#7C3AED]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                      <span>Konfirmasi Kerjakan Mandiri</span>
                    </div>
                    <p class="leading-relaxed">
                      Laporan ini akan dialihkan ke status <strong>Dalam Proses</strong> dan dipindahkan ke menu <strong>Laporan Saya</strong> untuk Anda selesaikan secara langsung.
                    </p>
                  </div>

                  <div class="flex justify-center">
                    <button type="submit"
                            class="px-8 py-2.5 rounded-xl font-bold bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-xs sm:text-sm shadow-sm hover:shadow-md transition inline-flex items-center gap-2 cursor-pointer">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                      <span>Lanjutkan Kerjakan Mandiri</span>
                    </button>
                  </div>
                </form>
              </div>

              <!-- State 2: Tolak Laporan Terpilih -->
              <div x-show="selectedChecklist === 'tolak'" style="display: none;">
                <form action="{{ $isPetugas ? route('petugas.reject', $report) : ($isKasubag ? route('kasubag.reject', $report) : route('kabag.reject', $report)) }}" method="POST" enctype="multipart/form-data"
                      onsubmit="return confirm('Apakah Anda yakin ingin menolak laporan ini?')">
                  @csrf
                  <div class="mb-3">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                      Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea rows="4" maxlength="500" name="rejection_reason" required
                              placeholder="{{ ($isKasubag || $isPetugas) ? 'Jelaskan alasan laporan ditolak dan dikembalikan ke KABAG...' : 'Contoh: Laporan tidak valid karena bukti foto tidak relevan atau informasi tidak lengkap...' }}"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-rose-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-rose-500 focus:outline-none transition"></textarea>
                    <p class="text-[11px] text-slate-400 mt-1">
                      {{ ($isKasubag || $isPetugas) ? 'Laporan akan diarahkan kembali ke KABAG dan status laporan menjadi ditolak.' : 'Alasan penolakan akan dapat dibaca oleh pelapor pada halaman status laporannya.' }}
                    </p>
                  </div>

                  @if ($isPetugas)
                    <div class="mb-4">
                      <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Bukti Penolakan <span class="text-slate-400 text-[10px] font-normal normal-case">(Opsional - JPG, PNG, PDF maks 5MB)</span>
                      </label>
                      <div class="relative border-2 border-dashed border-rose-200 hover:border-rose-300 rounded-xl p-3 bg-rose-50/30 transition text-center">
                        <input type="file" name="rejection_evidence" id="rejection_evidence" accept=".jpg,.jpeg,.png,.pdf"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               onchange="document.getElementById('rejection-evidence-filename').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file bukti penolakan'">
                        <div class="flex items-center justify-center gap-2 text-rose-600">
                          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                          </svg>
                          <span id="rejection-evidence-filename" class="text-xs font-medium truncate max-w-xs text-slate-600">
                            Klik untuk unggah file bukti penolakan
                          </span>
                        </div>
                      </div>
                    </div>
                  @endif

                  <div class="flex justify-center">
                    <button type="submit"
                            class="px-8 py-2.5 rounded-xl font-bold bg-[#B91C1C] hover:bg-[#991B1B] text-white text-xs sm:text-sm shadow-sm hover:shadow-md transition inline-flex items-center gap-2 cursor-pointer">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                      <span>Lanjutkan Tolak Laporan</span>
                    </button>
                  </div>
                </form>
              </div>

            </div>

          </div>
        @elseif ($isSelfExecuting)
          @php
            $actionRoute = match(auth()->user()->role) {
              'kabag' => route('kabag.complete-self', $report),
              'kasubag' => route('kasubag.submit-verification', $report),
              default => route('petugas.complete', $report),
            };
          @endphp
          <!-- COLUMN 2: PILIH TINDAKAN (Laporan Tertunda / Terlaksana) -->
          <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6">

            <h3 class="text-base font-bold text-[#0C3934]">Pilih Tindakan</h3>

            <form action="{{ $actionRoute }}" method="POST" enctype="multipart/form-data"
                  @submit="if (!selectedChecklist) { alert('Silakan pilih salah satu tindakan di atas terlebih dahulu (Laporan Tertunda atau Laporan Terlaksana).'); $event.preventDefault(); return false; }">
              @csrf
              <input type="hidden" name="execution_result" :value="selectedChecklist" required>

              <!-- KARTU TINDAKAN -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">

                <!-- 1. Laporan Tertunda -->
                <div @click="selectedChecklist = (selectedChecklist === 'tertunda' ? '' : 'tertunda')"
                     class="rounded-xl border p-3.5 cursor-pointer transition flex items-center justify-between gap-2.5 shadow-xs hover:shadow-md"
                     :class="selectedChecklist === 'tertunda' ? 'border-[#B91C1C] bg-[#FEE2E2] ring-2 ring-[#B91C1C]/40' : 'border-[#B91C1C] bg-[#FEE2E2] hover:bg-[#FECACA]'">
                  <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-[#EF4444] text-white flex items-center justify-center shrink-0 shadow-xs">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="min-w-0">
                      <div class="text-xs font-bold text-[#B91C1C] leading-tight">Laporan Tertunda</div>
                      <p class="text-[10px] sm:text-[10.5px] text-[#B91C1C] mt-0.5 leading-snug">Tugaskan kepada petugas sesuai bidang</p>
                    </div>
                  </div>
                  <!-- Radio indicator -->
                  <div class="w-4 h-4 rounded-full border-2 border-[#B91C1C] flex items-center justify-center transition shrink-0"
                       :class="selectedChecklist === 'tertunda' ? 'bg-[#B91C1C]' : 'bg-transparent'">
                    <span x-show="selectedChecklist === 'tertunda'" class="w-1.5 h-1.5 rounded-full bg-white"></span>
                  </div>
                </div>

                <!-- 2. Laporan Terlaksana -->
                <div @click="selectedChecklist = (selectedChecklist === 'terlaksana' ? '' : 'terlaksana')"
                     class="rounded-xl border p-3.5 cursor-pointer transition flex items-center justify-between gap-2.5 shadow-xs hover:shadow-md"
                     :class="selectedChecklist === 'terlaksana' ? 'border-[#15803D] bg-[#DCFCE7] ring-2 ring-[#15803D]/40' : 'border-[#15803D] bg-[#DCFCE7] hover:bg-[#BBF7D0]'">
                  <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-[#22C55E] text-white flex items-center justify-center shrink-0 shadow-xs">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="min-w-0">
                      <div class="text-xs font-bold text-[#15803D] leading-tight">Laporan Terlaksana</div>
                      <p class="text-[10px] sm:text-[10.5px] text-[#15803D] mt-0.5 leading-snug">Ambil alih penanganan laporan ini</p>
                    </div>
                  </div>
                  <!-- Radio indicator -->
                  <div class="w-4 h-4 rounded-full border-2 border-[#15803D] flex items-center justify-center transition shrink-0"
                       :class="selectedChecklist === 'terlaksana' ? 'bg-[#15803D]' : 'bg-transparent'">
                    <span x-show="selectedChecklist === 'terlaksana'" class="w-1.5 h-1.5 rounded-full bg-white"></span>
                  </div>
                </div>

              </div>

              <!-- Callout info -->
              <div class="mt-5 p-3.5 rounded-xl bg-[#DBEAFE] flex items-start gap-2.5 text-[#1E40AF]">
                <div class="w-4 h-4 rounded-full bg-[#1D4ED8] text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">i</div>
                <span class="text-[11.5px] leading-snug">Pilih salah satu tindakan di atas untuk melanjutkan proses penanganggan laporan</span>
              </div>

              <hr class="border-slate-200 my-5">

              <!-- Catatan / Komentar -->
              <label class="block text-base font-bold text-[#0C3934]">Catatan / Komentar</label>
              <textarea rows="4" maxlength="500" name="notes"
                        @input="notesCount = $event.target.value.length"
                        placeholder="Tambahkan catatan atau komentar"
                        class="mt-3 w-full px-4 py-3 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-[#2E978E] focus:outline-none transition"></textarea>
              <div class="text-right text-[11px] text-slate-400 mt-1.5 pr-1" x-text="notesCount + '/500'">0/500</div>

              <!-- Form Input File Bukti Laporan (Opsional) -->
              <div class="mt-4">
                <label class="block text-xs font-bold uppercase tracking-wider text-[#0C3934] mb-1.5">
                  Bukti Laporan <span class="text-slate-400 text-[10px] font-normal normal-case">(Opsional - JPG, PNG, PDF maks 5MB)</span>
                </label>
                <div class="relative border-2 border-dashed border-slate-200 hover:border-[#2E978E] rounded-xl p-3.5 bg-slate-50/50 hover:bg-[#F0FDFA] transition text-center group cursor-pointer">
                  <input type="file" name="evidence" id="evidence_self_execute" accept=".jpg,.jpeg,.png,.pdf"
                         class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                         onchange="document.getElementById('evidence-self-filename').textContent = this.files[0] ? this.files[0].name : 'Klik atau seret file bukti untuk mengunggah'">
                  <div class="flex items-center justify-center gap-2 text-slate-500 group-hover:text-[#166E65]">
                    <svg class="w-4 h-4 shrink-0 text-[#2E978E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span id="evidence-self-filename" class="text-xs font-medium truncate max-w-xs text-slate-600">
                      Klik atau seret file bukti untuk mengunggah
                    </span>
                  </div>
                </div>
              </div>

              <!-- Tombol -->
              <div class="flex justify-center mt-5">
                <button type="submit"
                        class="px-10 py-2.5 rounded-xl font-bold bg-[#166E65] hover:bg-[#0C3934] text-white text-xs sm:text-sm shadow-sm hover:shadow-md transition inline-flex items-center gap-2 cursor-pointer">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                  <span>Lanjutkan</span>
                </button>
              </div>
            </form>

          </div>
        @else
          @php
            $assignedUser = null;
            $assignedAt = null;
            $completedAt = null;

            if ($report->status === 'ditolak') {
                $rejectionDisposition = $report->dispositions->sortByDesc('id')->first(function ($d) {
                    return str_contains(strtolower($d->notes ?? ''), 'ditolak') || ($d->receiver_id === null && $d->sender?->role === 'kabag');
                });
                $assignedUser = $rejectionDisposition?->sender ?? $report->dispositions->first()?->sender ?? auth()->user();
                $assignedAt = $rejectionDisposition?->created_at ?? $report->updated_at;
            } elseif ($report->handler) {
                $assignedUser = $report->handler;
                $assignmentDisp = $report->dispositions->first(function ($d) use ($report) {
                    return $d->receiver_id === $report->handler_id || ($d->sender_id === $report->handler_id && $d->receiver_id === $report->handler_id);
                });
                $assignedAt = $assignmentDisp?->created_at ?? $report->updated_at;
            } else {
                $lastDisp = $report->dispositions->first();
                $assignedUser = $lastDisp?->receiver ?? $lastDisp?->sender ?? auth()->user();
                $assignedAt = $lastDisp?->created_at ?? $report->updated_at;
            }

            if ($report->status === 'selesai') {
                $completedAt = $report->updated_at;
            }

            $jabatanText = $assignedUser?->position ?? ($assignedUser?->role ? ucfirst($assignedUser->role) : 'Petugas');
            if ($assignedUser?->role === 'petugas' && $assignedUser?->subDepartment && !str_contains(strtolower($jabatanText), strtolower($assignedUser->subDepartment->name))) {
                $jabatanText .= ' ' . strtolower($assignedUser->subDepartment->name);
            }
          @endphp

          <!-- COLUMN 2: INFORMASI PETUGAS -->
          <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6">

            <!-- Header -->
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-[#CCFBF1] text-[#0F766E] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              </div>
              <h3 class="text-lg font-bold text-[#0C3934]">Informasi Petugas</h3>
            </div>

            <!-- Kartu petugas -->
            <div class="mt-4 rounded-xl border border-slate-200 bg-[#F8FFFE] p-4">

              <!-- Nama + bidang -->
              <div class="flex items-center gap-3 pb-3 border-b border-slate-200">
                <div class="w-10 h-10 rounded-full bg-[#CCFBF1] text-[#0C3934] flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-3.3 0-8 1.7-8 5v1h16v-1c0-3.3-4.7-5-8-5z"/></svg>
                </div>
                <div class="min-w-0">
                  <div class="text-sm font-bold text-[#0C3934] leading-tight">{{ $assignedUser?->name ?? 'Belum ditentukan' }}</div>
                  <div class="text-xs text-slate-700 mt-0.5">Bidang {{ $assignedUser?->subDepartment?->name ?? 'Dekanat' }}</div>
                </div>
              </div>

              <!-- Grid 2 kolom -->
              <div class="grid grid-cols-2 gap-x-4 gap-y-4 mt-4">

                <!-- NIP/NISN -->
                <div class="flex items-start gap-2.5 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-[#CCFBF1] text-[#0F766E] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="text-sm font-bold text-[#0C3934] leading-tight">NIP/NISN</div>
                    <div class="text-xs text-slate-700 mt-0.5 break-all">{{ $assignedUser?->identity_number ?? $assignedUser?->nip ?? '-' }}</div>
                  </div>
                </div>

                <!-- Email -->
                <div class="flex items-start gap-2.5 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-[#CCFBF1] text-[#0F766E] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="text-sm font-bold text-[#0C3934] leading-tight">Email</div>
                    <div class="text-xs text-slate-700 mt-0.5 break-all">{{ $assignedUser?->email ?? '-' }}</div>
                  </div>
                </div>

                <!-- Jabatan -->
                <div class="flex items-start gap-2.5 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-[#CCFBF1] text-[#0F766E] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="text-sm font-bold text-[#0C3934] leading-tight">Jabatan</div>
                    <div class="text-xs text-slate-700 mt-0.5">{{ $jabatanText }}</div>
                  </div>
                </div>

                <!-- Telepon -->
                <div class="flex items-start gap-2.5 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-[#CCFBF1] text-[#0F766E] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                  </div>
                  <div class="min-w-0">
                    <div class="text-sm font-bold text-[#0C3934] leading-tight">Telepon</div>
                    <div class="text-xs text-slate-700 mt-0.5">{{ $assignedUser?->phone_number ?? $assignedUser?->phone ?? $assignedUser?->no_telp ?? '-' }}</div>
                  </div>
                </div>
              </div>

              <!-- Waktu -->
              <div class="mt-5 rounded-lg bg-[#E6F7F4] px-4 py-3 grid grid-cols-2 relative">
                <div class="absolute left-1/2 top-3 bottom-3 w-px bg-[#BFE8E1]"></div>

                <div class="flex items-start gap-2.5 pr-3 min-w-0">
                  <svg class="w-6 h-6 text-[#0F766E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v5l3 2"/></svg>
                  <div class="min-w-0">
                    <div class="text-xs text-slate-600 leading-tight">Ditugaskan pada</div>
                    <div class="text-xs text-slate-800 mt-0.5 leading-snug">
                      @if ($assignedAt)
                        {{ $assignedAt->translatedFormat('d M Y') }}<br>{{ $assignedAt->format('H:i A') }}
                      @else
                        —
                      @endif
                    </div>
                  </div>
                </div>

                <div class="flex items-start gap-2.5 pl-4 min-w-0">
                  <svg class="w-6 h-6 text-[#0F766E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 12.5l2.5 2.5 4.5-5"/></svg>
                  <div class="min-w-0">
                    <div class="text-xs text-slate-600 leading-tight">Diselesaikan pada</div>
                    <div class="text-xs text-slate-800 mt-0.5 leading-snug">
                      @if ($completedAt)
                        {{ $completedAt->translatedFormat('d M Y') }}<br>{{ $completedAt->format('H:i A') }}
                      @else
                        —
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Callout info -->
            <div class="mt-4 p-3.5 rounded-lg bg-[#DBEAFE] flex items-start gap-2.5 text-[#1E40AF]">
              <div class="w-4 h-4 rounded-full bg-[#1D4ED8] text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">i</div>
              <span class="text-[11.5px] leading-snug">Informasi kontak petugas dapat digunakan untuk koordinasi terkait penanganan laporan.</span>
            </div>



          </div>
        @endif

      </div>

    </main>

  </div>

  @if (!$isKasubag && !$isPetugas)
  <!-- ================= POPUP MODAL DISPOSISI KE KASUBAG ================= -->
  <div x-show="showKasubagModal" style="display: none;"
       class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
       @keydown.escape.window="showKasubagModal = false"
       role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative transform transition-all"
         @click.away="showKasubagModal = false">

      <!-- Close Button -->
      <button type="button" @click="showKasubagModal = false"
              class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition"
              title="Tutup">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>

      <!-- Header -->
      <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100 mb-5">
        <div class="w-11 h-11 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#047857] flex items-center justify-center shrink-0 shadow-xs">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
          <h3 class="text-base sm:text-lg font-bold text-[#0C3934] leading-tight">Disposisi Laporan Ke Kasubag</h3>
          <p class="text-xs text-slate-500 mt-0.5">Pilih Kasubag penerima dan sertakan instruksi penanganan</p>
        </div>
      </div>

      <!-- Form Disposisi Kasubag -->
      <form action="{{ route('kabag.dispose', $report) }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="target_type" value="kasubag">

        <!-- Ringkasan Laporan Singkat -->
        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs flex items-center justify-between">
          <div>
            <span class="text-slate-400 font-medium">Laporan:</span>
            <span class="font-bold text-slate-800 ml-1">#{{ $report->ticket_code }}</span>
          </div>
          <div class="text-right">
            <span class="text-slate-400 font-medium">Bidang Asal:</span>
            <span class="font-bold text-[#0a8080] ml-1">{{ $report->subDepartment?->name ?? 'Dekanat' }}</span>
          </div>
        </div>

        <!-- DAFTAR PILIHAN KASUBAG (Nama Kasubag & Nama Bidangnya) -->
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
            Pilih Kasubag Tujuan <span class="text-red-500">*</span>
          </label>
          <div class="space-y-2 max-h-56 overflow-y-auto custom-scroll pr-1">
            @forelse ($kasubags as $kasubag)
              @php
                $isMatchingDept = $report->sub_department_id && $kasubag->sub_department_id == $report->sub_department_id;
              @endphp
              <label class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition hover:bg-slate-50"
                     :class="selectedKasubagId == '{{ $kasubag->id }}' ? 'border-[#0a8080] bg-[#ECFDF5] ring-1 ring-[#0a8080]' : 'border-slate-200'">
                <div class="flex items-center gap-3 min-w-0">
                  <input type="radio" name="receiver_id" value="{{ $kasubag->id }}"
                         x-model="selectedKasubagId"
                         class="text-[#0a8080] focus:ring-[#0a8080] shrink-0" required>
                  <div class="min-w-0 text-left">
                    <!-- NAMA KASUBAG -->
                    <div class="font-bold text-xs sm:text-sm text-slate-900 truncate">
                      {{ $kasubag->name }}
                    </div>
                    <!-- NAMA BIDANG -->
                    <div class="text-[11px] font-semibold text-[#0D9488] flex items-center gap-1.5 mt-0.5">
                      <span class="w-1.5 h-1.5 rounded-full bg-[#0D9488]"></span>
                      <span>Bidang: {{ $kasubag->subDepartment?->name ?? 'Dekanat' }}</span>
                    </div>
                  </div>
                </div>
                @if ($isMatchingDept)
                  <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#CCFBF1] text-[#0F766E] border border-[#99F6E4] shrink-0">
                    Sesuai Bidang
                  </span>
                @endif
              </label>
            @empty
              <div class="p-4 text-center text-xs text-slate-400 bg-slate-50 rounded-xl">
                Belum ada data akun Kasubag yang terdaftar di sistem.
              </div>
            @endforelse
          </div>
        </div>

        <!-- Catatan / Instruksi Disposisi -->
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
            Catatan / Instruksi Disposisi <span class="text-red-500">*</span>
          </label>
          <textarea rows="3" maxlength="500" name="notes" required
                    placeholder="Contoh: Mohon segera ditindaklanjuti dan dikoordinasikan dengan teknisi lapangan..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-[#0a8080] focus:outline-none transition"></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
          <button type="button" @click="showKasubagModal = false"
                  class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-xs hover:bg-slate-50 transition">
            Batal
          </button>
          <button type="submit"
                  class="px-5 py-2.5 rounded-xl bg-[#047857] hover:bg-[#065F46] text-white font-bold text-xs shadow-md transition flex items-center gap-1.5 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>Kirim Disposisi Kasubag</span>
          </button>
        </div>

      </form>

    </div>
  </div>
  @endif

  @if (!$isPetugas)
  <!-- ================= POPUP MODAL KIRIM KE PETUGAS ================= -->
  <div x-show="showPetugasModal" style="display: none;"
       class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
       @keydown.escape.window="showPetugasModal = false"
       role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative transform transition-all"
         @click.away="showPetugasModal = false">

      <!-- Close Button -->
      <button type="button" @click="showPetugasModal = false"
              class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition"
              title="Tutup">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>

      <!-- Header -->
      <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100 mb-5">
        <div class="w-11 h-11 rounded-2xl bg-[#DBEAFE] border border-[#BAE6FD] text-[#0369A1] flex items-center justify-center shrink-0 shadow-xs">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
          <h3 class="text-base sm:text-lg font-bold text-[#0C3934] leading-tight">Tugaskan Laporan Ke Petugas</h3>
          <p class="text-xs text-slate-500 mt-0.5">Pilih Petugas pelaksana dan sertakan rincian penugasan</p>
        </div>
      </div>

      <!-- Form Kirim ke Petugas -->
      <form action="{{ $isKasubag ? route('kasubag.dispose', $report) : route('kabag.dispose', $report) }}" method="POST" class="space-y-4">
        @csrf
        @if (!$isKasubag)
          <input type="hidden" name="target_type" value="petugas">
        @endif

        <!-- Ringkasan Laporan Singkat -->
        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs flex items-center justify-between">
          <div>
            <span class="text-slate-400 font-medium">Laporan:</span>
            <span class="font-bold text-slate-800 ml-1">#{{ $report->ticket_code }}</span>
          </div>
          <div class="text-right">
            <span class="text-slate-400 font-medium">Bidang Asal:</span>
            <span class="font-bold text-[#0369A1] ml-1">{{ $report->subDepartment?->name ?? 'Dekanat' }}</span>
          </div>
        </div>

        <!-- DAFTAR PILIHAN PETUGAS (Nama Petugas & Nama Bidangnya) -->
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
            Pilih Petugas Pelaksana <span class="text-red-500">*</span>
          </label>
          <div class="space-y-2 max-h-56 overflow-y-auto custom-scroll pr-1">
            @forelse ($filteredPetugas as $p)
              @php
                $isMatchingDept = $report->sub_department_id && $p->sub_department_id == $report->sub_department_id;
              @endphp
              <label class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition hover:bg-slate-50"
                     :class="selectedPetugasId == '{{ $p->id }}' ? 'border-[#0284C7] bg-[#F0F9FF] ring-1 ring-[#0284C7]' : 'border-slate-200'">
                <div class="flex items-center gap-3 min-w-0">
                  <input type="radio" name="receiver_id" value="{{ $p->id }}"
                         x-model="selectedPetugasId"
                         class="text-[#0284C7] focus:ring-[#0284C7] shrink-0" required>
                  <div class="min-w-0 text-left">
                    <!-- NAMA PETUGAS -->
                    <div class="font-bold text-xs sm:text-sm text-slate-900 truncate">
                      {{ $p->name }}
                    </div>
                    <!-- NAMA BIDANG -->
                    <div class="text-[11px] font-semibold text-[#0369A1] flex items-center gap-1.5 mt-0.5">
                      <span class="w-1.5 h-1.5 rounded-full bg-[#0EA5E9]"></span>
                      <span>Bidang: {{ $p->subDepartment?->name ?? 'Dekanat' }}</span>
                    </div>
                  </div>
                </div>
                @if ($isMatchingDept)
                  <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#E0F2FE] text-[#0369A1] border border-[#BAE6FD] shrink-0">
                    Sesuai Bidang
                  </span>
                @endif
              </label>
            @empty
              <div class="p-4 text-center text-xs text-slate-400 bg-slate-50 rounded-xl">
                Belum ada data akun petugas yang terdaftar pada bidang {{ $report->subDepartment?->name ?? 'ini' }}.
              </div>
            @endforelse
          </div>
        </div>

        <!-- Catatan / Instruksi Petugas -->
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
            Instruksi Penugasan <span class="text-red-500">*</span>
          </label>
          <textarea rows="3" maxlength="500" name="notes" required
                    placeholder="Contoh: Segera lakukan pemeriksaan lapangan dan laporkan hasil penanganan..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-[#0284C7] focus:outline-none transition"></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
          <button type="button" @click="showPetugasModal = false"
                  class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-xs hover:bg-slate-50 transition">
            Batal
          </button>
          <button type="submit"
                  class="px-5 py-2.5 rounded-xl bg-[#0284C7] hover:bg-[#0369A1] text-white font-bold text-xs shadow-md transition flex items-center gap-1.5 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>Kirim ke Petugas</span>
          </button>
        </div>

      </form>

    </div>
  </div>
  @endif

</body>
</html>
