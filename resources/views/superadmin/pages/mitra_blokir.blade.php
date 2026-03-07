{{-- resources/views/superadmin/pages/mitra_blokir.blade.php --}}

@extends('superadmin.layouts.app') {{-- ganti kalau layout kamu beda --}}

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-[22px] font-semibold text-slate-800">Mitra</h1>

        <div class="flex items-center gap-3">
            <div class="relative">
                <input
                    type="text"
                    placeholder="Search"
                    class="w-[260px] h-[36px] rounded-full border border-slate-200 bg-white pl-10 pr-4 text-sm outline-none focus:ring-2 focus:ring-slate-200"
                >
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21l-4.3-4.3m1.3-5.2a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h2 class="text-[14px] font-semibold text-slate-800">Daftar Blockir Data Mitra</h2>

            <div class="flex items-center gap-2">
                <button class="h-[34px] px-4 rounded-lg border border-slate-200 text-sm text-slate-700 hover:bg-slate-50 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M8 7V3m8 4V3M4 11h16M6 5h12a2 2 0 012 2v13a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Kalender
                </button>

                <button class="h-[34px] px-4 rounded-lg bg-emerald-500 text-white text-sm hover:bg-emerald-600 inline-flex items-center gap-2">
                    Download
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M12 3v10m0 0l4-4m-4 4L8 9M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Search in table --}}
        <div class="mb-3">
            <div class="relative w-[240px]">
                <input
                    type="text"
                    placeholder="Search"
                    class="w-full h-[34px] rounded-lg border border-slate-200 bg-white pl-10 pr-3 text-sm outline-none focus:ring-2 focus:ring-slate-200"
                >
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21l-4.3-4.3m1.3-5.2a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        <div class="overflow-x-auto">
           @php
    $rows = $rows ?? collect();
    $isEmpty = method_exists($rows, 'isEmpty') ? $rows->isEmpty() : empty($rows);

    $badgeBlock = function ($status) {
        $s = strtolower(trim((string) $status));
        if (in_array($s, ['block','blocked','1','true'], true)) {
            return ['text' => 'BLOCK', 'class' => 'bg-red-500 text-white'];
        }
        return ['text' => strtoupper($status ?: 'AKTIF'), 'class' => 'bg-slate-200 text-slate-700'];
    };
@endphp

