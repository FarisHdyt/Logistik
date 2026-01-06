<table width="100%" style="font-family: Calibri, Arial, sans-serif; font-size:11pt;">

    {{-- HEADER TANPA BORDER --}}
    <tr>
        <td colspan="8"
            style="text-align:center; font-size:16pt; font-weight:bold; color:#1e40af;">
            SILOG POLRES - SISTEM LOGISTIK KEPOLISIAN
        </td>
    </tr>

    <tr>
        <td colspan="8" style="text-align:center;">
            Tanggal Generate: {{ now()->format('d/m/Y H:i') }}
        </td>
    </tr>

    <tr>
        <td colspan="8" style="text-align:center;">
            Laporan Permintaan Barang - {{ $user->name }}
        </td>
    </tr>

    <tr>
        <td colspan="8" style="text-align:center;">
            Periode: {{ $filters['start_date'] ?? '-' }} s/d {{ $filters['end_date'] ?? '-' }}
        </td>
    </tr>

    {{-- BARIS KOSONG --}}
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>

    {{-- STATISTIK AWAL --}}
    <tr>
        <td colspan="2"><strong>Total Permintaan:</strong></td>
        <td colspan="2">{{ $stats['total'] ?? 0 }}</td>
        <td colspan="2"><strong>Total Barang (Jenis):</strong></td>
        <td colspan="2">{{ $stats['total_items'] ?? 0 }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Permintaan Pending:</strong></td>
        <td colspan="2">{{ $stats['pending'] ?? 0 }}</td>
        <td colspan="2"><strong>Permintaan Disetujui:</strong></td>
        <td colspan="2">{{ $stats['approved'] ?? 0 }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Permintaan Ditolak:</strong></td>
        <td colspan="2">{{ $stats['rejected'] ?? 0 }}</td>
        <td colspan="2"><strong>Permintaan Terkirim:</strong></td>
        <td colspan="2">{{ $stats['delivered'] ?? 0 }}</td>
    </tr>

    {{-- BARIS KOSONG --}}
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>

    {{-- HEADER TABEL --}}
    <tr style="background:#f2f2f2; text-align:center; font-weight:bold;">
        <th style="border:1px solid #000; padding:4px;">No</th>
        <th style="border:1px solid #000; padding:4px;">Kode Permintaan</th>
        <th style="border:1px solid #000; padding:4px;">Barang</th>
        <th style="border:1px solid #000; padding:4px;">Jumlah</th>
        <th style="border:1px solid #000; padding:4px;">Satuan Kerja</th>
        <th style="border:1px solid #000; padding:4px;">Tanggal Permintaan</th>
        <th style="border:1px solid #000; padding:4px;">Tanggal Dibutuhkan</th>
        <th style="border:1px solid #000; padding:4px;">Status</th>
        <th style="border:1px solid #000; padding:4px;">Keterangan</th>
    </tr>

    {{-- DATA --}}
    @php
        $totalQuantity = 0;
    @endphp
    
    @foreach($permintaan as $i => $p)
    @php
        // Cek apakah ini multi barang atau single barang
        $isMultiBarang = isset($p->details) && $p->details->count() > 0;
        $totalJumlah = $isMultiBarang ? $p->details->sum('jumlah') : $p->jumlah;
        $barangCount = $isMultiBarang ? $p->details->count() : 1;
        
        // Hitung total quantity
        $totalQuantity += $totalJumlah;
    @endphp
    <tr>
        <td style="border:1px solid #000; text-align:center; padding:4px;">{{ $i + 1 }}</td>
        <td style="border:1px solid #000; padding:4px;">{{ $p->kode_permintaan }}</td>
        <td style="border:1px solid #000; padding:6px;">
        @if($isMultiBarang)
            <strong>{{ $barangCount }} jenis barang:</strong><br>
            <div style="font-size:10pt; margin-top:5px;">
            @foreach($p->details as $detail)
                <div style="margin:4px 0; padding-left:5px;">
                {{ $detail->barang->nama_barang ?? 'N/A' }}<br>
                <span style="font-size:9pt; color:#666;">
                Kode: {{ $detail->barang->kode_barang ?? '' }} | 
                Jumlah: {{ $detail->jumlah }} {{ $detail->barang->satuan->nama_satuan ?? 'unit' }}
                </span>
                </div>
            @endforeach
            </div>
        @else
            <strong>{{ $p->barang->nama_barang ?? '-' }}</strong><br>
            <span style="font-size:10pt; color:#666;">
            Kode: {{ $p->barang->kode_barang ?? '' }}
            </span>
        @endif
    </td>
        <td style="border:1px solid #000; text-align:center; padding:4px; vertical-align:top;">
            @if($isMultiBarang)
                <strong>{{ $totalJumlah }} unit</strong><br>
                <span style="font-size:10pt; color:#666;">
                ({{ $barangCount }} jenis)
                </span>
            @else
                {{ $p->jumlah }}<br>
                <span style="font-size:10pt; color:#666;">
                {{ $p->barang->satuan->nama_satuan ?? 'unit' }}
                </span>
            @endif
        </td>
        <td style="border:1px solid #000; padding:4px; vertical-align:top;">
            {{ $p->satker->nama_satker ?? '-' }}
        </td>
        <td style="border:1px solid #000; text-align:center; padding:4px; vertical-align:top;">
            {{ $p->created_at->format('d/m/Y H:i') }}
        </td>
        <td style="border:1px solid #000; text-align:center; padding:4px; vertical-align:top;">
            {{ $p->tanggal_dibutuhkan ? \Carbon\Carbon::parse($p->tanggal_dibutuhkan)->format('d/m/Y') : '-' }}
        </td>
        <td style="border:1px solid #000; text-align:center; padding:4px; vertical-align:top;">
            @if($p->status == 'pending')
                <span style="background:#fbbf24; color:#000; padding:2px 6px; border-radius:3px; font-size:9pt; font-weight:bold;">
                PENDING
                </span>
            @elseif($p->status == 'approved')
                <span style="background:#10b981; color:black; padding:2px 6px; border-radius:3px; font-size:9pt; font-weight:bold;">
                DISETUJUI
                </span>
            @elseif($p->status == 'rejected')
                <span style="background:#ef4444; color:black; padding:2px 6px; border-radius:3px; font-size:9pt; font-weight:bold;">
                DITOLAK
                </span>
            @elseif($p->status == 'delivered')
                <span style="background:#3b82f6; color:black; padding:2px 6px; border-radius:3px; font-size:9pt; font-weight:bold;">
                DIKIRIM
                </span>
            @endif
            
            @if($p->alasan_penolakan && $p->status == 'rejected')
            <div style="font-size:9pt; color:#dc2626; margin-top:2px;">
                Alasan: {{ Str::limit($p->alasan_penolakan, 50) }}
            </div>
            @endif
        </td>
        <td style="border:1px solid #000; padding:4px; vertical-align:top;">
            {{ Str::limit($p->keterangan, 100) }}
        </td>
    </tr>
    @endforeach
    
    {{-- Jika tidak ada data --}}
    @if(count($permintaan) == 0)
    <tr>
        <td colspan="9" style="border:1px solid #000; text-align:center; padding:20px;">
            Tidak ada data permintaan untuk periode ini
        </td>
    </tr>
    @endif

    {{-- BARIS KOSONG --}}
    <tr>
        <td colspan="9">&nbsp;</td>
    </tr>

    {{-- RINGKASAN AKHIR --}}
    <tr>
        <td colspan="3" style="border:1px solid #000; background:#f2f2f2; padding:6px; font-weight:bold;">
            RINGKASAN AKHIR
        </td>
        <td colspan="6" style="border:1px solid #000; background:#f2f2f2;"></td>
    </tr>
    <tr>
        <td colspan="3" style="border:1px solid #000; padding:4px;">
            <strong>Total Permintaan:</strong>
        </td>
        <td colspan="2" style="border:1px solid #000; padding:4px;">
            {{ count($permintaan) }}
        </td>
        <td colspan="2" style="border:1px solid #000; padding:4px;">
            <strong>Total Jumlah Barang:</strong>
        </td>
        <td colspan="2" style="border:1px solid #000; padding:4px;">
            {{ $totalQuantity }} unit
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border:1px solid #000; padding:4px;">
            <strong>Total Jenis Barang:</strong>
        </td>
        <td colspan="2" style="border:1px solid #000; padding:4px;">
            {{ $stats['total_items'] ?? 0 }}
        </td>
        <td colspan="2" style="border:1px solid #000; padding:4px;">
            <strong>Rata-rata Barang/Request:</strong>
        </td>
        <td colspan="2" style="border:1px solid #000; padding:4px;">
            @if(count($permintaan) > 0)
                {{ round($totalQuantity / count($permintaan), 1) }} unit
            @else
                0 unit
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border:1px solid #000; padding:4px;">
            <strong>Status Laporan:</strong>
        </td>
        <td colspan="6" style="border:1px solid #000; padding:4px;">
            @if(count($permintaan) > 0)
                <span style="background:#10b981; color:black; padding:2px 8px; border-radius:3px; font-weight:bold;">
                DATA TERSEDIA ({{ count($permintaan) }} permintaan)
                </span>
            @else
                <span style="background:#fbbf24; color:#000; padding:2px 8px; border-radius:3px; font-weight:bold;">
                TIDAK ADA DATA
                </span>
            @endif
        </td>
    </tr>

    {{-- BARIS KOSONG --}}
    <tr>
        <td colspan="9">&nbsp;</td>
    </tr>

    {{-- FOOTER TANPA BORDER --}}
    <tr>
        <td colspan="9" style="text-align:center; font-size:10pt; color:#666;">
            Generated by SILOG Polres - Sistem Logistik<br>
            {{ $user->name }} | {{ date('d/m/Y H:i:s') }}
        </td>
    </tr>

</table>