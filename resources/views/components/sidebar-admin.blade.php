@props([
    'active' => 'dashboard',
    'pendingRequestsCount' => null,
])

@php
    $user = Auth::user();
    $pendingCount = $pendingRequestsCount ?? \App\Models\OrganizationRequest::where('status', 'pending')->count();
    $isPlatformActive = in_array($active, ['dashboard', 'organizations', 'admins']);
    $roleName = $user && $user->isAdminPenerima() ? 'Admin Penerima' : 'Super Admin';
@endphp

<!-- Mobile Top Header (Tampil Hanya di Layar HP < 768px: NEUMORPHISM.md) -->
<header class="md:hidden sticky top-0 z-30 w-full px-4 py-2.5 bg-ice-100/90 backdrop-blur-md border-b border-ice-200/70 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-2.5 min-w-0">
        <a href="{{ route('admin.dashboard') }}" class="w-8 h-8 rounded-xl bg-white flex items-center justify-center border border-white/90 shadow-[2px_2px_6px_rgba(122,163,214,0.35),-2px_-2px_6px_rgba(255,255,255,0.9)] shrink-0">
            <img src="{{ asset('logo/icon-kasly.svg') }}" onerror="this.onerror=null; this.src='{{ asset('favicon.ico') }}';" alt="{{ config('app.name', 'Kasly') }}" class="w-4 h-4 object-contain">
        </a>
        <div class="min-w-0">
            <p class="text-xs font-bold text-navy-900 truncate">
                Pusat Kontrol Kasly
            </p>
            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-navy-500">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                {{ $roleName }}
            </span>
        </div>
    </div>

    <div class="flex items-center gap-2 shrink-0">
        <a href="{{ route('admin.profile') }}" class="w-8 h-8 rounded-full overflow-hidden border border-ice-200/80 shadow-xs" title="Profil Admin">
            <img src="{{ $user ? $user->avatarUrl() : asset('logo/logo-light.svg') }}" alt="{{ $user->name ?? 'Admin' }}" class="w-full h-full object-cover">
        </a>
        <button
            type="button"
            onclick="openLogoutModal()"
            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all cursor-pointer border border-white/80 shadow-[2px_2px_6px_rgba(122,163,214,0.3),-2px_-2px_6px_#ffffff] text-danger hover:scale-105 active:scale-95"
            style="background: linear-gradient(145deg, #FFF5F5, #FEE8E8);"
            title="Keluar Sesi"
        >
            <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
        </button>
    </div>
</header>

<!-- Desktop Sidebar Neumorphic (Hanya Tampil di Layar Desktop/Tablet >= 768px) -->
<aside
    id="sidebar-admin"
    data-app-sidebar
    class="w-[260px] neu-sidebar hidden md:flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen z-40 select-none transition-all duration-300 ease-in-out"
>
    <div class="flex flex-col min-h-0 flex-1">
        <!-- Brand Header (Logo Kasly & Tombol Collapse/Expand) -->
        <div class="h-20 px-3 flex items-center justify-between border-b border-ice-200/70 shrink-0 sidebar-header-box">
            <!-- Full Logo saat expanded -->
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-full inline-flex items-center justify-center flex-1 pl-3" title="{{ config('app.name', 'Kasly') }}">
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

        <!-- Identitas Sistem Kasly -->
        <div class="px-3 pt-3.5 pb-3 border-b border-ice-200/60 shrink-0 sidebar-org-box">
            <div class="sidebar-org-inner flex items-center gap-2.5 min-w-0">
                <div class="sidebar-org-icon w-8 h-8 rounded-xl bg-ice-200/70 text-navy-700 flex items-center justify-center shrink-0 transition-all" title="Pusat Kontrol Kasly">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="sidebar-org-text min-w-0 flex-1">
                    <p class="text-xs font-bold text-navy-900 truncate" title="Pusat Kontrol Kasly">
                        Pusat Kontrol Kasly
                    </p>
                    <span class="text-[11px] font-medium text-navy-500">
                        {{ $roleName }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigasi Menu (Scrollable) -->
        <nav class="flex-1 overflow-y-auto px-2.5 py-2 space-y-1.5 custom-scrollbar">
            <!-- Group: Kelola Platform (Dropdown Interaktif) -->
            <div class="neu-dropdown-group" data-sidebar-dropdown>
                <button
                    type="button"
                    class="neu-nav-item w-full justify-between {{ $isPlatformActive ? 'text-navy-900 font-semibold' : '' }}"
                    data-sidebar-toggle
                    title="Kelola Platform"
                >
                    <span class="flex items-center gap-2.5 min-w-0">
                        <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="sidebar-text truncate">Kelola Platform</span>
                    </span>
                    <svg class="w-3.5 h-3.5 text-navy-500 transition-transform duration-200 shrink-0" data-sidebar-icon fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="neu-dropdown-content space-y-1 mt-1 pl-1" data-sidebar-content>
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="neu-nav-subitem {{ $active === 'dashboard' ? 'is-active' : '' }}"
                        title="Overview Sistem"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $active === 'dashboard' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                        <span class="sidebar-text truncate">Overview Sistem</span>
                    </a>
                    <a
                        href="{{ route('admin.organizations.index') }}"
                        class="neu-nav-subitem justify-between {{ $active === 'organizations' ? 'is-active' : '' }}"
                        title="Verifikasi Organisasi"
                    >
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-1.5 h-1.5 rounded-full {{ $active === 'organizations' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                            <span class="sidebar-text truncate">Verifikasi Organisasi</span>
                        </div>
                        @if ($pendingCount > 0)
                            <span class="neu-badge-pill text-[9px] py-0 px-1.5 bg-butter-100 shrink-0">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                    @if ($user && $user->isSuperAdmin())
                        <a
                            href="{{ route('admin.admins.index') }}"
                            class="neu-nav-subitem {{ $active === 'admins' ? 'is-active' : '' }}"
                            title="Kelola Administrator"
                        >
                            <span class="w-1.5 h-1.5 rounded-full {{ $active === 'admins' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                            <span class="sidebar-text truncate">Kelola Administrator</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Single Menu Items: Profil Admin & Beranda Web -->
            <div class="pt-2 border-t border-ice-200/50 space-y-1">
                <a
                    href="{{ route('admin.profile') }}"
                    class="neu-nav-item {{ $active === 'profile' ? 'is-active' : '' }}"
                    title="Profil Admin"
                >
                    <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="sidebar-text truncate">Profil Admin</span>
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
            <div class="neu-avatar-ring shrink-0" title="{{ $user->name ?? 'Admin' }}">
                <img
                    src="{{ $user ? $user->avatarUrl() : asset('logo/logo-light.svg') }}"
                    alt="{{ $user->name ?? 'Admin' }}"
                    class="w-9 h-9 rounded-full object-cover bg-ice-100"
                >
            </div>
            <div class="min-w-0 flex-1 sidebar-user-text">
                <p class="text-xs font-bold text-navy-900 truncate">
                    {{ $user->name ?? 'Admin' }}
                </p>
            </div>
        </div>

        <button
            type="button"
            onclick="openLogoutModal()"
            class="w-full h-8 flex items-center justify-center gap-1.5 rounded-lg bg-ice-100/90 text-navy-900 font-semibold text-xs border border-ice-200/80 shadow-[inset_1px_1px_2px_rgba(255,255,255,0.9),1px_1px_3px_rgba(122,163,214,0.25)] hover:bg-red-50 hover:text-danger hover:border-red-200 active:shadow-[inset_2px_2px_4px_rgba(122,163,214,0.35)] transition-all cursor-pointer"
            title="Keluar Sesi"
        >
            <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="sidebar-btn-text">Keluar Sesi</span>
        </button>
    </div>
</aside>

<!-- Mobile Floating Bottom Toolbar (Neumorphic Capsule di HP < 768px: NEUMORPHISM.md) -->
<nav class="neu-bottomnav md:hidden" aria-label="Toolbar Super Admin Mobile">
    <!-- 1. Overview -->
    <a href="{{ route('admin.dashboard') }}" class="neu-bottomnav__item {{ $active === 'dashboard' ? 'is-active' : '' }}" aria-label="Overview">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span class="text-[10px] font-semibold">Overview</span>
    </a>

    <!-- 2. Verifikasi Organisasi -->
    <a href="{{ route('admin.organizations.index') }}" class="neu-bottomnav__item {{ $active === 'organizations' ? 'is-active' : '' }} relative" aria-label="Verifikasi">
        <div class="relative">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @if ($pendingCount > 0)
                <span class="absolute -top-1 -right-2.5 w-4 h-4 rounded-full bg-butter-100 text-navy-900 font-bold text-[9px] flex items-center justify-center border border-navy-900/10">
                    {{ $pendingCount }}
                </span>
            @endif
        </div>
        <span class="text-[10px] font-semibold">Verifikasi</span>
    </a>

    <!-- 3. Kelola Administrator -->
    @if ($user && $user->isSuperAdmin())
        <a href="{{ route('admin.admins.index') }}" class="neu-bottomnav__item {{ $active === 'admins' ? 'is-active' : '' }}" aria-label="Admin">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="text-[10px] font-semibold">Admin</span>
        </a>
    @endif

    <!-- 4. Profil -->
    <a href="{{ route('admin.profile') }}" class="neu-bottomnav__item {{ $active === 'profile' ? 'is-active' : '' }}" aria-label="Profil">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <span class="text-[10px] font-semibold">Profil</span>
    </a>
</nav>

<!-- Modal Konfirmasi Logout Sesi (Neumorphic Sesuai NEUMORPHISM.md) -->
<div id="modal-logout-confirm" style="display: none;" onclick="if(event.target === this) closeLogoutModal()" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/40 backdrop-blur-xs">
    <div class="rounded-[32px] max-w-sm w-full p-7 sm:p-8 text-center space-y-5 border border-white/80 shadow-[14px_14px_32px_rgba(122,163,214,0.45),-14px_-14px_32px_rgba(255,255,255,0.95)]" style="background: linear-gradient(145deg, #F4F9FF, #D7E7F9);">
        <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center text-danger border border-white/90 shadow-[inset_3px_3px_6px_rgba(122,163,214,0.35),inset_-3px_-3px_6px_#ffffff,4px_4px_10px_rgba(192,82,74,0.18)]" style="background: linear-gradient(145deg, #FFF5F5, #FEE8E8);">
            <svg class="w-7 h-7 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
        </div>

        <div>
            <h3 class="font-display font-extrabold text-xl text-navy-900 tracking-tight">Keluar Sesi Admin?</h3>
            <p class="text-xs text-navy-500 mt-1.5 leading-relaxed">
                Sesi Administrator Anda akan diakhiri. Anda perlu masuk kembali dengan akun admin untuk mengelola platform.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-1">
            <button
                type="button"
                onclick="closeLogoutModal()"
                class="neu-btn h-11 px-5 rounded-full font-bold text-xs text-navy-700 border border-white/80 shadow-[4px_4px_10px_rgba(122,163,214,0.35),-4px_-4px_10px_#ffffff] hover:scale-105 active:scale-95 transition-all cursor-pointer w-full"
                style="background: linear-gradient(145deg, #F8FAFD, #DBE7F6);"
            >
                Batal
            </button>
            <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                @csrf
                <button
                    type="submit"
                    class="h-11 px-5 rounded-full font-bold text-xs text-white border border-white/30 shadow-[6px_8px_18px_rgba(192,82,74,0.38),-3px_-3px_8px_rgba(255,255,255,0.9)] hover:scale-105 active:scale-95 transition-all cursor-pointer flex items-center justify-center gap-1.5 w-full"
                    style="background: linear-gradient(135deg, #DE5D53, #9E2A2B);"
                >
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Ya, Keluar</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    window.openLogoutModal = function() {
        const m = document.getElementById('modal-logout-confirm');
        if (m) {
            m.style.display = 'flex';
        }
    };

    window.closeLogoutModal = function() {
        const m = document.getElementById('modal-logout-confirm');
        if (m) {
            m.style.display = 'none';
        }
    };

    window.toggleAppSidebar = function() {
        const sb = document.querySelector('[data-app-sidebar]') || document.getElementById('sidebar-admin') || document.getElementById('sidebar-pengurus');
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
        const sb = document.querySelector('[data-app-sidebar]') || document.getElementById('sidebar-admin') || document.getElementById('sidebar-pengurus');
        if (sb) {
            sb.classList.add('is-collapsed');
            updateSidebarIcons(true);
        }
    }
</script>