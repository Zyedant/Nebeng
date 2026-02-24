@extends('superadmin.layouts.app')

@section('content')
@php
    $s = strtolower(trim((string)($order->status ?? 'proses')));

    // =========================
    // BADGE STATUS (chip)
    // =========================
    $badgeClass = 'bg-amber-400 text-white';
    $badgeText  = 'Proses';

    if ($s === 'selesai') {
        $badgeClass = 'bg-emerald-500 text-white';
        $badgeText  = 'Selesai';
    } elseif ($s === 'batal') {
        $badgeClass = 'bg-red-500 text-white';
        $badgeText  = 'Batal';
    }

    // =========================
    // THEME TAMPILAN PER STATUS (card, page tone)
    // =========================
    $pageTone = '';          // optional tone wrapper
    $cardTone = 'bg-white';  // default card bg
    $muted    = '';          // untuk "batal" biar pudar

    if ($s === 'selesai') {
        $cardTone = 'bg-emerald-50';
        $pageTone = '';
        $muted    = '';
    } elseif ($s === 'batal') {
        $cardTone = 'bg-red-50';
        $pageTone = '';
        $muted    = 'opacity-70';
    } else { // proses
        $cardTone = 'bg-amber-50';
        $pageTone = '';
        $muted    = '';
    }

    $customer = $order->customer;
    $mitra = $order->mitra;
    $vehicle = $mitra?->partner?->vehicles?->first();

    // format tanggal
    $tanggalText = '—';
    if (!empty($order->tanggal)) {
        try {
            $tanggalText = \Carbon\Carbon::parse($order->tanggal)->format('d/m/Y H:i');
        } catch (\Throwable $e) {
            $tanggalText = (string)$order->tanggal;
        }
    }

    // perjalanan (fallback)
    $pickupAddress  = $order->pickup_address ?? $order->titik_jemput ?? null;
    $dropoffAddress = $order->dropoff_address ?? $order->tujuan ?? null;

    $distanceKm  = $order->distance_km ?? null;
    $durationMin = $order->duration_min ?? null;

    // pembayaran (kalau ada)
    $paymentMethod = $order->payment_method ?? null;
    $transactionNo = $order->transaction_no ?? null;
@endphp

