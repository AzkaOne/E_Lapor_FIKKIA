<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Petugas - Panel KASUBAG E-Lapor FIKKIA UNAIR</title>

  @include('komponen.favicon')
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'teal-brand': '#2E978E',
            'teal-dark': '#0C3934',
            'teal-active': '#166E65',
            'teal-light': '#CCFBF1',
            'teal-theme': '#0A8080',
            'teal-nav': '#1A5B51',
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'Poppins', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    :root {
      --brand-teal: #0a8080;
      --dark-teal: #0c3934;
      --active-teal: #1a5b51;
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased min-h-screen flex flex-col selection:bg-[#2E978E] selection:text-white">

  <!-- ================= TOP HEADER BAR ================= -->
  @include('komponen.header_kabag')

  <!-- ================= MAIN LAYOUT WRAPPER ================= -->
  <div class="flex-1 flex flex-col min-w-0 lg:ml-52 min-h-screen">

    @include('komponen.sidebar_Kabag')

    <main class="flex-1 p-4 sm:p-6 lg:p-7 pt-20 sm:pt-24 lg:pt-24 max-w-[1400px] w-full mx-auto space-y-6">

      <!-- Flash Messages -->
      @if (session('success'))
        <div class="p-3.5 sm:p-4 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#065F46] text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
          <div class="w-6 h-6 rounded-full bg-[#059669] text-white flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if (session('error'))
        <div class="p-3.5 sm:p-4 rounded-2xl bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
          <div class="w-6 h-6 rounded-full bg-[#DC2626] text-white flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      @if ($errors->any())
        <div class="p-3.5 sm:p-4 rounded-2xl bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-xs sm:text-sm font-medium space-y-1 shadow-xs">
          <div class="font-bold mb-1 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Terdapat beberapa kesalahan input:</span>
          </div>
          @foreach ($errors->all() as $err)
            <p class="ml-6">• {{ $err }}</p>
          @endforeach
        </div>
      @endif

      <!-- Main White Card Container -->
      <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-7 space-y-6">

        <!-- Top Title & Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#CCFBF1] text-[#0A8080] flex items-center justify-center shrink-0 shadow-xs">
              <svg class="w-5 h-5 text-[#0A8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <div>
              <h1 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-snug">Manajemen Petugas</h1>
              <p class="text-xs text-slate-500">
                Kelola staf pelaksana dan akun petugas lapangan pada unit <span class="font-bold text-[#0A8080]">{{ auth()->user()->subDepartment?->name ?? 'Sub Bagian Anda' }}</span>
              </p>
            </div>
          </div>

          <button type="button" onclick="openAddModal()"
                  class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A8080] hover:bg-[#086666] text-white font-bold text-xs sm:text-sm shadow-xs transition hover:shadow-sm cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Petugas Baru</span>
          </button>
        </div>

        <!-- ================= SEARCH & FILTERS ================= -->
        <form method="GET" action="{{ route('kasubag.petugas.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-2.5 sm:gap-3">

          <!-- Search Field -->
          <div class="relative flex-1 min-w-0">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama pegawai, email UNAIR, atau NIP/NIK..."
                   class="w-full pl-3.5 pr-10 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-[#2E978E] focus:border-[#2E978E] focus:outline-none transition">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#2E978E] transition" title="Cari">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>

          <!-- Fixed Bidang Indicator -->
          <div class="px-3.5 py-2 rounded-xl border border-teal-200/80 bg-teal-50/70 text-xs font-semibold text-[#0C706D] flex items-center gap-2 shrink-0">
            <svg class="w-3.5 h-3.5 text-[#0A8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span>Unit: {{ auth()->user()->subDepartment?->name ?? 'Sub Bagian Anda' }}</span>
          </div>

          <!-- Filter Role -->
          <div class="w-full sm:w-36 shrink-0">
            <select name="role" onchange="this.form.submit()"
                    class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 font-medium focus:ring-2 focus:ring-[#2E978E] focus:border-[#2E978E] focus:outline-none transition cursor-pointer"
                    title="Filter Berdasarkan Role">
              <option value="">Semua Peran</option>
              <option value="kasubag" {{ request('role') === 'kasubag' ? 'selected' : '' }}>KASUBAG</option>
              <option value="petugas" {{ request('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
            </select>
          </div>

          <!-- Reset Filter -->
          <div class="w-full sm:w-auto shrink-0">
            <a href="{{ route('kasubag.petugas.index') }}"
               class="w-full sm:w-auto px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-semibold transition text-center shrink-0 flex items-center justify-center gap-1.5"
               title="Reset Semua Filter">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              <span>Reset</span>
            </a>
          </div>

        </form>

        <!-- ================= TABLE SECTION ================= -->
        <div class="border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs">
          <div class="overflow-x-auto custom-scroll">
            <table class="w-full text-left text-xs min-w-[850px]">
              <thead class="bg-[#F8FAFC] border-b border-slate-200 text-slate-800 font-bold uppercase text-[11px] tracking-wide">
                <tr>
                  <th class="py-3 px-3.5 w-12 text-center">No</th>
                  <th class="py-3 px-3.5">Nama Pegawai</th>
                  <th class="py-3 px-3.5">Email</th>
                  <th class="py-3 px-3.5">NIP/NIK</th>
                  <th class="py-3 px-3.5">Role</th>
                  <th class="py-3 px-3.5">Bidang</th>
                  <th class="py-3 px-3.5 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($users as $index => $user)
                  <tr class="hover:bg-slate-50/70 transition">
                    <!-- No -->
                    <td class="py-4 px-3.5 text-center font-bold text-slate-900">
                      {{ $users->firstItem() ? $users->firstItem() + $index : $index + 1 }}
                    </td>

                    <!-- Nama Pegawai -->
                    <td class="py-4 px-3.5">
                      <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-[#CCFBF1] text-[#0D9488] flex items-center justify-center font-bold text-xs shrink-0">
                          {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                          <div class="font-bold text-slate-900 text-[13px] leading-snug line-clamp-1">
                            {{ $user->name }}
                          </div>
                          <div class="text-[10.5px] text-slate-400 mt-0.5">
                            Tenaga Kependidikan (Tendik)
                          </div>
                        </div>
                      </div>
                    </td>

                    <!-- Email -->
                    <td class="py-4 px-3.5">
                      <div class="font-mono text-xs text-slate-700 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $user->email }}</span>
                      </div>
                    </td>

                    <!-- NIP/NIK -->
                    <td class="py-4 px-3.5">
                      <span class="font-mono text-xs font-semibold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg">
                        {{ $user->identity_number }}
                      </span>
                    </td>

                    <!-- Role -->
                    <td class="py-4 px-3.5">
                      @if ($user->role === 'kasubag')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                          <span>KASUBAG</span>
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EFF6FF] text-[#2563EB] border border-[#BFDBFE]">
                          <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6]"></span>
                          <span>Petugas</span>
                        </span>
                      @endif
                    </td>

                    <!-- Bidang -->
                    <td class="py-4 px-3.5">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#F8FAFC] text-slate-700 border border-slate-200">
                        <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>{{ $user->subDepartment?->name ?? 'Sub Bagian' }}</span>
                      </span>
                    </td>

                    <!-- Aksi -->
                    <td class="py-4 px-3.5 text-center">
                      <div class="inline-flex items-center justify-center gap-1.5">

                        <!-- Button Detail (Selalu muncul untuk semua role) -->
                        <button type="button"
                                onclick="openDetailModal({{ json_encode([
                                  'id' => $user->id,
                                  'name' => $user->name,
                                  'email' => $user->email,
                                  'identity_number' => $user->identity_number,
                                  'role' => strtoupper($user->role),
                                  'sub_department' => $user->subDepartment?->name ?? 'Sub Bagian',
                                  'created_at' => $user->created_at ? $user->created_at->format('d M Y, H:i') : '-',
                                ]) }})"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-[#0D9488] text-[#0D9488] hover:bg-[#0D9488] hover:text-white transition font-bold text-xs shadow-xs cursor-pointer">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                          <span>Detail</span>
                        </button>

                        <!-- Menu Titik 3 (Hanya untuk Petugas; Kasubag TIDAK bisa diedit/dihapus) -->
                        @if ($user->role !== 'kasubag')
                          <div class="relative inline-block text-left" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" type="button"
                                    class="w-8 h-8 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 flex items-center justify-center text-slate-600 hover:text-slate-900 transition shadow-xs cursor-pointer"
                                    title="Pilihan Aksi">
                              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="5" r="1.75"></circle>
                                <circle cx="12" cy="12" r="1.75"></circle>
                                <circle cx="12" cy="19" r="1.75"></circle>
                              </svg>
                            </button>

                            <!-- Dropdown Items -->
                            <div x-show="open" x-cloak
                                 class="origin-top-right absolute right-0 mt-1 w-32 rounded-xl bg-white shadow-lg border border-slate-200/90 z-20 py-1 text-xs divide-y divide-slate-100">
                              <!-- Edit -->
                              <button type="button"
                                      @click="open = false; openEditModal({{ json_encode([
                                        'id' => $user->id,
                                        'name' => $user->name,
                                        'email' => $user->email,
                                        'identity_number' => $user->identity_number,
                                        'role' => $user->role,
                                      ]) }})"
                                      class="w-full text-left px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-[#0D9488] font-semibold flex items-center gap-2 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Edit</span>
                              </button>

                              <!-- Hapus -->
                              <button type="button"
                                      @click="open = false; openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                      class="w-full text-left px-3 py-2 text-rose-600 hover:bg-rose-50 font-semibold flex items-center gap-2 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Hapus</span>
                              </button>
                            </div>
                          </div>
                        @endif

                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="py-14 px-4 text-center">
                      <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                        <div class="w-14 h-14 rounded-2xl bg-[#CCFBF1]/60 border border-[#99F6E4] flex items-center justify-center text-[#0a8080] mb-3 shadow-xs">
                          <svg class="w-7 h-7 text-[#0a8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                          </svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">Tidak ada data pegawai</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                          @if (request()->hasAny(['search', 'role']))
                            Tidak ada data yang cocok dengan kriteria pencarian Anda. Silakan klik tombol reset di bawah.
                          @else
                            Saat ini belum ada data petugas lapangan yang terdaftar pada unit {{ auth()->user()->subDepartment?->name ?? 'Anda' }}.
                          @endif
                        </p>
                        @if (request()->hasAny(['search', 'role']))
                          <a href="{{ route('kasubag.petugas.index') }}"
                             class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white text-xs font-semibold transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Reset Semua Filter</span>
                          </a>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          @if ($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-white">
              {{ $users->links() }}
            </div>
          @endif
        </div>

      </div>

    </main>

  </div>

  <!-- ================= MODAL TAMBAH PETUGAS ================= -->
  <div id="modal-add"
       class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/50 backdrop-blur-sm p-4">

      <div class="min-h-full flex items-center justify-center">

          <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden my-6">

              <!-- HEADER -->
              <div class="bg-gradient-to-r from-[#0C706D] to-[#0a8b85] px-6 py-5">
                  <div class="flex items-center justify-between">

                      <div class="flex items-center gap-3">
                          <div class="w-11 h-11 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-white">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v16m8-8H4" />
                              </svg>
                          </div>

                          <div>
                              <h3 class="text-lg font-bold text-white" id="modal-add-title">
                                  Tambah Petugas Baru
                              </h3>
                              <p class="text-xs text-teal-50 mt-0.5" id="modal-add-subtitle">
                                  Daftarkan akun dan informasi petugas lapangan
                              </p>
                          </div>
                      </div>

                      <button type="button" onclick="closeAddModal()"
                          class="w-9 h-9 flex items-center justify-center rounded-lg text-white/80 hover:text-white hover:bg-white/15 transition cursor-pointer">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                          </svg>
                      </button>

                  </div>
              </div>

              <!-- FORM MANUAL -->
              <form id="form-add-manual" action="{{ route('kasubag.petugas.store') }}" method="POST"
                  class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">

                  @csrf

                  <!-- INFORMASI PETUGAS -->
                  <div class="space-y-4">

                      <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                          <div class="w-7 h-7 rounded-lg bg-teal-50 text-[#0a8080] flex items-center justify-center">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m16 0v-2a4 4 0 00-3-3.87M10 11a4 4 0 100-8 4 4 0 000 8zm8-3a4 4 0 11-8 0" />
                              </svg>
                          </div>

                          <div>
                              <h4 class="text-sm font-bold text-slate-800">
                                  Informasi Petugas
                              </h4>
                              <p class="text-[11px] text-slate-400">
                                  Masukkan identitas dasar petugas baru
                              </p>
                          </div>
                      </div>

                      <!-- NAMA -->
                      <div>
                          <label for="add-name"
                              class="block text-xs font-semibold text-slate-700 mb-1.5">
                              Nama Lengkap & Gelar
                              <span class="text-red-500">*</span>
                          </label>

                          <input type="text" id="add-name" name="name" required
                              placeholder="Contoh: Ahmad Dani, A.Md."
                              class="w-full text-sm rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3
                              placeholder:text-slate-400
                              focus:bg-white focus:border-[#0a8080] focus:ring-4 focus:ring-[#0a8080]/10
                              focus:outline-none transition">
                      </div>

                      <!-- EMAIL & NIP -->
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                          <div>
                              <label for="add-email"
                                  class="block text-xs font-semibold text-slate-700 mb-1.5">
                                  Email Resmi UNAIR
                                  <span class="text-red-500">*</span>
                              </label>

                              <input type="email" id="add-email" name="email" required
                                  placeholder="nama@unair.ac.id"
                                  class="w-full text-sm rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3
                                  placeholder:text-slate-400
                                  focus:bg-white focus:border-[#0a8080] focus:ring-4 focus:ring-[#0a8080]/10
                                  focus:outline-none transition">
                          </div>

                          <div>
                              <label for="add-identity"
                                  class="block text-xs font-semibold text-slate-700 mb-1.5">
                                  NIP / NIK Pegawai
                                  <span class="text-red-500">*</span>
                              </label>

                              <input type="text" id="add-identity" name="identity_number" required
                                  placeholder="Masukkan nomor identitas"
                                  class="w-full text-sm rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3
                                  placeholder:text-slate-400
                                  focus:bg-white focus:border-[#0a8080] focus:ring-4 focus:ring-[#0a8080]/10
                                  focus:outline-none transition">
                          </div>

                      </div>
                  </div>

                  <!-- PENUGASAN (TERKUNCI KE PETUGAS DAN UNIT KASUBAG) -->
                  <div class="space-y-4">

                      <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                          <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 21h18M5 21V7l8-4v18m-5-9h2m-2 4h2m4-7h2m-2 4h2m-2 4h2" />
                              </svg>
                          </div>

                          <div>
                              <h4 class="text-sm font-bold text-slate-800">
                                  Penugasan Petugas
                              </h4>
                              <p class="text-[11px] text-slate-400">
                                  Petugas akan otomatis ditugaskan pada unit kerja Anda
                              </p>
                          </div>
                      </div>

                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                          <!-- ROLE LOCKED -->
                          <div>
                              <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                  Peran (Role)
                              </label>
                              <div class="w-full text-sm rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-700 font-semibold flex items-center justify-between">
                                  <span>Petugas Lapangan</span>
                                  <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 text-[11px] font-bold">petugas</span>
                              </div>
                          </div>

                          <!-- UNIT LOCKED -->
                          <div>
                              <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                  Sub Bagian / Unit Kerja
                              </label>
                              <div class="w-full text-sm rounded-xl border border-teal-200 bg-teal-50/60 px-4 py-3 text-[#0C706D] font-bold flex items-center justify-between">
                                  <span class="truncate">{{ auth()->user()->subDepartment?->name ?? 'Sub Bagian Anda' }}</span>
                                  <span class="px-2 py-0.5 rounded-md bg-[#CCFBF1] text-[#0A8080] text-[11px]">Terkunci</span>
                              </div>
                          </div>

                      </div>
                  </div>

                  <!-- KEAMANAN AKUN -->
                  <div class="rounded-xl border border-amber-200/80 bg-amber-50/50 p-4 space-y-3">

                      <div class="flex items-start gap-3">
                          <div class="w-8 h-8 shrink-0 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm2-11V7a4 4 0 118 0v2" />
                              </svg>
                          </div>

                          <div>
                              <h4 class="text-sm font-bold text-slate-800">
                                  Keamanan Akun
                              </h4>
                              <p class="text-[11px] text-slate-500 mt-0.5">
                                  Buat password awal untuk akun petugas.
                              </p>
                          </div>
                      </div>

                      <div>
                          <label for="add-password"
                              class="block text-xs font-semibold text-slate-700 mb-1.5">
                              Password Default
                              <span class="text-red-500">*</span>
                          </label>

                          <input type="password" id="add-password" name="password"
                              required minlength="6" autocomplete="new-password"
                              placeholder="Masukkan minimal 6 karakter"
                              class="w-full text-sm rounded-xl border border-amber-200 bg-white px-4 py-3
                              placeholder:text-slate-400
                              focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10
                              focus:outline-none transition">

                          <p class="text-[11px] text-slate-400 mt-1.5">
                              Password digunakan untuk login pertama kali petugas.
                          </p>
                      </div>
                  </div>

                  <!-- FOOTER -->
                  <div class="flex flex-col-reverse sm:flex-row justify-end items-center gap-2.5 pt-4 border-t border-slate-100">

                      <button type="button" onclick="closeAddModal()"
                          class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600
                          text-xs font-bold hover:bg-slate-50 hover:border-slate-300 transition cursor-pointer">
                          Batal
                      </button>

                      <button type="button" onclick="switchAddMode('import')"
                          class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5
                          rounded-xl border border-[#0a8080] text-[#0a8080] bg-teal-50 hover:bg-teal-100/80
                          text-xs font-bold transition cursor-pointer">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                          </svg>
                          Import Excel / CSV
                      </button>

                      <button type="submit"
                          class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5
                          rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white
                          text-xs font-bold shadow-sm hover:shadow-md transition cursor-pointer">

                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7" />
                          </svg>

                          Simpan Petugas
                      </button>

                  </div>

              </form>

              <!-- FORM IMPORT EXCEL / CSV -->
              <form id="form-add-import" action="{{ route('kasubag.petugas.import') }}" method="POST" enctype="multipart/form-data"
                  class="hidden p-6 space-y-5 max-h-[75vh] overflow-y-auto">

                  @csrf

                  <!-- HEADER BANNER & TEMPLATE DOWNLOAD -->
                  <div class="rounded-2xl border border-teal-200 bg-teal-50/60 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                      <div class="flex items-start gap-3">
                          <div class="w-9 h-9 rounded-xl bg-[#0a8080] text-white flex items-center justify-center shrink-0">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                              </svg>
                          </div>
                          <div>
                              <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                  Format Berkas Excel & CSV Petugas
                              </h4>
                              <p class="text-[11px] text-slate-600 mt-0.5">
                                  Unggah berkas data petugas untuk disimpan ke unit <span class="font-bold text-[#0A8080]">{{ auth()->user()->subDepartment?->name ?? 'Anda' }}</span>.
                              </p>
                          </div>
                      </div>

                      <a href="{{ route('kasubag.petugas.template') }}"
                          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-teal-300 text-[#0a8080] hover:bg-teal-50 text-xs font-bold shadow-xs transition shrink-0 cursor-pointer">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                          </svg>
                          Unduh Template Petugas
                      </a>
                  </div>

                  <!-- DROPZONE UPLOAD AREA -->
                  <div>
                      <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                          Pilih Berkas Excel / CSV <span class="text-red-500">*</span>
                      </label>

                      <div id="dropzone-box" 
                          onclick="document.getElementById('import-excel-file').click()"
                          class="border-2 border-dashed border-teal-300 rounded-2xl p-6 text-center bg-teal-50/20 hover:bg-teal-50/50 hover:border-teal-400 transition cursor-pointer relative">
                          
                          <input type="file" id="import-excel-file" name="file" accept=".xlsx,.xls,.csv" required
                              class="hidden" onchange="handleImportFileSelect(this)">

                          <!-- Prompt State -->
                          <div id="import-dropzone-prompt" class="space-y-2">
                              <div class="w-12 h-12 rounded-2xl bg-teal-100 text-[#0a8080] flex items-center justify-center mx-auto shadow-xs">
                                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                  </svg>
                              </div>
                              <div class="text-xs font-bold text-slate-800">
                                  Klik untuk memilih berkas Excel atau CSV
                              </div>
                              <p class="text-[11px] text-slate-500">
                                  Mendukung format: <strong class="text-teal-700">.xlsx, .xls, .csv</strong> (Maksimal 10 MB)
                              </p>
                          </div>

                          <!-- Selected File State -->
                          <div id="import-file-preview" class="hidden flex items-center justify-between p-3 bg-white rounded-xl border border-teal-300 shadow-xs">
                              <div class="flex items-center gap-3 min-w-0">
                                  <div class="w-10 h-10 rounded-lg bg-teal-100 text-[#0a8080] flex items-center justify-center shrink-0">
                                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                      </svg>
                                  </div>
                                  <div class="text-left min-w-0">
                                      <span id="import-file-name" class="block text-xs font-bold text-slate-800 truncate"></span>
                                      <span id="import-file-size" class="block text-[10px] text-slate-400"></span>
                                  </div>
                              </div>

                              <button type="button" onclick="clearImportFile(event)"
                                  class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition cursor-pointer"
                                  title="Hapus file">
                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                  </svg>
                              </button>
                          </div>

                      </div>
                  </div>

                  <!-- GUIDELINES -->
                  <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 space-y-2 text-xs text-slate-600">
                      <div class="font-bold text-slate-800 flex items-center gap-1.5">
                          <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          Ketentuan Import Petugas:
                      </div>
                      <ul class="list-disc list-inside space-y-1 text-[11px] text-slate-600 pl-1">
                          <li><strong>Password Kosong:</strong> Otomatis menggunakan nomor <strong>NIP/NIK</strong> petugas sebagai password awal.</li>
                          <li><strong>Role & Unit Terkunci:</strong> Seluruh data akan otomatis didaftarkan sebagai role <code>petugas</code> pada unit <strong>{{ auth()->user()->subDepartment?->name ?? 'Anda' }}</strong>.</li>
                          <li><strong>Email:</strong> Harus berupa email resmi UNAIR (<code>@unair.ac.id</code>) yang valid dan belum terdaftar.</li>
                      </ul>
                  </div>

                  <!-- FOOTER -->
                  <div class="flex flex-col-reverse sm:flex-row justify-end items-center gap-2.5 pt-4 border-t border-slate-100">

                      <button type="button" onclick="closeAddModal()"
                          class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600
                          text-xs font-bold hover:bg-slate-50 hover:border-slate-300 transition cursor-pointer">
                          Batal
                      </button>

                      <button type="button" onclick="switchAddMode('manual')"
                          class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5
                          rounded-xl border border-slate-200 text-slate-700 bg-slate-50 hover:bg-slate-100
                          text-xs font-bold transition cursor-pointer">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                          </svg>
                          Input Manual
                      </button>

                      <button type="submit"
                          class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5
                          rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white
                          text-xs font-bold shadow-sm hover:shadow-md transition cursor-pointer">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                          </svg>
                          Simpan Petugas
                      </button>

                  </div>

              </form>
          </div>
      </div>
  </div>

  <!-- ================= MODAL DETAIL PEGAWAI ================= -->
  <div id="modal-detail" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-5">

      <div class="text-center space-y-2">
        <div id="detail-avatar" class="w-16 h-16 rounded-full bg-[#CCFBF1] text-[#0D9488] flex items-center justify-center font-extrabold text-xl mx-auto shadow-xs">
          -
        </div>
        <div>
          <h4 id="detail-name" class="font-bold text-slate-900 text-base">-</h4>
          <p class="text-xs text-slate-400">Tenaga Kependidikan (Tendik)</p>
        </div>
        <div class="flex items-center justify-center gap-2 pt-1">
          <span id="detail-role" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0] uppercase">-</span>
          <span id="detail-subdept" class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">-</span>
        </div>
      </div>

      <div class="bg-slate-50 rounded-2xl p-4 space-y-2.5 text-xs border border-slate-100">
        <div class="flex items-center justify-between">
          <span class="text-slate-400 font-medium">NIP / NIK</span>
          <span id="detail-nip" class="font-mono font-bold text-slate-800">-</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-slate-400 font-medium">Email UNAIR</span>
          <span id="detail-email" class="font-mono text-slate-800">-</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-slate-400 font-medium">Terdaftar Sejak</span>
          <span id="detail-created" class="text-slate-800 font-medium">-</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-slate-400 font-medium">Status Akun</span>
          <span class="inline-flex items-center gap-1 text-[#059669] font-bold">
            <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
            <span>Aktif</span>
          </span>
        </div>
      </div>

      <div class="pt-2 text-right">
        <button type="button" onclick="closeDetailModal()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer">
          Tutup
        </button>
      </div>
    </div>
  </div>

  <!-- ================= MODAL EDIT PETUGAS ================= -->
  <div id="modal-edit"
      class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-950/50 backdrop-blur-sm p-4">

      <div class="min-h-full flex items-center justify-center">

          <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden my-6">

              <!-- HEADER -->
              <div class="bg-gradient-to-r from-[#0C706D] to-[#0a8b85] px-6 py-5">
                  <div class="flex items-center justify-between">

                      <div class="flex items-center gap-3">
                          <div class="w-11 h-11 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-white">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M16 4h2a2 2 0 012 2v14H4V6a2 2 0 012-2h2m2-2h4a2 2 0 012 2v2h-8V4a2 2 0 012-2zM9 12h6m-6 4h6" />
                              </svg>
                          </div>

                          <div>
                              <h3 class="text-lg font-bold text-white">
                                  Edit Data Petugas
                              </h3>
                              <p class="text-xs text-teal-50 mt-0.5">
                                  Perbarui informasi dan akun petugas lapangan
                              </p>
                          </div>
                      </div>

                      <button type="button" onclick="closeEditModal()"
                          class="w-9 h-9 flex items-center justify-center rounded-lg text-white/80 hover:text-white hover:bg-white/15 transition cursor-pointer">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                          </svg>
                      </button>
                  </div>
              </div>

              <!-- FORM CONTENT -->
              <form id="form-edit" method="POST"
                  class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">

                  @csrf
                  @method('PUT')

                  <!-- INFORMASI PETUGAS -->
                  <div class="space-y-4">

                      <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                          <div class="w-7 h-7 rounded-lg bg-teal-50 text-[#0a8080] flex items-center justify-center">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m16 0v-2a4 4 0 00-3-3.87M10 11a4 4 0 100-8 4 4 0 000 8zm8-3a4 4 0 11-8 0" />
                              </svg>
                          </div>

                          <div>
                              <h4 class="text-sm font-bold text-slate-800">
                                  Informasi Petugas
                              </h4>
                              <p class="text-[11px] text-slate-400">
                                  Identitas dan data dasar petugas
                              </p>
                          </div>
                      </div>

                      <!-- NAMA -->
                      <div>
                          <label for="edit-name"
                              class="block text-xs font-semibold text-slate-700 mb-1.5">
                              Nama Lengkap & Gelar
                              <span class="text-red-500">*</span>
                          </label>

                          <input type="text" id="edit-name" name="name" required
                              placeholder="Masukkan nama lengkap petugas"
                              class="w-full text-sm rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3
                              placeholder:text-slate-400
                              focus:bg-white focus:border-[#0a8080] focus:ring-4 focus:ring-[#0a8080]/10
                              focus:outline-none transition">
                      </div>

                      <!-- EMAIL & NIP -->
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                          <div>
                              <label for="edit-email"
                                  class="block text-xs font-semibold text-slate-700 mb-1.5">
                                  Email Resmi UNAIR
                                  <span class="text-red-500">*</span>
                              </label>

                              <input type="email" id="edit-email" name="email" required
                                  placeholder="nama@unair.ac.id"
                                  class="w-full text-sm rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3
                                  placeholder:text-slate-400
                                  focus:bg-white focus:border-[#0a8080] focus:ring-4 focus:ring-[#0a8080]/10
                                  focus:outline-none transition">
                          </div>

                          <div>
                              <label for="edit-nip"
                                  class="block text-xs font-semibold text-slate-700 mb-1.5">
                                  NIP / NIK Pegawai
                                  <span class="text-red-500">*</span>
                              </label>

                              <input type="text" id="edit-nip" name="identity_number" required
                                  placeholder="Masukkan nomor identitas"
                                  class="w-full text-sm rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3
                                  placeholder:text-slate-400
                                  focus:bg-white focus:border-[#0a8080] focus:ring-4 focus:ring-[#0a8080]/10
                                  focus:outline-none transition">
                          </div>

                      </div>
                  </div>

                  <!-- PENUGASAN (READONLY DISPLAY) -->
                  <div class="space-y-4">

                      <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                          <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 21h18M5 21V7l8-4v18m-5-9h2m-2 4h2m4-7h2m-2 4h2m-2 4h2" />
                              </svg>
                          </div>

                          <div>
                              <h4 class="text-sm font-bold text-slate-800">
                                  Penugasan Petugas
                              </h4>
                              <p class="text-[11px] text-slate-400">
                                  Peran dan unit kerja petugas pada sistem
                              </p>
                          </div>
                      </div>

                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                          <!-- ROLE LOCKED -->
                          <div>
                              <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                  Peran (Role)
                              </label>
                              <div class="w-full text-sm rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-700 font-semibold flex items-center justify-between">
                                  <span>Petugas Lapangan</span>
                                  <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 text-[11px] font-bold">petugas</span>
                              </div>
                          </div>

                          <!-- UNIT LOCKED -->
                          <div>
                              <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                  Sub Bagian / Unit Kerja
                              </label>
                              <div class="w-full text-sm rounded-xl border border-teal-200 bg-teal-50/60 px-4 py-3 text-[#0C706D] font-bold flex items-center justify-between">
                                  <span class="truncate">{{ auth()->user()->subDepartment?->name ?? 'Sub Bagian Anda' }}</span>
                                  <span class="px-2 py-0.5 rounded-md bg-[#CCFBF1] text-[#0A8080] text-[11px]">Terkunci</span>
                              </div>
                          </div>

                      </div>
                  </div>

                  <!-- KEAMANAN AKUN -->
                  <div class="rounded-xl border border-amber-200/80 bg-amber-50/50 p-4 space-y-3">

                      <div class="flex items-start gap-3">
                          <div class="w-8 h-8 shrink-0 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm2-11V7a4 4 0 118 0v2" />
                              </svg>
                          </div>

                          <div>
                              <h4 class="text-sm font-bold text-slate-800">
                                  Keamanan Akun
                              </h4>
                              <p class="text-[11px] text-slate-500 mt-0.5">
                                  Isi hanya jika ingin mengganti password petugas.
                              </p>
                          </div>
                      </div>

                      <div>
                          <label for="edit-password"
                              class="block text-xs font-semibold text-slate-700 mb-1.5">
                              Password Baru
                          </label>

                          <input type="password" id="edit-password" name="password"
                              minlength="6" autocomplete="new-password"
                              placeholder="Kosongkan jika tidak ingin mengubah password"
                              class="w-full text-sm rounded-xl border border-amber-200 bg-white px-4 py-3
                              placeholder:text-slate-400
                              focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10
                              focus:outline-none transition">
                          <p class="text-[11px] text-slate-400 mt-1.5">
                              Minimal 6 karakter jika password diubah.
                          </p>
                      </div>
                  </div>

                  <!-- FOOTER -->
                  <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-4 border-t border-slate-100">

                      <button type="button" onclick="closeEditModal()"
                          class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600
                          text-xs font-bold hover:bg-slate-50 hover:border-slate-300 transition cursor-pointer">
                          Batal
                      </button>

                      <button type="submit"
                          class="inline-flex items-center justify-center gap-2 px-5 py-2.5
                          rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white
                          text-xs font-bold shadow-sm hover:shadow-md transition cursor-pointer">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7" />
                          </svg>
                          Simpan Perubahan
                      </button>

                  </div>

              </form>
          </div>
      </div>
  </div>

  <!-- ================= MODAL HAPUS PETUGAS ================= -->
  <div id="modal-delete" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
      <div class="w-12 h-12 rounded-2xl bg-[#FFF1F2] text-[#F43F5E] flex items-center justify-center mx-auto border border-[#FFE4E6]">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
      </div>
      <div class="text-center space-y-1.5">
        <h3 class="text-base font-bold text-slate-900">Hapus Akun Petugas</h3>
        <p class="text-xs text-slate-500 leading-relaxed">
          Apakah Anda yakin ingin menghapus akun petugas <strong id="delete-user-name" class="text-slate-800"></strong>? Tindakan ini akan menghapus akses login akun tersebut.
        </p>
      </div>

      <form id="form-delete" method="POST" class="pt-2 flex items-center justify-center gap-2.5 text-xs">
        @csrf
        @method('DELETE')
        <button type="button" onclick="closeDeleteModal()" class="w-1/2 py-2.5 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition cursor-pointer">
          Batal
        </button>
        <button type="submit" class="w-1/2 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold shadow-xs transition cursor-pointer">
          Ya, Hapus Petugas
        </button>
      </form>
    </div>
  </div>

  <!-- ================= MODAL HASIL IMPORT ================= -->
  @if(session('import_result'))
  @php
      $importResult = session('import_result');
      $savedCount = $importResult['saved_count'] ?? 0;
      $failedCount = $importResult['failed_count'] ?? 0;
      $failedRows = $importResult['failed_rows'] ?? [];
  @endphp
  <div id="modal-import-result" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-scaleUp">
      
      <!-- Top Icon -->
      <div class="text-center">
        @if($failedCount === 0 && $savedCount > 0)
          <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-xs">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-slate-900">Import Petugas Berhasil</h3>
          <p class="text-xs text-slate-500 mt-1">Seluruh data petugas dari berkas berhasil didaftarkan ke sistem.</p>
        @else
          <div class="w-14 h-14 rounded-2xl {{ $savedCount > 0 ? 'bg-amber-100 text-amber-600' : 'bg-rose-100 text-rose-600' }} flex items-center justify-center mx-auto mb-3 shadow-xs">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-slate-900">Laporan Hasil Import Data</h3>
          <p class="text-xs text-slate-500 mt-1">Proses import berkas selesai dengan rincian berikut:</p>
        @endif
      </div>

      <!-- Stat Counters -->
      <div class="grid grid-cols-2 gap-3">
        <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200 text-center">
          <span class="block text-2xl font-black text-emerald-700">{{ $savedCount }}</span>
          <span class="block text-xs font-bold text-emerald-800 mt-0.5">
            {{ $savedCount }} data pegawai tersimpan
          </span>
        </div>

        <div class="p-4 rounded-2xl {{ $failedCount > 0 ? 'bg-rose-50/80 border border-rose-200 text-rose-700' : 'bg-slate-50 border border-slate-200 text-slate-500' }} text-center">
          <span class="block text-2xl font-black {{ $failedCount > 0 ? 'text-rose-700' : 'text-slate-600' }}">{{ $failedCount }}</span>
          <span class="block text-xs font-bold {{ $failedCount > 0 ? 'text-rose-800' : 'text-slate-600' }} mt-0.5">
            {{ $failedCount }} data pegawai tidak tersimpan
          </span>
        </div>
      </div>

      <!-- Failure reasons if any -->
      @if($failedCount > 0 && !empty($failedRows))
        <div class="space-y-2">
          <div class="flex items-center justify-between text-xs font-bold text-slate-700">
            <span>Rincian Baris yang Gagal:</span>
            <span class="text-[10px] text-slate-400 font-normal">Periksa format pada baris berikut</span>
          </div>
          <div class="max-h-40 overflow-y-auto rounded-xl border border-rose-200/80 bg-rose-50/50 p-3 space-y-2 text-xs">
            @foreach($failedRows as $fail)
              <div class="flex items-start gap-2 text-rose-900 text-[11px] leading-tight">
                <span class="font-bold shrink-0 px-1.5 py-0.5 rounded bg-rose-200/80 text-rose-800 text-[10px]">Baris {{ $fail['row'] }}</span>
                <div>
                  <strong class="text-slate-800">{{ $fail['name'] }}:</strong>
                  <span class="text-rose-700">{{ $fail['reason'] }}</span>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      <!-- Dismiss Button -->
      <div>
        <button type="button" onclick="closeImportResultModal()"
          class="w-full py-2.5 rounded-xl bg-[#0a8080] hover:bg-[#086666] text-white text-xs font-bold shadow-sm transition cursor-pointer">
          Tutup & Selesai
        </button>
      </div>

    </div>
  </div>
  @endif

  <!-- JavaScript Modal Handlers -->
  <script>
    function openAddModal() {
      switchAddMode('manual');
      document.getElementById('modal-add').classList.remove('hidden');
    }
    function closeAddModal() {
      document.getElementById('modal-add').classList.add('hidden');
    }

    function switchAddMode(mode) {
      const manualForm = document.getElementById('form-add-manual');
      const importForm = document.getElementById('form-add-import');
      const title = document.getElementById('modal-add-title');
      const subtitle = document.getElementById('modal-add-subtitle');

      if (mode === 'import') {
        manualForm.classList.add('hidden');
        importForm.classList.remove('hidden');
        if (title) title.textContent = 'Import Data Petugas (Excel / CSV)';
        if (subtitle) subtitle.textContent = 'Unggah berkas data petugas untuk disimpan sekaligus ke sistem';
      } else {
        importForm.classList.add('hidden');
        manualForm.classList.remove('hidden');
        if (title) title.textContent = 'Tambah Petugas Baru';
        if (subtitle) subtitle.textContent = 'Daftarkan akun dan informasi petugas lapangan';
      }
    }

    function handleImportFileSelect(input) {
      const preview = document.getElementById('import-file-preview');
      const fileNameSpan = document.getElementById('import-file-name');
      const fileSizeSpan = document.getElementById('import-file-size');
      const dropzonePrompt = document.getElementById('import-dropzone-prompt');

      if (input.files && input.files[0]) {
        const file = input.files[0];
        fileNameSpan.textContent = file.name;
        fileSizeSpan.textContent = (file.size / 1024).toFixed(1) + ' KB';
        preview.classList.remove('hidden');
        dropzonePrompt.classList.add('hidden');
      }
    }

    function clearImportFile(e) {
      if (e) e.stopPropagation();
      const input = document.getElementById('import-excel-file');
      const preview = document.getElementById('import-file-preview');
      const dropzonePrompt = document.getElementById('import-dropzone-prompt');
      input.value = '';
      preview.classList.add('hidden');
      dropzonePrompt.classList.remove('hidden');
    }

    function closeImportResultModal() {
      const modal = document.getElementById('modal-import-result');
      if (modal) modal.remove();
    }

    function openDetailModal(data) {
      document.getElementById('detail-avatar').textContent = (data.name || 'P').charAt(0).toUpperCase();
      document.getElementById('detail-name').textContent = data.name;
      document.getElementById('detail-role').textContent = data.role;
      document.getElementById('detail-subdept').textContent = data.sub_department;
      document.getElementById('detail-nip').textContent = data.identity_number;
      document.getElementById('detail-email').textContent = data.email;
      document.getElementById('detail-created').textContent = data.created_at;
      document.getElementById('modal-detail').classList.remove('hidden');
    }
    function closeDetailModal() {
      document.getElementById('modal-detail').classList.add('hidden');
    }

    function openEditModal(data) {
      document.getElementById('form-edit').action = "/kasubag/petugas/" + data.id;
      document.getElementById('edit-name').value = data.name;
      document.getElementById('edit-email').value = data.email;
      document.getElementById('edit-nip').value = data.identity_number;
      document.getElementById('modal-edit').classList.remove('hidden');
    }
    function closeEditModal() {
      document.getElementById('modal-edit').classList.add('hidden');
    }

    function openDeleteModal(id, name) {
      document.getElementById('form-delete').action = "/kasubag/petugas/" + id;
      document.getElementById('delete-user-name').textContent = name;
      document.getElementById('modal-delete').classList.remove('hidden');
    }
    function closeDeleteModal() {
      document.getElementById('modal-delete').classList.add('hidden');
    }
  </script>

</body>
</html>
