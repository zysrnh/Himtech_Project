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
    public function index(Request $request): View|RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.organizations.index', ['tab' => 'requests'])
                ->withErrors(['error' => 'Hanya Super Admin Kasly yang memiliki hak akses kelola administrator.']);
        }

        $search = $request->query('search');
        $query = User::where('is_super_admin', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $admins = $query->latest()->paginate(10)->withQueryString();
        $totalAdmins = User::where('is_super_admin', true)->count();
        $pendingRequestsCount = OrganizationRequest::where('status', 'pending')->count();

        return view('admin.admins.index', [
            'admins' => $admins,
            'totalAdmins' => $totalAdmins,
            'search' => $search,
            'pendingRequestsCount' => $pendingRequestsCount,
        ]);
    }

    /**
     * Daftarkan akun Administrator Kasly baru.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak.');
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
        if (! Auth::check() || ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak.');
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
