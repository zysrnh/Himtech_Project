<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Member;
use App\Models\MemberRegistrationToken;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MemberRegistrationController extends Controller
{
    /**
     * Tampilkan formulir pendaftaran anggota mandiri via QR Code atau token.
     */
    public function showForm(Request $request): View|RedirectResponse
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

        $tokenString = trim($request->query('token', ''));
        $orgCode = trim($request->query('org', ''));

        $token = null;
        $organization = null;
        $tokenStatus = 'no_token'; // no_token, valid, expired, quota_full, invalid, inactive

        if (! empty($tokenString)) {
            $token = MemberRegistrationToken::with('organization')->where('token', $tokenString)->first();

            if (! $token) {
                $tokenStatus = 'invalid';
            } elseif (! $token->is_active) {
                $tokenStatus = 'inactive';
            } elseif ($token->isExpired()) {
                $tokenStatus = 'expired';
            } elseif ($token->isQuotaFull()) {
                $tokenStatus = 'quota_full';
            } else {
                $tokenStatus = 'valid';
                $organization = $token->organization;
            }
        } elseif (! empty($orgCode)) {
            $organization = Organization::where('code', Str::upper($orgCode))->first();
            if ($organization && $organization->status === 'active') {
                $token = $organization->activeRegistrationToken();
                if ($token) {
                    $tokenStatus = 'valid';
                } else {
                    $tokenStatus = 'no_active_token';
                }
            } else {
                $tokenStatus = 'invalid';
            }
        }

        return view('auth.register_member', [
            'token' => $token,
            'organization' => $organization,
            'tokenStatus' => $tokenStatus,
            'tokenString' => $tokenString,
        ]);
    }

    /**
     * Proses konfirmasi registrasi anggota mandiri via QR Code (otomatis membuat profil default).
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
        ], [
            'token.required' => 'Token pendaftaran QR wajib disertakan.',
        ]);

        $token = MemberRegistrationToken::with('organization')
            ->where('token', $validated['token'])
            ->first();

        if (! $token || ! $token->is_active) {
            return back()->withErrors(['token' => 'QR Code atau token pendaftaran tidak valid atau telah dinonaktifkan.']);
        }

        if ($token->isExpired()) {
            return back()->withErrors(['token' => 'Masa berlaku QR Code pendaftaran ini telah habis. Silakan hubungi pengurus ormawa untuk meminta QR terbaru.']);
        }

        if ($token->isQuotaFull()) {
            return back()->withErrors(['token' => 'Batas kuota penggunaan QR Code ini telah tercapai. Silakan hubungi pengurus ormawa untuk membuka kuota pendaftaran baru.']);
        }

        $organization = $token->organization;

        if ($organization->status !== 'active') {
            return back()->withErrors(['token' => 'Organisasi ini sedang dinonaktifkan sementara oleh Administrator Kasly.']);
        }

        // Generate ID anggota unik default berdasarkan kode ormawa (contoh: HIMTECH-0011)
        // Pastikan unik baik di tabel members maupun tabel users (username)
        $orgCode = Str::upper($organization->code);
        $counter = max($organization->members()->count() + 1, 1);
        do {
            $defaultNim = $orgCode.'-'.str_pad($counter, 4, '0', STR_PAD_LEFT);
            $testUsername = Str::lower($defaultNim);
            $existsInMembers = Member::where('organization_id', $organization->id)->where('member_number', $defaultNim)->exists();
            $existsInUsers = User::where('username', $testUsername)->exists();
            $exists = $existsInMembers || $existsInUsers;
            $counter++;
        } while ($exists);

        $defaultName = 'Anggota '.$orgCode.' #'.substr($defaultNim, -4);
        $username = Str::lower($defaultNim);
        $passwordPlain = $username;
        $member = null;
        $user = null;

        DB::transaction(function () use ($defaultNim, $defaultName, $username, $passwordPlain, $organization, $token, &$member, &$user) {
            // 1. Tambah kuota terpakai pada token pendaftaran
            $token->increment('used_count');

            // 2. Buat akun user login
            $user = User::create([
                'name' => $defaultName,
                'username' => $username,
                'phone' => null,
                'password' => Hash::make($passwordPlain),
                'is_super_admin' => false,
            ]);

            // 3. Buat entitas anggota di buku induk ormawa
            $member = Member::create([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'member_number' => $defaultNim,
                'name' => $defaultName,
                'phone' => null,
                'is_active' => true,
            ]);

            // 4. Hubungkan ke pivot organization_user sebagai peran anggota
            $organization->users()->syncWithoutDetaching([
                $user->id => [
                    'role' => 'anggota',
                    'member_id' => $member->id,
                    'is_active' => true,
                ],
            ]);

            // 5. Catat audit trail aktivitas registrasi
            ActivityLog::create([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'action' => 'register_member_via_qr',
                'subject_type' => Member::class,
                'subject_id' => $member->id,
                'payload' => [
                    'token' => $token->token,
                    'member_number' => $defaultNim,
                    'name' => $defaultName,
                ],
            ]);
        });

        // 6. Langsung Auto-Login ke Portal Anggota
        Auth::login($user);
        $request->session()->regenerate();

        session([
            'active_organization_id' => $organization->id,
            'active_member_id' => $member->id,
            'active_role' => 'anggota',
        ]);

        return redirect()->route('anggota.dashboard')->with('success', "Selamat bergabung di {$organization->name}! Akun kamu telah dibuat dengan ID: {$defaultNim}. Kamu bisa menyesuaikan nama asli, NIM kampus, dan kata sandi di menu Profil Saya kapan saja.");
    }
}
