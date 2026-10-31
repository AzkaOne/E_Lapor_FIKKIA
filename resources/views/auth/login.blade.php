<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - E-LAPOR FIKKIA UNAIR</title>
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
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0a8080] selection:text-white">
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shadow-xs">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-[#0a8080] flex items-center justify-center text-white font-black text-xl shadow-md">
                E
            </div>
            <div>
                <h1 class="text-base font-black text-[#0c3934] leading-tight tracking-tight">E-LAPOR FIKKIA</h1>
                <p class="text-xs text-slate-500 font-medium">Universitas Airlangga Kampus Banyuwangi</p>
            </div>
        </div>
        <a href="{{ route('home') }}" class="text-xs font-bold text-slate-600 hover:text-[#0a8080] transition flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Beranda</span>
        </a>
    </header>

    <!-- Main Container -->
    <main class="flex-grow flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-slate-200/80 p-8 sm:p-10">
            <div class="text-center mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#ecfdf5] text-[#064e3b] border border-[rgba(167,243,208,0.8)] mb-3">
                    Portal Autentikasi Civitas
                </span>
                <h2 class="text-2xl font-black text-[#0c3934] tracking-tight">Masuk ke E-Lapor</h2>
                <p class="text-xs text-slate-500 mt-1">Gunakan Email Kampus @unair.ac.id atau NIM/NIP</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email / NIM / NIP</label>
                    <input type="text" name="login" value="{{ old('login', old('email')) }}" required autofocus
                        placeholder="contoh: nama@unair.ac.id atau NIM"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080] transition text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                    <input type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080] transition text-sm">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#0a8080] focus:ring-[#0a8080] border-slate-300">
                        <span class="ml-2 font-medium">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 bg-[#0a8080] hover:bg-[#086666] text-white font-bold rounded-xl shadow-md hover:shadow-lg transition duration-200 text-sm">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-600 font-medium">
                Belum memiliki akun Pelapor?
                <a href="{{ route('register') }}" class="font-bold text-[#0a8080] hover:text-[#086666] ml-1">Daftar Akun Baru</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-100">
        &copy; 2025 FIKKIA Universitas Airlangga. E-Lapor: Suara Anda, Perubahan Nyata.
    </footer>
</body>
</html>