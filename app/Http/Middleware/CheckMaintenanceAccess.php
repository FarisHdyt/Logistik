<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CheckMaintenanceAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
public function handle(Request $request, Closure $next)
{
    // Debug informasi
    \Log::info('========== MAINTENANCE CHECK ==========');
    \Log::info('URL: ' . $request->fullUrl());
    \Log::info('Path: ' . $request->path());
    \Log::info('Method: ' . $request->method());
    \Log::info('IP: ' . $request->ip());
    
    if (Auth::check()) {
        $user = Auth::user();
        \Log::info('User ID: ' . $user->id);
        \Log::info('User Name: ' . $user->name);
        \Log::info('User Role: ' . $user->role);
        \Log::info('User Email: ' . $user->email);
        
        // SUPERADMIN LANGSUNG DILOLOSKAN
        if ($user->role === 'superadmin') {
            \Log::info('SUPERADMIN DILOLOSKAN!');
            return $next($request);
        }
    } else {
        \Log::info('User belum login');
    }
    
    // Lanjutkan ke middleware berikutnya
    return $next($request);
}
}