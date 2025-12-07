<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Permintaan;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        
        // Jika user adalah admin/superadmin, redirect ke admin dashboard
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // Jika user adalah kabid, redirect ke kabid dashboard
        if ($user->role === 'kabid') {
            return redirect()->route('kabid.dashboard');
        }
        
        // Untuk user biasa, tampilkan dashboard user
        $data = $this->getUserDashboardData($user);
        return view('dashboard.index', compact('data', 'user'));
    }

    /**
     * Get dashboard data based on user role
     */
    private function getDashboardData($user)
    {
        $data = [];

        switch ($user->role) {
            case 'superadmin':
            case 'admin':
                $data = $this->getAdminDashboardData();
                break;
            
            case 'kabid':
                $data = $this->getKabidDashboardData($user);
                break;
            
            default:
                $data = $this->getUserDashboardData($user);
                break;
        }

        return $data;
    }

    /**
     * Dashboard data for admin/superadmin
     */
    private function getAdminDashboardData()
    {
        try {
            return [
                'total_barang' => Barang::count(),
                'total_stok' => Barang::sum('stok'),
                'barang_habis' => Barang::where('stok', '<=', DB::raw('stok_minimal'))->count(),
                'permintaan_pending' => Permintaan::where('status', 'pending')->count(),
                'permintaan_disetujui' => Permintaan::where('status', 'approved')->count(),
                'permintaan_ditolak' => Permintaan::where('status', 'rejected')->count(),
                'total_pengeluaran' => Pengeluaran::whereMonth('created_at', now()->month)->count(),
                'total_users' => User::count(),
                'recent_requests' => Permintaan::with('user', 'barang')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get(),
                'low_stock' => Barang::where('stok', '<=', DB::raw('stok_minimal'))
                    ->orderBy('stok', 'asc')
                    ->take(10)
                    ->get(),
            ];
        } catch (\Exception $e) {
            // Jika tabel belum ada, return data default
            return [
                'total_barang' => 0,
                'total_stok' => 0,
                'barang_habis' => 0,
                'permintaan_pending' => 0,
                'permintaan_disetujui' => 0,
                'permintaan_ditolak' => 0,
                'total_pengeluaran' => 0,
                'total_users' => User::count(),
                'recent_requests' => collect(),
                'low_stock' => collect(),
            ];
        }
    }

    /**
     * Dashboard data for kabid
     */
    private function getKabidDashboardData($user)
    {
        try {
            return [
                'permintaan_pending' => Permintaan::where('satker_id', $user->satker_id)
                    ->where('status', 'pending')
                    ->count(),
                'permintaan_disetujui' => Permintaan::where('satker_id', $user->satker_id)
                    ->where('status', 'approved')
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
            // Jika tabel belum ada, return data default
            return [
                'permintaan_pending' => 0,
                'permintaan_disetujui' => 0,
                'permintaan_ditolak' => 0,
                'total_barang_satker' => 0,
                'recent_requests' => collect(),
            ];
        }
    }

    /**
     * Dashboard data for regular user
     */
    private function getUserDashboardData($user)
    {
        try {
            return [
                'my_requests' => Permintaan::where('user_id', $user->id)->count(),
                'requests_pending' => Permintaan::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->count(),
                'requests_approved' => Permintaan::where('user_id', $user->id)
                    ->where('status', 'approved')
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
            // Jika tabel belum ada, return data default
            return [
                'my_requests' => 0,
                'requests_pending' => 0,
                'requests_approved' => 0,
                'requests_rejected' => 0,
                'recent_requests' => collect(),
            ];
        }
    }

    /**
     * Show admin dashboard
     */
    public function adminDashboard()
    {
        $user = Auth::user();
        
        // Authorization check
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $data = $this->getAdminDashboardData();
        return view('dashboard.admin', compact('data', 'user'));
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
}