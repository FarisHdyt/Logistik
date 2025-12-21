<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Permintaan;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Data statistik utama
        $stats = $this->getGlobalStats();
        
        // Data untuk charts
        $monthlyRequestsData = $this->getMonthlyRequestsData();
        $requestStatusData = $this->getRequestStatusData();
        
        // Data statistik bulanan default
        $selectedMonth = request('month', date('Y-m'));
        $monthlyStats = $this->getMonthlyStatistics($selectedMonth);
        
        $data = compact(
            'user', 
            'stats', 
            'monthlyRequestsData', 
            'requestStatusData',
            'monthlyStats',
            'selectedMonth'
        );
        
        return view('admin.reports', $data);
    }
    
    /**
     * Get global statistics
     */
    private function getGlobalStats()
    {
        return [
            'total_items' => Barang::count(),
            'total_requests' => Permintaan::count(),
            'total_expenditures' => Permintaan::where('status', 'delivered')->count(),
            'total_categories' => Kategori::count(),
            'good_stock' => Barang::whereRaw('stok > stok_minimal * 2')->count(),
            'low_stock' => Barang::whereRaw('stok <= stok_minimal * 2')->where('stok', '>', 0)->count(),
            'critical_stock' => Barang::whereRaw('stok <= stok_minimal')->where('stok', '>', 0)->count(),
            'out_of_stock' => Barang::where('stok', '<=', 0)->count(),
            'pending_requests' => Permintaan::where('status', 'pending')->count(),
            'approved_requests' => Permintaan::where('status', 'approved')->count(),
            'rejected_requests' => Permintaan::where('status', 'rejected')->count(),
            'processing_requests' => Permintaan::where('status', 'processing')->count(),
            'delivered_requests' => Permintaan::where('status', 'delivered')->count(),
        ];
    }
    
    /**
     * Get monthly statistics for specific month
     */
    public function getMonthlyStats(Request $request)
    {
        try {
            $request->validate(['month' => 'required|date_format:Y-m']);
            $stats = $this->getMonthlyStatistics($request->month);
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Helper method to get monthly statistics
     */
    private function getMonthlyStatistics($month)
    {
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();
        
        // Inventory stats
        $inventoryItems = Barang::whereBetween('created_at', [$startDate, $endDate])->get();
        
        // Requests stats
        $requests = Permintaan::whereBetween('created_at', [$startDate, $endDate])->get();
        
        // Expenditures stats
        $expenditures = Permintaan::where('status', 'delivered')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereNotNull('delivered_at')
                      ->whereBetween('delivered_at', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->whereNull('delivered_at')
                            ->whereBetween('updated_at', [$startDate, $endDate]);
                      });
            })->count();
        
        return [
            'total_items' => $inventoryItems->count(),
            'good_stock' => $inventoryItems->filter(fn($item) => $item->stok > $item->stok_minimal * 2)->count(),
            'low_stock' => $inventoryItems->filter(fn($item) => $item->stok <= $item->stok_minimal * 2 && $item->stok > $item->stok_minimal && $item->stok > 0)->count(),
            'critical_stock' => $inventoryItems->filter(fn($item) => $item->stok <= $item->stok_minimal && $item->stok > 0)->count(),
            'out_of_stock' => $inventoryItems->filter(fn($item) => $item->stok <= 0)->count(),
            'total_requests' => $requests->count(),
            'pending_requests' => $requests->where('status', 'pending')->count(),
            'approved_requests' => $requests->where('status', 'approved')->count(),
            'rejected_requests' => $requests->where('status', 'rejected')->count(),
            'delivered_requests' => $requests->where('status', 'delivered')->count(),
            'total_expenditures' => $expenditures,
        ];
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
            
            $count = Permintaan::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $data[] = $count;
            $labels[] = $month->translatedFormat('M');
        }
        
        return compact('labels', 'data');
    }
    
    /**
     * Get request status data for pie chart
     */
    private function getRequestStatusData()
    {
        $statuses = ['pending', 'approved', 'rejected', 'processing', 'delivered'];
        $data = [];
        
        foreach ($statuses as $status) {
            $count = Permintaan::where('status', $status)->count();
            $total = Permintaan::count();
            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            
            $data[$status] = compact('count', 'percentage');
        }
        
        return $data;
    }
    
    /**
     * Method untuk menampilkan detail laporan via AJAX
     */
    public function viewDetails(Request $request)
    {
        try {
            $request->validate([
                'report_type' => 'required|in:inventory,requests,expenditures',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);
            
            $type = $request->report_type;
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
            
            return $this->generateReportTable($type, $startDate, $endDate);
            
        } catch (\Exception $e) {
            Log::error('Error in viewDetails: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return '<div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Terjadi kesalahan sistem: ' . htmlspecialchars($e->getMessage()) . '
                <br><small>File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '</small>
            </div>';
        }
    }
    
    /**
     * Generate HTML table for report details
     */
    private function generateReportTable($type, $startDate, $endDate)
    {
        try {
            switch ($type) {
                case 'inventory':
                    return $this->generateInventoryTable($startDate, $endDate);
                case 'requests':
                    return $this->generateRequestsTable($startDate, $endDate);
                case 'expenditures':
                    return $this->generateExpendituresTable($startDate, $endDate);
                default:
                    return '<div class="alert alert-info">Jenis laporan tidak valid.</div>';
            }
        } catch (\Exception $e) {
            Log::error('Error generating report table: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return '<div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Terjadi kesalahan saat memuat data: ' . htmlspecialchars($e->getMessage()) . '
                <br><small>File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '</small>
            </div>';
        }
    }
    
    /**
     * Generate inventory report table
     */
    private function generateInventoryTable($startDate, $endDate)
    {
        $data = Barang::with(['kategori', 'satuan', 'gudang'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        
        if ($data->isEmpty()) {
            return '<tr><td colspan="9" class="text-center py-4">Tidak ada data barang dalam periode yang dipilih.</td></tr>';
        }
        
        $html = '';
        $no = 1;
        foreach ($data as $item) {
            $status = $item->stok <= 0 ? 'Habis' : 
                     ($item->stok <= $item->stok_minimal ? 'Kritis' : 
                     ($item->stok <= $item->stok_minimal * 2 ? 'Rendah' : 'Baik'));
            
            $statusClass = $item->stok <= 0 ? 'bg-danger' : 
                         ($item->stok <= $item->stok_minimal ? 'bg-danger' : 
                         ($item->stok <= $item->stok_minimal * 2 ? 'bg-warning' : 'bg-success'));
            
            $html .= '<tr>';
            $html .= '<td>' . $no++ . '</td>';
            $html .= '<td>' . $item->kode_barang . '</td>';
            $html .= '<td>' . $item->nama_barang . '</td>';
            $html .= '<td>' . ($item->kategori->nama_kategori ?? '-') . '</td>';
            $html .= '<td>' . $item->stok . '</td>';
            $html .= '<td>' . $item->stok_minimal . '</td>';
            $html .= '<td>' . ($item->satuan->nama_satuan ?? '-') . '</td>';
            $html .= '<td>' . ($item->gudang->nama_gudang ?? '-') . '</td>';
            $html .= '<td><span class="badge ' . $statusClass . '">' . $status . '</span></td>';
            $html .= '</tr>';
        }
        
        return $html;
    }
    
    /**
     * Generate requests report table
     */
    private function generateRequestsTable($startDate, $endDate)
    {
        $data = Permintaan::with(['user', 'barang', 'satker'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        
        if ($data->isEmpty()) {
            return '<tr><td colspan="9" class="text-center py-4">Tidak ada data permintaan dalam periode yang dipilih.</td></tr>';
        }
        
        $html = '';
        $no = 1;
        foreach ($data as $item) {
            $statusText = $this->getStatusText($item->status);
            $statusClass = $this->getStatusClass($item->status);
            
            $html .= '<tr>';
            $html .= '<td>' . $no++ . '</td>';
            $html .= '<td>' . $item->kode_permintaan . '</td>';
            $html .= '<td>' . $item->created_at->format('d/m/Y H:i') . '</td>';
            $html .= '<td>' . ($item->user->name ?? '-') . '</td>';
            $html .= '<td>' . ($item->satker->nama_satker ?? '-') . '</td>';
            $html .= '<td>' . ($item->barang->nama_barang ?? '-') . '</td>';
            $html .= '<td>' . $item->jumlah . ' ' . ($item->barang->satuan->nama_satuan ?? '') . '</td>';
            $html .= '<td>' . $item->keperluan . '</td>';
            $html .= '<td><span class="badge ' . $statusClass . '">' . $statusText . '</span></td>';
            $html .= '</tr>';
        }
        
        return $html;
    }
    
    /**
     * Generate expenditures report table
     */
    private function generateExpendituresTable($startDate, $endDate)
    {
        $data = Permintaan::with(['user', 'barang', 'satker'])
            ->where('status', 'delivered')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereNotNull('delivered_at')
                      ->whereBetween('delivered_at', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->whereNull('delivered_at')
                            ->whereBetween('updated_at', [$startDate, $endDate]);
                      });
            })
            ->get();
        
        if ($data->isEmpty()) {
            return '<tr><td colspan="8" class="text-center py-4">Tidak ada data pengeluaran dalam periode yang dipilih.</td></tr>';
        }
        
        $html = '';
        $no = 1;
        foreach ($data as $item) {
            $tanggal = '-';
            if ($item->delivered_at) {
                try {
                    $tanggal = Carbon::parse($item->delivered_at)->format('d/m/Y H:i');
                } catch (\Exception $e) {
                    $tanggal = $item->delivered_at;
                }
            } elseif ($item->updated_at) {
                $tanggal = $item->updated_at->format('d/m/Y H:i') . ' (update)';
            }
            
            $html .= '<tr>';
            $html .= '<td>' . $no++ . '</td>';
            $html .= '<td>' . $item->kode_permintaan . '</td>';
            $html .= '<td>' . $tanggal . '</td>';
            $html .= '<td>' . ($item->barang->nama_barang ?? '-') . '</td>';
            $html .= '<td>' . $item->jumlah . '</td>';
            $html .= '<td>' . ($item->barang->satuan->nama_satuan ?? '-') . '</td>';
            $html .= '<td>' . ($item->satker->nama_satker ?? '-') . '</td>';
            $html .= '<td>' . ($item->keperluan ?? '-') . '</td>';
            $html .= '</tr>';
        }
        
        return $html;
    }
    
    public function export(Request $request, $type = null)
    {
        try {
            if (!$type) {
                $type = $request->type;
            }
            
            $request->validate([
                'type' => 'sometimes|required|in:inventory,requests,expenditures',
                'format' => 'required|in:csv,excel,pdf',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);
            
            $format = $request->format;
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : null;
            
            switch ($type) {
                case 'inventory':
                    return $this->exportInventory($format, $startDate, $endDate);
                case 'requests':
                    return $this->exportRequests($format, $startDate, $endDate);
                case 'expenditures':
                    return $this->exportExpenditures($format, $startDate, $endDate);
                default:
                    return back()->with('error', 'Jenis laporan tidak valid');
            }
        } catch (\Exception $e) {
            Log::error('Error in export: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage());
        }
    }
    
    /**
     * Export inventory data
     */
    private function exportInventory($format, $startDate, $endDate)
    {
        $query = Barang::with(['kategori', 'satuan', 'gudang']);
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $data = $query->get();
        
        $filename = 'laporan-barang-' . date('Y-m-d');
        $headers = ['Kode Barang', 'Nama Barang', 'Kategori', 'Stok', 'Stok Minimal', 'Satuan', 'Gudang', 'Lokasi', 'Harga Beli', 'Harga Jual', 'Status Stok'];
        
        $rows = $data->map(function($item) {
            $status = $item->stok <= 0 ? 'Habis' : 
                     ($item->stok <= $item->stok_minimal ? 'Kritis' : 
                     ($item->stok <= $item->stok_minimal * 2 ? 'Rendah' : 'Baik'));
            
            return [
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
        });
        
        // Pilih format berdasarkan parameter
        switch ($format) {
            case 'pdf':
                return $this->exportToPdf('Laporan Stok Barang', $headers, $rows->toArray(), $filename . '.pdf');
            case 'excel':
                return $this->exportToExcel('Laporan Stok Barang', $headers, $rows->toArray(), $filename);
            default: // csv
                return $this->exportToCsv($headers, $rows->toArray(), $filename . '.csv');
        }
    }
    
    /**
     * Export requests data
     */
    private function exportRequests($format, $startDate, $endDate)
    {
        $query = Permintaan::with(['user', 'barang', 'satker']);
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $data = $query->get();
        
        $filename = 'laporan-permintaan-' . date('Y-m-d');
        $headers = ['Kode Permintaan', 'Tanggal', 'Pemohon', 'Satker', 'Barang', 'Jumlah', 'Satuan', 'Keperluan', 'Status'];
        
        $rows = $data->map(function($requestItem) {
            return [
                $requestItem->kode_permintaan,
                $requestItem->created_at->format('d/m/Y'),
                $requestItem->user->name ?? '',
                $requestItem->satker->nama_satker ?? '',
                $requestItem->barang->nama_barang ?? '',
                $requestItem->jumlah,
                $requestItem->barang->satuan->nama_satuan ?? '',
                $requestItem->keperluan,
                $this->getStatusText($requestItem->status),
            ];
        });
        
        // Pilih format berdasarkan parameter
        switch ($format) {
            case 'pdf':
                return $this->exportToPdf('Laporan Permintaan Barang', $headers, $rows->toArray(), $filename . '.pdf');
            case 'excel':
                return $this->exportToExcel('Laporan Permintaan Barang', $headers, $rows->toArray(), $filename);
            default: // csv
                return $this->exportToCsv($headers, $rows->toArray(), $filename . '.csv');
        }
    }
    
    /**
     * Export expenditures data
     */
    private function exportExpenditures($format, $startDate, $endDate)
    {
        $query = Permintaan::with(['user', 'barang', 'satker'])
            ->where('status', 'delivered');
            
        if ($startDate && $endDate) {
            $query->where(function($q) use ($startDate, $endDate) {
                $q->whereNotNull('delivered_at')
                  ->whereBetween('delivered_at', [$startDate, $endDate])
                  ->orWhere(function($q2) use ($startDate, $endDate) {
                      $q2->whereNull('delivered_at')
                         ->whereBetween('updated_at', [$startDate, $endDate]);
                  });
            });
        }
        
        $data = $query->get();
        
        $filename = 'laporan-pengeluaran-' . date('Y-m-d');
        $headers = ['Kode Permintaan', 'Tanggal Pengiriman', 'Barang', 'Jumlah', 'Satuan', 'Penerima (Satker)', 'Keperluan'];
        
        $rows = $data->map(function($expenditure) {
            $deliveredAtFormatted = '';
            if ($expenditure->delivered_at) {
                try {
                    $deliveredAtFormatted = Carbon::parse($expenditure->delivered_at)->format('d/m/Y H:i');
                } catch (\Exception $e) {
                    $deliveredAtFormatted = $expenditure->delivered_at;
                }
            } elseif ($expenditure->updated_at) {
                $deliveredAtFormatted = $expenditure->updated_at->format('d/m/Y H:i') . ' (update)';
            }
            
            return [
                $expenditure->kode_permintaan,
                $deliveredAtFormatted,
                $expenditure->barang->nama_barang ?? '',
                $expenditure->jumlah,
                $expenditure->barang->satuan->nama_satuan ?? '',
                $expenditure->satker->nama_satker ?? '',
                $expenditure->keperluan,
            ];
        });
        
        // Pilih format berdasarkan parameter
        switch ($format) {
            case 'pdf':
                return $this->exportToPdf('Laporan Pengeluaran Barang', $headers, $rows->toArray(), $filename . '.pdf');
            case 'excel':
                return $this->exportToExcel('Laporan Pengeluaran Barang', $headers, $rows->toArray(), $filename);
            default: // csv
                return $this->exportToCsv($headers, $rows->toArray(), $filename . '.csv');
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
            
            $count = Permintaan::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $monthlyData[] = $count;
            $monthlyLabels[] = $current->translatedFormat('M Y');
            $current->addMonth();
        }
        
        // Status data
        $statusData = [
            'pending' => Permintaan::where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'approved' => Permintaan::where('status', 'approved')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'rejected' => Permintaan::where('status', 'rejected')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'processing' => Permintaan::where('status', 'processing')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'delivered' => Permintaan::where('status', 'delivered')
                ->where(function($query) use ($startDate, $endDate) {
                    $query->whereNotNull('delivered_at')
                          ->whereBetween('delivered_at', [$startDate, $endDate])
                          ->orWhere(function($q) use ($startDate, $endDate) {
                              $q->whereNull('delivered_at')
                                ->whereBetween('updated_at', [$startDate, $endDate]);
                          });
                })->count(),
        ];
        
        return response()->json(compact('monthlyLabels', 'monthlyData', 'statusData'));
    }
    
    /**
     * Export to CSV
     */
    private function exportToCsv($headers, $rows, $filename)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for UTF-8
            fputcsv($output, $headers);
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
        }, $filename);
    }
    
    /**
     * Export to PDF
     */
    private function exportToPdf($title, $headers, $data, $filename)
    {
        try {
            $html = '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>' . $title . '</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 12px; }
                    h1 { text-align: center; color: #1e3a8a; margin-bottom: 5px; }
                    h2 { text-align: center; color: #333; margin-top: 0; margin-bottom: 20px; }
                    .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th { background-color: #1e3a8a; color: white; padding: 10px; border: 1px solid #ddd; text-align: left; font-weight: bold; }
                    td { padding: 8px; border: 1px solid #ddd; }
                    tr:nth-child(even) { background-color: #f8fafc; }
                    .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; }
                    .logo { text-align: center; margin-bottom: 20px; }
                    .page-break { page-break-before: always; }
                </style>
            </head>
            <body>
                <div class="logo">
                    <h1>SILOG POLRES</h1>
                    <h3>Sistem Logistik Kepolisian</h3>
                </div>
                <h2>' . $title . '</h2>
                <p class="subtitle">Tanggal Generate: ' . date('d/m/Y H:i') . '</p>';
            
            if (!empty($data)) {
                $html .= '<table>
                    <thead>
                        <tr>';
                foreach ($headers as $header) {
                    $html .= '<th>' . $header . '</th>';
                }
                $html .= '</tr>
                    </thead>
                    <tbody>';
                
                $rowCount = 0;
                foreach ($data as $row) {
                    $rowCount++;
                    $html .= '<tr>';
                    foreach ($row as $cell) {
                        $html .= '<td>' . htmlspecialchars($cell) . '</td>';
                    }
                    $html .= '</tr>';
                    
                    // Tambahkan page break setiap 25 baris
                    if ($rowCount % 25 == 0) {
                        $html .= '</tbody></table><div class="page-break"></div>';
                        $html .= '<table><thead><tr>';
                        foreach ($headers as $header) {
                            $html .= '<th>' . $header . '</th>';
                        }
                        $html .= '</tr></thead><tbody>';
                    }
                }
                
                $html .= '</tbody>
                </table>';
            } else {
                $html .= '<p style="text-align: center; color: #999; padding: 40px;">Tidak ada data untuk ditampilkan.</p>';
            }
            
            $html .= '<div class="footer">
                    <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
                    Generated by SILOG Polres - Sistem Logistik<br>
                    ' . date('d/m/Y H:i') . ' | Halaman <span class="page-number"></span>
                </div>
            </body>
            </html>';
            
            $pdf = PDF::loadHTML($html);
            $pdf->setPaper('A4', 'landscape');
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            // Fallback ke CSV jika PDF gagal
            Log::warning('PDF export failed, falling back to CSV: ' . $e->getMessage());
            return $this->exportToCsv($headers, $data, str_replace('.pdf', '.csv', $filename));
        }
    }
    
    /**
     * Export to Excel menggunakan PhpSpreadsheet (lebih rapi)
     */
    private function exportToExcel($title, $headers, $data, $filename)
    {
        try {
            // Buat spreadsheet baru
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set properties dokumen
            $spreadsheet->getProperties()
                ->setCreator('SILOG Polres')
                ->setLastModifiedBy('SILOG Polres')
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription('Laporan generated by SILOG Polres');
            
            // ========== HEADER DAN JUDUL ==========
            
            // Baris 1: Judul Utama
            $sheet->setCellValue('A1', 'SILOG POLRES - SISTEM LOGISTIK KEPOLISIAN');
            $sheet->mergeCells('A1:' . $this->getColumnLetter(count($headers)) . '1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FF1E3A8A'); // Warna biru
            
            // Baris 2: Judul Laporan
            $sheet->setCellValue('A2', $title);
            $sheet->mergeCells('A2:' . $this->getColumnLetter(count($headers)) . '2');
            $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Baris 3: Tanggal Generate
            $sheet->setCellValue('A3', 'Tanggal Generate: ' . date('d/m/Y H:i'));
            $sheet->mergeCells('A3:' . $this->getColumnLetter(count($headers)) . '3');
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A3')->getFont()->setItalic(true);
            
            // Spasi
            $sheet->setCellValue('A4', '');
            
            // ========== HEADER TABEL ==========
            
            $headerRow = 5;
            $columnIndex = 0;
            
            foreach ($headers as $header) {
                $columnLetter = $this->getColumnLetter($columnIndex);
                $sheet->setCellValue($columnLetter . $headerRow, $header);
                
                // Style untuk header
                $sheet->getStyle($columnLetter . $headerRow)->getFont()->setBold(true);
                $sheet->getStyle($columnLetter . $headerRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                
                $sheet->getStyle($columnLetter . $headerRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF1E3A8A'); // Warna biru
                
                $sheet->getStyle($columnLetter . $headerRow)->getFont()->getColor()->setARGB('FFFFFFFF'); // Putih
                
                $sheet->getStyle($columnLetter . $headerRow)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()->setARGB('FF000000');
                
                // Auto size untuk kolom
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                
                $columnIndex++;
            }
            
            // ========== DATA ROWS ==========
            
            $dataRow = $headerRow + 1;
            
            if (!empty($data)) {
                foreach ($data as $rowIndex => $row) {
                    $columnIndex = 0;
                    
                    foreach ($row as $cellValue) {
                        $columnLetter = $this->getColumnLetter($columnIndex);
                        $sheet->setCellValue($columnLetter . $dataRow, $cellValue);
                        
                        // Style untuk sel data
                        $sheet->getStyle($columnLetter . $dataRow)->getBorders()->getAllBorders()
                            ->setBorderStyle(Border::BORDER_THIN)
                            ->getColor()->setARGB('FFCCCCCC');
                        
                        // Format angka jika perlu
                        if (is_numeric($cellValue) && !preg_match('/[a-zA-Z]/', $cellValue)) {
                            $sheet->getStyle($columnLetter . $dataRow)
                                ->getNumberFormat()
                                ->setFormatCode(NumberFormat::FORMAT_NUMBER);
                            $sheet->getStyle($columnLetter . $dataRow)
                                ->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        } else {
                            $sheet->getStyle($columnLetter . $dataRow)
                                ->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                                ->setWrapText(true);
                        }
                        
                        // Warna background untuk baris genap
                        if ($rowIndex % 2 == 0) {
                            $sheet->getStyle($columnLetter . $dataRow)->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFF8FAFC'); // Abu-abu muda
                        }
                        
                        $columnIndex++;
                    }
                    
                    $dataRow++;
                }
                
                // Auto filter untuk header
                $sheet->setAutoFilter('A' . $headerRow . ':' . $this->getColumnLetter(count($headers) - 1) . ($dataRow - 1));
                
                // Freeze header row
                $sheet->freezePane('A' . ($headerRow + 1));
                
            } else {
                // Jika tidak ada data
                $sheet->setCellValue('A' . $dataRow, 'Tidak ada data untuk ditampilkan');
                $sheet->mergeCells('A' . $dataRow . ':' . $this->getColumnLetter(count($headers) - 1) . $dataRow);
                $sheet->getStyle('A' . $dataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $dataRow)->getFont()->setItalic(true);
                $dataRow++;
            }
            
            // ========== FOOTER ==========
            
            $footerRow = $dataRow + 2;
            $sheet->setCellValue('A' . $footerRow, 'Generated by SILOG Polres - Sistem Logistik');
            $sheet->mergeCells('A' . $footerRow . ':' . $this->getColumnLetter(count($headers) - 1) . $footerRow);
            $sheet->getStyle('A' . $footerRow)->getFont()->setItalic(true);
            $sheet->getStyle('A' . $footerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Set judul sheet
            $sheet->setTitle('Laporan');
            
            // ========== EXPORT KE FILE ==========
            
            // Buat writer
            $writer = new Xlsx($spreadsheet);
            
            // Tambahkan ekstensi .xlsx jika belum ada
            if (!str_ends_with($filename, '.xlsx')) {
                $filename .= '.xlsx';
            }
            
            // Stream file ke browser
            return response()->streamDownload(
                function () use ($writer) {
                    $writer->save('php://output');
                },
                $filename,
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Cache-Control' => 'max-age=0',
                ]
            );
            
        } catch (\Exception $e) {
            Log::error('Excel export failed: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            
            // Fallback ke CSV jika Excel gagal
            Log::warning('Excel export failed, falling back to CSV: ' . $e->getMessage());
            return $this->exportToCsv($headers, $data, str_replace('.xlsx', '.csv', $filename));
        }
    }
    
    /**
     * Helper method untuk mendapatkan huruf kolom dari index
     */
    private function getColumnLetter($index)
    {
        $letters = range('A', 'Z');
        
        if ($index < 26) {
            return $letters[$index];
        } else {
            $firstLetter = floor($index / 26) - 1;
            $secondLetter = $index % 26;
            return $letters[$firstLetter] . $letters[$secondLetter];
        }
    }
    
    /**
     * Get status text
     */
    private function getStatusText($status)
    {
        return match($status) {
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'processing' => 'Diproses',
            'delivered' => 'Terkirim',
            default => $status
        };
    }
    
    /**
     * Get status class
     */
    private function getStatusClass($status)
    {
        return match($status) {
            'pending' => 'badge-pending',
            'approved' => 'badge-approved',
            'rejected' => 'badge-rejected',
            'processing' => 'badge-processing',
            'delivered' => 'badge-delivered',
            default => ''
        };
    }
}