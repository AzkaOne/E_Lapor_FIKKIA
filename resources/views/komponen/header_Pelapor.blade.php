  <header class="w-full bg-[#2E978E] text-white h-14 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4 shadow-sm z-40 fixed top-0 left-0 right-0">
    <!-- Left: UNAIR Brand & Faculty Name -->
    <div class="flex items-center gap-3">
      <!-- Mobile Sidebar Toggle -->
      <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white focus:outline-none" title="Buka Menu">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 hover:opacity-95 transition" title="Beranda E-Lapor">
        <img src="{{ asset('assets/unair_header_logo.png') }}" alt="Logo UNAIR" class="h-8 sm:h-9 w-auto object-contain">
        <div class="h-7 w-[1px] bg-white/30 hidden sm:block"></div>
        <div class="hidden sm:block font-['Plus_Jakarta_Sans',sans-serif] text-xs font-bold leading-tight text-white/95 max-w-[240px]">
          Fakultas Ilmu Kesehatan,<br>Kedokteran, dan Ilmu Alam
        </div>
      </a>
    </div>

    <!-- Right: Accreditation, Notification & User Profile -->
    <div class="flex items-center gap-2.5 sm:gap-4">
      <!-- Accreditation Badges -->
      <div class=" xl:flex items-center">
        <img src="{{ asset('assets/header_accreditation.png') }}" alt="Akreditasi & Penghargaan UNAIR" class="h-12 w-auto object-contain opacity-95">
      </div>

      <!-- Notification Bell -->
      <button type="button" class="relative w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/90 hover:text-white transition cursor-pointer " title="Notifikasi">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 ring-2 "></span>
      </button>

      <!-- User Profile Badge -->
      <div class="flex items-center gap-2 pl-2 sm:pl-3 border-l border-white/20">
        <div class="w-8 h-8 rounded-full bg-[#0F766E] border border-teal-300 flex items-center justify-center text-white shrink-0 font-bold shadow-xs">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>
        <div class="hidden md:flex flex-col text-left">
          <span class="text-xs font-bold text-white leading-tight truncate max-w-[130px]">
            {{ auth()->user()->name }}
          </span>
          <span class="text-[10px] text-teal-200 capitalize leading-tight">
            {{ auth()->user()->position ?? 'Pelapor' }}
          </span>
        </div>

        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST" class="ml-1">
          @csrf
          <button type="submit" class="w-8 h-8 rounded-full bg-white/10 hover:bg-rose-500/80 hover:text-white transition flex items-center justify-center text-white/90" title="Keluar">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
          </button>
        </form>
      </div>
    </div>
  </header>
