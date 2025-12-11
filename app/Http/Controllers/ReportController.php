<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Permintaan;
use App\Models\Pengeluaran;
use App\Models\Satker;
use App\Models\Satuan;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Gudang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Data statistik utama
        $stats = [
            'total_items' => Barang::count(),
            'total_requests' => Permintaan::count(),
            'total_expenditures' => Pengeluaran::count(),
            'total_users' => User::count(),
            'total_satker' => Satker::count(),
            'total_categories' => Kategori::count(),
            'total_warehouses' => Gudang::count(),
            'total_units' => Satuan::count(),
            'good_stock' => Barang::whereRaw('stok > stok_minimal * 2')->count(),
            'low_stock' => Barang::whereRaw('stok <= stok_minimal * 2')->where('stok', '>', 0)->count(),
            'critical_stock' => Barang::whereRaw('stok <= stok_minimal')->where('stok', '>', 0)->count(),
            'out_of_stock' => Barang::where('stok', '<=', 0)->count(),
            'pending_requests' => Permintaan::where('status', 'pending')->count(),
            'approved_requests' => Permintaan::where('status', 'approved')->count(),
            'rejected_requests' => Permintaan::where('status', 'rejected')->count(),
            'processing_requests' => Permintaan::where('status', 'processing')->count(),
            'delivered_requests' => Permintaan::where('status', 'delivered')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'user_users' => User::where('role', 'user')->count(),
            'operator_users' => User::where('role', 'operator')->count(),
        ];
        
        // Data untuk chart permintaan bulanan (12 bulan terakhir)
        $monthlyRequestsData = $this->getMonthlyRequestsData();
        
        // Data untuk chart status permintaan
        $requestStatusData = $this->getRequestStatusData();
        
        // Data untuk chart stok barang berdasarkan kategori
        $inventoryByCategoryData = $this->getInventoryByCategoryData();
        
        // Data untuk chart pengguna berdasarkan role
        $usersByRoleData = $this->getUsersByRoleData();
        
        return view('admin.reports', compact('user', 'stats', 'monthlyRequestsData', 'requestStatusData', 'inventoryByCategoryData', 'usersByRoleData'));
    }
    
    /**
     * Get monthly requests data for chart (12 months)
     */
    private function getMonthlyRequestsData()
    {
        $data = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            // Hitung total permintaan per bulan
            $count = Permintaan::whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            $data[] = $count;
            $labels[] = $month->translatedFormat('M'); // Format: Jan, Feb, Mar, dst
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Get request status data for pie chart
     */
    private function getRequestStatusData()
    {
        $statuses = ['pending', 'approved', 'rejected', 'processing', 'delivered'];
        $data = [];
        $total = Permintaan::count();
        
        foreach ($statuses as $status) {
            $count = Permintaan::where('status', $status)->count();
            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            
            $data[$status] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }
        
        return $data;
    }
    
    /**
     * Get inventory data by category for chart
     */
    private function getInventoryByCategoryData()
    {
        $categories = Kategori::withCount(['barang' => function($query) {
            $query->where('stok', '>', 0);
        }])->get();
        
        $labels = [];
        $data = [];
        $backgroundColors = [
            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
            '#06b6d4', '#84cc16', '#f97316', '#6366f1', '#ec4899'
        ];
        
        foreach ($categories as $index => $category) {
            $labels[] = $category->nama_kategori;
            $data[] = $category->barang_count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($backgroundColors, 0, count($labels))
        ];
    }
    
    /**
     * Get users data by role for chart
     */
    private function getUsersByRoleData()
    {
        $roles = ['admin', 'user', 'operator'];
        $labels = ['Administrator', 'User', 'Operator'];
        $data = [];
        $colors = ['#3b82f6', '#10b981', '#f59e0b'];
        
        foreach ($roles as $index => $role) {
            $count = User::where('role', $role)->count();
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors
        ];
    }
    
    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:inventory,requests,expenditures,users,satker,summary',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $type = $request->report_type;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        switch ($type) {
            case 'inventory':
                $data = Barang::with(['kategori', 'satuan', 'gudang'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                $view = 'reports.inventory';
                break;
                
            case 'requests':
                $data = Permintaan::with(['user', 'barang', 'satker'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                $view = 'reports.requests';
                break;
                
            case 'expenditures':
                $data = Pengeluaran::with(['user', 'barang'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                $view = 'reports.expenditures';
                break;
                
            case 'users':
                $data = User::with('satker')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                $view = 'reports.users';
                break;
                
            case 'satker':
                $data = Satker::withCount(['users', 'permintaans'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                $view = 'reports.satker';
                break;
                
            default:
                // Summary report
                $inventoryData = Barang::with('kategori')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                $requestsData = Permintaan::with(['user', 'barang'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                $expendituresData = Pengeluaran::with(['user', 'barang'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                $usersData = User::with('satker')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                $data = [
                    'inventory' => $inventoryData,
                    'requests' => $requestsData,
                    'expenditures' => $expendituresData,
                    'users' => $usersData,
                ];
                $view = 'reports.summary';
        }
        
        return view($view, compact('data', 'type', 'startDate', 'endDate'));
    }
    
    public function export(Request $request, $type = null)
    {
        // Jika type dari parameter route
        if (!$type) {
            $type = $request->type;
        }
        
        $request->validate([
            'type' => 'sometimes|required|in:inventory,requests,expenditures,users,satker',
            'format' => 'required|in:csv,excel,pdf',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $format = $request->format;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : null;
        
        switch ($type) {
            case 'inventory':
                $query = Barang::with(['kategori', 'satuan', 'gudang']);
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $data = $query->get();
                
                $filename = 'laporan-barang-' . date('Y-m-d');
                $headers = [
                    'Kode Barang', 'Nama Barang', 'Kategori', 'Stok', 
                    'Stok Minimal', 'Satuan', 'Gudang', 'Lokasi', 
                    'Harga Beli', 'Harga Jual', 'Status Stok'
                ];
                $rows = [];
                foreach ($data as $item) {
                    $status = $item->stok <= 0 ? 'Habis' : 
                              ($item->stok <= $item->stok_minimal ? 'Kritis' : 
                              ($item->stok <= $item->stok_minimal * 2 ? 'Rendah' : 'Baik'));
                    
                    $rows[] = [
                        $item->kode_barang,
                        $item->nama_barang,
                        $item->kategori->nama_kategori ?? '',
                        $item->stok,
                        $item->stok_minimal,
                        $item->satuan->nama_satuan ?? '',
                        $item->gudang->nama_gudang ?? '',
                        $item->lokasi,
                        $item->harga_beli,
                        $item->harga_jual,
                        $status
                    ];
                }
                break;
                
            case 'requests':
                $query = Permintaan::with(['user', 'barang', 'satker', 'approved_by_user']);
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $data = $query->get();
                
                $filename = 'laporan-permintaan-' . date('Y-m-d');
                $headers = [
                    'Kode Permintaan', 'Tanggal', 'Pemohon', 'Satker',
                    'Barang', 'Jumlah', 'Satuan', 'Keperluan',
                    'Status', 'Disetujui Oleh', 'Tanggal Persetujuan'
                ];
                $rows = [];
                foreach ($data as $requestItem) {
                    $rows[] = [
                        $requestItem->kode_permintaan,
                        $requestItem->created_at->format('d/m/Y'),
                        $requestItem->user->name ?? '',
                        $requestItem->satker->nama_satker ?? '',
                        $requestItem->barang->nama_barang ?? '',
                        $requestItem->jumlah,
                        $requestItem->barang->satuan->nama_satuan ?? '',
                        $requestItem->keperluan,
                        $this->getStatusText($requestItem->status),
                        $requestItem->approved_by_user->name ?? '',
                        $requestItem->approved_at ? $requestItem->approved_at->format('d/m/Y H:i') : ''
                    ];
                }
                break;
                
            case 'expenditures':
                $query = Pengeluaran::with(['user', 'barang']);
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $data = $query->get();
                
                $filename = 'laporan-pengeluaran-' . date('Y-m-d');
                $headers = [
                    'Kode Pengeluaran', 'Tanggal', 'Barang', 'Jumlah',
                    'Satuan', 'Penerima', 'Keperluan', 'Lokasi',
                    'Dikeluarkan Oleh'
                ];
                $rows = [];
                foreach ($data as $expenditure) {
                    $rows[] = [
                        $expenditure->kode_pengeluaran,
                        $expenditure->created_at->format('d/m/Y'),
                        $expenditure->barang->nama_barang ?? '',
                        $expenditure->jumlah,
                        $expenditure->barang->satuan->nama_satuan ?? '',
                        $expenditure->penerima,
                        $expenditure->keperluan,
                        $expenditure->lokasi,
                        $expenditure->user->name ?? ''
                    ];
                }
                break;
                
            case 'users':
                $query = User::with('satker');
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $data = $query->get();
                
                $filename = 'laporan-user-' . date('Y-m-d');
                $headers = [
                    'Nama', 'Email', 'Role', 'Satker',
                    'Tanggal Bergabung', 'Status'
                ];
                $rows = [];
                foreach ($data as $user) {
                    $rows[] = [
                        $user->name,
                        $user->email,
                        $this->getRoleText($user->role),
                        $user->satker->nama_satker ?? '',
                        $user->created_at->format('d/m/Y'),
                        $user->is_active ? 'Aktif' : 'Nonaktif'
                    ];
                }
                break;
                
            case 'satker':
                $query = Satker::withCount(['users', 'permintaans']);
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $data = $query->get();
                
                $filename = 'laporan-satker-' . date('Y-m-d');
                $headers = [
                    'Kode Satker', 'Nama Satker', 'Alamat', 'Telepon',
                    'Jumlah User', 'Jumlah Permintaan', 'Tanggal Dibuat'
                ];
                $rows = [];
                foreach ($data as $satker) {
                    $rows[] = [
                        $satker->kode_satker,
                        $satker->nama_satker,
                        $satker->alamat,
                        $satker->telepon,
                        $satker->users_count,
                        $satker->permintaans_count,
                        $satker->created_at->format('d/m/Y')
                    ];
                }
                break;
                
            default:
                return back()->with('error', 'Jenis laporan tidak valid');
        }
        
        // Return based on format
        if ($format === 'csv') {
            $filename .= '.csv';
            return $this->exportToCsv($headers, $rows, $filename);
        } elseif ($format === 'excel') {
            $filename .= '.xlsx';
            return $this->exportToExcel($headers, $rows, $filename);
        } else {
            $filename .= '.pdf';
            return $this->exportToPdf($headers, $rows, $filename, $type);
        }
    }
    
    /**
     * Get chart data via AJAX
     */
    public function getChartData(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        // Monthly requests data
        $monthlyData = [];
        $monthlyLabels = [];
        
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();
            
            $count = Permintaan::whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            $monthlyData[] = $count;
            $monthlyLabels[] = $current->translatedFormat('M Y');
            
            $current->addMonth();
        }
        
        // Status data
        $statusData = [
            'pending' => Permintaan::where('status', 'pending')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'approved' => Permintaan::where('status', 'approved')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'rejected' => Permintaan::where('status', 'rejected')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'processing' => Permintaan::where('status', 'processing')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'delivered' => Permintaan::where('status', 'delivered')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
        ];
        
        return response()->json([
            'monthly_labels' => $monthlyLabels,
            'monthly_data' => $monthlyData,
            'status_data' => $statusData
        ]);
    }
    
    private function exportToCsv($headers, $rows, $filename)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $output = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            fputcsv($output, $headers);
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
        }, $filename);
    }
    
    private function exportToExcel($headers, $rows, $filename)
    {
        // In a real implementation, you would use Maatwebsite/Laravel-Excel
        // For now, return CSV with Excel extension
        return $this->exportToCsv($headers, $rows, $filename);
    }
    
    private function exportToPdf($headers, $rows, $filename, $type)
    {
        // In a real implementation, you would use DomPDF or similar
        // For now, return a message
        return response()->json([
            'message' => 'PDF export for ' . $type . ' is not implemented yet.',
            'filename' => $filename,
            'headers' => $headers,
            'row_count' => count($rows)
        ]);
    }
    
    private function getStatusText($status)
    {
        switch ($status) {
            case 'pending': return 'Pending';
            case 'approved': return 'Disetujui';
            case 'rejected': return 'Ditolak';
            case 'processing': return 'Diproses';
            case 'delivered': return 'Terkirim';
            default: return $status;
        }
    }
    
    private function getRoleText($role)
    {
        switch ($role) {
            case 'admin': return 'Administrator';
            case 'user': return 'User';
            case 'operator': return 'Operator';
            default: return $role;
        }
    }
}
