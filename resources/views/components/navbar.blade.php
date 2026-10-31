<header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
    <div class="flex items-center gap-4">
        <a href="/" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#0a8080] rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md">
                E
            </div>
            <div>
                <span class="text-base font-black text-[#0c3934] tracking-tight">E-LAPOR</span>
                <span class="text-[10px] font-bold text-[#0a8080] block uppercase tracking-wider">FIKKIA UNAIR</span>
            </div>
        </a>

        @auth
            <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                @if (auth()->user()->role === 'kabag') bg-[#ecfdf5] text-[#064e3b] border border-[rgba(167,243,208,0.8)]
                @elseif (auth()->user()->role === 'kasubag') bg-amber-50 text-amber-800 border border-amber-200
                @elseif (auth()->user()->role === 'petugas') bg-teal-50 text-teal-800 border border-teal-200
                @else bg-slate-100 text-slate-700 @endif">
                Peran: {{ auth()->user()->role }}
            </span>
        @endauth
    </div>

    @auth
        <div class="flex items-center gap-4">
            <!-- Time Indicator (Figma 106:4568) -->
            <div class="hidden lg:flex items-center text-xs font-medium text-slate-500 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-200/60">
                <svg class="w-3.5 h-3.5 mr-1.5 text-[#0a8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ date('l, d M Y') }}</span>
            </div>

            <!-- Profile Badge -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#f0fdfa] border border-[#ccfbf1] text-[#0a8080] flex items-center justify-center font-bold text-sm shadow-xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="text-right hidden sm:block">
                    <div class="text-xs font-bold text-[#1e293b] leading-tight">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-slate-500 font-medium">{{ auth()->user()->subDepartment?->name ?? ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 sm:px-3 sm:py-1.5 border border-slate-200 hover:bg-slate-100 text-slate-600 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        </div>
    @endauth
</header>