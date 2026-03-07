<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Kendaraan Mitra</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin: 0 0 10px 0; }
        p { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h2>Daftar Kendaraan Mitra</h2>

@if(!empty($q))
  <p>Search: {{ $q }}</p>
@endif

<table>
    <thead>
        <tr>
            <th style="width:40px;">No</th>
            <th>Nama</th>
            <th>Kendaraan</th>
            <th>Merk</th>
            <th>Plat</th>
            <th>Warna</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->name ?? '-' }}</td>
                <td>{{ $row->kendaraan ?? '-' }}</td>
                <td>{{ $row->merk ?? '-' }}</td>
                <td>{{ $row->plat ?? '-' }}</td>
                <td>{{ $row->warna ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center;">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>