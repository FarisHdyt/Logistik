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
        
        .table-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        
        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e2e8f0;
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
            @foreach(['total_items' => 'Total Barang', 'total_categories' => 'Total Kategori', 
                    'critical_stock' => 'Stok Kritis', 'out_of_stock' => 'Stok Habis'] as $key => $label)
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
                    ['type' => 'expenditures', 'value' => $stats['total_expenditures'] ?? 0, 'label' => 'Pengeluaran', 'desc' => 'Pengeluaran barang bulan ini']
                ];
            @endphp
            
            @foreach($reportData as $report)
            <div class="report-card">
                <div class="report-icon {{ $report['type'] }}">
                    <i class="bi bi-{{ $report['type'] == 'inventory' ? 'box' : ($report['type'] == 'requests' ? 'clipboard-check' : 'cash-stack') }}"></i>
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
                    <h6 class="mb-3">Distribusi Status Permintaan</h6>
                    <canvas id="requestStatusChart" height="250"></canvas>
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
            
            <!-- Report Summary Table -->
            <div id="reportSummaryTable">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Jenis Laporan</th>
                                <th>Periode</th>
                                <th>Total Data</th>
                                <th>Detail Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $reportTypes = [
                                    ['type' => 'inventory', 'name' => 'Laporan Stok Barang'],
                                    ['type' => 'requests', 'name' => 'Laporan Permintaan'],
                                    ['type' => 'expenditures', 'name' => 'Laporan Pengeluaran']
                                ];
                            @endphp
                            
                            @foreach($reportTypes as $report)
                            <tr>
                                <td>{{ $report['name'] }}</td>
                                <td>{{ date('F Y', strtotime($selectedMonth . '-01')) }}</td>
                                <td id="total_{{ $report['type'] }}_monthly">
                                    {{ $monthlyStats['total_' . $report['type']] ?? ($report['type'] == 'expenditures' ? $monthlyStats['total_expenditures'] : ($monthlyStats['total_' . $report['type'] . 's'] ?? 0)) }} 
                                    {{ $report['type'] == 'inventory' ? 'barang' : ($report['type'] == 'requests' ? 'permintaan' : 'pengeluaran') }}
                                </td>
                                <td id="status_{{ $report['type'] }}_monthly">
                                    @if($report['type'] == 'inventory')
                                    <span class="badge bg-success">{{ $monthlyStats['good_stock'] ?? 0 }} Baik</span>
                                    <span class="badge bg-warning">{{ $monthlyStats['low_stock'] ?? 0 }} Rendah</span>
                                    <span class="badge bg-danger">{{ $monthlyStats['critical_stock'] ?? 0 }} Kritis</span>
                                    <span class="badge bg-secondary">{{ $monthlyStats['out_of_stock'] ?? 0 }} Habis</span>
                                    @elseif($report['type'] == 'requests')
                                    <span class="badge badge-pending">{{ $monthlyStats['pending_requests'] ?? 0 }} Pending</span>
                                    <span class="badge badge-approved">{{ $monthlyStats['approved_requests'] ?? 0 }} Disetujui</span>
                                    <span class="badge badge-rejected">{{ $monthlyStats['rejected_requests'] ?? 0 }} Ditolak</span>
                                    <span class="badge badge-delivered">{{ $monthlyStats['delivered_requests'] ?? 0 }} Terkirim</span>
                                    @else
                                    <span class="badge bg-info">Pengeluaran Barang</span>
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
    $(document).ready(function() {
        initCharts();
        initExportModal();
        setupAjax();
        autoDismissAlerts();
    });
    
    function initCharts() {
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
        
        new Chart(document.getElementById('requestStatusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Disetujui', 'Ditolak', 'Terkirim'],
                datasets: [{
                    data: [
                        {{ $requestStatusData['pending']['count'] ?? 0 }},
                        {{ $requestStatusData['approved']['count'] ?? 0 }},
                        {{ $requestStatusData['rejected']['count'] ?? 0 }},
                        {{ $requestStatusData['delivered']['count'] ?? 0 }}
                    ],
                    backgroundColor: ['#fbbf24', '#10b981', '#ef4444', '#8b5cf6'],
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
        $('#total_inventory_monthly').text(data.total_items + ' barang');
        $('#status_inventory_monthly').html(`
            <span class="badge bg-success">${data.good_stock} Baik</span>
            <span class="badge bg-warning">${data.low_stock} Rendah</span>
            <span class="badge bg-danger">${data.critical_stock} Kritis</span>
            <span class="badge bg-secondary">${data.out_of_stock} Habis</span>
        `);
        
        $('#total_requests_monthly').text(data.total_requests + ' permintaan');
        $('#status_requests_monthly').html(`
            <span class="badge badge-pending">${data.pending_requests} Pending</span>
            <span class="badge badge-approved">${data.approved_requests} Disetujui</span>
            <span class="badge badge-rejected">${data.rejected_requests} Ditolak</span>
            <span class="badge badge-delivered">${data.delivered_requests} Terkirim</span>
        `);
        
        $('#total_expenditures_monthly').text(data.total_expenditures + ' pengeluaran');
    }
    
    function updateButtons(selectedMonth) {
        $('button[onclick*="viewReport("]').attr('onclick', function(i, old) {
            return old.replace(/viewReport\('(inventory|requests|expenditures)'.*?\)/, 
                "viewReport('$1', '" + selectedMonth + "')");
        });
        
        $('button[onclick*="exportReportWithPeriod("]').attr('onclick', function(i, old) {
            return old.replace(/exportReportWithPeriod\('(inventory|requests|expenditures)'.*?\)/, 
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
            <table class="table table-hover">
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
            data: { report_type: type, start_date: startDate, end_date: endDate },
            success: function(response) {
                $('#reportDataBody').html(response);
            },
            error: function(xhr) {
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
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Stok Minimal</th>
                    <th>Satuan</th>
                    <th>Gudang</th>
                    <th>Status</th>
                </tr>`;
                
            case 'requests':
                return `<tr>
                    <th>No</th>
                    <th>Kode Permintaan</th>
                    <th>Tanggal</th>
                    <th>Pemohon</th>
                    <th>Satker</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                </tr>`;
                
            case 'expenditures':
                return `<tr>
                    <th>No</th>
                    <th>Kode Permintaan</th>
                    <th>Tanggal Pengiriman</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Penerima</th>
                    <th>Keperluan</th>
                </tr>`;
                
            default:
                return `<tr><th>Data</th></tr>`;
        }
    }
    
    function getColumnCount(type) {
        switch(type) {
            case 'inventory': return 9;
            case 'requests': return 9;
            case 'expenditures': return 8;
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
            'expenditures': 'Pengeluaran'
        };
        return types[type] || type;
    }
    
    function getMonthName(monthString) {
        if (!monthString) return '';
        const [year, month] = monthString.split('-');
        const date = new Date(year, month - 1, 1);
        return date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
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