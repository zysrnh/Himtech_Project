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
use Illuminate\View\View;

class LoginController extends Controller
{
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

    public function loginPengurus(Request $request): RedirectResponse
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
            return back()
                ->withInput($request->only('email', 'remember'))
                ->with('active_tab', 'pengurus')
                ->withErrors([
                    'email' => 'Email atau kata sandi pengurus salah.',
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

            return back()
                ->withInput($request->only('email'))
                ->with('active_tab', 'pengurus')
                ->withErrors([
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

        $orgCode = strtoupper(trim($request->organization_code));
        $organization = Organization::where('code', $orgCode)
            ->where('status', 'active')
            ->first();

        if (!$organization) {
            return back()
                ->withInput($request->only('organization_code', 'member_number'))
                ->with('active_tab', 'anggota')
                ->withErrors([
                    'organization_code' => 'Kode organisasi tidak ditemukan atau sedang tidak aktif.',
                ]);
        }

        $memberNumber = trim($request->member_number);
        $member = Member::where('organization_id', $organization->id)
            ->where('member_number', $memberNumber)
            ->where('is_active', true)
            ->first();

        if (!$member) {
            return back()
                ->withInput($request->only('organization_code', 'member_number'))
                ->with('active_tab', 'anggota')
                ->withErrors([
                    'member_number' => "Nomor anggota atau NIM tidak terdaftar pada organisasi {$organization->name}.",
                ]);
        }

        $user = null;
        if ($member->user_id) {
            $user = User::find($member->user_id);
        }

        if ($user) {
            if (!Hash::check($request->password, $user->password)) {
                return back()
                    ->withInput($request->only('organization_code', 'member_number'))
                    ->with('active_tab', 'anggota')
                    ->withErrors([
                        'password' => 'Kata sandi anggota salah.',
                    ]);
            }
        } else {
            // Password default awal anggota sama dengan nomor anggota / NIM
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
                return back()
                    ->withInput($request->only('organization_code', 'member_number'))
                    ->with('active_tab', 'anggota')
                    ->withErrors([
                        'password' => 'Kata sandi anggota salah.',
                    ]);
            }
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        session([
            'active_organization_id' => $organization->id,
            'active_member_id' => $member->id,
            'active_role' => 'anggota',
        ]);

        return redirect()->intended(route('anggota.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesi berhasil diakhiri.');
    }
}
