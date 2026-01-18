<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Models\ProcurementItem;
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
        
        // Query dasar dengan relasi untuk superadmin
        $query = Procurement::with([
            'items',
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
                $q->where('kode_pengadaan', 'like', '%' . $search . '%')
                  ->orWhereHas('items', function($q) use ($search) {
                      $q->where('nama_barang', 'like', '%' . $search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $search . '%');
                  })
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
                $q->where('kode_pengadaan', 'like', '%' . $search . '%')
                  ->orWhereHas('items', function($q) use ($search) {
                      $q->where('nama_barang', 'like', '%' . $search . '%');
                  });
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
        $cancelledQuery = clone $statsQuery;
        
        $pendingCount = $pendingQuery->where('status', 'pending')->count();
        $approvedCount = $approvedQuery->where('status', 'approved')->count();
        $rejectedCount = $rejectedQuery->where('status', 'rejected')->count();
        $completedCount = $completedQuery->where('status', 'completed')->count();
        $cancelledCount = $cancelledQuery->where('status', 'cancelled')->count();
        
        // Hitung total nilai pengadaan yang pending (untuk superadmin)
        $pendingValue = Procurement::where('status', 'pending')
            ->get()
            ->sum(function($procurement) {
                return $procurement->total_perkiraan;
            });
        
        return [
            'total' => $total,
            'pending' => $pendingCount,
            'approved' => $approvedCount,
            'rejected' => $rejectedCount,
            'completed' => $completedCount,
            'cancelled' => $cancelledCount,
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
        
        try {
            $procurement = Procurement::with([
                'items' => function($query) {
                    $query->orderBy('id', 'asc');
                },
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
            
            // Pastikan items ada dan tidak null
            $procurementItems = $procurement->items ?? collect();
            
            // Format data untuk response JSON
            $data = [
                'procurement' => [
                    'id' => $procurement->id,
                    'kode_pengadaan' => $procurement->kode_pengadaan,
                    'tipe_pengadaan' => $procurement->tipe_pengadaan,
                    'tipe_pengadaan_display' => $this->getTipePengadaanDisplay($procurement->tipe_pengadaan),
                    'is_multi_item' => $procurement->is_multi_item,
                    'prioritas' => $procurement->prioritas,
                    'prioritas_display' => $this->getPrioritasDisplay($procurement->prioritas),
                    'status' => $procurement->status,
                    'status_display' => $this->getStatusDisplay($procurement->status),
                    'alasan_pengadaan' => $procurement->alasan_pengadaan,
                    'catatan' => $procurement->catatan,
                    'alasan_penolakan' => $procurement->alasan_penolakan,
                    'alasan_pembatalan' => $procurement->alasan_pembatalan,
                    'created_at' => $procurement->created_at ? $procurement->created_at->format('Y-m-d H:i:s') : null,
                    'approved_at' => $procurement->tanggal_disetujui,
                    'rejected_at' => $procurement->tanggal_ditolak,
                    'completed_at' => $procurement->tanggal_selesai,
                    'cancelled_at' => $procurement->tanggal_dibatalkan,
                    // Data procurement items
                    'items' => $procurementItems->map(function($item) {
                        return [
                            'id' => $item->id,
                            'kode_barang' => $item->kode_barang,
                            'nama_barang' => $item->nama_barang,
                            'kategori' => $item->kategori,
                            'satuan' => $item->satuan,
                            'jumlah' => $item->jumlah,
                            'harga_perkiraan' => $item->harga_perkiraan,
                            'subtotal' => $item->jumlah * $item->harga_perkiraan,
                            'deskripsi' => $item->deskripsi,
                            'subtotal_formatted' => 'Rp ' . number_format($item->jumlah * $item->harga_perkiraan, 0, ',', '.'),
                            'harga_perkiraan_formatted' => 'Rp ' . number_format($item->harga_perkiraan, 0, ',', '.'),
                            'jumlah_display' => $item->jumlah . ' ' . $item->satuan,
                            'status' => $item->status ?? 'pending',
                            'status_display' => $this->getItemStatusDisplay($item->status ?? 'pending'),
                            // Kolom opsional - cek dulu apakah ada di database
                            'approved_at' => property_exists($item, 'approved_at') ? $item->approved_at : null,
                            'rejected_at' => property_exists($item, 'rejected_at') ? $item->rejected_at : null,
                            'approved_by' => property_exists($item, 'approved_by') ? $item->approved_by : null,
                            'rejected_by' => property_exists($item, 'rejected_by') ? $item->rejected_by : null,
                            'alasan_penolakan' => property_exists($item, 'alasan_penolakan') ? $item->alasan_penolakan : null,
                        ];
                    })->toArray(),
                    // Summary data
                    'total_items' => $procurement->total_jumlah ?? 0,
                    'total_value' => $procurement->total_perkiraan ?? 0,
                    'total_value_formatted' => 'Rp ' . number_format($procurement->total_perkiraan ?? 0, 0, ',', '.'),
                    // Data user
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
            
        } catch (\Exception $e) {
            Log::error('Error fetching procurement details: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Gagal memuat data pengadaan: ' . $e->getMessage()
            ], 500);
        }
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
     * Helper method untuk mendapatkan display status item
     */
    private function getItemStatusDisplay($status)
    {
        $statuses = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan'
        ];
        
        return $statuses[$status] ?? ucfirst($status);
    }
    
    /**
     * Helper method untuk mendapatkan display tipe pengadaan
     */
    private function getTipePengadaanDisplay($tipe)
    {
        $tipes = [
            'restock' => 'Restock',
            'baru' => 'Baru'
        ];
        
        return $tipes[$tipe] ?? ucfirst($tipe);
    }
    
    /**
     * Helper method untuk mendapatkan display prioritas
     */
    private function getPrioritasDisplay($prioritas)
    {
        $priorities = [
            'mendesak' => 'Mendesak',
            'tinggi' => 'Tinggi',
            'normal' => 'Normal'
        ];
        
        return $priorities[$prioritas] ?? ucfirst($prioritas);
    }
    
    /**
     * Validasi custom per item dalam pengadaan
     */
    public function customApprove(Request $request, $id)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya untuk superadmin.'
            ], 403);
        }
        
        $procurement = Procurement::with('items')->findOrFail($id);
        
        // Validasi: hanya bisa approve jika status pending
        if ($procurement->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengadaan dengan status "Menunggu" yang dapat divalidasi'
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $procurement->status;
            $approvedItems = $request->input('approved_items', []);
            $allItems = $procurement->items->pluck('id')->toArray();
            $rejectedItems = array_diff($allItems, $approvedItems);
            
            // Cek apakah kolom-kolom approval ada di tabel
            $hasApprovalColumns = $this->checkApprovalColumnsExist();
            
            // Update status item yang disetujui
            if (!empty($approvedItems)) {
                foreach ($approvedItems as $itemId) {
                    $item = ProcurementItem::find($itemId);
                    if ($item) {
                        $updateData = ['status' => 'approved'];
                        
                        // Hanya update kolom jika ada di database
                        if ($hasApprovalColumns) {
                            $updateData['approved_at'] = now();
                            $updateData['approved_by'] = Auth::id();
                        }
                        
                        $item->update($updateData);
                    }
                }
            }
            
            // Update status item yang ditolak
            if (!empty($rejectedItems)) {
                foreach ($rejectedItems as $itemId) {
                    $item = ProcurementItem::find($itemId);
                    if ($item) {
                        $updateData = ['status' => 'rejected'];
                        
                        // Hanya update kolom jika ada di database
                        if ($hasApprovalColumns) {
                            $updateData['rejected_at'] = now();
                            $updateData['rejected_by'] = Auth::id();
                            $updateData['alasan_penolakan'] = $request->alasan_penolakan_items;
                        }
                        
                        $item->update($updateData);
                    }
                }
            }
            
            // Tentukan status procurement berdasarkan hasil validasi
            $approvedCount = count($approvedItems);
            $totalCount = count($allItems);
            
            if ($approvedCount === $totalCount) {
                // Semua item disetujui
                $newStatus = 'approved';
                $procurement->update([
                    'status' => 'approved',
                    'tanggal_disetujui' => now(),
                    'disetujui_oleh' => Auth::id(),
                    'catatan' => $request->catatan_umum,
                ]);
            } elseif ($approvedCount === 0) {
                // Semua item ditolak
                $newStatus = 'rejected';
                $procurement->update([
                    'status' => 'rejected',
                    'tanggal_ditolak' => now(),
                    'alasan_penolakan' => $request->alasan_penolakan_items ?? 'Semua item ditolak',
                    'disetujui_oleh' => Auth::id(),
                    'catatan' => $request->catatan_umum,
                ]);
            } else {
                // Sebagian item disetujui, sebagian ditolak
                // Karena kolom status tidak mendukung 'partially_approved', gunakan 'approved' dengan catatan
                $newStatus = 'approved';
                $catatan = $request->catatan_umum ?? '';
                $catatan .= " (Disetujui sebagian: {$approvedCount} dari {$totalCount} item)";
                
                $procurement->update([
                    'status' => 'approved',
                    'tanggal_disetujui' => now(),
                    'disetujui_oleh' => Auth::id(),
                    'catatan' => trim($catatan),
                ]);
            }
            
            // Log aktivitas validasi custom
            $this->logActivity(
                'Validasi Custom Pengadaan',
                "Pengadaan divalidasi custom oleh superadmin: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'disetujui_oleh' => Auth::user()->name,
                    'catatan_umum' => $request->catatan_umum,
                    'approved_items_count' => $approvedCount,
                    'rejected_items_count' => count($rejectedItems),
                    'total_items' => $totalCount,
                    'validation_type' => 'custom',
                    'is_partial_approval' => ($approvedCount > 0 && $approvedCount < $totalCount)
                ])
            );
            
            DB::commit();
            
            $message = "Validasi berhasil diproses. ";
            if ($approvedCount === $totalCount) {
                $message .= "Semua item ($approvedCount/$totalCount) disetujui.";
            } elseif ($approvedCount === 0) {
                $message .= "Semua item ($totalCount/$totalCount) ditolak.";
            } else {
                $message .= "$approvedCount item disetujui dan " . count($rejectedItems) . " item ditolak.";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'approved_count' => $approvedCount,
                    'rejected_count' => count($rejectedItems),
                    'total_count' => $totalCount,
                    'new_status' => $newStatus,
                    'is_partial' => ($approvedCount > 0 && $approvedCount < $totalCount)
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error custom approving procurement by superadmin: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyetujui pengadaan (Superadmin Action) - Untuk semua item
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
        
        $procurement = Procurement::with('items')->findOrFail($id);
        
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
            
            // Cek apakah kolom-kolom approval ada di tabel
            $hasApprovalColumns = $this->checkApprovalColumnsExist();
            
            // Update semua item menjadi approved
            if ($procurement->items && $procurement->items->count() > 0) {
                foreach ($procurement->items as $item) {
                    $updateData = ['status' => 'approved'];
                    
                    // Hanya update kolom jika ada di database
                    if ($hasApprovalColumns) {
                        $updateData['approved_at'] = now();
                        $updateData['approved_by'] = Auth::id();
                    }
                    
                    $item->update($updateData);
                }
            }
            
            // Log aktivitas persetujuan oleh superadmin
            $this->logActivity(
                'Setujui Pengadaan',
                "Pengadaan disetujui oleh superadmin: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'approved',
                    'disetujui_oleh' => Auth::user()->name,
                    'catatan' => $request->catatan,
                    'total_items' => $procurement->total_jumlah,
                    'total_value' => $procurement->total_perkiraan,
                    'approval_type' => 'full'
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
     * Menolak pengadaan (Superadmin Action) - Untuk semua item
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
        
        $procurement = Procurement::with('items')->findOrFail($id);
        
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
                'disetujui_oleh' => Auth::id(),
            ]);
            
            // Cek apakah kolom-kolom approval ada di tabel
            $hasApprovalColumns = $this->checkApprovalColumnsExist();
            
            // Update semua item menjadi rejected
            if ($procurement->items && $procurement->items->count() > 0) {
                foreach ($procurement->items as $item) {
                    $updateData = ['status' => 'rejected'];
                    
                    // Hanya update kolom jika ada di database
                    if ($hasApprovalColumns) {
                        $updateData['rejected_at'] = now();
                        $updateData['rejected_by'] = Auth::id();
                        $updateData['alasan_penolakan'] = $request->alasan_penolakan;
                    }
                    
                    $item->update($updateData);
                }
            }
            
            // Log aktivitas penolakan oleh superadmin
            $this->logActivity(
                'Tolak Pengadaan',
                "Pengadaan ditolak oleh superadmin: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'rejected',
                    'ditolak_oleh' => Auth::user()->name,
                    'alasan_penolakan' => $request->alasan_penolakan,
                    'total_items' => $procurement->total_jumlah,
                    'total_value' => $procurement->total_perkiraan,
                    'rejection_type' => 'full'
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
     * Helper method untuk mengecek apakah kolom approval ada di tabel procurement_items
     */
    private function checkApprovalColumnsExist()
    {
        try {
            // Cek skema tabel untuk melihat kolom
            $columns = DB::getSchemaBuilder()->getColumnListing('procurement_items');
            $requiredColumns = ['approved_at', 'approved_by', 'rejected_at', 'rejected_by', 'alasan_penolakan'];
            
            foreach ($requiredColumns as $column) {
                if (!in_array($column, $columns)) {
                    return false;
                }
            }
            return true;
        } catch (\Exception $e) {
            Log::warning('Error checking approval columns: ' . $e->getMessage());
            return false;
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
        
        try {
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
        } catch (\Exception $e) {
            Log::error('Error logging activity: ' . $e->getMessage());
        }
    }
    
    /**
     * Menyelesaikan pengadaan yang sudah disetujui
     */
    public function complete(Request $request, $id)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya untuk superadmin.'
            ], 403);
        }
        
        $procurement = Procurement::findOrFail($id);
        
        // Validasi: hanya bisa complete jika status approved
        if ($procurement->status != 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengadaan dengan status "Disetujui" yang dapat diselesaikan'
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $procurement->status;
            
            // Update status ke completed
            $procurement->update([
                'status' => 'completed',
                'tanggal_selesai' => now(),
                'selesai_oleh' => Auth::id(),
            ]);
            
            // Log aktivitas penyelesaian oleh superadmin
            $this->logActivity(
                'Selesaikan Pengadaan',
                "Pengadaan diselesaikan oleh superadmin: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'completed',
                    'selesai_oleh' => Auth::user()->name,
                    'total_items' => $procurement->total_jumlah,
                    'total_value' => $procurement->total_perkiraan,
                ])
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil diselesaikan'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error completing procurement by superadmin: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Membatalkan pengadaan
     */
    public function cancel(Request $request, $id)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya untuk superadmin.'
            ], 403);
        }
        
        $request->validate([
            'alasan_pembatalan' => 'required|string|min:10',
        ]);
        
        $procurement = Procurement::with('items')->findOrFail($id);
        
        // Validasi: hanya bisa cancel jika status pending atau approved
        if (!in_array($procurement->status, ['pending', 'approved'])) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengadaan dengan status "Menunggu" atau "Disetujui" yang dapat dibatalkan'
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $procurement->status;
            
            // Update status ke cancelled
            $procurement->update([
                'status' => 'cancelled',
                'tanggal_dibatalkan' => now(),
                'alasan_pembatalan' => $request->alasan_pembatalan,
                'dibatalkan_oleh' => Auth::id(),
            ]);
            
            // Update semua item menjadi cancelled
            if ($procurement->items && $procurement->items->count() > 0) {
                foreach ($procurement->items as $item) {
                    if ($item->status == 'pending') {
                        $item->update([
                            'status' => 'cancelled'
                            // Kolom cancelled_at, cancelled_by tidak ada di migration asli
                        ]);
                    }
                }
            }
            
            // Log aktivitas pembatalan oleh superadmin
            $this->logActivity(
                'Batalkan Pengadaan',
                "Pengadaan dibatalkan oleh superadmin: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'cancelled',
                    'dibatalkan_oleh' => Auth::user()->name,
                    'alasan_pembatalan' => $request->alasan_pembatalan,
                    'total_items' => $procurement->total_jumlah,
                    'total_value' => $procurement->total_perkiraan,
                ])
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil dibatalkan'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error cancelling procurement by superadmin: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mendapatkan statistik item dalam pengadaan
     */
    public function getItemStats($id)
    {
        // Cek apakah user adalah superadmin
        if (auth()->user()->role !== 'superadmin') {
            return response()->json([
                'error' => 'Akses ditolak. Hanya untuk superadmin.'
            ], 403);
        }
        
        $procurement = Procurement::with('items')->findOrFail($id);
        
        $stats = [
            'total_items' => $procurement->items->count(),
            'approved_items' => $procurement->items->where('status', 'approved')->count(),
            'rejected_items' => $procurement->items->where('status', 'rejected')->count(),
            'pending_items' => $procurement->items->where('status', 'pending')->count(),
            'cancelled_items' => $procurement->items->where('status', 'cancelled')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'procurement_status' => $procurement->status,
        ]);
    }
}