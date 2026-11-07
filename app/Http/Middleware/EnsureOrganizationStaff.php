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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('pengurus.login');
        }

        $user = Auth::user();

        $orgId = session('active_organization_id');
        $organization = null;

        if ($orgId) {
            $organization = Organization::where('id', $orgId)
                ->where('status', 'active')
                ->first();
        }

        if (!$organization) {
            // Coba ambil organisasi pertama tempat user terdaftar sebagai staff/bendahara
            $orgUser = $user->organizations()
                ->where('organizations.status', 'active')
                ->wherePivotIn('role', ['staff', 'bendahara'])
                ->wherePivot('is_active', true)
                ->first();

            if ($orgUser) {
                $organization = $orgUser;
                session([
                    'active_organization_id' => $orgUser->id,
                    'active_role' => $orgUser->pivot->role,
                ]);
            } elseif ($user->is_super_admin) {
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

        if (!$organization) {
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
