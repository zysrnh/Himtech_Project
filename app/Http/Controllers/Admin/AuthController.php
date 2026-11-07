<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan form login khusus super admin / admin kasly penerima.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->is_super_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Proses submit login admin kasly dengan proteksi rate limiting dan fleksibilitas email / username.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Email atau Username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($request->input('login'));
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Proteksi Brute Force (Maksimal 5 percobaan per menit)
        $throttleKey = 'login.admin:'.Str::lower($loginInput).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withErrors([
                    'login' => "Terlalu banyak percobaan login. Akses dikunci sementara. Silakan coba lagi dalam {$seconds} detik.",
                ])
                ->onlyInput('login');
        }

        $remember = $request->boolean('remember');

        // Autentikasi dengan verifikasi hak akses admin pusat (is_super_admin)
        $credentials = [
            $fieldType => $fieldType === 'username' ? Str::lower($loginInput) : $loginInput,
            'password' => $request->input('password'),
            'is_super_admin' => true,
        ];

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'login' => 'Kredensial tidak valid atau akun tidak terdaftar sebagai Administrator Kasly.',
        ])->onlyInput('login');
    }

    /**
     * Logout admin kasly dari sesi.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Sesi Administrator Kasly berhasil diakhiri.');
    }
}
