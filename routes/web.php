<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\PermintaanUserController;
use App\Http\Controllers\UserLaporanController;
use App\Http\Controllers\SatkerController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SuperadminReportsController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\SuperadminProcurementController; // Ditambahkan

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ==================== MAINTENANCE BYPASS ROUTES ====================
Route::get('/maintenance-bypass/{secret}', function ($secret) {
    if ($secret === 'silog-maintenance-2024') {
        session(['maintenance_bypass' => true]);
        return redirect('/login')->with('success', 'Maintenance bypass berhasil. Silakan login sebagai superadmin.');
    }
    abort(404);
})->name('maintenance.bypass');

// ==================== AUTHENTICATION ROUTES ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ==================== PROTECTED ROUTES DENGAN MAINTENANCE CHECK ====================
Route::middleware(['auth', 'check.maintenance'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

    // ==================== ROUTES UNTUK USER ====================
    Route::prefix('user')->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('user.permintaan');
        })->name('user.dashboard');
        
        Route::get('/laporan', [UserLaporanController::class, 'index'])->name('user.laporan');
        Route::get('/laporan/export/{type}', [UserLaporanController::class, 'export'])->name('user.laporan.export');
        Route::get('/laporan/print', [UserLaporanController::class, 'print'])->name('user.laporan.print');
        
        Route::prefix('permintaan')->group(function () {
            Route::get('/', [PermintaanUserController::class, 'index'])->name('user.permintaan');
            Route::get('/create', [PermintaanUserController::class, 'create'])->name('user.permintaan.create');
            Route::post('/', [PermintaanUserController::class, 'store'])->name('user.permintaan.store');
            Route::get('/{id}', [PermintaanUserController::class, 'show'])->name('user.permintaan.show');
            Route::get('/{id}/edit', [PermintaanUserController::class, 'edit'])->name('user.permintaan.edit');
            Route::put('/{id}', [PermintaanUserController::class, 'update'])->name('user.permintaan.update');
            Route::delete('/{id}', [PermintaanUserController::class, 'destroy'])->name('user.permintaan.destroy');
            Route::get('/track/{kode_permintaan}', [PermintaanUserController::class, 'track'])->name('user.permintaan.track');
            Route::get('/cetak/print', [PermintaanUserController::class, 'cetak'])->name('user.permintaan.cetak');
            Route::get('/barang/{id}/stok', [PermintaanUserController::class, 'getStokBarang'])
                ->name('user.permintaan.barang.stok');
        });
    });

    // ==================== ROUTES UNTUK ADMIN ====================
    Route::prefix('admin')->middleware(['role:admin,superadmin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartDataApi'])->name('admin.dashboard.chart-data');

        // Inventory Routes
        Route::prefix('inventory')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('admin.inventory');
            Route::get('/create', [InventoryController::class, 'create'])->name('admin.inventory.create');
            Route::post('/', [InventoryController::class, 'store'])->name('admin.inventory.store');
            Route::get('/{barang}/edit', [InventoryController::class, 'edit'])->name('admin.inventory.edit');
            Route::put('/{barang}', [InventoryController::class, 'update'])->name('admin.inventory.update');
            Route::delete('/{barang}', [InventoryController::class, 'destroy'])->name('admin.inventory.destroy');
            Route::post('/{barang}/restock', [InventoryController::class, 'restock'])->name('admin.inventory.restock');
            Route::get('/{barang}', [InventoryController::class, 'show'])->name('admin.inventory.show');
            
            Route::post('/store-pengadaan', [InventoryController::class, 'storePengadaan'])
                ->name('admin.inventory.store.pengadaan');
            
            Route::get('/get-barang-procurement', [InventoryController::class, 'getBarangForProcurement'])
                ->name('admin.inventory.get.barang.procurement');
            
            Route::get('/get-barang-detail/{id}', [InventoryController::class, 'getBarangDetail'])
                ->name('admin.inventory.get.barang.detail');
            
            Route::get('/get-recent-procurements', [InventoryController::class, 'getRecentProcurements'])
                ->name('admin.inventory.get.recent.procurements');
            
            Route::post('/category/quick-store', [InventoryController::class, 'quickStoreCategory'])
                ->name('admin.inventory.category.quick-store');
            
            Route::get('/search', [InventoryController::class, 'search'])->name('admin.inventory.search');
            
            Route::get('/get-by-kode/{kode}', [InventoryController::class, 'getBarangByKode'])
                ->name('admin.inventory.get-by-kode');
        });
        
        // Procurement Routes (Pengadaan Barang)
        Route::prefix('procurement')->group(function () {
            Route::get('/', [ProcurementController::class, 'index'])->name('admin.procurement');
            Route::get('/create', [ProcurementController::class, 'create'])->name('admin.procurement.create');
            Route::post('/', [ProcurementController::class, 'store'])->name('admin.procurement.store');
            Route::get('/{procurement}', [ProcurementController::class, 'show'])->name('admin.procurement.show');
            Route::get('/{procurement}/edit', [ProcurementController::class, 'edit'])->name('admin.procurement.edit');
            Route::put('/{procurement}', [ProcurementController::class, 'update'])->name('admin.procurement.update');
            Route::delete('/{procurement}', [ProcurementController::class, 'destroy'])->name('admin.procurement.destroy');
            
            // Action routes untuk mengubah status
            Route::post('/{procurement}/approve', [ProcurementController::class, 'approve'])->name('admin.procurement.approve');
            Route::post('/{procurement}/reject', [ProcurementController::class, 'reject'])->name('admin.procurement.reject');
            Route::post('/{procurement}/process', [ProcurementController::class, 'process'])->name('admin.procurement.process');
            Route::post('/{procurement}/complete', [ProcurementController::class, 'complete'])->name('admin.procurement.complete');
            Route::post('/{procurement}/cancel', [ProcurementController::class, 'cancel'])->name('admin.procurement.cancel');
            
            // API routes untuk AJAX
            Route::get('/barang-ajax', [ProcurementController::class, 'getBarangForSelect'])->name('admin.procurement.barang-ajax');
            Route::get('/recent-ajax', [ProcurementController::class, 'getRecentProcurements'])->name('admin.procurement.recent-ajax');
        });
        
        // Category Routes
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.categories.index');
            Route::post('/', [CategoryController::class, 'store'])->name('admin.categories.store');
            Route::post('/quick-store', [CategoryController::class, 'quickStore'])->name('admin.categories.quick-store');
            Route::get('/{kategori}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
            Route::put('/{kategori}', [CategoryController::class, 'update'])->name('admin.categories.update');
            Route::delete('/{kategori}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        });
        
        // Permintaan (Requests) Routes
        Route::prefix('requests')->group(function () {
            Route::get('/', [PermintaanController::class, 'index'])->name('admin.requests');
            Route::get('/create', [PermintaanController::class, 'create'])->name('admin.requests.create');
            Route::post('/', [PermintaanController::class, 'store'])->name('admin.requests.store');
            Route::post('/{permintaan}/approve', [PermintaanController::class, 'approve'])->name('admin.requests.approve');
            Route::post('/{permintaan}/reject', [PermintaanController::class, 'reject'])->name('admin.requests.reject');
            Route::post('/{permintaan}/deliver', [PermintaanController::class, 'markAsDelivered'])->name('admin.requests.deliver');
            Route::delete('/{permintaan}', [PermintaanController::class, 'destroy'])->name('admin.requests.destroy');
            Route::get('/{permintaan}', [PermintaanController::class, 'show'])->name('admin.requests.show');
        });
        
        // Reports Routes
        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('admin.reports');
            Route::get('/generate', [ReportController::class, 'generate'])->name('admin.reports.generate');
            Route::get('/export/{type?}', [ReportController::class, 'export'])->name('admin.reports.export');
            Route::get('/get-monthly-stats', [ReportController::class, 'getMonthlyStats'])->name('admin.reports.get-monthly-stats');
            Route::get('/view-details', [ReportController::class, 'viewDetails'])->name('admin.reports.view-details');
            Route::get('/get-chart-data', [ReportController::class, 'getChartData'])->name('admin.reports.get-chart-data');
        });
        
        // Satker Routes
        Route::prefix('satker')->group(function () {
            Route::get('/', [SatkerController::class, 'index'])->name('admin.satker');
            Route::post('/', [SatkerController::class, 'store'])->name('admin.satker.store');
            Route::put('/{satker}', [SatkerController::class, 'update'])->name('admin.satker.update');
            Route::delete('/{satker}', [SatkerController::class, 'destroy'])->name('admin.satker.destroy');
            Route::get('/{id}/details', [SatkerController::class, 'getDetails'])->name('admin.satker.details');
            Route::post('/{satker}/toggle-status', [SatkerController::class, 'toggleStatus'])->name('admin.satker.toggle-status');
            Route::get('/{satker}/edit', [SatkerController::class, 'edit'])->name('admin.satker.edit');
            Route::get('/create', [SatkerController::class, 'create'])->name('admin.satker.create');
            Route::get('/{id}', [SatkerController::class, 'show'])->name('admin.satker.show');
        });
    });
    
    // Category Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::post('/quick-store', [CategoryController::class, 'quickStore'])->name('admin.categories.quick-store');
        Route::get('/{kategori}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/{kategori}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/{kategori}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    });
    
    // Permintaan (Requests) Routes - untuk admin mengelola permintaan
    Route::prefix('requests')->group(function () {
        // ROUTE DENGAN METHOD GET HARUS DIDAHULUKAN
        Route::get('/', [PermintaanController::class, 'index'])->name('admin.requests');
        Route::get('/create', [PermintaanController::class, 'create'])->name('admin.requests.create');
        Route::get('/{permintaan}', [PermintaanController::class, 'show'])->name('admin.requests.show');
        Route::get('/{permintaan}/details', [PermintaanController::class, 'show'])->name('admin.requests.details');
        Route::get('/{permintaan}/debug', [PermintaanController::class, 'debugShow'])->name('admin.requests.debug');
        
        // ROUTE DENGAN METHOD POST/PUT/DELETE SETELAHNYA
        Route::post('/', [PermintaanController::class, 'store'])->name('admin.requests.store');
        Route::post('/{permintaan}/approve', [PermintaanController::class, 'approve'])->name('admin.requests.approve');
        Route::post('/{permintaan}/reject', [PermintaanController::class, 'reject'])->name('admin.requests.reject');
        Route::post('/{permintaan}/details/{detail}/approve', [PermintaanController::class, 'approveDetail'])
            ->name('admin.requests.details.approve');
        Route::post('/{permintaan}/details/{detail}/reject', [PermintaanController::class, 'rejectDetail'])
            ->name('admin.requests.details.reject');
        Route::post('/{permintaan}/deliver', [PermintaanController::class, 'markAsDelivered'])->name('admin.requests.deliver');
        Route::delete('/{permintaan}', [PermintaanController::class, 'destroy'])->name('admin.requests.destroy');
    });
    
    // Reports Routes - TAMBAHKAN ROUTE GET-REQUEST-DETAILS
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('admin.reports');
        Route::get('/generate', [ReportController::class, 'generate'])->name('admin.reports.generate');
        Route::get('/export/{type?}', [ReportController::class, 'export'])->name('admin.reports.export');
        Route::get('/get-monthly-stats', [ReportController::class, 'getMonthlyStats'])->name('admin.reports.get-monthly-stats');
        Route::get('/view-details', [ReportController::class, 'viewDetails'])->name('admin.reports.view-details');
        Route::get('/get-chart-data', [ReportController::class, 'getChartData'])->name('admin.reports.get-chart-data');
        // TAMBAH ROUTE INI UNTUK DETAIL PERMINTAAN
        Route::get('/get-request-details', [ReportController::class, 'getRequestDetails'])->name('admin.reports.get-request-details');
    });
    
    // Satker Routes untuk Admin
    Route::prefix('satker')->group(function () {
        Route::get('/', [SatkerController::class, 'index'])->name('admin.satker');
        Route::post('/', [SatkerController::class, 'store'])->name('admin.satker.store');
        Route::put('/{satker}', [SatkerController::class, 'update'])->name('admin.satker.update');
        Route::delete('/{satker}', [SatkerController::class, 'destroy'])->name('admin.satker.destroy');
        Route::get('/{id}/details', [SatkerController::class, 'getDetails'])->name('admin.satker.details');
        Route::post('/{satker}/toggle-status', [SatkerController::class, 'toggleStatus'])->name('admin.satker.toggle-status');
        Route::get('/{satker}/edit', [SatkerController::class, 'edit'])->name('admin.satker.edit');
        Route::get('/create', [SatkerController::class, 'create'])->name('admin.satker.create');
        Route::get('/{id}', [SatkerController::class, 'show'])->name('admin.satker.show');
    });
});

    // ==================== ROUTES UNTUK SUPERADMIN ====================
    Route::prefix('superadmin')->middleware(['role:superadmin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'superadminDashboard'])->name('superadmin.dashboard');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'getSuperadminChartData'])->name('superadmin.dashboard.chart-data');
        
        // Accounts Management
        Route::prefix('accounts')->group(function () {
            Route::get('/', [AccountsController::class, 'index'])->name('superadmin.accounts.index');
            Route::get('/create', [AccountsController::class, 'create'])->name('superadmin.accounts.create');
            Route::post('/', [AccountsController::class, 'store'])->name('superadmin.accounts.store');
            Route::get('/{user}', [AccountsController::class, 'show'])->name('superadmin.accounts.show');
            Route::get('/{user}/edit', [AccountsController::class, 'edit'])->name('superadmin.accounts.edit');
            Route::put('/{user}', [AccountsController::class, 'update'])->name('superadmin.accounts.update');
            Route::delete('/{user}', [AccountsController::class, 'destroy'])->name('superadmin.accounts.destroy');
            
            Route::post('/{user}/toggle-status', [AccountsController::class, 'toggleStatus'])
                ->name('superadmin.accounts.toggle-status');
            
            Route::post('/{user}/reset-password', [AccountsController::class, 'resetPassword'])
                ->name('superadmin.accounts.reset-password');
        });
        
        // Validasi Pengadaan untuk Superadmin
        Route::prefix('procurement')->group(function () {
            Route::get('/', [SuperadminProcurementController::class, 'index'])->name('superadmin.procurement');
            Route::get('/{id}', [SuperadminProcurementController::class, 'show'])->name('superadmin.procurement.show');
            Route::post('/{id}/approve', [SuperadminProcurementController::class, 'approve'])->name('superadmin.procurement.approve');
            Route::post('/{id}/reject', [SuperadminProcurementController::class, 'reject'])->name('superadmin.procurement.reject');
            
            // Route untuk chart data
            Route::get('/chart-data', [SuperadminProcurementController::class, 'getChartData'])->name('superadmin.procurement.chart-data');
        });
        
        // Validasi Permintaan untuk Superadmin (diasumsikan ada controller yang sesuai)
        Route::prefix('requests')->group(function () {
            Route::get('/', [PermintaanController::class, 'index'])->name('superadmin.requests'); // Sesuaikan dengan controller yang tepat
        });
        
        // Manajemen Satker
        Route::prefix('satker')->group(function () {
            Route::get('/', [SatkerController::class, 'index'])->name('superadmin.satker.index');
            Route::get('/create', [SatkerController::class, 'create'])->name('superadmin.satker.create');
            Route::post('/', [SatkerController::class, 'store'])->name('superadmin.satker.store');
            Route::get('/{id}', [SatkerController::class, 'show'])->name('superadmin.satker.show');
            Route::get('/{satker}/edit', [SatkerController::class, 'edit'])->name('superadmin.satker.edit');
            Route::put('/{satker}', [SatkerController::class, 'update'])->name('superadmin.satker.update');
            Route::delete('/{satker}', [SatkerController::class, 'destroy'])->name('superadmin.satker.destroy');
            
            Route::get('/{id}/details', [SatkerController::class, 'getDetails'])->name('superadmin.satker.details');
            Route::get('/select-options', [SatkerController::class, 'getSatkersForSelect'])->name('superadmin.satker.select-options');
        });
        
        // Log Aktivitas
        Route::prefix('activity-logs')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('superadmin.activity-logs');
            Route::get('/{id}', [ActivityLogController::class, 'show'])->name('superadmin.activity-logs.show');
            Route::post('/clear', [ActivityLogController::class, 'clear'])->name('superadmin.activity-logs.clear');
            Route::get('/export', [ActivityLogController::class, 'export'])->name('superadmin.activity-logs.export');
        });
        
        // Pengaturan Sistem
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('superadmin.settings');
            
            // Profil
            Route::put('/profile', [SettingController::class, 'updateProfile'])->name('superadmin.update-profile');
            Route::put('/password', [SettingController::class, 'changePassword'])->name('superadmin.change-password');
            
            // Backup Database
            Route::get('/export-database', [SettingController::class, 'exportDatabase'])->name('superadmin.export-database');
            Route::get('/download-backup/{filename}', [SettingController::class, 'downloadBackup'])->name('superadmin.download-backup');
            Route::delete('/delete-backup/{filename}', [SettingController::class, 'deleteBackup'])->name('superadmin.delete-backup');
            Route::post('/restore-database', [SettingController::class, 'restoreDatabase'])->name('superadmin.restore-database');
            
            // Import Data
            Route::post('/import-users', [SettingController::class, 'importUsers'])->name('superadmin.import-users');
            Route::post('/import-satker', [SettingController::class, 'importSatker'])->name('superadmin.import-satker');
            Route::get('/download-template/{type}', [SettingController::class, 'downloadTemplate'])->name('superadmin.download-template');
            
            // System Maintenance
            Route::post('/maintenance/enable', [SettingController::class, 'enableMaintenance'])
                ->name('superadmin.maintenance.enable');
            
            Route::post('/maintenance/disable', [SettingController::class, 'disableMaintenance'])
                ->name('superadmin.maintenance.disable');
            
            Route::get('/maintenance/status', [SettingController::class, 'checkMaintenanceStatus'])
                ->name('superadmin.maintenance.status');
            
            // System maintenance lainnya
            Route::post('/clear-cache', [SettingController::class, 'clearCache'])->name('superadmin.clear-cache');
            Route::post('/optimize-database', [SettingController::class, 'optimizeDatabase'])->name('superadmin.optimize-database');
            
            // Export Data
            Route::get('/export/users', [SettingController::class, 'exportUsers'])->name('superadmin.export-users');
            Route::get('/export/satker', [SettingController::class, 'exportSatker'])->name('superadmin.export-satker');
            
            // General Settings
            Route::put('/update-general', [SettingController::class, 'updateGeneral'])->name('superadmin.update-general');
            Route::put('/update-email', [SettingController::class, 'updateEmail'])->name('superadmin.update-email');
        });
        
        // Reports untuk Superadmin
        Route::prefix('reports')->group(function () {
            Route::get('/', [SuperadminReportsController::class, 'index'])->name('superadmin.reports');
            Route::get('/generate-pdf', [SuperadminReportsController::class, 'generatePdf'])->name('superadmin.reports.generate-pdf');
            Route::get('/export-excel', [SuperadminReportsController::class, 'exportExcel'])->name('superadmin.reports.export-excel');
            Route::get('/get-chart-data', [SuperadminReportsController::class, 'getChartData'])->name('superadmin.reports.get-chart-data');
            Route::get('/reset-filter', [SuperadminReportsController::class, 'resetFilter'])->name('superadmin.reports.reset-filter');
        });
    });

    // ==================== API ROUTES UNTUK SEMUA USER ====================
    Route::prefix('api')->group(function () {
        // Route untuk pencarian barang
        Route::get('/barang/search', function (\Illuminate\Http\Request $request) {
            $query = $request->get('q');
            $barang = \App\Models\Barang::with(['kategori', 'satuan', 'gudang'])
                ->where('stok', '>', 0)
                ->where(function($q) use ($query) {
                    $q->where('nama_barang', 'LIKE', "%{$query}%")
                      ->orWhere('kode_barang', 'LIKE', "%{$query}%");
                })
                ->orderBy('nama_barang')
                ->limit(10)
                ->get();
            return response()->json($barang);
        })->name('api.barang.search');

        // Route untuk mendapatkan detail barang
        Route::get('/barang/{id}', function ($id) {
            $barang = \App\Models\Barang::with(['kategori', 'satuan', 'gudang'])
                ->find($id);        
            if (!$barang) {
                return response()->json(['error' => 'Barang tidak ditemukan'], 404);
            }
            return response()->json($barang);
        })->name('api.barang.get');
        
        // API Routes untuk Satker
        Route::get('/satker/{id}/details', [SatkerController::class, 'getDetails'])->name('api.satker.details');
        Route::get('/satker/select-options', [SatkerController::class, 'getSatkersForSelect'])->name('api.satker.select-options');
        
        Route::get('/inventory/get-barang-procurement', [InventoryController::class, 'getBarangForProcurement'])
            ->name('api.inventory.get.barang.procurement');
            
        Route::get('/inventory/get-recent-procurements', [InventoryController::class, 'getRecentProcurements'])
            ->name('api.inventory.get.recent.procurements');
    });


