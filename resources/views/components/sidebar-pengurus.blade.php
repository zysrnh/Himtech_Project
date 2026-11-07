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
    $isFinanceActive = in_array($active, ['dashboard', 'tagihan', 'pembayaran', 'buku-kas']);
    $isOrmawaActive = in_array($active, ['anggota', 'staff']);
@endphp

<aside
    id="sidebar-pengurus"
    data-app-sidebar
    class="w-[260px] neu-sidebar flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen z-40 select-none transition-all duration-300 ease-in-out"
>
    <div class="flex flex-col min-h-0 flex-1">
        <!-- Brand Header (Logo Kasly & Tombol Collapse/Expand) -->
        <div class="h-20 px-3 flex items-center justify-between border-b border-ice-200/70 shrink-0 sidebar-header-box">
            <!-- Full Logo saat expanded -->
            <a href="{{ route('pengurus.dashboard') }}" class="sidebar-brand-full inline-flex items-center justify-center flex-1 pl-3" title="{{ config('app.name', 'Kasly') }}">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-11 w-auto max-w-[170px] object-contain">
            </a>

            <!-- Tombol Toggle Collapse/Expand Sidebar -->
            <button
                type="button"
                onclick="toggleAppSidebar()"
                class="sidebar-toggle-btn p-1.5 rounded-lg text-navy-500 hover:text-navy-900 hover:bg-ice-200/70 cursor-pointer transition-colors shrink-0"
                title="Sembunyikan / Tampilkan Menu"
            >
                <svg id="icon-chevron-collapse" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                <svg id="icon-chevron-expand" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Identitas Organisasi -->
        <div class="px-3 pt-3.5 pb-3 border-b border-ice-200/60 shrink-0 sidebar-org-box">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-7 h-7 rounded-lg bg-ice-200/70 text-navy-700 flex items-center justify-center shrink-0" title="{{ $org->name ?? 'Kasly Ormawa' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <div class="sidebar-org-text min-w-0 flex-1">
                    <p class="text-xs font-bold text-navy-900 truncate" title="{{ $org->name ?? 'Kasly Ormawa' }}">
                        {{ $org->name ?? 'Kasly Ormawa' }}
                    </p>
                    <span class="text-[11px] font-medium text-navy-500">
                        {{ $roleName }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigasi Menu (Scrollable) -->
        <nav class="flex-1 overflow-y-auto px-2.5 py-2 space-y-1.5 custom-scrollbar">
            @if ($user && $user->is_super_admin)
                <a
                    href="{{ $user->isAdminPenerima() ? route('admin.organizations.index', ['tab' => 'requests']) : route('admin.dashboard') }}"
                    class="flex items-center justify-between h-9 px-3 rounded-xl bg-butter-100 text-navy-900 font-semibold text-xs border border-ice-200/70 shadow-xs hover:bg-butter-100/80 transition-colors mb-2"
                    title="Panel Admin Kasly"
                >
                    <span class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-navy-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="sidebar-text truncate">Panel Admin Kasly</span>
                    </span>
                    <span class="sidebar-text text-[9px] bg-white/70 px-1.5 py-0.2 rounded font-mono font-bold">SA</span>
                </a>
            @endif

            <!-- Group 1: Kas & Keuangan (Dropdown Interaktif) -->
            <div class="neu-dropdown-group" data-sidebar-dropdown>
                <button
                    type="button"
                    class="neu-nav-item w-full justify-between {{ $isFinanceActive ? 'text-navy-900 font-semibold' : '' }}"
                    data-sidebar-toggle
                    title="Kas & Keuangan"
                >
                    <span class="flex items-center gap-2.5 min-w-0">
                        <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="sidebar-text truncate">Kas & Keuangan</span>
                    </span>
                    <svg class="w-3.5 h-3.5 text-navy-500 transition-transform duration-200 shrink-0" data-sidebar-icon fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="neu-dropdown-content space-y-1 mt-1 pl-1" data-sidebar-content>
                    <a
                        href="{{ route('pengurus.dashboard') }}"
                        class="neu-nav-subitem {{ $active === 'dashboard' ? 'is-active' : '' }}"
                        title="Overview Kas"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'dashboard' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span class="sidebar-text truncate">Overview Kas</span>
                    </a>
                    <a
                        href="{{ route('pengurus.tagihan.index') }}"
                        class="neu-nav-subitem {{ $active === 'tagihan' ? 'is-active' : '' }}"
                        title="Jadwal & Tagihan"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'tagihan' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span class="sidebar-text truncate">Jadwal & Tagihan</span>
                    </a>
                    <a
                        href="{{ route('pengurus.pembayaran.index') }}"
                        class="neu-nav-subitem {{ $active === 'pembayaran' ? 'is-active' : '' }}"
                        title="Metode Pembayaran"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'pembayaran' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span class="sidebar-text truncate">Metode Pembayaran</span>
                    </a>
                    <a
                        href="{{ route('pengurus.buku-kas.index') }}"
                        class="neu-nav-subitem {{ $active === 'buku-kas' ? 'is-active' : '' }}"
                        title="Buku Kas Mutasi"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'buku-kas' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span class="sidebar-text truncate">Buku Kas Mutasi</span>
                    </a>
                </div>
            </div>

            <!-- Group 2: Kelola Ormawa (Dropdown Interaktif) -->
            <div class="neu-dropdown-group pt-1" data-sidebar-dropdown>
                <button
                    type="button"
                    class="neu-nav-item w-full justify-between {{ $isOrmawaActive ? 'text-navy-900 font-semibold' : '' }}"
                    data-sidebar-toggle
                    title="Kelola Ormawa"
                >
                    <span class="flex items-center gap-2.5 min-w-0">
                        <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="sidebar-text truncate">Kelola Ormawa</span>
                    </span>
                    <svg class="w-3.5 h-3.5 text-navy-500 transition-transform duration-200 shrink-0" data-sidebar-icon fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="neu-dropdown-content space-y-1 mt-1 pl-1" data-sidebar-content>
                    <a
                        href="{{ route('pengurus.anggota.index') }}"
                        class="neu-nav-subitem {{ $active === 'anggota' ? 'is-active' : '' }}"
                        title="Data Anggota"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'anggota' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span class="sidebar-text truncate">Data Anggota</span>
                    </a>
                    @if (session('active_role') === 'admin_organisasi' || ($user && $user->isSuperAdmin()))
                        <a
                            href="{{ route('pengurus.staff.index') }}"
                            class="neu-nav-subitem {{ $active === 'staff' ? 'is-active' : '' }}"
                            title="Kelola Staff"
                        >
                            <span class="w-1.5 h-1.5 rounded-full {{ $active === 'staff' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                            <span class="sidebar-text truncate">Kelola Staff</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Single Menu Items: Profil Saya & Beranda Web -->
            <div class="pt-2 border-t border-ice-200/50 space-y-1">
                <a
                    href="{{ route('pengurus.profile') }}"
                    class="neu-nav-item {{ $active === 'profile' ? 'is-active' : '' }}"
                    title="Profil Saya"
                >
                    <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="sidebar-text truncate">Profil Saya</span>
                </a>
                <a
                    href="/"
                    target="_blank"
                    class="neu-nav-item"
                    title="Lihat Beranda Web"
                >
                    <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span class="sidebar-text truncate">Lihat Beranda Web</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- User Profile & Logout Footer -->
    <div class="p-3 border-t border-ice-200/70 bg-white/40 shrink-0">
        <div class="flex items-center gap-2.5 mb-2.5 px-1 sidebar-user-box">
            <div class="neu-avatar-ring shrink-0" title="{{ $user->name ?? 'Pengurus' }}">
                <img
                    src="{{ $user ? $user->avatarUrl() : asset('logo/logo-light.svg') }}"
                    alt="{{ $user->name ?? 'User' }}"
                    class="w-9 h-9 rounded-full object-cover bg-ice-100"
                >
            </div>
            <div class="min-w-0 flex-1 sidebar-user-text">
                <p class="text-xs font-bold text-navy-900 truncate">
                    {{ $user->name ?? 'Pengurus' }}
                </p>
            </div>
        </div>

        <form action="{{ route('pengurus.logout') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="w-full h-8 flex items-center justify-center gap-1.5 rounded-lg bg-ice-100/90 text-navy-900 font-semibold text-xs border border-ice-200/80 shadow-[inset_1px_1px_2px_rgba(255,255,255,0.9),1px_1px_3px_rgba(122,163,214,0.25)] hover:bg-ice-200/70 active:shadow-[inset_2px_2px_4px_rgba(122,163,214,0.35)] transition-all cursor-pointer"
                title="Keluar Sesi"
            >
                <svg class="w-3.5 h-3.5 text-navy-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="sidebar-btn-text">Keluar Sesi</span>
            </button>
        </form>
    </div>
</aside>

<script>
    window.toggleAppSidebar = function() {
        const sb = document.querySelector('[data-app-sidebar]') || document.getElementById('sidebar-pengurus') || document.getElementById('sidebar-anggota');
        if (!sb) return;
        const isCollapsed = sb.classList.toggle('is-collapsed');
        localStorage.setItem('kasly_sidebar_collapsed', isCollapsed ? '1' : '0');
        updateSidebarIcons(isCollapsed);
    };

    function updateSidebarIcons(isCollapsed) {
        const iconCollapse = document.getElementById('icon-chevron-collapse');
        const iconExpand = document.getElementById('icon-chevron-expand');
        if (iconCollapse && iconExpand) {
            if (isCollapsed) {
                iconCollapse.classList.add('hidden');
                iconExpand.classList.remove('hidden');
            } else {
                iconCollapse.classList.remove('hidden');
                iconExpand.classList.add('hidden');
            }
        }
    }

    if (localStorage.getItem('kasly_sidebar_collapsed') === '1') {
        const sb = document.querySelector('[data-app-sidebar]') || document.getElementById('sidebar-pengurus') || document.getElementById('sidebar-anggota');
        if (sb) {
            sb.classList.add('is-collapsed');
            updateSidebarIcons(true);
        }
    }
</script>
