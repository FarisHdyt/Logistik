<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Satker;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

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
        
        // Data umum untuk view
        $data = [
            'title' => 'Laporan Sistem',
            'user' => Auth::user(),
            'reportType' => $reportType,
            'satkers' => Satker::orderBy('nama_satker')->get(),
        ];
        
        // Tambahkan data berdasarkan jenis laporan
        switch ($reportType) {
            case 'user':
                $data = array_merge($data, $this->getUserData());
                break;
                
            case 'activity':
                $data = array_merge($data, $this->getActivityData());
                break;
                
            case 'satker':
                $data = array_merge($data, $this->getSatkerData());
                break;
                
            case 'system':
                $data = array_merge($data, $this->getSystemData());
                break;
        }
        
        return view('superadmin.reports', $data);
    }
    
    /**
     * Data untuk laporan user
     */
    private function getUserData()
    {
        $query = User::with('satker');
        
        // Statistik
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalAdmins = User::where('role', 'admin')->where('is_active', true)->count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)->count();
        
        // Distribusi role
        $superadminCount = User::where('role', 'superadmin')->where('is_active', true)->count();
        $adminCount = User::where('role', 'admin')->where('is_active', true)->count();
        $userCount = User::where('role', 'user')->where('is_active', true)->count();
        
        // Data user (tanpa pagination)
        $users = $query->orderBy('created_at', 'desc')->get();
        
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
    private function getActivityData()
    {
        $query = ActivityLog::with('user');
        
        // Statistik
        $totalActivities = $query->count();
        $loginCount = ActivityLog::where('action', 'login')->count();
        $logoutCount = ActivityLog::where('action', 'logout')->count();
        $createCount = ActivityLog::where('action', 'create')->count();
        $updateCount = ActivityLog::where('action', 'update')->count();
        $deleteCount = ActivityLog::where('action', 'delete')->count();
        
        // Data aktivitas (tanpa pagination)
        $activities = $query->orderBy('created_at', 'desc')->get();
        
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
    private function getSatkerData()
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
        
        // Data satker (tanpa pagination)
        $satkers = $query->orderBy('nama_satker')->get();
        
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
    private function getSystemData()
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
     * Generate PDF report yang sederhana dan rapi
     */
    public function generatePdf(Request $request)
    {
        $reportType = $request->type ?? 'user';
        $fileName = 'laporan_' . $reportType . '_' . Carbon::now()->format('Ymd_His') . '.pdf';
        
        // Data berdasarkan jenis laporan
        switch ($reportType) {
            case 'user':
                $data = $this->getUserData();
                $html = $this->getUserPdfHtml($data);
                break;
                
            case 'activity':
                $data = $this->getActivityData();
                $html = $this->getActivityPdfHtml($data);
                break;
                
            case 'satker':
                $data = $this->getSatkerData();
                $html = $this->getSatkerPdfHtml($data);
                break;
                
            case 'system':
                $data = $this->getSystemData();
                $html = $this->getSystemPdfHtml($data);
                break;
        }
        
        // Generate PDF
        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'dpi' => 150,
            ]);
        
        return $pdf->download($fileName);
    }
    
    /**
     * HTML untuk PDF user (sederhana)
     */
    private function getUserPdfHtml($data)
    {
        $currentUser = Auth::user();
        $now = Carbon::now()->format('d/m/Y H:i:s');
        
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; font-size: 10pt; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { color: #333; margin: 5px 0; }
                .header p { color: #666; margin: 2px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #f2f2f2; padding: 8px; border: 1px solid #ddd; text-align: left; }
                td { padding: 6px; border: 1px solid #ddd; }
                .footer { margin-top: 20px; text-align: center; font-size: 8pt; color: #888; }
                .stats { margin: 10px 0; }
                .stat-item { display: inline-block; margin-right: 20px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN USER - SILOG POLRES</h1>
                <p>Dicetak: ' . $now . ' | Oleh: ' . $currentUser->name . '</p>
            </div>
            
            <div class="stats">
                <div class="stat-item"><strong>Total User:</strong> ' . $data['totalUsers'] . '</div>
                <div class="stat-item"><strong>User Aktif:</strong> ' . $data['activeUsers'] . '</div>
                <div class="stat-item"><strong>User Baru (Bulan Ini):</strong> ' . $data['newUsersThisMonth'] . '</div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Satker</th>
                        <th>Status</th>
                        <th>Terakhir Login</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($data['users'] as $index => $user) {
            $status = $user->is_active ? 'Aktif' : 'Nonaktif';
            $lastLogin = $user->last_login_at ? Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Belum login';
            
            $html .= '
                <tr>
                    <td>' . ($index + 1) . '</td>
                    <td>' . htmlspecialchars($user->name) . '</td>
                    <td>' . htmlspecialchars($user->username) . '</td>
                    <td>' . htmlspecialchars($user->email) . '</td>
                    <td>' . ucfirst($user->role) . '</td>
                    <td>' . htmlspecialchars($user->satker->nama_satker ?? '-') . '</td>
                    <td>' . $status . '</td>
                    <td>' . $lastLogin . '</td>
                </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                Halaman 1/1 | SILOG Polres | ' . $now . '
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * HTML untuk PDF aktivitas (sederhana)
     */
    private function getActivityPdfHtml($data)
    {
        $currentUser = Auth::user();
        $now = Carbon::now()->format('d/m/Y H:i:s');
        
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; font-size: 10pt; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { color: #333; margin: 5px 0; }
                .header p { color: #666; margin: 2px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #f2f2f2; padding: 8px; border: 1px solid #ddd; text-align: left; }
                td { padding: 6px; border: 1px solid #ddd; }
                .footer { margin-top: 20px; text-align: center; font-size: 8pt; color: #888; }
                .stats { margin: 10px 0; }
                .stat-item { display: inline-block; margin-right: 15px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN AKTIVITAS - SILOG POLRES</h1>
                <p>Dicetak: ' . $now . ' | Oleh: ' . $currentUser->name . '</p>
            </div>
            
            <div class="stats">
                <div class="stat-item"><strong>Total Aktivitas:</strong> ' . $data['totalActivities'] . '</div>
                <div class="stat-item"><strong>Login:</strong> ' . $data['loginCount'] . '</div>
                <div class="stat-item"><strong>Logout:</strong> ' . $data['logoutCount'] . '</div>
                <div class="stat-item"><strong>Create:</strong> ' . $data['createCount'] . '</div>
                <div class="stat-item"><strong>Update:</strong> ' . $data['updateCount'] . '</div>
                <div class="stat-item"><strong>Delete:</strong> ' . $data['deleteCount'] . '</div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP Address</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($data['activities'] as $index => $activity) {
            $html .= '
                <tr>
                    <td>' . ($index + 1) . '</td>
                    <td>' . htmlspecialchars($activity->user->name ?? 'System') . '</td>
                    <td>' . ucfirst($activity->action) . '</td>
                    <td>' . htmlspecialchars($activity->description) . '</td>
                    <td>' . htmlspecialchars($activity->ip_address) . '</td>
                    <td>' . Carbon::parse($activity->created_at)->format('d/m/Y H:i:s') . '</td>
                </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                Halaman 1/1 | SILOG Polres | ' . $now . '
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * HTML untuk PDF satker (sederhana)
     */
    private function getSatkerPdfHtml($data)
    {
        $currentUser = Auth::user();
        $now = Carbon::now()->format('d/m/Y H:i:s');
        
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; font-size: 10pt; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { color: #333; margin: 5px 0; }
                .header p { color: #666; margin: 2px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #f2f2f2; padding: 8px; border: 1px solid #ddd; text-align: left; }
                td { padding: 6px; border: 1px solid #ddd; }
                .footer { margin-top: 20px; text-align: center; font-size: 8pt; color: #888; }
                .stats { margin: 10px 0; }
                .stat-item { display: block; margin-bottom: 5px; }
                .top-satker { margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN SATKER - SILOG POLRES</h1>
                <p>Dicetak: ' . $now . ' | Oleh: ' . $currentUser->name . '</p>
            </div>
            
            <div class="stats">
                <div class="stat-item"><strong>Total Satker:</strong> ' . $data['totalSatker'] . '</div>
                <div class="stat-item"><strong>Total User:</strong> ' . $data['totalUsers'] . '</div>
                <div class="stat-item"><strong>Rata-rata User per Satker:</strong> ' . $data['averageUsersPerSatker'] . '</div>
            </div>
            
            <div class="top-satker">
                <strong>Top 5 Satker dengan User Terbanyak:</strong><br>';
        
        foreach ($data['topSatkers'] as $index => $satker) {
            $html .= ($index + 1) . '. ' . htmlspecialchars($satker->nama_satker) . ' (' . ($satker->users_count ?? 0) . ' user)<br>';
        }
        
        $html .= '
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Satker</th>
                        <th>Kode Satker</th>
                        <th>Jumlah User</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($data['satkers'] as $index => $satker) {
            $html .= '
                <tr>
                    <td>' . ($index + 1) . '</td>
                    <td>' . htmlspecialchars($satker->nama_satker) . '</td>
                    <td>' . htmlspecialchars($satker->kode_satker ?? '-') . '</td>
                    <td>' . ($satker->users_count ?? 0) . '</td>
                    <td>' . $satker->created_at->format('d/m/Y') . '</td>
                </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                Halaman 1/1 | SILOG Polres | ' . $now . '
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * HTML untuk PDF sistem (sederhana)
     */
    private function getSystemPdfHtml($data)
    {
        $currentUser = Auth::user();
        $now = Carbon::now()->format('d/m/Y H:i:s');
        
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; font-size: 10pt; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { color: #333; margin: 5px 0; }
                .header p { color: #666; margin: 2px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #f2f2f2; padding: 8px; border: 1px solid #ddd; text-align: left; }
                td { padding: 6px; border: 1px solid #ddd; }
                .footer { margin-top: 20px; text-align: center; font-size: 8pt; color: #888; }
                .today-stats { margin: 15px 0; padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN SISTEM - SILOG POLRES</h1>
                <p>Dicetak: ' . $now . ' | Oleh: ' . $currentUser->name . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th width="30%">Metrik</th>
                        <th width="70%">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Total User</td><td>' . $data['totalUsers'] . '</td></tr>
                    <tr><td>User Aktif</td><td>' . $data['activeUsers'] . '</td></tr>
                    <tr><td>Total Satker</td><td>' . $data['totalSatker'] . '</td></tr>
                    <tr><td>Total Aktivitas</td><td>' . $data['totalActivities'] . '</td></tr>
                    <tr><td>Uptime Sistem</td><td>' . $data['systemUptime'] . '</td></tr>
                    <tr><td>Backup Terakhir</td><td>' . $data['lastBackup'] . '</td></tr>
                </tbody>
            </table>
            
            <div class="today-stats">
                <strong>Statistik Hari Ini:</strong><br>
                • Aktivitas: ' . $data['todayActivities'] . '<br>
                • Login: ' . $data['todayLogins'] . '<br>
                • User Baru: ' . $data['newUsersToday'] . '
            </div>
            
            <div class="footer">
                Halaman 1/1 | SILOG Polres | ' . $now . ' | Laporan otomatis
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Export to Excel menggunakan PhpSpreadsheet
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->type ?? 'user';
        
        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set judul laporan
        $sheet->setTitle(ucfirst($reportType));
        
        // Header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '8B5CF6'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        
        // Cell style
        $cellStyle = [
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        
        // Generate data berdasarkan tipe laporan
        switch ($reportType) {
            case 'user':
                $this->generateUserExcel($spreadsheet, $sheet, $headerStyle, $cellStyle);
                $fileName = 'laporan_user_' . Carbon::now()->format('Ymd_His') . '.xlsx';
                break;
                
            case 'activity':
                $this->generateActivityExcel($spreadsheet, $sheet, $headerStyle, $cellStyle);
                $fileName = 'laporan_aktivitas_' . Carbon::now()->format('Ymd_His') . '.xlsx';
                break;
                
            case 'satker':
                $this->generateSatkerExcel($spreadsheet, $sheet, $headerStyle, $cellStyle);
                $fileName = 'laporan_satker_' . Carbon::now()->format('Ymd_His') . '.xlsx';
                break;
                
            case 'system':
                $this->generateSystemExcel($spreadsheet, $sheet, $headerStyle, $cellStyle);
                $fileName = 'laporan_sistem_' . Carbon::now()->format('Ymd_His') . '.xlsx';
                break;
        }
        
        // Auto size columns
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Create writer and output
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Generate Excel untuk laporan user
     */
    private function generateUserExcel($spreadsheet, $sheet, $headerStyle, $cellStyle)
    {
        $data = $this->getUserData();
        $row = 1;
        
        // Judul
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'LAPORAN USER - SILOG POLRES');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        
        // Tanggal cetak
        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'Dicetak pada: ' . Carbon::now()->format('d/m/Y H:i:s'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        
        // Statistik
        $sheet->mergeCells('A4:I4');
        $sheet->setCellValue('A4', 'STATISTIK USER');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $row = 5;
        
        $statistics = [
            ['Total User', $data['totalUsers']],
            ['User Aktif', $data['activeUsers']],
            ['User Baru (Bulan Ini)', $data['newUsersThisMonth']],
            ['Superadmin', $data['superadminCount']],
            ['Admin', $data['adminCount']],
            ['User', $data['userCount']],
        ];
        
        foreach ($statistics as $index => $stat) {
            $sheet->setCellValue('A' . $row, $stat[0]);
            $sheet->setCellValue('B' . $row, $stat[1]);
            $row++;
        }
        
        $row += 2; // Spasi
        
        // Header tabel
        $headers = ['No', 'Nama', 'Username', 'Email', 'Role', 'Satker', 'Status', 'Terakhir Login', 'Tanggal Dibuat'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->applyFromArray($headerStyle);
            $col++;
        }
        $row++;
        
        // Data user
        foreach ($data['users'] as $index => $user) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $user->name);
            $sheet->setCellValue('C' . $row, $user->username);
            $sheet->setCellValue('D' . $row, $user->email);
            $sheet->setCellValue('E' . $row, ucfirst($user->role));
            $sheet->setCellValue('F' . $row, $user->satker->nama_satker ?? '-');
            $sheet->setCellValue('G' . $row, $user->is_active ? 'Aktif' : 'Nonaktif');
            $sheet->setCellValue('H' . $row, $user->last_login_at ? Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Belum login');
            $sheet->setCellValue('I' . $row, $user->created_at->format('d/m/Y'));
            
            // Apply cell style
            for ($col = 'A'; $col <= 'I'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray($cellStyle);
            }
            
            $row++;
        }
    }
    
    /**
     * Generate Excel untuk laporan aktivitas
     */
    private function generateActivityExcel($spreadsheet, $sheet, $headerStyle, $cellStyle)
    {
        $data = $this->getActivityData();
        $row = 1;
        
        // Judul
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN AKTIVITAS - SILOG POLRES');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        
        // Tanggal cetak
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'Dicetak pada: ' . Carbon::now()->format('d/m/Y H:i:s'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        
        // Statistik
        $sheet->mergeCells('A4:F4');
        $sheet->setCellValue('A4', 'STATISTIK AKTIVITAS');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $row = 5;
        
        $statistics = [
            ['Total Aktivitas', $data['totalActivities']],
            ['Login', $data['loginCount']],
            ['Logout', $data['logoutCount']],
            ['Create', $data['createCount']],
            ['Update', $data['updateCount']],
            ['Delete', $data['deleteCount']],
        ];
        
        foreach ($statistics as $index => $stat) {
            $sheet->setCellValue('A' . $row, $stat[0]);
            $sheet->setCellValue('B' . $row, $stat[1]);
            $row++;
        }
        
        $row += 2; // Spasi
        
        // Header tabel
        $headers = ['No', 'User', 'Aksi', 'Deskripsi', 'IP Address', 'Waktu'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->applyFromArray($headerStyle);
            $col++;
        }
        $row++;
        
        // Data aktivitas
        foreach ($data['activities'] as $index => $activity) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $activity->user->name ?? 'System');
            $sheet->setCellValue('C' . $row, ucfirst($activity->action));
            $sheet->setCellValue('D' . $row, $activity->description);
            $sheet->setCellValue('E' . $row, $activity->ip_address);
            $sheet->setCellValue('F' . $row, Carbon::parse($activity->created_at)->format('d/m/Y H:i:s'));
            
            // Apply cell style
            for ($col = 'A'; $col <= 'F'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray($cellStyle);
            }
            
            $row++;
        }
    }
    
    /**
     * Generate Excel untuk laporan satker
     */
    private function generateSatkerExcel($spreadsheet, $sheet, $headerStyle, $cellStyle)
    {
        $data = $this->getSatkerData();
        $row = 1;
        
        // Judul
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'LAPORAN SATKER - SILOG POLRES');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        
        // Tanggal cetak
        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', 'Dicetak pada: ' . Carbon::now()->format('d/m/Y H:i:s'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        
        // Statistik
        $sheet->mergeCells('A4:E4');
        $sheet->setCellValue('A4', 'STATISTIK SATKER');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $row = 5;
        
        $statistics = [
            ['Total Satker', $data['totalSatker']],
            ['Total User', $data['totalUsers']],
            ['Rata-rata User per Satker', $data['averageUsersPerSatker']],
        ];
        
        foreach ($statistics as $index => $stat) {
            $sheet->setCellValue('A' . $row, $stat[0]);
            $sheet->setCellValue('B' . $row, $stat[1]);
            $row++;
        }
        
        $row += 1; // Spasi
        
        // Top 5 Satker
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->setCellValue('A' . $row, 'TOP 5 SATKER DENGAN USER TERBANYAK');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $topHeaders = ['No', 'Nama Satker', 'Kode Satker', 'Jumlah User'];
        $col = 'A';
        foreach ($topHeaders as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->applyFromArray($headerStyle);
            $col++;
        }
        $row++;
        
        foreach ($data['topSatkers'] as $index => $satker) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $satker->nama_satker);
            $sheet->setCellValue('C' . $row, $satker->kode_satker ?? '-');
            $sheet->setCellValue('D' . $row, $satker->users_count ?? 0);
            
            for ($col = 'A'; $col <= 'D'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray($cellStyle);
            }
            
            $row++;
        }
        
        $row += 2; // Spasi
        
        // Header tabel lengkap
        $headers = ['No', 'Nama Satker', 'Kode Satker', 'Jumlah User', 'Tanggal Dibuat'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->applyFromArray($headerStyle);
            $col++;
        }
        $row++;
        
        // Data satker lengkap
        foreach ($data['satkers'] as $index => $satker) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $satker->nama_satker);
            $sheet->setCellValue('C' . $row, $satker->kode_satker ?? '-');
            $sheet->setCellValue('D' . $row, $satker->users_count ?? 0);
            $sheet->setCellValue('E' . $row, $satker->created_at->format('d/m/Y'));
            
            // Apply cell style
            for ($col = 'A'; $col <= 'E'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray($cellStyle);
            }
            
            $row++;
        }
    }
    
    /**
     * Generate Excel untuk laporan sistem
     */
    private function generateSystemExcel($spreadsheet, $sheet, $headerStyle, $cellStyle)
    {
        $data = $this->getSystemData();
        $row = 1;
        
        // Judul
        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('A1', 'LAPORAN SISTEM - SILOG POLRES');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        
        // Tanggal cetak
        $sheet->mergeCells('A2:B2');
        $sheet->setCellValue('A2', 'Dicetak pada: ' . Carbon::now()->format('d/m/Y H:i:s'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;
        
        // Header tabel
        $headers = ['Metrik', 'Nilai'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->applyFromArray($headerStyle);
            $col++;
        }
        $row++;
        
        // Data sistem
        $systemData = [
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
        
        foreach ($systemData as $item) {
            $sheet->setCellValue('A' . $row, $item[0]);
            $sheet->setCellValue('B' . $row, $item[1]);
            
            // Apply cell style
            $sheet->getStyle('A' . $row)->applyFromArray($cellStyle);
            $sheet->getStyle('B' . $row)->applyFromArray($cellStyle);
            
            $row++;
        }
    }
    
    /**
     * Download CSV sederhana
     */
    public function downloadCsv(Request $request)
    {
        $reportType = $request->type ?? 'user';
        $filename = 'laporan_' . $reportType . '_' . Carbon::now()->format('Ymd_His') . '.csv';
        
        // Data berdasarkan jenis laporan
        switch ($reportType) {
            case 'user':
                $data = $this->getUserData();
                $headers = ['No', 'Nama', 'Username', 'Email', 'Role', 'Satker', 'Status', 'Terakhir Login', 'Tanggal Dibuat'];
                break;
                
            case 'activity':
                $data = $this->getActivityData();
                $headers = ['No', 'User', 'Aksi', 'Deskripsi', 'IP Address', 'Waktu'];
                break;
                
            case 'satker':
                $data = $this->getSatkerData();
                $headers = ['No', 'Nama Satker', 'Kode Satker', 'Jumlah User', 'Tanggal Dibuat'];
                break;
                
            case 'system':
                $data = $this->getSystemData();
                $headers = ['Metrik', 'Nilai'];
                break;
        }
        
        // Set headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Buka output stream
        $output = fopen('php://output', 'w');
        
        // Tambahkan BOM untuk UTF-8
        fwrite($output, "\xEF\xBB\xBF");
        
        // Tulis header
        fputcsv($output, $headers);
        
        // Tulis data
        switch ($reportType) {
            case 'user':
                foreach ($data['users'] as $index => $user) {
                    fputcsv($output, [
                        $index + 1,
                        $user->name,
                        $user->username,
                        $user->email,
                        ucfirst($user->role),
                        $user->satker->nama_satker ?? '-',
                        $user->is_active ? 'Aktif' : 'Nonaktif',
                        $user->last_login_at ? Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Belum login',
                        $user->created_at->format('d/m/Y'),
                    ]);
                }
                break;
                
            case 'activity':
                foreach ($data['activities'] as $index => $activity) {
                    fputcsv($output, [
                        $index + 1,
                        $activity->user->name ?? 'System',
                        ucfirst($activity->action),
                        $activity->description,
                        $activity->ip_address,
                        Carbon::parse($activity->created_at)->format('d/m/Y H:i:s'),
                    ]);
                }
                break;
                
            case 'satker':
                foreach ($data['satkers'] as $index => $satker) {
                    fputcsv($output, [
                        $index + 1,
                        $satker->nama_satker,
                        $satker->kode_satker ?? '-',
                        $satker->users_count ?? 0,
                        $satker->created_at->format('d/m/Y'),
                    ]);
                }
                break;
                
            case 'system':
                fputcsv($output, ['Total User', $data['totalUsers']]);
                fputcsv($output, ['User Aktif', $data['activeUsers']]);
                fputcsv($output, ['Total Satker', $data['totalSatker']]);
                fputcsv($output, ['Total Aktivitas', $data['totalActivities']]);
                fputcsv($output, ['Aktivitas Hari Ini', $data['todayActivities']]);
                fputcsv($output, ['Login Hari Ini', $data['todayLogins']]);
                fputcsv($output, ['User Baru Hari Ini', $data['newUsersToday']]);
                fputcsv($output, ['Uptime Sistem', $data['systemUptime']]);
                fputcsv($output, ['Backup Terakhir', $data['lastBackup']]);
                break;
        }
        
        fclose($output);
        exit;
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