<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\MidtransController;
use App\Models\Member;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            ->with('duesPeriod')
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
                ->with('duesPeriod')
                ->latest('id')
                ->get();
        }

        // Ringkasan Transparansi Kas Organisasi
        $totalIncome = (float) $organization->transactions()->where('type', 'in')->sum('amount');
        $totalExpense = (float) $organization->transactions()->where('type', 'out')->sum('amount');
        $currentBalance = $totalIncome - $totalExpense;

        $unpaidBills = $myBills->where('status', 'unpaid');
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
}
