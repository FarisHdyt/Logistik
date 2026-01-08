<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Gudang;
use App\Models\Procurement;
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
        $query = Barang::with('kategori', 'satuan', 'gudang');
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%')
                  ->orWhereHas('kategori', function($query) use ($search) {
                      $query->where('nama_kategori', 'like', '%' . $search . '%');
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
        $items = $query->latest()->paginate(10)->withQueryString();
        
        // Ambil data untuk filter dropdown
        $categories = Kategori::all();
        $units = Satuan::all();
        $warehouses = Gudang::all();
        
        // Hitung stats
        $stats = [
            'total_items' => Barang::count(),
            'total_categories' => Kategori::count(),
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
        
        return view('admin.inventory', compact(
            'user', 
            'items', 
            'categories', 
            'units', 
            'warehouses', 
            'stats'
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
        
        $data = $request->all();
        
        $barang = Barang::create($data);
        
        // Log aktivitas tambah barang
        $this->logActivity(
            'Tambah Barang',
            "Barang baru berhasil ditambahkan: {$barang->kode_barang} - {$barang->nama_barang}",
            $barang
        );
        
        return redirect()->route('admin.inventory')
            ->with('success', 'Barang berhasil ditambahkan.');
    }
    
    public function edit(Barang $barang)
    {
        $categories = Kategori::all();
        $units = Satuan::all();
        $warehouses = Gudang::all();
        
        return response()->json([
            'barang' => $barang->load('kategori', 'satuan', 'gudang'),
            'categories' => $categories,
            'units' => $units,
            'warehouses' => $warehouses
        ]);
    }
    
    public function update(Request $request, Barang $barang)
    {
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
        
        $newData = $request->all();
        
        $barang->update($newData);
        
        // Log aktivitas ubah barang
        $this->logActivity(
            'Update Barang',
            "Barang berhasil diperbarui: {$barang->kode_barang} - {$barang->nama_barang}",
            $barang,
            json_encode(['old_data' => $oldData, 'new_data' => $newData])
        );
        
        return redirect()->route('admin.inventory')
            ->with('success', 'Barang berhasil diperbarui.');
    }
    
    public function destroy(Barang $barang)
    {
        $barangData = [
            'id' => $barang->id,
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'kategori_id' => $barang->kategori_id,
            'satuan_id' => $barang->satuan_id,
            'stok' => $barang->stok,
            'stok_minimal' => $barang->stok_minimal,
            'harga_beli' => $barang->harga_beli,
            'harga_jual' => $barang->harga_jual,
        ];
        
        // Log aktivitas hapus barang
        $this->logActivity(
            'Hapus Barang',
            "Barang berhasil dihapus: {$barang->kode_barang} - {$barang->nama_barang}",
            $barang
        );
        
        $barang->delete();
        
        return redirect()->route('admin.inventory')
            ->with('success', 'Barang berhasil dihapus.');
    }
    
    public function show(Barang $barang)
    {
        return response()->json([
            'barang' => $barang->load('kategori', 'satuan', 'gudang')
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
        
        // Log aktivitas restock barang
        $this->logActivity(
            'Restock Barang',
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
        $barang = Barang::with('kategori', 'satuan', 'gudang')
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
        
        $items = Barang::with('kategori', 'satuan', 'gudang')
            ->where('kode_barang', 'like', "%{$search}%")
            ->orWhere('nama_barang', 'like', "%{$search}%")
            ->orWhereHas('kategori', function($query) use ($search) {
                $query->where('nama_kategori', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);
            
        return view('admin.inventory', compact('items'));
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
        
        // Log aktivitas tambah kategori
        $this->logActivity(
            'Tambah Kategori',
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
     * Menangani pengajuan pengadaan barang dari modal
     */
    public function storePengadaan(Request $request)
    {
        // Log data yang diterima
        Log::info('StorePengadaan Request Data:', $request->all());
        
        // Validasi berdasarkan tipe pengadaan
        $validator = Validator::make($request->all(), [
            'tipe_pengadaan' => 'required|in:baru,restock',
            'kode_barang' => 'required_if:tipe_pengadaan,baru|string|max:50|nullable',
            'nama_barang' => 'required_if:tipe_pengadaan,baru|string|max:255|nullable',
            'kategori_id' => 'required_if:tipe_pengadaan,baru|exists:kategoris,id|nullable',
            'satuan_id' => 'required_if:tipe_pengadaan,baru|exists:satuans,id|nullable',
            'barang_id' => 'required_if:tipe_pengadaan,restock|exists:barangs,id|nullable',
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
        
        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return redirect()->route('admin.inventory')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . implode(', ', $validator->errors()->all()));
        }
        
        try {
            DB::beginTransaction();
            
            $data = $request->all();
            $data['user_id'] = Auth::id();
            $data['status'] = 'pending';
            
            Log::info('Process Pengadaan - Tipe: ' . $request->tipe_pengadaan);
            
            // Handle berdasarkan tipe pengadaan
            if ($request->tipe_pengadaan == 'restock' && $request->filled('barang_id')) {
                $barang = Barang::with('kategori', 'satuan')->find($request->barang_id);
                
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
            
            // Hilangkan field yang tidak diperlukan
            unset($data['_token']);
            
            // Debug: Cek data sebelum disimpan
            Log::info('Final data to save:', $data);
            
            // Simpan data pengadaan
            $procurement = Procurement::create($data);
            
            Log::info('Procurement created successfully:', [
                'id' => $procurement->id,
                'kode_barang' => $procurement->kode_barang,
                'nama_barang' => $procurement->nama_barang,
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
            
            return redirect()->route('admin.inventory')
                ->with('success', 'Pengajuan pengadaan berhasil dikirim. Menunggu persetujuan.')
                ->with('procurement_id', $procurement->id);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error storePengadaan: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Request data: ', $request->all());
            
            return redirect()->route('admin.inventory')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function getBarangForProcurement()
    {
        $barang = Barang::select('id', 'kode_barang', 'nama_barang', 'stok', 'stok_minimal')
            ->orderBy('nama_barang')
            ->get();
            
        return response()->json($barang);
    }
    
    public function getBarangDetail($id)
    {
        $barang = Barang::with('kategori', 'satuan')
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
        $procurements = Procurement::with('user', 'kategori', 'satuan')
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