{{-- TABLE SCROLL (seperti Verifikasi Mitra) --}}
<div class="mt-4 rounded-xl border border-slate-100 overflow-hidden">
    <div class="max-h-[420px] overflow-y-auto overflow-x-hidden">
        <table class="w-full table-fixed text-[12px]">
            <thead class="bg-[#EEF5FF] text-slate-600 font-semibold sticky top-0 z-10">
                <tr>
                    <th class="text-left px-4 py-3 w-[95px]">NO. ID</th>
                    <th class="text-left px-4 py-3 w-[200px]">NAMA</th>
                    <th class="text-left px-4 py-3 w-[240px]">EMAIL</th>
                    <th class="text-left px-4 py-3 w-[150px]">NO. TLP</th>
                    <th class="text-left px-4 py-3 w-[130px]">LAYANAN</th>
                    <th class="text-left px-4 py-3 w-[120px]">STATUS</th>
                    <th class="text-left px-4 py-3 w-[140px]">AKSI</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
                @if($isEmpty)
                    @for($i=1;$i<=8;$i++)
                        <tr class="text-slate-300">
                            <td class="px-4 py-4">—</td>
                            <td class="px-4 py-4">—</td>
                            <td class="px-4 py-4">—</td>
                            <td class="px-4 py-4">—</td>
                            <td class="px-4 py-4">—</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400">—</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-[28px] h-[28px] rounded-[8px] bg-slate-200/60"></div>
                                    <div class="w-[28px] h-[28px] rounded-[8px] bg-emerald-200/60"></div>
                                    <div class="w-[28px] h-[28px] rounded-[8px] bg-rose-200/60"></div>
                                </div>
                            </td>
                        </tr>
                    @endfor
                @else
                    @foreach($rows as $r)
                        @php
                            // support object/array
                            $id = is_array($r) ? ($r['id'] ?? '-') : ($r->id ?? '-');
                            $nama = is_array($r) ? ($r['nama'] ?? $r['name'] ?? '-') : ($r->nama ?? $r->name ?? '-');
                            $email = is_array($r) ? ($r['email'] ?? '-') : ($r->email ?? '-');
                            $telp = is_array($r) ? ($r['telp'] ?? $r['phone_number'] ?? '-') : ($r->telp ?? $r->phone_number ?? '-');
                            $layanan = is_array($r) ? ($r['layanan'] ?? '-') : ($r->layanan ?? '-');
                            $status = is_array($r) ? ($r['status'] ?? 'BLOCK') : ($r->status ?? 'BLOCK');
                            $b = $badgeBlock($status);

                            // ganti route sesuai punyamu:
                            $detailUrl  = route('sa.mitra.detail', ['id' => $id]); // contoh
                            $unblockUrl = route('sa.mitra.unblock', ['id' => $id]); // contoh
                            $deleteUrl  = route('sa.mitra.delete', ['id' => $id]); // contoh
                        @endphp

                        <tr class="align-middle">
                            <td class="px-4 py-4 whitespace-nowrap">{{ $id }}</td>

                            <td class="px-4 py-4">
                                <div class="truncate font-medium text-slate-800">{{ $nama }}</div>
                            </td>

                            <td class="px-4 py-4">
                                <div class="truncate">{{ $email }}</div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">{{ $telp }}</td>

                            <td class="px-4 py-4">
                                <div class="truncate">{{ $layanan }}</div>
                            </td>

                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold {{ $b['class'] }}">
                                    {{ $b['text'] }}
                                </span>
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">

                                    {{-- VIEW --}}
                                    <a href="{{ $detailUrl }}"
                                       class="w-[28px] h-[28px] rounded-[8px] bg-[#0B3A82] text-white flex items-center justify-center hover:opacity-90 transition"
                                       title="Lihat">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"
                                                  stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"
                                                  stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                    </a>

                                    {{-- UNBLOCK --}}
                                    <form method="POST" action="{{ $unblockUrl }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-[28px] h-[28px] rounded-[8px] bg-emerald-500 text-white flex items-center justify-center hover:opacity-90 transition"
                                            title="Unblock">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                <path d="M7 10V8a5 5 0 0 1 9.7-2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M7 10h10a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"
                                                      stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </form>

                                    {{-- DELETE --}}
                                    <form method="POST" action="{{ $deleteUrl }}" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-[28px] h-[28px] rounded-[8px] bg-red-500 text-white flex items-center justify-center hover:opacity-90 transition"
                                            title="Hapus">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                <path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M8 6V4h8v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M7 6l1 16h8l1-16" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


        {{-- Footer pagination --}}
        <div class="flex items-center justify-between mt-4 text-xs text-slate-500">
            <div class="flex items-center gap-2">
                <select class="h-8 rounded-md border border-slate-200 bg-white px-2">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <span>of {{ count($rows) }} entries</span>
            </div>

            <div class="flex items-center gap-2">
                <button class="w-8 h-8 rounded-md border border-slate-200 hover:bg-slate-50">‹</button>
                <button class="w-8 h-8 rounded-md bg-slate-100">1</button>
                <button class="w-8 h-8 rounded-md border border-slate-200 hover:bg-slate-50">2</button>
                <button class="w-8 h-8 rounded-md border border-slate-200 hover:bg-slate-50">3</button>
                <button class="w-8 h-8 rounded-md border border-slate-200 hover:bg-slate-50">›</button>
            </div>
        </div>
    </div>
</div>
@endsection
