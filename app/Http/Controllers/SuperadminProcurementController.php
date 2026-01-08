<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SuperadminProcurementController extends Controller
{
    /**
     * Menampilkan halaman validasi pengadaan untuk superadmin
     */
    public function index(Request $request)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak. Hanya untuk superadmin.');
        }
        
        $user = auth()->user();
        
        // Query dasar dengan relasi untuk superadmin - DIPERBAIKI
        $query = Procurement::with([
            'kategori', 
            'satuan', 
            'user' => function($q) {
                $q->select('id', 'name', 'username', 'email', 'jabatan', 'satker_id')
                  ->with(['satker' => function($q) {
                      $q->select('id', 'nama_satker', 'kode_satker');
                  }]);
            },
            'disetujuiOleh' => function($q) {
                $q->select('id', 'name', 'username');
            },
            'diprosesOleh' => function($q) {
                $q->select('id', 'name', 'username');
            },
            'selesaiOleh' => function($q) {
                $q->select('id', 'name', 'username');
            },
            'dibatalkanOleh' => function($q) {
                $q->select('id', 'name', 'username');
            }
        ]);
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('username', 'like', '%' . $search . '%');
                  });
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
        
        // Sorting: menampilkan yang pending terlebih dahulu, lalu berdasarkan prioritas dan tanggal
        $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'completed', 'cancelled')")
              ->orderByRaw("FIELD(prioritas, 'mendesak', 'tinggi', 'normal')")
              ->orderBy('created_at', 'desc');
        
        // Pagination
        $procurements = $query->paginate(10)->withQueryString();
        
        // Hitung statistik khusus untuk superadmin
        $stats = $this->getSuperadminProcurementStats($request);
        
        return view('superadmin.procurement', compact('user', 'procurements', 'stats'));
    }
    
    /**
     * Mendapatkan statistik pengadaan untuk superadmin
     */
    private function getSuperadminProcurementStats($request)
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
        
        // Statistik untuk superadmin
        $pendingQuery = clone $statsQuery;
        $approvedQuery = clone $statsQuery;
        $rejectedQuery = clone $statsQuery;
        $completedQuery = clone $statsQuery;
        
        $pendingCount = $pendingQuery->where('status', 'pending')->count();
        $approvedCount = $approvedQuery->where('status', 'approved')->count();
        $rejectedCount = $rejectedQuery->where('status', 'rejected')->count();
        $completedCount = $completedQuery->where('status', 'completed')->count();
        
        // Hitung total nilai pengadaan yang pending (untuk superadmin)
        $pendingValue = Procurement::where('status', 'pending')
            ->selectRaw('SUM(jumlah * harga_perkiraan) as total_value')
            ->first()
            ->total_value ?? 0;
        
        return [
            'total' => $total,
            'pending' => $pendingCount,
            'approved' => $approvedCount,
            'rejected' => $rejectedCount,
            'completed' => $completedCount,
            'pending_value' => $pendingValue,
        ];
    }
    
    /**
     * Menampilkan detail pengadaan untuk superadmin
     */
    public function show($id)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role !== 'superadmin') {
            return response()->json([
                'error' => 'Akses ditolak. Hanya untuk superadmin.'
            ], 403);
        }
        
        $procurement = Procurement::with([
            'kategori', 
            'satuan', 
            'user' => function($q) {
                $q->select('id', 'name', 'username', 'email', 'jabatan', 'pangkat', 'nrp', 'satker_id')
                  ->with(['satker' => function($q) {
                      $q->select('id', 'nama_satker', 'kode_satker');
                  }]);
            },
            'disetujuiOleh' => function($q) {
                $q->select('id', 'name', 'username');
            },
            'diprosesOleh' => function($q) {
                $q->select('id', 'name', 'username');
            },
            'selesaiOleh' => function($q) {
                $q->select('id', 'name', 'username');
            },
            'dibatalkanOleh' => function($q) {
                $q->select('id', 'name', 'username');
            }
        ])->findOrFail($id);
        
        // Format data untuk response JSON - DIPERBAIKI
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
                'catatan_approve' => $procurement->catatan,
                'alasan_penolakan' => $procurement->alasan_penolakan,
                'alasan_pembatalan' => $procurement->alasan_pembatalan,
                'created_at' => $procurement->created_at,
                'approved_at' => $procurement->tanggal_disetujui,
                'rejected_at' => $procurement->tanggal_ditolak,
                'completed_at' => $procurement->tanggal_selesai,
                'cancelled_at' => $procurement->tanggal_dibatalkan,
                // Perbaikan: gunakan 'user' bukan 'diajukan_oleh_user'
                'user' => $procurement->user ? [
                    'id' => $procurement->user->id,
                    'name' => $procurement->user->name,
                    'username' => $procurement->user->username,
                    'email' => $procurement->user->email,
                    'jabatan' => $procurement->user->jabatan,
                    'pangkat' => $procurement->user->pangkat,
                    'nrp' => $procurement->user->nrp,
                    'satker' => $procurement->user->satker,
                ] : null,
                'disetujui_oleh_user' => $procurement->disetujuiOleh ? [
                    'id' => $procurement->disetujuiOleh->id,
                    'name' => $procurement->disetujuiOleh->name,
                    'username' => $procurement->disetujuiOleh->username,
                ] : null,
                'diproses_oleh_user' => $procurement->diprosesOleh ? [
                    'id' => $procurement->diprosesOleh->id,
                    'name' => $procurement->diprosesOleh->name,
                    'username' => $procurement->diprosesOleh->username,
                ] : null,
                'selesai_oleh_user' => $procurement->selesaiOleh ? [
                    'id' => $procurement->selesaiOleh->id,
                    'name' => $procurement->selesaiOleh->name,
                    'username' => $procurement->selesaiOleh->username,
                ] : null,
                'dibatalkan_oleh_user' => $procurement->dibatalkanOleh ? [
                    'id' => $procurement->dibatalkanOleh->id,
                    'name' => $procurement->dibatalkanOleh->name,
                    'username' => $procurement->dibatalkanOleh->username,
                ] : null,
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
     * Menyetujui pengadaan (Superadmin Action)
     */
    public function approve(Request $request, $id)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya untuk superadmin.'
            ], 403);
        }
        
        $procurement = Procurement::findOrFail($id);
        
        // Validasi: hanya bisa approve jika status pending
        if ($procurement->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengadaan dengan status "Menunggu" yang dapat disetujui'
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $procurement->status;
            
            // Update status dan tambahkan informasi persetujuan
            $procurement->update([
                'status' => 'approved',
                'tanggal_disetujui' => now(),
                'disetujui_oleh' => Auth::id(),
                'catatan' => $request->catatan,
            ]);
            
            // Log aktivitas persetujuan oleh superadmin
            $this->logActivity(
                'Setujui Pengadaan',
                "Pengadaan disetujui oleh superadmin: {$procurement->kode_barang} - {$procurement->nama_barang}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'approved',
                    'disetujui_oleh' => Auth::user()->name,
                    'catatan' => $request->catatan,
                    'jumlah' => $procurement->jumlah,
                    'harga_perkiraan' => $procurement->harga_perkiraan,
                    'total_nilai' => $procurement->jumlah * $procurement->harga_perkiraan
                ])
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil disetujui'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error approving procurement by superadmin: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Menolak pengadaan (Superadmin Action)
     */
    public function reject(Request $request, $id)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya untuk superadmin.'
            ], 403);
        }
        
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ]);
        
        $procurement = Procurement::findOrFail($id);
        
        // Validasi: hanya bisa reject jika status pending
        if ($procurement->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengadaan dengan status "Menunggu" yang dapat ditolak'
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $procurement->status;
            
            // Update status ke rejected
            $procurement->update([
                'status' => 'rejected',
                'tanggal_ditolak' => now(),
                'alasan_penolakan' => $request->alasan_penolakan,
                // Gunakan 'dibatalkan_oleh' karena tidak ada kolom 'ditolak_oleh'
                'dibatalkan_oleh' => Auth::id(),
            ]);
            
            // Log aktivitas penolakan oleh superadmin
            $this->logActivity(
                'Tolak Pengadaan',
                "Pengadaan ditolak oleh superadmin: {$procurement->kode_barang} - {$procurement->nama_barang}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'rejected',
                    'ditolak_oleh' => Auth::user()->name,
                    'alasan_penolakan' => $request->alasan_penolakan,
                    'jumlah' => $procurement->jumlah,
                    'harga_perkiraan' => $procurement->harga_perkiraan,
                    'total_nilai' => $procurement->jumlah * $procurement->harga_perkiraan
                ])
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil ditolak'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error rejecting procurement by superadmin: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Helper method untuk log aktivitas superadmin
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
        Log::info('Superadmin Activity Log: ' . $action, [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'description' => $description,
            'model_type' => $modelType ?? null,
            'model_id' => $modelId ?? null,
            'details' => $details,
            'ip' => request()->ip(),
        ]);
    }
}