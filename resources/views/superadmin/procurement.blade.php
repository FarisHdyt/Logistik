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
        
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid rgba(0,0,0,0.1);
            color: var(--dark) !important;
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
        
        .badge-item-pending {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border-color: #fbbf24;
        }
        
        .badge-item-approved {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border-color: #10b981;
        }
        
        .badge-item-rejected {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border-color: #f87171;
        }
        
        .badge-item-cancelled {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border-color: #fbbf24;
        }
        
        .badge-item-completed {
            background-color: #8b5cf6 !important;
            color: white !important;
            border-color: #7c3aed;
        }
        
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
        
        .badge-multi {
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
            border-color: #f87171;
        }
        
        .badge-superadmin {
            background-color: var(--superadmin-color) !important;
            color: white !important;
            border-color: var(--superadmin-color);
        }
        
        .multi-item-badge {
            background-color: #0ea5e9;
            color: white;
            padding: 0.1rem 0.4rem;
            border-radius: 3px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }
        
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
        
        /* PERBAIKAN: Tambahkan min-width untuk tabel pada mobile */
        .table {
            min-width: 800px;
        }
        
        /* PERBAIKAN: Perbaikan table-responsive untuk mobile */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* PERBAIKAN: Pastikan tabel tetap responsif pada mobile */
        @media (max-width: 768px) {
            .table-responsive {
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
            }
            
            .table {
                margin-bottom: 0;
                min-width: 100%;
            }
            
            .table td, .table th {
                white-space: nowrap;
                vertical-align: middle;
            }
            
            .table td:nth-child(2),  /* Kode Pengadaan */
            .table td:nth-child(4),  /* Tipe */
            .table td:nth-child(5),  /* Jumlah */
            .table td:nth-child(6),  /* Total Nilai */
            .table td:nth-child(7),  /* Prioritas */
            .table td:nth-child(8),  /* Status */
            .table td:nth-child(9) { /* Tanggal */
                min-width: 120px;
            }
            
            .table td:nth-child(3) { /* Item Barang */
                min-width: 200px;
                white-space: normal !important;
            }
            
            .table td:nth-child(10) { /* Aksi */
                min-width: 150px;
            }
        }
        
        .filter-bar {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        
        .multi-item-container {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.75rem;
            background-color: #f8fafc;
            margin-top: 0.5rem;
        }
        
        .multi-item-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .multi-item-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.85rem;
        }
        
        .multi-item-list li {
            padding: 0.25rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .multi-item-list li:last-child {
            border-bottom: none;
        }
        
        .item-kode {
            background-color: #e0f2fe;
            color: #0369a1;
            padding: 0.1rem 0.3rem;
            border-radius: 3px;
            font-family: monospace;
            font-size: 0.75rem;
            margin-right: 0.3rem;
        }
        
        .item-detail {
            color: #64748b;
            font-size: 0.8rem;
            display: block;
        }
        
        /* Item Status Badges in Multi Item List */
        .item-status-badge {
            font-size: 0.65rem;
            padding: 0.1rem 0.3rem;
            border-radius: 3px;
            margin-left: 0.5rem;
            font-weight: 600;
        }
        
        .item-status-completed {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        .item-status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
        
        .item-status-cancelled {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        
        .item-status-pending {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #0ea5e9;
        }
        
        .item-status-approved {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #60a5fa;
        }
        
        /* Rejected/Cancelled Item Styling */
        .item-rejected {
            opacity: 0.7;
            text-decoration: line-through;
            color: #991b1b;
        }
        
        .item-cancelled {
            opacity: 0.7;
            color: #92400e;
        }
        
        .item-rejected .item-detail,
        .item-cancelled .item-detail {
            color: inherit;
        }
        
        .rejection-reason {
            background-color: #fee2e2;
            border: 1px solid #f87171;
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: #991b1b;
        }
        
        .total-summary {
            background-color: #f0f9ff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }
        
        .total-summary span {
            font-weight: 600;
            color: #0369a1;
        }
        
        /* Summary Stats in Multi Item */
        .summary-stats {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            flex-wrap: wrap;
        }
        
        .stat-approved {
            color: #065f46;
            background-color: #d1fae5;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            border: 1px solid #10b981;
        }
        
        .stat-rejected {
            color: #991b1b;
            background-color: #fee2e2;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            border: 1px solid #f87171;
        }
        
        .stat-cancelled {
            color: #92400e;
            background-color: #fef3c7;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            border: 1px solid #fbbf24;
        }
        
        .stat-pending {
            color: #0369a1;
            background-color: #e0f2fe;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            border: 1px solid #0ea5e9;
        }
        
        .stat-completed {
            color: #6d28d9;
            background-color: #ede9fe;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            border: 1px solid #8b5cf6;
        }
        
        .item-validation-checkbox {
            margin-right: 0.5rem;
            transform: scale(1.2);
        }
        
        .validation-summary {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .validation-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .item-validation-row {
            transition: background-color 0.3s;
        }
        
        .item-validation-row:hover {
            background-color: #f8fafc;
        }
        
        .item-validation-row.selected {
            background-color: #e0f2fe;
        }
        
        .validation-status-badge {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
        }
        
        .btn-reject-all-custom {
            background-color: #fee2e2 !important;
            border-color: #f87171 !important;
            color: #991b1b !important;
        }
        
        .btn-reject-all-custom:hover {
            background-color: #fecaca !important;
            border-color: #ef4444 !important;
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
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .summary-stats {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            /* PERBAIKAN: Pastikan filter bar responsif */
            .filter-bar .row {
                flex-direction: column;
            }
            
            .filter-bar .col-md-4 {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .filter-bar .d-flex {
                flex-direction: column;
            }
            
            .filter-bar .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            /* PERBAIKAN: Tombol aksi di tabel untuk mobile */
            .btn-group {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .btn-group .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
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
        <!-- Top Bar -->
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
        <div class="alert-container" id="alertContainer">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>
        
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Validasi Pengajuan Pengadaan</h5>
                    <p class="text-muted mb-0">Tinjau dan validasi pengajuan pengadaan barang</p>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-warning btn-action" onclick="printTable()">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
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
                <div class="stat-icon" style="background-color: #d1fae5; color: var(--success);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['approved'] ?? 0 }}</h3>
                    <p>Telah Disetujui</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: #8b5cf6; color: white;">
                    <i class="bi bi-check2-all"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['completed'] ?? 0 }}</h3>
                    <p>Selesai</p>
                </div>
            </div>
        </div>
        
        <!-- Filter Bar -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('superadmin.procurement') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="searchInput" name="search" 
                               placeholder="Cari barang..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="tipeFilter" name="tipe">
                            <option value="">Semua Tipe</option>
                            <option value="baru" {{ request('tipe') == 'baru' ? 'selected' : '' }}>Barang Baru</option>
                            <option value="restock" {{ request('tipe') == 'restock' ? 'selected' : '' }}>Restock</option>
                            <option value="multi" {{ request('tipe') == 'multi' ? 'selected' : '' }}>Multi Item</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="priorityFilter" name="priority">
                            <option value="">Semua Prioritas</option>
                            <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="tinggi" {{ request('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="mendesak" {{ request('priority') == 'mendesak' ? 'selected' : '' }}>Mendesak</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1" style="background-color: var(--superadmin-color); border-color: var(--superadmin-color);">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->has('search') || request()->has('tipe') || request()->has('priority'))
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
            <a href="{{ route('superadmin.procurement', ['status' => 'rejected']) }}" class="status-tab {{ request('status') == 'rejected' ? 'active' : '' }}>Ditolak</a>
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
                            <th>Kode Pengadaan</th>
                            <th>Item Barang</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Total Nilai</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Tanggal Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($procurements) && $procurements->count() > 0)
                            @foreach($procurements as $index => $procurement)
                            @php
                                // PERBAIKAN UTAMA: Gunakan method isMultiItem() dari model Procurement
                                $items = $procurement->items ?? collect();
                                $itemCount = $items->count();
                                
                                // Menggunakan method isMultiItem() dari model Procurement
                                // Method ini sudah menangani logika: cek items count > 1 atau is_multi_item = true
                                $isMultiItem = $procurement->isMultiItem();
                                
                                $firstItem = $itemCount > 0 ? $items->first() : null;
                                
                                // Tentukan tipe pengadaan yang benar
                                $tipePengadaan = $procurement->tipe_pengadaan ?? 'baru';
                                
                                // Jika ini multi-item, tampilkan sebagai 'multi'
                                if ($isMultiItem && $itemCount > 1) {
                                    $tipePengadaan = 'multi';
                                }
                                
                                // Hitung statistik item
                                $itemStats = [
                                    'total' => $itemCount,
                                    'approved' => $items->where('status', 'approved')->count(),
                                    'completed' => $items->where('status', 'completed')->count(),
                                    'rejected' => $items->where('status', 'rejected')->count(),
                                    'cancelled' => $items->where('status', 'cancelled')->count(),
                                    'pending' => $items->where('status', 'pending')->count(),
                                ];
                                
                                // Hitung total jumlah dan nilai
                                $totalJumlah = 0;
                                $totalNilai = 0;
                                
                                if ($isMultiItem) {
                                    // Untuk multi-item: hitung total dari semua item
                                    $totalJumlah = $items->sum('jumlah');
                                    $totalNilai = $items->sum(function($item) {
                                        return ($item->jumlah ?? 0) * ($item->harga_perkiraan ?? 0);
                                    });
                                } else {
                                    // Untuk single item
                                    if ($firstItem) {
                                        $totalJumlah = $firstItem->jumlah ?? 0;
                                        $totalNilai = ($firstItem->jumlah ?? 0) * ($firstItem->harga_perkiraan ?? 0);
                                    } else {
                                        // Fallback untuk data lama (backward compatibility)
                                        $totalJumlah = $procurement->jumlah ?? 0;
                                        $totalNilai = ($procurement->jumlah ?? 0) * ($procurement->harga_perkiraan ?? 0);
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ ($procurements->currentPage() - 1) * $procurements->perPage() + $index + 1 }}</td>
                                <td>
                                    <strong>{{ $procurement->kode_pengadaan ?? 'P-' . str_pad($procurement->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                </td>
                                <td>
                                    @if($isMultiItem && $itemCount > 1)
                                        <!-- Tampilan Multi Item -->
                                        <div class="multi-item-container">
                                            <div class="multi-item-header">
                                                <span class="multi-item-badge">{{ $itemCount }} ITEM</span>
                                                <strong>Pengadaan Multi Item</strong>
                                            </div>
                                            <ul class="multi-item-list">
                                                @foreach($items as $item)
                                                @php
                                                    $itemClass = '';
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    
                                                    switch($item->status) {
                                                        case 'completed':
                                                            $statusClass = 'item-status-completed';
                                                            $statusText = 'Selesai';
                                                            break;
                                                        case 'rejected':
                                                            $itemClass = 'item-rejected';
                                                            $statusClass = 'item-status-rejected';
                                                            $statusText = 'Ditolak';
                                                            break;
                                                        case 'cancelled':
                                                            $itemClass = 'item-cancelled';
                                                            $statusClass = 'item-status-cancelled';
                                                            $statusText = 'Dibatalkan';
                                                            break;
                                                        case 'approved':
                                                            $statusClass = 'item-status-approved';
                                                            $statusText = 'Disetujui';
                                                            break;
                                                        case 'pending':
                                                        default:
                                                            $statusClass = 'item-status-pending';
                                                            $statusText = 'Menunggu';
                                                            break;
                                                    }
                                                @endphp
                                                <li class="{{ $itemClass }}">
                                                    <span class="item-kode">{{ $item->kode_barang ?? 'N/A' }}</span>
                                                    <strong>{{ $item->nama_barang ?? 'Item' }}</strong>
                                                    <span class="item-status-badge {{ $statusClass }}">
                                                        {{ $statusText }}
                                                    </span>
                                                    <span class="item-detail">
                                                        {{ $item->jumlah ?? 0 }} unit @ Rp {{ number_format($item->harga_perkiraan ?? 0, 0, ',', '.') }} per unit
                                                    </span>
                                                    @if(in_array($item->status, ['rejected', 'cancelled']) && $item->alasan_penolakan)
                                                    <div class="rejection-reason">
                                                        <small><strong>Alasan:</strong> {{ $item->alasan_penolakan }}</small>
                                                    </div>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                            
                                            <!-- Summary Stats -->
                                            @if($itemStats['rejected'] > 0 || $itemStats['cancelled'] > 0 || $itemStats['completed'] > 0 || $itemStats['approved'] > 0)
                                            <div class="summary-stats">
                                                @if($itemStats['approved'] > 0)
                                                <span class="stat-approved">
                                                    <i class="bi bi-check-circle"></i> {{ $itemStats['approved'] }} Disetujui
                                                </span>
                                                @endif
                                                @if($itemStats['completed'] > 0)
                                                <span class="stat-completed">
                                                    <i class="bi bi-check-circle-fill"></i> {{ $itemStats['completed'] }} Selesai
                                                </span>
                                                @endif
                                                @if($itemStats['rejected'] > 0)
                                                <span class="stat-rejected">
                                                    <i class="bi bi-x-circle"></i> {{ $itemStats['rejected'] }} Ditolak
                                                </span>
                                                @endif
                                                @if($itemStats['cancelled'] > 0)
                                                <span class="stat-cancelled">
                                                    <i class="bi bi-x-circle-fill"></i> {{ $itemStats['cancelled'] }} Dibatalkan
                                                </span>
                                                @endif
                                                @if($itemStats['pending'] > 0)
                                                <span class="stat-pending">
                                                    <i class="bi bi-clock"></i> {{ $itemStats['pending'] }} Menunggu
                                                </span>
                                                @endif
                                            </div>
                                            @endif
                                            
                                            <div class="total-summary">
                                                Total: <span>{{ $totalJumlah }} unit</span> • 
                                                Nilai: <span>Rp {{ number_format($totalNilai, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @elseif($firstItem)
                                        <!-- Tampilan Single Item -->
                                        @php
                                            $itemClass = '';
                                            $statusText = '';
                                            $statusClass = '';
                                            
                                            if(in_array($firstItem->status, ['rejected', 'cancelled'])) {
                                                $itemClass = 'item-rejected';
                                            }
                                            
                                            switch($firstItem->status) {
                                                case 'completed':
                                                    $statusText = 'Selesai';
                                                    $statusClass = 'item-status-completed';
                                                    break;
                                                case 'rejected':
                                                    $statusText = 'Ditolak';
                                                    $statusClass = 'item-status-rejected';
                                                    break;
                                                case 'cancelled':
                                                    $statusText = 'Dibatalkan';
                                                    $statusClass = 'item-status-cancelled';
                                                    break;
                                                case 'approved':
                                                    $statusText = 'Disetujui';
                                                    $statusClass = 'item-status-approved';
                                                    break;
                                                case 'pending':
                                                default:
                                                    $statusText = 'Menunggu';
                                                    $statusClass = 'item-status-pending';
                                                    break;
                                            }
                                        @endphp
                                        <div class="{{ $itemClass }}">
                                            <strong>{{ $firstItem->kode_barang ?? 'N/A' }}</strong><br>
                                            <small>{{ $firstItem->nama_barang ?? 'Barang' }}</small>
                                            @if($statusText)
                                            <br><span class="item-status-badge {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                            @endif
                                            @if(in_array($firstItem->status, ['rejected', 'cancelled']) && $firstItem->alasan_penolakan)
                                            <div class="rejection-reason mt-1">
                                                <small><strong>Alasan:</strong> {{ $firstItem->alasan_penolakan }}</small>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Fallback untuk data lama -->
                                        <strong>{{ $procurement->kode_barang ?? 'N/A' }}</strong><br>
                                        <small>{{ $procurement->nama_barang ?? 'Barang' }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($tipePengadaan == 'multi')
                                    <span class="badge badge-multi">
                                        Multi Item
                                    </span>
                                    @elseif($tipePengadaan == 'baru')
                                    <span class="badge badge-baru">
                                        Baru
                                    </span>
                                    @else
                                    <span class="badge badge-restock">
                                        Restock
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $totalJumlah }}
                                </td>
                                <td>
                                    Rp {{ number_format($totalNilai, 0, ',', '.') }}
                                </td>
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
                                        
                                        @if($procurement->status == 'pending')
                                            <!-- Untuk single item -->
                                            @if(!$isMultiItem || $itemCount <= 1)
                                            <button type="button" class="btn btn-success btn-sm approve-procurement" 
                                                    data-id="{{ $procurement->id }}" title="Setujui Semua">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm reject-procurement" 
                                                    data-id="{{ $procurement->id }}" title="Tolak Semua">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                            @else
                                            <!-- Custom validation untuk multi-item -->
                                            <button type="button" class="btn btn-warning btn-sm custom-approve-procurement" 
                                                    data-id="{{ $procurement->id }}" title="Validasi Custom">
                                                <i class="bi bi-check2-all"></i>
                                            </button>
                                            @endif
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
                                        @if(request()->has('search') || request()->has('status') || request()->has('tipe') || request()->has('priority'))
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
                            @if ($procurements->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link" aria-hidden="true">&laquo; Sebelumnya</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $procurements->previousPageUrl() }}" rel="prev">&laquo; Sebelumnya</a>
                                </li>
                            @endif

                            @foreach ($procurements->getUrlRange(1, $procurements->lastPage()) as $page => $url)
                                @if ($page == $procurements->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if ($procurements->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $procurements->nextPageUrl() }}" rel="next">Selanjutnya &raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled" aria-disabled="true">
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
    
    <!-- Detail Modal -->
    <div class="modal fade" id="detailProcurementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengajuan Pengadaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailProcurementBody">
                    <!-- Detail akan diisi dengan JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Custom Validation Modal -->
    <div class="modal fade" id="customValidationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Validasi Custom Pengadaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="customValidationForm">
                    @csrf
                    <div class="modal-body" id="customValidationBody">
                        <!-- Konten akan diisi dengan JavaScript -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="submitCustomValidationBtn">
                            <i class="bi bi-check-circle me-1"></i> Submit Validasi
                        </button>
                        <button type="button" class="btn btn-reject-all-custom" id="rejectAllCustomBtn">
                            <i class="bi bi-x-circle me-1"></i> Tolak Semua
                        </button>
                    </div>
                </form>
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
                        <p>Apakah Anda yakin ingin menyetujui seluruh pengadaan ini?</p>
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
                            <i class="bi bi-check-circle me-1"></i> Setujui Semua
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
                            </label>
                            <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" 
                                      rows="3" placeholder="Masukkan alasan penolakan" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger" id="submitRejectBtn">Tolak Semua</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Bootstrap modals
    const detailModal = new bootstrap.Modal(document.getElementById('detailProcurementModal'));
    const customValidationModal = new bootstrap.Modal(document.getElementById('customValidationModal'));
    const approveModal = new bootstrap.Modal(document.getElementById('approveProcurementModal'));
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectProcurementModal'));
    
    // Helper functions
    function getStatusDisplay(status) {
        const statusMap = {
            'pending': 'Menunggu',
            'approved': 'Disetujui',
            'completed': 'Selesai',
            'cancelled': 'Dibatalkan',
            'rejected': 'Ditolak'
        };
        return statusMap[status] || status.charAt(0).toUpperCase() + status.slice(1);
    }
    
    function getItemStatusText(status) {
        const statusMap = {
            'pending': 'Menunggu',
            'approved': 'Disetujui',
            'completed': 'Selesai',
            'rejected': 'Ditolak',
            'cancelled': 'Dibatalkan'
        };
        return statusMap[status] || status.charAt(0).toUpperCase() + status.slice(1);
    }
    
    function getItemStatusClass(status) {
        const classMap = {
            'pending': 'item-status-pending',
            'approved': 'item-status-approved',
            'completed': 'item-status-completed',
            'rejected': 'item-status-rejected',
            'cancelled': 'item-status-cancelled'
        };
        return classMap[status] || 'item-status-pending';
    }
    
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }
    
    function formatNumber(num) {
        if (!num) return '0';
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Show alert function
    function showAlert(message, type = 'success') {
        const alertContainer = $('#alertContainer');
        
        // Hapus alert sebelumnya
        alertContainer.find('.alert').alert('close');
        
        let icon = 'bi-check-circle';
        if (type === 'warning') icon = 'bi-exclamation-triangle';
        if (type === 'danger') icon = 'bi-exclamation-octagon';
        if (type === 'info') icon = 'bi-info-circle';
        
        const alert = $(`
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="bi ${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
        
        alertContainer.append(alert);
        
        // Auto dismiss setelah 5 detik
        setTimeout(() => {
            alert.alert('close');
        }, 5000);
    }
    
    // Auto dismiss alerts
    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
    
    // Submit form filter dengan Enter
    $('#searchInput').keypress(function(e) {
        if (e.which == 13) {
            $('#filterForm').submit();
            return false;
        }
    });
    
    // View detail procurement - menggunakan event delegation yang tepat
    $(document).on('click', '.view-procurement', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
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
        
        // Show modal first
        detailModal.show();
        
        // Fetch data
        $.ajax({
            url: "{{ url('superadmin/procurement') }}/" + procurementId,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.error) {
                    $('#detailProcurementBody').html(`
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ${response.error}
                        </div>
                    `);
                    return;
                }
                
                const procurement = response.procurement;
                const items = procurement.items || [];
                const isMultiItem = items.length > 1;
                
                // Pastikan kode_pengadaan ada
                const kodePengadaan = procurement.kode_pengadaan || 
                                      'P-' + procurement.id.toString().padStart(6, '0');
                
                // Hitung statistik item
                const itemStats = {
                    approved: items.filter(item => item.status === 'approved').length,
                    completed: items.filter(item => item.status === 'completed').length,
                    rejected: items.filter(item => item.status === 'rejected').length,
                    cancelled: items.filter(item => item.status === 'cancelled').length,
                    pending: items.filter(item => item.status === 'pending').length,
                    total: items.length
                };
                
                // Hitung total hanya untuk item yang tidak ditolak/dibatalkan
                const validItems = items.filter(item => !['rejected', 'cancelled'].includes(item.status));
                let totalJumlah = 0;
                let totalNilai = 0;
                
                validItems.forEach(item => {
                    const jumlah = parseInt(item.jumlah) || 0;
                    const harga = parseFloat(item.harga_perkiraan) || 0;
                    totalJumlah += jumlah;
                    totalNilai += (jumlah * harga);
                });
                
                // Tentukan tipe pengadaan yang benar
                const tipePengadaan = isMultiItem ? 'multi' : procurement.tipe_pengadaan;
                
                // Build HTML content
                let html = `
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title mb-3" style="color: var(--superadmin-color);">
                                        <i class="bi bi-info-circle me-2"></i>Informasi Pengadaan
                                    </h6>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Kode Pengadaan:</div>
                                        <div class="col-7"><code>${kodePengadaan}</code></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Tipe Pengadaan:</div>
                                        <div class="col-7">
                                            ${tipePengadaan === 'multi' ? `
                                            <span class="badge badge-multi">Multi Item</span>
                                            ` : tipePengadaan === 'baru' ? `
                                            <span class="badge badge-baru">Baru</span>
                                            ` : `
                                            <span class="badge badge-restock">Restock</span>
                                            `}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Status:</div>
                                        <div class="col-7">
                                            <span class="badge badge-${procurement.status}">
                                                ${getStatusDisplay(procurement.status)}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Prioritas:</div>
                                        <div class="col-7">
                                            <span class="badge badge-priority-${procurement.prioritas}">
                                                ${procurement.prioritas === 'mendesak' ? 'Mendesak' : procurement.prioritas === 'tinggi' ? 'Tinggi' : 'Normal'}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Tanggal Diajukan:</div>
                                        <div class="col-7">${formatDate(procurement.created_at)}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 fw-bold">Alasan Pengadaan:</div>
                                        <div class="col-7">${procurement.alasan_pengadaan || '-'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title mb-3" style="color: var(--superadmin-color);">
                                        <i class="bi bi-calculator me-2"></i>Ringkasan
                                    </h6>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Jumlah Item:</div>
                                        <div class="col-7">
                                            ${itemStats.total} item 
                                            ${isMultiItem ? `(${itemStats.approved} disetujui, ${itemStats.completed} selesai, ${itemStats.rejected} ditolak, ${itemStats.cancelled} dibatalkan, ${itemStats.pending} menunggu)` : ''}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Total Jumlah:</div>
                                        <div class="col-7">${formatNumber(totalJumlah)} unit</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Total Nilai:</div>
                                        <div class="col-7 fw-bold" style="color: var(--superadmin-color);">
                                            Rp ${formatNumber(totalNilai)}
                                        </div>
                                    </div>
                                    ${procurement.catatan ? `
                                    <div class="row">
                                        <div class="col-5 fw-bold">Catatan:</div>
                                        <div class="col-7">${procurement.catatan}</div>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Daftar Barang dengan status
                html += `
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0" style="color: var(--superadmin-color);">
                                <i class="bi bi-list-check me-2"></i>Daftar Barang
                                ${isMultiItem ? '<span class="badge bg-info">' + items.length + ' Item</span>' : ''}
                            </h6>
                            ${itemStats.rejected > 0 || itemStats.cancelled > 0 || itemStats.completed > 0 || itemStats.approved > 0 ? `
                            <div class="summary-stats">
                                ${itemStats.approved > 0 ? `<span class="stat-approved"><i class="bi bi-check-circle"></i> ${itemStats.approved} Disetujui</span>` : ''}
                                ${itemStats.completed > 0 ? `<span class="stat-completed"><i class="bi bi-check-circle-fill"></i> ${itemStats.completed} Selesai</span>` : ''}
                                ${itemStats.rejected > 0 ? `<span class="stat-rejected"><i class="bi bi-x-circle"></i> ${itemStats.rejected} Ditolak</span>` : ''}
                                ${itemStats.cancelled > 0 ? `<span class="stat-cancelled"><i class="bi bi-x-circle-fill"></i> ${itemStats.cancelled} Dibatalkan</span>` : ''}
                                ${itemStats.pending > 0 ? `<span class="stat-pending"><i class="bi bi-clock"></i> ${itemStats.pending} Menunggu</span>` : ''}
                            </div>
                            ` : ''}
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Status</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th>Satuan</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-end">Harga/Unit</th>
                                            <th class="text-end">Subtotal</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                `;
                
                // Items table rows dengan status
                if (items && items.length > 0) {
                    items.forEach((item, index) => {
                        const jumlah = parseInt(item.jumlah) || 0;
                        const harga = parseFloat(item.harga_perkiraan) || 0;
                        const subtotal = jumlah * harga;
                        const statusClass = getItemStatusClass(item.status);
                        const statusText = getItemStatusText(item.status);
                        
                        let rowClass = '';
                        if (item.status === 'rejected') rowClass = 'table-danger';
                        else if (item.status === 'cancelled') rowClass = 'table-warning';
                        else if (item.status === 'completed') rowClass = 'table-success';
                        else if (item.status === 'approved') rowClass = 'table-primary';
                        else if (item.status === 'pending') rowClass = 'table-secondary';
                        
                        html += `
                            <tr class="${rowClass}">
                                <td>${index + 1}</td>
                                <td>
                                    <span class="item-status-badge ${statusClass}">
                                        ${statusText}
                                    </span>
                                </td>
                                <td><code>${item.kode_barang || '-'}</code></td>
                                <td>${item.nama_barang || '-'}</td>
                                <td>${item.kategori || '-'}</td>
                                <td>${item.satuan || '-'}</td>
                                <td class="text-center">${formatNumber(jumlah)}</td>
                                <td class="text-end">Rp ${formatNumber(harga)}</td>
                                <td class="text-end fw-bold">Rp ${formatNumber(subtotal)}</td>
                                <td>
                                    ${item.alasan_penolakan ? `<small class="text-danger"><strong>Alasan:</strong> ${item.alasan_penolakan}</small>` : ''}
                                    ${item.deskripsi ? `<br><small>${item.deskripsi}</small>` : ''}
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    // Fallback jika tidak ada items
                    html += `
                        <tr>
                            <td colspan="10" class="text-center py-3">
                                <i class="bi bi-exclamation-circle text-muted"></i>
                                Tidak ada data item
                            </td>
                        </tr>
                    `;
                }
                
                html += `
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="6" class="text-end">TOTAL (Item yang disetujui):</th>
                                            <th class="text-center">${formatNumber(totalJumlah)}</th>
                                            <th></th>
                                            <th class="text-end fw-bold" style="color: var(--superadmin-color);">Rp ${formatNumber(totalNilai)}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
                
                // Informasi Pemohon
                if (procurement.user) {
                    html += `
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0" style="color: var(--superadmin-color);">
                                    <i class="bi bi-person me-2"></i>Informasi Pemohon
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Nama Pemohon:</div>
                                    <div class="col-8">${procurement.user.name || '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class-4 fw-bold">Username:</div>
                                    <div class="col-8">${procurement.user.username || '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Email:</div>
                                    <div class="col-8">${procurement.user.email || '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Jabatan:</div>
                                    <div class="col-8">${procurement.user.jabatan || '-'}</div>
                                </div>
                                ${procurement.user.satker ? `
                                <div class="row">
                                    <div class="col-4 fw-bold">Satker:</div>
                                    <div class="col-8">${procurement.user.satker.nama_satker || '-'}</div>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }
                
                // Timeline jika ada data
                if (procurement.created_at || procurement.approved_at || procurement.rejected_at || procurement.cancelled_at) {
                    html += `
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0" style="color: var(--superadmin-color);">
                                    <i class="bi bi-clock-history me-2"></i>Timeline
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline" style="position: relative; padding-left: 30px;">
                    `;
                    
                    if (procurement.created_at) {
                        html += `
                            <div class="timeline-item" style="position: relative; padding-bottom: 20px;">
                                <div class="timeline-marker" style="position: absolute; left: -30px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background-color: #dee2e6;"></div>
                                <div class="timeline-content">
                                    <strong>Diajukan</strong>
                                    <div class="text-muted small">${formatDate(procurement.created_at)}</div>
                                </div>
                            </div>
                        `;
                    }
                    
                    if (procurement.approved_at) {
                        html += `
                            <div class="timeline-item" style="position: relative; padding-bottom: 20px;">
                                <div class="timeline-marker" style="position: absolute; left: -30px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background-color: #10b981;"></div>
                                <div class="timeline-content">
                                    <strong>Disetujui</strong>
                                    <div class="text-muted small">${formatDate(procurement.approved_at)}</div>
                                </div>
                            </div>
                        `;
                    }
                    
                    if (procurement.rejected_at) {
                        html += `
                            <div class="timeline-item" style="position: relative; padding-bottom: 20px;">
                                <div class="timeline-marker" style="position: absolute; left: -30px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background-color: #dc2626;"></div>
                                <div class="timeline-content">
                                    <strong>Ditolak</strong>
                                    <div class="text-muted small">${formatDate(procurement.rejected_at)}</div>
                                    ${procurement.alasan_penolakan ? `
                                    <div class="alert alert-danger mt-2 mb-0 p-2">
                                        <small><strong>Alasan:</strong> ${procurement.alasan_penolakan}</small>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }
                    
                    if (procurement.cancelled_at) {
                        html += `
                            <div class="timeline-item" style="position: relative; padding-bottom: 20px;">
                                <div class="timeline-marker" style="position: absolute; left: -30px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background-color: #dc2626;"></div>
                                <div class="timeline-content">
                                    <strong>Dibatalkan</strong>
                                    <div class="text-muted small">${formatDate(procurement.cancelled_at)}</div>
                                    ${procurement.alasan_pembatalan ? `
                                    <div class="alert alert-danger mt-2 mb-0 p-2">
                                        <small><strong>Alasan:</strong> ${procurement.alasan_pembatalan}</small>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }
                    
                    if (procurement.completed_at) {
                        html += `
                            <div class="timeline-item" style="position: relative; padding-bottom: 20px;">
                                <div class="timeline-marker" style="position: absolute; left: -30px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background-color: var(--superadmin-color);"></div>
                                <div class="timeline-content">
                                    <strong>Selesai</strong>
                                    <div class="text-muted small">${formatDate(procurement.completed_at)}</div>
                                </div>
                            </div>
                        `;
                    }
                    
                    html += `
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                $('#detailProcurementBody').html(html);
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText);
                $('#detailProcurementBody').html(`
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                        <h5 class="mt-3 text-danger">Gagal memuat data pengadaan</h5>
                        <p class="text-muted">Terjadi kesalahan saat memuat data</p>
                    </div>
                `);
            }
        });
    });
    
    // Custom validation modal - FIXED: Perbaikan event handler dengan event delegation yang tepat
    $(document).on('click', '.custom-approve-procurement', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const procurementId = $(this).data('id');
        console.log('Tombol custom-approve ditekan untuk ID:', procurementId);
        
        // Tampilkan loading state
        $('#customValidationBody').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status" style="color: var(--superadmin-color);">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data pengadaan...</p>
            </div>
        `);
        
        // Reset form
        $('#customValidationForm')[0].reset();
        
        // Show modal
        customValidationModal.show();
        
        // Fetch data
        $.ajax({
            url: "{{ url('superadmin/procurement') }}/" + procurementId,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.error) {
                    $('#customValidationBody').html(`
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ${response.error}
                        </div>
                    `);
                    return;
                }
                
                const procurement = response.procurement;
                const items = procurement.items || [];
                
                // Build HTML for custom validation
                let html = `
                    <input type="hidden" name="procurement_id" value="${procurementId}">
                    <div class="validation-summary">
                        <h6 class="mb-3" style="color: var(--superadmin-color);">
                            <i class="bi bi-check2-all me-2"></i>Validasi Custom Pengadaan
                        </h6>
                        <p class="mb-2">Pilih item yang akan disetujui:</p>
                        <p class="text-muted small mb-0">
                            <strong>Item yang dicentang akan disetujui, item yang tidak dicentang akan ditolak.</strong><br>
                            Anda bisa memilih semua, sebagian, atau bahkan tidak memilih sama sekali (menolak semua item).
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="catatan_umum" class="form-label">
                            Catatan Umum (Opsional)
                        </label>
                        <textarea class="form-control" id="catatan_umum" name="catatan_umum" 
                                  rows="2" placeholder="Tambahkan catatan umum untuk pengadaan ini"></textarea>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" id="selectAllItems" class="item-validation-checkbox" checked>
                                    </th>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga/Unit</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                items.forEach((item, index) => {
                    const jumlah = parseInt(item.jumlah) || 0;
                    const harga = parseFloat(item.harga_perkiraan) || 0;
                    const subtotal = jumlah * harga;
                    const itemId = item.id || index;
                    
                    html += `
                        <tr class="item-validation-row selected" data-item-id="${itemId}">
                            <td>
                                <input type="checkbox" class="item-validation-checkbox item-checkbox" 
                                       name="approved_items[]" value="${itemId}" checked 
                                       data-jumlah="${jumlah}" data-harga="${harga}" data-subtotal="${subtotal}">
                            </td>
                            <td>${index + 1}</td>
                            <td><code>${item.kode_barang || '-'}</code></td>
                            <td>${item.nama_barang || '-'}</td>
                            <td class="text-center">${formatNumber(jumlah)}</td>
                            <td class="text-end">Rp ${formatNumber(harga)}</td>
                            <td class="text-end fw-bold">Rp ${formatNumber(subtotal)}</td>
                        </tr>
                    `;
                });
                
                const totalJumlah = items.reduce((sum, item) => sum + (parseInt(item.jumlah) || 0), 0);
                const totalNilai = items.reduce((sum, item) => {
                    const jumlah = parseInt(item.jumlah) || 0;
                    const harga = parseFloat(item.harga_perkiraan) || 0;
                    return sum + (jumlah * harga);
                }, 0);
                
                html += `
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">TOTAL YANG DISETUJUI:</td>
                                    <td class="text-center fw-bold" id="approvedTotalJumlah">${formatNumber(totalJumlah)}</td>
                                    <td></td>
                                    <td class="text-end fw-bold" style="color: var(--superadmin-color);" id="approvedTotalNilai">Rp ${formatNumber(totalNilai)}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">TOTAL YANG DITOLAK:</td>
                                    <td class="text-center fw-bold" id="rejectedTotalJumlah">0</td>
                                    <td></td>
                                    <td class="text-end fw-bold text-danger" id="rejectedTotalNilai">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mb-3">
                        <label for="alasan_penolakan_items" class="form-label">
                            Alasan Penolakan Item (Opsional, untuk item yang ditolak)
                        </label>
                        <textarea class="form-control" id="alasan_penolakan_items" name="alasan_penolakan_items" 
                                  rows="3" placeholder="Masukkan alasan penolakan untuk item yang tidak disetujui"></textarea>
                    </div>
                    
                    <div class="validation-actions">
                        <button type="button" class="btn btn-outline-primary" id="selectAllBtn">
                            <i class="bi bi-check2-square me-1"></i> Pilih Semua
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="deselectAllBtn">
                            <i class="bi bi-x-square me-1"></i> Batalkan Semua
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="rejectAllItemsBtn">
                            <i class="bi bi-x-circle me-1"></i> Tolak Semua Item
                        </button>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Informasi:</strong> Anda bisa memilih semua item untuk disetujui, sebagian item, atau bahkan tidak memilih sama sekali untuk menolak semua item.
                    </div>
                `;
                
                $('#customValidationBody').html(html);
                
                // Fungsi untuk update summary
                function updateValidationSummary() {
                    let approvedJumlah = 0;
                    let approvedNilai = 0;
                    let rejectedJumlah = 0;
                    let rejectedNilai = 0;
                    
                    $('.item-checkbox:checked').each(function() {
                        const jumlah = parseInt($(this).data('jumlah')) || 0;
                        const harga = parseFloat($(this).data('harga')) || 0;
                        approvedJumlah += jumlah;
                        approvedNilai += (jumlah * harga);
                    });
                    
                    $('.item-checkbox').not(':checked').each(function() {
                        const jumlah = parseInt($(this).data('jumlah')) || 0;
                        const harga = parseFloat($(this).data('harga')) || 0;
                        rejectedJumlah += jumlah;
                        rejectedNilai += (jumlah * harga);
                    });
                    
                    $('#approvedTotalJumlah').text(formatNumber(approvedJumlah));
                    $('#approvedTotalNilai').text('Rp ' + formatNumber(approvedNilai));
                    $('#rejectedTotalJumlah').text(formatNumber(rejectedJumlah));
                    $('#rejectedTotalNilai').text('Rp ' + formatNumber(rejectedNilai));
                    
                    // Update class row
                    $('.item-validation-row').each(function() {
                        const checkbox = $(this).find('.item-checkbox');
                        if (checkbox.is(':checked')) {
                            $(this).addClass('selected');
                        } else {
                            $(this).removeClass('selected');
                        }
                    });
                    
                    // Update checkbox select all
                    const totalCheckboxes = $('.item-checkbox').length;
                    const checkedCheckboxes = $('.item-checkbox:checked').length;
                    $('#selectAllItems').prop('checked', totalCheckboxes === checkedCheckboxes);
                }
                
                // Inisialisasi fungsi updateValidationSummary di window scope
                window.updateValidationSummary = updateValidationSummary;
                
                // Select all items - menggunakan event delegation yang lebih baik
                $(document).off('change', '#selectAllItems').on('change', '#selectAllItems', function() {
                    const isChecked = $(this).is(':checked');
                    $('.item-checkbox').prop('checked', isChecked);
                    updateValidationSummary();
                });
                
                // Select all button
                $(document).off('click', '#selectAllBtn').on('click', '#selectAllBtn', function() {
                    $('#selectAllItems').prop('checked', true).trigger('change');
                });
                
                // Deselect all button
                $(document).off('click', '#deselectAllBtn').on('click', '#deselectAllBtn', function() {
                    $('#selectAllItems').prop('checked', false).trigger('change');
                });
                
                // Reject all items button
                $(document).off('click', '#rejectAllItemsBtn').on('click', '#rejectAllItemsBtn', function() {
                    if (confirm('Apakah Anda yakin ingin menolak semua item dalam pengadaan ini?')) {
                        $('#selectAllItems').prop('checked', false).trigger('change');
                        $('#alasan_penolakan_items').focus();
                    }
                });
                
                // Individual checkbox change - menggunakan event delegation
                $(document).off('change', '.item-checkbox').on('change', '.item-checkbox', function() {
                    updateValidationSummary();
                });
                
                // Initialize summary
                updateValidationSummary();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                $('#customValidationBody').html(`
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                        <h5 class="mt-3 text-danger">Gagal memuat data pengadaan</h5>
                        <p class="text-muted">Terjadi kesalahan saat memuat data</p>
                    </div>
                `);
            }
        });
    });
    
    // Submit custom validation form - FIXED: Perbaikan event handler untuk form submission
    $('#customValidationForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const procurementId = form.find('input[name="procurement_id"]').val();
        
        // Ambil data form
        const formData = new FormData(this);
        
        // Disable tombol submit selama proses
        const submitBtn = $('#submitCustomValidationBtn');
        submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Memproses...
        `);
        
        console.log('Mengirim data custom validation untuk procurement ID:', procurementId);
        
        $.ajax({
            url: "{{ url('superadmin/procurement') }}/" + procurementId + "/custom-approve",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Response sukses:', response);
                if (response.success) {
                    showAlert(response.message || 'Validasi berhasil diproses', 'success');
                    customValidationModal.hide();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert(response.message || 'Terjadi kesalahan saat memproses validasi', 'danger');
                    submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Submit Validasi');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                
                let errorMessage = 'Terjadi kesalahan saat memproses validasi';
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    // Jika tidak bisa parse JSON, gunakan pesan default
                }
                
                showAlert(errorMessage, 'danger');
                submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Submit Validasi');
            }
        });
    });
    
    // Reject all custom button - FIXED: Perbaikan event handler
    $(document).on('click', '#rejectAllCustomBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (confirm('Apakah Anda yakin ingin menolak SEMUA item dalam pengadaan ini? Semua item akan ditandai sebagai ditolak.')) {
            // Uncheck semua checkbox
            $('.item-checkbox').prop('checked', false);
            
            // Update summary jika fungsi ada
            if (typeof window.updateValidationSummary === 'function') {
                window.updateValidationSummary();
            }
            
            // Tampilkan alert info
            showAlert('Semua item telah ditandai untuk ditolak. Silahkan submit validasi untuk melanjutkan.', 'info');
        }
    });
    
    // Approve procurement - tampilkan modal (untuk single item) - FIXED
    $(document).on('click', '.approve-procurement', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const procurementId = $(this).data('id');
        $('#approve_procurement_id').val(procurementId);
        $('#catatan_approve').val('');
        
        approveModal.show();
    });
    
    // Submit approve form (untuk single item) - FIXED: Perbaikan event handler
    $('#approveProcurementForm').on('submit', function(e) {
        e.preventDefault();
        
        const procurementId = $('#approve_procurement_id').val();
        const formData = $(this).serialize();
        
        // Disable tombol submit selama proses
        const submitBtn = $('#submitApproveBtn');
        submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Memproses...
        `);
        
        console.log('Mengirim data approve untuk procurement ID:', procurementId);
        
        $.ajax({
            url: "{{ url('superadmin/procurement') }}/" + procurementId + "/approve",
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Response approve sukses:', response);
                if (response.success) {
                    showAlert('Pengadaan berhasil disetujui', 'success');
                    approveModal.hide();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert(response.message || 'Terjadi kesalahan saat menyetujui pengadaan', 'danger');
                    submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Setujui Semua');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX approve:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                
                let errorMessage = 'Terjadi kesalahan saat menyetujui pengadaan';
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    // Jika tidak bisa parse JSON, gunakan pesan default
                }
                
                showAlert(errorMessage, 'danger');
                submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Setujui Semua');
            }
        });
    });
    
    // Reject procurement - tampilkan modal (untuk single item) - FIXED
    $(document).on('click', '.reject-procurement', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const procurementId = $(this).data('id');
        $('#reject_procurement_id').val(procurementId);
        $('#alasan_penolakan').val('');
        
        rejectModal.show();
    });
    
    // Submit reject form dengan validasi (untuk single item) - FIXED: Perbaikan event handler
    $('#rejectProcurementForm').on('submit', function(e) {
        e.preventDefault();
        
        const procurementId = $('#reject_procurement_id').val();
        const alasan = $('#alasan_penolakan').val().trim();
        
        // Validasi alasan
        if (alasan.length < 10) {
            showAlert('Alasan penolakan harus minimal 10 karakter!', 'danger');
            $('#alasan_penolakan').focus();
            return false;
        }
        
        const formData = $(this).serialize();
        
        // Disable tombol submit selama proses
        const submitBtn = $('#submitRejectBtn');
        submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Memproses...
        `);
        
        console.log('Mengirim data reject untuk procurement ID:', procurementId);
        
        $.ajax({
            url: "{{ url('superadmin/procurement') }}/" + procurementId + "/reject",
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Response reject sukses:', response);
                if (response.success) {
                    showAlert('Pengadaan berhasil ditolak', 'success');
                    rejectModal.hide();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert(response.message || 'Terjadi kesalahan saat menolak pengadaan', 'danger');
                    submitBtn.prop('disabled', false).text('Tolak Semua');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX reject:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                
                let errorMessage = 'Terjadi kesalahan saat menolak pengadaan';
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    // Jika tidak bisa parse JSON, gunakan pesan default
                }
                
                showAlert(errorMessage, 'danger');
                submitBtn.prop('disabled', false).text('Tolak Semua');
            }
        });
    });
    
    // PERBAIKAN: Tambahkan event handler untuk merespons perubahan ukuran layar
    $(window).on('resize', function() {
        // Reset table-responsive jika diperlukan
        if ($(window).width() > 768) {
            $('.table-responsive').css('border', 'none');
        } else {
            $('.table-responsive').css('border', '1px solid #dee2e6');
        }
    });
});

// Print Function
function printTable() {
    // Simpan elemen yang akan disembunyikan
    const elementsToHide = document.querySelectorAll('.sidebar, .topbar, .page-header, .stats-grid, .filter-bar, .status-tabs, .action-buttons, .btn-group, .pagination, .alert-container');
    
    // Sembunyikan elemen yang tidak perlu dicetak
    elementsToHide.forEach(el => {
        if (el) {
            el.style.display = 'none';
        }
    });
    
    // Perlebar tabel untuk cetak
    const tableCard = document.querySelector('.table-card');
    if (tableCard) {
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
        
        // Tambahkan tanggal cetak
        const printDate = document.createElement('p');
        printDate.textContent = 'Tanggal: ' + new Date().toLocaleDateString('id-ID');
        printDate.style.textAlign = 'center';
        printDate.style.marginBottom = '20px';
        printDate.style.color = '#666';
        tableCard.parentNode.insertBefore(printDate, tableCard.nextSibling);
        
        // Trigger print
        window.print();
        
        // Kembalikan tampilan setelah cetak
        setTimeout(() => {
            elementsToHide.forEach(el => {
                if (el) {
                    el.style.display = '';
                }
            });
            
            if (tableCard) {
                tableCard.style.boxShadow = '';
                tableCard.style.padding = '';
                tableCard.style.margin = '';
            }
            
            if (mainContent) {
                mainContent.style.marginLeft = '';
                mainContent.style.padding = '';
            }
            
            // Hapus elemen yang ditambahkan untuk cetak
            if (printTitle.parentNode) {
                printTitle.parentNode.removeChild(printTitle);
            }
            if (printDate.parentNode) {
                printDate.parentNode.removeChild(printDate);
            }
        }, 100);
    }
}
</script>
</body>
</html>