<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Debug: Log middleware execution
        \Log::info('CheckRole Middleware Executed', [
            'url' => $request->url(),
            'user_id' => auth()->id(),
            'user_role' => auth()->check() ? auth()->user()->role : 'not_logged_in',
            'required_roles' => $roles
        ]);

        // Cek apakah user sudah login
        if (!auth()->check()) {
            \Log::warning('CheckRole: User not authenticated');
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        
        // Debug: Log user info
        \Log::info('CheckRole: User Information', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'user_email' => $user->email
        ]);

        // Jika tidak ada role yang ditentukan, izinkan akses
        if (empty($roles)) {
            \Log::info('CheckRole: No roles specified, allowing access');
            return $next($request);
        }

        // Cek apakah user memiliki salah satu role yang diizinkan
        foreach ($roles as $role) {
            // Pengecekan role dengan trim dan lowercase untuk menghindari masalah case sensitivity
            $userRole = strtolower(trim($user->role ?? ''));
            $requiredRole = strtolower(trim($role));
            
            \Log::info('CheckRole: Comparing roles', [
                'user_role' => $userRole,
                'required_role' => $requiredRole,
                'match' => $userRole === $requiredRole
            ]);
            
            if ($userRole === $requiredRole) {
                \Log::info('CheckRole: Role matched, allowing access');
                return $next($request);
            }
        }

        // Jika tidak memiliki akses, log dan redirect berdasarkan role user
        \Log::warning('CheckRole: Unauthorized access attempt', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'required_roles' => $roles,
            'url' => $request->url()
        ]);

        // Redirect user berdasarkan role mereka ke dashboard yang sesuai
        switch ($user->role) {
            case 'superadmin':
                return redirect()->route('superadmin.dashboard')
                    ->with('error', 'Akses ditolak ke halaman ini.');
            case 'admin':
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Akses ditolak. Hanya untuk Superadmin.');
            case 'user':
                return redirect()->route('user.dashboard')
                    ->with('error', 'Akses ditolak. Hanya untuk Admin/Superadmin.');
            default:
                return redirect()->route('dashboard')
                    ->with('error', 'Akses ditolak. Role tidak dikenali.');
        }
        
        // Atau jika ingin menggunakan abort 403:
        // abort(403, 'Unauthorized access. Your role: ' . $user->role);
    }
}