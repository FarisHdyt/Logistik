<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\ActivityLogController;

class SettingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Load settings from cache or database
        $settings = [
            'system_name' => config('app.name', 'SILOG Polres'),
            'system_version' => config('app.version', '1.0.0'),
            'timezone' => config('app.timezone', 'Asia/Jakarta'),
            'language' => config('app.locale', 'id'),
            'date_format' => config('app.date_format', 'd/m/Y'),
            'items_per_page' => config('app.items_per_page', 10),
            'system_description' => config('app.description', 'Sistem Logistik Polres'),
            
            // Inventory settings
            'auto_generate_code' => config('inventory.auto_generate_code', 1),
            'code_prefix' => config('inventory.code_prefix', 'BRG'),
            'low_stock_threshold' => config('inventory.low_stock_threshold', 2),
            'critical_stock_threshold' => config('inventory.critical_stock_threshold', 1),
            'default_stock_minimal' => config('inventory.default_stock_minimal', 10),
            'auto_alert_stock' => config('inventory.auto_alert_stock', 1),
            'allow_negative_stock' => config('inventory.allow_negative_stock', 0),
            
            // Security settings
            'session_timeout' => config('session.lifetime', 30),
            'max_login_attempts' => config('auth.max_login_attempts', 5),
            'password_expiry_days' => config('auth.password_expiry_days', 90),
            'password_min_length' => config('auth.password_min_length', 8),
            'force_password_change' => config('auth.force_password_change', 1),
            'enable_2fa' => config('auth.enable_2fa', 0),
            'log_user_activity' => config('auth.log_user_activity', 1),
        ];
        
        return view('admin.settings', compact('user', 'settings'));
    }
    
    public function save(Request $request)
    {
        $validated = $request->validate([
            'system_name' => 'required',
            'system_version' => 'required',
            'timezone' => 'required',
            'language' => 'required',
            'date_format' => 'required',
            'items_per_page' => 'required|integer|min:5|max:100',
            'system_description' => 'nullable',
            
            // Inventory settings
            'auto_generate_code' => 'required|boolean',
            'code_prefix' => 'required',
            'low_stock_threshold' => 'required|integer|min:1|max:10',
            'critical_stock_threshold' => 'required|integer|min:0|max:5',
            'default_stock_minimal' => 'required|integer|min:1',
            'auto_alert_stock' => 'required|boolean',
            'allow_negative_stock' => 'boolean',
            
            // Security settings
            'session_timeout' => 'required|integer|min:5|max:240',
            'max_login_attempts' => 'required|integer|min:1|max:10',
            'password_expiry_days' => 'required|integer|min:30|max:365',
            'password_min_length' => 'required|integer|min:6|max:20',
            'force_password_change' => 'boolean',
            'enable_2fa' => 'boolean',
            'log_user_activity' => 'boolean',
        ]);
        
        // Get current settings for logging
        $oldSettings = [];
        foreach ($validated as $key => $value) {
            $oldSettings[$key] = Cache::get('setting.' . $key) ?? config("app.{$key}", config("inventory.{$key}", config("auth.{$key}", config("session.{$key}"))));
        }
        
        // Save to cache
        foreach ($validated as $key => $value) {
            Cache::forever('setting.' . $key, $value);
        }
        
        // Update runtime configuration
        config([
            'app.name' => $validated['system_name'],
            'app.timezone' => $validated['timezone'],
            'app.locale' => $validated['language'],
            'app.items_per_page' => $validated['items_per_page'],
        ]);
        
        // Log activity
        $logData = [
            'old_settings' => $oldSettings,
            'new_settings' => $validated,
            'changed_by' => auth()->user()->name ?? 'System',
            'changed_count' => count(array_diff_assoc($validated, $oldSettings))
        ];
        ActivityLogController::logAction('update_settings', 'Memperbarui pengaturan sistem', $logData);
        
        return redirect()->route('admin.settings')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}