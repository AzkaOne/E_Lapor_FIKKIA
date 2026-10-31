<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - E-LAPOR FIKKIA UNAIR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] font-sans antialiased min-h-screen flex flex-col justify-between">
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-4">
            <div class="w-10 h-10 rounded-lg bg-[#1E3A5F] flex items-center justify-center text-white font-bold text-lg shadow-sm">
                EL
            </div>
            <div>
                <h1 class="text-base font-bold text-[#1E3A5F] leading-tight">E-LAPOR FIKKIA</h1>
                <p class="text-xs text-slate-500">Universitas Airlangga Kampus Banyuwangi</p>
            </div>
        </div>
        <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-[#1E3A5F] transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>
    </header>

    <!-- Main Container -->
    <main class="flex-grow flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
            <div class="text-center mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-[#1E3A5F] border border-blue-100 mb-2">
                    Portal Autentikasi Sivitas
                </span>
                <h2 class="text-2xl font-bold text-slate-900">Masuk ke E-Lapor</h2>
                <p class="text-sm text-slate-500 mt-1">Gunakan Email Kampus @unair.ac.id atau NIM/NIP</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email / NIM / NIP</label>
                    <input type="text" name="login" value="{{ old('login', old('email')) }}" required autofocus
                        placeholder="contoh: nama@unair.ac.id atau NIM"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#1E3A5F] focus:border-[#1E3A5F] transition text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kata Sandi</label>
                    <input type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#1E3A5F] focus:border-[#1E3A5F] transition text-sm">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#1E3A5F] focus:ring-[#1E3A5F] border-slate-300">
                        <span class="ml-2">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 bg-[#1E3A5F] hover:bg-[#152a45] text-white font-semibold rounded-xl shadow-md transition duration-200">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center text-sm text-slate-600">
                Belum memiliki akun Pelapor?
                <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 ml-1">Daftar Akun Baru</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-100">
        &copy; 2026 FIKKIA Universitas Airlangga. E-Lapor: Suara Anda, Perubahan Nyata.
    </footer>
</body>
</html>
