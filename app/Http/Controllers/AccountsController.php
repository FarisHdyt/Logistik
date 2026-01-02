<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Satker;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ActivityLogController;

class AccountsController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        // Get all satkers for filter dropdown
        $satkers = Satker::orderBy('nama_satker')->get();
        
        // Start building query
        $query = User::with('satker');
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('nrp', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('status')) {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }
        
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }
        
        // Order by latest
        $query->orderBy('created_at', 'desc');
        
        // Paginate results
        $perPage = $request->per_page ?? 10;
        $users = $query->paginate($perPage);
        
        return view('superadmin.accounts', compact('users', 'satkers'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $satkers = Satker::orderBy('nama_satker')->get();
        $roles = ['superadmin', 'admin', 'user'];
        
        return view('superadmin.accounts', [
            'satkers' => $satkers, 
            'roles' => $roles,
            'mode' => 'create',
            'users' => User::paginate(10)
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nrp' => ['required', 'string', 'max:10', 'unique:users'],
            'username' => ['nullable', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:superadmin,admin,user'],
            'satker_id' => ['nullable', 'exists:satkers,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nrp' => $request->nrp,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'satker_id' => $request->satker_id,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        // Log activity
        ActivityLogController::logAction('create', 'Membuat akun baru: ' . $user->name, [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nrp' => $user->nrp,
            'role' => $user->role
        ]);

        return redirect()->route('superadmin.accounts.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::with('satker')->findOrFail($id);
        
        $query = User::with('satker');
        $perPage = request('per_page', 10);
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        $satkers = Satker::orderBy('nama_satker')->get();
        
        return view('superadmin.accounts', [
            'user' => $user,
            'users' => $users,
            'satkers' => $satkers,
            'mode' => 'show'
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $satkers = Satker::orderBy('nama_satker')->get();
        $roles = ['superadmin', 'admin', 'user'];
        
        $query = User::with('satker');
        $perPage = request('per_page', 10);
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return view('superadmin.accounts', [
            'user' => $user,
            'satkers' => $satkers,
            'roles' => $roles,
            'users' => $users,
            'mode' => 'edit'
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 
                        Rule::unique('users')->ignore($user->id)],
            'nrp' => ['required', 'string', 'max:10',
                     Rule::unique('users')->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:50', 
                          Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', 'in:superadmin,admin,user'],
            'satker_id' => ['nullable', 'exists:satkers,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prevent superadmin from downgrading their own role
        if ($user->id === auth()->id() && $request->role !== 'superadmin') {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        // Simpan data lama untuk logging
        $oldData = $user->toArray();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nrp' => $request->nrp,
            'username' => $request->username,
            'role' => $request->role,
            'satker_id' => $request->satker_id,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Log activity
        ActivityLogController::logAction('update', 'Memperbarui data akun: ' . $user->name, [
            'user_id' => $user->id,
            'old_data' => $oldData,
            'new_data' => $user->toArray()
        ]);

        return redirect()->route('superadmin.accounts.index')
            ->with('success', 'Data akun berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Log activity before deletion
        ActivityLogController::logAction('delete', 'Menghapus akun: ' . $user->name, [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nrp' => $user->nrp,
            'role' => $user->role
        ]);

        $user->delete();

        return redirect()->route('superadmin.accounts.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent disabling own account
        if ($user->id === auth()->id() && !$request->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.'
            ]);
        }

        $oldStatus = $user->is_active;
        
        $user->update([
            'is_active' => $request->boolean('is_active'),
        ]);

        // Log activity
        ActivityLogController::logAction('update', 'Mengubah status akun ' . $user->name, [
            'user_id' => $user->id,
            'old_status' => $oldStatus,
            'new_status' => $user->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status akun berhasil diubah.',
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Show user activity logs.
     */
    public function activityLogs(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $logs = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $query = User::with('satker');
        $perPage = request('per_page', 10);
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        $satkers = Satker::orderBy('nama_satker')->get();
        
        return view('superadmin.accounts', [
            'user' => $user,
            'logs' => $logs,
            'users' => $users,
            'satkers' => $satkers,
            'mode' => 'activity-logs'
        ]);
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Log activity
        ActivityLogController::logAction('update', 'Reset password akun: ' . $user->name, [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nrp' => $user->nrp
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset.'
        ]);
    }

    /**
     * Bulk actions for users.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete'],
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $action = $request->action;
        $userIds = $request->user_ids;
        $currentUserId = auth()->id();

        // Remove current user from list to prevent self-action
        $userIds = array_diff($userIds, [$currentUserId]);

        if (empty($userIds)) {
            return redirect()->back()
                ->with('error', 'Tidak ada akun yang dipilih atau Anda tidak dapat melakukan aksi pada diri sendiri.');
        }

        $affectedUsers = User::whereIn('id', $userIds)->get();

        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['is_active' => true]);
                $message = 'Akun berhasil diaktifkan.';
                $logAction = 'update';
                break;
                
            case 'deactivate':
                User::whereIn('id', $userIds)->update(['is_active' => false]);
                $message = 'Akun berhasil dinonaktifkan.';
                $logAction = 'update';
                break;
                
            case 'delete':
                User::whereIn('id', $userIds)->delete();
                $message = 'Akun berhasil dihapus.';
                $logAction = 'delete';
                break;
        }

        // Log activity
        ActivityLogController::logAction($logAction, 'Melakukan aksi ' . $action . ' pada ' . count($userIds) . ' akun', [
            'action' => $action,
            'user_count' => count($userIds),
            'user_ids' => $userIds
        ]);

        return redirect()->route('superadmin.accounts.index')
            ->with('success', $message);
    }
}