<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Member;
use App\Models\Organization;
use App\Models\OrganizationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman overview sistem super admin.
     */
    public function index(): View|RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_super_admin) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Silakan login sebagai Super Admin untuk mengakses panel pusat.',
            ]);
        }

        $totalOrganizations = Organization::count();
        $activeOrganizations = Organization::where('status', 'active')->count();
        $totalMembers = Member::count();
        $pendingRequestsCount = OrganizationRequest::where('status', 'pending')->count();

        // 5 Pengajuan ormawa yang pending
        $pendingRequests = OrganizationRequest::where('status', 'pending')->latest()->take(5)->get();

        // 5 Organisasi yang baru didaftarkan
        $recentOrganizations = Organization::latest()->take(5)->get();

        // 5 Log aktivitas terbaru
        $recentActivities = ActivityLog::with('user', 'organization')->latest()->take(5)->get();

        return view('admin.dashboard', [
            'totalOrganizations' => $totalOrganizations,
            'activeOrganizations' => $activeOrganizations,
            'totalMembers' => $totalMembers,
            'pendingRequestsCount' => $pendingRequestsCount,
            'pendingRequests' => $pendingRequests,
            'recentOrganizations' => $recentOrganizations,
            'recentActivities' => $recentActivities,
        ]);
    }
}
