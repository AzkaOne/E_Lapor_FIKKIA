<header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
    <div class="flex items-center gap-4">
        <a href="/" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#1E3A5F] rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md">
                E
            </div>
            <div>
                <span class="text-base font-black text-[#1E3A5F] tracking-tight">E-LAPOR</span>
                <span class="text-[10px] font-bold text-amber-600 block uppercase tracking-wider">FIKKIA UNAIR</span>
            </div>
        </a>

        @auth
            <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                @if (auth()->user()->role === 'kabag') bg-[#1E3A5F]/10 text-[#1E3A5F]
                @elseif (auth()->user()->role === 'kasubag') bg-amber-100 text-amber-800
                @elseif (auth()->user()->role === 'petugas') bg-blue-100 text-blue-800
                @else bg-slate-100 text-slate-700 @endif">
                Peran: {{ auth()->user()->role }}
            </span>
        @endauth
    </div>

    @auth
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <div class="text-sm font-bold text-slate-900 leading-tight">{{ auth()->user()->name }}</div>
                <div class="text-xs text-slate-500 font-mono">{{ auth()->user()->email }}</div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    @endauth
</header>