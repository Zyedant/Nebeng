@extends('superadmin.layouts.app')

@section('content')
@php
    $rows = $rows ?? collect();

    $isPaginator = $rows instanceof \Illuminate\Pagination\LengthAwarePaginator
                || $rows instanceof \Illuminate\Pagination\Paginator;

    $isEmpty = $isPaginator
        ? ($rows->total() === 0)
        : (method_exists($rows, 'isEmpty') ? $rows->isEmpty() : empty($rows));

    $q = request('q');
    $statusFilter = request('status');
    $date = request('date');

    $badge = function($verified_status, $is_banned = 0) {
        if ((int)$is_banned === 1) return ['text' => 'DIBLOK', 'class' => 'bg-slate-800 text-white'];

        $s = strtolower(trim((string) $verified_status));

        if (in_array($s, ['verified','approved','accepted','terverifikasi','1','true'], true)) {
            return ['text' => 'TERVERIFIKASI', 'class' => 'bg-emerald-500 text-white'];
        }
        if (in_array($s, ['rejected','declined','ditolak','0','false'], true)) {
            return ['text' => 'DITOLAK', 'class' => 'bg-red-500 text-white'];
        }
        return ['text' => 'PENGAJUAN', 'class' => 'bg-amber-400 text-white'];
    };
@endphp

<div class="space-y-6 font-['Urbanist']">
    <div class="text-[20px] md:text-[22px] font-semibold text-slate-900">
        Mitra
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">

        {{-- HEADER --}}
        <div class="flex items-center justify-between gap-4">
            <div class="text-[16px] font-semibold text-slate-900">Daftar Mitra</div>

            <div class="flex items-center gap-3 relative z-50">

                {{-- SEARCH --}}
                <form method="GET" class="relative w-[260px]">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.5 18a7.5 7.5 0 1 1 0-15a7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>

                    <input name="q" value="{{ $q }}" type="text" placeholder="Search"
                           class="w-full h-9 bg-white rounded-lg pl-9 pr-3 text-sm border border-slate-200
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300" />

                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                </form>

                {{-- STATUS --}}
                <form method="GET">
                    <input type="hidden" name="q" value="{{ $q }}">
                    <input type="hidden" name="date" value="{{ $date }}">

                    <select name="status" onchange="this.form.submit()"
                        class="h-9 px-3 rounded-lg border border-slate-200 bg-white text-slate-700 text-[13px]
                               hover:bg-slate-50 transition">
                        <option value="" @selected(empty($statusFilter))>Semua</option>
                        <option value="pengajuan" @selected($statusFilter==='pengajuan')>Pengajuan</option>
                        <option value="terverifikasi" @selected($statusFilter==='terverifikasi')>Terverifikasi</option>
                        <option value="ditolak" @selected($statusFilter==='ditolak')>Ditolak</option>
                        <option value="diblok" @selected($statusFilter==='diblok')>Diblok</option>
                    </select>
                </form>

                {{-- CALENDAR --}}
                <input id="calendarInput" type="date" class="hidden" value="{{ $date }}"/>

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
                <a href="{{ route('sa.mitra.download', request()->query()) }}"
                   class="h-9 px-4 rounded-lg bg-emerald-500 text-white text-[13px]
                          flex items-center gap-2 hover:bg-emerald-600 transition">
                    Download
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M12 3v10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 11l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 21h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- TABLE (✅ samakan feel dengan Customer) --}}
<div class="mt-4 rounded-xl border border-slate-100 overflow-hidden">
    {{-- penting: jangan hidden X, biar aksi gak kepotong --}}
    <div class="max-h-[420px] overflow-y-auto overflow-x-auto">
        <table class="w-full table-fixed text-[11px] min-w-[980px]">
            <thead class="bg-[#EEF5FF] text-slate-600 font-semibold sticky top-0 z-10">
                <tr>
                    <th class="text-left px-2 py-2 w-[90px]">NO. ID</th>
                    <th class="text-left px-2 py-2 w-[170px]">NAMA</th>
                    <th class="text-left px-2 py-2 w-[240px]">EMAIL</th>

                    {{-- ✅ diperkecil --}}
                    <th class="text-left px-2 py-2 w-[120px]">NO. TLP</th>

                    {{-- ✅ diperkecil --}}
                    <th class="text-left px-2 py-2 w-[130px]">LAYANAN</th>

                    <th class="text-left px-2 py-2 w-[140px]">STATUS</th>

                    {{-- ✅ sedikit dibesar-in biar tombol lega --}}
                    <th class="text-center px-2 py-2 w-[80px]">AKSI</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">
                @if($isEmpty)
                    @for($i=1;$i<=10;$i++)
                        <tr class="text-slate-300">
                            <td class="px-2 py-[8px]">—</td>
                            <td class="px-2 py-[8px]">—</td>
                            <td class="px-2 py-[8px]">—</td>
                            <td class="px-2 py-[8px]">—</td>
                            <td class="px-2 py-[8px]">—</td>
                            <td class="px-2 py-[8px]">—</td>
                            <td class="px-2 py-[8px] text-center">
                                <div class="w-[28px] h-[28px] rounded-[8px] bg-slate-200/60 inline-block"></div>
                            </td>
                        </tr>
                    @endfor
                @else
                    @foreach($rows as $m)
                        @php
                            $b = $badge($m->verified_status ?? ($m->status ?? null), $m->is_banned ?? 0);
                        @endphp
                        <tr class="text-slate-700">
                            <td class="px-2 py-[8px] whitespace-nowrap truncate">{{ $m->id ?? '—' }}</td>
                            <td class="px-2 py-[8px] truncate">{{ $m->name ?? '—' }}</td>
                            <td class="px-2 py-[8px] truncate">{{ $m->email ?? '—' }}</td>

                            {{-- ✅ no tlp dipadatkan --}}
                            <td class="px-2 py-[8px] whitespace-nowrap truncate">{{ $m->phone_number ?? '—' }}</td>

                            {{-- ✅ layanan dipadatkan --}}
                            <td class="px-2 py-[8px] truncate">{{ $m->layanan ?? '—' }}</td>

                            <td class="px-2 py-[8px]">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold {{ $b['class'] }}">
                                    {{ $b['text'] }}
                                </span>
                            </td>

                            <td class="px-2 py-[8px] text-center">
                                <a href="{{ route('sa.mitra.detail', ['id' => $m->id]) }}"
                                   class="w-[30px] h-[30px] rounded-[8px] bg-[#0B3A82] text-white
                                          inline-flex items-center justify-center hover:opacity-90 transition"
                                   title="Detail">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"
                                              stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"
                                              stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

        {{-- FOOTER + PAGINATION --}}
        <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-[12px] text-slate-400">
            <div>
                @if($isPaginator)
                    {{ $rows->firstItem() ?? 0 }}–{{ $rows->lastItem() ?? 0 }} dari {{ $rows->total() }} entries
                @else
                    {{ $isEmpty ? '0' : $rows->count() }} entries
                @endif
            </div>

            @if($isPaginator)
                <div class="text-slate-600">
                    {{ $rows->onEachSide(1)->links() }}
                </div>
            @endif
        </div>

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