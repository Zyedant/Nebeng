<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Mitra</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h2>Daftar Mitra</h2>

@if(!empty($q)) <p>Search: {{ $q }}</p> @endif
@if(!empty($status)) <p>Status: {{ $status }}</p> @endif
@if(!empty($date)) <p>Tanggal: {{ $date }}</p> @endif

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->name ?? '-' }}</td>
                <td>{{ $row->email ?? '-' }}</td>
                <td>{{ $row->phone_number ?? '-' }}</td>
                <td>{{ $row->verified_status ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center;">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
