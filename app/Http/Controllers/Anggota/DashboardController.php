<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Organization;
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

        // Ringkasan Transparansi Kas Organisasi
        $totalIncome = (float) $organization->transactions()->where('type', 'in')->sum('amount');
        $totalExpense = (float) $organization->transactions()->where('type', 'out')->sum('amount');
        $currentBalance = $totalIncome - $totalExpense;

        // Tagihan Saya
        $myBills = $member->duesBills()
            ->with('duesPeriod')
            ->latest('id')
            ->get();

        $unpaidBills = $myBills->where('status', 'unpaid');
        $totalUnpaidAmount = (float) $unpaidBills->sum('amount');

        // 5 Mutasi Kas Organisasi Terbaru (Transparansi untuk Anggota)
        $publicTransactions = $organization->transactions()
            ->with(['category', 'creator'])
            ->latest('transaction_date')
            ->latest('id')
            ->take(5)
            ->get();

        return view('anggota.dashboard', compact(
            'organization',
            'member',
            'currentBalance',
            'totalIncome',
            'totalExpense',
            'myBills',
            'unpaidBills',
            'totalUnpaidAmount',
            'publicTransactions'
        ));
    }
}
