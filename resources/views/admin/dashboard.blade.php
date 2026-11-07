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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row selection:bg-ice-200 selection:text-navy-900">

    <!-- Sidebar Super Admin Neumorphic Interaktif -->
    <x-sidebar-admin active="dashboard" :pending-requests-count="$pendingRequestsCount ?? null" />

    <!-- Main Content Area: Overview -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen md:h-screen overflow-y-auto custom-scrollbar pb-28 md:pb-8">

        <main class="p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-bold text-2xl lg:text-3xl text-navy-900 tracking-tight">
                        Overview Sistem Kasly
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        Ringkasan telemetri, metrik global, dan status operasional sistem multi-tenant ormawa.
                    </p>
                </div>
                <div class="shrink-0">
                    <a
                        href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                        class="h-11 px-5 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_4px_12px_rgba(47,72,98,0.25)] hover:bg-navy-700 active:scale-[0.99] transition-all flex items-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Verifikasi Pengajuan ({{ $pendingRequestsCount }})</span>
                    </a>
                </div>
            </div>

            <!-- Notifikasi Pengajuan Baru (Banner Alert Neumorphic) -->
            @if ($pendingRequestsCount > 0)
                <div class="p-5 rounded-[28px] bg-white/90 border border-butter-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-in">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-butter-100 text-navy-900 flex items-center justify-center font-bold text-base shrink-0 shadow-xs border border-butter-200">
                            {{ $pendingRequestsCount }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-navy-900">
                                Ada {{ $pendingRequestsCount }} pengajuan organisasi baru yang menunggu persetujuan Anda!
                            </p>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Segera tinjau identitas pemohon dan lakukan ACC agar akun pengelola ormawa aktif.
                            </p>
                        </div>
                    </div>
                    <a
                        href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                        class="h-10 px-5 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-colors shadow-xs flex items-center justify-center gap-1.5 shrink-0"
                    >
                        <span>Tinjau & Setujui (ACC)</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            @endif

            <!-- Kartu Hero Stat Telemetri Neumorphic -->
            <div class="bg-gradient-to-br from-navy-900 via-navy-700 to-navy-900 text-white rounded-[28px] p-6 sm:p-8 shadow-[6px_6px_20px_rgba(47,72,98,0.3),-4px_-4px_16px_rgba(255,255,255,0.7)] border border-navy-700/60 flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-white/70 font-medium">Total Organisasi Terdaftar</span>
                        <span class="neu-badge-pill text-[10px] py-0.5 px-2 bg-butter-100 text-navy-900">MULTI-TENANT</span>
                    </div>
                    <p class="font-display font-bold text-4xl sm:text-5xl tabular-nums mt-3 tracking-tight text-white">
                        {{ $totalOrganizations }}
                    </p>
                    <p class="text-xs text-white/60 mt-2">Seluruh himpunan & unit mahasiswa terhubung di Kasly.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 relative z-10">
                    <div class="bg-white/10 px-5 py-3.5 rounded-2xl border border-white/10 shadow-xs min-w-[130px]">
                        <span class="text-xs text-white/70 block font-medium">Organisasi Aktif</span>
                        <span class="font-display font-bold text-2xl tabular-nums mt-1 block text-white">
                            {{ $activeOrganizations }}
                        </span>
                    </div>
                    <div class="bg-white/10 px-5 py-3.5 rounded-2xl border border-white/10 shadow-xs min-w-[130px]">
                        <span class="text-xs text-white/70 block font-medium">Anggota Terdata</span>
                        <span class="font-display font-bold text-2xl tabular-nums mt-1 block text-white">
                            {{ $totalMembers }}
                        </span>
                    </div>
                    <div class="bg-white/10 px-5 py-3.5 rounded-2xl border border-white/10 shadow-xs min-w-[130px] {{ $pendingRequestsCount > 0 ? 'border-butter-100/60 bg-butter-100/10' : '' }}">
                        <span class="text-xs text-white/70 block font-medium">Pengajuan Pending</span>
                        <span class="font-display font-bold text-2xl tabular-nums mt-1 block text-butter-100">
                            {{ $pendingRequestsCount }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Widget Antrean Pengajuan Masuk (Jika Ada) -->
            @if ($pendingRequests->isNotEmpty())
                <div class="bg-white/90 rounded-[28px] p-6 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3.5 border-b border-ice-200/70">
                        <div>
                            <h2 class="font-display font-bold text-base text-navy-900">
                                Pengajuan Organisasi Terbaru yang Butuh ACC
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Formulir permohonan pendaftaran yang baru masuk dari calon pengelola ormawa.
                            </p>
                        </div>
                        <a href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}" class="text-xs font-semibold text-navy-700 hover:text-navy-900 transition-colors inline-flex items-center gap-1">
                            <span>Lihat Semua ({{ $pendingRequestsCount }})</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    <div class="divide-y divide-ice-100/80">
                        @foreach ($pendingRequests as $req)
                            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs sm:text-sm text-navy-900">{{ $req->name }}</span>
                                        <span class="neu-badge-ice text-[10px] py-0.2 px-2 font-mono font-bold">{{ $req->code }}</span>
                                    </div>
                                    <p class="text-xs text-navy-500 mt-1">
                                        Pemohon: <strong class="text-navy-900">{{ $req->pic_name }}</strong> (WA: <span class="font-mono">{{ $req->pic_phone }}</span>) · Diajukan {{ $req->created_at->diffForHumans() }}
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
                                            class="h-8 px-4 rounded-lg bg-navy-900 text-white hover:bg-navy-700 text-xs font-semibold shadow-xs transition-colors cursor-pointer"
                                        >
                                            Setujui (ACC)
                                        </button>
                                    </form>
                                    <a
                                        href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                                        class="h-8 px-3 rounded-lg border border-ice-200 text-navy-600 hover:text-navy-900 bg-white hover:bg-ice-100 text-xs font-medium shadow-xs transition-colors flex items-center justify-center"
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Kolom 1: Organisasi Terbaru -->
                <div class="bg-white/90 rounded-[28px] p-6 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between pb-3.5 border-b border-ice-200/70">
                            <div>
                                <h2 class="font-display font-bold text-base text-navy-900">
                                    Organisasi Terdaftar
                                </h2>
                                <p class="text-xs text-navy-500 mt-0.5">Entitas ormawa yang aktif dalam platform.</p>
                            </div>
                            <a href="{{ route('admin.organizations.index') }}" class="text-xs font-semibold text-navy-700 hover:text-navy-900 transition-colors">
                                Kelola Semua &rarr;
                            </a>
                        </div>

                        @if ($recentOrganizations->isEmpty())
                            <div class="py-12 text-center">
                                <p class="text-xs text-navy-500">
                                    Belum ada organisasi yang terdaftar.
                                </p>
                            </div>
                        @else
                            <div class="divide-y divide-ice-100/80">
                                @foreach ($recentOrganizations as $org)
                                    <div class="py-3.5 flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-xs sm:text-sm font-bold text-navy-900">
                                                {{ $org->name }}
                                            </p>
                                            <p class="text-[11px] text-navy-500 mt-0.5 font-mono">
                                                Kode: {{ $org->code }} · {{ $org->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            @if ($org->status === 'active')
                                                <span class="neu-badge-pill text-[10px] py-0.5 px-2 bg-butter-100">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full bg-danger/10 text-danger border border-danger/20 text-[10px] font-bold">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-ice-200/70">
                        <a
                            href="{{ route('admin.organizations.index') }}"
                            class="w-full flex items-center justify-center h-10 px-4 rounded-xl bg-ice-100 text-navy-900 font-semibold text-xs hover:bg-ice-200 transition-colors border border-ice-200/70 shadow-xs"
                        >
                            Ke Manajemen Organisasi
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Status & Aktivitas Sistem -->
                <div class="bg-white/90 rounded-[28px] p-6 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] flex flex-col justify-between space-y-4">
                    <div>
                        <div class="pb-3.5 border-b border-ice-200/70">
                            <h2 class="font-display font-bold text-base text-navy-900">
                                Status & Aktivitas Sistem
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Log audit trail dan status telemetri keamanan multi-tenant.
                            </p>
                        </div>

                        <!-- 2 Status Card Kecil Inset Neumorphic -->
                        <div class="grid grid-cols-2 gap-3 my-3">
                            <div class="p-3.5 rounded-2xl bg-ice-100/60 border border-ice-200/70 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.15)]">
                                <span class="text-[10.5px] font-semibold text-navy-500 block uppercase tracking-wider">Isolasi Multi-Tenant</span>
                                <span class="text-xs font-bold text-navy-900 block mt-1">Aktif Normal</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-ice-100/60 border border-ice-200/70 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.15)]">
                                <span class="text-[10.5px] font-semibold text-navy-500 block uppercase tracking-wider">Integritas Audit Log</span>
                                <span class="text-xs font-bold text-navy-900 block mt-1">Terkunci</span>
                            </div>
                        </div>

                        <!-- Daftar Log Aktivitas -->
                        <div>
                            <p class="text-[11px] font-bold text-navy-500 mb-2 uppercase tracking-wider">Log Terakhir</p>
                            @if ($recentActivities->isEmpty())
                                <p class="text-xs text-navy-500 italic py-4">Belum ada aktivitas tercatat.</p>
                            @else
                                <div class="divide-y divide-ice-100/80 text-xs">
                                    @foreach ($recentActivities as $act)
                                        <div class="py-2.5 flex items-center justify-between gap-2">
                                            <div>
                                                <p class="font-mono text-navy-900 font-bold text-[11px]">{{ $act->action }}</p>
                                                <p class="text-navy-500 text-[10.5px] mt-0.5">
                                                    {{ $act->payload['organization_name'] ?? ($act->organization->name ?? 'Sistem') }}
                                                </p>
                                            </div>
                                            <span class="text-navy-400 tabular-nums text-[10.5px] shrink-0 font-mono">
                                                {{ $act->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-3 border-t border-ice-200/70">
                        <a
                            href="{{ route('admin.organizations.index') }}"
                            class="w-full flex items-center justify-center h-10 px-4 rounded-xl bg-ice-100 text-navy-900 font-semibold text-xs hover:bg-ice-200 transition-colors border border-ice-200/70 shadow-xs"
                        >
                            Lihat Log Lengkap
                        </a>
                    </div>
                </div>

            </div>

        </main>
    </div>

</body>
</html>
