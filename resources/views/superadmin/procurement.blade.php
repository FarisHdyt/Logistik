<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Pengadaan Barang | SILOG Polres</title>
    <!-- CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        
        /* Sidebar - Sesuai Dashboard Superadmin */
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
        
        /* Top Bar - Sesuai Dashboard Superadmin */
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
        
        /* Page Header */
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
            background-color: var(--superadmin-color);
            border-color: var(--superadmin-color);
        }
        
        .btn-action:hover {
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
        
        /* Stats Cards - Sesuai Dashboard Superadmin */
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
        
        /* Status Tabs */
        .status-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .status-tab {
            padding: 0.6rem 1.2rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        
        .status-tab:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
        }
        
        .status-tab.active {
            background: var(--superadmin-color);
            color: white;
            border-color: var(--superadmin-color);
        }
        
        /* Badges */
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid rgba(0,0,0,0.1);
            color: var(--dark) !important;
        }
        
        /* Badge Status Pengadaan */
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
        
        .badge-completed {
            background-color: #8b5cf6 !important;
            color: white !important;
            border-color: #7c3aed;
        }
        
        .badge-cancelled {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border-color: #f87171;
        }
        
        .badge-rejected {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border-color: #f87171;
        }
        
        /* Badge Tipe Pengadaan */
        .badge-baru {
            background-color: #dbeafe !important;
            color: #1e40af !important;
            border-color: #60a5fa;
        }
        
        .badge-restock {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border-color: #fbbf24;
        }
        
        /* Prioritas badges */
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
            border-color: #f87171;
        }
        
        /* Badge khusus superadmin */
        .badge-superadmin {
            background-color: var(--superadmin-color) !important;
            color: white !important;
            border-color: var(--superadmin-color);
        }
        
        /* Tables */
        .table-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e2e8f0;
        }
        
        /* Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        /* Alert */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        
        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            border: 1px solid #dee2e6;
            height: calc(1.5em + 0.75rem + 2px);
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            color: #212529;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 0.75rem + 2px);
        }
        
        /* Modal Action Buttons */
        .modal-action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        
        /* Karakter counter styling */
        .char-counter {
            font-size: 0.75rem;
            text-align: right;
            margin-top: 0.25rem;
            color: #6c757d;
        }
        
        .char-counter.warning {
            color: #f59e0b;
        }
        
        .char-counter.danger {
            color: #dc2626;
        }
        
        /* Action Buttons Styling */
        .btn-validate {
            min-width: 80px;
        }
        
        /* Loading Spinner */
        .spinner-border {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
        }
        
        /* Quick Print Button */
        .btn-print {
            background-color: var(--warning);
            border-color: var(--warning);
        }
        
        .btn-print:hover {
            background-color: #d97706;
            border-color: #d97706;
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
            
            .action-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .btn-action {
                width: 100%;
                justify-content: center;
            }
            
            .status-tabs {
                padding: 0.75rem;
            }
            
            .status-tab {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }
            
            .modal-action-buttons {
                flex-direction: column;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }
            
            .stat-content h3 {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .status-tabs {
                justify-content: center;
            }
        }
        
        /* Print Styles */
        @media print {
            .sidebar, .topbar, .page-header, .stats-grid, 
            .filter-bar, .status-tabs, .action-buttons, 
            .btn-group, .pagination, .alert-container {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            
            .table-card {
                box-shadow: none !important;
                padding: 0 !important;
                border: none !important;
            }
            
            body {
                background-color: white !important;
                color: black !important;
            }
            
            .badge {
                color: black !important;
                border: 1px solid #000 !important;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar - Sesuai Dashboard Superadmin -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h3>SILOG</h3>
            <p>Validasi Pengadaan Barang</p>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('superadmin.procurement') }}" class="nav-link active">
                    <i class="bi bi-cart-check"></i>
                    <span>Validasi Pengadaan</span>
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
        <!-- Top Bar - Sesuai Dashboard Superadmin -->
        <div class="topbar">
            <h4 class="mb-0">Validasi Pengadaan Barang</h4>
            <div class="user-info">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <strong>{{ auth()->user()->name }}</strong><br>
                    <small class="text-muted">
                        <span class="badge badge-superadmin">Superadmin</span>
                    </small>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Alert Container -->
        <div class="alert-container" id="alertContainer"></div>
        
        <!-- Alert Messages dari Session -->
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
                    <h5 class="mb-1">Validasi Pengajuan Pengadaan</h5>
                    <p class="text-muted mb-0">Tinjau dan validasi pengajuan pengadaan barang</p>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-print btn-action" onclick="printTable()">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Sesuai Dashboard Superadmin -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #ede9fe; color: var(--superadmin-color);">
                    <i class="bi bi-cart-plus"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                    <p>Total Pengadaan</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #fef3c7; color: var(--warning);">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['pending'] ?? 0 }}</h3>
                    <p>Menunggu Validasi</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #dbeafe; color: var(--primary);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-content">
                    <h3>Rp {{ number_format($stats['pending_value'] ?? 0, 0, ',', '.') }}</h3>
                    <p>Nilai Menunggu</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #d1fae5; color: var(--success);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['approved'] ?? 0 }}</h3>
                    <p>Telah Disetujui</p>
                </div>
            </div>
        </div>
        
        <!-- Filter Bar -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('superadmin.procurement') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" name="search" 
                               placeholder="Cari barang..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="tipeFilter" name="tipe">
                            <option value="">Semua Tipe</option>
                            <option value="baru" {{ request('tipe') == 'baru' ? 'selected' : '' }}>Barang Baru</option>
                            <option value="restock" {{ request('tipe') == 'restock' ? 'selected' : '' }}>Restock</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1" style="background-color: var(--superadmin-color); border-color: var(--superadmin-color);">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->has('search') || request()->has('tipe'))
                        <a href="{{ route('superadmin.procurement') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Status Tabs -->
        <div class="status-tabs">
            <a href="{{ route('superadmin.procurement', ['status' => 'all']) }}" class="status-tab {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('superadmin.procurement', ['status' => 'pending']) }}" class="status-tab {{ request('status') == 'pending' ? 'active' : '' }}">Menunggu Validasi</a>
            <a href="{{ route('superadmin.procurement', ['status' => 'approved']) }}" class="status-tab {{ request('status') == 'approved' ? 'active' : '' }}">Disetujui</a>
            <a href="{{ route('superadmin.procurement', ['status' => 'rejected']) }}" class="status-tab {{ request('status') == 'rejected' ? 'active' : '' }}">Ditolak</a>
            <a href="{{ route('superadmin.procurement', ['status' => 'completed']) }}" class="status-tab {{ request('status') == 'completed' ? 'active' : '' }}">Selesai</a>
            <a href="{{ route('superadmin.procurement', ['status' => 'cancelled']) }}" class="status-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">Dibatalkan</a>
        </div>
        
        <!-- Procurement Table -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode/Nama Barang</th>
                            <th>Diajukan Oleh</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Harga Perkiraan</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Tanggal Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($procurements) && $procurements->count() > 0)
                            @foreach($procurements as $index => $procurement)
                            <tr>
                                <td>{{ ($procurements->currentPage() - 1) * $procurements->perPage() + $index + 1 }}</td>
                                <td>
                                    <strong>{{ $procurement->kode_barang }}</strong><br>
                                    <small>{{ $procurement->nama_barang }}</small>
                                </td>
                                <td>
                                    <small>{{ $procurement->user->name ?? 'Tidak diketahui' }}</small><br>
                                    <small class="text-muted">{{ $procurement->user->satker->nama_satker ?? 'Tidak ada satker' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $procurement->tipe_pengadaan }}">
                                        {{ $procurement->tipe_pengadaan == 'baru' ? 'Baru' : 'Restock' }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $procurement->jumlah }}</td>
                                <td>Rp {{ number_format($procurement->harga_perkiraan, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-priority-{{ $procurement->prioritas }}">
                                        {{ ucfirst($procurement->prioritas) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $procurement->status }}">
                                        @if($procurement->status == 'pending')
                                            Menunggu
                                        @elseif($procurement->status == 'approved')
                                            Disetujui
                                        @elseif($procurement->status == 'completed')
                                            Selesai
                                        @elseif($procurement->status == 'cancelled')
                                            Dibatalkan
                                        @elseif($procurement->status == 'rejected')
                                            Ditolak
                                        @else
                                            {{ ucfirst($procurement->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $procurement->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Aksi">
                                        <button type="button" class="btn btn-info btn-sm view-procurement" 
                                                data-id="{{ $procurement->id }}" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        {{-- Tombol validasi hanya untuk status pending --}}
                                        @if($procurement->status == 'pending')
                                        <button type="button" class="btn btn-success btn-sm approve-procurement" 
                                                data-id="{{ $procurement->id }}" title="Setujui">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm reject-procurement" 
                                                data-id="{{ $procurement->id }}" title="Tolak">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center">
                                    <div class="py-4">
                                        <i class="bi bi-cart-plus display-6 text-muted"></i>
                                        <p class="mt-2">Tidak ada data pengadaan ditemukan</p>
                                        @if(request()->has('search') || request()->has('tipe'))
                                        <a href="{{ route('superadmin.procurement') }}" class="btn btn-primary btn-sm mt-2" style="background-color: var(--superadmin-color); border-color: var(--superadmin-color);">
                                            Reset Filter
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(isset($procurements) && $procurements->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $procurements->firstItem() }} - {{ $procurements->lastItem() }} dari {{ $procurements->total() }} data
                </div>
                <div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($procurements->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                    <span class="page-link" aria-hidden="true">&laquo; Sebelumnya</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $procurements->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&laquo; Sebelumnya</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($procurements->links()->elements as $element)
                                {{-- "Three Dots" Separator --}}
                                @if (is_string($element))
                                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                @endif

                                {{-- Array Of Links --}}
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $procurements->currentPage())
                                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($procurements->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $procurements->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Selanjutnya &raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                                    <span class="page-link" aria-hidden="true">Selanjutnya &raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Detail Modal untuk Pengadaan -->
    <div class="modal fade" id="detailProcurementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengajuan Pengadaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-form" id="detailProcurementBody">
                    <!-- Detail akan diisi dengan JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="printDetail()" style="background-color: var(--superadmin-color); border-color: var(--superadmin-color);">
                        <i class="bi bi-printer me-1"></i> Cetak
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Approve Modal -->
    <div class="modal fade" id="approveProcurementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Pengadaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="approveProcurementForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="procurement_id" id="approve_procurement_id">
                        <p>Apakah Anda yakin ingin menyetujui pengadaan ini?</p>
                        <div class="mb-3">
                            <label for="catatan_approve" class="form-label">
                                Catatan (Opsional)
                            </label>
                            <textarea class="form-control" id="catatan_approve" name="catatan" 
                                      rows="3" placeholder="Tambahkan catatan jika perlu"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="submitApproveBtn">
                            <i class="bi bi-check-circle me-1"></i> Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Reject Modal -->
    <div class="modal fade" id="rejectProcurementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pengadaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="rejectProcurementForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="procurement_id" id="reject_procurement_id">
                        <div class="mb-3">
                            <label for="alasan_penolakan" class="form-label">
                                Alasan Penolakan <span class="text-danger">*</span>
                                <small class="text-muted">(minimal 10 karakter)</small>
                            </label>
                            <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" 
                                      rows="3" placeholder="Masukkan alasan penolakan minimal 10 karakter" 
                                      minlength="10" required></textarea>
                            <div class="char-counter" id="alasan_counter">0/10 karakter</div>
                            <div class="form-text text-danger d-none" id="alasan_error">
                                <i class="bi bi-exclamation-circle"></i> Alasan penolakan harus minimal 10 karakter
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger" id="submitRejectBtn">Tolak Pengadaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Submit form filter dengan Enter
        $('#searchInput').keypress(function(e) {
            if (e.which == 13) {
                $('#filterForm').submit();
                return false;
            }
        });
        
        // Auto dismiss alerts setelah 5 detik
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
        
        // View detail procurement
        $(document).on('click', '.view-procurement', function() {
            const procurementId = $(this).data('id');
            
            // Tampilkan loading state
            $('#detailProcurementBody').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status" style="color: var(--superadmin-color);">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data pengadaan...</p>
                </div>
            `);
            
            fetch(`/superadmin/procurement/${procurementId}`)
                .then(response => response.json())
                .then(data => {
                    const procurement = data.procurement;
                    
                    let html = `
                        <!-- Informasi Umum -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3" style="color: var(--superadmin-color);">
                                            <i class="bi bi-info-circle me-2"></i>Informasi Pengadaan
                                        </h6>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Kode Barang:</div>
                                            <div class="col-7">${procurement.kode_barang || '-'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Nama Barang:</div>
                                            <div class="col-7">${procurement.nama_barang || '-'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Tipe Pengadaan:</div>
                                            <div class="col-7">
                                                <span class="badge badge-${procurement.tipe_pengadaan}">
                                                    ${procurement.tipe_pengadaan_display || procurement.tipe_pengadaan}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Kategori:</div>
                                            <div class="col-7">${procurement.kategori?.nama_kategori || '-'}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-5 fw-bold">Satuan:</div>
                                            <div class="col-7">${procurement.satuan?.nama_satuan || '-'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3" style="color: var(--superadmin-color);">
                                            <i class="bi bi-clipboard-check me-2"></i>Status Pengadaan
                                        </h6>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Status:</div>
                                            <div class="col-7">
                                                <span class="badge badge-${procurement.status}">
                                                    ${procurement.status_display || procurement.status}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Prioritas:</div>
                                            <div class="col-7">
                                                <span class="badge badge-priority-${procurement.prioritas}">
                                                    ${procurement.prioritas_display || procurement.prioritas}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Jumlah:</div>
                                            <div class="col-7">${procurement.jumlah}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Harga Perkiraan:</div>
                                            <div class="col-7">Rp ${formatNumber(procurement.harga_perkiraan)}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Total Perkiraan:</div>
                                            <div class="col-7 fw-bold" style="color: var(--superadmin-color);">Rp ${formatNumber(procurement.total_perkiraan || procurement.jumlah * procurement.harga_perkiraan)}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-5 fw-bold">Tanggal Diajukan:</div>
                                            <div class="col-7">${new Date(procurement.created_at).toLocaleDateString('id-ID', {
                                                day: '2-digit',
                                                month: 'long',
                                                year: 'numeric'
                                            })}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Pemohon -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0" style="color: var(--superadmin-color);">
                                    <i class="bi bi-person me-2"></i>Informasi Pemohon
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Nama Pemohon:</div>
                                    <div class="col-8">${procurement.user?.name || procurement.diajukan_oleh_user?.name || '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Username:</div>
                                    <div class="col-8">${procurement.user?.username || '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Email:</div>
                                    <div class="col-8">${procurement.user?.email || '-'}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Alasan Pengadaan -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0" style="color: var(--superadmin-color);">
                                    <i class="bi bi-card-text me-2"></i>Alasan Pengadaan
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">${procurement.alasan_pengadaan || 'Tidak ada alasan yang dicantumkan'}</p>
                            </div>
                        </div>
                    `;
                    
                    // Catatan jika ada
                    if (procurement.catatan) {
                        html += `
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0" style="color: var(--superadmin-color);">
                                        <i class="bi bi-sticky me-2"></i>Catatan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">${procurement.catatan}</p>
                                </div>
                            </div>
                        `;
                    }
                    
                    // Informasi timeline jika ada
                    html += `
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0" style="color: var(--superadmin-color);">
                                    <i class="bi bi-clock-history me-2"></i>Timeline
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <strong>Diajukan</strong>
                                            <div class="text-muted small">
                                                ${new Date(procurement.created_at).toLocaleDateString('id-ID')}
                                                ${procurement.user?.name ? `oleh ${procurement.user.name}` : ''}
                                            </div>
                                        </div>
                                    </div>
                    `;
                    
                    if (procurement.approved_at) {
                        html += `
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <strong>Disetujui</strong>
                                    <div class="text-muted small">
                                        ${new Date(procurement.approved_at).toLocaleDateString('id-ID')}
                                        ${procurement.disetujui_oleh_user ? `oleh ${procurement.disetujui_oleh_user.name}` : ''}
                                    </div>
                                    ${procurement.catatan_approve ? `
                                    <div class="alert alert-success mt-2 mb-0 p-2">
                                        <small><strong>Catatan:</strong> ${procurement.catatan_approve}</small>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }
                    
                    if (procurement.completed_at) {
                        html += `
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <strong>Selesai</strong>
                                    <div class="text-muted small">
                                        ${new Date(procurement.completed_at).toLocaleDateString('id-ID')}
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    
                    if (procurement.cancelled_at) {
                        html += `
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <strong>Dibatalkan</strong>
                                    <div class="text-muted small">
                                        ${new Date(procurement.cancelled_at).toLocaleDateString('id-ID')}
                                    </div>
                                    ${procurement.alasan_pembatalan ? `
                                    <div class="alert alert-danger mt-2 mb-0 p-2">
                                        <small><strong>Alasan:</strong> ${procurement.alasan_pembatalan}</small>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }
                    
                    if (procurement.rejected_at) {
                        html += `
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <strong>Ditolak</strong>
                                    <div class="text-muted small">
                                        ${new Date(procurement.rejected_at).toLocaleDateString('id-ID')}
                                        ${procurement.ditolak_oleh_user ? `oleh ${procurement.ditolak_oleh_user.name}` : ''}
                                    </div>
                                    ${procurement.alasan_penolakan ? `
                                    <div class="alert alert-danger mt-2 mb-0 p-2">
                                        <small><strong>Alasan:</strong> ${procurement.alasan_penolakan}</small>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }
                    
                    html += `
                                </div>
                            </div>
                        </div>
                        
                        <style>
                            .timeline {
                                position: relative;
                                padding-left: 30px;
                            }
                            .timeline-item {
                                position: relative;
                                padding-bottom: 20px;
                            }
                            .timeline-marker {
                                position: absolute;
                                left: -30px;
                                top: 5px;
                                width: 12px;
                                height: 12px;
                                border-radius: 50%;
                                background-color: #dee2e6;
                            }
                            .timeline-marker.bg-success { background-color: var(--success); }
                            .timeline-marker.bg-primary { background-color: var(--primary); }
                            .timeline-marker.bg-danger { background-color: var(--secondary); }
                            .timeline-content {
                                margin-left: 0;
                            }
                        </style>
                    `;
                    
                    $('#detailProcurementBody').html(html);
                    
                    const modal = new bootstrap.Modal(document.getElementById('detailProcurementModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#detailProcurementBody').html(`
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                            <h5 class="mt-3 text-danger">Gagal memuat data pengadaan</h5>
                            <p class="text-muted">Silakan coba lagi atau hubungi administrator</p>
                            <button class="btn btn-primary btn-sm mt-2" onclick="location.reload()" style="background-color: var(--superadmin-color); border-color: var(--superadmin-color);">
                                <i class="bi bi-arrow-clockwise"></i> Coba Lagi
                            </button>
                        </div>
                    `);
                });
        });
        
        // Approve procurement - tampilkan modal
        $(document).on('click', '.approve-procurement', function() {
            const procurementId = $(this).data('id');
            $('#approve_procurement_id').val(procurementId);
            $('#catatan_approve').val('');
            
            const modal = new bootstrap.Modal(document.getElementById('approveProcurementModal'));
            modal.show();
        });
        
        // Submit approve form
        $('#approveProcurementForm').submit(function(e) {
            e.preventDefault();
            
            const procurementId = $('#approve_procurement_id').val();
            const formData = $(this).serialize();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            // Disable tombol submit selama proses
            $('#submitApproveBtn').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Memproses...
            `);
            
            fetch(`/superadmin/procurement/${procurementId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Terjadi kesalahan saat menyetujui pengadaan');
                }
            })
            .then(data => {
                if (data.success) {
                    showAlert('Pengadaan berhasil disetujui', 'success');
                    $('#approveProcurementModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    const errorMessage = data.message || 'Terjadi kesalahan saat menyetujui pengadaan';
                    showAlert(errorMessage, 'danger');
                    $('#submitApproveBtn').prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Setujui');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menyetujui pengadaan', 'danger');
                $('#submitApproveBtn').prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Setujui');
            });
        });
        
        // Reject procurement - tampilkan modal
        $(document).on('click', '.reject-procurement', function() {
            const procurementId = $(this).data('id');
            $('#reject_procurement_id').val(procurementId);
            
            // Reset form dan validasi
            $('#alasan_penolakan').val('');
            $('#alasan_error').addClass('d-none');
            $('#alasan_counter').text('0/10 karakter').removeClass('warning danger');
            $('#submitRejectBtn').prop('disabled', false);
            
            const modal = new bootstrap.Modal(document.getElementById('rejectProcurementModal'));
            modal.show();
        });
        
        // Validasi real-time pada textarea alasan penolakan
        $('#alasan_penolakan').on('input', function() {
            const text = $(this).val();
            const charCount = text.length;
            const counter = $('#alasan_counter');
            const errorElement = $('#alasan_error');
            
            // Update counter
            counter.text(`${charCount}/10 karakter`);
            
            // Update warna counter berdasarkan jumlah karakter
            if (charCount < 10) {
                counter.removeClass('warning').addClass('danger');
                errorElement.removeClass('d-none');
            } else if (charCount >= 10 && charCount < 20) {
                counter.removeClass('danger').addClass('warning');
                errorElement.addClass('d-none');
            } else {
                counter.removeClass('danger warning');
                errorElement.addClass('d-none');
            }
            
            // Enable/disable tombol submit
            $('#submitRejectBtn').prop('disabled', charCount < 10);
        });
        
        // Submit reject form dengan validasi
        $('#rejectProcurementForm').submit(function(e) {
            e.preventDefault();
            
            const procurementId = $('#reject_procurement_id').val();
            const alasan = $('#alasan_penolakan').val().trim();
            
            // Validasi panjang karakter
            if (alasan.length < 10) {
                showAlert('Alasan penolakan harus minimal 10 karakter!', 'danger');
                $('#alasan_penolakan').focus();
                return false;
            }
            
            const formData = $(this).serialize();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            // Disable tombol submit selama proses
            $('#submitRejectBtn').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Memproses...
            `);
            
            fetch(`/superadmin/procurement/${procurementId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Terjadi kesalahan saat menolak pengadaan');
                }
            })
            .then(data => {
                if (data.success) {
                    showAlert('Pengadaan berhasil ditolak', 'success');
                    $('#rejectProcurementModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    const errorMessage = data.message || 'Terjadi kesalahan saat menolak pengadaan';
                    showAlert(errorMessage, 'danger');
                    $('#submitRejectBtn').prop('disabled', false).text('Tolak Pengadaan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menolak pengadaan', 'danger');
                $('#submitRejectBtn').prop('disabled', false).text('Tolak Pengadaan');
            });
        });
        
        // Mobile sidebar toggle
        if (window.innerWidth <= 768) {
            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'btn btn-primary position-fixed';
            toggleBtn.style.cssText = 'top: 10px; left: 10px; z-index: 1001; padding: 5px 10px; background-color: var(--superadmin-color); border-color: var(--superadmin-color);';
            toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
            toggleBtn.onclick = function() {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
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
    });
    
    // Fungsi untuk menampilkan alert
    function showAlert(message, type = 'success') {
        // Pastikan alert container ada
        let alertContainer = document.getElementById('alertContainer');
        
        if (!alertContainer) {
            // Buat alert container jika tidak ada
            alertContainer = document.createElement('div');
            alertContainer.className = 'alert-container';
            alertContainer.id = 'alertContainer';
            document.body.appendChild(alertContainer);
        }
        
        // Hapus alert sebelumnya
        const existingAlerts = alertContainer.querySelectorAll('.alert');
        existingAlerts.forEach(alert => {
            const bsAlert = bootstrap.Alert.getInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            } else {
                alert.remove();
            }
        });
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.setAttribute('role', 'alert');
        
        let icon = 'bi-check-circle';
        if (type === 'warning') icon = 'bi-exclamation-triangle';
        if (type === 'danger') icon = 'bi-exclamation-octagon';
        if (type === 'info') icon = 'bi-info-circle';
        
        alert.innerHTML = `
            <i class="bi ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Inisialisasi Bootstrap alert
        const bsAlert = new bootstrap.Alert(alert);
        
        // Auto dismiss setelah 5 detik
        setTimeout(() => {
            if (alert.parentNode === alertContainer) {
                bsAlert.close();
            }
        }, 5000);
    }
    
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Print Function - PERBAIKAN: Menyimpan referensi elemen yang ditambahkan
    let addedElements = [];
    
    function printTable() {
        // Simpan elemen yang akan disembunyikan
        const elementsToHide = document.querySelectorAll('.sidebar, .topbar, .page-header, .stats-grid, .filter-bar, .status-tabs, .action-buttons, .btn-group, .pagination, .alert-container');
        
        // Sembunyikan elemen yang tidak perlu dicetak
        const originalStyles = [];
        elementsToHide.forEach((el, index) => {
            if (el) {
                originalStyles[index] = {
                    element: el,
                    display: el.style.display
                };
                el.style.display = 'none';
            }
        });
        
        // Perlebar tabel untuk cetak
        const tableCard = document.querySelector('.table-card');
        if (tableCard) {
            const originalTableCardStyles = {
                boxShadow: tableCard.style.boxShadow,
                padding: tableCard.style.padding,
                margin: tableCard.style.margin
            };
            
            tableCard.style.boxShadow = 'none';
            tableCard.style.padding = '0';
            tableCard.style.margin = '0';
            
            // Atur margin untuk konten utama
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.style.marginLeft = '0';
                mainContent.style.padding = '20px';
            }
            
            // Tambahkan judul cetak
            const printTitle = document.createElement('h4');
            printTitle.textContent = 'Laporan Validasi Pengadaan Barang - SILOG Polres';
            printTitle.style.textAlign = 'center';
            printTitle.style.marginBottom = '20px';
            printTitle.style.fontWeight = 'bold';
            printTitle.style.color = 'var(--superadmin-color)';
            tableCard.parentNode.insertBefore(printTitle, tableCard);
            addedElements.push(printTitle);
            
            // Tambahkan tanggal cetak
            const printDate = document.createElement('p');
            printDate.textContent = 'Tanggal: ' + new Date().toLocaleDateString('id-ID');
            printDate.style.textAlign = 'center';
            printDate.style.marginBottom = '20px';
            printDate.style.color = '#666';
            tableCard.parentNode.insertBefore(printDate, tableCard.nextSibling);
            addedElements.push(printDate);
            
            // Tambahkan filter info jika ada
            let filterInfo = '';
            const searchInput = document.getElementById('searchInput');
            const tipeFilter = document.getElementById('tipeFilter');
            
            if (searchInput && searchInput.value) {
                filterInfo += `Pencarian: ${searchInput.value}<br>`;
            }
            if (tipeFilter && tipeFilter.value) {
                const selectedTipe = document.querySelector('#tipeFilter option:checked');
                filterInfo += `Tipe: ${selectedTipe ? selectedTipe.text : ''}<br>`;
            }
            
            // Tambahkan status info dari tab aktif
            const activeTab = document.querySelector('.status-tab.active');
            if (activeTab) {
                const statusText = activeTab.textContent;
                if (statusText !== 'Semua') {
                    filterInfo += `Status: ${statusText}<br>`;
                }
            }
            
            if (filterInfo) {
                const filterElement = document.createElement('p');
                filterElement.innerHTML = filterInfo;
                filterElement.style.textAlign = 'center';
                filterElement.style.marginBottom = '20px';
                filterElement.style.color = '#666';
                filterElement.style.fontSize = '0.9rem';
                tableCard.parentNode.insertBefore(filterElement, printDate.nextSibling);
                addedElements.push(filterElement);
            }
            
            // Simpan event listener untuk afterprint
            window.addEventListener('afterprint', restoreAfterPrint);
            
            // Trigger print
            window.print();
        }
    }
    
    // Fungsi untuk mengembalikan tampilan setelah cetak
    function restoreAfterPrint() {
        // Hapus event listener
        window.removeEventListener('afterprint', restoreAfterPrint);
        
        // Kembalikan elemen yang ditambahkan
        addedElements.forEach(element => {
            if (element && element.parentNode) {
                element.parentNode.removeChild(element);
            }
        });
        
        // Kosongkan array
        addedElements = [];
        
        // Kembalikan style asli untuk semua elemen yang diubah
        const tableCard = document.querySelector('.table-card');
        if (tableCard) {
            tableCard.style.boxShadow = '';
            tableCard.style.padding = '';
            tableCard.style.margin = '';
        }
        
        const mainContent = document.querySelector('.main-content');
        if (mainContent) {
            mainContent.style.marginLeft = '';
            mainContent.style.padding = '';
        }
        
        // Tampilkan kembali elemen yang disembunyikan
        const elementsToShow = document.querySelectorAll('.sidebar, .topbar, .page-header, .stats-grid, .filter-bar, .status-tabs, .action-buttons, .btn-group, .pagination, .alert-container');
        elementsToShow.forEach(el => {
            if (el) {
                el.style.display = '';
            }
        });
    }
    
    // Print Detail Function
    function printDetail() {
        const detailContent = document.getElementById('detailProcurementBody');
        if (!detailContent) {
            showAlert('Tidak ada konten untuk dicetak', 'warning');
            return;
        }
        
        const clonedContent = detailContent.cloneNode(true);
        const printWindow = window.open('', '_blank');
        
        const title = document.createElement('h4');
        title.textContent = 'Detail Validasi Pengadaan Barang - SILOG Polres';
        title.style.textAlign = 'center';
        title.style.marginBottom = '20px';
        title.style.fontWeight = 'bold';
        title.style.color = 'var(--superadmin-color)';
        
        const date = document.createElement('p');
        date.textContent = 'Tanggal Cetak: ' + new Date().toLocaleDateString('id-ID');
        date.style.textAlign = 'center';
        date.style.marginBottom = '30px';
        date.style.color = '#666';
        
        printWindow.document.open();
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Detail Validasi Pengadaan Barang - SILOG Polres</title>
                <style>
                    body { 
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                        margin: 40px; 
                        color: #333;
                    }
                    .card { 
                        margin-bottom: 20px; 
                        border: 1px solid #ddd; 
                        border-radius: 8px;
                    }
                    .card-header { 
                        background-color: #f8f9fa; 
                        padding: 12px 15px; 
                        border-bottom: 1px solid #ddd;
                    }
                    .card-body { 
                        padding: 15px; 
                    }
                    .badge { 
                        padding: 4px 8px; 
                        border-radius: 4px; 
                        font-weight: bold; 
                        font-size: 0.85em;
                    }
                    .badge-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }
                    .badge-approved { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; }
                    .badge-completed { background-color: #8b5cf6; color: white; border: 1px solid #7c3aed; }
                    .badge-cancelled { background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
                    .badge-rejected { background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
                    .badge-baru { background-color: #dbeafe; color: #1e40af; border: 1px solid #60a5fa; }
                    .badge-restock { background-color: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }
                    .badge-priority-normal { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; }
                    .badge-priority-tinggi { background-color: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }
                    .badge-priority-mendesak { background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
                    .badge-superadmin { background-color: #8b5cf6; color: white; border: 1px solid #7c3aed; }
                    .text-primary { color: #8b5cf6; } /* Warna superadmin untuk print */
                    .text-muted { color: #6c757d; }
                    .fw-bold { font-weight: 600; }
                    .row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; }
                    .col-5, .col-7, .col-md-6 { position: relative; width: 100%; padding-right: 15px; padding-left: 15px; }
                    .col-5 { flex: 0 0 41.666667%; max-width: 41.666667%; }
                    .col-7 { flex: 0 0 58.333333%; max-width: 58.333333%; }
                    .col-md-6 { flex: 0 0 50%; max-width: 50%; }
                    .mb-2 { margin-bottom: 0.5rem; }
                    .mb-3 { margin-bottom: 1rem; }
                    .mb-4 { margin-bottom: 1.5rem; }
                    .text-center { text-align: center; }
                    .timeline { position: relative; padding-left: 30px; }
                    .timeline-item { position: relative; padding-bottom: 20px; }
                    .timeline-marker { position: absolute; left: -30px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background-color: #dee2e6; }
                    .timeline-content { margin-left: 0; }
                    @media print {
                        body { margin: 0; padding: 20px; }
                        .card { page-break-inside: avoid; }
                    }
                </style>
            </head>
            <body>
                ${title.outerHTML}
                ${date.outerHTML}
                ${clonedContent.innerHTML}
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 1000);
                    }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
    
    // Logout confirmation
    document.querySelector('form[action="{{ route("logout") }}"]')?.addEventListener('submit', function(e) {
        if (!confirm('Apakah Anda yakin ingin logout?')) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>