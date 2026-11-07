<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan form login khusus super admin.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->is_super_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Proses submit login super admin.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Autentikasi dengan verifikasi wajib status super admin
        if (Auth::attempt(array_merge($credentials, ['is_super_admin' => true]), $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak valid atau akun tidak memiliki hak akses Super Admin.',
        ])->onlyInput('email');
    }

    /**
     * Logout super admin dari sesi.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

