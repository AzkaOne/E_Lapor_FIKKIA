
<header class="w-full bg-[#2E978E] text-white py-2 sm:py-2.5 lg:py-2 px-4 sm:px-8 lg:px-12 flex items-center justify-between gap-4 shadow-sm z-30 relative shrink-0">

  <div class="flex items-center gap-3 sm:gap-5">
    <a href="{{ route('home') }}" class="flex items-center gap-3 sm:gap-4 hover:opacity-95 transition" title="Kembali ke Beranda">
      <!-- Exact Header Logo from FigCraft -->
      <img src="{{ asset('assets/unair_header_logo.png') }}" alt="Logo UNAIR" class="h-10 sm:h-12 md:h-13 w-auto object-contain">

      <!-- Divider Line -->
      <div class="h-9 sm:h-11 w-[1.5px] bg-white/30 hidden sm:block"></div>

      <!-- Faculty Name Text (Plus Jakarta Sans Bold) -->
      <div class="hidden sm:block font-['Plus_Jakarta_Sans',sans-serif] text-xs sm:text-[14px] lg:text-[15px] font-bold leading-tight text-white/95 max-w-[280px]">
        Fakultas Ilmu Kesehatan,<br>Kedokteran, dan Ilmu Alam
      </div>
    </a>
  </div>

  <!-- Right: Accreditation Badges & Back Button -->
  <div class="flex items-center gap-3 sm:gap-5">
    <div class="hidden md:flex items-center">
      <!-- Exact Accreditation Logos from FigCraft -->
      <img src="{{ asset('assets/header_accreditation.png') }}" alt="Akreditasi & Penghargaan UNAIR" class="h-12 lg:h-14 w-auto object-contain opacity-95">
    </div>

  </div>
</header>
