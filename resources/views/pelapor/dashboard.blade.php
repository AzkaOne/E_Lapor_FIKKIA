<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelapor - E-LAPOR FIKKIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between">

    <!-- TopHeader matching Figma node 44:1559 -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-[#1E3A5F] flex items-center justify-center text-white font-extrabold text-xl shadow-md">
                    EL
                </div>
                <div class="border-l border-slate-200 pl-4 py-1">
                    <h1 class="text-base sm:text-lg font-bold text-[#1E3A5F] leading-tight tracking-tight">E-LAPOR FIKKIA</h1>
                    <p class="text-xs text-slate-500 font-medium">Universitas Airlangga Kampus Banyuwangi</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 uppercase">{{ auth()->user()->identity_number }} &bull; {{ ucfirst(auth()->user()->position) }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2.5 rounded-xl border border-slate-200 text-slate-600 hover:text-red-600 hover:bg-red-50 transition" title="Keluar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Sub-Navbar Navigation Tabs matching Figma nodes -->
    <div class="bg-white border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex space-x-2 sm:space-x-8">
            <a href="{{ route('pelapor.dashboard') }}" class="py-4 border-b-2 border-[#1E3A5F] text-sm font-bold text-[#1E3A5F] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Buat Laporan Baru</span>
            </a>
            <a href="{{ route('pelapor.status') }}" class="py-4 border-b-2 border-transparent hover:border-slate-300 text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span>Status Laporan Aktif</span>
                @if ($activeReports->count() > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-[#1E3A5F]">{{ $activeReports->count() }}</span>
                @endif
            </a>
            <a href="{{ route('pelapor.history') }}" class="py-4 border-b-2 border-transparent hover:border-slate-300 text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Riwayat Laporan & Rating</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Form Input Laporan Pelayanan matching Figma node 44:1558 -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-[#1E3A5F] border border-blue-100 mb-2">
                            Layanan Aspirasi & Pengaduan Sivitas
                        </span>
                        <h2 class="text-2xl font-bold text-slate-900">Form Pengaduan Layanan Kampus</h2>
                        <p class="text-xs text-slate-500 mt-1">Sampaikan keluhan fasilitas fisik, kegiatan ormawa, SDM, atau administrasi dekanat.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-sm text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pelapor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Keluhan / Laporan <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                placeholder="Tuliskan inti masalah secara singkat dan jelas"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#1E3A5F] focus:border-[#1E3A5F] transition text-sm">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi Kampus <span class="text-red-500">*</span></label>
                                <select name="campus_location" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#1E3A5F] focus:border-[#1E3A5F] transition text-sm bg-white">
                                    <option value="">Pilih Kampus</option>
                                    <option value="mojo" {{ old('campus_location') === 'mojo' ? 'selected' : '' }}>Kampus Mojo</option>
                                    <option value="sobo" {{ old('campus_location') === 'sobo' ? 'selected' : '' }}>Kampus Sobo</option>
                                    <option value="giri" {{ old('campus_location') === 'giri' ? 'selected' : '' }}>Kampus Giri</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Tujuan Bagian / Sub Bagian <span class="text-red-500">*</span></label>
                                <select name="sub_department_id" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#1E3A5F] focus:border-[#1E3A5F] transition text-sm bg-white">
                                    <option value="">Pilih Bagian Tujuan</option>
                                    @foreach ($subDepartments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('sub_department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Lengkap Masalah <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="5" required
                                placeholder="Jelaskan secara detail lokasi ruangan, kondisi fasilitas, atau kronologi yang dialami..."
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#1E3A5F] focus:border-[#1E3A5F] transition text-sm">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Lampiran Bukti Foto / Berkas (Opsional)</label>
                            <input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf"
                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#1E3A5F] hover:file:bg-blue-100 cursor-pointer border border-slate-300 rounded-xl p-1">
                            <p class="text-xs text-slate-400 mt-1">Format gambar JPG, PNG atau PDF (Maksimal 5MB)</p>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full py-3.5 px-4 bg-[#1E3A5F] hover:bg-[#152a45] text-white font-bold rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                <span>Kirim Laporan Pelayanan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Profile & Active Status Summary Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <!-- User Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-[#1E3A5F] font-bold text-xl flex items-center justify-center shadow-inner">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 leading-snug">{{ auth()->user()->name }}</h3>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-[#1E3A5F]">
                                {{ ucfirst(auth()->user()->position) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Recent Active Reports widget -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-slate-900 text-sm">Laporan Aktif Anda</h4>
                        <a href="{{ route('pelapor.status') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">Lihat Semua &rarr;</a>
                    </div>

                    @if ($activeReports->isEmpty())
                        <div class="text-center py-6 text-slate-400 text-xs">
                            <p>Tidak ada laporan yang sedang diproses.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($activeReports->take(3) as $rep)
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                                    <div class="flex items-center justify-between font-mono font-bold text-slate-700 mb-1">
                                        <span>{{ $rep->ticket_code }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold 
                                            {{ $rep->status === 'diajukan' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $rep->status }}
                                        </span>
                                    </div>
                                    <p class="font-medium text-slate-800 truncate">{{ $rep->title }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">{{ $rep->subDepartment?->name }} &bull; {{ $rep->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
            <p>&copy; 2026 FIKKIA Universitas Airlangga.</p>
            <p class="font-medium text-slate-700">E-Lapor. Suara Anda, Perubahan Nyata.</p>
            <div>Kampus Mojo &bull; Sobo &bull; Giri</div>
        </div>
    </footer>
</body>
</html>
