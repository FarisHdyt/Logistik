<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem - Superadmin SILOG</title>
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
        
        /* Settings Container */
        .settings-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Tab Navigation */
        .settings-tabs {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .settings-tabs .nav-tabs {
            border-bottom: none;
            padding: 1rem 1rem 0 1rem;
            background: #f8fafc;
        }
        
        .settings-tabs .nav-link {
            border: none;
            color: #64748b;
            padding: 0.75rem 1.5rem;
            border-radius: 8px 8px 0 0;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .settings-tabs .nav-link:hover {
            color: var(--dark);
            background: rgba(139, 92, 246, 0.1);
        }
        
        .settings-tabs .nav-link.active {
            background: white;
            color: var(--superadmin-color);
            border-bottom: 3px solid var(--superadmin-color);
        }
        
        .tab-content {
            padding: 2rem;
        }
        
        /* Cards */
        .settings-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .settings-card h5 {
            color: var(--dark);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
            font-weight: 600;
        }
        
        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--superadmin-color);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        
        .input-group-text {
            background-color: #f8fafc;
            border: 1px solid #d1d5db;
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--superadmin-color);
            border-color: var(--superadmin-color);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
        
        .btn-outline-primary {
            color: var(--superadmin-color);
            border-color: var(--superadmin-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--superadmin-color);
            border-color: var(--superadmin-color);
        }
        
        .btn-success {
            background-color: var(--success);
            border-color: var(--success);
        }
        
        .btn-warning {
            background-color: var(--warning);
            border-color: var(--warning);
        }
        
        /* Password Strength Meter */
        .password-strength-meter {
            height: 5px;
            background-color: #e2e8f0;
            border-radius: 3px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .password-strength-meter-bar {
            height: 100%;
            width: 0;
            border-radius: 3px;
            transition: width 0.3s, background-color 0.3s;
        }
        
        /* Database Backup Section */
        .backup-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .backup-card {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .backup-card:hover {
            border-color: var(--superadmin-color);
            background: rgba(139, 92, 246, 0.05);
            transform: translateY(-3px);
        }
        
        .backup-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--superadmin-color);
        }
        
        .backup-card h6 {
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .backup-card p {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 1rem;
        }
        
        /* System Info */
        .system-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .info-item {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid var(--superadmin-color);
        }
        
        .info-item h6 {
            font-size: 0.85rem;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 0.5rem;
        }
        
        .info-item p {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0;
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
        
        /* Modal */
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 10px 10px 0 0;
            padding: 1.5rem;
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
            
            .tab-content {
                padding: 1rem;
            }
            
            .settings-card {
                padding: 1.5rem;
            }
            
            .backup-options {
                grid-template-columns: 1fr;
            }
        }
        
        /* Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .pagination-info {
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .rows-per-page select {
            width: 80px;
            padding: 0.375rem;
            border-radius: 4px;
            border: 1px solid #d1d5db;
        }
        
        /* Tabs untuk riwayat */
        .history-tabs .nav-link {
            padding: 0.5rem 1rem;
        }
        
        .badge-export {
            background-color: var(--success);
        }
        
        .badge-import {
            background-color: var(--info);
        }
        
        .badge-restore {
            background-color: var(--warning);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h3>SILOG</h3>
            <p>Pengaturan Sistem Aplikasi</p>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('superadmin.dashboard') }}" class="nav-link">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <!-- Menu Validasi Pengadaan -->
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
                <a href="{{ route('superadmin.activity-logs') }}" class="nav-link">
                    <i class="bi bi-clock-history"></i>
                    <span>Log Aktivitas</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('superadmin.settings') }}" class="nav-link active">
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
        <h4 class="mb-0">Pengaturan Sistem</h4>
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
    
    <div class="settings-container">
        <!-- Tab Navigation -->
        <div class="settings-tabs">
            <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'profile' ? 'active' : '' }}" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                        <i class="bi bi-person-circle me-2"></i> Profil Superadmin
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'database' ? 'active' : '' }}" id="database-tab" data-bs-toggle="tab" data-bs-target="#database" type="button" role="tab">
                        <i class="bi bi-database me-2"></i> Backup Database
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'system' ? 'active' : '' }}" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                        <i class="bi bi-cpu me-2"></i> Informasi Sistem
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="settingsTabContent">
                <!-- Tab 1: Profil Superadmin -->
                <div class="tab-pane fade {{ $activeTab == 'profile' ? 'show active' : '' }}" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="settings-card">
                        <h5><i class="bi bi-person-circle me-2"></i>Informasi Profil</h5>
                        
                        <form id="profileForm" action="{{ route('superadmin.update-profile') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ Auth::user()->name }}" required>
                                    @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ Auth::user()->email }}" required>
                                    @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="{{ Auth::user()->username }}" required>
                                    @error('username')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="{{ Auth::user()->phone ?? '' }}">
                                    @error('phone')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="settings-card">
                        <h5><i class="bi bi-shield-lock me-2"></i>Ubah Password</h5>
                        
                        <form id="passwordForm" action="{{ route('superadmin.change-password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength-meter mt-2">
                                        <div class="password-strength-meter-bar" id="passwordStrengthBar"></div>
                                    </div>
                                    <small class="text-muted">Minimal 8 karakter, mengandung huruf besar, kecil, angka, dan simbol</small>
                                    @error('new_password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2" id="passwordMatchIndicator"></div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success" id="changePasswordBtn">
                                    <i class="bi bi-key me-2"></i>Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Tab 2: Backup Database -->
                <div class="tab-pane fade {{ $activeTab == 'database' ? 'show active' : '' }}" id="database" role="tabpanel" aria-labelledby="database-tab">
                    <div class="settings-card">
                        <h5><i class="bi bi-database me-2"></i>Backup Database</h5>
                        <p class="text-muted mb-4">Lakukan backup database secara berkala untuk menjaga keamanan data. Backup hanya dalam format JSON.</p>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Informasi:</strong> Backup terakhir dilakukan pada 
                            <strong>{{ count($backupHistory) > 0 ? $backupHistory[0]['date'] : 'Belum pernah dilakukan' }}</strong>
                        </div>
                        
                        <div class="backup-options">
                            <div class="backup-card" onclick="exportDatabase('json')">
                                <div class="backup-icon">
                                    <i class="bi bi-filetype-json"></i>
                                </div>
                                <h6>JSON Format</h6>
                                <p>Ekspor data dalam format JSON untuk backup dan restore</p>
                                <span class="badge bg-primary">Format Backup</span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button class="btn btn-outline-primary" onclick="showRestoreModal()">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Restore Database
                            </button>
                        </div>
                    </div>
                    
                    <!-- Tab untuk Riwayat Backup & Restore -->
                    <div class="settings-card">
                        <h5><i class="bi bi-clock-history me-2"></i>Riwayat Backup & Restore</h5>
                        
                        <!-- Tab Navigation untuk Riwayat -->
                        <ul class="nav nav-tabs history-tabs mb-4" id="historyTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="backup-tab" data-bs-toggle="tab" data-bs-target="#backupHistory" type="button" role="tab">
                                    <i class="bi bi-upload me-1"></i> Backup
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="restore-tab" data-bs-toggle="tab" data-bs-target="#restoreHistory" type="button" role="tab">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="historyTabContent">
                            <!-- Tab 1: Riwayat Backup -->
                            <div class="tab-pane fade show active" id="backupHistory" role="tabpanel" aria-labelledby="backup-tab">
                                <div class="table-controls">
                                    <div class="rows-per-page">
                                        <label for="backupRowsPerPage" class="form-label me-2">Baris per halaman:</label>
                                        <select id="backupRowsPerPage" class="form-select form-select-sm" onchange="changeBackupRowsPerPage(this.value)">
                                            <option value="10">10</option>
                                            <option value="25" selected>25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                    <div class="table-search">
                                        <input type="text" id="backupSearch" class="form-control form-control-sm" placeholder="Cari backup..." onkeyup="searchBackupTable()" style="width: 200px;">
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover" id="backupTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama File</th>
                                                <th>Format</th>
                                                <th>Ukuran</th>
                                                <th>Tanggal Backup</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="backupTableBody">
                                            @php
                                                $backupPage = request()->get('backup_page', 1);
                                                $backupPerPage = request()->get('backup_per_page', 25);
                                                $backupStart = ($backupPage - 1) * $backupPerPage;
                                                $backupEnd = $backupStart + $backupPerPage;
                                                $backupPaginated = array_slice($backupHistory, $backupStart, $backupPerPage);
                                                $backupTotalPages = ceil(count($backupHistory) / $backupPerPage);
                                            @endphp
                                            
                                            @forelse($backupPaginated as $index => $backup)
                                            <tr>
                                                <td>{{ $backupStart + $index + 1 }}</td>
                                                <td>
                                                    <i class="bi bi-file-earmark me-2"></i>
                                                    {{ $backup['filename'] }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        {{ strtoupper($backup['format']) }}
                                                    </span>
                                                </td>
                                                <td>{{ $backup['size'] }}</td>
                                                <td>{{ $backup['date'] }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button class="btn btn-outline-primary" onclick="downloadBackup('{{ $backup['filename'] }}')">
                                                            <i class="bi bi-download"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger" onclick="deleteBackup('{{ $backup['filename'] }}')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <i class="bi bi-database" style="font-size: 2rem; color: #cbd5e1;"></i>
                                                    <p class="mt-2 text-muted">Belum ada riwayat backup</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination untuk Backup -->
                                @if(count($backupHistory) > 0)
                                <div class="pagination-container">
                                    <div class="pagination-info">
                                        Menampilkan {{ $backupStart + 1 }} - {{ min($backupStart + count($backupPaginated), count($backupHistory)) }} dari {{ count($backupHistory) }} backup
                                    </div>
                                    <nav aria-label="Backup pagination">
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item {{ $backupPage == 1 ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['backup_page' => $backupPage - 1]) }}&active_tab=database">Sebelumnya</a>
                                            </li>
                                            
                                            @for($i = 1; $i <= min(5, $backupTotalPages); $i++)
                                            <li class="page-item {{ $backupPage == $i ? 'active' : '' }}">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['backup_page' => $i]) }}&active_tab=database">{{ $i }}</a>
                                            </li>
                                            @endfor
                                            
                                            @if($backupTotalPages > 5)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['backup_page' => $backupTotalPages]) }}&active_tab=database">{{ $backupTotalPages }}</a>
                                            </li>
                                            @endif
                                            
                                            <li class="page-item {{ $backupPage >= $backupTotalPages ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['backup_page' => $backupPage + 1]) }}&active_tab=database">Berikutnya</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Tab 2: Riwayat Restore -->
                            <div class="tab-pane fade" id="restoreHistory" role="tabpanel" aria-labelledby="restore-tab">
                                <div class="table-controls">
                                    <div class="rows-per-page">
                                        <label for="restoreRowsPerPage" class="form-label me-2">Baris per halaman:</label>
                                        <select id="restoreRowsPerPage" class="form-select form-select-sm" onchange="changeRestoreRowsPerPage(this.value)">
                                            <option value="10">10</option>
                                            <option value="25" selected>25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                    <div class="table-search">
                                        <input type="text" id="restoreSearch" class="form-control form-control-sm" placeholder="Cari restore..." onkeyup="searchRestoreTable()" style="width: 200px;">
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover" id="restoreTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama File</th>
                                                <th>Format</th>
                                                <th>Ukuran</th>
                                                <th>Metode</th>
                                                <th>Tanggal Restore</th>
                                                <th>Status</th>
                                                <th>Detail</th>
                                                <th>Oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody id="restoreTableBody">
                                            @php
                                                $restoreHistory = $restoreHistory ?? [];
                                                $restorePage = request()->get('restore_page', 1);
                                                $restorePerPage = request()->get('restore_per_page', 25);
                                                $restoreStart = ($restorePage - 1) * $restorePerPage;
                                                $restorePaginated = array_slice($restoreHistory, $restoreStart, $restorePerPage);
                                                $restoreTotalPages = ceil(count($restoreHistory) / $restorePerPage);
                                            @endphp
                                            
                                            @forelse($restorePaginated as $index => $restore)
                                            <tr>
                                                <td>{{ $restoreStart + $index + 1 }}</td>
                                                <td>
                                                    <i class="bi bi-file-earmark me-2"></i>
                                                    {{ $restore['filename'] }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">JSON</span>
                                                </td>
                                                <td>{{ $restore['size'] }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $restore['method'] == 'replace' ? 'warning' : 'info' }}">
                                                        {{ $restore['method_text'] }}
                                                    </span>
                                                </td>
                                                <td>{{ $restore['date'] }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $restore['status_badge'] }}">
                                                        {{ $restore['status_text'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($restore['status'] == 'success')
                                                        @if($restore['total_rows'])
                                                            <small>
                                                                <i class="bi bi-check-circle text-success me-1"></i>
                                                                {{ $restore['inserted_rows'] ?? 0 }} data
                                                                @if($restore['skipped_rows'] ?? 0 > 0)
                                                                    <span class="text-muted">({{ $restore['skipped_rows'] }} skipped)</span>
                                                                @endif
                                                            </small>
                                                        @else
                                                            <small class="text-success">Berhasil</small>
                                                        @endif
                                                    @else
                                                        <small class="text-danger" title="{{ $restore['message'] }}">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            {{ Str::limit($restore['message'], 30) }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $restore['user_name'] }}</small>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <i class="bi bi-arrow-counterclockwise" style="font-size: 2rem; color: #cbd5e1;"></i>
                                                    <p class="mt-2 text-muted">Belum ada riwayat restore</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination untuk Restore -->
                                @if(count($restoreHistory) > 0)
                                <div class="pagination-container">
                                    <div class="pagination-info">
                                        Menampilkan {{ $restoreStart + 1 }} - {{ min($restoreStart + count($restorePaginated), count($restoreHistory)) }} dari {{ count($restoreHistory) }} restore
                                    </div>
                                    <nav aria-label="Restore pagination">
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item {{ $restorePage == 1 ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['restore_page' => $restorePage - 1]) }}&active_tab=database">Sebelumnya</a>
                                            </li>
                                            
                                            @for($i = 1; $i <= min(5, $restoreTotalPages); $i++)
                                            <li class="page-item {{ $restorePage == $i ? 'active' : '' }}">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['restore_page' => $i]) }}&active_tab=database">{{ $i }}</a>
                                            </li>
                                            @endfor
                                            
                                            @if($restoreTotalPages > 5)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['restore_page' => $restoreTotalPages]) }}&active_tab=database">{{ $restoreTotalPages }}</a>
                                            </li>
                                            @endif
                                            
                                            <li class="page-item {{ $restorePage >= $restoreTotalPages ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['restore_page' => $restorePage + 1]) }}&active_tab=database">Berikutnya</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab 3: Informasi Sistem -->
                <div class="tab-pane fade {{ $activeTab == 'system' ? 'show active' : '' }}" id="system" role="tabpanel" aria-labelledby="system-tab">
                    <div class="settings-card">
                        <h5><i class="bi bi-cpu me-2"></i>Informasi Sistem</h5>
                        
                        <div class="system-info-grid">
                            <div class="info-item">
                                <h6>Versi Aplikasi</h6>
                                <p>SILOG v1.0.0</p>
                            </div>
                            
                            <div class="info-item">
                                <h6>Laravel Version</h6>
                                <p>{{ $systemInfo['laravel_version'] ?? '8.0+' }}</p>
                            </div>
                            
                            <div class="info-item">
                                <h6>PHP Version</h6>
                                <p>{{ $systemInfo['php_version'] ?? '7.4+' }}</p>
                            </div>
                            
                            <div class="info-item">
                                <h6>Database</h6>
                                <p>{{ $systemInfo['database'] ?? 'MySQL' }}</p>
                            </div>
                            
                            <div class="info-item">
                                <h6>Server Time</h6>
                                <p>{{ date('d/m/Y H:i:s') }}</p>
                            </div>
                            
                            <div class="info-item">
                                <h6>Timezone</h6>
                                <p>{{ config('app.timezone') ?? 'Asia/Jakarta' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Server Information -->
                    <div class="settings-card">
                        <h5><i class="bi bi-server me-2"></i>Informasi Server</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td width="30%"><strong>Sistem Operasi:</strong></td>
                                        <td>{{ $serverInfo['os'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Web Server:</strong></td>
                                        <td>{{ $serverInfo['web_server'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Penggunaan Memori:</strong></td>
                                        <td>
                                            <div class="progress" style="height: 10px; width: 200px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $serverInfo['memory_usage'] ?? 0 }}%">
                                                </div>
                                            </div>
                                            <small>{{ $serverInfo['memory_usage'] ?? 0 }} MB</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- System Maintenance -->
                    <div class="settings-card">
                        <h5><i class="bi bi-tools me-2"></i>Pemeliharaan Sistem</h5>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Peringatan:</strong> Hanya lakukan tindakan ini jika benar-benar diperlukan.
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-outline-danger w-100" onclick="enableMaintenance()">
                                    <i class="bi bi-gear me-2"></i>Aktifkan Maintenance Mode
                                </button>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-outline-warning w-100" onclick="disableMaintenance()">
                                    <i class="bi bi-gear me-2"></i>Nonaktifkan Maintenance Mode
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->

<!-- Restore Database Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-arrow-counterclockwise me-2"></i>Restore Database</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>PERINGATAN:</strong> Proses restore akan mengganti data saat ini dengan data dari backup.
                </div>
                
                <form id="restoreForm" action="{{ route('superadmin.restore-database') }}?active_tab=database" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="restore_file" class="form-label">Pilih File Backup</label>
                        <input type="file" class="form-control" id="restore_file" name="restore_file" accept=".json" required>
                        <small class="text-muted">Hanya format JSON yang didukung (maks 100MB)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="restore_method" class="form-label">Metode Restore</label>
                        <select class="form-select" id="restore_method" name="restore_method" required>
                            <option value="">Pilih metode...</option>
                            <option value="append">Append - Tambahkan data tanpa menghapus yang ada</option>
                            <option value="replace" selected>Replace - Ganti semua data dengan backup</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirm_restore" required>
                            <label class="form-check-label" for="confirm_restore">
                                Saya memahami bahwa data saat ini akan diganti (untuk metode Replace)
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="restoreForm" class="btn btn-danger" id="restoreBtn">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Upload Backup
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto dismiss alerts HANYA untuk alert biasa (bukan backup/restore)
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            // Hanya hapus alert yang bukan dari backup/restore
            const isBackupAlert = alert.textContent.includes('database') || 
                                 alert.textContent.includes('backup') || 
                                 alert.textContent.includes('Backup') ||
                                 alert.textContent.includes('restore') ||
                                 alert.textContent.includes('Restore');
            
            if (!isBackupAlert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
    
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
    
    // Password strength checker
    const newPasswordInput = document.getElementById('new_password');
    const passwordStrengthBar = document.getElementById('passwordStrengthBar');
    const passwordConfirmationInput = document.getElementById('new_password_confirmation');
    const passwordMatchIndicator = document.getElementById('passwordMatchIndicator');
    
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength += 25;
            
            // Lowercase check
            if (/[a-z]/.test(password)) strength += 25;
            
            // Uppercase check
            if (/[A-Z]/.test(password)) strength += 25;
            
            // Number/Symbol check
            if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength += 25;
            
            // Update strength bar
            passwordStrengthBar.style.width = strength + '%';
            
            // Update color based on strength
            if (strength < 50) {
                passwordStrengthBar.style.backgroundColor = '#ef4444';
            } else if (strength < 75) {
                passwordStrengthBar.style.backgroundColor = '#f59e0b';
            } else {
                passwordStrengthBar.style.backgroundColor = '#10b981';
            }
        });
    }
    
    // Password confirmation check
    if (passwordConfirmationInput && newPasswordInput) {
        passwordConfirmationInput.addEventListener('input', function() {
            const password = newPasswordInput.value;
            const confirmation = this.value;
            
            if (confirmation.length === 0) {
                passwordMatchIndicator.innerHTML = '';
                return;
            }
            
            if (password === confirmation) {
                passwordMatchIndicator.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Password cocok</span>';
            } else {
                passwordMatchIndicator.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Password tidak cocok</span>';
            }
        });
    }
    
    // Form validation for profile
    document.getElementById('profileForm')?.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(email)) {
            e.preventDefault();
            showAlert('Email tidak valid', 'danger');
            return;
        }
        
        showAlert('Menyimpan perubahan profil...', 'info');
    });
    
    // Form validation for password change
    document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmation = document.getElementById('new_password_confirmation').value;
        
        if (newPassword !== confirmation) {
            e.preventDefault();
            showAlert('Password konfirmasi tidak cocok', 'danger');
            return;
        }
        
        if (newPassword.length < 8) {
            e.preventDefault();
            showAlert('Password minimal 8 karakter', 'danger');
            return;
        }
        
        const hasUpperCase = /[A-Z]/.test(newPassword);
        const hasLowerCase = /[a-z]/.test(newPassword);
        const hasNumbers = /\d/.test(newPassword);
        
        if (!hasUpperCase || !hasLowerCase || !hasNumbers) {
            e.preventDefault();
            showAlert('Password harus mengandung huruf besar, huruf kecil, dan angka', 'danger');
            return;
        }
        
        showAlert('Mengubah password...', 'info');
    });
    
    // Backup functions
    function exportDatabase(format) {
        if (confirm(`Ekspor database dalam format ${format.toUpperCase()}? Proses ini mungkin memakan waktu beberapa saat.`)) {
            showBackupAlert(`Memulai ekspor database dalam format ${format.toUpperCase()}...`, 'info');
            
            setTimeout(() => {
                window.location.href = `/superadmin/settings/export-database?format=${format}&active_tab=database`;
            }, 1500);
        }
    }

    function downloadBackup(filename) {
        showBackupAlert(`Mengunduh file backup: ${filename}...`, 'info');
        
        setTimeout(() => {
            window.location.href = `/superadmin/settings/download-backup/${filename}?active_tab=database`;
        }, 1500);
    }

    function deleteBackup(filename) {
        if (confirm(`Hapus backup ${filename}?`)) {
            showBackupAlert(`Menghapus backup: ${filename}...`, 'info');
            
            fetch(`/superadmin/settings/delete-backup/${filename}?active_tab=database`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showBackupAlert('Backup berhasil dihapus', 'success', 10000);
                    setTimeout(() => {
                        window.location.href = '/superadmin/settings?active_tab=database';
                    }, 1500);
                } else {
                    showBackupAlert('Gagal menghapus backup', 'danger', 10000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showBackupAlert('Terjadi kesalahan saat menghapus backup', 'danger', 10000);
            });
        }
    }
    
    // Form submit untuk restore database
    document.getElementById('restoreForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!document.getElementById('confirm_restore').checked) {
            showAlert('Harap centang konfirmasi restore', 'warning');
            return;
        }
        
        const restoreMethod = document.getElementById('restore_method').value;
        if (!restoreMethod) {
            showAlert('Harap pilih metode restore', 'warning');
            return;
        }
        
        const fileInput = document.getElementById('restore_file');
        if (!fileInput.files.length) {
            showAlert('Harap pilih file backup', 'warning');
            return;
        }
        
        const file = fileInput.files[0];
        const fileName = file.name.toLowerCase();
        
        // Validasi format file HANYA JSON
        if (!fileName.endsWith('.json')) {
            showAlert('Format file harus .json', 'warning');
            return;
        }
        
        // Validasi ukuran file (maks 100MB)
        const maxSize = 100 * 1024 * 1024; // 100MB
        if (file.size > maxSize) {
            showAlert('Ukuran file terlalu besar. Maksimal 100MB.', 'warning');
            return;
        }
        
        // Tampilkan alert proses restore
        const restoreBtn = document.getElementById('restoreBtn');
        const originalText = restoreBtn.innerHTML;
        restoreBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        restoreBtn.disabled = true;
        
        showBackupAlert('Memulai proses restore database. Proses ini mungkin memakan waktu beberapa saat...', 'warning');
        
        // Submit form setelah alert tampil
        setTimeout(() => {
            this.submit();
        }, 2000);
    });
    
    function showRestoreModal() {
        const modal = new bootstrap.Modal(document.getElementById('restoreModal'));
        modal.show();
    }
    
    function enableMaintenance() {
        if (confirm('Aktifkan maintenance mode? Sistem akan tidak dapat diakses.')) {
            showBackupAlert('Mengaktifkan maintenance mode...', 'warning');
            
            fetch('/superadmin/settings/maintenance/enable?active_tab=system', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showBackupAlert('Maintenance mode diaktifkan. Sistem sekarang dalam mode maintenance.', 'success', 15000);
                    setTimeout(() => {
                        window.location.href = '/superadmin/settings?active_tab=system';
                    }, 1500);
                } else {
                    showBackupAlert('Gagal mengaktifkan maintenance mode: ' + data.message, 'danger', 10000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showBackupAlert('Terjadi kesalahan saat mengaktifkan maintenance mode', 'danger', 10000);
            });
        }
    }
    
    function disableMaintenance() {
        if (confirm('Nonaktifkan maintenance mode?')) {
            showBackupAlert('Menonaktifkan maintenance mode...', 'info');
            
            fetch('/superadmin/settings/maintenance/disable?active_tab=system', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showBackupAlert('Maintenance mode dinonaktifkan. Sistem sekarang dapat diakses kembali.', 'success', 15000);
                    setTimeout(() => {
                        window.location.href = '/superadmin/settings?active_tab=system';
                    }, 1500);
                } else {
                    showBackupAlert('Gagal menonaktifkan maintenance mode: ' + data.message, 'danger', 10000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showBackupAlert('Terjadi kesalahan saat menonaktifkan maintenance mode', 'danger', 10000);
            });
        }
    }
    
    // Show alert message untuk backup/restore
    function showBackupAlert(message, type = 'info', duration = 30000) {
        const alertContainer = document.querySelector('.alert-container') || createAlertContainer();
        
        // Hapus alert backup/restore sebelumnya jika ada
        const existingAlerts = alertContainer.querySelectorAll('.alert');
        existingAlerts.forEach(alert => {
            if (alert.textContent.includes('database') || 
                alert.textContent.includes('backup') || 
                alert.textContent.includes('Backup') ||
                alert.textContent.includes('restore') ||
                alert.textContent.includes('Restore') ||
                alert.textContent.includes('cache') ||
                alert.textContent.includes('Cache') ||
                alert.textContent.includes('maintenance') ||
                alert.textContent.includes('Maintenance')) {
                alert.remove();
            }
        });
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show backup-alert`;
        alert.role = 'alert';
        alert.innerHTML = `
            <i class="bi ${type === 'danger' ? 'bi-exclamation-triangle' : 
                          type === 'success' ? 'bi-check-circle' : 
                          type === 'warning' ? 'bi-exclamation-circle' : 'bi-info-circle'} me-2"></i>
            <strong>${type === 'info' ? 'INFO:' : 
                     type === 'success' ? 'SUKSES:' : 
                     type === 'warning' ? 'PERHATIAN:' : 'ERROR:'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Auto-dismiss setelah waktu yang lebih lama
        setTimeout(() => {
            if (alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, duration);
        
        alert.style.borderLeft = `4px solid ${type === 'success' ? 'var(--success)' : 
                                             type === 'warning' ? 'var(--warning)' : 
                                             type === 'danger' ? 'var(--secondary)' : 'var(--info)'}`;
        alert.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
    }
    
    // Show alert message biasa
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
        
        setTimeout(() => {
            if (alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
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
    
    // Table search and pagination functions
    function changeBackupRowsPerPage(rows) {
        const url = new URL(window.location.href);
        url.searchParams.set('backup_per_page', rows);
        url.searchParams.set('backup_page', 1);
        window.location.href = url.toString();
    }
    
    function changeRestoreRowsPerPage(rows) {
        const url = new URL(window.location.href);
        url.searchParams.set('restore_per_page', rows);
        url.searchParams.set('restore_page', 1);
        window.location.href = url.toString();
    }
    
    function searchBackupTable() {
        const input = document.getElementById('backupSearch');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('backupTable');
        const tr = table.getElementsByTagName('tr');
        
        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            
            if (found) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
    
    function searchRestoreTable() {
        const input = document.getElementById('restoreSearch');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('restoreTable');
        const tr = table.getElementsByTagName('tr');
        
        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            
            if (found) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
    
    // Initialize on DOM loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Handle file upload progress
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files[0]?.name;
                if (fileName) {
                    const parent = this.parentElement;
                    const small = parent.querySelector('small') || document.createElement('small');
                    small.className = 'text-success';
                    small.innerHTML = `<i class="bi bi-check-circle me-1"></i>${fileName}`;
                    parent.appendChild(small);
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
        
        // Set selected rows per page
        const backupRowsPerPage = {{ request()->get('backup_per_page', 25) }};
        const restoreRowsPerPage = {{ request()->get('restore_per_page', 25) }};
        
        document.getElementById('backupRowsPerPage').value = backupRowsPerPage;
        document.getElementById('restoreRowsPerPage').value = restoreRowsPerPage;
        
        // Cek apakah ada session message dari backup/restore
        @if(session('success'))
            const successMsg = `{{ session('success') }}`;
            if (successMsg.includes('backup') || successMsg.includes('Backup') || 
                successMsg.includes('restore') || successMsg.includes('Restore') ||
                successMsg.includes('ekspor') || successMsg.includes('Ekspor') ||
                successMsg.includes('import') || successMsg.includes('Import')) {
                showBackupAlert(successMsg, 'success', 15000);
            }
        @endif
        
        @if(session('error'))
            const errorMsg = `{{ session('error') }}`;
            if (errorMsg.includes('backup') || errorMsg.includes('Backup') || 
                errorMsg.includes('restore') || errorMsg.includes('Restore') ||
                errorMsg.includes('ekspor') || errorMsg.includes('Ekspor') ||
                errorMsg.includes('import') || errorMsg.includes('Import')) {
                showBackupAlert(errorMsg, 'danger', 15000);
            }
        @endif
        
        // Simpan tab aktif ke localStorage saat tab diklik
        document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.id;
                const tabName = tabId.replace('-tab', '');
                localStorage.setItem('active_tab', tabName);
                
                const url = new URL(window.location.href);
                url.searchParams.set('active_tab', tabName);
                history.replaceState(null, '', url.toString());
            });
        });
        
        // Coba set tab dari localStorage atau parameter URL
        const urlParams = new URLSearchParams(window.location.search);
        const urlTab = urlParams.get('active_tab');
        const savedTab = localStorage.getItem('active_tab') || urlTab;
        
        if (savedTab && savedTab !== '{{ $activeTab }}') {
            const tabElement = document.getElementById(savedTab + '-tab');
            if (tabElement) {
                const tab = new bootstrap.Tab(tabElement);
                tab.show();
            }
        }
    });
</script>
</body>
</html>