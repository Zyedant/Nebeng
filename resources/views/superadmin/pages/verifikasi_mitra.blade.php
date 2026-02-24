@extends('superadmin.layouts.app')

@section('content')
@php
    $rows = $rows ?? collect();
    $isEmpty = $rows->isEmpty();

    $q = request('q');
    $date = request('date');

    // Badge status (feel UI)
    $badge = function($status, $is_banned = 0) {
        if ((int)$is_banned === 1) {
            return ['text' => 'DIBLOK', 'class' => 'bg-slate-800 text-white'];
        }

        $s = strtolower(trim((string) $status));

        if (in_array($s, ['verified','approved','accepted','terverifikasi','1','true'], true)) {
            return ['text' => 'TERVERIFIKASI', 'class' => 'bg-emerald-100 text-emerald-700'];
        }
        if (in_array($s, ['rejected','declined','ditolak','0','false'], true)) {
            return ['text' => 'DITOLAK', 'class' => 'bg-red-100 text-red-700'];
        }

        return ['text' => 'PENGAJUAN', 'class' => 'bg-amber-400 text-white'];
    };
@endphp

<div class="space-y-6 font-['Urbanist']">

    <div class="text-[20px] md:text-[22px] font-semibold text-slate-900">
        Verifikasi Mitra
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">

        {{-- HEADER: title + actions (search, calendar, download) --}}
        <div class="flex items-center justify-between gap-4">
            <div class="text-[16px] font-semibold text-slate-900">Data Mitra</div>

            <div class="flex items-center gap-3 relative z-50">
                {{-- SEARCH (GET) --}}
                <form method="GET" action="{{ route('sa.verifikasi.mitra') }}" class="relative w-[260px]">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.5 18a7.5 7.5 0 1 1 0-15a7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>

                    <input name="q" value="{{ $q }}" type="text" placeholder="Search"
                           class="w-full h-9 bg-white rounded-lg pl-9 pr-3 text-sm border border-slate-200
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300">

                    {{-- bawa date --}}
                    <input type="hidden" name="date" value="{{ $date }}">
                </form>

                {{-- hidden date input untuk showPicker --}}
                <input id="calendarInput" type="date" class="hidden" value="{{ $date }}"/>

                {{-- KALENDER --}}
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

                {{-- DOWNLOAD (ikut query) --}}
                <a id="btnDownload"
                   href="{{ route('sa.verifikasi.mitra.download', request()->query()) }}"
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
<div class="mt-4 rounded-xl border border-slate-100 overflow-hidden">
    <div class="max-h-[420px] overflow-y-auto overflow-x-hidden">
        <table class="w-full table-fixed text-[11px]">
            <thead class="bg-[#EEF5FF] text-slate-600 font-semibold sticky top-0 z-10">
                <tr>
                    <th class="text-left px-2 py-2 w-[80px]">NO. ID</th>
                    <th class="text-left px-2 py-2 w-[180px]">NAMA</th>
                    <th class="text-left px-2 py-2 w-[220px]">EMAIL</th>
                    <th class="text-left px-2 py-2 w-[140px]">NO. TLP</th>
                    <th class="text-left px-2 py-2 w-[140px]">LAYANAN</th>
                    <th class="text-left px-2 py-2 w-[120px]">STATUS</th>
                    <th class="text-center px-2 py-2 w-[118px]">AKSI</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">
                @if($isEmpty)
                    @for($i=1;$i<=8;$i++)
                        <tr class="text-slate-300">
                            <td class="px-2 py-[6px]">—</td>
                            <td class="px-2 py-[6px]">—</td>
                            <td class="px-2 py-[6px]">—</td>
                            <td class="px-2 py-[6px]">—</td>
                            <td class="px-2 py-[6px]">—</td>
                            <td class="px-2 py-[6px]">—</td>
                            <td class="px-2 py-[6px]">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-[28px] h-[28px] rounded-[8px] bg-slate-200/60"></div>
                                    <div class="w-[28px] h-[28px] rounded-[8px] bg-rose-200/60"></div>
                                </div>
                            </td>
                        </tr>
                    @endfor
                @else
                    @foreach($rows as $m)
                        @php
                            $b = $badge($m->verified_status ?? null, $m->is_banned ?? 0);
                        @endphp

                        <tr class="text-slate-700">
                            <td class="px-2 py-[6px] whitespace-nowrap">{{ $m->id ?? '—' }}</td>

                            <td class="px-2 py-[6px]">
                                <div class="truncate">{{ $m->name ?? '—' }}</div>
                            </td>

                            <td class="px-2 py-[6px]">
                                <div class="truncate">{{ $m->email ?? '—' }}</div>
                            </td>

                            <td class="px-2 py-[6px] whitespace-nowrap">{{ $m->phone_number ?? '—' }}</td>

                            <td class="px-2 py-[6px]">
                                <div class="truncate">{{ $m->layanan ?? '—' }}</div>
                            </td>

                            <td class="px-2 py-[6px]">
                                <span class="inline-flex items-center px-2 py-[2px] rounded-full text-[10px] font-bold {{ $b['class'] }}">
                                    {{ $b['text'] }}
                                </span>
                            </td>

                            <td class="px-2 py-[6px]">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- DETAIL --}}
                                    <a href="{{ route('sa.verifikasi.mitra.detail', ['id' => $m->id]) }}"
                                       class="w-[28px] h-[28px] rounded-[8px] bg-[#0B3A82] text-white
                                              flex items-center justify-center hover:opacity-90 transition"
                                       title="Detail">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"
                                                  stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"
                                                  stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                    </a>

                                    {{-- BLOCK/UNBLOCK --}}
                                    <button type="button"
                                            class="w-[28px] h-[28px] rounded-[8px] bg-red-500 text-white
                                                   flex items-center justify-center hover:opacity-90 transition"
                                            title="Block/Unblock"
                                            onclick="openBlockConfirm('{{ route('sa.verifikasi.mitra.block', ['id' => $m->id]) }}')">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"
                                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M6 11h12v10H6V11Z"
                                                  stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            <path d="M12 15v2"
                                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </button>
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
    {{ $isEmpty ? '0' : $rows->count() }} entries
