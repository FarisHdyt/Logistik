<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if(isset($is_pdf_export) && $is_pdf_export)
            Laporan {{ ucfirst($reportType) }} - {{ $export_date ?? now()->format('d/m/Y H:i:s') }}
        @elseif(isset($is_excel_export) && $is_excel_export)
            Laporan {{ ucfirst($reportType) }}
        @else
            Laporan - Superadmin SILOG Polres
        @endif
    </title>
    
    {{-- Hanya load CSS/JS jika bukan ekspor --}}
    @if(!isset($is_pdf_export) && !isset($is_excel_export))
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @else
    {{-- Minimal CSS untuk PDF/Excel --}}
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; margin: 0; padding: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th { background-color: #f8f9fa; font-weight: bold; padding: 8px; border: 1px solid #dee2e6; text-align: left; }
        .table td { padding: 8px; border: 1px solid #dee2e6; }
        .text-center { text-align: center; }
        .badge { padding: 0.25em 0.4em; font-size: 75%; border-radius: 0.25rem; display: inline-block; }
        .bg-success { background-color: #28a745; color: white; }
        .bg-danger { background-color: #dc3545; color: white; }
        .bg-warning { background-color: #ffc107; color: #212529; }
        .bg-info { background-color: #17a2b8; color: white; }
        .bg-primary { background-color: #007bff; color: white; }
        .bg-secondary { background-color: #6c757d; color: white; }
        .bg-purple { background-color: #8b5cf6; color: white; }
        .header-report { border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .footer-report { border-top: 1px solid #ddd; padding-top: 10px; margin-top: 30px; font-size: 9pt; color: #666; }
        .stats-grid { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .stat-card { flex: 1; margin: 0 10px; padding: 15px; border: 1px solid #dee2e6; border-radius: 5px; text-align: center; }
        .stat-card h3 { margin: 0; font-size: 24px; font-weight: bold; }
        .stat-card p { margin: 5px 0 0 0; color: #666; }
    </style>
    @endif
    
    {{-- CSS utama aplikasi (hanya untuk non-ekspor) --}}
    @if(!isset($is_pdf_export) && !isset($is_excel_export))
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
        
        /* Report Header */
        .report-header {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        
        .report-title h1 {
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .report-title p {
            color: #64748b;
            margin-bottom: 0;
        }
        
        /* Report Types */
        .report-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .report-type-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border: 2px solid transparent;
            cursor: pointer;
        }
        
        .report-type-card:hover {
            transform: translateY(-5px);
            border-color: var(--superadmin-color);
        }
        
        .report-type-card.active {
            border-color: var(--superadmin-color);
            background-color: #f8f7ff;
        }
        
        .report-type-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--superadmin-color);
            margin-bottom: 1rem;
        }
        
        .report-type-content h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .report-type-content p {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        /* Report Preview */
        .report-preview {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .preview-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-warning {
            background: var(--warning);
            color: white;
        }
        
        .btn-warning:hover {
            background: #e68900;
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background: #0da271;
        }
        
        /* Tables */
        .table-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
        }
        
        .table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
            transform: translateY(-2px);
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
        
        /* Charts */
        .chart-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        
        .chart-card h5 {
            margin-bottom: 1.5rem;
            color: var(--dark);
            font-weight: 600;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Distribution Grid */
        .distribution-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .distribution-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .distribution-card h5 {
            margin-bottom: 1.5rem;
            color: var(--dark);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .distribution-list {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 0.5rem;
        }
        
        .distribution-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .distribution-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .distribution-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .distribution-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem;
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s;
        }
        
        .distribution-item:hover {
            background-color: #f8fafc;
        }
        
        .distribution-item:last-child {
            border-bottom: none;
        }
        
        .distribution-name {
            font-weight: 500;
            color: var(--dark);
            max-width: 70%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .distribution-count {
            background: var(--superadmin-color);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            min-width: 60px;
            text-align: center;
        }
        
        /* Alert */
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
        
        /* Chart Legend Custom */
        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }
        
        .legend-color {
            width: 15px;
            height: 15px;
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
            
            .report-types {
                grid-template-columns: 1fr;
            }
            
            .preview-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .distribution-grid {
                grid-template-columns: 1fr;
            }
            
            .distribution-list {
                max-height: 250px;
            }
            
            .chart-container {
                height: 250px;
            }
        }
    </style>
    @endif
</head>
<body>
    {{-- Untuk PDF/Excel: tampilkan layout sederhana --}}
    @if(isset($is_pdf_export) || isset($is_excel_export))
    
        <div class="header-report">
            <h2 style="text-align: center; margin: 0;">SILOG - SISTEM LOGISTIK POLRES</h2>
            <h3 style="text-align: center; margin: 10px 0 5px 0;">LAPORAN {{ strtoupper($reportType) }}</h3>
            <p style="text-align: center; margin: 5px 0;">
                Dicetak pada: {{ $export_date ?? now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
        
        {{-- Ringkasan Statistik --}}
        @if($reportType == 'user' || $reportType == 'system')
        <div class="stats-grid mb-4">
            @if($reportType == 'user')
            <div class="stat-card">
                <h3>{{ $totalUsers ?? 0 }}</h3>
                <p>Total User</p>
            </div>
            <div class="stat-card">
                <h3>{{ $activeUsers ?? 0 }}</h3>
                <p>User Aktif</p>
            </div>
            <div class="stat-card">
                <h3>{{ $totalAdmins ?? 0 }}</h3>
                <p>Total Admin</p>
            </div>
            <div class="stat-card">
                <h3>{{ $newUsersThisMonth ?? 0 }}</h3>
                <p>User Baru (Bulan Ini)</p>
            </div>
            @elseif($reportType == 'system')
            <div class="stat-card">
                <h3>{{ $totalUsers ?? 0 }}</h3>
                <p>Total User</p>
            </div>
            <div class="stat-card">
                <h3>{{ $totalSatker ?? 0 }}</h3>
                <p>Total Satker</p>
            </div>
            <div class="stat-card">
                <h3>{{ $totalActivities ?? 0 }}</h3>
                <p>Total Aktivitas</p>
            </div>
            <div class="stat-card">
                <h3>{{ $newUsersFiltered ?? 0 }}</h3>
                <p>User Baru (Periode)</p>
            </div>
            @endif
        </div>
        @endif
        
        {{-- Tabel Data Utama --}}
        <div class="mb-3">
            <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px;">
                DATA {{ strtoupper($reportType == 'user' ? 'USER' : ($reportType == 'activity' ? 'AKTIVITAS' : ($reportType == 'satker' ? 'SATKER' : 'SISTEM'))) }}
            </h4>
            
            @if($reportType == 'user' && isset($users) && $users->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Satker</th>
                        <th>Status</th>
                        <th>Terakhir Login</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'superadmin')
                                <span class="badge bg-purple">Superadmin</span>
                            @elseif($user->role == 'admin')
                                <span class="badge bg-primary">Admin</span>
                            @else
                                <span class="badge bg-info">User</span>
                            @endif
                        </td>
                        <td>{{ $user->satker->nama_satker ?? '-' }}</td>
                        <td class="text-center">
                            @if($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Belum login' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @elseif($reportType == 'activity' && isset($activities) && $activities->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP Address</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $activity->user->name ?? 'System' }}</td>
                        <td>
                            <span class="badge 
                                @if($activity->action == 'login') bg-success
                                @elseif($activity->action == 'logout') bg-secondary
                                @elseif($activity->action == 'create') bg-primary
                                @elseif($activity->action == 'update') bg-warning
                                @elseif($activity->action == 'delete') bg-danger
                                @else bg-info @endif">
                                {{ ucfirst($activity->action) }}
                            </span>
                        </td>
                        <td>{{ $activity->description ?? '-' }}</td>
                        <td>{{ $activity->ip_address ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y H:i:s') ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @elseif($reportType == 'satker' && isset($satkers) && $satkers->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Satker</th>
                        <th>Kode Satker</th>
                        <th class="text-center">Jumlah User</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($satkers as $satker)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $satker->nama_satker }}</td>
                        <td>{{ $satker->kode_satker ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $satker->users_count ?? 0 }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($satker->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @elseif($reportType == 'system')
            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <table class="table">
                        <tr><th colspan="2" class="text-center">STATISTIK SISTEM</th></tr>
                        <tr><td>Total User</td><td class="text-right">{{ $totalUsers ?? 0 }}</td></tr>
                        <tr><td>User Aktif</td><td class="text-right">{{ $activeUsers ?? 0 }}</td></tr>
                        <tr><td>Total Satker</td><td class="text-right">{{ $totalSatker ?? 0 }}</td></tr>
                        <tr><td>Total Aktivitas</td><td class="text-right">{{ $totalActivities ?? 0 }}</td></tr>
                    </table>
                </div>
                <div style="flex: 1;">
                    <table class="table">
                        <tr><th colspan="2" class="text-center">INFORMASI SISTEM</th></tr>
                        <tr><td>System Uptime</td><td class="text-right">{{ $systemUptime ?? '99.9%' }}</td></tr>
                        <tr><td>Backup Terakhir</td><td class="text-right">{{ $lastBackup ?? '-' }}</td></tr>
                        <tr><td>User Baru Hari Ini</td><td class="text-right">{{ $newUsersFiltered ?? 0 }}</td></tr>
                        <tr><td>Versi Aplikasi</td><td class="text-right">v1.0.0</td></tr>
                    </table>
                </div>
            </div>
            @else
            <div style="text-align: center; padding: 40px;">
                <p style="color: #666; font-style: italic;">Tidak ada data yang ditemukan</p>
            </div>
            @endif
        </div>
        
        <div class="footer-report">
            <table width="100%">
                <tr>
                    <td width="60%">
                        <small>
                            Dicetak oleh: {{ $user->name ?? 'System' }}<br>
                            Role: {{ $user->role ?? '-' }}
                        </small>
                    </td>
                    <td width="40%" class="text-right">
                        <small>
                            {{ config('app.name', 'SILOG Polres') }}<br>
                            Halaman 1 dari 1
                        </small>
                    </td>
                </tr>
            </table>
        </div>
    
    {{-- Untuk tampilan normal aplikasi --}}
    @else
    
    <!-- Sidebar (hanya untuk tampilan web) -->
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
                <a href="{{ route('superadmin.reports') }}" class="nav-link active">
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
            <h4 class="mb-0">Laporan Sistem</h4>
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
        
        <!-- Report Header -->
        <div class="report-header">
            <div class="report-title">
                <h1>{{ $title ?? 'Laporan Sistem' }}</h1>
                <p>{{ $subtitle ?? 'Generate dan kelola berbagai jenis laporan sistem SILOG Polres' }}</p>
            </div>
        </div>
        
        <!-- Report Types -->
        <div class="report-types">
            <div class="report-type-card {{ $reportType == 'user' ? 'active' : '' }}" data-report-type="user">
                <div class="report-type-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="report-type-content">
                    <h5>Laporan User</h5>
                    <p>Laporan detail mengenai pengguna sistem, termasuk data admin, user aktif, dan statistik registrasi.</p>
                </div>
            </div>
            
            <div class="report-type-card {{ $reportType == 'activity' ? 'active' : '' }}" data-report-type="activity">
                <div class="report-type-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="report-type-content">
                    <h5>Laporan Aktivitas</h5>
                    <p>Log aktivitas sistem termasuk login, logout, dan perubahan data oleh semua pengguna.</p>
                </div>
            </div>
            
            <div class="report-type-card {{ $reportType == 'satker' ? 'active' : '' }}" data-report-type="satker">
                <div class="report-type-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="report-type-content">
                    <h5>Laporan Satker</h5>
                    <p>Data lengkap satuan kerja, distribusi user per satker, dan statistik penggunaan.</p>
                </div>
            </div>
            
            <div class="report-type-card {{ $reportType == 'system' ? 'active' : '' }}" data-report-type="system">
                <div class="report-type-icon">
                    <i class="bi bi-speedometer2"></i>
                </div>
                <div class="report-type-content">
                    <h5>Laporan Sistem</h5>
                    <p>Statistik penggunaan sistem, performa, dan informasi teknis lainnya.</p>
                </div>
            </div>
        </div>
        
        <!-- Report Preview Section -->
        <div class="report-preview">
            <div class="preview-header">
                <h4>{{ $title ?? 'Pratinjau Laporan' }}</h4>
                <div class="preview-actions">
                    <button class="btn btn-warning" id="generateReport">
                        <i class="bi bi-file-earmark-pdf"></i> Generate PDF
                    </button>
                    <button class="btn btn-success" id="exportExcel">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </button>
                </div>
            </div>
            
            <!-- Stats Cards - Dinamis berdasarkan jenis laporan -->
            <div class="stats-grid">
                @if($reportType == 'user')
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $totalUsers ?? 0 }}</h3>
                            <p>Total User</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $activeUsers ?? 0 }}</h3>
                            <p>User Aktif</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $totalAdmins ?? 0 }}</h3>
                            <p>Total Admin</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $newUsersThisMonth ?? 0 }}</h3>
                            <p>User Baru (Bulan Ini)</p>
                        </div>
                    </div>
                @elseif($reportType == 'activity')
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $totalActivities ?? 0 }}</h3>
                            <p>Total Aktivitas</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $loginCount ?? 0 }}</h3>
                            <p>Login</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $createCount ?? 0 }}</h3>
                            <p>Create</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $updateCount ?? 0 }}</h3>
                            <p>Update</p>
                        </div>
                    </div>
                @elseif($reportType == 'satker')
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $totalSatker ?? 0 }}</h3>
                            <p>Total Satker</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $totalUsers ?? 0 }}</h3>
                            <p>Total User</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $averageUsersPerSatker ?? 0 }}</h3>
                            <p>Rata-rata User/Satker</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $topSatkers->first()->users_count ?? 0 }}</h3>
                            <p>Satker Terbanyak User</p>
                        </div>
                    </div>
                @elseif($reportType == 'system')
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $totalUsers ?? 0 }}</h3>
                            <p>Total User</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $activeUsers ?? 0 }}</h3>
                            <p>User Aktif</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $totalActivities ?? 0 }}</h3>
                            <p>Total Aktivitas</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <h3>{{ $newUsersFiltered ?? 0 }}</h3>
                            <p>User Baru (Periode)</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Distribution Cards - Untuk User dan Satker Report -->
            @if($reportType == 'user' && isset($superadminCount) && isset($adminCount) && isset($userCount))
            <div class="distribution-grid">
                <div class="distribution-card">
                    <h5><i class="bi bi-pie-chart"></i> Distribusi User per Role</h5>
                    <div class="distribution-list">
                        <div class="distribution-item">
                            <span class="distribution-name">Superadmin</span>
                            <span class="distribution-count">{{ $superadminCount }}</span>
                        </div>
                        <div class="distribution-item">
                            <span class="distribution-name">Admin</span>
                            <span class="distribution-count">{{ $adminCount }}</span>
                        </div>
                        <div class="distribution-item">
                            <span class="distribution-name">User</span>
                            <span class="distribution-count">{{ $userCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if($reportType == 'satker' && isset($topSatkers))
            <div class="distribution-grid">
                <div class="distribution-card">
                    <h5><i class="bi bi-trophy"></i> Top 5 Satker dengan User Terbanyak</h5>
                    <div class="distribution-list">
                        @forelse($topSatkers as $index => $satker)
                        <div class="distribution-item">
                            <span class="distribution-name">{{ $index + 1 }}. {{ $satker->nama_satker }}</span>
                            <span class="distribution-count">{{ $satker->users_count ?? 0 }} user</span>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-building" style="font-size: 2rem;"></i>
                            <p class="mt-2">Tidak ada data satker</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Chart - Dinamis berdasarkan jenis laporan -->
            @if(isset($chartData))
            <div class="chart-card">
                <h5>{{ $chartData['title'] ?? 'Statistik Laporan' }}</h5>
                <div class="chart-container">
                    <canvas id="reportChart"></canvas>
                </div>
            </div>
            @endif
            
            <!-- Tabel Data - Dinamis berdasarkan jenis laporan -->
            @if($reportType == 'user' && isset($users))
            <div class="table-card">
                <h5>Data User <span class="badge bg-primary ms-2">{{ $users->count() }} data</span></h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Satker</th>
                                <th>Status</th>
                                <th>Terakhir Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'superadmin')
                                        <span class="badge bg-purple">Superadmin</span>
                                    @elseif($user->role == 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @else
                                        <span class="badge bg-info">User</span>
                                    @endif
                                </td>
                                <td>{{ $user->satker->nama_satker ?? '-' }}</td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Belum login' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-people" style="font-size: 2rem; color: #cbd5e1;"></i>
                                    <p class="mt-2 text-muted">Tidak ada data user</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @elseif($reportType == 'activity' && isset($activities))
            <div class="table-card">
                <h5>Data Aktivitas <span class="badge bg-primary ms-2">{{ $activities->count() }} data</span></h5>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $activity)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            {{ substr($activity->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <div>{{ $activity->user->name ?? 'System' }}</div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $action = $activity->action ?? 'unknown';
                                        $actionColors = [
                                            'login' => 'success',
                                            'logout' => 'secondary',
                                            'create' => 'primary',
                                            'update' => 'warning',
                                            'delete' => 'danger',
                                            'view' => 'info'
                                        ];
                                        $color = $actionColors[$action] ?? 'dark';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($action) }}</span>
                                </td>
                                <td>{{ $activity->description ?? '-' }}</td>
                                <td><code>{{ $activity->ip_address ?? '-' }}</code></td>
                                <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y H:i:s') ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bi bi-clock-history" style="font-size: 2rem; color: #10b981;"></i>
                                    <p class="mt-2 text-muted">Tidak ada aktivitas terbaru</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @elseif($reportType == 'satker' && isset($satkers))
            <div class="table-card">
                <h5>Data Satker <span class="badge bg-primary ms-2">{{ $satkers->count() }} data</span></h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Satker</th>
                                <th>Kode Satker</th>
                                <th>Jumlah User</th>
                                <th>Tanggal Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($satkers as $satker)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 30px; height: 30px; font-size: 0.8rem; background: #10b981;">
                                            <i class="bi bi-building" style="color: white;"></i>
                                        </div>
                                        <div>{{ $satker->nama_satker }}</div>
                                    </div>
                                </td>
                                <td>{{ $satker->kode_satker ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $satker->users_count ?? 0 }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($satker->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-building" style="font-size: 2rem; color: #cbd5e1;"></i>
                                    <p class="mt-2 text-muted">Tidak ada data satker</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @elseif($reportType == 'system')
            <div class="table-card">
                <h5>Informasi Sistem</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6><i class="bi bi-info-circle me-2"></i>Statistik Sistem</h6>
                                <hr>
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Total User</small>
                                        <p class="mb-0"><strong>{{ $totalUsers ?? 0 }}</strong></p>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">User Aktif</small>
                                        <p class="mb-0"><strong>{{ $activeUsers ?? 0 }}</strong></p>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Total Satker</small>
                                        <p class="mb-0"><strong>{{ $totalSatker ?? 0 }}</strong></p>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Aktivitas Hari Ini</small>
                                        <p class="mb-0"><strong>{{ $todayActivities ?? 0 }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><i class="bi bi-gear me-2"></i>Status Sistem</h6>
                                <hr>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-check-circle text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small>System Uptime</small>
                                        <p class="mb-0"><strong>{{ $systemUptime ?? '99.9%' }}</strong></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-database text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small>Backup Terakhir</small>
                                        <p class="mb-0"><strong>{{ $lastBackup ?? '-' }}</strong></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-person-plus text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small>User Baru Hari Ini</small>
                                        <p class="mb-0"><strong>{{ $newUsersFiltered ?? 0 }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    @endif {{-- End of non-export view --}}
    
    {{-- JavaScript hanya untuk tampilan web --}}
    @if(!isset($is_pdf_export) && !isset($is_excel_export))
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Report type selection
        document.querySelectorAll('.report-type-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove active class from all cards
                document.querySelectorAll('.report-type-card').forEach(c => {
                    c.classList.remove('active');
                });
                
                // Add active class to clicked card
                this.classList.add('active');
                
                // Refresh data
                const reportType = this.dataset.reportType;
                window.location.href = '{{ route("superadmin.reports") }}?type=' + reportType;
            });
        });
        
        // Generate PDF
        document.getElementById('generateReport').addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Generating...';
            btn.disabled = true;
            
            // Get report type
            const reportType = document.querySelector('.report-type-card.active')?.dataset.reportType || 'user';
            
            // Build URL
            let url = '{{ route("superadmin.reports.generate-pdf") }}?type=' + reportType;
            
            // Open in new window for PDF download
            window.open(url, '_blank');
            
            // Reset button
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 2000);
        });
        
        // Export Excel
        document.getElementById('exportExcel').addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Exporting...';
            btn.disabled = true;
            
            // Get report type
            const reportType = document.querySelector('.report-type-card.active')?.dataset.reportType || 'user';
            
            // Build URL
            let url = '{{ route("superadmin.reports.export-excel") }}?type=' + reportType;
            
            // Open in new window for Excel download
            window.open(url, '_blank');
            
            // Reset button
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 2000);
        });
        
        // Initialize chart based on report type
        function initChart() {
            @if(isset($chartData))
            const ctx = document.getElementById('reportChart').getContext('2d');
            const chartData = @json($chartData);
            
            // Special handling for satker doughnut chart
            if (chartData.type === 'doughnut') {
                // Generate distinct colors for each segment
                const chartColors = generateDistinctColors(chartData.data.length);
                
                let chartConfig = {
                    type: 'doughnut',
                    data: {
                        labels: chartData.labels || [],
                        datasets: [{
                            data: chartData.data || [],
                            backgroundColor: chartColors,
                            borderColor: 'white',
                            borderWidth: 2,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    padding: 15,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: chartData.title || 'Statistik Laporan',
                                font: {
                                    size: 16
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} user (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '65%',
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        }
                    }
                };
                
                new Chart(ctx, chartConfig);
            } else {
                // For other chart types (line, bar, etc.)
                let chartConfig = {
                    type: chartData.type || 'bar',
                    data: {
                        labels: chartData.labels || [],
                        datasets: []
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: chartData.title || 'Statistik Laporan',
                                font: {
                                    size: 16
                                }
                            }
                        }
                    }
                };
                
                if (chartData.datasets) {
                    // Multiple datasets (for system report)
                    chartData.datasets.forEach((dataset, index) => {
                        chartConfig.data.datasets.push({
                            label: dataset.label,
                            data: dataset.data,
                            backgroundColor: dataset.color || getColor(index),
                            borderColor: dataset.color || getColor(index),
                            borderWidth: 2,
                            fill: chartData.type === 'line'
                        });
                    });
                } else {
                    // Single dataset
                    chartConfig.data.datasets.push({
                        label: chartData.title || 'Data',
                        data: chartData.data || [],
                        backgroundColor: chartData.color || 'rgba(139, 92, 246, 0.8)',
                        borderColor: chartData.color || 'rgba(139, 92, 246, 1)',
                        borderWidth: 2,
                        fill: chartData.type === 'line'
                    });
                }
                
                new Chart(ctx, chartConfig);
            }
            @endif
        }
        
        // Generate distinct colors for doughnut chart segments
        function generateDistinctColors(count) {
            const baseColors = [
                '#8b5cf6', '#3b82f6', '#10b981', '#f59e0b', '#ef4444',
                '#0ea5e9', '#a855f7', '#84cc16', '#f97316', '#ec4899',
                '#06b6d4', '#22c55e', '#eab308', '#6366f1', '#14b8a6'
            ];
            
            if (count <= baseColors.length) {
                return baseColors.slice(0, count);
            }
            
            const colors = [...baseColors];
            for (let i = baseColors.length; i < count; i++) {
                const baseColor = baseColors[i % baseColors.length];
                const variation = i * 20;
                colors.push(lightenDarkenColor(baseColor, variation));
            }
            
            return colors;
        }
        
        // Function to lighten or darken a hex color
        function lightenDarkenColor(color, amount) {
            let usePound = false;
            
            if (color[0] === "#") {
                color = color.slice(1);
                usePound = true;
            }
            
            const num = parseInt(color, 16);
            let r = (num >> 16) + amount;
            let g = ((num >> 8) & 0x00FF) + amount;
            let b = (num & 0x0000FF) + amount;
            
            r = Math.min(Math.max(0, r), 255);
            g = Math.min(Math.max(0, g), 255);
            b = Math.min(Math.max(0, b), 255);
            
            return (usePound ? "#" : "") + (b | (g << 8) | (r << 16)).toString(16).padStart(6, '0');
        }
        
        // Helper function for chart colors
        function getColor(index) {
            const colors = [
                'rgba(139, 92, 246, 0.8)',
                'rgba(14, 165, 233, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ];
            return colors[index % colors.length];
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
        
        // Auto dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initChart();
        });
    </script>
    @endif
</body>
</html>