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

        // Metrik Realisasi Target Kas Iuran
        $totalBillsAmount = (float) $organization->duesBills()->sum('amount');
        $paidBillsAmount = (float) $organization->duesBills()->where('status', 'paid')->sum('amount');
        $realizationRate = $totalBillsAmount > 0 ? (int) round(($paidBillsAmount / $totalBillsAmount) * 100) : 100;

        // Metrik Anggota & Tunggakan
        $totalMembers = $organization->members()->where('is_active', true)->count();
        $unpaidBillsCount = $organization->duesBills()->where('status', 'unpaid')->count();
        $unpaidBillsAmount = (float) $organization->duesBills()->where('status', 'unpaid')->sum('amount');

        // Statistik Tren Arus Kas 6 Bulan Terakhir (Statistik Bendahara)
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyTrends = [];
        $now = now();
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            $monthLabel = $monthNames[$month - 1];

            $income = (float) $organization->transactions()
                ->where('type', 'in')
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->sum('amount');

            $expense = (float) $organization->transactions()
                ->where('type', 'out')
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->sum('amount');

            $monthlyTrends[] = [
                'label' => $monthLabel,
                'year' => $year,
                'income' => $income,
                'expense' => $expense,
            ];
        }

        // Breakdown Kategori Pengeluaran Teratas (Statistik Belanja Bendahara)
        $expenseCategories = $organization->transactions()
            ->where('type', 'out')
            ->with('category')
            ->get()
            ->groupBy(fn ($tx) => $tx->category?->name ?? 'Operasional & Lainnya')
            ->map(function ($items, $categoryName) use ($totalExpense) {
                $amount = (float) $items->sum('amount');
                $pct = $totalExpense > 0 ? (int) round(($amount / $totalExpense) * 100) : 0;

                return [
                    'name' => $categoryName,
                    'amount' => $amount,
                    'percentage' => $pct,
                ];
            })
            ->sortByDesc('amount')
            ->values()
            ->take(4);

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
            'totalBillsAmount',
            'paidBillsAmount',
            'realizationRate',
            'totalMembers',
            'unpaidBillsCount',
            'unpaidBillsAmount',
            'monthlyTrends',
            'expenseCategories',
            'recentTransactions',
            'activePeriods'
        ));
    }
}
