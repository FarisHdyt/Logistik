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
            vertical-align: top;
        }

        table.data th {
            background-color: #eaeaea;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
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

        /* Styling untuk multi barang */
        .multi-barang {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        .multi-barang-item {
            padding: 2px 0;
            border-bottom: 1px dotted #ddd;
        }

        .multi-barang-item:last-child {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 1px 4px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 3px;
            margin-right: 3px;
        }

        .badge-primary {
            background-color: #3b82f6;
            color: white;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fbbf24;
            color: #000;
        }

        .status-approved {
            background-color: #10b981;
            color: white;
        }

        .status-rejected {
            background-color: #ef4444;
            color: white;
        }

        .status-delivered {
            background-color: #3b82f6;
            color: white;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>KEPOLISIAN NEGARA REPUBLIK INDONESIA</h1>
    <h2>POLRES METRO BEKASI KOTA</h2>
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
        @if($filters['status'] ?? false)
        <tr>
            <td>Status</td>
            <td colspan="3">: {{ ucfirst($filters['status']) }}</td>
        </tr>
        @endif
    </table>
</div>

<table class="data">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="14%">Kode Permintaan</th>
            <th width="25%">Barang</th>
            <th width="8%">Jumlah</th>
            <th width="10%">Satuan Kerja</th>
            <th width="12%">Tanggal</th>
            <th width="12%">Status</th>
            <th width="15%">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalItems = 0;
            $totalQuantity = 0;
        @endphp
        
        @foreach($permintaan as $i => $p)
        @php
            // Cek apakah ini multi barang atau single barang
            $isMultiBarang = isset($p->details) && $p->details->count() > 0;
            $totalJumlah = $isMultiBarang ? $p->details->sum('jumlah') : $p->jumlah;
            $barangCount = $isMultiBarang ? $p->details->count() : 1;
            
            // Hitung total untuk summary
            $totalItems += $barangCount;
            $totalQuantity += $totalJumlah;
        @endphp
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $p->kode_permintaan }}</td>
            <td class="text-left">
                @if($isMultiBarang)
                    <div style="margin-bottom: 5px;">
                        <strong>{{ $barangCount }} jenis barang</strong>
                    </div>
                    <ul class="multi-barang">
                        @foreach($p->details as $detail)
                        <li class="multi-barang-item">
                            {{ $detail->barang->nama_barang ?? 'N/A' }}
                            <span class="badge badge-primary">{{ $detail->jumlah }}</span>
                            {{ $detail->barang->satuan->nama_satuan ?? 'unit' }}
                        </li>
                        @endforeach
                    </ul>
                @else
                    <strong>{{ $p->barang->nama_barang ?? '-' }}</strong><br>
                    <small>{{ $p->barang->kode_barang ?? '' }}</small>
                @endif
            </td>
            <td class="text-center">
                @if($isMultiBarang)
                    {{ $totalJumlah }} unit<br>
                    <small>({{ $barangCount }} jenis)</small>
                @else
                    {{ $p->jumlah }}<br>
                    <small>{{ $p->barang->satuan->nama_satuan ?? 'unit' }}</small>
                @endif
            </td>
            <td>{{ $p->satker->nama_satker ?? '-' }}</td>
            <td class="text-center">
                {{ $p->created_at->format('d/m/Y') }}<br>
                <small>{{ $p->tanggal_dibutuhkan ? \Carbon\Carbon::parse($p->tanggal_dibutuhkan)->format('d/m/Y') : '-' }}</small>
            </td>
            <td class="text-center">
                @if($p->status == 'pending')
                    <span class="status-badge status-pending">Pending</span>
                @elseif($p->status == 'approved')
                    <span class="status-badge status-approved">Disetujui</span>
                @elseif($p->status == 'rejected')
                    <span class="status-badge status-rejected">Ditolak</span>
                @elseif($p->status == 'delivered')
                    <span class="status-badge status-delivered">Terkirim</span>
                @endif
                
                @if($p->alasan_penolakan && $p->status == 'rejected')
                <div style="font-size: 7px; margin-top: 2px; color: #dc2626;">
                    {{ Str::limit($p->alasan_penolakan, 30) }}
                </div>
                @endif
            </td>
            <td>{{ Str::limit($p->keterangan, 50) }}</td>
        </tr>
        @endforeach
        
        @if(count($permintaan) == 0)
        <tr>
            <td colspan="8" class="text-center" style="padding: 20px;">
                Tidak ada data permintaan untuk periode ini
            </td>
        </tr>
        @endif
    </tbody>
</table>

<table class="summary">
    <tr><td><strong>Total Permintaan</strong></td><td>: {{ count($permintaan) }}</td></tr>
    <tr><td>Total Jenis Barang</td><td>: {{ $totalItems }}</td></tr>
    <tr><td>Total Jumlah Barang</td><td>: {{ $totalQuantity }} unit</td></tr>
    <tr><td>Rata-rata Barang/Request</td><td>: {{ count($permintaan) > 0 ? round($totalQuantity / count($permintaan), 1) : 0 }} unit</td></tr>
    <tr><td>Permintaan Pending</td><td>: {{ $stats['pending'] ?? 0 }}</td></tr>
    <tr><td>Permintaan Disetujui</td><td>: {{ $stats['approved'] ?? 0 }}</td></tr>
    <tr><td>Permintaan Ditolak</td><td>: {{ $stats['rejected'] ?? 0 }}</td></tr>
    <tr><td>Permintaan Terkirim</td><td>: {{ $stats['delivered'] ?? 0 }}</td></tr>
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