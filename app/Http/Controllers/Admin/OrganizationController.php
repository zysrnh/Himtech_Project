<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StaffAccountCreatedMail;
use App\Models\ActivityLog;
use App\Models\CashCategory;
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

class OrganizationController extends Controller
{
    /**
     * Tampilkan halaman daftar organisasi lengkap & form penambahan.
     */
    public function index(): View|RedirectResponse
    {
        if (!Auth::check() || !Auth::user()->is_super_admin) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Silakan login sebagai Super Admin untuk mengakses panel pusat.',
            ]);
        }

        $totalOrganizations = Organization::count();
        $activeOrganizations = Organization::where('status', 'active')->count();
        $organizations = Organization::latest()->paginate(10);

        return view('admin.organizations.index', [
            'organizations' => $organizations,
            'totalOrganizations' => $totalOrganizations,
            'activeOrganizations' => $activeOrganizations,
        ]);
    }

    /**
     * Simpan organisasi baru beserta akun staff awalnya, lalu blast via WhatsApp (Fonnte) & Email.
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
            'staff_name' => ['required', 'string', 'max:255'],
            'staff_username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'staff_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'staff_phone' => ['required', 'string', 'max:25'],
            'staff_password' => ['nullable', 'string', 'min:8'],
        ], [
            'staff_username.required' => 'Username staff awal wajib diisi.',
            'staff_username.unique' => 'Username staff sudah digunakan akun lain.',
            'staff_username.alpha_dash' => 'Username hanya boleh berupa huruf, angka, dan strip/garis bawah.',
            'staff_password.min' => 'Kata sandi staff awal minimal 8 karakter kombinasi.',
        ]);

        $code = Str::upper($validated['code']);

        // Gunakan password yang diisi, atau generate password kuat acak jika dikosongkan
        if (!empty($validated['staff_password'])) {
            $password = $validated['staff_password'];
        } else {
            $password = Str::password(10, letters: true, numbers: true, symbols: true, spaces: false);
        }

        $organization = null;
        $staff = null;

        DB::transaction(function () use ($validated, $code, $password, &$organization, &$staff) {
            // 1. Simpan Organisasi
            $organization = Organization::create([
                'name' => $validated['name'],
                'code' => $code,
                'description' => $validated['description'] ?? null,
                'status' => 'active',
            ]);

            // 2. Buat Akun Pengurus / Staff Awal dengan Username & Nomor WhatsApp
            $staff = User::create([
                'name' => $validated['staff_name'],
                'username' => Str::lower($validated['staff_username']),
                'email' => $validated['staff_email'],
                'phone' => $validated['staff_phone'],
                'password' => Hash::make($password),
                'is_super_admin' => false,
            ]);

            // 3. Hubungkan di tabel pivot organization_user dengan role staff
            $organization->users()->attach($staff->id, [
                'role' => 'staff',
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
                    'staff_username' => $staff->username,
                    'staff_email' => $staff->email,
                    'staff_phone' => $staff->phone,
                ],
            ]);
        });

        // 6. Blast Kredensial via WhatsApp (Fonnte API)
        if ($staff && !empty($staff->phone)) {
            FonnteService::sendStaffAccountCreated(
                phone: $staff->phone,
                staffName: $staff->name,
                orgName: $organization->name,
                orgCode: $organization->code,
                email: $staff->email,
                password: $password,
                username: $staff->username
            );
        }

        // 7. Blast Kredensial via Email (SMTP)
        if ($staff && !empty($staff->email)) {
            try {
                Mail::to($staff->email)->send(new StaffAccountCreatedMail(
                    staffName: $staff->name,
                    orgName: $organization->name,
                    orgCode: $organization->code,
                    email: $staff->email,
                    password: $password,
                    loginUrl: url('/login'),
                    username: $staff->username
                ));
            } catch (\Throwable $e) {
                Log::warning("Gagal mengirim email kredensial staff ke [{$staff->email}]: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.organizations.index')->with('success', "Organisasi {$validated['name']} ({$code}) berhasil didaftarkan. Kredensial akun staff telah dikirim via WhatsApp dan Email.");
    }
}
