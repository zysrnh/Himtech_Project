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
     * Proses submit registrasi anggota mandiri via QR Code dengan validasi kuota & waktu.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'member_number' => ['required', 'string', 'max:50', 'alpha_num'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'token.required' => 'Token pendaftaran QR wajib disertakan.',
            'member_number.required' => 'Nomor Anggota / NIM wajib diisi.',
            'member_number.alpha_num' => 'Nomor Anggota / NIM hanya boleh berupa kombinasi huruf dan angka.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter jika ingin diubah.',
        ]);

        $token = MemberRegistrationToken::with('organization')
            ->where('token', $validated['token'])
            ->first();

        if (! $token || ! $token->is_active) {
            return back()->withErrors(['token' => 'QR Code atau token pendaftaran tidak valid atau telah dinonaktifkan.'])->withInput();
        }

        if ($token->isExpired()) {
            return back()->withErrors(['token' => 'Masa berlaku QR Code pendaftaran ini telah habis. Silakan hubungi pengurus ormawa untuk meminta QR terbaru.'])->withInput();
        }

        if ($token->isQuotaFull()) {
            return back()->withErrors(['token' => 'Batas kuota penggunaan QR Code ini telah tercapai. Silakan hubungi pengurus ormawa untuk membuka kuota pendaftaran baru.'])->withInput();
        }

        $organization = $token->organization;

        if ($organization->status !== 'active') {
            return back()->withErrors(['token' => 'Organisasi ini sedang dinonaktifkan sementara oleh Administrator Kasly.'])->withInput();
        }

        $nim = trim($validated['member_number']);

        // Cek apakah NIM sudah terdaftar di organisasi ini
        $existingMember = Member::where('organization_id', $organization->id)
            ->where('member_number', $nim)
            ->first();

        if ($existingMember) {
            return back()->withErrors(['member_number' => "NIM/Nomor Anggota {$nim} sudah terdaftar di organisasi {$organization->name}. Silakan langsung masuk ke sistem."])->withInput();
        }

        $passwordPlain = ! empty($validated['password']) ? $validated['password'] : $nim;
        $member = null;
        $user = null;

        DB::transaction(function () use ($validated, $nim, $passwordPlain, $organization, $token, &$member, &$user) {
            // 1. Tambah kuota terpakai pada token pendaftaran
            $token->increment('used_count');

            // 2. Cek apakah akun user dengan username NIM sudah ada atau buat baru
            $user = User::where('username', Str::lower($nim))->first();

            if (! $user) {
                $user = User::create([
                    'name' => $validated['name'],
                    'username' => Str::lower($nim),
                    'phone' => $validated['phone'],
                    'password' => Hash::make($passwordPlain),
                    'is_super_admin' => false,
                ]);
            }

            // 3. Buat entitas anggota di buku induk ormawa
            $member = Member::create([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'member_number' => $nim,
                'name' => $validated['name'],
                'phone' => $validated['phone'],
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
                    'member_number' => $nim,
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
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

        return redirect()->route('anggota.dashboard')->with('success', "Selamat datang di Kasly! Akun Anda berhasil terdaftar sebagai anggota {$organization->name} ({$organization->code}).");
    }
}
