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
                <img src="{{ asset('assets/unair_logo.png') }}"
                    alt="UNAIR Logo"
                    class="w-full h-full object-contain">
            @else
                <span class="font-extrabold text-[#2E978E] text-2xl">U</span>
            @endif
        </div>
      <div class="min-w-0">
        <h1 class="text-base font-bold text-white tracking-tight leading-tight truncate">E-Lapor FIKKIA</h1>
        <p class="text-[11px] text-[#CCFBF1] font-medium leading-tight">Panel Kepala Bagian</p>
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
      <a href="{{ route('kabag.dashboard') }}"
         class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] transition {{ request()->routeIs('kabag.dashboard') ? 'bg-[#166E65] text-white font-bold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }}">
        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span class="truncate">Dashboard</span>
      </a>

      <!-- 2. Laporan Pelayanan -->
      <a href="{{ route('kabag.laporan-pelayanan') }}"
         class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] transition {{ request()->routeIs('kabag.laporan-pelayanan*') ? 'bg-[#166E65] text-white font-bold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }}">
        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <span class="truncate">Laporan Pelayanan</span>
      </a>

      <!-- 3. Laporan Whistleblower -->
      <a href="{{ route('kabag.whistleblowing') }}"
         class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] transition {{ request()->routeIs('kabag.whistleblowing*') ? 'bg-[#166E65] text-white font-bold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }}">
        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <span class="truncate">Laporan Whistleblower</span>
      </a>

      <!-- 4. Manajemen Kasubag -->
      <a href="{{ route('kabag.users.index') }}"
         class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] transition {{ request()->routeIs('kabag.users.*') ? 'bg-[#166E65] text-white font-bold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }}">
        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <span class="truncate">Manajemen Kasubag</span>
      </a>

      <!-- 5. Riwayat Laporan -->
      <a href="{{ route('kabag.history') }}"
         class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-[13px] transition {{ request()->routeIs('kabag.history') ? 'bg-[#166E65] text-white font-bold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }}">
        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span class="truncate">Riwayat Laporan</span>
      </a>
    </nav>
  </div>

  <!-- Sidebar Footer -->
  <div class="pt-4 border-t border-white/20 text-[11px] text-white/80 font-['Plus_Jakarta_Sans',sans-serif]">
    <p class="font-bold text-white leading-tight">E-Lapor FIKKIA UNAIR</p>
    <p class="text-[10px] text-[#CCFBF1] mt-0.5">Versi Kepala Bagian (KABAG)</p>
  </div>

</aside>

<script>
  if (typeof toggleSidebar === 'undefined') {
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebarMenu');
      const backdrop = document.getElementById('sidebarBackdrop');
      if (sidebar && backdrop) {
        const isHidden = sidebar.classList.contains('-translate-x-full');
        if (isHidden) {
          sidebar.classList.remove('-translate-x-full');
          backdrop.classList.remove('hidden');
        } else {
          sidebar.classList.add('-translate-x-full');
          backdrop.classList.add('hidden');
        }
      }
    }
  }
</script>
