@extends('superadmin.layouts.app')

@section('content')
@php
    $order    = $refund->order;
    $customer = $order?->customer;
    $mitra    = $order?->mitra;

    $st = strtolower(trim((string)($refund->status ?? 'diproses')));

    // Header title + visual state
    $isApproved = $st === 'diterima';
    $isRejected = $st === 'ditolak';
    $isProcess  = !$isApproved && !$isRejected;

    $titleText = $isApproved ? 'REFUND SALDO BERHASIL' : ($isRejected ? 'REFUND SALDO DIBATALKAN' : 'REFUND SALDO DIPROSES');

    // gray-out effect for rejected
    $mutedWrap = $isRejected ? 'opacity-45' : '';

    // amount
    $amount = (int)($order->harga ?? 60000); // fallback biar ga kosong
    $amountText = number_format($amount, 0, ',', '.') . ',-';

    // date
    $dateText = '—';
    if (!empty($order?->tanggal)) {
        try { $dateText = \Carbon\Carbon::parse($order->tanggal)->translatedFormat('l, d F Y'); }
        catch (\Throwable $e) { $dateText = (string)$order->tanggal; }
    }

    // safe fallback
    $orderNo = $order?->order_no ?? ('NEBENG-' . str_pad((string)($order?->id ?? 0), 6, '0', STR_PAD_LEFT));
    $trxNo   = $order->transaction_no ?? 'INV/' . now()->format('Ymd') . '/' . str_pad((string)$refund->id, 9, '0', STR_PAD_LEFT);

    $layanan = $order->layanan ?? 'Motor';
    $pickup  = $order->pickup_address ?? $order->titik_jemput ?? '—';
    $dropoff = $order->dropoff_address ?? $order->tujuan ?? '—';

    // time fallback
    $timeText = '09.30 WIB';
@endphp

<div class="px-8 pb-10 font-['Urbanist']">

    {{-- Back --}}
    <div class="mt-2 flex items-center gap-3">
        <a href="{{ route('sa.refund.index') }}"
           class="w-9 h-9 rounded-lg bg-white border border-slate-200 flex items-center justify-center hover:bg-slate-50">
            <span class="text-xl leading-none text-slate-600">‹</span>
        </a>
        <div class="text-[18px] font-semibold text-slate-900">Detail Refund</div>
    </div>

    {{-- Canvas background like screenshot --}}
    <div class="mt-6 flex items-center justify-center">

        {{-- Card --}}
        <div class="w-[360px] max-w-[92vw] bg-white rounded-[10px] shadow-[0_18px_45px_rgba(0,0,0,0.25)] overflow-hidden relative">

            {{-- side “ticket” cutouts --}}
            <div class="absolute left-[-10px] top-[132px] w-[20px] h-[20px] bg-[#6B6A96] rounded-full"></div>
            <div class="absolute right-[-10px] top-[132px] w-[20px] h-[20px] bg-[#6B6A96] rounded-full"></div>

            <div class="p-6 {{ $mutedWrap }}">

                {{-- Title --}}
                <div class="text-center text-[12px] tracking-[0.10em] font-extrabold text-slate-900">
                    {{ $titleText }}
                </div>

                {{-- Icon --}}
                <div class="mt-4 flex items-center justify-center">
                    @if($isApproved)
                        <div class="w-14 h-14 rounded-full bg-[#0B3A82] flex items-center justify-center shadow-[0_10px_20px_rgba(11,58,130,0.25)]">
                            <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none">
                                <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    @elseif($isProcess)
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#0B3A82]"></span>
                            <span class="w-3 h-3 rounded-full bg-slate-200"></span>
                            <span class="w-3 h-3 rounded-full bg-slate-200"></span>
                        </div>
                    @else
                        <div class="w-14 h-14 rounded-full bg-slate-300 flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none">
                                <path d="M7 7l10 10M17 7L7 17" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Date --}}
                <div class="mt-4 text-center text-[10px] text-slate-500">
                    {{ $dateText }}
                </div>

                {{-- Amount --}}
                <div class="mt-1 text-center text-[26px] font-extrabold text-slate-900 leading-[30px]">
                    {{ $amountText }}
                </div>

                {{-- dashed divider --}}
                <div class="mt-5 border-t border-dashed border-slate-300"></div>

                {{-- Top info row --}}
                <div class="mt-4 flex items-start justify-between text-[10px] text-slate-500">
                    <div class="space-y-1">
                        <div>ID Pesanan</div>
                        <div>No. Transaksi</div>
                    </div>
                    <div class="space-y-1 text-right text-slate-700 font-semibold">
                        <div>{{ $orderNo }}</div>
                        <div>{{ $trxNo }}</div>
                    </div>
                </div>

                <div class="mt-4 h-px bg-slate-200"></div>

                {{-- Middle details --}}
                <div class="mt-4 grid grid-cols-2 gap-x-6 gap-y-2 text-[10px]">
                    <div class="text-slate-500">Metode Refund</div>
                    <div class="text-right text-slate-700 font-semibold">Transfer BRIVA</div>

                    <div class="text-slate-500">Layanan Nebeng</div>
                    <div class="text-right text-slate-700 font-semibold">{{ $layanan }}</div>

                    <div class="text-slate-500">Biaya Penumpang<br><span class="text-[9px]">(2 x 30.000,-)</span></div>
                    <div class="text-right text-slate-700 font-semibold">
                        {{ number_format((int)($amount ?? 60000), 0, ',', '.') }},00,-
                    </div>

                    <div class="text-slate-500">Biaya Admin</div>
                    <div class="text-right text-slate-700 font-semibold">0.000,00,-</div>
                </div>

                <div class="mt-4 h-px bg-slate-200"></div>

                <div class="mt-3 flex items-center justify-between text-[10px]">
                    <div class="text-slate-500">Total Refund</div>
                    <div class="text-slate-900 font-extrabold">
                        {{ number_format((int)($amount ?? 60000), 0, ',', '.') }},00,-
                    </div>
                </div>

                {{-- Bottom route section --}}
                <div class="mt-5 grid grid-cols-2 gap-6">
                    <div>
                        <div class="text-[10px] font-bold text-[#0B3A82]">Titik Jemput</div>
                        <div class="mt-1 text-[10px] font-extrabold text-slate-900">{{ $pickup }}</div>
                        <div class="mt-1 text-[9px] text-slate-500">{{ $timeText }}</div>
                        <div class="mt-1 text-[9px] text-slate-500">{{ $pickup }}</div>
                    </div>

                    <div>
                        <div class="text-[10px] font-bold text-[#0B3A82]">Tujuan</div>
                        <div class="mt-1 text-[10px] font-extrabold text-slate-900">{{ $dropoff }}</div>
                        <div class="mt-1 text-[9px] text-slate-500">{{ $timeText }}</div>
                        <div class="mt-1 text-[9px] text-slate-500">{{ $dropoff }}</div>
                    </div>
                </div>

                {{-- mini dot route --}}
                <div class="mt-3 flex items-center justify-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#0B3A82]"></span>
                    <span class="w-10 h-[2px] bg-slate-200"></span>
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                </div>

                {{-- close button --}}
                <div class="mt-5">
                    <a href="{{ route('sa.refund.index') }}"
                       class="h-11 w-full rounded-[6px] bg-slate-300 text-slate-800 text-[12px] font-semibold
                              flex items-center justify-center hover:opacity-95">
                        CLOSE
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
