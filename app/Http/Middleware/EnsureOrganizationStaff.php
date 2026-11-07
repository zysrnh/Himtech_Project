<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('pengurus.login');
        }

        $user = Auth::user();

        // Admin Penerima hanya bertugas memproses pengajuan organisasi di panel admin pusat
        if ($user->isAdminPenerima()) {
            return redirect()->route('admin.organizations.index', ['tab' => 'requests']);
        }

        $orgId = session('active_organization_id');
        $organization = null;

        if ($orgId) {
            $organization = Organization::where('id', $orgId)
                ->where('status', 'active')
                ->first();
        }

        if (! $organization) {
            // Coba ambil organisasi pertama tempat user terdaftar sebagai pengurus (admin_organisasi, staff, bendahara)
            $orgUser = $user->organizations()
                ->where('organizations.status', 'active')
                ->wherePivotIn('role', ['admin_organisasi', 'bendahara', 'staff'])
                ->wherePivot('is_active', true)
                ->first();

            if ($orgUser) {
                $organization = $orgUser;
                session([
                    'active_organization_id' => $orgUser->id,
                    'active_role' => $orgUser->pivot->role,
                ]);
            } elseif ($user->isSuperAdmin()) {
                // Akses Super Admin untuk memantau portal organisasi
                $organization = Organization::where('status', 'active')->first();
                if ($organization) {
                    session([
                        'active_organization_id' => $organization->id,
                        'active_role' => 'staff',
                    ]);
                }
            }
        }

        if (! $organization) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('pengurus.login')->withErrors([
                'email' => 'Sesi tidak valid atau Anda tidak memiliki akses ke organisasi aktif.',
            ]);
        }

        // Simpan organisasi aktif ke atribut request agar controller mudah membaca
        $request->attributes->set('current_organization', $organization);

        return $next($request);
    }
}
