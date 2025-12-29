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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            color: var(--dark);
            font-weight: 600;
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
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        /* Badges */
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 500;
        }
        
        .badge-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-inactive {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        
        /* Action Buttons */
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }
        
        /* Search and Filter */
        .filter-bar {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }
        
        .search-box {
            flex: 1;
            min-width: 300px;
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
        
        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }
        
        .empty-state p {
            color: #64748b;
            font-size: 1.1rem;
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
            
            .search-box {
                min-width: 100%;
            }
            
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
        }
        
        /* Custom Styles */
        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + .75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
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
                <h1 class="page-title">Manajemen Satuan Kerja (Satker)</h1>
                <p class="text-muted mb-0">Kelola data satuan kerja di bawah naungan Polres</p>
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
                <div class="stat-icon" style="background-color: #d1fae5; color: var(--success);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['active_satker'] ?? 0 }}</h3>
                    <p>Satker Aktif</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #fef3c7; color: var(--warning);">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['inactive_satker'] ?? 0 }}</h3>
                    <p>Satker Non-Aktif</p>
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
        </div>
        
        <!-- Filter and Search -->
        <div class="filter-bar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Cari satker..." id="searchInput">
            </div>
            
            <div class="d-flex gap-2">
                <select class="form-select" style="min-width: 150px;" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Non-Aktif</option>
                </select>
                
                <button class="btn btn-outline-secondary" id="resetFilter">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div>
        
        <!-- Main Table Card -->
        <div class="main-card">
            <div class="card-header">
                <h5>Daftar Satker</h5>
                <div>
                    <span class="text-muted">Total: {{ $satkers->total() }} satker</span>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Satker</th>
                            <th>Nama Satker</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Jumlah User</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($satkers as $index => $satker)
                        <tr>
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
                                        <strong>{{ $satker->nama_satker }}</strong><br>
                                        <small class="text-muted">{{ $satker->kategori ?? 'Satker Umum' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ Str::limit($satker->alamat ?? 'Belum diisi', 30) }}</td>
                            <td>{{ $satker->telepon ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $satker->users_count ?? 0 }}</span> user
                            </td>
                            <td>
                                @if($satker->is_active)
                                    <span class="badge badge-active">
                                        <i class="bi bi-check-circle me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge badge-inactive">
                                        <i class="bi bi-x-circle me-1"></i>Non-Aktif
                                    </span>
                                @endif
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
                                    <i class="bi bi-building"></i>
                                    <p class="mb-3">Belum ada data satker</p>
                                    <button type="button" class="btn btn-primary" onclick="showAddSatkerForm()">
                                        <i class="bi bi-plus-circle me-2"></i>Tambah Satker Pertama
                                    </button>
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
        </div>
        
        <!-- Quick Info -->
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6><i class="bi bi-info-circle me-2"></i>Informasi Manajemen Satker</h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-building text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small>Total Satker</small>
                                        <p class="mb-0"><strong>{{ $stats['total_satker'] ?? 0 }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-check-circle text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small>Satker Aktif</small>
                                        <p class="mb-0"><strong>{{ $stats['active_satker'] ?? 0 }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-people text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small>Total User</small>
                                        <p class="mb-0"><strong>{{ $stats['total_users'] ?? 0 }}</strong></p>
                                    </div>
                                </div>
                            </div>
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
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus satker <strong id="satkerName"></strong>?</p>
                    <p class="text-danger">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        <small>Data yang dihapus tidak dapat dikembalikan.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                    </form>
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="satkerForm">
                        @csrf
                        <input type="hidden" name="form_type" id="formType">
                        <input type="hidden" name="id" id="satkerId">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_satker" class="form-label">Kode Satker <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_satker" name="kode_satker" required>
                                <div class="invalid-feedback" id="kode_satker_error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nama_satker" class="form-label">Nama Satker <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_satker" name="nama_satker" required>
                                <div class="invalid-feedback" id="nama_satker_error"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            <div class="invalid-feedback" id="alamat_error"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                                <div class="invalid-feedback" id="email_error"></div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_kepala" class="form-label">Nama Kepala</label>
                                <input type="text" class="form-control" id="nama_kepala" name="nama_kepala">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="pangkat_kepala" class="form-label">Pangkat Kepala</label>
                                <input type="text" class="form-control" id="pangkat_kepala" name="pangkat_kepala">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nrp_kepala" class="form-label">NRP Kepala</label>
                            <input type="text" class="form-control" id="nrp_kepala" name="nrp_kepala">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">Status Aktif</label>
                            </div>
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
            
            // Setup filter functionality
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', filterSatkers);
            }
            
            // Setup reset filter
            const resetBtn = document.getElementById('resetFilter');
            if (resetBtn) {
                resetBtn.addEventListener('click', resetFilters);
            }
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
                    document.getElementById('is_active').checked = data.data.is_active == 1;
                    
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
            document.getElementById('satkerName').textContent = name;
            document.getElementById('deleteForm').action = `/superadmin/satker/${id}`;
            deleteModal.show();
        }
        
        // ==================== VIEW SATKER FUNCTION - FIXED ====================
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
            
            // Pastikan modal sudah diinisialisasi
            if (!viewModal) {
                viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
            }
            viewModal.show();
            
            // **PERBAIKAN: Gunakan URL baru ajax-view**
            const url = `/superadmin/satker/ajax-view/${id}`;
            console.log('Fetching from NEW URL:', url);
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response URL:', response.url);
                
                if (!response.ok) {
                    if (response.status === 404) {
                        throw new Error(`Endpoint tidak ditemukan: ${url}. Periksa route.`);
                    }
                    return response.json().then(err => {
                        throw new Error(err.message || `HTTP Error ${response.status}`);
                    }).catch(() => {
                        throw new Error(`HTTP ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success && data.data) {
                    const satker = data.data;
                    
                    // Format tanggal
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
                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);">
                                            <i class="bi bi-building" style="font-size: 2rem;"></i>
                                        </div>
                                        <h5 class="mb-1">${satker.nama_satker || 'N/A'}</h5>
                                        <p class="text-muted mb-2">${satker.kode_satker || 'N/A'}</p>
                                        ${satker.is_active ? 
                                            '<span class="badge badge-active"><i class="bi bi-check-circle me-1"></i>Aktif</span>' : 
                                            '<span class="badge badge-inactive"><i class="bi bi-x-circle me-1"></i>Non-Aktif</span>'
                                        }
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <h6>Informasi Satker</h6>
                                    <hr class="mt-1">
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Kategori:</strong></div>
                                        <div class="col-8">${satker.kategori || 'Satker Umum'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Alamat:</strong></div>
                                        <div class="col-8">${satker.alamat || 'Belum diisi'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Telepon:</strong></div>
                                        <div class="col-8">${satker.telepon || '-'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Email:</strong></div>
                                        <div class="col-8">${satker.email || '-'}</div>
                                    </div>
                                    ${satker.nama_kepala ? `
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Kepala Satker:</strong></div>
                                        <div class="col-8">${satker.nama_kepala} ${satker.pangkat_kepala ? `(${satker.pangkat_kepala})` : ''}</div>
                                    </div>` : ''}
                                    ${satker.nrp_kepala ? `
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>NRP Kepala:</strong></div>
                                        <div class="col-8">${satker.nrp_kepala}</div>
                                    </div>` : ''}
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Statistik</h6>
                                    <hr class="mt-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Total User</small>
                                            <p class="mb-0"><strong>${satker.users_count || 0}</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Total Permintaan</small>
                                            <p class="mb-0"><strong>${satker.permintaans_count || 0}</strong></p>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <small class="text-muted">Tanggal Dibuat</small>
                                            <p class="mb-0"><strong>${satker.created_at_formatted || formatDate(satker.created_at)}</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Terakhir Diperbarui</small>
                                            <p class="mb-0"><strong>${satker.updated_at_formatted || formatDate(satker.updated_at)}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    document.getElementById('satkerDetail').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ${data.message || 'Gagal memuat data satker'}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Fetch error details:', error);
                document.getElementById('satkerDetail').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Error: ${error.message}<br>
                        <small>URL: ${url}</small><br>
                        <small>Silakan coba lagi atau hubungi administrator.</small>
                    </div>
                `;
            });
        }
        // ==================== END OF FIXED FUNCTION ====================
        
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
        
        // Filter functionality
        function filterSatkers() {
            const status = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if (row.querySelector('.empty-state')) return;
                
                const statusCell = row.querySelector('td:nth-child(7)').textContent.toLowerCase();
                let showRow = true;
                
                if (status === 'active' && !statusCell.includes('aktif')) {
                    showRow = false;
                } else if (status === 'inactive' && !statusCell.includes('non-aktif')) {
                    showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }
        
        // Reset filters
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }
        
        // Auto dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
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