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
    <!-- Navbar -->
    @include('components.navbar')

    <div class="flex-grow flex">
        <!-- Sidebar Navigation -->
        @include('components.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 p-6 md:p-8 max-w-7xl mx-auto w-full">
            @if (session('success'))
                <div class="mb-6 p-4 bg-[#ecfdf5] border border-[rgba(167,243,208,0.8)] text-[#064e3b] rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-sm">
                    <div class="w-7 h-7 rounded-full bg-[#059669] text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    <!-- Institutional Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 px-8 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} FIKKIA Universitas Airlangga (Kampus Mojo • Sobo • Giri Banyuwangi). Bersama Mewujudkan Pelayanan yang Lebih Baik.</p>
    </footer>
</body>
</html>