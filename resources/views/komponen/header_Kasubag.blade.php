<!-- ================= TOP HEADER BAR ================= -->
<header class="bg-[#2E978E] text-white h-16 sm:h-20 fixed top-0 left-0 right-0 lg:left-52 z-40 shadow-xs w-auto">
  <div class="px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between gap-4">

    <!-- Left: Mobile Toggle & Accreditation Logos -->
    <div class="flex items-center gap-3 sm:gap-6">
      <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition focus:outline-none" title="Buka Menu">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- Institutional Logos (Desktop & Tablet) -->
      <div class="hidden sm:flex items-center">
        @if (file_exists(public_path('assets/header_accreditation.png')))
          <img src="{{ asset('assets/header_accreditation.png') }}" alt="Akreditasi & Mitra UNAIR" class="h-10 sm:h-12 w-auto object-contain opacity-95">
        @elseif (file_exists(public_path('assets/accreditation_logos.png')))
          <img src="{{ asset('assets/accreditation_logos.png') }}" alt="Akreditasi UNAIR" class="h-10 sm:h-12 w-auto object-contain opacity-95">
        @else
          <div class="flex items-center gap-3 text-[11px] font-semibold text-white/95">
            <span class="px-2.5 py-1 rounded-md bg-white/15 tracking-wide uppercase text-[10px]">Zona Integritas</span>
            <span class="px-2.5 py-1 rounded-md bg-white/15 tracking-wide text-[10px]">SMARTVERSITAS</span>
            <span class="px-2.5 py-1 rounded-md bg-white/15 tracking-wide text-[10px]">TOP #287 QS</span>
          </div>
        @endif
      </div>

      <!-- Mobile Title -->
      <div class="sm:hidden flex items-center gap-2">
        <span class="text-xs font-bold tracking-tight text-white uppercase">E-Lapor KASUBAG</span>
      </div>
    </div>

    <!-- Right: Notifications & User Profile -->
    <div class="flex items-center gap-3 sm:gap-4">
      <!-- Notification Bell -->
      <a href="{{ route('kasubag.status') }}" class="relative p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition" title="Laporan Masuk">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if (($counts['masuk'] ?? 0) > 0)
          <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-[#EF4444] rounded-full ring-2 ring-[#2E978E]"></span>
        @endif
      </a>

      <!-- User Profile Badge -->
      <div class="flex items-center gap-2 pl-2 sm:pl-3 border-l border-white/20">
        <div class="w-8 h-8 rounded-full bg-[#0F766E] border border-teal-300 flex items-center justify-center text-white shrink-0 font-bold shadow-xs">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>

        <div class="hidden md:flex flex-col text-left">
          <span class="text-xs font-bold text-white leading-tight truncate max-w-[140px]">
            {{ auth()->user()->name ?? 'Kasubag' }}
          </span>
          <span class="text-[10px] text-teal-200 capitalize leading-tight truncate max-w-[140px]">
            Kasubag {{ auth()->user()->subDepartment?->name ?? 'Unit' }}
          </span>
        </div>
      </div>
    </div>

  </div>
</header>
