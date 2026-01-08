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
        
        /* Cart Specific Styles */
        .cart-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .cart-table td {
            vertical-align: middle;
        }
        
        .cart-quantity-control {
            width: 120px;
        }
        
        .cart-quantity-control .input-group {
            width: 100%;
        }
        
        .cart-quantity-control input {
            text-align: center;
        }
        
        .quick-preview {
            border-left: 4px solid #3b82f6;
            background-color: #f8f9fa;
        }
        
        /* Validasi input tanggal */
        .is-valid {
            border-color: #28a745;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .is-valid:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }

        .is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }

        /* Placeholder untuk input date */
        input[type="date"]:not(:valid):not(:focus) {
            color: #6c757d;
        }

        /* Helper text styling */
        #tanggal-helper, #edit-tanggal-helper {
            transition: all 0.3s ease;
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
            
            .cart-table {
                font-size: 0.875rem;
            }
            
            .cart-quantity-control {
                width: 100px;
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
            
            <!-- Form Create - MULTI BARANG -->
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
                        
                        <!-- Bagian Pencarian Barang (SISTEM KASIR) -->
                        <div class="row mb-4">
                            <div class="col-md-10">
                                <label for="barang_search" class="form-label">
                                    Cari Barang untuk Ditambahkan
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2-barang-search" id="barang_search" 
                                        style="width: 100%;">
                                    <option value="">-- Pilih Barang --</option>
                                    @if(isset($barang) && $barang->count() > 0)
                                        @foreach($barang as $item)
                                        <option value="{{ $item->id }}" 
                                                data-stok="{{ $item->stok }}"
                                                data-kode="{{ $item->kode_barang }}"
                                                data-nama="{{ $item->nama_barang }}"
                                                data-satuan="{{ $item->satuan->nama_satuan ?? 'unit' }}"
                                                data-kategori="{{ $item->kategori->nama_kategori ?? '-' }}">
                                            {{ $item->kode_barang }} - {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                        </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="form-text">Ketik untuk mencari barang yang tersedia</div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="btnTambahBarang" disabled>
                                    <i class="bi bi-plus-circle me-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                        
                        <!-- Quick Preview Barang yang Dipilih -->
                        <div class="alert alert-info quick-preview mb-3" id="quickPreview" style="display: none;">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Kode:</strong> <span id="previewKode">-</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Nama:</strong> <span id="previewNama">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Stok:</strong> <span id="previewStok">-</span>
                                </div>
                                <div class="col-md-2">
                                    <strong>Satuan:</strong> <span id="previewSatuan">-</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tombol Aksi Daftar -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-danger btn-sm" id="btnKosongkanDaftar">
                                    <i class="bi bi-trash me-1"></i>Kosongkan Semua
                                </button>
                                <span class="ms-3 text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Tambahkan minimal 1 barang untuk dapat mengajukan permintaan
                                </span>
                            </div>
                        </div>
                        
                        <!-- Daftar Barang yang Dipilih (CART) -->
                        <div class="card mb-4" id="cartCard" style="display: none;">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="bi bi-cart me-2"></i>Daftar Barang yang Diminta
                                </h6>
                                <span class="badge bg-primary" id="cartCount">0 item</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 cart-table" id="cartTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="15%">Kode Barang</th>
                                                <th width="25%">Nama Barang</th>
                                                <th width="15%">Kategori</th>
                                                <th width="15%">Satuan</th>
                                                <th width="15%">Jumlah</th>
                                                <th width="10%">Stok</th>
                                                <th width="5%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartItems">
                                            <!-- Items akan ditambahkan dinamis -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Total Barang: <span id="totalItems">0</span> jenis</strong>
                                    </div>
                                    <div>
                                        <strong>Total Jumlah: <span id="totalQuantity">0</span> unit</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Satker dan Tanggal -->
                        <div class="row g-3 mt-4">
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
                                    <i class="bi bi-calendar-date"></i> Tanggal Dibutuhkan
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal_dibutuhkan" 
                                       class="form-control" 
                                       id="tanggal_dibutuhkan"
                                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                       required
                                       oninvalid="setCustomValidity('Pilih tanggal paling lambat barang dibutuhkan (minimal besok)')"
                                       onchange="try{setCustomValidity('')}catch(e){}">
                                
                                <div class="form-text d-flex align-items-start">
                                    <i class="bi bi-info-circle me-2 mt-1"></i>
                                    <div>
                                        <strong>Kapan barang akan digunakan?</strong><br>
                                        Pilih tanggal kebutuhan barang. 
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="keterangan" class="form-label">
                                    Keterangan / Alasan Permintaan
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="keterangan" name="keterangan" 
                                          rows="4" placeholder="Jelaskan alasan dan kebutuhan barang ini..." 
                                          required></textarea>
                                <div class="form-text">Contoh: Untuk keperluan rapat rutin, penggantian alat rusak, dll.</div>
                            </div>
                        </div>
                        
                        <!-- Hidden input untuk data barang -->
                        <div id="barangDataContainer">
                            <!-- Data barang akan disimpan sebagai input hidden -->
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
            
            <!-- Form Edit - MULTI BARANG -->
            @elseif(isset($isEdit))
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Permintaan - {{ $permintaan->kode_permintaan }}</h5>
                    <a href="{{ route('user.permintaan') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.permintaan.update', $permintaan->id) }}" method="POST" id="editPermintaanForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Info Permintaan -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-info-circle"></i>
                                </div>
                                <div>
                                    <p class="mb-1"><strong>Informasi Permintaan:</strong></p>
                                    <p class="mb-0">Kode: {{ $permintaan->kode_permintaan }} | 
                                       Tanggal Dibuat: {{ $permintaan->created_at->format('d/m/Y H:i') }} | 
                                       Status: <span class="badge bg-warning">Pending</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bagian Pencarian Barang untuk Edit -->
                        <div class="row mb-4">
                            <div class="col-md-10">
                                <label for="edit_barang_search" class="form-label">
                                    Cari Barang untuk Ditambahkan
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2-barang-search" id="edit_barang_search" 
                                        style="width: 100%;">
                                    <option value="">-- Pilih Barang --</option>
                                    @if(isset($barang) && $barang->count() > 0)
                                        @foreach($barang as $item)
                                        <option value="{{ $item->id }}" 
                                                data-stok="{{ $item->stok }}"
                                                data-kode="{{ $item->kode_barang }}"
                                                data-nama="{{ $item->nama_barang }}"
                                                data-satuan="{{ $item->satuan->nama_satuan ?? 'unit' }}"
                                                data-kategori="{{ $item->kategori->nama_kategori ?? '-' }}">
                                            {{ $item->kode_barang }} - {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                        </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="form-text">Ketik untuk mencari barang yang tersedia</div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="edit_btnTambahBarang" disabled>
                                    <i class="bi bi-plus-circle me-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                        
                        <!-- Quick Preview Barang yang Dipilih -->
                        <div class="alert alert-info quick-preview mb-3" id="edit_quickPreview" style="display: none;">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Kode:</strong> <span id="edit_previewKode">-</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Nama:</strong> <span id="edit_previewNama">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Stok:</strong> <span id="edit_previewStok">-</span>
                                </div>
                                <div class="col-md-2">
                                    <strong>Satuan:</strong> <span id="edit_previewSatuan">-</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tombol Aksi Daftar -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-danger btn-sm" id="edit_btnKosongkanDaftar">
                                    <i class="bi bi-trash me-1"></i>Kosongkan Semua
                                </button>
                                <span class="ms-3 text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Minimal 1 barang diperlukan untuk mengupdate permintaan
                                </span>
                            </div>
                        </div>
                        
                        <!-- Daftar Barang yang Dipilih (CART) -->
                        <div class="card mb-4" id="edit_cartCard">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="bi bi-cart me-2"></i>Daftar Barang yang Diminta
                                </h6>
                                <span class="badge bg-primary" id="edit_cartCount">{{ $permintaan->details->count() ?? 1 }} item</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 cart-table" id="edit_cartTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="15%">Kode Barang</th>
                                                <th width="25%">Nama Barang</th>
                                                <th width="15%">Kategori</th>
                                                <th width="15%">Satuan</th>
                                                <th width="15%">Jumlah</th>
                                                <th width="10%">Stok</th>
                                                <th width="5%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="edit_cartItems">
                                            <!-- Items akan diisi oleh JavaScript -->
                                            @if(isset($permintaan->details) && $permintaan->details->count() > 0)
                                                @foreach($permintaan->details as $index => $detail)
                                                @php
                                                    $barang = $detail->barang;
                                                @endphp
                                                <tr data-id="{{ $barang->id }}" data-index="{{ $index }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $barang->kode_barang }}</td>
                                                    <td>
                                                        <strong>{{ $barang->nama_barang }}</strong>
                                                    </td>
                                                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                                    <td>{{ $barang->satuan->nama_satuan ?? 'unit' }}</td>
                                                    <td>
                                                        <div class="input-group input-group-sm cart-quantity-control">
                                                            <button class="btn btn-outline-secondary btn-minus" type="button" 
                                                                    data-id="{{ $barang->id }}" data-index="{{ $index }}">
                                                                <i class="bi bi-dash"></i>
                                                            </button>
                                                            <input type="number" class="form-control text-center jumlah-input" 
                                                                   value="{{ $detail->jumlah }}" min="1" 
                                                                   data-id="{{ $barang->id }}" data-index="{{ $index }}"
                                                                   data-stok="{{ $barang->stok }}">
                                                            <button class="btn btn-outline-secondary btn-plus" type="button" 
                                                                    data-id="{{ $barang->id }}" data-index="{{ $index }}">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $stok = $barang->stok ?? 0;
                                                            $badgeClass = ($stok > 10) ? 'bg-success' : (($stok > 0) ? 'bg-warning' : 'bg-danger');
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }} stok-badge" 
                                                              data-id="{{ $barang->id }}" 
                                                              data-satuan="{{ $barang->satuan->nama_satuan ?? 'unit' }}">
                                                            {{ $stok }} {{ $barang->satuan->nama_satuan ?? 'unit' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger btn-hapus" 
                                                                data-id="{{ $barang->id }}" data-index="{{ $index }}" title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <!-- Fallback untuk permintaan lama (single barang) -->
                                                @php
                                                    $barang = $permintaan->barang;
                                                @endphp
                                                <tr data-id="{{ $barang->id }}" data-index="0">
                                                    <td>1</td>
                                                    <td>{{ $barang->kode_barang }}</td>
                                                    <td>
                                                        <strong>{{ $barang->nama_barang }}</strong>
                                                    </td>
                                                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                                    <td>{{ $barang->satuan->nama_satuan ?? 'unit' }}</td>
                                                    <td>
                                                        <div class="input-group input-group-sm cart-quantity-control">
                                                            <button class="btn btn-outline-secondary btn-minus" type="button" 
                                                                    data-id="{{ $barang->id }}" data-index="0">
                                                                <i class="bi bi-dash"></i>
                                                            </button>
                                                            <input type="number" class="form-control text-center jumlah-input" 
                                                                   value="{{ $permintaan->jumlah }}" min="1" 
                                                                   data-id="{{ $barang->id }}" data-index="0"
                                                                   data-stok="{{ $barang->stok }}">
                                                            <button class="btn btn-outline-secondary btn-plus" type="button" 
                                                                    data-id="{{ $barang->id }}" data-index="0">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $stok = $barang->stok ?? 0;
                                                            $badgeClass = ($stok > 10) ? 'bg-success' : (($stok > 0) ? 'bg-warning' : 'bg-danger');
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }} stok-badge" 
                                                              data-id="{{ $barang->id }}" 
                                                              data-satuan="{{ $barang->satuan->nama_satuan ?? 'unit' }}">
                                                            {{ $stok }} {{ $barang->satuan->nama_satuan ?? 'unit' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger btn-hapus" 
                                                                data-id="{{ $barang->id }}" data-index="0" title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>   
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Total Barang: <span id="edit_totalItems">{{ $permintaan->details->count() > 0 ? $permintaan->details->count() : 1 }}</span> jenis</strong>
                                    </div>
                                    <div>
                                        <strong>Total Jumlah: <span id="edit_totalQuantity">{{ $permintaan->details->sum('jumlah') ?? $permintaan->jumlah }}</span> unit</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Satker dan Tanggal -->
                        <div class="row g-3 mt-4">
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
                                <div class="form-text">Pilih satuan kerja yang membutuhkan barang</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="edit_tanggal_dibutuhkan" class="form-label">
                                    <i class="bi bi-calendar-date"></i> Tanggal Dibutuhkan
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal_dibutuhkan" 
                                       class="form-control" 
                                       id="edit_tanggal_dibutuhkan"
                                       value="{{ $permintaan->tanggal_dibutuhkan ? \Carbon\Carbon::parse($permintaan->tanggal_dibutuhkan)->format('Y-m-d') : '' }}"
                                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                       required
                                       oninvalid="setCustomValidity('Pilih tanggal paling lambat barang dibutuhkan (minimal besok)')"
                                       onchange="try{setCustomValidity('')}catch(e){}">
                                
                                <div class="form-text d-flex align-items-start">
                                    <i class="bi bi-info-circle me-2 mt-1"></i>
                                    <div>
                                        <strong>Kapan barang akan digunakan?</strong><br>
                                        Pilih tanggal kebutuhan barang. 
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="edit_keterangan" class="form-label">
                                    Keterangan / Alasan Permintaan
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="edit_keterangan" name="keterangan" 
                                          rows="4" placeholder="Jelaskan alasan dan kebutuhan barang ini..." 
                                          required>{{ $permintaan->keterangan }}</textarea>
                                <div class="form-text">Contoh: Untuk keperluan rapat rutin, penggantian alat rusak, dll.</div>
                            </div>
                        </div>
                        
                        <!-- Hidden input untuk data barang -->
                        <div id="edit_barangDataContainer">
                            <!-- Data barang akan disimpan sebagai input hidden -->
                            @if(isset($permintaan->details) && $permintaan->details->count() > 0)
                                @foreach($permintaan->details as $index => $detail)
                                <input type="hidden" name="barang_items[{{ $index }}][barang_id]" value="{{ $detail->barang_id }}">
                                <input type="hidden" name="barang_items[{{ $index }}][jumlah]" value="{{ $detail->jumlah }}">
                                @endforeach
                            @else
                                <!-- Fallback untuk permintaan lama -->
                                <input type="hidden" name="barang_items[0][barang_id]" value="{{ $permintaan->barang_id }}">
                                <input type="hidden" name="barang_items[0][jumlah]" value="{{ $permintaan->jumlah }}">
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('user.permintaan') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary" id="edit_submitBtn">
                                <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Show Detail - DIUPDATE untuk multi barang -->
            @elseif(isset($isShow))
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Permintaan</h5>
                    <div>
                        <a href="{{ route('user.permintaan.track', $permintaan->kode_permintaan) }}" class="btn btn-sm btn-secondary me-2">
                            <i class="bi bi-geo-alt me-1"></i>Track
                        </a>
                        <a href="{{ route('user.permintaan') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                        </a>
                    </div>
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
                                    <td class="text-danger">{{ $permintaan->alasan_penolakan }}</td>
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
                                <p class="mb-1"><strong>Disetujui Oleh:</strong></p>
                                <p class="mb-0">{{ $permintaan->approvedBy->name ?? 'Admin' }}<br>
                                <small>{{ \Carbon\Carbon::parse($permintaan->approved_at)->format('d/m/Y H:i') }}</small></p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Daftar Barang (Multi Barang) -->
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bi bi-box-seam me-2"></i>Daftar Barang yang Diminta
                            </h6>
                            <span class="badge bg-primary">
                                {{ $permintaan->details->count() ?? 1 }} jenis barang
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th>Satuan</th>
                                            <th>Jumlah</th>
                                            <th>Stok Tersedia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $items = isset($permintaan->details) && $permintaan->details->count() > 0 ? $permintaan->details : collect([$permintaan]);
                                        @endphp
                                        
                                        @foreach($items as $index => $item)
                                        @php
                                            $detail = isset($item->barang) ? $item : null;
                                            $barang = $detail ? $detail->barang : $permintaan->barang;
                                            $jumlah = $detail ? $detail->jumlah : $permintaan->jumlah;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $barang->kode_barang ?? '-' }}</td>
                                            <td>
                                                <strong>{{ $barang->nama_barang ?? 'N/A' }}</strong>
                                            </td>
                                            <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                            <td>{{ $barang->satuan->nama_satuan ?? 'unit' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $jumlah }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $stok = $barang->stok ?? 0;
                                                    $isEnough = $stok >= $jumlah;
                                                    $badgeClass = $isEnough ? 'bg-success' : 'bg-warning';
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">
                                                    {{ $stok }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Total Jumlah:</strong></td>
                                            <td colspan="2">
                                                <strong>{{ $items->sum('jumlah') }} unit</strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
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
                                    <td>
                                        @if(isset($permintaanTrack->details) && $permintaanTrack->details->count() > 0)
                                            {{ $permintaanTrack->details->count() }} jenis barang
                                        @else
                                            {{ $permintaanTrack->barang->nama_barang ?? '-' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Total:</strong></td>
                                    <td>
                                        @if(isset($permintaanTrack->details) && $permintaanTrack->details->count() > 0)
                                            {{ $permintaanTrack->details->sum('jumlah') }} unit
                                        @else
                                            {{ $permintaanTrack->jumlah }} {{ $permintaanTrack->barang->satuan->nama_satuan ?? 'unit' }}
                                        @endif
                                    </td>
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
                                        @if(isset($item->details) && $item->details->count() > 0)
                                            <strong>{{ $item->details->count() }} jenis barang</strong><br>
                                            <small class="text-muted">
                                                @foreach($item->details->take(2) as $detail)
                                                    {{ $detail->barang->nama_barang ?? 'N/A' }},
                                                @endforeach
                                                @if($item->details->count() > 2)
                                                    dan {{ $item->details->count() - 2 }} lainnya
                                                @endif
                                            </small>
                                        @else
                                            <strong>{{ $item->barang->nama_barang ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $item->barang->kode_barang ?? '' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($item->details) && $item->details->count() > 0)
                                            {{ $item->details->sum('jumlah') }} unit<br>
                                            <small class="text-muted">{{ $item->details->count() }} jenis</small>
                                        @else
                                            {{ $item->jumlah }} {{ $item->barang->satuan->nama_satuan ?? 'unit' }}
                                        @endif
                                    </td>
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
        console.log('Document ready - Permintaan page loaded');
        
        // Debug: Cek apakah form ada
        if ($('#permintaanForm').length) {
            console.log('Form permintaan ditemukan');
        }
        
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
        
        // ==============================================
        // VALIDASI TANGGAL DIBUTUHKAN - REAL-TIME
        // ==============================================
        
        // Fungsi untuk format tanggal Indonesia
        function formatTanggalIndonesia(date) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }
        
        // Set min date ke besok untuk semua form
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().split('T')[0];
        const tomorrowDisplay = formatTanggalIndonesia(tomorrow);
        
        // Untuk form CREATE
        if ($('#tanggal_dibutuhkan').length) {
            // Set minimum date ke besok
            $('#tanggal_dibutuhkan').attr('min', tomorrowStr);
            
            // Tambahkan helper text dinamis
            setTimeout(() => {
                $('#tanggal_dibutuhkan').after(`
                    <div class="text-info small mt-1" id="tanggal-helper">
                        <i class="bi bi-lightbulb"></i> 
                        Contoh: ${tomorrowDisplay} atau setelahnya
                    </div>
                `);
            }, 100);
            
            // Event untuk validasi real-time
            $('#tanggal_dibutuhkan').on('change', function() {
                validateTanggalDibutuhkan($(this), 'create');
            });
        }
        
        // Untuk form EDIT
        if ($('#edit_tanggal_dibutuhkan').length) {
            // Set minimum date ke besok
            $('#edit_tanggal_dibutuhkan').attr('min', tomorrowStr);
            
            // Validasi tanggal yang sudah ada
            const editDate = $('#edit_tanggal_dibutuhkan').val();
            if (editDate) {
                const editDateObj = new Date(editDate);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                // Jika tanggal edit lebih kecil dari besok, set ke besok
                if (editDateObj < tomorrow) {
                    $('#edit_tanggal_dibutuhkan').val(tomorrowStr);
                }
            }
            
            // Tambahkan helper text dinamis
            setTimeout(() => {
                $('#edit_tanggal_dibutuhkan').after(`
                    <div class="text-info small mt-1" id="edit-tanggal-helper">
                        <i class="bi bi-lightbulb"></i> 
                        Contoh: ${tomorrowDisplay} atau setelahnya
                    </div>
                `);
            }, 100);
            
            // Event untuk validasi real-time
            $('#edit_tanggal_dibutuhkan').on('change', function() {
                validateTanggalDibutuhkan($(this), 'edit');
            });
        }
        
        // Fungsi validasi tanggal
        function validateTanggalDibutuhkan(input, formType) {
            const value = input.val();
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (value) {
                const selectedDate = new Date(value);
                selectedDate.setHours(0, 0, 0, 0);
                
                // Cek apakah tanggal setelah hari ini
                if (selectedDate <= today) {
                    // Tampilkan error
                    input.addClass('is-invalid');
                    input.removeClass('is-valid');
                    
                    // Update helper text dengan error
                    const helperId = formType === 'create' ? '#tanggal-helper' : '#edit-tanggal-helper';
                    $(helperId).html(`
                        <i class="bi bi-exclamation-triangle text-danger"></i> 
                        <span class="text-danger">Pilih tanggal setelah hari ini</span>
                    `);
                    
                    return false;
                } else {
                    // Valid
                    input.removeClass('is-invalid');
                    input.addClass('is-valid');
                    
                    // Update helper text dengan sukses
                    const helperId = formType === 'create' ? '#tanggal-helper' : '#edit-tanggal-helper';
                    const selectedDisplay = formatTanggalIndonesia(selectedDate);
                    $(helperId).html(`
                        <i class="bi bi-check-circle text-success"></i> 
                        <span class="text-success">Barang dibutuhkan: ${selectedDisplay}</span>
                    `);
                    
                    return true;
                }
            } else {
                // Kosong
                input.removeClass('is-invalid is-valid');
                return null;
            }
        }
        
        // ==============================================
        // VALIDASI FORM SUBMIT UNTUK TANGGAL
        // ==============================================
        
        // Untuk form CREATE
        if ($('#permintaanForm').length) {
            $('#permintaanForm').on('submit', function(e) {
                // Validasi tanggal dibutuhkan
                const tanggalInput = $('#tanggal_dibutuhkan');
                const tanggalValue = tanggalInput.val();
                
                if (!tanggalValue) {
                    e.preventDefault();
                    alert('Harap pilih tanggal dibutuhkan');
                    tanggalInput.focus();
                    return false;
                }
                
                const selectedDate = new Date(tanggalValue);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate <= today) {
                    e.preventDefault();
                    alert('Tanggal dibutuhkan harus setelah hari ini (minimal besok)');
                    tanggalInput.focus();
                    return false;
                }
                
                // Jika semua valid, submit form
                return true;
            });
        }
        
        // Untuk form EDIT
        if ($('#editPermintaanForm').length) {
            $('#editPermintaanForm').on('submit', function(e) {
                // Validasi tanggal dibutuhkan
                const tanggalInput = $('#edit_tanggal_dibutuhkan');
                const tanggalValue = tanggalInput.val();
                
                if (!tanggalValue) {
                    e.preventDefault();
                    alert('Harap pilih tanggal dibutuhkan');
                    tanggalInput.focus();
                    return false;
                }
                
                const selectedDate = new Date(tanggalValue);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate <= today) {
                    e.preventDefault();
                    alert('Tanggal dibutuhkan harus setelah hari ini (minimal besok)');
                    tanggalInput.focus();
                    return false;
                }
                
                // Jika semua valid, submit form
                return true;
            });
        }
        
        // ==============================================
        // SISTEM KASIR UNTUK MULTI BARANG (CREATE FORM)
        // ==============================================
        if ($('#barang_search').length) {
            // Inisialisasi variabel
            let cartItems = [];
            let selectedBarang = null;
            
            // Initialize Select2 for barang search
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
                    const stok = parseInt(selectedOption.data('stok')) || 0;
                    const kode = selectedOption.data('kode');
                    const nama = selectedOption.data('nama');
                    const satuan = selectedOption.data('satuan');
                    const kategori = selectedOption.data('kategori');
                    
                    // Simpan data barang yang dipilih
                    selectedBarang = {
                        id: barangId,
                        kode: kode,
                        nama: nama,
                        stok: stok,
                        satuan: satuan,
                        kategori: kategori,
                        jumlah: 1 // Default jumlah 1
                    };
                    
                    // Tampilkan quick preview
                    $('#previewKode').text(kode);
                    $('#previewNama').text(nama);
                    $('#previewStok').text(stok);
                    $('#previewSatuan').text(satuan);
                    $('#quickPreview').show();
                    
                    // Enable tombol tambah
                    $('#btnTambahBarang').prop('disabled', false);
                } else {
                    selectedBarang = null;
                    $('#btnTambahBarang').prop('disabled', true);
                    $('#quickPreview').hide();
                }
            });
            
            // Tombol Tambah Barang ke Cart
            $('#btnTambahBarang').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!selectedBarang) return;
                
                // Cek apakah barang sudah ada di cart
                const existingIndex = cartItems.findIndex(item => item.id === selectedBarang.id);
                
                if (existingIndex !== -1) {
                    // Jika sudah ada, tambah jumlah
                    if (cartItems[existingIndex].jumlah < selectedBarang.stok) {
                        cartItems[existingIndex].jumlah += 1;
                    } else {
                        alert('Jumlah melebihi stok tersedia (' + selectedBarang.stok + ' ' + selectedBarang.satuan + ')!');
                        return;
                    }
                } else {
                    // Jika belum ada, tambah baru
                    cartItems.push({
                        ...selectedBarang,
                        jumlah: 1
                    });
                }
                
                // Refresh tampilan cart
                refreshCart();
                
                // Reset select
                $('#barang_search').val(null).trigger('change');
                selectedBarang = null;
                $('#btnTambahBarang').prop('disabled', true);
                $('#quickPreview').hide();
            });
            
            // Tombol Kosongkan Daftar
            $('#btnKosongkanDaftar').on('click', function(e) {
                e.preventDefault();
                
                if (cartItems.length === 0) {
                    alert('Daftar barang sudah kosong!');
                    return;
                }
                
                if (confirm('Apakah Anda yakin ingin mengosongkan semua barang dari daftar?')) {
                    cartItems = [];
                    refreshCart();
                }
            });
            
            // Fungsi refresh cart
            function refreshCart() {
                const cartTable = $('#cartItems');
                const cartCount = $('#cartCount');
                const totalItems = $('#totalItems');
                const totalQuantity = $('#totalQuantity');
                const cartCard = $('#cartCard');
                const submitBtn = $('#submitBtn');
                
                // Kosongkan tabel
                cartTable.empty();
                
                // Hitung total
                const totalJenis = cartItems.length;
                const totalJumlah = cartItems.reduce((sum, item) => sum + item.jumlah, 0);
                
                // Update totals
                totalItems.text(totalJenis);
                totalQuantity.text(totalJumlah);
                
                if (cartItems.length > 0) {
                    // Tampilkan cart card
                    cartCard.show();
                    cartCount.text(totalJenis + ' item');
                    
                    // Enable submit button jika ada minimal 1 barang
                    submitBtn.prop('disabled', false);
                    
                    // Tambah setiap item ke tabel
                    cartItems.forEach((item, index) => {
                        // Tentukan badge class berdasarkan stok
                        let badgeClass;
                        if (item.stok > 10) {
                            badgeClass = 'bg-success';
                        } else if (item.stok > 0) {
                            badgeClass = 'bg-warning';
                        } else {
                            badgeClass = 'bg-danger';
                        }
                        
                        const row = `
                            <tr data-id="${item.id}" data-index="${index}">
                                <td>${index + 1}</td>
                                <td>${item.kode}</td>
                                <td>
                                    <strong>${item.nama}</strong>
                                </td>
                                <td>${item.kategori}</td>
                                <td>${item.satuan}</td>
                                <td>
                                    <div class="input-group input-group-sm cart-quantity-control">
                                        <button class="btn btn-outline-secondary btn-minus" type="button" 
                                                data-id="${item.id}" data-index="${index}">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control text-center jumlah-input" 
                                               value="${item.jumlah}" min="1" max="${item.stok}" 
                                               data-id="${item.id}" data-index="${index}">
                                        <button class="btn btn-outline-secondary btn-plus" type="button" 
                                                data-id="${item.id}" data-index="${index}">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge ${badgeClass}">
                                        ${item.stok} ${item.satuan}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger btn-hapus" 
                                            data-id="${item.id}" data-index="${index}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        cartTable.append(row);
                    });
                    
                    // Update hidden inputs untuk form submission
                    updateHiddenInputs();
                } else {
                    // Sembunyikan cart card
                    cartCard.hide();
                    cartCount.text('0 item');
                    
                    // Disable submit button
                    submitBtn.prop('disabled', true);
                }
            }
            
            // Event delegation untuk button di cart
            $(document).on('click', '#cartItems .btn-plus', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const id = $(this).data('id');
                const index = $(this).data('index');
                
                if (index !== undefined && cartItems[index]) {
                    const item = cartItems[index];
                    
                    if (item.jumlah < item.stok) {
                        item.jumlah++;
                        refreshCart();
                    } else {
                        alert('Jumlah melebihi stok tersedia (' + item.stok + ' ' + item.satuan + ')!');
                    }
                }
            });
            
            $(document).on('click', '#cartItems .btn-minus', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const id = $(this).data('id');
                const index = $(this).data('index');
                
                if (index !== undefined && cartItems[index]) {
                    const item = cartItems[index];
                    
                    if (item.jumlah > 1) {
                        item.jumlah--;
                        refreshCart();
                    }
                }
            });
            
            $(document).on('input', '#cartItems .jumlah-input', function() {
                const id = $(this).data('id');
                const index = $(this).data('index');
                const newJumlah = parseInt($(this).val()) || 1;
                
                if (index !== undefined && cartItems[index]) {
                    const item = cartItems[index];
                    
                    if (newJumlah > item.stok) {
                        alert('Jumlah melebihi stok tersedia (' + item.stok + ' ' + item.satuan + ')!');
                        $(this).val(item.jumlah);
                    } else if (newJumlah < 1) {
                        $(this).val(1);
                        item.jumlah = 1;
                        refreshCart();
                    } else {
                        item.jumlah = newJumlah;
                        refreshCart();
                    }
                }
            });
            
            $(document).on('click', '#cartItems .btn-hapus', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const id = $(this).data('id');
                const index = $(this).data('index');
                
                if (index !== undefined && cartItems[index]) {
                    // Konfirmasi sebelum menghapus
                    if (confirm('Apakah Anda yakin ingin menghapus barang ini dari daftar?')) {
                        cartItems.splice(index, 1);
                        refreshCart();
                    }
                }
            });
            
            // Fungsi update hidden inputs
            function updateHiddenInputs() {
                const container = $('#barangDataContainer');
                container.empty();
                
                cartItems.forEach((item, index) => {
                    container.append(`
                        <input type="hidden" name="barang_items[${index}][barang_id]" value="${item.id}">
                        <input type="hidden" name="barang_items[${index}][jumlah]" value="${item.jumlah}">
                    `);
                });
            }
            
            // Form validation before submit
            $('#permintaanForm').on('submit', function(e) {
                e.preventDefault();
                
                console.log('Form submit dipanggil');
                
                if (cartItems.length === 0) {
                    alert('Harap tambahkan minimal 1 barang ke daftar permintaan');
                    return false;
                }
                
                const satkerId = $('#satker_id').val();
                const keterangan = $('#keterangan').val().trim();
                const tanggalDibutuhkan = $('#tanggal_dibutuhkan').val();
                
                if (!tanggalDibutuhkan) {
                    alert('Harap pilih tanggal dibutuhkan');
                    return false;
                }
                
                const selectedDate = new Date(tanggalDibutuhkan);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate <= today) {
                    alert('Tanggal dibutuhkan harus setelah hari ini (minimal besok)');
                    return false;
                }
                
                if (!satkerId) {
                    alert('Harap pilih satuan kerja');
                    return false;
                }
                
                if (!keterangan) {
                    alert('Harap isi keterangan kebutuhan barang');
                    return false;
                }
                
                // Validasi stok untuk setiap barang
                let isValid = true;
                let errorMessage = '';
                
                cartItems.forEach((item, index) => {
                    if (item.jumlah > item.stok) {
                        isValid = false;
                        errorMessage = `Jumlah barang "${item.nama}" melebihi stok tersedia (${item.stok} ${item.satuan})`;
                    }
                });
                
                if (!isValid) {
                    alert(errorMessage);
                    return false;
                }
                
                // Show loading state
                $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Mengajukan...');
                
                // Submit form dengan timeout untuk memastikan UI update
                setTimeout(() => {
                    console.log('Form benar-benar disubmit');
                    $(this).off('submit').submit();
                }, 100);
            });
            
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
        }
        
        // ==============================================
        // SISTEM KASIR UNTUK EDIT MULTI BARANG (DENGAN STOK REAL-TIME)
        // ==============================================
        if ($('#edit_barang_search').length) {
            // Inisialisasi variabel dengan data dari server
            let editCartItems = [];
            let editSelectedBarang = null;
            
            // Initialize Select2 for barang search
            $('#edit_barang_search').select2({
                placeholder: "Ketik untuk mencari barang...",
                allowClear: true,
                width: '100%',
                templateResult: formatBarangResult,
                templateSelection: formatBarangSelection
            });
            
            // Fungsi untuk update stok dari server
             function updateStokFromServer(barangId) {
                return new Promise((resolve, reject) => {
                    // Perbaiki URL dengan cara yang benar
                    const url = '{{ route("user.permintaan.barang.stok", ":id") }}'.replace(':id', barangId);
                    
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                resolve(response);
                            } else {
                                reject(response.message);
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching stock:', xhr);
                            reject('Gagal mengambil data stok dari server');
                        }
                    });
                });
            }
            
            // Load existing items from table to cart array
            $('#edit_cartItems tr').each(function(index) {
                const row = $(this);
                const barangId = row.data('id');
                const barangKode = row.find('td:eq(1)').text();
                const barangNama = row.find('td:eq(2) strong').text();
                const barangKategori = row.find('td:eq(3)').text();
                const barangSatuan = row.find('td:eq(4)').text();
                const barangJumlah = parseInt(row.find('.jumlah-input').val()) || 1;
                
                // Get initial stok from data attribute
                const barangStok = parseInt(row.find('.stok-badge').text().split(' ')[0]) || 0;
                
                editCartItems.push({
                    id: barangId,
                    kode: barangKode,
                    nama: barangNama,
                    kategori: barangKategori,
                    satuan: barangSatuan,
                    stok: barangStok,
                    jumlah: barangJumlah
                });
            });
            
            // Event when barang is selected
            $('#edit_barang_search').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const barangId = selectedOption.val();
                
                if (barangId) {
                    // Cek apakah barang sudah ada di cart
                    const existingItem = editCartItems.find(item => item.id == barangId);
                    if (existingItem) {
                        alert('Barang ini sudah ada di daftar permintaan!');
                        $(this).val(null).trigger('change');
                        return;
                    }
                    
                    // Get data from data attributes
                    const stok = parseInt(selectedOption.data('stok')) || 0;
                    const kode = selectedOption.data('kode');
                    const nama = selectedOption.data('nama');
                    const satuan = selectedOption.data('satuan');
                    const kategori = selectedOption.data('kategori');
                    
                    // Simpan data barang yang dipilih
                    editSelectedBarang = {
                        id: barangId,
                        kode: kode,
                        nama: nama,
                        stok: stok,
                        satuan: satuan,
                        kategori: kategori,
                        jumlah: 1 // Default jumlah 1
                    };
                    
                    // Ambil stok real-time dari server
                    updateStokFromServer(barangId)
                        .then(response => {
                            editSelectedBarang.stok = response.stok;
                            
                            // Tampilkan quick preview dengan stok terbaru
                            $('#edit_previewKode').text(kode);
                            $('#edit_previewNama').text(nama);
                            $('#edit_previewStok').text(response.stok);
                            $('#edit_previewSatuan').text(response.satuan);
                            $('#edit_quickPreview').show();
                            
                            // Enable tombol tambah
                            $('#edit_btnTambahBarang').prop('disabled', false);
                        })
                        .catch(error => {
                            console.error('Error fetching stock:', error);
                            // Fallback ke stok dari data attribute
                            $('#edit_previewKode').text(kode);
                            $('#edit_previewNama').text(nama);
                            $('#edit_previewStok').text(stok);
                            $('#edit_previewSatuan').text(satuan);
                            $('#edit_quickPreview').show();
                            $('#edit_btnTambahBarang').prop('disabled', false);
                        });
                } else {
                    editSelectedBarang = null;
                    $('#edit_btnTambahBarang').prop('disabled', true);
                    $('#edit_quickPreview').hide();
                }
            });
            
            // Tombol Tambah Barang ke Cart
            $('#edit_btnTambahBarang').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!editSelectedBarang) return;
                
                // Cek apakah barang sudah ada di cart
                const existingIndex = editCartItems.findIndex(item => item.id === editSelectedBarang.id);
                
                if (existingIndex !== -1) {
                    // Jika sudah ada, tambah jumlah
                    if (editCartItems[existingIndex].jumlah < editSelectedBarang.stok) {
                        editCartItems[existingIndex].jumlah += 1;
                    } else {
                        alert('Jumlah melebihi stok tersedia (' + editSelectedBarang.stok + ' ' + editSelectedBarang.satuan + ')!');
                        return;
                    }
                } else {
                    // Jika belum ada, tambah baru
                    editCartItems.push({
                        ...editSelectedBarang,
                        jumlah: 1
                    });
                }
                
                // Refresh tampilan cart
                refreshEditCart();
                
                // Reset select
                $('#edit_barang_search').val(null).trigger('change');
                editSelectedBarang = null;
                $('#edit_btnTambahBarang').prop('disabled', true);
                $('#edit_quickPreview').hide();
            });
            
            // Tombol Kosongkan Daftar
            $('#edit_btnKosongkanDaftar').on('click', function(e) {
                e.preventDefault();
                
                if (editCartItems.length === 0) {
                    alert('Daftar barang sudah kosong!');
                    return;
                }
                
                if (confirm('Apakah Anda yakin ingin mengosongkan semua barang dari daftar?')) {
                    editCartItems = [];
                    refreshEditCart();
                }
            });
            
            // Fungsi refresh cart edit
            function refreshEditCart() {
                const cartTable = $('#edit_cartItems');
                const cartCount = $('#edit_cartCount');
                const totalItems = $('#edit_totalItems');
                const totalQuantity = $('#edit_totalQuantity');
                const cartCard = $('#edit_cartCard');
                const container = $('#edit_barangDataContainer');
                
                // Kosongkan tabel
                cartTable.empty();
                
                // Hitung total
                const totalJenis = editCartItems.length;
                const totalJumlah = editCartItems.reduce((sum, item) => sum + item.jumlah, 0);
                
                // Update totals
                totalItems.text(totalJenis);
                totalQuantity.text(totalJumlah);
                
                if (editCartItems.length > 0) {
                    // Tampilkan cart card
                    cartCard.show();
                    cartCount.text(totalJenis + ' item');
                    
                    // Update hidden inputs
                    container.empty();
                    
                    // Update stok semua barang dari server sebelum menampilkan
                    const updatePromises = editCartItems.map((item, index) => {
                        return updateStokFromServer(item.id)
                            .then(response => {
                                // Update stok di item
                                editCartItems[index].stok = response.stok;
                                editCartItems[index].satuan = response.satuan;
                                
                                // Tentukan badge class berdasarkan stok
                                let badgeClass;
                                if (response.stok > 10) {
                                    badgeClass = 'bg-success';
                                } else if (response.stok > 0) {
                                    badgeClass = 'bg-warning';
                                } else {
                                    badgeClass = 'bg-danger';
                                }
                                
                                // Buat row untuk item
                                const row = `
                                    <tr data-id="${item.id}" data-index="${index}">
                                        <td>${index + 1}</td>
                                        <td>${item.kode}</td>
                                        <td>
                                            <strong>${item.nama}</strong>
                                        </td>
                                        <td>${item.kategori}</td>
                                        <td>${response.satuan}</td>
                                        <td>
                                            <div class="input-group input-group-sm cart-quantity-control">
                                                <button class="btn btn-outline-secondary btn-minus" type="button" 
                                                        data-id="${item.id}" data-index="${index}">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" class="form-control text-center jumlah-input" 
                                                       value="${item.jumlah}" min="1" max="${response.stok}" 
                                                       data-id="${item.id}" data-index="${index}"
                                                       data-stok="${response.stok}">
                                                <button class="btn btn-outline-secondary btn-plus" type="button" 
                                                        data-id="${item.id}" data-index="${index}">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge ${badgeClass} stok-badge" 
                                                  data-id="${item.id}" 
                                                  data-satuan="${response.satuan}">
                                                ${response.stok} ${response.satuan}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger btn-hapus" 
                                                    data-id="${item.id}" data-index="${index}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                cartTable.append(row);
                                
                                // Tambah hidden input
                                container.append(`
                                    <input type="hidden" name="barang_items[${index}][barang_id]" value="${item.id}">
                                    <input type="hidden" name="barang_items[${index}][jumlah]" value="${item.jumlah}">
                                `);
                            })
                            .catch(error => {
                                console.error(`Error updating stock for item ${item.id}:`, error);
                                // Fallback ke stok lama
                                let badgeClass;
                                if (item.stok > 10) {
                                    badgeClass = 'bg-success';
                                } else if (item.stok > 0) {
                                    badgeClass = 'bg-warning';
                                } else {
                                    badgeClass = 'bg-danger';
                                }
                                
                                const row = `
                                    <tr data-id="${item.id}" data-index="${index}">
                                        <td>${index + 1}</td>
                                        <td>${item.kode}</td>
                                        <td>
                                            <strong>${item.nama}</strong>
                                        </td>
                                        <td>${item.kategori}</td>
                                        <td>${item.satuan}</td>
                                        <td>
                                            <div class="input-group input-group-sm cart-quantity-control">
                                                <button class="btn btn-outline-secondary btn-minus" type="button" 
                                                        data-id="${item.id}" data-index="${index}">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" class="form-control text-center jumlah-input" 
                                                       value="${item.jumlah}" min="1" max="${item.stok}" 
                                                       data-id="${item.id}" data-index="${index}"
                                                       data-stok="${item.stok}">
                                                <button class="btn btn-outline-secondary btn-plus" type="button" 
                                                        data-id="${item.id}" data-index="${index}">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge ${badgeClass} stok-badge" 
                                                  data-id="${item.id}" 
                                                  data-satuan="${item.satuan}">
                                                ${item.stok} ${item.satuan}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger btn-hapus" 
                                                    data-id="${item.id}" data-index="${index}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                cartTable.append(row);
                                
                                // Tambah hidden input
                                container.append(`
                                    <input type="hidden" name="barang_items[${index}][barang_id]" value="${item.id}">
                                    <input type="hidden" name="barang_items[${index}][jumlah]" value="${item.jumlah}">
                                `);
                            });
                    });
                    
                    // Tunggu semua update selesai
                    Promise.all(updatePromises).then(() => {
                        console.log('All stock updates completed');
                    });
                } else {
                    // Sembunyikan cart card
                    cartCard.hide();
                    cartCount.text('0 item');
                    
                    // Kosongkan hidden inputs
                    container.empty();
                }
            }
            
            // Event delegation untuk button di cart edit
            $(document).on('click', '#edit_cartTable .btn-plus', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const id = $(this).data('id');
                const index = $(this).data('index');
                
                if (index !== undefined && editCartItems[index]) {
                    const item = editCartItems[index];
                    
                    // Ambil stok terbaru dari server
                    updateStokFromServer(id)
                        .then(response => {
                            const currentStok = response.stok;
                            
                            if (item.jumlah < currentStok) {
                                item.jumlah++;
                                // Update stok di array
                                editCartItems[index].stok = currentStok;
                                refreshEditCart();
                            } else {
                                alert('Jumlah melebihi stok tersedia (' + currentStok + ' ' + response.satuan + ')!');
                            }
                        })
                        .catch(error => {
                            console.error('Error checking stock:', error);
                            // Fallback ke stok yang ada
                            if (item.jumlah < item.stok) {
                                item.jumlah++;
                                refreshEditCart();
                            } else {
                                alert('Jumlah melebihi stok tersedia (' + item.stok + ' ' + item.satuan + ')!');
                            }
                        });
                }
            });
            
            $(document).on('click', '#edit_cartTable .btn-minus', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const id = $(this).data('id');
                const index = $(this).data('index');
                
                if (index !== undefined && editCartItems[index]) {
                    const item = editCartItems[index];
                    
                    if (item.jumlah > 1) {
                        item.jumlah--;
                        refreshEditCart();
                    }
                }
            });
            
            $(document).on('input', '#edit_cartTable .jumlah-input', function() {
                const id = $(this).data('id');
                const index = $(this).data('index');
                const newJumlah = parseInt($(this).val()) || 1;
                
                if (index !== undefined && editCartItems[index]) {
                    const item = editCartItems[index];
                    
                    // Ambil stok terbaru dari server
                    updateStokFromServer(id)
                        .then(response => {
                            const currentStok = response.stok;
                            
                            if (newJumlah > currentStok) {
                                alert('Jumlah melebihi stok tersedia (' + currentStok + ' ' + response.satuan + ')!');
                                $(this).val(item.jumlah);
                            } else if (newJumlah < 1) {
                                $(this).val(1);
                                item.jumlah = 1;
                                refreshEditCart();
                            } else {
                                item.jumlah = newJumlah;
                                editCartItems[index].stok = currentStok;
                                refreshEditCart();
                            }
                        })
                        .catch(error => {
                            console.error('Error checking stock:', error);
                            // Fallback ke stok yang ada
                            if (newJumlah > item.stok) {
                                alert('Jumlah melebihi stok tersedia (' + item.stok + ' ' + item.satuan + ')!');
                                $(this).val(item.jumlah);
                            } else if (newJumlah < 1) {
                                $(this).val(1);
                                item.jumlah = 1;
                                refreshEditCart();
                            } else {
                                item.jumlah = newJumlah;
                                refreshEditCart();
                            }
                        });
                }
            });
            
            $(document).on('click', '#edit_cartTable .btn-hapus', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const id = $(this).data('id');
                const index = $(this).data('index');
                
                if (index !== undefined && editCartItems[index]) {
                    // Konfirmasi sebelum menghapus
                    if (confirm('Apakah Anda yakin ingin menghapus barang ini dari daftar?')) {
                        editCartItems.splice(index, 1);
                        refreshEditCart();
                    }
                }
            });
            
            // Form validation before submit - DENGAN STOK REAL-TIME
            $('#editPermintaanForm').on('submit', function(e) {
                e.preventDefault();
                
                console.log('Edit form submit dipanggil');
                
                if (editCartItems.length === 0) {
                    alert('Harap tambahkan minimal 1 barang ke daftar permintaan');
                    return false;
                }
                
                const satkerId = $('#edit_satker_id').val();
                const keterangan = $('#edit_keterangan').val().trim();
                const tanggalDibutuhkan = $('#edit_tanggal_dibutuhkan').val();
                
                if (!tanggalDibutuhkan) {
                    alert('Harap pilih tanggal dibutuhkan');
                    return false;
                }
                
                const selectedDate = new Date(tanggalDibutuhkan);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate <= today) {
                    alert('Tanggal dibutuhkan harus setelah hari ini (minimal besok)');
                    return false;
                }
                
                if (!satkerId) {
                    alert('Harap pilih satuan kerja');
                    return false;
                }
                
                if (!keterangan) {
                    alert('Harap isi keterangan kebutuhan barang');
                    return false;
                }
                
                // Validasi stok real-time untuk setiap barang
                const validationPromises = editCartItems.map((item, index) => {
                    return updateStokFromServer(item.id)
                        .then(response => {
                            if (item.jumlah > response.stok) {
                                throw new Error(`Jumlah barang "${item.nama}" melebihi stok tersedia (${response.stok} ${response.satuan})`);
                            }
                            return true;
                        })
                        .catch(error => {
                            throw error;
                        });
                });
                
                // Tampilkan loading
                $('#edit_submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Memvalidasi...');
                
                // Jalankan semua validasi
                Promise.all(validationPromises)
                    .then(() => {
                        // Semua validasi berhasil, submit form
                        console.log('Edit form validasi berhasil, submit form');
                        $(this).off('submit').submit();
                    })
                    .catch(error => {
                        // Ada error validasi
                        $('#edit_submitBtn').prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i>Simpan Perubahan');
                        alert(error.message);
                    });
            });
        }

        // Detail Request Modal Handler - PERBAIKAN
$(document).on('click', '.btn-detail', function() {
    const requestId = $(this).data('request-id');
    
    console.log('🚀 Detail button clicked');
    console.log('📌 Request ID:', requestId);
    console.log('🔗 URL yang akan diakses:', `/admin/requests/${requestId}`);
    
    // Tampilkan loading state
    $('#detailRequestModalBody').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat data permintaan...</p>
        </div>
    `);
    
    // Clear modal data dulu
    $('#detailRequestModal').data('request-id', requestId);
    
    // Coba dengan error handling yang lebih baik
    $.ajax({
        url: `/admin/requests/${requestId}`,
        method: 'GET',
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        success: function(response) {
            console.log('✅ Response success:', response);
            
            if (response.success && response.request) {
                renderRequestDetails(response.request);
            } else {
                console.error('❌ Response error:', response);
                showErrorModal('Data tidak ditemukan', response.message || 'Data tidak tersedia');
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ AJAX Error:', {
                status: xhr.status,
                statusText: xhr.statusText,
                error: error,
                responseText: xhr.responseText,
                url: xhr.responseURL
            });
            
            // Coba route alternatif
            if (xhr.status === 404 || xhr.status === 500) {
                tryAlternativeRoute(requestId, xhr.status);
            } else {
                showErrorModal('Koneksi Gagal', 
                    `Status: ${xhr.status}<br>
                     Error: ${error}<br>
                     URL: /admin/requests/${requestId}`);
            }
        }
    });
});

