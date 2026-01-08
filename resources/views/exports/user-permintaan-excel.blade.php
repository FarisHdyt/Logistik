<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            padding: 6px;
            border: 1px solid #000;
            text-align: center;
        }
        
        td {
            padding: 5px;
            border: 1px solid #000;
            vertical-align: top;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .header h1 {
            font-size: 16px;
            margin: 0;
        }
        
        .header h2 {
            font-size: 14px;
            margin: 3px 0;
        }
        
        .info {
            margin-bottom: 10px;
        }
        
        .info td {
            border: none;
            padding: 2px;
        }
        
        .summary {
            margin-top: 15px;
        }
        
        .summary td {
            border: none;
            padding: 3px;
        }
        
        /* Status colors */
        .status-pending {
            background-color: #fbbf24;
            color: #000;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-approved {
            background-color: #10b981;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-rejected {
            background-color: #ef4444;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-delivered {
            background-color: #3b82f6;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-mixed {
            background-color: #f97316;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
        }
        
        .multi-barang-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
            font-size: 10px;
        }
        
        .multi-barang-item {
            padding: 2px 0;
            border-bottom: 1px dotted #ddd;
        }
        
        .multi-barang-item:last-child {
            border-bottom: none;
        }
        
        .detail-badge {
            font-size: 9px;
            padding: 1px 3px;
            border-radius: 2px;
            margin-right: 2px;
        }
        
        .detail-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .detail-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .detail-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .detail-delivered {
            background-color: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>KEPOLISIAN NEGARA REPUBLIK INDONESIA</h1>
    <h2>POLRES METRO BEKASI KOTA</h2>
    <p><strong>SISTEM INFORMASI LOGISTIK (SILOG)</strong></p>
    <p><strong>LAPORAN PERMINTAAN BARANG - {{ $user->name }}</strong></p>
</div>

<div class="info">
    <table>
        <tr>
            <td width="15%">Nama Pemohon:</td>
            <td width="35%">{{ $user->name }}</td>
            <td width="15%">Tanggal Cetak:</td>
            <td width="35%">{{ $printDate }}</td>
        </tr>
        <tr>
            <td>Periode:</td>
            <td colspan="3">{{ $filters['start_date'] ?? '-' }} s/d {{ $filters['end_date'] ?? '-' }}</td>
        </tr>
        @if($filters['status'] ?? false)
        <tr>
            <td>Status Filter:</td>
            <td colspan="3">{{ ucfirst($filters['status']) }}</td>
        </tr>
        @endif
    </table>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Permintaan</th>
            <th>Barang</th>
            <th>Jumlah</th>
            <th>Satuan Kerja</th>
            <th>Tanggal Permintaan</th>
            <th>Tanggal Dibutuhkan</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalItems = 0;
            $totalQuantity = 0;
            $totalJenisBarang = 0;
        @endphp
        
        @foreach($permintaan as $i => $p)
        @php
            // Cek apakah ini multi barang atau single barang
            $isMultiBarang = isset($p->details) && $p->details->count() > 0;
            $totalJumlah = $isMultiBarang ? $p->details->sum('jumlah') : $p->jumlah;
            $barangCount = $isMultiBarang ? $p->details->count() : 1;
            
            // Hitung status per detail untuk status campuran
            $approvedDetails = 0;
            $rejectedDetails = 0;
            $pendingDetails = 0;
            $deliveredDetails = 0;
            
            if ($isMultiBarang && $p->details) {
                foreach ($p->details as $detail) {
                    if ($detail->status === 'approved' || $detail->status === 'delivered') {
                        $approvedDetails++;
                        if ($detail->status === 'delivered') $deliveredDetails++;
                    } 
                    else if ($detail->status === 'rejected') $rejectedDetails++;
                    else $pendingDetails++;
                }
            }
            
            // Cek apakah status campuran
            $hasMixedStatus = $approvedDetails > 0 && $rejectedDetails > 0;
            
            // Hitung total untuk summary
            $totalItems += 1;
            $totalJenisBarang += $barangCount;
            $totalQuantity += $totalJumlah;
        @endphp
        <tr>
            <td style="text-align: center;">{{ $i + 1 }}</td>
            <td><strong>{{ $p->kode_permintaan }}</strong></td>
            <td>
                @if($isMultiBarang)
                    <div><strong>{{ $barangCount }} jenis barang:</strong></div>
                    <ul class="multi-barang-list">
                        @foreach($p->details as $detail)
                        <li class="multi-barang-item">
                            <div style="display: flex; justify-content: space-between;">
                                <div>
                                    {{ $detail->barang->kode_barang ?? 'N/A' }} - 
                                    {{ $detail->barang->nama_barang ?? 'N/A' }}
                                    ({{ $detail->jumlah }} {{ $detail->barang->satuan->nama_satuan ?? 'unit' }})
                                </div>
                                <div>
                                    @if($detail->status == 'approved')
                                        <span class="detail-badge detail-approved">✓</span>
                                    @elseif($detail->status == 'rejected')
                                        <span class="detail-badge detail-rejected">✗</span>
                                    @elseif($detail->status == 'delivered')
                                        <span class="detail-badge detail-delivered">🚚</span>
                                    @else
                                        <span class="detail-badge detail-pending">⏳</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div>
                        <strong>{{ $p->barang->nama_barang ?? '-' }}</strong><br>
                        <span style="color:#666;">{{ $p->barang->kode_barang ?? '' }}</span>
                    </div>
                @endif
            </td>
            <td style="text-align: center;">
                @if($isMultiBarang)
                    <strong>{{ $totalJumlah }}</strong><br>
                    <span style="color:#666;">({{ $barangCount }} jenis)</span>
                @else
                    <strong>{{ $p->jumlah }}</strong><br>
                    <span style="color:#666;">{{ $p->barang->satuan->nama_satuan ?? 'unit' }}</span>
                @endif
            </td>
            <td>
                {{-- ✅ PERBAIKAN: Tampilkan SATKER yang DIPILIH USER --}}
                {{ $p->satker->nama_satker ?? '-' }}
            </td>
            <td style="text-align: center;">
                {{ $p->created_at->format('d/m/Y H:i') }}
            </td>
            <td style="text-align: center;">
                {{ $p->tanggal_dibutuhkan ? \Carbon\Carbon::parse($p->tanggal_dibutuhkan)->format('d/m/Y') : '-' }}
            </td>
            <td style="text-align: center;">
                @if($p->status == 'pending')
                    <span class="status-pending">Pending</span>
                @elseif($p->status == 'approved')
                    @if($hasMixedStatus)
                        <span class="status-mixed">Status Campuran</span>
                    @else
                        <span class="status-approved">Disetujui</span>
                    @endif
                @elseif($p->status == 'rejected')
                    <span class="status-rejected">Ditolak</span>
                @elseif($p->status == 'delivered')
                    <span class="status-delivered">Terkirim</span>
                @endif
                
                @if($p->alasan_penolakan && $p->status == 'rejected')
                <div style="font-size: 9px; margin-top: 2px; color: #dc2626;">
                    {{ Str::limit($p->alasan_penolakan, 30) }}
                </div>
                @endif
                
                @if($isMultiBarang)
                <div style="font-size: 9px; margin-top: 2px;">
                    @if($approvedDetails > 0)<span style="color:#10b981;">✓{{ $approvedDetails }}</span> @endif
                    @if($rejectedDetails > 0)<span style="color:#ef4444;">✗{{ $rejectedDetails }}</span> @endif
                    @if($pendingDetails > 0)<span style="color:#fbbf24;">⏳{{ $pendingDetails }}</span> @endif
                </div>
                @endif
            </td>
            <td>{{ Str::limit($p->keterangan, 100) }}</td>
        </tr>
        @endforeach
        
        @if(count($permintaan) == 0)
        <tr>
            <td colspan="9" style="text-align: center; padding: 20px;">
                Tidak ada data permintaan untuk periode ini
            </td>
        </tr>
        @endif
    </tbody>
</table>

<div class="summary">
    <table>
        <tr>
            <td colspan="2" style="border: 1px solid #000; background-color: #f0f0f0; font-weight: bold;">RINGKASAN LAPORAN</td>
        </tr>
        <tr>
            <td width="60%">Total Permintaan:</td>
            <td width="40%">{{ count($permintaan) }}</td>
        </tr>
        <tr>
            <td>Total Jenis Barang:</td>
            <td>{{ $totalJenisBarang }}</td>
        </tr>
        <tr>
            <td>Total Jumlah Barang:</td>
            <td>{{ $totalQuantity }} unit</td>
        </tr>
        <tr>
            <td>Rata-rata Barang/Request:</td>
            <td>{{ count($permintaan) > 0 ? round($totalQuantity / count($permintaan), 1) : 0 }} unit</td>
        </tr>
        <tr>
            <td>Permintaan Pending:</td>
            <td>{{ $stats['pending'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>Permintaan Disetujui:</td>
            <td>{{ $stats['approved'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>Permintaan Ditolak:</td>
            <td>{{ $stats['rejected'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>Permintaan Terkirim:</td>
            <td>{{ $stats['delivered'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>Status Laporan:</td>
            <td>
                @if(count($permintaan) > 0)
                    <span style="color: #10b981; font-weight: bold;">DATA TERSEDIA</span>
                @else
                    <span style="color: #ef4444; font-weight: bold;">TIDAK ADA DATA</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>Nama Pemohon:</td>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <td>Tanggal Cetak:</td>
            <td>{{ $printDate }}</td>
        </tr>
    </table>
</div>

</body>
</html>