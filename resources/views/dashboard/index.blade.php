<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SILOG Polres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #0f172a;
            --primary-light: #3b82f6;
            --delivered-color: #8b5cf6;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary) 0%, #0f172a 100%);
            color: white;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            padding: 0;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .dashboard-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            transition: transform 0.3s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .card-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        /* Sidebar Styling - Menyesuaikan dengan admin */
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-nav {
            padding: 0.5rem 0;
        }
        
        .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .nav-link {
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 0;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }
        
        .nav-link.active {
            background-color: rgba(59, 130, 246, 0.15);
            color: white;
            border-left-color: var(--delivered-color);
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.1);
        }
        
        /* Status Badge */
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.875em;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-footer {
                position: relative;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Brand -->
        <div class="sidebar-brand">
            <h3 class="mb-1 fw-bold">SILOG POLRES</h3>
            <p class="mb-0 text-white-50" style="font-size: 0.875rem;">User Dashboard</p>
        </div>
        
        <!-- Sidebar Navigation -->
        <div class="sidebar-nav">
            <div class="nav-item">
                <a class="nav-link d-flex align-items-center active" href="">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a class="nav-link d-flex align-items-center" href="{{ route('user.permintaan') }}">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Permintaan Barang</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a class="nav-link d-flex align-items-center" href="{{ route('user.laporan') }}">
                    <i class="bi bi-file-text"></i>
                    <span>Laporan</span>
                </a>
            </div>
        </div>
        
        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="text-center text-white-50">
                <small style="opacity: 0.7;">Sistem Logistik Polres</small><br>
                <small style="opacity: 0.5; font-size: 0.75rem;">v1.0.0</small>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-custom">
            <div class="container-fluid">
                <span class="navbar-brand">Dashboard</span>
                <div class="d-flex align-items-center">
                    <span class="me-3">Selamat datang, {{ Auth::user()->name }}!</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        
        <!-- Dashboard Content -->
        <div class="container-fluid mt-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Stats Cards -->
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card text-white bg-primary">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <h5>Permintaan Saya</h5>
                            <h3>{{ $data['my_requests'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card text-white bg-success">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h5>Disetujui</h5>
                            <h3>{{ $data['requests_approved'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card text-white bg-warning">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h5>Pending</h5>
                            <h3>{{ $data['requests_pending'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card text-white bg-danger">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <h5>Ditolak</h5>
                            <h3>{{ $data['requests_rejected'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Info -->
            <div class="card">
                <div class="card-header">
                    <h5>Informasi Akun</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> {{ $user->name }}</p>
                            <p><strong>Username:</strong> {{ $user->username }}</p>
                            <p><strong>NRP:</strong> {{ $user->nrp ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Role:</strong> <span class="badge bg-primary">{{ $user->role }}</span></p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Terakhir Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i:s') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Requests -->
            @if(isset($data['recent_requests']) && $data['recent_requests']->count() > 0)
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Permintaan Terbaru</h5>
                    <a href="{{ route('user.permintaan') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Permintaan</th>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Satuan Kerja</th>
                                    <th>Tanggal Permintaan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recent_requests'] as $request)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $request->kode_permintaan }}</span>
                                    </td>
                                    <td>
                                        @if(isset($request->details) && $request->details->count() > 0)
                                            <strong>{{ $request->details->count() }} jenis barang</strong><br>
                                            <small class="text-muted">
                                                @foreach($request->details->take(2) as $detail)
                                                    {{ $detail->barang->nama_barang ?? 'N/A' }},
                                                @endforeach
                                                @if($request->details->count() > 2)
                                                    dan {{ $request->details->count() - 2 }} lainnya
                                                @endif
                                            </small>
                                        @else
                                            <strong>{{ $request->barang->nama_barang ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $request->barang->kode_barang ?? '' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($request->details) && $request->details->count() > 0)
                                            {{ $request->details->sum('jumlah') }} unit<br>
                                            <small class="text-muted">{{ $request->details->count() }} jenis</small>
                                        @else
                                            {{ $request->jumlah }} {{ $request->barang->satuan->nama_satuan ?? 'unit' }}
                                        @endif
                                    </td>
                                    <td>{{ $request->satker->nama_satker ?? '-' }}</td>
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <small class="text-muted">
                        Menampilkan {{ $data['recent_requests']->count() }} permintaan terbaru
                    </small>
                </div>
            </div>
            @else
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Permintaan Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard-x display-1 text-muted"></i>
                        <h5 class="mt-3">Belum ada permintaan</h5>
                        <p class="text-muted">Anda belum mengajukan permintaan barang</p>
                        <a href="{{ route('user.permintaan.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle me-1"></i>Ajukan Permintaan
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>