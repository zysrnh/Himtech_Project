@props([
    'active' => 'dashboard',
    'pendingRequestsCount' => null,
])

@php
    $user = Auth::user();
    $pendingCount = $pendingRequestsCount ?? \App\Models\OrganizationRequest::where('status', 'pending')->count();
    $isAdminPenerima = $user && $user->isAdminPenerima();
    $roleName = $isAdminPenerima ? 'Admin Penerima Organisasi' : 'Super Admin Kasly';
    $isPlatformActive = in_array($active, ['dashboard', 'organizations', 'admins']);
@endphp

<aside class="w-[260px] neu-sidebar flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen z-30 select-none">
    <div class="flex flex-col min-h-0 flex-1">
        <!-- Brand Header (Logo Kasly Bersih Sesuai Sidebar Pengurus) -->
        <div class="h-20 px-4 flex items-center justify-center border-b border-ice-200/70 shrink-0">
            <a href="{{ $isAdminPenerima ? route('admin.organizations.index', ['tab' => 'requests']) : route('admin.dashboard') }}" class="inline-flex items-center justify-center" title="{{ config('app.name', 'Kasly') }}">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-11 w-auto max-w-[170px] object-contain">
            </a>
        </div>

        <!-- Identitas Sistem Kasly (Gaya Flat Rata Kiri Sesuai Sidebar Pengurus) -->
        <div class="px-4 pt-3.5 pb-3 border-b border-ice-200/60 shrink-0">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-xl bg-ice-200/70 text-navy-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-navy-900 truncate" title="Pusat Kontrol Kasly">
                        Pusat Kontrol Kasly
                    </p>
                    <span class="text-[11px] font-medium text-navy-500 block truncate">
                        {{ $roleName }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigasi Menu (Scrollable) -->
        <nav class="flex-1 overflow-y-auto px-2.5 py-2 space-y-1.5 custom-scrollbar">
            @if ($isAdminPenerima)
                <!-- Menu Khusus Admin Penerima Organisasi (Spesialisasi Pengajuan Ormawa) -->
                <a
                    href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                    class="neu-nav-item justify-between {{ $active === 'organizations' ? 'is-active' : '' }}"
                    title="Pengajuan Masuk"
                >
                    <span class="flex items-center gap-2.5 min-w-0">
                        <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="truncate font-medium">Pengajuan Masuk</span>
                    </span>
                    @if ($pendingCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-butter-100 text-navy-900 border border-amber-200 shrink-0">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            @else
                <!-- Group: Manajemen Platform untuk Super Admin -->
                <div class="neu-dropdown-group" data-sidebar-dropdown>
                    <button
                        type="button"
                        class="neu-nav-item w-full justify-between {{ $isPlatformActive ? 'text-navy-900 font-semibold' : '' }}"
                        data-sidebar-toggle
                    >
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-navy-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="truncate font-medium">Kelola Platform</span>
                        </span>
                        <div class="flex items-center gap-1.5 shrink-0">
                            @if ($pendingCount > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-butter-100 text-navy-900 border border-amber-200">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                            <svg class="w-3.5 h-3.5 text-navy-500 transition-transform duration-200" data-sidebar-icon fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div class="neu-dropdown-content space-y-1 mt-1 pl-1" data-sidebar-content>
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="neu-nav-subitem {{ $active === 'dashboard' ? 'is-active' : '' }}"
                        >
                            <span class="w-1.5 h-1.5 rounded-full {{ $active === 'dashboard' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                            <span>Overview Sistem</span>
                        </a>
                        <a
                            href="{{ route('admin.organizations.index') }}"
                            class="neu-nav-subitem justify-between {{ $active === 'organizations' ? 'is-active' : '' }}"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $active === 'organizations' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                                <span>Verifikasi Organisasi</span>
                            </div>
                            @if ($pendingCount > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-butter-100 text-navy-900 border border-amber-200">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                        <a
                            href="{{ route('admin.admins.index') }}"
                            class="neu-nav-subitem {{ $active === 'admins' ? 'is-active' : '' }}"
                        >
                            <span class="w-1.5 h-1.5 rounded-full {{ $active === 'admins' ? 'bg-navy-900' : 'bg-ice-400' }} shrink-0"></span>
                            <span>Kelola Administrator</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Single Menu Items: Profil Saya & Beranda Web -->
            <div class="pt-2 border-t border-ice-200/50 space-y-1">
                <a
                    href="{{ route('admin.profile') }}"
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

    <!-- User Profile & Logout Footer (Sesuai Gaya Sidebar Pengurus) -->
    <div class="p-3 border-t border-ice-200/70 bg-white/40 shrink-0">
        <div class="flex items-center gap-2.5 mb-2.5 px-1">
            <div class="neu-avatar-ring shrink-0">
                <img
                    src="{{ $user ? $user->avatarUrl() : asset('logo/logo-light.svg') }}"
                    alt="{{ $user->name ?? 'Admin' }}"
                    class="w-9 h-9 rounded-full object-cover bg-ice-100"
                >
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-navy-900 truncate">
                    {{ $user->name ?? 'Admin Kasly' }}
                </p>
                <span class="text-[11px] font-medium text-navy-500 block truncate">
                    {{ $roleName }}
                </span>
            </div>
        </div>

        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="w-full h-8 flex items-center justify-center gap-1.5 rounded-lg bg-ice-100/90 text-navy-900 font-semibold text-xs border border-ice-200/80 shadow-[inset_1px_1px_2px_rgba(255,255,255,0.9),1px_1px_3px_rgba(122,163,214,0.25)] hover:bg-red-50 hover:text-danger hover:border-red-200 active:shadow-[inset_2px_2px_4px_rgba(122,163,214,0.35)] transition-all cursor-pointer"
            >
                <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar Sesi</span>
            </button>
        </form>
    </div>
</aside>
