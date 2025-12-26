<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\PermintaanUserController;
use App\Http\Controllers\UserLaporanController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\SatkerController;
use App\Http\Controllers\ActivityLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
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

// Protected Routes - SEMUA user yang login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

    // ==================== ROUTES UNTUK USER ====================
    Route::prefix('user')->group(function () {
        // Dashboard user - redirect ke permintaan
        Route::get('/dashboard', function () {
            return redirect()->route('user.permintaan');
        })->name('user.dashboard');
        
        // Laporan Routes
        Route::get('/laporan', [UserLaporanController::class, 'index'])->name('user.laporan');
        Route::get('/laporan/export/{type}', [UserLaporanController::class, 'export'])->name('user.laporan.export');
        Route::get('/laporan/print', [UserLaporanController::class, 'print'])->name('user.laporan.print');
        
        // Permintaan Routes
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
        });
    });

    // ==================== ROUTES UNTUK ADMIN ====================
    Route::prefix('admin')->middleware(['role:admin,superadmin'])->group(function () {
        // Dashboard admin
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartDataApi'])->name('admin.dashboard.chart-data');

        // Inventory Routes
        Route::prefix('inventory')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('admin.inventory');
            Route::post('/', [InventoryController::class, 'store'])->name('admin.inventory.store');
            Route::get('/{barang}/edit', [InventoryController::class, 'edit'])->name('admin.inventory.edit');
            Route::put('/{barang}', [InventoryController::class, 'update'])->name('admin.inventory.update');
            Route::delete('/{barang}', [InventoryController::class, 'destroy'])->name('admin.inventory.destroy');
            Route::post('/{barang}/restock', [InventoryController::class, 'restock'])->name('admin.inventory.restock');
            Route::get('/{barang}', [InventoryController::class, 'show'])->name('admin.inventory.show');
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
        // Dashboard Superadmin
        Route::get('/dashboard', [DashboardController::class, 'superadminDashboard'])->name('superadmin.dashboard');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'getSuperadminChartData'])->name('superadmin.dashboard.chart-data');
        
        // Accounts Management Routes menggunakan Controller
        Route::prefix('accounts')->group(function () {
            Route::get('/', [AccountsController::class, 'index'])->name('superadmin.accounts.index');
            Route::get('/create', [AccountsController::class, 'create'])->name('superadmin.accounts.create');
            Route::post('/', [AccountsController::class, 'store'])->name('superadmin.accounts.store');
            Route::get('/{user}', [AccountsController::class, 'show'])->name('superadmin.accounts.show');
            Route::get('/{user}/edit', [AccountsController::class, 'edit'])->name('superadmin.accounts.edit');
            Route::put('/{user}', [AccountsController::class, 'update'])->name('superadmin.accounts.update');
            Route::delete('/{user}', [AccountsController::class, 'destroy'])->name('superadmin.accounts.destroy');
            
            // Additional routes
            Route::post('/{user}/toggle-status', [AccountsController::class, 'toggleStatus'])
                ->name('superadmin.accounts.toggle-status');
            
            Route::post('/bulk-action', [AccountsController::class, 'bulkAction'])
                ->name('superadmin.accounts.bulk-action');
            
            Route::post('/{user}/reset-password', [AccountsController::class, 'resetPassword'])
                ->name('superadmin.accounts.reset-password');
            
            Route::get('/{user}/activity-logs', [AccountsController::class, 'activityLogs'])
                ->name('superadmin.accounts.activity-logs');
        });
        
        // Manajemen Satker untuk Superadmin
        Route::prefix('satker')->group(function () {
            // Index page
            Route::get('/', [SatkerController::class, 'index'])->name('superadmin.satker.index');
            
            // Create page
            Route::get('/create', [SatkerController::class, 'create'])->name('superadmin.satker.create');
            
            // Store new satker
            Route::post('/', [SatkerController::class, 'store'])->name('superadmin.satker.store');
            
            // Show satker details (AJAX)
            Route::get('/{id}', [SatkerController::class, 'show'])->name('superadmin.satker.show');
            
            // Edit page
            Route::get('/{satker}/edit', [SatkerController::class, 'edit'])->name('superadmin.satker.edit');
            
            // Update satker
            Route::put('/{satker}', [SatkerController::class, 'update'])->name('superadmin.satker.update');
            
            // Delete satker
            Route::delete('/{satker}', [SatkerController::class, 'destroy'])->name('superadmin.satker.destroy');
            
            // AJAX routes untuk fitur tambahan
            Route::get('/{id}/details', [SatkerController::class, 'getDetails'])->name('superadmin.satker.details');
            Route::get('/select-options', [SatkerController::class, 'getSatkersForSelect'])->name('superadmin.satker.select-options');
            Route::post('/search', [SatkerController::class, 'search'])->name('superadmin.satker.search');
            Route::get('/statistics', [SatkerController::class, 'getStatistics'])->name('superadmin.satker.statistics');
            Route::get('/{satker}/check-users', [SatkerController::class, 'checkHasUsers'])->name('superadmin.satker.check-users');
        });
        
        // Log Aktivitas - menggunakan Controller
        Route::prefix('activity-logs')->group(function () {
            // Index page
            Route::get('/', [ActivityLogController::class, 'index'])->name('superadmin.activity-logs');
            
            // Show log details (AJAX)
            Route::get('/{id}', [ActivityLogController::class, 'show'])->name('superadmin.activity-logs.show');
            
            // Clear all logs
            Route::post('/clear', [ActivityLogController::class, 'clear'])->name('superadmin.activity-logs.clear');
            
            // Export logs
            Route::get('/export', [ActivityLogController::class, 'export'])->name('superadmin.activity-logs.export');
        });
        
        // Pengaturan Sistem - simple route
        Route::get('/settings', function() {
            $user = auth()->user();
            return view('superadmin.settings.index', compact('user'));
        })->name('superadmin.settings');
        
        // Laporan - simple route
        Route::get('/reports', function() {
            $user = auth()->user();
            return view('superadmin.reports.index', compact('user'));
        })->name('superadmin.reports');
    });

    // ==================== API ROUTES UNTUK SEMUA USER ====================
    Route::get('/api/barang/search', function (\Illuminate\Http\Request $request) {
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

    Route::get('/api/barang/{id}', function ($id) {
        $barang = \App\Models\Barang::with(['kategori', 'satuan', 'gudang'])
            ->find($id);        
        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }
        return response()->json($barang);
    })->name('api.barang.get');
    
    // API Routes untuk Satker
    Route::prefix('api')->group(function () {
        Route::get('/satker/{id}/details', [SatkerController::class, 'getDetails'])->name('api.satker.details');
        Route::get('/satker/select-options', [SatkerController::class, 'getSatkersForSelect'])->name('api.satker.select-options');
        Route::post('/satker/search', [SatkerController::class, 'search'])->name('api.satker.search');
    });
});

// Fallback Route
Route::fallback(function () {
    return redirect()->route('home');
});