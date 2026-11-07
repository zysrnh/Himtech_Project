<?php

namespace App\Http\Middleware;

use App\Models\Member;
use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationMember
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('anggota.login');
        }

        $orgId = session('active_organization_id');
        $memberId = session('active_member_id');

        $organization = null;
        $member = null;

        if ($orgId && $memberId) {
            $organization = Organization::where('id', $orgId)->where('status', 'active')->first();
            $member = Member::where('id', $memberId)->where('organization_id', $orgId)->where('is_active', true)->first();
        }

        if (! $organization || ! $member) {
            $user = Auth::user();
            $member = $user->member;
            if ($member && $member->is_active && $member->organization?->status === 'active') {
                $organization = $member->organization;
                session([
                    'active_organization_id' => $organization->id,
                    'active_member_id' => $member->id,
                    'active_role' => 'anggota',
                ]);
            }
        }

        if (! $organization || ! $member) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('anggota.login')->withErrors([
                'member_number' => 'Sesi anggota tidak valid atau organisasi sudah tidak aktif.',
            ]);
        }

        $request->attributes->set('current_organization', $organization);
        $request->attributes->set('current_member', $member);

        return $next($request);
    }
}
