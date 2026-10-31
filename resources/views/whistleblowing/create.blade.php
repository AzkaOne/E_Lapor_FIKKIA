<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Anonim (Whistleblowing) - E-LAPOR FIKKIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between"
      x-data="{ showModal: {{ session('ticket_code') ? 'true' : 'false' }}, copied: false }">

    <!-- TopHeader matching Figma node 88:1024 -->
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

            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-[#1E3A5F] transition px-3 py-2 rounded-xl hover:bg-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </header>

    <!-- Main Content Layout with 3 Columns matching Figma node 88:1023 -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Sidebar info matching Figma node 88:1149 -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm sticky top-28">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 mb-2">
                        Whistleblowing
                    </span>
                    <h2 class="text-xl font-bold text-slate-900 leading-snug">
                        Laporan Anonim
                    </h2>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Sampaikan aspirasi, keluhan pelanggaran, atau informasi penting secara anonim. Identitas Anda terjamin 100% aman dan tidak tercatat di server.
                    </p>

                    <div class="mt-6 pt-6 border-t border-slate-100 text-xs text-slate-600 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Tanpa login akun</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Akses langsung Dekanat/Kabag</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Pelacakan via Kode Tiket</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Main Form matching Figma node 88:1656 -->
            <div class="lg:col-span-6">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-xl font-bold text-slate-900">Formulir Pengaduan Anonim</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Isi rincian laporan di bawah ini secara jelas dan objektif</p>
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

                    <form action="{{ route('whistleblowing.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Laporan / Pengaduan <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                placeholder="Tulis ringkasan topik pengaduan"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi Kampus <span class="text-red-500">*</span></label>
                                <select name="campus_location" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm bg-white">
                                    <option value="">Pilih Kampus</option>
                                    <option value="mojo" {{ old('campus_location') === 'mojo' ? 'selected' : '' }}>Kampus Mojo</option>
                                    <option value="sobo" {{ old('campus_location') === 'sobo' ? 'selected' : '' }}>Kampus Sobo</option>
                                    <option value="giri" {{ old('campus_location') === 'giri' ? 'selected' : '' }}>Kampus Giri</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Tujuan / Bidang Masalah</label>
                                <select name="sub_department_id"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm bg-white">
                                    <option value="">Umum / Dekanat</option>
                                    @foreach ($subDepartments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('sub_department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi & Rincian Kejadian <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="5" required
                                placeholder="Jelaskan secara kronologis kejadian atau temuan yang ingin Anda laporkan..."
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Lampiran Bukti (Opsional)</label>
                            <input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf"
                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100 cursor-pointer border border-slate-300 rounded-xl p-1">
                            <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, PDF (Maksimal 5MB)</p>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full py-3.5 px-4 bg-[#D97706] hover:bg-[#b45309] text-white font-bold rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                <span>Kirim Laporan Anonim</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Sidebar Guidelines matching Figma node 88:2230 -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm sticky top-28">
                    <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Panduan Topik Whistleblowing
                    </h4>

                    <div class="space-y-2.5 text-xs">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-2 text-slate-700">
                            <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                            <span>Pelanggaran etika & perilaku</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-2 text-slate-700">
                            <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                            <span>Penyalahgunaan wewenang / gratifikasi</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-2 text-slate-700">
                            <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                            <span>Pelanggaran integritas akademik</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-2 text-slate-700">
                            <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                            <span>Penyimpangan pelayanan & administrasi</span>
                        </div>
                    </div>

                    <div class="mt-6 p-4 rounded-2xl bg-blue-50 border border-blue-100 text-xs text-[#1E3A5F]">
                        <p class="font-semibold mb-1">Sudah punya tiket?</p>
                        <p class="text-slate-600 mb-3">Lacak laporan anonim Anda kapan saja menggunakan kode tiket.</p>
                        <a href="{{ route('tracking') }}" class="inline-block font-bold text-blue-600 hover:text-blue-800 underline">
                            Lacak Tiket Sekarang &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Success Modal Popup with Ticket Code (matching specification) -->
    <div x-show="showModal" style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 text-center relative"
             @click.away="showModal = false">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>

            <h3 class="text-2xl font-bold text-slate-900">Laporan Terkirim!</h3>
            <p class="text-sm text-slate-600 mt-2">
                Laporan anonim Anda berhasil diterima. Simpan dan catat <strong>Kode Tiket</strong> berikut untuk melacak progres penanganan:
            </p>

            <div class="my-6 p-4 rounded-2xl bg-amber-50 border-2 border-amber-200 flex items-center justify-between">
                <span class="font-mono text-xl font-extrabold text-[#1E3A5F] tracking-wider" id="ticketCodeText">
                    {{ session('ticket_code') }}
                </span>
                <button type="button"
                    @click="navigator.clipboard.writeText('{{ session('ticket_code') }}'); copied = true; setTimeout(() => copied = false, 2500)"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#1E3A5F] text-white hover:bg-[#152a45] transition flex items-center gap-1">
                    <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                </button>
            </div>

            <p class="text-xs text-amber-700 bg-amber-50/80 p-3 rounded-xl mb-6">
                <strong>Peringatan:</strong> Karena ini laporan anonim, kode ini adalah satu-satunya cara Anda untuk memeriksa tanggapan dari pimpinan Dekanat.
            </p>

            <div class="flex flex-col gap-2">
                <a href="{{ route('tracking', ['ticket_code' => session('ticket_code')]) }}" 
                    class="w-full py-3 px-4 rounded-xl font-bold bg-[#1E3A5F] text-white hover:bg-[#152a45] transition">
                    Lihat Status Laporan
                </a>
                <button type="button" @click="showModal = false"
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-slate-500 hover:text-slate-800 transition">
                    Tutup Jendela
                </button>
            </div>
        </div>
    </div>

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
