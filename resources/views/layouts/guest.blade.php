<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'E-LAPOR FIKKIA UNAIR' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'teal-brand': '#0a8080',
                        'teal-dark': '#0c3934',
                        'teal-active': '#1a5b51',
                        'teal-light': '#ccfbf1',
                        'emerald-surface': '#ecfdf5',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0a8080] selection:text-white">
    <!-- Guest Header matching Figma -->
    <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-12 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <a href="/" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#0a8080] rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md">
                E
            </div>
            <div>
                <span class="text-lg font-black text-[#0c3934] tracking-tight">E-LAPOR</span>
                <span class="text-xs font-semibold text-[#0a8080] block leading-none">FIKKIA UNAIR</span>
            </div>
        </a>

        <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600">
            <a href="/" class="hover:text-[#0a8080] transition">Beranda</a>
            <a href="/login" class="hover:text-[#0a8080] transition">Pengaduan Pelayanan</a>
            <a href="/whistleblowing" class="hover:text-[#0a8080] transition">Whistleblowing (Anonim)</a>
            <a href="/tracking" class="hover:text-[#0a8080] transition">Lacak Pengaduan</a>
        </nav>

        <div class="flex items-center gap-3">
            @auth
                <a href="/login" class="px-5 py-2.5 bg-[#0a8080] hover:bg-[#086666] text-white rounded-xl text-sm font-bold shadow-md transition">
                    Ke Dashboard
                </a>
            @else
                <a href="/login" class="px-5 py-2.5 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-sm font-bold transition">
                    Masuk
                </a>
                <a href="/register" class="px-5 py-2.5 bg-[#0a8080] hover:bg-[#086666] text-white rounded-xl text-sm font-bold shadow-md transition">
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
        <p>&copy; {{ date('Y') }} FIKKIA Universitas Airlangga (Kampus Mojo • Sobo • Giri Banyuwangi). Bersama Mewujudkan Pelayanan yang Lebih Baik.</p>
    </footer>
</body>
</html>