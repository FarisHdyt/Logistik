<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - SILOG Polres</title>
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
            color: #1e293b;
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
        
        /* Badges - DIPERBAIKI DENGAN CSS YANG SANGAT SPESIFIK */
        .badge-login {
            background-color: #22c55e !important;
            color: white !important;
            padding: 0.4rem 0.8rem !important;
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            border: none !important;
        }
        
        .badge-logout {
            background-color: #94a3b8 !important;
            color: white !important;
            padding: 0.4rem 0.8rem !important;
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            border: none !important;
        }
        
        .badge-create {
            background-color: #3b82f6 !important;
            color: white !important;
            padding: 0.4rem 0.8rem !important;
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            border: none !important;
        }
        
        .badge-update {
            background-color: #f59e0b !important;
            color: white !important;
            padding: 0.4rem 0.8rem !important;
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            border: none !important;
        }
        
        .badge-delete {
            background-color: #ef4444 !important;
            color: white !important;
            padding: 0.4rem 0.8rem !important;
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            border: none !important;
        }
        
        .badge-secondary {
            background-color: #64748b !important;
            color: white !important;
            padding: 0.4rem 0.8rem !important;
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            border: none !important;
        }
        
        /* Filter Bar */
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
        
        .filter-controls {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: white;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: var(--superadmin-color);
            color: white;
            border-color: var(--superadmin-color);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: white;
            color: #64748b;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            background: #f8fafc;
            border-color: var(--superadmin-color);
            color: var(--superadmin-color);
        }
        
        /* Log Details Modal */
        .log-details {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }
        
        .log-details pre {
            background: white;
            padding: 1rem;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            font-size: 0.9rem;
            max-height: 300px;
            overflow: auto;
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
        
        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        
        /* Loading Spinner */
        .spinner-border {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
        }
        
        /* Alert Container */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
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
            
            .filter-controls {
                justify-content: center;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h3>SILOG</h3>
            <p>Log Aktivitas Sistem</p>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <!-- Menu Validasi Pengadaan ditambahkan di sini -->
            <div class="nav-item">
                <a href="{{ route('superadmin.procurement') }}" class="nav-link">
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
                <a href="{{ route('superadmin.activity-logs') }}" class="nav-link active">
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
        <!-- Alert Container -->
        <div class="alert-container"></div>
        
        <!-- Top Bar -->
        <div class="topbar">
            <h4 class="mb-0">Log Aktivitas Sistem</h4>
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
        
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Log Aktivitas Sistem</h1>
                <p class="text-muted mb-0">Monitor aktivitas semua pengguna sistem</p>
            </div>
            <div class="action-buttons">
                <button class="btn btn-outline-primary" onclick="refreshLogs()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
                <button class="btn btn-outline-danger" onclick="clearLogs()">
                    <i class="bi bi-trash"></i> Hapus Semua Log
                </button>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #ede9fe; color: var(--superadmin-color);">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_logs'] ?? 0 }}</h3>
                    <p>Total Log</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #d1fae5; color: var(--success);">
                    <i class="bi bi-person-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['today_logs'] ?? 0 }}</h3>
                    <p>Log Hari Ini</p>
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
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['error_logs'] ?? 0 }}</h3>
                    <p>Log Error</p>
                </div>
            </div>
        </div>
        
        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Cari log berdasarkan user, aksi, atau deskripsi..." id="searchInput" value="{{ request('search') }}">
            </div>
            
            <div class="filter-controls">
                <select class="form-select" style="min-width: 150px;" id="actionFilter">
                    <option value="">Semua Aksi</option>
                    <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                    <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                    <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                    <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                </select>
                
                <select class="form-select" style="min-width: 150px;" id="userFilter">
                    <option value="">Semua User</option>
                    @foreach($users as $logUser)
                        <option value="{{ $logUser->id }}" {{ request('user_id') == $logUser->id ? 'selected' : '' }}>
                            {{ $logUser->name }}
                        </option>
                    @endforeach
                </select>
                
                <button class="btn btn-primary" onclick="applyFilters()">
                    <i class="bi bi-filter"></i> Filter
                </button>
                
                <button class="btn btn-outline-secondary" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div>
        
        <!-- Quick Filter Buttons -->
        <div class="mb-3">
            <button class="filter-btn {{ !request()->has('time_filter') ? 'active' : '' }}" onclick="setTimeFilter('all')">
                Semua
            </button>
            <button class="filter-btn {{ request('time_filter') == 'today' ? 'active' : '' }}" onclick="setTimeFilter('today')">
                Hari Ini
            </button>
            <button class="filter-btn {{ request('time_filter') == 'week' ? 'active' : '' }}" onclick="setTimeFilter('week')">
                7 Hari Terakhir
            </button>
            <button class="filter-btn {{ request('time_filter') == 'month' ? 'active' : '' }}" onclick="setTimeFilter('month')">
                Bulan Ini
            </button>
        </div>
        
        <!-- Logs Table Card -->
        <div class="main-card">
            <div class="card-header">
                <h5>Daftar Log Aktivitas</h5>
                <div>
                    <span class="text-muted">Total: {{ $logs->total() }} log</span>
                </div>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $index + 1 + (($logs->currentPage() - 1) * $logs->perPage()) }}</td>
                            <td>
                                @if($log->user)
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ substr($log->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $log->user->name }}</strong><br>
                                        <small class="text-muted">{{ $log->user->email }}</small>
                                    </div>
                                </div>
                                @else
                                <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    // PASTIKAN ACTION DI-LOWERCASE DAN TRIM
                                    $action = strtolower(trim($log->action ?? 'unknown'));
                                    
                                    $badgeClass = [
                                        'login' => 'badge-login',
                                        'logout' => 'badge-logout',
                                        'create' => 'badge-create',
                                        'update' => 'badge-update',
                                        'delete' => 'badge-delete',
                                        'created' => 'badge-create',
                                        'updated' => 'badge-update',
                                        'deleted' => 'badge-delete',
                                    ][$action] ?? 'badge-secondary';
                                    
                                    $badgeStyle = [
                                        'login' => 'background-color: #22c55e !important; color: white !important;',
                                        'logout' => 'background-color: #94a3b8 !important; color: white !important;',
                                        'create' => 'background-color: #3b82f6 !important; color: white !important;',
                                        'update' => 'background-color: #f59e0b !important; color: white !important;',
                                        'delete' => 'background-color: #ef4444 !important; color: white !important;',
                                        'created' => 'background-color: #3b82f6 !important; color: white !important;',
                                        'updated' => 'background-color: #f59e0b !important; color: white !important;',
                                        'deleted' => 'background-color: #ef4444 !important; color: white !important;',
                                    ][$action] ?? 'background-color: #64748b !important; color: white !important;';
                                @endphp
                                <span class="badge {{ $badgeClass }}" style="{{ $badgeStyle }}">
                                    {{ ucfirst($action) }}
                                </span>
                            </td>
                            <td>{{ $log->description ?? '-' }}</td>
                            <td>
                                <code>{{ $log->ip_address ?? '-' }}</code>
                                @if($log->user_agent)
                                <br>
                                @php
                                    // Parsing user agent untuk tampilan yang lebih friendly
                                    $userAgent = strtolower($log->user_agent);
                                    $browser = 'Unknown';
                                    $os = 'Unknown';
                                    $device = 'Desktop';
                                    
                                    // DETEKSI BROWSER DENGAN URUTAN YANG TEPAT
                                    // 1. Opera harus dicek duluan karena mengandung "OPR" dan "Chrome"
                                    if (strpos($userAgent, 'opr') !== false || strpos($userAgent, 'opera') !== false) {
                                        $browser = 'Opera';
                                    }
                                    // 2. Edge harus dicek sebelum Chrome karena mengandung "Edg" dan "Chrome"
                                    elseif (strpos($userAgent, 'edg') !== false) {
                                        $browser = 'Edge';
                                    }
                                    // 3. Firefox
                                    elseif (strpos($userAgent, 'firefox') !== false) {
                                        $browser = 'Firefox';
                                    }
                                    // 4. Internet Explorer
                                    elseif (strpos($userAgent, 'msie') !== false || strpos($userAgent, 'trident') !== false) {
                                        $browser = 'Internet Explorer';
                                    }
                                    // 5. Safari (tanpa Chrome)
                                    elseif (strpos($userAgent, 'safari') !== false && strpos($userAgent, 'chrome') === false) {
                                        $browser = 'Safari';
                                    }
                                    // 6. Chrome (setelah semua browser lain dicek)
                                    elseif (strpos($userAgent, 'chrome') !== false) {
                                        $browser = 'Chrome';
                                    }
                                    // 7. Browser lainnya
                                    elseif (strpos($userAgent, 'brave') !== false) {
                                        $browser = 'Brave';
                                    }
                                    elseif (strpos($userAgent, 'vivaldi') !== false) {
                                        $browser = 'Vivaldi';
                                    }
                                    elseif (strpos($userAgent, 'ucbrowser') !== false) {
                                        $browser = 'UC Browser';
                                    }
                                    
                                    // Deteksi OS dengan urutan yang tepat
                                    if (strpos($userAgent, 'windows') !== false) {
                                        $os = 'Windows';
                                        // Versi Windows
                                        if (strpos($userAgent, 'windows nt 10') !== false) {
                                            $os = 'Windows 10/11';
                                        } elseif (strpos($userAgent, 'windows nt 6.3') !== false) {
                                            $os = 'Windows 8.1';
                                        } elseif (strpos($userAgent, 'windows nt 6.2') !== false) {
                                            $os = 'Windows 8';
                                        } elseif (strpos($userAgent, 'windows nt 6.1') !== false) {
                                            $os = 'Windows 7';
                                        }
                                    } elseif (strpos($userAgent, 'mac os x') !== false || strpos($userAgent, 'macintosh') !== false) {
                                        $os = 'macOS';
                                    } elseif (strpos($userAgent, 'linux') !== false) {
                                        $os = 'Linux';
                                    } elseif (strpos($userAgent, 'android') !== false) {
                                        $os = 'Android';
                                        $device = 'Mobile';
                                    } elseif (strpos($userAgent, 'iphone') !== false) {
                                        $os = 'iOS';
                                        $device = 'iPhone';
                                    } elseif (strpos($userAgent, 'ipad') !== false) {
                                        $os = 'iOS';
                                        $device = 'iPad';
                                    } elseif (strpos($userAgent, 'ios') !== false) {
                                        $os = 'iOS';
                                    }
                                    
                                    // Deteksi perangkat mobile
                                    if (strpos($userAgent, 'mobile') !== false && strpos($userAgent, 'ipad') === false) {
                                        $device = 'Mobile';
                                    }
                                    
                                    // Tentukan icon berdasarkan browser
                                    $browserColors = [
                                        'Chrome' => ['icon' => 'bi-browser-chrome', 'color' => '#4285F4'],
                                        'Firefox' => ['icon' => 'bi-browser-firefox', 'color' => '#FF7139'],
                                        'Safari' => ['icon' => 'bi-browser-safari', 'color' => '#1B73E8'],
                                        'Edge' => ['icon' => 'bi-browser-edge', 'color' => '#0078D7'],
                                        'Opera' => ['icon' => 'bi-globe', 'color' => '#FF1B2D'], // Diganti ke bi-globe
                                        'Internet Explorer' => ['icon' => 'bi-browser-ie', 'color' => '#1EBBEE'],
                                        'Brave' => ['icon' => 'bi-shield-check', 'color' => '#FB542B'],
                                        'Vivaldi' => ['icon' => 'bi-gear', 'color' => '#EF3939'], // Diganti ke bi-gear
                                        'UC Browser' => ['icon' => 'bi-phone', 'color' => '#FF6600'],
                                        'Unknown' => ['icon' => 'bi-globe', 'color' => '#64748b'],
                                    ];
                                    
                                    if (isset($browserColors[$browser])) {
                                        $browserIcon = $browserColors[$browser]['icon'];
                                    }
                                    
                                    // Tentukan icon berdasarkan OS
                                    $osIcon = 'bi-pc';
                                    $osColors = [
                                        'Windows' => ['icon' => 'bi-windows', 'color' => '#0078D7'],
                                        'Windows 10/11' => ['icon' => 'bi-windows', 'color' => '#0078D7'],
                                        'macOS' => ['icon' => 'bi-apple', 'color' => '#000000'],
                                        'Linux' => ['icon' => 'bi-ubuntu', 'color' => '#DD4814'],
                                        'Android' => ['icon' => 'bi-android2', 'color' => '#3DDC84'],
                                        'iOS' => ['icon' => 'bi-apple', 'color' => '#000000'],
                                        'Unknown' => ['icon' => 'bi-pc', 'color' => '#64748b'],
                                    ];
                                    
                                    if (isset($osColors[$os])) {
                                        $osIcon = $osColors[$os]['icon'];
                                    }
                                @endphp
                                <small class="text-muted">
                                    <i class="{{ $browserIcon }} me-1"></i>{{ $browser }} on 
                                    <i class="{{ $osIcon }} me-1"></i>{{ $os }}
                                    @if($device !== 'Desktop')
                                    <br><i class="bi bi-phone me-1"></i>{{ $device }}
                                    @endif
                                </small>
                                @endif
                            </td>
                            <td>
                                {{ $log->created_at->format('d/m/Y') }}<br>
                                <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewLogDetails({{ $log->id }})" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-clock-history"></i>
                                    <p class="mb-3">Tidak ada log aktivitas</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($logs->hasPages())
            <div class="pagination-container">
                <nav>
                    {{ $logs->withQueryString()->links('pagination::bootstrap-5') }}
                </nav>
            </div>
            @endif
        </div>
        
        <!-- Activity Summary -->
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6><i class="bi bi-bar-chart me-2"></i>Ringkasan Aktivitas</h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-clock-history text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted">Total Log</small>
                                        <p class="mb-0"><strong>{{ $stats['total_logs'] ?? 0 }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-person-check text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted">Log Hari Ini</small>
                                        <p class="mb-0"><strong>{{ $stats['today_logs'] ?? 0 }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-people text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted">User Aktif</small>
                                        <p class="mb-0"><strong>{{ $stats['active_users'] ?? 0 }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-exclamation-triangle text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted">Log Error</small>
                                        <p class="mb-0"><strong>{{ $stats['error_logs'] ?? 0 }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Log Details Modal -->
    <div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logDetailsModalLabel">Detail Log Aktivitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="logDetailsContent">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat data...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confirm Clear Modal -->
    <div class="modal fade" id="confirmClearModal" tabindex="-1" aria-labelledby="confirmClearModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmClearModalLabel">
                        <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus semua log aktivitas?</p>
                    <p class="text-danger">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        <small>Data yang dihapus tidak dapat dikembalikan.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmClearLogs()">
                        <i class="bi bi-trash me-1"></i>Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize modals
        let logDetailsModal = null;
        let confirmClearModal = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            logDetailsModal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
            confirmClearModal = new bootstrap.Modal(document.getElementById('confirmClearModal'));
            
            // Setup search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        applyFilters();
                    } else {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            applyFilters();
                        }, 1000);
                    }
                });
            }
            
            // Auto dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.parentElement === document.querySelector('.alert-container')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 5000);
        });
        
        // Function untuk parsing user agent menjadi format yang user-friendly
        function parseUserAgent(userAgent) {
            if (!userAgent) return null;
            
            const ua = userAgent.toLowerCase();
            let browser = 'Unknown';
            let os = 'Unknown';
            let device = 'Desktop';
            
            // Browser detection dengan urutan yang tepat
            // Opera harus pertama karena mengandung "OPR" dan "Chrome"
            if (ua.includes('opr') || ua.includes('opera')) {
                browser = 'Opera';
            }
            // Edge harus sebelum Chrome karena mengandung "Edg" dan "Chrome"
            else if (ua.includes('edg')) {
                browser = 'Edge';
            }
            // Firefox
            else if (ua.includes('firefox')) {
                browser = 'Firefox';
            }
            // Internet Explorer
            else if (ua.includes('msie') || ua.includes('trident')) {
                browser = 'Internet Explorer';
            }
            // Safari (tanpa Chrome)
            else if (ua.includes('safari') && !ua.includes('chrome')) {
                browser = 'Safari';
            }
            // Chrome (setelah semua browser lain dicek)
            else if (ua.includes('chrome')) {
                browser = 'Chrome';
            }
            // Browser lainnya
            else if (ua.includes('brave')) {
                browser = 'Brave';
            }
            else if (ua.includes('vivaldi')) {
                browser = 'Vivaldi';
            }
            else if (ua.includes('ucbrowser')) {
                browser = 'UC Browser';
            }
            
            // OS detection
            if (ua.includes('windows')) {
                os = 'Windows';
                if (ua.includes('windows nt 10')) {
                    os = 'Windows 10/11';
                } else if (ua.includes('windows nt 6.3')) {
                    os = 'Windows 8.1';
                } else if (ua.includes('windows nt 6.2')) {
                    os = 'Windows 8';
                } else if (ua.includes('windows nt 6.1')) {
                    os = 'Windows 7';
                }
            } else if (ua.includes('mac os x') || ua.includes('macintosh')) {
                os = 'macOS';
            } else if (ua.includes('linux')) {
                os = 'Linux';
            } else if (ua.includes('android')) {
                os = 'Android';
                device = 'Mobile';
            } else if (ua.includes('iphone')) {
                os = 'iOS';
                device = 'iPhone';
            } else if (ua.includes('ipad')) {
                os = 'iOS';
                device = 'iPad';
            } else if (ua.includes('ios')) {
                os = 'iOS';
            }
            
            // Device detection
            if (ua.includes('mobile') && !ua.includes('ipad')) {
                device = 'Mobile';
            }
            
            // Browser icons and colors - MENGGUNAKAN ICON UMUM UNTUK BROWSER YANG TIDAK PUNYA ICON KHUSUS
            const browserIcons = {
                'Chrome': 'bi-browser-chrome',
                'Firefox': 'bi-browser-firefox',
                'Safari': 'bi-browser-safari',
                'Edge': 'bi-browser-edge',
                'Opera': 'bi-globe', // Diganti dari bi-music-note
                'Internet Explorer': 'bi-browser-ie',
                'Brave': 'bi-shield-check',
                'Vivaldi': 'bi-gear', // Diganti dari bi-palette
                'UC Browser': 'bi-phone',
                'Unknown': 'bi-globe'
            };
            
            const browserColors = {
                'Chrome': '#4285F4',
                'Firefox': '#FF7139',
                'Safari': '#1B73E8',
                'Edge': '#0078D7',
                'Opera': '#FF1B2D',
                'Internet Explorer': '#1EBBEE',
                'Brave': '#FB542B',
                'Vivaldi': '#EF3939',
                'UC Browser': '#FF6600',
                'Unknown': '#64748b'
            };
            
            // OS icons and colors
            const osIcons = {
                'Windows': 'bi-windows',
                'Windows 10/11': 'bi-windows',
                'macOS': 'bi-apple',
                'Linux': 'bi-ubuntu',
                'Android': 'bi-android2',
                'iOS': 'bi-apple',
                'Unknown': 'bi-pc'
            };
            
            const osColors = {
                'Windows': '#0078D7',
                'Windows 10/11': '#0078D7',
                'macOS': '#000000',
                'Linux': '#DD4814',
                'Android': '#3DDC84',
                'iOS': '#000000',
                'Unknown': '#64748b'
            };
            
            return {
                browser: browser,
                os: os,
                device: device,
                browserIcon: browserIcons[browser] || 'bi-globe',
                browserColor: browserColors[browser] || '#64748b',
                osIcon: osIcons[os] || 'bi-pc',
                osColor: osColors[os] || '#64748b',
                display: `${browser} on ${os}` + (device !== 'Desktop' ? ` (${device})` : '')
            };
        }
        
        // View log details - dengan browser/device yang user-friendly
        function viewLogDetails(logId) {
            const contentDiv = document.getElementById('logDetailsContent');
            contentDiv.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
            `;
            
            fetch(`/superadmin/activity-logs/${logId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data) {
                        const log = data.data;
                        const browserInfo = parseUserAgent(log.user_agent);
                        
                        // Format timestamps
                        const formatDate = (dateString) => {
                            if (!dateString) return '-';
                            try {
                                const date = new Date(dateString);
                                return date.toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit'
                                });
                            } catch (e) {
                                return dateString;
                            }
                        };
                        
                        // Get badge class
                        const getBadgeClass = (action) => {
                            const actionLower = (action || '').toLowerCase().trim();
                            const badgeClasses = {
                                'login': 'badge-login',
                                'logout': 'badge-logout',
                                'create': 'badge-create',
                                'update': 'badge-update',
                                'delete': 'badge-delete',
                                'created': 'badge-create',
                                'updated': 'badge-update',
                                'deleted': 'badge-delete'
                            };
                            return badgeClasses[actionLower] || 'badge-secondary';
                        };
                        
                        contentDiv.innerHTML = `
                            <div class="log-details">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6>Informasi Log</h6>
                                        <div class="mb-2">
                                            <strong>ID:</strong> ${log.id || '-'}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Aksi:</strong>
                                            <span class="badge ${getBadgeClass(log.action)} ms-2">${log.action || '-'}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Deskripsi:</strong><br>
                                            ${log.description || '-'}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Informasi User</h6>
                                        <div class="mb-2">
                                            <strong>User:</strong> ${log.user ? log.user.name : 'System'}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Email:</strong> ${log.user ? log.user.email : '-'}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Role:</strong> ${log.user ? (log.user.role || '-') : '-'}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6>Informasi Teknis</h6>
                                        <div class="mb-2">
                                            <strong>IP Address:</strong><br>
                                            <code>${log.ip_address || '-'}</code>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Browser/Device:</strong><br>
                                            ${browserInfo ? `
                                            <div class="mt-1">
                                                <span class="badge me-1" style="background-color: ${browserInfo.browserColor} !important; color: white !important;">
                                                    <i class="${browserInfo.browserIcon} me-1"></i>${browserInfo.browser}
                                                </span>
                                                <span class="badge me-1" style="background-color: ${browserInfo.osColor} !important; color: white !important;">
                                                    <i class="${browserInfo.osIcon} me-1"></i>${browserInfo.os}
                                                </span>
                                                ${browserInfo.device !== 'Desktop' ? 
                                                    `<span class="badge bg-warning">
                                                        <i class="bi bi-phone me-1"></i>${browserInfo.device}
                                                    </span>` : ''
                                                }
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted">${browserInfo.display}</small>
                                            </div>
                                            ` : '<small>Tidak ada data browser</small>'}
                                            ${log.user_agent ? `
                                            <div class="mt-2">
                                                <small class="text-muted">Raw User Agent:</small><br>
                                                <small><code>${log.user_agent}</code></small>
                                            </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Waktu</h6>
                                        <div class="mb-2">
                                            <strong>Dibuat:</strong><br>
                                            ${formatDate(log.created_at)}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Diperbarui:</strong><br>
                                            ${formatDate(log.updated_at)}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        contentDiv.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                ${data.message || 'Gagal memuat data log'}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    contentDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Terjadi kesalahan saat memuat data. Silahkan coba lagi.
                        </div>
                    `;
                });
            
            logDetailsModal.show();
        }
        
        // Get badge class for action
        function getBadgeClass(action) {
            const actionLower = (action || '').toLowerCase().trim();
            const badgeClasses = {
                'login': 'badge-login',
                'logout': 'badge-logout',
                'create': 'badge-create',
                'update': 'badge-update',
                'delete': 'badge-delete',
                'created': 'badge-create',
                'updated': 'badge-update',
                'deleted': 'badge-delete'
            };
            return badgeClasses[actionLower] || 'badge-secondary';
        }
        
        // Apply filters
        function applyFilters() {
            const search = document.getElementById('searchInput').value;
            const action = document.getElementById('actionFilter').value;
            const userId = document.getElementById('userFilter').value;
            
            let url = '?';
            if (search) url += `search=${encodeURIComponent(search)}&`;
            if (action) url += `action=${action}&`;
            if (userId) url += `user_id=${userId}&`;
            
            if (url.endsWith('&')) url = url.slice(0, -1);
            if (url === '?') url = '';
            
            window.location.href = window.location.pathname + url;
        }
        
        // Set time filter
        function setTimeFilter(filter) {
            let url = '?';
            if (filter !== 'all') {
                url += `time_filter=${filter}&`;
            }
            
            const searchParams = new URLSearchParams(window.location.search);
            searchParams.forEach((value, key) => {
                if (key !== 'time_filter') {
                    url += `${key}=${value}&`;
                }
            });
            
            if (url.endsWith('&')) url = url.slice(0, -1);
            if (url === '?') url = '';
            
            window.location.href = window.location.pathname + url;
        }
        
        // Reset filters
        function resetFilters() {
            window.location.href = window.location.pathname;
        }
        
        // Refresh logs
        function refreshLogs() {
            window.location.reload();
        }
        
        // Show clear logs confirmation
        function clearLogs() {
            confirmClearModal.show();
        }
        
        // Confirm clear logs
        function confirmClearLogs() {
            fetch('/superadmin/activity-logs/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert('Semua log berhasil dihapus', 'success');
                    confirmClearModal.hide();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(data.message || 'Gagal menghapus log', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menghapus log', 'danger');
            });
        }
        
        // Show alert message
        function showAlert(message, type = 'info') {
            const alertContainer = document.querySelector('.alert-container');
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.role = 'alert';
            alert.innerHTML = `
                <i class="bi ${type === 'danger' ? 'bi-exclamation-triangle' : type === 'success' ? 'bi-check-circle' : 'bi-info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            alertContainer.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
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