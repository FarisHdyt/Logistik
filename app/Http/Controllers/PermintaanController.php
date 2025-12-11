<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\Barang;
use App\Models\Satker;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Query dengan filter status jika ada
        $status = request('status');
        $search = request('search');
        $satker = request('satker');
        
        $requests = Permintaan::with(['user', 'barang', 'satker'])
            ->when($status && $status != 'all', function($query) use ($status) {
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
                      });
                });
            })
            ->when($satker, function($query) use ($satker) {
                return $query->where('satker_id', $satker);
            })
            ->latest()
            ->paginate(10);
        
        $stats = [
            'total_requests' => Permintaan::count(),
            'pending_requests' => Permintaan::where('status', 'pending')->count(),
            'approved_requests' => Permintaan::where('status', 'approved')->count(),
            'rejected_requests' => Permintaan::where('status', 'rejected')->count(),
            'processing_requests' => Permintaan::where('status', 'processing')->count(),
            'delivered_requests' => Permintaan::where('status', 'delivered')->count(),
        ];
        
        $satkers = Satker::all();
        $barangs = Barang::where('stok', '>', 0)->get();
        
        return view('admin.requests', compact('user', 'requests', 'stats', 'satkers', 'barangs'));
    }
    
    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)->get();
        $satkers = Satker::all();
        return view('admin.requests.create', compact('barangs', 'satkers'));
    }
    
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:500',
        ]);

        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'satker_id' => 'required|exists:satkers,id',
            'keperluan' => 'nullable|string',
        ]);
        
        // Check stock availability
        $barang = Barang::find($validated['barang_id']);
        if ($barang->stok < $validated['jumlah']) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $barang->stok);
        }
        
        // Generate kode permintaan
        $lastRequest = Permintaan::latest()->first();
        $nextNumber = $lastRequest ? intval(substr($lastRequest->kode_permintaan, 4)) + 1 : 1;
        $kodePermintaan = 'PMT-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        Permintaan::create([
            'kode_permintaan' => $kodePermintaan,
            'user_id' => auth()->id(),
            'barang_id' => $validated['barang_id'],
            'jumlah' => $validated['jumlah'],
            'satker_id' => $validated['satker_id'],
            'keperluan' => $validated['keperluan'] ?? null,
            'status' => 'pending',
        ]);
        
        return redirect()->route('admin.requests')
            ->with('success', 'Permintaan berhasil dibuat dengan kode: ' . $kodePermintaan);
    }
    
    public function show(Permintaan $permintaan)
    {
        return response()->json([
            'success' => true,
            'request' => $permintaan->load(['user', 'barang', 'satker', 'barang.satuan', 'approvedBy'])
        ]);
    }
    
    public function approve(Request $request, Permintaan $permintaan)
    {
        // Validasi bahwa permintaan masih pending
        if ($permintaan->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan sudah diproses.'
            ], 400);
        }
        
        // Validasi stok masih cukup
        if ($permintaan->barang->stok < $permintaan->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $permintaan->barang->stok
            ], 400);
        }
        
        DB::beginTransaction();
        try {
            // Update request status
            $permintaan->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'catatan' => $permintaan->catatan ?? 'Disetujui oleh admin',
            ]);
            
            // Reduce item stock
            $permintaan->barang->decrement('stok', $permintaan->jumlah);
            
            // Create transaction log
            Transaction::create([
                'barang_id' => $permintaan->barang_id,
                'user_id' => $permintaan->user_id,
                'type' => 'out',
                'quantity' => $permintaan->jumlah,
                'note' => 'Permintaan disetujui: ' . $permintaan->kode_permintaan,
                'created_by' => auth()->id()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil disetujui.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function reject(Request $request, Permintaan $permintaan)
    {
        // Validasi bahwa permintaan masih pending
        if ($permintaan->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan sudah diproses.'
            ], 400);
        }
        
        $validated = $request->validate([
            'reason' => 'required|string',
        ]);
        
        $permintaan->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan' => $validated['reason'],
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Permintaan berhasil ditolak.'
        ]);
    }
    
    public function updateStatus(Request $request, Permintaan $permintaan)
    {
        $validated = $request->validate([
            'status' => 'required|in:processing,delivered',
            'catatan' => 'nullable|string'
        ]);
        
        $permintaan->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? $permintaan->catatan,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status permintaan berhasil diubah.'
        ]);
    }
}