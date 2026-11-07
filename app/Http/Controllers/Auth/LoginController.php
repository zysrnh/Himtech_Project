<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan formulir login terpadu (Pengurus & Anggota).
     */
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            if (session('active_role') === 'anggota') {
                return redirect()->route('anggota.dashboard');
            }

            if (Auth::user()->is_super_admin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('pengurus.dashboard');
        }

        $activeRole = $request->query('role', 'pengurus');
        if (!in_array($activeRole, ['pengurus', 'anggota'])) {
            $activeRole = 'pengurus';
        }

        return view('auth.login', compact('activeRole'));
    }

    /**
     * Proses autentikasi Pengurus / Staff dengan verifikasi kode organisasi & proteksi rate limiting.
     */
    public function loginPengurus(Request $request): RedirectResponse
    {
        $request->validate([
            'organization_code' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'organization_code.required' => 'Kode organisasi wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Proteksi Brute Force (Maksimal 5 percobaan per menit)
        $throttleKey = 'login.pengurus:'.Str::lower($request->input('email')).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('organization_code', 'email'))
                ->with('active_tab', 'pengurus')
                ->withErrors([
                    'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
                ]);
        }

        // 1. Verifikasi Keberadaan & Status Organisasi
        $orgCode = strtoupper(trim($request->organization_code));
        $organization = Organization::where('code', $orgCode)
            ->where('status', 'active')
            ->first();

        if (!$organization) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withInput($request->only('organization_code', 'email'))
                ->with('active_tab', 'pengurus')
                ->withErrors([
                    'organization_code' => 'Kode organisasi tidak ditemukan atau sedang tidak aktif.',
                ]);
        }

        // 2. Verifikasi Email & Kata Sandi
        $remember = $request->boolean('remember');
        if (!Auth::attempt($request->only('email', 'password'), $remember)) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withInput($request->only('organization_code', 'email'))
                ->with('active_tab', 'pengurus')
                ->withErrors([
                    'email' => 'Email atau kata sandi pengurus salah.',
                ]);
        }

        $user = Auth::user();

        // 3. Verifikasi Hak Akses Multi-Tenant (User harus terdaftar di organisasi ini)
        $orgPivot = $user->organizations()
            ->where('organizations.id', $organization->id)
            ->wherePivotIn('role', ['staff', 'bendahara'])
            ->wherePivot('is_active', true)
            ->first();

        // Izinkan Super Admin memantau organisasi jika diperlukan
        if (!$orgPivot && !$user->is_super_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withInput($request->only('organization_code', 'email'))
                ->with('active_tab', 'pengurus')
                ->withErrors([
                    'email' => "Akun Anda tidak memiliki hak akses pengurus pada organisasi {$organization->name} ({$organization->code}).",
                ]);
        }

        // Bersihkan hitungan percobaan jika login berhasil
        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();
        session([
            'active_organization_id' => $organization->id,
            'active_role' => $orgPivot?->pivot->role ?? 'staff',
        ]);

        return redirect()->intended(route('pengurus.dashboard'));
    }

    /**
     * Proses autentikasi Anggota dengan verifikasi tenant & proteksi rate limiting.
     */
    public function loginAnggota(Request $request): RedirectResponse
    {
        $request->validate([
            'organization_code' => ['required', 'string'],
            'member_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'organization_code.required' => 'Kode organisasi wajib diisi.',
            'member_number.required' => 'Nomor Anggota atau NIM wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Proteksi Brute Force (Maksimal 5 percobaan per menit)
        $orgCode = strtoupper(trim($request->organization_code));
        $memberNumber = trim($request->member_number);
        $throttleKey = 'login.anggota:'.$orgCode.'|'.$memberNumber.'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('organization_code', 'member_number'))
                ->with('active_tab', 'anggota')
                ->withErrors([
                    'member_number' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
                ]);
        }

        // 1. Verifikasi Keberadaan Organisasi
        $organization = Organization::where('code', $orgCode)
            ->where('status', 'active')
            ->first();

        if (!$organization) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withInput($request->only('organization_code', 'member_number'))
                ->with('active_tab', 'anggota')
                ->withErrors([
                    'organization_code' => 'Kode organisasi tidak ditemukan atau sedang tidak aktif.',
                ]);
        }

        // 2. Verifikasi Data Anggota Aktif di Organisasi Tersebut
        $member = Member::where('organization_id', $organization->id)
            ->where('member_number', $memberNumber)
            ->where('is_active', true)
            ->first();

        if (!$member) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withInput($request->only('organization_code', 'member_number'))
                ->with('active_tab', 'anggota')
                ->withErrors([
                    'member_number' => "Nomor anggota atau NIM tidak terdaftar pada organisasi {$organization->name}.",
                ]);
        }

        // 3. Verifikasi Kata Sandi
        $user = null;
        if ($member->user_id) {
            $user = User::find($member->user_id);
        }

        if ($user) {
            if (!Hash::check($request->password, $user->password)) {
                RateLimiter::hit($throttleKey, 60);

                return back()
                    ->withInput($request->only('organization_code', 'member_number'))
                    ->with('active_tab', 'anggota')
                    ->withErrors([
                        'password' => 'Kata sandi anggota salah.',
                    ]);
            }
        } else {
            // Jika akun user baru dan memasukkan kata sandi awal (default = nomor anggota)
            if ($request->password === $member->member_number) {
                $user = User::create([
                    'name' => $member->name,
                    'phone' => $member->phone,
                    'password' => Hash::make($request->password),
                    'is_super_admin' => false,
                ]);

                $member->update(['user_id' => $user->id]);

                $organization->users()->attach($user->id, [
                    'role' => 'anggota',
                    'member_id' => $member->id,
                    'is_active' => true,
                ]);
            } else {
                RateLimiter::hit($throttleKey, 60);

                return back()
                    ->withInput($request->only('organization_code', 'member_number'))
                    ->with('active_tab', 'anggota')
                    ->withErrors([
                        'password' => 'Kata sandi anggota salah.',
                    ]);
            }
        }

        // Bersihkan rate limiter jika berhasil
        RateLimiter::clear($throttleKey);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        session([
            'active_organization_id' => $organization->id,
            'active_member_id' => $member->id,
            'active_role' => 'anggota',
        ]);

        return redirect()->intended(route('anggota.dashboard'));
    }

    /**
     * Logout sesi dan regenerasi token CSRF.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesi berhasil diakhiri.');
    }
}
