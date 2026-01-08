<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Barang | SILOG Polres</title>
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
        
        /* Status Filter Tabs */
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
        
        .badge-amount {
            background-color: #f0f9ff !important;
            color: #0c4a6e !important;
            border: 1px solid #bae6fd !important;
            font-weight: 700;
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
            border-color: #f87171;
        }
        
        .badge-processing {
            background-color: #dbeafe !important;
            color: #1e40af !important;
            border-color: #60a5fa;
        }
        
        .badge-delivered {
            background-color: #ede9fe !important;
            color: #7c3aed !important;
            border-color: #a78bfa;
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
        
        .detail-row {
            margin-bottom: 0.75rem;
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
        
        /* Required Star */
        .required-star {
            color: #dc2626;
        }
        
        /* Multi Item Barang Styling */
        .multi-barang-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .multi-barang-list li {
            padding: 0.25rem 0;
            border-bottom: 1px solid #f1f1f1;
            font-size: 0.85rem;
        }
        
        .multi-barang-list li:last-child {
            border-bottom: none;
        }
        
        .barang-kode {
            background-color: #e8f4fd;
            color: #0369a1;
            padding: 0.15rem 0.4rem;
            border-radius: 3px;
            font-size: 0.75rem;
            margin-right: 0.3rem;
            font-family: monospace;
        }
        
        .barang-info {
            color: #666;
            font-size: 0.8rem;
            display: block;
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
            <p>Manajemen Permintaan</p>
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
                <a href="{{ route('admin.procurement') }}" class="nav-link">
                    <i class="bi bi-cart-plus"></i>
                    <span>Pengadaan Barang</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.requests') }}" class="nav-link active">
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
            <h4 class="mb-0">Permintaan Barang</h4>
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
        
        <!-- Alert Container (SELALU ADA) -->
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
                    <h5 class="mb-1">Data Permintaan Barang</h5>
                    <p class="text-muted mb-0">Kelola permintaan barang dari berbagai satker</p>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-warning btn-action" onclick="printRequests()">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['total_requests'] ?? 0 }}</h5>
                    <p>Total Permintaan</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['pending_requests'] ?? 0 }}</h5>
                    <p>Pending</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['approved_requests'] ?? 0 }}</h5>
                    <p>Disetujui</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['rejected_requests'] ?? 0 }}</h5>
                    <p>Ditolak</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h5>{{ $stats['delivered_requests'] ?? 0 }}</h5>
                    <p>Terkirim</p>
                </div>
            </div>
        </div>
        
        <!-- Filter Bar -->
        <div class="filter-bar">
            <form method="GET" action="{{ route('admin.requests') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="searchInput" name="search" 
                               placeholder="Cari kode/nama..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select select2-satker-filter" id="satkerFilter" name="satker">
                            <option value="">Semua Satker</option>
                            @foreach($satkers as $satker)
                            <option value="{{ $satker->id }}" {{ request('satker') == $satker->id ? 'selected' : '' }}>
                                {{ $satker->nama_satker }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter" name="status">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Terkirim</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        @if(request()->has('search') || request()->has('satker') || request()->has('status'))
                        <a href="{{ route('admin.requests') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Status Tabs -->
        <div class="status-tabs">
            <a href="{{ route('admin.requests', ['status' => 'all']) }}" class="status-tab {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.requests', ['status' => 'pending']) }}" class="status-tab {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.requests', ['status' => 'approved']) }}" class="status-tab {{ request('status') == 'approved' ? 'active' : '' }}">Disetujui</a>
            <a href="{{ route('admin.requests', ['status' => 'rejected']) }}" class="status-tab {{ request('status') == 'rejected' ? 'active' : '' }}">Ditolak</a>
            <a href="{{ route('admin.requests', ['status' => 'delivered']) }}" class="status-tab {{ request('status') == 'delivered' ? 'active' : '' }}">Terkirim</a>
        </div>
        
        <!-- Requests Table dengan Dukungan Multi Barang -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Permintaan</th>
                            <th>Pemohon</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Satker</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($requests) && $requests->count() > 0)
                            @foreach($requests as $index => $request)
                            @php
                                // Cek apakah multi barang atau single barang
                                $isMultiBarang = isset($request->details) && $request->details->count() > 0;
                                $totalJumlah = $isMultiBarang ? $request->details->sum('jumlah') : $request->jumlah;
                                $barangCount = $isMultiBarang ? $request->details->count() : 1;
                                
                                // Untuk single barang
                                $firstBarang = $isMultiBarang ? null : $request->barang;
                                
                                // Ambil satker (untuk multi barang ambil dari yang pertama atau dari request)
                                $satker = $request->satker ?? ($isMultiBarang && $request->details->first() ? $request->details->first()->satker : null);
                                
                                // Debug: untuk melihat data
                                // dd($request->details, $request->barang);
                            @endphp
                            <tr>
                                <td>{{ ($requests->currentPage() - 1) * $requests->perPage() + $index + 1 }}</td>
                                <td><strong>{{ $request->kode_permintaan }}</strong></td>
                                <td>{{ $request->user->name ?? '-' }}</td>
                                <td>
                                    @if($isMultiBarang)
                                        <div>
                                            <strong>{{ $barangCount }} jenis barang</strong>
                                            <ul class="multi-barang-list mt-1 mb-0">
                                                @foreach($request->details as $detail)
                                                <li>
                                                    <span class="text-muted">{{ $detail->barang->kode_barang ?? 'BRG-001' }}</span>
                                                    {{ $detail->barang->nama_barang ?? 'Barang Contoh' }}
                                                    <span class="barang-info">
                                                        {{ $detail->jumlah ?? 1 }} {{ $detail->barang->satuan->nama_satuan ?? 'unit' }}
                                                        @if(isset($detail->satker) && isset($satker) && $detail->satker->nama_satker != $satker->nama_satker)
                                                        | {{ $detail->satker->nama_satker ?? 'SATUAN HUMAS' }}
                                                        @endif
                                                    </span>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <div>
                                            <strong>{{ $firstBarang->nama_barang ?? 'Barang Contoh' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $firstBarang->kode_barang ?? 'BRG-001' }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="text-muted">
                                        <strong>{{ $totalJumlah }}</strong>
                                        @if($isMultiBarang)
                                        unit<br>
                                        <small class="text-muted">{{ $barangCount }} jenis</small>
                                        @else
                                        {{ $request->barang->satuan->nama_satuan ?? 'unit' }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @if($isMultiBarang)
                                        @php
                                            $uniqueSatkers = $request->details->pluck('satker')->unique('id');
                                        @endphp
                                        @if($uniqueSatkers->count() == 1)
                                            {{ $uniqueSatkers->first()->nama_satker ?? '-' }}
                                        @else
                                            <div>
                                                <span class="text-primary">
                                                    <i class="bi bi-collection"></i> Multiple
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $uniqueSatkers->count() }} satker</small>
                                            </div>
                                        @endif
                                    @else
                                        {{ $satker->nama_satker ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($request->status == 'pending')
                                        <span class="badge bg-warning status-badge">
                                            <i class="bi bi-clock-history me-1"></i>Pending
                                        </span>
                                    @elseif($request->status == 'approved')
                                        <span class="badge bg-success status-badge">
                                            <i class="bi bi-check-circle me-1"></i>Disetujui
                                        </span>
                                    @elseif($request->status == 'rejected')
                                        <span class="badge bg-danger status-badge">
                                            <i class="bi bi-x-circle me-1"></i>Ditolak
                                        </span>
                                    @elseif($request->status == 'delivered')
                                        <span class="badge bg-info status-badge">
                                            <i class="bi bi-truck me-1"></i>Dikirim
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Aksi">
                                        <button type="button" class="btn btn-info btn-sm btn-detail" data-bs-toggle="modal" 
                                                data-bs-target="#detailRequestModal" data-request-id="{{ $request->id }}" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @if($request->status == 'pending')
                                        <button type="button" class="btn btn-success btn-sm btn-approve" 
                                                data-request-id="{{ $request->id }}" title="Setujui">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-reject" 
                                                data-request-id="{{ $request->id }}" title="Tolak">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        @endif
                                        @if($request->status == 'approved')
                                        <button type="button" class="btn btn-primary btn-sm btn-deliver" 
                                                data-request-id="{{ $request->id }}" title="Tandai Terkirim">
                                            <i class="bi bi-truck"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="py-4">
                                        <i class="bi bi-clipboard-check display-6 text-muted"></i>
                                        <p class="mt-2">Tidak ada data permintaan ditemukan</p>
                                        @if(request()->has('search') || request()->has('satker') || request()->has('status'))
                                        <a href="{{ route('admin.requests') }}" class="btn btn-primary btn-sm mt-2">
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
            @if(isset($requests) && $requests->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $requests->firstItem() }} - {{ $requests->lastItem() }} dari {{ $requests->total() }} data
                </div>
                <div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($requests->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                    <span class="page-link" aria-hidden="true">&laquo; Sebelumnya</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $requests->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&laquo; Sebelumnya</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($requests->links()->elements as $element)
                                {{-- "Three Dots" Separator --}}
                                @if (is_string($element))
                                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                                @endif

                                {{-- Array Of Links --}}
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $requests->currentPage())
                                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($requests->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $requests->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Selanjutnya &raquo;</a>
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
    
    <!-- Detail Request Modal -->
    <div class="modal fade" id="detailRequestModal" tabindex="-1" aria-labelledby="detailRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailRequestModalLabel">
                        <i class="bi bi-info-circle me-2"></i>Detail Permintaan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-form" id="detailRequestModalBody">
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
    
    <!-- Approve Confirmation Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Permintaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-check-circle text-success display-4"></i>
                    </div>
                    <p class="text-center">Apakah Anda yakin ingin menyetujui permintaan ini?</p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Stok akan dikurangi saat barang ditandai sebagai "Terkirim"
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirmApprove">
                        <i class="bi bi-check me-1"></i> Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reject Confirmation Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Permintaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-x-circle text-danger display-4"></i>
                    </div>
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">
                            Alasan Penolakan
                            <span class="required-star">*</span>
                        </label>
                        <textarea class="form-control" id="rejectReason" rows="3" 
                                  placeholder="Masukkan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmReject">
                        <i class="bi bi-x me-1"></i> Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Deliver Confirmation Modal -->
    <div class="modal fade" id="deliverModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tandai Sebagai Terkirim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-truck text-primary display-4"></i>
                    </div>
                    <p class="text-center">Apakah barang sudah dikirim kepada pemohon?</p>
                    <div class="mb-3">
                        <label for="deliverNote" class="form-label">Catatan Pengiriman (Opsional)</label>
                        <textarea class="form-control" id="deliverNote" rows="2" 
                                  placeholder="Masukkan catatan pengiriman..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Stok barang akan dikurangi setelah ditandai sebagai terkirim.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmDeliver">
                        <i class="bi bi-check me-1"></i> Ya, Sudah Terkirim
                    </button>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk filter satker
        $('.select2-satker-filter').select2({
            placeholder: "Semua Satker",
            allowClear: true
        });
        
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
        
        // Variabel global untuk menyimpan request ID
        let currentRequestId = null;
        
        // Event Delegation untuk tombol approve (untuk data dinamis/pagination)
        $(document).on('click', '.btn-approve', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            currentRequestId = $(this).data('request-id');
            console.log('Approve clicked for ID:', currentRequestId);
            
            if (!currentRequestId) {
                showAlert('ID permintaan tidak valid', 'warning');
                return;
            }
            
            $('#approveModal').modal('show');
        });
        
        // Event Delegation untuk tombol reject
        $(document).on('click', '.btn-reject', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            currentRequestId = $(this).data('request-id');
            console.log('Reject clicked for ID:', currentRequestId);
            
            if (!currentRequestId) {
                showAlert('ID permintaan tidak valid', 'warning');
                return;
            }
            
            $('#rejectReason').val('');
            $('#rejectModal').modal('show');
        });
        
        // Event Delegation untuk tombol deliver (tandai terkirim)
        $(document).on('click', '.btn-deliver', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            currentRequestId = $(this).data('request-id');
            console.log('Deliver clicked for ID:', currentRequestId);
            
            if (!currentRequestId) {
                showAlert('ID permintaan tidak valid', 'warning');
                return;
            }
            
            $('#deliverNote').val('');
            $('#deliverModal').modal('show');
        });
        
        // Handler untuk confirm approve
        $('#confirmApprove').click(function(e) {
            e.preventDefault();
            
            if (!currentRequestId) {
                showAlert('ID permintaan tidak ditemukan', 'warning');
                return;
            }
            
            console.log('Confirm approve for ID:', currentRequestId);
            
            // Ambil CSRF token dari meta tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
            
            // Kirim request approve
            $.ajax({
                url: `/admin/requests/${currentRequestId}/approve`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    _token: csrfToken
                },
                beforeSend: function() {
                    $('#confirmApprove').prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm"></span> Memproses...');
                },
                success: function(response) {
                    console.log('Approve Response:', response);
                    
                    if (response.success) {
                        showAlert(response.message, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert(response.message || 'Terjadi kesalahan', 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Approve AJAX Error:', xhr.responseText);
                    let errorMessage = 'Terjadi kesalahan saat menyetujui permintaan';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    showAlert(errorMessage, 'danger');
                },
                complete: function() {
                    $('#confirmApprove').prop('disabled', false)
                        .html('<i class="bi bi-check me-1"></i> Setujui');
                    $('#approveModal').modal('hide');
                }
            });
        });
        
        // Handler untuk confirm reject
        $('#confirmReject').click(function(e) {
            e.preventDefault();
            
            if (!currentRequestId) {
                showAlert('ID permintaan tidak ditemukan', 'warning');
                return;
            }
            
            const reason = $('#rejectReason').val().trim();
            if (!reason) {
                showAlert('Harap masukkan alasan penolakan', 'warning');
                $('#rejectReason').focus();
                return;
            }
            
            console.log('Confirm reject for ID:', currentRequestId, 'Reason:', reason);
            
            // Ambil CSRF token dari meta tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
            
            // Kirim request reject
            $.ajax({
                url: `/admin/requests/${currentRequestId}/reject`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    _token: csrfToken,
                    reason: reason
                },
                beforeSend: function() {
                    $('#confirmReject').prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm"></span> Memproses...');
                },
                success: function(response) {
                    console.log('Reject Response:', response);
                    
                    if (response.success) {
                        showAlert(response.message, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert(response.message || 'Terjadi kesalahan', 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Reject AJAX Error:', xhr.responseText);
                    let errorMessage = 'Terjadi kesalahan saat menolak permintaan';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    showAlert(errorMessage, 'danger');
                },
                complete: function() {
                    $('#confirmReject').prop('disabled', false)
                        .html('<i class="bi bi-x me-1"></i> Tolak');
                    $('#rejectModal').modal('hide');
                }
            });
        });
        
        // Handler untuk confirm deliver
        $('#confirmDeliver').click(function(e) {
            e.preventDefault();
            
            if (!currentRequestId) {
                showAlert('ID permintaan tidak ditemukan', 'warning');
                return;
            }
            
            const note = $('#deliverNote').val().trim();
            
            console.log('Confirm deliver for ID:', currentRequestId, 'Note:', note);
            
            // Ambil CSRF token dari meta tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
            
            // Kirim request deliver
            $.ajax({
                url: `/admin/requests/${currentRequestId}/deliver`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    _token: csrfToken,
                    catatan: note || 'Barang telah dikirim'
                },
                beforeSend: function() {
                    $('#confirmDeliver').prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm"></span> Memproses...');
                },
                success: function(response) {
                    console.log('Deliver Response:', response);
                    
                    if (response.success) {
                        showAlert(response.message, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert(response.message || 'Terjadi kesalahan', 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Deliver AJAX Error:', xhr.responseText);
                    let errorMessage = 'Terjadi kesalahan saat menandai sebagai terkirim';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    showAlert(errorMessage, 'danger');
                },
                complete: function() {
                    $('#confirmDeliver').prop('disabled', false)
                        .html('<i class="bi bi-check me-1"></i> Ya, Sudah Terkirim');
                    $('#deliverModal').modal('hide');
                }
            });
        });
        
        // Detail Request Modal Handler - Updated untuk multi barang
        $(document).on('click', '.btn-detail', function() {
            const requestId = $(this).data('request-id');
            
            // Tampilkan loading state
            $('#detailRequestModalBody').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data permintaan...</p>
                </div>
            `);
            
            // Fetch request data via AJAX
            fetch(`/admin/requests/${requestId}`)
                .then(response => response.json())
                .then(data => {
                    const request = data.request || data;
                    const isMultiBarang = request.details && request.details.length > 1;
                    const totalJumlah = isMultiBarang 
                        ? request.details.reduce((sum, detail) => sum + (detail.jumlah || 0), 0)
                        : request.jumlah || 0;
                    const totalJenis = isMultiBarang ? request.details.length : 1;
                    
                    // Debug: tampilkan data di console
                    console.log('Request Data:', request);
                    console.log('Is Multi Barang:', isMultiBarang);
                    console.log('Details:', request.details);
                    
                    let html = `
                        <!-- Informasi Umum -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3">
                                            <i class="bi bi-card-checklist me-2"></i>Informasi Permintaan
                                        </h6>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Kode Permintaan:</div>
                                            <div class="col-7">${request.kode_permintaan || '-'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Tanggal:</div>
                                            <div class="col-7">${new Date(request.created_at).toLocaleDateString('id-ID', {
                                                day: '2-digit',
                                                month: 'long',
                                                year: 'numeric'
                                            })}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Pemohon:</div>
                                            <div class="col-7">${request.user?.name || '-'}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-5 fw-bold">Satker Utama:</div>
                                            <div class="col-7">${request.satker?.nama_satker || '-'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3">
                                            <i class="bi bi-clipboard-check me-2"></i>Status Permintaan
                                        </h6>
                                        <div class="row mb-2">
                                            <div class="col-5 fw-bold">Status:</div>
                                            <div class="col-7">
                    `;
                    
                    // Status badge
                    if (request.status == 'pending') {
                        html += `<span class="badge bg-warning"><i class="bi bi-clock-history me-1"></i>Pending</span>`;
                    } else if (request.status == 'approved') {
                        html += `<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui</span>`;
                    } else if (request.status == 'rejected') {
                        html += `<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>`;
                    } else if (request.status == 'delivered') {
                        html += `<span class="badge bg-info"><i class="bi bi-truck me-1"></i>Dikirim</span>`;
                    }
                    
                    html += `
                                            </div>
                                        </div>
                    `;
                    
                    // Tanggal status sesuai kondisi
                    if (request.delivered_at) {
                        html += `
                            <div class="row mb-2">
                                <div class="col-5 fw-bold">Dikirim pada:</div>
                                <div class="col-7">${new Date(request.delivered_at).toLocaleDateString('id-ID')}</div>
                            </div>
                        `;
                    } else if (request.approved_at) {
                        html += `
                            <div class="row mb-2">
                                <div class="col-5 fw-bold">Disetujui pada:</div>
                                <div class="col-7">${new Date(request.approved_at).toLocaleDateString('id-ID')}</div>
                            </div>
                        `;
                    } else if (request.rejected_at) {
                        html += `
                            <div class="row mb-2">
                                <div class="col-5 fw-bold">Ditolak pada:</div>
                                <div class="col-7">${new Date(request.rejected_at).toLocaleDateString('id-ID')}</div>
                            </div>
                        `;
                    }
                    
                    // Approved by
                    if (request.approved_by_user) {
                        html += `
                            <div class="row">
                                <div class="col-5 fw-bold">Oleh:</div>
                                <div class="col-7">${request.approved_by_user.name || '-'}</div>
                            </div>
                        `;
                    }
                    
                    html += `
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ringkasan Barang -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="bi bi-box-seam me-2"></i>Ringkasan Barang
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-${isMultiBarang ? '4' : '6'} mb-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-primary fw-bold">${totalJumlah}</h4>
                                            <small class="text-muted">Total Unit</small>
                                        </div>
                                    </div>
                                    <div class="col-md-${isMultiBarang ? '4' : '6'} mb-3">
                                        <div class="border rounded p-3">
                                            <h4 class="text-primary fw-bold">${totalJenis}</h4>
                                            <small class="text-muted">Jenis Barang</small>
                                        </div>
                                    </div>
                    `;
                    
                    if (isMultiBarang) {
                        // Hitung jumlah satker unik untuk multi barang
                        const satkerNames = [];
                        if (request.satker?.nama_satker) {
                            satkerNames.push(request.satker.nama_satker);
                        }
                        if (request.details) {
                            request.details.forEach(detail => {
                                if (detail.satker?.nama_satker && !satkerNames.includes(detail.satker.nama_satker)) {
                                    satkerNames.push(detail.satker.nama_satker);
                                }
                            });
                        }
                        
                        html += `
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-primary fw-bold">${satkerNames.length}</h4>
                                    <small class="text-muted">Jumlah Satker</small>
                                </div>
                            </div>
                        `;
                    }
                    
                    html += `
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detail Barang -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="bi bi-list-ul me-2"></i>Detail Barang
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="15%">Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th width="10%">Jumlah</th>
                                                <th width="10%">Satuan</th>
                                                <th width="20%">Satker</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                    `;
                    
                    if (isMultiBarang && request.details && request.details.length > 0) {
                        // Tampilkan semua detail barang untuk multi barang
                        request.details.forEach((detail, index) => {
                            html += `
                                <tr>
                                    <td class="text-center">${index + 1}</td>
                                    <td><span class="badge bg-light text-dark">${detail.barang?.kode_barang || 'BRG-001'}</span></td>
                                    <td>${detail.barang?.nama_barang || 'Nama Barang Tidak Tersedia'}</td>
                                    <td class="text-center"><strong>${detail.jumlah || 0}</strong></td>
                                    <td class="text-center">${detail.barang?.satuan?.nama_satuan || 'unit'}</td>
                                    <td>${detail.satker?.nama_satker || request.satker?.nama_satker || '-'}</td>
                                </tr>
                            `;
                        });
                    } else {
                        // Tampilkan single barang
                        html += `
                            <tr>
                                <td class="text-center">1</td>
                                <td><span class="badge bg-light text-dark">${request.barang?.kode_barang || 'BRG-001'}</span></td>
                                <td>${request.barang?.nama_barang || 'Nama Barang Tidak Tersedia'}</td>
                                <td class="text-center"><strong>${request.jumlah || 0}</strong></td>
                                <td class="text-center">${request.barang?.satuan?.nama_satuan || 'unit'}</td>
                                <td>${request.satker?.nama_satker || '-'}</td>
                            </tr>
                        `;
                    }
                    
                    html += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Keterangan jika ada
                    if (request.keperluan || request.catatan) {
                        html += `
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-card-text me-2"></i>Keterangan
                                    </h6>
                                </div>
                                <div class="card-body">
                        `;
                        
                        if (request.keperluan) {
                            html += `
                                <div class="mb-3">
                                    <h6 class="text-muted">Keperluan:</h6>
                                    <p class="mb-0">${request.keperluan}</p>
                                </div>
                            `;
                        }
                        
                        if (request.catatan) {
                            html += `
                                <div>
                                    <h6 class="text-muted">Catatan Admin:</h6>
                                    <p class="mb-0">${request.catatan}</p>
                                </div>
                            `;
                        }
                        
                        html += `
                                </div>
                            </div>
                        `;
                    }
                    
                    $('#detailRequestModalBody').html(html);
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#detailRequestModalBody').html(`
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                            <h5 class="mt-3 text-danger">Gagal memuat data permintaan</h5>
                            <p class="text-muted">Silakan coba lagi atau hubungi administrator</p>
                            <button class="btn btn-primary btn-sm mt-2" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise"></i> Coba Lagi
                            </button>
                        </div>
                    `);
                });
        });
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
        
        // Auto dismiss setelah 5 detik (kecuali untuk warning/error penting)
        if (type !== 'warning' && type !== 'danger') {
            setTimeout(() => {
                if (alert.parentNode === alertContainer) {
                    bsAlert.close();
                }
            }, 5000);
        }
    }
    
    // Print Function
    function printRequests() {
        // Sembunyikan elemen yang tidak perlu dicetak
        const elementsToHide = document.querySelectorAll('.sidebar, .topbar, .page-header, .stats-grid, .filter-bar, .status-tabs, .action-buttons, .btn-group');
        elementsToHide.forEach(el => {
            if (el) el.style.display = 'none';
        });
        
        // Perlebar tabel untuk cetak
        const tableCard = document.querySelector('.table-card');
        if (tableCard) {
            const originalStyles = {
                boxShadow: tableCard.style.boxShadow,
                padding: tableCard.style.padding
            };
            tableCard.style.boxShadow = 'none';
            tableCard.style.padding = '0';
            
            // Tambahkan judul cetak
            const printTitle = document.createElement('h4');
            printTitle.textContent = 'Laporan Permintaan Barang - SILOG Polres';
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
            
            // Tambahkan filter info jika ada
            let filterInfo = '';
            const searchInput = document.getElementById('searchInput');
            const satkerFilter = document.getElementById('satkerFilter');
            const statusFilter = document.getElementById('statusFilter');
            
            if (searchInput && searchInput.value) {
                filterInfo += `Pencarian: ${searchInput.value}<br>`;
            }
            if (satkerFilter && satkerFilter.value) {
                const selectedSatker = document.querySelector('#satkerFilter option:checked');
                filterInfo += `Satker: ${selectedSatker ? selectedSatker.text : ''}<br>`;
            }
            if (statusFilter && statusFilter.value) {
                const selectedStatus = document.querySelector('#statusFilter option:checked');
                filterInfo += `Status: ${selectedStatus ? selectedStatus.text : ''}<br>`;
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
            
            // Cetak
            window.print();
            
            // Kembalikan tampilan normal setelah cetak
            setTimeout(() => {
                elementsToHide.forEach(el => {
                    if (el) el.style.display = '';
                });
                
                tableCard.style.boxShadow = originalStyles.boxShadow;
                tableCard.style.padding = originalStyles.padding;
                
                // Hapus elemen yang ditambahkan
                [printTitle, printDate, filterElement].forEach(el => {
                    if (el && el.parentNode) {
                        el.parentNode.removeChild(el);
                    }
                });
            }, 500);
        }
    }
    
    // Print Detail Function
    function printDetail() {
        const detailContent = document.getElementById('detailRequestModalBody');
        if (!detailContent) {
            showAlert('Tidak ada konten untuk dicetak', 'warning');
            return;
        }
        
        const clonedContent = detailContent.cloneNode(true);
        const printWindow = window.open('', '_blank');
        
        const title = document.createElement('h4');
        title.textContent = 'Detail Permintaan Barang - SILOG Polres';
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
                <title>Detail Permintaan Barang - SILOG Polres</title>
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
                    table { 
                        width: 100%; 
                        border-collapse: collapse; 
                        margin: 10px 0; 
                    }
                    table th { 
                        background-color: #f8f9fa; 
                        padding: 10px; 
                        border: 1px solid #ddd; 
                        text-align: left; 
                        font-weight: 600;
                    }
                    table td { 
                        padding: 10px; 
                        border: 1px solid #ddd; 
                    }
                    .badge { 
                        padding: 4px 8px; 
                        border-radius: 4px; 
                        font-weight: bold; 
                        font-size: 0.85em;
                    }
                    .bg-warning { background-color: #ffc107; color: #000; }
                    .bg-success { background-color: #198754; color: #fff; }
                    .bg-danger { background-color: #dc3545; color: #fff; }
                    .bg-info { background-color: #0dcaf0; color: #fff; }
                    .bg-light { background-color: #f8f9fa; color: #212529; }
                    .text-primary { color: #0d6efd; }
                    .text-muted { color: #6c757d; }
                    .fw-bold { font-weight: 600; }
                    .row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; }
                    .col-5, .col-7 { position: relative; width: 100%; padding-right: 15px; padding-left: 15px; }
                    .col-5 { flex: 0 0 41.666667%; max-width: 41.666667%; }
                    .col-7 { flex: 0 0 58.333333%; max-width: 58.333333%; }
                    .mb-2 { margin-bottom: 0.5rem; }
                    .mb-3 { margin-bottom: 1rem; }
                    .mb-4 { margin-bottom: 1.5rem; }
                    .text-center { text-align: center; }
                    @media print {
                        body { margin: 0; padding: 20px; }
                        .card { page-break-inside: avoid; }
                        table { page-break-inside: avoid; }
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