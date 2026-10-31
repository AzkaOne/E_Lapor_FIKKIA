<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kasubag & Petugas - KABAG E-LAPOR</title>
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
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#0a8080] selection:text-white" x-data="{ addModal: false }">
    <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('kabag.dashboard') }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-[#0a8080] hover:bg-slate-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-lg font-black text-[#0c3934] leading-tight">Manajemen Akun Kasubag & Petugas</h1>
                <p class="text-xs text-slate-500">Kelola hierarki operasional dan pimpinan sub bagian di lingkungan FIKKIA</p>
            </div>
        </div>
        <button @click="addModal = true" class="px-4 py-2.5 bg-[#0a8080] hover:bg-[#086666] text-white rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Akun Baru</span>
        </button>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 w-full flex-grow">
        @if (session('success'))
            <div class="mb-6 p-4 bg-[#ecfdf5] border border-[#a7f3d0] text-[#064e3b] rounded-2xl text-sm font-semibold flex items-center gap-3">
                <svg class="w-5 h-5 text-[#059669] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#f0fdfa] text-xs font-bold uppercase tracking-wider text-[#0c3934] border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Nama Pegawai</th>
                            <th class="px-6 py-4">Email UNAIR</th>
                            <th class="px-6 py-4">NIP / NIK</th>
                            <th class="px-6 py-4">Peran (Role)</th>
                            <th class="px-6 py-4">Sub Bagian / Unit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $user->identity_number }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                                        {{ $user->role === 'kasubag' ? 'bg-[#ccfbf1] text-[#0f766e]' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                                    {{ $user->subDepartment?->name ?? 'Dekanat' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">Belum ada akun terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </main>

    <!-- Modal Tambah Akun -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4" @click.away="addModal = false">
            <h3 class="text-lg font-bold text-[#0c3934]">Tambah Akun KASUBAG / Petugas</h3>
            <p class="text-xs text-slate-500">Buat akun kredensial operasional baru menggunakan email resmi UNAIR.</p>

            <form action="{{ route('kabag.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap & Gelar</label>
                    <input type="text" name="name" required class="w-full text-sm rounded-xl border-slate-200 focus:border-[#0a8080] focus:ring-[#0a8080]" placeholder="Contoh: Budi Santoso, S.T.">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Resmi (@unair.ac.id)</label>
                    <input type="email" name="email" required class="w-full text-sm rounded-xl border-slate-200 focus:border-[#0a8080] focus:ring-[#0a8080]" placeholder="nama@unair.ac.id">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NIP / NIK Pegawai</label>
                    <input type="text" name="identity_number" required class="w-full text-sm rounded-xl border-slate-200 focus:border-[#0a8080] focus:ring-[#0a8080]" placeholder="19800101...">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Peran (Role)</label>
                        <select name="role" required class="w-full text-sm rounded-xl border-slate-200 focus:border-[#0a8080] focus:ring-[#0a8080]">
                            <option value="kasubag">KASUBAG</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Sub Bagian / Unit</label>
                        <select name="sub_department_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-[#0a8080] focus:ring-[#0a8080]">
                            @foreach ($subDepartments as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password Default</label>
                    <input type="password" name="password" required class="w-full text-sm rounded-xl border-slate-200 focus:border-[#0a8080] focus:ring-[#0a8080]" placeholder="Minimal 6 karakter">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="addModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#0a8080] hover:bg-[#086666] text-white rounded-xl text-sm font-bold shadow-md">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
