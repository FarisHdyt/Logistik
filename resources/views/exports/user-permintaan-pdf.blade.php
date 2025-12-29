<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 14px;
            margin: 0;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 12px;
            margin: 2px 0;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 9px;
        }

        .info {
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
            font-size: 10px;
        }

        .info td {
            padding: 2px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 4px;
        }

        table.data th {
            background-color: #eaeaea;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            margin-top: 10px;
            width: 40%;
            font-size: 9px;
        }

        .summary td {
            padding: 3px;
        }

        .signature {
            margin-top: 30px;
            width: 100%;
            font-size: 10px;
        }

        .signature td {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>KEPOLISIAN NEGARA REPUBLIK INDONESIA</h1>
    <h2>POLRES XXXXX</h2>
    <p>SISTEM INFORMASI LOGISTIK (SILOG)</p>
</div>

<div class="info">
    <table>
        <tr>
            <td width="18%">Nama Pemohon</td>
            <td width="32%">: {{ $user->name }}</td>
            <td width="18%">Tanggal Cetak</td>
            <td width="32%">: {{ $printDate }}</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td colspan="3">
                : {{ $filters['start_date'] ?? '-' }} s/d {{ $filters['end_date'] ?? '-' }}
            </td>
        </tr>
    </table>
</div>

<table class="data">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="14%">Kode Permintaan</th>
            <th width="20%">Nama Barang</th>
            <th width="8%">Jumlah</th>
            <th width="12%">Satuan</th>
            <th width="18%">Satker</th>
            <th width="12%">Tanggal</th>
            <th width="12%">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($permintaan as $i => $p)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $p->kode_permintaan }}</td>
            <td>{{ $p->barang->nama_barang ?? '-' }}</td>
            <td class="text-center">{{ $p->jumlah }}</td>
            <td class="text-center">{{ $p->barang->satuan->nama_satuan ?? '-' }}</td>
            <td>{{ $p->satker->nama_satker ?? '-' }}</td>
            <td class="text-center">{{ $p->created_at->format('d/m/Y') }}</td>
            <td class="text-center">{{ strtoupper($p->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table class="summary">
    <tr><td>Total Permintaan</td><td>: {{ $stats['total'] }}</td></tr>
    <tr><td>Pending</td><td>: {{ $stats['pending'] }}</td></tr>
    <tr><td>Disetujui</td><td>: {{ $stats['approved'] }}</td></tr>
    <tr><td>Ditolak</td><td>: {{ $stats['rejected'] }}</td></tr>
    <tr><td>Terkirim</td><td>: {{ $stats['delivered'] }}</td></tr>
</table>

<table class="signature">
    <tr>
        <td width="60%"></td>
        <td>
            Bekasi, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            Pemohon<br><br><br>
            <strong>{{ $user->name }}</strong>
        </td>
    </tr>
</table>
</body>
</html>
