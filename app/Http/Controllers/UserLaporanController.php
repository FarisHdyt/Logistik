<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Str;

class UserLaporanController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    
    // PERBAIKAN: Load satker dari permintaan utama (bukan dari details)
    $query = Permintaan::where('user_id', $user->id)
        ->with([
            'barang.satuan',
            'satker:id,nama_satker', // ✅ Ini SATKER UTAMA yang dipilih user
            'details' => function($query) {
                $query->with([
                    'barang.satuan',
                    // JANGAN load satker dari detail, karena kita mau pakai satker utama
                ])->orderBy('id');
            },
            'approver:id,name'
        ])
        ->orderBy('created_at', 'desc');
    
    // ... filter code tetap sama ...
    
    $permintaan = $query->paginate(15);
    
    $stats = $this->getUserStatistics($user->id);
    $chartData = $this->getUserChartData($user->id);
    
    return view('user.laporan', compact('permintaan', 'stats', 'chartData'));
}
    
    /**
     * Get statistics for specific user
     */
    private function getUserStatistics($userId)
    {
        // Hitung total items termasuk dari details
        $totalItems = Permintaan::where('user_id', $userId)
            ->withCount('details')
            ->get()
            ->sum(function($permintaan) {
                return $permintaan->details_count > 0 ? $permintaan->details_count : 1;
            });
        
        // Hitung total quantity
        $totalQuantity = Permintaan::where('user_id', $userId)
            ->with(['details'])
            ->get()
            ->sum(function($permintaan) {
                if ($permintaan->details->count() > 0) {
                    return $permintaan->details->sum('jumlah');
                }
                return $permintaan->jumlah;
            });
        
        return [
            'total' => Permintaan::where('user_id', $userId)->count(),
            'pending' => Permintaan::where('user_id', $userId)->where('status', 'pending')->count(),
            'approved' => Permintaan::where('user_id', $userId)->where('status', 'approved')->count(),
            'rejected' => Permintaan::where('user_id', $userId)->where('status', 'rejected')->count(),
            'delivered' => Permintaan::where('user_id', $userId)->where('status', 'delivered')->count(),
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity,
            'total_satker' => Permintaan::where('user_id', $userId)->distinct('satker_id')->count('satker_id'),
        ];
    }
    
    /**
     * Get chart data for user reports
     */
    private function getUserChartData($userId)
    {
        $startDate = Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $labels = [];
        $data = [];
        
        // Generate last 6 months data untuk user ini
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');
            
            $count = Permintaan::where('user_id', $userId)
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            
            $data[] = $count;
        }
        
        // Data untuk pie chart status
        $statusData = Permintaan::where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        return [
            'monthlyLabels' => $labels,
            'monthlyData' => $data,
            'statusData' => $statusData,
        ];
    }
    
    /**
     * Export reports for user
     */
    public function export($type, Request $request)
    {
        $user = auth()->user();
        
        // Load data dengan detail untuk export
        $query = Permintaan::where('user_id', $user->id)
            ->with([
                'barang.satuan',
                'satker',
                'details' => function($query) {
                    $query->with([
                        'barang.satuan',
                        'satker'
                    ])->orderBy('id');
                },
                'approver'
            ])
            ->orderBy('created_at', 'desc');
        
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $permintaan = $query->get();
        
        // Statistics untuk export user dengan perhitungan yang benar
        $stats = [
            'total' => $permintaan->count(),
            'pending' => $permintaan->where('status', 'pending')->count(),
            'approved' => $permintaan->where('status', 'approved')->count(),
            'rejected' => $permintaan->where('status', 'rejected')->count(),
            'delivered' => $permintaan->where('status', 'delivered')->count(),
            'total_items' => $permintaan->sum(function($item) {
                return $item->details->count() > 0 ? $item->details->count() : 1;
            }),
            'total_quantity' => $permintaan->sum(function($item) {
                return $item->details->count() > 0 ? $item->details->sum('jumlah') : $item->jumlah;
            }),
        ];
        
        // Prepare data untuk PDF/Excel
        $data = [
            'permintaan' => $permintaan,
            'stats' => $stats,
            'user' => $user,
            'filters' => $request->all(),
            'printDate' => Carbon::now()->format('d/m/Y H:i:s'),
            'title' => 'Laporan Permintaan Barang - ' . $user->name,
        ];
        
        if ($type === 'excel') {
            $filename = 'laporan-permintaan-' . $user->name . '-' . date('Y-m-d') . '.xls';
            
            return response()
                ->view('exports.user-permintaan-excel', $data)
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }
        
        // PDF Export
        $pdf = PDF::loadView('exports.user-permintaan-pdf', $data);
        $filename = 'laporan-permintaan-' . str_replace(' ', '-', strtolower($user->name)) . '-' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
    
    /**
     * Print report for user
     */
    public function print(Request $request)
    {
        $user = auth()->user();
        
        // Apply filters untuk print user
        $query = Permintaan::where('user_id', $user->id)
            ->with([
                'barang.satuan',
                'satker',
                'details' => function($query) {
                    $query->with([
                        'barang.satuan',
                        'satker'
                    ])->orderBy('id');
                },
                'approver'
            ])
            ->orderBy('created_at', 'desc');
        
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $permintaan = $query->get();
        
        // Hitung statistik untuk print
        $stats = [
            'total' => $permintaan->count(),
            'pending' => $permintaan->where('status', 'pending')->count(),
            'approved' => $permintaan->where('status', 'approved')->count(),
            'rejected' => $permintaan->where('status', 'rejected')->count(),
            'delivered' => $permintaan->where('status', 'delivered')->count(),
            'total_items' => $permintaan->sum(function($item) {
                return $item->details->count() > 0 ? $item->details->count() : 1;
            }),
            'total_quantity' => $permintaan->sum(function($item) {
                return $item->details->count() > 0 ? $item->details->sum('jumlah') : $item->jumlah;
            }),
        ];
        
        $data = [
            'permintaan' => $permintaan,
            'stats' => $stats,
            'user' => $user,
            'filters' => $request->all(),
            'printDate' => Carbon::now()->format('d/m/Y H:i')
        ];
        
        return view('prints.user-permintaan', $data);
    }
    
    /**
     * Get user's monthly request summary via AJAX
     */
    public function getMonthlySummary(Request $request)
    {
        $user = auth()->user();
        
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        $monthlyData = [];
        $monthlyLabels = [];
        
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();
            
            $count = Permintaan::where('user_id', $user->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            $monthlyData[] = $count;
            $monthlyLabels[] = $current->translatedFormat('M Y');
            
            $current->addMonth();
        }
        
        // Status data for pie chart
        $statusData = [
            'pending' => Permintaan::where('user_id', $user->id)
                ->where('status', 'pending')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'approved' => Permintaan::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'rejected' => Permintaan::where('user_id', $user->id)
                ->where('status', 'rejected')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'delivered' => Permintaan::where('user_id', $user->id)
                ->where('status', 'delivered')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
        ];
        
        return response()->json([
            'monthly_labels' => $monthlyLabels,
            'monthly_data' => $monthlyData,
            'status_data' => $statusData
        ]);
    }
    
    /**
     * Helper method untuk menghitung status campuran
     */
    private function calculateMixedStatus($permintaan)
    {
        if ($permintaan->details->count() == 0) {
            return false;
        }
        
        $hasApproved = false;
        $hasRejected = false;
        
        foreach ($permintaan->details as $detail) {
            if ($detail->status === 'approved') {
                $hasApproved = true;
            } elseif ($detail->status === 'rejected') {
                $hasRejected = true;
            }
        }
        
        return $hasApproved && $hasRejected;
    }
}