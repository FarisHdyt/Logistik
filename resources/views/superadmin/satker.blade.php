<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Satker - SILOG Polres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #dc2626;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #0ea5e9;
            --superadmin-color: #8b5cf6;
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
            background: linear-gradient(135deg, var(--superadmin-color) 0%, #6d28d9 100%);
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
        
        /* Page Header */
        .page-header {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-header h1 {
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: #64748b;
            margin-bottom: 0;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
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
            transform: translateY(-3px);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-content h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-content p {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        /* Filter and Search */
        .filter-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .filter-card h6 {
            color: var(--dark);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        
        .search-box input {
            padding-left: 40px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            width: 100%;
        }
        
        /* Main Card */
        .main-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .card-header h5 {
            color: var(--dark);
            font-weight: 600;
            margin: 0;
        }
        
        /* Table Styles */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e2e8f0;
            padding: 1rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e2e8f0;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        /* Badges */
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        /* Action Buttons in Table */
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }
        
        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--superadmin-color) 0%, #6d28d9 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 1.5rem;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        /* Alert Container */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .empty-state-icon {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }
        
        /* Loading Spinner */
        .spinner-border {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
        }
        
        /* Form Controls */
        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + .75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
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
                padding: 1rem;
            }
            
            .nav-link {
                justify-content: center;
                padding: 0.8rem;
            }
            
            .search-box {
                min-width: 100%;
            }
            
            .filter-card .row > div {
                margin-bottom: 1rem;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .table th, .table td {
                padding: 0.5rem;
            }
            
            .badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .topbar h4 {
                font-size: 1.2rem;
            }
            
            .user-info {
                flex-wrap: wrap;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Quick Info Card */
        .quick-info-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .quick-info-card h6 {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .quick-info-card hr {
            margin: 0.5rem 0 1rem 0;
            opacity: 0.2;
        }
        
        /* Status indicators */
        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-inactive {
            background-color: #f1f5f9;
            color: #64748b;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        
        .status-dot-active {
            background-color: #10b981;
        }
        
        .status-dot-inactive {
            background-color: #94a3b8;
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
                <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
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
                <a href="{{ route('superadmin.satker.index') }}" class="nav-link active">
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
            <h4 class="mb-0">Manajemen Satker</h4>
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
        
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1>Manajemen Satuan Kerja (Satker)</h1>
                <p class="mb-0">Kelola data satuan kerja di bawah naungan Polres</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="showAddSatkerForm()">
                <i class="bi bi-plus-circle me-2"></i>Tambah Satker
            </button>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #ede9fe; color: var(--superadmin-color);">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_satker'] ?? 0 }}</h3>
                    <p>Total Satker</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #dbeafe; color: var(--primary);">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                    <p>Total User</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #fef3c7; color: var(--warning);">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_permintaan'] ?? 0 }}</h3>
                    <p>Total Permintaan</p>
                </div>
            </div>
            
            <!-- Stat Card Satker Aktif - DIPERBAHARUI -->
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #d1fae5; color: var(--success);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['satker_aktif'] ?? 0 }}</h3>
                    <p>Satker Aktif</p>
                </div>
            </div>
        </div>
        
        <!-- Filter Card -->
        <div class="filter-card">
            <h6><i class="bi bi-funnel me-2"></i>Filter & Pencarian</h6>
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" placeholder="Cari satker..." id="searchInput">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-secondary w-100" id="resetFilter">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Table Card -->
        <div class="main-card">
            <div class="card-header">
                <h5>Daftar Satker</h5>
                <div>
                    <span class="text-muted">
                        <i class="bi bi-building me-1"></i>
                        Total: {{ $satkers->total() }} satker 
                        ({{ $stats['satker_aktif'] ?? 0 }} aktif, 
                        {{ $stats['total_satker'] - $stats['satker_aktif'] }} tidak aktif)
                    </span>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover" id="satkerTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Satker</th>
                            <th>Nama Satker</th>
                            <th>Status</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Jumlah User</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($satkers as $index => $satker)
                        @php
                            $isActive = ($satker->users_count ?? 0) > 0;
                        @endphp
                        <tr data-satker-id="{{ $satker->id }}" class="{{ $isActive ? 'table-row-active' : '' }}">
                            <td>{{ $index + 1 + (($satkers->currentPage() - 1) * $satkers->perPage()) }}</td>
                            <td>
                                <strong>{{ $satker->kode_satker ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 0.8rem; background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $satker->nama_satker }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($isActive)
                                <span class="status-indicator status-active">
                                    <span class="status-dot status-dot-active"></span>
                                    Aktif
                                </span>
                                @else
                                <span class="status-indicator status-inactive">
                                    <span class="status-dot status-dot-inactive"></span>
                                    Tidak Aktif
                                </span>
                                @endif
                            </td>
                            <td>{{ Str::limit($satker->alamat ?? 'Belum diisi', 30) }}</td>
                            <td>{{ $satker->telepon ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $isActive ? 'badge-success' : 'badge-warning' }}">
                                    {{ $satker->users_count ?? 0 }}
                                </span> user
                            </td>
                            <td>
                                {{ $satker->created_at->format('d/m/Y') }}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewSatker({{ $satker->id }})" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" onclick="showEditSatkerForm({{ $satker->id }})" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteSatker({{ $satker->id }}, '{{ $satker->nama_satker }}')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">Belum ada data satker</h5>
                                    <p class="text-muted mb-4">
                                        @if(request()->has('search'))
                                            Coba ubah pencarian Anda atau
                                        @endif
                                        <button type="button" class="btn btn-primary" onclick="showAddSatkerForm()">
                                            <i class="bi bi-plus-circle me-2"></i>Tambah Satker Pertama
                                        </button>
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($satkers->hasPages())
            <div class="pagination-container">
                <nav>
                    {{ $satkers->links('pagination::bootstrap-5') }}
                </nav>
            </div>
            @endif
            
            <!-- Summary -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <small class="text-muted">
                        Menampilkan {{ $satkers->firstItem() ?? 0 }} - {{ $satkers->lastItem() ?? 0 }} dari {{ $satkers->total() }} satker
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changePerPage(10)" {{ request('per_page', 10) == 10 ? 'disabled' : '' }}>10</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changePerPage(25)" {{ request('per_page', 10) == 25 ? 'disabled' : '' }}>25</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changePerPage(50)" {{ request('per_page', 10) == 50 ? 'disabled' : '' }}>50</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changePerPage(100)" {{ request('per_page', 10) == 100 ? 'disabled' : '' }}>100</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Info Card -->
        <div class="quick-info-card">
            <h6><i class="bi bi-info-circle me-2"></i>Informasi Manajemen Satker</h6>
            <hr>
            <div class="row">
                <div class="col-md-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-building text-primary fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted">Total Satker</small>
                            <p class="mb-0"><strong>{{ $stats['total_satker'] ?? 0 }}</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted">Satker Aktif</small>
                            <p class="mb-0"><strong>{{ $stats['satker_aktif'] ?? 0 }}</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-x-circle text-secondary fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted">Satker Tidak Aktif</small>
                            <p class="mb-0"><strong>{{ $stats['total_satker'] - $stats['satker_aktif'] }}</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted">Total User</small>
                            <p class="mb-0"><strong>{{ $stats['total_users'] ?? 0 }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Satker
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus satker <strong id="satkerName"></strong>?</p>
                    <p class="text-danger">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        <strong>Peringatan:</strong> Aksi ini tidak dapat dibatalkan. Semua data satker akan dihapus permanen.
                    </p>
                    <form id="deleteForm" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="submitDelete()">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Satker Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Detail Satker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="satkerDetail"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Satker Modal -->
    <div class="modal fade" id="satkerFormModal" tabindex="-1" aria-labelledby="satkerFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="satkerFormModalLabel">Tambah Satker Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="satkerForm">
                        @csrf
                        <input type="hidden" name="form_type" id="formType">
                        <input type="hidden" name="id" id="satkerId">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_satker" class="form-label">Kode Satker <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kode_satker" name="kode_satker" required>
                                    <div class="invalid-feedback" id="kode_satker_error"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_satker" class="form-label">Nama Satker <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_satker" name="nama_satker" required>
                                    <div class="invalid-feedback" id="nama_satker_error"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            <div class="invalid-feedback" id="alamat_error"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="telepon" name="telepon">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                    <div class="invalid-feedback" id="email_error"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_kepala" class="form-label">Nama Kepala</label>
                                    <input type="text" class="form-control" id="nama_kepala" name="nama_kepala">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pangkat_kepala" class="form-label">Pangkat Kepala</label>
                                    <input type="text" class="form-control" id="pangkat_kepala" name="pangkat_kepala">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nrp_kepala" class="form-label">NRP Kepala</label>
                            <input type="text" class="form-control" id="nrp_kepala" name="nrp_kepala">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="submitSatkerBtn" onclick="submitSatkerForm()">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let deleteModal = null;
        let viewModal = null;
        let satkerFormModal = null;
        let userToDelete = null;
        
        // Initialize modals on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
            satkerFormModal = new bootstrap.Modal(document.getElementById('satkerFormModal'));
            
            // Setup search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        searchSatkers();
                    }, 500);
                });
            }
            
            // Setup reset filter
            const resetBtn = document.getElementById('resetFilter');
            if (resetBtn) {
                resetBtn.addEventListener('click', resetFilters);
            }
            
            // Auto dismiss alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Add CSS for active rows
            const style = document.createElement('style');
            style.textContent = `
                .table-row-active {
                    background-color: rgba(16, 185, 129, 0.05);
                }
                .table-row-active:hover {
                    background-color: rgba(16, 185, 129, 0.1) !important;
                }
            `;
            document.head.appendChild(style);
        });
        
        // Function to show add satker form
        function showAddSatkerForm() {
            // Reset form first
            resetSatkerForm();
            
            // Set form type to create
            document.getElementById('formType').value = 'create';
            document.getElementById('satkerFormModalLabel').textContent = 'Tambah Satker Baru';
            
            // Show modal
            satkerFormModal.show();
        }
        
        // Function to show edit satker form
        function showEditSatkerForm(id) {
            // Show loading state
            document.getElementById('satkerFormModalLabel').textContent = 'Memuat...';
            
            // Fetch satker data via AJAX
            fetch(`/superadmin/satker/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Reset form
                    resetSatkerForm();
                    
                    // Set form type to edit
                    document.getElementById('formType').value = 'edit';
                    document.getElementById('satkerFormModalLabel').textContent = 'Edit Satker';
                    document.getElementById('satkerId').value = data.data.id;
                    
                    // Fill form with data
                    document.getElementById('kode_satker').value = data.data.kode_satker || '';
                    document.getElementById('nama_satker').value = data.data.nama_satker || '';
                    document.getElementById('alamat').value = data.data.alamat || '';
                    document.getElementById('telepon').value = data.data.telepon || '';
                    document.getElementById('email').value = data.data.email || '';
                    document.getElementById('nama_kepala').value = data.data.nama_kepala || '';
                    document.getElementById('pangkat_kepala').value = data.data.pangkat_kepala || '';
                    document.getElementById('nrp_kepala').value = data.data.nrp_kepala || '';
                    
                    // Show modal
                    satkerFormModal.show();
                } else {
                    showAlert('danger', data.message || 'Gagal memuat data satker');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Terjadi kesalahan saat memuat data');
            });
        }
        
        // Function to reset satker form
        function resetSatkerForm() {
            const form = document.getElementById('satkerForm');
            if (form) {
                form.reset();
            }
            
            // Clear all validation errors
            const inputs = document.querySelectorAll('#satkerForm .form-control');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
            
            const errorElements = document.querySelectorAll('#satkerForm .invalid-feedback');
            errorElements.forEach(element => {
                element.textContent = '';
            });
            
            // Reset hidden fields
            document.getElementById('formType').value = '';
            document.getElementById('satkerId').value = '';
        }
        
        // Function to submit satker form
        function submitSatkerForm() {
            const form = document.getElementById('satkerForm');
            const formData = new FormData(form);
            const formType = document.getElementById('formType').value;
            
            // Reset validation errors
            resetValidationErrors();
            
            // Show loading state
            const submitBtn = document.getElementById('submitSatkerBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
            submitBtn.disabled = true;
            
            // Determine URL and method
            let url = '{{ route("superadmin.satker.store") }}';
            let method = 'POST';
            
            if (formType === 'edit') {
                const id = document.getElementById('satkerId').value;
                url = `/superadmin/satker/${id}`;
                method = 'PUT';
                formData.append('_method', 'PUT');
            }
            
            // Send AJAX request
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert('success', data.message || 'Data berhasil disimpan');
                    
                    // Close modal
                    satkerFormModal.hide();
                    
                    // Reload page after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Show validation errors
                    if (data.errors) {
                        showValidationErrors(data.errors);
                    } else {
                        showAlert('danger', data.message || 'Terjadi kesalahan');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Terjadi kesalahan saat menyimpan data');
            })
            .finally(() => {
                // Restore button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
        
        // Function to reset validation errors
        function resetValidationErrors() {
            const inputs = document.querySelectorAll('#satkerForm .form-control');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
            
            const errorElements = document.querySelectorAll('#satkerForm .invalid-feedback');
            errorElements.forEach(element => {
                element.textContent = '';
            });
        }
        
        // Function to show validation errors
        function showValidationErrors(errors) {
            for (const field in errors) {
                const input = document.getElementById(field);
                const errorElement = document.getElementById(field + '_error');
                
                if (input) {
                    input.classList.add('is-invalid');
                }
                
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                }
            }
        }
        
        // Delete Satker function
        function deleteSatker(id, name) {
            userToDelete = id;
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/superadmin/satker/${id}`;
            document.getElementById('satkerName').textContent = name;
            deleteModal.show();
        }
        
        function submitDelete() {
            if (userToDelete) {
                document.getElementById('deleteForm').submit();
            }
        }
        
        // View Satker function
        function viewSatker(id) {
            console.log('Viewing satker ID:', id);
            
            // Show loading state
            document.getElementById('satkerDetail').innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
            `;
            
            // Ensure modal is initialized
            if (!viewModal) {
                viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
            }
            viewModal.show();
            
            // Get the table row data
            const row = document.querySelector(`tr[data-satker-id="${id}"]`);
            if (row) {
                const cells = row.cells;
                const isActive = cells[3]?.querySelector('.status-active') !== null;
                const satkerData = {
                    id: id,
                    kode_satker: cells[1]?.querySelector('strong')?.textContent.trim() || 'N/A',
                    nama_satker: cells[2]?.querySelector('strong')?.textContent.trim() || 'N/A',
                    status: isActive ? 'Aktif' : 'Tidak Aktif',
                    alamat: cells[4]?.textContent.trim() || 'Belum diisi',
                    telepon: cells[5]?.textContent.trim() || '-',
                    users_count: parseInt(cells[6]?.querySelector('.badge')?.textContent) || 0,
                    created_at: new Date().toISOString()
                };
                
                displaySatkerDetail(satkerData);
            } else {
                displaySatkerDetail(null);
            }
        }
        
        // Helper function to display satker detail
        function displaySatkerDetail(satker) {
            if (!satker) {
                document.getElementById('satkerDetail').innerHTML = `
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Data satker tidak ditemukan
                    </div>
                `;
                return;
            }
            
            // Format date
            const formatDate = (dateString) => {
                if (!dateString) return '-';
                try {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                } catch (e) {
                    return dateString;
                }
            };
            
            document.getElementById('satkerDetail').innerHTML = `
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <div class="user-avatar mb-3 mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);">
                                    <i class="bi bi-building" style="font-size: 2rem;"></i>
                                </div>
                                <h4>${satker.nama_satker}</h4>
                                <p class="text-muted mb-1">${satker.kode_satker}</p>
                                <span class="status-indicator ${satker.status === 'Aktif' ? 'status-active' : 'status-inactive'}">
                                    <span class="status-dot ${satker.status === 'Aktif' ? 'status-dot-active' : 'status-dot-inactive'}"></span>
                                    ${satker.status}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Informasi Satker</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">Kode Satker</th>
                                        <td>${satker.kode_satker}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="status-indicator ${satker.status === 'Aktif' ? 'status-active' : 'status-inactive'}">
                                                <span class="status-dot ${satker.status === 'Aktif' ? 'status-dot-active' : 'status-dot-inactive'}"></span>
                                                ${satker.status}
                                            </span>
                                            ${satker.users_count > 0 ? `(${satker.users_count} user)` : '(Belum ada user)'}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>${satker.alamat}</td>
                                    </tr>
                                    <tr>
                                        <th>Telepon</th>
                                        <td>${satker.telepon}</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah User</th>
                                        <td>
                                            <span class="badge ${satker.users_count > 0 ? 'badge-success' : 'badge-warning'}">
                                                ${satker.users_count}
                                            </span> user
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Dibuat</th>
                                        <td>${formatDate(satker.created_at)}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Helper function to show alerts
        function showAlert(type, message) {
            // Create or get alert container
            let alertContainer = document.querySelector('.alert-container');
            if (!alertContainer) {
                alertContainer = document.createElement('div');
                alertContainer.className = 'alert-container';
                document.body.appendChild(alertContainer);
            }
            
            // Create alert element
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Add alert to container
            alertContainer.appendChild(alert);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
        
        // Search functionality
        function searchSatkers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }
        
        // Reset filters
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }
        
        // Print table
        function printTable() {
            const table = document.getElementById('satkerTable');
            if (!table) {
                showAlert('warning', 'Tabel tidak ditemukan');
                return;
            }
            
            const originalContents = document.body.innerHTML;
            const printContents = table.outerHTML;
            
            document.body.innerHTML = `
                <html>
                <head>
                    <title>Daftar Satker - SILOG Polres</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                        tr:nth-child(even) { background-color: #f9f9f9; }
                        h2 { color: #333; }
                        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
                        .status-indicator { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 12px; font-size: 12px; }
                        .status-active { background-color: #d1fae5; color: #065f46; }
                        .status-inactive { background-color: #f1f5f9; color: #64748b; }
                        .status-dot { width: 6px; height: 6px; border-radius: 50%; }
                        .status-dot-active { background-color: #10b981; }
                        .status-dot-inactive { background-color: #94a3b8; }
                        @media print {
                            @page { size: landscape; margin: 0.5cm; }
                            body { margin: 0; }
                        }
                    </style>
                </head>
                <body>
                    <h2>DAFTAR SATKER - SILOG POLRES</h2>
                    <p><strong>Tanggal cetak:</strong> ${new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                    <p><strong>Total satker:</strong> {{ $satkers->total() }}</p>
                    <p><strong>Satker aktif:</strong> {{ $stats['satker_aktif'] ?? 0 }}</p>
                    ${printContents}
                </body>
                </html>
            `;
            
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
        
        // Change items per page
        function changePerPage(perPage) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        }
        
        // Logout confirmation
        document.querySelector('form[action="{{ route("logout") }}"]').addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin logout?')) {
                e.preventDefault();
            }
        });
        
        // Mobile sidebar toggle
        if (window.innerWidth <= 768) {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'btn btn-primary position-fixed';
            toggleBtn.style.cssText = 'top: 10px; left: 10px; z-index: 1001; padding: 5px 10px;';
            toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
            toggleBtn.onclick = function() {
                if (sidebar.style.width === '70px') {
                    sidebar.style.width = '250px';
                    mainContent.style.marginLeft = '250px';
                } else {
                    sidebar.style.width = '70px';
                    mainContent.style.marginLeft = '70px';
                }
            };
            document.body.appendChild(toggleBtn);
        }
    </script>
</body>
</html>