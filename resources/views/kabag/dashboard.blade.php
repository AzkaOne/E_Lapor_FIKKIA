<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard KABAG - E-LAPOR FIKKIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex">

    <!-- Sidebar matching Figma Dashboard KABAG -->
    <aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col justify-between shrink-0 h-screen sticky top-0">
        <div>
            <!-- Brand -->
            <div class="h-20 flex items-center px-6 border-b border-slate-100 gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#1E3A5F] flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                    EL
                </div>
                <div>
                    <h1 class="text-sm font-bold text-[#1E3A5F] leading-tight">E-LAPOR KABAG</h1>
                    <p class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">Portal Dekanat</p>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="p-4 space-y-1.5 text-sm">
                <a href="{{ route('kabag.dashboard') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold bg-[#1E3A5F] text-white shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Laporan Pelayanan</span>
                </a>
                <a href="{{ route('kabag.whistleblowing') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-amber-800 hover:bg-amber-50 transition">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>Whistleblowing</span>
                    @if ($counts['whistleblowing'] > 0)
                        <span class="ml-auto px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">{{ $counts['whistleblowing'] }}</span>
                    @endif
                </a>
                <a href="{{ route('kabag.my-reports') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Laporan Saya</span>
                </a>
                <a href="{{ route('kabag.history') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-[#1E3A5F] hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Riwayat Laporan</span>
                </a>
            </nav>
        </div>

        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs">
                    <p class="font-bold text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400">Kepala Bagian Dekanat</p>
                </div>
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

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h2 class="text-lg font-bold text-slate-900 leading-tight">Manajemen Laporan Pengaduan</h2>
                <p class="text-xs text-slate-500">Seluruh laporan sivitas dari Kampus Mojo, Sobo, dan Giri</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#1E3A5F]">
                    KABAG / DEKANAT
                </span>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="p-6 sm:p-8 flex-grow">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Stats Metrics matching Figma node 94:1501 -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Total Masuk</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $counts['total'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <p class="text-xs font-semibold text-amber-600 uppercase">Diajukan</p>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $counts['diajukan'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <p class="text-xs font-semibold text-blue-600 uppercase">Didisposisi</p>
                    <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $counts['didisposisi'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <p class="text-xs font-semibold text-indigo-600 uppercase">Proses Kerja</p>
                    <p class="text-2xl font-extrabold text-indigo-600 mt-1">{{ $counts['proses'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <p class="text-xs font-semibold text-purple-600 uppercase">Verifikasi</p>
                    <p class="text-2xl font-extrabold text-purple-600 mt-1">{{ $counts['verifikasi'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <p class="text-xs font-semibold text-emerald-600 uppercase">Selesai</p>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $counts['selesai'] }}</p>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 mb-6">
                <form action="{{ route('kabag.dashboard') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Filter Sub Bagian</label>
                        <select name="sub_department_id" onchange="this.form.submit()"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#1E3A5F] bg-white">
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
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#1E3A5F] bg-white">
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
                        <a href="{{ route('kabag.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">
                            Reset Filter
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table Laporan matching Figma node 109:7005 -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
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
                                    <td class="px-6 py-4 font-mono font-bold text-[#1E3A5F]">
                                        {{ $report->ticket_code }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $report->user?->name ?? 'Anonim' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $report->user?->identity_number }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-800 line-clamp-1">{{ $report->title }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $report->created_at->translatedFormat('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-[#1E3A5F]">
                                            {{ $report->subDepartment?->name ?? 'Dekanat' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-600 font-medium">
                                        Kampus {{ ucfirst($report->campus_location) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase
                                            @if ($report->status === 'diajukan') bg-amber-100 text-amber-800
                                            @elseif (in_array($report->status, ['didisposisi', 'diterima', 'proses'])) bg-blue-100 text-blue-800
                                            @elseif ($report->status === 'verifikasi_kabag') bg-purple-100 text-purple-800
                                            @elseif ($report->status === 'selesai') bg-emerald-100 text-emerald-800
                                            @elseif ($report->status === 'ditolak') bg-red-100 text-red-800
                                            @endif">
                                            {{ str_replace('_', ' ', $report->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('kabag.show', $report) }}" 
                                            class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#1E3A5F] hover:bg-[#152a45] text-white shadow-xs transition">
                                            Detail & Disposisi &rarr;
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

                <div class="p-4 border-t border-slate-100">
                    {{ $reports->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>
