@props([
    'active' => 'dashboard',
    'organization' => null,
])

@php
    $org = $organization ?? (session('active_organization_id') ? \App\Models\Organization::find(session('active_organization_id')) : null);
    $user = Auth::user();
    $role = session('active_role', 'staff');
    $roleName = match($role) {
        'admin_organisasi' => 'Admin Organisasi',
        'bendahara' => 'Bendahara',
        default => 'Staff Ormawa',
    };
    if ($user && $user->is_super_admin && !session('active_role')) {
        $roleName = 'Super Admin';
    }
    $isFinanceActive = in_array($active, ['dashboard', 'tagihan', 'buku-kas']);
    $isOrmawaActive = in_array($active, ['anggota', 'staff']);
@endphp

<aside class="w-[260px] neu-sidebar flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen z-30 select-none">
    <div class="flex flex-col min-h-0 flex-1">
        <!-- Brand & Organization Code Header -->
        <div class="h-16 px-5 flex items-center justify-between border-b border-ice-200/70 shrink-0">
            <a href="{{ route('pengurus.dashboard') }}" class="inline-flex items-center gap-2" title="{{ config('app.name', 'Kasly') }}">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-8 w-auto">
            </a>
            @if ($org)
                <span class="neu-badge-ice" title="Kode Organisasi">
                    {{ $org->code }}
                </span>
            @endif
        </div>

        <!-- Identitas Organisasi Aktif (Neumorphic Inset Card) -->
        <div class="px-4 pt-3 pb-2 shrink-0">
            <div class="neu-sidebar-card p-3">
                <div class="flex items-center justify-between gap-1 mb-1">
                    <span class="text-[10px] font-bold text-navy-500 uppercase tracking-wider">Organisasi Aktif</span>
                    <span class="neu-badge-pill text-[9.5px] py-0.5 px-2 whitespace-nowrap shrink-0">
                        {{ $roleName }}
                    </span>
                </div>
                <p class="text-xs font-bold text-navy-900 truncate" title="{{ $org->name ?? 'Kasly Ormawa' }}">
                    {{ $org->name ?? 'Kasly Ormawa' }}
                </p>
            </div>
        </div>

        <!-- Navigasi Menu (Scrollable) -->
        <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-1.5 custom-scrollbar">
            @if ($user && $user->is_super_admin)
                <a
                    href="{{ $user->isAdminPenerima() ? route('admin.organizations.index', ['tab' => 'requests']) : route('admin.dashboard') }}"
                    class="flex items-center justify-between h-9 px-3 rounded-xl bg-butter-100 text-navy-900 font-semibold text-xs border border-ice-200/70 shadow-xs hover:bg-butter-100/80 transition-colors mb-2"
                >
                    <span class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Panel Admin Kasly</span>
                    </span>
                    <span class="text-[9px] bg-white/70 px-1.5 py-0.2 rounded font-mono font-bold">SA</span>
                </a>
            @endif

            <!-- Group 1: Kas & Keuangan (Dropdown Interaktif) -->
            <div class="neu-dropdown-group" data-sidebar-dropdown>
                <button
                    type="button"
                    class="neu-nav-item w-full justify-between {{ $isFinanceActive ? 'text-navy-900 font-semibold' : '' }}"
                    data-sidebar-toggle
                >
                    <span class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Kas & Keuangan</span>
                    </span>
                    <svg class="w-3.5 h-3.5 text-navy-500 transition-transform duration-200 shrink-0" data-sidebar-icon fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="neu-dropdown-content space-y-1 mt-1 pl-1" data-sidebar-content>
                    <a
                        href="{{ route('pengurus.dashboard') }}"
                        class="neu-nav-subitem {{ $active === 'dashboard' ? 'is-active' : '' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'dashboard' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span>Overview Kas</span>
                    </a>
                    <a
                        href="{{ route('pengurus.tagihan.index') }}"
                        class="neu-nav-subitem {{ $active === 'tagihan' ? 'is-active' : '' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'tagihan' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span>Tagihan Iuran</span>
                    </a>
                    <a
                        href="{{ route('pengurus.dashboard') }}#buku-kas"
                        class="neu-nav-subitem {{ $active === 'buku-kas' ? 'is-active' : '' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'buku-kas' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span>Buku Kas Mutasi</span>
                    </a>
                </div>
            </div>

            <!-- Group 2: Kelola Ormawa (Dropdown Interaktif) -->
            <div class="neu-dropdown-group pt-1" data-sidebar-dropdown>
                <button
                    type="button"
                    class="neu-nav-item w-full justify-between {{ $isOrmawaActive ? 'text-navy-900 font-semibold' : '' }}"
                    data-sidebar-toggle
                >
                    <span class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Kelola Ormawa</span>
                    </span>
                    <svg class="w-3.5 h-3.5 text-navy-500 transition-transform duration-200 shrink-0" data-sidebar-icon fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="neu-dropdown-content space-y-1 mt-1 pl-1" data-sidebar-content>
                    <a
                        href="{{ route('pengurus.anggota.index') }}"
                        class="neu-nav-subitem {{ $active === 'anggota' ? 'is-active' : '' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'anggota' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span>Data Anggota</span>
                    </a>
                    @if (session('active_role') === 'admin_organisasi' || ($user && $user->isSuperAdmin()))
                        <a
                            href="{{ route('pengurus.staff.index') }}"
                            class="neu-nav-subitem {{ $active === 'staff' ? 'is-active' : '' }}"
                        >
                            <span class="w-1.5 h-1.5 rounded-full {{ $active === 'staff' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                            <span>Kelola Staff</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Single Menu Items: Profil Saya & Beranda Web -->
            <div class="pt-2 border-t border-ice-200/50 space-y-1">
                <a
                    href="{{ route('pengurus.profile') }}"
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
                    alt="{{ $user->name ?? 'User' }}"
                    class="w-9 h-9 rounded-full object-cover bg-ice-100"
                >
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-navy-900 truncate">
                    {{ $user->name ?? 'Pengurus' }}
                </p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="neu-badge-pill text-[9px] py-0.2 px-1.5 whitespace-nowrap shrink-0">
                        {{ $roleName }}
                    </span>
                    <span class="text-[10px] text-navy-500 truncate" title="{{ $user->email ?? $user->username }}">
                        {{ '@' . ($user->username ?? Str::before($user->email ?? '', '@')) }}
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('pengurus.logout') }}" method="POST">
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

