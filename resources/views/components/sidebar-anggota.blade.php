@props([
    'active' => 'dashboard',
    'organization' => null,
    'member' => null,
])

@php
    $user = Auth::user();
    $org = $organization ?? (session('active_organization_id') ? \App\Models\Organization::find(session('active_organization_id')) : null);
    $mem = $member ?? ($user && $org ? \App\Models\Member::where('user_id', $user->id)->where('organization_id', $org->id)->first() : null);
    $isKasActive = in_array($active, ['dashboard', 'tagihan', 'buku-kas']);
@endphp

<aside class="w-[260px] neu-sidebar flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen z-30 select-none">
    <div class="flex flex-col min-h-0 flex-1">
        <!-- Brand & Organization Code Header -->
        <div class="h-16 px-5 flex items-center justify-between border-b border-ice-200/70 shrink-0">
            <a href="{{ route('anggota.dashboard') }}" class="inline-flex items-center gap-2" title="{{ config('app.name', 'Kasly') }}">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-8 w-auto">
            </a>
            @if ($org)
                <span class="neu-badge-ice" title="Kode Organisasi">
                    {{ $org->code }}
                </span>
            @endif
        </div>

        <!-- Identitas Portal Anggota (Neumorphic Inset Card) -->
        <div class="px-4 pt-3 pb-2 shrink-0">
            <div class="neu-sidebar-card p-3">
                <div class="flex items-center justify-between gap-1 mb-1">
                    <span class="text-[10px] font-bold text-navy-500 uppercase tracking-wider">Portal Anggota</span>
                    <span class="neu-badge-pill text-[9.5px] py-0.5 px-2 whitespace-nowrap shrink-0">
                        Anggota
                    </span>
                </div>
                <p class="text-xs font-bold text-navy-900 truncate" title="{{ $org->name ?? 'Kasly Ormawa' }}">
                    {{ $org->name ?? 'Kasly Ormawa' }}
                </p>
            </div>
        </div>

        <!-- Navigasi Menu (Scrollable) -->
        <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-1.5 custom-scrollbar">
            <!-- Group: Iuran & Transparansi (Dropdown Interaktif) -->
            <div class="neu-dropdown-group" data-sidebar-dropdown>
                <button
                    type="button"
                    class="neu-nav-item w-full justify-between {{ $isKasActive ? 'text-navy-900 font-semibold' : '' }}"
                    data-sidebar-toggle
                >
                    <span class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span>Iuran & Transparansi</span>
                    </span>
                    <svg class="w-3.5 h-3.5 text-navy-500 transition-transform duration-200 shrink-0" data-sidebar-icon fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="neu-dropdown-content space-y-1 mt-1 pl-1" data-sidebar-content>
                    <a
                        href="{{ route('anggota.dashboard') }}"
                        class="neu-nav-subitem {{ $active === 'dashboard' ? 'is-active' : '' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'dashboard' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span>Overview Kas Saya</span>
                    </a>
                    <a
                        href="{{ route('anggota.dashboard') }}#tagihan"
                        class="neu-nav-subitem {{ $active === 'tagihan' ? 'is-active' : '' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'tagihan' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span>Tagihan Iuran Saya</span>
                    </a>
                    <a
                        href="{{ route('anggota.dashboard') }}#buku-kas"
                        class="neu-nav-subitem {{ $active === 'buku-kas' ? 'is-active' : '' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'buku-kas' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span>Transparansi Buku Kas</span>
                    </a>
                </div>
            </div>

            <!-- Single Menu Items: Profil Saya & Beranda Web -->
            <div class="pt-2 border-t border-ice-200/50 space-y-1">
                <a
                    href="{{ route('anggota.profile') }}"
                    class="neu-nav-item {{ $active === 'profile' ? 'is-active' : '' }}"
                >
                    <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profil Saya</span>
                </a>
                <a
                    href="/"
                    target="_blank"
                    class="neu-nav-item"
                >
                    <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Lihat Beranda Web</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- User Profile & Logout Footer -->
    <div class="p-3 border-t border-ice-200/70 bg-white/40 shrink-0">
        <div class="flex items-center gap-2.5 mb-2.5 px-1">
            <div class="neu-avatar-ring shrink-0">
                <img
                    src="{{ $user ? $user->avatarUrl() : asset('logo/logo-light.svg') }}"
                    alt="{{ $mem->name ?? ($user->name ?? 'Anggota') }}"
                    class="w-9 h-9 rounded-full object-cover bg-ice-100"
                >
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-navy-900 truncate" title="{{ $mem->name ?? ($user->name ?? 'Anggota') }}">
                    {{ $mem->name ?? ($user->name ?? 'Anggota') }}
                </p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="neu-badge-pill text-[9px] py-0.2 px-1.5">
                        ANGGOTA
                    </span>
                    <span class="text-[10px] text-navy-500 font-mono truncate" title="{{ $mem->member_number ?? '-' }}">
                        {{ $mem->member_number ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('anggota.logout') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="w-full h-8 flex items-center justify-center gap-1.5 rounded-lg bg-ice-100/90 text-navy-900 font-semibold text-xs border border-ice-200/80 shadow-[inset_1px_1px_2px_rgba(255,255,255,0.9),1px_1px_3px_rgba(122,163,214,0.25)] hover:bg-ice-200/70 active:shadow-[inset_2px_2px_4px_rgba(122,163,214,0.35)] transition-all cursor-pointer"
            >
                <svg class="w-3.5 h-3.5 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar Sesi</span>
            </button>
        </form>
    </div>
</aside>

