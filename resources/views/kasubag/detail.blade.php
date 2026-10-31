<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail & Disposisi Petugas - KASUBAG E-LAPOR</title>
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
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0a8080] selection:text-white"
      x-data="{ showDisposeModal: false, showReturnModal: false }">

    <!-- Header -->
    <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('kasubag.dashboard') }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-[#0a8080] hover:bg-slate-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-lg font-black text-[#0c3934] leading-tight">Detail Pengaduan Unit</h1>
                <p class="text-xs font-mono font-bold text-[#0a8080]">{{ $report->ticket_code }}</p>
            </div>
        </div>
        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase
            @if ($report->status === 'didisposisi') bg-amber-100 text-amber-800
            @elseif (in_array($report->status, ['diterima', 'proses'])) bg-[#ccfbf1] text-[#0f766e]
            @elseif ($report->status === 'verifikasi_kabag') bg-purple-100 text-purple-800
            @elseif ($report->status === 'selesai') bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]
            @endif">
            {{ str_replace('_', ' ', $report->status) }}
        </span>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 w-full flex-grow">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-[#ecfdf5] border border-[#a7f3d0] text-[#064e3b] text-sm font-semibold flex items-center gap-3">
                <svg class="w-5 h-5 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-sm text-red-700">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Info Column -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                    <div class="border-b border-slate-100 pb-4 mb-4">
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-2">
                            <span>Lokasi: Kampus <strong>{{ ucfirst($report->campus_location) }}</strong></span>
                            <span>&bull;</span>
                            <span>{{ $report->created_at->translatedFormat('d F Y, H:i') }}</span>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900">{{ $report->title }}</h2>
                    </div>

                    <div class="prose max-w-none text-sm text-slate-700 whitespace-pre-line leading-relaxed mb-6">
                        {{ $report->description }}
                    </div>

                    @if ($report->evidences->where('type', 'initial')->isNotEmpty())
                        <div class="border-t border-slate-100 pt-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Bukti Awal Pelapor:</h4>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($report->evidences->where('type', 'initial') as $ev)
                                    <a href="{{ asset('storage/' . $ev->file_path) }}" target="_blank"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-[#ccfbf1] bg-[#f0fdfa] text-xs font-semibold text-[#0a8080] hover:bg-[#ccfbf1] transition">
                                        <svg class="w-4 h-4 text-[#0a8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <span>Buka Berkas Bukti</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Dispositions Timeline -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                    <h3 class="text-base font-bold text-slate-900 mb-4">Catatan Disposisi dari KABAG</h3>
                    <div class="space-y-4">
                        @foreach ($report->dispositions as $disp)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3 text-xs">
                                <div class="w-8 h-8 rounded-full bg-[#ecfdf5] text-[#0a8080] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                    {{ substr($disp->sender?->role ?? 'U', 0, 1) }}
                                </div>
                                <div class="w-full">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-slate-900">{{ $disp->sender?->name }} ({{ strtoupper($disp->sender?->role) }})</span>
                                        <span class="text-slate-400">{{ $disp->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-slate-700 bg-white p-3 rounded-xl border border-slate-100 mt-2">{{ $disp->notes }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Actions Sidebar matching Figma node 171:406 -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm text-xs">
                    <h4 class="font-bold uppercase tracking-wider text-slate-400 mb-3">Pelapor</h4>
                    <p class="text-sm font-bold text-slate-900">{{ $report->user?->name ?? 'Anonim' }}</p>
                    <p class="text-slate-500 mt-0.5">{{ $report->user?->identity_number }} &bull; {{ ucfirst($report->user?->position) }}</p>
                </div>

                @if (!in_array($report->status, ['verifikasi_kabag', 'selesai', 'ditolak']))
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-md space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Aksi Kasubag</h4>

                        <!-- Disposisi ke Petugas -->
                        <button type="button" @click="showDisposeModal = true"
                            class="w-full py-3.5 px-4 rounded-xl font-bold bg-[#0a8080] hover:bg-[#086666] text-white text-sm shadow transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span>Tugaskan ke Petugas</span>
                        </button>

                        <!-- Kerjakan Sendiri -->
                        <form action="{{ route('kasubag.self-execute', $report) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda ingin memproses laporan ini sendiri?')"
                                class="w-full py-3 px-4 rounded-xl font-bold bg-[#ecfdf5] hover:bg-[#ccfbf1] text-[#0a8080] text-sm border border-[#a7f3d0] transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span>Kerjakan Sendiri</span>
                            </button>
                        </form>

                        <!-- Kembalikan ke Kabag (Salah Alamat) -->
                        <button type="button" @click="showReturnModal = true"
                            class="w-full py-2.5 px-4 rounded-xl font-semibold bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs border border-amber-200 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                            <span>Kembalikan ke KABAG (Salah Disposisi)</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Modal Disposisi ke Petugas Unit -->
    <div x-show="showDisposeModal" style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100"
             @click.away="showDisposeModal = false">
            <h3 class="text-xl font-bold text-[#0c3934] mb-1">Disposisi ke Petugas Lapangan</h3>
            <p class="text-xs text-slate-500 mb-6">Pilih petugas di bawah unit {{ auth()->user()->subDepartment?->name }}</p>

            <form action="{{ route('kasubag.dispose', $report) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Pilih Petugas <span class="text-red-500">*</span></label>
                    <select name="receiver_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080]">
                        <option value="">Pilih Petugas</option>
                        @foreach ($petugasList as $pet)
                            <option value="{{ $pet->id }}">{{ $pet->name }} ({{ $pet->identity_number ?? 'Petugas' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Catatan Instruksi Tugas</label>
                    <textarea name="notes" rows="3" required placeholder="Instruksi pengerjaan teknis..."
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080]"></textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="submit" class="flex-grow py-3 rounded-xl font-bold bg-[#0a8080] text-white hover:bg-[#086666] text-sm shadow-md transition">
                        Kirim Tugas ke Petugas
                    </button>
                    <button type="button" @click="showDisposeModal = false" class="py-3 px-4 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Kembalikan ke Kabag -->
    <div x-show="showReturnModal" style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100"
             @click.away="showReturnModal = false">
            <h3 class="text-xl font-bold text-amber-800 mb-1">Kembalikan ke KABAG?</h3>
            <p class="text-xs text-slate-500 mb-4">Gunakan aksi ini jika laporan keliru diarahkan ke sub bagian Anda.</p>

            <form action="{{ route('kasubag.return', $report) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Alasan Pengembalian <span class="text-red-500">*</span></label>
                    <textarea name="notes" rows="3" required placeholder="Contoh: Masalah ini merupakan ranah Kemahasiswaan & Ormawa, bukan Sarana Prasarana..."
                        class="w-full px-4 py-2.5 rounded-xl border border-amber-300 text-sm focus:ring-2 focus:ring-amber-500"></textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="submit" class="flex-grow py-2.5 rounded-xl font-bold bg-amber-600 hover:bg-amber-700 text-white text-sm shadow-md transition">
                        Kembalikan Sekarang
                    </button>
                    <button type="button" @click="showReturnModal = false" class="py-2.5 px-4 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