<div class="px-8 pb-10 font-['Urbanist'] {{ $pageTone }}">

    {{-- Header kecil: Back + Title --}}
    <div class="mt-2 flex items-center gap-3">
        <a href="{{ route('sa.transaksi') }}"
           class="w-9 h-9 rounded-lg bg-white border border-slate-200 flex items-center justify-center hover:bg-slate-50">
            <span class="text-xl leading-none text-slate-600">‹</span>
        </a>
        <div class="text-[18px] font-semibold text-slate-900">Detail Pesanan</div>
    </div>

    {{-- ID Pesanan --}}
    <div class="mt-5 flex items-center justify-between {{ $muted }}">
        <div class="text-[12px] text-slate-600">
            <span class="font-semibold text-slate-700">ID Pesanan :</span>
        </div>

        <div class="flex items-center gap-2 text-[12px] text-slate-700">
            <span id="orderIdText" class="font-semibold">{{ $order->order_no ?? '—' }}</span>
            <button type="button" onclick="copyOrderId()"
                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center"
                    title="Copy">
                ⧉
            </button>
        </div>
    </div>

    {{-- GRID ATAS: Customer + Mitra --}}
    <div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-5 {{ $muted }}">

        {{-- CARD CUSTOMER --}}
       <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/70 overflow-hidden flex items-center justify-center border border-slate-100">
                        <span class="text-slate-500">👤</span>
                    </div>
                    <div>
                        <div class="text-[13px] font-semibold text-slate-900">
                            {{ $customer?->name ?? '—' }}
                        </div>
                        <div class="text-[11px] text-slate-500">Customer</div>
                        <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-[10px] font-bold {{ $badgeClass }}">
                            {{ $badgeText }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-5 text-[13px] font-semibold text-slate-900">Informasi Customer</div>

            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <div class="text-[11px] text-slate-500 mb-1">Nama Lengkap</div>
                    <div class="h-9 rounded-lg bg-white/70 px-3 flex items-center text-[12px] text-slate-700 border border-slate-100">
                        {{ $customer?->name ?? '—' }}
                    </div>
                </div>
                <div>
                    <div class="text-[11px] text-slate-500 mb-1">No. Tlp</div>
                    <div class="h-9 rounded-lg bg-white/70 px-3 flex items-center text-[12px] text-slate-700 border border-slate-100">
                        {{ $customer?->phone_number ?? '—' }}
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <div class="text-[11px] text-slate-500 mb-1">Catatan Untuk Driver</div>
                <div class="min-h-[90px] rounded-lg bg-white/70 p-3 text-[12px] text-slate-700 border border-slate-100">
                    {{ $order->catatan ?? '—' }}
                </div>
            </div>
        </div>

        {{-- CARD MITRA --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/70 overflow-hidden flex items-center justify-center border border-slate-100">
                        <span class="text-slate-500">🧑‍✈️</span>
                    </div>
                    <div>
                        <div class="text-[13px] font-semibold text-slate-900">
                            {{ $mitra?->name ?? '—' }}
                        </div>
                        <div class="text-[11px] text-slate-500">Mitra</div>
                        <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-[10px] font-bold {{ $badgeClass }}">
                            {{ $badgeText }}
                        </span>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white/70 px-4 py-3 text-right">
                    <div class="text-[10px] text-slate-500">ID MITRA</div>
                    <div class="text-[12px] font-semibold text-slate-800 flex items-center justify-end gap-2">
                        {{ $mitra?->id ?? '—' }} <span class="text-slate-500">✓</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 text-[13px] font-semibold text-slate-900">Informasi Mitra</div>

            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <div class="text-[11px] text-slate-500 mb-1">Nama Lengkap</div>
                    <div class="h-9 rounded-lg bg-white/70 px-3 flex items-center text-[12px] text-slate-700 border border-slate-100">
                        {{ $mitra?->name ?? '—' }}
                    </div>
                </div>
                <div>
                    <div class="text-[11px] text-slate-500 mb-1">No. Tlp</div>
                    <div class="h-9 rounded-lg bg-white/70 px-3 flex items-center text-[12px] text-slate-700 border border-slate-100">
                        {{ $mitra?->phone_number ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500 mb-1">Kendaraan</div>
                    <div class="h-9 rounded-lg bg-white/70 px-3 flex items-center text-[12px] text-slate-700 border border-slate-100">
                        {{ $vehicle->vehicle_type ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500 mb-1">Merk Kendaraan</div>
                    <div class="h-9 rounded-lg bg-white/70 px-3 flex items-center text-[12px] text-slate-700 border border-slate-100">
                        {{ $vehicle->vehicle_brand ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500 mb-1">Plat Nomor Kendaraan</div>
                    <div class="h-9 rounded-lg bg-white/70 px-3 flex items-center text-[12px] text-slate-700 border border-slate-100">
                        {{ $vehicle->plate_number ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500 mb-1">Warna Kendaraan</div>
                    <div class="h-9 rounded-lg bg-white/70 px-3 flex items-center text-[12px] text-slate-700 border border-slate-100">
                        {{ $vehicle->color ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- GRID BAWAH: Rincian Perjalanan + Pembayaran --}}
    <div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-5 {{ $muted }}">

        {{-- Rincian Perjalanan --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="text-[13px] font-semibold text-slate-900">Rincian Perjalanan</div>

            <div class="mt-3 flex items-center justify-between text-[11px] text-slate-500">
                <div>{{ $tanggalText }}</div>
                <div>{{ $order->layanan ? strtoupper($order->layanan) : '—' }}</div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <div class="text-[11px] text-slate-500">Titik Jemput</div>
                    <div class="mt-1 text-[12px] font-semibold text-slate-900">
                        {{ $pickupAddress ?? '—' }}
                    </div>
                    <div class="mt-1 text-[11px] text-slate-500">
                        {{ $distanceKm !== null ? $distanceKm.' km' : '—' }}
                    </div>
                    <div class="mt-1 text-[11px] text-slate-600">
                        {{ $durationMin !== null ? $durationMin.' menit' : '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">Tujuan</div>
                    <div class="mt-1 text-[12px] font-semibold text-slate-900">
                        {{ $dropoffAddress ?? '—' }}
                    </div>
                    <div class="mt-1 text-[11px] text-slate-500">—</div>
                    <div class="mt-1 text-[11px] text-slate-600">—</div>
                </div>
            </div>

            <div class="mt-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                <span class="w-10 h-[2px] bg-slate-200"></span>
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
            </div>
        </div>

        {{-- Rincian Pembayaran --}}
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="text-[13px] font-semibold text-slate-900">Rincian Pembayaran</div>

            <div class="mt-3 grid grid-cols-2 gap-4">
                <div class="text-[11px] text-slate-500 space-y-2">
                    <div>Type Pembayaran</div>
                    <div>Tanggal</div>
                    <div>ID Pesanan</div>
                    <div>No Transaksi</div>
                </div>

                <div class="text-[11px] text-slate-800 font-semibold space-y-2 text-right">
                    <div>{{ $paymentMethod ?? 'QRIS' }}</div>
                    <div>{{ $tanggalText }}</div>
                    <div>{{ $order->order_no ?? '—' }}</div>
                    <div>{{ $transactionNo ?? '—' }}</div>
                </div>
            </div>

            <div class="mt-4 h-px bg-slate-200"></div>

            <div class="mt-4 space-y-2 text-[11px]">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Harga</span>
                    <span class="text-slate-800 font-semibold">
                        Rp {{ number_format((int)($order->harga ?? 0), 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Status</span>
                    <span class="text-slate-800 font-semibold">{{ $badgeText }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyOrderId() {
            const el = document.getElementById('orderIdText');
            if (!el) return;
            navigator.clipboard.writeText(el.textContent.trim());
        }
    </script>

</div>
@endsection
