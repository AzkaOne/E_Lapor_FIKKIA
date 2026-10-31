<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - E-LAPOR FIKKIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex">

    <!-- Sidebar matching Figma Dashboard Petugas -->
    <aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col justify-between shrink-0 h-screen sticky top-0">
        <div>
            <div class="h-20 flex items-center px-6 border-b border-slate-100 gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#1E3A5F] flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                    EL
                </div>
                <div>
                    <h1 class="text-sm font-bold text-[#1E3A5F] leading-tight">E-LAPOR PETUGAS</h1>
                    <p class="text-[10px] text-slate-400 font-semibold uppercase truncate max-w-[130px]">{{ auth()->user()->subDepartment?->name ?? 'Tim Lapangan' }}</p>
                </div>
            </div>

            <nav class="p-4 space-y-1.5 text-sm">
                <a href="{{ route('petugas.dashboard') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-[#1E3A5F] text-white shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Laporan Ditugaskan</span>
                    @if ($counts['masuk'] > 0)
                        <span class="ml-auto px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">{{ $counts['masuk'] }}</span>
                    @endif
                </a>
                <a href="{{ route('petugas.my-reports') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Laporan Saya (Aktif)</span>
                    @if ($counts['proses'] > 0)
                        <span class="ml-auto px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">{{ $counts['proses'] }}</span>
                    @endif
                </a>
                <a href="{{ route('petugas.history') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Riwayat Selesai</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-100">
            <div class="mb-3 text-xs">
                <p class="font-bold text-slate-900">{{ auth()->user()->name }}</p>
                <p class="text-slate-400">Petugas Eksekutor</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2 px-3 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:text-red-600 hover:bg-red-50 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Workspace -->
    <div class="flex-grow flex flex-col min-w-0">
        <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h2 class="text-lg font-bold text-slate-900 leading-tight">Daftar Penugasan Lapangan</h2>
                <p class="text-xs text-slate-500">{{ auth()->user()->subDepartment?->name ?? 'Petugas Dekanat / Unit' }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#1E3A5F]">
                PETUGAS
            </span>
        </header>

        <main class="p-6 sm:p-8 flex-grow">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <p class="text-xs font-semibold text-amber-600 uppercase">Tugas Masuk (Perlu Mulai)</p>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $counts['masuk'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <p class="text-xs font-semibold text-blue-600 uppercase">Sedang Dikerjakan</p>
                    <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $counts['proses'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <p class="text-xs font-semibold text-emerald-600 uppercase">Telah Diselesaikan</p>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $counts['selesai'] }}</p>
                </div>
            </div>

            <!-- Table Penugasan matching Figma node 190:2731 -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/80 text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Kode Tiket</th>
                                <th class="px-6 py-4">Judul Pengaduan</th>
                                <th class="px-6 py-4">Lokasi Kampus</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($reports as $report)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-[#1E3A5F]">{{ $report->ticket_code }}</td>
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        <div>{{ $report->title }}</div>
                                        <div class="text-[11px] text-slate-400 font-normal line-clamp-1">{{ $report->description }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-slate-600">Kampus {{ ucfirst($report->campus_location) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase
                                            {{ $report->status === 'diterima' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $report->status === 'diterima' ? 'Menunggu Mulai' : 'Sedang Diproses' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($report->status === 'diterima')
                                            <form action="{{ route('petugas.start', $report) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" 
                                                    class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-[#1E3A5F] hover:bg-[#152a45] text-white shadow-xs transition">
                                                    Mulai Kerjakan &rarr;
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('petugas.my-reports') }}" 
                                                class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition">
                                                Lanjutkan Pengerjaan &rarr;
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                                        Tidak ada tugas yang ditugaskan kepada Anda saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
