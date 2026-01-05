<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SILOG Polres</title>
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
            --delivered-color: #8b5cf6; /* Warna baru untuk status terkirim */
            --dark: #1e293b;
            --light: #f8fafc;
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
        
        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, var(--dark) 0%, #0f172a 100%);
            color: white;
            min-height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand h3 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .sidebar-brand p {
            font-size: 0.85rem;
            opacity: 0.8;
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
        
        .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: 100vh;
        }
        
        /* Top Bar */
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
        
        .logout-btn {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #b91c1c;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        
        .stat-content h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-content p {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        /* Charts Section */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .chart-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .chart-card h5 {
            margin-bottom: 1.5rem;
            color: var(--dark);
            font-weight: 600;
        }
        
        /* Year Selector for Chart */
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .year-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .year-selector select {
            padding: 0.25rem 0.5rem;
            border-radius: 5px;
            border: 1px solid #d1d5db;
            background-color: white;
        }
        
        /* Tables */
        .table-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .table-card h5 {
            margin-bottom: 1.5rem;
            color: var(--dark);
            font-weight: 600;
        }
        
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e2e8f0;
        }
        
        /* Badges */
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 500;
        }
        
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-delivered {
            background-color: var(--delivered-color); /* Menggunakan warna #8b5cf6 */
            color: white;
        }
        
        .badge-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .action-btn {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 1.5rem 1rem;
            text-align: center;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            border-color: var(--primary);
            background-color: #f8fafc;
            transform: translateY(-3px);
        }
        
        .action-icon {
            font-size: 2rem;
            margin-bottom: 0.8rem;
            color: var(--primary);
        }
        
        /* Alert */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        
        /* Loading Spinner */
        .spinner-border {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
        }
        
        /* Responsive */
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
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h3>SILOG</h3>
            <p>Admin Dashboard</p>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link active">
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
                <a href="{{ route('admin.reports') }}" class="nav-link">
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
            <h4 class="mb-0">Dashboard Admin</h4>
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
                    <button type="submit" class="logout-btn">
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
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('admin.inventory') }}" class="action-btn">
                <div class="action-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <div>Tambah Barang</div>
            </a>
            
            <a href="{{ route('admin.requests') }}" class="action-btn">
                <div class="action-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div>Permintaan Baru</div>
            </a>
            
            <a href="{{ route('admin.reports') }}" class="action-btn">
                <div class="action-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>Buat Laporan</div>
            </a>
            
            <a href="{{ route('admin.reports') }}" class="action-btn">
                <div class="action-icon">
                    <i class="bi bi-printer"></i>
                </div>
                <div>Cetak Laporan</div>
            </a>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #dbeafe; color: #1d4ed8;">
                    <i class="bi bi-box"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $data['total_barang'] ?? 0 }}</h3>
                    <p>Total Barang</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #f0f9ff; color: #0ea5e9;">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $data['total_stok'] ?? 0 }}</h3>
                    <p>Total Stok Barang</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #fef3c7; color: #d97706;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $data['permintaan_pending'] ?? 0 }}</h3>
                    <p>Permintaan Pending</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #8b5cf6; color: white;">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $data['permintaan_delivered'] ?? 0 }}</h3>
                    <p>Permintaan Terkirim</p>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <h5>Statistik Permintaan</h5>
                <canvas id="requestsChart" height="250"></canvas>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="mb-0">Statistik Barang</h5>
                    <div class="year-selector">
                        <label for="yearSelect">Tahun:</label>
                        <select id="yearSelect">
                            @for($i = date('Y') - 2; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $i == ($data['current_year'] ?? date('Y')) ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        <button id="refreshChart" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
                <canvas id="inventoryChart" height="250"></canvas>
            </div>
        </div>
        
        <!-- Recent Requests Table -->
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Permintaan Terbaru</h5>
                <a href="{{ route('admin.requests') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Permintaan</th>
                            <th>Pemohon</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Satker</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($data['recent_requests'] ?? collect()) as $request)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $request->kode_permintaan ?? 'PMT-' . str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $request->user->name ?? 'N/A' }}</td>
                            <td>{{ $request->barang->nama_barang ?? 'N/A' }}</td>
                            <td>{{ $request->jumlah ?? 0 }}</td>
                            <td>{{ $request->satker->nama_satker ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $status = $request->status ?? 'pending';
                                @endphp
                                @if($status == 'pending')
                                    <span class="badge badge-pending">Pending</span>
                                @elseif($status == 'approved')
                                    <span class="badge badge-approved">Disetujui</span>
                                @elseif($status == 'delivered')
                                    <span class="badge badge-delivered">Terkirim</span>
                                @elseif($status == 'rejected')
                                    <span class="badge badge-rejected">Ditolak</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                            <td>{{ $request->created_at->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem; color: #cbd5e1;"></i>
                                <p class="mt-2 text-muted">Belum ada permintaan barang</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Low Stock Items -->
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Barang Stok Rendah</h5>
                <a href="{{ route('admin.inventory') }}?filter=low_stock" class="btn btn-sm btn-warning">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok Saat Ini</th>
                            <th>Stok Minimal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($data['low_stock'] ?? collect()) as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $item->kode_barang ?? 'BRG-' . str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $item->nama_barang ?? 'N/A' }}</td>
                            <td>{{ $item->kategori->nama_kategori ?? 'N/A' }}</td>
                            <td class="fw-bold" style="color: #dc2626;">{{ $item->stok ?? 0 }}</td>
                            <td>{{ $item->stok_minimal ?? 10 }}</td>
                            <td>
                                @php
                                    $stok = $item->stok ?? 0;
                                    $stokMinimal = $item->stok_minimal ?? 10;
                                    $stokWarning = $stokMinimal * 2;
                                @endphp
                                @if($stok <= $stokMinimal)
                                    <span class="badge bg-danger">Kritis</span>
                                @elseif($stok < $stokWarning)
                                    <span class="badge bg-warning">Rendah</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-check-circle" style="font-size: 2rem; color: #10b981;"></i>
                                <p class="mt-2 text-muted">Tidak ada barang dengan stok rendah</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- System Info -->
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6><i class="bi bi-info-circle me-2"></i>Informasi Sistem</h6>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Total Barang</small>
                                <p class="mb-1"><strong>{{ $data['total_barang'] ?? 0 }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Total Stok</small>
                                <p class="mb-1"><strong>{{ $data['total_stok'] ?? 0 }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Barang Habis</small>
                                <p class="mb-1"><strong>{{ $data['barang_habis'] ?? 0 }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Permintaan Bulan Ini</small>
                                <p class="mb-1"><strong>{{ $data['permintaan_bulan_ini'] ?? 0 }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6><i class="bi bi-clock-history me-2"></i>Aktivitas Terakhir</h6>
                        <hr>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person-check text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <small>Login terakhir</small>
                                <p class="mb-0"><strong>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i:s') : 'Belum pernah login' }}</strong></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-geo-alt text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <small>IP Address</small>
                                <p class="mb-0"><strong>{{ $user->last_login_ip ?? '-' }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variable untuk menyimpan chart instance
        window.inventoryChart = null;
        
        // Auto dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Requests Chart
        const requestsCtx = document.getElementById('requestsChart').getContext('2d');
        
        // Data untuk chart permintaan - sesuaikan dengan controller
        const requestData = {
            pending: {{ $data['permintaan_pending'] ?? 0 }},
            approved: {{ $data['permintaan_disetujui'] ?? 0 }},
            delivered: {{ $data['permintaan_delivered'] ?? 0 }},
            rejected: {{ $data['permintaan_ditolak'] ?? 0 }}
        };
        
        // Jika data delivered tidak ada, hitung dari recent_requests
        let deliveredCount = requestData.delivered;
        if (deliveredCount === 0 && {{ isset($data['recent_requests']) && count($data['recent_requests']) > 0 ? 'true' : 'false' }}) {
            const recentRequests = {!! json_encode($data['recent_requests'] ?? []) !!};
            deliveredCount = recentRequests.filter(r => r.status === 'delivered').length;
        }
        
        const requestsChart = new Chart(requestsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Disetujui', 'Terkirim', 'Ditolak'],
                datasets: [{
                    data: [
                        requestData.pending,
                        requestData.approved,
                        deliveredCount,
                        requestData.rejected
                    ],
                    backgroundColor: [
                        '#fbbf24', // Pending - kuning
                        '#10b981', // Disetujui - hijau
                        '#8b5cf6', // Terkirim - ungu (#8b5cf6)
                        '#ef4444'  // Ditolak - merah
                    ],
                    borderWidth: 1,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
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
        
        // Initialize Inventory Chart dengan data dari database
        function initInventoryChart() {
            const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
            
            // Data dari controller dengan escape yang benar
            const chartData = {
                labels: {!! json_encode($data['chart_months'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']) !!},
                datasets: [{
                    label: 'Barang Masuk',
                    data: {!! json_encode($data['chart_barang_masuk'] ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }, {
                    label: 'Barang Keluar',
                    data: {!! json_encode($data['chart_barang_keluar'] ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            };
            
            window.inventoryChart = new Chart(inventoryCtx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Barang',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Bulan',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Statistik Barang Masuk dan Keluar {{ $data["current_year"] ?? date("Y") }}',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            padding: {
                                bottom: 20
                            }
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20
                            }
                        }
                    }
                }
            });
        }        

        // Load chart data via AJAX
        function loadInventoryChartData(year) {
            const refreshBtn = document.getElementById('refreshChart');
            const originalHtml = refreshBtn.innerHTML;
            
            // Show loading state
            refreshBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
            refreshBtn.disabled = true;
            
            fetch(`/admin/dashboard/chart-data?year=${year}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && window.inventoryChart) {
                        updateInventoryChart(data.data, year);
                    } else {
                        showAlert('Gagal memuat data chart', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat memuat data', 'danger');
                })
                .finally(() => {
                    // Restore button state
                    refreshBtn.innerHTML = originalHtml;
                    refreshBtn.disabled = false;
                });
        }
        
        // Update chart dengan data baru
        function updateInventoryChart(chartData, year) {
            if (window.inventoryChart && chartData) {
                window.inventoryChart.data.labels = chartData.months;
                window.inventoryChart.data.datasets[0].data = chartData.barang_masuk;
                window.inventoryChart.data.datasets[1].data = chartData.barang_keluar;
                window.inventoryChart.options.plugins.title.text = `Statistik Barang Masuk dan Keluar ${year}`;
                window.inventoryChart.update();
            }
        }
        
        // Show alert message
        function showAlert(message, type = 'info') {
            const alertContainer = document.querySelector('.alert-container') || createAlertContainer();
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.role = 'alert';
            alert.innerHTML = `
                <i class="bi ${type === 'danger' ? 'bi-exclamation-triangle' : type === 'success' ? 'bi-check-circle' : 'bi-info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            alertContainer.appendChild(alert);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }
        
        // Create alert container if not exists
        function createAlertContainer() {
            const container = document.createElement('div');
            container.className = 'alert-container';
            document.body.appendChild(container);
            return container;
        }
        
        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (sidebar.style.width === '70px') {
                sidebar.style.width = '250px';
                mainContent.style.marginLeft = '250px';
            } else {
                sidebar.style.width = '70px';
                mainContent.style.marginLeft = '70px';
            }
        }
        
        // View Request Detail
        function viewRequest(requestId) {
            window.location.href = `/admin/requests/${requestId}`;
        }
        
        // Logout confirmation
        document.querySelector('form[action="{{ route("logout") }}"]').addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin logout?')) {
                e.preventDefault();
            }
        });
        
        // Event listeners for chart controls
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize inventory chart
            initInventoryChart();
            
            // Year selector change event
            document.getElementById('yearSelect').addEventListener('change', function() {
                loadInventoryChartData(this.value);
            });
            
            // Refresh button click event
            document.getElementById('refreshChart').addEventListener('click', function() {
                const year = document.getElementById('yearSelect').value;
                loadInventoryChartData(year);
            });
        });
    </script>
</body>
</html>