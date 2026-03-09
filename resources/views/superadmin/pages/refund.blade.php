@extends('superadmin.layouts.app')

@section('content')
@php
    $q      = $q ?? request('q');
    $status = $status ?? request('status');
    $date   = $date ?? request('date');

    $statusOpt = [
        ''         => 'Status',
        'diproses' => 'Diproses',
        'diterima' => 'Diterima',
        'ditolak'  => 'Ditolak',
    ];
@endphp
<div class="space-y-6 font-['Urbanist'] w-full">

    {{-- Title --}}
    <div class="mt-2">
        <div class="text-[18px] font-semibold text-slate-900">Refund</div>
        <div class="text-[12px] text-slate-500 mt-1">Kelola pengajuan refund.</div>
    </div>

    <div class="mt-6 bg-white rounded-2xl border border-slate-100 shadow-sm w-full">

        {{-- Header row (Daftar Refund + filter kanan) --}}
        <div class="p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 w-full">
            <div class="text-[24px] font-semibold text-slate-900">Daftar Refund</div>

            <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3 w-full lg:w-auto">
                {{-- Search --}}
                <div class="relative w-full sm:w-[260px]">
                    <input name="q" value="{{ $q }}" placeholder="Search"
                           class="w-full h-10 rounded-xl border border-slate-200 bg-white px-4 pr-10 text-[12px] text-slate-700 focus:outline-none" />
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">⌕</span>
                </div>

                {{-- Status --}}
                <select name="status"
                        class="h-10 rounded-xl border border-slate-200 bg-white px-4 text-[12px] text-slate-700 focus:outline-none w-full sm:w-[160px]">
                    @foreach($statusOpt as $val => $label)
                        <option value="{{ $val }}" @selected((string)$status === (string)$val)>{{ $label }}</option>
                    @endforeach
                </select>

                {{-- Kalender button + hidden date input (biar mirip UI gambar) --}}
                <input id="dateInput" type="date" name="date" value="{{ $date }}"
                       class="hidden" />

                <button type="button"
                        onclick="document.getElementById('dateInput').showPicker?.(); document.getElementById('dateInput').click();"
                        class="h-10 px-4 rounded-xl bg-white border border-slate-200 text-slate-700 text-[12px]
                               flex items-center gap-2 hover:bg-slate-50 w-full sm:w-auto justify-center">
                    <span>Kalender</span>
                </button>

                {{-- Download --}}
                <a href="{{ route('sa.refund.download', request()->query()) }}"
                   class="h-10 px-4 rounded-xl bg-emerald-600 text-white text-[12px] font-semibold hover:opacity-95
                          flex items-center gap-2 w-full sm:w-auto justify-center">
                    Download
                    <span class="text-[14px]">⭳</span>
                </a>
            </form>
        </div>

        {{-- TABLE --}}
{{-- TABLE (samakan seperti Laporan) --}}
<div class="px-5 pb-5">
    <div class="mt-4 rounded-xl border border-slate-100 overflow-hidden">
        <div class="max-h-[420px] overflow-y-auto overflow-x-hidden">
            <table class="w-full table-fixed text-[11px]">
                <thead class="bg-[#EEF5FF] text-slate-600 font-semibold sticky top-0 z-10">
                    <tr>
                        <th class="text-left px-2 py-2 w-[100px]">NO. ORDER</th>
                        <th class="text-left px-2 py-2 w-[170px]">NAMA COSTUMER</th>
                        <th class="text-left px-2 py-2 w-[170px]">NAMA DRIVER</th>
                        <th class="text-left px-2 py-2 w-[150px]">TANGGAL</th>
                        <th class="text-left px-2 py-2 w-[115px]">LAYANAN</th>
                        <th class="text-left px-2 py-2 w-[110px]">HARGA</th>
                        <th class="text-left px-2 py-2 w-[95px]">STATUS</th>
                        <th class="text-left px-2 py-2 w-[70px]">AKSI</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($rows as $r)
                        @php
                            $order    = $r->order;
                            $customer = $order?->customer;
                            $mitra    = $order?->mitra;

                            $st = strtolower(trim((string)($r->status ?? 'diproses')));
                            $badgeClass = $st === 'diterima'
                                ? 'bg-emerald-500 text-white'
                                : ($st === 'ditolak' ? 'bg-red-500 text-white' : 'bg-amber-400 text-white');

                            $badgeText = strtoupper($st);

                            $tglVal  = $order?->tanggal ?? $r->created_at ?? null;
                            $tglText = $tglVal ? \Carbon\Carbon::parse($tglVal)->format('d/m/Y H:i') : '—';

                            $detailRoute = route('sa.refund.detail', ['id' => $r->id]);
                        @endphp

                        <tr class="text-slate-700">
                            <td class="px-2 py-[6px] truncate">{{ $order?->order_no ?? '—' }}</td>
                            <td class="px-2 py-[6px] truncate">{{ $customer?->name ?? '—' }}</td>
                            <td class="px-2 py-[6px] truncate">{{ $mitra?->name ?? '—' }}</td>
                            <td class="px-2 py-[6px] truncate">{{ $tglText }}</td>
                            <td class="px-2 py-[6px] truncate">{{ $order?->layanan ?? '—' }}</td>
                            <td class="px-2 py-[6px] whitespace-nowrap">
                                Rp {{ number_format((int)($order?->harga ?? 0), 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-[6px]">
                                <span class="inline-flex items-center px-2 py-[2px] rounded-full text-[10px] font-bold {{ $badgeClass }}">
                                    {{ $badgeText }}
                                </span>
                            </td>
                            <td class="px-2 py-[6px]">
                                <a href="{{ $detailRoute }}"
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
                    @empty
                        <tr class="text-slate-300">
                            <td colspan="8" class="px-2 py-6 text-center">
                                Belum ada data refund.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-[12px] text-slate-400">
        {{ $rows instanceof \Illuminate\Pagination\LengthAwarePaginator ? $rows->total() : (is_countable($rows) ? count($rows) : 0) }} entries
    </div>
</div>

@endsection
