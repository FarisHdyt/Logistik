<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Barang - SILOG Polres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Tambahkan Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
        }
        
        .sidebar {
            background: var(--primary);
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
        
        /* Sidebar Styling */
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
            border-left-color: var(--primary-light);
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
        
        /* Permintaan Specific */
        .table-actions {
            white-space: nowrap;
        }
        
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.875em;
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 20px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid white;
            box-shadow: 0 0 0 3px var(--primary);
        }
        
        .timeline-item.completed::before {
            background: #28a745;
            box-shadow: 0 0 0 3px #28a745;
        }
        
        /* Select2 Custom */
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 5px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
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
        <div class="d-flex flex-column h-100">
            <!-- Sidebar Brand -->
            <div class="sidebar-brand">
                <h3 class="mb-1 fw-bold">SILOG POLRES</h3>
                <p class="mb-0 text-white-50" style="font-size: 0.875rem;">User Dashboard</p>
            </div>
            
            <!-- Sidebar Navigation -->
            <div class="sidebar-nav flex-grow-1">
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center active" href="{{ route('user.permintaan') }}">
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
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-custom">
            <div class="container-fluid">
                <span class="navbar-brand">
                    @isset($isShow)
                        Detail Permintaan
                    @elseif(isset($isEdit))
                        Edit Permintaan
                    @elseif(isset($isCreate))
                        Ajukan Permintaan Baru
                    @elseif(isset($isTrack))
                        Tracking Permintaan
                    @else
                        Permintaan Barang
                    @endif
                </span>
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
        
        <!-- Content -->
        <div class="container-fluid mt-4">
            <!-- Session Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Form Create -->
            @if(isset($isCreate))
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ajukan Permintaan Barang Baru</h5>
                    <a href="{{ route('user.permintaan') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.permintaan.store') }}" method="POST" id="permintaanForm">
                        @csrf
                        
                        <!-- Bagian Pencarian Barang -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="barang_search" class="form-label">
                                    Cari Barang
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2-barang-search" id="barang_search" name="barang_id" 
                                        style="width: 100%;" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @if(isset($barang) && $barang->count() > 0)
                                        @foreach($barang as $item)
                                        <option value="{{ $item->id }}" 
                                                data-stok="{{ $item->stok }}"
                                                data-kode="{{ $item->kode_barang }}"
                                                data-satuan="{{ $item->satuan->nama_satuan ?? 'unit' }}"
                                                data-kategori="{{ $item->kategori->nama_kategori ?? '-' }}">
                                            {{ $item->kode_barang }} - {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                        </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="form-text">Ketik untuk mencari barang yang tersedia</div>
                            </div>
                        </div>
                        
                        <!-- Bagian Informasi Barang -->
                        <div class="card mb-4" id="barangInfoCard" style="display: none;">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informasi Barang yang Dipilih</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Kode Barang:</strong></p>
                                        <p id="info_kode_barang">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Nama Barang:</strong></p>
                                        <p id="info_nama_barang">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Kategori:</strong></p>
                                        <p id="info_kategori">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Stok Tersedia:</strong></p>
                                        <p id="info_stok">- <span id="info_satuan">unit</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detail Permintaan -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="jumlah" class="form-label">
                                    Jumlah yang Diminta
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="jumlah" 
                                           class="form-control" 
                                           id="jumlah"
                                           min="1" 
                                           required
                                           disabled>
                                    <span class="input-group-text" id="satuan_label">unit</span>
                                </div>
                                <div class="form-text">Stok tersedia: <span id="stok_tersedia">0</span></div>
                                <div class="invalid-feedback" id="jumlah_error"></div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="satker_id" class="form-label">
                                    Satuan Kerja
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="satker_id" id="satker_id" class="form-select" required>
                                    <option value="">-- Pilih Satker --</option>
                                    @if(isset($satkers) && $satkers->count() > 0)
                                        @foreach($satkers as $satker)
                                            <option value="{{ $satker->id }}">{{ $satker->nama_satker }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="form-text">Pilih satuan kerja yang membutuhkan barang</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="tanggal_dibutuhkan" class="form-label">
                                    Tanggal Dibutuhkan
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal_dibutuhkan" 
                                       class="form-control" 
                                       id="tanggal_dibutuhkan"
                                       required>
                                <div class="form-text">Tanggal paling lambat barang dibutuhkan</div>
                            </div>
                            
                            <div class="col-12">
                                <label for="keterangan" class="form-label">
                                    Keterangan
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="keterangan" name="keterangan" 
                                          rows="4" placeholder="Jelaskan alasan dan kebutuhan barang ini..." 
                                          required></textarea>
                                <div class="form-text">Contoh: Untuk keperluan rapat rutin, penggantian alat rusak, dll.</div>
                            </div>
                        </div>
                        
                        <!-- Informasi Pengaju -->
                        <div class="alert alert-info mt-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-info-circle"></i>
                                </div>
                                <div>
                                    <p class="mb-1"><strong>Informasi Pengaju:</strong></p>
                                    <p class="mb-0">Nama: {{ Auth::user()->name }} | Tanggal: {{ date('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('user.permintaan') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="bi bi-check-circle me-1"></i>Ajukan Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Form Edit -->
            @elseif(isset($isEdit))
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Permintaan</h5>
                    <a href="{{ route('user.permintaan') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.permintaan.update', $permintaan->id) }}" method="POST" id="editPermintaanForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Info Barang -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informasi Barang</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Kode Barang:</strong></p>
                                        <p>{{ $permintaan->barang->kode_barang ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Nama Barang:</strong></p>
                                        <p>{{ $permintaan->barang->nama_barang ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Kategori:</strong></p>
                                        <p>{{ $permintaan->barang->kategori->nama_kategori ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Stok Tersedia:</strong></p>
                                        <p>{{ $permintaan->barang->stok ?? '0' }} {{ $permintaan->barang->satuan->nama_satuan ?? 'unit' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detail Permintaan -->
                        <div class="row g-3">
                            <input type="hidden" name="barang_id" value="{{ $permintaan->barang_id }}">
                            
                            <div class="col-md-6">
                                <label for="edit_jumlah" class="form-label">
                                    Jumlah yang Diminta
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="jumlah" 
                                           class="form-control" 
                                           id="edit_jumlah"
                                           value="{{ $permintaan->jumlah }}"
                                           min="1" 
                                           required>
                                    <span class="input-group-text">
                                        {{ $permintaan->barang->satuan->nama_satuan ?? 'unit' }}
                                    </span>
                                </div>
                                <div class="invalid-feedback" id="edit_jumlah_error"></div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="edit_satker_id" class="form-label">
                                    Satuan Kerja
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="satker_id" id="edit_satker_id" class="form-select" required>
                                    <option value="">-- Pilih Satker --</option>
                                    @if(isset($satkers) && $satkers->count() > 0)
                                        @foreach($satkers as $satker)
                                            <option value="{{ $satker->id }}" {{ $permintaan->satker_id == $satker->id ? 'selected' : '' }}>
                                                {{ $satker->nama_satker }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="edit_tanggal_dibutuhkan" class="form-label">
                                    Tanggal Dibutuhkan
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal_dibutuhkan" 
                                       class="form-control" 
                                       id="edit_tanggal_dibutuhkan"
                                       value="{{ $permintaan->tanggal_dibutuhkan ?? date('Y-m-d') }}"
                                       required>
                            </div>
                            
                            <div class="col-12">
                                <label for="edit_keterangan" class="form-label">
                                    Keterangan
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="edit_keterangan" name="keterangan" 
                                          rows="4" required>{{ $permintaan->keterangan }}</textarea>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('user.permintaan') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Show Detail -->
            @elseif(isset($isShow))
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Permintaan</h5>
                    <a href="{{ route('user.permintaan') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Informasi Permintaan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Kode Permintaan:</strong></td>
                                    <td>{{ $permintaan->kode_permintaan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Permintaan:</strong></td>
                                    <td>{{ $permintaan->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Dibutuhkan:</strong></td>
                                    <td>{{ $permintaan->tanggal_dibutuhkan ? \Carbon\Carbon::parse($permintaan->tanggal_dibutuhkan)->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Satuan Kerja:</strong></td>
                                    <td>{{ $permintaan->satker->nama_satker ?? '-' }}</td>
                                </tr>
                                @if($permintaan->alasan_penolakan)
                                <tr>
                                    <td><strong>Alasan Penolakan:</strong></td>
                                    <td>{{ $permintaan->alasan_penolakan }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Status</h6>
                            @if($permintaan->status == 'pending')
                                <span class="badge bg-warning fs-6 p-2">Pending</span>
                            @elseif($permintaan->status == 'approved')
                                <span class="badge bg-success fs-6 p-2">Disetujui</span>
                            @elseif($permintaan->status == 'rejected')
                                <span class="badge bg-danger fs-6 p-2">Ditolak</span>
                            @elseif($permintaan->status == 'delivered')
                                <span class="badge bg-info fs-6 p-2">Dikirim</span>
                            @endif
                            
                            @if($permintaan->approved_by && $permintaan->approved_at)
                            <div class="mt-3">
                                <p class="mb-1"><strong>Disetujui/Oleh:</strong></p>
                                <p class="mb-0">{{ $permintaan->approvedBy->name ?? 'Admin' }}<br>
                                <small>{{ \Carbon\Carbon::parse($permintaan->approved_at)->format('d/m/Y H:i') }}</small></p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informasi Barang</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Kode Barang:</strong></p>
                                    <p>{{ $permintaan->barang->kode_barang ?? '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Nama Barang:</strong></p>
                                    <p>{{ $permintaan->barang->nama_barang ?? '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Kategori:</strong></p>
                                    <p>{{ $permintaan->barang->kategori->nama_kategori ?? '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Jumlah Diminta:</strong></p>
                                    <p>{{ $permintaan->jumlah }} {{ $permintaan->barang->satuan->nama_satuan ?? 'unit' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($permintaan->keterangan)
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Keterangan</h6>
                        </div>
                        <div class="card-body">
                            <p>{{ $permintaan->keterangan }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Track Permintaan -->
            @elseif(isset($isTrack))
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tracking Permintaan</h5>
                    <a href="{{ route('user.permintaan') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Informasi Permintaan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Kode Permintaan:</strong></td>
                                    <td>{{ $permintaanTrack->kode_permintaan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Barang:</strong></td>
                                    <td>{{ $permintaanTrack->barang->nama_barang ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah:</strong></td>
                                    <td>{{ $permintaanTrack->jumlah }} {{ $permintaanTrack->barang->satuan->nama_satuan ?? 'unit' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Satuan Kerja:</strong></td>
                                    <td>{{ $permintaanTrack->satker->nama_satker ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Status Saat Ini</h6>
                            @if($permintaanTrack->status == 'pending')
                                <span class="badge bg-warning fs-6 p-2">Pending</span>
                            @elseif($permintaanTrack->status == 'approved')
                                <span class="badge bg-success fs-6 p-2">Disetujui</span>
                            @elseif($permintaanTrack->status == 'rejected')
                                <span class="badge bg-danger fs-6 p-2">Ditolak</span>
                            @elseif($permintaanTrack->status == 'delivered')
                                <span class="badge bg-info fs-6 p-2">Dikirim</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Timeline -->
                    <h6>Status Timeline</h6>
                    <div class="timeline">
                        @foreach($timeline as $item)
                        <div class="timeline-item {{ $item['completed'] ? 'completed' : '' }}">
                            <h6 class="mb-1">{{ $item['status'] }}</h6>
                            <p class="text-muted mb-1">{{ $item['date'] }}</p>
                            <p class="mb-0">{{ $item['description'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Default: List Permintaan -->
            @else
            <!-- Stats Cards -->
            @if(isset($stats))
            <div class="row mb-4">
                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card text-white bg-primary">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <h5>Total Permintaan</h5>
                            <h3>{{ $stats['total'] ?? 0 }}</h3>
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
                            <h3>{{ $stats['approved'] ?? 0 }}</h3>
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
                            <h3>{{ $stats['pending'] ?? 0 }}</h3>
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
                            <h3>{{ $stats['rejected'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Filter Permintaan</h5>
                    <a href="{{ route('user.permintaan.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Ajukan Permintaan
                    </a>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('user.permintaan') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Dikirim</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-funnel me-1"></i>Filter
                                </button>
                                @if(request()->anyFilled(['status', 'start_date', 'end_date']))
                                <a href="{{ route('user.permintaan') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Permintaan Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Permintaan Barang Saya</h5>
                </div>
                <div class="card-body">
                    @if($permintaan->count() > 0)
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
                                    <th>Tanggal Dibutuhkan</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permintaan as $item)
                                <tr>
                                    <td>{{ $loop->iteration + (($permintaan->currentPage() - 1) * $permintaan->perPage()) }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $item->kode_permintaan }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $item->barang->nama_barang ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $item->barang->kode_barang ?? '' }}</small>
                                    </td>
                                    <td>{{ $item->jumlah }} {{ $item->barang->satuan->nama_satuan ?? 'unit' }}</td>
                                    <td>{{ $item->satker->nama_satker ?? '-' }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $item->tanggal_dibutuhkan ? \Carbon\Carbon::parse($item->tanggal_dibutuhkan)->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($item->status == 'pending')
                                            <span class="badge bg-warning status-badge">
                                                <i class="bi bi-clock-history me-1"></i>Pending
                                            </span>
                                        @elseif($item->status == 'approved')
                                            <span class="badge bg-success status-badge">
                                                <i class="bi bi-check-circle me-1"></i>Disetujui
                                            </span>
                                        @elseif($item->status == 'rejected')
                                            <span class="badge bg-danger status-badge">
                                                <i class="bi bi-x-circle me-1"></i>Ditolak
                                            </span>
                                        @elseif($item->status == 'delivered')
                                            <span class="badge bg-info status-badge">
                                                <i class="bi bi-truck me-1"></i>Dikirim
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-actions">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('user.permintaan.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('user.permintaan.track', $item->kode_permintaan) }}" class="btn btn-sm btn-secondary" title="Track">
                                                <i class="bi bi-geo-alt"></i>
                                            </a>
                                            @if($item->status == 'pending')
                                            <a href="{{ route('user.permintaan.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('user.permintaan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permintaan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($permintaan->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="mb-0">Menampilkan {{ $permintaan->firstItem() }} - {{ $permintaan->lastItem() }} dari {{ $permintaan->total() }} permintaan</p>
                        </div>
                        <nav>
                            {{ $permintaan->links() }}
                        </nav>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-x display-1 text-muted"></i>
                        <h5 class="mt-3">Tidak ada permintaan barang</h5>
                        <p class="text-muted">Belum ada permintaan barang yang diajukan</p>
                        <a href="{{ route('user.permintaan.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle me-1"></i>Ajukan Permintaan
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Auto dismiss alerts
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
            
            // Filter date validation
            const startDate = $('input[name="start_date"]');
            const endDate = $('input[name="end_date"]');
            
            if (startDate.length && endDate.length) {
                startDate.on('change', function() {
                    endDate.attr('min', $(this).val());
                });
                
                endDate.on('change', function() {
                    startDate.attr('max', $(this).val());
                });
            }
            
            // Initialize Select2 for barang search
            if ($('#barang_search').length) {
                $('#barang_search').select2({
                    placeholder: "Ketik untuk mencari barang...",
                    allowClear: true,
                    width: '100%',
                    templateResult: formatBarangResult,
                    templateSelection: formatBarangSelection
                });
                
                // Event when barang is selected
                $('#barang_search').on('change', function() {
                    const selectedOption = $(this).find('option:selected');
                    const barangId = selectedOption.val();
                    
                    if (barangId) {
                        // Get data from data attributes
                        const stok = selectedOption.data('stok');
                        const kode = selectedOption.data('kode');
                        const satuan = selectedOption.data('satuan');
                        const kategori = selectedOption.data('kategori');
                        const namaBarang = selectedOption.text().split(' - ')[1]?.split(' (Stok:')[0];
                        
                        // Show barang info
                        $('#info_kode_barang').text(kode);
                        $('#info_nama_barang').text(namaBarang);
                        $('#info_kategori').text(kategori);
                        $('#info_stok').text(stok);
                        $('#info_satuan').text(satuan);
                        
                        // Update form inputs
                        $('#jumlah').prop('disabled', false);
                        $('#jumlah').attr('max', stok);
                        $('#satuan_label').text(satuan);
                        $('#stok_tersedia').text(stok + ' ' + satuan);
                        
                        // Show barang info card
                        $('#barangInfoCard').show();
                        
                        // Enable submit button
                        $('#submitBtn').prop('disabled', false);
                        
                        // Validate jumlah
                        validateJumlah();
                    } else {
                        // Reset form if no barang selected
                        resetForm();
                    }
                });
                
                // Validate jumlah input
                $('#jumlah').on('input', validateJumlah);
                
                function validateJumlah() {
                    const jumlah = parseInt($('#jumlah').val()) || 0;
                    const maxStok = parseInt($('#jumlah').attr('max')) || 0;
                    
                    if (jumlah > maxStok) {
                        $('#jumlah').addClass('is-invalid');
                        $('#jumlah_error').text('Jumlah melebihi stok tersedia (' + maxStok + ')');
                        $('#submitBtn').prop('disabled', true);
                    } else if (jumlah < 1) {
                        $('#jumlah').addClass('is-invalid');
                        $('#jumlah_error').text('Jumlah minimal 1');
                        $('#submitBtn').prop('disabled', true);
                    } else {
                        $('#jumlah').removeClass('is-invalid');
                        $('#submitBtn').prop('disabled', false);
                    }
                }
                
                function resetForm() {
                    $('#barangInfoCard').hide();
                    $('#jumlah').val('').prop('disabled', true);
                    $('#submitBtn').prop('disabled', true);
                    $('#stok_tersedia').text('0');
                    $('#jumlah').removeClass('is-invalid');
                }
                
                // Set minimum date for tanggal dibutuhkan
                const today = new Date().toISOString().split('T')[0];
                $('#tanggal_dibutuhkan').attr('min', today);
                $('#tanggal_dibutuhkan').val(today);
                
                // Form submit validation
                $('#permintaanForm').submit(function(e) {
                    const barangId = $('#barang_search').val();
                    const jumlah = parseInt($('#jumlah').val()) || 0;
                    const maxStok = parseInt($('#jumlah').attr('max')) || 0;
                    const keterangan = $('#keterangan').val().trim();
                    const tanggalDibutuhkan = $('#tanggal_dibutuhkan').val();
                    const satkerId = $('#satker_id').val();
                    
                    // Validate tanggal
                    if (tanggalDibutuhkan < today) {
                        e.preventDefault();
                        alert('Tanggal dibutuhkan tidak boleh kurang dari hari ini');
                        return false;
                    }
                    
                    if (!barangId || jumlah < 1 || jumlah > maxStok) {
                        e.preventDefault();
                        alert('Harap pilih barang dan isi jumlah dengan benar');
                        return false;
                    }
                    
                    if (!satkerId) {
                        e.preventDefault();
                        alert('Harap pilih satuan kerja');
                        return false;
                    }
                    
                    if (!keterangan) {
                        e.preventDefault();
                        alert('Harap isi keterangan kebutuhan barang');
                        return false;
                    }
                    
                    // Show loading state
                    $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Mengajukan...');
                });
            }
            
            // For edit form validation
            if ($('#editPermintaanForm').length) {
                const today = new Date().toISOString().split('T')[0];
                $('#edit_tanggal_dibutuhkan').attr('min', today);
                
                $('#editPermintaanForm').submit(function(e) {
                    const jumlah = parseInt($('#edit_jumlah').val()) || 0;
                    const keterangan = $('#edit_keterangan').val().trim();
                    const tanggalDibutuhkan = $('#edit_tanggal_dibutuhkan').val();
                    const satkerId = $('#edit_satker_id').val();
                    
                    if (tanggalDibutuhkan < today) {
                        e.preventDefault();
                        alert('Tanggal dibutuhkan tidak boleh kurang dari hari ini');
                        return false;
                    }
                    
                    if (jumlah < 1) {
                        e.preventDefault();
                        alert('Jumlah minimal 1');
                        return false;
                    }
                    
                    if (!satkerId) {
                        e.preventDefault();
                        alert('Harap pilih satuan kerja');
                        return false;
                    }
                    
                    if (!keterangan) {
                        e.preventDefault();
                        alert('Harap isi keterangan kebutuhan barang');
                        return false;
                    }
                });
            }
            
            // Format functions for Select2
            function formatBarangResult(barang) {
                if (!barang.id) {
                    return barang.text;
                }
                
                if (barang.element) {
                    const stok = $(barang.element).data('stok');
                    const satuan = $(barang.element).data('satuan');
                    const kategori = $(barang.element).data('kategori');
                    let badgeClass = 'badge bg-success';
                    
                    if (stok <= 0) {
                        badgeClass = 'badge bg-danger';
                    } else if (stok <= 5) {
                        badgeClass = 'badge bg-warning';
                    }
                    
                    const $container = $(
                        '<div class="d-flex justify-content-between align-items-center">' +
                            '<div>' +
                                '<strong>' + barang.text.split(' - ')[1]?.split(' (Stok:')[0] + '</strong>' +
                                '<div class="small text-muted">Kategori: ' + kategori + '</div>' +
                            '</div>' +
                            '<span class="' + badgeClass + ' ms-2">' + stok + ' ' + satuan + '</span>' +
                        '</div>'
                    );
                    return $container;
                }
                
                return barang.text;
            }
            
            function formatBarangSelection(barang) {
                if (!barang.id) {
                    return barang.text;
                }
                
                if (barang.element) {
                    const namaBarang = barang.text.split(' - ')[1]?.split(' (Stok:')[0] || barang.text;
                    return namaBarang;
                }
                
                return barang.text;
            }
        });
    </script>
</body>
</html>