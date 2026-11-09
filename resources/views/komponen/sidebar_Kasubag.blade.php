<!-- ================= SIDEBAR NAVIGATION ================= -->
<!-- Mobile Sidebar Backdrop Overlay -->
<div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden transition-opacity"></div>

<!-- Sidebar Container -->
<aside id="sidebarMenu" class="fixed top-0 left-0 h-screen w-52 bg-[#2E978E] text-white flex flex-col justify-between py-5 px-4 z-50 transform -translate-x-full lg:translate-x-0 lg:transform-none shadow-xl lg:shadow-none shrink-0 overflow-y-auto transition-transform duration-300">

  <div class="space-y-6">
    <!-- Brand / University Emblem -->
    <div class="flex items-center gap-3 pb-4 border-b border-white/20">
      <div class="w-12 h-12 flex items-center justify-center shrink-0">
        @if (file_exists(public_path('assets/unair_logo.png')))
          <img src="{{ asset('assets/unair_logo.png') }}" alt="UNAIR Logo" class="w-full h-full object-contain">
        @else
          <span class="font-extrabold text-[#2E978E] text-2xl">U</span>
        @endif
      </div>
      <div class="min-w-0">
        <h1 class="text-base font-bold text-white tracking-tight leading-tight truncate">E-Lapor FIKKIA</h1>
        <p class="text-[11px] text-[#CCFBF1] font-medium leading-tight truncate">
          {{ auth()->user()->subDepartment?->name ? 'Kasubag ' . auth()->user()->subDepartment->name : 'Panel Kasubag' }}
        </p>
      </div>
      <!-- Mobile Close Button -->
      <button type="button" onclick="toggleSidebar()" class="lg:hidden ml-auto p-1.5 rounded-lg bg-white/10 text-white hover:bg-white/20" title="Tutup Menu">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="space-y-1.5 font-['Plus_Jakarta_Sans',sans-serif]">
      <!-- 1. Dashboard -->
      <a href="{{ route('kasubag.dashboard') }}"
         class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] transition {{ request()->routeIs('kasubag.dashboard') ? 'bg-[#0D6E76] text-white font-bold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }}">
        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span class="truncate">Dashboard</span>
      </a>

      <!-- 2. Laporan Masuk -->
      <a href="{{ route('kasubag.status') }}"
         class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] transition {{ request()->routeIs('kasubag.status*') ? 'bg-[#0D6E76] text-white font-bold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }}">
        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <span class="truncate">Laporan Masuk</span>
      </a>

      <!-- 3. Riwayat Laporan -->
      <a href="{{ route('kasubag.history') }}"
         class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] transition {{ request()->routeIs('kasubag.history*') ? 'bg-[#0D6E76] text-white font-bold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }}">
        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="truncate">Riwayat Laporan</span>
      </a>

      <!-- 4. Manajemen Petugas -->
      <a href="{{ route('kasubag.petugas.index') }}"
         class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] transition {{ request()->routeIs('kasubag.petugas*') ? 'bg-[#0D6E76] text-white font-bold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }}">
        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span class="truncate">Manajemen Petugas</span>
      </a>
    </nav>
  </div>

  <!-- Bottom Session / Logout -->
  <div class="pt-4 border-t border-white/20">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit"
              class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] font-semibold text-rose-100 hover:bg-rose-500/20 hover:text-white transition">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <span>Keluar</span>
      </button>
    </form>
  </div>
</aside>
