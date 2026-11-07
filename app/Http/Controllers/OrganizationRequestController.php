<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationRequest;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrganizationRequestController extends Controller
{
    /**
     * Tampilkan formulir publik pengajuan pendaftaran organisasi baru.
     */
    public function showForm(): View
    {
        return view('auth.register_organization');
    }

    /**
     * Simpan pengajuan pendaftaran organisasi baru dari publik.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'alpha_dash'],
            'description' => ['nullable', 'string', 'max:1000'],
            'pic_name' => ['required', 'string', 'max:255'],
            'pic_username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'pic_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'pic_phone' => ['required', 'string', 'max:25'],
        ], [
            'name.required' => 'Nama organisasi wajib diisi.',
            'code.required' => 'Usulan kode organisasi wajib diisi.',
            'code.alpha_dash' => 'Kode organisasi hanya boleh berupa huruf, angka, dan strip/garis bawah.',
            'pic_name.required' => 'Nama lengkap pemohon/ketua wajib diisi.',
            'pic_username.required' => 'Username login yang diinginkan wajib diisi.',
            'pic_username.unique' => 'Username sudah digunakan oleh akun lain.',
            'pic_username.alpha_dash' => 'Username hanya boleh berupa huruf, angka, dan strip/garis bawah.',
            'pic_email.required' => 'Email pemohon wajib diisi.',
            'pic_email.unique' => 'Email sudah terdaftar di sistem.',
            'pic_phone.required' => 'Nomor WhatsApp pemohon wajib diisi.',
        ]);

        $code = Str::upper($validated['code']);

        // Cek apakah kode sudah digunakan oleh organisasi aktif
        if (Organization::where('code', $code)->exists()) {
            return back()->withInput()->withErrors([
                'code' => "Kode organisasi '{$code}' sudah digunakan oleh organisasi yang telah terdaftar.",
            ]);
        }

        // Cek apakah ada pengajuan dengan kode yang sama yang masih pending
        if (OrganizationRequest::where('code', $code)->where('status', 'pending')->exists()) {
            return back()->withInput()->withErrors([
                'code' => "Kode organisasi '{$code}' sedang dalam proses peninjauan sebelumnya.",
            ]);
        }

        // Simpan permohonan ke database
        $orgRequest = OrganizationRequest::create([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'pic_name' => $validated['pic_name'],
            'pic_username' => Str::lower($validated['pic_username']),
            'pic_email' => $validated['pic_email'],
            'pic_phone' => $validated['pic_phone'],
            'status' => 'pending',
        ]);

        // 1. Notifikasi WhatsApp ke Pemohon
        $applicantMsg = "Halo {$validated['pic_name']},\n\n"
            ."Terima kasih! Pengajuan pendaftaran organisasi *{$validated['name']} ({$code})* telah kami terima di platform Kasly.\n\n"
            ."Status saat ini: *Menunggu Peninjauan Super Admin*\n"
            ."Begitu disetujui (ACC), kata sandi akun Pengelola akan otomatis dikirimkan ke WhatsApp dan Email Anda.\n\n"
            ."Salam,\nTim Kasly";
        FonnteService::send($validated['pic_phone'], $applicantMsg);

        // 2. Notifikasi WhatsApp ke Super Admin
        $superAdmin = User::where('is_super_admin', true)->whereNotNull('phone')->first();
        if ($superAdmin && ! empty($superAdmin->phone)) {
            $adminMsg = "Halo Super Admin Kasly,\n\n"
                ."Ada pengajuan pendaftaran ormawa baru yang masuk:\n"
                ."• Organisasi: *{$validated['name']}* ({$code})\n"
                ."• Pemohon/Ketua: *{$validated['pic_name']}*\n"
                ."• WhatsApp: {$validated['pic_phone']}\n"
                ."• Email: {$validated['pic_email']}\n\n"
                ."Silakan tinjau dan setujui (ACC) melalui Panel Super Admin:\n"
                .url('/admin/organizations');
            FonnteService::send($superAdmin->phone, $adminMsg);
        }

        return redirect()->route('ajukan-organisasi.success')->with([
            'org_name' => $validated['name'],
            'org_code' => $code,
            'pic_name' => $validated['pic_name'],
            'pic_phone' => $validated['pic_phone'],
        ]);
    }

    /**
     * Tampilkan halaman konfirmasi sukses pengajuan.
     */
    public function success(): View
    {
        return view('auth.register_organization_success');
    }
}

