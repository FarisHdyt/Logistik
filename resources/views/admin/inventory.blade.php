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
        
        /* Badges - PERBAIKAN: Tambahkan warna teks yang jelas */
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
        
        /* Select2 dropdown styling */
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-light);
            color: white;
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
            
            <!-- Hapus menu Manajemen User dan Pengaturan yang hanya untuk superadmin -->
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
                    <h5 class="mb-1">Data Barang Logistik</h5>
                    <p class="text-muted mb-0">Kelola data barang Logistik Polres</p>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="bi bi-plus-circle"></i> Tambah Barang
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
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge 
                                        {{ $item->stok <= 0 ? 'badge-danger' : 
                                           ($item->stok <= $item->stok_minimal ? 'badge-warning' : 
                                           'badge-success') }}">
                                        <strong>{{ $item->stok }}</strong>
                                    </span>
                                </td>
                                <td class="text-center">{{ $item->stok_minimal }}</td>
                                <td>{{ $item->satuan->nama_satuan ?? '-' }}</td>
                                <td>{{ $item->gudang->nama_gudang ?? '-' }}</td>
                                <td>{{ $item->lokasi ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Aksi">
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#detailModal" data-item-id="{{ $item->id }}" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm edit-item" 
                                                data-item-id="{{ $item->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-restock btn-sm restock-item" 
                                                data-item-id="{{ $item->id }}" title="Restock">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" data-item-id="{{ $item->id }}" title="Hapus">
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
    
    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.inventory.store') }}" id="addItemForm">
                    @csrf
                    <div class="modal-body modal-form">
                        <!-- Bagian Informasi Dasar -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-info-circle"></i>
                                Informasi Dasar Barang
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="kode_barang" class="form-label">
                                        Kode Barang
                                        <span class="required-star">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="kode_barang" name="kode_barang" 
                                           placeholder="Masukkan kode barang" required>
                                    <div class="form-text">Kode unik untuk identifikasi barang</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama_barang" class="form-label">
                                        Nama Barang
                                        <span class="required-star">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" 
                                           placeholder="Masukkan nama barang" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bagian Klasifikasi -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-tags"></i>
                                Klasifikasi Barang
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="kategori_id" class="form-label">
                                        Kategori
                                        <span class="required-star">*</span>
                                    </label>
                                    <select class="form-select select2-category-add" id="kategori_id" name="kategori_id" 
                                            style="width: 100%;" required>
                                        <option value="">Pilih Kategori</option>
                                        @if(isset($categories) && $categories->count() > 0)
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="form-text">Ketik untuk mencari atau tambah kategori baru</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="satuan_id" class="form-label">
                                        Satuan
                                        <span class="required-star">*</span>
                                    </label>
                                    <select class="form-select select2-satuan-add" id="satuan_id" name="satuan_id" required>
                                        <option value="">Pilih Satuan</option>
                                        @if(isset($units) && $units->count() > 0)
                                            @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->nama_satuan }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bagian Lokasi Penyimpanan -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-geo-alt"></i>
                                Lokasi Penyimpanan
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="gudang_id" class="form-label">Gudang</label>
                                    <select class="form-select select2-gudang-add" id="gudang_id" name="gudang_id">
                                        <option value="">Pilih Gudang</option>
                                        @if(isset($warehouses) && $warehouses->count() > 0)
                                            @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->nama_gudang }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="lokasi" class="form-label">Lokasi Spesifik</label>
                                    <input type="text" class="form-control" id="lokasi" name="lokasi" 
                                           placeholder="Contoh: Rak A-1, Lantai 2">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bagian Stok & Harga -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-box"></i>
                                Stok & Harga
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="stok" class="form-label">
                                        Stok Awal
                                        <span class="required-star">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="stok" name="stok" 
                                           min="0" placeholder="0" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="stok_minimal" class="form-label">
                                        Stok Minimal
                                        <span class="required-star">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="stok_minimal" name="stok_minimal" 
                                           min="1" placeholder="10" required>
                                    <div class="form-text">Akan muncul notifikasi jika stok mencapai angka ini</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="harga_beli" class="form-label">Harga Beli (Rp)</label>
                                    <input type="number" class="form-control" id="harga_beli" name="harga_beli" 
                                           min="0" step="100" placeholder="0">
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="harga_jual" class="form-label">Harga Jual (Rp)</label>
                                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" 
                                           min="0" step="100" placeholder="0">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bagian Keterangan Tambahan -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-card-text"></i>
                                Keterangan Tambahan
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" 
                                          rows="3" placeholder="Masukkan keterangan tambahan jika ada"></textarea>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4 mb-0">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-info-circle fs-5 me-2"></i>
                                <div>
                                    <small><strong>Catatan:</strong> Field dengan tanda <span class="required-star">*</span> wajib diisi. Stok minimal tidak boleh lebih besar dari stok awal.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan
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
    
    <!-- Restock Modal -->
    <div class="modal fade" id="restockModal" tabindex="-1" aria-labelledby="restockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restockModalLabel">Restock Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="restockForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="restock_jumlah" class="form-label">
                                Jumlah Restock
                                <span class="required-star">*</span>
                            </label>
                            <input type="number" class="form-control" id="restock_jumlah" name="jumlah" 
                                   min="1" placeholder="Masukkan jumlah" required>
                        </div>
                        <div class="mb-3">
                            <label for="restock_harga_beli" class="form-label">Harga Beli (Opsional)</label>
                            <input type="number" class="form-control" id="restock_harga_beli" name="harga_beli" 
                                   min="0" step="100" placeholder="0">
                            <div class="form-text">Diisi jika harga beli berbeda dengan sebelumnya</div>
                        </div>
                        <div class="mb-3">
                            <label for="restock_keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="restock_keterangan" name="keterangan" 
                                      rows="2" placeholder="Misal: Restock dari supplier A, tanggal ..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Restock
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
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk kategori di modal tambah barang
        $('#kategori_id').select2({
            placeholder: "Pilih Kategori",
            allowClear: true,
            dropdownParent: $('#addItemModal'),
            tags: true,
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
        
        // Inisialisasi Select2 untuk satuan di modal tambah barang
        $('#satuan_id').select2({
            placeholder: "Pilih Satuan",
            allowClear: true,
            dropdownParent: $('#addItemModal')
        });
        
        // Inisialisasi Select2 untuk gudang di modal tambah barang
        $('#gudang_id').select2({
            placeholder: "Pilih Gudang",
            allowClear: true,
            dropdownParent: $('#addItemModal')
        });
        
        // Inisialisasi Select2 untuk filter kategori
        $('.select2-category-filter').select2({
            placeholder: "Semua Kategori",
            allowClear: true
        });
        
        // Tangkap ketika user memilih "Tambah baru" di modal tambah barang
        $('#kategori_id').on('select2:select', function (e) {
            var data = e.params.data;
            
            // Jika ini adalah kategori baru
            if (data.isNew) {
                // Tampilkan modal untuk konfirmasi
                $('#newCategoryName').val(data.text.replace(' (Tambah baru)', ''));
                $('#quickAddCategoryModal').modal('show');
                
                // Reset pilihan
                $(this).val(null).trigger('change');
            }
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
                url: '{{ route("admin.categories.quick-store") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    nama_kategori: categoryName
                },
                success: function(response) {
                    if (response.success) {
                        // Tambahkan opsi baru ke semua select kategori
                        var newOption = new Option(response.category.nama_kategori, response.category.id, true, true);
                        
                        // Tambahkan ke modal tambah barang
                        $('#kategori_id').append(newOption).trigger('change');
                        
                        // Tambahkan ke filter kategori
                        $('#categoryFilter').append(new Option(response.category.nama_kategori, response.category.id));
                        
                        // Tutup modal
                        $('#quickAddCategoryModal').modal('hide');
                        $('#newCategoryName').val('');
                        
                        // Tampilkan notifikasi sukses
                        showAlert('Kategori "' + categoryName + '" berhasil ditambahkan!', 'success');
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
                        alert('Terjadi kesalahan saat menyimpan kategori');
                    }
                },
                complete: function() {
                    // Reset button state
                    $('#saveNewCategory').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan');
                }
            });
        });
        
        // Enter untuk save di modal kategori
        $('#newCategoryName').keypress(function(e) {
            if (e.which == 13) {
                $('#saveNewCategory').click();
                return false;
            }
        });
        
        // Reset modal kategori saat ditutup
        $('#quickAddCategoryModal').on('hidden.bs.modal', function() {
            $('#newCategoryName').val('').removeClass('is-invalid');
            $('#categoryError').text('');
            $('#saveNewCategory').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan');
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
        
        // Reset Select2 saat modal tambah barang ditutup
        $('#addItemModal').on('hidden.bs.modal', function() {
            $('#kategori_id').val(null).trigger('change');
            $('#satuan_id').val(null).trigger('change');
            $('#gudang_id').val(null).trigger('change');
            $('#addItemForm')[0].reset();
        });
        
        // Focus ke input pertama saat modal tambah barang dibuka
        $('#addItemModal').on('shown.bs.modal', function() {
            $('#kode_barang').focus();
        });
        
        // Validasi form tambah barang
        $('#addItemForm').submit(function(e) {
            // Reset validasi
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            
            let isValid = true;
            
            // Validasi stok minimal harus <= stok
            const stok = parseInt($('#stok').val()) || 0;
            const stokMinimal = parseInt($('#stok_minimal').val()) || 0;
            
            if (stokMinimal > stok) {
                showAlert('Stok minimal tidak boleh lebih besar dari stok awal', 'danger');
                $('#stok_minimal').addClass('is-invalid');
                isValid = false;
            }
            
            // Validasi harga jual tidak boleh kurang dari harga beli
            const hargaBeli = parseFloat($('#harga_beli').val()) || 0;
            const hargaJual = parseFloat($('#harga_jual').val()) || 0;
            
            if (hargaJual > 0 && hargaBeli > 0 && hargaJual < hargaBeli) {
                showAlert('Harga jual tidak boleh kurang dari harga beli', 'danger');
                $('#harga_jual').addClass('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    
    // Edit Item Handler
    $('.edit-item').click(function() {
        const itemId = $(this).data('item-id');
        
        // Fetch item data via AJAX
        fetch(`/admin/inventory/${itemId}/edit`)
            .then(response => response.json())
            .then(data => {
                const item = data.item || data.barang;
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
                
                // Inisialisasi Select2 untuk kategori di modal edit
                $('#edit_kategori_id').select2({
                    placeholder: "Pilih Kategori",
                    allowClear: true,
                    dropdownParent: $('#editItemModal'),
                    tags: true,
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
                
                // Inisialisasi Select2 untuk satuan di modal edit
                $('#edit_satuan_id').select2({
                    placeholder: "Pilih Satuan",
                    allowClear: true,
                    dropdownParent: $('#editItemModal')
                });
                
                // Inisialisasi Select2 untuk gudang di modal edit
                $('#edit_gudang_id').select2({
                    placeholder: "Pilih Gudang",
                    allowClear: true,
                    dropdownParent: $('#editItemModal')
                });
                
                // Handle tambah kategori baru di modal edit
                $('#edit_kategori_id').on('select2:select', function (e) {
                    var data = e.params.data;
                    
                    if (data.isNew) {
                        $('#newCategoryName').val(data.text.replace(' (Tambah baru)', ''));
                        $('#quickAddCategoryModal').modal('show');
                        $(this).val(null).trigger('change');
                    }
                });
                
                // Validasi form edit
                $('#editItemForm').submit(function(e) {
                    const editStok = parseInt($('#edit_stok').val()) || 0;
                    const editStokMinimal = parseInt($('#edit_stok_minimal').val()) || 0;
                    const editHargaBeli = parseFloat($('#edit_harga_beli').val()) || 0;
                    const editHargaJual = parseFloat($('#edit_harga_jual').val()) || 0;
                    
                    if (editStokMinimal > editStok) {
                        showAlert('Stok minimal tidak boleh lebih besar dari stok', 'danger');
                        e.preventDefault();
                        return false;
                    }
                    
                    if (editHargaJual > 0 && editHargaBeli > 0 && editHargaJual < editHargaBeli) {
                        showAlert('Harga jual tidak boleh kurang dari harga beli', 'danger');
                        e.preventDefault();
                        return false;
                    }
                });
                
                // Update form action
                $('#editItemForm').attr('action', `/admin/inventory/${itemId}`);
                
                // Tampilkan modal edit
                const editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
                editModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat mengambil data barang', 'danger');
            });
    });
    
    // Restock Item Handler
    $('.restock-item').click(function() {
        const itemId = $(this).data('item-id');
        
        // Update form action
        $('#restockForm').attr('action', `/admin/inventory/${itemId}/restock`);
        $('#restockForm')[0].reset();
        
        // Tampilkan modal restock
        const restockModal = new bootstrap.Modal(document.getElementById('restockModal'));
        restockModal.show();
    });
    
    // Detail Modal Handler - PERBAIKAN: Tampilkan detail dengan layout yang rapi
    const detailModal = document.getElementById('detailModal');
    detailModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const itemId = button.getAttribute('data-item-id');
        
        // Tampilkan loading state
        $('#detailModalBody').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data barang...</p>
            </div>
        `);
        
        // Fetch item data via AJAX
        fetch(`/admin/inventory/${itemId}`)
            .then(response => response.json())
            .then(data => {
                const item = data.item || data.barang;
                
                // Tentukan status stok
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
                    <!-- Bagian Informasi Dasar -->
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
                    
                    <!-- Bagian Klasifikasi -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-tags"></i>
                            Klasifikasi Barang
                        </div>
                        <div class="row">
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Kategori</div>
                                <div class="detail-value">${item.kategori?.nama_kategori || '-'}</div>
                            </div>
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Satuan</div>
                                <div class="detail-value">${item.satuan?.nama_satuan || '-'}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bagian Lokasi Penyimpanan -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-geo-alt"></i>
                            Lokasi Penyimpanan
                        </div>
                        <div class="row">
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Gudang</div>
                                <div class="detail-value">${item.gudang?.nama_gudang || '-'}</div>
                            </div>
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Lokasi Spesifik</div>
                                <div class="detail-value">${item.lokasi || '-'}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bagian Stok & Status -->
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
                
                // Bagian Harga (jika ada)
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
                
                // Bagian Keterangan (jika ada)
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
                
                // Bagian Informasi Sistem
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
    
    // Delete Modal Handler
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const itemId = button.getAttribute('data-item-id');
        
        // Update form action
        const form = deleteModal.querySelector('#deleteForm');
        form.action = `/admin/inventory/${itemId}`;
    });
    
    // Fungsi untuk menampilkan alert
    function showAlert(message, type = 'success') {
        const alertContainer = document.querySelector('.alert-container');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        alertContainer.appendChild(alert);
        
        // Auto dismiss setelah 5 detik
        setTimeout(() => {
            if (alert.parentNode === alertContainer) {
                alert.remove();
            }
        }, 5000);
    }
    
    // Print Detail Function
    function printDetail() {
        const detailContent = document.getElementById('detailModalBody').cloneNode(true);
        const printWindow = window.open('', '_blank');
        
        // Hapus progress bar dari cetakan
        const progressBars = detailContent.querySelectorAll('.progress');
        progressBars.forEach(bar => {
            bar.style.display = 'none';
        });
        
        // Tambahkan judul cetakan
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
        // Sembunyikan elemen yang tidak perlu dicetak
        const elementsToHide = document.querySelectorAll('.sidebar, .topbar, .page-header, .stats-grid, .filter-bar, .action-buttons, .btn-group');
        elementsToHide.forEach(el => el.style.display = 'none');
        
        // Perlebar tabel untuk cetak
        const tableCard = document.querySelector('.table-card');
        const originalStyles = {
            boxShadow: tableCard.style.boxShadow,
            padding: tableCard.style.padding
        };
        tableCard.style.boxShadow = 'none';
        tableCard.style.padding = '0';
        
        // Tambahkan judul cetak
        const printTitle = document.createElement('h4');
        printTitle.textContent = 'Laporan Data Barang Logistik - SILOG Polres';
        printTitle.style.textAlign = 'center';
        printTitle.style.marginBottom = '20px';
        printTitle.style.fontWeight = 'bold';
        tableCard.parentNode.insertBefore(printTitle, tableCard);
        
        // Tambahkan tanggal cetak
        const printDate = document.createElement('p');
        printDate.textContent = 'Tanggal: ' + new Date().toLocaleDateString('id-ID');
        printDate.style.textAlign = 'center';
        printDate.style.marginBottom = '20px';
        printDate.style.color = '#666';
        printTitle.parentNode.insertBefore(printDate, printTitle.nextSibling);
        
        // Cetak
        window.print();
        
        // Kembalikan tampilan normal setelah cetak
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
</script>
</body>
</html>