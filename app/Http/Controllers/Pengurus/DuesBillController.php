<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DuesBill;
use App\Models\DuesPeriod;
use App\Models\Organization;
use App\Models\Transaction;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DuesBillController extends Controller
{
    /**
     * Tampilkan halaman pengelolaan tagihan iuran kas, kalender mingguan, & verifikasi pelunasan.
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

        // Data Kalender Bulanan
        $monthQuery = $request->query('month', $selectedPeriod ? $selectedPeriod->due_date->format('Y-m') : now()->format('Y-m'));
        try {
            $calendarDate = Carbon::createFromFormat('Y-m', $monthQuery)->startOfMonth();
        } catch (\Throwable $e) {
            $calendarDate = now()->startOfMonth();
        }

        $startOfMonth = $calendarDate->copy()->startOfMonth();
        $endOfMonth = $calendarDate->copy()->endOfMonth();

        // Ambil semua periode kas di bulan ini beserta metrik kelunasannya
        $monthPeriods = $organization->duesPeriods()
            ->whereBetween('due_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->with(['duesBills'])
            ->get();

        // Group periods by due_date (Format: Y-m-d)
        $calendarPeriodsByDate = [];
        foreach ($monthPeriods as $mp) {
            $dateStr = $mp->due_date->format('Y-m-d');
            $paidCount = $mp->duesBills->where('status', 'paid')->count();
            $unpaidCount = $mp->duesBills->where('status', 'unpaid')->count();
            $totalCount = $mp->duesBills->count();

            $calendarPeriodsByDate[$dateStr][] = [
                'id' => $mp->id,
                'title' => $mp->title,
                'amount' => (float) $mp->amount,
                'paid_count' => $paidCount,
                'unpaid_count' => $unpaidCount,
                'total_count' => $totalCount,
                'is_all_paid' => $totalCount > 0 && $unpaidCount === 0,
            ];
        }

        // Query tagihan anggota pada periode terpilih
        $billsQuery = DuesBill::query()
            ->where('dues_bills.organization_id', $organization->id);

        if ($selectedPeriod) {
            $billsQuery->where('dues_bills.dues_period_id', $selectedPeriod->id);
        } else {
            $billsQuery->whereRaw('1 = 0');
        }

        // Filter status tagihan
        $statusFilter = $request->query('status', 'all');
        if ($statusFilter === 'paid_ontime') {
            $billsQuery->where('dues_bills.status', 'paid')->where('dues_bills.is_on_time', true);
        } elseif ($statusFilter === 'paid_late') {
            $billsQuery->where('dues_bills.status', 'paid')->where('dues_bills.is_on_time', false);
        } elseif ($statusFilter === 'pending') {
            $billsQuery->where('dues_bills.status', 'pending');
        } elseif ($statusFilter === 'unpaid') {
            $billsQuery->where('dues_bills.status', 'unpaid');
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
            'pending_count' => 0,
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
            $metrics['pending_count'] = $allPeriodBills->where('status', 'pending')->count();
            $metrics['unpaid_count'] = $allPeriodBills->where('status', 'unpaid')->count();
            $metrics['total_amount'] = (float) $allPeriodBills->sum('amount');
            $metrics['collected_amount'] = (float) $allPeriodBills->where('status', 'paid')->sum('amount');
            $metrics['uncollected_amount'] = (float) $allPeriodBills->whereIn('status', ['unpaid', 'pending'])->sum('amount');
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
            'statusFilter',
            'calendarDate',
            'calendarPeriodsByDate',
            'monthPeriods'
        ));
    }

    /**
     * Buat jadwal periode tagihan kas baru (Mendukung mode Tunggal atau Mingguan Otomatis Batch).
     */
    public function storePeriod(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        $scheduleType = $request->input('schedule_type', 'single');

        // MODE 1: MINGGUAN OTOMATIS (SET PER MINGGU / RECURRING DALAM BULAN)
        if ($scheduleType === 'weekly') {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'amount' => ['required', 'numeric', 'min:1000'],
                'month' => ['required', 'date_format:Y-m'],
                'day_of_week' => ['required', 'integer', 'between:1,7'], // 1=Senin, ..., 6=Sabtu, 7=Minggu
            ], [
                'title.required' => 'Nama kegiatan/kas mingguan wajib diisi.',
                'amount.required' => 'Nominal tagihan kas mingguan wajib diisi.',
                'month.required' => 'Bulan jadwal kas mingguan wajib dipilih.',
                'day_of_week.required' => 'Hari rutin kas mingguan wajib ditentukan.',
            ]);

            $startMonth = Carbon::createFromFormat('Y-m', $validated['month'])->startOfMonth();
            $endMonth = $startMonth->copy()->endOfMonth();
            $dayOfWeek = (int) $validated['day_of_week'];

            // Cari semua tanggal yang jatuh pada hari tersebut di bulan ini
            $targetDates = [];
            $cur = $startMonth->copy();
            while ($cur->lte($endMonth)) {
                if ($cur->dayOfWeekIso === $dayOfWeek) {
                    $targetDates[] = $cur->copy();
                }
                $cur->addDay();
            }

            if (empty($targetDates)) {
                return back()->withInput()->withErrors(['day_of_week' => 'Tidak ditemukan hari yang dipilih dalam bulan ini.']);
            }

            DB::beginTransaction();
            try {
                $members = $organization->members()->where('is_active', true)->get();
                $createdCount = 0;
                $lastPeriodId = null;
                $now = now();

                foreach ($targetDates as $index => $targetDate) {
                    $weekNum = $index + 1;
                    $periodTitle = "{$validated['title']} - M{$weekNum} (".$targetDate->format('d M Y').')';

                    $period = DuesPeriod::create([
                        'organization_id' => $organization->id,
                        'title' => $periodTitle,
                        'amount' => $validated['amount'],
                        'due_date' => $targetDate->toDateString(),
                        'created_by' => Auth::id(),
                    ]);

                    $lastPeriodId = $period->id;
                    $createdCount++;

                    $billsData = [];
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
                }

                ActivityLog::create([
                    'organization_id' => $organization->id,
                    'user_id' => Auth::id(),
                    'action' => 'create_weekly_dues_batch',
                    'subject_type' => DuesPeriod::class,
                    'subject_id' => $lastPeriodId,
                    'payload' => [
                        'title' => $validated['title'],
                        'amount' => $validated['amount'],
                        'month' => $validated['month'],
                        'day_of_week' => $dayOfWeek,
                        'total_weeks_generated' => $createdCount,
                    ],
                ]);

                DB::commit();

                return redirect()->route('pengurus.tagihan.index', [
                    'period_id' => $lastPeriodId,
                    'month' => $validated['month'],
                ])->with('success', "Berhasil menerbitkan {$createdCount} jadwal kas mingguan untuk bulan {$startMonth->isoFormat('MMMM Y')}.");
            } catch (\Throwable $e) {
                DB::rollBack();

                return back()->withInput()->withErrors(['title' => 'Gagal membuat jadwal kas mingguan: '.$e->getMessage()]);
            }
        }

        // MODE 2: TUNGGAL (SEKALI BUAT)
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'due_date' => ['required', 'date'],
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

            return redirect()->route('pengurus.tagihan.index', [
                'period_id' => $period->id,
                'month' => $period->due_date->format('Y-m'),
            ])->with('success', "Periode tagihan \"{$period->title}\" berhasil dibuat dan {$members->count()} tagihan telah diterbitkan ke anggota.");
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

            // Otomatis kirim notifikasi tanda terima resmi via WhatsApp Fonnte ke anggota
            $memberPhone = $bill->member->phone ?? $bill->member->user?->phone;
            if (! empty($memberPhone)) {
                $methodLabel = match ($validated['payment_method']) {
                    'cash' => 'Tunai / Cash',
                    'transfer' => 'Transfer Bank',
                    'qris' => 'QRIS',
                    default => 'Manual',
                };

                FonnteService::sendPaymentSuccess(
                    $memberPhone,
                    $bill->member->name,
                    $organization->name,
                    $bill->duesPeriod->title,
                    (float) $bill->amount,
                    now()->format('d M Y H:i'),
                    $methodLabel,
                    $isOnTime
                );
            }

            $statusText = $isOnTime ? 'Tepat Waktu' : 'Terlambat';

            return back()->with('success', "Pembayaran iuran {$bill->member->name} berhasil diverifikasi lunas ({$statusText}) dan mutasi kas masuk telah dicatat.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal memverifikasi pembayaran: '.$e->getMessage()]);
        }
    }

    /**
     * Kirim pengingat tagihan kas personal via WhatsApp Fonnte ke anggota yang belum bayar.
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

        $dueDate = $bill->duesPeriod->due_date;
        $now = now()->startOfDay();
        $daysLeft = (int) $now->diffInDays($dueDate, false);

        $sent = FonnteService::sendDuesReminder(
            $phone,
            $bill->member->name,
            $organization->name,
            $bill->duesPeriod->title,
            (float) $bill->amount,
            $dueDate->format('d M Y'),
            $daysLeft
        );

        if ($sent) {
            return back()->with('success', "Pengingat WhatsApp berhasil dikirim ke nomor {$bill->member->name} ({$phone}).");
        }

        return back()->with('info', "Pesan pengingat telah disiapkan dan dicatat ke log sistem (nomor: {$phone}).");
    }

    /**
     * Broadcast pengingat tagihan WhatsApp secara massal ke seluruh anggota yang belum lunas pada periode ini.
     */
    public function broadcastPeriodReminders(Request $request, DuesPeriod $period): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        if ($period->organization_id !== $organization->id) {
            abort(403);
        }

        $unpaidBills = $period->duesBills()
            ->whereIn('status', ['unpaid', 'pending'])
            ->with(['member.user'])
            ->get();

        if ($unpaidBills->isEmpty()) {
            return back()->with('info', 'Semua anggota pada periode ini sudah lunas! Tidak ada tagihan tertunda.');
        }

        $dueDate = $period->due_date;
        $now = now()->startOfDay();
        $daysLeft = (int) $now->diffInDays($dueDate, false);

        $sentCount = 0;
        $skippedCount = 0;

        foreach ($unpaidBills as $bill) {
            $phone = $bill->member->phone ?? $bill->member->user?->phone;
            if (! empty($phone)) {
                $sent = FonnteService::sendDuesReminder(
                    $phone,
                    $bill->member->name,
                    $organization->name,
                    $period->title,
                    (float) $bill->amount,
                    $dueDate->format('d M Y'),
                    $daysLeft
                );
                if ($sent) {
                    $sentCount++;
                }
            } else {
                $skippedCount++;
            }
        }

        ActivityLog::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'action' => 'broadcast_dues_reminder',
            'subject_type' => DuesPeriod::class,
            'subject_id' => $period->id,
            'payload' => [
                'period_title' => $period->title,
                'total_unpaid' => $unpaidBills->count(),
                'sent_count' => $sentCount,
                'skipped_no_phone' => $skippedCount,
            ],
        ]);

        $msg = "Broadcast pengingat tagihan berhasil diproses ke {$unpaidBills->count()} anggota penunggak ({$sentCount} pesan WhatsApp Fonnte terkirim).";
        if ($skippedCount > 0) {
            $msg .= " ({$skippedCount} anggota dilewati karena nomor HP belum diisi).";
        }

        return back()->with('success', $msg);
    }
}
