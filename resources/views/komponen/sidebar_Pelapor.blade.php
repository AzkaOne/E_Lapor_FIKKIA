<!-- ================= SIDEBAR NAVIGATION ================= -->
<!-- Mobile Sidebar Backdrop Overlay -->
<div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-45 hidden lg:hidden transition-opacity"></div>

<!-- Sidebar Container -->
<aside id="sidebarMenu" class="fixed top-0 lg:top-14 left-0 h-screen lg:h-[calc(100vh-3.5rem)] w-52 bg-[#2E978E] text-white flex flex-col justify-between py-4 px-3 z-50 lg:z-30 transform -translate-x-full lg:translate-x-0 lg:transform-none shadow-lg lg:shadow-none shrink-0 overflow-y-auto custom-scroll transition-transform duration-300 lg:transition-none">

  <div class="space-y-3.5">
    <!-- Mobile Close Button -->
    <div class="flex items-center justify-between lg:hidden pb-2 border-b border-white/20">
      <span class="text-[11px] font-bold text-white tracking-wider uppercase">Menu</span>
      <button type="button" onclick="toggleSidebar()" class="p-1 rounded-md bg-white/10 text-white hover:bg-white/20" title="Tutup Menu">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Menu List -->
    <nav class="space-y-1 font-['Inter',sans-serif]">
      <!-- Item 1: Dashboard -->
      <a href="{{ route('pelapor.dashboard') }}" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg sm:rounded-xl {{ request()->routeIs('pelapor.dashboard') ? 'bg-[#0F6B60] text-white font-semibold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }} text-xs sm:text-[12.5px] transition">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span class="truncate">Dashboard</span>
      </a>

      <!-- Item 2: Riwayat Laporan -->
      <a href="{{ route('pelapor.history') }}" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg sm:rounded-xl {{ request()->routeIs('pelapor.history') ? 'bg-[#0F6B60] text-white font-semibold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }} text-xs sm:text-[12.5px] transition">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span class="truncate">Riwayat Laporan</span>
      </a>

      <!-- Item 3: Status Laporan -->
      <a href="{{ route('pelapor.status') }}" class="w-full flex items-center justify-between px-3 py-2 rounded-lg sm:rounded-xl {{ request()->routeIs('pelapor.status') ? 'bg-[#0F6B60] text-white font-semibold shadow-xs' : 'text-white/90 hover:bg-white/10 hover:text-white font-medium' }} text-xs sm:text-[12.5px] transition">
        <div class="flex items-center gap-2.5 truncate">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="truncate">Status Laporan</span>
        </div>
        @if (isset($activeReports) && $activeReports->count() > 0)
          <span class="ml-1.5 px-1.5 py-0.2 rounded-full text-[9.5px] font-bold bg-[#CCFBF1] text-[#0F766E] shrink-0">
            {{ $activeReports->count() }}
          </span>
        @endif
      </a>
    </nav>
  </div>

  <!-- Sidebar Bottom Info -->
  <div class="pt-3 border-t border-white/20 text-[10.5px] text-white/75 font-['Plus_Jakarta_Sans',sans-serif]">
    <p class="font-semibold text-white leading-tight">E-Lapor FIKKIA UNAIR</p>
    <p class="text-[9.5px] text-white/60 mt-0.5">Versi Sivitas Akademika</p>
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
