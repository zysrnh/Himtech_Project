<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DuesBill;
use App\Models\DuesPeriod;
use App\Models\Organization;
use App\Models\OrganizationPaymentMethod;
use App\Models\Transaction;
use App\Services\FonnteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DuesBillController extends Controller
{
    /**
     * Tampilkan halaman pengelolaan tagihan iuran kas & metode pembayaran ormawa.
     */
    public function index(Request $request): View
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        if (! $organization) {
            abort(404, 'Organisasi tidak ditemukan.');
        }

        // Ambil seluruh periode tagihan kas ormawa
        $periods = $organization->duesPeriods()->latest('due_date')->latest('id')->get();

        // Periode terpilih (default periode terbaru)
        $selectedPeriodId = $request->query('period_id', $periods->first()?->id);
        $selectedPeriod = $periods->firstWhere('id', (int) $selectedPeriodId);

        // Query tagihan anggota pada periode terpilih
        $billsQuery = DuesBill::query()
            ->where('organization_id', $organization->id);

        if ($selectedPeriod) {
            $billsQuery->where('dues_period_id', $selectedPeriod->id);
        } else {
            $billsQuery->whereRaw('1 = 0');
        }

        // Filter status tagihan
        $statusFilter = $request->query('status', 'all');
        if ($statusFilter === 'paid_ontime') {
            $billsQuery->where('status', 'paid')->where('is_on_time', true);
        } elseif ($statusFilter === 'paid_late') {
            $billsQuery->where('status', 'paid')->where('is_on_time', false);
        } elseif ($statusFilter === 'unpaid') {
            $billsQuery->where('status', 'unpaid');
        }

        $bills = $billsQuery->with(['member.user', 'verifier'])
            ->join('members', 'members.id', '=', 'dues_bills.member_id')
            ->orderBy('members.member_number', 'asc')
            ->select('dues_bills.*')
            ->paginate(20)
            ->withQueryString();

        // Statistik periode terpilih
        $metrics = [
            'total_members' => $organization->members()->where('is_active', true)->count(),
            'total_bills' => 0,
            'paid_count' => 0,
            'ontime_count' => 0,
            'late_count' => 0,
            'unpaid_count' => 0,
            'total_amount' => 0,
            'collected_amount' => 0,
            'uncollected_amount' => 0,
            'ontime_rate' => 100,
        ];

        if ($selectedPeriod) {
            $allPeriodBills = DuesBill::where('organization_id', $organization->id)
                ->where('dues_period_id', $selectedPeriod->id)
                ->get();

            $metrics['total_bills'] = $allPeriodBills->count();
            $metrics['paid_count'] = $allPeriodBills->where('status', 'paid')->count();
            $metrics['ontime_count'] = $allPeriodBills->where('status', 'paid')->where('is_on_time', true)->count();
            $metrics['late_count'] = $allPeriodBills->where('status', 'paid')->where('is_on_time', false)->count();
            $metrics['unpaid_count'] = $allPeriodBills->where('status', 'unpaid')->count();
            $metrics['total_amount'] = (float) $allPeriodBills->sum('amount');
            $metrics['collected_amount'] = (float) $allPeriodBills->where('status', 'paid')->sum('amount');
            $metrics['uncollected_amount'] = (float) $allPeriodBills->where('status', 'unpaid')->sum('amount');
            $metrics['ontime_rate'] = $metrics['paid_count'] > 0
                ? (int) round(($metrics['ontime_count'] / $metrics['paid_count']) * 100)
                : 100;
        }

        // Ambil metode pembayaran mandiri organisasi
        $paymentMethods = $organization->paymentMethods()->latest('id')->get();

        return view('pengurus.tagihan.index', compact(
            'organization',
            'periods',
            'selectedPeriod',
            'bills',
            'metrics',
            'paymentMethods',
            'statusFilter'
        ));
    }

    /**
     * Buat jadwal periode tagihan kas baru dan terbitkan tagihan massal ke seluruh anggota aktif.
     */
    public function storePeriod(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'due_date' => ['required', 'date'],
            'auto_generate' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Nama periode tagihan kas wajib diisi.',
            'amount.required' => 'Nominal tagihan kas wajib diisi.',
            'amount.min' => 'Nominal tagihan minimal Rp 1.000.',
            'due_date.required' => 'Batas tanggal jatuh tempo wajib ditentukan.',
        ]);

        DB::beginTransaction();
        try {
            $period = DuesPeriod::create([
                'organization_id' => $organization->id,
                'title' => $validated['title'],
                'amount' => $validated['amount'],
                'due_date' => $validated['due_date'],
                'created_by' => Auth::id(),
            ]);

            // Terbitkan tagihan ke seluruh anggota aktif
            $members = $organization->members()->where('is_active', true)->get();
            $billsData = [];
            $now = now();

            foreach ($members as $member) {
                $billsData[] = [
                    'organization_id' => $organization->id,
                    'dues_period_id' => $period->id,
                    'member_id' => $member->id,
                    'amount' => $period->amount,
                    'status' => 'unpaid',
                    'is_on_time' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (! empty($billsData)) {
                DuesBill::insert($billsData);
            }

            ActivityLog::create([
                'organization_id' => $organization->id,
                'user_id' => Auth::id(),
                'action' => 'create_dues_period',
                'subject_type' => DuesPeriod::class,
                'subject_id' => $period->id,
                'payload' => [
                    'title' => $period->title,
                    'amount' => $period->amount,
                    'due_date' => $period->due_date->format('Y-m-d'),
                    'total_bills_generated' => count($billsData),
                ],
            ]);

            DB::commit();

            return redirect()->route('pengurus.tagihan.index', ['period_id' => $period->id])
                ->with('success', "Periode tagihan \"{$period->title}\" berhasil dibuat dan {$members->count()} tagihan telah diterbitkan ke anggota.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['title' => 'Gagal membuat periode tagihan: '.$e->getMessage()]);
        }
    }

    /**
     * Verifikasi pelunasan tagihan kas anggota secara manual (Tunai / Transfer Bank).
     */
    public function verifyPayment(Request $request, DuesBill $bill): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        if ($bill->organization_id !== $organization->id) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'in:cash,transfer,qris,other'],
        ]);

        if ($bill->status === 'paid') {
            return back()->with('info', 'Tagihan ini sudah tercatat lunas sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Evaluasi kedisiplinan ketepatan waktu: bayar <= due_date
            $dueDate = $bill->duesPeriod->due_date->endOfDay();
            $isOnTime = now()->lte($dueDate);

            $bill->status = 'paid';
            $bill->is_on_time = $isOnTime;
            $bill->paid_at = now();
            $bill->verified_by = Auth::id();
            $bill->payment_method = $validated['payment_method'];
            $bill->payment_channel = 'manual_'.$validated['payment_method'];
            $bill->save();

            // Otomatis catat transaksi kas masuk di Buku Kas
            $category = $organization->cashCategories()
                ->where('type', 'in')
                ->where('name', 'Iuran Wajib')
                ->first()
                ?? $organization->cashCategories()->where('type', 'in')->first();

            Transaction::create([
                'organization_id' => $organization->id,
                'type' => 'in',
                'category_id' => $category?->id,
                'amount' => $bill->amount,
                'transaction_date' => now()->toDateString(),
                'description' => "Iuran {$bill->duesPeriod->title} - {$bill->member->name} ({$bill->member->member_number})",
                'created_by' => Auth::id(),
                'is_locked' => true,
            ]);

            ActivityLog::create([
                'organization_id' => $organization->id,
                'user_id' => Auth::id(),
                'action' => 'verify_bill_payment',
                'subject_type' => DuesBill::class,
                'subject_id' => $bill->id,
                'payload' => [
                    'member_id' => $bill->member_id,
                    'member_name' => $bill->member->name,
                    'amount' => $bill->amount,
                    'is_on_time' => $isOnTime,
                    'payment_method' => $validated['payment_method'],
                ],
            ]);

            DB::commit();

            $statusText = $isOnTime ? 'Tepat Waktu' : 'Terlambat';

            return back()->with('success', "Pembayaran iuran {$bill->member->name} berhasil diverifikasi lunas ({$statusText}) dan mutasi kas masuk telah dicatat.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal memverifikasi pembayaran: '.$e->getMessage()]);
        }
    }

    /**
     * Kirim pengingat tagihan kas via WhatsApp Fonnte ke anggota yang belum bayar.
     */
    public function sendReminder(Request $request, DuesBill $bill): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        if ($bill->organization_id !== $organization->id) {
            abort(403);
        }

        $phone = $bill->member->phone ?? $bill->member->user?->phone;

        if (empty($phone)) {
            return back()->withErrors(['error' => "Nomor WhatsApp untuk anggota {$bill->member->name} belum diisi."]);
        }

        $formattedAmount = 'Rp '.number_format($bill->amount, 0, ',', '.');
        $dueDateFormatted = $bill->duesPeriod->due_date->format('d M Y');

        $message = "Halo, {$bill->member->name}.\n\n"
            ."Ini adalah pengingat resmi pembayaran iuran kas dari {$organization->name}.\n\n"
            ."*Rincian Tagihan:*\n"
            ."- Periode: {$bill->duesPeriod->title}\n"
            ."- Jumlah: {$formattedAmount}\n"
            ."- Batas Jatuh Tempo: {$dueDateFormatted}\n\n"
            ."Status saat ini: *Belum Lunas*.\n"
            ."Mohon segera melakukan pembayaran agar catatan pembayaran Anda tetap dinilai *Tepat Waktu*.\n\n"
            .'Pembayaran dapat ditransfer ke rekening bendahara atau bayar instan di portal: '.url('/login')."\n\n"
            ."Terima kasih.\n_Bendahara {$organization->name}_";

        $sent = FonnteService::send($phone, $message);

        if ($sent) {
            return back()->with('success', "Pengingat WhatsApp berhasil dikirim ke nomor {$bill->member->name} ({$phone}).");
        }

        return back()->with('info', "Pesan pengingat telah disiapkan dan dicatat ke log sistem (nomor: {$phone}).");
    }

    /**
     * Tambah metode pembayaran mandiri organisasi (Rekening Bank, E-Wallet, atau QRIS Statis).
     */
    public function storePaymentMethod(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:bank,ewallet,qris'],
            'channel_name' => ['required', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'account_holder' => ['nullable', 'string', 'max:150'],
            'instructions' => ['nullable', 'string', 'max:500'],
            'qris_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'type.required' => 'Pilih jenis metode pembayaran.',
            'channel_name.required' => 'Nama bank atau e-wallet wajib diisi.',
            'qris_image.image' => 'File QRIS harus berupa gambar.',
            'qris_image.max' => 'Ukuran gambar QRIS maksimal 2MB.',
        ]);

        $qrisPath = null;
        if ($request->hasFile('qris_image')) {
            $qrisPath = $request->file('qris_image')->store('qris_methods', 'public');
        }

        OrganizationPaymentMethod::create([
            'organization_id' => $organization->id,
            'type' => $validated['type'],
            'channel_name' => $validated['channel_name'],
            'account_number' => $validated['account_number'] ?? null,
            'account_holder' => $validated['account_holder'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'qris_image_path' => $qrisPath,
            'is_active' => true,
        ]);

        return back()->with('success', "Metode pembayaran \"{$validated['channel_name']}\" berhasil ditambahkan.");
    }

    /**
     * Hapus metode pembayaran mandiri organisasi.
     */
    public function destroyPaymentMethod(OrganizationPaymentMethod $paymentMethod): RedirectResponse
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

    /**
     * Saklar pengaktifan Midtrans Sandbox ormawa.
     */
    public function toggleMidtrans(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        $organization->is_midtrans_enabled = ! $organization->is_midtrans_enabled;
        $organization->save();

        $statusText = $organization->is_midtrans_enabled ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pembayaran otomatis via Midtrans Sandbox berhasil {$statusText}.");
    }
}
