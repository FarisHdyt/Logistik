<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Log activity untuk aksi penting
     */
    public static function logAction($action, $description = null, $data = null, $userId = null)
    {
        try {
            $userId = $userId ?? auth()->id();
            
            return ActivityLog::create([
                'user_id' => $userId,
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log activity: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Log untuk login
     */
    public static function logLogin($user)
    {
        $description = "Login ke sistem";
        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'role' => $user->role
        ];
        
        return self::logAction('login', $description, $data, $user->id);
    }

    /**
     * Log untuk logout
     */
    public static function logLogout($user)
    {
        $description = "Logout dari sistem";
        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email
        ];
        
        return self::logAction('logout', $description, $data, $user->id);
    }

    /**
     * Log untuk tambah barang/logistik
     */
    public static function logCreateBarang($barang, $data = null, $userId = null)
    {
        $description = "Menambah barang/logistik: {$barang->nama_barang} ({$barang->kode_barang})";
        
        $logData = [
            'barang_id' => $barang->id,
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'stok' => $barang->stok,
            'stok_minimal' => $barang->stok_minimal,
            'satuan' => $barang->satuan,
            'kategori' => $barang->kategori->nama_kategori ?? 'Tidak diketahui'
        ];
        
        if ($data) {
            $logData = array_merge($logData, $data);
        }
        
        return self::logAction('create', $description, $logData, $userId);
    }

    /**
     * Log untuk ubah barang/logistik
     */
    public static function logUpdateBarang($barang, $oldData = null, $newData = null, $userId = null)
    {
        $description = "Mengubah barang/logistik: {$barang->nama_barang} ({$barang->kode_barang})";
        
        $logData = [
            'barang_id' => $barang->id,
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'old_data' => $oldData,
            'new_data' => $newData,
            'changes' => $newData !== null && $oldData !== null ? 
                array_diff_assoc($newData, $oldData) : null
        ];
        
        return self::logAction('update', $description, $logData, $userId);
    }

    /**
     * Log untuk hapus barang/logistik
     */
    public static function logDeleteBarang($barangData, $userId = null)
    {
        $description = "Menghapus barang/logistik: {$barangData['nama_barang']} ({$barangData['kode_barang']})";
        
        return self::logAction('delete', $description, $barangData, $userId);
    }

    /**
     * Log untuk restock barang
     */
    public static function logRestockBarang($barang, $oldStok, $addedStok, $keterangan = null, $userId = null)
    {
        $description = "Restock barang: {$barang->nama_barang} (+{$addedStok})";
        
        $logData = [
            'barang_id' => $barang->id,
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'old_stok' => $oldStok,
            'added_stok' => $addedStok,
            'new_stok' => $barang->stok,
            'keterangan' => $keterangan
        ];
        
        return self::logAction('restock', $description, $logData, $userId);
    }

    /**
     * Log untuk approval permintaan
     */
    public static function logApprovePermintaan($permintaan, $oldStatus, $catatan = null, $userId = null)
    {
        $description = "Menyetujui permintaan: {$permintaan->kode_permintaan}";
        
        $logData = [
            'permintaan_id' => $permintaan->id,
            'kode_permintaan' => $permintaan->kode_permintaan,
            'barang' => $permintaan->barang->nama_barang ?? 'Tidak diketahui',
            'jumlah' => $permintaan->jumlah,
            'old_status' => $oldStatus,
            'new_status' => 'approved',
            'approved_by' => auth()->user()->name ?? 'System',
            'catatan' => $catatan,
            'user_pemohon' => $permintaan->user->name ?? 'Tidak diketahui',
            'satker' => $permintaan->satker->nama_satker ?? 'Tidak diketahui'
        ];
        
        return self::logAction('approve', $description, $logData, $userId);
    }

    /**
     * Log untuk reject permintaan
     */
    public static function logRejectPermintaan($permintaan, $oldStatus, $alasan = null, $userId = null)
    {
        $description = "Menolak permintaan: {$permintaan->kode_permintaan}";
        
        $logData = [
            'permintaan_id' => $permintaan->id,
            'kode_permintaan' => $permintaan->kode_permintaan,
            'barang' => $permintaan->barang->nama_barang ?? 'Tidak diketahui',
            'jumlah' => $permintaan->jumlah,
            'old_status' => $oldStatus,
            'new_status' => 'rejected',
            'rejected_by' => auth()->user()->name ?? 'System',
            'alasan_penolakan' => $alasan,
            'user_pemohon' => $permintaan->user->name ?? 'Tidak diketahui'
        ];
        
        return self::logAction('reject', $description, $logData, $userId);
    }

    /**
     * Log untuk distribusi barang
     */
    public static function logDeliverPermintaan($permintaan, $oldStatus, $bukti = null, $userId = null)
    {
        $description = "Mendistribusikan barang: {$permintaan->kode_permintaan}";
        
        $logData = [
            'permintaan_id' => $permintaan->id,
            'kode_permintaan' => $permintaan->kode_permintaan,
            'barang' => $permintaan->barang->nama_barang ?? 'Tidak diketahui',
            'jumlah' => $permintaan->jumlah,
            'old_status' => $oldStatus,
            'new_status' => 'delivered',
            'delivered_by' => auth()->user()->name ?? 'System',
            'bukti_pengiriman' => $bukti,
            'penerima' => $permintaan->user->name ?? 'Tidak diketahui',
            'satker' => $permintaan->satker->nama_satker ?? 'Tidak diketahui'
        ];
        
        return self::logAction('deliver', $description, $logData, $userId);
    }

    /**
     * Log untuk retur barang
     */
    public static function logReturnPermintaan($permintaan, $jumlahRetur, $alasan, $kondisi, $userId = null)
    {
        $description = "Memproses retur barang: {$permintaan->kode_permintaan}";
        
        $logData = [
            'permintaan_id' => $permintaan->id,
            'kode_permintaan' => $permintaan->kode_permintaan,
            'barang' => $permintaan->barang->nama_barang ?? 'Tidak diketahui',
            'jumlah_retur' => $jumlahRetur,
            'alasan_retur' => $alasan,
            'kondisi_barang' => $kondisi,
            'processed_by' => auth()->user()->name ?? 'System'
        ];
        
        return self::logAction('return', $description, $logData, $userId);
    }

    /**
     * Log untuk tambah user (opsional)
     */
    public static function logCreateUser($user, $createdBy = null, $userId = null)
    {
        $description = "Menambah user baru: {$user->name} ({$user->email})";
        
        $logData = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'created_by' => $createdBy ?? auth()->user()->name ?? 'System'
        ];
        
        return self::logAction('create_user', $description, $logData, $userId);
    }

    /**
     * Log untuk hapus semua logs
     */
    public static function logClearAllLogs($user)
    {
        $description = "Menghapus semua log aktivitas sistem";
        
        $logData = [
            'cleared_by' => $user->name,
            'cleared_by_id' => $user->id,
            'cleared_at' => now()->toDateTimeString()
        ];
        
        return self::logAction('delete_all', $description, $logData, $user->id);
    }

    // ============================================================
    // METHOD UNTUK VIEW ACTIVITY LOGS (tetap seperti sebelumnya)
    // ============================================================

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        // Query logs with user relationship
        $query = ActivityLog::with('user')->latest();
        
        // Filter berdasarkan search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter berdasarkan action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }
        
        // Filter berdasarkan user
        if ($request->has('user_id') && $request->user_id) {
            // Jika user_id = 0, tampilkan logs tanpa user (System)
            if ($request->user_id == 0) {
                $query->whereNull('user_id');
            } else {
                $query->where('user_id', $request->user_id);
            }
        }
        
        // Filter berdasarkan date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Quick time filters
        if ($request->has('time_filter')) {
            switch ($request->time_filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [
                        now()->subDays(7),
                        now()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
            }
        }
        
        // Paginate results
        $logs = $query->paginate(20);
        
        // Get ALL users for filter dropdown (semua user di sistem)
        $users = User::orderBy('name')->get();
        
        // Statistics - data real dari database
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'error_logs' => ActivityLog::where('action', 'error')->count(),
            'login_logs' => ActivityLog::where('action', 'login')->count(),
            'logout_logs' => ActivityLog::where('action', 'logout')->count(),
            'create_logs' => ActivityLog::where('action', 'create')->orWhere('action', 'create_user')->count(),
            'update_logs' => ActivityLog::where('action', 'update')->count(),
            'delete_logs' => ActivityLog::where('action', 'delete')->orWhere('action', 'delete_all')->count(),
            'approve_logs' => ActivityLog::where('action', 'approve')->count(),
            'reject_logs' => ActivityLog::where('action', 'reject')->count(),
            'deliver_logs' => ActivityLog::where('action', 'deliver')->count(),
            'return_logs' => ActivityLog::where('action', 'return')->count(),
            'restock_logs' => ActivityLog::where('action', 'restock')->count(),
        ];
        
        return view('superadmin.activity-logs', compact('user', 'logs', 'users', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $log = ActivityLog::with('user')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $log
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Log tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Clear all activity logs.
     */
    public function clear(Request $request)
    {
        $user = Auth::user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            // Log sebelum menghapus semua logs
            self::logClearAllLogs($user);
            
            // Hapus semua logs
            ActivityLog::truncate();
            
            return response()->json([
                'success' => true,
                'message' => 'Semua log aktivitas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus log aktivitas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity statistics for dashboard.
     */
    public function statistics()
    {
        $user = Auth::user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            // Get statistics for the last 30 days
            $startDate = now()->subDays(30);
            $endDate = now();
            
            $dailyStats = ActivityLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            $actionStats = ActivityLog::selectRaw('action, COUNT(*) as count')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('action')
                ->orderByDesc('count')
                ->get();
            
            $userStats = ActivityLog::selectRaw('user_id, COUNT(*) as count')
                ->whereNotNull('user_id')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('user_id')
                ->orderByDesc('count')
                ->limit(10)
                ->with('user')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'daily_stats' => $dailyStats,
                    'action_stats' => $actionStats,
                    'top_users' => $userStats,
                    'total_logs' => ActivityLog::count(),
                    'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
                    'month_logs' => ActivityLog::whereBetween('created_at', [$startDate, $endDate])->count(),
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search logs by advanced criteria.
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $query = ActivityLog::with('user');
            
            // Multiple search criteria
            if ($request->has('keywords') && $request->keywords) {
                $keywords = $request->keywords;
                $query->where(function($q) use ($keywords) {
                    $q->where('description', 'like', "%{$keywords}%")
                      ->orWhere('ip_address', 'like', "%{$keywords}%")
                      ->orWhere('action', 'like', "%{$keywords}%")
                      ->orWhereHas('user', function($userQuery) use ($keywords) {
                          $userQuery->where('name', 'like', "%{$keywords}%")
                                   ->orWhere('email', 'like', "%{$keywords}%");
                      });
                });
            }
            
            if ($request->has('actions') && is_array($request->actions)) {
                $query->whereIn('action', $request->actions);
            }
            
            if ($request->has('user_ids') && is_array($request->user_ids)) {
                $userIds = array_filter($request->user_ids);
                if (in_array(0, $request->user_ids)) {
                    // Include system logs (user_id = null)
                    $query->where(function($q) use ($userIds) {
                        $q->whereIn('user_id', $userIds)
                          ->orWhereNull('user_id');
                    });
                } else {
                    $query->whereIn('user_id', $userIds);
                }
            }
            
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            
            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            $logs = $query->latest()->paginate($request->per_page ?? 20);
            
            return response()->json([
                'success' => true,
                'data' => $logs,
                'message' => 'Pencarian berhasil'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pencarian: ' . $e->getMessage()
            ], 500);
        }
    }
}