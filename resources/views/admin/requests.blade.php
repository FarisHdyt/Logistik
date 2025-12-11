<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Barang | SILOG Polres</title>
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
            background: linear-gradient(180deg, var(--primary) 0%, #1e40af 100%);
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
            border-left: 4px solid var(--primary-light);
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
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
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
            <a href="{{ route('admin.requests', ['status' => 'processing']) }}" class="status-tab {{ request('status') == 'processing' ? 'active' : '' }}">Diproses</a>
            <a href="{{ route('admin.requests', ['status' => 'delivered']) }}" class="status-tab {{ request('status') == 'delivered' ? 'active' : '' }}">Terkirim</a>
        </div>
        
        <!-- Requests Table -->
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
                            <tr>
                                <td>{{ ($requests->currentPage() - 1) * $requests->perPage() + $index + 1 }}</td>
                                <td><strong>{{ $request->kode_permintaan }}</strong></td>
                                <td>{{ $request->user->name ?? '-' }}</td>
                                <td>{{ $request->barang->nama_barang ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge">
                                        <strong>{{ $request->jumlah }}</strong>
                                        {{ $request->barang->satuan->nama_satuan ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $request->satker->nama_satker ?? '-' }}</td>
                                <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @if($request->status == 'pending')
                                        <span class="badge badge-pending">Pending</span>
                                    @elseif($request->status == 'approved')
                                        <span class="badge badge-approved">Disetujui</span>
                                    @elseif($request->status == 'rejected')
                                        <span class="badge badge-rejected">Ditolak</span>
                                    @elseif($request->status == 'processing')
                                        <span class="badge badge-processing">Diproses</span>
                                    @elseif($request->status == 'delivered')
                                        <span class="badge badge-delivered">Terkirim</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Aksi">
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#detailRequestModal" data-request-id="{{ $request->id }}" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @if($request->status == 'pending')
                                        <button type="button" class="btn btn-success btn-sm approve-request" 
                                                data-request-id="{{ $request->id }}" title="Setujui">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm reject-request" 
                                                data-request-id="{{ $request->id }}" title="Tolak">
                                            <i class="bi bi-x"></i>
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
        
        // Auto dismiss alerts
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
        
        // Approve Request Handler
        let currentRequestId;
        
        $('.approve-request').click(function() {
            currentRequestId = $(this).data('request-id');
            $('#approveModal').modal('show');
        });
        
        $('#confirmApprove').click(function() {
            if (!currentRequestId) return;
            
            $.ajax({
                url: `/admin/requests/${currentRequestId}/approve`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showAlert(response.message, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert(response.message, 'danger');
                    }
                    $('#approveModal').modal('hide');
                },
                error: function(xhr) {
                    showAlert('Terjadi kesalahan saat menyetujui permintaan', 'danger');
                    $('#approveModal').modal('hide');
                }
            });
        });
        
        // Reject Request Handler
        $('.reject-request').click(function() {
            currentRequestId = $(this).data('request-id');
            $('#rejectReason').val('');
            $('#rejectModal').modal('show');
        });
        
        $('#confirmReject').click(function() {
            if (!currentRequestId) return;
            
            const reason = $('#rejectReason').val().trim();
            if (!reason) {
                showAlert('Harap masukkan alasan penolakan', 'warning');
                return;
            }
            
            $.ajax({
                url: `/admin/requests/${currentRequestId}/reject`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: reason
                },
                success: function(response) {
                    if (response.success) {
                        showAlert(response.message, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert(response.message, 'danger');
                    }
                    $('#rejectModal').modal('hide');
                },
                error: function(xhr) {
                    showAlert('Terjadi kesalahan saat menolak permintaan', 'danger');
                    $('#rejectModal').modal('hide');
                }
            });
        });
    });
    
    // Detail Request Modal Handler
    const detailRequestModal = document.getElementById('detailRequestModal');
    detailRequestModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const requestId = button.getAttribute('data-request-id');
        
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
                
                let html = `
                    <!-- Bagian Informasi Dasar -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-info-circle"></i>
                            Informasi Dasar
                        </div>
                        <div class="row">
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Kode Permintaan</div>
                                <div class="detail-value">${request.kode_permintaan || '-'}</div>
                            </div>
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Tanggal Permintaan</div>
                                <div class="detail-value">${new Date(request.created_at).toLocaleDateString('id-ID')}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bagian Pemohon -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-person"></i>
                            Informasi Pemohon
                        </div>
                        <div class="row">
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Nama Pemohon</div>
                                <div class="detail-value">${request.user?.name || '-'}</div>
                            </div>
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Satuan Kerja</div>
                                <div class="detail-value">${request.satker?.nama_satker || '-'}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bagian Barang -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-box"></i>
                            Detail Barang
                        </div>
                        <div class="row">
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Nama Barang</div>
                                <div class="detail-value">${request.barang?.nama_barang || '-'}</div>
                            </div>
                            <div class="col-md-3 detail-row">
                                <div class="detail-label">Kode Barang</div>
                                <div class="detail-value">${request.barang?.kode_barang || '-'}</div>
                            </div>
                            <div class="col-md-3 detail-row">
                                <div class="detail-label">Jumlah</div>
                                <div class="detail-value">
                                    <span class="badge">
                                        <strong>${request.jumlah || 0}</strong> ${request.barang?.satuan?.nama_satuan || '-'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bagian Status -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-clipboard-check"></i>
                            Status Permintaan
                        </div>
                        <div class="row">
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Status</div>
                                <div class="detail-value">
                                    ${request.status == 'pending' ? 
                                        '<span class="badge badge-pending">Pending</span>' : 
                                    request.status == 'approved' ? 
                                        '<span class="badge badge-approved">Disetujui</span>' : 
                                    request.status == 'rejected' ? 
                                        '<span class="badge badge-rejected">Ditolak</span>' : 
                                    request.status == 'processing' ? 
                                        '<span class="badge badge-processing">Diproses</span>' : 
                                        '<span class="badge badge-delivered">Terkirim</span>'}
                                </div>
                            </div>
                            <div class="col-md-6 detail-row">
                                <div class="detail-label">Tanggal Status</div>
                                <div class="detail-value">${request.approved_at ? new Date(request.approved_at).toLocaleDateString('id-ID') : '-'}</div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Bagian Keterangan (jika ada)
                if (request.keperluan || request.catatan) {
                    html += `
                        <div class="detail-section">
                            <div class="detail-section-title">
                                <i class="bi bi-card-text"></i>
                                Keterangan
                            </div>
                            <div class="detail-row">
                                ${request.keperluan ? `
                                    <div class="mb-2">
                                        <div class="detail-label">Keperluan</div>
                                        <div class="detail-value">${request.keperluan}</div>
                                    </div>
                                ` : ''}
                                ${request.catatan ? `
                                    <div>
                                        <div class="detail-label">Catatan Admin</div>
                                        <div class="detail-value">${request.catatan}</div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }
                
                // Bagian Disetujui/Ditolak Oleh (jika ada)
                if (request.approved_by_user) {
                    html += `
                        <div class="detail-section">
                            <div class="detail-section-title">
                                <i class="bi bi-person-check"></i>
                                Disetujui/Ditolak Oleh
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Admin</div>
                                <div class="detail-value">${request.approved_by_user.name || '-'}</div>
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
                        <p class="mt-2 text-danger">Gagal memuat data permintaan</p>
                        <button class="btn btn-primary btn-sm mt-2" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Coba Lagi
                        </button>
                    </div>
                `);
            });
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
    
    // Print Function
    function printRequests() {
        // Sembunyikan elemen yang tidak perlu dicetak
        const elementsToHide = document.querySelectorAll('.sidebar, .topbar, .page-header, .stats-grid, .filter-bar, .status-tabs, .action-buttons, .btn-group');
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
        if (document.getElementById('searchInput').value) {
            filterInfo += `Pencarian: ${document.getElementById('searchInput').value}<br>`;
        }
        if (document.getElementById('satkerFilter').value) {
            const selectedSatker = document.querySelector('#satkerFilter option:checked').text;
            filterInfo += `Satker: ${selectedSatker}<br>`;
        }
        if (document.getElementById('statusFilter').value) {
            const selectedStatus = document.querySelector('#statusFilter option:checked').text;
            filterInfo += `Status: ${selectedStatus}<br>`;
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
    
    // Print Detail Function
    function printDetail() {
        const detailContent = document.getElementById('detailRequestModalBody').cloneNode(true);
        const printWindow = window.open('', '_blank');
        
        // Tambahkan judul cetakan
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
                    .badge-pending { background-color: #fef3c7; color: #92400e; }
                    .badge-approved { background-color: #d1fae5; color: #065f46; }
                    .badge-rejected { background-color: #fee2e2; color: #991b1b; }
                    .badge-processing { background-color: #dbeafe; color: #1e40af; }
                    .badge-delivered { background-color: #ede9fe; color: #7c3aed; }
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
    
    // Logout confirmation
    document.querySelector('form[action="{{ route("logout") }}"]').addEventListener('submit', function(e) {
        if (!confirm('Apakah Anda yakin ingin logout?')) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>