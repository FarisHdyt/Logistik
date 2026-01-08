<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Carbon\Carbon;

class ProcurementController extends Controller
{
    /**
     * Menampilkan halaman pengadaan barang
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Query dasar dengan relasi
        $query = Procurement::with(['kategori', 'satuan', 'user']);
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%');
            });
        }
        
        // Filter berdasarkan status
        if ($request->has('status') && !empty($request->status) && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tipe pengadaan
        if ($request->has('tipe') && !empty($request->tipe)) {
            $query->where('tipe_pengadaan', $request->tipe);
        }
        
        // Sorting berdasarkan prioritas dan tanggal
        $query->orderByRaw("FIELD(prioritas, 'mendesak', 'tinggi', 'normal')")
              ->orderBy('created_at', 'desc');
        
        // Pagination
        $procurements = $query->paginate(10)->withQueryString();
        
        // Hitung statistik
        $stats = $this->getProcurementStats($request);
        
        return view('admin.procurement', compact('user', 'procurements', 'stats'));
    }
    
    /**
     * Mendapatkan statistik pengadaan
     */
    private function getProcurementStats($request)
    {
        $statsQuery = Procurement::query();
        
        // Apply filters if any
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $statsQuery->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%');
            });
        }
        
        if ($request->has('tipe') && !empty($request->tipe)) {
            $statsQuery->where('tipe_pengadaan', $request->tipe);
        }
        
        $total = $statsQuery->count();
        $pending = clone $statsQuery;
        $completed = clone $statsQuery;
        
        $pendingCount = $pending->where('status', 'pending')->count();
        $completedCount = $completed->where('status', 'completed')->count();
        
        // Hitung total nilai pengadaan yang completed
        $totalValue = Procurement::where('status', 'completed')
            ->selectRaw('SUM(jumlah * harga_perkiraan) as total_value')
            ->first()
            ->total_value ?? 0;
        
        return [
            'total' => $total,
            'pending' => $pendingCount,
            'completed' => $completedCount,
            'total_value' => $totalValue,
        ];
    }
    
    /**
     * Menyimpan pengajuan pengadaan baru
     */
    public function store(Request $request)
    {
        Log::info('Store procurement request data:', $request->all());
        
        // Validasi berdasarkan tipe pengadaan
        $validatedData = $request->validate([
            'tipe_pengadaan' => 'required|in:baru,restock',
            'kode_barang' => 'required_if:tipe_pengadaan,baru|string|max:50|nullable',
            'nama_barang' => 'required_if:tipe_pengadaan,baru|string|max:255|nullable',
            'kategori_id' => 'required_if:tipe_pengadaan,baru|nullable|exists:kategoris,id',
            'satuan_id' => 'required_if:tipe_pengadaan,baru|nullable|exists:satuans,id',
            'barang_id' => 'required_if:tipe_pengadaan,restock|nullable|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'harga_perkiraan' => 'required|numeric|min:0',
            'prioritas' => 'required|in:normal,tinggi,mendesak',
            'alasan_pengadaan' => 'required|string|min:10',
            'catatan' => 'nullable|string',
        ], [
            'kode_barang.required_if' => 'Kode barang wajib diisi untuk pengadaan barang baru',
            'nama_barang.required_if' => 'Nama barang wajib diisi untuk pengadaan barang baru',
            'kategori_id.required_if' => 'Kategori wajib dipilih untuk pengadaan barang baru',
            'satuan_id.required_if' => 'Satuan wajib dipilih untuk pengadaan barang baru',
            'barang_id.required_if' => 'Barang wajib dipilih untuk pengadaan restock',
            'barang_id.exists' => 'Barang yang dipilih tidak valid',
        ]);
        
        try {
            DB::beginTransaction();
            
            $data = $validatedData;
            $data['user_id'] = Auth::id();
            $data['status'] = 'pending';
            
            Log::info('Process Procurement - Tipe: ' . $request->tipe_pengadaan);
            
            // Handle berdasarkan tipe pengadaan
            if ($request->tipe_pengadaan == 'restock' && $request->filled('barang_id')) {
                $barang = Barang::find($request->barang_id);
                
                if (!$barang) {
                    throw new \Exception('Barang tidak ditemukan untuk restock');
                }
                
                // Isi data dari barang yang ada
                $data['kode_barang'] = $barang->kode_barang;
                $data['nama_barang'] = $barang->nama_barang;
                $data['kategori_id'] = $barang->kategori_id;
                $data['satuan_id'] = $barang->satuan_id;
                $data['barang_id'] = $barang->id; // Simpan ID barang
                
                Log::info('Restock data prepared:', [
                    'barang_id' => $barang->id,
                    'kode_barang' => $barang->kode_barang,
                    'nama_barang' => $barang->nama_barang,
                    'kategori_id' => $barang->kategori_id,
                    'satuan_id' => $barang->satuan_id,
                ]);
            } else {
                // Untuk barang baru, barang_id null
                $data['barang_id'] = null;
                
                // Cek jika kode_barang sudah ada untuk barang baru
                if ($request->filled('kode_barang')) {
                    $existing = Barang::where('kode_barang', $request->kode_barang)->first();
                    if ($existing) {
                        throw new \Exception('Kode barang sudah digunakan');
                    }
                }
                
                Log::info('Barang baru data prepared:', [
                    'kode_barang' => $data['kode_barang'] ?? 'N/A',
                    'nama_barang' => $data['nama_barang'] ?? 'N/A',
                    'kategori_id' => $data['kategori_id'] ?? 'N/A',
                    'satuan_id' => $data['satuan_id'] ?? 'N/A',
                ]);
            }
            
            // Pastikan semua field yang diperlukan ada
            if (empty($data['kategori_id']) || empty($data['satuan_id'])) {
                throw new \Exception('Kategori dan Satuan harus diisi');
            }
            
            // Simpan data pengadaan
            $procurement = Procurement::create($data);
            
            Log::info('Procurement created successfully:', [
                'id' => $procurement->id,
                'kode_barang' => $procurement->kode_barang,
                'nama_barang' => $procurement->nama_barang,
                'tipe_pengadaan' => $procurement->tipe_pengadaan,
            ]);
            
            // Log aktivitas pengajuan pengadaan
            $this->logActivity(
                'Pengajuan Pengadaan',
                "Pengajuan pengadaan diajukan: {$procurement->kode_barang} - {$procurement->nama_barang}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'tipe' => $procurement->tipe_pengadaan,
                    'jumlah' => $procurement->jumlah,
                    'harga_perkiraan' => $procurement->harga_perkiraan,
                    'prioritas' => $procurement->prioritas
                ])
            );
            
            DB::commit();
            
            return redirect()->route('admin.procurement')
                ->with('success', 'Pengajuan pengadaan berhasil dikirim. Menunggu persetujuan.')
                ->with('procurement_id', $procurement->id);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error store procurement: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Request data: ', $request->all());
            
            return redirect()->route('admin.procurement')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Menampilkan detail pengadaan
     */
    public function show($id)
    {
        $procurement = Procurement::with([
            'kategori', 
            'satuan', 
            'user'
        ])->findOrFail($id);
        
        // Format data untuk response JSON
        $data = [
            'procurement' => [
                'id' => $procurement->id,
                'kode_barang' => $procurement->kode_barang,
                'nama_barang' => $procurement->nama_barang,
                'tipe_pengadaan' => $procurement->tipe_pengadaan,
                'tipe_pengadaan_display' => $procurement->tipe_pengadaan == 'baru' ? 'Barang Baru' : 'Restock',
                'kategori' => $procurement->kategori,
                'satuan' => $procurement->satuan,
                'jumlah' => $procurement->jumlah,
                'harga_perkiraan' => $procurement->harga_perkiraan,
                'prioritas' => $procurement->prioritas,
                'prioritas_display' => ucfirst($procurement->prioritas),
                'status' => $procurement->status,
                'status_display' => $this->getStatusDisplay($procurement->status),
                'alasan_pengadaan' => $procurement->alasan_pengadaan,
                'catatan' => $procurement->catatan,
                'created_at' => $procurement->created_at,
                'approved_at' => $procurement->approved_at,
                'completed_at' => $procurement->completed_at,
                'cancelled_at' => $procurement->cancelled_at,
                'alasan_pembatalan' => $procurement->alasan_pembatalan,
                'disetujui_oleh_user' => $procurement->disetujuiOleh ?? null,
            ]
        ];
        
        return response()->json($data);
    }
    
    /**
     * Helper method untuk mendapatkan display status
     */
    private function getStatusDisplay($status)
    {
        $statuses = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'rejected' => 'Ditolak'
        ];
        
        return $statuses[$status] ?? ucfirst($status);
    }
    
    /**
     * Menyelesaikan pengadaan
     */
    public function complete(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);
        
        // Validasi: hanya bisa complete jika status approved
        if ($procurement->status != 'approved') {
            return response()->json([
                'error' => 'Hanya pengadaan dengan status "Disetujui" yang dapat ditandai selesai'
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $procurement->status;
            
            // Update status
            $procurement->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            
            // Proses pengadaan yang diselesaikan
            $this->processCompletedProcurement($procurement);
            
            // Log aktivitas
            $this->logActivity(
                'Selesaikan Pengadaan',
                "Pengadaan diselesaikan: {$procurement->kode_barang} - {$procurement->nama_barang}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'completed',
                    'jumlah' => $procurement->jumlah
                ])
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil ditandai selesai'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error completing procurement: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Membatalkan pengadaan
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|min:10',
        ]);
        
        $procurement = Procurement::findOrFail($id);
        
        // Validasi: hanya bisa cancel jika status pending atau approved
        if (!in_array($procurement->status, ['pending', 'approved'])) {
            return response()->json([
                'error' => 'Hanya pengadaan dengan status "Menunggu" atau "Disetujui" yang dapat dibatalkan'
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $procurement->status;
            
            // Update status
            $procurement->update([
                'status' => 'cancelled',
                'alasan_pembatalan' => $request->alasan_pembatalan,
                'cancelled_at' => now(),
            ]);
            
            // Log aktivitas
            $this->logActivity(
                'Batalkan Pengadaan',
                "Pengadaan dibatalkan: {$procurement->kode_barang} - {$procurement->nama_barang}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'cancelled',
                    'alasan_pembatalan' => $request->alasan_pembatalan
                ])
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil dibatalkan'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error cancelling procurement: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Proses pengadaan yang telah selesai
     */
    private function processCompletedProcurement(Procurement $procurement)
    {
        try {
            if ($procurement->tipe_pengadaan == 'restock' && $procurement->barang_id) {
                // Restock barang yang sudah ada
                $barang = Barang::find($procurement->barang_id);
                if ($barang) {
                    $oldStock = $barang->stok;
                    $barang->increment('stok', $procurement->jumlah);
                    
                    // Update harga beli jika ada
                    if ($procurement->harga_perkiraan > 0) {
                        $barang->update(['harga_beli' => $procurement->harga_perkiraan]);
                    }
                    
                    // Log restock
                    $this->logActivity(
                        'Restock dari Pengadaan',
                        "Barang direstock dari pengadaan: {$barang->kode_barang} - {$barang->nama_barang} (Stok +{$procurement->jumlah})",
                        $barang,
                        json_encode([
                            'procurement_id' => $procurement->id,
                            'barang_id' => $barang->id,
                            'old_stok' => $oldStock,
                            'added_stok' => $procurement->jumlah,
                            'new_stok' => $barang->stok
                        ])
                    );
                }
            } else {
                // Buat barang baru
                $barang = Barang::create([
                    'kode_barang' => $procurement->kode_barang,
                    'nama_barang' => $procurement->nama_barang,
                    'kategori_id' => $procurement->kategori_id,
                    'satuan_id' => $procurement->satuan_id,
                    'stok' => $procurement->jumlah,
                    'stok_minimal' => 10, // Default minimal stock
                    'harga_beli' => $procurement->harga_perkiraan,
                    'gudang_id' => null, // Default
                    'lokasi' => 'Gudang Utama',
                    'keterangan' => 'Barang dari pengadaan #' . $procurement->id,
                ]);
                
                // Log pembuatan barang baru
                $this->logActivity(
                    'Buat Barang dari Pengadaan',
                    "Barang baru dibuat dari pengadaan: {$barang->kode_barang} - {$barang->nama_barang}",
                    $barang,
                    json_encode([
                        'procurement_id' => $procurement->id,
                        'barang_id' => $barang->id,
                        'jumlah' => $procurement->jumlah,
                        'harga_beli' => $procurement->harga_perkiraan
                    ])
                );
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error processing completed procurement: ' . $e->getMessage());
            throw $e; // Re-throw untuk rollback
        }
    }
    
    /**
     * API: Mendapatkan data barang untuk select2
     */
    public function getBarangForSelect(Request $request)
    {
        $query = Barang::select('id', 'kode_barang', 'nama_barang', 'stok', 'stok_minimal')
            ->orderBy('nama_barang');
        
        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }
        
        $barang = $query->limit(20)->get();
        
        return response()->json($barang->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->kode_barang . ' - ' . $item->nama_barang . ' (Stok: ' . $item->stok . ')',
                'kode' => $item->kode_barang,
                'nama' => $item->nama_barang,
                'stok' => $item->stok,
                'stok_minimal' => $item->stok_minimal,
            ];
        }));
    }
    
    /**
     * Helper method untuk log aktivitas
     */
    private function logActivity($action, $description, $relatedModel = null, $details = null)
    {
        $activityData = [
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // Tambahkan informasi terkait model
        if ($relatedModel) {
            $modelType = get_class($relatedModel);
            $modelId = $relatedModel->id;
            
            $activityData['model_type'] = $modelType;
            $activityData['model_id'] = $modelId;
        }
        
        // Simpan ke database
        ActivityLog::create($activityData);
        
        // Juga log ke file untuk backup
        Log::info('Activity Log: ' . $action, [
            'user_id' => Auth::id(),
            'description' => $description,
            'model_type' => $modelType ?? null,
            'model_id' => $modelId ?? null,
            'details' => $details,
            'ip' => request()->ip(),
        ]);
    }
}