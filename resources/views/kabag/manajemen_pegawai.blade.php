<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Pegawai - Panel KABAG E-Lapor FIKKIA UNAIR</title>
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
              <h1 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-snug">Manajemen Pegawai</h1>
              <p class="text-xs text-slate-500">Kelola hierarki operasional dan kredensial staf Kasubag & Petugas FIKKIA</p>
            </div>
          </div>

          <button type="button" onclick="openAddModal()"
                  class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A8080] hover:bg-[#086666] text-white font-bold text-xs sm:text-sm shadow-xs transition hover:shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Pegawai Baru</span>
          </button>
        </div>

        <!-- ================= SEARCH & FILTERS ================= -->
        <form method="GET" action="{{ route('kabag.users.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-2.5 sm:gap-3">

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

          <!-- Filter Bidang -->
          <div class="w-full sm:w-44 shrink-0">
            <select name="sub_department_id" onchange="this.form.submit()"
                    class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 font-medium focus:ring-2 focus:ring-[#2E978E] focus:border-[#2E978E] focus:outline-none transition truncate cursor-pointer"
                    title="Filter Berdasarkan Bidang">
              <option value="">Semua Bidang</option>
              @foreach ($subDepartments as $dept)
                <option value="{{ $dept->id }}" {{ request('sub_department_id') == $dept->id ? 'selected' : '' }}>
                  {{ $dept->name }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Filter Role -->
          <div class="w-full sm:w-36 shrink-0">
            <select name="role" onchange="this.form.submit()"
                    class="w-full px-2.5 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 font-medium focus:ring-2 focus:ring-[#2E978E] focus:border-[#2E978E] focus:outline-none transition cursor-pointer"
                    title="Filter Berdasarkan Role">
              <option value="">Semua Role</option>
              <option value="kasubag" {{ request('role') === 'kasubag' ? 'selected' : '' }}>KASUBAG</option>
              <option value="petugas" {{ request('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
            </select>
          </div>

          <!-- Reset Filter -->
          <div class="w-full sm:w-auto shrink-0">
            <a href="{{ route('kabag.users.index') }}"
               class="w-full sm:w-auto px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-semibold transition text-center shrink-0 flex items-center justify-center gap-1.5"
               title="Reset Semua Filter">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              <span>Reset</span>
            </a>
          </div>

        </form>

        <!-- ================= STATS SECTION (5 BOXES) ================= -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">

          <!-- Stat 1: Total Pegawai -->
          <div class="bg-[#ECFDF5] border border-[#059669] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#CCFBF1] text-[#059669] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#059669] leading-tight truncate">Total Pegawai</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['total'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- Stat 2: Sarpras -->
          <div class="bg-[#EFF6FF] border border-[#0369A1] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#DBEAFE] text-[#0369A1] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#0369A1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#0369A1] leading-tight truncate">Sarpras</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['sarpras'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- Stat 3: Dekanat -->
          <div class="bg-[#F0FDFA] border border-[#CCFBF1] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#CCFBF1] text-[#15803D] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#15803D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#15803D] leading-tight truncate">Dekanat</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['dekanat'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- Stat 4: Keuangan & SDM -->
          <div class="bg-[#FEF3C7] border border-[#FDE68A] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-[#FDE68A] text-[#D97706] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#78350F] leading-tight truncate">Keuangan &amp; SDM</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['keuangan'] ?? 0 }}
              </div>
            </div>
          </div>

          <!-- Stat 5: Akademik -->
          <div class="bg-[#FAF5FF] border border-[#F3E8FF] rounded-2xl p-4 flex items-center gap-3.5 shadow-xs transition hover:shadow-sm col-span-2 sm:col-span-1">
            <div class="w-11 h-11 rounded-xl bg-[#F3E8FF] text-[#7E22CE] flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-[#7E22CE]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <div class="min-w-0">
              <div class="text-xs font-semibold text-[#7E22CE] leading-tight truncate">Akademik</div>
              <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-0.5 leading-tight">
                {{ $counts['akademik'] ?? 0 }}
              </div>
            </div>
          </div>

        </div>

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
                        <span>{{ $user->subDepartment?->name ?? 'Dekanat' }}</span>
                      </span>
                    </td>

                    <!-- Aksi -->
                    <td class="py-4 px-3.5 text-center">
                      <div class="inline-flex items-center justify-center gap-1.5">

                        <!-- Button Detail -->
                        <button type="button"
                                onclick="openDetailModal({{ json_encode([
                                  'id' => $user->id,
                                  'name' => $user->name,
                                  'email' => $user->email,
                                  'identity_number' => $user->identity_number,
                                  'role' => strtoupper($user->role),
                                  'sub_department' => $user->subDepartment?->name ?? 'Dekanat',
                                  'created_at' => $user->created_at ? $user->created_at->format('d M Y, H:i') : '-',
                                ]) }})"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-[#0D9488] text-[#0D9488] hover:bg-[#0D9488] hover:text-white transition font-bold text-xs shadow-xs">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                          <span>Detail</span>
                        </button>

                        <!-- Titik 3 (Menu Aksi Dropdown) -->
                        <div class="relative inline-block text-left" x-data="{ open: false }">
                          <button @click="open = !open" @click.away="open = false" type="button"
                                  class="w-8 h-8 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 flex items-center justify-center text-slate-600 hover:text-slate-900 transition shadow-xs"
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
                                      'sub_department_id' => $user->sub_department_id,
                                    ]) }})"
                                    class="w-full text-left px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-[#0D9488] font-semibold flex items-center gap-2">
                              <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                              </svg>
                              <span>Edit</span>
                            </button>

                            <!-- Hapus -->
                            <button type="button"
                                    @click="open = false; openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                    class="w-full text-left px-3 py-2 text-rose-600 hover:bg-rose-50 font-semibold flex items-center gap-2">
                              <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                              </svg>
                              <span>Hapus</span>
                            </button>
                          </div>
                        </div>

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
                          @if (request()->hasAny(['search', 'sub_department_id', 'role']))
                            Tidak ada data pegawai yang cocok dengan filter yang Anda tentukan. Silakan klik tombol reset di bawah.
                          @else
                            Saat ini belum ada data akun Kasubag atau Petugas yang terdaftar di dalam sistem.
                          @endif
                        </p>
                        @if (request()->hasAny(['search', 'sub_department_id', 'role']))
                          <a href="{{ route('kabag.users.index') }}"
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

  <!-- ================= MODAL TAMBAH PEGAWAI ================= -->
  <div id="modal-add" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-xl bg-[#CCFBF1] text-[#0A8080] flex items-center justify-center font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
          </div>
          <h3 class="text-base font-bold text-slate-900">Tambah Akun Pegawai Baru</h3>
        </div>
        <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 p-1">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>

      <form action="{{ route('kabag.users.store') }}" method="POST" class="space-y-4 text-xs">
        @csrf
        <div>
          <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap & Gelar</label>
          <input type="text" name="name" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none" placeholder="Contoh: Budi Santoso, S.Kom.">
        </div>

        <div>
          <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Email Resmi UNAIR (@unair.ac.id)</label>
          <input type="email" name="email" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none" placeholder="nama@unair.ac.id">
        </div>

        <div>
          <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">NIP / NIK Pegawai</label>
          <input type="text" name="identity_number" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none" placeholder="19850101...">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Peran (Role)</label>
            <select name="role" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 bg-white focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none cursor-pointer">
              <option value="kasubag">KASUBAG</option>
              <option value="petugas">Petugas</option>
            </select>
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Sub Bagian / Unit</label>
            <select name="sub_department_id" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 bg-white focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none cursor-pointer">
              @foreach ($subDepartments as $sub)
                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div>
          <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Password Default</label>
          <input type="password" name="password" required minlength="6" class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none" placeholder="Minimal 6 karakter">
        </div>

        <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
          <button type="button" onclick="closeAddModal()" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">Batal</button>
          <button type="submit" class="px-5 py-2 bg-[#0a8080] hover:bg-[#086666] text-white rounded-xl font-bold shadow-xs transition">Simpan Pegawai</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ================= MODAL DETAIL PEGAWAI ================= -->
  <div id="modal-detail" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-5">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <h3 class="text-base font-bold text-slate-900">Detail Informasi Pegawai</h3>
        <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 p-1">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>

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
        <button type="button" onclick="closeDetailModal()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
          Tutup
        </button>
      </div>
    </div>
  </div>

  <!-- ================= MODAL EDIT PEGAWAI ================= -->
  <div id="modal-edit" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-xl bg-[#FEF3C7] text-[#D97706] flex items-center justify-center font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
          </div>
          <h3 class="text-base font-bold text-slate-900">Edit Data Pegawai</h3>
        </div>
        <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>

      <form id="form-edit" method="POST" class="space-y-4 text-xs">
        @csrf
        @method('PUT')
        <div>
          <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap & Gelar</label>
          <input type="text" id="edit-name" name="name" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none">
        </div>

        <div>
          <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Email Resmi UNAIR (@unair.ac.id)</label>
          <input type="email" id="edit-email" name="email" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none">
        </div>

        <div>
          <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">NIP / NIK Pegawai</label>
          <input type="text" id="edit-nip" name="identity_number" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Peran (Role)</label>
            <select id="edit-role" name="role" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 bg-white focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none cursor-pointer">
              <option value="kasubag">KASUBAG</option>
              <option value="petugas">Petugas</option>
            </select>
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Sub Bagian / Unit</label>
            <select id="edit-subdept" name="sub_department_id" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 bg-white focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none cursor-pointer">
              @foreach ($subDepartments as $sub)
                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div>
          <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
            Ganti Password <span class="text-slate-400 font-normal lowercase">(kosongkan jika tidak diubah)</span>
          </label>
          <input type="password" name="password" minlength="6" class="w-full text-xs sm:text-sm rounded-xl border border-slate-200 px-3 py-2.5 focus:border-[#0a8080] focus:ring-2 focus:ring-[#0a8080]/20 focus:outline-none" placeholder="Minimal 6 karakter baru">
        </div>

        <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">Batal</button>
          <button type="submit" class="px-5 py-2 bg-[#0a8080] hover:bg-[#086666] text-white rounded-xl font-bold shadow-xs transition">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ================= MODAL HAPUS PEGAWAI ================= -->
  <div id="modal-delete" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
      <div class="w-12 h-12 rounded-2xl bg-[#FFF1F2] text-[#F43F5E] flex items-center justify-center mx-auto border border-[#FFE4E6]">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
      </div>
      <div class="text-center space-y-1.5">
        <h3 class="text-base font-bold text-slate-900">Hapus Akun Pegawai</h3>
        <p class="text-xs text-slate-500 leading-relaxed">
          Apakah Anda yakin ingin menghapus akun pegawai <strong id="delete-user-name" class="text-slate-800"></strong>? Tindakan ini akan menghapus akses login pegawai tersebut.
        </p>
      </div>

      <form id="form-delete" method="POST" class="pt-2 flex items-center justify-center gap-2.5 text-xs">
        @csrf
        @method('DELETE')
        <button type="button" onclick="closeDeleteModal()" class="w-1/2 py-2.5 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">
          Batal
        </button>
        <button type="submit" class="w-1/2 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold shadow-xs transition">
          Ya, Hapus Pegawai
        </button>
      </form>
    </div>
  </div>

  <!-- JavaScript Modal Handlers -->
  <script>
    function openAddModal() {
      document.getElementById('modal-add').classList.remove('hidden');
    }
    function closeAddModal() {
      document.getElementById('modal-add').classList.add('hidden');
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
      document.getElementById('form-edit').action = "/kabag/users/" + data.id;
      document.getElementById('edit-name').value = data.name;
      document.getElementById('edit-email').value = data.email;
      document.getElementById('edit-nip').value = data.identity_number;
      document.getElementById('edit-role').value = data.role;
      document.getElementById('edit-subdept').value = data.sub_department_id;
      document.getElementById('modal-edit').classList.remove('hidden');
    }
    function closeEditModal() {
      document.getElementById('modal-edit').classList.add('hidden');
    }

    function openDeleteModal(id, name) {
      document.getElementById('form-delete').action = "/kabag/users/" + id;
      document.getElementById('delete-user-name').textContent = name;
      document.getElementById('modal-delete').classList.remove('hidden');
    }
    function closeDeleteModal() {
      document.getElementById('modal-delete').classList.add('hidden');
    }
  </script>

</body>
</html>
