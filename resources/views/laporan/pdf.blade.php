<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        th,
        td {
            border: 1px solid #333;
            padding: 4px
        }

        th {
            background: #eee
        }
    </style>
</head>

<body>
    <h3>Laporan Keuangan ({{ $start }} s/d {{ $end }}) - {{ $status }}</h3>
    <p>Total Masuk: Rp {{ number_format($totalMasuk, 0, ',', '.') }} | Total Keluar: Rp
        {{ number_format($totalKeluar, 0, ',', '.') }} | Balance: Rp {{ number_format($balance, 0, ',', '.') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Bank</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r->tanggal_keuangan->format('d-m-Y') }}</td>
                    <td>{{ $r->status }}</td>
                    <td>{{ $r->bank->nama_bank ?? '-' }}</td>
                    <td>{{ $r->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ number_format($r->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $r->catatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
