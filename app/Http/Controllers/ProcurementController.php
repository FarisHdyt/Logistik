<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use App\Models\ProcurementItem;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Gudang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProcurementController extends Controller
{
    /**
     * Menampilkan halaman pengadaan barang
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Query dasar dengan eager loading
        $query = Procurement::with([
            'user',
            'items',
            'disetujuiOleh',
            'selesaiOleh'
        ]);
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_pengadaan', 'like', '%' . $search . '%')
                  ->orWhereHas('items', function($itemQuery) use ($search) {
                      $itemQuery->where('nama_barang', 'like', '%' . $search . '%')
                               ->orWhere('kode_barang', 'like', '%' . $search . '%');
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
        
        // Filter berdasarkan prioritas
        if ($request->has('prioritas') && !empty($request->prioritas)) {
            $query->where('prioritas', $request->prioritas);
        }
        
        // Sorting
        $query->orderByRaw("FIELD(prioritas, 'mendesak', 'tinggi', 'normal')")
              ->orderBy('created_at', 'desc');
        
        // Pagination
        $procurements = $query->paginate(15)->withQueryString();
        
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
                $q->where('kode_pengadaan', 'like', '%' . $search . '%')
                  ->orWhereHas('items', function($itemQuery) use ($search) {
                      $itemQuery->where('nama_barang', 'like', '%' . $search . '%')
                               ->orWhere('kode_barang', 'like', '%' . $search . '%');
                  });
            });
        }
        
        if ($request->has('tipe') && !empty($request->tipe)) {
            $statsQuery->where('tipe_pengadaan', $request->tipe);
        }
        
        if ($request->has('status') && !empty($request->status) && $request->status != 'all') {
            $statsQuery->where('status', $request->status);
        }
        
        $total = $statsQuery->count();
        $pending = clone $statsQuery;
        $approved = clone $statsQuery;
        $completed = clone $statsQuery;
        $cancelled = clone $statsQuery;
        
        $pendingCount = $pending->where('status', 'pending')->count();
        $approvedCount = $approved->where('status', 'approved')->count();
        $completedCount = $completed->where('status', 'completed')->count();
        $cancelledCount = $cancelled->where('status', 'cancelled')->count();
        
        // Hitung total nilai pengadaan (semua status)
        $totalValueQuery = Procurement::with('items')->get();
        $totalValue = 0;
            
        foreach ($totalValueQuery as $procurement) {
            if ($procurement->items && $procurement->items->count() > 0) {
                foreach ($procurement->items as $item) {
                    $totalValue += ($item->jumlah ?? 0) * ($item->harga_perkiraan ?? 0);
                }
            }
        }
        
        return [
            'total' => $total,
            'pending' => $pendingCount,
            'approved' => $approvedCount,
            'completed' => $completedCount,
            'cancelled' => $cancelledCount,
            'total_value' => $totalValue,
        ];
    }
    
    /**
     * Menampilkan form tambah pengadaan
     */
    public function create()
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();
        $gudangs = Gudang::orderBy('nama_gudang')->get();
        
        return view('admin.procurement.create', compact('barangs', 'kategoris', 'satuans', 'gudangs'));
    }
    
    public function store(Request $request)
    {
        Log::info('Store procurement request data:', $request->all());
        
        try {
            DB::beginTransaction();
            
            // Tentukan apakah ini multi item
            $isMultiItem = $request->has('items') && is_array($request->items) && count($request->items) > 0;
            
            // Validasi data dasar
            $validatedData = $request->validate([
                'tipe_pengadaan' => 'required|in:baru,restock',
                'prioritas' => 'required|in:normal,tinggi,mendesak',
                'alasan_pengadaan' => 'required|string|min:10',
                'catatan' => 'nullable|string',
            ]);
            
            // BUAT PROCUREMENT DENGAN KODE YANG SUDAH DI-GENERATE
            $procurement = new Procurement();
            $procurement->tipe_pengadaan = $validatedData['tipe_pengadaan'];
            $procurement->prioritas = $validatedData['prioritas'];
            $procurement->alasan_pengadaan = $validatedData['alasan_pengadaan'];
            $procurement->catatan = $validatedData['catatan'] ?? null;
            $procurement->status = 'pending';
            $procurement->user_id = Auth::id();
            $procurement->is_multi_item = $isMultiItem;
            
            // Kode akan di-generate OTOMATIS oleh boot method di model
            $procurement->save();
            
            Log::info('Procurement created:', [
                'id' => $procurement->id,
                'kode_pengadaan' => $procurement->kode_pengadaan,
                'is_multi_item' => $procurement->is_multi_item,
            ]);
            
            if ($isMultiItem) {
                // Validasi untuk multi item - PERBAIKAN: kode_barang dibuat nullable
                $validatedItems = $request->validate([
                    'items' => 'required|array|min:1',
                    'items.*.barang_id' => 'nullable|exists:barangs,id',
                    'items.*.jumlah' => 'required|integer|min:1',
                    'items.*.harga_perkiraan' => 'required|numeric|min:0',
                    'items.*.deskripsi' => 'nullable|string|max:500',
                    'items.*.kategori_id' => 'nullable|exists:kategoris,id',
                    'items.*.satuan_id' => 'nullable|exists:satuans,id',
                    'items.*.gudang_id' => 'nullable|exists:gudangs,id',
                    'items.*.stok_minimal' => 'nullable|integer|min:1',
                    'items.*.nama_barang' => 'nullable|string|max:255', // Untuk barang baru
                    // Kode barang dibuat nullable karena akan digenerate otomatis untuk barang baru
                ]);
                
                // Simpan items
                foreach ($validatedItems['items'] as $itemData) {
                    $procurementItem = new ProcurementItem();
                    $procurementItem->procurement_id = $procurement->id;
                    $procurementItem->jumlah = $itemData['jumlah'];
                    $procurementItem->harga_perkiraan = $itemData['harga_perkiraan'];
                    $procurementItem->deskripsi = $itemData['deskripsi'] ?? null;
                    $procurementItem->stok_minimal = $itemData['stok_minimal'] ?? 10;
                    $procurementItem->status = 'pending'; // Set status awal
                    $procurementItem->tipe_pengadaan = $procurement->tipe_pengadaan; // Set tipe pengadaan
                    
                    // Jika ada barang_id, ambil data dari barang
                    if (!empty($itemData['barang_id'])) {
                        $barang = Barang::find($itemData['barang_id']);
                        if ($barang) {
                            $procurementItem->barang_id = $barang->id;
                            $procurementItem->kode_barang = $barang->kode_barang;
                            $procurementItem->nama_barang = $barang->nama_barang;
                            $procurementItem->kategori = $barang->kategori->nama_kategori ?? 'Umum';
                            $procurementItem->kategori_id = $barang->kategori_id;
                            $procurementItem->satuan = $barang->satuan->nama_satuan ?? 'Unit';
                            $procurementItem->satuan_id = $barang->satuan_id;
                            $procurementItem->gudang = $barang->gudang->nama_gudang ?? 'Gudang Utama';
                            $procurementItem->gudang_id = $barang->gudang_id;
                        }
                    } else {
                        // Untuk item tanpa barang_id (barang baru)
                        // PERBAIKAN: Generate kode barang otomatis untuk barang baru
                        $procurementItem->kode_barang = $this->generateKodeBarangBaru();
                        
                        // Gunakan nama_barang dari data atau default
                        $procurementItem->nama_barang = $itemData['nama_barang'] ?? 'Barang Baru';
                        
                        // Simpan kategori, satuan, dan gudang ID jika ada
                        if (!empty($itemData['kategori_id'])) {
                            $kategori = Kategori::find($itemData['kategori_id']);
                            if ($kategori) {
                                $procurementItem->kategori = $kategori->nama_kategori;
                                $procurementItem->kategori_id = $kategori->id;
                            }
                        } else {
                            $procurementItem->kategori = 'Umum';
                        }
                        
                        if (!empty($itemData['satuan_id'])) {
                            $satuan = Satuan::find($itemData['satuan_id']);
                            if ($satuan) {
                                $procurementItem->satuan = $satuan->nama_satuan;
                                $procurementItem->satuan_id = $satuan->id;
                            }
                        } else {
                            $procurementItem->satuan = 'Unit';
                        }
                        
                        if (!empty($itemData['gudang_id'])) {
                            $gudang = Gudang::find($itemData['gudang_id']);
                            if ($gudang) {
                                $procurementItem->gudang = $gudang->nama_gudang;
                                $procurementItem->gudang_id = $gudang->id;
                            }
                        } else {
                            $procurementItem->gudang = 'Gudang Utama';
                        }
                    }
                    
                    $procurementItem->save();
                }
                
                $message = 'Pengajuan pengadaan multi item berhasil dibuat';
                
            } else {
                // Validasi untuk single item
                $singleItemData = $request->validate([
                    'barang_id' => 'required|exists:barangs,id',
                    'jumlah' => 'required|integer|min:1',
                    'harga_perkiraan' => 'required|numeric|min:0',
                    'keterangan' => 'nullable|string',
                ]);
                
                // Ambil data barang
                $barang = Barang::find($singleItemData['barang_id']);
                
                if (!$barang) {
                    throw new \Exception('Barang tidak ditemukan');
                }
                
                // Buat item untuk single item
                $procurementItem = new ProcurementItem();
                $procurementItem->procurement_id = $procurement->id;
                $procurementItem->barang_id = $barang->id;
                $procurementItem->kode_barang = $barang->kode_barang;
                $procurementItem->nama_barang = $barang->nama_barang;
                $procurementItem->kategori = $barang->kategori->nama_kategori ?? 'Umum';
                $procurementItem->kategori_id = $barang->kategori_id;
                $procurementItem->satuan = $barang->satuan->nama_satuan ?? 'Unit';
                $procurementItem->satuan_id = $barang->satuan_id;
                $procurementItem->gudang = $barang->gudang->nama_gudang ?? 'Gudang Utama';
                $procurementItem->gudang_id = $barang->gudang_id;
                $procurementItem->jumlah = $singleItemData['jumlah'];
                $procurementItem->harga_perkiraan = $singleItemData['harga_perkiraan'];
                $procurementItem->deskripsi = $singleItemData['keterangan'] ?? null;
                $procurementItem->stok_minimal = $barang->stok_minimal ?? 10;
                $procurementItem->status = 'pending'; // Set status awal
                $procurementItem->tipe_pengadaan = $procurement->tipe_pengadaan; // Set tipe pengadaan
                $procurementItem->save();
                
                $message = 'Pengajuan pengadaan berhasil dibuat';
            }
            
            // Log aktivitas - HANYA CREATE
            $this->logActivity(
                'Create',
                "Pengajuan pengadaan dibuat: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'kode_pengadaan' => $procurement->kode_pengadaan,
                    'tipe' => $procurement->tipe_pengadaan,
                    'is_multi_item' => $procurement->is_multi_item,
                    'items_count' => $procurement->items->count(),
                ])
            );
            
            DB::commit();
            
            return redirect()->route('admin.procurement')
                ->with('success', $message . '. Kode: ' . $procurement->kode_pengadaan)
                ->with('procurement_id', $procurement->id);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error store procurement: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
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
            'user',
            'disetujuiOleh',
            'selesaiOleh',
            'dibatalkanOleh',
            'items'
        ])->findOrFail($id);
        
        // Hitung total jumlah dan nilai
        $totalJumlah = $procurement->items->sum('jumlah');
        $totalNilai = $procurement->items->sum(function($item) {
            return ($item->jumlah ?? 0) * ($item->harga_perkiraan ?? 0);
        });
        
        $data = [
            'success' => true,
            'procurement' => [
                'id' => $procurement->id,
                'kode_pengadaan' => $procurement->kode_pengadaan,
                'tipe_pengadaan' => $procurement->tipe_pengadaan,
                'tipe_pengadaan_display' => $procurement->tipe_pengadaan == 'baru' ? 'Baru' : 'Restock',
                'prioritas' => $procurement->prioritas,
                'prioritas_display' => $this->getPrioritasDisplay($procurement->prioritas),
                'alasan_pengadaan' => $procurement->alasan_pengadaan,
                'catatan' => $procurement->catatan,
                'status' => $procurement->status,
                'status_display' => $this->getStatusDisplay($procurement->status),
                'user_id' => $procurement->user_id,
                'user_name' => $procurement->user->name ?? 'Unknown',
                'created_at' => $procurement->created_at->toDateTimeString(),
                'approved_at' => $procurement->tanggal_disetujui?->toDateTimeString(),
                'completed_at' => $procurement->tanggal_selesai?->toDateTimeString(),
                'cancelled_at' => $procurement->tanggal_dibatalkan?->toDateTimeString(),
                'rejected_at' => $procurement->tanggal_ditolak?->toDateTimeString(),
                'alasan_pembatalan' => $procurement->alasan_pembatalan,
                'alasan_penolakan' => $procurement->alasan_penolakan,
                'disetujui_oleh' => $procurement->disetujuiOleh->name ?? null,
                'selesai_oleh' => $procurement->selesaiOleh->name ?? null,
                'dibatalkan_oleh' => $procurement->dibatalkanOleh->name ?? null,
                'is_multi_item' => $procurement->is_multi_item,
                'items' => $procurement->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'barang_id' => $item->barang_id,
                        'kode_barang' => $item->kode_barang,
                        'nama_barang' => $item->nama_barang,
                        'kategori' => $item->kategori,
                        'kategori_id' => $item->kategori_id,
                        'satuan' => $item->satuan,
                        'satuan_id' => $item->satuan_id,
                        'gudang' => $item->gudang,
                        'gudang_id' => $item->gudang_id,
                        'jumlah' => $item->jumlah,
                        'harga_perkiraan' => (float) $item->harga_perkiraan,
                        'deskripsi' => $item->deskripsi,
                        'stok_minimal' => $item->stok_minimal,
                        'status' => $item->status,
                        'status_display' => $this->getStatusDisplay($item->status),
                        'subtotal' => $item->jumlah * $item->harga_perkiraan,
                    ];
                })->toArray(),
                'items_count' => $procurement->items->count(),
                'total_jumlah' => $totalJumlah,
                'total_nilai' => $totalNilai,
            ]
        ];
        
        return response()->json($data);
    }
    
    /**
     * Menyelesaikan pengadaan - DIPERBAIKI
     */
    public function complete($id)
    {
        try {
            DB::beginTransaction();
            
            $procurement = Procurement::with('items')->findOrFail($id);
            
            // Validasi status procurement
            if ($procurement->status != 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengadaan dengan status "Disetujui" yang dapat diselesaikan'
                ], 400);
            }
            
            // ========== PERBAIKAN UTAMA: FILTER ITEM BERDASARKAN STATUS ==========
            // Hanya proses item dengan status 'pending' atau 'approved'
            // Item dengan status 'rejected' atau 'cancelled' akan dilewati
            $itemsToProcess = $procurement->items->filter(function($item) {
                return in_array($item->status, ['pending', 'approved']);
            });
            
            if ($itemsToProcess->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada item yang dapat diproses. Semua item sudah ditolak atau dibatalkan.'
                ], 400);
            }
            
            $processedCount = 0;
            $skippedCount = 0;
            
            foreach ($itemsToProcess as $item) {
                try {
                    // Proses item (restock atau buat barang baru)
                    $this->processProcurementItem($item, $procurement);
                    
                    // Update status item menjadi 'completed'
                    $item->update([
                        'status' => 'completed',
                        'approved_at' => now(), // Gunakan approved_at sebagai timestamp processed
                        'approved_by' => Auth::id()
                    ]);
                    
                    $processedCount++;
                    
                } catch (\Exception $e) {
                    Log::error('Error processing procurement item ' . $item->id . ': ' . $e->getMessage());
                    
                    // Update status item menjadi failed
                    $item->update([
                        'status' => 'rejected', // Gunakan rejected untuk item yang gagal
                        'rejected_at' => now(),
                        'rejected_by' => Auth::id(),
                        'alasan_penolakan' => 'Gagal diproses: ' . $e->getMessage()
                    ]);
                    
                    $skippedCount++;
                }
            }
            
            // Update status procurement menjadi completed jika ada item yang berhasil diproses
            if ($processedCount > 0) {
                $procurement->update([
                    'status' => 'completed',
                    'selesai_oleh' => Auth::id(),
                    'tanggal_selesai' => now(),
                ]);
                
                // Log aktivitas - HANYA UPDATE
                $this->logActivity(
                    'Update',
                    "Pengadaan diselesaikan: {$procurement->kode_pengadaan} (Diproses: {$processedCount}, Gagal: {$skippedCount})",
                    $procurement,
                    json_encode([
                        'procurement_id' => $procurement->id,
                        'kode_pengadaan' => $procurement->kode_pengadaan,
                        'old_status' => 'approved',
                        'new_status' => 'completed',
                        'total_items' => $procurement->items->count(),
                        'processed_items' => $processedCount,
                        'failed_items' => $skippedCount,
                    ])
                );
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pengadaan berhasil ditandai selesai. ' . 
                                "Berhasil diproses: {$processedCount} item" . 
                                ($skippedCount > 0 ? ", Gagal: {$skippedCount} item" : '')
                ]);
            } else {
                DB::rollBack();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada item yang berhasil diproses. Semua item gagal.'
                ], 400);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error completing procurement: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Membatalkan pengadaan - DIPERBAIKI
     */
    public function cancel(Request $request, $id)
    {
        $validated = $request->validate([
            'alasan_pembatalan' => 'required|string|min:10',
        ]);
        
        try {
            DB::beginTransaction();
            
            $procurement = Procurement::with('items')->findOrFail($id);
            
            // Validasi status
            if (!in_array($procurement->status, ['pending', 'approved'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengadaan dengan status "Menunggu" atau "Disetujui" yang dapat dibatalkan'
                ], 400);
            }
            
            // Update status procurement
            $oldStatus = $procurement->status;
            $procurement->update([
                'status' => 'cancelled',
                'alasan_pembatalan' => $validated['alasan_pembatalan'],
                'dibatalkan_oleh' => Auth::id(),
                'tanggal_dibatalkan' => now(),
            ]);
            
            // Update status items yang masih pending atau approved menjadi cancelled
            $cancelledItems = $procurement->items()
                ->whereIn('status', ['pending', 'approved'])
                ->update([
                    'status' => 'cancelled',
                    'rejected_at' => now(), // Gunakan rejected_at untuk timestamp pembatalan
                    'rejected_by' => Auth::id(),
                    'alasan_penolakan' => 'Pengadaan dibatalkan: ' . $validated['alasan_pembatalan']
                ]);
            
            // Log aktivitas - HANYA UPDATE
            $this->logActivity(
                'Update',
                "Pengadaan dibatalkan: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'kode_pengadaan' => $procurement->kode_pengadaan,
                    'old_status' => $oldStatus,
                    'new_status' => 'cancelled',
                    'alasan_pembatalan' => $validated['alasan_pembatalan'],
                    'cancelled_items' => $cancelledItems
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
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Proses item pengadaan (untuk barang yang diselesaikan) - DIPERBAIKI
     */
    private function processProcurementItem(ProcurementItem $item, Procurement $procurement)
    {
        Log::info('Processing procurement item:', [
            'item_id' => $item->id,
            'barang_id' => $item->barang_id,
            'kode_barang' => $item->kode_barang,
            'status' => $item->status,
        ]);
        
        // Jika ada barang_id (restock atau barang yang sudah ada)
        if ($item->barang_id) {
            $barang = Barang::find($item->barang_id);
            if ($barang) {
                $oldStock = $barang->stok;
                $barang->increment('stok', $item->jumlah);
                
                // Update harga beli jika ada
                if ($item->harga_perkiraan > 0) {
                    $barang->update(['harga_beli' => $item->harga_perkiraan]);
                }
                
                // Log aktivitas - HANYA UPDATE (restock)
                $this->logActivity(
                    'Update',
                    "Barang direstock: {$barang->kode_barang} - {$barang->nama_barang} (+{$item->jumlah})",
                    $barang,
                    json_encode([
                        'procurement_id' => $procurement->id,
                        'item_id' => $item->id,
                        'barang_id' => $barang->id,
                        'old_stok' => $oldStock,
                        'added_stok' => $item->jumlah,
                        'new_stok' => $barang->stok,
                    ])
                );
            } else {
                throw new \Exception("Barang dengan ID {$item->barang_id} tidak ditemukan");
            }
        } else {
            // PERBAIKAN: Generate kode barang baru jika belum ada atau tidak valid
            $kodeBarang = $item->kode_barang;
            
            // Validasi format kode barang
            if (empty($kodeBarang) || !$this->isValidKodeBarangFormat($kodeBarang)) {
                $kodeBarang = $this->generateKodeBarangBaru();
            }
            
            // Cek apakah kode barang sudah ada di database
            $existingBarang = Barang::where('kode_barang', $kodeBarang)->first();
            if ($existingBarang) {
                // Jika kode sudah ada, generate kode baru
                $kodeBarang = $this->generateKodeBarangBaru();
            }
            
            // Buat barang baru dengan data lengkap
            $barangData = [
                'kode_barang' => $kodeBarang,
                'nama_barang' => $item->nama_barang,
                'stok' => $item->jumlah,
                'stok_minimal' => $item->stok_minimal ?? 10,
                'harga_beli' => $item->harga_perkiraan,
                'harga_jual' => $item->harga_perkiraan * 1.3, // Harga jual = harga beli + 30%
                'lokasi' => 'Gudang Utama',
                'keterangan' => 'Barang dari pengadaan: ' . $procurement->kode_pengadaan . 
                              ($item->deskripsi ? ' - ' . $item->deskripsi : ''),
            ];
            
            // Tambahkan kategori_id jika ada
            if (!empty($item->kategori_id)) {
                $barangData['kategori_id'] = $item->kategori_id;
            } else {
                // Coba cari kategori berdasarkan nama
                if (!empty($item->kategori)) {
                    $kategori = Kategori::where('nama_kategori', $item->kategori)->first();
                    if ($kategori) {
                        $barangData['kategori_id'] = $kategori->id;
                    } else {
                        // Buat kategori baru jika tidak ditemukan
                        $newKategori = Kategori::create([
                            'nama_kategori' => $item->kategori,
                            'deskripsi' => 'Kategori otomatis dibuat dari pengadaan'
                        ]);
                        $barangData['kategori_id'] = $newKategori->id;
                    }
                }
            }
            
            // Tambahkan satuan_id jika ada
            if (!empty($item->satuan_id)) {
                $barangData['satuan_id'] = $item->satuan_id;
            } else {
                // Coba cari satuan berdasarkan nama
                if (!empty($item->satuan)) {
                    $satuan = Satuan::where('nama_satuan', $item->satuan)->first();
                    if ($satuan) {
                        $barangData['satuan_id'] = $satuan->id;
                    } else {
                        // Buat satuan baru jika tidak ditemukan
                        $newSatuan = Satuan::create([
                            'nama_satuan' => $item->satuan,
                            'deskripsi' => 'Satuan otomatis dibuat dari pengadaan'
                        ]);
                        $barangData['satuan_id'] = $newSatuan->id;
                    }
                }
            }
            
            // Tambahkan gudang_id jika ada
            if (!empty($item->gudang_id)) {
                $barangData['gudang_id'] = $item->gudang_id;
            } else {
                // Coba cari gudang berdasarkan nama
                if (!empty($item->gudang)) {
                    $gudang = Gudang::where('nama_gudang', $item->gudang)->first();
                    if ($gudang) {
                        $barangData['gudang_id'] = $gudang->id;
                        $barangData['lokasi'] = $gudang->lokasi;
                    } else {
                        // Gunakan gudang default
                        $defaultGudang = Gudang::first();
                        if ($defaultGudang) {
                            $barangData['gudang_id'] = $defaultGudang->id;
                            $barangData['lokasi'] = $defaultGudang->lokasi;
                        }
                    }
                } else {
                    // Gunakan gudang default
                    $defaultGudang = Gudang::first();
                    if ($defaultGudang) {
                        $barangData['gudang_id'] = $defaultGudang->id;
                        $barangData['lokasi'] = $defaultGudang->lokasi;
                    }
                }
            }
            
            Log::info('Creating new barang with data:', $barangData);
            
            // Buat barang baru
            $barang = Barang::create($barangData);
            
            // Update procurement item dengan barang_id yang baru dibuat
            $item->update(['barang_id' => $barang->id]);
            
            // Log aktivitas - HANYA CREATE (barang baru)
            $this->logActivity(
                'Create',
                "Barang baru dibuat: {$barang->kode_barang} - {$barang->nama_barang}",
                $barang,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'item_id' => $item->id,
                    'barang_id' => $barang->id,
                    'jumlah' => $item->jumlah,
                    'harga_beli' => $item->harga_perkiraan,
                    'kategori_id' => $barang->kategori_id,
                    'satuan_id' => $barang->satuan_id,
                    'gudang_id' => $barang->gudang_id,
                ])
            );
        }
    }
    
    /**
     * Generate kode barang baru dengan format BRG-YYYY-XXX
     */
    private function generateKodeBarangBaru()
    {
        $year = date('Y');
        
        // Cari kode terakhir untuk tahun ini
        $lastBarang = Barang::where('kode_barang', 'like', "BRG-{$year}-%")
            ->orderBy('kode_barang', 'desc')
            ->first();
        
        if ($lastBarang) {
            // Extract nomor urut dari kode terakhir
            if (preg_match('/BRG-' . $year . '-(\d{3})/', $lastBarang->kode_barang, $matches)) {
                $lastNumber = (int)$matches[1];
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
        } else {
            $nextNumber = 1;
        }
        
        // Format nomor dengan 3 digit
        $nomorUrut = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        
        return "BRG-{$year}-{$nomorUrut}";
    }
    
    /**
     * Validasi format kode barang
     */
    private function isValidKodeBarangFormat($kode)
    {
        // Format yang valid: BRG-YYYY-XXX
        return preg_match('/^BRG-\d{4}-\d{3}$/', $kode);
    }
    
    /**
     * API: Mendapatkan data barang untuk select2
     */
    public function getBarangForSelect(Request $request)
    {
        $query = Barang::with(['kategori', 'satuan', 'gudang'])
            ->select('id', 'kode_barang', 'nama_barang', 'stok', 'stok_minimal', 'kategori_id', 'satuan_id', 'gudang_id')
            ->orderBy('nama_barang');
        
        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }
        
        $barang = $query->limit(50)->get();
        
        return response()->json($barang->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->kode_barang . ' - ' . $item->nama_barang . ' (Stok: ' . $item->stok . ')',
                'kode_barang' => $item->kode_barang,
                'nama_barang' => $item->nama_barang,
                'stok' => $item->stok,
                'stok_minimal' => $item->stok_minimal,
                'kategori' => $item->kategori->nama_kategori ?? null,
                'kategori_id' => $item->kategori_id,
                'satuan' => $item->satuan->nama_satuan ?? null,
                'satuan_id' => $item->satuan_id,
                'gudang' => $item->gudang->nama_gudang ?? null,
                'gudang_id' => $item->gudang_id,
            ];
        }));
    }
    
    /**
     * Mendapatkan data untuk form edit pengadaan
     */
    public function edit($id)
    {
        $procurement = Procurement::with(['items'])->findOrFail($id);
        
        if ($procurement->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengadaan dengan status "Menunggu" yang dapat diedit'
            ], 400);
        }
        
        $barangs = Barang::orderBy('nama_barang')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();
        $gudangs = Gudang::orderBy('nama_gudang')->get();
        
        return response()->json([
            'success' => true,
            'procurement' => $procurement,
            'barangs' => $barangs,
            'kategoris' => $kategoris,
            'satuans' => $satuans,
            'gudangs' => $gudangs,
        ]);
    }
    
    /**
     * Update pengadaan
     */
    public function update(Request $request, $id)
    {
        $procurement = Procurement::with(['items'])->findOrFail($id);
        
        if ($procurement->status != 'pending') {
            return redirect()->back()
                ->with('error', 'Hanya pengadaan dengan status "Menunggu" yang dapat diedit');
        }
        
        try {
            DB::beginTransaction();
            
            // Validasi data dasar
            $validatedData = $request->validate([
                'prioritas' => 'required|in:normal,tinggi,mendesak',
                'alasan_pengadaan' => 'required|string|min:10',
                'catatan' => 'nullable|string',
            ]);
            
            // Update procurement
            $procurement->update($validatedData);
            
            // Log aktivitas - HANYA UPDATE
            $this->logActivity(
                'Update',
                "Pengadaan diupdate: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'old_data' => $procurement->getOriginal(),
                    'new_data' => $validatedData,
                ])
            );
            
            DB::commit();
            
            return redirect()->route('admin.procurement')
                ->with('success', 'Pengadaan berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating procurement: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Hapus pengadaan
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $procurement = Procurement::findOrFail($id);
            
            if ($procurement->status != 'pending') {
                return redirect()->back()
                    ->with('error', 'Hanya pengadaan dengan status "Menunggu" yang dapat dihapus');
            }
            
            $kodePengadaan = $procurement->kode_pengadaan;
            
            // Hapus items terlebih dahulu
            $procurement->items()->delete();
            
            // Hapus procurement
            $procurement->delete();
            
            // Log aktivitas - HANYA DELETE
            $this->logActivity(
                'Delete',
                "Pengadaan dihapus: {$kodePengadaan}",
                null,
                json_encode([
                    'procurement_id' => $id,
                    'kode_pengadaan' => $kodePengadaan,
                ])
            );
            
            DB::commit();
            
            return redirect()->route('admin.procurement')
                ->with('success', 'Pengadaan berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting procurement: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Approve pengadaan - DIPERBAIKI
     */
    public function approve($id)
    {
        try {
            DB::beginTransaction();
            
            $procurement = Procurement::with('items')->findOrFail($id);
            
            if ($procurement->status != 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengadaan dengan status "Menunggu" yang dapat disetujui'
                ], 400);
            }
            
            // Update status procurement
            $procurement->update([
                'status' => 'approved',
                'disetujui_oleh' => Auth::id(),
                'tanggal_disetujui' => now(),
            ]);
            
            // Update status items menjadi 'approved'
            $procurement->items()->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);
            
            // Log aktivitas - HANYA UPDATE
            $this->logActivity(
                'Update',
                "Pengadaan disetujui: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'kode_pengadaan' => $procurement->kode_pengadaan,
                    'old_status' => 'pending',
                    'new_status' => 'approved',
                    'approved_items' => $procurement->items()->count()
                ])
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil disetujui'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error approving procurement: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reject pengadaan - DIPERBAIKI
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ]);
        
        try {
            DB::beginTransaction();
            
            $procurement = Procurement::with('items')->findOrFail($id);
            
            if ($procurement->status != 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengadaan dengan status "Menunggu" yang dapat ditolak'
                ], 400);
            }
            
            // Update status procurement
            $procurement->update([
                'status' => 'rejected',
                'alasan_penolakan' => $validated['alasan_penolakan'],
                'tanggal_ditolak' => now(),
                'disetujui_oleh' => Auth::id(), // Simpan siapa yang menolak
            ]);
            
            // Update status items menjadi 'rejected'
            $procurement->items()->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => Auth::id(),
                'alasan_penolakan' => $validated['alasan_penolakan']
            ]);
            
            // Log aktivitas - HANYA UPDATE
            $this->logActivity(
                'Update',
                "Pengadaan ditolak: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode([
                    'procurement_id' => $procurement->id,
                    'kode_pengadaan' => $procurement->kode_pengadaan,
                    'old_status' => 'pending',
                    'new_status' => 'rejected',
                    'alasan_penolakan' => $validated['alasan_penolakan'],
                    'rejected_items' => $procurement->items()->count()
                ])
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil ditolak'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error rejecting procurement: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Helper: Mendapatkan display status
     */
    private function getStatusDisplay($status)
    {
        $statusMap = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'rejected' => 'Ditolak',
        ];
        
        return $statusMap[$status] ?? ucfirst($status);
    }
    
    /**
     * Helper: Mendapatkan display prioritas
     */
    private function getPrioritasDisplay($prioritas)
    {
        $prioritasMap = [
            'normal' => 'Normal',
            'tinggi' => 'Tinggi',
            'mendesak' => 'Mendesak',
        ];
        
        return $prioritasMap[$prioritas] ?? ucfirst($prioritas);
    }
    
    /**
     * Helper method untuk log aktivitas
     * HANYA untuk: Login, Logout, Create, Update, Delete
     */
    private function logActivity($action, $description, $relatedModel = null, $details = null)
    {
        // Daftar aksi yang diperbolehkan
        $allowedActions = ['Login', 'Logout', 'Create', 'Update', 'Delete'];
        
        // Hanya log jika aksi termasuk dalam daftar yang diperbolehkan
        if (!in_array($action, $allowedActions)) {
            return;
        }
        
        try {
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
                'ip' => request()->ip(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error saving activity log: ' . $e->getMessage());
        }
    }
    
    /**
     * Export data pengadaan ke Excel
     */
    public function export(Request $request)
    {
        $query = Procurement::with(['user', 'items'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters if any
        if ($request->has('status') && !empty($request->status) && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('tipe') && !empty($request->tipe)) {
            $query->where('tipe_pengadaan', $request->tipe);
        }
        
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $procurements = $query->get();
        
        // Return data untuk diproses oleh Excel (gunakan package seperti Maatwebsite/Laravel-Excel)
        // Contoh sederhana:
        $data = [
            'procurements' => $procurements,
            'title' => 'Laporan Pengadaan Barang',
            'filters' => $request->all()
        ];
        
        // Di sini Anda bisa return view untuk export atau download file
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Data siap di-export'
        ]);
    }
}