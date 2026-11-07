<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    /**
     * Tampilkan daftar seluruh akun Administrator Kasly.
     */
    public function index(): View|RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            return redirect()->route('admin.login');
        }

        $admins = User::where('is_super_admin', true)->latest()->paginate(10);
        $pendingRequestsCount = OrganizationRequest::where('status', 'pending')->count();

        return view('admin.admins.index', [
            'admins' => $admins,
            'pendingRequestsCount' => $pendingRequestsCount,
        ]);
    }

    /**
     * Daftarkan akun Administrator Kasly baru.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan akun lain.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => Str::lower($validated['username']),
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_super_admin' => true,
        ]);

        return redirect()->route('admin.admins.index')->with('success', "Akun Administrator Kasly baru {$validated['name']} ({$validated['username']}) berhasil ditambahkan.");
    }

    /**
     * Hapus akun Administrator Kasly.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            abort(403);
        }

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.']);
        }

        $totalAdmins = User::where('is_super_admin', true)->count();
        if ($totalAdmins <= 1) {
            return back()->withErrors(['error' => 'Sistem membutuhkan minimal 1 Administrator Kasly. Akun ini tidak dapat dihapus.']);
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.admins.index')->with('success', "Akun Administrator Kasly {$name} berhasil dihapus.");
    }
}
