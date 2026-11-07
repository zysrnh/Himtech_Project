<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\MidtransController;
use App\Models\ActivityLog;
use App\Models\DuesBill;
use App\Models\Member;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');
        /** @var Member $member */
        $member = $request->attributes->get('current_member');

        // Tagihan Saya
        $myBills = $member->duesBills()
            ->with(['duesPeriod', 'verifier'])
            ->latest('id')
            ->get();

        // Auto-sync status pembayaran Midtrans untuk tagihan unpaid yang memiliki order id
        $hasSynced = false;
        foreach ($myBills->where('status', 'unpaid') as $unpaidBill) {
            if (! empty($unpaidBill->midtrans_order_id)) {
                if (MidtransController::syncBillStatus($unpaidBill)) {
                    $hasSynced = true;
                }
            }
        }

        if ($hasSynced) {
            $myBills = $member->duesBills()
                ->with(['duesPeriod', 'verifier'])
                ->latest('id')
                ->get();
        }

        // Ringkasan Transparansi Kas Organisasi
        $totalIncome = (float) $organization->transactions()->where('type', 'in')->sum('amount');
        $totalExpense = (float) $organization->transactions()->where('type', 'out')->sum('amount');
        $currentBalance = $totalIncome - $totalExpense;

        $unpaidBills = $myBills->whereIn('status', ['unpaid', 'pending']);
        $totalUnpaidAmount = (float) $unpaidBills->sum('amount');

        // Data Kalender Kas Mingguan Anggota (Bulan Berjalan)
        $calendarDate = Carbon::now()->startOfMonth();
        $startOfMonth = $calendarDate->copy()->startOfMonth();
        $endOfMonth = $calendarDate->copy()->endOfMonth();

        $monthlyBills = $myBills->filter(function ($bill) use ($startOfMonth, $endOfMonth) {
            $dueDate = $bill->duesPeriod?->due_date;

            return $dueDate && $dueDate->gte($startOfMonth) && $dueDate->lte($endOfMonth);
        });

        $billsByDate = [];
        foreach ($monthlyBills as $bill) {
            $dateStr = $bill->duesPeriod->due_date->format('Y-m-d');
            $billsByDate[$dateStr] = [
                'id' => $bill->id,
                'period_title' => $bill->duesPeriod->title,
                'amount' => (float) $bill->amount,
                'status' => $bill->status,
                'due_date' => $bill->duesPeriod->due_date->format('d M Y'),
                'is_paid' => $bill->status === 'paid',
                'is_pending' => $bill->status === 'pending',
            ];
        }

        // 5 Mutasi Kas Organisasi Terbaru (Transparansi untuk Anggota)
        $publicTransactions = $organization->transactions()
            ->with(['category', 'creator'])
            ->latest('transaction_date')
            ->latest('id')
            ->take(5)
            ->get();

        // Metode Pembayaran Ormawa Aktif (Rekening Bank, E-Wallet, QRIS)
        $paymentMethods = $organization->paymentMethods()->where('is_active', true)->get();

        return view('anggota.dashboard', compact(
            'organization',
            'member',
            'currentBalance',
            'totalIncome',
            'totalExpense',
            'myBills',
            'unpaidBills',
            'totalUnpaidAmount',
            'publicTransactions',
            'paymentMethods',
            'calendarDate',
            'billsByDate'
        ));
    }

    /**
     * Anggota mengajukan konfirmasi pembayaran kas tunai secara langsung ke Bendahara (Menunggu ACC).
     */
    public function confirmCashPayment(Request $request, DuesBill $bill): RedirectResponse
    {
        /** @var Member|null $member */
        $member = Member::where('user_id', Auth::id())
            ->where('organization_id', $bill->organization_id)
            ->first();

        if (! $member || $bill->member_id !== $member->id) {
            abort(403, 'Akses pembayaran ini tidak sah untuk akun Anda.');
        }

        if ($bill->status === 'paid') {
            return back()->with('info', 'Tagihan kas ini sudah tercatat lunas sebelumnya.');
        }

        $request->validate([
            'confirm_cash' => ['required'],
        ], [
            'confirm_cash.required' => 'Centang kotak persetujuan serah terima uang tunai terlebih dahulu.',
        ]);

        $bill->update([
            'status' => 'pending',
            'payment_method' => 'cash',
            'payment_channel' => 'manual_cash',
        ]);

        ActivityLog::create([
            'organization_id' => $bill->organization_id,
            'user_id' => Auth::id(),
            'action' => 'claim_cash_payment',
            'subject_type' => DuesBill::class,
            'subject_id' => $bill->id,
            'payload' => [
                'member_id' => $bill->member_id,
                'amount' => $bill->amount,
                'period_title' => $bill->duesPeriod?->title,
            ],
        ]);

        return back()->with('success', 'Konfirmasi bayar tunai berhasil diajukan! Menunggu ACC & verifikasi dari Bendahara ormawa.');
    }
}
