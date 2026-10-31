<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan #{{ $report->ticket_code }} - KABAG E-LAPOR</title>
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
      x-data="{ showDisposeModal: false, showRejectModal: false, targetType: 'kasubag' }">

    <!-- Header -->
    <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('kabag.dashboard') }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-[#0a8080] hover:bg-slate-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-lg font-black text-[#0c3934] leading-tight">Detail & Disposisi Laporan</h1>
                <p class="text-xs font-mono font-bold text-[#0a8080]">{{ $report->ticket_code }}</p>
            </div>
        </div>
        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase
            @if ($report->status === 'diajukan') bg-amber-100 text-amber-800
            @elseif (in_array($report->status, ['didisposisi', 'diterima', 'proses'])) bg-[#ccfbf1] text-[#0f766e]
            @elseif ($report->status === 'verifikasi_kabag') bg-purple-100 text-purple-800
            @elseif ($report->status === 'selesai') bg-[#ecfdf5] text-[#064e3b] border border-[#a7f3d0]
            @elseif ($report->status === 'ditolak') bg-red-100 text-red-800
            @endif">
            Status: {{ str_replace('_', ' ', $report->status) }}
        </span>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 w-full flex-grow">
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
                <!-- Report Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                    <div class="border-b border-slate-100 pb-4 mb-4">
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-2">
                            <span>Ditujukan ke: <strong class="text-[#0a8080]">{{ $report->subDepartment?->name ?? 'Dekanat' }}</strong></span>
                            <span>&bull;</span>
                            <span>Kampus <strong>{{ ucfirst($report->campus_location) }}</strong></span>
                            <span>&bull;</span>
                            <span>{{ $report->created_at->translatedFormat('d F Y, H:i') }}</span>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900">{{ $report->title }}</h2>
                    </div>

                    <div class="prose max-w-none text-sm text-slate-700 whitespace-pre-line leading-relaxed mb-6">
                        {{ $report->description }}
                    </div>

                    <!-- Initial Evidence Attachments -->
                    @if ($report->evidences->where('type', 'initial')->isNotEmpty())
                        <div class="border-t border-slate-100 pt-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Lampiran Bukti Awal Pelapor:</h4>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($report->evidences->where('type', 'initial') as $ev)
                                    <a href="{{ asset('storage/' . $ev->file_path) }}" target="_blank"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#ccfbf1] bg-[#f0fdfa] text-xs font-semibold text-[#0a8080] hover:bg-[#ccfbf1] transition">
                                        <svg class="w-4 h-4 text-[#0a8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <span>Buka Lampiran Bukti</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Dispositions History -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
                    <h3 class="text-base font-bold text-slate-900 mb-4">Riwayat Jejak Disposisi & Catatan</h3>
                    @if ($report->dispositions->isEmpty())
                        <p class="text-xs text-slate-400 italic">Belum ada tindakan disposisi untuk laporan ini.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($report->dispositions as $disp)
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#ecfdf5] text-[#0a8080] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                        {{ substr($disp->sender?->role ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="text-xs flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-slate-900">{{ $disp->sender?->name }} ({{ strtoupper($disp->sender?->role) }})</span>
                                            @if ($disp->receiver)
                                                <span class="text-slate-400">&rarr;</span>
                                                <span class="font-bold text-[#0a8080]">{{ $disp->receiver->name }} ({{ strtoupper($disp->receiver->role) }})</span>
                                            @endif
                                            <span class="text-slate-400 ml-1">{{ $disp->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-slate-700 bg-white p-3 rounded-xl border border-slate-100 mt-2">{{ $disp->notes }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Action Sidebar matching Figma node 123:1117 -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Reporter Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Identitas Pelapor</h4>
                    @if ($report->user)
                        <div class="space-y-2 text-xs">
                            <p class="text-base font-bold text-slate-900">{{ $report->user->name }}</p>
                            <p class="text-slate-500">NIM/NIP: <strong class="text-slate-800">{{ $report->user->identity_number }}</strong></p>
                            <p class="text-slate-500">Jabatan: <strong class="text-slate-800">{{ ucfirst($report->user->position) }}</strong></p>
                            <p class="text-slate-500">Email: <strong class="text-slate-800">{{ $report->user->email }}</strong></p>
                        </div>
                    @else
                        <p class="text-xs font-semibold text-amber-700">Pelapor Anonim (Whistleblowing)</p>
                    @endif
                </div>

                <!-- Action Buttons Box -->
                @if (!in_array($report->status, ['selesai', 'ditolak']))
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-md space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Tindakan KABAG</h4>

                        <!-- Button Disposisi -->
                        <button type="button" @click="showDisposeModal = true"
                            class="w-full py-3.5 px-4 rounded-xl font-bold bg-[#0a8080] hover:bg-[#086666] text-white text-sm shadow transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                            <span>Disposisi Laporan</span>
                        </button>

                        <!-- Button Kerjakan Sendiri -->
                        <form action="{{ route('kabag.self-execute', $report) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memproses laporan ini secara mandiri?')"
                                class="w-full py-3 px-4 rounded-xl font-bold bg-[#ecfdf5] hover:bg-[#ccfbf1] text-[#0a8080] text-sm border border-[#a7f3d0] transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span>Kerjakan Sendiri</span>
                            </button>
                        </form>

                        <!-- Button Tolak Laporan -->
                        <button type="button" @click="showRejectModal = true"
                            class="w-full py-2.5 px-4 rounded-xl font-semibold bg-red-50 hover:bg-red-100 text-red-700 text-xs border border-red-200 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span>Tolak Laporan (Spam / Invalid)</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Modal Disposisi Bersyarat (Alpine.js) matching authority specifications -->
    <div x-show="showDisposeModal" style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100"
             @click.away="showDisposeModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div>
                    <h3 class="text-xl font-bold text-[#0c3934]">Form Disposisi Laporan</h3>
                    <p class="text-xs text-slate-500">Tentukan penerima disposisi sesuai hierarki wewenang</p>
                </div>
                <button type="button" @click="showDisposeModal = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('kabag.dispose', $report) }}" method="POST" class="space-y-4">
                @csrf

                <!-- Override sub-department if misrouted -->
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Koreksi Sub Bagian Tujuan (Jika Salah Alamat)</label>
                    <select name="target_sub_department_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 text-sm bg-white focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080]">
                        @foreach ($subDepartments as $dept)
                            <option value="{{ $dept->id }}" {{ $report->sub_department_id == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Choose Target Type: Kasubag vs Petugas Kabag -->
                @php
                    $isDekanat = $report->subDepartment && ($report->subDepartment->code === 'DEK' || str_contains(strtolower($report->subDepartment->name), 'dekanat'));
                @endphp

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Target Disposisi</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center p-3 rounded-xl border cursor-pointer transition"
                               :class="targetType === 'kasubag' ? 'border-[#0a8080] bg-[#ecfdf5]' : 'border-slate-200'">
                            <input type="radio" name="target_type" value="kasubag" x-model="targetType" class="text-[#0a8080] focus:ring-[#0a8080]">
                            <span class="ml-2 text-xs font-bold text-slate-800">Kasubag Terkait</span>
                        </label>

                        @if ($isDekanat)
                            <label class="flex items-center p-3 rounded-xl border cursor-pointer transition"
                                   :class="targetType === 'petugas' ? 'border-[#0a8080] bg-[#ecfdf5]' : 'border-slate-200'">
                                <input type="radio" name="target_type" value="petugas" x-model="targetType" class="text-[#0a8080] focus:ring-[#0a8080]">
                                <span class="ml-2 text-xs font-bold text-slate-800">Petugas Dekanat</span>
                            </label>
                        @else
                            <div class="p-3 rounded-xl border border-slate-100 bg-slate-50 text-[11px] text-slate-400 flex items-center">
                                Petugas hanya untuk Dekanat
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Receiver Select -->
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Pilih Penerima Disposisi <span class="text-red-500">*</span></label>
                    <!-- Kasubags list -->
                    <select name="receiver_id" x-show="targetType === 'kasubag'" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080]">
                        <option value="">Pilih Kasubag</option>
                        @foreach ($kasubags as $kb)
                            <option value="{{ $kb->id }}">{{ $kb->name }} ({{ $kb->subDepartment?->name ?? 'Kasubag' }})</option>
                        @endforeach
                    </select>

                    <!-- Petugas Dekanat list -->
                    @if ($isDekanat)
                        <select name="receiver_id" x-show="targetType === 'petugas'"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080]">
                            <option value="">Pilih Petugas Dekanat</option>
                            @foreach ($petugasKabag as $pk)
                                <option value="{{ $pk->id }}">{{ $pk->name }} (Petugas Dekanat)</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Catatan / Arahan Disposisi</label>
                    <textarea name="notes" rows="3" required placeholder="Tuliskan arahan pelaksanaan..."
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#0a8080] focus:border-[#0a8080]"></textarea>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-grow py-3 rounded-xl font-bold bg-[#0a8080] text-white hover:bg-[#086666] transition text-sm shadow-md">
                        Kirim Disposisi Sekarang
                    </button>
                    <button type="button" @click="showDisposeModal = false" class="py-3 px-5 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tolak Laporan (Alpine.js) -->
    <div x-show="showRejectModal" style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100"
             @click.away="showRejectModal = false">
            <h3 class="text-xl font-bold text-red-700 mb-1">Tolak Laporan Ini?</h3>
            <p class="text-xs text-slate-500 mb-4">Sertakan alasan objektif mengapa laporan ditolak (misal: spam, duplikat, di luar kewenangan kampus).</p>

            <form action="{{ route('kabag.reject', $report) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="rejection_reason" rows="3" required placeholder="Contoh: Laporan bukan merupakan aset kampus FIKKIA..."
                        class="w-full px-4 py-2.5 rounded-xl border border-red-300 focus:ring-2 focus:ring-red-500 text-sm"></textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="submit" class="flex-grow py-2.5 rounded-xl font-bold bg-red-600 hover:bg-red-700 text-white text-sm transition">
                        Konfirmasi Penolakan
                    </button>
                    <button type="button" @click="showRejectModal = false" class="py-2.5 px-4 rounded-xl font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 text-sm">
                        Batal
                    </button>
                </div>
            </form>
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
