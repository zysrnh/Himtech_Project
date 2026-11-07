<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard panel pusat super admin.
     */
    public function index(): View|RedirectResponse
    {
        if (!Auth::check() || !Auth::user()->is_super_admin) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Silakan login sebagai Super Admin untuk mengakses panel pusat.',
            ]);
        }

        $totalOrganizations = Organization::count();
        $activeOrganizations = Organization::where('status', 'active')->count();
        $totalMembers = Member::count();

        $organizations = Organization::latest()->paginate(10);

        return view('admin.dashboard', [
            'totalOrganizations' => $totalOrganizations,
            'activeOrganizations' => $activeOrganizations,
            'totalMembers' => $totalMembers,
            'organizations' => $organizations,
        ]);
    }
}

