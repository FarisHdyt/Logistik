<?php

namespace App\Http\Controllers;

use App\Models\Satker;
use App\Models\User;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use App\Http\Controllers\ActivityLogController;

class SatkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        // Ambil data satker dengan jumlah user
        $satkers = Satker::withCount('users')
            ->latest()
            ->paginate(10);
        
        // Statistik untuk dashboard
        $stats = [
            'total_satker' => Satker::count(),
            'total_users' => User::count(),
            'satker_dengan_user' => Satker::has('users')->count(),
            'satker_tanpa_user' => Satker::doesntHave('users')->count(),
            'total_permintaan' => class_exists(Permintaan::class) ? Permintaan::count() : 0,
        ];
        
        return view('superadmin.satker', compact('user', 'satkers', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        return view('superadmin.satker-create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'kode_satker' => 'required|unique:satkers,kode_satker|max:20',
            'nama_satker' => 'required|max:100',
            'alamat' => 'required',
            'telepon' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'nama_kepala' => 'nullable|max:100',
            'pangkat_kepala' => 'nullable|max:50',
            'nrp_kepala' => 'nullable|max:30',
        ]);
        
        try {
            $satker = Satker::create($request->all());
            
            // Log activity
            $logData = [
                'satker_id' => $satker->id,
                'kode_satker' => $satker->kode_satker,
                'nama_satker' => $satker->nama_satker,
                'created_by' => auth()->user()->name ?? 'System'
            ];
            ActivityLogController::logAction('create_satker', 'Menambahkan satker baru: ' . $satker->nama_satker, $logData);
            
            return redirect()->route('superadmin.satker.index')
                ->with('success', 'Satker berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.satker.index')
                ->with('error', 'Gagal menambahkan satker: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        try {
            $satker = Satker::withCount(['users', 'permintaans'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $satker
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Satker tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Satker $satker)
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        return view('superadmin.satker-edit', compact('user', 'satker'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Satker $satker)
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'kode_satker' => 'required|unique:satkers,kode_satker,' . $satker->id . '|max:20',
            'nama_satker' => 'required|max:100',
            'alamat' => 'required',
            'telepon' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'nama_kepala' => 'nullable|max:100',
            'pangkat_kepala' => 'nullable|max:50',
            'nrp_kepala' => 'nullable|max:30',
        ]);
        
        try {
            // Simpan data lama untuk logging
            $oldData = [
                'kode_satker' => $satker->kode_satker,
                'nama_satker' => $satker->nama_satker,
                'alamat' => $satker->alamat,
                'telepon' => $satker->telepon,
                'email' => $satker->email,
                'nama_kepala' => $satker->nama_kepala,
            ];
            
            $satker->update($request->all());
            
            // Log activity
            $logData = [
                'satker_id' => $satker->id,
                'kode_satker' => $satker->kode_satker,
                'nama_satker' => $satker->nama_satker,
                'old_data' => $oldData,
                'new_data' => $request->all(),
                'updated_by' => auth()->user()->name ?? 'System'
            ];
            ActivityLogController::logAction('update_satker', 'Memperbarui data satker: ' . $satker->nama_satker, $logData);
            
            return redirect()->route('superadmin.satker.index')
                ->with('success', 'Satker berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.satker.index')
                ->with('error', 'Gagal memperbarui satker: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Satker $satker)
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        try {
            // Cek apakah satker memiliki user
            if ($satker->users()->count() > 0) {
                return redirect()->route('superadmin.satker.index')
                    ->with('error', 'Tidak dapat menghapus satker yang masih memiliki user.');
            }
            
            // Simpan data untuk logging sebelum dihapus
            $logData = [
                'satker_id' => $satker->id,
                'kode_satker' => $satker->kode_satker,
                'nama_satker' => $satker->nama_satker,
                'alamat' => $satker->alamat,
                'deleted_by' => auth()->user()->name ?? 'System'
            ];
            
            $satker->delete();
            
            // Log activity
            ActivityLogController::logAction('delete_satker', 'Menghapus satker: ' . $logData['nama_satker'], $logData);
            
            return redirect()->route('superadmin.satker.index')
                ->with('success', 'Satker berhasil dihapus.');
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.satker.index')
                ->with('error', 'Gagal menghapus satker: ' . $e->getMessage());
        }
    }

    /**
     * Get satker details for AJAX request
     */
    public function getDetails($id)
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $satker = Satker::withCount(['users', 'permintaans'])->findOrFail($id);
            
            // Hitung statistik status satker berdasarkan apakah memiliki user atau tidak
            $status = $satker->users_count > 0 ? 'Berisi User' : 'Kosong';
            
            return response()->json([
                'success' => true,
                'data' => [
                    'satker' => $satker,
                    'users_count' => $satker->users_count,
                    'permintaans_count' => $satker->permintaans_count ?? 0,
                    'status' => $status,
                    'recent_users' => $satker->users()->latest()->take(5)->get(),
                    'recent_permintaans' => $satker->permintaans()->latest()->take(5)->get() ?? collect(),
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Satker tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Get all satkers for dropdown/select
     */
    public function getSatkersForSelect()
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        $satkers = Satker::select('id', 'kode_satker', 'nama_satker')
            ->orderBy('nama_satker')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $satkers
        ]);
    }

    /**
     * Search satkers by keyword
     */
    public function search(Request $request)
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $keyword = $request->get('q');
            
            $satkers = Satker::withCount('users')
                ->where('nama_satker', 'like', "%{$keyword}%")
                ->orWhere('kode_satker', 'like', "%{$keyword}%")
                ->orWhere('alamat', 'like', "%{$keyword}%")
                ->orWhere('nama_kepala', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('telepon', 'like', "%{$keyword}%")
                ->latest()
                ->paginate(10);
            
            return response()->json([
                'success' => true,
                'data' => $satkers
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pencarian'
            ], 500);
        }
    }

    /**
     * Get satker statistics for dashboard
     */
    public function getStatistics()
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $stats = [
                'total' => Satker::count(),
                'with_users' => Satker::has('users')->count(),
                'without_users' => Satker::doesntHave('users')->count(),
                'recent_added' => Satker::whereDate('created_at', today())->count(),
                'total_users' => User::count(),
                'avg_users_per_satker' => Satker::has('users')->count() > 0 ? 
                    round(User::count() / Satker::has('users')->count(), 2) : 0,
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }

    /**
     * Check if satker has users
     */
    public function checkHasUsers(Satker $satker)
    {
        $user = auth()->user();
        
        // Hanya superadmin yang bisa mengakses
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $hasUsers = $satker->users()->count() > 0;
            
            return response()->json([
                'success' => true,
                'has_users' => $hasUsers,
                'users_count' => $satker->users()->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status satker'
            ], 500);
        }
    }
}