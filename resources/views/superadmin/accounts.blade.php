<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akun - SILOG Polres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
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
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .btn-primary {
            background: var(--superadmin-color);
            border-color: var(--superadmin-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: #7c3aed;
            border-color: #7c3aed;
        }
        
        .btn-outline-primary {
            border-color: var(--superadmin-color);
            color: var(--superadmin-color);
        }
        
        .btn-outline-primary:hover {
            background: var(--superadmin-color);
            border-color: var(--superadmin-color);
        }
        
        /* Filter Card */
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
        
        .filter-form .form-label {
            font-weight: 500;
            color: #475569;
            font-size: 0.9rem;
        }
        
        .filter-form .form-control,
        .filter-form .form-select {
            border-color: #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
        }
        
        .filter-form .form-control:focus,
        .filter-form .form-select:focus {
            border-color: var(--superadmin-color);
            box-shadow: 0 0 0 0.25rem rgba(139, 92, 246, 0.25);
        }
        
        /* Table Card */
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
            border: 1px solid #e2e8f0;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e2e8f0;
            padding: 1rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table td {
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
        
        .badge-superadmin {
            background-color: rgba(139, 92, 246, 0.1);
            color: var(--superadmin-color);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }
        
        .badge-admin {
            background-color: rgba(30, 58, 138, 0.1);
            color: var(--primary);
            border: 1px solid rgba(30, 58, 138, 0.2);
        }
        
        .badge-user {
            background-color: rgba(14, 165, 233, 0.1);
            color: var(--info);
            border: 1px solid rgba(14, 165, 233, 0.2);
        }
        
        .badge-kabid {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .badge-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-inactive {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        
        /* Action Buttons in Table */
        .action-btns {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
            transition: all 0.2s;
        }
        
        .btn-icon:hover {
            background: #f8fafc;
            color: var(--dark);
        }
        
        .btn-icon-edit:hover {
            border-color: var(--warning);
            color: var(--warning);
        }
        
        .btn-icon-delete:hover {
            border-color: var(--secondary);
            color: var(--secondary);
        }
        
        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 1.5rem;
        }
        
        .page-link {
            border-color: #e2e8f0;
            color: #64748b;
            padding: 0.5rem 0.75rem;
        }
        
        .page-link:hover {
            background-color: #f1f5f9;
            color: var(--superadmin-color);
            border-color: #e2e8f0;
        }
        
        .page-item.active .page-link {
            background-color: var(--superadmin-color);
            border-color: var(--superadmin-color);
            color: white;
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
        
        /* Modal */
        .modal-header {
            background: linear-gradient(135deg, var(--superadmin-color) 0%, #6d28d9 100%);
            color: white;
            border-bottom: none;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .modal-content {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* Alert Container */
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
            
            .action-buttons {
                flex-wrap: wrap;
            }
            
            .filter-card .row > div {
                margin-bottom: 1rem;
            }
            
            .action-btns {
                flex-wrap: wrap;
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
                <a href="{{ route('superadmin.accounts.index') }}" class="nav-link active">
                    <i class="bi bi-people"></i>
                    <span>Manajemen Akun</span>
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
            <h4 class="mb-0">Manajemen Akun</h4>
            <div class="user-info">
                <div class="user-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <strong>{{ Auth::user()->name }}</strong><br>
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
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Manajemen Akun</h1>
                    <p>Kelola semua akun yang terdaftar dalam sistem</p>
                </div>
                <div>
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-person me-1"></i> Total: {{ $users->total() }} akun
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('superadmin.accounts.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-2"></i> Tambah Akun Baru
            </a>
            <button class="btn btn-outline-primary" onclick="exportToExcel()">
                <i class="bi bi-file-earmark-excel me-2"></i> Export Excel
            </button>
            <button class="btn btn-outline-primary" onclick="printTable()">
                <i class="bi bi-printer me-2"></i> Print
            </button>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-2"></i> Import Akun
            </button>
        </div>
        
        <!-- Filter Card -->
        <div class="filter-card">
            <h6><i class="bi bi-funnel me-2"></i>Filter & Pencarian</h6>
            <form method="GET" action="{{ route('superadmin.accounts.index') }}" class="filter-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari Akun</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nama, email, atau username...">
                    </div>
                    <div class="col-md-2">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Semua Role</option>
                            <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kabid" {{ request('role') == 'kabid' ? 'selected' : '' }}>Kabid</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="satker" class="form-label">Satuan Kerja</label>
                        <select class="form-select" id="satker" name="satker_id">
                            <option value="">Semua Satker</option>
                            @foreach($satkers as $satker)
                                <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                                    {{ $satker->nama_satker }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Users Table -->
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Daftar Akun</h5>
                <div>
                    @if(request()->hasAny(['search', 'role', 'status', 'satker_id']))
                        <a href="{{ route('superadmin.accounts.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                            <i class="bi bi-x-circle me-1"></i> Reset Filter
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Akun</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Satker</th>
                            <th>Status</th>
                            <th>Terakhir Login</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="width: 36px; height: 36px; font-size: 0.9rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $user->name }}</strong>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->username ?? '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'superadmin')
                                    <span class="badge badge-superadmin">
                                        <i class="bi bi-shield-check me-1"></i> Superadmin
                                    </span>
                                @elseif($user->role == 'admin')
                                    <span class="badge badge-admin">
                                        <i class="bi bi-shield me-1"></i> Admin
                                    </span>
                                @elseif($user->role == 'kabid')
                                    <span class="badge badge-kabid">
                                        <i class="bi bi-person-badge me-1"></i> Kabid
                                    </span>
                                @else
                                    <span class="badge badge-user">
                                        <i class="bi bi-person me-1"></i> User
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user->satker)
                                    <span class="badge bg-light text-dark">
                                        {{ $user->satker->nama_satker }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input status-toggle" type="checkbox" 
                                           data-user-id="{{ $user->id }}"
                                           {{ $user->is_active ? 'checked' : '' }}
                                           onchange="toggleUserStatus({{ $user->id }}, this.checked)">
                                </div>
                                <span class="ms-2 {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td>
                                @if($user->last_login_at)
                                    <div class="text-nowrap">
                                        <small class="d-block">{{ $user->last_login_at->format('d/m/Y') }}</small>
                                        <small class="text-muted">{{ $user->last_login_at->format('H:i') }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">Belum login</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn btn-icon btn-icon-edit" onclick="editUser({{ $user->id }})" 
                                            title="Edit Akun">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-icon" onclick="viewUser({{ $user->id }})" 
                                            title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-icon btn-icon-delete" onclick="confirmDelete({{ $user->id }})" 
                                            title="Hapus Akun" {{ $user->id == Auth::id() ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h5 class="text-muted mb-3">Tidak ada akun ditemukan</h5>
                                <p class="text-muted mb-4">
                                    @if(request()->hasAny(['search', 'role', 'status', 'satker_id']))
                                        Coba ubah filter pencarian Anda atau
                                    @endif
                                    <a href="{{ route('superadmin.accounts.create') }}" class="text-decoration-none">
                                        tambah akun baru
                                    </a>
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    {{-- Previous Page Link --}}
                    @if($users->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach(range(1, $users->lastPage()) as $page)
                        @if($page == $users->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->url($page) }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if($users->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                        </li>
                    @endif
                </ul>
            </nav>
            @endif
            
            <!-- Summary -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <small class="text-muted">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} akun
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
        
        <!-- Summary Stats -->
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ $users->total() }}</h3>
                        <small class="text-muted">Total Akun</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ $users->where('is_active', true)->count() }}</h3>
                        <small class="text-muted">Akun Aktif</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ $users->where('role', 'admin')->count() }}</h3>
                        <small class="text-muted">Admin</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ $users->where('role', 'superadmin')->count() }}</h3>
                        <small class="text-muted">Superadmin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="bi bi-upload me-2"></i>Import Akun dari Excel
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Petunjuk Import:</strong>
                        <ul class="mb-0 mt-2">
                            <li>File harus berformat .xlsx atau .xls</li>
                            <li>Kolom yang diperlukan: Nama, Email, Username, Password, Role</li>
                            <li>Role yang valid: superadmin, admin, kabid, user</li>
                            <li>Download template <a href="#" onclick="downloadTemplate()">di sini</a></li>
                        </ul>
                    </div>
                    
                    <form id="importForm" action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="importFile" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="importFile" name="file" accept=".xlsx,.xls" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="sendWelcomeEmail" name="send_welcome_email">
                            <label class="form-check-label" for="sendWelcomeEmail">
                                Kirim email selamat datang ke akun baru
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitImport()">
                        <i class="bi bi-upload me-1"></i> Import
                    </button>
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
                        <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Akun
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus akun ini?</p>
                    <p class="text-danger">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        <strong>Peringatan:</strong> Aksi ini tidak dapat dibatalkan. Semua data akun akan dihapus permanen.
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        // Global variables
        let userToDelete = null;
        
        // Auto dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // User management functions
        function editUser(userId) {
            window.location.href = `/superadmin/accounts/${userId}/edit`;
        }
        
        function viewUser(userId) {
            window.location.href = `/superadmin/accounts/${userId}`;
        }
        
        function confirmDelete(userId) {
            userToDelete = userId;
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/superadmin/accounts/${userId}`;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
        
        function submitDelete() {
            if (userToDelete) {
                document.getElementById('deleteForm').submit();
            }
        }
        
        // Toggle user status
        function toggleUserStatus(userId, isActive) {
            fetch(`/superadmin/accounts/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    is_active: isActive
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(`Status akun berhasil diubah menjadi ${isActive ? 'Aktif' : 'Non-Aktif'}`, 'success');
                } else {
                    showAlert(data.message || 'Gagal mengubah status akun', 'danger');
                    // Revert toggle
                    const toggle = document.querySelector(`[data-user-id="${userId}"]`);
                    if (toggle) {
                        toggle.checked = !isActive;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat mengubah status', 'danger');
                // Revert toggle
                const toggle = document.querySelector(`[data-user-id="${userId}"]`);
                if (toggle) {
                    toggle.checked = !isActive;
                }
            });
        }
        
        // Export to Excel
        function exportToExcel() {
            const table = document.getElementById('usersTable');
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Akun');
            
            // Add header
            const header = [['DAFTAR AKUN - SILOG POLRES', '', '', '', '', '', '', '', '']];
            XLSX.utils.sheet_add_aoa(ws, header, { origin: 'A1' });
            
            // Save file
            XLSX.writeFile(wb, `akun_export_${new Date().toISOString().split('T')[0]}.xlsx`);
        }
        
        // Print table
        function printTable() {
            const originalContents = document.body.innerHTML;
            const printContents = document.getElementById('usersTable').outerHTML;
            
            document.body.innerHTML = `
                <html>
                <head>
                    <title>Daftar Akun - SILOG Polres</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; }
                        th { background-color: #f2f2f2; }
                        tr:nth-child(even) { background-color: #f9f9f9; }
                        @media print {
                            @page { size: landscape; }
                        }
                    </style>
                </head>
                <body>
                    <h2>DAFTAR AKUN - SILOG POLRES</h2>
                    <p>Tanggal cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                    ${printContents}
                </body>
                </html>
            `;
            
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
        
        // Import functions
        function downloadTemplate() {
            const templateData = [
                ['Nama', 'Email', 'Username', 'Password', 'Role', 'Satker ID'],
                ['John Doe', 'john@example.com', 'johndoe', 'password123', 'user', '1'],
                ['Jane Smith', 'jane@example.com', 'janesmith', 'password123', 'admin', '2']
            ];
            
            const ws = XLSX.utils.aoa_to_sheet(templateData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Template');
            XLSX.writeFile(wb, 'template_import_akun.xlsx');
        }
        
        function submitImport() {
            const fileInput = document.getElementById('importFile');
            const form = document.getElementById('importForm');
            
            if (!fileInput.files.length) {
                showAlert('Pilih file terlebih dahulu', 'warning');
                return;
            }
            
            // Show loading state
            const importBtn = document.querySelector('#importModal .btn-primary');
            const originalText = importBtn.innerHTML;
            importBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengimpor...';
            importBtn.disabled = true;
            
            form.submit();
        }
        
        // Change items per page
        function changePerPage(perPage) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
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
        
        // Initialize Bootstrap Select
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize select pickers
            const selectElements = document.querySelectorAll('select.form-select');
            selectElements.forEach(select => {
                select.addEventListener('change', function() {
                    // Auto submit filter form on role/status/satker change
                    if (this.id === 'role' || this.id === 'status' || this.id === 'satker') {
                        document.querySelector('.filter-form').submit();
                    }
                });
            });
            
            // Mobile sidebar toggle
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth <= 768) {
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
            
            // Table row click event
            const tableRows = document.querySelectorAll('#usersTable tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('click', function(e) {
                    // Don't trigger if clicking on action buttons
                    if (!e.target.closest('.action-btns')) {
                        const userId = this.querySelector('[data-user-id]')?.getAttribute('data-user-id');
                        if (userId) {
                            viewUser(userId);
                        }
                    }
                });
            });
            
            // Quick search with debounce
            let searchTimeout;
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (this.value.length >= 3 || this.value.length === 0) {
                            document.querySelector('.filter-form').submit();
                        }
                    }, 500);
                });
            }
        });
    </script>
</body>
</html>