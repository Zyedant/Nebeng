<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #ddd; padding:6px; }
    th { background:#f3f6ff; }
  </style>
</head>
<body>
  <h3>Daftar Pesanan</h3>

  <table>
    <thead>
      <tr>
        <th>No Order</th>
        <th>Customer</th>
        <th>Driver</th>
        <th>Tanggal</th>
        <th>Layanan</th>
        <th>Harga</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $r)
        <tr>
          <td>{{ $r->order_no ?? '-' }}</td>
          <td>{{ $r->customer?->name ?? '-' }}</td>
          <td>{{ $r->mitra?->name ?? '-' }}</td>
          <td>
            @php $tglVal = $r->tanggal ?? $r->created_at ?? null; @endphp
            {{ $tglVal ? \Carbon\Carbon::parse($tglVal)->format('d/m/Y H:i') : '-' }}
          </td>
          <td>{{ $r->layanan ?? '-' }}</td>
          <td>Rp {{ number_format((int)($r->harga ?? 0), 0, ',', '.') }}</td>
          <td>{{ strtoupper($r->status ?? '-') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>