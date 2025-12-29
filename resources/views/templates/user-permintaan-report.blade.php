<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Laporan Permintaan Barang' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }
        h3 {
            margin-bottom: 5px;
        }
        .meta {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .summary td {
            border: none;
            padding: 3px 0;
        }
    </style>
</head>
<body>

<h3>{{ $title }}</h3>

<div class="meta">
    <table class="summary">
        <tr>
            <td><strong>Nama User</strong></td>
            <td>: {{ $user->name }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Cetak</strong></td>
            <td>: {{ $printDate }}</td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Barang</th>
            <th>Satker</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Disetujui Oleh</th>
        </tr>
    </thead>
    <tbody>
        @foreach($permintaan as $i => $p)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->kode_permintaan }}</td>
            <td>{{ $p->barang->nama_barang ?? '-' }}</td>
            <td>{{ $p->satker->nama_satker ?? '-' }}</td>
            <td>{{ $p->jumlah }}</td>
            <td>{{ ucfirst($p->status) }}</td>
            <td>{{ $p->approver->name ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
