<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\PermintaanDetail;
use App\Models\Barang;
use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\ActivityLogController;

class PermintaanUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Cek jika ada parameter action untuk show form
        if ($request->has('action')) {
            switch ($request->action) {
                case 'create':
                    return $this->showCreateForm();
                default:
                    return $this->showList($request);
            }
        }
        
        // Default: show list
        return $this->showList($request);
    }
    
    private function showList(Request $request)
    {
        $user = auth()->user();
        
        // Query untuk permintaan user yang sedang login
        $query = Permintaan::where('user_id', $user->id)
            ->with(['details.barang', 'barang', 'satker'])
            ->latest();
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Pagination
        $permintaan = $query->paginate(10);
        
        // Data barang untuk dropdown (hanya yang stok > 0)
        $barang = Barang::where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();
        
        // Data satker untuk dropdown
        $satkers = Satker::orderBy('nama_satker')->get();
        
        // Statistik untuk user
        $stats = [
            'total' => Permintaan::where('user_id', $user->id)->count(),
            'pending' => Permintaan::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => Permintaan::where('user_id', $user->id)->where('status', 'approved')->count(),
            'rejected' => Permintaan::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];
        
        return view('user.permintaan', compact('permintaan', 'barang', 'satkers', 'stats'));
    }
    
    private function showCreateForm()
    {
        $barang = Barang::where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();
        
        $satkers = Satker::orderBy('nama_satker')->get();
        
        return view('user.permintaan', [
            'barang' => $barang,
            'satkers' => $satkers,
            'isCreate' => true
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();
        
        $satkers = Satker::orderBy('nama_satker')->get();
        
        return view('user.permintaan', [
            'barang' => $barang,
            'satkers' => $satkers,
            'isCreate' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input untuk multi barang - TANGGAL HARUS after:today
        $validated = $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'keterangan' => 'nullable|string|max:500',
            'tanggal_dibutuhkan' => 'required|date|after:today', // HARUS after:today (bukan after_or_equal)
            'barang_items' => 'required|array|min:1',
            'barang_items.*.barang_id' => 'required|exists:barangs,id',
            'barang_items.*.jumlah' => 'required|integer|min:1',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Generate kode permintaan
            $kodePermintaan = 'PM-' . date('Ymd') . '-' . Str::random(6);
            
            $totalJumlah = 0;
            $totalHarga = 0;
            
            // Hitung total dulu
            foreach ($validated['barang_items'] as $item) {
                $barang = Barang::findOrFail($item['barang_id']);
                
                // Cek stok barang
                if ($barang->stok < $item['jumlah']) {
                    DB::rollBack();
                    return back()->with('error', 'Stok barang "' . $barang->nama_barang . '" tidak mencukupi. Stok tersedia: ' . $barang->stok);
                }
                
                $harga = $barang->harga ?? 0;
                $totalJumlah += $item['jumlah'];
                $totalHarga += ($harga * $item['jumlah']);
            }
            
            // Simpan permintaan header
            $permintaan = Permintaan::create([
                'kode_permintaan' => $kodePermintaan,
                'user_id' => auth()->id(),
                'barang_id' => $validated['barang_items'][0]['barang_id'], // Simpan barang pertama untuk kompatibilitas
                'satker_id' => $validated['satker_id'],
                'jumlah' => $totalJumlah,
                'total_items' => count($validated['barang_items']),
                'total_harga' => $totalHarga,
                'keterangan' => $validated['keterangan'] ?? null,
                'tanggal_dibutuhkan' => $validated['tanggal_dibutuhkan'],
                'status' => 'pending',
            ]);
            
            // Simpan detail permintaan
            foreach ($validated['barang_items'] as $item) {
                $barang = Barang::findOrFail($item['barang_id']);
                $harga = $barang->harga ?? 0;
                
                PermintaanDetail::create([
                    'permintaan_id' => $permintaan->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $harga,
                    'subtotal' => $harga * $item['jumlah'],
                ]);
            }
            
            // Log aktivitas pengajuan permintaan baru
            $logData = [
                'permintaan_id' => $permintaan->id,
                'kode_permintaan' => $kodePermintaan,
                'jumlah_barang' => count($validated['barang_items']),
                'total_jumlah' => $totalJumlah,
                'total_harga' => $totalHarga,
                'satker' => Satker::find($validated['satker_id'])->nama_satker ?? 'Tidak diketahui',
                'tanggal_dibutuhkan' => $validated['tanggal_dibutuhkan'],
            ];
            ActivityLogController::logAction('create_request', 'Mengajukan permintaan baru: ' . $kodePermintaan, $logData);
            
            DB::commit();
            
            return redirect()->route('user.permintaan')
                ->with('success', 'Permintaan berhasil diajukan dengan kode: ' . $kodePermintaan);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user();
        
        $permintaan = Permintaan::where('user_id', $user->id)
            ->with(['details.barang.satuan', 'details.barang.kategori', 'barang.satuan', 'barang.kategori', 'satker', 'approver'])
            ->findOrFail($id);
        
        return view('user.permintaan', [
            'permintaan' => $permintaan,
            'isShow' => true
        ]);
    }

    /**
     * Show the form for editing the specified resource - MULTI BARANG
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        // Hanya bisa edit permintaan yang masih pending
        $permintaan = Permintaan::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['details.barang.satuan', 'details.barang.kategori', 'satker'])
            ->findOrFail($id);
        
        // Get all available barang (stok > 0) + barang yang sudah ada di permintaan
        $barang = Barang::where(function($query) use ($permintaan) {
                $query->where('stok', '>', 0)
                      ->orWhereIn('id', $permintaan->details->pluck('barang_id')->toArray());
            })
            ->orderBy('nama_barang')
            ->get();
                
        $satkers = Satker::orderBy('nama_satker')->get();
        
        return view('user.permintaan', [
            'permintaan' => $permintaan,
            'barang' => $barang,
            'satkers' => $satkers,
            'isEdit' => true
        ]);
    }

    /**
     * API untuk mendapatkan stok barang real-time
     */
    public function getStokBarang($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'stok' => $barang->stok,
                'satuan' => $barang->satuan->nama_satuan ?? 'unit'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage - MULTI BARANG
     */
    public function update(Request $request, $id)
    {
        // Validasi input untuk multi barang - TANGGAL HARUS after:today
        $validated = $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'keterangan' => 'nullable|string|max:500',
            'tanggal_dibutuhkan' => 'required|date|after:today', // HARUS after:today (bukan after_or_equal)
            'barang_items' => 'required|array|min:1',
            'barang_items.*.barang_id' => 'required|exists:barangs,id',
            'barang_items.*.jumlah' => 'required|integer|min:1',
        ]);
        
        try {
            $user = auth()->user();
            
            // Cari permintaan milik user yang sedang login dan status pending
            $permintaan = Permintaan::where('user_id', $user->id)
                ->where('status', 'pending')
                ->with(['details'])
                ->findOrFail($id);
            
            DB::beginTransaction();
            
            // Update permintaan header
            $permintaan->update([
                'satker_id' => $validated['satker_id'],
                'keterangan' => $validated['keterangan'] ?? null,
                'tanggal_dibutuhkan' => $validated['tanggal_dibutuhkan'],
                'total_items' => count($validated['barang_items']),
            ]);
            
            // Hapus detail lama
            $permintaan->details()->delete();
            
            $totalJumlah = 0;
            $totalHarga = 0;
            
            // Simpan detail baru
            foreach ($validated['barang_items'] as $item) {
                $barang = Barang::findOrFail($item['barang_id']);
                
                // Cek stok barang
                if ($barang->stok < $item['jumlah']) {
                    DB::rollBack();
                    return back()->with('error', 'Stok barang "' . $barang->nama_barang . '" tidak mencukupi. Stok tersedia: ' . $barang->stok);
                }
                
                $harga = $barang->harga ?? 0;
                $subtotal = $harga * $item['jumlah'];
                
                PermintaanDetail::create([
                    'permintaan_id' => $permintaan->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $harga,
                    'subtotal' => $subtotal,
                ]);
                
                $totalJumlah += $item['jumlah'];
                $totalHarga += $subtotal;
            }
            
            // Update total jumlah dan harga di header
            $permintaan->update([
                'jumlah' => $totalJumlah,
                'total_harga' => $totalHarga,
                'barang_id' => $validated['barang_items'][0]['barang_id'], // Update barang_id untuk kompatibilitas
            ]);
            
            // Log aktivitas edit permintaan
            $logData = [
                'permintaan_id' => $permintaan->id,
                'kode_permintaan' => $permintaan->kode_permintaan,
                'jumlah_barang' => count($validated['barang_items']),
                'total_jumlah' => $totalJumlah,
                'total_harga' => $totalHarga,
            ];
            ActivityLogController::logAction('update_request', 'Mengubah permintaan: ' . $permintaan->kode_permintaan, $logData);
            
            DB::commit();
            
            return redirect()->route('user.permintaan')
                ->with('success', 'Permintaan berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user();
            
            // Hanya bisa hapus permintaan sendiri yang masih pending
            $permintaan = Permintaan::where('user_id', $user->id)
                ->where('status', 'pending')
                ->with(['details'])
                ->findOrFail($id);
            
            // Simpan data untuk logging sebelum dihapus
            $logData = [
                'permintaan_id' => $permintaan->id,
                'kode_permintaan' => $permintaan->kode_permintaan,
                'jumlah_barang' => $permintaan->details->count(),
                'satker' => $permintaan->satker->nama_satker ?? 'Tidak diketahui',
            ];
            
            $permintaan->delete();
            
            // Log aktivitas hapus permintaan
            ActivityLogController::logAction('delete_request', 'Menghapus permintaan: ' . $logData['kode_permintaan'], $logData);
            
            return redirect()->route('user.permintaan')
                ->with('success', 'Permintaan berhasil dihapus');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Track request status - FIXED VERSION
     */
    public function track($kode_permintaan)
    {
        $user = auth()->user();
        
        $permintaan = Permintaan::where('user_id', $user->id)
            ->where('kode_permintaan', $kode_permintaan)
            ->with(['details.barang', 'barang', 'satker', 'approver'])
            ->firstOrFail();
        
        // Bangun timeline berdasarkan status yang sebenarnya
        $timeline = [];
        
        // 1. STATUS: DIUSULKAN (selalu ada)
        $timeline[] = [
            'status' => 'Diusulkan',
            'date' => $permintaan->created_at->format('d/m/Y H:i'),
            'description' => 'Permintaan diajukan oleh ' . $user->name . ' dari ' . ($permintaan->satker->nama_satker ?? 'SATUAN TEKNOLOGI INFORMASI'),
            'completed' => true,
        ];
        
        // 2. LOGIKA BERDASARKAN STATUS AKTUAL
        switch ($permintaan->status) {
            case 'pending':
                // Status masih menunggu persetujuan
                $timeline[] = [
                    'status' => 'Diproses',
                    'date' => '-',
                    'description' => 'Menunggu persetujuan administrator',
                    'completed' => false,
                ];
                
                $timeline[] = [
                    'status' => 'Dikirim',
                    'date' => '-',
                    'description' => 'Belum dapat dikirim',
                    'completed' => false,
                ];
                break;
                
            case 'rejected':
                // Status ditolak - timeline harus konsisten dengan penolakan
                $date = $permintaan->approved_at 
                    ? $permintaan->approved_at->format('d/m/Y H:i') 
                    : ($permintaan->updated_at->format('d/m/Y H:i'));
                
                $description = 'Ditolak oleh ' . ($permintaan->approver->name ?? 'Administrator');
                if ($permintaan->alasan_penolakan) {
                    $description .= ': ' . $permintaan->alasan_penolakan;
                }
                
                $timeline[] = [
                    'status' => 'Diproses',
                    'date' => $date,
                    'description' => $description,
                    'completed' => true,
                ];
                
                // Tidak ada timeline Dikirim karena sudah ditolak
                $timeline[] = [
                    'status' => 'Dikirim',
                    'date' => '-',
                    'description' => 'Tidak dapat dikirim karena permintaan ditolak',
                    'completed' => false,
                ];
                break;
                
            case 'approved':
                // Status disetujui - timeline: disetujui, menunggu pengiriman
                $date = $permintaan->approved_at 
                    ? $permintaan->approved_at->format('d/m/Y H:i') 
                    : $permintaan->updated_at->format('d/m/Y H:i');
                
                $timeline[] = [
                    'status' => 'Diproses',
                    'date' => $date,
                    'description' => 'Disetujui oleh ' . ($permintaan->approver->name ?? 'Administrator'),
                    'completed' => true,
                ];
                
                $timeline[] = [
                    'status' => 'Dikirim',
                    'date' => '-',
                    'description' => 'Dalam proses pengiriman',
                    'completed' => false,
                ];
                break;
                
            case 'delivered':
                // Status delivered - timeline: disetujui, dikirim
                // Pertama, status diproses/disetujui
                $approvedDate = $permintaan->approved_at 
                    ? $permintaan->approved_at->format('d/m/Y H:i') 
                    : $permintaan->updated_at->format('d/m/Y H:i');
                
                // PASTIKAN tidak menampilkan "Ditolak" jika status delivered
                // Cek apakah ada alasan penolakan di database
                if ($permintaan->alasan_penolakan) {
                    // Jika ada alasan penolakan tapi status delivered, ini data tidak konsisten
                    // Tampilkan sebagai disetujui saja (karena akhirnya dikirim)
                    $timeline[] = [
                        'status' => 'Diproses',
                        'date' => $approvedDate,
                        'description' => 'Disetujui oleh Administrator (setelah revisi)',
                        'completed' => true,
                    ];
                } else {
                    // Normal case: approved lalu delivered
                    $timeline[] = [
                        'status' => 'Diproses',
                        'date' => $approvedDate,
                        'description' => 'Disetujui oleh ' . ($permintaan->approver->name ?? 'Administrator'),
                        'completed' => true,
                    ];
                }
                
                // Kedua, status dikirim
                $timeline[] = [
                    'status' => 'Dikirim',
                    'date' => $permintaan->updated_at->format('d/m/Y H:i'),
                    'description' => 'Barang sudah dikirim ke ' . ($permintaan->satker->nama_satker ?? 'SATUAN TEKNOLOGI INFORMASI'),
                    'completed' => true,
                ];
                break;
        }
        
        // Log untuk debugging
        \Log::info('Tracking Permintaan - MULTI BARANG', [
            'kode_permintaan' => $permintaan->kode_permintaan,
            'status_db' => $permintaan->status,
            'alasan_penolakan_db' => $permintaan->alasan_penolakan,
            'approved_at_db' => $permintaan->approved_at,
            'timeline_items' => count($timeline)
        ]);
        
        return view('user.permintaan', [
            'permintaanTrack' => $permintaan,
            'timeline' => $timeline,
            'isTrack' => true
        ]);
    }

    /**
     * Cetak permintaan
     */
    public function cetak(Request $request)
    {
        $user = auth()->user();
        
        $query = Permintaan::where('user_id', $user->id)
            ->with(['details.barang', 'barang', 'satker']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $permintaan = $query->latest()->get();
        
        return view('user.permintaan_cetak', compact('permintaan', 'user'));
    }
}