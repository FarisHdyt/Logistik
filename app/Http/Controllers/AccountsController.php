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
                  ->orWhere('username', 'like', "%{$search}%");
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
        $roles = ['superadmin', 'admin', 'kabid', 'user'];
        
        return view('superadmin.accounts', [
            'satkers' => $satkers, 
            'roles' => $roles,
            'mode' => 'create'
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
            'username' => ['nullable', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:superadmin,admin,kabid,user'],
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
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'satker_id' => $request->satker_id,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        // Log activity menggunakan ActivityLogController
        ActivityLogController::logCreateUser($user, auth()->user()->name ?? 'System');

        return redirect()->route('superadmin.accounts.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Display the specified user.
     */
    public function show(Request $request, User $user)
    {
        return view('superadmin.accounts', [
            'user' => $user,
            'mode' => 'show'
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $satkers = Satker::orderBy('nama_satker')->get();
        $roles = ['superadmin', 'admin', 'kabid', 'user'];
        
        return view('superadmin.accounts', [
            'user' => $user,
            'satkers' => $satkers,
            'roles' => $roles,
            'mode' => 'edit'
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 
                        Rule::unique('users')->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:50', 
                          Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', 'in:superadmin,admin,kabid,user'],
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
        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'satker_id' => $user->satker_id,
        ];

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
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

        // Log activity menggunakan ActivityLogController
        $logData = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'old_data' => $oldData,
            'new_data' => $request->all(),
            'updated_by' => auth()->user()->name ?? 'System'
        ];
        ActivityLogController::logAction('update_user', 'Memperbarui data akun: ' . $user->name, $logData);

        return redirect()->route('superadmin.accounts.index')
            ->with('success', 'Data akun berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Log activity before deletion
        $logData = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'deleted_by' => auth()->user()->name ?? 'System'
        ];
        ActivityLogController::logAction('delete_user', 'Menghapus akun: ' . $user->name, $logData);

        $user->delete();

        return redirect()->route('superadmin.accounts.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(Request $request, User $user)
    {
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
        $logData = [
            'user_id' => $user->id,
            'name' => $user->name,
            'old_status' => $oldStatus,
            'new_status' => $user->is_active,
            'changed_by' => auth()->user()->name ?? 'System'
        ];
        ActivityLogController::logAction('toggle_user_status', 'Mengubah status akun ' . $user->name, $logData);

        return response()->json([
            'success' => true,
            'message' => 'Status akun berhasil diubah.',
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Show user activity logs.
     */
    public function activityLogs(Request $request, User $user)
    {
        $logs = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('superadmin.accounts', [
            'user' => $user,
            'logs' => $logs,
            'mode' => 'activity-logs'
        ]);
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Log activity
        $logData = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'reset_by' => auth()->user()->name ?? 'System'
        ];
        ActivityLogController::logAction('reset_password', 'Reset password akun: ' . $user->name, $logData);

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
                break;
                
            case 'deactivate':
                User::whereIn('id', $userIds)->update(['is_active' => false]);
                $message = 'Akun berhasil dinonaktifkan.';
                break;
                
            case 'delete':
                User::whereIn('id', $userIds)->delete();
                $message = 'Akun berhasil dihapus.';
                break;
        }

        // Log activity
        $logData = [
            'action' => $action,
            'user_count' => count($userIds),
            'user_ids' => $userIds,
            'user_names' => $affectedUsers->pluck('name')->toArray(),
            'performed_by' => auth()->user()->name ?? 'System'
        ];
        ActivityLogController::logAction('bulk_user_action', 'Melakukan aksi ' . $action . ' pada ' . count($userIds) . ' akun', $logData);

        return redirect()->route('superadmin.accounts.index')
            ->with('success', $message);
    }
}