<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan formulir profil Super Admin.
     */
    public function showAdminProfile(): View|RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            return redirect()->route('admin.login');
        }

        return view('admin.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Perbarui data profil dan kontak notifikasi WhatsApp Super Admin.
     */
    public function updateAdminProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user || ! $user->is_super_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:25'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi sebagai nomor penerima alert notifikasi sistem Fonnte.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
        ]);

        $user->name = $validated['name'];
        if (! empty($validated['username'])) {
            $user->username = Str::lower($validated['username']);
        }
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profil Super Admin dan nomor WhatsApp penerima notifikasi sistem berhasil diperbarui.');
    }

    /**
     * Tampilkan formulir profil Pengurus / Pengelola Ormawa.
     */
    public function showPengurusProfile(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        return view('pengurus.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Perbarui data profil pengurus ormawa.
     */
    public function updatePengurusProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:25'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username login wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
        ]);

        $user->name = $validated['name'];
        $user->username = Str::lower($validated['username']);
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('pengurus.profile')->with('success', 'Profil kepengurusan berhasil diperbarui.');
    }

    /**
     * Tampilkan formulir profil Anggota Mahasiswa.
     */
    public function showAnggotaProfile(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $member = Member::where('user_id', $user->id)
            ->where('organization_id', session('active_organization_id'))
            ->first();

        return view('anggota.profile', [
            'user' => $user,
            'member' => $member,
        ]);
    }

    /**
     * Perbarui profil mandiri anggota mahasiswa (Nama, WA, Email, dan Ubah Password).
     */
    public function updateAnggotaProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:25'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'] ?? null;
        $user->phone = $validated['phone'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update data anggota terkait
        $member = Member::where('user_id', $user->id)
            ->where('organization_id', session('active_organization_id'))
            ->first();

        if ($member) {
            $member->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);
        }

        return redirect()->route('anggota.profile')->with('success', 'Profil dan kata sandi akun anggota Anda berhasil diperbarui.');
    }
}
