<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin: 0 0 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; vertical-align: top; }
        th { background: #f5f5f5; }
        .muted { color: #666; font-size: 11px; }
    </style>
</head>
<body>
    <h2>Daftar Laporan</h2>

    <div class="muted">
        Filter:
        Q = {{ $q ?: '-' }},
        Tanggal = {{ $date ?: '-' }}
    </div>

    <br>

    <table>
        <thead>
            <tr>
                <th>No. Order</th>
                <th>Pelapor</th>
                <th>Terlapor</th>
                <th>Tanggal</th>
                <th>Layanan</th>
                <th>Reason</th>
                <th>Deskripsi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @forelse($rows as $r)
            <tr>
                <td>{{ $r->order_no ?? '-' }}</td>
                <td>{{ ($r->reporter_name ?? '-') }} ({{ strtoupper($r->reporter_role ?? '-') }})</td>
                <td>{{ ($r->reported_name ?? '-') }} ({{ strtoupper($r->reported_role ?? '-') }})</td>
                <td>
                    {{ !empty($r->tanggal_tampil) ? \Carbon\Carbon::parse($r->tanggal_tampil)->format('d/m/Y H:i') : '-' }}
                </td>
                <td>{{ $r->layanan ?? '-' }}</td>
                <td>{{ $r->reason ?? '-' }}</td>
                <td>{{ $r->description ?? '-' }}</td>
                <td>{{ $r->status ?? '-' }}</td>
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
