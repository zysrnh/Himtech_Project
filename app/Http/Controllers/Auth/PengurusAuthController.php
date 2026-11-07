<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PengurusAuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->is_super_admin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('pengurus.dashboard');
        }

        return view('auth.pengurus-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withInput($request->only('email', 'remember'))->withErrors([
                'email' => 'Email atau kata sandi yang Anda masukkan salah.',
            ]);
        }

        $user = Auth::user();

        // Cari organisasi tempat user terdaftar sebagai staff atau bendahara
        $organization = $user->organizations()
            ->where('organizations.status', 'active')
            ->wherePivotIn('role', ['staff', 'bendahara'])
            ->wherePivot('is_active', true)
            ->first();

        // Fallback untuk Super Admin jika ingin meninjau portal pengurus
        if (!$organization && $user->is_super_admin) {
            $organization = Organization::where('status', 'active')->first();
            $activeRole = 'staff';
        } else {
            $activeRole = $organization?->pivot->role ?? null;
        }

        if (!$organization) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Akun Anda tidak memiliki hak akses pengurus pada organisasi aktif manapun.',
            ]);
        }

        $request->session()->regenerate();
        session([
            'active_organization_id' => $organization->id,
            'active_role' => $activeRole,
        ]);

        return redirect()->intended(route('pengurus.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pengurus.login')->with('success', 'Sesi pengurus berhasil diakhiri.');
    }
}
