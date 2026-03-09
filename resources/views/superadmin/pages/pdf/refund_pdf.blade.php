<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Refund</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f0f0f0; }
        .meta { margin: 8px 0 12px; }
    </style>
</head>
<body>
<h2>Daftar Refund</h2>

<div class="meta">
    @if(!empty($q)) <div>Search: {{ $q }}</div> @endif
    @if(!empty($status)) <div>Status: {{ strtoupper($status) }}</div> @endif
    @if(!empty($date)) <div>Tanggal: {{ $date }}</div> @endif
</div>

<table>
    <thead>
    <tr>
        <th>No</th>
        <th>No. Order</th>
        <th>Nama Customer</th>
        <th>Nama Driver</th>
        <th>Tanggal</th>
        <th>Layanan</th>
        <th>Harga</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($rows as $i => $r)
        @php
            $order = $r->order;
            $customer = $order?->customer;
            $mitra = $order?->mitra;

            $tglVal  = $order?->tanggal ?? $r->created_at ?? null;
            $tglText = $tglVal ? \Carbon\Carbon::parse($tglVal)->format('d/m/Y H:i') : '—';
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $order?->order_no ?? '—' }}</td>
            <td>{{ $customer?->name ?? '—' }}</td>
            <td>{{ $mitra?->name ?? '—' }}</td>
            <td>{{ $tglText }}</td>
            <td>{{ $order?->layanan ?? '—' }}</td>
            <td>Rp {{ number_format((int)($order?->harga ?? 0), 0, ',', '.') }}</td>
            <td>{{ strtoupper((string)($r->status ?? 'diproses')) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" style="text-align:center;">Tidak ada data</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>