<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - E-LAPOR FIKKIA</title>
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0a8080] selection:text-white">

    <!-- Top Navbar -->
    @include('components.navbar')

    <div class="flex-grow flex">
        <!-- Sidebar matching Figma Page 3 -->
        @include('components.sidebar')

        <!-- Main Workspace -->
        <div class="flex-grow flex flex-col min-w-0">
            <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div>
                    <h2 class="text-xl font-black text-[#0c3934] leading-tight">Daftar Penugasan Lapangan</h2>
                    <p class="text-xs text-slate-500">{{ auth()->user()->subDepartment?->name ?? 'Petugas Dekanat / Unit' }}</p>
                </div>
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]">
                    PETUGAS LAPANGAN
                </span>
            </header>

            <main class="p-6 sm:p-8 flex-grow max-w-7xl w-full mx-auto">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-[#ecfdf5] border border-[#a7f3d0] text-[#064e3b] text-sm font-semibold flex items-center gap-3">
                        <svg class="w-5 h-5 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Summary Cards matching Figma Page 3 -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                        <p class="text-xs font-semibold text-amber-600 uppercase">Tugas Masuk (Perlu Mulai)</p>
                        <p class="text-2xl font-black text-amber-600 mt-1">{{ $counts['masuk'] }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                        <p class="text-xs font-semibold text-[#0a8080] uppercase">Sedang Dikerjakan</p>
                        <p class="text-2xl font-black text-[#0a8080] mt-1">{{ $counts['proses'] }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                        <p class="text-xs font-semibold text-[#059669] uppercase">Telah Diselesaikan</p>
                        <p class="text-2xl font-black text-[#059669] mt-1">{{ $counts['selesai'] }}</p>
                    </div>
                </div>

                <!-- Table Penugasan matching Figma node 190:2731 -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#f0fdfa] text-xs font-bold uppercase tracking-wider text-[#0c3934] border-b border-slate-100">
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
                                        <td class="px-6 py-4 font-mono font-bold text-[#0a8080]">{{ $report->ticket_code }}</td>
                                        <td class="px-6 py-4 font-medium text-slate-900">
                                            <div>{{ $report->title }}</div>
                                            <div class="text-[11px] text-slate-400 font-normal line-clamp-1">{{ $report->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-medium text-slate-600">Kampus {{ ucfirst($report->campus_location) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase
                                                {{ $report->status === 'diterima' ? 'bg-amber-100 text-amber-800' : 'bg-[#ccfbf1] text-[#0f766e]' }}">
                                                {{ $report->status === 'diterima' ? 'Menunggu Mulai' : 'Sedang Diproses' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if ($report->status === 'diterima')
                                                <form action="{{ route('petugas.start', $report) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" 
                                                        class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-[#0a8080] hover:bg-[#086666] text-white shadow-xs transition">
                                                        Mulai Kerjakan &rarr;
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('petugas.my-reports') }}" 
                                                    class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-[#0a8080] hover:bg-[#086666] text-white shadow-xs transition">
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
    </div>
</body>
</html>
