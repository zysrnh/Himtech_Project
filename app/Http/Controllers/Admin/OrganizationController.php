<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CashCategory;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    /**
     * Simpan organisasi baru beserta akun bendahara awalnya.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::check() || !Auth::user()->is_super_admin) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'alpha_dash', 'unique:organizations,code'],
            'description' => ['nullable', 'string', 'max:1000'],
            'treasurer_name' => ['required', 'string', 'max:255'],
            'treasurer_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'treasurer_password' => ['nullable', 'string', 'min:6'],
        ]);

        $code = Str::upper($validated['code']);
        $password = $validated['treasurer_password'] ?: 'password';

        DB::transaction(function () use ($validated, $code, $password) {
            // 1. Simpan Organisasi
            $organization = Organization::create([
                'name' => $validated['name'],
                'code' => $code,
                'description' => $validated['description'] ?? null,
                'status' => 'active',
            ]);

            // 2. Buat Akun Pengurus / Bendahara Awal
            $treasurer = User::create([
                'name' => $validated['treasurer_name'],
                'email' => $validated['treasurer_email'],
                'password' => Hash::make($password),
                'is_super_admin' => false,
            ]);

            // 3. Hubungkan di tabel pivot organization_user
            $organization->users()->attach($treasurer->id, [
                'role' => 'bendahara',
                'is_active' => true,
            ]);

            // 4. Buatkan Kategori Kas Bawaan
            $defaultCategories = [
                ['name' => 'Iuran Wajib', 'type' => 'in'],
                ['name' => 'Donasi / Sponsor', 'type' => 'in'],
                ['name' => 'Kas Awal', 'type' => 'in'],
                ['name' => 'Konsumsi', 'type' => 'out'],
                ['name' => 'ATK & Perlengkapan', 'type' => 'out'],
                ['name' => 'Operasional Kegiatan', 'type' => 'out'],
                ['name' => 'Lain-lain', 'type' => 'out'],
            ];

            foreach ($defaultCategories as $category) {
                CashCategory::create([
                    'organization_id' => $organization->id,
                    'name' => $category['name'],
                    'type' => $category['type'],
                ]);
            }

            // 5. Catat Log Aktivitas
            ActivityLog::create([
                'organization_id' => $organization->id,
                'user_id' => Auth::id(),
                'action' => 'create_organization',
                'subject_type' => Organization::class,
                'subject_id' => $organization->id,
                'payload' => [
                    'organization_name' => $organization->name,
                    'organization_code' => $organization->code,
                    'treasurer_email' => $treasurer->email,
                ],
            ]);
        });

        return redirect()->route('admin.dashboard')->with('success', "Organisasi {$validated['name']} ({$code}) dan akun bendahara berhasil didaftarkan.");
    }
}
