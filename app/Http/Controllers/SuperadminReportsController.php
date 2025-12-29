<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Satker;
use App\Models\ActivityLog;
use Carbon\Carbon;

class SuperadminReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('superadmin');
    }
    
    /**
     * Menampilkan halaman laporan
     */
    public function index(Request $request)
    {
        // Ambil parameter filter
        $reportType = $request->get('type', 'user');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $satkerId = $request->get('satker_id');
        $role = $request->get('role');
        
        // Data umum untuk view
        $data = [
            'title' => 'Laporan Sistem',
            'user' => Auth::user(),
            'reportType' => $reportType,
            'satkers' => Satker::orderBy('nama_satker')->get(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedSatker' => $satkerId,
            'selectedRole' => $role,
        ];
        
        // Tambahkan data berdasarkan jenis laporan
        switch ($reportType) {
            case 'user':
                $data = array_merge($data, $this->getUserData($request));
                break;
                
            case 'activity':
                $data = array_merge($data, $this->getActivityData($request));
                break;
                
            case 'satker':
                $data = array_merge($data, $this->getSatkerData($request));
                break;
                
            case 'system':
                $data = array_merge($data, $this->getSystemData($request));
                break;
        }
        
        return view('superadmin.reports', $data);
    }
    
    /**
     * Data untuk laporan user
     */
    private function getUserData(Request $request)
    {
        $query = User::with('satker');
        
        // Filter tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }
        
        // Filter satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }
        
        // Filter role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Statistik
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalAdmins = User::where('role', 'admin')->where('is_active', true)->count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)->count();
        
        // Distribusi role
        $superadminCount = User::where('role', 'superadmin')->where('is_active', true)->count();
        $adminCount = User::where('role', 'admin')->where('is_active', true)->count();
        $userCount = User::where('role', 'user')->where('is_active', true)->count();
        
        // Data user dengan pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Chart data (6 bulan terakhir)
        $chartData = $this->getUserChartData();
        
        return [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalAdmins' => $totalAdmins,
            'newUsersThisMonth' => $newUsersThisMonth,
            'superadminCount' => $superadminCount,
            'adminCount' => $adminCount,
            'userCount' => $userCount,
            'users' => $users,
            'chartData' => $chartData,
            'title' => 'Laporan User',
            'subtitle' => 'Statistik dan Data User',
        ];
    }
    
    /**
     * Data untuk laporan aktivitas
     */
    private function getActivityData(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Filter tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }
        
        // Filter user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter aksi
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        // Statistik
        $totalActivities = $query->count();
        $loginCount = ActivityLog::where('action', 'login')->count();
        $logoutCount = ActivityLog::where('action', 'logout')->count();
        $createCount = ActivityLog::where('action', 'create')->count();
        $updateCount = ActivityLog::where('action', 'update')->count();
        $deleteCount = ActivityLog::where('action', 'delete')->count();
        
        // Data aktivitas dengan pagination
        $activities = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Chart data (6 bulan terakhir)
        $chartData = $this->getActivityChartData();
        
        return [
            'totalActivities' => $totalActivities,
            'loginCount' => $loginCount,
            'logoutCount' => $logoutCount,
            'createCount' => $createCount,
            'updateCount' => $updateCount,
            'deleteCount' => $deleteCount,
            'activities' => $activities,
            'chartData' => $chartData,
            'title' => 'Laporan Aktivitas',
            'subtitle' => 'Log Aktivitas Sistem',
        ];
    }
    
    /**
     * Data untuk laporan satker
     */
    private function getSatkerData(Request $request)
    {
        $query = Satker::withCount(['users' => function($q) {
            $q->where('is_active', true);
        }])->with('users');
        
        // Statistik
        $totalSatker = Satker::count();
        $totalUsers = User::where('is_active', true)->count();
        $averageUsersPerSatker = $totalSatker > 0 ? round($totalUsers / $totalSatker, 1) : 0;
        
        // Satker dengan user terbanyak
        $topSatkers = Satker::withCount(['users' => function($q) {
            $q->where('is_active', true);
        }])->orderBy('users_count', 'desc')->limit(5)->get();
        
        // Data satker dengan pagination
        $satkers = $query->orderBy('nama_satker')->paginate(10);
        
        // Chart data (distribusi user per satker)
        $chartData = $this->getSatkerChartData();
        
        return [
            'totalSatker' => $totalSatker,
            'totalUsers' => $totalUsers,
            'averageUsersPerSatker' => $averageUsersPerSatker,
            'topSatkers' => $topSatkers,
            'satkers' => $satkers,
            'chartData' => $chartData,
            'title' => 'Laporan Satker',
            'subtitle' => 'Data Satuan Kerja',
        ];
    }
    
    /**
     * Data untuk laporan sistem
     */
    private function getSystemData(Request $request)
    {
        // Statistik sistem
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalSatker = Satker::count();
        $totalActivities = ActivityLog::count();
        
        // Aktivitas hari ini
        $todayActivities = ActivityLog::whereDate('created_at', Carbon::today())->count();
        $todayLogins = ActivityLog::whereDate('created_at', Carbon::today())
            ->where('action', 'login')
            ->count();
        
        // User baru hari ini
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();
        
        // Sistem info
        $systemUptime = '99.9%';
        $lastBackup = Carbon::now()->subDays(1)->format('d/m/Y H:i');
        
        // Chart data (pertumbuhan 6 bulan)
        $chartData = $this->getSystemChartData();
        
        return [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalSatker' => $totalSatker,
            'totalActivities' => $totalActivities,
            'todayActivities' => $todayActivities,
            'todayLogins' => $todayLogins,
            'newUsersToday' => $newUsersToday,
            'systemUptime' => $systemUptime,
            'lastBackup' => $lastBackup,
            'chartData' => $chartData,
            'title' => 'Laporan Sistem',
            'subtitle' => 'Overview Sistem',
        ];
    }
    
    /**
     * Chart data untuk user report
     */
    private function getUserChartData()
    {
        $months = [];
        $userCounts = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $count = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $userCounts[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $userCounts,
            'type' => 'line',
            'title' => 'Pertumbuhan User (6 Bulan Terakhir)',
            'color' => 'rgba(139, 92, 246, 0.8)',
        ];
    }
    
    /**
     * Chart data untuk activity report
     */
    private function getActivityChartData()
    {
        $months = [];
        $activityCounts = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $count = ActivityLog::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $activityCounts[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $activityCounts,
            'type' => 'bar',
            'title' => 'Aktivitas Sistem (6 Bulan Terakhir)',
            'color' => 'rgba(245, 158, 11, 0.8)',
        ];
    }
    
    /**
     * Chart data untuk satker report
     */
    private function getSatkerChartData()
    {
        $satkers = Satker::withCount(['users' => function($q) {
            $q->where('is_active', true);
        }])->orderBy('users_count', 'desc')->limit(10)->get();
        
        $labels = $satkers->pluck('nama_satker')->toArray();
        $data = $satkers->pluck('users_count')->toArray();
        
        return [
            'labels' => $labels,
            'data' => $data,
            'type' => 'doughnut',
            'title' => 'Distribusi User per Satker (Top 10)',
            'color' => 'rgba(16, 185, 129, 0.8)',
        ];
    }
    
    /**
     * Chart data untuk system report
     */
    private function getSystemChartData()
    {
        $months = [];
        $userData = [];
        $activityData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $userData[] = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $activityData[] = ActivityLog::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        
        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'User Baru',
                    'data' => $userData,
                    'color' => 'rgba(139, 92, 246, 0.8)',
                ],
                [
                    'label' => 'Aktivitas',
                    'data' => $activityData,
                    'color' => 'rgba(14, 165, 233, 0.8)',
                ]
            ],
            'type' => 'line',
            'title' => 'Pertumbuhan Sistem (6 Bulan Terakhir)',
        ];
    }
    
    /**
     * Generate PDF report
     */
    public function generatePdf(Request $request)
    {
        $reportType = $request->type;
        $fileName = 'laporan_' . $reportType . '_' . Carbon::now()->format('Ymd_His') . '.pdf';
        
        // Load data berdasarkan jenis laporan
        switch ($reportType) {
            case 'user':
                $data = $this->getUserData($request);
                break;
                
            case 'activity':
                $data = $this->getActivityData($request);
                break;
                
            case 'satker':
                $data = $this->getSatkerData($request);
                break;
                
            case 'system':
                $data = $this->getSystemData($request);
                break;
        }
        
        // Tambahkan data umum
        $data['user'] = Auth::user();
        $data['generatedAt'] = Carbon::now()->format('d/m/Y H:i:s');
        
        // Generate PDF
        $pdf = \PDF::loadView('superadmin.reports-pdf', $data);
        
        return $pdf->download($fileName);
    }
    
    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->type;
        $fileName = 'laporan_' . $reportType . '_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        
        // Load data berdasarkan jenis laporan
        switch ($reportType) {
            case 'user':
                $data = $this->getUserData($request);
                $exportData = $this->prepareExcelData($data, 'user');
                break;
                
            case 'activity':
                $data = $this->getActivityData($request);
                $exportData = $this->prepareExcelData($data, 'activity');
                break;
                
            case 'satker':
                $data = $this->getSatkerData($request);
                $exportData = $this->prepareExcelData($data, 'satker');
                break;
                
            case 'system':
                $data = $this->getSystemData($request);
                $exportData = $this->prepareExcelData($data, 'system');
                break;
        }
        
        // Simpan ke Excel (gunakan library sesuai kebutuhan)
        // Contoh sederhana: CSV
        $filePath = storage_path('app/' . $fileName);
        $handle = fopen($filePath, 'w');
        
        // Header
        fputcsv($handle, $exportData['headers']);
        
        // Data
        foreach ($exportData['rows'] as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);
        
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
    
    /**
     * Siapkan data untuk export Excel
     */
    private function prepareExcelData($data, $type)
    {
        switch ($type) {
            case 'user':
                $headers = ['Nama', 'Username', 'Email', 'Role', 'Satker', 'Status', 'Tanggal Dibuat'];
                $rows = [];
                
                foreach ($data['users'] as $user) {
                    $rows[] = [
                        $user->name,
                        $user->username,
                        $user->email,
                        ucfirst($user->role),
                        $user->satker->nama_satker ?? '-',
                        $user->is_active ? 'Aktif' : 'Nonaktif',
                        $user->created_at->format('d/m/Y'),
                    ];
                }
                break;
                
            case 'activity':
                $headers = ['User', 'Aksi', 'Deskripsi', 'IP Address', 'Waktu'];
                $rows = [];
                
                foreach ($data['activities'] as $activity) {
                    $rows[] = [
                        $activity->user->name ?? 'System',
                        ucfirst($activity->action),
                        $activity->description,
                        $activity->ip_address,
                        $activity->created_at->format('d/m/Y H:i:s'),
                    ];
                }
                break;
                
            case 'satker':
                $headers = ['Nama Satker', 'Kode Satker', 'Jumlah User', 'Tanggal Dibuat'];
                $rows = [];
                
                foreach ($data['satkers'] as $satker) {
                    $rows[] = [
                        $satker->nama_satker,
                        $satker->kode_satker ?? '-',
                        $satker->users_count ?? 0,
                        $satker->created_at->format('d/m/Y'),
                    ];
                }
                break;
                
            case 'system':
                $headers = ['Metrik', 'Nilai'];
                $rows = [
                    ['Total User', $data['totalUsers']],
                    ['User Aktif', $data['activeUsers']],
                    ['Total Satker', $data['totalSatker']],
                    ['Total Aktivitas', $data['totalActivities']],
                    ['Aktivitas Hari Ini', $data['todayActivities']],
                    ['Login Hari Ini', $data['todayLogins']],
                    ['User Baru Hari Ini', $data['newUsersToday']],
                    ['Uptime Sistem', $data['systemUptime']],
                    ['Backup Terakhir', $data['lastBackup']],
                ];
                break;
        }
        
        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }
    
    /**
     * AJAX endpoint untuk chart data
     */
    public function getChartData(Request $request)
    {
        $request->validate([
            'type' => 'required|in:user,activity,satker,system',
            'year' => 'nullable|integer|min:2020|max:' . date('Y'),
        ]);
        
        $year = $request->year ?? date('Y');
        $data = [];
        
        switch ($request->type) {
            case 'user':
                $data = $this->getUserChartDataByYear($year);
                break;
                
            case 'activity':
                $data = $this->getActivityChartDataByYear($year);
                break;
                
            case 'satker':
                $data = $this->getSatkerChartDataByYear($year);
                break;
                
            case 'system':
                $data = $this->getSystemChartDataByYear($year);
                break;
        }
        
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    
    /**
     * Chart data user berdasarkan tahun
     */
    private function getUserChartDataByYear($year)
    {
        $months = [];
        $userCounts = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create($year, $i, 1)->format('M');
            
            $count = User::whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();
            $userCounts[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $userCounts,
        ];
    }
    
    /**
     * Chart data aktivitas berdasarkan tahun
     */
    private function getActivityChartDataByYear($year)
    {
        $months = [];
        $activityCounts = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create($year, $i, 1)->format('M');
            
            $count = ActivityLog::whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();
            $activityCounts[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $activityCounts,
        ];
    }
    
    /**
     * Chart data satker berdasarkan tahun
     */
    private function getSatkerChartDataByYear($year)
    {
        $satkers = Satker::withCount(['users' => function($q) use ($year) {
            $q->whereYear('created_at', $year);
        }])->orderBy('users_count', 'desc')->limit(10)->get();
        
        $labels = $satkers->pluck('nama_satker')->toArray();
        $data = $satkers->pluck('users_count')->toArray();
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    
    /**
     * Chart data sistem berdasarkan tahun
     */
    private function getSystemChartDataByYear($year)
    {
        $months = [];
        $userData = [];
        $activityData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create($year, $i, 1)->format('M');
            
            $userData[] = User::whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();
                
            $activityData[] = ActivityLog::whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->count();
        }
        
        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'User Baru',
                    'data' => $userData,
                ],
                [
                    'label' => 'Aktivitas',
                    'data' => $activityData,
                ]
            ],
        ];
    }
    
    /**
     * Reset filter
     */
    public function resetFilter()
    {
        return redirect()->route('superadmin.reports');
    }
}