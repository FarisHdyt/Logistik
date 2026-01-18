<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Barang | SILOG Polres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Tambahkan Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
        
        /* Badges */
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 600;
            color: #000 !important;
            border: 1px solid rgba(0,0,0,0.1);
        }
        
        .badge-danger, .badge-out {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border-color: #f87171;
        }
        
        .badge-warning, .badge-critical, .badge-low {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border-color: #fbbf24;
        }
        
        .badge-success, .badge-good {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border-color: #10b981;
        }
        
        .badge-pending {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border-color: #fbbf24;
        }
        
        .badge-processing {
            background-color: #dbeafe !important;
            color: #1e40af !important;
            border-color: #60a5fa;
        }
        
        .badge-completed {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border-color: #10b981;
        }
        
        .badge-cancelled {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border-color: #f87171;
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
        
        /* Alert */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        
        /* Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
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
        
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            min-height: calc(1.5em + 0.75rem + 2px);
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding: 0.375rem 0.75rem;
        }
        
        /* PERBAIKAN: Atur z-index untuk dropdown Select2 di dalam modal */
        .select2-container--open {
            z-index: 99999 !important;
        }
        
        .select2-dropdown {
            z-index: 99999 !important;
        }
        
        .modal .select2-container {
            z-index: 99999 !important;
        }
        
        /* Restock Button */
        .btn-restock {
            background-color: #10b981;
            color: white;
            border: none;
        }
        
        .btn-restock:hover {
            background-color: #0da271;
        }
        
        /* Pengadaan Button */
        .btn-pengadaan {
            background-color: #0ea5e9;
            color: white;
            border: none;
        }
        
        .btn-pengadaan:hover {
            background-color: #0284c7;
        }
        
        /* Pagination Styling */
        .pagination {
            margin-bottom: 0;
        }
        
        .pagination .page-item .page-link {
            border: 1px solid #dee2e6;
            color: var(--primary);
            padding: 0.5rem 0.75rem;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8fafc;
        }
        
        .pagination .page-item .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        /* Form Section Styling */
        .form-section {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary);
        }
        
        .detail-section {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--info);
        }
        
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .detail-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--info);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-section-title i, .detail-section-title i {
            font-size: 1.2rem;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.4rem;
            color: #374151;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.4rem;
            display: block;
        }
        
        .detail-value {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            min-height: 44px;
            display: flex;
            align-items: center;
            color: #374151;
        }
        
        .detail-value p {
            margin: 0;
        }
        
        .detail-row {
            margin-bottom: 0.75rem;
        }
        
        .detail-row:last-child {
            margin-bottom: 0;
        }
        
        .form-control, .form-select, .select2-selection {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 0.625rem 0.875rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-text {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        .required-star {
            color: #ef4444;
            margin-left: 2px;
        }
        
        /* Modal Form Styling */
        .modal-form {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 0.5rem;
        }
        
        .modal-form::-webkit-scrollbar {
            width: 6px;
        }
        
        .modal-form::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .modal-form::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        /* Barang List Styling */
        .barang-list-container {
            max-height: 300px;
            overflow-y: auto;
            margin-top: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 1rem;
            background: white;
        }
        
        .barang-item {
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin-bottom: 0.75rem;
            background: #f9fafb;
        }
        
        .barang-item:last-child {
            margin-bottom: 0;
        }
        
        .barang-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px dashed #d1d5db;
        }
        
        .barang-title {
            font-weight: 600;
            color: var(--primary);
        }
        
        .remove-barang {
            color: var(--secondary);
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }
        
        .remove-barang:hover {
            background: #fee2e2;
        }
        
        .empty-barang-list {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }
        
        .empty-barang-list i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        /* Notifikasi Toast */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        
        .custom-toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-left: 4px solid var(--success);
            animation: slideInRight 0.3s ease-out;
        }
        
        .custom-toast.warning {
            border-left-color: var(--warning);
        }
        
        .custom-toast.info {
            border-left-color: var(--info);
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
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
            
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .pagination .page-item {
                margin-bottom: 0.25rem;
            }
            
            .form-section, .detail-section {
                padding: 1rem;
            }
            
            .detail-value {
                padding: 0.5rem 0.75rem;
            }
            
            .barang-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .modal-form {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h3>SILOG</h3>
            <p>Manajemen Barang</p>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.inventory') }}" class="nav-link active">
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
            <h4 class="mb-0">Manajemen Barang</h4>
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
                {!! session('success') !!}
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
        
        @if($errors->any())
        <div class="alert-container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif
        
        <!-- Toast Notification Container -->
        <div class="toast-container" id="toastContainer"></div>
        
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Data Barang Logistik</h5>
                    <p class="text-muted mb-0">Kelola data barang Logistik Polres</p>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#pengadaanModal">
                        <i class="bi bi-cart-plus"></i> Ajukan Pengadaan
                        <span class="badge bg-danger ms-1" id="pendingItemsBadge" style="display: none;">0</span>
                    </button>
                    <button class="btn btn-warning btn-action" onclick="printTable()">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['total_items'] ?? 0 }}</h5>
                    <p>Total Barang</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['total_categories'] ?? 0 }}</h5>
                    <p>Total Kategori</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['critical_stock'] ?? 0 }}</h5>
                    <p>Stok Kritis</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['out_of_stock'] ?? 0 }}</h5>
                    <p>Stok Habis</p>
                </div>
            </div>
        </div>
        
        <!-- Filter Bar -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('admin.inventory') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="searchInput" name="search" 
                               placeholder="Cari nama barang..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select select2-category-filter" id="categoryFilter" name="category">
                            <option value="">Semua Kategori</option>
                            @if(isset($categories) && $categories->count() > 0)
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter" name="status">
                            <option value="">Semua Status</option>
                            <option value="good" {{ request('status') == 'good' ? 'selected' : '' }}>Stok Baik</option>
                            <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                            <option value="critical" {{ request('status') == 'critical' ? 'selected' : '' }}>Stok Kritis</option>
                            <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->has('search') || request()->has('category') || request()->has('status'))
                        <a href="{{ route('admin.inventory') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Inventory Table -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Stok Minimal</th>
                            <th>Satuan</th>
                            <th>Gudang</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($items) && $items->count() > 0)
                            @foreach($items as $index => $item)
                            <tr class="{{ $item->stok <= 0 ? 'table-danger' : ($item->stok <= $item->stok_minimal ? 'table-warning' : '') }}">
                                <td>{{ ($items->currentPage() - 1) * $items->perPage() + $index + 1 }}</td>
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ optional($item->kategori)->nama_kategori ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge 
                                        {{ $item->stok <= 0 ? 'badge-danger' : 
                                           ($item->stok <= $item->stok_minimal ? 'badge-warning' : 
                                           'badge-success') }}">
                                        <strong>{{ $item->stok }}</strong>
                                    </span>
                                </td>
                                <td class="text-center">{{ $item->stok_minimal }}</td>
                                <td>{{ optional($item->satuan)->nama_satuan ?? '-' }}</td>
                                <td>{{ optional($item->gudang)->nama_gudang ?? '-' }}</td>
                                <td>{{ $item->lokasi ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Aksi">
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#detailModal" data-item-id="{{ $item->id }}" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm edit-item" 
                                                data-item-id="{{ $item->id }}" 
                                                data-item-kode="{{ $item->kode_barang }}"
                                                data-item-nama="{{ $item->nama_barang }}"
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm add-to-procurement" 
                                                data-item-id="{{ $item->id }}" 
                                                data-item-kode="{{ $item->kode_barang }}" 
                                                data-item-nama="{{ $item->nama_barang }}"
                                                data-item-kategori="{{ optional($item->kategori)->nama_kategori ?? '' }}"
                                                data-item-satuan="{{ optional($item->satuan)->nama_satuan ?? '' }}"
                                                data-item-stok="{{ $item->stok }}"
                                                data-item-stok-minimal="{{ $item->stok_minimal }}"
                                                title="Tambah ke Pengadaan">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-item" 
                                                data-item-id="{{ $item->id }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center">
                                    <div class="py-4">
                                        <i class="bi bi-inbox display-6 text-muted"></i>
                                        <p class="mt-2">Tidak ada data barang ditemukan</p>
                                        @if(request()->has('search') || request()->has('category') || request()->has('status'))
                                        <a href="{{ route('admin.inventory') }}" class="btn btn-primary btn-sm mt-2">
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
            @if(isset($items) && $items->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $items->firstItem() }} - {{ $items->lastItem() }} dari {{ $items->total() }} data
                </div>
                <div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($items->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                    <span class="page-link" aria-hidden="true">&laquo; Sebelumnya</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $items->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&laquo; Sebelumnya</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($items->links()->elements as $element)
                                {{-- "Three Dots" Separator --}}
                                @if (is_string($element))
                                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                @endif

                                {{-- Array Of Links --}}
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $items->currentPage())
                                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($items->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $items->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Selanjutnya &raquo;</a>
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
    
    <!-- Pengadaan Modal -->
    <div class="modal fade" id="pengadaanModal" tabindex="-1" aria-labelledby="pengadaanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pengadaanModalLabel">
                        <i class="bi bi-cart-plus me-2"></i>
                        Ajukan Pengadaan Multi-Barang
                        <span class="badge bg-warning ms-2" id="savedIndicator" style="display: none;">
                            <i class="bi bi-save"></i> Disimpan
                        </span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.inventory.storePengadaan') }}" id="pengadaanForm">
                    @csrf
                    <div class="modal-body modal-form">
                        <!-- Informasi Umum Pengadaan -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-info-circle"></i>
                                Informasi Pengadaan
                                <button type="button" class="btn btn-outline-info btn-sm ms-auto" id="clearAllDataBtn">
                                    <i class="bi bi-trash"></i> Hapus Semua Data
                                </button>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="prioritas" class="form-label">
                                        Prioritas
                                        <span class="required-star">*</span>
                                    </label>
                                    <select class="form-select" id="prioritas" name="prioritas" required>
                                        <option value="normal">Normal</option>
                                        <option value="tinggi">Tinggi</option>
                                        <option value="mendesak">Mendesak</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kode Pengadaan</label>
                                    <div class="form-control bg-light">
                                        <span class="text-muted">Akan dibuat otomatis saat pengajuan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-12">
                                    <label for="alasan_pengadaan" class="form-label">
                                        Alasan Pengadaan
                                        <span class="required-star">*</span>
                                    </label>
                                    <textarea class="form-control" id="alasan_pengadaan" name="alasan_pengadaan" 
                                              rows="3" placeholder="Jelaskan alasan pengadaan barang-barang ini..." required></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Daftar Barang yang Diajukan -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-list-check"></i>
                                Daftar Barang yang Diajukan
                                <span class="badge bg-primary ms-2" id="barangCount">0 Barang</span>
                            </div>
                            
                            <div class="alert alert-info">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <div>
                                        <small>
                                            <strong>Informasi Penting:</strong><br>
                                            • Data barang akan otomatis disimpan di browser Anda<br>
                                            • Data tetap aman meskipun halaman di-refresh<br>
                                            • Klik <i class="bi bi-plus-circle"></i> di tabel untuk menambahkan barang<br>
                                            • Atau gunakan form tambah barang baru di bawah
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Container untuk daftar barang -->
                            <div class="barang-list-container" id="barangListContainer">
                                <div class="empty-barang-list" id="emptyBarangList">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada barang ditambahkan</p>
                                    <small class="text-muted">Tambahkan barang dari tabel atau form di bawah</small>
                                </div>
                                <!-- Daftar barang akan ditambahkan di sini secara dinamis -->
                            </div>
                            
                            <!-- Tombol untuk menambahkan barang baru -->
                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="tambahBarangBaruBtn">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Barang Baru
                                </button>
                            </div>
                            
                            <!-- Form untuk menambahkan barang baru (hidden by default) -->
                            <div class="mt-3" id="formTambahBarangBaru" style="display: none;">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Tambah Barang Baru</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="kode_barang_baru" class="form-label">Kode Barang</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="kode_barang_baru" 
                                                           placeholder="Otomatis" readonly>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="generateKodeBarang()">
                                                        <i class="bi bi-arrow-clockwise"></i> Generate
                                                    </button>
                                                </div>
                                                <div class="form-text">Kode barang akan dibuat otomatis</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nama_barang_baru" class="form-label">Nama Barang</label>
                                                <input type="text" class="form-control" id="nama_barang_baru" 
                                                       placeholder="Masukkan nama barang">
                                            </div>
                                        </div>
                                        <div class="row g-3 mt-2">
                                            <div class="col-md-6">
                                                <label for="kategori_barang_baru" class="form-label">Kategori</label>
                                                <select class="form-select select2-category-barang-baru" id="kategori_barang_baru" 
                                                        style="width: 100%;">
                                                    <option value="">Pilih Kategori</option>
                                                    @if(isset($categories) && $categories->count() > 0)
                                                        @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="satuan_barang_baru" class="form-label">Satuan</label>
                                                <select class="form-select select2-satuan-barang-baru" id="satuan_barang_baru">
                                                    <option value="">Pilih Satuan</option>
                                                    @if(isset($units) && $units->count() > 0)
                                                        @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}">{{ $unit->nama_satuan }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-3 mt-2">
                                            <div class="col-md-4">
                                                <label for="jumlah_barang_baru" class="form-label">Jumlah</label>
                                                <input type="number" class="form-control" id="jumlah_barang_baru" 
                                                       min="1" value="1">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="harga_perkiraan_baru" class="form-label">Harga Perkiraan (Rp)</label>
                                                <input type="number" class="form-control" id="harga_perkiraan_baru" 
                                                       min="0" step="100" placeholder="0">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="stok_minimal_baru" class="form-label">Stok Minimal</label>
                                                <input type="number" class="form-control" id="stok_minimal_baru" 
                                                       min="1" value="10">
                                            </div>
                                        </div>
                                        <div class="row g-3 mt-2">
                                            <div class="col-md-12">
                                                <label for="keterangan_barang_baru" class="form-label">Keterangan</label>
                                                <textarea class="form-control" id="keterangan_barang_baru" 
                                                          rows="2" placeholder="Keterangan tambahan untuk barang ini"></textarea>
                                            </div>
                                        </div>
                                        <div class="mt-3 d-flex gap-2">
                                            <button type="button" class="btn btn-primary btn-sm" id="simpanBarangBaruBtn">
                                                <i class="bi bi-plus-circle me-1"></i> Tambahkan
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="batalBarangBaruBtn">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total Pengadaan -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-calculator"></i>
                                Ringkasan Pengadaan
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Ringkasan</h6>
                                            <table class="table table-sm mb-0">
                                                <tr>
                                                    <td>Total Barang:</td>
                                                    <td class="text-end"><strong id="totalBarangCount">0</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Jumlah:</td>
                                                    <td class="text-end"><strong id="totalJumlahBarang">0</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Harga:</td>
                                                    <td class="text-end"><strong id="totalHargaBarang">Rp 0</strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Informasi Tambahan</h6>
                                            <div class="mb-3">
                                                <label for="catatan" class="form-label">Catatan Tambahan</label>
                                                <textarea class="form-control" id="catatan" name="catatan" 
                                                          rows="2" placeholder="Masukkan catatan tambahan jika ada"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields untuk data barang -->
                        <div id="barangDataContainer" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="submitPengadaanBtn" disabled>
                            <i class="bi bi-send me-1"></i> Ajukan Pengadaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Quick Add Category Modal -->
    <div class="modal fade" id="quickAddCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newCategoryName" class="form-label">
                            Nama Kategori
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" class="form-control" id="newCategoryName" 
                               placeholder="Contoh: Alat Tulis Kantor">
                        <div class="invalid-feedback" id="categoryError"></div>
                        <div class="form-text">Masukkan nama kategori yang jelas dan deskriptif</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveNewCategory">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Edit Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editItemForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body modal-form" id="editModalBody">
                        <!-- Form akan diisi dengan JavaScript -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="bi bi-info-circle me-2"></i>Detail Barang
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-form" id="detailModalBody">
                    <!-- Detail akan diisi dengan JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="printDetail()">
                        <i class="bi bi-printer me-1"></i> Cetak
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin menghapus barang ini?</p>
                    <p class="text-danger text-center mb-0"><strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" id="deleteForm" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
    // Array untuk menyimpan barang yang dipilih
    let selectedItems = [];
    
    // Key untuk localStorage
    const STORAGE_KEY = 'pengadaan_data';
    const PENGADAAN_FORM_KEY = 'pengadaan_form_data';
    
    $(document).ready(function() {
        // Muat data dari localStorage saat halaman dimuat
        loadSavedData();
        
        // Update badge pending items
        updatePendingItemsBadge();
        
        // Generate kode barang untuk form baru
        generateKodeBarang();
        
        // Inisialisasi Select2 untuk filter kategori
        $('.select2-category-filter').select2({
            placeholder: "Semua Kategori",
            allowClear: true
        });
        
        // Inisialisasi Select2 untuk kategori barang baru DI DALAM MODAL
        $('.select2-category-barang-baru').select2({
            placeholder: "Pilih Kategori",
            allowClear: true,
            tags: true,
            dropdownParent: $('#pengadaanModal'),
            createTag: function (params) {
                var term = params.term.trim();
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term + ' (Tambah baru)',
                    isNew: true
                };
            }
        });
        
        // Inisialisasi Select2 untuk satuan barang baru DI DALAM MODAL
        $('.select2-satuan-barang-baru').select2({
            placeholder: "Pilih Satuan",
            allowClear: true,
            dropdownParent: $('#pengadaanModal')
        });
        
        // Tangkap ketika user memilih "Tambah baru" di kategori barang baru
        $('.select2-category-barang-baru').on('select2:select', function (e) {
            var data = e.params.data;
            
            if (data.isNew) {
                $('#newCategoryName').val(data.text.replace(' (Tambah baru)', ''));
                $('#quickAddCategoryModal').modal('show');
                $(this).val(null).trigger('change');
            }
        });
        
        // Tombol Tambah ke Pengadaan dari tabel (untuk restock)
        $('.add-to-procurement').click(function() {
            const itemId = $(this).data('item-id');
            const kodeBarang = $(this).data('item-kode');
            const namaBarang = $(this).data('item-nama');
            const kategori = $(this).data('item-kategori');
            const satuan = $(this).data('item-satuan');
            const stok = $(this).data('item-stok');
            const stokMinimal = $(this).data('item-stok-minimal');
            
            // Cek apakah barang sudah ada di daftar
            const existingItem = selectedItems.find(item => item.id == itemId && !item.isNew);
            if (existingItem) {
                showToast(`${kodeBarang} - ${namaBarang} sudah ada dalam daftar pengadaan`, 'warning');
                return;
            }
            
            // Tambahkan ke array sebagai restock
            selectedItems.push({
                id: itemId,
                kode: kodeBarang,
                nama: namaBarang,
                kategori: kategori,
                satuan: satuan,
                stok: stok,
                stok_minimal: stokMinimal,
                jumlah: 1,
                harga: 0,
                keterangan: '',
                isNew: false
            });
            
            // Simpan ke localStorage
            saveToLocalStorage();
            
            // Update tampilan
            updateBarangList();
            
            // Tampilkan notifikasi toast
            showToast(`${namaBarang} berhasil ditambahkan ke daftar pengadaan!`, 'success');
            
            // Jika modal belum terbuka, buka modal
            if (!$('#pengadaanModal').hasClass('show')) {
                const pengadaanModal = new bootstrap.Modal(document.getElementById('pengadaanModal'));
                pengadaanModal.show();
            }
        });
        
        // Tombol Tambah Barang Baru
        $('#tambahBarangBaruBtn').click(function() {
            generateKodeBarang();
            $('#formTambahBarangBaru').slideDown();
            $(this).hide();
        });
        
        // Tombol Batal Tambah Barang Baru
        $('#batalBarangBaruBtn').click(function() {
            resetFormBarangBaru();
            $('#formTambahBarangBaru').slideUp();
            $('#tambahBarangBaruBtn').show();
        });
        
        // Simpan Barang Baru ke Daftar
        $('#simpanBarangBaruBtn').click(function() {
            const kode = $('#kode_barang_baru').val();
            const nama = $('#nama_barang_baru').val().trim();
            const kategoriId = $('#kategori_barang_baru').val();
            const kategoriText = $('#kategori_barang_baru option:selected').text();
            const satuanId = $('#satuan_barang_baru').val();
            const satuanText = $('#satuan_barang_baru option:selected').text();
            const jumlah = parseInt($('#jumlah_barang_baru').val()) || 1;
            const harga = parseInt($('#harga_perkiraan_baru').val()) || 0;
            const stokMinimal = parseInt($('#stok_minimal_baru').val()) || 10;
            const keterangan = $('#keterangan_barang_baru').val().trim();
            
            // Validasi
            if (!nama) {
                showToast('Nama barang harus diisi', 'danger');
                $('#nama_barang_baru').addClass('is-invalid');
                return;
            }
            
            if (!kategoriId) {
                showToast('Kategori harus dipilih', 'danger');
                $('#kategori_barang_baru').next('.select2-container').find('.select2-selection').addClass('is-invalid');
                return;
            }
            
            if (!satuanId) {
                showToast('Satuan harus dipilih', 'danger');
                $('#satuan_barang_baru').addClass('is-invalid');
                return;
            }
            
            if (jumlah <= 0) {
                showToast('Jumlah harus lebih dari 0', 'danger');
                $('#jumlah_barang_baru').addClass('is-invalid');
                return;
            }
            
            // Cek apakah barang dengan kode yang sama sudah ada (untuk barang baru)
            const existingItem = selectedItems.find(item => item.kode === kode && item.isNew);
            if (existingItem) {
                showToast(`Barang baru dengan kode ${kode} sudah ada dalam daftar`, 'warning');
                return;
            }
            
            // Cek apakah barang dengan kode yang sama sudah ada di database
            $.ajax({
                url: '{{ route("admin.inventory.get.barang.by.kode", "") }}/' + kode,
                type: 'GET',
                success: function(response) {
                    if (response.barang) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Kode Barang Sudah Digunakan',
                            html: `Kode barang <strong>${kode}</strong> sudah digunakan oleh:<br>
                                   <strong>${response.barang.nama_barang}</strong><br><br>
                                   Apakah Anda ingin menambahkan sebagai <strong>restock</strong>?`,
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Restock',
                            cancelButtonText: 'Tidak, Buat Kode Baru',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Tambahkan sebagai restock
                                selectedItems.push({
                                    id: response.barang.id,
                                    kode: response.barang.kode_barang,
                                    nama: response.barang.nama_barang,
                                    kategori: response.barang.kategori?.nama_kategori || '',
                                    satuan: response.barang.satuan?.nama_satuan || '',
                                    stok: response.barang.stok,
                                    stok_minimal: response.barang.stok_minimal,
                                    jumlah: jumlah,
                                    harga: harga,
                                    keterangan: keterangan,
                                    isNew: false
                                });
                                
                                saveToLocalStorage();
                                updateBarangList();
                                resetFormBarangBaru();
                                showToast(`${response.barang.nama_barang} ditambahkan sebagai restock`, 'success');
                            } else {
                                generateKodeBarang();
                            }
                        });
                        return;
                    } else {
                        // Tambahkan sebagai barang baru
                        selectedItems.push({
                            id: 'new_' + Date.now(),
                            kode: kode,
                            nama: nama,
                            kategori: kategoriText,
                            satuan: satuanText,
                            kategori_id: kategoriId,
                            satuan_id: satuanId,
                            jumlah: jumlah,
                            harga: harga,
                            stok_minimal: stokMinimal,
                            keterangan: keterangan,
                            isNew: true
                        });
                        
                        saveToLocalStorage();
                        updateBarangList();
                        resetFormBarangBaru();
                        showToast(`Barang baru "${nama}" berhasil ditambahkan ke daftar pengadaan`, 'success');
                    }
                },
                error: function() {
                    // Jika tidak ada di database, tambahkan sebagai barang baru
                    selectedItems.push({
                        id: 'new_' + Date.now(),
                        kode: kode,
                        nama: nama,
                        kategori: kategoriText,
                        satuan: satuanText,
                        kategori_id: kategoriId,
                        satuan_id: satuanId,
                        jumlah: jumlah,
                        harga: harga,
                        stok_minimal: stokMinimal,
                        keterangan: keterangan,
                        isNew: true
                    });
                    
                    saveToLocalStorage();
                    updateBarangList();
                    resetFormBarangBaru();
                    showToast(`Barang baru "${nama}" berhasil ditambahkan ke daftar pengadaan`, 'success');
                }
            });
        });
        
        // Simpan kategori baru via AJAX
        $('#saveNewCategory').click(function() {
            var categoryName = $('#newCategoryName').val().trim();
            var categoryError = $('#categoryError');
            
            if (!categoryName) {
                categoryError.text('Nama kategori tidak boleh kosong');
                $('#newCategoryName').addClass('is-invalid');
                return;
            }
            
            if (categoryName.length < 2) {
                categoryError.text('Nama kategori minimal 2 karakter');
                $('#newCategoryName').addClass('is-invalid');
                return;
            }
            
            // Reset error state
            categoryError.text('');
            $('#newCategoryName').removeClass('is-invalid');
            
            // Show loading state
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');
            
            $.ajax({
                url: '{{ route("admin.inventory.quickStoreCategory") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    nama_kategori: categoryName,
                    deskripsi: ''
                },
                success: function(response) {
                    if (response.success) {
                        // Tambahkan opsi baru ke semua select kategori
                        var newOption = new Option(response.category.nama_kategori, response.category.id, true, true);
                        
                        // Tambahkan ke form barang baru
                        $('.select2-category-barang-baru').append(newOption).trigger('change');
                        
                        // Tambahkan ke filter kategori
                        $('#categoryFilter').append(new Option(response.category.nama_kategori, response.category.id));
                        
                        // Tutup modal
                        $('#quickAddCategoryModal').modal('hide');
                        $('#newCategoryName').val('');
                        
                        // Tampilkan notifikasi sukses
                        showToast('Kategori "' + categoryName + '" berhasil ditambahkan!', 'success');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation error
                        var errors = xhr.responseJSON.errors;
                        if (errors.nama_kategori) {
                            categoryError.text(errors.nama_kategori[0]);
                            $('#newCategoryName').addClass('is-invalid');
                        }
                    } else {
                        showToast('Terjadi kesalahan saat menyimpan kategori', 'danger');
                    }
                },
                complete: function() {
                    // Reset button state
                    $('#saveNewCategory').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan');
                }
            });
        });
        
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
        
        // Reset modal pengadaan saat ditutup
        $('#pengadaanModal').on('hidden.bs.modal', function() {
            saveFormDataToLocalStorage();
        });
        
        // Simpan data form ke localStorage saat input berubah
        $('#prioritas, #alasan_pengadaan, #catatan').on('input change', function() {
            saveFormDataToLocalStorage();
        });
        
        // Validasi form pengadaan sebelum submit
        $('#pengadaanForm').submit(function(e) {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            
            let isValid = true;
            
            // Validasi prioritas
            const prioritas = $('#prioritas').val();
            if (!prioritas) {
                showToast('Prioritas harus dipilih', 'danger');
                $('#prioritas').addClass('is-invalid');
                isValid = false;
            }
            
            // Validasi alasan
            const alasan = $('#alasan_pengadaan').val();
            if (!alasan || alasan.trim().length < 10) {
                showToast('Alasan pengadaan minimal 10 karakter', 'danger');
                $('#alasan_pengadaan').addClass('is-invalid');
                isValid = false;
            }
            
            // Validasi daftar barang
            if (selectedItems.length === 0) {
                showToast('Tambahkan minimal 1 barang ke daftar pengadaan', 'danger');
                isValid = false;
            }
            
            // Validasi setiap barang
            selectedItems.forEach((item, index) => {
                if (item.jumlah <= 0) {
                    showToast(`Jumlah untuk ${item.nama} harus lebih dari 0`, 'danger');
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Siapkan data barang untuk dikirim
            prepareBarangData();
            
            // Show loading state
            $('#submitPengadaanBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...');
            
            // Hapus data dari localStorage setelah submit berhasil
            localStorage.removeItem(STORAGE_KEY);
            localStorage.removeItem(PENGADAAN_FORM_KEY);
            
            return true;
        });
        
        // Edit Item Handler dengan konfirmasi
        $('.edit-item').click(function() {
            const itemId = $(this).data('item-id');
            const kodeBarang = $(this).data('item-kode');
            const namaBarang = $(this).data('item-nama');
            
            Swal.fire({
                title: 'Konfirmasi Edit Barang',
                html: `
                    <div class="text-start">
                        <p>Anda akan mengedit data barang berikut:</p>
                        <div class="alert alert-info mb-0 mt-2">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td width="120"><strong>Kode Barang:</strong></td>
                                    <td>${kodeBarang}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Barang:</strong></td>
                                    <td>${namaBarang}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="alert alert-warning mb-0 mt-2">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Perhatian:</strong> Pastikan data yang diubah sudah benar. Perubahan data barang dapat mempengaruhi laporan dan transaksi yang terkait.
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Edit Data',
                cancelButtonText: 'Batal',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                },
                preConfirm: () => {
                    return new Promise((resolve) => {
                        resolve();
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetchEditData(itemId);
                }
            });
        });
        
        // Detail Modal Handler
        const detailModal = document.getElementById('detailModal');
        detailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const itemId = button.getAttribute('data-item-id');
            
            $('#detailModalBody').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data barang...</p>
                </div>
            `);
            
            fetch(`/admin/inventory/${itemId}`)
                .then(response => response.json())
                .then(data => {
                    const item = data.barang;
                    renderDetailModal(item);
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#detailModalBody').html(`
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                            <p class="mt-2 text-danger">Gagal memuat data barang</p>
                            <button class="btn btn-primary btn-sm mt-2" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise"></i> Coba Lagi
                            </button>
                        </div>
                    `);
                });
        });
        
        // Delete Item Handler
        $('.delete-item').click(function() {
            const itemId = $(this).data('item-id');
            $('#deleteForm').attr('action', `/admin/inventory/${itemId}`);
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
        
        // Tombol Hapus Semua Data
        $('#clearAllDataBtn').click(function() {
            Swal.fire({
                title: 'Hapus Semua Data Pengadaan?',
                html: `
                    <div class="text-start">
                        <p>Anda akan menghapus semua data pengadaan yang tersimpan:</p>
                        <div class="alert alert-warning mb-0 mt-2">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Data yang akan dihapus:</strong>
                            <ul class="mb-0 mt-1">
                                <li>${selectedItems.length} barang dalam daftar</li>
                                <li>Data form pengadaan</li>
                                <li>Semua data yang disimpan di browser</li>
                            </ul>
                        </div>
                        <div class="alert alert-danger mb-0 mt-2">
                            <i class="bi bi-exclamation-octagon-fill me-2"></i>
                            <strong>PERHATIAN:</strong> Tindakan ini tidak dapat dibatalkan!
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Semua',
                cancelButtonText: 'Batal',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    selectedItems = [];
                    
                    localStorage.removeItem(STORAGE_KEY);
                    localStorage.removeItem(PENGADAAN_FORM_KEY);
                    
                    $('#pengadaanForm')[0].reset();
                    $('#formTambahBarangBaru').hide();
                    $('#tambahBarangBaruBtn').show();
                    resetFormBarangBaru();
                    
                    updateBarangList();
                    updatePendingItemsBadge();
                    
                    $('#savedIndicator').hide();
                    
                    showToast('Semua data pengadaan telah dihapus', 'success');
                }
            });
        });
        
        // Tampilkan indikator data tersimpan saat modal dibuka
        $('#pengadaanModal').on('show.bs.modal', function() {
            if (selectedItems.length > 0) {
                $('#savedIndicator').show();
            } else {
                $('#savedIndicator').hide();
            }
        });
        
        // Event listener untuk perubahan pada jumlah dan harga barang
        $(document).on('input', '.jumlah-barang, .harga-barang', function() {
            const index = $(this).data('index');
            const value = $(this).val();
            
            if ($(this).hasClass('jumlah-barang')) {
                updateBarangJumlah(index, value);
            } else if ($(this).hasClass('harga-barang')) {
                updateBarangHarga(index, value);
            }
            
            saveToLocalStorage();
        });
        
        // Event untuk fetch barang detail untuk pengadaan (Select2)
        $(document).on('select2:select', '.select2-barang', function(e) {
            const barangId = e.params.data.id;
            $.ajax({
                url: `{{ url('admin/inventory/barang-detail') }}/${barangId}`,
                method: 'GET',
                success: function(response) {
                    if (response.barang) {
                        const row = $(this).closest('.barang-item');
                        row.find('.barang-detail').html(`
                            <small class="text-muted">
                                Stok: ${response.barang.stok} | Minimal: ${response.barang.stok_minimal} |
                                Kategori: ${response.barang.kategori.nama_kategori}
                            </small>
                        `);
                    }
                }
            });
        });
        
        // Event untuk restock barang
        $(document).on('click', '.btn-restock', function() {
            const itemId = $(this).data('item-id');
            const itemName = $(this).data('item-name');
            
            Swal.fire({
                title: 'Restock Barang',
                html: `
                    <div class="text-start">
                        <p>Restock untuk: <strong>${itemName}</strong></p>
                        <div class="mb-3">
                            <label for="restockJumlah" class="form-label">Jumlah Stok yang Ditambahkan</label>
                            <input type="number" class="form-control" id="restockJumlah" min="1" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="restockHargaBeli" class="form-label">Harga Beli (Opsional)</label>
                            <input type="number" class="form-control" id="restockHargaBeli" min="0" step="1000" placeholder="Harga beli per unit">
                        </div>
                        <div class="mb-3">
                            <label for="restockKeterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="restockKeterangan" rows="2" placeholder="Alasan restock atau catatan lainnya"></textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Restock',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const jumlah = $('#restockJumlah').val();
                    const hargaBeli = $('#restockHargaBeli').val();
                    const keterangan = $('#restockKeterangan').val();
                    
                    if (!jumlah || parseInt(jumlah) <= 0) {
                        Swal.showValidationMessage('Jumlah harus lebih dari 0');
                        return false;
                    }
                    
                    return { jumlah, hargaBeli, keterangan };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = result.value;
                    $.ajax({
                        url: `/admin/inventory/${itemId}/restock`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            jumlah: data.jumlah,
                            harga_beli: data.hargaBeli,
                            keterangan: data.keterangan
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.success || 'Stok berhasil ditambahkan',
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                            });
                        }
                    });
                }
            });
        });
    });
    
    // Fungsi untuk generate kode barang otomatis
    function generateKodeBarang() {
        const prefix = 'BRG';
        const timestamp = Date.now().toString().slice(-6);
        const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        const kode = `${prefix}${timestamp}${random}`;
        
        $('#kode_barang_baru').val(kode);
        return kode;
    }
    
    // Fungsi untuk menyimpan data ke localStorage
    function saveToLocalStorage() {
        try {
            const data = {
                items: selectedItems,
                timestamp: new Date().toISOString()
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            
            $('#savedIndicator').show();
            updatePendingItemsBadge();
            
            return true;
        } catch (e) {
            console.error('Error saving to localStorage:', e);
            return false;
        }
    }
    
    // Fungsi untuk menyimpan data form ke localStorage
    function saveFormDataToLocalStorage() {
        try {
            const formData = {
                prioritas: $('#prioritas').val(),
                alasan_pengadaan: $('#alasan_pengadaan').val(),
                catatan: $('#catatan').val(),
                timestamp: new Date().toISOString()
            };
            localStorage.setItem(PENGADAAN_FORM_KEY, JSON.stringify(formData));
            
            $('#savedIndicator').show();
            
            return true;
        } catch (e) {
            console.error('Error saving form data to localStorage:', e);
            return false;
        }
    }
    
    // Fungsi untuk memuat data dari localStorage
    function loadSavedData() {
        try {
            // Muat data barang
            const savedData = localStorage.getItem(STORAGE_KEY);
            if (savedData) {
                const data = JSON.parse(savedData);
                selectedItems = data.items || [];
                
                if (selectedItems.length > 0) {
                    showToast(`Ditemukan ${selectedItems.length} barang yang tersimpan dari sebelumnya`, 'info');
                }
            }
            
            // Muat data form
            const savedFormData = localStorage.getItem(PENGADAAN_FORM_KEY);
            if (savedFormData) {
                const formData = JSON.parse(savedFormData);
                
                $('#prioritas').val(formData.prioritas || 'normal');
                $('#alasan_pengadaan').val(formData.alasan_pengadaan || '');
                $('#catatan').val(formData.catatan || '');
            }
            
            // Update tampilan jika ada data
            if (selectedItems.length > 0) {
                updateBarangList();
            }
            
            return true;
        } catch (e) {
            console.error('Error loading from localStorage:', e);
            return false;
        }
    }
    
    // Fungsi untuk update badge pending items
    function updatePendingItemsBadge() {
        const badge = $('#pendingItemsBadge');
        if (selectedItems.length > 0) {
            badge.text(selectedItems.length);
            badge.show();
        } else {
            badge.hide();
        }
    }
    
    // Fungsi untuk menampilkan toast notification
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toastContainer');
        
        let icon = 'bi-check-circle';
        let title = 'Berhasil!';
        
        if (type === 'warning') {
            icon = 'bi-exclamation-triangle';
            title = 'Peringatan';
        } else if (type === 'danger') {
            icon = 'bi-x-circle';
            title = 'Error';
        } else if (type === 'info') {
            icon = 'bi-info-circle';
            title = 'Informasi';
        }
        
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;
        toast.id = toastId;
        toast.innerHTML = `
            <div class="d-flex align-items-start p-3">
                <div class="me-3">
                    <i class="bi ${icon} fs-4 text-${type}"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">${title}</h6>
                    <p class="mb-0 small">${message}</p>
                </div>
                <button type="button" class="btn-close ms-2" onclick="removeToast('${toastId}')"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        setTimeout(() => {
            const toastToRemove = document.getElementById(toastId);
            if (toastToRemove) {
                toastToRemove.remove();
            }
        }, 5000);
    }
    
    // Fungsi untuk menghapus toast
    function removeToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.remove();
        }
    }
    
    // Fungsi untuk update daftar barang
    function updateBarangList() {
        const container = $('#barangListContainer');
        const emptyMessage = $('#emptyBarangList');
        
        if (selectedItems.length === 0) {
            container.html(`
                <div class="empty-barang-list" id="emptyBarangList">
                    <i class="bi bi-inbox"></i>
                    <p>Belum ada barang ditambahkan</p>
                    <small class="text-muted">Tambahkan barang dari tabel atau form di bawah</small>
                </div>
            `);
            $('#barangCount').text('0 Barang');
            $('#submitPengadaanBtn').prop('disabled', true);
            $('#savedIndicator').hide();
            return;
        }
        
        container.empty();
        
        $('#barangCount').text(`${selectedItems.length} Barang`);
        $('#submitPengadaanBtn').prop('disabled', false);
        
        let baruCount = 0;
        let restockCount = 0;
        selectedItems.forEach(item => {
            if (item.isNew) {
                baruCount++;
            } else {
                restockCount++;
            }
        });
        
        selectedItems.forEach((item, index) => {
            const tipeBadge = item.isNew ? 
                '<span class="badge bg-info me-2">Baru</span>' : 
                '<span class="badge bg-warning me-2">Restock</span>';
            
            const barangElement = `
                <div class="barang-item" data-index="${index}">
                    <div class="barang-header">
                        <div class="barang-title">
                            ${tipeBadge}
                            ${item.kode} - ${item.nama}
                        </div>
                        <button type="button" class="remove-barang" onclick="removeBarang(${index})">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Kategori</label>
                            <div>${item.kategori || '-'}</div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Satuan</label>
                            <div>${item.satuan || '-'}</div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control form-control-sm jumlah-barang" 
                                   value="${item.jumlah}" min="1" data-index="${index}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Harga Perkiraan (Rp)</label>
                            <input type="number" class="form-control form-control-sm harga-barang" 
                                   value="${item.harga}" min="0" data-index="${index}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Subtotal</label>
                            <div class="fw-bold">Rp ${formatNumber(item.jumlah * item.harga)}</div>
                        </div>
                    </div>
                    ${item.keterangan ? `
                    <div class="mt-2">
                        <label class="form-label">Keterangan</label>
                        <div class="small text-muted">${item.keterangan}</div>
                    </div>
                    ` : ''}
                    ${!item.isNew && item.stok ? `
                    <div class="mt-2">
                        <label class="form-label">Stok Saat Ini</div>
                        <div class="small">${item.stok} unit (Minimal: ${item.stok_minimal})</div>
                    </div>
                    ` : ''}
                </div>
            `;
            container.append(barangElement);
        });
        
        updateSummary(baruCount, restockCount);
        $('#savedIndicator').show();
    }
    
    // Update ringkasan
    function updateSummary(baruCount = 0, restockCount = 0) {
        let totalBarang = selectedItems.length;
        let totalJumlah = 0;
        let totalHarga = 0;
        
        selectedItems.forEach(item => {
            totalJumlah += parseInt(item.jumlah);
            totalHarga += (item.jumlah * item.harga);
        });
        
        $('#totalBarangCount').html(`
            ${totalBarang} barang
            <small class="text-muted d-block">
                (${baruCount} baru, ${restockCount} restock)
            </small>
        `);
        $('#totalJumlahBarang').text(totalJumlah);
        $('#totalHargaBarang').text('Rp ' + formatNumber(totalHarga));
    }
    
    // Fungsi untuk menghapus barang dari daftar
    function removeBarang(index) {
        Swal.fire({
            title: 'Hapus Barang dari Daftar',
            text: `Apakah Anda yakin ingin menghapus ${selectedItems[index].nama} dari daftar pengadaan?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const removedItem = selectedItems.splice(index, 1)[0];
                
                saveToLocalStorage();
                
                updateBarangList();
                showToast(`"${removedItem.nama}" dihapus dari daftar pengadaan`, 'info');
            }
        });
    }
    
    // Fungsi untuk update jumlah barang
    function updateBarangJumlah(index, value) {
        const jumlah = parseInt(value) || 0;
        if (jumlah > 0) {
            selectedItems[index].jumlah = jumlah;
            updateSummary();
            $(`.barang-item[data-index="${index}"] .fw-bold`).text('Rp ' + formatNumber(jumlah * selectedItems[index].harga));
        }
    }
    
    // Fungsi untuk update harga barang
    function updateBarangHarga(index, value) {
        const harga = parseInt(value) || 0;
        selectedItems[index].harga = harga;
        updateSummary();
        $(`.barang-item[data-index="${index}"] .fw-bold`).text('Rp ' + formatNumber(selectedItems[index].jumlah * harga));
    }
    
    // Fungsi untuk reset form tambah barang baru
    function resetFormBarangBaru() {
        generateKodeBarang();
        $('#nama_barang_baru').val('').removeClass('is-invalid');
        $('#kategori_barang_baru').val(null).trigger('change');
        $('#satuan_barang_baru').val(null).trigger('change');
        $('#jumlah_barang_baru').val('1');
        $('#harga_perkiraan_baru').val('');
        $('#stok_minimal_baru').val('10');
        $('#keterangan_barang_baru').val('');
        $('#formTambahBarangBaru').slideUp();
        $('#tambahBarangBaruBtn').show();
    }
    
    // Fungsi untuk menyiapkan data barang sebelum submit
    function prepareBarangData() {
        const container = $('#barangDataContainer');
        container.empty();
        
        selectedItems.forEach((item, index) => {
            if (item.isNew) {
                container.append(`
                    <input type="hidden" name="barang[${index}][tipe_pengadaan]" value="baru">
                    <input type="hidden" name="barang[${index}][kode_barang]" value="${item.kode}">
                    <input type="hidden" name="barang[${index}][nama_barang]" value="${item.nama}">
                    <input type="hidden" name="barang[${index}][kategori_id]" value="${item.kategori_id}">
                    <input type="hidden" name="barang[${index}][satuan_id]" value="${item.satuan_id}">
                    <input type="hidden" name="barang[${index}][jumlah]" value="${item.jumlah}">
                    <input type="hidden" name="barang[${index}][harga_perkiraan]" value="${item.harga}">
                    <input type="hidden" name="barang[${index}][stok_minimal]" value="${item.stok_minimal}">
                    <input type="hidden" name="barang[${index}][keterangan]" value="${item.keterangan}">
                `);
            } else {
                container.append(`
                    <input type="hidden" name="barang[${index}][tipe_pengadaan]" value="restock">
                    <input type="hidden" name="barang[${index}][barang_id]" value="${item.id}">
                    <input type="hidden" name="barang[${index}][jumlah]" value="${item.jumlah}">
                    <input type="hidden" name="barang[${index}][harga_perkiraan]" value="${item.harga}">
                    <input type="hidden" name="barang[${index}][keterangan]" value="${item.keterangan}">
                `);
            }
        });
    }
    
    // Fungsi untuk fetch data edit
    function fetchEditData(itemId) {
        fetch(`/admin/inventory/${itemId}/edit`)
            .then(response => response.json())
            .then(data => {
                renderEditForm(data);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat mengambil data barang', 'danger');
            });
    }
    
    // Fungsi untuk render form edit
    function renderEditForm(data) {
        const item = data.barang;
        const categories = data.categories || [];
        const units = data.units || [];
        const warehouses = data.warehouses || [];
        
        let html = `
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-info-circle"></i>
                    Informasi Dasar Barang
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="edit_kode_barang" class="form-label">
                            Kode Barang
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" class="form-control" id="edit_kode_barang" name="kode_barang" 
                               value="${item.kode_barang}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_nama_barang" class="form-label">
                            Nama Barang
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" class="form-control" id="edit_nama_barang" name="nama_barang" 
                               value="${item.nama_barang}" required>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-tags"></i>
                    Klasifikasi Barang
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="edit_kategori_id" class="form-label">
                            Kategori
                            <span class="required-star">*</span>
                        </label>
                        <select class="form-select select2-category-edit" id="edit_kategori_id" name="kategori_id" 
                                style="width: 100%;" required>
                            <option value="">Pilih Kategori</option>
                            ${categories.map(cat => 
                                `<option value="${cat.id}" ${cat.id == item.kategori_id ? 'selected' : ''}>${cat.nama_kategori}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_satuan_id" class="form-label">
                            Satuan
                            <span class="required-star">*</span>
                        </label>
                        <select class="form-select select2-satuan-edit" id="edit_satuan_id" name="satuan_id" required>
                            <option value="">Pilih Satuan</option>
                            ${units.map(unit => 
                                `<option value="${unit.id}" ${unit.id == item.satuan_id ? 'selected' : ''}>${unit.nama_satuan}</option>`
                            ).join('')}
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-geo-alt"></i>
                    Lokasi Penyimpanan
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="edit_gudang_id" class="form-label">Gudang</label>
                        <select class="form-select select2-gudang-edit" id="edit_gudang_id" name="gudang_id">
                            <option value="">Pilih Gudang</option>
                            ${warehouses.map(wh => 
                                `<option value="${wh.id}" ${wh.id == item.gudang_id ? 'selected' : ''}>${wh.nama_gudang}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_lokasi" class="form-label">Lokasi Spesifik</label>
                        <input type="text" class="form-control" id="edit_lokasi" name="lokasi" 
                               value="${item.lokasi || ''}">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-box"></i>
                    Stok & Harga
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="edit_stok" class="form-label">
                            Stok
                            <span class="required-star">*</span>
                        </label>
                        <input type="number" class="form-control" id="edit_stok" name="stok" 
                               value="${item.stok}" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label for="edit_stok_minimal" class="form-label">
                            Stok Minimal
                            <span class="required-star">*</span>
                        </label>
                        <input type="number" class="form-control" id="edit_stok_minimal" name="stok_minimal" 
                               value="${item.stok_minimal}" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label for="edit_harga_beli" class="form-label">Harga Beli (Rp)</label>
                        <input type="number" class="form-control" id="edit_harga_beli" name="harga_beli" 
                               value="${item.harga_beli || ''}" min="0" step="100">
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label for="edit_harga_jual" class="form-label">Harga Jual (Rp)</label>
                        <input type="number" class="form-control" id="edit_harga_jual" name="harga_jual" 
                               value="${item.harga_jual || ''}" min="0" step="100">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-card-text"></i>
                    Keterangan Tambahan
                </div>
                <div class="mb-3">
                    <label for="edit_keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3">${item.keterangan || ''}</textarea>
                </div>
            </div>
            
            <div class="alert alert-info mt-4 mb-0">
                <div class="d-flex align-items-start">
                    <i class="bi bi-info-circle fs-5 me-2"></i>
                    <div>
                        <small><strong>Catatan:</strong> Field dengan tanda <span class="required-star">*</span> wajib diisi. Stok minimal tidak boleh lebih besar dari stok.</small>
                    </div>
                </div>
            </div>
        `;
        
        $('#editModalBody').html(html);
        $('#editItemForm').attr('action', `/admin/inventory/${item.id}`);
        
        $('#edit_kategori_id').select2({ 
            dropdownParent: $('#editItemModal')
        });
        $('#edit_satuan_id').select2({ 
            dropdownParent: $('#editItemModal')
        });
        $('#edit_gudang_id').select2({ 
            dropdownParent: $('#editItemModal')
        });
        
        const editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
        editModal.show();
    }
    
    // Fungsi untuk render detail modal
    function renderDetailModal(item) {
        const kategoriNama = item.kategori ? item.kategori.nama_kategori : '-';
        const satuanNama = item.satuan ? item.satuan.nama_satuan : '-';
        const gudangNama = item.gudang ? item.gudang.nama_gudang : '-';
        
        let statusText, statusClass, statusBadge;
        if (item.stok <= 0) {
            statusText = 'Habis';
            statusClass = 'text-danger';
            statusBadge = 'badge-danger';
        } else if (item.stok <= item.stok_minimal) {
            statusText = 'Kritis';
            statusClass = 'text-warning';
            statusBadge = 'badge-warning';
        } else if (item.stok <= (item.stok_minimal * 2)) {
            statusText = 'Rendah';
            statusClass = 'text-warning';
            statusBadge = 'badge-warning';
        } else {
            statusText = 'Baik';
            statusClass = 'text-success';
            statusBadge = 'badge-success';
        }
        
        let html = `
            <div class="detail-section">
                <div class="detail-section-title">
                    <i class="bi bi-info-circle"></i>
                    Informasi Dasar Barang
                </div>
                <div class="row">
                    <div class="col-md-6 detail-row">
                        <div class="detail-label">Kode Barang</div>
                        <div class="detail-value">${item.kode_barang || '-'}</div>
                    </div>
                    <div class="col-md-6 detail-row">
                        <div class="detail-label">Nama Barang</div>
                        <div class="detail-value">${item.nama_barang || '-'}</div>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <div class="detail-section-title">
                    <i class="bi bi-tags"></i>
                    Klasifikasi Barang
                </div>
                <div class="row">
                    <div class="col-md-6 detail-row">
                        <div class="detail-label">Kategori</div>
                        <div class="detail-value">${kategoriNama}</div>
                    </div>
                    <div class="col-md-6 detail-row">
                        <div class="detail-label">Satuan</div>
                        <div class="detail-value">${satuanNama}</div>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <div class="detail-section-title">
                    <i class="bi bi-geo-alt"></i>
                    Lokasi Penyimpanan
                </div>
                <div class="row">
                    <div class="col-md-6 detail-row">
                        <div class="detail-label">Gudang</div>
                        <div class="detail-value">${gudangNama}</div>
                    </div>
                    <div class="col-md-6 detail-row">
                        <div class="detail-label">Lokasi Spesifik</div>
                        <div class="detail-value">${item.lokasi || '-'}</div>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <div class="detail-section-title">
                    <i class="bi bi-box"></i>
                    Stok & Status
                </div>
                <div class="row">
                    <div class="col-md-3 detail-row">
                        <div class="detail-label">Stok Tersedia</div>
                        <div class="detail-value">
                            <span class="badge ${item.stok <= 0 ? 'badge-danger' : (item.stok <= item.stok_minimal ? 'badge-warning' : 'badge-success')}">
                                <strong>${item.stok || 0}</strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 detail-row">
                        <div class="detail-label">Stok Minimal</div>
                        <div class="detail-value">${item.stok_minimal || 0}</div>
                    </div>
                    <div class="col-md-3 detail-row">
                        <div class="detail-label">Status Stok</div>
                        <div class="detail-value">
                            <span class="badge ${statusBadge}">${statusText}</span>
                        </div>
                    </div>
                    <div class="col-md-3 detail-row">
                        <div class="detail-label">Sisa Stok</div>
                        <div class="detail-value">
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar ${item.stok <= 0 ? 'bg-danger' : (item.stok <= item.stok_minimal ? 'bg-warning' : 'bg-success')}" 
                                     role="progressbar" 
                                     style="width: ${item.stok_minimal > 0 ? Math.min(100, (item.stok / item.stok_minimal) * 50) : 0}%;">
                                </div>
                            </div>
                            <small class="text-muted">${item.stok} / ${item.stok_minimal * 2} (ideal)</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        if (item.harga_beli || item.harga_jual) {
            html += `
                <div class="detail-section">
                    <div class="detail-section-title">
                        <i class="bi bi-currency-dollar"></i>
                        Informasi Harga
                    </div>
                    <div class="row">
                        <div class="col-md-6 detail-row">
                            <div class="detail-label">Harga Beli</div>
                            <div class="detail-value">${item.harga_beli ? 'Rp ' + formatNumber(item.harga_beli) : '-'}</div>
                        </div>
                        <div class="col-md-6 detail-row">
                            <div class="detail-label">Harga Jual</div>
                            <div class="detail-value">${item.harga_jual ? 'Rp ' + formatNumber(item.harga_jual) : '-'}</div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        if (item.keterangan) {
            html += `
                <div class="detail-section">
                    <div class="detail-section-title">
                        <i class="bi bi-card-text"></i>
                        Keterangan
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Keterangan Tambahan</div>
                        <div class="detail-value">
                            <p class="mb-0">${item.keterangan}</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        html += `
            <div class="detail-section">
                <div class="detail-section-title">
                    <i class="bi bi-clock-history"></i>
                    Informasi Sistem
                </div>
                <div class="row">
                    <div class="col-md-6 detail-row">
                        <div class="detail-label">Tanggal Dibuat</div>
                        <div class="detail-value">${item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-'}</div>
                    </div>
                    <div class="col-md-6 detail-row">
                        <div class="detail-label">Terakhir Diperbarui</div>
                        <div class="detail-value">${item.updated_at ? new Date(item.updated_at).toLocaleDateString('id-ID') : '-'}</div>
                    </div>
                </div>
            </div>
        `;
        
        $('#detailModalBody').html(html);
    }
    
    // Print Detail Function
    function printDetail() {
        const detailContent = document.getElementById('detailModalBody').cloneNode(true);
        const printWindow = window.open('', '_blank');
        
        const progressBars = detailContent.querySelectorAll('.progress');
        progressBars.forEach(bar => {
            bar.style.display = 'none';
        });
        
        const title = document.createElement('h4');
        title.textContent = 'Detail Barang - SILOG Polres';
        title.style.textAlign = 'center';
        title.style.marginBottom = '20px';
        title.style.fontWeight = 'bold';
        
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
                <title>Detail Barang - SILOG Polres</title>
                <style>
                    body { 
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                        margin: 40px; 
                        color: #333;
                    }
                    .detail-section { 
                        margin-bottom: 20px; 
                        padding: 15px; 
                        border: 1px solid #ddd; 
                        border-radius: 5px;
                        background-color: #f9f9f9;
                    }
                    .detail-section-title { 
                        font-weight: bold; 
                        color: #1e3a8a; 
                        margin-bottom: 15px;
                        font-size: 16px;
                        border-bottom: 2px solid #1e3a8a;
                        padding-bottom: 5px;
                    }
                    .detail-row { 
                        margin-bottom: 10px; 
                        display: flex; 
                        align-items: center;
                    }
                    .detail-label { 
                        font-weight: bold; 
                        width: 150px; 
                        color: #1e3a8a;
                    }
                    .detail-value { 
                        flex: 1; 
                        padding: 8px; 
                        background: white; 
                        border: 1px solid #ddd; 
                        border-radius: 4px;
                    }
                    .badge { 
                        padding: 4px 8px; 
                        border-radius: 4px; 
                        font-weight: bold;
                    }
                    .badge-danger { background-color: #fee2e2; color: #991b1b; }
                    .badge-warning { background-color: #fef3c7; color: #92400e; }
                    .badge-success { background-color: #d1fae5; color: #065f46; }
                    @media print {
                        body { margin: 0; padding: 20px; }
                        .detail-section { page-break-inside: avoid; }
                    }
                </style>
            </head>
            <body>
                ${title.outerHTML}
                ${date.outerHTML}
                ${detailContent.innerHTML}
                <script>
                    window.onload = function() {
                        window.print();
                        window.close();
                    }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
    
    // Print Function untuk tabel
    function printTable() {
        const elementsToHide = document.querySelectorAll('.sidebar, .topbar, .page-header, .stats-grid, .filter-bar, .action-buttons, .btn-group');
        elementsToHide.forEach(el => el.style.display = 'none');
        
        const tableCard = document.querySelector('.table-card');
        const originalStyles = {
            boxShadow: tableCard.style.boxShadow,
            padding: tableCard.style.padding
        };
        tableCard.style.boxShadow = 'none';
        tableCard.style.padding = '0';
        
        const printTitle = document.createElement('h4');
        printTitle.textContent = 'Laporan Data Barang Logistik - SILOG Polres';
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
        
        window.print();
        
        setTimeout(() => {
            elementsToHide.forEach(el => el.style.display = '');
            tableCard.style.boxShadow = originalStyles.boxShadow;
            tableCard.style.padding = originalStyles.padding;
            if (printTitle.parentNode) {
                printTitle.parentNode.removeChild(printTitle);
            }
            if (printDate.parentNode) {
                printDate.parentNode.removeChild(printDate);
            }
        }, 500);
    }
    
    // Format Number Function
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Logout confirmation
    document.querySelector('form[action="{{ route("logout") }}"]').addEventListener('submit', function(e) {
        if (!confirm('Apakah Anda yakin ingin logout?')) {
            e.preventDefault();
        }
    });
    
    // Search barang untuk pengadaan
    function searchBarangForProcurement() {
        const searchTerm = $('#searchBarangPengadaan').val();
        if (searchTerm.length < 2) {
            $('#searchResults').html('');
            return;
        }
        
        $.ajax({
            url: '{{ route("admin.inventory.getBarangForProcurement") }}',
            method: 'GET',
            data: { q: searchTerm },
            success: function(response) {
                const results = response.results;
                let html = '';
                
                if (results.length > 0) {
                    results.forEach(item => {
                        html += `
                            <div class="search-result-item" data-id="${item.id}" 
                                 data-kode="${item.kode}" data-nama="${item.nama}"
                                 data-kategori="${item.kategori}" data-satuan="${item.satuan}"
                                 data-stok="${item.stok}" data-stok-minimal="${item.stok_minimal}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${item.kode} - ${item.nama}</strong><br>
                                        <small class="text-muted">
                                            Kategori: ${item.kategori} | Satuan: ${item.satuan} |
                                            Stok: ${item.stok} | Minimal: ${item.stok_minimal}
                                        </small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary add-to-list-btn">
                                        <i class="bi bi-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center text-muted py-3">Tidak ada barang ditemukan</div>';
                }
                
                $('#searchResults').html(html);
                
                // Add event listeners to add buttons
                $('.add-to-list-btn').click(function() {
                    const itemElement = $(this).closest('.search-result-item');
                    const itemId = itemElement.data('id');
                    const kodeBarang = itemElement.data('kode');
                    const namaBarang = itemElement.data('nama');
                    const kategori = itemElement.data('kategori');
                    const satuan = itemElement.data('satuan');
                    const stok = itemElement.data('stok');
                    const stokMinimal = itemElement.data('stok-minimal');
                    
                    // Cek apakah barang sudah ada di daftar
                    const existingItem = selectedItems.find(item => item.id == itemId && !item.isNew);
                    if (existingItem) {
                        showToast(`${kodeBarang} - ${namaBarang} sudah ada dalam daftar pengadaan`, 'warning');
                        return;
                    }
                    
                    // Tambahkan ke array sebagai restock
                    selectedItems.push({
                        id: itemId,
                        kode: kodeBarang,
                        nama: namaBarang,
                        kategori: kategori,
                        satuan: satuan,
                        stok: stok,
                        stok_minimal: stokMinimal,
                        jumlah: 1,
                        harga: 0,
                        keterangan: '',
                        isNew: false
                    });
                    
                    saveToLocalStorage();
                    updateBarangList();
                    showToast(`${namaBarang} berhasil ditambahkan ke daftar pengadaan!`, 'success');
                    
                    // Clear search
                    $('#searchBarangPengadaan').val('');
                    $('#searchResults').html('');
                });
            }
        });
    }
</script>
</body>
</html>