// Fungsi coba route alternatif
function tryAlternativeRoute(requestId, previousStatus) {
    console.log('🔄 Mencoba route alternatif...');
    
    $.ajax({
        url: `/admin/requests/${requestId}/details`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('✅ Alternative route success:', response);
            
            if (response.success && response.request) {
                renderRequestDetails(response.request);
            } else {
                showErrorModal('Route tidak ditemukan', 
                    'Silakan periksa konfigurasi route atau hubungi administrator.');
            }
        },
        error: function(xhr) {
            console.error('❌ Alternative route also failed:', xhr.status);
            
            // Tampilkan error detail untuk debugging
            let errorMessage = '';
            if (previousStatus === 404) {
                errorMessage = 'Endpoint tidak ditemukan (404). Periksa route di web.php';
            } else if (previousStatus === 500) {
                errorMessage = 'Server error (500). Periksa controller atau database';
            } else if (previousStatus === 403) {
                errorMessage = 'Akses ditolak (403). Periksa middleware auth';
            } else {
                errorMessage = 'Tidak dapat menghubungi server';
            }
            
            showErrorModal('Koneksi Gagal', 
                `Status: ${previousStatus || xhr.status}<br>
                 Error: ${errorMessage}<br>
                 URL: /admin/requests/${requestId}`);
        }
    });
}

