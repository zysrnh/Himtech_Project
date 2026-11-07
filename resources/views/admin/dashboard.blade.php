<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Overview Sistem - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Web (240px) Sesuai Ice & Butter v2 -->
    <aside class="w-[240px] bg-white border-r border-ice-200 flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen">
        <div>
            <!-- Brand -->
            <div class="h-18 px-6 flex items-center border-b border-ice-200">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center">
                    <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-9 w-auto">
                </a>
            </div>

            <!-- Nav Items -->
            <nav class="p-4 space-y-1">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center h-11 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm transition-colors"
                >
                    Overview
                </a>
                <a
                    href="{{ route('admin.organizations.index') }}"
                    class="flex items-center justify-between h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    <span>Organisasi</span>
                    @if ($pendingRequestsCount > 0)
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-butter-100 text-navy-900 text-xs font-bold font-display">
                            {{ $pendingRequestsCount }}
                        </span>
                    @endif
                </a>
                <a
                    href="{{ route('admin.admins.index') }}"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Kelola Admin
                </a>
                <a
                    href="{{ route('admin.profile') }}"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Profil Admin
                </a>
                <a
                    href="/"
                    target="_blank"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Lihat Beranda Web
                </a>
            </nav>
        </div>

        <!-- User Info & Logout dengan Avatar -->
        <div class="p-4 border-t border-ice-200">
            <div class="flex items-center gap-3 mb-3 px-1">
                <img
                    src="{{ Auth::user()->avatarUrl() }}"
                    alt="{{ Auth::user()->name }}"
                    class="w-10 h-10 rounded-full object-cover border border-ice-200 shrink-0 bg-ice-100"
                >
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-navy-900 truncate">
                        {{ Auth::user()->name ?? 'Super Admin' }}
                    </p>
                    <p class="text-[12px] text-navy-500 truncate">
                        {{ Auth::user()->email ?? 'admin@kasly.id' }}
                    </p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center justify-center h-10 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm hover:bg-ice-200 transition-colors cursor-pointer"
                >
                    Keluar Sesi
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area: Overview -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        Overview Sistem
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Ringkasan telemetri, metrik global, dan status operasional sistem Kasly.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a
                        href="{{ route('admin.organizations.index') }}"
                        class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm"
                    >
                        Kelola Organisasi
                    </a>
                </div>
            </div>

            <!-- Notifikasi Pengajuan Baru (Banner Alert) -->
            @if ($pendingRequestsCount > 0)
                <div class="p-5 rounded-lg bg-butter-100 text-navy-900 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-navy-900/10">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-sm shrink-0">
                            {{ $pendingRequestsCount }}
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-navy-900">
                                Ada {{ $pendingRequestsCount }} pengajuan organisasi baru yang menunggu persetujuan Anda!
                            </p>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Segera tinjau identitas pemohon dan lakukan ACC agar akun pengelola aktif.
                            </p>
                        </div>
                    </div>
                    <a
                        href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                        class="inline-flex items-center justify-center h-10 px-5 rounded-md bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors shrink-0"
                    >
                        Tinjau & Setujui (ACC) Sekarang
                    </a>
                </div>
            @endif

            <!-- Kartu Hero Stat Telemetri (radius-blob khas Ice & Butter v2) -->
            <div class="bg-navy-900 text-white rounded-blob p-8 shadow-card flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <p class="text-sm text-white/70">Total Organisasi Terdaftar</p>
                    <p class="font-display font-semibold text-4xl md:text-5xl tabular-nums mt-2">
                        {{ $totalOrganizations }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="bg-white/10 px-5 py-3 rounded-md">
                        <span class="text-xs text-white/70 block">Organisasi Aktif</span>
                        <span class="font-display font-semibold text-xl tabular-nums mt-0.5 block">
                            {{ $activeOrganizations }}
                        </span>
                    </div>
                    <div class="bg-white/10 px-5 py-3 rounded-md">
                        <span class="text-xs text-white/70 block">Total Anggota Terdata</span>
                        <span class="font-display font-semibold text-xl tabular-nums mt-0.5 block">
                            {{ $totalMembers }}
                        </span>
                    </div>
                    <div class="bg-white/10 px-5 py-3 rounded-md {{ $pendingRequestsCount > 0 ? 'border border-butter-100' : '' }}">
                        <span class="text-xs text-white/70 block">Pengajuan Pending</span>
                        <span class="font-display font-semibold text-xl tabular-nums mt-0.5 block text-butter-100">
                            {{ $pendingRequestsCount }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Widget Antrean Pengajuan Masuk (Jika Ada) -->
            @if ($pendingRequests->isNotEmpty())
                <div class="bg-white rounded-lg shadow-card p-6 border border-ice-200">
                    <div class="flex items-center justify-between pb-4 border-b border-ice-200">
                        <div>
                            <h2 class="font-display font-medium text-lg text-navy-900">
                                Pengajuan Organisasi Terbaru yang Butuh ACC
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Formulir permohonan pendaftaran yang baru masuk dari calon pengelola ormawa.
                            </p>
                        </div>
                        <a href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}" class="text-xs font-semibold text-navy-500 hover:text-navy-900 transition-colors">
                            Lihat Semua ({{ $pendingRequestsCount }})
                        </a>
                    </div>

                    <div class="divide-y divide-ice-200">
                        @foreach ($pendingRequests as $req)
                            <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-sm text-navy-900">{{ $req->name }}</span>
                                        <span class="font-mono text-xs px-2 py-0.5 rounded bg-ice-100 text-navy-900">{{ $req->code }}</span>
                                    </div>
                                    <p class="text-xs text-navy-500 mt-1">
                                        Pemohon: <strong class="text-navy-900">{{ $req->pic_name }}</strong> (WA: {{ $req->pic_phone }}) · Diajukan {{ $req->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form
                                        action="{{ route('admin.organizations.requests.approve', $req) }}"
                                        method="POST"
                                        onsubmit="return confirm('SETUJUI (ACC) pengajuan organisasi {{ $req->name }}?');"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center h-8 px-4 rounded-md bg-navy-900 text-white hover:bg-navy-700 text-xs font-semibold transition-colors cursor-pointer shadow-sm"
                                        >
                                            Setujui (ACC)
                                        </button>
                                    </form>
                                    <a
                                        href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                                        class="inline-flex items-center justify-center h-8 px-3 rounded-md border border-ice-200 text-navy-500 hover:text-navy-900 text-xs font-medium transition-colors"
                                    >
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 2 Kolom Ringkasan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Kolom 1: Organisasi Terbaru -->
                <div class="bg-white rounded-lg shadow-card p-6 flex flex-col justify-between border border-ice-200">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-ice-200">
                            <h2 class="font-display font-medium text-lg text-navy-900">
                                Organisasi Terdaftar
                            </h2>
                            <a href="{{ route('admin.organizations.index') }}" class="text-xs font-semibold text-navy-500 hover:text-navy-900 transition-colors">
                                Kelola Semua
                            </a>
                        </div>

                        @if ($recentOrganizations->isEmpty())
                            <div class="py-12 text-center">
                                <p class="text-sm text-navy-500">
                                    Belum ada organisasi yang terdaftar.
                                </p>
                            </div>
                        @else
                            <div class="divide-y divide-ice-200">
                                @foreach ($recentOrganizations as $org)
                                    <div class="py-4 flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-navy-900">
                                                {{ $org->name }}
                                            </p>
                                            <p class="text-xs text-navy-500 mt-0.5 font-display">
                                                Kode: {{ $org->code }} · {{ $org->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            @if ($org->status === 'active')
                                                <span class="inline-block px-2.5 py-1 rounded-full bg-butter-100 text-navy-900 text-xs font-semibold">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="text-danger font-medium text-xs">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-ice-200">
                        <a
                            href="{{ route('admin.organizations.index') }}"
                            class="w-full flex items-center justify-center h-10 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm hover:bg-ice-200 transition-colors"
                        >
                            Ke Manajemen Organisasi
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Status & Aktivitas Sistem -->
                <div class="bg-white rounded-lg shadow-card p-6 flex flex-col justify-between border border-ice-200">
                    <div>
                        <div class="pb-4 border-b border-ice-200">
                            <h2 class="font-display font-medium text-lg text-navy-900">
                                Status & Aktivitas Sistem
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Log audit trail dan status telemetri keamanan multi-tenant.
                            </p>
                        </div>

                        <!-- 2 Status Card Kecil -->
                        <div class="grid grid-cols-2 gap-4 my-4">
                            <div class="p-4 rounded-md bg-ice-100/60 border border-ice-200">
                                <span class="text-xs text-navy-500 block">Isolasi Multi-Tenant</span>
                                <span class="text-sm font-semibold text-navy-900 block mt-1">Aktif Normal</span>
                            </div>
                            <div class="p-4 rounded-md bg-ice-100/60 border border-ice-200">
                                <span class="text-xs text-navy-500 block">Integritas Audit Log</span>
                                <span class="text-sm font-semibold text-navy-900 block mt-1">Terkunci</span>
                            </div>
                        </div>

                        <!-- Daftar Log Aktivitas -->
                        <div>
                            <p class="text-xs font-medium text-navy-500 mb-2">Log Terakhir</p>
                            @if ($recentActivities->isEmpty())
                                <p class="text-xs text-navy-500 italic py-4">Belum ada aktivitas tercatat.</p>
                            @else
                                <div class="divide-y divide-ice-200 text-xs">
                                    @foreach ($recentActivities as $act)
                                        <div class="py-2.5 flex items-center justify-between">
                                            <div>
                                                <p class="font-mono text-navy-900 font-medium">{{ $act->action }}</p>
                                                <p class="text-navy-500 text-[11px] mt-0.5">
                                                    {{ $act->payload['organization_name'] ?? ($act->organization->name ?? 'Sistem') }}
                                                </p>
                                            </div>
                                            <span class="text-navy-500 tabular-nums text-[11px]">
                                                {{ $act->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-ice-200">
                        <a
                            href="{{ route('admin.organizations.index') }}"
                            class="w-full flex items-center justify-center h-10 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm hover:bg-ice-200 transition-colors"
                        >
                            Lihat Log Lengkap
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </main>

</body>
</html>
