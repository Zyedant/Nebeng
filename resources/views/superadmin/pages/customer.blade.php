@extends('superadmin.layouts.app')

@section('content')
@php
    $rows = $rows ?? collect();
    $q = request('q');
    $status = request('status');
    $date = request('date');

    $badge = function($verified_status, $is_banned = 0) {
        if ((int)$is_banned === 1) {
            return ['text' => 'DIBLOK', 'class' => 'bg-slate-800 text-white'];
        }

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
        Customer
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">

        {{-- HEADER --}}
        <div class="flex items-center justify-between gap-4">
            <div class="text-[16px] font-semibold text-slate-900">Daftar Customer</div>

            <div class="flex items-center gap-3">
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
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                </form>

                {{-- STATUS --}}
                <form method="GET">
                    <input type="hidden" name="q" value="{{ $q }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <select name="status" onchange="this.form.submit()"
                        class="h-9 px-3 rounded-lg border border-slate-200 bg-white text-slate-700 text-[13px]
                               hover:bg-slate-50 transition">
                        <option value="" @selected(empty($status))>Semua</option>
                        <option value="pengajuan" @selected($status==='pengajuan')>Pengajuan</option>
                        <option value="terverifikasi" @selected($status==='terverifikasi')>Terverifikasi</option>
                        <option value="ditolak" @selected($status==='ditolak')>Ditolak</option>
                        <option value="diblok" @selected($status==='diblok')>Diblok</option>
                    </select>
                </form>

                <input id="calendarInput" type="date" class="hidden" value="{{ $date }}"/>

                {{-- CALENDAR --}}
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
                <a href="{{ route('sa.customer.download', request()->query()) }}"
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
                            <th class="text-left px-2 py-2 w-[100px]">NO ID</th>
                            <th class="text-left px-2 py-2 w-[170px]">NAMA</th>
                            <th class="text-left px-2 py-2 w-[170px]">EMAIL</th>
                            <th class="text-left px-2 py-2 w-[150px]">NO. TLP</th>
                            <th class="text-left px-2 py-2 w-[110px]">TGL DAFTAR</th>
                            <th class="text-left px-2 py-2 w-[95px]">STATUS</th>
                            <th class="text-left px-2 py-2 w-[70px]">AKSI</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
                        @forelse($rows as $i => $c)
                            @php
                                $b = $badge($c->verified_status ?? null, $c->is_banned ?? 0);
                                $tglVal = $c->created_at ?? null;
                                $tglText = $tglVal ? \Carbon\Carbon::parse($tglVal)->format('d/m/Y') : '—';
                            @endphp

                            <tr>
                                <td class="px-2 py-[6px] truncate">{{ $c->id ?? '—' }}</td>
                                <td class="px-2 py-[6px] truncate">{{ $c->name ?? '—' }}</td>
                                <td class="px-2 py-[6px] truncate">{{ $c->email ?? '—' }}</td>
                                <td class="px-2 py-[6px] whitespace-nowrap">{{ $c->phone_number ?? '—' }}</td>
                                <td class="px-2 py-[6px] truncate">{{ $tglText }}</td>

                                <td class="px-2 py-[6px]">
                                    <span class="inline-flex items-center px-2 py-[2px] rounded-full text-[10px] font-bold {{ $b['class'] }}">
                                        {{ $b['text'] }}
                                    </span>
                                </td>

                                <td class="px-2 py-[6px]">
                                    <a href="{{ route('sa.customer.detail', ['id' => $c->id]) }}"
                                       class="w-[30px] h-[30px] rounded-[8px] bg-[#0B3A82] text-white inline-flex items-center justify-center hover:opacity-90 transition"
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
                        @empty
                            <tr class="text-slate-300">
                                <td colspan="7" class="px-2 py-6 text-center">
                                    Belum ada data customer.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 text-[12px] text-slate-400">
            {{ $rows instanceof \Illuminate\Pagination\LengthAwarePaginator ? $rows->total() : $rows->count() }} entries
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