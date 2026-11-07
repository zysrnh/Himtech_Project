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
     * Tampilkan formulir login tunggal universal (Otomatis deteksi peran).
     */
    public function showLoginForm(): View|RedirectResponse
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

        return view('auth.login');
    }

    /**
     * Proses autentikasi tunggal dengan deteksi otomatis peran (Staff vs Anggota).
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'organization_code' => ['required', 'string'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'organization_code.required' => 'Kode organisasi wajib diisi.',
            'username.required' => 'Username atau NIM wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $orgCode = strtoupper(trim($request->organization_code));
        $inputIdentifier = trim($request->username);

        // Proteksi Brute Force (Maksimal 5 percobaan per menit per IP + Akun)
        $throttleKey = 'login:'.$orgCode.'|'.Str::lower($inputIdentifier).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('organization_code', 'username'))
                ->withErrors([
                    'username' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
                ]);
        }

        // 1. Verifikasi Keberadaan & Status Keaktifan Organisasi
        $organization = Organization::where('code', $orgCode)->first();

        if (! $organization) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withInput($request->only('organization_code', 'username'))
                ->withErrors([
                    'organization_code' => 'Kode organisasi tidak ditemukan di dalam sistem.',
                ]);
        }

        if ($organization->status !== 'active') {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withInput($request->only('organization_code', 'username'))
                ->withErrors([
                    'organization_code' => "Organisasi {$organization->name} ({$organization->code}) sedang dinonaktifkan oleh Administrator.",
                ]);
        }

        $remember = $request->boolean('remember');

        // 2. Deteksi Otomatis Akun: Cek Apakah Ini Pengurus / Staff
        $staffUser = $organization->users()
            ->where(function ($query) use ($inputIdentifier) {
                $query->where('users.username', $inputIdentifier)
                    ->orWhere('users.email', $inputIdentifier);
            })
            ->wherePivotIn('role', ['staff', 'bendahara'])
            ->wherePivot('is_active', true)
            ->first();

        if ($staffUser && Hash::check($request->password, $staffUser->password)) {
            RateLimiter::clear($throttleKey);
            Auth::login($staffUser, $remember);
            $request->session()->regenerate();

            session([
                'active_organization_id' => $organization->id,
                'active_role' => $staffUser->pivot->role ?? 'staff',
            ]);

            return redirect()->intended(route('pengurus.dashboard'));
        }

        // 3. Deteksi Otomatis Akun: Cek Apakah Ini Anggota Ormawa (berdasarkan NIM / Nomor Anggota)
        $member = Member::where('organization_id', $organization->id)
            ->where('member_number', $inputIdentifier)
            ->where('is_active', true)
            ->first();

        if ($member) {
            $memberUser = null;
            if ($member->user_id) {
                $memberUser = User::find($member->user_id);
            }

            // Jika anggota sudah memiliki user terhubung
            if ($memberUser && Hash::check($request->password, $memberUser->password)) {
                RateLimiter::clear($throttleKey);
                Auth::login($memberUser, $remember);
                $request->session()->regenerate();

                session([
                    'active_organization_id' => $organization->id,
                    'active_member_id' => $member->id,
                    'active_role' => 'anggota',
                ]);

                return redirect()->intended(route('anggota.dashboard'));
            }

            // Jika akun user baru dan memasukkan kata sandi awal default (nomor anggota / NIM)
            if (! $memberUser && $request->password === $member->member_number) {
                RateLimiter::clear($throttleKey);

                $memberUser = User::create([
                    'name' => $member->name,
                    'username' => $member->member_number,
                    'phone' => $member->phone,
                    'password' => Hash::make($request->password),
                    'is_super_admin' => false,
                ]);

                $member->update(['user_id' => $memberUser->id]);

                $organization->users()->attach($memberUser->id, [
                    'role' => 'anggota',
                    'member_id' => $member->id,
                    'is_active' => true,
                ]);

                Auth::login($memberUser, $remember);
                $request->session()->regenerate();

                session([
                    'active_organization_id' => $organization->id,
                    'active_member_id' => $member->id,
                    'active_role' => 'anggota',
                ]);

                return redirect()->intended(route('anggota.dashboard'));
            }
        }

        // Jika tidak cocok dengan akun pengurus maupun anggota
        RateLimiter::hit($throttleKey, 60);

        return back()
            ->withInput($request->only('organization_code', 'username'))
            ->withErrors([
                'username' => "Username/NIM atau kata sandi tidak cocok dengan data organisasi {$organization->name}.",
            ]);
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
