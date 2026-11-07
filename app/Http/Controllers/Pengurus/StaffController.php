<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Mail\StaffAccountCreatedMail;
use App\Models\ActivityLog;
use App\Models\Organization;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Tampilkan daftar seluruh staff & bendahara pada organisasi aktif.
     * Khusus hanya dapat diakses oleh Admin Organisasi (Ketua).
     */
    public function index(Request $request): View|RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        if (session('active_role') !== 'admin_organisasi' && ! Auth::user()->isSuperAdmin()) {
            return redirect()->route('pengurus.dashboard')
                ->withErrors(['error' => 'Akses ditolak: Hanya Admin Organisasi (Ketua) yang berwenang mengelola akun staf dan bendahara.']);
        }

        $staffMembers = $organization->users()
            ->wherePivotIn('role', ['admin_organisasi', 'bendahara', 'staff'])
            ->withPivot('role', 'is_active', 'created_at')
            ->get();

        return view('pengurus.staff.index', [
            'organization' => $organization,
            'staffMembers' => $staffMembers,
        ]);
    }

    /**
     * Daftarkan akun Admin Staff atau Bendahara baru oleh Admin Organisasi.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        if (session('active_role') !== 'admin_organisasi' && ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak: Hanya Admin Organisasi (Ketua) yang berwenang menambahkan staf baru.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25'],
            'role' => ['required', 'string', 'in:bendahara,staff'],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'name.required' => 'Nama lengkap staff/bendahara wajib diisi.',
            'username.required' => 'Username login wajib diisi.',
            'username.unique' => 'Username sudah digunakan akun lain.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor WhatsApp wajib diisi untuk pengiriman kredensial.',
            'role.required' => 'Peran staf wajib dipilih.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $password = ! empty($validated['password'])
            ? $validated['password']
            : Str::password(10, letters: true, numbers: true, symbols: true, spaces: false);

        $newStaff = null;

        DB::transaction(function () use ($validated, $password, $organization, &$newStaff) {
            $newStaff = User::create([
                'name' => $validated['name'],
                'username' => Str::lower($validated['username']),
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($password),
                'is_super_admin' => false,
            ]);

            $organization->users()->attach($newStaff->id, [
                'role' => $validated['role'],
                'is_active' => true,
            ]);

            ActivityLog::create([
                'organization_id' => $organization->id,
                'user_id' => Auth::id(),
                'action' => 'create_staff',
                'subject_type' => User::class,
                'subject_id' => $newStaff->id,
                'payload' => [
                    'name' => $newStaff->name,
                    'username' => $newStaff->username,
                    'role' => $validated['role'],
                ],
            ]);
        });

        $roleLabel = $validated['role'] === 'bendahara' ? 'Bendahara Ormawa' : 'Staff Operasional Kas';

        // 1. Dispatch Kredensial Otomatis via WhatsApp (Fonnte API)
        if (! empty($validated['phone'])) {
            $waMessage = "Halo {$validated['name']},\n\n"
                ."Anda telah didaftarkan sebagai *{$roleLabel}* untuk *{$organization->name}* di sistem Kasly.\n\n"
                ."Berikut adalah rincian kredensial login Anda:\n"
                ."• Kode Organisasi: *{$organization->code}*\n"
                ."• Username: *{$validated['username']}*\n"
                ."• Email: {$validated['email']}\n"
                ."• Kata Sandi: *{$password}*\n\n"
                ."Silakan masuk ke panel kerja Anda melalui tautan berikut:\n"
                .url('/login')."\n\n"
                ."Salam,\nTim Pengurus {$organization->name}";

            FonnteService::send($validated['phone'], $waMessage);
        }

        // 2. Dispatch Kredensial Cadangan via Email (SMTP)
        try {
            Mail::to($validated['email'])->send(new StaffAccountCreatedMail(
                organization: $organization,
                staffName: $validated['name'],
                staffEmail: $validated['email'],
                staffUsername: $validated['username'],
                staffPassword: $password,
                role: $validated['role']
            ));
        } catch (\Throwable $e) {
            // Log warning jika SMTP gagal, pendaftaran tetap berhasil
            report($e);
        }

        return redirect()->route('pengurus.staff.index')
            ->with('success', "Akun {$roleLabel} baru atas nama {$validated['name']} (@{$validated['username']}) berhasil ditambahkan dan kredensial telah dikirim via WhatsApp.");
    }

    /**
     * Ubah status aktif/nonaktif staff dalam organisasi.
     */
    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        if (session('active_role') !== 'admin_organisasi' && ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak: Hanya Admin Organisasi (Ketua) yang berwenang mengubah status staf.');
        }

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        $pivot = $organization->users()->where('users.id', $user->id)->first();
        if (! $pivot) {
            return back()->withErrors(['error' => 'User tidak terdaftar dalam kepengurusan organisasi ini.']);
        }

        $currentStatus = (bool) $pivot->pivot->is_active;
        $newStatus = ! $currentStatus;

        $organization->users()->updateExistingPivot($user->id, [
            'is_active' => $newStatus,
        ]);

        $statusText = $newStatus ? 'diaktifkan kembali' : 'dinonaktifkan';

        ActivityLog::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'action' => 'toggle_staff_status',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'payload' => ['new_status' => $newStatus],
        ]);

        return redirect()->route('pengurus.staff.index')
            ->with('success', "Status akun staf {$user->name} berhasil {$statusText}.");
    }

    /**
     * Hapus akun staf dari kepengurusan organisasi.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        if (session('active_role') !== 'admin_organisasi' && ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak: Hanya Admin Organisasi (Ketua) yang berwenang menghapus staf.');
        }

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri dari kepengurusan.']);
        }

        $userName = $user->name;

        DB::transaction(function () use ($organization, $user) {
            $organization->users()->detach($user->id);

            // Jika user tidak terhubung ke organisasi lain dan bukan admin pusat, bersihkan akun
            if ($user->organizations()->count() === 0 && ! $user->is_super_admin) {
                $user->delete();
            }
        });

        ActivityLog::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'action' => 'delete_staff',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'payload' => ['deleted_user_name' => $userName],
        ]);

        return redirect()->route('pengurus.staff.index')
            ->with('success', "Akun staf {$userName} berhasil dihapus dari kepengurusan {$organization->name}.");
    }
}
