<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Permintaan - SILOG Polres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
        }
        
        .sidebar {
            background: var(--primary);
            color: white;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            padding: 0;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .dashboard-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            transition: transform 0.3s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .card-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        /* Sidebar Styling */
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-nav {
            padding: 0.5rem 0;
        }
        
        .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .nav-link {
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 0;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }
        
        .nav-link.active {
            background-color: rgba(59, 130, 246, 0.15);
            color: white;
            border-left-color: var(--primary-light);
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.1);
        }
        
        /* Laporan Specific */
        .stat-card {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            color: white;
        }
        
        .stat-card.total { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
        }
        .stat-card.pending { 
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); 
        }
        .stat-card.approved { 
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); 
        }
        .stat-card.rejected { 
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%); 
        }
        .stat-card.delivered { 
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); 
        }
        .stat-card.items { 
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); 
        }
        
        .table-actions {
            white-space: nowrap;
        }
        
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.875em;
        }
        
        /* Charts */
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-footer {
                position: relative;
            }
            
            .stat-card {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="d-flex flex-column h-100">
            <!-- Sidebar Brand -->
            <div class="sidebar-brand">
                <h3 class="mb-1 fw-bold">SILOG POLRES</h3>
                <p class="mb-0 text-white-50" style="font-size: 0.875rem;">User Dashboard</p>
            </div>
            
            <!-- Sidebar Navigation -->
            <div class="sidebar-nav flex-grow-1">
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('user.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('user.permintaan') }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Permintaan Barang</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center active" href="{{ route('user.laporan') }}">
                        <i class="bi bi-file-text"></i>
                        <span>Laporan</span>
                    </a>
                </div>
            </div>
            
            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="text-center text-white-50">
                    <small style="opacity: 0.7;">Sistem Logistik Polres</small><br>
                    <small style="opacity: 0.5; font-size: 0.75rem;">v1.0.0</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-custom">
            <div class="container-fluid">
                <span class="navbar-brand">
                    Laporan Permintaan Saya
                </span>
                <div class="d-flex align-items-center">
                    <span class="me-3">Selamat datang, {{ Auth::user()->name }}!</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        
        <!-- Content -->
        <div class="container-fluid mt-4">
            <!-- Session Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-2 col-6 mb-3">
                    <div class="stat-card total text-center">
                        <h5>{{ $stats['total'] ?? 0 }}</h5>
                        <small>Total Permintaan</small>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="stat-card pending text-center">
                        <h5>{{ $stats['pending'] ?? 0 }}</h5>
                        <small>Pending</small>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="stat-card approved text-center">
                        <h5>{{ $stats['approved'] ?? 0 }}</h5>
                        <small>Disetujui</small>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="stat-card rejected text-center">
                        <h5>{{ $stats['rejected'] ?? 0 }}</h5>
                        <small>Ditolak</small>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="stat-card delivered text-center">
                        <h5>{{ $stats['delivered'] ?? 0 }}</h5>
                        <small>Terkirim</small>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="stat-card items text-center">
                        <h5>{{ $stats['total_items'] ?? 0 }}</h5>
                        <small>Jenis Barang</small>
                    </div>
                </div>
            </div>
            
            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Filter Laporan</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm" onclick="exportToPDF()">
                            <i class="bi bi-file-pdf me-1"></i>Export PDF
                        </button>
                        <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                            <i class="bi bi-file-excel me-1"></i>Export Excel
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i>Print
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('user.laporan') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Dikirim</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date', date('Y-m-01')) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date', date('Y-m-t')) }}">
                            </div>
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-funnel me-1"></i>Filter
                                </button>
                                @if(request()->anyFilled(['status', 'start_date', 'end_date']))
                                <a href="{{ route('user.laporan') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Charts -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h6>Permintaan Bulanan</h6>
                        <canvas id="monthlyChart" height="200"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h6>Status Permintaan</h6>
                        <canvas id="statusChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Laporan Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Permintaan Barang Saya</h5>
                </div>
                <div class="card-body">
                    @if($permintaan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                @foreach($permintaan as $item)
                                <tr>
                                    <td>{{ $loop->iteration + (($permintaan->currentPage() - 1) * $permintaan->perPage()) }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $item->kode_permintaan }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $item->barang->nama_barang ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $item->barang->kode_barang ?? '' }}</small>
                                    </td>
                                    <td>{{ $item->jumlah }} {{ $item->barang->satuan->nama_satuan ?? 'unit' }}</td>
                                    <td>{{ $item->satker->nama_satker ?? '-' }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $item->tanggal_dibutuhkan ? \Carbon\Carbon::parse($item->tanggal_dibutuhkan)->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($item->status == 'pending')
                                            <span class="badge bg-warning status-badge">
                                                <i class="bi bi-clock-history me-1"></i>Pending
                                            </span>
                                        @elseif($item->status == 'approved')
                                            <span class="badge bg-success status-badge">
                                                <i class="bi bi-check-circle me-1"></i>Disetujui
                                            </span>
                                        @elseif($item->status == 'rejected')
                                            <span class="badge bg-danger status-badge">
                                                <i class="bi bi-x-circle me-1"></i>Ditolak
                                            </span>
                                        @elseif($item->status == 'delivered')
                                            <span class="badge bg-info status-badge">
                                                <i class="bi bi-truck me-1"></i>Dikirim
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($item->keterangan, 50) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($permintaan->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="mb-0">Menampilkan {{ $permintaan->firstItem() }} - {{ $permintaan->lastItem() }} dari {{ $permintaan->total() }} permintaan</p>
                        </div>
                        <nav>
                            {{ $permintaan->links() }}
                        </nav>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-x display-1 text-muted"></i>
                        <h5 class="mt-3">Tidak ada data laporan</h5>
                        <p class="text-muted">Belum ada permintaan barang yang tercatat</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Summary Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Ringkasan Laporan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Total Permintaan:</strong></td>
                                    <td>{{ $stats['total'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Permintaan Pending:</strong></td>
                                    <td>{{ $stats['pending'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Permintaan Disetujui:</strong></td>
                                    <td>{{ $stats['approved'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Permintaan Ditolak:</strong></td>
                                    <td>{{ $stats['rejected'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Permintaan Terkirim:</strong></td>
                                    <td>{{ $stats['delivered'] ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Periode Laporan:</strong></td>
                                    <td>{{ request('start_date', date('Y-m-01')) }} s/d {{ request('end_date', date('Y-m-t')) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Cetak:</strong></td>
                                    <td>{{ date('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Pemohon:</strong></td>
                                    <td>{{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Jenis Barang:</strong></td>
                                    <td>{{ $stats['total_items'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Laporan:</strong></td>
                                    <td>
                                        @if($permintaan->count() > 0)
                                            <span class="badge bg-success">Ada Data</span>
                                        @else
                                            <span class="badge bg-warning">Tidak Ada Data</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Auto dismiss alerts
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
            
            // Filter date validation
            const startDate = $('input[name="start_date"]');
            const endDate = $('input[name="end_date"]');
            
            if (startDate.length && endDate.length) {
                startDate.on('change', function() {
                    endDate.attr('min', $(this).val());
                });
                
                endDate.on('change', function() {
                    startDate.attr('max', $(this).val());
                });
            }
            
            // Initialize Charts
            initializeCharts();
        });
        
        // Initialize Charts
        function initializeCharts() {
            // Monthly Chart
            const monthlyLabels = @json($chartData['monthlyLabels'] ?? []);
            const monthlyData = @json($chartData['monthlyData'] ?? []);
            
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            if (monthlyCtx) {
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                            label: 'Jumlah Permintaan',
                            data: monthlyData,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
            
            // Status Chart
            const statusData = @json($chartData['statusData'] ?? []);
            
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Disetujui', 'Ditolak', 'Terkirim'],
                        datasets: [{
                            data: [
                                statusData.pending || 0,
                                statusData.approved || 0,
                                statusData.rejected || 0,
                                statusData.delivered || 0
                            ],
                            backgroundColor: [
                                '#fbbf24',
                                '#10b981',
                                '#ef4444',
                                '#3b82f6'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
        
        // Export Functions
        function exportToPDF() {
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;
            const status = document.querySelector('select[name="status"]').value;
            
            let url = '{{ route("user.laporan.export", ["type" => "pdf"]) }}';
            url += '?start_date=' + startDate + '&end_date=' + endDate;
            if (status) url += '&status=' + status;
            
            window.open(url, '_blank');
        }
        
        function exportToExcel() {
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;
            const status = document.querySelector('select[name="status"]').value;
            
            let url = '{{ route("user.laporan.export", ["type" => "excel"]) }}';
            url += '?start_date=' + startDate + '&end_date=' + endDate;
            if (status) url += '&status=' + status;
            
            window.location.href = url;
        }
    </script>
</body>
</html>