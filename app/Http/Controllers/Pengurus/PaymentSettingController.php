<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Organization;
use App\Models\OrganizationPaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentSettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan metode pembayaran ormawa (Cash & Cashless).
     */
    public function index(Request $request): View
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        if (! $organization) {
            abort(404, 'Organisasi tidak ditemukan.');
        }

        // Ambil atau inisialisasi pengaturan metode Cash (Tunai)
        $cashMethod = $organization->paymentMethods()->where('type', 'cash')->first();
        if (! $cashMethod) {
            $cashMethod = $organization->paymentMethods()->create([
                'type' => 'cash',
                'channel_name' => 'Tunai (Cash)',
                'account_holder' => Auth::user()->name ?? 'Bendahara Organisasi',
                'instructions' => 'Pembayaran tunai langsung diserahkan kepada Bendahara di sekretariat ormawa atau saat kumpul rutin.',
                'is_active' => true,
            ]);
        }

        // Ambil seluruh metode Cashless Bank & E-Wallet
        $bankMethods = $organization->paymentMethods()
            ->whereIn('type', ['bank', 'ewallet'])
            ->latest('id')
            ->get();

        // Ambil QRIS Statis Ormawa
        $qrisMethod = $organization->paymentMethods()->where('type', 'qris')->first();

        // Status konfigurasi Midtrans
        $midtransConfigured = ! empty(config('services.midtrans.server_key'));

        return view('pengurus.pembayaran.index', compact(
            'organization',
            'cashMethod',
            'bankMethods',
            'qrisMethod',
            'midtransConfigured'
        ));
    }

    /**
     * Update pengaturan metode pembayaran tunai (Cash).
     */
    public function updateCash(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        $validated = $request->validate([
            'account_holder' => ['required', 'string', 'max:150'],
            'instructions' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'account_holder.required' => 'Nama Bendahara / PIC penerima tunai wajib diisi.',
        ]);

        $cashMethod = $organization->paymentMethods()->firstOrNew(['type' => 'cash']);
        $cashMethod->organization_id = $organization->id;
        $cashMethod->channel_name = 'Tunai (Cash)';
        $cashMethod->account_holder = $validated['account_holder'];
        $cashMethod->instructions = $validated['instructions'] ?? 'Pembayaran tunai langsung diserahkan kepada Bendahara saat kumpul rutin.';
        $cashMethod->is_active = $request->has('is_active');
        $cashMethod->save();

        ActivityLog::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'action' => 'update_cash_payment_setting',
            'subject_type' => OrganizationPaymentMethod::class,
            'subject_id' => $cashMethod->id,
            'payload' => [
                'is_active' => $cashMethod->is_active,
                'account_holder' => $cashMethod->account_holder,
            ],
        ]);

        return back()->with('success', 'Pengaturan metode pembayaran Tunai (Cash) berhasil disimpan.');
    }

    /**
     * Toggle saklar Midtrans Sandbox.
     */
    public function toggleMidtrans(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        $organization->is_midtrans_enabled = ! $organization->is_midtrans_enabled;
        $organization->save();

        $statusText = $organization->is_midtrans_enabled ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'action' => 'toggle_midtrans_gateway',
            'subject_type' => Organization::class,
            'subject_id' => $organization->id,
            'payload' => ['is_midtrans_enabled' => $organization->is_midtrans_enabled],
        ]);

        return back()->with('success', "Payment Gateway Midtrans Sandbox berhasil {$statusText}.");
    }

    /**
     * Tambah rekening Bank atau E-Wallet Cashless baru.
     */
    public function storeCashless(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:bank,ewallet'],
            'channel_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:100'],
            'account_holder' => ['required', 'string', 'max:150'],
            'instructions' => ['nullable', 'string', 'max:500'],
        ], [
            'type.required' => 'Pilih kategori (Bank atau E-Wallet).',
            'channel_name.required' => 'Nama bank atau e-wallet wajib diisi.',
            'account_number.required' => 'Nomor rekening atau nomor handphone wajib diisi.',
            'account_holder.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        OrganizationPaymentMethod::create([
            'organization_id' => $organization->id,
            'type' => $validated['type'],
            'channel_name' => $validated['channel_name'],
            'account_number' => $validated['account_number'],
            'account_holder' => $validated['account_holder'],
            'instructions' => $validated['instructions'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', "Metode transfer {$validated['channel_name']} berhasil ditambahkan.");
    }

    /**
     * Upload / Update QRIS Statis Ormawa.
     */
    public function updateQris(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        $validated = $request->validate([
            'channel_name' => ['required', 'string', 'max:100'],
            'qris_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'instructions' => ['nullable', 'string', 'max:500'],
        ], [
            'qris_image.required' => 'File gambar barcode QRIS wajib diunggah.',
            'qris_image.image' => 'File harus berupa gambar barcode.',
            'qris_image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $qrisMethod = $organization->paymentMethods()->firstOrNew(['type' => 'qris']);

        if ($qrisMethod->qris_image_path && Storage::disk('public')->exists($qrisMethod->qris_image_path)) {
            Storage::disk('public')->delete($qrisMethod->qris_image_path);
        }

        $path = $request->file('qris_image')->store('qris_methods', 'public');

        $qrisMethod->organization_id = $organization->id;
        $qrisMethod->type = 'qris';
        $qrisMethod->channel_name = $validated['channel_name'];
        $qrisMethod->account_holder = $organization->name;
        $qrisMethod->qris_image_path = $path;
        $qrisMethod->instructions = $validated['instructions'] ?? 'Scan QRIS di atas melalui m-Banking atau aplikasi e-wallet apa saja (GoPay, OVO, Dana, LinkAja).';
        $qrisMethod->is_active = true;
        $qrisMethod->save();

        return back()->with('success', 'Barcode QRIS Statis Ormawa berhasil diperbarui.');
    }

    /**
     * Hapus metode pembayaran cashless.
     */
    public function destroy(OrganizationPaymentMethod $paymentMethod): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = request()->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        if ($paymentMethod->organization_id !== $organization->id) {
            abort(403);
        }

        if ($paymentMethod->qris_image_path && Storage::disk('public')->exists($paymentMethod->qris_image_path)) {
            Storage::disk('public')->delete($paymentMethod->qris_image_path);
        }

        $name = $paymentMethod->channel_name;
        $paymentMethod->delete();

        return back()->with('success', "Metode pembayaran \"{$name}\" berhasil dihapus.");
    }
}
