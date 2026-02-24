@extends('superadmin.layouts.app')

@section('content')
@php
    // $rows bisa Collection atau array
    $rows = $rows ?? [];
    $q = request('q', $q ?? '');

    $isCollection = $rows instanceof \Illuminate\Support\Collection;
    $isEmpty = $isCollection ? $rows->isEmpty() : empty($rows);
    $totalRows = $isCollection ? $rows->count() : count($rows);
@endphp

<div class="px-4 md:px-6 py-6 font-['Urbanist']">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-[22px] font-semibold text-slate-800">Mitra</h1>
        </div>

        {{-- Search (GET q) --}}
        <form method="GET" class="flex items-center gap-3">
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10.5 18a7.5 7.5 0 1 1 0-15a7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </span>
                <input
                    name="q"
                    value="{{ $q }}"
                    type="text"
                    placeholder="Search"
                    class="w-[260px] h-9 rounded-lg border border-slate-200 bg-white pl-10 pr-4 text-sm
                           outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"
                >
            </div>
            <button type="submit" class="hidden" aria-hidden="true"></button>
        </form>
    </div>

    {{-- CARD UTAMA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">

        {{-- TITLE + ACTIONS --}}
        <div class="flex items-center justify-between gap-3 mb-3">
            <div>
                <h2 class="text-[14px] font-semibold text-slate-800">Kendaraan Mitra</h2>
            </div>

            <div class="flex items-center gap-3 relative z-50">
                {{-- SEARCH --}}
                <form method="GET" class="relative w-[260px]">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.5 18a7.5 7.5 0 1 1 0-15a7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>

                    <input
                        name="q"
                        value="{{ $q }}"
                        type="text"
                        placeholder="Search"
                        class="w-full h-9 bg-white rounded-lg pl-9 pr-3 text-sm border border-slate-200
                               focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"
                    />
                </form>

                {{-- CALENDAR --}}
                <input id="calendarInput" type="date" class="hidden" value="{{ request('date') }}"/>

                <button id="btnCalendar" type="button"
                    class="h-9 px-4 rounded-lg border border-slate-200 bg-white text-slate-700 text-[13px]
                           flex items-center gap-2 hover:bg-slate-50 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M8 7V3m8 4V3M4 11h16M6 5h12a2 2 0 012 2v13a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Kalender
                </button>

                {{-- DOWNLOAD --}}
                <a href="{{ route('sa.mitra.kendaraan.download', request()->query()) }}"
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

        {{-- TABLE --}}
        <div class="mt-1 rounded-xl border border-slate-100 overflow-hidden">
            <div class="max-h-[420px] overflow-y-auto overflow-x-hidden scrollbar-hide">
                <table class="w-full table-fixed text-[11px]">
                    <thead class="bg-[#EEF5FF] text-slate-600 font-semibold sticky top-0 z-10">
                        <tr>
                            <th class="text-left px-2 py-2 w-[60px]">NO</th>
                            <th class="text-left px-2 py-2 w-[300px]">NAMA</th>
                            <th class="text-left px-2 py-2 w-[90px]">KEND</th>
                            <th class="text-left px-2 py-2 w-[140px]">MERK</th>
                            <th class="text-left px-2 py-2 w-[130px]">PLAT</th>
                            <th class="text-left px-2 py-2 w-[120px]">WARNA</th>
                            <th class="text-center px-2 py-2 w-[90px]">AKSI</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
                        @if($isEmpty)
                            @for($i=1;$i<=8;$i++)
                                <tr class="text-slate-300">
                                    <td class="px-2 py-[6px]">—</td>
                                    <td class="px-2 py-[6px]">—</td>
                                    <td class="px-2 py-[6px]">—</td>
                                    <td class="px-2 py-[6px]">—</td>
                                    <td class="px-2 py-[6px]">—</td>
                                    <td class="px-2 py-[6px]">—</td>
                                    <td class="px-2 py-[6px] text-center">—</td>
                                </tr>
                            @endfor
                        @else
                            @foreach($rows as $idx => $r)
                                @php
                                    // aman untuk array/collection
                                    $img = $r['img'] ?? null;
                                    $nama = $r['nama'] ?? '-';
                                    $initial = strtoupper(mb_substr($nama, 0, 1));
                                    $isHttp = $img && str_starts_with($img, 'http');
                                    $vehicleId = $r['vehicle_id'] ?? null;
                                @endphp

                                <tr class="align-middle">
                                    {{-- NO --}}
                                    <td class="px-2 py-[6px] truncate">{{ $idx + 1 }}</td>

                                    {{-- NAMA --}}
                                    <td class="px-2 py-[6px]">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center shrink-0">
                                                @if($img && $isHttp)
                                                    <img src="{{ $img }}" class="w-full h-full object-cover" alt="img">
                                                @elseif($img && !$isHttp)
                                                    <img src="{{ $img }}" class="w-full h-full object-cover" alt="img"
                                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=&quot;text-[10px] font-semibold text-slate-500&quot;>{{ $initial }}</span>';"
                                                    >
                                                @else
                                                    <span class="text-[10px] font-semibold text-slate-500">{{ $initial }}</span>
                                                @endif
                                            </div>

                                            <div class="min-w-0">
                                                <div class="truncate font-medium text-slate-800">{{ $nama }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- KEND --}}
                                    <td class="px-2 py-[6px] truncate">{{ $r['kendaraan'] ?? '-' }}</td>

                                    {{-- MERK --}}
                                    <td class="px-2 py-[6px] truncate">{{ $r['merk'] ?? '-' }}</td>

                                    {{-- PLAT --}}
                                    <td class="px-2 py-[6px] whitespace-nowrap">{{ $r['plat'] ?? '-' }}</td>

                                    {{-- WARNA --}}
                                    <td class="px-2 py-[6px] truncate">{{ $r['warna'] ?? '-' }}</td>

                                    {{-- AKSI --}}
                                    <td class="px-2 py-[6px]">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- DETAIL --}}
                                            <a href="{{ $vehicleId ? route('sa.mitra.kendaraan.detail', ['id' => $vehicleId]) : '#' }}"
                                               class="w-[28px] h-[28px] rounded-[8px] bg-[#0B3A82] hover:opacity-95 transition text-white inline-flex items-center justify-center"
                                               title="Detail">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"
                                                          stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                    <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"
                                                          stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                            </a>

                                            {{-- EDIT --}}
                                            <a href="{{ $vehicleId ? route('sa.mitra.kendaraan.detail', ['id' => $vehicleId, 'edit' => 1]) : '#' }}"
                                               class="w-[28px] h-[28px] rounded-[8px] bg-amber-500 hover:opacity-95 transition text-white inline-flex items-center justify-center"
                                               title="Edit">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                    <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z"
                                                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 text-[12px] text-slate-400">
            {{ $isEmpty ? '0' : $totalRows }} entries
        </div>

    </div>
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
@endsection