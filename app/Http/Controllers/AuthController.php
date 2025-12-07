<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah login.');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Check if user exists
        $user = User::where('username', $request->username)
                    ->orWhere('nrp', $request->username)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Username/NRP tidak ditemukan.',
            ])->withInput($request->except('password'));
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ])->withInput($request->except('password'));
        }

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'username' => 'Akun ini tidak aktif. Hubungi administrator.',
            ])->withInput($request->except('password'));
        }

        // Attempt to login
        Auth::login($user, $request->has('remember'));

        // Update last login info
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Redirect based on role
        $route = $this->getRedirectRoute($user->role);
        
        return redirect()->route($route)
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    /**
     * Get redirect route based on user role
     */
    private function getRedirectRoute($role)
    {
        return match($role) {
            'superadmin', 'admin' => 'admin.dashboard',
            'kabid' => 'kabid.dashboard',
            default => 'dashboard',
        };
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users',
            'nrp' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,kabid,admin,superadmin',
            'satker_id' => 'required_if:role,user,kabid|exists:satkers,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'nrp' => $request->nrp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'satker_id' => $request->satker_id,
            'is_active' => true,
        ]);

        // Auto login after registration
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di SILOG POLRES.');
    }

    /**
     * Handle logout - FIXED VERSION
     */
    public function logout()
    {
        // Clear authentication
        Auth::logout();
        
        // Clear all session data
        Session::flush();
        
        // Regenerate session ID
        Session::regenerate(true);
        
        // Forget laravel_session cookie
        $cookie = Cookie::forget('laravel_session');
        
        // Redirect to home page with success message
        return redirect()->route('home')
            ->with('success', 'Anda telah berhasil logout.')
            ->withCookie($cookie);
    }

    /**
     * Alternative logout method - more aggressive
     */
    public function forceLogout()
    {
        // Clear auth
        Auth::logout();
        
        // Clear all sessions
        session()->flush();
        session()->regenerate(true);
        
        // Clear all cookies
        $cookies = [
            'laravel_session',
            'XSRF-TOKEN',
            'remember_web_' . sha1(get_class($this)),
        ];
        
        foreach ($cookies as $cookieName) {
            Cookie::queue(Cookie::forget($cookieName));
        }
        
        return redirect()->route('home')
            ->with('success', 'Semua session telah dibersihkan.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle reset password request
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}