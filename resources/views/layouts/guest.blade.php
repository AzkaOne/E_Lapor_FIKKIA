<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'E-LAPOR FIKKIA UNAIR' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between">
    <!-- Guest Institutional Header -->
    <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-12 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <a href="/" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#1E3A5F] rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md">
                E
            </div>
            <div>
                <span class="text-lg font-black text-[#1E3A5F] tracking-tight">E-LAPOR</span>
                <span class="text-xs font-semibold text-amber-600 block leading-none">FIKKIA UNAIR</span>
            </div>
        </a>

        <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600">
            <a href="/" class="hover:text-[#1E3A5F] transition">Beranda</a>
            <a href="/login" class="hover:text-[#1E3A5F] transition">Pengaduan Pelayanan</a>
            <a href="/whistleblowing" class="hover:text-[#1E3A5F] transition">Whistleblowing (Anonim)</a>
            <a href="/tracking" class="hover:text-[#1E3A5F] transition">Lacak Pengaduan</a>
        </nav>

        <div class="flex items-center gap-3">
            @auth
                <a href="/login" class="px-5 py-2.5 bg-[#1E3A5F] hover:bg-[#152a45] text-white rounded-xl text-sm font-bold shadow-md transition">
                    Ke Dashboard
                </a>
            @else
                <a href="/login" class="px-5 py-2.5 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-sm font-bold transition">
                    Masuk
                </a>
                <a href="/register" class="px-5 py-2.5 bg-[#1E3A5F] hover:bg-[#152a45] text-white rounded-xl text-sm font-bold shadow-md transition">
                    Registrasi Civitas
                </a>
            @endauth
        </div>
    </header>

    <main class="flex-grow flex flex-col">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Institutional Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 px-8 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} FIKKIA Universitas Airlangga (Kampus Mojo • Sobo • Giri Banyuwangi). Sistem Pengaduan & Layanan Aspirasi Terpadu.</p>
    </footer>
</body>
</html>