// Fungsi untuk render detail permintaan
function renderRequestDetails(request) {
    const isMultiBarang = request.details && request.details.length > 1;
    const totalJumlah = isMultiBarang 
        ? request.details.reduce((sum, detail) => sum + (detail.jumlah || 0), 0)
        : request.jumlah || 0;
    const totalJenis = isMultiBarang ? request.details.length : 1;
    
    // Hitung status
    const approvedDetails = isMultiBarang ? 
        request.details.filter(d => d.status === 'approved').length : 0;
    const rejectedDetails = isMultiBarang ? 
        request.details.filter(d => d.status === 'rejected').length : 0;
    const pendingDetails = isMultiBarang ? 
        request.details.filter(d => !d.status || d.status === 'pending').length : 0;
    
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
                            <div class="col-7">${formatDate(request.created_at)}</div>
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
                            <div class="col-7">${getStatusBadge(request.status, rejectedDetails > 0)}</div>
                        </div>
    `;
    
    // Tampilkan detail status jika multi barang
    if (isMultiBarang) {
        html += `
            <div class="row mb-2">
                <div class="col-5 fw-bold">Detail Status:</div>
                <div class="col-7">
                    <div class="d-flex flex-wrap gap-1">
        `;
        
        if (approvedDetails > 0) {
            html += `<span class="badge bg-success">${approvedDetails} Disetujui</span>`;
        }
        if (rejectedDetails > 0) {
            html += `<span class="badge bg-danger">${rejectedDetails} Ditolak</span>`;
        }
        if (pendingDetails > 0) {
            html += `<span class="badge bg-warning">${pendingDetails} Pending</span>`;
        }
        
        html += `
                    </div>
                </div>
            </div>
        `;
    }
    
    // Tanggal status
    if (request.delivered_at) {
        html += `
            <div class="row mb-2">
                <div class="col-5 fw-bold">Dikirim pada:</div>
                <div class="col-7">${formatDate(request.delivered_at)}</div>
            </div>
        `;
    } else if (request.approved_at) {
        html += `
            <div class="row mb-2">
                <div class="col-5 fw-bold">Disetujui pada:</div>
                <div class="col-7">${formatDate(request.approved_at)}</div>
            </div>
        `;
    } else if (request.rejected_at) {
        html += `
            <div class="row mb-2">
                <div class="col-5 fw-bold">Ditolak pada:</div>
                <div class="col-7">${formatDate(request.rejected_at)}</div>
            </div>
        `;
    }
    
    // Approved by
    if (request.approved_by_user) {
        html += `
            <div class="row">
                <div class="col-5 fw-bold">Disetujui oleh:</div>
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
                    <div class="col-md-${isMultiBarang ? '3' : '6'} mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-primary fw-bold">${totalJumlah}</h4>
                            <small class="text-muted">Total Unit</small>
                        </div>
                    </div>
                    <div class="col-md-${isMultiBarang ? '3' : '6'} mb-3">
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
            <div class="col-md-3 mb-3">
                <div class="border rounded p-3">
                    <h4 class="text-primary fw-bold">${satkerNames.length}</h4>
                    <small class="text-muted">Jumlah Satker</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="border rounded p-3">
                    <h4 class="text-primary fw-bold">${approvedDetails}</h4>
                    <small class="text-muted">Disetujui</small>
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
                                <th width="15%">Status</th>
                                <th width="15%">Satker</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    if (isMultiBarang && request.details && request.details.length > 0) {
        request.details.forEach((detail, index) => {
            let statusBadge = '';
            if (detail.status === 'approved') {
                statusBadge = '<span class="badge bg-success">Disetujui</span>';
            } else if (detail.status === 'rejected') {
                statusBadge = '<span class="badge bg-danger">Ditolak</span>';
            } else {
                statusBadge = '<span class="badge bg-warning">Pending</span>';
            }
            
            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td><span class="badge bg-light text-dark">${detail.barang?.kode_barang || 'BRG-001'}</span></td>
                    <td>${detail.barang?.nama_barang || 'Nama Barang Tidak Tersedia'}</td>
                    <td class="text-center"><strong>${detail.jumlah || 0}</strong></td>
                    <td class="text-center">${detail.barang?.satuan?.nama_satuan || 'unit'}</td>
                    <td>${statusBadge}</td>
                    <td>${detail.satker?.nama_satker || request.satker?.nama_satker || '-'}</td>
                </tr>
            `;
        });
    } else {
        let statusBadge = '';
        if (request.status === 'approved') {
            statusBadge = '<span class="badge bg-success">Disetujui</span>';
        } else if (request.status === 'rejected') {
            statusBadge = '<span class="badge bg-danger">Ditolak</span>';
        } else {
            statusBadge = '<span class="badge bg-warning">Pending</span>';
        }
        
        html += `
            <tr>
                <td class="text-center">1</td>
                <td><span class="badge bg-light text-dark">${request.barang?.kode_barang || 'BRG-001'}</span></td>
                <td>${request.barang?.nama_barang || 'Nama Barang Tidak Tersedia'}</td>
                <td class="text-center"><strong>${request.jumlah || 0}</strong></td>
                <td class="text-center">${request.barang?.satuan?.nama_satuan || 'unit'}</td>
                <td>${statusBadge}</td>
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
                    <p class="mb-0">${request.keperluan || '-'}</p>
                </div>
            `;
        }
        
        if (request.catatan) {
            html += `
                <div>
                    <h6 class="text-muted">Catatan Admin:</h6>
                    <p class="mb-0">${request.catatan || '-'}</p>
                </div>
            `;
        }
        
        html += `
                </div>
            </div>
        `;
    }
    
    $('#detailRequestModalBody').html(html);
}

// Fungsi helper untuk format tanggal
function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    } catch (e) {
        return dateString;
    }
}

// Fungsi helper untuk status badge
function getStatusBadge(status, hasRejected) {
    if (status == 'pending') {
        return `<span class="badge bg-warning"><i class="bi bi-clock-history me-1"></i>Pending</span>`;
    } else if (status == 'approved') {
        if (hasRejected) {
            return `<span class="badge bg-mixed"><i class="bi bi-patch-check me-1"></i>Status Campuran</span>`;
        } else {
            return `<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui</span>`;
        }
    } else if (status == 'rejected') {
        return `<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>`;
    } else if (status == 'delivered') {
        return `<span class="badge bg-info"><i class="bi bi-truck me-1"></i>Dikirim</span>`;
    }
    return `<span class="badge bg-secondary">Unknown</span>`;
}

// Fungsi untuk menampilkan error modal
function showErrorModal(title, message) {
    $('#detailRequestModalBody').html(`
        <div class="text-center py-4">
            <i class="bi bi-exclamation-triangle text-danger display-4"></i>
            <h5 class="mt-3 text-danger">${title}</h5>
            <p class="text-muted">${message}</p>
            <button class="btn btn-primary btn-sm mt-2" onclick="$('#detailRequestModal').modal('hide')">
                <i class="bi bi-x-circle"></i> Tutup
            </button>
        </div>
    `);
}
    });
    </script>
</body>
</html>