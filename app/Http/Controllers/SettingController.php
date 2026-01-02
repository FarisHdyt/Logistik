<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Models\RestoreHistory;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan sistem
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $backupHistory = $this->getBackupHistory();
        $restoreHistory = $this->getRestoreHistory();
        $systemInfo = $this->getSystemInfo();
        $serverInfo = $this->getServerInfo();
        
        $activeTab = $request->get('active_tab', 'profile');
        
        return view('superadmin.settings', compact(
            'user', 
            'backupHistory',
            'restoreHistory',
            'systemInfo', 
            'serverInfo',
            'activeTab'
        ));
    }
    
    /**
     * Update profil superadmin
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('superadmin.settings', ['active_tab' => 'profile'])
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
            ]);
            
            return redirect()->route('superadmin.settings', ['active_tab' => 'profile'])
                ->with('success', 'Profil berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.settings', ['active_tab' => 'profile'])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Ubah password superadmin
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('superadmin.settings', ['active_tab' => 'profile'])
                ->withErrors($validator)
                ->withInput();
        }
        
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('superadmin.settings', ['active_tab' => 'profile'])
                ->with('error', 'Password saat ini salah.');
        }
        
        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            return redirect()->route('superadmin.settings', ['active_tab' => 'profile'])
                ->with('success', 'Password berhasil diubah.');
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.settings', ['active_tab' => 'profile'])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Ekspor database - HANYA FORMAT JSON
     */
    public function exportDatabase(Request $request)
    {
        $format = 'json'; // Format tetap JSON saja
        
        try {
            return $this->exportAsJSON();
        } catch (\Exception $e) {
            return redirect()->route('superadmin.settings', ['active_tab' => 'database'])
                ->with('error', 'Gagal mengekspor database: ' . $e->getMessage());
        }
    }
    
    /**
     * Download backup file
     */
    public function downloadBackup(Request $request, $filename)
    {
        $filePath = storage_path('app/backups/' . $filename);
        
        if (!File::exists($filePath)) {
            return redirect()->route('superadmin.settings', ['active_tab' => 'database'])
                ->with('error', 'File backup tidak ditemukan.');
        }
        
        return response()->download($filePath);
    }
    
    /**
     * Hapus backup file
     */
    public function deleteBackup(Request $request, $filename)
    {
        try {
            $filePath = storage_path('app/backups/' . $filename);
            
            if (File::exists($filePath)) {
                File::delete($filePath);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Backup berhasil dihapus.'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'File backup tidak ditemukan.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Restore database - HANYA FORMAT JSON
     */
    public function restoreDatabase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restore_file' => 'required|file|mimes:json|max:102400', // Hanya JSON
            'restore_method' => 'required|in:append,replace',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('superadmin.settings', ['active_tab' => 'database'])
                ->withErrors($validator)
                ->withInput();
        }
        
        set_time_limit(300);
        ini_set('memory_limit', '512M');
        
        try {
            $user = Auth::user();
            $file = $request->file('restore_file');
            $originalName = $file->getClientOriginalName();
            $extension = 'json'; // Format tetap JSON
            $method = $request->restore_method;
            $fileSize = $file->getSize();
            
            \Log::info("Mulai restore JSON: {$originalName}, metode: {$method}");
            \Log::info("File size: " . $this->formatBytes($fileSize));
            
            // Simpan file temporary
            $tempPath = $file->storeAs('temp', 'restore_' . time() . '.json');
            $fullPath = storage_path('app/' . $tempPath);
            
            $result = $this->restoreJSON($fullPath, $method);
            
            // Hapus file temporary
            File::delete($fullPath);
            
            // Simpan history restore
            $this->saveRestoreHistory([
                'filename' => $originalName,
                'format' => $extension,
                'size' => $this->formatBytes($fileSize),
                'method' => $method,
                'total_rows' => $result['total_rows'] ?? 0,
                'inserted_rows' => $result['inserted_rows'] ?? 0,
                'skipped_rows' => $result['skipped_rows'] ?? 0,
                'status' => 'success',
                'message' => 'Database berhasil direstore',
                'user_id' => $user->id,
            ]);
            
            return redirect()->route('superadmin.settings', ['active_tab' => 'database'])
                ->with('success', 'Database berhasil direstore! ' . 
                    ($result['inserted_rows'] > 0 ? $result['inserted_rows'] . ' data dipulihkan.' : ''));
                
        } catch (\Exception $e) {
            \Log::error('Restore JSON gagal: ' . $e->getMessage());
            
            // Simpan history restore gagal
            if (isset($user) && isset($originalName) && isset($method)) {
                $fileSize = $request->file('restore_file')->getSize() ?? 0;
                
                $this->saveRestoreHistory([
                    'filename' => $originalName,
                    'format' => 'json',
                    'size' => $this->formatBytes($fileSize),
                    'method' => $method,
                    'total_rows' => 0,
                    'inserted_rows' => 0,
                    'skipped_rows' => 0,
                    'status' => 'failed',
                    'message' => 'Gagal restore: ' . $e->getMessage(),
                    'user_id' => $user->id,
                ]);
            }
            
            return redirect()->route('superadmin.settings', ['active_tab' => 'database'])
                ->with('error', 'Gagal restore database: ' . $e->getMessage());
        }
    }
    
    /**
     * Restore dari file backup JSON yang sudah ada
     */
    public function restoreFromBackup(Request $request, $filename)
    {
        try {
            $user = Auth::user();
            $filePath = storage_path('app/backups/' . $filename);
            
            if (!File::exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File backup tidak ditemukan.'
                ]);
            }
            
            // Validasi ekstensi file (harus JSON)
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            if (strtolower($extension) !== 'json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Format file harus JSON.'
                ]);
            }
            
            $method = $request->get('method', 'replace');
            
            \Log::info("Restore dari backup file: {$filename}, metode: {$method}");
            
            $result = $this->restoreJSON($filePath, $method);
            
            // Simpan history restore
            $this->saveRestoreHistory([
                'filename' => $filename,
                'format' => 'json',
                'size' => $this->formatBytes(File::size($filePath)),
                'method' => $method,
                'total_rows' => $result['total_rows'] ?? 0,
                'inserted_rows' => $result['inserted_rows'] ?? 0,
                'skipped_rows' => $result['skipped_rows'] ?? 0,
                'status' => 'success',
                'message' => 'Database berhasil direstore dari backup',
                'user_id' => $user->id,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Database berhasil direstore dari backup.',
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Restore dari backup gagal: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal restore: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Aktifkan maintenance mode - DIPERBAIKI untuk superadmin access
     */
    public function enableMaintenance(Request $request)
    {
        try {
            // Cek apakah sudah dalam mode maintenance
            if (app()->isDownForMaintenance()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sistem sudah dalam mode maintenance.'
                ]);
            }
            
            $user = Auth::user();
            $message = $request->get('message', 'Sistem sedang dalam pemeliharaan. Harap coba lagi beberapa saat lagi.');
            
            // Buat array data untuk maintenance mode
            $data = [
                'time' => time(),
                'retry' => 60,
                'secret' => 'silog-maintenance-2024',
                'message' => $message,
                'allowed' => [] // Kosongkan allowed untuk umum
            ];
            
            // Tambahkan superadmin ke daftar yang diizinkan berdasarkan IP atau User ID
            // Cara 1: Izinkan berdasarkan IP saat ini
            if ($request->ip()) {
                $data['allowed'][] = $request->ip();
            }
            
            // Cara 2: Izinkan berdasarkan user ID superadmin (untuk cookie bypass)
            $superadminUserId = $user->id;
            $data['superadmin_id'] = $superadminUserId;
            
            // Cara 3: Izinkan rute tertentu untuk superadmin
            $superadminRoutes = [
                '/login',
                '/superadmin/*',
                '/settings/*',
                '/logout'
            ];
            $data['superadmin_routes'] = $superadminRoutes;
            
            // Buat file maintenance dengan konfigurasi custom
            $filePath = storage_path('framework/down');
            
            // Pastikan direktori ada
            if (!File::isDirectory(dirname($filePath))) {
                File::makeDirectory(dirname($filePath), 0755, true);
            }
            
            file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
            
            \Log::info('Maintenance mode diaktifkan oleh: ' . $user->name . ' (ID: ' . $user->id . ')');
            \Log::info('IP yang diizinkan: ' . $request->ip());
            
            // Buat cookie bypass untuk superadmin (opsional, untuk akses lebih mudah)
            $bypassCookie = cookie(
                'maintenance_bypass',
                encrypt(json_encode(['user_id' => $user->id, 'expires' => time() + 3600])),
                60, // 60 menit
                null,
                null,
                false, // Secure
                true   // HttpOnly
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode berhasil diaktifkan. Superadmin tetap dapat mengakses sistem.',
                'secret' => 'silog-maintenance-2024',
                'bypass_ip' => $request->ip()
            ])->cookie($bypassCookie);
            
        } catch (\Exception $e) {
            \Log::error('Gagal mengaktifkan maintenance mode: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan maintenance mode: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Nonaktifkan maintenance mode
     */
    public function disableMaintenance()
    {
        try {
            // Cek apakah sedang dalam mode maintenance
            if (!app()->isDownForMaintenance()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sistem sudah dalam mode normal.'
                ]);
            }
            
            // Hapus file maintenance
            $filePath = storage_path('framework/down');
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            
            // Juga panggil Artisan up untuk memastikan
            Artisan::call('up');
            
            \Log::info('Maintenance mode dinonaktifkan oleh: ' . Auth::user()->name);
            
            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode berhasil dinonaktifkan.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Gagal menonaktifkan maintenance mode: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan maintenance mode: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Cek status maintenance mode
     */
    public function checkMaintenanceStatus()
    {
        try {
            $isDown = app()->isDownForMaintenance();
            $details = null;
            
            if ($isDown) {
                $filePath = storage_path('framework/down');
                if (File::exists($filePath)) {
                    $content = File::get($filePath);
                    $details = json_decode($content, true);
                }
            }
            
            // Cek apakah user saat ini adalah superadmin
            $user = Auth::user();
            $isSuperadmin = $user && $user->role === 'superadmin';
            
            return response()->json([
                'success' => true,
                'is_maintenance' => $isDown,
                'is_superadmin' => $isSuperadmin,
                'can_access' => $isSuperadmin && $isDown, // Superadmin dapat akses meski maintenance
                'message' => $isDown ? 'Sistem sedang dalam mode maintenance' : 'Sistem berjalan normal',
                'details' => $details
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status maintenance: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Bypass maintenance mode untuk superadmin
     */
    public function bypassMaintenance(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Pastikan hanya superadmin yang bisa bypass
            if (!$user || $user->role !== 'superadmin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya superadmin yang dapat bypass maintenance mode.'
                ]);
            }
            
            // Cek beberapa metode bypass
            $secret = $request->get('secret', '');
            $currentSecret = 'silog-maintenance-2024';
            
            // Metode 1: Secret key
            if ($secret === $currentSecret) {
                // Buat session bypass
                session(['maintenance_bypass' => true]);
                session(['bypassed_by' => $user->id]);
                session(['bypassed_at' => time()]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Bypass berhasil dengan secret key',
                    'redirect_url' => url('/')
                ]);
            }
            
            // Metode 2: Cookie bypass (jika ada)
            $bypassCookie = $request->cookie('maintenance_bypass');
            if ($bypassCookie) {
                try {
                    $cookieData = json_decode(decrypt($bypassCookie), true);
                    if (isset($cookieData['user_id']) && $cookieData['user_id'] == $user->id) {
                        session(['maintenance_bypass' => true]);
                        
                        return response()->json([
                            'success' => true,
                            'message' => 'Bypass berhasil dengan cookie',
                            'redirect_url' => url('/')
                        ]);
                    }
                } catch (\Exception $e) {
                    // Cookie tidak valid
                }
            }
            
            // Metode 3: IP whitelist
            $filePath = storage_path('framework/down');
            if (File::exists($filePath)) {
                $content = File::get($filePath);
                $maintenanceData = json_decode($content, true);
                
                if (isset($maintenanceData['allowed']) && in_array($request->ip(), $maintenanceData['allowed'])) {
                    session(['maintenance_bypass' => true]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Bypass berhasil dengan IP whitelist',
                        'redirect_url' => url('/')
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada metode bypass yang valid'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal bypass: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Middleware untuk memeriksa apakah superadmin dapat mengakses selama maintenance
     * (Method ini bisa dipanggil dari middleware custom)
     */
    public static function checkMaintenanceAccess(Request $request)
    {
        // Cek apakah sistem dalam maintenance
        if (!app()->isDownForMaintenance()) {
            return true;
        }
        
        // Cek apakah user adalah superadmin
        $user = Auth::user();
        if ($user && $user->role === 'superadmin') {
            // Cek beberapa kondisi untuk mengizinkan akses
            
            // 1. Cek session bypass
            if (session('maintenance_bypass')) {
                return true;
            }
            
            // 2. Cek IP dalam whitelist
            $filePath = storage_path('framework/down');
            if (File::exists($filePath)) {
                $content = File::get($filePath);
                $maintenanceData = json_decode($content, true);
                
                if (isset($maintenanceData['allowed']) && in_array($request->ip(), $maintenanceData['allowed'])) {
                    return true;
                }
                
                // 3. Cek apakah rute saat ini diizinkan untuk superadmin
                if (isset($maintenanceData['superadmin_routes'])) {
                    $currentPath = $request->path();
                    foreach ($maintenanceData['superadmin_routes'] as $route) {
                        if (strpos($route, '*') !== false) {
                            // Pattern matching untuk wildcard
                            $pattern = str_replace('*', '.*', $route);
                            $pattern = '#^' . $pattern . '$#';
                            if (preg_match($pattern, $currentPath)) {
                                return true;
                            }
                        } elseif ($route === $currentPath) {
                            return true;
                        }
                    }
                }
            }
            
            // 4. Cek query parameter bypass
            if ($request->has('bypass') && $request->get('bypass') === 'silog-maintenance-2024') {
                session(['maintenance_bypass' => true]);
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * =====================================
     * HELPER METHODS
     * =====================================
     */
    
    /**
     * Ekspor database sebagai JSON
     */
    private function exportAsJSON()
    {
        $filename = 'backup_' . date('Ymd_His') . '.json';
        $filePath = storage_path('app/backups/' . $filename);
        
        if (!File::isDirectory(dirname($filePath))) {
            File::makeDirectory(dirname($filePath), 0755, true);
        }
        
        $backupData = [
            'metadata' => [
                'date' => date('Y-m-d H:i:s'),
                'database' => config('database.connections.mysql.database'),
                'app' => config('app.name', 'SILOG'),
                'version' => '1.0.0'
            ],
            'tables' => []
        ];
        
        // Urutan tabel untuk memastikan foreign key constraints
        $tableOrder = ['satker', 'satuan', 'kategori', 'gudang', 'users', 'barang', 'permintaan', 'pengeluaran', 'activity_logs'];
        
        foreach ($tableOrder as $tableName) {
            if (Schema::hasTable($tableName)) {
                $data = DB::table($tableName)->get()->map(function ($item) {
                    return (array)$item;
                })->toArray();
                
                $backupData['tables'][$tableName] = $data;
                
                \Log::info("Exported table: {$tableName} - " . count($data) . " rows");
            }
        }
        
        $json = json_encode($backupData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        if ($json === false) {
            throw new \Exception('Gagal encode JSON: ' . json_last_error_msg());
        }
        
        File::put($filePath, $json);
        
        return response()->download($filePath)->deleteFileAfterSend(false);
    }
    
    /**
     * Restore dari file JSON
     */
    private function restoreJSON($filePath, $method)
    {
        if (!File::exists($filePath)) {
            throw new \Exception('File tidak ditemukan');
        }
        
        $jsonContent = File::get($filePath);
        $data = json_decode($jsonContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Format JSON tidak valid: ' . json_last_error_msg());
        }
        
        if (!isset($data['tables'])) {
            throw new \Exception('Format backup tidak sesuai - tidak ada data tabel');
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        $result = [
            'total_rows' => 0,
            'inserted_rows' => 0,
            'skipped_rows' => 0,
        ];
        
        try {
            // Jika metode replace, hapus semua data dulu dalam urutan yang benar
            if ($method === 'replace') {
                $this->clearExistingData();
            }
            
            // Restore tabel satu per satu dalam urutan yang benar untuk foreign key constraints
            $tableOrder = ['satker', 'satuan', 'kategori', 'gudang', 'users', 'barang', 'permintaan', 'pengeluaran', 'activity_logs'];
            
            foreach ($tableOrder as $tableName) {
                if (isset($data['tables'][$tableName]) && is_array($data['tables'][$tableName])) {
                    $rows = $data['tables'][$tableName];
                    
                    if (!empty($rows)) {
                        $tableTotal = count($rows);
                        $result['total_rows'] += $tableTotal;
                        $insertedCount = 0;
                        $skippedCount = 0;
                        
                        \Log::info("Processing table: {$tableName} - {$tableTotal} rows");
                        
                        foreach ($rows as $row) {
                            try {
                                if ($method === 'append') {
                                    // Untuk append, gunakan INSERT IGNORE untuk menghindari duplicate
                                    $columns = array_map(function($col) { 
                                        return "`{$col}`"; 
                                    }, array_keys($row));
                                    
                                    $values = array_map(function($val) { 
                                        return is_null($val) ? 'NULL' : DB::connection()->getPdo()->quote($val); 
                                    }, array_values($row));
                                    
                                    $sql = "INSERT IGNORE INTO `{$tableName}` (" . 
                                        implode(', ', $columns) . 
                                        ") VALUES (" . 
                                        implode(', ', $values) . 
                                        ")";
                                    
                                    DB::statement($sql);
                                    
                                    if (DB::getPdo()->lastInsertId()) {
                                        $insertedCount++;
                                    } else {
                                        $skippedCount++;
                                    }
                                } else {
                                    // Untuk replace, langsung insert (tabel sudah dikosongkan)
                                    DB::table($tableName)->insert($row);
                                    $insertedCount++;
                                }
                            } catch (\Exception $e) {
                                // Tangani duplicate entry untuk append
                                if ($method === 'append' && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                                    $skippedCount++;
                                    \Log::warning("Duplicate skipped in {$tableName}");
                                } else {
                                    throw $e;
                                }
                            }
                        }
                        
                        $result['inserted_rows'] += $insertedCount;
                        $result['skipped_rows'] += $skippedCount;
                        
                        \Log::info("Restored {$tableName}: {$insertedCount} inserted, {$skippedCount} skipped");
                    }
                }
            }
            
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            throw new \Exception('Error restore JSON pada tabel ' . $tableName . ': ' . $e->getMessage());
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        return $result;
    }
    
    /**
     * Hapus data existing (untuk metode replace)
     */
    private function clearExistingData()
    {
        // Hapus dalam urutan yang benar untuk menghindari constraint errors
        $tablesToClear = [
            'activity_logs',
            'pengeluaran',
            'permintaan',
            'barang',
            'users',
            'gudang',
            'kategori',
            'satuan',
            'satker'
        ];
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        foreach ($tablesToClear as $tableName) {
            if (Schema::hasTable($tableName)) {
                DB::table($tableName)->truncate();
                \Log::info("Cleared table: {$tableName}");
            }
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
    
    /**
     * Get backup history
     */
    private function getBackupHistory()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::isDirectory($backupPath)) {
            return [];
        }
        
        $files = File::files($backupPath);
        $history = [];
        
        foreach ($files as $file) {
            $filename = $file->getFilename();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            // Hanya ambil file JSON backup
            if (strpos($filename, 'backup_') === 0 && $extension === 'json') {
                $history[] = [
                    'filename' => $filename,
                    'format' => $extension,
                    'size' => $this->formatBytes($file->getSize()),
                    'date' => date('d/m/Y H:i', $file->getMTime())
                ];
            }
        }
        
        // Urutkan berdasarkan tanggal terbaru
        usort($history, function ($a, $b) {
            $timeA = strtotime(str_replace('/', '-', $a['date']));
            $timeB = strtotime(str_replace('/', '-', $b['date']));
            return $timeB - $timeA;
        });
        
        return $history;
    }
    
    /**
     * Get restore history
     */
    private function getRestoreHistory()
    {
        try {
            if (!Schema::hasTable('restore_history')) {
                return [];
            }
            
            if (class_exists('App\Models\RestoreHistory')) {
                $history = \App\Models\RestoreHistory::with('user')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'filename' => $item->filename,
                            'format' => $item->format,
                            'size' => $item->size,
                            'method' => $item->method,
                            'method_text' => $item->method === 'replace' ? 'Replace' : 'Append',
                            'total_rows' => $item->total_rows,
                            'inserted_rows' => $item->inserted_rows,
                            'skipped_rows' => $item->skipped_rows,
                            'date' => $item->created_at->format('d/m/Y H:i'),
                            'status' => $item->status,
                            'status_text' => $item->status === 'success' ? 'Berhasil' : 'Gagal',
                            'status_badge' => $item->status === 'success' ? 'success' : 'danger',
                            'message' => $item->message,
                            'user_name' => $item->user->name ?? 'System',
                        ];
                    })
                    ->toArray();
                    
                return $history;
            } else {
                $history = DB::table('restore_history')
                    ->leftJoin('users', 'restore_history.user_id', '=', 'users.id')
                    ->select('restore_history.*', 'users.name as user_name')
                    ->orderBy('restore_history.created_at', 'desc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'filename' => $item->filename,
                            'format' => $item->format,
                            'size' => $item->size,
                            'method' => $item->method,
                            'method_text' => $item->method === 'replace' ? 'Replace' : 'Append',
                            'total_rows' => $item->total_rows,
                            'inserted_rows' => $item->inserted_rows,
                            'skipped_rows' => $item->skipped_rows,
                            'date' => date('d/m/Y H:i', strtotime($item->created_at)),
                            'status' => $item->status,
                            'status_text' => $item->status === 'success' ? 'Berhasil' : 'Gagal',
                            'status_badge' => $item->status === 'success' ? 'success' : 'danger',
                            'message' => $item->message,
                            'user_name' => $item->user_name ?? 'System',
                        ];
                    })
                    ->toArray();
                    
                return $history;
            }
            
        } catch (\Exception $e) {
            \Log::error('Error getting restore history: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Simpan riwayat restore
     */
    private function saveRestoreHistory($data)
    {
        try {
            if (!Schema::hasTable('restore_history')) {
                return false;
            }
            
            if (class_exists('App\Models\RestoreHistory')) {
                \App\Models\RestoreHistory::create($data);
                return true;
            } else {
                DB::table('restore_history')->insert(array_merge($data, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
                return true;
            }
            
        } catch (\Exception $e) {
            \Log::error('Error saving restore history: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get system information
     */
    private function getSystemInfo()
    {
        return [
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'database' => config('database.default'),
            'app_name' => config('app.name', 'SILOG'),
            'environment' => config('app.env'),
        ];
    }
    
    /**
     * Get server information - DIPERBAIKI
     */
    private function getServerInfo()
    {
        // OS information
        $os = php_uname('s') . ' ' . php_uname('r');
        
        // Web server
        $webServer = $_SERVER['SERVER_SOFTWARE'] ?? 'Tidak diketahui';
        
        // Memory usage - DIPERBAIKI
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        $memoryPercent = 0;
        $memoryUsedFormatted = $this->formatBytes($memoryUsage);
        
        if ($memoryLimit != '-1') {
            $memoryLimitBytes = $this->convertToBytes($memoryLimit);
            $memoryPercent = $memoryLimitBytes > 0 ? round(($memoryUsage / $memoryLimitBytes) * 100, 1) : 0;
        } else {
            $memoryLimit = 'Unlimited';
            $memoryPercent = 0;
        }
        
        // Disk usage - DIPERBAIKI
        $diskTotal = disk_total_space(base_path());
        $diskFree = disk_free_space(base_path());
        $diskUsed = $diskTotal - $diskFree;
        $diskPercent = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100, 1) : 0;
        
        // Uptime - DIPERBAIKI
        $uptime = 'Tidak tersedia';
        if (function_exists('shell_exec')) {
            try {
                // Coba beberapa command untuk mendapatkan uptime
                $commands = [
                    'uptime -p',
                    'uptime',
                    'cat /proc/uptime'
                ];
                
                foreach ($commands as $command) {
                    $output = @shell_exec($command);
                    if ($output) {
                        $uptime = trim($output);
                        break;
                    }
                }
                
                // Format uptime jika perlu
                if (strpos($uptime, 'up') !== false) {
                    $uptime = preg_replace('/\s+/', ' ', $uptime);
                } elseif (is_numeric($uptime)) {
                    // Jika output berupa seconds (dari /proc/uptime)
                    $seconds = (float)$uptime;
                    $days = floor($seconds / 86400);
                    $hours = floor(($seconds % 86400) / 3600);
                    $minutes = floor(($seconds % 3600) / 60);
                    
                    $uptime = '';
                    if ($days > 0) $uptime .= $days . ' hari ';
                    if ($hours > 0) $uptime .= $hours . ' jam ';
                    if ($minutes > 0) $uptime .= $minutes . ' menit';
                    if (empty($uptime)) $uptime = 'Beberapa detik';
                    $uptime = 'up ' . trim($uptime);
                }
            } catch (\Exception $e) {
                $uptime = 'Tidak tersedia';
            }
        }
        
        // Load average
        $loadAverage = 'Tidak tersedia';
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            $loadAverage = round($load[0], 2) . ' ' . round($load[1], 2) . ' ' . round($load[2], 2);
        }
        
        return [
            'os' => $os,
            'web_server' => $webServer,
            'memory_usage' => $memoryPercent,
            'memory_used_formatted' => $memoryUsedFormatted,
            'memory_limit' => $memoryLimit,
            'disk_usage' => $diskPercent,
            'disk_used_formatted' => $this->formatBytes($diskUsed),
            'disk_total_formatted' => $this->formatBytes($diskTotal),
            'uptime' => $uptime,
            'load_average' => $loadAverage,
        ];
    }
    
    /**
     * Convert memory limit string to bytes
     */
    private function convertToBytes($value)
    {
        $value = trim($value);
        if (empty($value)) {
            return 0;
        }
        
        $last = strtolower($value[strlen($value) - 1]);
        $number = substr($value, 0, -1);
        
        // Jika hanya angka tanpa suffix
        if (is_numeric($value)) {
            return (int)$value;
        }
        
        // Jika ada suffix (K, M, G)
        if (is_numeric($number)) {
            switch ($last) {
                case 'g':
                    return (int)$number * 1024 * 1024 * 1024;
                case 'm':
                    return (int)$number * 1024 * 1024;
                case 'k':
                    return (int)$number * 1024;
            }
        }
        
        return 0;
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    /**
     * Get disk usage percentage (old method - kept for compatibility)
     */
    private function getDiskUsage()
    {
        $diskTotal = disk_total_space('/');
        $diskFree = disk_free_space('/');
        
        if ($diskTotal > 0) {
            return round((($diskTotal - $diskFree) / $diskTotal) * 100, 2);
        }
        
        return 0;
    }
}