<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Member;
use App\Models\Organization;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan formulir permintaan reset kata sandi (Kode Ormawa & NIM/Username).
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim kode OTP 6-digit pemulihan akun via WhatsApp Fonnte.
     */
    public function sendResetOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_code' => ['required', 'string', 'max:50'],
            'identity' => ['required', 'string', 'max:100'],
        ], [
            'organization_code.required' => 'Kode organisasi wajib diisi.',
            'identity.required' => 'Username, NIM, atau Email wajib diisi.',
        ]);

        $org = Organization::where('code', strtoupper(trim($validated['organization_code'])))
            ->where('status', 'active')
            ->first();

        if (! $org) {
            return back()->withInput()->withErrors([
                'organization_code' => 'Organisasi dengan kode tersebut tidak ditemukan atau sedang tidak aktif.',
            ]);
        }

        $identity = trim($validated['identity']);
        $user = null;
        $targetPhone = null;
        $recipientName = null;

        // 1. Cek apakah identitas adalah NIM Anggota di ormawa ini
        $member = Member::where('organization_id', $org->id)
            ->where('member_number', $identity)
            ->first();

        if ($member && $member->user) {
            $user = $member->user;
            $targetPhone = $member->phone ?? $user->phone;
            $recipientName = $member->name;
        }

        // 2. Jika bukan anggota, cek apakah identitas adalah username atau email staf/pengurus
        if (! $user) {
            $potentialUser = User::where(function ($q) use ($identity) {
                $q->where('username', $identity)
                    ->orWhere('email', $identity);
            })->first();

            if ($potentialUser) {
                $isInOrg = $potentialUser->organizations()
                    ->where('organizations.id', $org->id)
                    ->exists() || $potentialUser->is_super_admin;

                if ($isInOrg) {
                    $user = $potentialUser;
                    $targetPhone = $user->phone;
                    $recipientName = $user->name;
                }
            }
        }

        if (! $user) {
            return back()->withInput()->withErrors([
                'identity' => "Akun dengan NIM/Username/Email '{$identity}' tidak terdaftar pada organisasi {$org->name}.",
            ]);
        }

        // Generate OTP 6 Digit
        $otp = (string) rand(100000, 999999);
        $expiresAt = now()->addMinutes(15);

        session([
            'password_reset_user_id' => $user->id,
            'password_reset_org_id' => $org->id,
            'password_reset_otp' => $otp,
            'password_reset_expires' => $expiresAt->timestamp,
            'password_reset_phone' => $targetPhone,
            'password_reset_name' => $recipientName,
        ]);

        // Kirim OTP via WhatsApp jika nomor tersedia
        $sentWa = false;
        if (! empty($targetPhone)) {
            $msg = "*KODE PEMULIHAN KATA SANDI KASLY*\n"
                ."*{$org->name}*\n\n"
                ."Halo {$recipientName},\n"
                ."Berikut adalah kode OTP untuk mereset kata sandi akun Kasly Anda:\n\n"
                ."*{$otp}*\n\n"
                ."Kode ini berlaku selama 15 menit. Masukkan kode tersebut pada halaman verifikasi.\n"
                ."Jangan bagikan kode ini kepada siapa pun.\n\n"
                .'_Tim Keamanan Kasly_';

            $sentWa = FonnteService::send($targetPhone, $msg);
        }

        $flashMsg = 'Kode OTP pemulihan telah dibuat!';
        if ($sentWa) {
            $flashMsg .= " Silakan periksa pesan WhatsApp di nomor {$targetPhone}.";
        } else {
            $flashMsg .= ' Silakan masukkan kode OTP pada form di bawah ini.';
        }

        return redirect()->route('password.verify.form')->with('success', $flashMsg);
    }

    /**
     * Tampilkan form verifikasi kode OTP dan input kata sandi baru.
     */
    public function showVerifyOtpForm(): View|RedirectResponse
    {
        if (! session('password_reset_user_id') || ! session('password_reset_otp')) {
            return redirect()->route('password.request')->withErrors([
                'error' => 'Sesi permintaan reset kata sandi telah berakhir. Silakan ajukan ulang.',
            ]);
        }

        return view('auth.reset-password-otp', [
            'name' => session('password_reset_name'),
            'phone' => session('password_reset_phone'),
            'otpHint' => session('password_reset_otp'), // Memudahkan saat demo lokal
        ]);
    }

    /**
     * Verifikasi kode OTP dan simpan kata sandi baru.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $userId = session('password_reset_user_id');
        $savedOtp = session('password_reset_otp');
        $expires = session('password_reset_expires');

        if (! $userId || ! $savedOtp || ! $expires) {
            return redirect()->route('password.request')->withErrors([
                'error' => 'Sesi reset kata sandi telah kedaluwarsa. Silakan ajukan ulang.',
            ]);
        }

        if (time() > $expires) {
            session()->forget([
                'password_reset_user_id',
                'password_reset_org_id',
                'password_reset_otp',
                'password_reset_expires',
                'password_reset_phone',
                'password_reset_name',
            ]);

            return redirect()->route('password.request')->withErrors([
                'error' => 'Kode OTP telah kedaluwarsa (lebih dari 15 menit). Silakan minta kode baru.',
            ]);
        }

        $validated = $request->validate([
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'otp.required' => 'Kode OTP 6 digit wajib diisi.',
            'otp.size' => 'Kode OTP harus berupa 6 digit angka.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (trim($validated['otp']) !== (string) $savedOtp) {
            return back()->withInput()->withErrors([
                'otp' => 'Kode OTP yang Anda masukkan salah. Periksa kembali pesan Anda.',
            ]);
        }

        $user = User::find($userId);
        if (! $user) {
            return redirect()->route('password.request')->withErrors(['error' => 'Pengguna tidak ditemukan.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Catat di log aktivitas
        ActivityLog::create([
            'organization_id' => session('password_reset_org_id'),
            'user_id' => $user->id,
            'action' => 'password_reset_success',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'payload' => [
                'reset_at' => now()->toDateTimeString(),
                'ip' => $request->ip(),
            ],
        ]);

        // Notifikasi WA sukses ubah password jika ada nomor
        $phone = session('password_reset_phone');
        if (! empty($phone)) {
            $msg = "*PEMBERITAHUAN KEAMANAN KASLY*\n\n"
                ."Halo {$user->name},\n"
                .'Kata sandi akun Kasly Anda telah BERHASIL DIPERBARUI pada '.now()->format('d M Y H:i')." WIB.\n"
                ."Jika Anda tidak melakukan perubahan ini, segera hubungi Pengurus / Admin Ormawa Anda.\n\n"
                .'Tautan Login: '.url('/login');

            FonnteService::send($phone, $msg);
        }

        session()->forget([
            'password_reset_user_id',
            'password_reset_org_id',
            'password_reset_otp',
            'password_reset_expires',
            'password_reset_phone',
            'password_reset_name',
        ]);

        return redirect()->route('login')->with('success', 'Kata sandi akun Anda berhasil diperbarui! Silakan masuk menggunakan kata sandi baru.');
    }
}
