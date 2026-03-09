@extends('superadmin.layouts.app')

@section('content')
<div class="space-y-6 font-['Urbanist']">

    <div class="text-[20px] md:text-[22px] font-semibold text-slate-900">
        Laporan
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">

        {{-- HEADER --}}
        <div class="flex items-center justify-between gap-4">
            <div class="text-[24px] font-semibold text-slate-900">Daftar Laporan</div>

            <div class="flex items-center gap-3 relative z-50">
                {{-- SEARCH --}}
                <form method="GET" class="relative w-[260px]">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.5 18a7.5 7.5 0 1 1 0-15a7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>

                    <input name="q" value="{{ request('q') }}" type="text" placeholder="Search"
                           class="w-full h-9 bg-white rounded-lg pl-9 pr-3 text-sm border border-slate-200
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300" />

                    <input type="hidden" name="date" value="{{ request('date') }}">
                </form>

                {{-- CALENDAR --}}
                <input id="calendarInput" type="date" class="hidden" value="{{ request('date') }}"/>

                <button id="btnCalendar" type="button"
                        class="h-9 px-4 rounded-lg border border-slate-200 bg-white text-slate-700 text-[13px]
                               flex items-center gap-2 hover:bg-slate-50 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M7 3v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M17 3v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 8h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M6 5h12a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"
                              stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                    Kalender
                </button>

                {{-- DOWNLOAD --}}
                <a href="{{ route('sa.laporan.download', request()->query()) }}"
                   class="h-9 px-4 rounded-lg bg-emerald-500 text-white text-[13px]
                          flex items-center gap-2 hover:bg-emerald-600 transition">
                    Download
                </a>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="mt-4 rounded-xl border border-slate-100 overflow-hidden">
            <div class="max-h-[420px] overflow-y-auto overflow-x-hidden">
                <table class="w-full table-fixed text-[11px]">
                    <thead class="bg-[#EEF5FF] text-slate-600 font-semibold sticky top-0 z-10">
                        <tr>
                            <th class="text-left px-2 py-2 w-[110px]">NO. ORDER</th>
                            <th class="text-left px-2 py-2 w-[220px]">PELAPOR → TERLAPOR</th>
                            <th class="text-left px-2 py-2 w-[140px]">TANGGAL</th>
                            <th class="text-left px-2 py-2 w-[130px]">LAYANAN</th>
                            <th class="text-left px-2 py-2 w-[240px]">LAPORAN</th>
                            <th class="text-center px-2 py-2 w-[80px]">AKSI</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
                    @forelse(($rows ?? []) as $row)
                        @php
                            $tglVal  = $row->tanggal_tampil ?? $row->report_created_at ?? null;
                            $tglText = $tglVal ? \Carbon\Carbon::parse($tglVal)->format('d/m/Y H:i') : '—';

                            $pelapor  = trim(($row->reporter_name ?? '—') . ' (' . strtoupper($row->reporter_role ?? '-') . ')');
                            $terlapor = trim(($row->reported_name ?? '—') . ' (' . strtoupper($row->reported_role ?? '-') . ')');

                            $laporanText = $row->reason ?: ($row->description ?: '—');
                        @endphp

                        <tr>
                            <td class="px-2 py-[8px] truncate">{{ $row->order_no ?? '—' }}</td>

                            <td class="px-2 py-[8px] truncate">
                                {{ $pelapor }} → {{ $terlapor }}
                            </td>

                            <td class="px-2 py-[8px] whitespace-nowrap">{{ $tglText }}</td>

                            <td class="px-2 py-[8px] truncate">{{ $row->layanan ?? '—' }}</td>

                            <td class="px-2 py-[8px]">
                                <div class="line-clamp-2 text-slate-600">
                                    {{ \Illuminate\Support\Str::limit($laporanText, 120) }}
                                </div>
                            </td>
                            <td class="px-2 py-[8px] text-center">
                               <a href="{{ route('sa.laporan.detail', ['id' => $row->report_id]) }}"
                                class="w-[30px] h-[30px] rounded-[8px] bg-[#0B3A82] text-white
                                        inline-flex items-center justify-center hover:opacity-90 transition"
                                title="Detail Laporan">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"
                                            stroke="currentColor" stroke-width="2"/>
                                        <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"
                                            stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="text-slate-300">
                            <td colspan="6" class="px-2 py-8 text-center">
                                Belum ada data laporan.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="mt-4 text-[12px] text-slate-400">
            {{ $rows instanceof \Illuminate\Pagination\LengthAwarePaginator ? $rows->total() : (is_countable($rows) ? count($rows) : 0) }} entries
        </div>

        {{-- PAGINATION --}}
        @if($rows instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-4">
                {{ $rows->links() }}
            </div>
        @endif

        <script>
            const calendarBtn = document.getElementById('btnCalendar');
            const calendarInput = document.getElementById('calendarInput');

            if (calendarBtn && calendarInput) {
                calendarBtn.addEventListener('click', () => {
                    if (calendarInput.showPicker) calendarInput.showPicker();
                    else calendarInput.click();
                });

                calendarInput.addEventListener('change', () => {
                    const url = new URL(window.location.href);
                    if (calendarInput.value) url.searchParams.set('date', calendarInput.value);
                    else url.searchParams.delete('date');
                    window.location.href = url.toString();
                });
            }
        </script>

    </div>
</div>
@endsection
