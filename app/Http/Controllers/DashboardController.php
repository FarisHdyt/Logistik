<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Permintaan;
use App\Models\Pengeluaran;
use App\Models\User;
use App\Models\Satker;
use App\Models\ActivityLog;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the main dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirect berdasarkan role
        switch ($user->role) {
            case 'superadmin':
                // Perbaikan: Langsung redirect ke route superadmin
                return redirect()->route('superadmin.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'kabid':
                return redirect()->route('kabid.dashboard');
            default:
                // Untuk user biasa, tampilkan dashboard user
                $data = $this->getUserDashboardData($user);
                return view('dashboard.index', compact('data', 'user'));
        }
    }

    /**
     * Dashboard data for admin
     */
    private function getAdminDashboardData()
    {
        try {
            // Cek apakah tabel Barang ada
            if (!\Schema::hasTable('barangs')) {
                return $this->getDummyAdminData();
            }
            
            // Data statistik dasar
            $basicData = [
                'total_barang' => Barang::count(),
                'total_stok' => Barang::sum('stok'),
                'barang_habis' => Barang::where('stok', 0)->count(),
                'permintaan_pending' => Permintaan::where('status', 'pending')->count(),
                'permintaan_diproses' => Permintaan::where('status', 'diproses')->count(),
                'permintaan_disetujui' => Permintaan::where('status', 'approved')->count(),
                'permintaan_delivered' => Permintaan::where('status', 'delivered')->count(),
                'permintaan_ditolak' => Permintaan::where('status', 'rejected')->count(),
                'permintaan_bulan_ini' => Permintaan::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'total_pengeluaran' => Pengeluaran::whereMonth('created_at', now()->month)->count(),
                'total_users' => User::count(),
                'recent_requests' => Permintaan::with('user', 'barang', 'satker')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get(),
                'low_stock' => Barang::where('stok', '<', DB::raw('stok_minimal * 2'))
                    ->orderBy('stok', 'asc')
                    ->take(10)
                    ->get(),
            ];
            
            // Data untuk grafik
            $chartData = $this->getChartData();
            
            // Gabungkan semua data
            return array_merge($basicData, $chartData);
            
        } catch (\Exception $e) {
            \Log::error('Error getting admin dashboard data: ' . $e->getMessage());
            return $this->getDummyAdminData();
        }
    }

    /**
     * Dashboard data for superadmin
     */
    private function getSuperadminDashboardData()
    {
        try {
            $basicData = [
                'total_users' => User::count(),
                'total_admins' => User::where('role', 'admin')->count(),
                'total_satker' => Satker::count(),
                'total_activities' => class_exists(ActivityLog::class) ? ActivityLog::whereDate('created_at', today())->count() : 0,
                'superadmin_count' => User::where('role', 'superadmin')->count(),
                'admin_count' => User::where('role', 'admin')->count(),
                'user_count' => User::where('role', 'user')->count(),
                'recent_users' => User::with('satker')->latest()->take(5)->get(),
                'recent_activities' => class_exists(ActivityLog::class) ? ActivityLog::with('user')->latest()->take(5)->get() : collect(),
                'active_users' => User::where('is_active', true)->count(),
                'today_activities' => class_exists(ActivityLog::class) ? ActivityLog::whereDate('created_at', today())->count() : 0,
                'chart_months' => $this->getMonthLabels(),
                'chart_new_users' => $this->getMonthlyUserData(),
                'chart_new_admins' => $this->getMonthlyAdminData(),
                'current_year' => date('Y'),
                
                // Additional stats for system info
                'barang_count' => class_exists(Barang::class) ? Barang::count() : 0,
                'permintaan_count' => class_exists(Permintaan::class) ? Permintaan::count() : 0,
                'kategori_count' => class_exists(Kategori::class) ? Kategori::count() : 0,
                'total_stok' => class_exists(Barang::class) ? Barang::sum('stok') : 0,
                'permintaan_pending' => class_exists(Permintaan::class) ? Permintaan::where('status', 'pending')->count() : 0,
                'permintaan_disetujui' => class_exists(Permintaan::class) ? Permintaan::where('status', 'approved')->count() : 0,
                'permintaan_ditolak' => class_exists(Permintaan::class) ? Permintaan::where('status', 'rejected')->count() : 0,
                'permintaan_delivered' => class_exists(Permintaan::class) ? Permintaan::where('status', 'delivered')->count() : 0,
            ];
            
            return $basicData;
            
        } catch (\Exception $e) {
            \Log::error('Error getting superadmin dashboard data: ' . $e->getMessage());
            
            // Return fallback data
            return [
                'total_users' => User::count(),
                'total_admins' => User::where('role', 'admin')->count(),
                'total_satker' => 0,
                'total_activities' => 0,
                'superadmin_count' => User::where('role', 'superadmin')->count(),
                'admin_count' => User::where('role', 'admin')->count(),
                'user_count' => User::where('role', 'user')->count(),
                'recent_users' => User::latest()->take(5)->get(),
                'recent_activities' => collect(),
                'active_users' => User::where('is_active', true)->count(),
                'today_activities' => 0,
                'chart_months' => $this->getMonthLabels(),
                'chart_new_users' => array_fill(0, 12, 0),
                'chart_new_admins' => array_fill(0, 12, 0),
                'current_year' => date('Y'),
            ];
        }
    }

    /**
     * Get month labels for charts
     */
    private function getMonthLabels()
    {
        return ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    }

    /**
     * Get monthly user data for charts
     */
    private function getMonthlyUserData($year = null)
    {
        $year = $year ?? date('Y');
        $data = [];
        
        try {
            for ($i = 1; $i <= 12; $i++) {
                $count = User::whereYear('created_at', $year)
                    ->whereMonth('created_at', $i)
                    ->count();
                $data[] = $count;
            }
        } catch (\Exception $e) {
            \Log::error('Error getting monthly user data: ' . $e->getMessage());
            $data = array_fill(0, 12, 0);
        }
        
        return $data;
    }

    /**
     * Get monthly admin data for charts
     */
    private function getMonthlyAdminData($year = null)
    {
        $year = $year ?? date('Y');
        $data = [];
        
        try {
            for ($i = 1; $i <= 12; $i++) {
                $count = User::where('role', 'admin')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $i)
                    ->count();
                $data[] = $count;
            }
        } catch (\Exception $e) {
            \Log::error('Error getting monthly admin data: ' . $e->getMessage());
            $data = array_fill(0, 12, 0);
        }
        
        return $data;
    }

    /**
     * Get chart data from existing tables (barangs & permintaans)
     */
    private function getChartData()
    {
        $currentYear = now()->year;
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        // Initialize arrays with zeros for all months
        $barangMasukData = array_fill(0, 12, 0);
        $barangKeluarData = array_fill(0, 12, 0);
        
        try {
            // 1. DATA BARANG MASUK: Barang yang stoknya bertambah
            if (\Schema::hasTable('barangs')) {
                $barangMasuk = Barang::select(
                        DB::raw('MONTH(created_at) as bulan'),
                        DB::raw('SUM(stok) as total')
                    )
                    ->whereYear('created_at', $currentYear)
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get();
                    
                foreach ($barangMasuk as $item) {
                    $barangMasukData[$item->bulan - 1] = (int)$item->total;
                }
            }
            
            // 2. DATA BARANG KELUAR: HANYA dari permintaan yang statusnya DELIVERED
            if (\Schema::hasTable('permintaans')) {
                $barangKeluar = Permintaan::select(
                        DB::raw('MONTH(updated_at) as bulan'),
                        DB::raw('SUM(jumlah) as total')
                    )
                    ->where('status', 'delivered')
                    ->whereYear('updated_at', $currentYear)
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get();
                    
                foreach ($barangKeluar as $item) {
                    $barangKeluarData[$item->bulan - 1] = (int)$item->total;
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Error getting chart data: ' . $e->getMessage());
            // Use dummy data for development
            $barangMasukData = [65, 59, 80, 81, 56, 55, 40, 50, 30, 70, 90, 60];
            $barangKeluarData = [28, 48, 40, 19, 86, 27, 90, 30, 45, 60, 25, 50];
        }
        
        return [
            'chart_months' => $months,
            'chart_barang_masuk' => $barangMasukData,
            'chart_barang_keluar' => $barangKeluarData,
            'current_year' => $currentYear,
        ];
    }

    /**
     * Dummy data for admin dashboard (for development)
     */
    private function getDummyAdminData()
    {
        $totalUsers = User::count();
        $currentYear = now()->year;
        
        return [
            'total_barang' => 45,
            'total_stok' => 1280,
            'barang_habis' => 3,
            'permintaan_pending' => 12,
            'permintaan_diproses' => 8,
            'permintaan_disetujui' => 28,
            'permintaan_delivered' => 20,
            'permintaan_ditolak' => 5,
            'permintaan_bulan_ini' => 15,
            'total_pengeluaran' => 42,
            'total_users' => $totalUsers,
            'recent_requests' => $this->getDummyRequests(),
            'low_stock' => $this->getDummyLowStock(),
            'chart_months' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'chart_barang_masuk' => [65, 59, 80, 81, 56, 55, 40, 50, 30, 70, 90, 60],
            'chart_barang_keluar' => [28, 48, 40, 19, 86, 27, 90, 30, 45, 60, 25, 50],
            'current_year' => $currentYear,
        ];
    }

    /**
     * Dummy requests data
     */
    private function getDummyRequests()
    {
        $statuses = ['pending', 'diproses', 'approved', 'delivered', 'rejected'];
        $barangNames = [
            'Bahan Bakar Pertamax', 'Ban Mobil Patroli', 'Oli Mesin', 
            'Sparepart Motor', 'Alat Tulis Kantor', 'Seragam Polisi',
            'Sepatu Dinas', 'Handy Talky', 'Laptop', 'Printer'
        ];
        
        $satkers = Satker::take(3)->get();
        $users = User::where('role', 'user')->take(5)->get();
        
        $requests = collect();
        
        for ($i = 1; $i <= 5; $i++) {
            $status = $statuses[array_rand($statuses)];
            $user = $users->count() > 0 ? $users->random() : (object)['name' => 'User ' . $i];
            $satker = $satkers->count() > 0 ? $satkers->random() : (object)['nama_satker' => 'POLRES Test'];
            
            $requests->push((object)[
                'id' => $i,
                'kode_permintaan' => 'PMT-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'user' => $user,
                'barang' => (object)[
                    'nama_barang' => $barangNames[array_rand($barangNames)]
                ],
                'jumlah' => rand(1, 100),
                'satker' => $satker,
                'status' => $status,
                'created_at' => now()->subDays(rand(1, 30))
            ]);
        }
        
        return $requests;
    }

    /**
     * Dummy low stock data
     */
    private function getDummyLowStock()
    {
        $barangNames = [
            'Bahan Bakar Pertamax', 'Ban Mobil Patroli', 'Oli Mesin', 
            'Sparepart Motor', 'Alat Tulis Kantor', 'Seragam Polisi',
            'Sepatu Dinas', 'Handy Talky', 'Laptop', 'Printer'
        ];
        
        $kategoris = ['Bahan Bakar', 'Sparepart', 'ATK', 'Elektronik', 'Seragam'];
        
        $lowStock = collect();
        
        for ($i = 1; $i <= 5; $i++) {
            $stok = rand(1, 15);
            $stokMinimal = rand(20, 50);
            
            $lowStock->push((object)[
                'id' => $i,
                'kode_barang' => 'BRG-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nama_barang' => $barangNames[array_rand($barangNames)],
                'kategori' => (object)[
                    'nama_kategori' => $kategoris[array_rand($kategoris)]
                ],
                'stok' => $stok,
                'stok_minimal' => $stokMinimal
            ]);
        }
        
        return $lowStock;
    }

    /**
     * Dashboard data for kabid
     */
    private function getKabidDashboardData($user)
    {
        try {
            // Cek apakah tabel ada
            if (!\Schema::hasTable('permintaans')) {
                return $this->getDummyKabidData($user);
            }
            
            return [
                'permintaan_pending' => Permintaan::where('satker_id', $user->satker_id)
                    ->where('status', 'pending')
                    ->count(),
                'permintaan_diproses' => Permintaan::where('satker_id', $user->satker_id)
                    ->where('status', 'diproses')
                    ->count(),
                'permintaan_disetujui' => Permintaan::where('satker_id', $user->satker_id)
                    ->where('status', 'approved')
                    ->count(),
                'permintaan_delivered' => Permintaan::where('satker_id', $user->satker_id)
                    ->where('status', 'delivered')
                    ->count(),
                'permintaan_ditolak' => Permintaan::where('satker_id', $user->satker_id)
                    ->where('status', 'rejected')
                    ->count(),
                'total_barang_satker' => Barang::whereHas('gudang', function($query) use ($user) {
                    $query->where('satker_id', $user->satker_id);
                })->count(),
                'recent_requests' => Permintaan::with('user', 'barang')
                    ->where('satker_id', $user->satker_id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get(),
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting kabid dashboard data: ' . $e->getMessage());
            return $this->getDummyKabidData($user);
        }
    }

    /**
     * Dummy data for kabid dashboard
     */
    private function getDummyKabidData($user)
    {
        return [
            'permintaan_pending' => rand(1, 10),
            'permintaan_diproses' => rand(1, 5),
            'permintaan_disetujui' => rand(5, 20),
            'permintaan_delivered' => rand(3, 15),
            'permintaan_ditolak' => rand(0, 5),
            'total_barang_satker' => rand(10, 50),
            'recent_requests' => $this->getDummyRequests()->take(3),
        ];
    }

    /**
     * Dashboard data for regular user
     */
    private function getUserDashboardData($user)
    {
        try {
            // Cek apakah tabel ada
            if (!\Schema::hasTable('permintaans')) {
                return $this->getDummyUserData($user);
            }
            
            return [
                'my_requests' => Permintaan::where('user_id', $user->id)->count(),
                'requests_pending' => Permintaan::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->count(),
                'requests_diproses' => Permintaan::where('user_id', $user->id)
                    ->where('status', 'diproses')
                    ->count(),
                'requests_approved' => Permintaan::where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->count(),
                'requests_delivered' => Permintaan::where('user_id', $user->id)
                    ->where('status', 'delivered')
                    ->count(),
                'requests_rejected' => Permintaan::where('user_id', $user->id)
                    ->where('status', 'rejected')
                    ->count(),
                'recent_requests' => Permintaan::with('barang')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get(),
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting user dashboard data: ' . $e->getMessage());
            return $this->getDummyUserData($user);
        }
    }

    /**
     * Dummy data for user dashboard
     */
    private function getDummyUserData($user)
    {
        return [
            'my_requests' => rand(0, 10),
            'requests_pending' => rand(0, 5),
            'requests_diproses' => rand(0, 3),
            'requests_approved' => rand(0, 8),
            'requests_delivered' => rand(0, 6),
            'requests_rejected' => rand(0, 2),
            'recent_requests' => collect(),
        ];
    }

    /**
     * Show admin dashboard
     */
    public function adminDashboard()
    {
        $user = Auth::user();
        
        // Authorization check - hanya admin dan superadmin
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access.');
        }
        
        // Jika user adalah superadmin, redirect ke dashboard superadmin
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        }
        
        $data = $this->getAdminDashboardData();
        return view('dashboard.admin', compact('data', 'user'));
    }

    /**
     * Show superadmin dashboard
     */
    public function superadminDashboard()
    {
        $user = Auth::user();
        
        // Authorization check - hanya superadmin
        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        $data = $this->getSuperadminDashboardData();
        return view('dashboard.superadmin', compact('data', 'user'));
    }

    /**
     * Show kabid dashboard
     */
    public function kabidDashboard()
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->role !== 'kabid') {
            abort(403, 'Unauthorized access.');
        }
        
        $data = $this->getKabidDashboardData($user);
        return view('dashboard.kabid', compact('data', 'user'));
    }

    /**
     * API endpoint for chart data (AJAX) - Admin Dashboard
     */
    public function getChartDataApi(Request $request)
    {
        try {
            $year = $request->get('year', now()->year);
            
            $chartData = $this->getChartDataForYear($year);
            
            return response()->json([
                'success' => true,
                'data' => $chartData,
                'year' => $year
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting chart data API: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data chart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint for superadmin chart data (AJAX)
     */
    public function getSuperadminChartData(Request $request)
    {
        try {
            $year = $request->get('year', date('Y'));
            
            $data = [
                'success' => true,
                'data' => [
                    'months' => $this->getMonthLabels(),
                    'new_users' => $this->getMonthlyUserData($year),
                    'new_admins' => $this->getMonthlyAdminData($year)
                ]
            ];
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            \Log::error('Error getting superadmin chart data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data chart',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get chart data for specific year
     */
    private function getChartDataForYear($year)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $barangMasukData = array_fill(0, 12, 0);
        $barangKeluarData = array_fill(0, 12, 0);
        
        try {
            // 1. Barang Masuk: Barang yang dibuat/ditambahkan
            if (\Schema::hasTable('barangs')) {
                $barangMasuk = Barang::select(
                        DB::raw('MONTH(created_at) as bulan'),
                        DB::raw('SUM(stok) as total')
                    )
                    ->whereYear('created_at', $year)
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get();
                    
                foreach ($barangMasuk as $item) {
                    $barangMasukData[$item->bulan - 1] = (int)$item->total;
                }
            }
            
            // 2. Barang Keluar: HANYA dari permintaan yang statusnya DELIVERED
            if (\Schema::hasTable('permintaans')) {
                $barangKeluar = Permintaan::select(
                        DB::raw('MONTH(updated_at) as bulan'),
                        DB::raw('SUM(jumlah) as total')
                    )
                    ->where('status', 'delivered')
                    ->whereYear('updated_at', $year)
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get();
                    
                foreach ($barangKeluar as $item) {
                    $barangKeluarData[$item->bulan - 1] = (int)$item->total;
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Error getting chart data for year ' . $year . ': ' . $e->getMessage());
        }
        
        return [
            'months' => $months,
            'barang_masuk' => $barangMasukData,
            'barang_keluar' => $barangKeluarData,
        ];
    }
}