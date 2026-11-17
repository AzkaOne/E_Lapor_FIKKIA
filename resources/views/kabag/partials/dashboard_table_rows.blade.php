@forelse ($reports as $index => $report)
  <tr class="hover:bg-slate-50/70 transition">
    <td class="py-3.5 px-3 text-center font-bold text-slate-700">
      {{ $reports->firstItem() ? $reports->firstItem() + $index : $index + 1 }}
    </td>
    <td class="py-3.5 px-3">
      <div class="font-bold text-slate-800 text-[13px] leading-snug line-clamp-1">{{ $report->title }}</div>
      <div class="text-[10.5px] text-slate-400 mt-0.5">Tiket: {{ $report->ticket_code }} • Kampus {{ $report->campus_location }}</div>
    </td>
    <td class="py-3.5 px-3">
      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#EFF6FF] text-[#1D4ED8] border border-[#DBEAFE]">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        <span>{{ $report->subDepartment?->name ? (str_contains($report->subDepartment->name, 'Sarana') ? 'Sarpras' : (str_contains($report->subDepartment->name, 'Pendidikan') ? 'Akademik' : (str_contains($report->subDepartment->name, 'SDM') ? 'Keuangan & SDM' : 'Dekanat'))) : 'Dekanat' }}</span>
      </span>
    </td>
    <td class="py-3.5 px-3 text-slate-600">
      <div class="font-medium text-[11.5px]">{{ $report->created_at->format('d M Y') }}</div>
      <div class="text-[10px] text-slate-400">{{ $report->created_at->format('H:i') }}</div>
    </td>
    <td class="py-3.5 px-3">
      @if ($report->status === 'diajukan')
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]">
          <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
          <span>Diajukan</span>
        </span>
      @elseif (in_array($report->status, ['proses', 'didisposisi', 'diterima']))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EFF6FF] text-[#2563EB] border border-[#BFDBFE]">
          <span class="w-1.5 h-1.5 rounded-full bg-[#3B82F6]"></span>
          <span>Dalam Proses</span>
        </span>
      @elseif ($report->status === 'verifikasi_kabag')
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#F5F3FF] text-[#7C3AED] border border-[#DDD6FE]">
          <span class="w-1.5 h-1.5 rounded-full bg-[#8B5CF6]"></span>
          <span>Verifikasi</span>
        </span>
      @elseif ($report->status === 'selesai')
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0]">
          <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
          <span>Selesai</span>
        </span>
      @else
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
          <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
          <span>Ditolak</span>
        </span>
      @endif
    </td>
    <td class="py-3.5 px-3 text-right">
      <a href="{{ route('kabag.show', $report) }}"
         class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-[#0D9488] text-[#0D9488] hover:bg-[#0D9488] hover:text-white transition font-bold text-xs shadow-xs">
        <span>Lihat Detail</span>
      </a>
    </td>
  </tr>
@empty
  <tr>
    <td colspan="7" class="py-14 px-4 text-center">
      <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
        <div class="w-14 h-14 rounded-2xl bg-[#CCFBF1]/60 border border-[#99F6E4] flex items-center justify-center text-[#0a8080] mb-3 shadow-xs">
          <svg class="w-7 h-7 text-[#0a8080]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <h4 class="text-sm font-bold text-slate-800">Tidak ada data</h4>
        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
          @if (request()->hasAny(['search', 'sub_department_id', 'status', 'date', 'start_date', 'end_date']))
            Tidak ada data laporan yang cocok dengan filter yang Anda tentukan. Silakan ubah filter atau klik tombol reset di bawah.
          @else
            Saat ini belum ada data laporan pelayanan yang masuk ke dalam sistem.
          @endif
        </p>
        @if (request()->hasAny(['search', 'sub_department_id', 'status', 'date', 'start_date', 'end_date']))
          <a href="{{ route('kabag.laporan-pelayanan') }}"
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