</div>

        <div class="mt-4 text-[12px] text-slate-400">
            {{ $isEmpty ? '0' : $rows->count() }} entries
        </div>

    </div>
</div>

{{-- ===================== MODAL: KONFIRMASI BLOCK ===================== --}}
<div id="modalBlockConfirm" class="fixed inset-0 z-[9999] hidden">
    <div id="blockConfirmBackdrop" class="absolute inset-0 bg-[#5B5E88]/80"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-3xl bg-white rounded-xl shadow-xl px-6 py-10">
            <h2 class="text-center text-2xl md:text-3xl font-extrabold text-slate-800 leading-snug">
                Apakah Anda Yakin Ingin<br>Memblock Akun Ini
            </h2>

            <div class="flex justify-center mt-8">
                <svg width="90" height="90" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="#FF0000" stroke-width="2.5" stroke-linecap="round"/>
                    <rect x="5.5" y="11" width="13" height="10" rx="2.2" stroke="#FF0000" stroke-width="2.5"/>
                    <circle cx="12" cy="16" r="1.3" fill="#FF0000"/>
                    <path d="M12 17.3V19" stroke="#FF0000" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="flex justify-center gap-4 mt-10">
                <button type="button" onclick="closeBlockConfirm()"
                        class="px-8 py-2.5 rounded-md bg-gray-500 text-white font-medium hover:bg-gray-600">
                    Kembali
                </button>

                <button type="button" onclick="submitBlockAction()"
                        class="px-10 py-2.5 rounded-md bg-red-600 text-white font-medium hover:bg-red-700">
                    Yakin
                </button>
            </div>
        </div>
    </div>
</div>

<form id="blockActionForm" method="POST" class="hidden">
    @csrf
</form>

{{-- ===================== MODAL: SUKSES BLOCK ===================== --}}
<div id="modalBlockSuccess" class="fixed inset-0 z-[9999] hidden">
    <div id="blockSuccessBackdrop" class="absolute inset-0 bg-[#5B5E88]/80"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-3xl bg-white rounded-xl shadow-xl px-6 py-12">
            <h2 class="text-center text-2xl md:text-3xl font-extrabold text-slate-800">
                Block Akun Berhasil
            </h2>

            <div class="flex justify-center mt-10">
                <svg width="140" height="110" viewBox="0 0 140 110" fill="none" aria-hidden="true">
                    <rect x="32" y="30" width="76" height="56" rx="10" fill="#E5E7EB"/>
                    <rect x="72" y="38" width="28" height="36" rx="6" fill="#D1D5DB"/>
                    <circle cx="50" cy="52" r="8" fill="#3B82F6"/>
                    <rect x="42" y="64" width="18" height="12" rx="6" fill="#3B82F6"/>
                    <circle cx="92" cy="38" r="18" fill="#22C55E"/>
                    <path d="M84 38l6 6 12-14" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <div class="flex justify-center mt-10">
                <button type="button" onclick="closeBlockSuccess()"
                        class="px-14 py-2.5 rounded-md bg-[#0B3A82] text-white font-medium hover:opacity-95">
                    Oke
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // ===== Block Modal Logic =====
    const modalConfirm = document.getElementById('modalBlockConfirm');
    const modalSuccess = document.getElementById('modalBlockSuccess');
    const blockForm = document.getElementById('blockActionForm');

    const confirmBackdrop = document.getElementById('blockConfirmBackdrop');
    const successBackdrop = document.getElementById('blockSuccessBackdrop');

    let pendingActionUrl = null;

    function openBlockConfirm(actionUrl) {
        pendingActionUrl = actionUrl;
        modalConfirm.classList.remove('hidden');
    }

    function closeBlockConfirm() {
        modalConfirm.classList.add('hidden');
        pendingActionUrl = null;
    }

    function submitBlockAction() {
        if (!pendingActionUrl) return;
        blockForm.action = pendingActionUrl;
        blockForm.submit();
    }

    function openBlockSuccess() {
        modalSuccess.classList.remove('hidden');
    }

    function closeBlockSuccess() {
        modalSuccess.classList.add('hidden');
    }

    confirmBackdrop?.addEventListener('click', closeBlockConfirm);
    successBackdrop?.addEventListener('click', closeBlockSuccess);

    // ===== Kalender filter -> update URL query (q tetap kebawa) =====
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

@if(session('block_success'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        openBlockSuccess();
    });
</script>
@endif

@endsection