// ==================== MAINTENANCE ROUTES (KHUSUS) ====================
Route::middleware('auth')->group(function () {
    Route::post('/settings/maintenance/enable', [SettingController::class, 'enableMaintenance'])
        ->name('settings.maintenance.enable')
        ->middleware('role:superadmin');
    
    Route::post('/settings/maintenance/disable', [SettingController::class, 'disableMaintenance'])
        ->name('settings.maintenance.disable')
        ->middleware('role:superadmin');
    
    Route::get('/settings/maintenance/status', [SettingController::class, 'checkMaintenanceStatus'])
        ->name('settings.maintenance.status')
        ->middleware('role:superadmin');
});

// ==================== MAINTENANCE PAGE ROUTE ====================
Route::get('/maintenance', function () {
    $filePath = storage_path('framework/down');
    if (file_exists($filePath)) {
        $data = json_decode(file_get_contents($filePath), true);
        $message = $data['message'] ?? 'Sistem sedang dalam pemeliharaan. Harap coba lagi beberapa saat lagi.';
        $retry = $data['retry'] ?? 60;
        $secret = $data['secret'] ?? null;
        
        return response()->view('errors.maintenance', [
            'message' => $message,
            'retry' => $retry,
            'secret' => $secret
        ], 503);
    }
    
    return response()->view('errors.maintenance', [
        'message' => 'Sistem sedang dalam pemeliharaan.',
        'retry' => 60
    ], 503);
})->name('maintenance');

// Fallback Route
Route::fallback(function () {
    $filePath = storage_path('framework/down');
    if (file_exists($filePath)) {
        return redirect()->route('maintenance');
    }
    return redirect()->route('home');
});