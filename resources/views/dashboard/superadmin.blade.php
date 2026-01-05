<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - SILOG Polres</title>
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
            --superadmin-color: #8b5cf6; /* Warna khusus superadmin */
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
            background: linear-gradient(180deg, var(--dark) 0%, #0f172a 100%); /* Warna gelap untuk superadmin */
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
            border-left: 4px solid var(--superadmin-color);
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
            background: linear-gradient(135deg, var(--superadmin-color) 0%, #6d28d9 100%); /* Warna superadmin */
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
        
        .badge-superadmin {
            background-color: var(--superadmin-color);
            color: white;
        }
        
        .badge-admin {
            background-color: var(--primary);
            color: white;
        }
        
        .badge-user {
            background-color: var(--info);
            color: white;
        }
        
        .badge-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-inactive {
            background-color: #f3f4f6;
            color: #6b7280;
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
            border-color: var(--superadmin-color);
            background-color: #f8fafc;
            transform: translateY(-3px);
        }
        
        .action-icon {
            font-size: 2rem;
            margin-bottom: 0.8rem;
            color: var(--superadmin-color);
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
        
        /* Role Badges */
        .role-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
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
            <p>Superadmin Dashboard</p>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('superadmin.dashboard') }}" class="nav-link active">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('superadmin.accounts.index') }}" class="nav-link">
                    <i class="bi bi-people"></i>
                    <span>Manajemen User</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('superadmin.satker.index') }}" class="nav-link">
                    <i class="bi bi-building"></i>
                    <span>Manajemen Satker</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('superadmin.activity-logs') }}" class="nav-link">
                    <i class="bi bi-clock-history"></i>
                    <span>Log Aktivitas</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('superadmin.settings') }}" class="nav-link">
                    <i class="bi bi-gear"></i>
                    <span>Pengaturan Sistem</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('superadmin.reports') }}" class="nav-link">
                    <i class="bi bi-file-text"></i>
                    <span>Laporan</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-footer" style="padding: 1.5rem; position: absolute; bottom: 0; width: 100%;">
            <div class="text-center">
                <small style="opacity: 0.7;">Sistem Logistik Polres</small><br>
                <small style="opacity: 0.5;">Superadmin v1.0.0</small>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <h4 class="mb-0">Dashboard Superadmin</h4>
            <div class="user-info">
                <div class="user-avatar">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <strong>{{ $user->name }}</strong><br>
                    <small class="text-muted">Superadmin</small>
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
            <a href="{{ route('superadmin.accounts.create') }}" class="action-btn">
                <div class="action-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <div>Tambah User</div>
            </a>
            
            <a href="{{ route('superadmin.satker.create') }}" class="action-btn">
                <div class="action-icon">
                    <i class="bi bi-building-add"></i>
                </div>
                <div>Tambah Satker</div>
            </a>
            
            <a href="{{ route('superadmin.reports') }}" class="action-btn">
                <div class="action-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>Generate Laporan</div>
            </a>
            
            <a href="{{ route('superadmin.settings') }}" class="action-btn">
                <div class="action-icon">
                    <i class="bi bi-sliders"></i>
                </div>
                <div>Pengaturan Sistem</div>
            </a>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #ede9fe; color: var(--superadmin-color);">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $data['total_users'] ?? 0 }}</h3>
                    <p>Total Users</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #dbeafe; color: var(--primary);">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $data['total_admins'] ?? 0 }}</h3>
                    <p>Admin Aktif</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #f0f9ff; color: var(--info);">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $data['total_satker'] ?? 0 }}</h3>
                    <p>Satuan Kerja</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #fef3c7; color: var(--warning);">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $data['total_activities'] ?? 0 }}</h3>
                    <p>Aktivitas Hari Ini</p>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <h5>Distribusi User per Role</h5>
                <canvas id="usersChart" height="250"></canvas>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h5 class="mb-0">Pertumbuhan User</h5>
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
                <canvas id="growthChart" height="250"></canvas>
            </div>
        </div>
        
        <!-- Recent Users Table -->
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">User Terbaru</h5>
                <a href="{{ route('superadmin.accounts.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Satker</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($data['recent_users'] ?? collect()) as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->username ?? '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $role = $user->role ?? 'user';
                                @endphp
                                @if($role == 'superadmin')
                                    <span class="badge badge-superadmin">Superadmin</span>
                                @elseif($role == 'admin')
                                    <span class="badge badge-admin">Admin</span>
                                @else
                                    <span class="badge badge-user">User</span>
                                @endif
                            </td>
                            <td>{{ $user->satker->nama_satker ?? '-' }}</td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge badge-active">Aktif</span>
                                @else
                                    <span class="badge badge-inactive">Non-Aktif</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-people" style="font-size: 2rem; color: #cbd5e1;"></i>
                                <p class="mt-2 text-muted">Belum ada user terdaftar</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Aktivitas Terbaru</h5>
                <a href="{{ route('superadmin.activity-logs') }}" class="btn btn-sm btn-warning">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>IP Address</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($data['recent_activities'] ?? collect()) as $activity)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                        {{ substr($activity->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $activity->user->name ?? 'System' }}</strong><br>
                                        <small class="text-muted">{{ $activity->user->role ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $action = $activity->action ?? 'unknown';
                                    $actionColors = [
                                        'login' => 'success',
                                        'logout' => 'secondary',
                                        'create' => 'primary',
                                        'update' => 'warning',
                                        'delete' => 'danger',
                                        'view' => 'info'
                                    ];
                                    $color = $actionColors[$action] ?? 'dark';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($action) }}</span>
                            </td>
                            <td>{{ $activity->description ?? '-' }}</td>
                            <td><code>{{ $activity->ip_address ?? '-' }}</code></td>
                            <td>{{ $activity->created_at->format('d/m/Y H:i:s') ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-clock-history" style="font-size: 2rem; color: #10b981;"></i>
                                <p class="mt-2 text-muted">Tidak ada aktivitas terbaru</p>
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
                                <small class="text-muted">Total User</small>
                                <p class="mb-1"><strong>{{ $data['total_users'] ?? 0 }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">User Aktif</small>
                                <p class="mb-1"><strong>{{ $data['active_users'] ?? 0 }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Total Satker</small>
                                <p class="mb-1"><strong>{{ $data['total_satker'] ?? 0 }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Aktivitas Hari Ini</small>
                                <p class="mb-1"><strong>{{ $data['today_activities'] ?? 0 }}</strong></p>
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
        window.growthChart = null;
        
        // Auto dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Users Distribution Chart
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        
        // Data untuk chart distribusi user
        const usersData = {
            superadmin: {{ $data['superadmin_count'] ?? 1 }},
            admin: {{ $data['admin_count'] ?? 0 }},
            user: {{ $data['user_count'] ?? 0 }}
        };
        
        const usersChart = new Chart(usersCtx, {
            type: 'doughnut',
            data: {
                labels: ['Superadmin', 'Admin', 'User'],
                datasets: [{
                    data: [
                        usersData.superadmin,
                        usersData.admin,
                        usersData.user
                    ],
                    backgroundColor: [
                        'rgba(139, 92, 246, 0.8)',    // Superadmin - ungu
                        'rgba(30, 58, 138, 0.8)',     // Admin - biru tua
                        'rgba(14, 165, 233, 0.8)'     // User - biru muda
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
                                return `${label}: ${value} user (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Initialize Growth Chart dengan data dari database
        function initGrowthChart() {
            const growthCtx = document.getElementById('growthChart').getContext('2d');
            
            // Data dari controller dengan escape yang benar
            const chartData = {
                labels: {!! json_encode($data['chart_months'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']) !!},
                datasets: [{
                    label: 'User Baru',
                    data: {!! json_encode($data['chart_new_users'] ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderColor: 'rgba(139, 92, 246, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }, {
                    label: 'Admin Baru',
                    data: {!! json_encode($data['chart_new_admins'] ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!},
                    backgroundColor: 'rgba(30, 58, 138, 0.1)',
                    borderColor: 'rgba(30, 58, 138, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            };
            
            window.growthChart = new Chart(growthCtx, {
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
                                text: 'Jumlah User',
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
                            text: 'Pertumbuhan User {{ $data["current_year"] ?? date("Y") }}',
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
        function loadChartData(year) {
            const refreshBtn = document.getElementById('refreshChart');
            const originalHtml = refreshBtn.innerHTML;
            
            // Show loading state
            refreshBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
            refreshBtn.disabled = true;
            
            fetch(`/superadmin/dashboard/chart-data?year=${year}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && window.growthChart) {
                        updateGrowthChart(data.data, year);
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
        function updateGrowthChart(chartData, year) {
            if (window.growthChart && chartData) {
                window.growthChart.data.labels = chartData.months;
                window.growthChart.data.datasets[0].data = chartData.new_users;
                window.growthChart.data.datasets[1].data = chartData.new_admins;
                window.growthChart.options.plugins.title.text = `Pertumbuhan User ${year}`;
                window.growthChart.update();
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
        
        // Logout confirmation
        document.querySelector('form[action="{{ route("logout") }}"]').addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin logout?')) {
                e.preventDefault();
            }
        });
        
        // Event listeners for chart controls
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize growth chart
            initGrowthChart();
            
            // Year selector change event
            document.getElementById('yearSelect').addEventListener('change', function() {
                loadChartData(this.value);
            });
            
            // Refresh button click event
            document.getElementById('refreshChart').addEventListener('click', function() {
                const year = document.getElementById('yearSelect').value;
                loadChartData(year);
            });
            
            // Mobile sidebar toggle
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            // Add mobile toggle button if on small screen
            if (window.innerWidth <= 768) {
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'btn btn-primary position-fixed';
                toggleBtn.style.cssText = 'top: 10px; left: 10px; z-index: 1001; padding: 5px 10px;';
                toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
                toggleBtn.onclick = function() {
                    if (sidebar.style.width === '70px') {
                        sidebar.style.width = '250px';
                        mainContent.style.marginLeft = '250px';
                        toggleBtn.style.left = '10px';
                    } else {
                        sidebar.style.width = '70px';
                        mainContent.style.marginLeft = '70px';
                        toggleBtn.style.left = '10px';
                    }
                };
                document.body.appendChild(toggleBtn);
            }
        });
    </script>
</body>
</html>