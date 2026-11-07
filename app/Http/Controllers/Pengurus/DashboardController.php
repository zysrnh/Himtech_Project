<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization');

        // Perhitungan Saldo Kas Organisasi
        $totalIncome = (float) $organization->transactions()->where('type', 'in')->sum('amount');
        $totalExpense = (float) $organization->transactions()->where('type', 'out')->sum('amount');
        $currentBalance = $totalIncome - $totalExpense;

        // Metrik Kedisiplinan Pembayaran Iuran Tepat Waktu (Tema Lomba)
        $paidBillsCount = $organization->duesBills()->where('status', 'paid')->count();
        $onTimeBillsCount = $organization->duesBills()->where('status', 'paid')->where('is_on_time', true)->count();
        $onTimeRate = $paidBillsCount > 0 ? (int) round(($onTimeBillsCount / $paidBillsCount) * 100) : 100;

        // Metrik Anggota & Tunggakan
        $totalMembers = $organization->members()->where('is_active', true)->count();
        $unpaidBillsCount = $organization->duesBills()->where('status', 'unpaid')->count();
        $unpaidBillsAmount = (float) $organization->duesBills()->where('status', 'unpaid')->sum('amount');

        // 5 Transaksi Kas Terakhir
        $recentTransactions = $organization->transactions()
            ->with(['category', 'creator'])
            ->latest('transaction_date')
            ->latest('id')
            ->take(5)
            ->get();

        // Periode Iuran Terkini
        $activePeriods = $organization->duesPeriods()
            ->withCount([
                'duesBills as total_bills_count',
                'duesBills as paid_bills_count' => fn ($query) => $query->where('status', 'paid'),
                'duesBills as unpaid_bills_count' => fn ($query) => $query->where('status', 'unpaid'),
            ])
            ->latest('due_date')
            ->take(3)
            ->get();

        return view('pengurus.dashboard', compact(
            'organization',
            'currentBalance',
            'totalIncome',
            'totalExpense',
            'onTimeRate',
            'paidBillsCount',
            'onTimeBillsCount',
            'totalMembers',
            'unpaidBillsCount',
            'unpaidBillsAmount',
            'recentTransactions',
            'activePeriods'
        ));
    }
}

