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
use Illuminate\Http\Request;

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
    // Dashboard umum
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout - Harus di luar group prefix
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

    // ==================== ROUTES UNTUK USER ====================
    Route::prefix('user')->group(function () {
        // Dashboard user - redirect ke permintaan
        Route::get('/dashboard', function () {
            return redirect()->route('user.permintaan');
        })->name('user.dashboard');
        
        // Laporan Routes - Tambahkan 'user.' prefix
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
    Route::prefix('admin')->group(function () {
        // Dashboard admin
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
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
            Route::get('/{permintaan}', [PermintaanController::class, 'show'])->name('admin.requests.show');
            Route::post('/{permintaan}/approve', [PermintaanController::class, 'approve'])->name('admin.requests.approve');
            Route::post('/{permintaan}/reject', [PermintaanController::class, 'reject'])->name('admin.requests.reject');
            Route::delete('/{permintaan}', [PermintaanController::class, 'destroy'])->name('admin.requests.destroy');
        });
        
        // Reports Routes
        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('admin.reports');
            Route::post('/generate', [ReportController::class, 'generate'])->name('admin.reports.generate');
            Route::get('/export/{type}', [ReportController::class, 'export'])->name('admin.reports.export');
            Route::get('/chart-data', [ReportController::class, 'getChartData'])->name('admin.reports.chart-data');
        });
    });

    // ==================== ROUTES UNTUK KABID ====================
    Route::prefix('kabid')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kabidDashboard'])->name('kabid.dashboard');
        // Tambahkan route kabid lainnya di sini
    });

    // ==================== API ROUTES UNTUK SEMUA USER ====================
    // API untuk pencarian barang (digunakan oleh user dalam form permintaan)
    Route::get('/api/barang/search', function (Request $request) {
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

    // API untuk mendapatkan data barang by ID (untuk edit form)
    Route::get('/api/barang/{id}', function ($id) {
        $barang = \App\Models\Barang::with(['kategori', 'satuan', 'gudang'])
            ->find($id);
        
        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }
        
        return response()->json($barang);
    })->name('api.barang.get');
});

// Fallback Route
Route::fallback(function () {
    return redirect()->route('home');
});