<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
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

class MemberController extends Controller
{
    /**
     * Tampilkan halaman data anggota ormawa beserta kartu QR Code pendaftaran mandiri.
     */
    public function index(Request $request): View
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        // Ambil token registrasi aktif atau buatkan otomatis jika belum ada/kedaluwarsa
        $activeToken = $organization->activeRegistrationToken();

        if (! $activeToken) {
            $activeToken = MemberRegistrationToken::create([
                'organization_id' => $organization->id,
                'token' => 'kasly_'.Str::lower($organization->code).'_'.Str::random(12),
                'max_uses' => 100,
                'used_count' => 0,
                'expires_at' => now()->addDays(2),
                'created_by' => Auth::id(),
                'is_active' => true,
            ]);
        }

        $members = $organization->members()
            ->with('user')
            ->latest('id')
            ->paginate(15);

        $totalMembers = $organization->members()->count();
        $activeMembers = $organization->members()->where('is_active', true)->count();

        // Bangun URL registrasi resmi untuk disematkan ke dalam QR Code
        $registrationUrl = route('anggota.register.qr', ['token' => $activeToken->token]);

        return view('pengurus.anggota.index', [
            'organization' => $organization,
            'members' => $members,
            'activeToken' => $activeToken,
            'registrationUrl' => $registrationUrl,
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
        ]);
    }

    /**
     * Ubah kuota maksimal & perpanjang masa berlaku QR Code yang sedang aktif tanpa mengubah barcode/link.
     */
    public function updateToken(Request $request, MemberRegistrationToken $token): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        if ($token->organization_id !== $organization->id) {
            abort(403);
        }

        $validated = $request->validate([
            'max_uses' => ['required', 'integer', 'min:'.max(1, $token->used_count), 'max:2000'],
            'extend_hours' => ['required', 'integer', 'min:0', 'max:720'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'max_uses.required' => 'Batas kuota maksimal wajib diisi.',
            'max_uses.min' => "Kuota tidak boleh lebih kecil dari jumlah anggota yang sudah mendaftar ({$token->used_count} orang).",
            'max_uses.max' => 'Kuota maksimal 2000 anggota.',
            'extend_hours.required' => 'Waktu perpanjangan wajib ditentukan.',
        ]);

        $newExpiresAt = $token->expires_at;
        if ((int) $validated['extend_hours'] > 0) {
            $baseTime = $token->isExpired() ? now() : $token->expires_at;
            $newExpiresAt = $baseTime->addHours((int) $validated['extend_hours']);
        }

        $isActive = $request->boolean('is_active', true);

        $token->update([
            'max_uses' => (int) $validated['max_uses'],
            'expires_at' => $newExpiresAt,
            'is_active' => $isActive,
        ]);

        ActivityLog::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'action' => 'update_registration_qr',
            'subject_type' => MemberRegistrationToken::class,
            'subject_id' => $token->id,
            'payload' => [
                'max_uses' => $token->max_uses,
                'expires_at' => $token->expires_at->toIso8601String(),
                'is_active' => $token->is_active,
            ],
        ]);

        return redirect()->route('pengurus.anggota.index')
            ->with('success', "Pengaturan QR Code aktif berhasil diperbarui! Kuota diubah menjadi {$token->max_uses} anggota dan berlaku hingga {$token->expires_at->format('d M Y H:i')}. Barcode QR dan tautan yang sudah dibagikan di WhatsApp tetap berlaku.");
    }

    /**
     * Buat QR Code & Token Pendaftaran Baru (menggantikan barcode lama).
     */
    public function renewToken(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        $validated = $request->validate([
            'max_uses' => ['required', 'integer', 'min:1', 'max:1000'],
            'valid_duration_hours' => ['required', 'integer', 'min:1', 'max:720'],
        ], [
            'max_uses.required' => 'Batas maksimal kuota penggunaan wajib diisi.',
            'max_uses.min' => 'Kuota minimal 1 orang anggota.',
            'max_uses.max' => 'Kuota maksimal 1000 orang per batch QR.',
            'valid_duration_hours.required' => 'Durasi masa aktif wajib ditentukan.',
        ]);

        // Nonaktifkan token lama
        $organization->registrationTokens()->update(['is_active' => false]);

        // Buat token baru yang fresh
        $newToken = MemberRegistrationToken::create([
            'organization_id' => $organization->id,
            'token' => 'kasly_'.Str::lower($organization->code).'_'.Str::random(12),
            'max_uses' => (int) $validated['max_uses'],
            'used_count' => 0,
            'expires_at' => now()->addHours((int) $validated['valid_duration_hours']),
            'created_by' => Auth::id(),
            'is_active' => true,
        ]);

        ActivityLog::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'action' => 'renew_registration_qr',
            'subject_type' => MemberRegistrationToken::class,
            'subject_id' => $newToken->id,
            'payload' => [
                'token' => $newToken->token,
                'max_uses' => $newToken->max_uses,
                'expires_at' => $newToken->expires_at->toIso8601String(),
            ],
        ]);

        return redirect()->route('pengurus.anggota.index')
            ->with('success', "QR Code pendaftaran baru berhasil diterbitkan! Masa berlaku aktif {$validated['valid_duration_hours']} jam dengan kuota maksimal {$validated['max_uses']} anggota.");
    }

    /**
     * Tambah anggota secara manual oleh pengurus.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        $validated = $request->validate([
            'member_number' => ['required', 'string', 'max:50', 'alpha_num'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
        ], [
            'member_number.required' => 'Nomor Anggota / NIM wajib diisi.',
            'name.required' => 'Nama lengkap anggota wajib diisi.',
        ]);

        $nim = trim($validated['member_number']);

        if ($organization->members()->where('member_number', $nim)->exists()) {
            return back()->withErrors(['member_number' => "NIM/Nomor Anggota {$nim} sudah terdaftar di organisasi ini."])->withInput();
        }

        DB::transaction(function () use ($validated, $nim, $organization) {
            $user = User::where('username', Str::lower($nim))->first();

            if (! $user) {
                $user = User::create([
                    'name' => $validated['name'],
                    'username' => Str::lower($nim),
                    'phone' => $validated['phone'] ?? null,
                    'password' => Hash::make($nim),
                    'is_super_admin' => false,
                ]);
            }

            $member = Member::create([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'member_number' => $nim,
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => true,
            ]);

            $organization->users()->syncWithoutDetaching([
                $user->id => [
                    'role' => 'anggota',
                    'member_id' => $member->id,
                    'is_active' => true,
                ],
            ]);

            ActivityLog::create([
                'organization_id' => $organization->id,
                'user_id' => Auth::id(),
                'action' => 'manual_create_member',
                'subject_type' => Member::class,
                'subject_id' => $member->id,
                'payload' => [
                    'member_number' => $nim,
                    'name' => $validated['name'],
                ],
            ]);
        });

        return redirect()->route('pengurus.anggota.index')
            ->with('success', "Anggota baru {$validated['name']} ({$nim}) berhasil didaftarkan.");
    }

    /**
     * Perbarui data anggota (koreksi nama, NIM, atau nomor WhatsApp).
     */
    public function update(Request $request, Member $member): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        if ($member->organization_id !== $organization->id) {
            abort(403);
        }

        $validated = $request->validate([
            'member_number' => ['required', 'string', 'max:50', 'alpha_num'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
        ], [
            'member_number.required' => 'Nomor Anggota / NIM wajib diisi.',
            'name.required' => 'Nama lengkap anggota wajib diisi.',
        ]);

        $nim = trim($validated['member_number']);

        $exists = $organization->members()
            ->where('member_number', $nim)
            ->where('id', '!=', $member->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['member_number' => "NIM/Nomor Anggota {$nim} sudah digunakan oleh anggota lain di organisasi ini."])->withInput();
        }

        DB::transaction(function () use ($member, $validated, $nim, $organization) {
            $oldNim = $member->member_number;
            $oldName = $member->name;

            $member->update([
                'member_number' => $nim,
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
            ]);

            // Jika anggota memiliki akun user login, update username dan nama
            if ($member->user) {
                $member->user->update([
                    'name' => $validated['name'],
                    'username' => Str::lower($nim),
                    'phone' => $validated['phone'] ?? $member->user->phone,
                ]);
            }

            ActivityLog::create([
                'organization_id' => $organization->id,
                'user_id' => Auth::id(),
                'action' => 'update_member_data',
                'subject_type' => Member::class,
                'subject_id' => $member->id,
                'payload' => [
                    'old_nim' => $oldNim,
                    'new_nim' => $nim,
                    'old_name' => $oldName,
                    'new_name' => $validated['name'],
                ],
            ]);
        });

        return redirect()->route('pengurus.anggota.index')
            ->with('success', "Data anggota {$validated['name']} ({$nim}) berhasil diperbarui.");
    }

    /**
     * Ubah status aktif / nonaktif anggota.
     */
    public function toggleStatus(Member $member): RedirectResponse
    {
        $newStatus = ! $member->is_active;
        $member->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()->route('pengurus.anggota.index')
            ->with('success', "Status anggota {$member->name} ({$member->member_number}) berhasil {$statusText}.");
    }

    /**
     * Hapus anggota dari organisasi.
     */
    public function destroy(Member $member): RedirectResponse
    {
        $name = $member->name;
        $nim = $member->member_number;

        $member->delete();

        return redirect()->route('pengurus.anggota.index')
            ->with('success', "Data anggota {$name} ({$nim}) berhasil dihapus dari organisasi.");
    }
}
