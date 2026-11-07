<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StaffAccountCreatedMail;
use App\Models\ActivityLog;
use App\Models\CashCategory;
use App\Models\Organization;
use App\Models\OrganizationRequest;
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
     * Tampilkan halaman daftar seluruh organisasi terdaftar dan pengajuan masuk.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Silakan login sebagai Super Admin untuk mengakses panel pusat.',
            ]);
        }

        if (Auth::user()->isAdminPenerima()) {
            $activeTab = 'requests';
        } else {
            $activeTab = $request->query('tab', 'organizations');
        }

        $totalOrganizations = Organization::count();
        $activeOrganizations = Organization::where('status', 'active')->count();
        $inactiveOrganizations = Organization::where('status', 'inactive')->count();
        $pendingRequestsCount = OrganizationRequest::where('status', 'pending')->count();

        $organizations = Organization::latest()->paginate(10, ['*'], 'org_page');
        $pendingRequests = OrganizationRequest::where('status', 'pending')->latest()->paginate(10, ['*'], 'req_page');

        return view('admin.organizations.index', [
            'organizations' => $organizations,
            'pendingRequests' => $pendingRequests,
            'totalOrganizations' => $totalOrganizations,
            'activeOrganizations' => $activeOrganizations,
            'inactiveOrganizations' => $inactiveOrganizations,
            'pendingRequestsCount' => $pendingRequestsCount,
            'activeTab' => $activeTab,
        ]);
    }

    /**
     * Tampilkan formulir terpisah untuk pendaftaran organisasi baru secara manual.
     */
    public function create(): View|RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Silakan login sebagai Super Admin untuk mengakses panel pusat.',
            ]);
        }

        return view('admin.organizations.create');
    }

    /**
     * Simpan organisasi baru beserta akun staff awalnya, lalu blast via WhatsApp (Fonnte) & Email.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
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
        if (! empty($validated['staff_password'])) {
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

            // 3. Hubungkan di tabel pivot organization_user dengan role admin_organisasi
            $organization->users()->attach($staff->id, [
                'role' => 'admin_organisasi',
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
        if ($staff && ! empty($staff->phone)) {
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
        if ($staff && ! empty($staff->email)) {
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
                Log::warning("Gagal mengirim email kredensial staff ke [{$staff->email}]: ".$e->getMessage());
            }
        }

        return redirect()->route('admin.organizations.index')->with('success', "Organisasi {$validated['name']} ({$code}) berhasil didaftarkan. Kredensial akun staff telah dikirim via WhatsApp dan Email.");
    }

    /**
     * Setujui (ACC) pengajuan organisasi publik.
     */
    public function approveRequest(OrganizationRequest $organizationRequest): RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            abort(403, 'Akses ditolak.');
        }

        if ($organizationRequest->status !== 'pending') {
            return redirect()->route('admin.organizations.index', ['tab' => 'requests'])
                ->withErrors(['error' => 'Pengajuan ini sudah pernah diproses sebelumnya.']);
        }

        // Generate password acak kuat 10 karakter
        $password = Str::password(10, letters: true, numbers: true, symbols: true, spaces: false);
        $organization = null;
        $staff = null;

        DB::transaction(function () use ($organizationRequest, $password, &$organization, &$staff) {
            // 1. Buat Organisasi
            $organization = Organization::create([
                'name' => $organizationRequest->name,
                'code' => $organizationRequest->code,
                'description' => $organizationRequest->description,
                'status' => 'active',
            ]);

            // 2. Buat Akun Pengelola / Staff Awal
            $staff = User::create([
                'name' => $organizationRequest->pic_name,
                'username' => Str::lower($organizationRequest->pic_username),
                'email' => $organizationRequest->pic_email,
                'phone' => $organizationRequest->pic_phone,
                'password' => Hash::make($password),
                'is_super_admin' => false,
            ]);

            // 3. Hubungkan di tabel pivot organization_user sebagai admin_organisasi
            $organization->users()->attach($staff->id, [
                'role' => 'admin_organisasi',
                'is_active' => true,
            ]);

            // 4. Buatkan 7 Kategori Kas Bawaan
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

            // 5. Update Status Pengajuan
            $organizationRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // 6. Catat Log Aktivitas
            ActivityLog::create([
                'organization_id' => $organization->id,
                'user_id' => Auth::id(),
                'action' => 'approve_organization_request',
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

        // 7. Blast Kredensial via WhatsApp (Fonnte API)
        if ($staff && ! empty($staff->phone)) {
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

        // 8. Blast Kredensial via Email (SMTP)
        if ($staff && ! empty($staff->email)) {
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
                Log::warning("Gagal mengirim email kredensial staff ke [{$staff->email}]: ".$e->getMessage());
            }
        }

        return redirect()->route('admin.organizations.index', ['tab' => 'requests'])
            ->with('success', "Pengajuan organisasi {$organizationRequest->name} ({$organizationRequest->code}) berhasil DISETUJUI (ACC). Organisasi telah aktif dan kredensial akun Pengelola telah dikirimkan via WhatsApp dan Email.");
    }

    /**
     * Tolak pengajuan organisasi publik.
     */
    public function rejectRequest(Request $request, OrganizationRequest $organizationRequest): RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            abort(403, 'Akses ditolak.');
        }

        $reason = $request->input('rejection_reason', 'Data pengajuan belum memenuhi persyaratan verifikasi.');

        $organizationRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_by' => Auth::id(),
        ]);

        // Notifikasi Penolakan via WhatsApp
        if (! empty($organizationRequest->pic_phone)) {
            $msg = "Halo {$organizationRequest->pic_name},\n\n"
                ."Mohon maaf, pengajuan pendaftaran organisasi *{$organizationRequest->name} ({$organizationRequest->code})* belum dapat disetujui saat ini.\n\n"
                ."Alasan: {$reason}\n\n"
                ."Anda dapat mengajukan kembali dengan melengkapi data yang sesuai.\n\n"
                ."Salam,\nTim Kasly";
            FonnteService::send($organizationRequest->pic_phone, $msg);
        }

        return redirect()->route('admin.organizations.index', ['tab' => 'requests'])
            ->with('success', "Pengajuan organisasi {$organizationRequest->name} telah ditolak.");
    }

    /**
     * Ubah status aktif / nonaktif organisasi.
     */
    public function toggleStatus(Organization $organization): RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            abort(403, 'Akses ditolak.');
        }

        if (Auth::user()->isAdminPenerima()) {
            abort(403, 'Akses dibatasi: Akun Admin Penerima tidak memiliki izin mengubah status organisasi.');
        }

        $newStatus = $organization->status === 'active' ? 'inactive' : 'active';
        $organization->update(['status' => $newStatus]);

        $statusLabel = $newStatus === 'active' ? 'diaktifkan kembali' : 'dinonaktifkan';

        // Catat ke log aktivitas
        ActivityLog::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'action' => 'toggle_organization_status',
            'subject_type' => Organization::class,
            'subject_id' => $organization->id,
            'payload' => [
                'organization_name' => $organization->name,
                'organization_code' => $organization->code,
                'new_status' => $newStatus,
            ],
        ]);

        return redirect()->route('admin.organizations.index')->with('success', "Organisasi {$organization->name} ({$organization->code}) berhasil {$statusLabel}.");
    }

    /**
     * Hapus permanen organisasi beserta seluruh data terkait (anggota, iuran, kas).
     */
    public function destroy(Organization $organization): RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            abort(403, 'Akses ditolak.');
        }

        if (Auth::user()->isAdminPenerima()) {
            abort(403, 'Akses dibatasi: Akun Admin Penerima tidak memiliki izin menghapus organisasi.');
        }

        $orgName = $organization->name;
        $orgCode = $organization->code;

        DB::transaction(function () use ($organization, $orgName, $orgCode) {
            // Ambil daftar user id yang terhubung ke organisasi ini (bukan super admin)
            $affiliatedUserIds = $organization->users()
                ->where('is_super_admin', false)
                ->pluck('users.id')
                ->all();

            // Catat log penghapusan sebelum organisasi dihapus
            ActivityLog::create([
                'organization_id' => null,
                'user_id' => Auth::id(),
                'action' => 'delete_organization',
                'subject_type' => Organization::class,
                'subject_id' => $organization->id,
                'payload' => [
                    'organization_name' => $orgName,
                    'organization_code' => $orgCode,
                ],
            ]);

            // Hapus organisasi (foreign keys cascadeOnDelete otomatis menghapus members, organization_user, transactions, dues_periods, dues_bills, cash_categories)
            $organization->delete();

            // Bersihkan akun user yang tidak punya organisasi lain dan bukan super admin
            foreach ($affiliatedUserIds as $userId) {
                $user = User::find($userId);
                if ($user && $user->organizations()->count() === 0 && ! $user->is_super_admin) {
                    $user->delete();
                }
            }
        });

        return redirect()->route('admin.organizations.index')->with('success', "Organisasi {$orgName} ({$orgCode}) beserta seluruh data transaksi dan anggotanya berhasil dihapus permanen.");
    }
}
