@auth
<aside class="w-64 bg-[#0c3934] text-white hidden md:flex flex-col justify-between shrink-0 p-5 shadow-xl">
    <div class="space-y-6">
        <!-- University Seal / Emblem Badge (Figma 106:4440) -->
        <div class="flex items-center gap-3 px-1 pb-4 border-b border-[rgba(15,118,110,0.5)]">
            <div class="w-10 h-10 rounded-full border-2 border-[rgba(52,211,153,0.4)] bg-[#082824] flex items-center justify-center text-white font-extrabold text-base shadow-inner">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <div class="text-sm font-bold text-white tracking-tight leading-tight">E-Lapor UNAIR</div>
                <div class="text-[11px] text-[rgba(94,234,212,0.8)] font-medium">FIKKIA Banyuwangi</div>
            </div>
        </div>

        <div class="space-y-1">
            <div class="text-[10px] font-bold uppercase tracking-wider text-[rgba(153,246,228,0.7)] px-3 pb-2">Navigasi Utama</div>

            @if (auth()->user()->role === 'pelapor')
                <a href="{{ route('pelapor.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('pelapor.dashboard') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Buat Laporan</span>
                </a>
                <a href="{{ route('pelapor.status') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('pelapor.status') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span>Status Laporan</span>
                </a>
                <a href="{{ route('pelapor.history') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('pelapor.history') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Riwayat Laporan</span>
                </a>
            @endif

            @if (auth()->user()->role === 'kabag')
                <a href="{{ route('kabag.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kabag.dashboard') || request()->routeIs('kabag.show') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard & Masuk</span>
                </a>
                <a href="{{ route('kabag.verifikasi.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kabag.verifikasi.*') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Verifikasi Hasil</span>
                </a>
                <a href="{{ route('kabag.my-reports') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kabag.my-reports') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Laporan Saya</span>
                </a>
                <a href="{{ route('kabag.whistleblowing') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kabag.whistleblowing*') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>Whistleblowing</span>
                </a>
                <a href="{{ route('kabag.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kabag.users.*') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Manajemen User</span>
                </a>
                <a href="{{ route('kabag.history') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kabag.history') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Riwayat Laporan</span>
                </a>
            @endif

            @if (auth()->user()->role === 'kasubag')
                <a href="{{ route('kasubag.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kasubag.dashboard') || request()->routeIs('kasubag.show') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <span>Laporan Masuk</span>
                </a>
                <a href="{{ route('kasubag.status') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kasubag.status') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span>Status Progres</span>
                </a>
                <a href="{{ route('kasubag.my-reports') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kasubag.my-reports') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Laporan Saya</span>
                </a>
                <a href="{{ route('kasubag.petugas.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kasubag.petugas.*') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Manajemen Petugas</span>
                </a>
                <a href="{{ route('kasubag.history') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('kasubag.history') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Riwayat Selesai</span>
                </a>
            @endif

            @if (auth()->user()->role === 'petugas')
                <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('petugas.dashboard') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <span>Penugasan Masuk</span>
                </a>
                <a href="{{ route('petugas.my-reports') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('petugas.my-reports') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Sedang Dikerjakan</span>
                </a>
                <a href="{{ route('petugas.history') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('petugas.history') ? 'bg-[#1a5b51] text-white shadow-xs font-semibold' : 'text-[#ccfbf1] hover:bg-[#1a5b51]/60' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Riwayat Penugasan</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Bottom Decoration Motto (Figma 106:4515) -->
    <div class="pt-6 border-t border-[rgba(15,118,110,0.5)] text-center space-y-1">
        <p class="text-[11px] text-[rgba(94,234,212,0.8)] font-medium leading-tight">Bersama Mewujudkan Pelayanan yang Lebih Baik</p>
        <p class="text-[10px] text-white/60 tracking-wider font-semibold uppercase">UNIVERSITAS AIRLANGGA</p>
    </div>
</aside>
@endauth