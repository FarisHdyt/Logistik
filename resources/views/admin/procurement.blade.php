<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengadaan Barang | SILOG Polres</title>
    <!-- CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
        
        /* Sidebar */
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
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .stat-content h5 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stat-content p {
            color: #64748b;
            font-size: 0.8rem;
            margin: 0;
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
            background: var(--primary);
            color: white;
            border-color: var(--primary);
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
        
        .badge-multi {
            background-color: #8b5cf6 !important;
            color: white !important;
            border-color: #7c3aed;
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
        
        /* Multi Item Badge */
        .multi-item-badge {
            background-color: #0ea5e9;
            color: white;
            padding: 0.1rem 0.4rem;
            border-radius: 3px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-right: 0.5rem;
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
        
        /* Multi Item Container */
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
            border: 1px solid #e0f2fe;
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
        }
        
        .stat-approved {
            color: #065f46;
            background-color: #d1fae5;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
        }
        
        .stat-rejected {
            color: #991b1b;
            background-color: #fee2e2;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
        }
        
        .stat-cancelled {
            color: #92400e;
            background-color: #fef3c7;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
        }
        
        /* Timeline Styles */
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
        
        .timeline-marker.bg-success { 
            background-color: var(--success) !important; 
        }
        
        .timeline-marker.bg-primary { 
            background-color: var(--primary) !important; 
        }
        
        .timeline-marker.bg-danger { 
            background-color: var(--secondary) !important; 
        }
        
        .timeline-content {
            margin-left: 0;
        }
        
        /* Required Star */
        .required-star {
            color: #dc2626;
        }
        
        /* Character Counter */
        .char-counter {
            font-size: 0.8rem;
            text-align: right;
            margin-top: 0.25rem;
        }
        
        .char-counter.danger {
            color: #dc2626;
        }
        
        .char-counter.warning {
            color: #f59e0b;
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
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .status-tabs {
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
            <p>Manajemen Pengadaan</p>
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
                <a href="{{ route('admin.procurement') }}" class="nav-link active">
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
                <a href="{{ route('admin.reports') }}" class="nav-link">
                    <i class="bi bi-file-text"></i>
                    <span>Laporan</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-footer" style="padding: 1.5rem; position: absolute; bottom: 0; width: 100%;">
            <div class="text-center">
                <small style="opacity: 0.7;">Sistem Logistik Polres</small><br>
                <small style="opacity: 0.5;">v1.1.0</small>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <h4 class="mb-0">Pengadaan Barang</h4>
            <div class="user-info">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <strong>{{ auth()->user()->name }}</strong><br>
                    <small class="text-muted">Admin</small>
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
                    <h5 class="mb-1">Data Pengadaan Barang</h5>
                    <p class="text-muted mb-0">Kelola pengajuan pengadaan dan restock barang</p>
                </div>
                <div class="action-buttons">
                    <a href="{{ route('admin.inventory') }}" class="btn btn-primary btn-action">
                        <i class="bi bi-box-seam me-1"></i> Ke Manajemen Barang
                    </a>
                    <button class="btn btn-warning btn-action" onclick="printTable()">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['total'] ?? 0 }}</h5>
                    <p>Total Pengadaan</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['pending'] ?? 0 }}</h5>
                    <p>Menunggu</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['approved'] ?? 0 }}</h5>
                    <p>Disetujui</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['completed'] ?? 0 }}</h5>
                    <p>Selesai</p>
                </div>
            </div>
        </div>
        
        <!-- Filter Bar -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('admin.procurement') }}" id="filterForm">
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
                            <option value="multi" {{ request('tipe') == 'multi' ? 'selected' : '' }}>Multi Item</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->has('search') || request()->has('tipe'))
                        <a href="{{ route('admin.procurement') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Status Tabs -->
        <div class="status-tabs">
            <a href="{{ route('admin.procurement', ['status' => 'all']) }}" class="status-tab {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.procurement', ['status' => 'pending']) }}" class="status-tab {{ request('status') == 'pending' ? 'active' : '' }}">Menunggu</a>
            <a href="{{ route('admin.procurement', ['status' => 'approved']) }}" class="status-tab {{ request('status') == 'approved' ? 'active' : '' }}">Disetujui</a>
            <a href="{{ route('admin.procurement', ['status' => 'completed']) }}" class="status-tab {{ request('status') == 'completed' ? 'active' : '' }}">Selesai</a>
            <a href="{{ route('admin.procurement', ['status' => 'cancelled']) }}" class="status-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">Dibatalkan</a>
            <a href="{{ route('admin.procurement', ['status' => 'rejected']) }}" class="status-tab {{ request('status') == 'rejected' ? 'active' : '' }}">Ditolak</a>
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
                                $items = $procurement->items ?? [];
                                $isMultiItem = $procurement->is_multi_item && count($items) > 0;
                                $firstItem = count($items) > 0 ? $items->first() : null;
                                
                                // PERBAIKAN: Cek tipe pengadaan yang benar untuk multi-item
                                $tipePengadaan = $procurement->tipe_pengadaan;
                                // Jika ini multi-item, tipe harus "multi"
                                if ($isMultiItem) {
                                    $tipePengadaan = 'multi';
                                }
                                
                                // Hitung total jumlah dan nilai HANYA untuk item yang tidak ditolak/dibatalkan
                                $approvedItems = $items->filter(function($item) {
                                    return !in_array($item->status, ['rejected', 'cancelled']);
                                });
                                
                                $totalJumlah = $isMultiItem ? $approvedItems->sum('jumlah') : 
                                              ($firstItem && !in_array($firstItem->status, ['rejected', 'cancelled']) ? 
                                               $firstItem->jumlah : 0);
                                
                                $totalNilai = $isMultiItem ? $approvedItems->sum(function($item) {
                                    return ($item->jumlah ?? 0) * ($item->harga_perkiraan ?? 0);
                                }) : ($firstItem && !in_array($firstItem->status, ['rejected', 'cancelled']) ? 
                                     (($firstItem->jumlah ?? 0) * ($firstItem->harga_perkiraan ?? 0)) : 0);
                                
                                // Hitung statistik item
                                $itemStats = [
                                    'total' => $items->count(),
                                    'approved' => $items->where('status', 'approved')->count(),
                                    'completed' => $items->where('status', 'completed')->count(),
                                    'rejected' => $items->where('status', 'rejected')->count(),
                                    'cancelled' => $items->where('status', 'cancelled')->count(),
                                    'pending' => $items->where('status', 'pending')->count(),
                                ];
                            @endphp
                            <tr>
                                <td>{{ ($procurements->currentPage() - 1) * $procurements->perPage() + $index + 1 }}</td>
                                <td>
                                    <strong>{{ $procurement->kode_pengadaan ?? 'P-' . str_pad($procurement->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                </td>
                                <td>
                                    @if($isMultiItem)
                                        <!-- Tampilan Multi Item dengan status -->
                                        <div class="multi-item-container">
                                            <div class="multi-item-header">
                                                <span class="multi-item-badge">{{ $items->count() }} ITEM</span>
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
                                                            $itemClass = '';
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
                                                            $itemClass = '';
                                                            $statusClass = 'item-status-approved';
                                                            $statusText = 'Disetujui';
                                                            break;
                                                        case 'pending':
                                                            $itemClass = '';
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
                                            @if($itemStats['rejected'] > 0 || $itemStats['cancelled'] > 0)
                                            <div class="summary-stats">
                                                @if($itemStats['approved'] > 0)
                                                <span class="stat-approved">
                                                    <i class="bi bi-check-circle"></i> {{ $itemStats['approved'] }} Disetujui
                                                </span>
                                                @endif
                                                @if($itemStats['completed'] > 0)
                                                <span class="stat-approved">
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
                                                Total Disetujui: <span>{{ $totalJumlah }} unit</span> • 
                                                Nilai: <span>Rp {{ number_format($totalNilai, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @elseif($firstItem)
                                        <!-- Tampilan Single Item dari relasi items -->
                                        @php
                                            $itemClass = '';
                                            $statusText = '';
                                            
                                            if(in_array($firstItem->status, ['rejected', 'cancelled'])) {
                                                $itemClass = 'item-rejected';
                                            }
                                            
                                            switch($firstItem->status) {
                                                case 'completed':
                                                    $statusText = 'Selesai';
                                                    break;
                                                case 'rejected':
                                                    $statusText = 'Ditolak';
                                                    break;
                                                case 'cancelled':
                                                    $statusText = 'Dibatalkan';
                                                    break;
                                                case 'approved':
                                                    $statusText = 'Disetujui';
                                                    break;
                                                case 'pending':
                                                    $statusText = 'Menunggu';
                                                    break;
                                            }
                                        @endphp
                                        <div class="{{ $itemClass }}">
                                            <strong>{{ $firstItem->kode_barang ?? 'N/A' }}</strong><br>
                                            <small>{{ $firstItem->nama_barang ?? 'Barang' }}</small>
                                            @if($statusText)
                                            <br><small class="item-status-badge item-status-{{ $firstItem->status }}">
                                                {{ $statusText }}
                                            </small>
                                            @endif
                                            @if(in_array($firstItem->status, ['rejected', 'cancelled']) && $firstItem->alasan_penolakan)
                                            <div class="rejection-reason mt-1">
                                                <small><strong>Alasan:</strong> {{ $firstItem->alasan_penolakan }}</small>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Fallback jika tidak ada items -->
                                        <strong>{{ $procurement->kode_barang ?? 'N/A' }}</strong><br>
                                        <small>{{ $procurement->nama_barang ?? 'Barang' }}</small>
                                    @endif
                                </td>
                                <td>
                                    <!-- PERBAIKAN: Tampilkan badge yang benar berdasarkan tipe pengadaan -->
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
                                        
                                        @if($procurement->status == 'approved')
                                        <button type="button" class="btn btn-primary btn-sm complete-procurement" 
                                                data-id="{{ $procurement->id }}" title="Tandai Selesai">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        @endif
                                        
                                        @if($procurement->status == 'pending' || $procurement->status == 'approved')
                                        <button type="button" class="btn btn-warning btn-sm cancel-procurement" 
                                                data-id="{{ $procurement->id }}" title="Batalkan">
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
                                        @if(request()->has('search') || request()->has('status') || request()->has('tipe'))
                                        <a href="{{ route('admin.procurement') }}" class="btn btn-primary btn-sm mt-2">
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
                    <h5 class="modal-title">Detail Pengadaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-form" id="detailProcurementBody">
                    <!-- Detail akan diisi dengan JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confirm Complete Modal -->
    <div class="modal fade" id="completeProcurementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tandai Pengadaan Selesai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menandai pengadaan ini sebagai selesai?</p>
                    <p class="text-muted"><small>Pastikan barang sudah diterima dan siap untuk ditambahkan ke inventory.</small></p>
                    <input type="hidden" id="complete_procurement_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmComplete">Ya, Tandai Selesai</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelProcurementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Pengadaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="cancelProcurementForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="procurement_id" id="cancel_procurement_id">
                        <div class="mb-3">
                            <label for="alasan_pembatalan" class="form-label">
                                Alasan Pembatalan <span class="required-star">*</span>
                                <small class="text-muted">(minimal 10 karakter)</small>
                            </label>
                            <textarea class="form-control" id="alasan_pembatalan" name="alasan_pembatalan" 
                                      rows="3" placeholder="Masukkan alasan pembatalan minimal 10 karakter" 
                                      minlength="10" required></textarea>
                            <div class="char-counter" id="alasan_counter">0/10 karakter</div>
                            <div class="form-text text-danger d-none" id="alasan_error">
                                <i class="bi bi-exclamation-circle"></i> Alasan pembatalan harus minimal 10 karakter
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning" id="submitCancelBtn">Konfirmasi Pembatalan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
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
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data pengadaan...</p>
                </div>
            `);
            
            fetch(`/admin/procurement/${procurementId}`)
                .then(response => response.json())
                .then(data => {
                    const procurement = data.procurement;
                    
                    // Pastikan kode_pengadaan ada, gunakan fallback jika tidak
                    const kodePengadaan = procurement.kode_pengadaan || 
                                          'P-' + procurement.id.toString().padStart(6, '0');
                    
                    // Validasi data sebelum digunakan
                    const items = procurement.items || [];
                    const isMultiItem = items.length > 1;
                    
                    // PERBAIKAN: Tentukan tipe pengadaan yang benar
                    const tipePengadaan = isMultiItem ? 'multi' : procurement.tipe_pengadaan;
                    
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
                    const totalJumlah = validItems.reduce((sum, item) => sum + (parseInt(item.jumlah) || 0), 0);
                    const totalNilai = validItems.reduce((sum, item) => {
                        const jumlah = parseInt(item.jumlah) || 0;
                        const harga = parseFloat(item.harga_perkiraan) || 0;
                        return sum + (jumlah * harga);
                    }, 0);
                    
                    let html = `
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3">
                                            <i class="bi bi-info-circle me-2"></i>Informasi Pengadaan
                                        </h6>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Kode Pengadaan:</div>
                                            <div class="col-7">
                                                <code>${kodePengadaan}</code>
                                            </div>
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
                                                    ${procurement.prioritas.charAt(0).toUpperCase() + procurement.prioritas.slice(1)}
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
                                        <h6 class="card-title text-primary mb-3">
                                            <i class="bi bi-calculator me-2"></i>Ringkasan
                                        </h6>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Jumlah Item:</div>
                                            <div class="col-7">
                                                ${itemStats.total} item 
                                                ${isMultiItem ? `(${itemStats.approved} disetujui, ${itemStats.completed} selesai, ${itemStats.rejected} ditolak, ${itemStats.cancelled} dibatalkan)` : ''}
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Total Jumlah:</div>
                                            <div class="col-7">
                                                ${totalJumlah} unit
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Total Nilai:</div>
                                            <div class="col-7 fw-bold text-primary">
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
                                <h6 class="mb-0">
                                    <i class="bi bi-list-check me-2"></i>Daftar Barang
                                    ${isMultiItem ? '<span class="badge bg-info">' + items.length + ' Item</span>' : ''}
                                </h6>
                                ${itemStats.rejected > 0 || itemStats.cancelled > 0 ? `
                                <div class="summary-stats">
                                    ${itemStats.approved > 0 ? `<span class="stat-approved"><i class="bi bi-check-circle"></i> ${itemStats.approved} Disetujui</span>` : ''}
                                    ${itemStats.completed > 0 ? `<span class="stat-approved"><i class="bi bi-check-circle-fill"></i> ${itemStats.completed} Selesai</span>` : ''}
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
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-end">Harga/Unit</th>
                                                <th class="text-end">Subtotal</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                    `;
                    
                    // Tampilkan items dengan status
                    if (items && items.length > 0) {
                        items.forEach((item, index) => {
                            const jumlah = item.jumlah || 0;
                            const harga = item.harga_perkiraan || 0;
                            const subtotal = jumlah * harga;
                            const statusClass = getItemStatusClass(item.status);
                            const statusText = getItemStatusText(item.status);
                            
                            let rowClass = '';
                            if (item.status === 'rejected') rowClass = 'table-danger';
                            else if (item.status === 'cancelled') rowClass = 'table-warning';
                            else if (item.status === 'completed') rowClass = 'table-success';
                            else if (item.status === 'approved') rowClass = 'table-primary';
                            
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
                                    <td class="text-center">${jumlah}</td>
                                    <td class="text-end">Rp ${formatNumber(harga)}</td>
                                    <td class="text-end fw-bold">Rp ${formatNumber(subtotal)}</td>
                                    <td>
                                        ${item.alasan_penolakan ? `<small class="text-danger"><strong>Alasan:</strong> ${item.alasan_penolakan}</small>` : ''}
                                        ${item.deskripsi ? `<br><small>${item.deskripsi}</small>` : ''}
                                    </td>
                                </tr>
                            `;
                        });
                        
                        html += `
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="4" class="text-end">TOTAL (Item yang disetujui):</th>
                                                <th class="text-center">${totalJumlah}</th>
                                                <th></th>
                                                <th class="text-end fw-bold text-primary">Rp ${formatNumber(totalNilai)}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                        `;
                    } else {
                        // Untuk single item (tanpa items array)
                        const jumlah = procurement.jumlah || 0;
                        const harga = procurement.harga_perkiraan || 0;
                        const subtotal = jumlah * harga;
                        
                        html += `
                            <tr>
                                <td>1</td>
                                <td>
                                    <span class="item-status-badge item-status-${procurement.status}">
                                        ${getStatusDisplay(procurement.status)}
                                    </span>
                                </td>
                                <td><code>${procurement.kode_barang || '-'}</code></td>
                                <td>${procurement.nama_barang || '-'}</td>
                                <td class="text-center">${jumlah}</td>
                                <td class="text-end">Rp ${formatNumber(harga)}</td>
                                <td class="text-end fw-bold">Rp ${formatNumber(subtotal)}</td>
                                <td>
                                    ${procurement.alasan_penolakan ? `<small class="text-danger"><strong>Alasan:</strong> ${procurement.alasan_penolakan}</small>` : ''}
                                </td>
                            </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">TOTAL:</th>
                                    <th class="text-center">${jumlah}</th>
                                    <th></th>
                                    <th class="text-end fw-bold text-primary">Rp ${formatNumber(subtotal)}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        `;
                    }
                    
                    html += `
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Timeline
                    html += `
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
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
                                                ${formatDateTime(procurement.created_at)}
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
                                        ${formatDateTime(procurement.approved_at)}
                                    </div>
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
                                        ${formatDateTime(procurement.completed_at)}
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
                                        ${formatDateTime(procurement.cancelled_at)}
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
                                        ${formatDateTime(procurement.rejected_at)}
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
                            <p class="text-muted">${error.message}</p>
                            <button class="btn btn-primary btn-sm mt-2" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise"></i> Coba Lagi
                            </button>
                        </div>
                    `);
                });
        });
        
        // Complete procurement
        $(document).on('click', '.complete-procurement', function() {
            const procurementId = $(this).data('id');
            $('#complete_procurement_id').val(procurementId);
            
            const modal = new bootstrap.Modal(document.getElementById('completeProcurementModal'));
            modal.show();
        });
        
        // Confirm complete procurement
        $('#confirmComplete').click(function() {
            const procurementId = $('#complete_procurement_id').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            fetch(`/admin/procurement/${procurementId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Terjadi kesalahan');
                }
            })
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    $('#completeProcurementModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert(data.message || 'Terjadi kesalahan saat menandai selesai', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menandai selesai', 'danger');
            });
        });
        
        // Cancel procurement - tampilkan modal
        $(document).on('click', '.cancel-procurement', function() {
            const procurementId = $(this).data('id');
            $('#cancel_procurement_id').val(procurementId);
            
            // Reset form dan validasi
            $('#alasan_pembatalan').val('');
            $('#alasan_error').addClass('d-none');
            $('#alasan_counter').text('0/10 karakter').removeClass('warning danger');
            $('#submitCancelBtn').prop('disabled', false);
            
            const modal = new bootstrap.Modal(document.getElementById('cancelProcurementModal'));
            modal.show();
        });
        
        // Validasi real-time pada textarea alasan pembatalan
        $('#alasan_pembatalan').on('input', function() {
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
            $('#submitCancelBtn').prop('disabled', charCount < 10);
        });
        
        // Submit cancel form dengan validasi
        $('#cancelProcurementForm').submit(function(e) {
            e.preventDefault();
            
            const procurementId = $('#cancel_procurement_id').val();
            const alasan = $('#alasan_pembatalan').val().trim();
            
            // Validasi panjang karakter
            if (alasan.length < 10) {
                showAlert('Alasan pembatalan harus minimal 10 karakter!', 'danger');
                $('#alasan_pembatalan').focus();
                return false;
            }
            
            const formData = $(this).serialize();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            // Disable tombol submit selama proses
            $('#submitCancelBtn').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Memproses...
            `);
            
            fetch(`/admin/procurement/${procurementId}/cancel`, {
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
                    throw new Error('Terjadi kesalahan saat membatalkan pengadaan');
                }
            })
            .then(data => {
                if (data.success) {
                    showAlert('Pengadaan berhasil dibatalkan', 'success');
                    $('#cancelProcurementModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    // Tampilkan pesan error dari server jika ada
                    const errorMessage = data.message || 'Terjadi kesalahan saat membatalkan pengadaan';
                    showAlert(errorMessage, 'danger');
                    $('#submitCancelBtn').prop('disabled', false).text('Konfirmasi Pembatalan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat membatalkan pengadaan', 'danger');
                $('#submitCancelBtn').prop('disabled', false).text('Konfirmasi Pembatalan');
            });
        });
        
        // Helper functions untuk item status
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
        
        function getItemStatusText(status) {
            const textMap = {
                'pending': 'Menunggu',
                'approved': 'Disetujui',
                'completed': 'Selesai',
                'rejected': 'Ditolak',
                'cancelled': 'Dibatalkan'
            };
            return textMap[status] || status.charAt(0).toUpperCase() + status.slice(1);
        }
        
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
        
        function calculateTotalJumlah(items) {
            if (!items || !Array.isArray(items)) return 0;
            const validItems = items.filter(item => !['rejected', 'cancelled'].includes(item.status));
            return validItems.reduce((sum, item) => sum + (parseInt(item.jumlah) || 0), 0);
        }
        
        function calculateTotalNilai(items) {
            if (!items || !Array.isArray(items)) return 0;
            const validItems = items.filter(item => !['rejected', 'cancelled'].includes(item.status));
            return validItems.reduce((sum, item) => {
                const jumlah = parseInt(item.jumlah) || 0;
                const harga = parseFloat(item.harga_perkiraan) || 0;
                return sum + (jumlah * harga);
            }, 0);
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
        
        function formatDateTime(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID') + ' ' + date.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
        }
        
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    });
    
    // Fungsi untuk menampilkan alert
    function showAlert(message, type = 'success') {
        let alertContainer = document.getElementById('alertContainer');
        
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'alert-container';
            alertContainer.id = 'alertContainer';
            document.body.appendChild(alertContainer);
        }
        
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
        
        const bsAlert = new bootstrap.Alert(alert);
        
        setTimeout(() => {
            if (alert.parentNode === alertContainer) {
                bsAlert.close();
            }
        }, 5000);
    }
    
    // Print Function
    function printTable() {
        const elementsToHide = document.querySelectorAll('.sidebar, .topbar, .page-header, .stats-grid, .filter-bar, .status-tabs, .action-buttons, .btn-group');
        elementsToHide.forEach(el => {
            if (el) el.style.display = 'none';
        });
        
        const tableCard = document.querySelector('.table-card');
        if (tableCard) {
            const originalStyles = {
                boxShadow: tableCard.style.boxShadow,
                padding: tableCard.style.padding
            };
            tableCard.style.boxShadow = 'none';
            tableCard.style.padding = '0';
            
            const printTitle = document.createElement('h4');
            printTitle.textContent = 'Laporan Pengadaan Barang - SILOG Polres';
            printTitle.style.textAlign = 'center';
            printTitle.style.marginBottom = '20px';
            printTitle.style.fontWeight = 'bold';
            tableCard.parentNode.insertBefore(printTitle, tableCard);
            
            const printDate = document.createElement('p');
            printDate.textContent = 'Tanggal: ' + new Date().toLocaleDateString('id-ID');
            printDate.style.textAlign = 'center';
            printDate.style.marginBottom = '20px';
            printDate.style.color = '#666';
            printTitle.parentNode.insertBefore(printDate, printTitle.nextSibling);
            
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
            
            if (filterInfo) {
                const filterElement = document.createElement('p');
                filterElement.innerHTML = filterInfo;
                filterElement.style.textAlign = 'center';
                filterElement.style.marginBottom = '20px';
                filterElement.style.color = '#666';
                filterElement.style.fontSize = '0.9rem';
                printTitle.parentNode.insertBefore(filterElement, printTitle.nextSibling.nextSibling);
            }
            
            window.print();
            
            setTimeout(() => {
                elementsToHide.forEach(el => {
                    if (el) el.style.display = '';
                });
                
                tableCard.style.boxShadow = originalStyles.boxShadow;
                tableCard.style.padding = originalStyles.padding;
                
                [printTitle, printDate, filterElement].forEach(el => {
                    if (el && el.parentNode) {
                        el.parentNode.removeChild(el);
                    }
                });
            }, 500);
        }
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