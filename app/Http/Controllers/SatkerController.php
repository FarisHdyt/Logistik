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
        
        if ($user->role !== 'superadmin') {
            abort(403, 'Unauthorized access.');
        }
        
        $satkers = Satker::withCount('users')
            ->latest()
            ->paginate(10);
        
        $stats = [
            'total_satker' => Satker::count(),
            'total_users' => User::count(),
            'satker_aktif' => Satker::has('users')->count(), // DIUBAH: dari 'satker_baru' menjadi 'satker_aktif'
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
        
        if ($user->role !== 'superadmin') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }
            abort(403, 'Unauthorized access.');
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'form_type' => 'create',
                'title' => 'Tambah Satker Baru'
            ]);
        }
        
        return redirect()->route('superadmin.satker.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role !== 'superadmin') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }
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
            
            ActivityLogController::logAction('create', 'Menambahkan satker baru: ' . $satker->nama_satker, [
                'satker_id' => $satker->id,
                'kode_satker' => $satker->kode_satker,
                'nama_satker' => $satker->nama_satker
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Satker berhasil ditambahkan.',
                    'data' => $satker
                ]);
            }
            
            return redirect()->route('superadmin.satker.index')
                ->with('success', 'Satker berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan satker: ' . $e->getMessage(),
                    'errors' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('superadmin.satker.index')
                ->with('error', 'Gagal menambahkan satker: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource (for non-AJAX requests).
     */
    public function show($id)
    {
        $user = auth()->user();
        
        if ($user->role !== 'superadmin') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            abort(403, 'Unauthorized access.');
        }
        
        try {
            $satker = Satker::withCount(['users', 'permintaans'])->findOrFail($id);
            
            // Jika request AJAX, kembalikan JSON
            if (request()->ajax() || request()->wantsJson()) {
                // Format tanggal untuk response JSON
                $satker->created_at_formatted = $satker->created_at->format('d/m/Y');
                $satker->updated_at_formatted = $satker->updated_at->format('d/m/Y');
                
                return response()->json([
                    'success' => true,
                    'data' => $satker
                ]);
            }
            
            // Jika bukan AJAX, redirect ke index
            return redirect()->route('superadmin.satker.index');
            
        } catch (\Exception $e) {
            \Log::error('Error fetching satker details: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Satker tidak ditemukan'
                ], 404);
            }
            
            return redirect()->route('superadmin.satker.index')
                ->with('error', 'Satker tidak ditemukan');
        }
    }

    /**
     * AJAX view for modal (to avoid route conflict) - METHOD BARU
     */
    public function ajaxView($id)
    {
        $user = auth()->user();
        
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $satker = Satker::withCount(['users', 'permintaans'])->findOrFail($id);
            
            // Format tanggal untuk response
            $satker->created_at_formatted = $satker->created_at->format('d/m/Y');
            $satker->updated_at_formatted = $satker->updated_at->format('d/m/Y');
            
            return response()->json([
                'success' => true,
                'data' => $satker
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in ajaxView: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Satker tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        if ($user->role !== 'superadmin') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }
            abort(403, 'Unauthorized access.');
        }
        
        try {
            $satker = Satker::findOrFail($id);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'form_type' => 'edit',
                    'title' => 'Edit Satker',
                    'data' => $satker
                ]);
            }
            
            return redirect()->route('superadmin.satker.index');
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Satker tidak ditemukan'
                ], 404);
            }
            
            return redirect()->route('superadmin.satker.index')
                ->with('error', 'Satker tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if ($user->role !== 'superadmin') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }
            abort(403, 'Unauthorized access.');
        }
        
        $satker = Satker::findOrFail($id);
        
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
            $oldData = $satker->toArray();
            
            $satker->update($request->all());
            
            ActivityLogController::logAction('update', 'Memperbarui data satker: ' . $satker->nama_satker, [
                'satker_id' => $satker->id,
                'kode_satker' => $satker->kode_satker,
                'nama_satker' => $satker->nama_satker,
                'old_data' => $oldData,
                'new_data' => $satker->toArray()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Satker berhasil diperbarui.',
                    'data' => $satker
                ]);
            }
            
            return redirect()->route('superadmin.satker.index')
                ->with('success', 'Satker berhasil diperbarui.');
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui satker: ' . $e->getMessage(),
                    'errors' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('superadmin.satker.index')
                ->with('error', 'Gagal memperbarui satker: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        
        if ($user->role !== 'superadmin') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }
            abort(403, 'Unauthorized access.');
        }
        
        $satker = Satker::findOrFail($id);
        
        try {
            if ($satker->users()->count() > 0) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat menghapus satker yang masih memiliki user.'
                    ], 400);
                }
                
                return redirect()->route('superadmin.satker.index')
                    ->with('error', 'Tidak dapat menghapus satker yang masih memiliki user.');
            }
            
            $logData = [
                'satker_id' => $satker->id,
                'kode_satker' => $satker->kode_satker,
                'nama_satker' => $satker->nama_satker,
                'alamat' => $satker->alamat
            ];
            
            $satker->delete();
            
            ActivityLogController::logAction('delete', 'Menghapus satker: ' . $logData['nama_satker'], $logData);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Satker berhasil dihapus.'
                ]);
            }
            
            return redirect()->route('superadmin.satker.index')
                ->with('success', 'Satker berhasil dihapus.');
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus satker: ' . $e->getMessage()
                ], 500);
            }
            
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
        
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $satker = Satker::withCount(['users', 'permintaans'])->findOrFail($id);
            
            // Tentukan status berdasarkan jumlah user
            $status = $satker->users_count > 0 ? 'Aktif' : 'Tidak Aktif';
            
            return response()->json([
                'success' => true,
                'data' => [
                    'satker' => $satker,
                    'users_count' => $satker->users_count,
                    'permintaans_count' => $satker->permintaans_count ?? 0,
                    'status' => $status
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
        
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $stats = [
                'total_satker' => Satker::count(),
                'satker_aktif' => Satker::has('users')->count(), // DIUBAH: konsisten dengan index method
                'satker_tanpa_user' => Satker::doesntHave('users')->count(),
                'total_users' => User::count(),
                'total_permintaan' => class_exists(Permintaan::class) ? Permintaan::count() : 0,
                'avg_users_per_satker_aktif' => Satker::has('users')->count() > 0 ? 
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
    public function checkHasUsers($id)
    {
        $user = auth()->user();
        
        if ($user->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        try {
            $satker = Satker::findOrFail($id);
            $hasUsers = $satker->users()->count() > 0;
            
            return response()->json([
                'success' => true,
                'has_users' => $hasUsers,
                'users_count' => $satker->users()->count(),
                'status' => $hasUsers ? 'Aktif' : 'Tidak Aktif' // DIUBAH: tambahkan status
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status satker'
            ], 500);
        }
    }
}