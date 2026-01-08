<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\PermintaanDetail;
use App\Models\Barang;
use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ActivityLogController;

class PermintaanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $status = request('status');
        $search = request('search');
        $satker = request('satker');
        
        $requests = Permintaan::with([
                'user', 
                'barang.satuan',
                'satker',
                'details.barang.satuan',
                'details.satker' // ✅ SATKER PER DETAIL
            ])
            ->when($status && $status != 'all', function($query) use ($status) {
                if ($status == 'mixed') {
                    return $query->where('status', 'approved')
                        ->whereHas('details', function($q) {
                            $q->where('status', 'rejected');
                        });
                }
                return $query->where('status', $status);
            })
            ->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('kode_permintaan', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('barang', function($barangQuery) use ($search) {
                          $barangQuery->where('nama_barang', 'like', "%{$search}%")
                                      ->orWhere('kode_barang', 'like', "%{$search}%");
                      })
                      ->orWhereHas('details.barang', function($detailQuery) use ($search) {
                          $detailQuery->where('nama_barang', 'like', "%{$search}%")
                                      ->orWhere('kode_barang', 'like', "%{$search}%");
                      });
                });
            })
            ->when($satker, function($query) use ($satker) {
                return $query->where('satker_id', $satker)
                            ->orWhereHas('details', function($q) use ($satker) {
                                $q->where('satker_id', $satker);
                            });
            })
            ->latest()
            ->paginate(10);
        
        $stats = [
            'total_requests' => Permintaan::count(),
            'pending_requests' => Permintaan::where('status', 'pending')->count(),
            'approved_requests' => Permintaan::where('status', 'approved')
                ->whereDoesntHave('details', function($q) {
                    $q->where('status', 'rejected');
                })->count(),
            'rejected_requests' => Permintaan::where('status', 'rejected')->count(),
            'mixed_requests' => Permintaan::where('status', 'approved')
                ->whereHas('details', function($q) {
                    $q->where('status', 'rejected');
                })->count(),
            'delivered_requests' => Permintaan::where('status', 'delivered')->count(),
        ];
        
        $satkers = Satker::all();
        $barangs = Barang::where('stok', '>', 0)->get();
        
        return view('admin.requests', compact('user', 'requests', 'stats', 'satkers', 'barangs'));
    }
    
    /**
     * Show detail of a request
     */
    public function show($id)
    {
        try {
            \Log::info('🔄 Menampilkan detail permintaan ID: ' . $id);
            
            $permintaan = Permintaan::with([
                'user:id,name,email',
                'barang:id,kode_barang,nama_barang,stok,satuan_id,keterangan',
                'barang.satuan:id,nama_satuan',
                'satker:id,nama_satker,kode_satker',
                'details' => function($query) {
                    $query->with([
                        'barang:id,kode_barang,nama_barang,satuan_id,keterangan',
                        'barang.satuan:id,nama_satuan',
                        'satker:id,nama_satker,kode_satker' // ✅ SATKER DETAIL
                    ])->orderBy('id');
                },
                'approver:id,name',
                'deliverer:id,name'
            ])
            ->withCount('details')
            ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data' => $permintaan,
                'metadata' => [
                    'is_multi_barang' => $permintaan->details_count > 0,
                    'total_items' => $permintaan->details_count > 0 ? 
                        $permintaan->details->sum('jumlah') : $permintaan->jumlah,
                    'total_types' => $permintaan->details_count > 0 ? 
                        $permintaan->details_count : 1,
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('🔥 Error pada show permintaan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Permintaan tidak ditemukan',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Data tidak tersedia'
            ], 404);
        }
    }
    
    /**
     * Debug method untuk testing
     */
    public function debugShow($id)
    {
        try {
            $permintaan = Permintaan::with(['details', 'barang', 'satker', 'user'])
                ->find($id);
                
            return response()->json([
                'success' => true,
                'debug' => [
                    'exists' => !is_null($permintaan),
                    'id' => $permintaan ? $permintaan->id : null,
                    'kode' => $permintaan ? $permintaan->kode_permintaan : null,
                    'details_count' => $permintaan ? $permintaan->details->count() : 0,
                    'has_barang' => $permintaan && !is_null($permintaan->barang),
                    'has_satker' => $permintaan && !is_null($permintaan->satker),
                    'satker_id' => $permintaan ? $permintaan->satker_id : null,
                    'details_satker_ids' => $permintaan ? 
                        $permintaan->details->pluck('satker_id')->filter()->values()->toArray() : [],
                    'timestamp' => now()->toDateTimeString(),
                    'model_fields' => $permintaan ? array_keys($permintaan->getAttributes()) : []
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Approve entire request
     */
    public function approve(Request $request, Permintaan $permintaan)
    {
        if ($permintaan->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan sudah diproses.'
            ], 400);
        }
        
        try {
            $oldStatus = $permintaan->status;
            
            $permintaan->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'catatan' => $permintaan->catatan ?? 'Disetujui oleh admin - Menunggu pengiriman',
            ]);
            
            // Approve semua detail jika ada
            if ($permintaan->details()->exists()) {
                $permintaan->details()->update(['status' => 'approved']);
            }
            
            ActivityLogController::logApprovePermintaan($permintaan, $oldStatus);
            
            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil disetujui.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reject entire request
     */
    public function reject(Request $request, Permintaan $permintaan)
    {
        if ($permintaan->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan sudah diproses.'
            ], 400);
        }
        
        $validated = $request->validate([
            'reason' => 'required|string',
        ]);
        
        $oldStatus = $permintaan->status;
        
        $permintaan->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan' => $validated['reason'],
        ]);
        
        // Reject semua detail jika ada
        if ($permintaan->details()->exists()) {
            $permintaan->details()->update(['status' => 'rejected']);
        }
        
        ActivityLogController::logRejectPermintaan($permintaan, $oldStatus, $validated['reason']);
        
        return response()->json([
            'success' => true,
            'message' => 'Permintaan berhasil ditolak.'
        ]);
    }
    
    /**
     * Approve specific detail/item in a request
     */
    public function approveDetail(Request $request, $permintaanId, $detailId)
    {
        $permintaan = Permintaan::findOrFail($permintaanId);
        $detail = PermintaanDetail::where('permintaan_id', $permintaanId)
            ->where('id', $detailId)
            ->with('barang')
            ->firstOrFail();
        
        if ($detail->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Barang ini sudah disetujui sebelumnya.'
            ], 400);
        }
        
        if ($permintaan->status === 'delivered') {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan sudah dikirim, tidak dapat diubah.'
            ], 400);
        }
        
        try {
            DB::beginTransaction();
            
            $oldDetailStatus = $detail->status;
            $detail->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'catatan' => $detail->catatan ? $detail->catatan . ' - Disetujui' : 'Disetujui',
            ]);
            
            $this->updateParentRequestStatus($permintaan);
            
            $logData = [
                'permintaan_id' => $permintaan->id,
                'kode_permintaan' => $permintaan->kode_permintaan,
                'detail_id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'barang_nama' => $detail->barang->nama_barang ?? 'N/A',
                'old_status' => $oldDetailStatus,
                'new_status' => 'approved'
            ];
            
            ActivityLogController::logAction(
                'approve_detail',
                'Approve detail barang: ' . ($detail->barang->nama_barang ?? 'N/A') . 
                ' pada permintaan: ' . $permintaan->kode_permintaan,
                $logData
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil disetujui.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error approve detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reject specific detail/item in a request
     */
    public function rejectDetail(Request $request, $permintaanId, $detailId)
    {
        $permintaan = Permintaan::findOrFail($permintaanId);
        $detail = PermintaanDetail::where('permintaan_id', $permintaanId)
            ->where('id', $detailId)
            ->with('barang')
            ->firstOrFail();
        
        if ($detail->status === 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'Barang ini sudah ditolak sebelumnya.'
            ], 400);
        }
        
        if ($permintaan->status === 'delivered') {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan sudah dikirim, tidak dapat diubah.'
            ], 400);
        }
        
        $validated = $request->validate([
            'reason' => 'required|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $oldDetailStatus = $detail->status;
            $detail->update([
                'status' => 'rejected',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'catatan' => $validated['reason'],
            ]);
            
            $this->updateParentRequestStatus($permintaan);
            
            $logData = [
                'permintaan_id' => $permintaan->id,
                'kode_permintaan' => $permintaan->kode_permintaan,
                'detail_id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'barang_nama' => $detail->barang->nama_barang ?? 'N/A',
                'old_status' => $oldDetailStatus,
                'new_status' => 'rejected',
                'reason' => $validated['reason']
            ];
            
            ActivityLogController::logAction(
                'reject_detail',
                'Reject detail barang: ' . ($detail->barang->nama_barang ?? 'N/A') . 
                ' pada permintaan: ' . $permintaan->kode_permintaan,
                $logData
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditolak.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error reject detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update parent request status based on details
     */
    private function updateParentRequestStatus($permintaan)
    {
        if ($permintaan->details()->exists()) {
            $totalDetails = $permintaan->details()->count();
            $approvedDetails = $permintaan->details()->where('status', 'approved')->count();
            $rejectedDetails = $permintaan->details()->where('status', 'rejected')->count();
            $pendingDetails = $permintaan->details()->where('status', 'pending')->count();
            $deliveredDetails = $permintaan->details()->where('status', 'delivered')->count();
            
            if ($deliveredDetails == $totalDetails && $totalDetails > 0) {
                $permintaan->update(['status' => 'delivered']);
            } else if ($pendingDetails > 0) {
                $permintaan->update(['status' => 'pending']);
            } else if ($rejectedDetails == $totalDetails) {
                $permintaan->update(['status' => 'rejected']);
            } else if ($approvedDetails == $totalDetails) {
                $permintaan->update(['status' => 'approved']);
            } else {
                $permintaan->update(['status' => 'approved']);
            }
        }
    }
    
    /**
     * Mark request as delivered
     */
    public function markAsDelivered(Request $request, Permintaan $permintaan)
    {
        if ($permintaan->status != 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya permintaan yang sudah disetujui yang bisa ditandai sebagai terkirim.'
            ], 400);
        }
        
        DB::beginTransaction();
        try {
            $oldStatus = $permintaan->status;
            
            if ($permintaan->details()->exists()) {
                foreach ($permintaan->details as $detail) {
                    if ($detail->status == 'approved') {
                        if ($detail->barang->stok < $detail->jumlah) {
                            DB::rollback();
                            return response()->json([
                                'success' => false,
                                'message' => 'Stok tidak mencukupi untuk barang: ' . $detail->barang->nama_barang . 
                                            '. Stok tersedia: ' . $detail->barang->stok . 
                                            ', Jumlah yang diminta: ' . $detail->jumlah
                            ], 400);
                        }
                        
                        $detail->barang->decrement('stok', $detail->jumlah);
                        
                        $detail->update([
                            'status' => 'delivered',
                            'delivered_at' => now(),
                            'delivered_by' => auth()->id(),
                            'catatan' => $detail->catatan ? $detail->catatan . ' - Barang dikirim' : 'Barang dikirim'
                        ]);
                    }
                }
            } else {
                if ($permintaan->barang->stok < $permintaan->jumlah) {
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk dikirim. Stok tersedia: ' . 
                                    $permintaan->barang->stok . ', Jumlah yang diminta: ' . $permintaan->jumlah
                    ], 400);
                }
                $permintaan->barang->decrement('stok', $permintaan->jumlah);
            }
            
            $permintaan->update([
                'status' => 'delivered',
                'delivered_at' => now(),
                'delivered_by' => auth()->id(),
                'catatan' => $request->input('catatan', $permintaan->catatan . ' - Barang telah dikirim'),
            ]);
            
            ActivityLogController::logDeliverPermintaan($permintaan, $oldStatus, $request->input('catatan'));
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Permintaan telah ditandai sebagai terkirim.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error markAsDelivered: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Check satker structure
     */
    public function checkSatkerStructure($id)
    {
        try {
            $permintaan = Permintaan::with(['details.satker', 'satker'])->findOrFail($id);
            
            $structure = [
                'permintaan' => [
                    'id' => $permintaan->id,
                    'satker_id' => $permintaan->satker_id,
                    'satker' => $permintaan->satker ? [
                        'id' => $permintaan->satker->id,
                        'nama_satker' => $permintaan->satker->nama_satker
                    ] : null
                ],
                'details' => $permintaan->details->map(function($detail) {
                    return [
                        'id' => $detail->id,
                        'satker_id' => $detail->satker_id,
                        'satker' => $detail->satker ? [
                            'id' => $detail->satker->id,
                            'nama_satker' => $detail->satker->nama_satker
                        ] : null
                    ];
                }),
                'database_columns' => [
                    'permintaan_table' => DB::select('DESCRIBE permintaan'),
                    'permintaan_details_table' => DB::select('DESCRIBE permintaan_details')
                ]
            ];
            
            return response()->json([
                'success' => true,
                'structure' => $structure
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Fix satker issues
     */
    public function fixSatkerIssues($id)
    {
        try {
            DB::beginTransaction();
            
            $permintaan = Permintaan::with(['details'])->findOrFail($id);
            
            $updates = [];
            
            if (empty($permintaan->satker_id) && $permintaan->details->count() > 0) {
                $firstDetail = $permintaan->details->first();
                if ($firstDetail && $firstDetail->satker_id) {
                    $permintaan->update(['satker_id' => $firstDetail->satker_id]);
                    $updates[] = "Updated permintaan.satker_id to {$firstDetail->satker_id}";
                }
            }
            
            foreach ($permintaan->details as $detail) {
                if (empty($detail->satker_id) && $permintaan->satker_id) {
                    $detail->update(['satker_id' => $permintaan->satker_id]);
                    $updates[] = "Updated detail {$detail->id} satker_id to {$permintaan->satker_id}";
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($updates) > 0 ? 'Data diperbaiki' : 'Tidak ada masalah ditemukan',
                'updates' => $updates,
                'permintaan' => [
                    'id' => $permintaan->id,
                    'satker_id' => $permintaan->satker_id,
                    'details_count' => $permintaan->details->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}