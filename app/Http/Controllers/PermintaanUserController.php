<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\Barang;
use App\Models\Satker; // Tambahkan model Satker
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
            ->with(['barang', 'satker'])
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
        // Validasi input sesuai dengan struktur tabel
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'satker_id' => 'required|exists:satkers,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:500',
            'tanggal_dibutuhkan' => 'required|date|after_or_equal:today',
        ]);
        
        try {
            // Cek stok barang
            $barang = Barang::findOrFail($validated['barang_id']);
            
            if ($barang->stok < $validated['jumlah']) {
                return back()->with('error', 'Stok barang tidak mencukupi. Stok tersedia: ' . $barang->stok);
            }
            
            DB::beginTransaction();
            
            // Generate kode permintaan
            $kodePermintaan = 'PM-' . date('Ymd') . '-' . Str::random(6);
            
            // Simpan permintaan sesuai struktur tabel
            Permintaan::create([
                'kode_permintaan' => $kodePermintaan,
                'user_id' => auth()->id(),
                'barang_id' => $validated['barang_id'],
                'satker_id' => $validated['satker_id'],
                'jumlah' => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => 'pending',
            ]);
            
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
            ->with(['barang', 'satker', 'approvedBy'])
            ->findOrFail($id);
        
        return view('user.permintaan', [
            'permintaan' => $permintaan,
            'isShow' => true
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        // Hanya bisa edit permintaan yang masih pending
        $permintaan = Permintaan::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['barang', 'satker'])
            ->findOrFail($id);
        
        $barang = Barang::where('stok', '>', 0)
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input sesuai struktur tabel
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'satker_id' => 'required|exists:satkers,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:500',
            'tanggal_dibutuhkan' => 'required|date|after_or_equal:today',
        ]);
        
        try {
            $user = auth()->user();
            
            // Cari permintaan milik user yang sedang login dan status pending
            $permintaan = Permintaan::where('user_id', $user->id)
                ->where('status', 'pending')
                ->findOrFail($id);
            
            // Cek stok barang
            $barang = Barang::findOrFail($validated['barang_id']);
            
            // Jika ganti barang atau jumlah berubah, cek stok
            if ($permintaan->barang_id != $validated['barang_id'] || $permintaan->jumlah != $validated['jumlah']) {
                if ($barang->stok < $validated['jumlah']) {
                    return back()->with('error', 'Stok barang tidak mencukupi. Stok tersedia: ' . $barang->stok);
                }
            }
            
            DB::beginTransaction();
            
            // Update permintaan sesuai struktur tabel
            $permintaan->update([
                'barang_id' => $validated['barang_id'],
                'satker_id' => $validated['satker_id'],
                'jumlah' => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);
            
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
                ->findOrFail($id);
            
            $permintaan->delete();
            
            return redirect()->route('user.permintaan')
                ->with('success', 'Permintaan berhasil dihapus');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Track request status
     */
    public function track($kode_permintaan)
    {
        $user = auth()->user();
        
        $permintaan = Permintaan::where('user_id', $user->id)
            ->where('kode_permintaan', $kode_permintaan)
            ->with(['barang', 'satker', 'approvedBy'])
            ->firstOrFail();
        
        // Status timeline
        $timeline = [
            [
                'status' => 'Diajukan',
                'date' => $permintaan->created_at->format('d/m/Y H:i'),
                'description' => 'Permintaan diajukan oleh ' . $user->name . ' dari ' . ($permintaan->satker->nama_satker ?? ''),
                'completed' => true,
            ]
        ];
        
        if ($permintaan->approved_at) {
            $timeline[] = [
                'status' => 'Diproses',
                'date' => $permintaan->approved_at->format('d/m/Y H:i'),
                'description' => $permintaan->status == 'approved' ? 
                    'Disetujui oleh ' . ($permintaan->approvedBy->name ?? 'Admin') : 
                    'Ditolak oleh ' . ($permintaan->approvedBy->name ?? 'Admin'),
                'completed' => true,
            ];
            
            if ($permintaan->status == 'approved' && $permintaan->alasan_penolakan) {
                $timeline[] = [
                    'status' => 'Alasan',
                    'date' => $permintaan->approved_at->format('d/m/Y H:i'),
                    'description' => 'Alasan: ' . $permintaan->alasan_penolakan,
                    'completed' => true,
                ];
            }
        }
        
        if ($permintaan->status == 'delivered') {
            $timeline[] = [
                'status' => 'Dikirim',
                'date' => $permintaan->updated_at->format('d/m/Y H:i'),
                'description' => 'Barang sudah dikirim ke ' . ($permintaan->satker->nama_satker ?? ''),
                'completed' => true,
            ];
        }
        
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
            ->with(['barang', 'satker']);
        
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