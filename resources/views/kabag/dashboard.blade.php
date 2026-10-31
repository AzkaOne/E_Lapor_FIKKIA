<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard KABAG - E-LAPOR FIKKIA</title>
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

    <!-- Top Navbar -->
    @include('components.navbar')

    <div class="flex-grow flex">
        <!-- Sidebar Navigation matching Figma Page 3 (106:4437) -->
        @include('components.sidebar')

        <!-- Main Workspace -->
        <main class="flex-1 p-6 sm:p-8 max-w-7xl mx-auto w-full">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-[#ecfdf5] border border-[rgba(167,243,208,0.8)] text-[#064e3b] text-sm font-semibold flex items-center gap-3 shadow-xs">
                    <div class="w-7 h-7 rounded-full bg-[#059669] text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- TopBar Greeting Header matching Figma 106:4535 -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">SELAMAT DATANG,</div>
                    <h2 class="text-2xl font-black text-[#0c3934] tracking-tight leading-tight">Bapak/Ibu Kepala Bagian (KABAG)</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Berikut adalah ringkasan laporan pengaduan terbaru di lingkungan FIKKIA UNAIR.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('kabag.verifikasi.index') }}" class="px-4 py-2.5 rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white text-xs font-bold shadow-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Verifikasi Laporan</span>
                        @if ($counts['verifikasi'] > 0)
                            <span class="px-1.5 py-0.2 bg-white text-[#0a8080] rounded-full text-[10px] font-extrabold">{{ $counts['verifikasi'] }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Stats Metrics Section matching Figma 106:4582 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Metric Card 1: Total Laporan (Figma 106:4583) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#eff6ff] text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Total Laporan</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">{{ $counts['total'] }}</div>
                        <div class="text-[11px] font-semibold text-[#059669] flex items-center gap-1 mt-0.5">
                            <span>↑ 12%</span>
                            <span class="text-slate-400 font-normal">dari periode lalu</span>
                        </div>
                    </div>
                </div>

                <!-- Metric Card 2: Dalam Penanganan (Figma 106:4602) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#f0fdfa] text-[#0a8080] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Dalam Penanganan</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">{{ $counts['proses'] + $counts['didisposisi'] }}</div>
                        <div class="text-[11px] font-semibold text-[#059669] flex items-center gap-1 mt-0.5">
                            <span>↑ 8%</span>
                            <span class="text-slate-400 font-normal">sedang berjalan</span>
                        </div>
                    </div>
                </div>

                <!-- Metric Card 3: Menunggu Verifikasi (Figma 106:4618) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#fffbeb] text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Menunggu Verifikasi</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">{{ $counts['verifikasi'] }}</div>
                        <div class="text-[11px] font-semibold text-[#d97706] flex items-center gap-1 mt-0.5">
                            <span>{{ $counts['verifikasi'] }} tiket</span>
                            <span class="text-slate-400 font-normal">siap ditinjau</span>
                        </div>
                    </div>
                </div>

                <!-- Metric Card 4: Selesai & Ditutup -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#ecfdf5] text-[#059669] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Laporan Selesai</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">{{ $counts['selesai'] }}</div>
                        <div class="text-[11px] font-semibold text-[#059669] flex items-center gap-1 mt-0.5">
                            <span>Selesai tuntas</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 mb-6">
                <form action="{{ route('kabag.dashboard') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Filter Sub Bagian</label>
                        <select name="sub_department_id" onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080] bg-white">
                            <option value="">Semua Sub Bagian</option>
                            @foreach ($subDepartments as $dept)
                                <option value="{{ $dept->id }}" {{ request('sub_department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Filter Status</label>
                        <select name="status" onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080] bg-white">
                            <option value="">Semua Status</option>
                            <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                            <option value="didisposisi" {{ request('status') === 'didisposisi' ? 'selected' : '' }}>Didisposisi</option>
                            <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="verifikasi_kabag" {{ request('status') === 'verifikasi_kabag' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <a href="{{ route('kabag.dashboard') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">
                            Reset Filter
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table Laporan matching Figma 106:4802 -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Laporan Masuk Terbaru</h3>
                        <p class="text-xs text-slate-500">Daftar aspirasi dan keluhan yang memerlukan disposisi pimpinan</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/80 text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Kode Tiket</th>
                                <th class="px-6 py-4">Pelapor</th>
                                <th class="px-6 py-4">Judul Pengaduan</th>
                                <th class="px-6 py-4">Tujuan Bidang</th>
                                <th class="px-6 py-4">Lokasi</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($reports as $report)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-[#0a8080]">{{ $report->ticket_code }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $report->user?->name ?? 'Anonim' }}</div>
                                        <div class="text-xs text-slate-400 capitalize">{{ $report->user?->position ?? 'Civitas' }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium max-w-xs truncate">{{ $report->title }}</td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-600">
                                        {{ $report->subDepartment?->name ?? 'Dekanat' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="capitalize px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                            Kampus {{ $report->campus_location }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                                            @if ($report->status === 'diajukan') bg-amber-100 text-amber-800
                                            @elseif ($report->status === 'didisposisi') bg-teal-100 text-teal-800
                                            @elseif ($report->status === 'diterima') bg-blue-100 text-blue-800
                                            @elseif ($report->status === 'proses') bg-indigo-100 text-indigo-800
                                            @elseif ($report->status === 'verifikasi_kabag') bg-purple-100 text-purple-800
                                            @elseif ($report->status === 'selesai') bg-emerald-100 text-emerald-800
                                            @else bg-rose-100 text-rose-800 @endif">
                                            {{ str_replace('_', ' ', $report->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('kabag.show', $report) }}" 
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#0a8080] hover:bg-[#086666] text-white shadow-xs transition">
                                            <span>Detail & Opsi</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                                        Tidak ada laporan pelayanan yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($reports->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Institutional Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 px-8 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} FIKKIA Universitas Airlangga (Kampus Mojo • Sobo • Giri Banyuwangi). Bersama Mewujudkan Pelayanan yang Lebih Baik.</p>
    </footer>
</body>
</html>