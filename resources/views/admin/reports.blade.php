<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan | SILOG Polres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #dc2626;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #0ea5e9;
            --dark: #1e293b;
            --light: #f8fafc;
            --delivered-color: #8b5cf6;
            --sidebar-width: 250px;
            --procurement-color: #10b981;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f1f5f9;
            color: var(--dark);
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--dark) 0%, #0f172a 100%);
            color: white;
            min-height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            margin-bottom: 0.5rem;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.8rem 1.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid var(--delivered-color);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: 100vh;
        }
        
        .topbar {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .page-header {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .report-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .report-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid var(--primary);
        }
        
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        .report-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: white;
        }
        
        .report-icon.inventory { background-color: var(--primary); }
        .report-icon.requests { background-color: var(--warning); }
        .report-icon.expenditures { background-color: var(--info); }
        .report-icon.procurement { background-color: var(--procurement-color); }
        
        .charts-container {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid rgba(0,0,0,0.1);
        }
        
        .badge-pending {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border-color: #fbbf24;
        }
        
        .badge-approved {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border-color: #10b981;
        }
        
        .badge-rejected {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border-color: #ef4444;
        }
        
        .badge-delivered {
            background-color: #ede9fe !important;
            color: #5b21b6 !important;
            border-color: #8b5cf6;
        }
        
        .badge-processing {
            background-color: #dbeafe !important;
            color: #1e40af !important;
            border-color: #60a5fa;
        }
        
        .badge-completed {
            background-color: #10b981 !important;
            color: white !important;
            border-color: #059669;
        }
        
        .badge-cancelled {
            background-color: #f87171 !important;
            color: #7f1d1d !important;
            border-color: #dc2626;
        }
        
        .badge-multi {
            background-color: #8b5cf6 !important;
            color: white !important;
            border-color: #7c3aed;
        }
        
        .badge-single {
            background-color: #6b7280 !important;
            color: white !important;
            border-color: #4b5563;
        }
        
        .badge-new-item {
            background-color: #0ea5e9 !important;
            color: white !important;
            border-color: #0284c7;
        }
        
        .badge-restock {
            background-color: #8b5cf6 !important;
            color: white !important;
            border-color: #7c3aed;
        }
        
        .badge-priority-normal {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border-color: #10b981;
        }
        
        .badge-priority-tinggi {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border-color: #fbbf24;
        }
        
        .badge-priority-mendesak {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border-color: #ef4444;
        }
        
        .table-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        
        /* PERBAIKAN TABLE STYLES */
        .table-report {
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        .table-report th {
            background-color: #f8fafc !important;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 0.75rem;
        }
        
        .table-report td {
            padding: 0.75rem;
            vertical-align: middle;
            border-color: #f1f5f9;
        }
        
        /* Kolom dengan teks panjang */
        .table-report td.text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Kolom angka - rata kanan */
        .table-report td.text-number {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }
        
        /* Kolom tengah */
        .table-report td.text-center {
            text-align: center;
        }
        
        /* Kolom kode */
        .table-report td.code-cell {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: var(--primary);
        }
        
        /* Kolom tanggal */
        .table-report td.date-cell {
            white-space: nowrap;
            min-width: 120px;
        }
        
        /* Kolom aksi */
        .table-report td.action-cell {
            min-width: 100px;
            white-space: nowrap;
        }
        
        /* Badge di dalam tabel */
        .table-report .badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
        }
        
        /* Responsive table */
        @media (max-width: 768px) {
            .table-report {
                font-size: 0.8rem;
            }
            
            .table-report th,
            .table-report td {
                padding: 0.5rem !important;
            }
            
            .table-report .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
        
        /* Zebra striping untuk readability */
        .table-report tbody tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .table-report tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        
        .barang-detail-item {
            padding: 0.25rem 0;
            border-bottom: 1px solid #f1f1f1;
            font-size: 0.85rem;
        }
        
        .barang-detail-item:last-child {
            border-bottom: none;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar-brand h3, .sidebar-brand p, .nav-link span {
                display: none;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .nav-link {
                justify-content: center;
                padding: 0.8rem;
            }
            
            .report-cards {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* Progress Bar for Procurement Status */
        .procurement-progress {
            height: 6px;
            background-color: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 5px;
        }
        
        .procurement-progress-bar {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        .progress-pending { background-color: #fbbf24; }
        .progress-approved { background-color: #10b981; }
        .progress-processing { background-color: #60a5fa; }
        .progress-completed { background-color: #8b5cf6; }
        .progress-cancelled { background-color: #ef4444; }
        .progress-rejected { background-color: #dc2626; }
        
        /* Budget Indicator */
        .budget-indicator {
            font-size: 0.8rem;
            padding: 2px 6px;
            border-radius: 3px;
            background-color: #f3f4f6;
            color: #6b7280;
        }
        
        .budget-high {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .budget-medium {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .budget-low {
            background-color: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h3>SILOG</h3>
            <p>Manajemen Laporan</p>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.inventory') }}" class="nav-link">
                    <i class="bi bi-box-seam"></i>
                    <span>Manajemen Barang</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.procurement') }}" class="nav-link">
                    <i class="bi bi-cart-plus"></i>
                    <span>Pengadaan Barang</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.requests') }}" class="nav-link">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Permintaan Barang</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.reports') }}" class="nav-link active">
                    <i class="bi bi-file-text"></i>
                    <span>Laporan</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-footer" style="padding: 1.5rem; position: absolute; bottom: 0; width: 100%;">
            <div class="text-center">
                <small style="opacity: 0.7;">Sistem Logistik Polres</small><br>
                <small style="opacity: 0.5;">v1.0.0</small>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <h4 class="mb-0">Laporan Sistem</h4>
            <div class="user-info">
                <div class="user-avatar">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <strong>{{ $user->name }}</strong><br>
                    <small class="text-muted">{{ $user->role }}</small>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert-container">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert-container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif
        
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Laporan Sistem SILOG</h5>
                    <p class="text-muted mb-0">Generate dan ekspor berbagai laporan sistem</p>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                        <i class="bi bi-file-earmark-text"></i> Generate Laporan
                    </button>
                    <button class="btn btn-warning btn-action" onclick="printReport()">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            @foreach([
                'total_items' => 'Total Barang', 
                'critical_stock' => 'Stok Kritis', 
                'out_of_stock' => 'Stok Habis',
                'total_procurements' => 'Total Pengadaan',
                'pending_procurements' => 'Pengadaan Pending'
            ] as $key => $label)
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats[$key] ?? 0 }}</h5>
                    <p>{{ $label }}</p>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Report Cards -->
        <div class="report-cards">
            @php
                $reportData = [
                    ['type' => 'inventory', 'value' => $stats['total_items'] ?? 0, 'label' => 'Total Barang', 'desc' => 'Data stok barang saat ini'],
                    ['type' => 'requests', 'value' => $stats['total_requests'] ?? 0, 'label' => 'Permintaan Barang', 'desc' => 'Total permintaan bulan ini'],
                    ['type' => 'expenditures', 'value' => $stats['total_expenditures'] ?? 0, 'label' => 'Pengeluaran', 'desc' => 'Pengeluaran barang bulan ini'],
                    ['type' => 'procurement', 'value' => $stats['total_procurements'] ?? 0, 'label' => 'Pengadaan', 'desc' => 'Total pengadaan bulan ini']
                ];
            @endphp
            
            @foreach($reportData as $report)
            <div class="report-card">
                <div class="report-icon {{ $report['type'] }}">
                    <i class="bi bi-{{ $report['type'] == 'inventory' ? 'box' : ($report['type'] == 'requests' ? 'clipboard-check' : ($report['type'] == 'procurement' ? 'cart-plus' : 'cash-stack')) }}"></i>
                </div>
                <div class="report-content">
                    <h5>{{ $report['value'] }}</h5>
                    <p>{{ $report['label'] }}</p>
                    <small class="text-muted">{{ $report['desc'] }}</small>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Charts Container -->
        <div class="charts-container">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-3">Statistik Permintaan Bulanan</h6>
                    <canvas id="monthlyRequestsChart" height="250"></canvas>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-3">Distribusi Status Pengadaan</h6>
                    <canvas id="procurementStatusChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Period Selection -->
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Ringkasan Laporan</h5>
                <div class="d-flex gap-2 align-items-center">
                    <label for="month" class="form-label mb-0">Pilih Periode:</label>
                    <select class="form-select w-auto" id="month" name="month">
                        @php
                            $currentYear = date('Y');
                            $currentMonth = date('m');
                            $selectedMonth = request('month', date('Y-m'));
                        @endphp
                        @for($i = 0; $i < 12; $i++)
                            @php
                                $date = date('Y-m', strtotime("-$i months"));
                                $monthName = date('F Y', strtotime("-$i months"));
                            @endphp
                            <option value="{{ $date }}" {{ $selectedMonth == $date ? 'selected' : '' }}>
                                {{ $monthName }}
                            </option>
                        @endfor
                    </select>
                    <button class="btn btn-primary" onclick="updateReportSummary()">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                </div>
            </div>
            
            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat data...</p>
            </div>
            
            <!-- Report Summary Table dengan Kolom Rapi -->
            <div id="reportSummaryTable">
                <div class="table-responsive">
                    <table class="table table-report table-hover">
                        <thead>
                            <tr>
                                <th>Jenis Laporan</th>
                                <th>Periode</th>
                                <th>Total Data</th>
                                <th>Detail Status</th>
                                <th>Total Anggaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Helper function untuk menentukan level budget
                                function getBudgetLevel($amount) {
                                    if ($amount > 100000000) return 'budget-high';
                                    if ($amount > 50000000) return 'budget-medium';
                                    return 'budget-low';
                                }
                                
                                $reportTypes = [
                                    ['type' => 'inventory', 'name' => 'Laporan Stok Barang'],
                                    ['type' => 'requests', 'name' => 'Laporan Permintaan'],
                                    ['type' => 'expenditures', 'name' => 'Laporan Pengeluaran'],
                                    ['type' => 'procurement', 'name' => 'Laporan Pengadaan']
                                ];
                            @endphp
                            
                            @foreach($reportTypes as $index => $report)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi {{ $report['icon'] }} text-primary"></i>
                                        <span>{{ $report['name'] }}</span>
                                    </div>
                                </td>
                                <td class="text-center date-cell">
                                    {{ date('F Y', strtotime($selectedMonth . '-01')) }}
                                </td>
                                <td class="text-center" id="total_{{ $report['type'] }}_monthly">
                                    @if($report['type'] == 'inventory')
                                        <span class="fw-bold text-primary">{{ $monthlyStats['total_items'] ?? 0 }}</span> barang
                                    @elseif($report['type'] == 'requests')
                                        {{ $monthlyStats['total_requests'] ?? 0 }} permintaan
                                        <br><small class="text-muted">
                                            {{ $monthlyStats['total_items_in_requests'] ?? 0 }} item barang
                                        </small>
                                    @elseif($report['type'] == 'procurement')
                                        {{ $monthlyStats['total_procurements'] ?? 0 }} pengadaan
                                        <br><small class="text-muted">
                                            {{ $monthlyStats['total_items_in_procurements'] ?? 0 }} item barang
                                        </small>
                                    @else
                                        <div>
                                            <span class="fw-bold text-primary">{{ $monthlyStats['total_expenditures'] ?? 0 }}</span> pengeluaran
                                        </div>
                                        <div class="text-muted small">
                                            <span class="fw-bold">{{ $monthlyStats['total_items_in_expenditures'] ?? 0 }}</span> item terkirim
                                        </div>
                                    @endif
                                </td>
                                <td id="status_{{ $report['type'] }}_monthly">
                                    @if($report['type'] == 'inventory')
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-success">{{ $monthlyStats['good_stock'] ?? 0 }} Baik</span>
                                        <span class="badge bg-warning">{{ $monthlyStats['low_stock'] ?? 0 }} Rendah</span>
                                        <span class="badge bg-danger">{{ $monthlyStats['critical_stock'] ?? 0 }} Kritis</span>
                                        <span class="badge bg-secondary">{{ $monthlyStats['out_of_stock'] ?? 0 }} Habis</span>
                                    </div>
                                    @elseif($report['type'] == 'requests')
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge badge-pending">{{ $monthlyStats['pending_requests'] ?? 0 }}</span>
                                        <span class="badge badge-approved">{{ $monthlyStats['approved_requests'] ?? 0 }}</span>
                                        <span class="badge badge-rejected">{{ $monthlyStats['rejected_requests'] ?? 0 }}</span>
                                        <span class="badge badge-delivered">{{ $monthlyStats['delivered_requests'] ?? 0 }}</span>
                                    </div>
                                    @if(isset($monthlyStats['multi_barang_requests']) && $monthlyStats['multi_barang_requests'] > 0)
                                    <div class="d-flex gap-1 mt-1">
                                        <span class="badge badge-multi">
                                            <i class="bi bi-layers me-1"></i>{{ $monthlyStats['multi_barang_requests'] ?? 0 }}
                                        </span>
                                        <span class="badge badge-single">
                                            <i class="bi bi-box me-1"></i>{{ $monthlyStats['single_barang_requests'] ?? 0 }}
                                        </span>
                                    </div>
                                    @endif
                                    @elseif($report['type'] == 'procurement')
                                    <span class="badge badge-pending">{{ $monthlyStats['pending_procurements'] ?? 0 }} Pending</span>
                                    <span class="badge badge-approved">{{ $monthlyStats['approved_procurements'] ?? 0 }} Disetujui</span>
                                    <span class="badge badge-rejected">{{ $monthlyStats['rejected_procurements'] ?? 0 }} Ditolak</span>
                                    <span class="badge badge-processing">{{ $monthlyStats['processing_procurements'] ?? 0 }} Diproses</span>
                                    <span class="badge badge-completed">{{ $monthlyStats['completed_procurements'] ?? 0 }} Selesai</span>
                                    <span class="badge badge-cancelled">{{ $monthlyStats['cancelled_procurements'] ?? 0 }} Dibatalkan</span>
                                    <br>
                                    <span class="badge badge-new-item mt-1">
                                        {{ $monthlyStats['new_item_procurements'] ?? 0 }} Barang Baru
                                    </span>
                                    <span class="badge badge-restock mt-1">
                                        {{ $monthlyStats['restock_procurements'] ?? 0 }} Restock
                                    </span>
                                    @else
                                    <span class="badge bg-info">
                                        <i class="bi bi-arrow-up-right me-1"></i>Pengeluaran Barang
                                    </span>
                                    @endif
                                </td>
                                <td id="budget_{{ $report['type'] }}_monthly">
                                    @if($report['type'] == 'procurement')
                                    @php
                                        $budgetAmount = $monthlyStats['total_budget_procurements'] ?? 0;
                                        $budgetClass = getBudgetLevel($budgetAmount);
                                    @endphp
                                    <span class="budget-indicator {{ $budgetClass }}">
                                        Rp {{ number_format($budgetAmount, 0, ',', '.') }}
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        Rp {{ number_format($monthlyStats['avg_budget_per_procurement'] ?? 0, 0, ',', '.') }} / pengadaan
                                    </small>
                                    @elseif($report['type'] == 'expenditures')
                                    <span class="budget-indicator budget-high">
                                        Rp {{ number_format($monthlyStats['total_expenditures_value'] ?? 0, 0, ',', '.') }}
                                    </span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewReport('{{ $report['type'] }}', '{{ $selectedMonth }}')" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" onclick="exportReportWithPeriod('{{ $report['type'] }}', '{{ $selectedMonth }}')" title="Export">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Report Details Table -->
        <div class="table-card" id="reportDetails" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0" id="reportTitle">Detail Laporan</h5>
                <button class="btn btn-sm btn-secondary" onclick="hideReportDetails()">
                    <i class="bi bi-x"></i> Tutup
                </button>
            </div>
            <div class="table-responsive" id="reportTable"></div>
        </div>
    </div>
    
    <!-- Generate Report Modal -->
    <div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateReportModalLabel">Generate Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="export_type" class="form-label">Jenis Laporan *</label>
                        <select class="form-select" id="export_type" name="type" required>
                            <option value="">Pilih Jenis Laporan</option>
                            <option value="inventory">Laporan Stok Barang</option>
                            <option value="requests">Laporan Permintaan</option>
                            <option value="expenditures">Laporan Pengeluaran</option>
                            <option value="procurement">Laporan Pengadaan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="export_format" class="form-label">Format Export *</label>
                        <select class="form-select" id="export_format" name="format" required>
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="export_period" class="form-label">Periode Bulan</label>
                        <select class="form-select" id="export_period" name="period">
                            <option value="">Pilih Periode</option>
                            @for($i = 0; $i < 12; $i++)
                                @php
                                    $date = date('Y-m', strtotime("-$i months"));
                                    $monthName = date('F Y', strtotime("-$i months"));
                                @endphp
                                <option value="{{ $date }}">{{ $monthName }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="export_start_date" class="form-label">Dari Tanggal</label>
                                <input type="date" class="form-control" id="export_start_date" name="start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="export_end_date" class="form-label">Sampai Tanggal</label>
                                <input type="date" class="form-control" id="export_end_date" name="end_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="downloadReport()">
                        <i class="bi bi-download me-1"></i> Generate & Download
                    </button>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    // Variabel global untuk menyimpan detail barang
    let detailData = {};
    
    $(document).ready(function() {
        initCharts();
        initExportModal();
        setupAjax();
        autoDismissAlerts();
    });
    
    function initCharts() {
        // Monthly Requests Chart
        new Chart(document.getElementById('monthlyRequestsChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyRequestsData['labels'] ?? []) !!},
                datasets: [{
                    label: 'Jumlah Permintaan',
                    data: {!! json_encode($monthlyRequestsData['data'] ?? []) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
        
        // Procurement Status Chart
        new Chart(document.getElementById('procurementStatusChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Pending', 'Disetujui', 'Diproses', 'Selesai', 'Dibatalkan', 'Ditolak'],
                datasets: [{
                    data: [
                        {{ $procurementStatusData['pending']['count'] ?? 0 }},
                        {{ $procurementStatusData['approved']['count'] ?? 0 }},
                        {{ $procurementStatusData['processing']['count'] ?? 0 }},
                        {{ $procurementStatusData['completed']['count'] ?? 0 }},
                        {{ $procurementStatusData['cancelled']['count'] ?? 0 }},
                        {{ $procurementStatusData['rejected']['count'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#fbbf24', // Pending - yellow
                        '#10b981', // Approved - green
                        '#60a5fa', // Processing - blue
                        '#8b5cf6', // Completed - purple
                        '#f87171', // Cancelled - red
                        '#dc2626'  // Rejected - dark red
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    function initExportModal() {
        const selectedMonth = $('#month').val();
        const startDate = selectedMonth + '-01';
        const endDate = getMonthEndDate(selectedMonth);
        
        $('#export_start_date').val(startDate);
        $('#export_end_date').val(endDate);
        $('#export_period').val(selectedMonth);
        
        $('#export_period').on('change', function() {
            const period = $(this).val();
            if (period) {
                $('#export_start_date').val(period + '-01');
                $('#export_end_date').val(getMonthEndDate(period));
            }
        });
    }
    
    function setupAjax() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }
    
    function autoDismissAlerts() {
        setTimeout(() => $('.alert').alert('close'), 5000);
    }
    
    function getMonthEndDate(monthString) {
        const [year, month] = monthString.split('-');
        const lastDay = new Date(year, month, 0).getDate();
        return monthString + '-' + lastDay.toString().padStart(2, '0');
    }
    
    function updateReportSummary() {
        const selectedMonth = $('#month').val();
        
        $('#loadingIndicator').show();
        $('#reportSummaryTable').hide();
        
        $.ajax({
            url: '{{ route("admin.reports.get-monthly-stats") }}',
            type: 'GET',
            data: { month: selectedMonth },
            success: function(response) {
                updateStats(response);
                updateButtons(selectedMonth);
                $('#loadingIndicator').hide();
                $('#reportSummaryTable').show();
            },
            error: function(xhr) {
                console.error('Error updating report summary:', xhr);
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                $('#loadingIndicator').hide();
                $('#reportSummaryTable').show();
            }
        });
    }
    
    function updateStats(data) {
        // Update inventory stats
        $('#total_inventory_monthly').text(data.total_items + ' barang');
        $('#status_inventory_monthly').html(`
            <div class="d-flex flex-wrap gap-1">
                <span class="badge bg-success">${data.good_stock} Baik</span>
                <span class="badge bg-warning">${data.low_stock} Rendah</span>
                <span class="badge bg-danger">${data.critical_stock} Kritis</span>
                <span class="badge bg-secondary">${data.out_of_stock} Habis</span>
            </div>
        `);
        
        // Update requests stats
        $('#total_requests_monthly').html(`
            <div>
                <span class="fw-bold text-primary">${data.total_requests}</span> permintaan
            </div>
            <div class="text-muted small">
                <span class="fw-bold">${data.total_items_in_requests}</span> item barang
            </div>
        `);
        
        // Update requests stats
        $('#total_requests_monthly').html(`${data.total_requests} permintaan<br>
            <small class="text-muted">${data.total_items_in_requests} item barang</small>`);
        $('#status_requests_monthly').html(`
            <div class="d-flex flex-wrap gap-1">
                <span class="badge badge-pending">${data.pending_requests}</span>
                <span class="badge badge-approved">${data.approved_requests}</span>
                <span class="badge badge-rejected">${data.rejected_requests}</span>
                <span class="badge badge-delivered">${data.delivered_requests}</span>
            </div>
            ${data.multi_barang_requests > 0 ? `
            <div class="d-flex gap-1 mt-1">
                <span class="badge badge-multi">
                    <i class="bi bi-layers me-1"></i>${data.multi_barang_requests}
                </span>
                <span class="badge badge-single">
                    <i class="bi bi-box me-1"></i>${data.single_barang_requests}
                </span>
            </div>` : ''}
        `);
        
        // Update procurement stats
        $('#total_procurement_monthly').html(`${data.total_procurements} pengadaan<br>
            <small class="text-muted">${data.total_items_in_procurements} item barang</small>`);
        $('#status_procurement_monthly').html(`
            <span class="badge badge-pending">${data.pending_procurements} Pending</span>
            <span class="badge badge-approved">${data.approved_procurements} Disetujui</span>
            <span class="badge badge-rejected">${data.rejected_procurements} Ditolak</span>
            <span class="badge badge-processing">${data.processing_procurements} Diproses</span>
            <span class="badge badge-completed">${data.completed_procurements} Selesai</span>
            <span class="badge badge-cancelled">${data.cancelled_procurements} Dibatalkan</span>
            <br>
            <span class="badge badge-new-item mt-1">
                ${data.new_item_procurements} Barang Baru
            </span>
            <span class="badge badge-restock mt-1">
                ${data.restock_procurements} Restock
            </span>
        `);
        
        // Update procurement budget
        const budgetClass = getBudgetLevelClass(data.total_budget_procurements || 0);
        $('#budget_procurement_monthly').html(`
            <span class="budget-indicator ${budgetClass}">
                Rp ${formatCurrency(data.total_budget_procurements || 0)}
            </span>
            <br>
            <small class="text-muted">
                Rp ${formatCurrency(data.avg_budget_per_procurement || 0)} / pengadaan
            </small>
        `);
        
        // Update expenditures stats
        $('#total_expenditures_monthly').html(`${data.total_expenditures} pengeluaran<br>
            <small class="text-muted">${data.total_items_in_expenditures} item terkirim</small>`);
        $('#budget_expenditures_monthly').html(`
            <span class="budget-indicator budget-high">
                Rp ${formatCurrency(data.total_expenditures_value || 0)}
            </span>
        `);
    }
    
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID').format(amount);
    }
    
    function getBudgetLevelClass(amount) {
        if (amount > 100000000) return 'budget-high';
        if (amount > 50000000) return 'budget-medium';
        return 'budget-low';
        // Update expenditures stats
        $('#total_expenditures_monthly').html(`
            <div>
                <span class="fw-bold text-primary">${data.total_expenditures}</span> pengeluaran
            </div>
            <div class="text-muted small">
                <span class="fw-bold">${data.total_items_in_expenditures}</span> item terkirim
            </div>
        `);
    }
    
    function updateButtons(selectedMonth) {
        $('button[onclick*="viewReport("]').attr('onclick', function(i, old) {
            return old.replace(/viewReport\('(inventory|requests|expenditures|procurement)'.*?\)/, 
                "viewReport('$1', '" + selectedMonth + "')");
        });
        
        $('button[onclick*="exportReportWithPeriod("]').attr('onclick', function(i, old) {
            return old.replace(/exportReportWithPeriod\('(inventory|requests|expenditures|procurement)'.*?\)/, 
                "exportReportWithPeriod('$1', '" + selectedMonth + "')");
        });
    }
    
    function printReport() {
        const elementsToHide = document.querySelectorAll('.sidebar, .topbar, .action-buttons, .btn-group');
        const tableCard = document.querySelector('.table-card');
        const originalStyles = {
            boxShadow: tableCard.style.boxShadow,
            padding: tableCard.style.padding
        };
        
        elementsToHide.forEach(el => el.style.display = 'none');
        tableCard.style.boxShadow = 'none';
        tableCard.style.padding = '0';
        
        const printTitle = document.createElement('h4');
        printTitle.textContent = 'Laporan Sistem SILOG Polres';
        printTitle.style.cssText = 'text-align: center; margin-bottom: 20px; font-weight: bold;';
        
        const printDate = document.createElement('p');
        printDate.textContent = 'Tanggal: ' + new Date().toLocaleDateString('id-ID');
        printDate.style.cssText = 'text-align: center; margin-bottom: 20px; color: #666;';
        
        tableCard.parentNode.insertBefore(printTitle, tableCard);
        printTitle.parentNode.insertBefore(printDate, printTitle.nextSibling);
        
        window.print();
        
        setTimeout(() => {
            elementsToHide.forEach(el => el.style.display = '');
            tableCard.style.boxShadow = originalStyles.boxShadow;
            tableCard.style.padding = originalStyles.padding;
            printTitle.parentNode?.removeChild(printTitle);
            printDate.parentNode?.removeChild(printDate);
        }, 500);
    }
    
    function viewReport(type, month = null) {
        const selectedMonth = month || $('#month').val();
        const monthName = getMonthName(selectedMonth);
        
        $('#reportTitle').text(`Detail Laporan ${getReportTypeName(type)} - ${monthName}`);
        
        // Reset detailData
        detailData = {};
        
        // Panggil fungsi untuk memuat data
        loadReportData(type, selectedMonth);
        
        $('#reportDetails').show().scrollIntoView({ behavior: 'smooth' });
    }
    
    function loadReportData(type, month) {
        const startDate = month + '-01';
        const endDate = getMonthEndDate(month);
        
        // Buat struktur tabel yang lengkap dengan loading state
        const tableHeaders = getTableHeaders(type);
        const columnCount = getColumnCount(type);
        
        const tableStructure = `
            <table class="table table-report table-hover">
                <thead>
                    ${tableHeaders}
                </thead>
                <tbody id="reportDataBody">
                    <tr>
                        <td colspan="${columnCount}" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        `;
        
        $('#reportTable').html(tableStructure);
        
        $.ajax({
            url: '{{ route("admin.reports.view-details") }}',
            type: 'GET',
            data: { 
                report_type: type, 
                start_date: startDate, 
                end_date: endDate 
            },
            success: function(response) {
                $('#reportDataBody').html(response);
            },
            error: function(xhr) {
                console.error('Error loading report data:', xhr);
                $('#reportDataBody').html(`
                    <tr>
                        <td colspan="${columnCount}" class="text-center py-4">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Terjadi kesalahan saat memuat data.
                            </div>
                        </td>
                    </tr>
                `);
            }
        });
    }
    
    function getTableHeaders(type) {
        switch(type) {
            case 'inventory':
                return `<tr>
                    <th class="text-center">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Stok Minimal</th>
                    <th>Satuan</th>
                    <th>Gudang</th>
                    <th class="text-center">Status</th>
                </tr>`;
                
            case 'requests':
                return `<tr>
                    <th class="text-center">No</th>
                    <th>Kode Permintaan</th>
                    <th>Tanggal</th>
                    <th>Pemohon</th>
                    <th>Satker</th>
                    <th class="text-center">Jenis Permintaan</th>
                    <th class="text-center">Jumlah Barang</th>
                    <th class="text-center">Total Item</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Detail</th>
                </tr>`;
                
            case 'expenditures':
                return `<tr>
                    <th class="text-center">No</th>
                    <th>Kode Permintaan</th>
                    <th>Tanggal Pengiriman</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-center">Jumlah Barang</th>
                    <th class="text-center">Total Item</th>
                    <th>Penerima</th>
                    <th>Keperluan</th>
                </tr>`;
                
            case 'procurement':
                return `<tr>
                    <th class="text-center">No</th>
                    <th>Kode/Nama Barang</th>
                    <th>Tipe Pengadaan</th>
                    <th class="text-center">Jumlah</th>
                    <th>Harga Perkiraan</th>
                    <th class="text-center">Total</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Diajukan Oleh</th>
                    <th>Tanggal</th>
                    <th class="text-center">Progress</th>
                    <th class="text-center">Aksi</th>
                </tr>`;
                
            default:
                return `<tr><th>Data</th></tr>`;
        }
    }
    
    function getColumnCount(type) {
        switch(type) {
            case 'inventory': return 9;
            case 'requests': return 10;
            case 'expenditures': return 8;
            case 'procurement': return 12;
            default: return 1;
        }
    }
    
    function hideReportDetails() {
        $('#reportDetails').hide();
    }
    
    function exportReportWithPeriod(type, month) {
        $('#export_type').val(type);
        $('#export_period').val(month);
        $('#export_start_date').val(month + '-01');
        $('#export_end_date').val(getMonthEndDate(month));
        $('#generateReportModal').modal('show');
    }
    
    function downloadReport() {
        const type = $('#export_type').val();
        const format = $('#export_format').val();
        const period = $('#export_period').val();
        const startDate = $('#export_start_date').val();
        const endDate = $('#export_end_date').val();
        
        if (!type) {
            alert('Pilih jenis laporan terlebih dahulu!');
            return;
        }
        
        let url = '{{ route("admin.reports.export", ["type" => ":type"]) }}'.replace(':type', type);
        url += '?format=' + format;
        
        if (period) {
            url += '&start_date=' + period + '-01&end_date=' + getMonthEndDate(period);
        } else if (startDate && endDate) {
            url += '&start_date=' + startDate + '&end_date=' + endDate;
        }
        
        window.location.href = url;
        $('#generateReportModal').modal('hide');
    }
    
    function getReportTypeName(type) {
        const types = {
            'inventory': 'Stok Barang',
            'requests': 'Permintaan Barang',
            'expenditures': 'Pengeluaran',
            'procurement': 'Pengadaan Barang'
        };
        return types[type] || type;
    }
    
    function getMonthName(monthString) {
        if (!monthString) return '';
        const [year, month] = monthString.split('-');
        const date = new Date(year, month - 1, 1);
        return date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
    }
    
    // Fungsi untuk menampilkan detail barang dalam modal
    function showBarangDetails(requestId) {
        const detailHtml = detailData[requestId];
        
        if (!detailHtml) {
            alert('Detail barang tidak tersedia');
            return;
        }
        
        // Buat modal untuk menampilkan detail
        const modalHtml = `
            <div class="modal fade" id="barangDetailModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-box-seam me-2"></i>Detail Barang
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ${detailHtml}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" onclick="printDetailBarang()">
                                <i class="bi bi-printer me-1"></i> Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Hapus modal sebelumnya jika ada
        const existingModal = document.getElementById('barangDetailModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Tambahkan modal ke body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Tampilkan modal
        const modal = new bootstrap.Modal(document.getElementById('barangDetailModal'));
        modal.show();
    }
    
    // Fungsi untuk mencetak detail barang
    function printDetailBarang() {
        const modalContent = document.querySelector('#barangDetailModal .modal-body');
        const printWindow = window.open('', '_blank');
        
        printWindow.document.open();
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Detail Barang - SILOG Polres</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                    th { background-color: #f8f9fa; padding: 10px; border: 1px solid #ddd; }
                    td { padding: 10px; border: 1px solid #ddd; }
                    .text-center { text-align: center; }
                    @media print { body { margin: 0; } }
                </style>
            </head>
            <body>
                <h3>Detail Barang</h3>
                <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                <hr>
                ${modalContent.innerHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
    
    // Logout confirmation
    document.querySelector('form[action="{{ route("logout") }}"]').addEventListener('submit', function(e) {
        if (!confirm('Apakah Anda yakin ingin logout?')) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>