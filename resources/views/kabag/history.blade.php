<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan Selesai / Ditolak - KABAG E-LAPOR</title>
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
        <!-- Sidebar Navigation matching Figma Page 3 -->
        @include('components.sidebar')

        <div class="flex-grow flex flex-col min-w-0">
            <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div>
                    <h2 class="text-xl font-black text-[#0c3934] leading-tight">Riwayat Pengaduan Ditutup</h2>
                    <p class="text-xs text-slate-500">Laporan pelayanan yang telah berstatus selesai atau ditolak</p>
                </div>
            </header>

            <main class="p-6 sm:p-8 flex-grow max-w-7xl w-full mx-auto">
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#f0fdfa] text-xs font-bold uppercase tracking-wider text-[#0c3934] border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4">Kode Tiket</th>
                                    <th class="px-6 py-4">Pelapor</th>
                                    <th class="px-6 py-4">Judul</th>
                                    <th class="px-6 py-4">Tujuan</th>
                                    <th class="px-6 py-4">Status Akhir</th>
                                    <th class="px-6 py-4">Rating</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($reports as $report)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="px-6 py-4 font-mono font-bold text-[#0a8080]">{{ $report->ticket_code }}</td>
                                        <td class="px-6 py-4">{{ $report->user?->name ?? 'Anonim' }}</td>
                                        <td class="px-6 py-4 font-medium">{{ $report->title }}</td>
                                        <td class="px-6 py-4">{{ $report->subDepartment?->name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                                                {{ $report->status === 'selesai' ? 'bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]' : 'bg-red-100 text-red-800' }}">
                                                {{ $report->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($report->rating)
                                                <span class="font-bold text-amber-600">⭐ {{ $report->rating }}/5</span>
                                            @else
                                                <span class="text-xs text-slate-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">Belum ada riwayat laporan ditutup.</td>
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
