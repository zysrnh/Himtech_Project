<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Overview Sistem - {{ config('app.name', 'Kasly') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Web (240px) Sesuai Ice & Butter v2 -->
    <aside class="w-[240px] bg-white border-r border-ice-200 flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen">
        <div>
            <!-- Brand -->
            <div class="h-18 px-6 flex items-center border-b border-ice-200">
                <a href="{{ route('admin.dashboard') }}" class="font-display font-semibold text-2xl text-navy-900 tracking-tight">
                    {{ config('app.name', 'Kasly') }}
                </a>
            </div>

            <!-- Nav Items (Tanpa kelompok judul sesuai aturan v2) -->
            <nav class="p-4 space-y-1">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center h-11 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm transition-colors"
                >
                    Overview
                </a>
                <a
                    href="{{ route('admin.organizations.index') }}"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Organisasi
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

        <!-- User Info & Logout -->
        <div class="p-4 border-t border-ice-200">
            <div class="px-2 mb-3">
                <p class="text-sm font-medium text-navy-900 truncate">
                    {{ Auth::user()->name ?? 'Super Admin' }}
                </p>
                <p class="text-[13px] text-navy-500 truncate">
                    {{ Auth::user()->email ?? 'admin@kasly.id' }}
                </p>
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
                <div>
                    <a
                        href="{{ route('admin.organizations.index') }}"
                        class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors"
                    >
                        Kelola Organisasi
                    </a>
                </div>
            </div>

            <!-- Kartu Hero Stat (radius-blob khas Ice & Butter v2) -->
            <div class="bg-navy-900 text-white rounded-blob p-8 shadow-card flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-sm text-white/70">Total Organisasi Terdaftar</p>
                    <p class="font-display font-semibold text-4xl md:text-5xl tabular-nums mt-2">
                        {{ $totalOrganizations }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-4">
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
                </div>
            </div>

            <!-- 2 Kolom Ringkasan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Kolom 1: Organisasi Terbaru -->
                <div class="bg-white rounded-lg shadow-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-ice-200">
                            <h2 class="font-display font-medium text-lg text-navy-900">
                                Organisasi Terbaru
                            </h2>
                            <a href="{{ route('admin.organizations.index') }}" class="text-xs font-semibold text-navy-500 hover:text-navy-900 transition-colors">
                                Lihat Semua
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
                            Ke Halaman Organisasi
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Status Sistem & Audit Log -->
                <div class="bg-white rounded-lg shadow-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="pb-4 border-b border-ice-200">
                            <h2 class="font-display font-medium text-lg text-navy-900">
                                Status & Aktivitas Sistem
                            </h2>
                        </div>

                        <!-- Status Cards -->
                        <div class="grid grid-cols-2 gap-4 my-4">
                            <div class="p-4 rounded-md bg-ice-100">
                                <span class="text-xs text-navy-500 block">Isolasi Multi-Tenant</span>
                                <span class="text-sm font-semibold text-navy-900 mt-1 block">Aktif Normal</span>
                            </div>
                            <div class="p-4 rounded-md bg-ice-100">
                                <span class="text-xs text-navy-500 block">Integritas Audit Log</span>
                                <span class="text-sm font-semibold text-navy-900 mt-1 block">Terkunci</span>
                            </div>
                        </div>

                        <!-- Log Aktivitas Terkini -->
                        <div class="mt-4">
                            <p class="text-xs font-medium text-navy-500 mb-3">Log Terakhir</p>
                            @if ($recentActivities->isEmpty())
                                <p class="text-xs text-navy-500 py-4 text-center">Belum ada aktivitas tercatat.</p>
                            @else
                                <div class="space-y-3">
                                    @foreach ($recentActivities as $log)
                                        <div class="text-xs text-navy-900 p-3 rounded-md bg-ice-100/50 flex items-center justify-between">
                                            <div>
                                                <span class="font-semibold">{{ $log->action }}</span>
                                                <span class="text-navy-500 block mt-0.5">
                                                    {{ $log->payload['organization_name'] ?? 'Sistem' }}
                                                </span>
                                            </div>
                                            <span class="text-[11px] text-navy-500 tabular-nums">
                                                {{ $log->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

</body>
</html>
