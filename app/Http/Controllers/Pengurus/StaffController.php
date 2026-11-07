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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Tampilkan daftar seluruh staff dan bendahara ormawa aktif.
     */
    public function index(Request $request): View
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

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

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25'],
            'role' => ['required', 'string', 'in:bendahara,staff,admin_organisasi'],
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

        // Blast Kredensial via WhatsApp (Fonnte API)
        if ($newStaff && ! empty($newStaff->phone)) {
            FonnteService::sendStaffAccountCreated(
                phone: $newStaff->phone,
                staffName: $newStaff->name,
                orgName: $organization->name,
                orgCode: $organization->code,
                email: $newStaff->email,
                password: $password,
                username: $newStaff->username
            );
        }

        // Blast Kredensial via Email (SMTP)
        if ($newStaff && ! empty($newStaff->email)) {
            try {
                Mail::to($newStaff->email)->send(new StaffAccountCreatedMail(
                    staffName: $newStaff->name,
                    orgName: $organization->name,
                    orgCode: $organization->code,
                    email: $newStaff->email,
                    password: $password,
                    loginUrl: url('/login'),
                    username: $newStaff->username
                ));
            } catch (\Throwable $e) {
                Log::warning("Gagal mengirim email kredensial staf ke [{$newStaff->email}]: ".$e->getMessage());
            }
        }

        $roleLabel = match ($validated['role']) {
            'bendahara' => 'Bendahara',
            'admin_organisasi' => 'Admin Organisasi',
            default => 'Staff',
        };

        return redirect()->route('pengurus.staff.index')
            ->with('success', "Akun {$roleLabel} baru atas nama {$validated['name']} ({$validated['username']}) berhasil dibuat dan kredensial login telah dikirimkan via WhatsApp dan Email.");
    }

    /**
     * Ubah status aktif / nonaktif staf dalam organisasi.
     */
    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri saat sedang login.']);
        }

        $currentPivot = $organization->users()->where('users.id', $user->id)->first()?->pivot;
        if (! $currentPivot) {
            abort(404);
        }

        $newStatus = ! $currentPivot->is_active;
        $organization->users()->updateExistingPivot($user->id, [
            'is_active' => $newStatus,
        ]);

        $statusText = $newStatus ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()->route('pengurus.staff.index')
            ->with('success', "Akun staf {$user->name} berhasil {$statusText}.");
    }

    /**
     * Hapus akun staf dari kepengurusan organisasi.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.']);
        }

        $userName = $user->name;

        DB::transaction(function () use ($organization, $user) {
            $organization->users()->detach($user->id);

            // Bersihkan jika user tidak terafiliasi dengan organisasi lain dan bukan super admin
            if ($user->organizations()->count() === 0 && ! $user->is_super_admin) {
                $user->delete();
            }
        });

        return redirect()->route('pengurus.staff.index')
            ->with('success', "Akun staf {$userName} berhasil dihapus dari kepengurusan {$organization->name}.");
    }
}
