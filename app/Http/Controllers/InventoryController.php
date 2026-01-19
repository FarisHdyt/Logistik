<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Gudang;
use App\Models\Procurement;
use App\Models\ProcurementItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Query dasar dengan relasi
        $query = Barang::with([
            'kategori:id,nama_kategori',
            'satuan:id,nama_satuan',
            'gudang:id,nama_gudang,lokasi'
        ]);
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%')
                  ->orWhereHas('kategori', function($query) use ($search) {
                      $query->where('nama_kategori', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('satuan', function($query) use ($search) {
                      $query->where('nama_satuan', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('gudang', function($query) use ($search) {
                      $query->where('nama_gudang', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Filter berdasarkan kategori
        if ($request->has('category') && !empty($request->category)) {
            $query->where('kategori_id', $request->category);
        }
        
        // Filter berdasarkan status stok
        if ($request->has('status') && !empty($request->status)) {
            switch ($request->status) {
                case 'out':
                    $query->where('stok', '<=', 0);
                    break;
                case 'critical':
                    $query->where('stok', '>', 0)
                          ->whereRaw('stok <= stok_minimal');
                    break;
                case 'low':
                    $query->whereRaw('stok > stok_minimal')
                          ->whereRaw('stok <= (stok_minimal * 2)');
                    break;
                case 'good':
                    $query->whereRaw('stok > (stok_minimal * 2)');
                    break;
            }
        }
        
        // Sorting dan pagination
        $items = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        // Data untuk filter dropdown
        $categories = Kategori::select('id', 'nama_kategori')->get();
        $units = Satuan::select('id', 'nama_satuan')->get();
        $warehouses = Gudang::select('id', 'nama_gudang')->get();
        
        // Hitung stats
        $stats = [
            'total_items' => Barang::count(),
            'total_categories' => $categories->count(),
            'critical_stock' => Barang::where('stok', '>', 0)
                                  ->whereRaw('stok <= stok_minimal')
                                  ->count(),
            'low_stock' => Barang::whereRaw('stok > stok_minimal')
                                ->whereRaw('stok <= (stok_minimal * 2)')
                                ->count(),
            'out_of_stock' => Barang::where('stok', '<=', 0)->count(),
        ];
        
        // Jika ada filter aktif
        if ($request->has('search') || $request->has('category') || $request->has('status')) {
            $filteredQuery = Barang::query();
            
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $filteredQuery->where(function($q) use ($search) {
                    $q->where('nama_barang', 'like', '%' . $search . '%')
                      ->orWhere('kode_barang', 'like', '%' . $search . '%')
                      ->orWhereHas('kategori', function($query) use ($search) {
                          $query->where('nama_kategori', 'like', '%' . $search . '%');
                      });
                });
            }
            
            if ($request->has('category') && !empty($request->category)) {
                $filteredQuery->where('kategori_id', $request->category);
            }
            
            if ($request->has('status') && !empty($request->status)) {
                switch ($request->status) {
                    case 'out':
                        $filteredQuery->where('stok', '<=', 0);
                        break;
                    case 'critical':
                        $filteredQuery->where('stok', '>', 0)
                                      ->whereRaw('stok <= stok_minimal');
                        break;
                    case 'low':
                        $filteredQuery->whereRaw('stok > stok_minimal')
                                      ->whereRaw('stok <= (stok_minimal * 2)');
                        break;
                    case 'good':
                        $filteredQuery->whereRaw('stok > (stok_minimal * 2)');
                        break;
                }
            }
            
            $filteredItems = $filteredQuery->get();
            
            $stats['filtered_total'] = $filteredItems->count();
            $stats['filtered_critical_stock'] = $filteredItems->filter(function($item) {
                return $item->stok > 0 && $item->stok <= $item->stok_minimal;
            })->count();
            $stats['filtered_low_stock'] = $filteredItems->filter(function($item) {
                return $item->stok > $item->stok_minimal && $item->stok <= ($item->stok_minimal * 2);
            })->count();
            $stats['filtered_out_of_stock'] = $filteredItems->filter(function($item) {
                return $item->stok <= 0;
            })->count();
        }
        
        // Data untuk view modal pengadaan
        $procurements = Procurement::where('status', 'pending')->count();
        
        return view('admin.inventory', compact(
            'user', 
            'items', 
            'categories', 
            'units', 
            'warehouses', 
            'stats',
            'procurements'
        ));
    }
    
    public function create()
    {
        $categories = Kategori::all();
        $units = Satuan::all();
        $warehouses = Gudang::all();
        return view('admin.inventory.create', compact('categories', 'units', 'warehouses'));
    }
    
    public function store(Request $request)
    {
        // DEBUG: Tampilkan semua data yang masuk
        \Log::info('=== STORE BARANG REQUEST DATA ===');
        \Log::info('Request Data:', $request->all());
        
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan_id' => 'required|exists:satuans,id',
            'gudang_id' => 'nullable|exists:gudangs,id',
            'stok' => 'required|integer|min:0',
            'stok_minimal' => 'required|integer|min:1',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'lokasi' => 'nullable',
            'keterangan' => 'nullable',
        ]);
        
        $data = $request->only([
            'kode_barang',
            'nama_barang',
            'kategori_id',
            'satuan_id',
            'gudang_id',
            'stok',
            'stok_minimal',
            'harga_beli',
            'harga_jual',
            'lokasi',
            'keterangan'
        ]);
        
        // DEBUG: Tampilkan data yang akan disimpan
        \Log::info('Data untuk disimpan:', $data);
        
        try {
            $barang = Barang::create($data);
            
            // DEBUG: Tampilkan data yang berhasil disimpan
            \Log::info('Barang berhasil disimpan:', $barang->toArray());
            \Log::info('Barang ID: ' . $barang->id);
            \Log::info('Kategori ID: ' . $barang->kategori_id);
            \Log::info('Satuan ID: ' . $barang->satuan_id);
            \Log::info('Gudang ID: ' . $barang->gudang_id);
            
            // Log aktivitas tambah barang - CREATE
            $this->logActivity(
                'Create',
                "Barang baru berhasil ditambahkan: {$barang->kode_barang} - {$barang->nama_barang}",
                $barang
            );
            
            return redirect()->route('admin.inventory')
                ->with('success', 'Barang berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            \Log::error('Error menyimpan barang: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan barang: ' . $e->getMessage());
        }
    }
    
    public function edit(Barang $barang)
    {
        $categories = Kategori::all();
        $units = Satuan::all();
        $warehouses = Gudang::all();
        
        // Load relasi untuk JSON response
        $barang->load(['kategori:id,nama_kategori', 'satuan:id,nama_satuan', 'gudang:id,nama_gudang']);
        
        return response()->json([
            'barang' => $barang,
            'categories' => $categories,
            'units' => $units,
            'warehouses' => $warehouses
        ]);
    }
    
    public function update(Request $request, Barang $barang)
    {
        // DEBUG: Tampilkan data update
        \Log::info('=== UPDATE BARANG REQUEST DATA ===');
        \Log::info('Barang ID: ' . $barang->id);
        \Log::info('Data Lama:', $barang->toArray());
        \Log::info('Request Data:', $request->all());
        
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan_id' => 'required|exists:satuans,id',
            'gudang_id' => 'nullable|exists:gudangs,id',
            'stok' => 'required|integer|min:0',
            'stok_minimal' => 'required|integer|min:1',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'lokasi' => 'nullable',
            'keterangan' => 'nullable',
        ]);
        
        $oldData = [
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'kategori_id' => $barang->kategori_id,
            'satuan_id' => $barang->satuan_id,
            'stok' => $barang->stok,
            'stok_minimal' => $barang->stok_minimal,
            'harga_beli' => $barang->harga_beli,
            'harga_jual' => $barang->harga_jual,
            'lokasi' => $barang->lokasi,
            'gudang_id' => $barang->gudang_id,
            'keterangan' => $barang->keterangan,
        ];
        
        $newData = $request->only([
            'kode_barang',
            'nama_barang',
            'kategori_id',
            'satuan_id',
            'gudang_id',
            'stok',
            'stok_minimal',
            'harga_beli',
            'harga_jual',
            'lokasi',
            'keterangan'
        ]);
        
        // DEBUG: Tampilkan data baru
        \Log::info('Data baru untuk update:', $newData);
        
        try {
            $barang->update($newData);
            
            // DEBUG: Tampilkan hasil update
            $barang->refresh();
            \Log::info('Barang setelah update:', $barang->toArray());
            
            // Log aktivitas ubah barang - UPDATE
            $this->logActivity(
                'Update',
                "Barang berhasil diperbarui: {$barang->kode_barang} - {$barang->nama_barang}",
                $barang,
                json_encode(['old_data' => $oldData, 'new_data' => $newData])
            );
            
            return redirect()->route('admin.inventory')
                ->with('success', 'Barang berhasil diperbarui.');
                
        } catch (\Exception $e) {
            \Log::error('Error update barang: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate barang: ' . $e->getMessage());
        }
    }
    
    public function destroy(Barang $barang)
    {
        // Log aktivitas hapus barang - DELETE
        $this->logActivity(
            'Delete',
            "Barang berhasil dihapus: {$barang->kode_barang} - {$barang->nama_barang}",
            $barang
        );
        
        $barang->delete();
        
        return redirect()->route('admin.inventory')
            ->with('success', 'Barang berhasil dihapus.');
    }
    
    public function show(Barang $barang)
    {
        // Load semua relasi yang dibutuhkan untuk detail
        $barang->load([
            'kategori:id,nama_kategori',
            'satuan:id,nama_satuan', 
            'gudang:id,nama_gudang,lokasi'
        ]);
        
        return response()->json([
            'barang' => $barang
        ]);
    }
    
    public function restock(Request $request, Barang $barang)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable',
        ]);
        
        $oldStok = $barang->stok;
        $addedStok = $request->jumlah;
        
        $barang->increment('stok', $addedStok);
        
        if ($request->filled('harga_beli')) {
            $barang->update(['harga_beli' => $request->harga_beli]);
        }
        
        // Log aktivitas restock barang - UPDATE (karena mengubah stok)
        $this->logActivity(
            'Update',
            "Barang direstock: {$barang->kode_barang} - {$barang->nama_barang} (Stok +{$addedStok})",
            $barang,
            json_encode([
                'old_stok' => $oldStok,
                'added_stok' => $addedStok,
                'new_stok' => $barang->stok,
                'keterangan' => $request->keterangan
            ])
        );
        
        return redirect()->route('admin.inventory')
            ->with('success', 'Stok berhasil ditambahkan.');
    }
    
    public function getBarangByKode($kode)
    {
        $barang = Barang::with([
            'kategori:id,nama_kategori',
            'satuan:id,nama_satuan',
            'gudang:id,nama_gudang'
        ])
        ->where('kode_barang', $kode)
        ->first();
            
        if (!$barang) {
            return response()->json([
                'error' => 'Barang tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'barang' => $barang
        ]);
    }
    
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $items = Barang::with(['kategori:id,nama_kategori', 'satuan:id,nama_satuan', 'gudang:id,nama_gudang'])
            ->where('kode_barang', 'like', "%{$search}%")
            ->orWhere('nama_barang', 'like', "%{$search}%")
            ->orWhereHas('kategori', function($query) use ($search) {
                $query->where('nama_kategori', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);
            
        $categories = Kategori::select('id', 'nama_kategori')->get();
        $units = Satuan::select('id', 'nama_satuan')->get();
        $warehouses = Gudang::select('id', 'nama_gudang')->get();
        
        return view('admin.inventory', compact('items', 'categories', 'units', 'warehouses'));
    }
    
    public function quickStoreCategory(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori',
            'deskripsi' => 'nullable|string',
        ]);
        
        $category = Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->deskripsi ?? null,
        ]);
        
        // Log aktivitas tambah kategori - CREATE
        $this->logActivity(
            'Create',
            "Kategori baru ditambahkan: {$category->nama_kategori}",
            null,
            json_encode(['kategori_id' => $category->id, 'nama_kategori' => $category->nama_kategori])
        );
        
        return response()->json([
            'success' => true,
            'category' => $category,
            'message' => 'Kategori berhasil ditambahkan'
        ]);
    }
    
    /**
     * Menangani pengajuan pengadaan multi-barang
     */
    public function storePengadaan(Request $request)
    {
        // Validasi data umum pengadaan
        $validator = Validator::make($request->all(), [
            'prioritas' => 'required|in:normal,tinggi,mendesak',
            'alasan_pengadaan' => 'required|string|min:10',
            'catatan' => 'nullable|string',
            'barang' => 'required|array|min:1',
            'barang.*.tipe_pengadaan' => 'required|in:restock,baru',
            'barang.*.jumlah' => 'required|integer|min:1',
            'barang.*.harga_perkiraan' => 'required|numeric|min:0',
            'barang.*.keterangan' => 'nullable|string',
        ], [
            'alasan_pengadaan.required' => 'Alasan pengadaan wajib diisi',
            'alasan_pengadaan.min' => 'Alasan pengadaan minimal 10 karakter',
            'barang.required' => 'Minimal 1 barang harus ditambahkan',
            'barang.array' => 'Format data barang tidak valid',
            'barang.min' => 'Minimal 1 barang harus ditambahkan',
            'barang.*.jumlah.required' => 'Jumlah barang wajib diisi',
            'barang.*.jumlah.min' => 'Jumlah barang minimal 1',
            'barang.*.harga_perkiraan.required' => 'Harga perkiraan wajib diisi',
            'barang.*.harga_perkiraan.min' => 'Harga perkiraan minimal 0',
        ]);
        
        // Validasi tambahan untuk barang baru
        $validator->sometimes('barang.*.kode_barang', 'required|string|max:50|unique:barangs,kode_barang', function($input, $item) {
            return $item['tipe_pengadaan'] == 'baru';
        });
        
        $validator->sometimes('barang.*.nama_barang', 'required|string|max:255', function($input, $item) {
            return $item['tipe_pengadaan'] == 'baru';
        });
        
        $validator->sometimes('barang.*.kategori_id', 'required|exists:kategoris,id', function($input, $item) {
            return $item['tipe_pengadaan'] == 'baru';
        });
        
        $validator->sometimes('barang.*.satuan_id', 'required|exists:satuans,id', function($input, $item) {
            return $item['tipe_pengadaan'] == 'baru';
        });
        
        $validator->sometimes('barang.*.stok_minimal', 'nullable|integer|min:1', function($input, $item) {
            return $item['tipe_pengadaan'] == 'baru';
        });
        
        // Validasi tambahan untuk restock barang
        $validator->sometimes('barang.*.barang_id', 'required|exists:barangs,id', function($input, $item) {
            return $item['tipe_pengadaan'] == 'restock';
        });
        
        if ($validator->fails()) {
            return redirect()->route('admin.inventory')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . implode(', ', $validator->errors()->all()));
        }
        
        try {
            DB::beginTransaction();
            
            $barangData = $request->input('barang', []);
            $totalHarga = 0;
            $totalJumlah = 0;
            $barangBaruCount = 0;
            $restockCount = 0;
            
            // Tentukan tipe pengadaan utama berdasarkan barang yang diajukan
            $tipePengadaan = 'multi';
            
            // Cek apakah semua barang adalah barang baru
            $allBarangBaru = collect($barangData)->every(function($item) {
                return $item['tipe_pengadaan'] == 'baru';
            });
            
            // Cek apakah semua barang adalah restock
            $allRestock = collect($barangData)->every(function($item) {
                return $item['tipe_pengadaan'] == 'restock';
            });
            
            // Tentukan tipe pengadaan berdasarkan komposisi barang
            if ($allBarangBaru) {
                $tipePengadaan = 'baru';
            } elseif ($allRestock) {
                $tipePengadaan = 'restock';
            } else {
                $tipePengadaan = 'multi';
            }
            
            // Buat procurement utama dengan tipe pengadaan yang benar
            $procurement = new Procurement();
            $procurement->user_id = Auth::id();
            $procurement->prioritas = $request->prioritas;
            $procurement->alasan_pengadaan = $request->alasan_pengadaan;
            $procurement->catatan = $request->catatan;
            $procurement->status = 'pending';
            $procurement->tipe_pengadaan = $tipePengadaan; // Tipe pengadaan utama
            $procurement->is_multi_item = (count($barangData) > 1);
            
            // Simpan procurement utama
            $procurement->save();
            
            // Simpan items ke procurement_items
            foreach ($barangData as $index => $item) {
                $procurementItem = new ProcurementItem();
                $procurementItem->procurement_id = $procurement->id;
                $procurementItem->jumlah = $item['jumlah'];
                $procurementItem->harga_perkiraan = $item['harga_perkiraan'];
                $procurementItem->deskripsi = $item['keterangan'] ?? null;
                $procurementItem->status = 'pending';
                $procurementItem->tipe_pengadaan = $item['tipe_pengadaan']; // Tipe per item
                
                if ($item['tipe_pengadaan'] == 'baru') {
                    // Barang baru
                    $procurementItem->kode_barang = $item['kode_barang'];
                    $procurementItem->nama_barang = $item['nama_barang'];
                    
                    // Jika ada kategori_id dan satuan_id, ambil namanya
                    if (!empty($item['kategori_id'])) {
                        $kategori = Kategori::find($item['kategori_id']);
                        if ($kategori) {
                            $procurementItem->kategori = $kategori->nama_kategori;
                            $procurementItem->kategori_id = $kategori->id;
                        }
                    }
                    
                    if (!empty($item['satuan_id'])) {
                        $satuan = Satuan::find($item['satuan_id']);
                        if ($satuan) {
                            $procurementItem->satuan = $satuan->nama_satuan;
                            $procurementItem->satuan_id = $satuan->id;
                        }
                    }
                    
                    // Default gudang jika tidak ada
                    $defaultGudang = Gudang::first();
                    if ($defaultGudang) {
                        $procurementItem->gudang = $defaultGudang->nama_gudang;
                        $procurementItem->gudang_id = $defaultGudang->id;
                    } else {
                        $procurementItem->gudang = 'Gudang Utama';
                    }
                    
                    // Untuk barang baru, barang_id = null
                    $procurementItem->barang_id = null;
                    $procurementItem->stok_minimal = $item['stok_minimal'] ?? 10;
                    
                    $barangBaruCount++;
                } else {
                    // Restock barang yang sudah ada
                    $barang = Barang::with(['kategori', 'satuan', 'gudang'])->find($item['barang_id']);
                    if (!$barang) {
                        throw new \Exception("Barang dengan ID {$item['barang_id']} tidak ditemukan");
                    }
                    
                    $procurementItem->barang_id = $barang->id;
                    $procurementItem->kode_barang = $barang->kode_barang;
                    $procurementItem->nama_barang = $barang->nama_barang;
                    $procurementItem->kategori = $barang->kategori->nama_kategori ?? 'Umum';
                    $procurementItem->kategori_id = $barang->kategori_id;
                    $procurementItem->satuan = $barang->satuan->nama_satuan ?? 'Unit';
                    $procurementItem->satuan_id = $barang->satuan_id;
                    $procurementItem->gudang = $barang->gudang->nama_gudang ?? 'Gudang Utama';
                    $procurementItem->gudang_id = $barang->gudang_id;
                    $procurementItem->stok_minimal = $barang->stok_minimal;
                    
                    $restockCount++;
                }
                
                // Hitung subtotal
                $procurementItem->subtotal = $item['jumlah'] * $item['harga_perkiraan'];
                
                // Simpan item
                $procurementItem->save();
                
                // Update total
                $totalHarga += $procurementItem->subtotal;
                $totalJumlah += $item['jumlah'];
            }
            
            // Update procurement dengan total
            $procurement->total_perkiraan = $totalHarga;
            $procurement->total_jumlah = $totalJumlah;
            $procurement->save();
            
            // Log aktivitas pengajuan pengadaan multi-barang - CREATE (karena membuat procurement baru)
            $logDetails = [
                'kode_pengadaan' => $procurement->kode_pengadaan,
                'tipe_pengadaan' => $tipePengadaan,
                'total_barang' => count($barangData),
                'barang_baru' => $barangBaruCount,
                'restock' => $restockCount,
                'total_jumlah' => $totalJumlah,
                'total_harga' => $totalHarga,
                'prioritas' => $request->prioritas
            ];
            
            if ($tipePengadaan == 'multi') {
                $logDetails['komposisi'] = 'Campuran (Baru dan Restock)';
            }
            
            $this->logActivity(
                'Create',
                "Pengajuan pengadaan {$tipePengadaan} diajukan: {$procurement->kode_pengadaan}",
                $procurement,
                json_encode($logDetails)
            );
            
            DB::commit();
            
            $summaryMessage = "Pengajuan pengadaan <strong>{$tipePengadaan}</strong> dengan kode <strong>{$procurement->kode_pengadaan}</strong> berhasil dikirim. ";
            $summaryMessage .= "Total: " . count($barangData) . " barang (" . $barangBaruCount . " baru, " . $restockCount . " restock), ";
            $summaryMessage .= $totalJumlah . " unit, Rp " . number_format($totalHarga, 0, ',', '.') . ". Menunggu persetujuan.";
            
            return redirect()->route('admin.inventory')
                ->with('success', $summaryMessage)
                ->with('procurement_id', $procurement->id);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error storePengadaan Multi-Barang: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('admin.inventory')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Generate kode barang otomatis
     */
    public function generateKodeBarang()
    {
        $prefix = 'BRG';
        $year = date('Y');
        $month = date('m');
        
        // Cari nomor urut terakhir untuk bulan ini
        $lastBarang = Barang::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastBarang && $lastBarang->kode_barang) {
            // Coba ekstrak nomor urut dari kode terakhir
            if (preg_match('/-(\d+)$/', $lastBarang->kode_barang, $matches)) {
                $lastNumber = (int)$matches[1];
                $nextNumber = $lastNumber + 1;
            } else {
                // Jika format tidak sesuai, hitung jumlah untuk bulan ini
                $countBarang = Barang::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();
                $nextNumber = $countBarang + 1;
            }
        } else {
            $nextNumber = 1;
        }
        
        // Format: BRG-YYYYMM-XXXX
        $kodeBarang = sprintf('%s-%s%02d-%04d', $prefix, $year, $month, $nextNumber);
        
        return response()->json([
            'success' => true,
            'kode_barang' => $kodeBarang,
            'message' => 'Kode barang berhasil digenerate'
        ]);
    }
    
    /**
     * Get barang untuk pengadaan (AJAX)
     */
    public function getBarangForProcurement(Request $request)
    {
        $search = $request->input('q', '');
        
        $barang = Barang::with(['kategori:id,nama_kategori', 'satuan:id,nama_satuan'])
            ->select('id', 'kode_barang', 'nama_barang', 'stok', 'stok_minimal', 'kategori_id', 'satuan_id')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('kode_barang', 'like', "%{$search}%")
                      ->orWhere('nama_barang', 'like', "%{$search}%")
                      ->orWhereHas('kategori', function($q) use ($search) {
                          $q->where('nama_kategori', 'like', "%{$search}%");
                      });
                });
            })
            ->orderBy('nama_barang')
            ->limit(20)
            ->get();
            
        $results = $barang->map(function($item) {
            $status = '';
            if ($item->stok <= 0) {
                $status = ' (Habis)';
            } elseif ($item->stok <= $item->stok_minimal) {
                $status = ' (Kritis)';
            } elseif ($item->stok <= ($item->stok_minimal * 2)) {
                $status = ' (Rendah)';
            }
            
            return [
                'id' => $item->id,
                'text' => $item->kode_barang . ' - ' . $item->nama_barang . ' (Stok: ' . $item->stok . $status . ')',
                'kode' => $item->kode_barang,
                'nama' => $item->nama_barang,
                'stok' => $item->stok,
                'stok_minimal' => $item->stok_minimal,
                'kategori' => $item->kategori ? $item->kategori->nama_kategori : 'Umum',
                'satuan' => $item->satuan ? $item->satuan->nama_satuan : 'Unit',
                'status' => $status
            ];
        });
        
        return response()->json([
            'results' => $results,
            'pagination' => ['more' => false]
        ]);
    }
    
    public function getBarangDetail($id)
    {
        $barang = Barang::with([
            'kategori:id,nama_kategori',
            'satuan:id,nama_satuan'
        ])
        ->find($id);
            
        if (!$barang) {
            return response()->json([
                'error' => 'Barang tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'barang' => $barang
        ]);
    }
    
    /**
     * Get recent procurements for notification
     */
    public function getRecentProcurements()
    {
        $procurements = Procurement::with(['user:id,name,email', 'procurementItems'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
            
        return response()->json([
            'count' => $procurements->count(),
            'procurements' => $procurements
        ]);
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
                'details' => $details,
                'ip' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }
}